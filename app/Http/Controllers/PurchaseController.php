<?php

namespace App\Http\Controllers;

use App\purchase;
use App\PurchaseDetail;
use App\Category;
use App\Supplier;
use App\Product;
use App\Unit;
use App\Store;
use App\Tax;
use App\Company;
use App\Cashbox;
use App\Account;
use App\Branch;
use PDF;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Helpers\ZatcaQrCode;
use Carbon\Carbon;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // جلب جميع الفواتير
        $purchases = Purchase::all(); 

        $cashboxes = Cashbox::all(); 
        $accounts = Account::all();
        $branchs = Branch::all();
        // إرجاع البيانات إلى الـ View
        return view('purchases.purchases', compact('purchases','cashboxes','accounts','branchs'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // الحصول على أكبر رقم فاتورة حالياً
        $lastPurchase = Purchase::orderBy('id', 'desc')->first(); 
        
        // إذا كانت هناك فواتير سابقة، نأخذ الرقم الأخير ونضيف 1 له
        $newPurchaseNumber = $lastPurchase ? 'PUR-' . str_pad(substr($lastPurchase->pur_number, 4) + 1, 6, '0', STR_PAD_LEFT) : 'PUR-000001';
    
        
        $suppliers = Supplier::all();
        $products = Product::all();
        $units = Unit::all();
        $stores = Store::all();
        $taxes = Tax::all();
        $e_categories = Category::all();

        // جلب الضريبة الأولى
        $tax = $taxes->first(); 
        $taxRate = $tax ? $tax->tax_rate : 0; // إذا كانت هناك ضريبة، نأخذ قيمتها، وإلا نستخدم القيمة 0
        
        $cashboxes = Cashbox::all(); 
        $accounts = Account::all();
        $branchs = Branch::all();
        // تمرير جميع البيانات إلى الـ View
        return view('purchases.create-purchases', compact('newPurchaseNumber','suppliers','products','units','stores','taxes','e_categories','taxRate','cashboxes','accounts','branchs'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   public function store(Request $request)
    {
        // جلب البيانات الأساسية
        $e_categories = Category::all();
        $products = Product::all();
        $units = Unit::all();
        $store = Store::find($request->store_id);
        $supplier = Supplier::find($request->supplier_id);

        if (!$supplier) {
            return back()->withErrors(['supplier_id' => 'المورد غير موجود']);
        }

        if (!$store) {
            return back()->withErrors(['store_id' => 'المتجر غير موجود']);
        }

        // التحقق من رصيد الخزنة قبل الحفظ
        $inv_payment = trim($request->inv_payment);
        $cash_options = ['cash', 'نقدي', 'نقــدي', 'شبكة', 'شــبكة'];

        if (in_array($inv_payment, $cash_options)) {
            if ($request->filled('cashbox_id')) {
                $cashbox = Cashbox::find($request->cashbox_id);
                if (!$cashbox) {
                    return back()->withErrors(['cashbox_id' => 'الخزنة غير موجودة']);
                }

                // تحقق من الرصيد الكافي
                if ($cashbox->cash_balance < $request->total_paid) {
                    return back()->withErrors(['cashbox_id' => 'الرصيد في الخزنة غير كافٍ لإتمام عملية الدفع.']);
                }
            }
        }

        // حفظ بيانات الفاتورة
        $data = $request->only([
            'pur_number', 'pur_date', 'supplier_id', 'store_id', 'inv_payment',
            'sub_total', 'discount_value', 'vat_value', 'total_deu',
            'total_paid', 'total_unpaid', 'total_deferred', 'notes',
            'branch_id', 'cashbox_id', 'account_id'
        ]);
        $data['user_id'] = Auth::id();

        $purchase = Purchase::create($data);

        // إنشاء تفاصيل الفاتورة
        $details_list = [];
        for ($i = 0; $i < count($request->categories); $i++) {
            $category = $e_categories->find($request->categories[$i]);
            $product = $products->find($request->products[$i]);
            $unit = $units->find($request->units[$i]);

            $details_list[] = [
                'purchase_id' => $purchase->id,
                'category_id' => $category ? $category->id : null,
                'product_id' => $product ? $product->id : null,
                'unit_id' => $unit ? $unit->id : null,
                'product_barcode' => $request->product_barcode[$i],
                'quantity' => $request->quantity[$i],
                'product_price' => $request->product_price[$i],
                'product_discount' => $request->product_discount[$i],
                'total_price' => $request->total_price[$i],
                'user_id' => auth()->id(),
            ];
        }
        $purchase->purchaseDetails()->createMany($details_list);

        // تحديث الرصيد بعد التأكد من كفاية الرصيد
        try {
            $account = $request->filled('account_id') ? Account::find($request->account_id) : null;
            $cashbox = $request->filled('cashbox_id') ? Cashbox::find($request->cashbox_id) : null;

            if (in_array($inv_payment, $cash_options)) {
                if ($cashbox) {
                    $cashbox->cash_balance -= $request->total_deu;
                    $cashbox->save();
                }

                if ($account) {
                    $account->balance -= $request->sub_total;
                    $account->save();
                }

            } elseif (in_array($inv_payment, ['credit', 'آجــل'])) {
                if ($account) {
                    $account->balance -= $request->total_deferred;
                    $account->save();
                }

                if ($supplier) {
                    $supplier->sup_balance += $request->total_deferred;
                    $supplier->save();
                }
            }

        } catch (\Exception $e) {
            Log::error('Error updating balances', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحديث الرصيد.']);
        }

        // التوجيه إلى قائمة الفواتير بعد النجاح
        return redirect()->route('purchases.index')->with('success', 'تم إنشاء الفاتورة بنجاح');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $purchase = Purchase::findOrFail($id);

        $tax = Tax::first();  // جلب أول ضريبة فقط
        $taxRate = $tax ? $tax->tax_rate : 0;  // إذا كانت هناك ضريبة، نأخذ قيمتها، وإلا نستخدم القيمة 0
        
        return view('purchases.pur_show', compact('purchase', 'tax', 'taxRate'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $purchase = Purchase::findOrFail($id);  // جلب الفاتورة من قاعدة البيانات
        
        $suppliers = Supplier::all();  // جلب جميع الموردين
        $e_categories = Category::all();  // جلب جميع الفئات
        $products = Product::all();  // جلب جميع المنتجات
        $units = Unit::all();  // جلب جميع الوحدات
        $stores = Store::all();  // جلب جميع المخازن
        $taxes = Tax::all();
        
        // جلب الضريبة الأولى
        $tax = $taxes->first(); 
        $taxRate = $tax ? $tax->tax_rate : 0; // إذا كانت هناك ضريبة، نأخذ قيمتها، وإلا نستخدم القيمة 0
        
        // تحديد رقم الفاتورة
        $newPurchaseNumber = $purchase->pur_number;

        $cashboxes = Cashbox::all(); 
        $accounts = Account::all();
        $branchs = Branch::all();
        // تمرير جميع المتغيرات إلى الـ view بما في ذلك المتغير suppliers
        return view('purchases.pur_edit', compact('purchase', 'newPurchaseNumber', 'suppliers', 'stores', 'e_categories', 'products', 'units', 'taxes', 'taxRate', 'cashboxes', 'accounts','branchs'));
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\purchase  $purchase
     * @return \Illuminate\Http\Response
     */
  public function update(Request $request, $id)
    {
        $purchase = Purchase::whereId($id)->first();

        $e_categories = Category::all();
        $products = Product::all();
        $units = Unit::all();

        $store = Store::find($request->store_id);
        $supplier = Supplier::find($request->supplier_id);
        $cashbox = $request->filled('cashbox_id') ? Cashbox::find($request->cashbox_id) : null;
        $account = $request->filled('account_id') ? Account::find($request->account_id) : null;

        if (!$supplier) {
            return back()->withErrors(['supplier_id' => 'المورد غير موجود']);
        }

        if (!$store) {
            return back()->withErrors(['store_id' => 'المتجر غير موجود']);
        }

        // التحقق من الرصيد قبل التحديث (للدفع النقدي فقط)
        $inv_payment = trim($request->inv_payment);
        $cash_options = ['cash', 'نقدي', 'نقــدي', 'شبكة', 'شــبكة'];

        if (in_array($inv_payment, $cash_options)) {
            if ($cashbox && $cashbox->cash_balance < $request->total_paid) {
                return back()->withErrors(['cashbox_id' => 'الرصيد في الخزنة غير كافٍ لإتمام عملية الدفع.']);
            }
        }

        // تحديث بيانات الفاتورة
        $data = $request->only([
            'pur_number', 'pur_date', 'supplier_id', 'store_id', 'inv_payment',
            'sub_total', 'discount_value', 'vat_value', 'total_deu',
            'total_paid', 'total_unpaid', 'total_deferred', 'notes',
            'branch_id', 'cashbox_id', 'account_id'
        ]);
        $data['user_id'] = Auth::id();

        $purchase->update($data);

        // حذف تفاصيل الفاتورة القديمة
        $purchase->purchaseDetails()->delete();

        // إعادة إنشاء تفاصيل الفاتورة
        $details_list = [];
        for ($i = 0; $i < count($request->categories); $i++) {
            $category = $e_categories->find($request->categories[$i]);
            $product = $products->find($request->products[$i]);
            $unit = $units->find($request->units[$i]);

            $details_list[] = [
                'purchase_id' => $purchase->id,
                'category_id' => $category ? $category->id : null,
                'product_id' => $product ? $product->id : null,
                'unit_id' => $unit ? $unit->id : null,
                'product_barcode' => $request->product_barcode[$i],
                'quantity' => $request->quantity[$i],
                'product_price' => $request->product_price[$i],
                'product_discount' => $request->product_discount[$i],
                'total_price' => $request->total_price[$i],
                'user_id' => auth()->id(),
            ];
        }
        $purchase->purchaseDetails()->createMany($details_list);

        // تحديث الأرصدة بعد التحقق
        try {
            if (in_array($inv_payment, $cash_options)) {
                if ($cashbox) {
                    $cashbox->cash_balance -= $request->total_paid;
                    $cashbox->save();
                }

                if ($account) {
                    $account->balance -= $request->total_paid;
                    $account->save();
                }

            } elseif (in_array($inv_payment, ['credit', 'آجــل'])) {
                if ($account) {
                    $account->balance -= $request->total_deu;
                    $account->save();
                }

                if ($supplier) {
                    $supplier->sup_balance += $request->total_deu;
                    $supplier->save();
                }
            }

        } catch (\Exception $e) {
            Log::error('Error updating balances during update', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحديث الرصيد.']);
        }

        return redirect()->route('purchases.index')->with('Update', 'تم تعديل الفاتورة بنجاح');
    }



    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $purchase = Purchase::findOrFail($id);
        $purchase->delete();
    
        return redirect()->route('purchases.index')->with('Delete', 'تم حذف الفاتورة بنجاح');
    }

    public function print($id)
    {
        // جلب الفاتورة
        $purchase = Purchase::findOrFail($id);
        
        // جلب بيانات الشركة
        $company = Company::first(); 
        
        // جلب أول ضريبة فقط
        $tax = Tax::first(); 
        $taxRate = $tax ? $tax->tax_rate : 0; // إذا كانت هناك ضريبة، نأخذ قيمتها، وإلا نستخدم القيمة 0

        // تجهيز بيانات QR حسب متطلبات ZATCA
        $companyName = $company->company_name;
        $taxNumber = $company->tax_number;
        $invoiceDate = Carbon::parse($purchase->pur_date)->format('Y-m-d\TH:i:s\Z');
        $totalVat = number_format($purchase->vat_value, 2, '.', '');
        $totalDue = number_format($purchase->total_deu, 2, '.', '');
    
        // ⚠️ استخدام الكلاس المساعد ZatcaQrCode
        $base64String = ZatcaQrCode::getBase64TLV(
            $companyName,
            $taxNumber,
            $invoiceDate,
            $totalDue,
            $totalVat
        );
    
        // ✅ إنشاء QR من النص المشفر (base64)
        $qrCode = new QrCode($base64String);
        $qrCode->setSize(200);
        $qrCode->setMargin(10);
        $qrCode->setEncoding('UTF-8');
    
        $qrImage = $qrCode->writeString(); // يرجع raw binary
        $qrImage = base64_encode($qrImage); // نحوله إلى base64 عشان نعرضه في HTML
    
        // تمرير البيانات إلى الـ View
        return view('purchases.print', compact('purchase', 'tax', 'taxRate', 'company', 'qrImage'));
    }



    public function getproducts($id)
    {
        $products = DB::table("products")->where("category_id", $id)->pluck("product_name", "id");
        return response()->json($products);
    }


    public function getProductDetails($id)
    {
        // جلب تفاصيل المنتج من قاعدة البيانات
        $product = Product::find($id);
        
        // التحقق إذا كان المنتج موجودًا
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // جلب الوحدة المتوافقة مع المنتج
        $unit = $product->unit; // هذا يعتمد على العلاقة بين المنتج والوحدة

        return response()->json([
            'product' => [
                'barcode' => $product->product_barcode,
                'unit' => $unit ? $unit->unit_name : 'غير محدد', // تأكد من إرجاع اسم الوحدة
                'unit_id' => $unit ? $unit->id : null, // إرجاع ID الوحدة
                'price' => $product->product_sale_price,
            ]
        ]);
    }

}

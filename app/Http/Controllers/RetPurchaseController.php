<?php

namespace App\Http\Controllers;

use App\RetPurchase;
use App\Purchase;
use App\DetailsReturnPurchase;
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


class RetPurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // جلب جميع المرتجعات
        $ret_purchase = RetPurchase::all(); 
        $purchases = Purchase::all(); 

        // التحقق مما إذا كانت الفاتورة قد تم إرجاعها
        foreach ($ret_purchase as $ret) {
            $purchase = Purchase::find($ret->purchase_id);
            $ret->isReturned = RetPurchase::where('purchase_id', $purchase->id)->exists();  // تحقق إذا كانت الفاتورة قد تم إرجاعها مسبقًا
        }

        // إرجاع البيانات إلى الـ View
        return view('ret_purchases.ret_purchases', compact('ret_purchase', 'purchases'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // الحصول على أكبر رقم فاتورة حالياً
        $lastRetPurchase = RetPurchase::orderBy('id', 'desc')->first(); 
        
        // إذا كانت هناك فواتير سابقة، نأخذ الرقم الأخير ونضيف 1 له
        $newRetPurchaseNumber = $lastRetPurchase ? 'RTP-' . str_pad(substr($lastRetPurchase->ret_pur_number, 4) + 1, 6, '0', STR_PAD_LEFT) : 'RTP-000001';
    
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
        
        $purchases = Purchase::all()->map(function ($purchase) {
            $purchase->is_returned = RetPurchase::where('purchase_id', $purchase->id)->exists();
            return $purchase;
        });
        
        
        // تمرير جميع البيانات إلى الـ View
        return view('ret_purchases.create_ret_purchases', compact('newRetPurchaseNumber','suppliers','products','units','stores','taxes','e_categories','taxRate','cashboxes','accounts','branchs','purchases'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // تحقق من وجود purchase_id في جدول المرتجع
        $existingRetPurchase = RetPurchase::where('purchase_id', $request->purchase_id)->first();

        if ($existingRetPurchase) {
            // إذا كانت الفاتورة قد تم إرجاعها مسبقًا، عرض رسالة خطأ
            return redirect()->back()->with('error', 'هذه الفاتورة تم إرجاعها مسبقًا.');
        }

        // جلب المجموعات والمنتجات والوحدات
        $e_categories = Category::all();
        $products = Product::all();
        $units = Unit::all();
        $store = Store::find($request->store_id);

        // جلب المورد باستخدام الـ ID
        $supplier = Supplier::find($request->supplier_id);

        // التحقق من وجود المورد والمتجر
        if (!$supplier) {
            return back()->withErrors(['supplier_id' => 'المورد غير موجود']);
        }

        if (!$store) {
            return back()->withErrors(['store_id' => 'المتجر غير موجود']);
        }

        // التحقق من وجود purchase_id
        if (empty($request->purchase_id)) {
            return back()->withErrors(['purchase_id' => 'رقم المرتجع مطلوب']);
        }

        // حفظ بيانات الفاتورة الأساسية مع إضافة user_id
        $data = $request->only([
            'ret_pur_number',
            'ret_pur_date',
            'supplier_id',
            'store_id',
            'ret_pur_payment',
            'return_reason',
            'notes',
            'sub_total',
            'discount_value',
            'vat_value',
            'total_deu',
            'total_paid',
            'total_unpaid',
            'branch_id',
            'purchase_id',
            'cashbox_id',
            'account_id',
            
        ]);

        $data['user_id'] = auth()->id();

        // حفظ الفاتورة
        $ret_purchase = RetPurchase::create($data);

        // حفظ تفاصيل الفاتورة
        $details_list = [];
        for ($i = 0; $i < count($request->categories); $i++) {
            $category = $e_categories->find($request->categories[$i]);
            $product = $products->find($request->products[$i]);
            $unit = $units->find($request->units[$i]);

            $details_list[$i] = [
                'ret_purchase_id' => $ret_purchase->id,
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

        // إنشاء تفاصيل الفاتورة دفعة واحدة
        $ret_purchase->detailsReturnPurchases()->createMany($details_list);

        return redirect()->route('ret_purchases.index', $ret_purchase->id);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\RetPurchase  $retPurchase
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ret_purchase = RetPurchase::findOrFail($id);

        $tax = Tax::first();  // جلب أول ضريبة فقط
        $taxRate = $tax ? $tax->tax_rate : 0; 

        return view('ret_purchases.ret_purchases_show', compact('ret_purchase', 'tax', 'taxRate'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\RetPurchase  $retPurchase
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ret_purchase = RetPurchase::findOrFail($id);  // جلب الفاتورة من قاعدة البيانات
        
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
        $newRetPurchaseNumber = $ret_purchase->ret_pur_number;

        // تمرير جميع المتغيرات إلى الـ view بما في ذلك المتغير suppliers
        return view('ret_purchases.ret_purchases_edit', compact('ret_purchase', 'newRetPurchaseNumber', 'suppliers', 'stores', 'e_categories', 'products', 'units', 'taxes', 'taxRate'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RetPurchase  $retPurchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request , $id)
    {
        $ret_purchase = RetPurchase::whereId($id)->first();
    
        // جلب المجموعات والمنتجات والوحدات
        $e_categories = Category::all();
        $products = Product::all();
        $units = Unit::all();
    
        $store = Store::find($request->store_id);
    
        // جلب المورد باستخدام الـ ID
        $supplier = Supplier::find($request->supplier_id);
        
        // التحقق من وجود المورد والمتجر
        if (!$supplier) {
            return back()->withErrors(['supplier_id' => 'المورد غير موجود']);
        }
    
        if (!$store) {
            return back()->withErrors(['store_id' => 'المتجر غير موجود']);
        }
    
        // حفظ بيانات الفاتورة الأساسية مع إضافة user_id
        $data['ret_pur_number'] = $request->ret_pur_number;  // تأكد من أن الاسم هنا يتطابق مع الحقل في النموذج
        $data['ret_pur_date'] = $request->ret_pur_date;
        $data['supplier_id'] = $request->supplier_id;
        $data['store_id'] = $request->store_id;
        $data['ret_pur_payment'] = $request->ret_pur_payment;
        $data['return_reason'] = $request->return_reason;
        $data['notes'] = $request->notes;
        $data['sub_total'] = $request->sub_total;
        $data['discount_value'] = $request->discount_value;
        $data['vat_value'] = $request->vat_value;
        $data['total_deu'] = $request->total_deu;
        $data['total_paid'] = $request->total_paid;
        $data['total_unpaid'] = $request->total_unpaid;
        // إضافة حقل branch_id
        $data['branch_id'] = $request->branch_id;  // تأكد من إضافة هذا الحقل
    
        $data['purchase_id'] = $request->purchase_id;
        $data['cashbox_id'] = $request->cashbox_id;
        $data['account_id'] = $request->account_id;
        
        // إضافة أو تحديث user_id
        $data['user_id'] = auth()->id();
    
        // تعديل الفاتورة
        $ret_purchase->update($data);
    
        $ret_purchase->detailsReturnPurchases()->delete();
        
        // حفظ تفاصيل الفاتورة
        $details_list = [];
        for ($i = 0; $i < count($request->categories); $i++) {
        
            $category = $e_categories->find($request->categories[$i]);
            $product = $products->find($request->products[$i]);
            $unit = $units->find($request->units[$i]);
    
            // تخزين الـ IDs بدلاً من الأسماء
            $details_list[$i] = [
                'ret_purchase_id' => $ret_purchase->id,  // ربط تفاصيل الفاتورة مع الفاتورة الرئيسية
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
    
        // إنشاء تفاصيل الفاتورة دفعة واحدة
        $ret_purchase->detailsReturnPurchases()->createMany($details_list);
    
        return redirect()->route('ret_purchases.index')->with('Update', 'تم تعديل مرتجع مشتريات بنجاح');
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\RetPurchase  $retPurchase
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ret_purchase = RetPurchase::findOrFail($id);
        $ret_purchase->delete();
    
        return redirect()->route('ret_purchases.index')->with('Delete', 'تم حذف المرتجع بنجاح');
    }

    public function print($id)
    {
        // جلب الفاتورة
        $ret_purchase = RetPurchase::findOrFail($id);
        
        // جلب بيانات الشركة
        $company = Company::first(); 
        $purchases = Purchase::all(); 
        // جلب أول ضريبة فقط
        $tax = Tax::first(); 
        $taxRate = $tax ? $tax->tax_rate : 0; // إذا كانت هناك ضريبة، نأخذ قيمتها، وإلا نستخدم القيمة 0

        // تجهيز بيانات QR حسب متطلبات ZATCA
        $companyName = $company->company_name;
        $taxNumber = $company->tax_number;
        $invoiceDate = Carbon::parse($ret_purchase->ret_pur_date)->format('Y-m-d\TH:i:s\Z');
        $totalVat = number_format($ret_purchase->vat_value, 2, '.', '');
        $totalDue = number_format($ret_purchase->total_deu, 2, '.', '');
    
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

       
        return view('ret_purchases.print', compact('ret_purchase', 'company', 'path', 'tax', 'taxRate','qrImage','purchases'));
    }
}

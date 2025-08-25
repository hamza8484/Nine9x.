<?php

namespace App\Http\Controllers;

use App\Quotation;
use App\DetailsQuotation;
use App\Category;
use App\Customer;
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
use App\Exports\InvoiceExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Helpers\ZatcaQrCode;
use Carbon\Carbon;


class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quotations = Quotation::all();
        return view('quotations.quotations', compact('quotations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // الحصول على أكبر رقم فاتورة حالياً
        $lastQuotation = Quotation::orderBy('id', 'desc')->first();
        
        // إذا كانت هناك فواتير سابقة، نأخذ الرقم الأخير ونضيف 1 له
        $newQuotationNumber = $lastQuotation ? 'QUT-' . str_pad(substr($lastQuotation->qut_number, 4) + 1, 6, '0', STR_PAD_LEFT) : 'QUT-000001';
    
        $customers = Customer::all(); 
        $products = Product::all();
        $units = Unit::all();
        $taxes = Tax::all();
        $e_categories = Category::all();
        $stores = Store::all();  // جلب الفروع من الـ Store

        // جلب الضريبة الأولى
        $tax = $taxes->first(); 
        $taxRate = $tax ? $tax->tax_rate : 0; // إذا كانت هناك ضريبة، نأخذ قيمتها، وإلا نستخدم القيمة 0

        // تمرير جميع البيانات إلى الـ View
        return view('quotations.create-quotations', compact('newQuotationNumber', 'customers', 'products', 'units', 'taxes', 'taxRate', 'e_categories', 'stores'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // التحقق من صحة البيانات المدخلة
        $request->validate([
            'qut_number' => 'required|string|max:255',
            'qut_date' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'store_id' => 'required|exists:stores,id',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'products' => 'required|array',
            'products.*' => 'exists:products,id',
            'units' => 'required|array',
            'units.*' => 'exists:units,id',
            'quantity' => 'required|array',
            'quantity.*' => 'numeric|min:0',
            'product_price' => 'required|array',
            'product_price.*' => 'numeric|min:0',
            'product_discount' => 'nullable|array',
            'product_discount.*' => 'numeric|min:0',
            'total_price' => 'required|array',
            'total_price.*' => 'numeric|min:0',
        ]);

        // جلب المجموعات والمنتجات والوحدات
        $e_categories = Category::all();
        $products = Product::all();
        $units = Unit::all();
        $stores = Store::all();  // إضافة استعلام الفروع

        // جلب العميل والمتجر باستخدام الـ ID
        $customer = Customer::find($request->customer_id);
        $store = Store::find($request->store_id);

        // التحقق من وجود العميل والمتجر
        if (!$customer) {
            return back()->withErrors(['customer_id' => 'العميل غير موجود']);
        }

        if (!$store) {
            return back()->withErrors(['store_id' => 'المتجر غير موجود']);
        }

        // حفظ بيانات الفاتورة الأساسية
        $data['qut_number'] = $request->qut_number;
        $data['qut_date'] = $request->qut_date;
        $data['qut_status'] = $request->qut_status;
        $data['customer_id'] = $request->customer_id;
        $data['store_id'] = $request->store_id;
        $data['sub_total'] = $request->sub_total;
        $data['discount_value'] = $request->discount_value;
        $data['vat_value'] = $request->vat_value;
        $data['total_deu'] = $request->total_deu;
        $data['qut_notes'] = $request->qut_notes;
        $data['customer_name'] = $customer->cus_name;  
        $data['store_name'] = $store->store_name;  
        $data['user_id'] =auth()->id();
        
        // حفظ الفاتورة
        $quotation = Quotation::create($data);

        // حفظ تفاصيل الفاتورة
        $details_list = [];
        for ($i = 0; $i < count($request->categories); $i++) {
            // جلب البيانات باستخدام الـ ID للـ Category والـ Product والـ Unit
            $category = $e_categories->find($request->categories[$i]);
            $product = $products->find($request->products[$i]);
            $unit = $units->find($request->units[$i]);

            // تخزين الـ IDs بدلاً من الأسماء
            if ($category && $product && $unit) {
                $details_list[$i] = [
                    'quotation_id' => $quotation->id,  // ربط كل تفاصيل بالفاتورة
                    'category_id' => $category->id,
                    'product_id' => $product->id,
                    'unit_id' => $unit->id,
                    'product_barcode' => $request->product_barcode[$i],
                    'quantity' => $request->quantity[$i],
                    'product_price' => $request->product_price[$i],
                    'product_discount' => $request->product_discount[$i],
                    'total_price' => $request->total_price[$i],
                    'user_id' => auth()->id(),
                ];
            } else {
                return back()->withErrors(['error' => 'هناك خطأ في البيانات المدخلة']);
            }
        }

        // إنشاء تفاصيل الفاتورة دفعة واحدة
        $quotation->detailsQuotations()->createMany($details_list);

        // إرجاع المستخدم إلى صفحة الفواتير مع رسالة نجاح
        return redirect()->route('quotations.index')->with('Add', 'تم حفظ الفاتورة بنجاح');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $quotation = Quotation::findOrFail($id);

        $tax = Tax::first();  // جلب أول ضريبة فقط
        $taxRate = $tax ? $tax->tax_rate : 0;  // إذا كانت هناك ضريبة، نأخذ قيمتها، وإلا نستخدم القيمة 0
        
        return view('quotations.qut_show', compact('quotation', 'tax', 'taxRate'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $quotation = Quotation::findOrFail($id); // استرجاع الفاتورة بناءً على المعرف
        
        $customers = Customer::all(); // استرجاع جميع العملاء من قاعدة البيانات
        $stores = Store::all();
        $e_categories = Category::all();
        $products = Product::all();
        $units = Unit::all();
        $taxes = Tax::all();
         // جلب الضريبة الأولى
         $tax = $taxes->first(); 
         $taxRate = $tax ? $tax->tax_rate : 0; // إذا كانت هناك ضريبة، نأخذ قيمتها، وإلا نستخدم القيمة 0
        // تحديد رقم الفاتورة
        $newQuotationNumber = $quotation->qut_number;

        return view('quotations.qut_edit', compact('quotation', 'newQuotationNumber','customers','stores','e_categories','products','units','taxes','taxRate')); // تمرير المتغير إلى الـ View
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request , $id)
    {
        $quotation = Quotation::whereId($id)->first();


        // جلب المجموعات والمنتجات والوحدات
        $e_categories = Category::all();
        $products = Product::all();
        $units = Unit::all();

        // جلب العميل والمتجر باستخدام الـ ID
        $customer = Customer::find($request->customer_id);
        $store = Store::find($request->store_id);

        // التحقق من وجود العميل والمتجر
        if (!$customer) {
            return back()->withErrors(['customer_id' => 'العميل غير موجود']);
        }

        if (!$store) {
            return back()->withErrors(['store_id' => 'المتجر غير موجود']);
        }

        // حفظ بيانات الفاتورة الأساسية
        $data['qut_number'] = $request->qut_number;
        $data['qut_date'] = $request->qut_date;
        $data['qut_status'] = $request->qut_status;
        $data['customer_id'] = $request->customer_id;
        $data['store_id'] = $request->store_id;
        $data['sub_total'] = $request->sub_total;
        $data['discount_value'] = $request->discount_value;
        $data['vat_value'] = $request->vat_value;
        $data['total_deu'] = $request->total_deu;
        $data['qut_notes'] = $request->qut_notes;
        $data['customer_name'] = $customer->cus_name;  // اسم العميل
        $data['store_name'] = $store->store_name;  // اسم المتجر
        $data['user_id'] =auth()->id();
        
        // تعديل العرض
        $quotation->update($data);

        $quotation->detailsQuotations()->delete();
    
        // حفظ تفاصيل الفاتورة
        $details_list = [];
        for ($i = 0; $i < count($request->categories); $i++) {
        
            $category = $e_categories->find($request->categories[$i]);
            $product = $products->find($request->products[$i]);
            $unit = $units->find($request->units[$i]);

            // تخزين الـ IDs بدلاً من الأسماء
            $details_list[$i] = [
                'quotation_id' => $quotation->id,   // ربط كل تفاصيل بالفاتورة
                'category_id' => $category ? $category->id : null, // تخزين ID بدلاً من الاسم
                'product_id' => $product ? $product->id : null,   // تخزين ID بدلاً من الاسم
                'unit_id' => $unit ? $unit->id : null,  // تخزين ID بدلاً من الاسم
                'product_barcode' => $request->product_barcode[$i],
                'quantity' => $request->quantity[$i],
                'product_price' => $request->product_price[$i],
                'product_discount' => $request->product_discount[$i],
                'total_price' => $request->total_price[$i],
                'user_id' => auth()->id(),
            ];
        }

        // إنشاء تفاصيل الفاتورة دفعة واحدة
        $quotation->detailsQuotations()->createMany($details_list);


        return redirect()->route('quotations.index')->with('Update', 'تم تعديل عرض السعر بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Quotation $quotation)
    {
        //
    }

    public function print($id)
    {
        $quotation = Quotation::with([
            'detailsQuotations.category',
            'detailsQuotations.product',
            'detailsQuotations.unit',
            'customer'
        ])->findOrFail($id);
        

        $company = Company::first(); 
        $tax = Tax::first(); 
        $taxRate = $tax ? $tax->tax_rate : 0;

        // تجهيز بيانات QR حسب متطلبات ZATCA
        $companyName = $company->company_name;
        $taxNumber = $company->tax_number;
        $quotationDate = Carbon::parse($quotation->qut_date)->format('Y-m-d\TH:i:s\Z');
        $totalVat = number_format($quotation->vat_value, 2, '.', '');
        $totalDue = number_format($quotation->total_deu, 2, '.', '');

        $base64String = ZatcaQrCode::getBase64TLV(
            $companyName,
            $taxNumber,
            $quotationDate,
            $totalDue,
            $totalVat
        );

        $qrCode = new QrCode($base64String);
        $qrCode->setSize(200);
        $qrCode->setMargin(10);
        $qrCode->setEncoding('UTF-8');
        $qrImage = base64_encode($qrCode->writeString());

        $products = Product::all();
        $cashboxes = Cashbox::all(); 
        $accounts = Account::all();
        $branchs = Branch::all();

        return view('quotations.print', compact(
            'quotation', 'tax', 'taxRate', 'company',
            'qrImage', 'cashboxes', 'accounts', 'branchs'
        ));
        
    }



    public function getproducts($id)
    {
        $products = DB::table("products")->where("category_id", $id)->pluck("product_name", "id");
        return json_encode($products);
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

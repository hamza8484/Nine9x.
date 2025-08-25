<?php

namespace App\Http\Controllers;

use App\ReturnInvoice;
use App\DetailsReturnInvoice;
use App\Invoice;
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

class ReturnInvoiceController extends Controller
{
    // دالة مشتركة لجلب البيانات المشتركة بين دالتي create و edit
    private function getCommonData()
    {
        $customers = Customer::all(); 
        $products = Product::all();
        $units = Unit::all();
        $stores = Store::all();
        $taxes = Tax::all();
        $e_categories = Category::all();

        // جلب الضريبة الأولى
        $tax = $taxes->first(); 
        $taxRate = $tax ? $tax->tax_rate : 0;

        $cashboxes = Cashbox::all(); 
        $accounts = Account::all();
        $branchs = Branch::all();

        return compact(
            'e_categories', 
            'customers', 
            'products', 
            'units', 
            'stores', 
            'taxes',
            'taxRate',
            'cashboxes',
            'accounts',
            'branchs'
        );
    }

    public function index()
    {
        // جلب جميع الفواتير المرتجعة
        $returnInvoices = ReturnInvoice::with('detailsReturnInvoices')->get();
        $invoices = Invoice::all(); 

        // التحقق مما إذا كانت الفاتورة قد تم إرجاعها
        foreach ($returnInvoices as $ret) {
            $invoices = Invoice::find($ret->invoice_id);
            $ret->isReturned = ReturnInvoice::where('invoice_id', $ret->invoice_id)->exists();  
        }

        $cashboxes = Cashbox::all(); 
        $accounts = Account::all();
        $branchs = Branch::all();
        // تمرير البيانات إلى الـ view
        return view('ret_invoices.ret_invoices', compact('returnInvoices', 'cashboxes', 'accounts', 'branchs'));
    }

    public function create(Request $request) // إضافة Request كوسيلة للحصول على البيانات المرسلة
    {
        // الحصول على أكبر رقم فاتورة حالياً
        $lastRInvoice = ReturnInvoice::orderBy('id', 'desc')->first(); 
    
        // إذا كانت هناك فواتير سابقة، نأخذ الرقم الأخير ونضيف 1 له
        $newRetInvoiceNumber = $lastRInvoice ? 'RET-' . str_pad(substr($lastRInvoice->ret_inv_number, 4) + 1, 6, '0', STR_PAD_LEFT) : 'RET-000001';
    
        // جلب البيانات المشتركة بين `create` و `edit`
        $data = $this->getCommonData();
    
        // جلب جميع الفواتير
        $invoices = Invoice::all(); 
    
        // تحديد الـ invoice_id الذي سيتم اختياره بشكل افتراضي
        $selectedInvoiceId = old('invoice_id', $request->input('invoice_id'));
    
        // تمرير البيانات إلى الـ view بشكل صحيح
        return view('ret_invoices.create_ret_invoices', array_merge(
            [
                'newRetInvoiceNumber' => $newRetInvoiceNumber,
                'invoices' => $invoices,
                'selectedInvoiceId' => $selectedInvoiceId
            ],
            $data // إضافة البيانات المشتركة التي تم جلبها من الدالة getCommonData
        ));
    }
    


    public function store(Request $request)
    {
        // تحقق من وجود invoice_id في جدول المرتجع
        $lastRInvoice = ReturnInvoice::where('invoice_id', $request->invoice_id)->first();

        if ($lastRInvoice) {
            // إذا كانت الفاتورة قد تم إرجاعها مسبقًا، عرض رسالة خطأ
            return redirect()->back()->with('error', 'هذه الفاتورة تم إرجاعها مسبقًا.');
        }

        // التحقق من صحة البيانات الواردة
        $request->validate([
            'categories' => 'required|array',
            'products' => 'required|array',
            'units' => 'required|array',
            'product_barcode' => 'required|array',
            'quantity' => 'required|array',
            'product_price' => 'required|array',
            'product_discount' => 'required|array',
            'total_price' => 'required|array',
            'invoice_id' => 'required|exists:invoices,id',
            'store_id' => 'required|exists:stores,id',
            'customer_id' => 'required|exists:customers,id',
        ]);

        // جلب البيانات المشتركة بين `create` و `edit`
        $data = $this->getCommonData();

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
        $data['ret_inv_number'] = $request->ret_inv_number;
        $data['ret_inv_date'] = $request->ret_inv_date;
        $data['customer_name'] = $customer->cus_name;  
        $data['store_name'] = $request->store_name;  
        $data['sub_total'] = $request->sub_total;
        $data['discount_value'] = $request->discount_value;
        $data['vat_value'] = $request->vat_value;
        $data['total_deu'] = $request->total_deu;
        $data['total_paid'] = $request->total_paid;
        $data['total_unpaid'] = $request->total_unpaid;
        $data['total_deferred'] = $request->total_deferred;
        $data['invoice_id'] = $request->invoice_id;
        $data['branch_id'] = $store->branch_id; 
        $data['cashbox_id'] = $request->cashbox_id; 
        $data['account_id'] = $request->account_id; 
        $data['store_id'] = $request->store_id; 
        $data['customer_id'] = $request->customer_id; 
        $data['user_id'] =auth()->id();
        
        // حفظ الفاتورة
        $lastRInvoice = ReturnInvoice::create($data);

        // حفظ تفاصيل الفاتورة
        $details_list = [];
        for ($i = 0; $i < count($request->categories); $i++) {
            $category = $data['e_categories']->find($request->categories[$i]);
            $product = $data['products']->find($request->products[$i]);
            $unit = $data['units']->find($request->units[$i]);

            // التحقق من وجود الـ category, product, و unit
            if (!$category || !$product || !$unit) {
                return back()->withErrors(['msg' => 'فشل في ربط البيانات مع الفاتورة']);
            }

            $details_list[$i] = [
                'return_invoice_id' => $lastRInvoice->id,
                'category_id' => $category->id,
                'product_id' => $product->id,
                'unit_id' => $unit->id,
                'product_barcode' => $request->product_barcode[$i],
                'quantity' => $request->quantity[$i],
                'product_price' => $request->product_price[$i],
                'product_discount' => $request->product_discount[$i],
                'total_price' => $request->total_price[$i],
            ];
        }

        // إنشاء تفاصيل الفاتورة دفعة واحدة
        $lastRInvoice->detailsReturnInvoices()->createMany($details_list);

        return redirect()->route('ret_invoices.index')->with('Add', 'تم حفظ مرتجع المبيعات بنجاح');
    }

    public function edit($id)
    {
        // استرجاع الفاتورة بناءً على المعرف
        $returnInvoices = ReturnInvoice::find($id);

        // جلب البيانات المشتركة بين `create` و `edit`
        $data = $this->getCommonData();

        $customers = Customer::all(); 
        $products = Product::all();
        $units = Unit::all();
        $stores = Store::all();
        $taxes = Tax::all();
        $e_categories = Category::all();

        // جلب الضريبة الأولى
        $tax = $taxes->first(); 
        $taxRate = $tax ? $tax->tax_rate : 0;

        // الحصول على أكبر رقم فاتورة حالياً
        $lastRInvoice = ReturnInvoice::orderBy('id', 'desc')->first(); 
        $newRetInvoiceNumber = $lastRInvoice ? 'RET-' . str_pad(substr($lastRInvoice->ret_inv_number, 4) + 1, 6, '0', STR_PAD_LEFT) : 'RET-000001';

        $cashboxes = Cashbox::all(); 
        $accounts = Account::all();
        $branchs = Branch::all();
        
        $invoices = Invoice::all();

        // تمرير المتغيرات إلى الـ View
        return view('ret_invoices.ret_edit', compact(
            'returnInvoices', 
            'newRetInvoiceNumber',
            'customers',
            'products',
            'units',
            'stores',
            'taxRate',
            'taxes',
            'e_categories',
            'cashboxes',
            'accounts',
            'branchs',
            'invoices',
            ...array_keys($data)
        ));
    }

    public function show($id)
    {
        $returnInvoices = ReturnInvoice::findOrFail($id);
        
        $tax = Tax::first();  // جلب أول ضريبة فقط
        $taxRate = $tax ? $tax->tax_rate : 0;  // إذا كانت هناك ضريبة، نأخذ قيمتها، وإلا نستخدم القيمة 0
        
        return view('ret_invoices.ret_show', compact('returnInvoices', 'tax', 'taxRate'));
    }

    public function update(Request $request , $id)
    {
        $returnInvoices = ReturnInvoice::findOrFail($id);

        // جلب البيانات المشتركة بين `create` و `edit`
        $data = $this->getCommonData();

        // حفظ بيانات الفاتورة الأساسية
        $data['ret_inv_number'] = $request->ret_inv_number;
        $data['ret_inv_date'] = $request->ret_inv_date;
        $data['customer_name'] = $request->customer_name;
        $data['store_name'] = $request->store_name;
        $data['sub_total'] = $request->sub_total;
        $data['discount_value'] = $request->discount_value;
        $data['vat_value'] = $request->vat_value;
        $data['total_deu'] = $request->total_deu;
        $data['total_paid'] = $request->total_paid;
        $data['total_unpaid'] = $request->total_unpaid;
        $data['total_deferred'] = $request->total_deferred;
        $data['invoice_id'] = $request->invoice_id;
        $data['branch_id'] = $request->branch_id; 
        $data['cashbox_id'] = $request->cashbox_id; 
        $data['account_id'] = $request->account_id; 
        $data['store_id'] = $request->store_id; 
        $data['customer_id'] = $request->customer_id; 
        $data['user_id'] = auth()->id();
        
        // تعديل الفاتورة
        $returnInvoices->update($data);

        // حذف التفاصيل الحالية وإضافة تفاصيل جديدة
        $returnInvoices->detailsReturnInvoices()->delete();
        
        // حفظ تفاصيل الفاتورة
        $details_list = [];
        for ($i = 0; $i < count($request->categories); $i++) {
            $category = $data['e_categories']->find($request->categories[$i]);
            $product = $data['products']->find($request->products[$i]);
            $unit = $data['units']->find($request->units[$i]);

            $details_list[$i] = [
                'return_invoice_id' => $returnInvoices->id,  
                'category_id' => $category ? $category->id : null, 
                'product_id' => $product ? $product->id : null,   
                'unit_id' => $unit ? $unit->id : null,  
                'product_barcode' => $request->product_barcode[$i],
                'quantity' => $request->quantity[$i],
                'product_price' => $request->product_price[$i],
                'product_discount' => $request->product_discount[$i],
                'total_price' => $request->total_price[$i],
            ];
        }

        // إنشاء تفاصيل الفاتورة دفعة واحدة
        $returnInvoices->detailsReturnInvoices()->createMany($details_list);

        return redirect()->route('ret_invoices.index')->with('Update', 'تم تعديل مرتجع المبيعات بنجاح');
    }

    public function destroy($id)
    {
        $returnInvoices = ReturnInvoice::findOrFail($id);
        $returnInvoices->delete();

        return redirect()->route('ret_invoices.index')->with('Delete', 'تم حذف مرتجع المبيعات بنجاح');
    }
}

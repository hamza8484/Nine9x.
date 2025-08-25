<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\InvoiceDetail;
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
//use PDF;
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
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Options;

class InvoiceController extends Controller
{
    /**
     * عرض الفواتير
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoice::all();
        $cashboxes = Cashbox::all(); 
        $accounts = Account::all();
        $branchs = Branch::all();

        // تمرير الفواتير إلى الـ View
        return view('invoices.invoices', compact('invoices','cashboxes','accounts','branchs'));
    }

    /**
     * عرض نموذج الفاتورة الجديدة
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // الحصول على أكبر رقم فاتورة حالياً
        $lastInvoice = Invoice::orderBy('id', 'desc')->first(); 

        // إذا كانت هناك فواتير سابقة، نأخذ الرقم الأخير ونضيف 1 له
        $newInvoiceNumber = $lastInvoice ? 'INV-' . str_pad(substr($lastInvoice->inv_number, 4) + 1, 6, '0', STR_PAD_LEFT) : 'INV-000001';

        // جلب باقي البيانات مثل العملاء، المنتجات، المخازن
        $customers = Customer::all(); 
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
        return view('invoices.create-invoice', compact(
            'e_categories',
            'newInvoiceNumber', 
            'customers', 
            'products', 
            'units', 
            'stores', 
            'taxes',
            'taxRate',
            'cashboxes',
            'accounts',
            'branchs'
        ));
    }


    public function show($id)
    {
        $invoices = Invoice::findOrFail($id);
        $tax = Tax::first();  // جلب أول ضريبة فقط
        $taxRate = $tax ? $tax->tax_rate : 0;  // إذا كانت هناك ضريبة، نأخذ قيمتها، وإلا نستخدم القيمة 0
        return view('invoices.inv_show', compact('invoices', 'tax', 'taxRate'));
    }
    


    /**
     * تخزين الفاتورة الجديدة
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
  public function store(Request $request)
    {
        //dd($request->all());

        if (empty($request->categories) || empty($request->products) || empty($request->units)) {
            return back()->withErrors(['error' => 'يجب إضافة تفاصيل المنتجات']);
        }

        $e_categories = Category::all();
        $products = Product::all();
        $units = Unit::all();

        $customer = Customer::find($request->customer_id);
        $store = Store::find($request->store_id);
        $cashbox = Cashbox::find($request->cashbox_id);
        $account = Account::find($request->account_id);

        if (!$customer || !$store || !$cashbox || !$account) {
            return back()->withErrors(['error' => 'العميل أو المتجر أو الخزنة أو الحساب غير موجود']);
        }

        if (!in_array($account->account_group, ['revenue', 'asset'])) {
            return back()->withErrors(['account_id' => 'الحساب المختار غير مناسب لتسجيل العملية البيعية']);
        }

        $data = [
            'inv_number' => $request->inv_number,
            'inv_date' => $request->inv_date,
            'customer_id' => $request->customer_id,
            'store_id' => $request->store_id,
            'inv_payment' => $request->inv_payment,
            'sub_total' => $request->sub_total,
            'discount_value' => $request->discount_value,
            'vat_value' => $request->vat_value,
            'total_deu' => $request->total_deu,
            'total_paid' => $request->total_paid,
            'total_unpaid' => $request->total_unpaid,
            'total_deferred' => $request->total_deferred,
            'customer_name' => $customer->cus_name,
            'store_name' => $store->store_name,
            'branch_id' => $request->branch_id,
            'cashbox_id' => $request->cashbox_id,
            'account_id' => $request->account_id,
            'user_id' => Auth::id(),
            'created_by' => Auth::id(),
        ];

        try {
            $invoice = Invoice::create($data);
        } catch (\Exception $e) {
            Log::error('Error saving invoice', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'حدث خطأ أثناء الحفظ']);
        }

        $details_list = [];
        for ($i = 0; $i < count($request->categories); $i++) {
            $category = $e_categories->find($request->categories[$i]);
            $product = $products->find($request->products[$i]);
            $unit = $units->find($request->units[$i]);

            if (!$category || !$product || !$unit) {
                return back()->withErrors(['error' => 'معلومات المنتج أو الوحدة أو الفئة غير صحيحة']);
            }

            $details_list[$i] = [
                'invoice_id' => $invoice->id,
                'category_id' => $category->id,
                'product_id' => $product->id,
                'unit_id' => $unit->id,
                'product_barcode' => $request->product_barcode[$i],
                'quantity' => $request->quantity[$i],
                'product_price' => $request->product_price[$i],
                'product_discount' => $request->product_discount[$i],
                'total_price' => $request->total_price[$i],
                'user_id' => Auth::id(),
            ];
        }

        try {
            $invoice->invoiceDetails()->createMany($details_list);
        } catch (\Exception $e) {
            Log::error('Error saving invoice details', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'حدث خطأ أثناء حفظ تفاصيل الفاتورة']);
        }

         // ✅ تحديث الرصيد
        try {
            Log::info('Payment type:', ['inv_payment' => $request->inv_payment]);

            $inv_payment = trim($request->inv_payment);
            $cash_options = ['cash', 'نقدي', 'نقــدي', 'شبكة', 'شــبكة'];

            // إذا كانت طريقة الدفع نقدًا
            if (in_array($inv_payment, $cash_options)) {

                // إذا كانت الخزنة مملوكة
                if ($request->filled('cashbox_id')) {
                    $cashbox = Cashbox::find($request->cashbox_id);
                    if ($cashbox) {
                        Log::info('Before update Cashbox', ['balance' => $cashbox->cash_balance]);
                        $cashbox->cash_balance += $request->total_deu;
                        $cashbox->save();
                        Log::info('After update Cashbox', ['balance' => $cashbox->cash_balance]);
                    } else {
                        Log::warning('Cashbox not found for ID: ' . $request->cashbox_id);
                    }
                }

                // تحديث الحساب المرتبط إذا كان الدفع نقدي
                if ($request->filled('account_id')) {
                    $account = Account::find($request->account_id);
                    if ($account) {
                        Log::info('Before update Account (Cash)', ['balance' => $account->balance]);
                        $account->balance += $request->sub_total;  // إضافة المبلغ المدفوع
                        $account->save();
                        Log::info('After update Account (Cash)', ['balance' => $account->balance]);
                    }
                }

            } elseif (in_array($inv_payment, ['credit', 'آجــل'])) {

                // إذا كانت طريقة الدفع "آجل"
                if ($request->filled('account_id')) {
                    $account = Account::find($request->account_id);
                    if ($account) {
                        Log::info('Before update Account (Credit)', ['balance' => $account->balance]);
                        $account->balance += $request->total_deu;  // إضافة المبلغ المستحق
                        $account->save();
                        Log::info('After update Account (Credit)', ['balance' => $account->balance]);
                    }
                }

                // إضافة المبلغ المستحق إلى حساب العميل
                $customer = Customer::find($request->customer_id);
                if ($customer) {
                    Log::info('Before update Customer Balance', ['balance' => $customer->cus_balance]);
                    $customer->cus_balance += $request->total_deferred;  // إضافة المبلغ المستحق للعميل
                    $customer->save();
                    Log::info('After update Customer Balance', ['balance' => $customer->cus_balance]);
                } else {
                    Log::warning('Customer not found for ID: ' . $request->customer_id);
                }

            }

        } catch (\Exception $e) {
            Log::error('Error updating account or cashbox balance', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحديث رصيد الحساب أو الخزنة']);
        }


        return redirect()->route('invoices.index')->with('Add', 'تم حفظ الفاتورة وتحديث الحساب والخزنة بنجاح');
    }



    
    public function pdf($id)
    {
        // جلب الفاتورة
        $invoice = Invoice::findOrFail($id);
        $company = Company::first(); 
        $tax = Tax::first(); 
        $taxRate = $tax ? $tax->tax_rate : 0;

        // بيانات الفاتورة
        $companyName = $company->company_name;
        $taxNumber = $company->tax_number;
        $invoiceDate = \Carbon\Carbon::parse($invoice->inv_date)->format('d-m-Y');
        $totalAmount = $invoice->total_deu;
        $qrCode = new ZatcaQrCode($invoice); // QR Code خاص بـ ZATCA
        $qrImage = $qrCode->generate(); 

        // إعداد خيارات PDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        // تجهيز الفاتورة للتصدير
        $pdf = Pdf::loadView('invoices.pdf', compact(
            'invoice', 
            'companyName', 
            'taxNumber', 
            'invoiceDate', 
            'totalAmount', 
            'qrImage', 
            'taxRate'
        ))->setOptions($options);

        // تحميل ملف PDF
        return $pdf->download('invoice-' . $invoice->inv_number . '.pdf');
    }

    /**
     * تصدير الفواتير إلى Excel
     */
    public function export() 
    {
        return Excel::download(new InvoiceExport, 'invoices.xlsx');
    }
}

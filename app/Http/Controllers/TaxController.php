<?php

namespace App\Http\Controllers;

use App\Tax;
use App\Invoice;
use App\Purchase;
use App\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaxController extends Controller
{
    // عرض جميع الضرائب
    public function index()
    {
        $taxes = Tax::where('user_id', Auth::id())->get(); // عرض الضرائب الخاصة بالمستخدم فقط
        return view('taxes.taxes', compact('taxes')); // عرض الضرائب في الصفحة
    }


    // عرض نموذج لإضافة ضريبة جديدة
    public function create()
    {
        // التحقق إذا كانت الضريبة قد تم إضافتها مسبقًا
        $existingTax = Tax::where('user_id', Auth::id())->first();
        if ($existingTax) {
            return redirect()->route('taxes.index')->with('error', 'تم إضافة الضريبة مسبقًا ولا يمكن إضافة ضريبة جديدة.');
        }

        return view('taxes.create');
    }


    // إضافة ضريبة جديدة
    public function store(Request $request)
    {
        // التحقق من صحة البيانات المدخلة
        $request->validate([
            'tax_rate' => 'required|numeric|min:0|max:100',
        ]);

        // تأكد من أن المستخدم مسجل دخوله
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'يرجى تسجيل الدخول أولاً.');
        }

        // التحقق إذا كانت الضريبة قد تم إضافتها مسبقًا
        $existingTax = Tax::where('user_id', Auth::id())->first();
        if ($existingTax) {
            return redirect()->route('taxes.index')->with('error', 'تم إضافة الضريبة مسبقًا ولا يمكن إضافة ضريبة جديدة.');
        }

        // تخزين الضريبة في قاعدة البيانات
        Tax::create([
            'tax_name' => $request->tax_name,
            'tax_rate' => $request->tax_rate,
            'user_id' => auth()->id(), // المستخدم الذي أضاف الضريبة
        ]);

        return redirect()->route('taxes.index')->with('Add', 'تم إضافة الضريبة بنجاح.');
    }


    // عرض نموذج لتعديل ضريبة موجودة
    public function edit(Tax $tax)
    {
        // التأكد من أن المستخدم هو من أضاف الضريبة
        if ($tax->user_id !== auth()->id()) {
            return redirect()->route('taxes.index')->with('error', 'لا يمكنك تعديل ضريبة لم تقم بإضافتها.');
        }
    
        return view('taxes.edit', compact('tax'));
    }
    

    // تحديث الضريبة
    public function update(Request $request, Tax $tax)
    {
        // التحقق من صحة البيانات المدخلة
        $request->validate([
            'tax_rate' => 'required|numeric|min:0|max:100',
        ]);
    
        // تأكد من أن المستخدم هو من قام بإضافة الضريبة
        if ($tax->user_id !== auth()->id()) {
            return redirect()->route('taxes.index')->with('error', 'لا يمكنك تعديل ضريبة لم تقم بإضافتها.');
        }
    
        // تحديث الضريبة
        $tax->update([
            'tax_rate' => $request->tax_rate,
        ]);
    
        return redirect()->route('taxes.index')->with('Update', 'تم تحديث الضريبة بنجاح.');
    }
    
    

    // حذف ضريبة
    public function destroy(Tax $tax)
    {
        // تأكد من أن المستخدم هو من قام بإضافة الضريبة
        if ($tax->user_id !== auth()->id()) {
            return redirect()->route('taxes.index')->with('error', 'لا يمكنك حذف ضريبة لم تقم بإضافتها.');
        }

        // حذف الضريبة
        $tax->delete();

        return redirect()->route('taxes.index')->with('Delete', 'تم حذف الضريبة بنجاح.');
    }

    // تقرير الربع السنوي للمبيعات والمشتريات والضرائب
    public function quarterlyReport(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
    
        // إذا المستخدم ما أرسل تواريخ، فقط نعرض النموذج
        if (!$startDate || !$endDate) {
            return view('taxes.quarterlyReport');
        }
    
        // جلب فواتير المبيعات
        $salesInvoices = Invoice::whereBetween('inv_date', [$startDate, $endDate])
                                ->whereNotNull('vat_value')
                                ->get();
    
        // جلب فواتير المشتريات
        $purchaseInvoices = Purchase::whereBetween('pur_date', [$startDate, $endDate])
                                    ->whereNotNull('vat_value')
                                    ->get();
    
        $totalSales = $salesInvoices->sum('total_deu');
        $totalSalesTax = $salesInvoices->sum('vat_value');
    
        $totalPurchases = $purchaseInvoices->sum('total_deu');
        $totalPurchasesTax = $purchaseInvoices->sum('vat_value');
    
        $netTax = $totalSalesTax - $totalPurchasesTax;
    
        return view('taxes.quarterlyReport', compact(
            'salesInvoices',
            'purchaseInvoices',
            'totalSales',
            'totalSalesTax',
            'totalPurchases',
            'totalPurchasesTax',
            'netTax',
            'startDate',
            'endDate'
        ));
    }
    
    public function printReport(Request $request)
    {
        $company = Company::where('user_id', Auth::id())->first(); 
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // تحقق من وجود التواريخ
        if (!$startDate || !$endDate) {
            return redirect()->back()->with('error', 'يرجى تحديد الفترة الزمنية');
        }

        $salesInvoices = Invoice::whereBetween('inv_date', [$startDate, $endDate])->whereNotNull('vat_value')->get();
        $purchaseInvoices = Purchase::whereBetween('pur_date', [$startDate, $endDate])->whereNotNull('vat_value')->get();

        $totalSales = $salesInvoices->sum('total_deu');
        $totalSalesTax = $salesInvoices->sum('vat_value');
        $totalPurchases = $purchaseInvoices->sum('total_deu');
        $totalPurchasesTax = $purchaseInvoices->sum('vat_value');
        $netTax = $totalSalesTax - $totalPurchasesTax;

        return view('taxes.print', compact(
            'startDate', 'endDate',
            'totalSales', 'totalSalesTax',
            'totalPurchases', 'totalPurchasesTax',
            'netTax',
            'company'
        ));
    }

    

}

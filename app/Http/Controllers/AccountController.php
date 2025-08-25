<?php

namespace App\Http\Controllers;

use App\Account;
use App\AccountType;
use App\JournalEntryLine;
use App\FiscalYear; 

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        // جلب الحسابات مع نوع الحسابات والحسابات الأب
        $accounts = Account::with('type', 'parent')->get();
        $accountTypes = AccountType::all();

        // جلب السنة المالية النشطة أو أحدث سنة مالية
        $fiscalYear = FiscalYear::where('status', 'نشطة')->first(); // هنا افترضنا أن "نشطة" هي الحالة المناسبة للسنة المالية النشطة

        return view('accounts.index', compact('accounts', 'accountTypes', 'fiscalYear'));
    }


    public function create()
    {
        $accountTypes = AccountType::all();
        $parentAccounts = Account::whereNull('parent_id')->get(); // جلب الحسابات التي ليس لها حساب رئيسي
        return view('accounts.create', compact('accountTypes', 'parentAccounts'));
    }

    public function store(Request $request)
    {
        // التحقق من المدخلات
        $request->validate([
            'name' => 'required|string',
            'code' => 'required|string|unique:accounts,code',
            'account_type_id' => 'required|exists:account_types,id',
            'account_nature' => 'required|in:debit,credit',
            'account_group' => 'required|in:asset,liability,equity,revenue,expense',
            'balance' => 'nullable|numeric|min:0',
           
            'category' => 'nullable|in:financial,sales,expenses,income,liabilities',
            'opening_date' => 'nullable|date',
            'opening_balance' => 'nullable|numeric|min:0',
            'last_modified_date' => 'nullable|date',
            'sub_account_type' => 'nullable|in:current,capital,revenue,expense',
            'currency_code' => 'nullable|in:SAR,USD,AED,JOD',
            'advanced_status' => 'nullable|in:active,inactive,closed,suspended',
            'account_description' => 'nullable|string|max:1000',
        ]);

        // إضافة الحساب الجديد
        Account::create([
            'name' => $request->input('name'),
            'code' => $request->input('code'),
            'account_type_id' => $request->input('account_type_id'),
            'parent_id' => $request->input('parent_id', null),
            'balance' => $request->input('balance', 0),
            'account_nature' => $request->input('account_nature'),
            'account_group' => $request->input('account_group'),
            
            'category' => $request->input('category', null),
            'opening_date' => $request->input('opening_date', null),
            'opening_balance' => $request->input('opening_balance', 0),
            'last_modified_date' => $request->input('last_modified_date', now()),
            'sub_account_type' => $request->input('sub_account_type', null),
            'currency_code' => $request->input('currency_code', 'SAR'),
            'advanced_status' => $request->input('advanced_status', 'active'),
            'account_description' => $request->input('account_description', null),
            'is_active' => $request->input('is_active', 0),

        ]);

        return redirect()->route('accounts.index')->with('success', 'تم إنشاء الحساب بنجاح');
    }


    public function edit($id)
    {
        // جلب الحساب بناءً على المعرف
        $account = Account::findOrFail($id);

        // جلب جميع أنواع الحسابات والحسابات الأب
        $accountTypes = AccountType::all();
        $parentAccounts = Account::whereNull('parent_id')->get(); // جلب الحسابات التي ليس لها حساب رئيسي

        // إرجاع العرض مع البيانات
        return view('accounts.edit', compact('account', 'accountTypes', 'parentAccounts'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'code' => 'required|string|unique:accounts,code,' . $id,
            'account_type_id' => 'required|exists:account_types,id',
            'account_nature' => 'required|in:debit,credit',
            'account_group' => 'required|in:asset,liability,equity,revenue,expense',
            'balance' => 'nullable|numeric|min:0',
           
            'category' => 'nullable|in:financial,sales,expenses,income,liabilities',
            'opening_date' => 'nullable|date',
            'opening_balance' => 'nullable|numeric|min:0',
            'last_modified_date' => 'nullable|date',
            'sub_account_type' => 'nullable|in:current,capital,revenue,expense',
            'currency_code' => 'nullable|in:SAR,USD,AED,JOD',
            'advanced_status' => 'nullable|in:active,inactive,closed,suspended',
            'account_description' => 'nullable|string|max:1000',
        ]);

        $account = Account::findOrFail($id);

        $account->update([
            'name' => $request->input('name'),
            'code' => $request->input('code'),
            'account_type_id' => $request->input('account_type_id'),
            'parent_id' => $request->input('parent_id', $account->parent_id),
            'balance' => $request->input('balance', $account->balance),
            'account_nature' => $request->input('account_nature'),
            'account_group' => $request->input('account_group'),
           
            'category' => $request->input('category', $account->category),
            'opening_date' => $request->input('opening_date', $account->opening_date),
            'opening_balance' => $request->input('opening_balance', $account->opening_balance),
            'last_modified_date' => $request->input('last_modified_date', now()),
            'sub_account_type' => $request->input('sub_account_type', $account->sub_account_type),
            'currency_code' => $request->input('currency_code', $account->currency_code),
            'advanced_status' => $request->input('advanced_status', $account->advanced_status),
            'account_description' => $request->input('account_description', $account->account_description),
            'is_active' => $request->input('is_active', 0),
        ]);

        return redirect()->route('accounts.index')->with('success', 'تم تحديث الحساب بنجاح!');
    }


    public function destroy($id)
    {
        // جلب الحساب بناءً على المعرف
        $account = Account::findOrFail($id);

        // التحقق إذا كان الحساب يحتوي على حسابات فرعية قبل الحذف
        if ($account->children()->count() > 0) {
            return redirect()->route('accounts.index')->with('error', 'لا يمكن حذف الحساب لأنه يحتوي على حسابات فرعية.');
        }

        // حذف الحساب
        $account->delete();

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('accounts.index')->with('success', 'تم حذف الحساب بنجاح!');
    }

    public function showLedger(Request $request, $id)
    {
        $account = Account::findOrFail($id);

        $query = JournalEntryLine::with('journalEntry')
            ->join('journal_entries', 'journal_entry_lines.journal_entry_id', '=', 'journal_entries.id')
            ->where('journal_entry_lines.account_id', $id);

        // الفلاتر بالتاريخ
        if ($request->from_date) {
            $query->where('journal_entries.transaction_date', '>=', $request->from_date);
        }

        if ($request->to_date) {
            $query->where('journal_entries.transaction_date', '<=', $request->to_date);
        }

        $journalEntryLines = $query->orderBy('journal_entries.transaction_date', 'asc')
                                ->select('journal_entry_lines.*') // لتفادي تعارض الأعمدة
                                ->get();

        return view('accounts.ledger', compact('account', 'journalEntryLines'));
    }

     // تأكد من أنك تستخدم مكتبة dompdf أو barryvdh/laravel-dompdf

   
  public function ledgerPdf(Request $request, $id)
    {
        $account = Account::findOrFail($id);

        // استعلام البيانات بناءً على التاريخ
        $journalEntryLines = JournalEntryLine::with('journalEntry')
            ->where('account_id', $id)
            ->whereHas('journalEntry', function ($q) use ($request) {
                if ($request->from_date) {
                    $q->where('transaction_date', '>=', $request->from_date);
                }
                if ($request->to_date) {
                    $q->where('transaction_date', '<=', $request->to_date);
                }
            })
            ->get();

        // تحميل العرض إلى PDF مع تمكين الخيارات الخاصة بـ DomPDF
        $pdf = PDF::loadView('accounts.ledger_pdf', compact('account', 'journalEntryLines'))
                ->setOption('isHtml5ParserEnabled', true)
                ->setOption('isPhpEnabled', true)
                ->setOption('font', 'Cairo'); // تأكد من أنك تستخدم الخط العربي

        // تحميل PDF
        return $pdf->download('ledger_' . $account->name . '.pdf');
    }

    

}


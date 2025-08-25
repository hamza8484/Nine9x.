<?php

namespace App\Http\Controllers;

use App\JournalEntry;
use App\Account;
use App\FiscalYear; 
use App\JournalEntryLine;
use Illuminate\Http\Request;
use DB;

class JournalEntryController extends Controller
{
    public function index()
    {
        // جلب القيود اليومية مع الحسابات المدين والدائنة
        $journalEntries = JournalEntry::with(['debitAccount', 'creditAccount'])->get(); 

        // إرجاع البيانات إلى الصفحة view
        return view('journal_entries.index', compact('journalEntries'));
    }

    // عرض نموذج إدخال القيد اليومي
    public function create()
    {
        $fiscalYears = FiscalYear::all();
        $accounts = Account::where('is_active', true)->get(); // جلب الحسابات النشطة
        return view('journal_entries.create', compact('accounts', 'fiscalYears'));
    }

    // تخزين القيد اليومي
    public function store(Request $request)
    {
        // التحقق من صحة البيانات
        $validated = $request->validate([
            'transaction_date' => 'required|date',
            'description' => 'nullable|string',
            'fiscal_year_id' => 'required|exists:fiscal_years,id',
            'reference_number' => 'nullable|string',
            'lines' => 'required|array',
            'lines.*.account_id' => 'required|exists:accounts,id',
            'lines.*.entry_type' => 'required|in:debit,credit',
            'lines.*.amount' => 'required|numeric|min:0',
        ]);

        // التحقق من وجود حسابات مدين ودائن
        $debitAmount = 0;
        $creditAmount = 0;
        $hasDebitAccount = false;
        $hasCreditAccount = false;
        $debitAccountId = null;
        $creditAccountId = null;

        // حساب المبالغ المدين والدائن
        foreach ($validated['lines'] as $line) {
            if ($line['entry_type'] === 'debit') {
                $debitAmount += $line['amount'];
                $hasDebitAccount = true;
                $debitAccountId = $line['account_id']; // حفظ الحساب المدين
            } elseif ($line['entry_type'] === 'credit') {
                $creditAmount += $line['amount'];
                $hasCreditAccount = true;
                $creditAccountId = $line['account_id']; // حفظ الحساب الدائن
            }
        }

        // التحقق من أنه يوجد حساب مدين وحساب دائن
        if (!$hasDebitAccount) {
            return redirect()->route('journal_entries.index')->with('error', 'لا يوجد حساب مدين');
        }

        if (!$hasCreditAccount) {
            return redirect()->route('journal_entries.index')->with('error', 'لا يوجد حساب دائن');
        }

        // التحقق من أن المبلغ المدين يساوي المبلغ الدائن
        if (abs($debitAmount - $creditAmount) > 0.01) {
            return redirect()->route('journal_entries.index')->with('error', 'المبالغ المدين والدائن غير متساوية');
        }

        // بدء المعاملة
        DB::beginTransaction();

        try {
            // تسجيل البيانات المدخلة في السجل
            \Log::debug('Journal Entry Data:', $validated);

            // إنشاء القيد اليومي
            $journalEntry = JournalEntry::create([
                'transaction_date' => $validated['transaction_date'],
                'description' => $validated['description'],
                'fiscal_year_id' => $validated['fiscal_year_id'],
                'reference_number' => $validated['reference_number'],
                'debit_account_id' => $debitAccountId, // إضافة حساب المدين
                'credit_account_id' => $creditAccountId, // إضافة حساب الدائن
            ]);

            // إضافة السطور (الحسابات المدين والدائن)
            foreach ($validated['lines'] as $line) {
                // إضافة السطر إلى جدول journal_entry_lines
                JournalEntryLine::create([
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => $line['account_id'],
                    'entry_type' => $line['entry_type'],
                    'amount' => $line['amount'],
                    'description' => $line['description'],
                ]);
            }

            DB::commit(); // تأكيد المعاملة
            return redirect()->route('journal_entries.index')->with('success', 'تم إضافة القيد اليومي بنجاح');
        } catch (\Exception $e) {
            DB::rollBack(); // التراجع عن المعاملة في حال حدوث خطأ
            \Log::error('Error in saving journal entry: ' . $e->getMessage(), ['data' => $validated]);
            return redirect()->route('journal_entries.index')->with('error', 'فشل حفظ القيد اليومي: ' . $e->getMessage());
        }
    }


    // دالة التعديل للقيد اليومي
    public function edit($id)
    {
        $journalEntry = JournalEntry::findOrFail($id);
        $accounts = Account::where('is_active', true)->get();
        $fiscalYears = FiscalYear::all();
        
        return view('journal_entries.edit', compact('journalEntry', 'accounts', 'fiscalYears'));
    }

    // دالة التحديث للقيد اليومي
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'transaction_date' => 'required|date',
            'description' => 'nullable|string',
            'fiscal_year_id' => 'required|exists:fiscal_years,id',
        ]);

        $journalEntry = JournalEntry::findOrFail($id);
        $journalEntry->update($validated);

        return redirect()->route('journal_entries.index')->with('success', 'تم تعديل القيد بنجاح');
    }
}

<?php


namespace App\Http\Controllers;

use App\JournalEntryLine;
use App\Account;
use App\JournalEntry;
use Illuminate\Http\Request;

class JournalEntryLineController extends Controller
{

     // عرض جميع سطور القيد
    public function index()
    {
        // جلب جميع سطور القيد من قاعدة البيانات
        $journalEntryLines = JournalEntryLine::with(['journalEntry', 'account'])->get();

        // إرجاع البيانات إلى واجهة العرض
        return view('journal_entry_lines.index', compact('journalEntryLines'));
    }
    // عرض نموذج إضافة سطر القيد
    public function create()
    {
        // جلب القيود اليومية والحسابات النشطة
        $journalEntries = JournalEntry::all();
        $accounts = Account::where('is_active', true)->get();

        return view('journal_entry_lines.create', compact('journalEntries', 'accounts'));
    }

    // تخزين سطر القيد الجديد
    public function store(Request $request)
    {
        // التحقق من صحة البيانات المدخلة
        $validated = $request->validate([
            'journal_entry_id' => 'required|exists:journal_entries,id',
            'account_id' => 'required|exists:accounts,id',
            'entry_type' => 'required|in:debit,credit',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        // إنشاء سطر القيد الجديد
        $journalEntryLine = JournalEntryLine::create([
            'journal_entry_id' => $validated['journal_entry_id'],
            'account_id' => $validated['account_id'],
            'entry_type' => $validated['entry_type'],
            'amount' => $validated['amount'],
            'description' => $validated['description'],
        ]);

        // تحديث رصيد الحساب المرتبط بناءً على نوع القيد (مدين أو دائن)
        $account = Account::find($validated['account_id']);
        
        if ($validated['entry_type'] == 'debit') {
            // إذا كان القيد مدينًا، نضيف المبلغ إلى رصيد الحساب
            $account->balance += $validated['amount'];
        } else {
            // إذا كان القيد دائنًا، نخصم المبلغ من رصيد الحساب
            $account->balance -= $validated['amount'];
        }

        // حفظ التحديثات في الحساب
        $account->save();

        // إعادة التوجيه إلى قائمة سطور القيد مع رسالة نجاح
        return redirect()->route('journal_entry_lines.index')->with('success', 'تم إضافة سطر القيد وتحديث الرصيد بنجاح');
    }

}


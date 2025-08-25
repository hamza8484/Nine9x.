<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\JournalEntry;
use App\Account;
use App\JournalEntryLine;

class JournalEntryLinesSeeder extends Seeder
{
    public function run()
    {
        // البحث عن أول قيد محاسبي موجود
        $journalEntry = JournalEntry::first(); // أو اختر القيد اليومي الذي ترغب في إضافة سطور له
        if (!$journalEntry) {
            throw new \Exception("لا توجد قيود محاسبية موجودة.");
        }

        // البحث عن حسابات الأصول والمصروفات باستخدام الأكواد الخاصة بها
        $assetAccount = Account::where('code', '1100')->first(); // حساب الأصول الثابتة
        $expensesAccount = Account::where('code', '2100')->first(); // حساب المصروفات

        // التحقق من وجود الحسابات
        if (!$assetAccount) {
            throw new \Exception("حساب الأصول الثابتة غير موجود.");
        }
        if (!$expensesAccount) {
            throw new \Exception("حساب المصروفات غير موجود.");
        }

        // إضافة السطر الأول (حركة مدين على حساب الأصول)
        JournalEntryLine::create([
            'journal_entry_id' => $journalEntry->id,
            'account_id' => $assetAccount->id,
            'entry_type' => 'debit', // مدين
            'amount' => 1000.00, // المبلغ المدين
            'description' => 'حركة من الأصول الثابتة', // وصف الحركة
        ]);

        // إضافة السطر الثاني (حركة دائن على حساب المصروفات)
        JournalEntryLine::create([
            'journal_entry_id' => $journalEntry->id,
            'account_id' => $expensesAccount->id,
            'entry_type' => 'credit', // دائن
            'amount' => 1000.00, // المبلغ الدائن
            'description' => 'حركة من المصروفات التشغيلية', // وصف الحركة
        ]);
    }
}


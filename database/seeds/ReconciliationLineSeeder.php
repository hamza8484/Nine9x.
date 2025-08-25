<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\ReconciliationLine;
use App\AccountReconciliation;
use App\JournalEntryLine;

class ReconciliationLineSeeder extends Seeder
{
    public function run()
    {
        // البحث عن أول تسوية موجودة في جدول تسويات الحسابات
        $reconciliation = AccountReconciliation::first(); // أو يمكنك تخصيصه لاختيار تسوية معينة
        if (!$reconciliation) {
            throw new \Exception("لا توجد تسويات حسابات موجودة.");
        }

        // البحث عن أول سطر قيد محاسبي
        $journalEntryLine = JournalEntryLine::first(); // أو اختر سطر قيد معين حسب الحاجة
        if (!$journalEntryLine) {
            throw new \Exception("لا توجد سطور قيد محاسبي.");
        }

        // إضافة سطر تسوية جديد
        ReconciliationLine::create([
            'reconciliation_id' => $reconciliation->id, // ربط التسوية
            'journal_entry_line_id' => $journalEntryLine->id, // ربط سطر القيد
            'amount' => 500.00, // المبلغ الذي تم تسويته
            'entry_type' => 'debit', // نوع الحركة (مدين أو دائن)
            'is_matched' => true, // الحركة مطابقة
        ]);

        // إضافة سطر تسوية آخر
        ReconciliationLine::create([
            'reconciliation_id' => $reconciliation->id, // ربط التسوية
            'journal_entry_line_id' => $journalEntryLine->id, // ربط سطر القيد
            'amount' => 500.00, // المبلغ الذي تم تسويته
            'entry_type' => 'credit', // نوع الحركة (دائن)
            'is_matched' => false, // الحركة غير مطابقة
        ]);
    }
}

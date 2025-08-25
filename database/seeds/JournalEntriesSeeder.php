<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\JournalEntry;
use App\FiscalYear;

class JournalEntriesSeeder extends Seeder
{
    public function run()
    {
        // البحث عن السنة المالية النشطة
        $fiscalYear = FiscalYear::where('status', 'نشطة')->first();

        if (!$fiscalYear) {
            throw new \Exception("لا توجد سنة مالية نشطة.");
        }

        // إضافة قيد محاسبي جديد دون الحاجة للحسابات المدين والدائن
        JournalEntry::create([
            'transaction_date' => now(), // تاريخ المعاملة (تاريخ اليوم)
            'description' => 'تسجيل قيد أولي', // وصف المعاملة
            'fiscal_year_id' => $fiscalYear->id, // ربط السنة المالية
            'reference_number' => 'REF001', // رقم مرجعي للقيد
            // لا حاجة لإضافة حسابات المدين والدائن
        ]);
    }
}




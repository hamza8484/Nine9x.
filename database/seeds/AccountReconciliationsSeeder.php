<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\User; 
use App\Account; 
use App\AccountReconciliation; 

class AccountReconciliationsSeeder extends Seeder
{
    public function run()
    {
        // البحث عن الحسابات التي سيتم تسويتها
        $account = Account::first(); // تأكد من وجود حساب للتسوية

        if (!$account) {
            throw new \Exception("لا يوجد حساب للتسوية.");
        }

        // البحث عن المستخدم الذي أجرى التسوية (هنا نفترض أننا نأخذ أول مستخدم)
        $user = User::first(); // أو اختر مستخدم معين حسب الحاجة

        if (!$user) {
            throw new \Exception("لا يوجد مستخدم للتسوية.");
        }

        // إضافة تسوية الحساب
        AccountReconciliation::create([
            'account_id' => $account->id, // معرّف الحساب الذي سيتم تسويته
            'reconciled_by' => $user->id, // معرّف المستخدم الذي أجرى التسوية
            'reconciliation_date' => now(), // تاريخ التسوية (تاريخ اليوم)
            'system_balance' => 1000.00, // الرصيد المسجل في النظام (يمكنك تغييره بناءً على البيانات)
            'actual_balance' => 1000.00, // الرصيد الفعلي من المصدر الخارجي
            'status' => 'reconciled', // حالة التسوية (مكتملة في هذه الحالة)
            'notes' => 'تسوية الحساب بنجاح', // ملاحظات إضافية
            'created_at' => now(), // تاريخ الإنشاء
            'updated_at' => now(), // تاريخ التحديث
        ]);
    }
}




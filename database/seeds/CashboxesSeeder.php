<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CashboxesSeeder extends Seeder
{
    public function run()
    {
        // إضافة بعض البيانات الوهمية أو المبدئية إلى جدول cashboxes
        DB::table('cashboxes')->insert([
            [
                'cash_name' => 'Main Cashbox', 
                'cash_balance' => 1000.00, 
                'opening_balance' => 1000.00, 
                'start_date' => '2025-01-01', 
                'last_opening_balance' => 1000.00, 
                'usable_balance' => 800.00, 
                'previous_balance' => 1000.00,
                'balance_at_last_reconciliation' => 800.00,
                'user_id' => 1, // معرّف المستخدم
                'cashbox_type' => 'main',
                'reconciliation_status' => 'pending',
                'currency_code' => 'SAR',
                'cash_limit' => 5000.00,
                'limit_effective_date' => '2025-12-31',
                'status' => 'active',
                'notes' => 'Cashbox for main office',
                'description' => 'Main cashbox for office transactions',
                'created_by' => 1, // معرّف المستخدم الذي أنشأ
                'updated_by' => 1, // معرّف المستخدم الذي حدّث
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cash_name' => 'Revenue Cashbox', 
                'cash_balance' => 500.00, 
                'opening_balance' => 500.00, 
                'start_date' => '2025-02-01', 
                'last_opening_balance' => 500.00, 
                'usable_balance' => 450.00, 
                'previous_balance' => 500.00,
                'balance_at_last_reconciliation' => 450.00,
                'user_id' => 1, // يجب أن تتأكد من وجود مستخدم بالـ id 1
                'cashbox_type' => 'revenue',
                'reconciliation_status' => 'completed',
                'currency_code' => 'USD',
                'cash_limit' => 1000.00,
                'limit_effective_date' => '2025-12-31',
                'status' => 'active',
                'notes' => 'Revenue cashbox for daily sales',
                'description' => 'Cashbox for revenue collection from sales',
                'created_by' => 1, // معرّف المستخدم الذي أنشأ
                'updated_by' => 1, // معرّف المستخدم الذي حدّث
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}

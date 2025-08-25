<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        DB::table('customers')->insert([
            'cus_name' => 'عميل إفتراضي',
            'cus_tax_number' => '00000000',
            'cus_phone' => '00000000',
            'cus_mobile' => '00000000',
            'cus_commercial_record' => '00000000',
            'cus_balance' => 0000.00,  // الرصيد
            'cus_maile' => 'hamza@gmail.com',
            'cus_address' => 'الرياض، شارع الملك عبد الله',
            'cus_notes' => 'عميل موثوق مع سجل تجاري مميز.',
            'user_id' => 1,  // assuming there's a user with ID 1
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}

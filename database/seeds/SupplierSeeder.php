<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        DB::table('suppliers')->insert([
            'sup_name' => 'مورد إفتراضي',
            'sup_tax_number' => '00000000',
            'sup_phone' => '00000000',
            'sup_mobile' => '00000000',
            'sup_commercial_record' => '00000000',
            'sup_balance' => 0000.00,
            'sup_address' => 'المدينة المنورة، شارع الملك عبد الله',
            'sup_notes' => 'مورد رئيسي يزودنا بالمنتجات الإلكترونية.',
            'user_id' => 1,  // assuming there's a user with ID 1
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}

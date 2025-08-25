<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StoreSeeder extends Seeder
{
    public function run()
    {
        DB::table('stores')->insert([
            'store_name' => 'المخزن الرئيسي',
            'store_location' => 'الطابق الأول، منطقة 1',
            'store_notes' => 'المخزن الرئيسي يحتوي على جميع الأصناف الأساسية.',
            'total_stock' => 0000,
            'inventory_value' => 00000.00,
            'status' => 'active',
            'created_by' => 'System',
            'branch_id' => 1,  // assuming there's a branch with ID 1
            'user_id' => 1,     // assuming there's a user with ID 1
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}

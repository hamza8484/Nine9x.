<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BranchSeeder extends Seeder
{
    public function run()
    {
        DB::table('branches')->insert([
            'bra_name' => 'الفرع الرئيسي',
            'bra_address' => 'الرياض، شارع الملك عبد الله',
            'bra_phone' => '00000000',
            'bra_telephon' => '00000000',
            'user_id' => 1,  // assuming there's a user with ID 1
            'bra_manager' => 'أحمد العتيبي',
            'bra_manager_phone' => '0509876543',
            'bra_type' => 'رئيسي',
            'is_active' => true,
            'is_online' => true,
            'branch_notes' => 'فرع رئيسي يقدم جميع الخدمات.',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TaxSeeder extends Seeder
{
    public function run()
    {
        DB::table('taxes')->insert([
            'tax_name' => 'ضريبة القيمة المضافة',
            'tax_rate' => 15.00,  // نسبة الضريبة
            'user_id' => 1,  // assuming there's a user with ID 1
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CompanySeeder extends Seeder
{
    public function run()
    {
        DB::table('companies')->insert([
            'company_name' => 'شركة إفتراضية ',
            'tax_number' => '000000000000000',
            'commercial_register' => '0000000',
            'email' => 'hamzajarrar@gmail.com',
            'phone' => '00000000',
            'mobile' => '00000000',
            'address' => 'المدينة المنورة ، شارع الملك عبد الله',
            'notes' => 'مؤسسة تجارية مختصة في بيع البرامج الحسابية والمواقع الإلكترونية.',
            'logo' => 'storage/logos/company_logo.png',
            'user_id' => 1,  // Assuming there is a user with ID 1
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}

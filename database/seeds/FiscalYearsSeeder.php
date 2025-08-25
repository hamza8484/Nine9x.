<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\FiscalYear;

class FiscalYearsSeeder extends Seeder
{
    public function run()
    {
        FiscalYear::create([
            'name' => '2024/2025',
            'start_date' => '2024-01-01',
            'end_date' => '2025-12-31',
            'status' => 'نشطة',
        ]);
    }
}


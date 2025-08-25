<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\SubscriptionPlan;
use Carbon\Carbon;



class SubscriptionPlansSeeder extends Seeder
{
    public function run()
    {
        SubscriptionPlan::create([
            'name' => 'الخطة الفضية',
            'price' => 89.99,
            'duration_months' => 6,
            'features' => 'دعم فني محدود، وصول محدود للمزايا، تحديثات نصف سنوية',
        ]);

        SubscriptionPlan::create([
            'name' => 'الخطة الذهبية',
            'price' => 99.99,
            'duration_months' => 12,
            'features' => 'دعم فني كامل، وصول لجميع المزايا، تحديثات مستمرة',
        ]);

        SubscriptionPlan::create([
            'name' => 'الخطة البلاتينية',
            'price' => 149.99,
            'duration_months' => 24,
            'features' => 'دعم VIP، مميزات حصرية، تحديثات لحظية، استشارات مجانية',
        ]);
    }
}

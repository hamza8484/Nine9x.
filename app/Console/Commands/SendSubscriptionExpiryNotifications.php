<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\UserSubscription;
use App\Notifications\SubscriptionEndingSoon;
use Carbon\Carbon;

class SendSubscriptionExpiryNotifications extends Command
{
    protected $signature = 'subscriptions:notify-expiring';
    protected $description = 'إرسال تنبيه للمستخدمين الذين اشتراكهم سينتهي قريباً';

    public function handle()
    {
        $today = Carbon::today();
        $threshold = Carbon::today()->addDays(5);

        $subscriptions = UserSubscription::whereBetween('end_date', [$today, $threshold])->get();

        foreach ($subscriptions as $subscription) {
            $user = $subscription->user;

            if ($user) {
                $user->notify(new SubscriptionEndingSoon($subscription));
            }
        }

        $this->info('تم إرسال التنبيهات للمستخدمين الذين تنتهي اشتراكاتهم قريباً.');
    }
}

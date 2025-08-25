<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\UserSubscription;
use Carbon\Carbon;
use App\Notifications\SubscriptionExpiring;
use Illuminate\Support\Facades\DB;

class NotifyExpiringSubscriptions extends Command
{
    protected $signature = 'subscriptions:notify-expiring';
    protected $description = 'إرسال تنبيهات للمستخدمين الذين سينتهي اشتراكهم قريباً';

    public function handle()
    {
        $targetDate = Carbon::now()->addDays(2)->startOfDay();

        $subscriptions = UserSubscription::whereDate('end_date', $targetDate)->get();

        foreach ($subscriptions as $subscription) {
            $user = $subscription->user;

            if (!$user) continue;

            // تحقق مما إذا تم إرسال إشعار مشابه سابقًا
            $alreadyNotified = DB::table('notifications')
                ->where('notifiable_id', $user->id)
                ->where('notifiable_type', get_class($user))
                ->where('type', \App\Notifications\SubscriptionExpiring::class)
                ->whereJsonContains('data->subscription_id', $subscription->id)
                ->exists();

            if (!$alreadyNotified) {
                $user->notify(new SubscriptionExpiring($subscription));
                $this->info("تم إرسال إشعار للمستخدم: {$user->id}");
            }
        }

        $this->info('اكتمل إرسال التنبيهات للمستخدمين.');
    }
}

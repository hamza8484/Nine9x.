<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubscriptionEndingSoon extends Notification
{
    use Queueable;

    protected $subscription;

    /**
     * Create a new notification instance.
     *
     * @param  \App\UserSubscription  $subscription
     * @return void
     */
    public function __construct($subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database']; // تنبيه في الواجهة فقط
    }

    /**
     * Get the array representation of the notification for database storage.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'subscription_id' => $this->subscription->id,
            'message' => 'تنبيه: اشتراكك سينتهي بتاريخ ' . $this->subscription->end_date->format('Y-m-d'),
            'end_date' => $this->subscription->end_date->toDateString(),
            'action_url' => url('/subscription/renew/' . $notifiable->id),
        ];
    }
}


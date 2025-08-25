<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

class SubscriptionExpiryNotification extends Notification
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
        return ['database']; // إرسال إلى قاعدة البيانات بدلاً من البريد
    }

    /**
     * Get the database representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'subscription_id' => $this->subscription->id,
            'message' => 'اشتراكك سينتهي بتاريخ: ' . $this->subscription->end_date->format('Y-m-d'),
            'action_url' => url('/subscription/renew/' . $notifiable->id), // رابط لتجديد الاشتراك
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            // يمكنك إضافة المزيد من البيانات هنا إذا احتجت
        ];
    }
}


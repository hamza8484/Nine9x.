<?php

// app/Models/SubscriptionPlan.php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'name', 'price', 'duration_months',
    ];

    /**
     * العلاقة بين الخطة والاشتراكات
     */
    public function userSubscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }
}



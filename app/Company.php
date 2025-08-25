<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    // إضافة 'user_id' إلى الـ fillable لكي يتم تخزينه مع بقية البيانات
    protected $fillable = [
        'company_name',
        'tax_number',
        'commercial_register',
        'email',
        'phone',
        'mobile',
        'address',
        'notes',
        'logo',
        'user_id', // إضافة user_id هنا
    ];

    public function user()
    {
        return $this->belongsTo(User::class); // الحساب ينتمي إلى مستخدم
    }
}

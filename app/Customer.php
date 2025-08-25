<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'cus_name',
        'cus_tax_number',
        'cus_phone',
        'cus_mobile',
        'cus_commercial_record',
        'cus_balance',
        'cus_address',
        'cus_notes',
        'cus_maile',
        'user_id',  // تأكد من إضافة 'user_id' هنا
    ];

    /**
     * علاقة مع جدول السندات (Receipt).
     */
    public function transactions()
    {
        return $this->hasMany(ClientTransaction::class);
    }

    /**
     * علاقة مع جدول المستخدمين (User).
     * كل عميل ينتمي إلى مستخدم واحد.
     */
    public function user()
    {
        return $this->belongsTo(User::class); // العلاقة مع جدول المستخدمين
    }
}


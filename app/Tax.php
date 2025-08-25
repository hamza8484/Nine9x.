<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    // إضافة tax_name في الـ $fillable
    protected $fillable = [
        'tax_name', 
        'tax_rate', 
        'user_id'
    ];

    // العلاقة بين Tax و User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function accounts()
    {
        return $this->hasMany(Account::class); // حسابات مرتبطة بالضريبة
    }
}

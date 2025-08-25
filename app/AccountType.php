<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class AccountType extends Model
{
    

    // تحديد جدول الـ Model
    protected $table = 'account_types';

    // الحقول القابلة للتعبئة
    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
    ];

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
}

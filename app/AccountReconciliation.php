<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountReconciliation extends Model
{
    protected $fillable = [
        'account_id', 
        'reconciled_by', 
        'reconciliation_date',
        'system_balance',
        'actual_balance',
        'status',
        'notes',
    ];

    // العلاقة مع جدول الحسابات
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    // العلاقة مع المستخدم الذي أجرى التسوية
    public function reconciledBy()
    {
        return $this->belongsTo(User::class, 'reconciled_by');
    }
   public function lines()
    {
        return $this->hasMany(ReconciliationLine::class, 'reconciliation_id');
    }


}


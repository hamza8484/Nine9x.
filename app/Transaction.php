<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'account_id',
        'cashbox_id',
        'tax_id',
        'type',
        'amount',
        'description',
        'transaction_date',
    ];

    /**
     * المستخدم الذي أنشأ الحركة.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * الحساب المرتبط بالحركة.
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * الخزنة المرتبطة بالحركة.
     */
    public function cashbox()
    {
        return $this->belongsTo(Cashbox::class);
    }

    /**
     * الضريبة المطبقة على الحركة (إن وجدت).
     */
    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

    /**
     * هل الحركة عبارة عن إيداع؟
     */
    public function isCredit()
    {
        return $this->type === 'credit';
    }

    /**
     * هل الحركة عبارة عن سحب؟
     */
    public function isDebit()
    {
        return $this->type === 'debit';
    }
}

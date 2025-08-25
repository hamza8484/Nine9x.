<?php

namespace App;


use App\User;
use App\Cashbox; 

use Illuminate\Database\Eloquent\Model;

class CashboxTransaction extends Model
{
    protected $fillable = [
        'cashbox_id', 
        'type', 
        'cash_amount', 
        'user_id', 
        'cash_description'
    ];

    public static function getTransactionsByCashbox($cashbox_id)
    {
        return self::with(['cashbox', 'user']) // تحميل البيانات المرتبطة مع الخزنة والمستخدم
                ->where('cashbox_id', $cashbox_id)
                ->orderBy('created_at', 'desc')
                ->get();
    }



    // علاقة مع الخزنة
    public function cashbox()
    {
        return $this->belongsTo(Cashbox::class);
    }

    // علاقة مع المستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ClientTransaction extends Model
{
    // الحقول التي يمكن تعبئتها عبر create أو update
    protected $fillable = [
        'amount',
        'balance',
        'payment_method',
        'date',
        'user_id',
        'client_id',
        'cashbox_id',  // تعديل هذا الحقل من treasury_id إلى cashbox_id
        'account_id',
        'notes',
    ];

    /**
     * علاقة مع نموذج العميل
     */
    public function client()
    {
        return $this->belongsTo(Customer::class, 'client_id');
    }

    /**
     * علاقة مع نموذج المستخدم (الذي أضاف السند)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * علاقة مع نموذج الخزنة
     */
    public function cashbox()
    {
        return $this->belongsTo(Cashbox::class, 'cashbox_id');  // تعديل هذا أيضاً ليكون cashbox_id بدلاً من treasury_id
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

}

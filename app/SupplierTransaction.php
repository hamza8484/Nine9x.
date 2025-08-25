<?php

namespace App;


use App\SupplierTransaction;
use App\Supplier;
use App\User;
use Illuminate\Database\Eloquent\Model;

class SupplierTransaction extends Model
{
    // تحديد الأعمدة القابلة للتعبئة
    protected $fillable = [
        'amount',
        'balance',
        'balance_before_transaction',  // لا تنسى تضمين هذا الحقل إذا كنت تستخدمه
        'payment_method',
        'date',
        'user_id',
        'supplier_id',
        'cashbox_id',  
        'notes',
    ];


    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cashbox()
    {
        return $this->belongsTo(Cashbox::class, 'cashbox_id');  // التأكد من أن الاسم صحيح هنا
    }
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
  
}

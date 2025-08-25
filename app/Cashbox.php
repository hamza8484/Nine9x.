<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cashbox extends Model
{
    use SoftDeletes; // تفعيل الحذف اللين (اختياري)

    // تحديد الحقول المسموح إدخالها بشكل جماعي (Mass Assignment)
    protected $fillable = [
       'user_id',
        'cash_name',
        'cashbox_type',
        'cash_balance',
        'start_date',
        'last_opening_balance',
        'usable_balance',
        'reconciliation_status',
        'limit_effective_date',
        'cash_limit',
        'currency_code',
        'status',
        'notes',
    ];

    // علاقة مع المعاملات الخاصة بالخزنة
    public function transactions()
    {
        return $this->hasMany(CashboxTransaction::class, 'cashbox_id');
    }

    // علاقة مع تاريخ الرصيد الخاص بالخزنة
    public function balanceHistories()
    {
        return $this->hasMany(CashboxBalanceHistory::class, 'cashbox_id');
    }

    // علاقة مع معاملات العملاء
    public function clientTransactions()
    {
        return $this->hasMany(ClientTransaction::class, 'cashbox_id');
    }

    // علاقة مع المستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // علاقة مع الحسابات (عند وجود جدول وسيط)
    public function accounts()
    {
        return $this->belongsToMany(Account::class, 'account_cashbox');
    }

    // في موديل Cashbox
    public function supplierTransactions()
    {
        return $this->hasMany(SupplierTransaction::class, 'cashbox_id');
    }

}


<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Supplier;
use App\User;
use App\Branch;
use App\Store;
use App\Cashbox;
use App\Account;

class Purchase extends Model
{
    protected $table = 'purchase';
    
    protected $fillable = [
        'pur_number',
        'pur_date',
        'supplier_id',
        'store_id',
        'branch_id', 
        'inv_payment',
        'sub_total',
        'discount_value',
        'vat_value',
        'total_deu',
        'total_paid',
        'total_unpaid',
        'total_deferred',
        'created_by',
        'cashbox_id', 
        'account_id', 
        'notes', 
        'user_id', // إضافة user_id إلى fillable
    ];

    // العلاقة مع جدول المورد (supplier)
    public function supplier() 
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    // العلاقة مع تفاصيل الفاتورة (PurchaseDetail)
    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class, 'purchase_id');
    }

    // العلاقة مع المستخدم الذي أنشأ الفاتورة
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // العلاقة مع المستخدم صاحب الفاتورة
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // العلاقة مع المخزن (Store)
    public function store() 
    {
        return $this->belongsTo(Store::class);
    }

    // العلاقة مع الفرع (Branch)
    public function branch() 
    {
        return $this->belongsTo(Branch::class);
    }

    // العلاقة مع الخزنة (Cashbox)
    public function cashbox() 
    {
        return $this->belongsTo(Cashbox::class);
    }

    // العلاقة مع الحسابات (Account)
    public function account() 
    {
        return $this->belongsTo(Account::class);
    }

    // العلاقة مع جدول الضرائب (Tax)
    public function tax()
    {
        return $this->belongsTo(Tax::class, 'tax_id');
    }
    
}


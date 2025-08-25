<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Supplier;
use App\User;
use App\Tax;
use App\Store;
use App\Purchase;

class RetPurchase extends Model
{
    protected $fillable = [
        'ret_pur_number',
        'ret_pur_date',
        'store_id',
        'supplier_id',
        'ret_pur_payment',
        'created_by',
        'sub_total',
        'discount_value',
        'discount_type',
        'vat_value',
        'total_deu',
        'total_paid',
        'total_unpaid',
        'notes',
        'return_reason',
        'cashbox_id', 
        'account_id', 
        'branch_id',
        'tax_id',
        'purchase_id',
        'user_id',
    ];

    // تعريف العلاقة مع DetailsReturnPurchase (واحد إلى متعدد)
    public function detailsReturnPurchases()
    {
        return $this->hasMany(DetailsReturnPurchase::class, 'ret_purchase_id');
    }

    // العلاقة مع جدول الضرائب (Tax)
    public function tax()
    {
        return $this->belongsTo(Tax::class, 'tax_id');
    }

    // العلاقة مع جدول المورد (Supplier)
    public function supplier() 
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    // العلاقة مع المستخدم (User)
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // العلاقة مع المتجر (Store)
    public function store() 
    {
        return $this->belongsTo(Store::class);
    }

    // العلاقة بين RetPurchase و Purchase
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    // العلاقة مع المستخدم (User)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');  // ربط مع الـ user_id
    }

}

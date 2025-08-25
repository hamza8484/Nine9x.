<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{

    protected $fillable = [
        'sup_name',
        'sup_tax_number',
        'sup_phone',
        'sup_mobile',
        'sup_commercial_record',
        'sup_balance',
        'sup_address',
        'sup_notes',
        'user_id'
    ];

    /**
     * علاقة مع جدول سندات الصرف.
     */
    public function supplierTransactions()
    {
        return $this->hasMany(SupplierTransaction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Customer;
use App\User;
use App\Branch;
use App\Store;
use App\Cashbox;
use App\Account;
use App\Tax; 


class Quotation extends Model
{
    protected $fillable = [
        'qut_number',
        'qut_date',
        'customer_id',
        'store_id',
        'qut_notes',
        'qut_status',
        'sub_total',
        'discount_value',
        'vat_value',
        'total_deu',
        'created_by',
        'user_id'
    ];

    
    // العلاقة مع جدول الضرائب (Tax)
    public function tax()
    {
        return $this->belongsTo(Tax::class, 'tax_id');
    }

    // العلاقة مع جدول العملاء (Customer)
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function detailsQuotations()
    {
        return $this->hasMany(DetailsQuotation::class, 'quotation_id');
    }
    

    // العلاقة مع المستخدم (User)
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function store() 
    {
        return $this->belongsTo(Store::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class); // الحساب ينتمي إلى مستخدم
    }

    
}

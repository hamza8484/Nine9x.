<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Details_ReturnInvoice;  
use App\Customer;
use App\User;
use App\Tax;
use App\Store;
use App\Invoice;

class ReturnInvoice extends Model
{
    protected $fillable = [
        'ret_inv_number', 
        'ret_inv_date', 
        'store_id', 
        'customer_id', 
        'ret_inv_payment', 
        'created_by', 
        'sub_total', 
        'discount_value', 
        'discount_type', 
        'vat_value', 
        'total_deu', 
        'total_paid', 
        'total_unpaid', 
        'total_deferred',
        'invoice_id',
        'user_id'
    ];
    
    protected static function booted()
    {
        static::creating(function ($invoice) {
            // تعيين تاريخ الفاتورة عند إنشائها
            $invoice->ret_inv_date = $invoice->ret_inv_date ?? now()->toDateString();
        });
    }

    public function detailsReturnInvoices()
    {
        return $this->hasMany(Details_ReturnInvoice::class, 'return_invoice_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class); // الحساب ينتمي إلى مستخدم
    }

}


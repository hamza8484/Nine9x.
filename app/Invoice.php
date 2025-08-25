<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Customer;
use App\User;
use App\Branch;
use App\Store;
use App\Cashbox;
use App\Account;
use App\Tax;  // تأكد من أن لديك الكلاس Tax

class Invoice extends Model
{
    protected $fillable = [
        'inv_number',
        'inv_date',
        'customer_id',
        'store_id',
        'inv_payment',
        'sub_total',
        'discount_value',
        'vat_value',
        'total_deu',
        'total_paid',
        'total_unpaid',
        'total_deferred',
        'created_by',
        'branch_id',
        'cashbox_id',
        'account_id',
        'user_id',
    ];

    // العلاقة مع جدول الضرائب (Tax)
    public function tax()
    {
        return $this->belongsTo(Tax::class, 'tax_id');  // تأكد من وجود حقل tax_id في قاعدة البيانات
    }

    // العلاقة مع جدول العملاء (Customer)
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    // العلاقة مع تفاصيل الفاتورة (InvoiceDetails)
    public function invoiceDetails()
    {
        return $this->hasMany(InvoiceDetail::class, 'invoice_id');
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

    // علاقة الفاتورة مع المرتجعات
    public function returnInvoices()
    {
        return $this->hasMany(ReturnInvoice::class, 'original_invoice_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}

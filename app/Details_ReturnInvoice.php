<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Details_ReturnInvoice extends Model
{
    // تأكد من تحديد اسم الجدول بشكل صحيح
    protected $table = 'details_return_invoices'; // اسم الجدول الصحيح في قاعدة البيانات

    protected $fillable = [
        'return_invoice_id',
        'category_id', 
        'product_id',  
        'unit_id',     
        'product_barcode',
        'quantity',
        'product_price',
        'product_discount',
        'total_price',
        'user_id'
    ];

    // العلاقة مع جدول مرتجع (return_invoice_id)
    public function returnInvoice()
    {
        return $this->belongsTo(ReturnInvoice::class, 'return_invoice_id');
    }

    // العلاقة مع جدول المنتجات (Product)
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // العلاقة مع جدول الفئات (Category)
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // العلاقة مع جدول الوحدات (Unit)
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class); // الحساب ينتمي إلى مستخدم
    }
}

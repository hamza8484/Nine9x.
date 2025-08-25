<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    protected $fillable = [
        'invoice_id',
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

    // العلاقة مع جدول الفاتورة (invoice)
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
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
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class); 
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    // إضافة العمود user_id في الـ fillable
    protected $fillable = [
        'purchase_id',
        'category_id', 
        'product_id',  
        'unit_id',     
        'product_barcode',
        'quantity',
        'product_price',
        'product_discount',
        'total_price',
        'user_id',  // إضافة user_id لتخزين معرف المستخدم
    ];

    // العلاقة مع جدول الفاتورة (Purchase)
    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
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

    // العلاقة مع جدول المستخدمين (User)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

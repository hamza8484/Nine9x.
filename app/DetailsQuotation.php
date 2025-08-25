<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailsQuotation extends Model
{
    protected $fillable = [
        'quotation_id',
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

    // العلاقة مع جدول عرض السعر  (Quotation)
    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id');
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
        return $this->belongsTo(User::class); // الحساب ينتمي إلى مستخدم
    }
}

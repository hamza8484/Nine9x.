<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailsReturnPurchase extends Model
{
    protected $table = 'details_return_purchases';
    
    protected $fillable = [
        'ret_purchase_id',
        'category_id', 
        'product_id',  
        'unit_id',     
        'product_barcode',
        'quantity',
        'product_price',
        'product_discount',
        'total_price',
        'user_id',  // تأكد من تضمين user_id في fillable
    ];
   
    // تعريف العلاقة مع RetPurchase (ينتمي إلى)
    public function retPurchase()
    {
        return $this->belongsTo(RetPurchase::class, 'ret_purchase_id');
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
        return $this->belongsTo(User::class);  // ربط السجل بالمستخدم
    }
}


<?php

namespace App;

use App\Category;
use App\Tax;
use App\Store;
use App\Unit;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // الحقول القابلة للملىء
    protected $fillable = [
        'product_name',
        'product_barcode',
        'product_serial_number',
        'store_id',
        'category_id',
        'unit_id',
        'tax_id',
        'tax_rate',
        'product_expiry_date',
        'product_image',
        'product_stock_quantity',
        'product_quantity',
        'product_sale_price',
        'product_total_price',
        'product_status',
        'expiry_date_status',
        'created_by',
        'product_description',
        'user_id'
    ];
    

    // العلاقة بين المنتج والمخزن
    public function store()
    {
        return $this->belongsTo(Store::class);  // هنا نفترض أن كل منتج مرتبط بمخزن واحد
    }

  // Product.php
    public function tax()
        {
            return $this->belongsTo(Tax::class, 'tax_id');
        }
        
      // العلاقة مع جدول الفئات (Category)
    public function category()
        {
            return $this->belongsTo(Category::class);
        }
        
     // العلاقة مع جدول الوحدات (Unit)
     public function unit()
     {
         return $this->belongsTo(Unit::class, 'unit_id');
     }

     public function user()
    {
        return $this->belongsTo(User::class);
    }


}

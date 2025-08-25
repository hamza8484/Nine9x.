<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;
use App\User; // إضافة العلاقة مع المستخدم

class Unit extends Model
{
    // حدد الحقول القابلة للتعيين
    protected $fillable = [
        'unit_name', 
        'user_id'  // تغيير created_by إلى user_id
    ];

    // العلاقة مع المنتجات
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // العلاقة مع المستخدم
    public function user()
    {
        return $this->belongsTo(User::class);  // كل وحدة تنتمي إلى مستخدم واحد
    }
}



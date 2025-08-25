<?php

namespace App;

use App\Product;
use App\User; // إضافة الـ User
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // تحديد الحقول القابلة للتعبئة عبر Eloquent
    protected $fillable = [
        'c_name',        // اسم الفئة
        'c_description', // وصف الفئة
        'user_id',       // ربط الفئة بالمستخدم
    ];

    // العلاقة مع المنتج
    public function products()
    {
        return $this->hasMany(Product::class); // علاقة فئة بالمنتجات
    }

    // العلاقة مع المستخدم (الذي أنشأ الفئة)
    public function user()
    {
        return $this->belongsTo(User::class);  // كل فئة ينتمي إليها مستخدم واحد
    }
}


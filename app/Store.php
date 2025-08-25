<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    // إذا كنت ترغب في تحديد الحقول التي يمكن ملؤها (Mass Assignment)
    protected $fillable = [
        'store_name',
        'store_location',
        'store_notes',
        'total_stock',
        'inventory_value',
        'status',
        'created_by',
        'branch_id',  // إضافة هذا الحقل لتمكين تعيينه
        'user_id',    // إضافة حقل user_id لربط المخزن بالمستخدم
    ];

    // في نموذج Store، علاقة مع المنتجات
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // إضافة علاقة مع الفرع (Branch)
    public function branch()
    {
        return $this->belongsTo(Branch::class);  // كل مخزن ينتمي إلى فرع واحد
    }

    // إضافة علاقة مع المستخدم (User)
    public function user()
    {
        return $this->belongsTo(User::class);  // كل مخزن ينتمي إلى مستخدم واحد
    }
}


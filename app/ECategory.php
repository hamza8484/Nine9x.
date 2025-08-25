<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Expenses;  // استيراد نموذج Expenses

class ECategory extends Model
{
    // تحديد الجدول (إذا كان الاسم مختلفًا)
    protected $table = 'e_categories';

    // الحقول القابلة للتحديث
    protected $fillable = [
        'cat_name', 
        'description', 
        'created_by',
        'user_id'
    ];

    /**
     * العلاقة بين ECategory و Expenses
     * كل فئة قد تحتوي على العديد من المصروفات
     */
    public function expenses()
    {
        return $this->hasMany(Expenses::class, 'category_id');  // العلاقة بين الفئة والمصروفات
    }
    public function user()
    {
        return $this->belongsTo(User::class); // الحساب ينتمي إلى مستخدم
    }
}

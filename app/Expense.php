<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ECategory;  // استيراد نموذج ECategory

class Expense extends Model
{
    // تحديد الحقول القابلة للتحديث (الموجودة في الجدول)
    protected $fillable = [
        'exp_inv_number',   // رقم المصروف
        'exp_name',         // اسم المصروف
        'exp_date',         // تاريخ المصروف
        'exp_inv_name',     // اسم مورد الفاتورة
        'exp_sup_number',   // رقم فاتورة المورد
        'exp_sup_date',     // تاريخ فاتورة المورد
        'category_id',      // الفئات (مفتاح خارجي)
        'exp_amount',       // المبلغ
        'exp_discount',     // الخصم
        'tax_id',           // tax_id (مفتاح خارجي يشير إلى جدول taxes)
        'tax_amount',       // قيمة الضريبة
        'final_amount',     // المجموع النهائي بعد حساب الضريبة والخصم
        'description',      // وصف المصروف
        'created_by',       // اسم المستخدم الذي أضاف المصروف
        'branch_id',        // المفتاح الخارجي لربط الفروع
        'cashbox_id',       // الخزنة التي تم الدفع منها (مفتاح خارجي)
        'account_id',       // الحساب الذي تم الدفع منه (مفتاح خارجي)
        'user_id'
    ];

    /**
     * العلاقة بين Expenses و ECategory
     * كل مصروف ينتمي إلى فئة واحدة
     */
    public function eCategory()
    {
        return $this->belongsTo(ECategory::class, 'category_id');  // العلاقة بين المصروف والفئة
    }
    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class); // الحساب ينتمي إلى مستخدم
    }
    
}

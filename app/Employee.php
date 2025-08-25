<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    // تحديد اسم الجدول في قاعدة البيانات إذا كان يختلف عن الاسم الافتراضي
    protected $table = 'employees';

    // الحقول القابلة للتعبئة (Mass Assignable)
    protected $fillable = [
        'emp_name',
        'emp_number',
        'emp_id_number',
        'emp_age',
        'emp_salary',
        'emp_allowance',
        'emp_birth_date',
        'emp_hire_date',
        'emp_phone',
        'emp_mobile',
        'emp_email',
        'emp_department',
        'emp_image',
        'emp_position',
        'emp_id_image',
        'emp_contract_image',
        'emp_status',
        'emp_notes',
        'user_id'
    ];

    // الحقول التي لا يمكن تعبئتها (إذا كانت هناك حقول تحتاج حماية من التعديل الجماعي)
    protected $guarded = [];

    // تعيين تواريخ الحقول
    protected $dates = ['emp_birth_date', 'emp_hire_date'];

    public function user()
    {
        return $this->belongsTo(User::class); // الحساب ينتمي إلى مستخدم
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalaryPayment extends Model
{
    // تحديد اسم الجدول إذا لم يتبع القواعد الافتراضية (مثل الجمع)
    protected $table = 'salary_payments';

    protected $casts = [
        'payment_date' => 'date',  // تأكد من أن Laravel يعامل هذا الحقل كـ Date
    ];
    
    // تحديد الحقول القابلة للتعبئة
    protected $fillable = [
        'employee_id',
        'cashbox_id',
        'account_id',
        'branch_id',
        'amount',
        'gross_salary',
        'bonus',
        'deduction',
        'tax_deduction',
        'total_deductions',
        'net_amount',
        'payment_date',
        'payment_status',
        'payment_method',
        'payment_reference',
        'currency',
        'payment_received_by',
        'payment_notes',
        'payment_method_details',
        'salary_month',
        'salary_year',
        'user_id'
    ];

    // علاقة مع جدول الموظفين
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // علاقة مع جدول الخزنة (Cashbox)
    public function cashbox()
    {
        return $this->belongsTo(Cashbox::class);
    }

    // علاقة مع جدول الحسابات (Account)
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    // علاقة مع جدول الفروع (Branch)
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // علاقة مع جدول المستخدمين
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    protected $fillable = [
        'transaction_date',
        'description',
        'debit_account_id',
        'credit_account_id',
        'reference_number',
        'fiscal_year_id',  // إضافة الحقل هنا
    ];

    // العلاقة مع حساب المدين
    public function debitAccount()
    {
        return $this->belongsTo(Account::class, 'debit_account_id');
    }

    // العلاقة مع حساب الدائن
    public function creditAccount()
    {
        return $this->belongsTo(Account::class, 'credit_account_id');
    }

    public function fiscalYear()
    {
        return $this->belongsTo(FiscalYear::class);
    }

    // في نموذج JournalEntry
    public function journalEntryLines()
    {
        return $this->hasMany(JournalEntryLine::class);
    }

    // تعيين السنة المالية تلقائيًا قبل حفظ القيد
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($entry) {
            if (!$entry->fiscal_year_id) {
                // تعيين السنة المالية الحالية بشكل تلقائي
                $entry->fiscal_year_id = \App\FiscalYear::current()->id;  // تأكد من أن لديك نموذج FiscalYear
            }
        });
    }
}

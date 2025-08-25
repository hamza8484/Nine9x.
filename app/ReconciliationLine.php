<?php


namespace App;

use Illuminate\Database\Eloquent\Model;

class ReconciliationLine extends Model
{
    protected $fillable = [
        'reconciliation_id',         // ✅ الاسم الصحيح الموجود في جدول قاعدة البيانات
        'journal_entry_line_id',     // ✅ مفتاح خارجي للقيد المحاسبي
        'amount',
        'entry_type',                // ✅ الاسم الصحيح للحقل (ليس type)
        'is_matched',
    ];

    // علاقة مع تسوية الحساب
    public function reconciliation()
    {
        return $this->belongsTo(AccountReconciliation::class, 'reconciliation_id');
    }

    // علاقة مع سطر القيد المحاسبي
    public function journalEntryLine()
    {
        return $this->belongsTo(JournalEntryLine::class);
    }
}



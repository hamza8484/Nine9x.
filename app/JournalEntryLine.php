<?php


namespace App;

use Illuminate\Database\Eloquent\Model;

class JournalEntryLine extends Model
{
    // تحديد الجدول الذي يرتبط به هذا الموديل
    protected $table = 'journal_entry_lines';
    
    // تحديد الحقول القابلة للتعبئة (Mass Assignable)
    protected $fillable = [
        'journal_entry_id', 
        'account_id',
        'entry_type', 
        'amount',
        'description'
    ];

    // علاقة الـ journalEntry
    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class);
    }

    // علاقة الـ account
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}



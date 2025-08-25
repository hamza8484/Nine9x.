<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'name',
        'code',
        'account_type_id',
        'parent_id',
        'balance',
        'is_active',
        'account_nature',
        'account_group',
        'cashbox_id',
        'tax_id',
        'category',
        'opening_date',
        'opening_balance',
        'last_modified_date',
        'sub_account_type',
        'currency_code',
        'advanced_status',
        'account_description',
        'created_by',
        'updated_by'
    ];
    

    public function type()
    {
        return $this->belongsTo(AccountType::class, 'account_type_id');
    }

    // في نموذج Account
    public function parent()
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Account::class, 'parent_id');
    }

    public function cashboxes()
    {
        return $this->hasMany(Cashbox::class);
    }
    public function tax()
    {
        return $this->belongsTo(Tax::class); // حساب مرتبط بالضريبة
    }
    public function accounts()
    {
        return $this->belongsToMany(Account::class, 'account_cashbox');
    }
     public function journalEntriesDebit()
    {
        return $this->hasMany(JournalEntry::class, 'debit_account_id');
    }

    public function journalEntriesCredit()
    {
        return $this->hasMany(JournalEntry::class, 'credit_account_id');
    }

    

    public function journalEntryLines()
    {
        return $this->hasMany(JournalEntryLine::class);
    }

}


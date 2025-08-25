<?php

namespace App\Exports;

use App\JournalEntry;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LedgerExport implements FromView
{
    protected $account;
    protected $entries;

    public function __construct($account, $entries)
    {
        $this->account = $account;
        $this->entries = $entries;
    }

    public function view(): View
    {
        return view('ledger.export', [
            'account' => $this->account,
            'entries' => $this->entries,
        ]);
    }
}


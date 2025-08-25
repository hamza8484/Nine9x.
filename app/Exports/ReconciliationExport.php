<?php

namespace App\Exports;

use App\ReconciliationLine;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;

class ReconciliationExport implements FromCollection, WithHeadings, WithEvents, WithCustomStartCell
{
    protected $reconciliationId;

    public function __construct($reconciliationId)
    {
        $this->reconciliationId = $reconciliationId;
    }

    public function collection()
    {
        return ReconciliationLine::where('reconciliation_id', $this->reconciliationId)
            ->select(
                'id',
                'reconciliation_id',
                'journal_entry_line_id',
                'amount',
                'entry_type',
                'is_matched',
                'created_at',
                'updated_at'
            )->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Reconciliation ID',
            'Journal Entry Line ID',
            'Amount',
            'Entry Type',
            'Is Matched',
            'Created At',
            'Updated At',
        ];
    }

    public function startCell(): string
    {
        return 'A2'; // تبدأ العناوين من الصف الثاني
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->setCellValue('A1', 'تفاصيل التسوية المالية رقم ' . $this->reconciliationId);
                $event->sheet->mergeCells('A1:H1'); // دمج الأعمدة A إلى H
                $event->sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
            },
        ];
    }
}





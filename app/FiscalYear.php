<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FiscalYear extends Model
{
    protected $fillable = [
        'name', 
        'start_date',
        'end_date', 
        'status', 
    ];

    public function journalEntries()
    {
        return $this->hasMany(JournalEntry::class);
    }


    // دالة لاستخراج السنة المالية الحالية
    public static function current()
    {
        return self::where('start_date', '<=', now())  // السنة المالية بدأت قبل أو في الوقت الحالي
            ->where('end_date', '>=', now())  // السنة المالية تنتهي بعد أو في الوقت الحالي
            ->where('status', 'نشطة')  // التأكد من أن السنة المالية نشطة
            ->first();  // إرجاع السنة المالية الحالية
    }

}



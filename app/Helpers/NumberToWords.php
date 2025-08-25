<?php

namespace App\Helpers;

class NumberToWords
{
    public static function convert($number)
    {
        $formatter = new \NumberFormatter("ar", \NumberFormatter::SPELLOUT);
        return $formatter->format($number);
    }
}

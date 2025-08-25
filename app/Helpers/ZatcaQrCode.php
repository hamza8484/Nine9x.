<?php

namespace App\Helpers;

class ZatcaQrCode
{
    public static function encodeTLV($tag, $value)
    {
        $value = trim($value);
        return chr($tag) . chr(strlen($value)) . $value;
    }

    public static function getBase64TLV($sellerName, $vatNumber, $invoiceDate, $invoiceTotal, $vatTotal)
    {
        $tlv =
            self::encodeTLV(1, $sellerName) .
            self::encodeTLV(2, $vatNumber) .
            self::encodeTLV(3, $invoiceDate) .
            self::encodeTLV(4, $invoiceTotal) .
            self::encodeTLV(5, $vatTotal);

        return base64_encode($tlv);
    }
}

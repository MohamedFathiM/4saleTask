<?php

namespace App\Enums;

enum PaymentType: string
{
    case METHOD_ONE = 'method_one';
    case METHOD_TWO = 'method_two';

    public static function casesValues()
    {
        return array_column(self::cases(), 'value');
    }
}

<?php

namespace App\Enum;

enum TransactionStatus: string
{
    case PENDING = 'pending';
    case SUCCESSFUL  = 'Successful ';
    case FAILED = 'failed';


    public static function getStatuses(): array
    {
        return [
            self::PENDING->value,
            self::SUCCESSFUL ->value,
            self::FAILED->value,
        ];
    }
}

<?php

namespace App\Enum;

enum TransactionStatus: string
{
    case COMPLETED = 'completed';
    case PENDING = 'pending';
    case FAILED = 'failed';
}

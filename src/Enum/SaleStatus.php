<?php

namespace App\Enum;

enum SaleStatus: string
{
    case COMPLETED = 'completed';
    case PENDING = 'pending';
    case CANCELLED = 'cancelled';
}

<?php

namespace App\Enum;

enum PaymentMethod: string
{
    case CARD = 'card';
    case MOBILE_MONEY = 'mobile_money';
    case BANK_TRANSFER = 'bank_transfer';
    case CASH = 'cash';
}

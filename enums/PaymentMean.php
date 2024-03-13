<?php

namespace Saleh7\Zatca\Enums;

enum PaymentMean: string
{
    case CASH = '10';
    case CHEQUE = '20';
    case CREDIT_CARD = '54';
    case DEBIT_CARD = '55';
}

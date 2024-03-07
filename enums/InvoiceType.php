<?php

namespace Saleh7\Zatca\Enums;

/**
 * The Document Type (Invoice - Debit Note - Credit Note - Prepayment Invoice)
 */
enum InvoiceType: int
{
    const INVOICE = 388;
    const PREPAYMENT = 386;
    const DEBIT_NOTE = 383;
    const CREDIT_NOTE = 381;
}

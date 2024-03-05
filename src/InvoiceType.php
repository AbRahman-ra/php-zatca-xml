<?php

namespace Saleh7\Zatca;

include __DIR__ . '/../vendor/autoload.php';

use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class InvoiceType implements XmlSerializable
{
    private $invoice;
    private $invoiceType;


    /**
     * Sets the document layout (B2B Documents `standard` - B2C Documents `simplified`) - Case Insensitive
     * @param string $invoice The documet layout
     * @return InvoiceType
     */
    public function setInvoice(string $invoice): InvoiceType
    {
        $this->invoice = strtolower($invoice);
        return $this;
    }

    /**
     * Sets the document Type (Invoice `invoice` - Debit notes `debit` - Credit Notes `credit` - Prepayment Invoices `prepayment`) - Case Insensitive
     * @param mixed $invoiceType
     * @return InvoiceType
     */
    public function setInvoiceType(string $invoiceType): InvoiceType
    {
        $this->invoiceType = strtolower($invoiceType);
        return $this;
    }

    /**
     * The xmlSerialize method is called during xml writing.
     *
     * @param Writer $writer
     * @return void
     */
    public function xmlSerialize(Writer $writer): void
    {
        // die($this->invoiceType);
        // Check Document Type
        switch ($this->invoiceType) {
            case 'invoice':
                $invoiceTypeCode = InvoiceTypeCode::INVOICE;
                break;
            case 'debit':
                $invoiceTypeCode = InvoiceTypeCode::DEBIT_NOTE;
                break;
            case 'credit':
                $invoiceTypeCode = InvoiceTypeCode::CREDIT_NOTE;
                break;
            case 'prepayment':
                $invoiceTypeCode = InvoiceTypeCode::PREPAYMENT;
                break;
            default:
                die("Document Type can be `Invoice`, `Debit`, `Credit` or `Prepayment` only, found $this->invoiceType\n");
        }

        // Check Document Layout
        switch ($this->invoice) {
            case 'standard':
                $invoiceType = InvoiceTypeCode::STANDARD;
                break;
            case 'simplified':
                $invoiceType = InvoiceTypeCode::SIMPLIFIED;
                break;
            default:
                die("Document Layout can be `Standard` or `Simplified` only, found $this->invoice\n");
        }


        $writer->write([
            [
                "name" => Schema::CBC . 'InvoiceTypeCode',
                "value" => $invoiceTypeCode,
                "attributes" => [
                    "name" => $invoiceType
                ]
            ],
        ]);
    }
}

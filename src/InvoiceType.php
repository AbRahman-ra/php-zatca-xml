<?php

namespace Saleh7\Zatca;

use Sabre\Xml\XmlSerializable;
use Saleh7\Zatca\Enums\DocumentType;
use Sabre\Xml\Writer;
use Saleh7\Zatca\Enums\DocumentLayout;

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
        // Check Document Type
        switch ($this->invoiceType) {
            case 'invoice':
                $invoiceTypeCode = DocumentType::INVOICE;
                break;
            case 'debit':
                $invoiceTypeCode = DocumentType::DEBIT_NOTE;
                break;
            case 'credit':
                $invoiceTypeCode = DocumentType::CREDIT_NOTE;
                break;
            case 'prepayment':
                $invoiceTypeCode = DocumentType::PREPAYMENT;
                break;
            default:
                die("Document Type can be `Invoice`, `Debit`, `Credit` or `Prepayment` only, found $this->invoiceType\n");
        }

        // Check Document Layout
        switch ($this->invoice) {
            case 'standard':
                $invoiceType = DocumentLayout::STANDARD;
                break;
            case 'simplified':
                $invoiceType = DocumentLayout::SIMPLIFIED;
                break;
            default:
                die("Document Layout can be `Standard` or `Simplified` only, found $this->invoice\n");
        }


        // Write the Invoice if everything is valid
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

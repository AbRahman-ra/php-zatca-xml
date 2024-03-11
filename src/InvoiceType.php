<?php

namespace Saleh7\Zatca;

use Sabre\Xml\XmlSerializable;
use Sabre\Xml\Writer;
use Saleh7\Zatca\Enums\DocumentType;
use Saleh7\Zatca\Enums\InvoiceSubtype;

class InvoiceType implements XmlSerializable
{
    private $invoice;
    private $invoiceType;
    private $documentType;
    private bool $is3rdParty = false;
    private bool $isNominal = false;
    private bool $isExport = false;
    private bool $isSummary = false;
    private bool $isSelfBilled = false;

    /**
     * @param string $documentType, the document type (`invoice` for invoices, `credit` for Credit Note, `debit` for Debit Note, `prepayment` for Prepayment Invoice)
     * @param string $invoiceType, the document layout (`standard` for B2B invoices, `simplified` for B2C invoices)
     */
    public function __construct(int $documentType, string $invoiceType)
    {
        $this->documentType = $documentType;
    }

    /**
     * `NN`: Invoice Subtype (`01` for B2B, `02` for B2C)
     * `P`: Is the invoice a 3rd party invoice? (`0` for false, `1` for true)
     * `N`: Is the invoice a nominal invoice? (`0` for false, `1` for true)
     * `E`: Is the invoice an export invoice? (`0` for false, `1` for true)
     * `S`: Is the invoice a summary invoice? (`0` for false, `1` for true)
     * `B`: Is the invoice a self billed invoice? (`0` for false, `1` for true)
     */

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
     * Marks The Invoice as a 3rd party invoice
     * @param void
     * @return InvoiceType
     */
    public function set3rdParty()
    {
        $this->is3rdParty = true;
        return $this;
    }

    /**
     * Marks The Invoice as a nominal invoice
     * @param void
     * @return InvoiceType
     */
    public function setNominal()
    {
        $this->isNominal = true;
        return $this;
    }

    /**
     * Marks The Invoice as an export invoice
     * @param void
     * @return InvoiceType
     */
    public function setExport()
    {
        $this->isExport = true;
        return $this;
    }

    /**
     * Marks The Invoice as a summary invoice
     * @param void
     * @return InvoiceType
     */
    public function setSummary()
    {
        $this->isSummary = true;
        return $this;
    }

    /**
     * Marks The Invoice as a self-billed invoice
     * @param void
     * @return InvoiceType
     */
    public function setSelfBilled()
    {
        $this->isSelfBilled = true;
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
                die("Document Type can be `invoice`, `debit`, `credit` or `prepayment` only, found $this->invoiceType\n");
        }

        // Check Document Layout
        switch ($this->invoice) {
            case 'standard':
                $invoiceType = InvoiceSubtype::STANDARD;
                break;
            case 'simplified':
                $invoiceType = InvoiceSubtype::SIMPLIFIED;
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

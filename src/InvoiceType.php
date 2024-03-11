<?php

namespace Saleh7\Zatca;

use BadMethodCallException;
use InvalidArgumentException;
use Sabre\Xml\XmlSerializable;
use Sabre\Xml\Writer;
use Saleh7\Zatca\Enums\DocumentLayout;
use Saleh7\Zatca\Enums\DocumentType;

class InvoiceType implements XmlSerializable
{
    // private $invoice;
    // private $invoiceType;
    /**
     * The Document Type (Invoice - Debit notes - Credit Notes - Prepayment Invoices)
     */
    private string $documentType;

    /**
     * The Document Layout (Standard - Simplified) of the invoice
     */
    private string $documentLayout;

    private bool $is3rdParty = false;
    private bool $isNominal = false;
    private bool $isExport = false;
    private bool $isSummary = false;
    private bool $isSelfBilled = false;

    /**
     * The 7-digit Document Type Code (NNPESB) of the invoice
     */
    private string $fullDocumentTypeCode;

    /**
     * 
     */
    private function validate(string $documentType, string $documentLayout)
    {
        // Check Document Type
        switch ($documentType) {
            case 'invoice':
                $this->documentType = DocumentType::INVOICE;
                break;
            case 'debit':
                $this->documentType = DocumentType::DEBIT_NOTE;
                break;
            case 'credit':
                $this->documentType = DocumentType::CREDIT_NOTE;
                break;
            case 'prepayment':
                $this->documentType = DocumentType::PREPAYMENT_INVOICE;
                break;
            default:
                $this->documentType = '';
                throw new InvalidArgumentException("Document Type can be `invoice`, `debit`, `credit` or `prepayment` only, found $this->documentType\n");
        }

        // Check Document Layout
        switch ($documentLayout) {
            case 'standard':
            case 'simplified':
                break;
            default:
                throw new InvalidArgumentException("Document Layout can be `standard` or `simplified` only, found $documentLayout\n");
        }
        return true;
    }

    /**
     * Creates a new invoice type instance with a given document type and layout (case insensitive)
     * @param string $documentType the document type (`'invoice'` for invoices, `'credit'` for Credit Note, `'debit'` for Debit Note, `prepayment` for Prepayment Invoice)
     * @param string $documentLayout the document layout (`'standard'` for B2B invoices, `'simplified'` for B2C invoices)
     * 
     */
    public function __construct(string $documentType, string $documentLayout)
    {
        $documentType = strtolower($documentType);
        $documentLayout = strtolower($documentLayout);

        if ($this->validate($documentType, $documentLayout) === true) {
            $this->documentType = $documentType;
            $this->documentLayout = $documentLayout;
        }
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

    private function generateDocumentFullCode(): InvoiceType
    {
        $fullDocumentTypeCode = '';

        // Check Document Layout
        switch ($this->documentLayout) {
            case 'standard':
                $fullDocumentTypeCode .= DocumentLayout::STANDARD;
                break;
            case 'simplified':
                $fullDocumentTypeCode .= DocumentLayout::SIMPLIFIED;
                break;
            default:
                throw new InvalidArgumentException("Document Layout can be `standard` or `simplified` only, found $this->documentLayout\n");
        }

        // Check Other Subtypes
        $fullDocumentTypeCode .= $this->is3rdParty ? '1' : '0';
        $fullDocumentTypeCode .= $this->isNominal ? '1' : '0';
        $fullDocumentTypeCode .= $this->isExport ? '1' : '0';
        $fullDocumentTypeCode .= $this->isSummary ? '1' : '0';
        $fullDocumentTypeCode .= $this->isSelfBilled ? '1' : '0';

        if ($this->isSelfBilled && $this->isExport) {
            throw new BadMethodCallException("Documents can't be self billed and export at the same time\n");
        }

        $this->fullDocumentTypeCode = $fullDocumentTypeCode;
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
        $this->generateDocumentFullCode();

        // Write the Invoice if everything is valid
        $writer->write([
            [
                "name" => Schema::CBC . 'InvoiceTypeCode',
                "value" => $this->documentType, // 3 digits (DocumentType::XXX enum)
                "attributes" => [
                    "name" => $this->fullDocumentTypeCode // 7 digits
                ]
            ],
        ]);
    }
}

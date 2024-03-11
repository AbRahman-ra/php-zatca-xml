<?php

namespace Saleh7\Zatca;

use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class BillingReference implements XmlSerializable
{
    private $id;

    /**
     * Generate a Billing Reference to an existing invoice
     * Billing Reference is a mandatory field when issuing a Credit or Debit Note
     * @param string $id
     * @return BillingReference
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * The xmlSerialize method is called during xml writing.
     *
     * @param Writer $writer
     * @return void
     */
    public function xmlSerialize(Writer $writer): void
    {
        if ($this->id !== null) {
            $writer->write([Schema::CAC . 'InvoiceDocumentReference' => [Schema::CBC . 'ID' => $this->id]]);
        }
    }
}

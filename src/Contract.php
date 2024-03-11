<?php

namespace Saleh7\Zatca;

use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class Contract implements XmlSerializable
{
    private $id;

    /**
     * Creates a contract reference for an invoice
     * Contract Reference is usually used in B2B documents
     * @param string $id
     * @return Contract
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
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
            $writer->write([Schema::CBC . 'ID' => $this->id]);
        }
    }
}

<?php

namespace Saleh7\Zatca;

use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class Signature implements XmlSerializable
{
    private $id;
    private $signatureMethod;

    public function __construct(
        string $id = 'urn:oasis:names:specification:ubl:signature:Invoice',
        string $signatureMethod = 'urn:oasis:names:specification:ubl:dsig:enveloped:xades'
    ) {
        $this->id = $id;
        $this->signatureMethod = $signatureMethod;
    }
    /**
     * @param string $id
     * @return Signature
     */
    public function setId(string $id): Signature
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $method
     * @return Signature
     */
    public function setSignatureMethod(string $method): Signature
    {
        $this->signatureMethod = $method;
        return $this;
    }

    /**
     * The xmlSerialize method is called during xml writing.
     * @param Writer $writer
     * @return void
     */
    public function xmlSerialize(Writer $writer): void
    {
        $writer->write([
            Schema::CBC .  "ID" => $this->id,
            Schema::CBC . "SignatureMethod" => $this->signatureMethod
        ]);
    }
}

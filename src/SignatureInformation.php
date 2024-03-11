<?php

namespace Saleh7\Zatca;

use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class SignatureInformation implements XmlSerializable
{
    private string $id;
    private string $referencedSignatureID;

    /**
     * CONSTANT - Don't Change
     * @param string $id
     * @param string $referencedSignatureID
     */
    public function __construct(
        $id = 'urn:oasis:names:specification:ubl:signature:1',
        $referencedSignatureID = 'urn:oasis:names:specification:ubl:signature:Invoice'
    ) {
        $this->id = $id;
        $this->referencedSignatureID = $referencedSignatureID;
    }

    /**
     * @param string $id
     * @return SignatureInformation
     */
    public function setID(string $id): SignatureInformation
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $referencedSignatureID
     * @return SignatureInformation
     */
    public function setReferencedSignatureID(string $referencedSignatureID): SignatureInformation
    {
        $this->referencedSignatureID = $referencedSignatureID;
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
        $writer->write([
            [Schema::CBC . 'ID' => $this->id],
            [Schema::SBC . 'ReferencedSignatureID' => $this->referencedSignatureID]
        ]);
    }
}

<?php

namespace Saleh7\Zatca;

use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class SignatureInformation implements XmlSerializable
{
    private string $id;
    private string $referencedSignatureID;

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


class UBLDocumentSignatures implements XmlSerializable
{

    private $signatureInformation;

    /**
     * @param SignatureInformation $signatureInformation
     * @return UBLDocumentSignatures
     */
    public function setSignatureInformation(SignatureInformation $signatureInformation): UBLDocumentSignatures
    {
        $this->signatureInformation = $signatureInformation;
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
            Schema::SAC . 'SignatureInformation' => $this->signatureInformation
        ]);
    }
}

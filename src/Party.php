<?php

namespace Saleh7\Zatca;

use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class Party implements XmlSerializable
{
    private $partyIdentification;
    private $partyIdentificationId;
    private $postalAddress;
    private $partyTaxScheme;
    private $legalEntity;

    /**
     * @param ?LegalEntity $legalEntity The legal entity of the party
     * @param ?TaxScheme $taxScheme The Tax Scheme Instance
     * @param ?string $partyIdentificationId The Party Identification ID
     * Seller Party Identification (Additional ID) is always required, and it can be
     * - Commercial Registration Number (CRN)
     * - MOMRAH License (MOM)
     * - MHRSD License (MLS)
     * - 700 Number (700)
     * - MISA License (SAG)
     * - Other (OTH)
     * 
     * Buyer Party Identification (required only for B2B if the buyer is not VAT registered)
     * - Tax Identification Number (TIN)
     * - Commercial registration number (CRN)
     * - MOMRAH license (MOM)
     * - MHRSD license (MLS)
     * - 700 Number (700)
     * - MISA license (SAG)
     * - National ID (NAT)
     * - GCC ID (GCC)
     * - Iqama Number (IQA)
     * - Passport ID (PAS)
     * - Other ID (OTH)
     * 
     * @param string|int|null $partyIdentification The Party Identification Number|String
     *    */
    public function __construct(
        LegalEntity $legalEntity = null,
        Address $postalAddress = null,
        PartyTaxScheme $partyTaxScheme = null,
        string $partyIdentificationId = null,
        string|int $partyIdentification = null,
    ) {
        $this->legalEntity = $legalEntity;
        $this->postalAddress = $postalAddress;
        $this->partyTaxScheme = $partyTaxScheme;
        $this->partyIdentification = $partyIdentification;
        $this->partyIdentificationId = $partyIdentificationId;
    }

    /**
     * @param string $partyIdentificationId The Party Identification ID
     * Seller Party Identification (Additional ID) is always required
     * Buyer Party Identification (required only for B2B if the buyer is not VAT registered)
     */
    public function setPartyIdentification(string|int|null $partyIdentification): Party
    {
        $this->partyIdentification = $partyIdentification;
        return $this;
    }

    /**
     * @param string $partyIdentificationId The Party Identification ID
     */
    public function setPartyIdentificationId(string $partyIdentificationId): Party
    {
        $this->partyIdentificationId = $partyIdentificationId;
        return $this;
    }

    /**
     * @param Address $postalAddress
     * @return Party
     */
    public function setPostalAddress(?Address $postalAddress): Party
    {
        $this->postalAddress = $postalAddress;
        return $this;
    }

    /**
     * @param PartyTaxScheme $partyTaxScheme
     * @return Party
     */
    public function setPartyTaxScheme(PartyTaxScheme $partyTaxScheme)
    {
        $this->partyTaxScheme = $partyTaxScheme;
        return $this;
    }

    /**
     * @param LegalEntity $legalEntity
     * @return Party
     */
    public function setLegalEntity(?LegalEntity $legalEntity): Party
    {
        $this->legalEntity = $legalEntity;
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

        if ($this->partyIdentification !== null) {
            $writer->write([
                'name' => Schema::CAC . 'PartyIdentification',
                'value' => [
                    "name" => Schema::CBC . 'ID',
                    "value" => $this->partyIdentification,
                    "attributes" => [
                        "schemeID" => "$this->partyIdentificationId"
                    ]
                ]
            ]);
        }
        // PostalAddress
        if ($this->postalAddress !== null) {
            $writer->write([Schema::CAC . 'PostalAddress' => $this->postalAddress]);
        }
        //partyTaxScheme
        if ($this->partyTaxScheme !== null) {
            $writer->write([Schema::CAC . 'PartyTaxScheme' => $this->partyTaxScheme]);
        }
        // PartyLegalEntity
        if ($this->legalEntity !== null) {
            $writer->write([Schema::CAC . 'PartyLegalEntity' => $this->legalEntity]);
        }
    }
}

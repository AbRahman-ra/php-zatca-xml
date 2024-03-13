<?php

namespace Saleh7\Zatca;

use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;
use InvalidArgumentException;

class PartyTaxScheme implements XmlSerializable
{
    private $companyId;
    private $taxScheme;

    /**
     * Creates a new Party Tax Scheme Tag
     * @param int|string $companyId The VAT Number of the company as a string, must be a 15-digit number starting and ending with 3
     * @param TaxScheme $taxScheme The Tax Scheme Instance
     * @return PartyTaxScheme
     */
    public function __construct(int|string $companyId, TaxScheme $taxScheme)
    {
        if (preg_match('/^3[0-9]{13}3$/', $companyId) === 0) {
            throw new InvalidArgumentException('The Company ID must be a 15-digit number starting and ending with 3');
        }
        $this->companyId = $companyId;
        $this->taxScheme = $taxScheme;
    }

    /**
     * The validate function that is called during xml writing to valid the data of the object.
     *
     * @return void
     * @throws InvalidArgumentException An error with information about required data that is missing to write the XML
     */
    public function validate()
    {
        if ($this->taxScheme === null) {
            throw new InvalidArgumentException('Missing TaxScheme');
        }
    }

    /**
     * The xmlSerialize method is called during xml writing.
     *
     * @param Writer $writer
     * @return void
     */
    public function xmlSerialize(Writer $writer): void
    {
        if ($this->companyId !== null) {
            $writer->write([
                Schema::CBC . 'CompanyID' => $this->companyId
            ]);
        }
        if ($this->taxScheme !== null) {
            $writer->write([
                Schema::CAC . 'TaxScheme' => $this->taxScheme
            ]);
        }
    }
}

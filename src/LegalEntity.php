<?php

namespace Saleh7\Zatca;

use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class LegalEntity implements XmlSerializable
{
    private $registrationName;

    /**
     * @param string $registrationName Company Name
     * @return LegalEntity
     */
    public function __construct(string $registrationName)

    {
        $this->registrationName = $registrationName;
    }

    /**
     * The xmlSerialize method is called during xml writing.
     *
     * @param Writer $writer
     * @return void
     */
    public function xmlSerialize(Writer $writer): void
    {
        if ($this->registrationName !== null) {
            $writer->write([Schema::CBC . 'RegistrationName' => $this->registrationName]);
        }
    }
}

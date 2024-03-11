<?php

namespace Saleh7\Zatca;

use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class UBLExtension implements XmlSerializable
{
    private $extensionUri;
    private $extensionContent;

    public function __construct(
        ExtensionContent $extensionContent,
        string $extensionUri = 'urn:oasis:names:specification:ubl:dsig:enveloped:xades'
    ) {
        $this->extensionContent = $extensionContent;
        $this->extensionUri = $extensionUri;
    }
    /**
     * @return string
     */
    public function getExtensionUri(): string
    {
        return $this->extensionUri;
    }

    /**
     * @param string $extensionUri
     * @return UBLExtension
     */
    public function setExtensionURI(string $extensionUri): UBLExtension
    {
        $this->extensionUri = $extensionUri;
        return $this;
    }

    /**
     * @return ExtensionContent
     */
    public function getExtensionContent(): ExtensionContent
    {
        return $this->extensionContent;
    }

    /**
     * @param ExtensionContent $extensionContent
     * @return UBLExtension
     */
    public function setExtensionContent(ExtensionContent $extensionContent): UBLExtension
    {
        $this->extensionContent = $extensionContent;
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
            [Schema::EXT . 'ExtensionURI' => $this->extensionUri],
            [Schema::EXT . 'ExtensionContent' => $this->extensionContent]
        ]);
    }
}

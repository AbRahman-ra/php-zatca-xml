<?php

namespace Saleh7\Zatca;

use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;
use DateTime;

class Delivery implements XmlSerializable
{
    private $actualDeliveryDate;
    private $latestDeliveryDate;
    private $deliveryLocation;

    /**
     * @param DateTime $actualDeliveryDate The supply beginning Date
     * @param DateTime $latestDeliveryDate The supply end Date - Only for summary invoices
     * @return Delivery
     */
    public function __construct(DateTime $actualDeliveryDate, ?DateTime $latestDeliveryDate)
    {
        $this->actualDeliveryDate = $actualDeliveryDate;
        if ($latestDeliveryDate != null) {
            $this->latestDeliveryDate = $latestDeliveryDate;
        }
        return $this;
    }

    /**
     * @param Address $deliveryLocation
     * @return Delivery
     */
    public function setDeliveryLocation($deliveryLocation): Delivery
    {
        $this->deliveryLocation = $deliveryLocation;
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
        if ($this->latestDeliveryDate != null) {
            $writer->write([
                Schema::CBC . 'LatestDeliveryDate' => $this->latestDeliveryDate
            ]);
        }
        if ($this->actualDeliveryDate != null) {
            $writer->write([
                Schema::CBC . 'ActualDeliveryDate' => $this->actualDeliveryDate
            ]);
        }
        if ($this->deliveryLocation != null) {
            $writer->write([
                Schema::CAC . 'DeliveryLocation' => [Schema::CAC . 'Address' => $this->deliveryLocation]
            ]);
        }
    }
}

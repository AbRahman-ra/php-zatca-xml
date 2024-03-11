<?php

use Saleh7\Zatca\Enums\DocumentType;
use Saleh7\Zatca\Enums\InvoiceSubtype;

include __DIR__ . '/vendor/autoload.php';

// ------------- CONSTANT -------------
// SignatureInformation
$sign = new \Saleh7\Zatca\SignatureInformation();
// UBLDocumentSignatures
$ublDecoment = new \Saleh7\Zatca\UBLDocumentSignatures($sign);
$extensionContent = new \Saleh7\Zatca\ExtensionContent($ublDecoment);
// UBLExtension
$UBLExtension[] = new \Saleh7\Zatca\UBLExtension($extensionContent);
$UBLExtensions = new \Saleh7\Zatca\UBLExtensions($UBLExtension);
$Signature = new \Saleh7\Zatca\Signature();
// ------------- END CONSTANT -------------

// invoice Type
$invoiceType = new \Saleh7\Zatca\InvoiceType('invoice', 'standard');
// $invoiceType
// ->set3rdParty()
// ->setExport()
// ->setNominal()
// ->setSelfBilled();

// Billing Reference (Mandatory in Credit & Debit Notes)
$inType = (new \Saleh7\Zatca\BillingReference('SME00021'));

// Contract Reference
$Contract = (new \Saleh7\Zatca\Contract('15'));


$AdditionalDocumentReferences = [];

$AdditionalDocumentReferences[] = (new \Saleh7\Zatca\AdditionalDocumentReference('ICV', 23));
$AdditionalDocumentReferences[] = (new \Saleh7\Zatca\AdditionalDocumentReference('PIH'));
$AdditionalDocumentReferences[] = (new \Saleh7\Zatca\AdditionalDocumentReference('QR'));

// ---------------STOPPED HERE----------------
// Tax scheme
$taxScheme = (new \Saleh7\Zatca\TaxScheme())
    ->setId("VAT");

$partyTaxScheme = (new \Saleh7\Zatca\PartyTaxScheme())
    ->setTaxScheme($taxScheme)
    ->setCompanyId('311111111101113');

$partyTaxSchemeCustomer = (new \Saleh7\Zatca\PartyTaxScheme())
    ->setTaxScheme($taxScheme);

$address = (new \Saleh7\Zatca\Address())
    ->setStreetName('الامير سلطان')
    ->setBuildingNumber(2322)
    ->setPlotIdentification(2223)
    ->setCitySubdivisionName('الرياض')
    ->setCityName('الرياض | Riyadh')
    ->setPostalZone('23333')
    ->setCountry('SA');

$legalEntity = (new \Saleh7\Zatca\LegalEntity())
    ->setRegistrationName('Acme Widget’s LTD');

$delivery = (new \Saleh7\Zatca\Delivery())
    ->setActualDeliveryDate("2022-09-07")
    ->setLatestDeliveryDate("2022-09-30"); // Only for summary invoices

$supplierCompany = (new \Saleh7\Zatca\Party())
    ->setPartyIdentification("311111111111113")
    ->setPartyIdentificationId("CRN")
    ->setLegalEntity($legalEntity)
    ->setPartyTaxScheme($partyTaxScheme)
    ->setPostalAddress($address);

$supplierCustomer = (new \Saleh7\Zatca\Party())
    ->setPartyIdentification("311111111111113")
    ->setPartyIdentificationId("NAT")
    ->setLegalEntity($legalEntity)
    ->setPartyTaxScheme($partyTaxSchemeCustomer)
    ->setPostalAddress($address);

$clientPaymentMeans = (new \Saleh7\Zatca\PaymentMeans())
    ->setPaymentMeansCode("10");

// Tax Category (S - E - Z - O)
$taxCategory = (new \Saleh7\Zatca\TaxCategory())
    ->setPercent(15)
    ->setTaxScheme($taxScheme);

// Document Level Allowances/Charges
$allowanceCharges = [];
$allowanceCharges[] = (new \Saleh7\Zatca\AllowanceCharge())
    ->setChargeIndicator(false)
    ->setAllowanceChargeReason('discount')
    ->setAmount(0.00)
    ->setTaxCategory($taxCategory);

// Invoice Line Tax Total
$lineTaxTotalOne = (new \Saleh7\Zatca\TaxTotal())
    ->setTaxAmount(0.6);

// Document Tax Total
$taxSubTotal = (new \Saleh7\Zatca\TaxSubTotal())
    ->setTaxableAmount(4)
    ->setTaxAmount(0.6)
    ->setTaxCategory($taxCategory);

$taxTotal = (new \Saleh7\Zatca\TaxTotal())
    ->addTaxSubTotal($taxSubTotal)
    ->setTaxAmount(0.6);

// Document Level Totals
$legalMonetaryTotal = (new \Saleh7\Zatca\LegalMonetaryTotal())
    ->setLineExtensionAmount(4)
    ->setTaxExclusiveAmount(4)
    ->setTaxInclusiveAmount(4.60)
    ->setPrepaidAmount(0)
    ->setPayableAmount(4.60)
    ->setAllowanceTotalAmount(0);

// Invoice Line Item Classified Tax
$classifiedTax = (new \Saleh7\Zatca\ClassifiedTaxCategory())
    ->setPercent(15)
    ->setTaxScheme($taxScheme);

// Invoice Line Product
$productItem = (new \Saleh7\Zatca\Item())
    ->setName('قلم رصاص')
    ->setClassifiedTaxCategory($classifiedTax);
// Invoice Line Price
$price = (new \Saleh7\Zatca\Price())
    ->setUnitCode(\Saleh7\Zatca\UnitCode::UNIT)
    ->setPriceAmount(2);

// Invoice Line tax totals
$lineTaxTotal = (new \Saleh7\Zatca\TaxTotal())
    ->setTaxAmount(0.60)
    ->setRoundingAmount(4.60);

// Invoice Lines array
$invoiceLines = [];
$invoiceLines[] = (new \Saleh7\Zatca\InvoiceLine())
    ->setUnitCode("PCE")
    ->setId(1)
    ->setItem($productItem)
    ->setLineExtensionAmount(4)
    ->setPrice($price)
    ->setTaxTotal($lineTaxTotal)
    ->setInvoicedQuantity(2);


// Invoice object
$invoice = (new \Saleh7\Zatca\Invoice())
    ->setUBLExtensions($UBLExtensions)
    ->setUUID('3cf5ee18-ee25-44ea-a444-2c37ba7f28be')
    ->setId('SME00023')
    ->setIssueDate(new \DateTime())
    ->setIssueTime(new \DateTime())
    ->setInvoiceType($invoiceType)
    ->Signature($Signature)
    ->setContract($Contact)
    // ->setBillingReference($inType)
    ->setAdditionalDocumentReferences($AdditionalDocumentReferences)
    ->setDelivery($delivery)
    ->setAllowanceCharges($allowanceCharges)
    ->setPaymentMeans($clientPaymentMeans)
    ->setTaxTotal($taxTotal)
    ->setInvoiceLines($invoiceLines)
    ->setLegalMonetaryTotal($legalMonetaryTotal)
    ->setAccountingCustomerParty($supplierCustomer)
    ->setAccountingSupplierParty($supplierCompany);

$generatorXml = new \Saleh7\Zatca\GeneratorInvoice();
$outputXML = $generatorXml->invoice($invoice);
header("Content-Type: application/xml; charset=utf-8");
echo $outputXML;

# Code Analysis

## Overview

The following document is discussing the 4 testing files and deriving conclusions about the creating XML invocie functionality, the files are (as from the author):

- `example.php`
- `tests/invoiceTest.php`
- `tests/creditTest.php`
- `tests/debitTest.php`

## Constants

The 4 files have the same snippet of code, we will assume they are constant along all invoices

```php
// SignatureInformation
$sign = (new \Saleh7\Zatca\SignatureInformation)
  ->setReferencedSignatureID("urn:oasis:names:specification:ubl:signature:Invoice")
  ->setID('urn:oasis:names:specification:ubl:signature:1');

// UBLDocumentSignatures
$ublDecoment = (new \Saleh7\Zatca\UBLDocumentSignatures)
  ->setSignatureInformation($sign);

$extensionContent = (new \Saleh7\Zatca\ExtensionContent)
  ->setUBLDocumentSignatures($ublDecoment);

// UBLExtension
$UBLExtension[] = (new \Saleh7\Zatca\UBLExtension)
  ->setExtensionURI('urn:oasis:names:specification:ubl:dsig:enveloped:xades')
  ->setExtensionContent($extensionContent);

$UBLExtensions = (new \Saleh7\Zatca\UBLExtensions)
  ->setUBLExtensions($UBLExtension);

$Signature = (new \Saleh7\Zatca\Signature)
  ->setId("urn:oasis:names:specification:ubl:signature:Invoice")
  ->setSignatureMethod("urn:oasis:names:specification:ubl:dsig:enveloped:xades");
```

## Invoice Type & Subtype

Note: Documents is a better word when talking about any generated XML to omit any confusion, but the official documentation is calling them as invoices. Until now, I am still confused which naming convention should I follow

- Documents can have 4 types
  - Invoice
  - Prepayment Invoice
  - Debit Note
  - Credit Note
- Additionally, they have two subtypes
  - Standard (B2B)
  - Simplified (B2C)

```php
$invoiceType = (new \Saleh7\Zatca\InvoiceType())
  ->setInvoice('standard') // Standard / Simplified
  ->setInvoiceType('Credit'); // Invoice / Debit / Credit
```

## Billing References

Billing References are mandatory for credit & debit notes, they are simply references to already exported invoices

```php
$inType = (new \Saleh7\Zatca\BillingReference())
  ->setId('SME00021');
```

## Contract References

Similar to billing references, contract references are references to specific contracts or agreements among parties. Usually it's most used with standard invoices

```php
$Contract = (new \Saleh7\Zatca\Contract())
  ->setId('15');
```

## Additional Document References

Additional Document References include

- ICV: The serial number of the document
- PIH (Previous Invoice Hash)
- Invoice QR Code (Handled in the Signing Process)

Additional document references shall be in an array of instances

## Tax Schemes

It's important to well visualize this in order to create well defined code

- Each document has 2 parties (supplier & customer)
- Each party has a tax scheme and it's called `Party Tax Scheme`
  - Company ID (vat number)
  - Tax Scheme ID (`VAT`)
- Percentage of tax is determined for each invoice line

```php
$taxScheme = (new \Saleh7\Zatca\TaxScheme())
  ->setId("VAT");

$partyTaxScheme = (new \Saleh7\Zatca\PartyTaxScheme())
  ->setTaxScheme($taxScheme)
  ->setCompanyId('311111111101113');

$partyTaxSchemeCustomer = (new \Saleh7\Zatca\PartyTaxScheme())
  ->setTaxScheme($taxScheme);
```

## Address

```php
$address = (new \Saleh7\Zatca\Address())
  ->setStreetName('الامير سلطان')
  ->setBuildingNumber(2322)
  ->setPlotIdentification(2223) // Never saw in the samples
  ->setCitySubdivisionName('الرياض')
  ->setCityName('الرياض | Riyadh')
  ->setPostalZone('23333')
  ->setCountry('SA');
```

## Party Legal Entity

Party Legal Entity is a part of the Party Information. Party Information include

- Seller Party Identification (Additional ID) is always required, and it can be
  - Commercial Registration Number (CRN)
  - MOMRAH License (MOM)
  - MHRSD License (MLS)
  - 700 Number (700)
  - MISA License (SAG)
  - Other (OTH)
- Buyer Party Identification (required only for B2B if the buyer is not VAT registered)
  - Tax Identification Number (TIN)
  - Commercial registration number (CRN)
  - MOMRAH license (MOM)
  - MHRSD license (MLS)
  - 700 Number (700)
  - MISA license (SAG)
  - National ID (NAT)
  - GCC ID (GCC)
  - Iqama Number (IQA)
  - Passport ID (PAS)
  - Other ID (OTH)

```php
$legalEntity = (new \Saleh7\Zatca\LegalEntity())
  ->setRegistrationName('Acme Widget’s LTD');

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
```

## Delivery (Supply) Date

Delivery date contains two fields

- Actual Delivery Date (Supply Date)
- Latest Delivery Date (Latest Supply Date) (Only for summary invoices)

```php
$delivery = (new \Saleh7\Zatca\Delivery())
  ->setActualDeliveryDate("2022-09-07")
  ->setLatestDeliveryDate("2022-09-30"); // Only for summary invoices
```

## Payment Means (Payment Method)

Payment means shall be represented with a payment means code. Some of the codes are

- 10: Cash
- 20: Cheque
- 54: Credit Card
- 55: Debit Card

[Access the full list of codes](https://unece.org/fileadmin/DAM/trade/untdid/d16b/tred/tred4461.htm)

```php
$clientPaymentMeans = (new \Saleh7\Zatca\PaymentMeans())
  ->setPaymentMeansCode("10");
```

## Tax Category

It's a part of `TaxSubtotal` & `AllowanceCharge` tags

```php
$taxCategory = (new \Saleh7\Zatca\TaxCategory())
  ->setPercent(15)
  ->setTaxScheme($taxScheme);
```

## Document Level Allowances/Charges

```php
$allowanceCharges = [];
$allowanceCharges[] = (new \Saleh7\Zatca\AllowanceCharge())
    ->setChargeIndicator(false)
    ->setAllowanceChargeReason('discount')
    ->setAmount(0.00)
    ->setTaxCategory($taxCategory);
```

## Invoice Lines

Invoice Lines contain 6 main components

- ID
- Invoiced Quantity
- Line Extension Amount (Invoice line net amount === without vat, with line level allowances/charges)
- Tax Total
  - Tax Amount (VAT Amount - Rounded)
  - Rounding Amount (Line Extension With VAT - Rounded)
- Item
  - Name
  - Classified Tax Category
- Price
  - Price Amount (Unit Price without VAT or allowances/charges)
  - Allowance Charge

```php
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
```

## Creating Invoice

Finally, we create the invoice using the following code

```php
$invoice = (new \Saleh7\Zatca\Invoice())
  ->setUBLExtensions($UBLExtensions)
  ->setUUID('3cf5ee18-ee25-44ea-a444-2c37ba7f28be')
  ->setId('SME00023')
  ->setIssueDate(new \DateTime())
  ->setIssueTime(new \DateTime())
  ->setInvoiceType($invoiceType)
  ->Signature($Signature)
  ->setContract($Contact)
  ->setBillingReference($inType)
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
```

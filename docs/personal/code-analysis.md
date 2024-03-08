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
- Invoice QR Code

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
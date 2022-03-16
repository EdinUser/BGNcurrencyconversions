# Currency tool for Bulgarian Lev
This tool can convert sums to Bulgarian Lev. The courses are read from the Bulgarian National Bank site.
It also can return an array with conversions for a list of currencies.

## Usage
### As a single converter
Convert 100 000 US Dollars to Bulgarian Lev
```php
<?php

use Fallenangelbg\BGNCurrencyTool\currencyConvertor;
use Fallenangelbg\BGNCurrencyTool\currencyReadExternal;

$currencyData = (new currencyReadExternal())->readCurrency();
$sumToBeConverted = 100000;
$sumCurrency = "USD";

$currencyConverterTool = new currencyConvertor(array(), $currencyData);
$convertToLev = $currencyConverterTool->convertToLev($sumToBeConverted, $sumCurrency);
if(!empty($convertToLev['error'])){
    die($convertToLev['error']);
}
var_dump($convertToLev);
```
Result:
```php 
array(2) {
  ["error"]=>
  string(0) ""
  ["sum"]=>
  float(177947.98)
}
```
### As a mass converter
```php
<?php
require __DIR__ . '/../vendor/autoload.php';

use Fallenangelbg\BGNCurrencyTool\currencyConvertor;
use Fallenangelbg\BGNCurrencyTool\currencyReadExternal;

$usedCurrencies = array(
  "BGN" => "BGN",
  "USD" => "USD",
  "EUR" => "EUR",
);

$currencyData = (new currencyReadExternal())->readCurrency();
$sumToBeConverted = 100000;
$sumCurrency = "USD";

$currencyConverterTool = new currencyConvertor($usedCurrencies, $currencyData);

$convertedCurrencies = $currencyConverterTool->currencyCalculate($convertToLev['sum']);
var_dump($convertedCurrencies);
```
Result
```php
array(3) {
  ["BGN"]=>
  float(177947.98)
  ["USD"]=>
  float(100000)
  ["EUR"]=>
  float(90983.02)
}
```
## External read
The tool read a predefined XML file from the site of the Bulgarian National Bank.
``` 
https://www.bnb.bg/Statistics/StExternalSector/StExchangeRates/StERForeignCurrencies/index.htm?download=xml&amp;search=&amp;lang=BG
```
Its good to do this via a cron job. Here's an example of SQL table to have the results saved:
```sql
CREATE TABLE `currency` (
    `currency_id` smallint(2) NOT NULL,
    `currency_code` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
    `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `quantity` mediumint(4) NOT NULL,
    `value` float NOT NULL,
    `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
```
Later, you can do a read to this table to get an array simillar to this one:
```php
$returnArray["BGN"]['name'] = "Bulgarian Lev";
$returnArray["BGN"]['code'] = "BGN";
$returnArray["BGN"]['quantity'] = "1";
$returnArray["BGN"]['value'] = 1;
```
or you can use some cache do read it once a day - Memcached, REDIS, etc.
It's important to keep the array in the given format.
## Example
In /example folder you can find the mentioned examples.
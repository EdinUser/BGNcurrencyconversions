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

$BNBPage = "https://www.bnb.bg/Statistics/StExternalSector/StExchangeRates/StERForeignCurrencies/index.htm?download=xml&amp;search=&amp;lang=BG";
$currencyData = array();
try {
    $currencyData = (new currencyReadExternal($BNBPage))->readCurrency();
}
catch (Exception $e) {
    die("Error occurred!" . $e);
}
$sumToBeConverted = 100000;
$sumCurrency = "USD";

$currencyConverterTool = new currencyConvertor($usedCurrencies, $currencyData);
try {
    $convertToLev = $currencyConverterTool->convertToLev($sumToBeConverted, $sumCurrency);
    var_dump($convertToLev);
}
catch (Exception $e) {
    die($e);
}
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
use Fallenangelbg\BGNCurrencyTool\currencyConvertor;
use Fallenangelbg\BGNCurrencyTool\currencyReadExternal;

$usedCurrencies = array(
  "BGN" => "BGN",
  "USD" => "USD",
  "EUR" => "EUR",
);

$currencyData = array();
try {
    $currencyData = (new currencyReadExternal($BNBPage))->readCurrency();
}
catch (Exception $e) {
    die("Error occurred!" . $e);
}
$sumToBeConverted = 100000;
$sumCurrency = "USD";

$currencyConverterTool = new currencyConvertor($usedCurrencies, $currencyData);

try {
    $convertedCurrencies = $currencyConverterTool->currencyCalculate($convertToLev['sum']);
    var_dump($convertedCurrencies);
}
catch (Exception $e) {
    die($e);
}
```
Result:
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
The reader only works like this:
```php
<?php
use Fallenangelbg\BGNCurrencyTool\currencyReadExternal;

$BNBPage = "https://www.bnb.bg/Statistics/StExternalSector/StExchangeRates/StERForeignCurrencies/index.htm?download=xml&amp;search=&amp;lang=BG";
$currencyData = (new currencyReadExternal($BNBPage))->readCurrency();
// Result:
Array
(
    ['EUR'] => Array
        (
            ['name'] => 'Euro'
            ['code'] => 'EUR'
            ['quantity'] => 1
            ['value'] => 0.51129
        )

    ['BGN'] => Array
        (
            ['name'] => 'Bulgarian Lev'
            ['code'] => 'BGN'
            ['quantity'] => 1
            ['value'] => 1
        )

    ['AUD'] => Array
        (
            ['name'] => 'Австралийски долар'
            ['code'] => 'AUD'
            ['quantity'] => 1
            ['value'] => 0.769752
        )

    ['BRL'] => Array
        (
            ['name'] => 'Бразилски реал'
            ['code'] => 'BRL'
            ['quantity'] => 10
            ['value'] => 2.88057
        )
        /* ... */
    ['USD'] => Array
        (
            ['name'] => 'Щатски долар'
            ['code'] => 'USD'
            ['quantity'] => 1
            ['value'] => 0.565029
        )

    ['ZAR'] => Array
        (
            ['name'] => 'Южноафрикански ранд'
            ['code'] => 'ZAR'
            ['quantity'] => 10
            ['value'] => 8.45094
        )        
)
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
Later, you can do a read to this table to get an array similar to this one:
```php
$returnArray["BGN"]['name'] = "Bulgarian Lev";
$returnArray["BGN"]['code'] = "BGN";
$returnArray["BGN"]['quantity'] = 1;
$returnArray["BGN"]['value'] = 1;
```
or you can use some cache do read it once a day - Memcached, REDIS, etc.
It's important to keep the array in the given format.
## Example
In [example](/example) folder you can find the mentioned examples.

## List of currencies
The list of currencies, supported by this tool (and Bulgarian National Bank)

| Currency              |           CODE            | Quantity |
|-----------------------|:-------------------------:| --------:|
| Australian Dollar     |            AUD            | 1        |
| Brazilian Real        |            BRL            | 10       |
| Canadian Dollar       |            CAD            | 1 |
| Swiss Franc           |            CHF            | 1 |
| Chinese Yuan Renminbi |            CNY            | 10 |
| Czech Koruna          |            CZK            | 100 |
| Danish Krone          |            DKK            | 10 |
| British Pound         |            GBP            | 1 |
| Hong Kong Dollar      |            HKD            | 10 |
| Croatian Kuna         |            HRK            | 10 |
| Hungarian Forint      |            HUF            | 1000 |
| Indonesian Rupiah     |            IDR            | 10000 |
| New Israel Shekel     |            ILS            | 10 |
| Indian Rupee          |            INR            | 100 |
| Icelandic Krona       |            ISK            | 100 |
| Japanese Yen          |            JPY            | 100 |
| South Korean Won      |            KRW            | 1000 |
| Mexican Peso          |            MXN            | 100 |
| Malaysian Ringgit     |            MYR            | 10 |
| Norwegian Krone       |            NOK            | 10  | 
| New Zealand Dollar    |            NZD            | 1 | 
| Philippine Peso       | PHP | 100 |
| Polish Zloty          | PLN | 10 |
| Romanian Leu          | RON | 10 |
| Russian Rouble        | RUB  | * |
| Swedish Krona         | SEK |10 | 
| Singaporean Dollar    | SGD |1 | 
| Thai Baht             |            THB            | 100 |
| Turkish Lira          |            TRY            | 10 |
| US Dollar             |            USD            | 1 |
| South African Rand    |            ZAR            | 10 |
Russian Ruble is not used at the moment due to the political situation.
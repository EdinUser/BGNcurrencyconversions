<?php
declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

use Fallenangelbg\BGNCurrencyTool\currencyConvertor;
use Fallenangelbg\BGNCurrencyTool\currencyReadExternal;

$usedCurrencies = array(
  "BGN" => "BGN",
  "USD" => "USD",
  "EUR" => "EUR",
);

// This is the current page to read from
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

try {
    $convertedCurrencies = $currencyConverterTool->currencyCalculate($convertToLev['sum']);
    var_dump($convertedCurrencies);
}
catch (Exception $e) {
    die($e);
}

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
$convertToLev = $currencyConverterTool->convertToLev($sumToBeConverted, $sumCurrency);
if(!empty($convertToLev['error'])){
    die($convertToLev['error']);
}
var_dump($convertToLev);

$convertedCurrencies = $currencyConverterTool->currencyCalculate($convertToLev['sum']);
var_dump($convertedCurrencies);

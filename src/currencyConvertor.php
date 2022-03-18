<?php

namespace Fallenangelbg\BGNCurrencyTool;

use Exception;

class currencyConvertor
{
    /**
     * @var array
     */
    private $usedCurrencies;
    /**
     * @var array
     */
    private $currencyData;

    public function __construct($usedCurrencies, $currencyData)
    {
        $this->usedCurrencies = $usedCurrencies;
        $this->currencyData = $currencyData;
    }

    /**
     * @param int    $sum      The amount to be converted to  BGN
     * @param string $currency From which currency it should be converted
     *
     * @return array
     * @throws Exception
     */
    function convertToLev(int $sum = 0, string $currency = ''): array
    {
        $currencyValue = $calculatedSum = 0.0;
        $currencyArray = $this->currencyData;
        $error = "";
        foreach ($currencyArray as $currencyCode => $currencyDetail) {
            if ($currencyCode === $currency) {
                $currencyValue = $currencyDetail['value'];
            }
        }

        if ($currencyValue <= 0) {
            throw new Exception("Converter error: No matching currency found!");
        } else {
            if (is_array($sum)) {
                $calculatedSum = round((current($sum) / $currencyValue), 2);
            } else {
                $calculatedSum = round(($sum / $currencyValue), 2);
            }
        }

        return array('error' => $error, 'sum' => $calculatedSum);
    }

    /**
     * @param float  $priceToCalculate
     * @param string $currencyCode
     *
     * @return array
     * @throws Exception
     */
    function currencyCalculate(float $priceToCalculate = 0, string $currencyCode = "BGN"): array
    {
        $returnCalculations = array();
        if ($currencyCode !== "BGN") {
            $convertToLev = $this->convertToLev($priceToCalculate, $currencyCode);
            if (!empty($convertToLev['error'])) {
                throw new Exception("Converter error: " . $convertToLev['error']);
            }
            $priceToCalculate = $convertToLev['sum'];
        }

        foreach ($this->usedCurrencies as $usedCurrency => $usedCurrencyData) {
            $calculateCurrencies = $this->currencyData[$usedCurrency]['value'];

            if ($calculateCurrencies != 1) {
                $newPrice = $priceToCalculate * $calculateCurrencies;
                $newPrice = round($newPrice, 2);
            } else {
                $newPrice = round($priceToCalculate, 2);
            }
            $returnCalculations[$usedCurrency] = $newPrice;
        }

        return $returnCalculations;
    }
}
<?php

namespace Fallenangelbg\BGNCurrencyTool;

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
     */
    function convertToLev(int $sum = 0, string $currency = ''): array
    {
        $currencyValue = $calculatedSum = 0.0;
        $currencyArray = $this->currencyData;
        $error = "";
        foreach ($currencyArray as $currencyCode => $currencyDetail) {
            if ($currencyCode === $currency) {
                $currencyValue = $currencyDetail['value'] / $currencyDetail['quantity'];
            }
        }

        if ($currencyValue <= 0) {
            $error = "No currency found!";
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
     */
    function currencyCalculate(float $priceToCalculate = 0, string $currencyCode = "BGN"): array
    {
        $returnCalculations = array();
        if ($currencyCode !== "BGN") {
            $convertToLev = $this->convertToLev($priceToCalculate, $currencyCode);
            if (!empty($convertToLev['error'])) {
                die($convertToLev['error']);
            }
            $priceToCalculate = $convertToLev['sum'];
        }

        foreach ($this->usedCurrencies as $usedCurrency => $usedCurrencyData) {
            $calculateCurrencies = $this->currencyData[$usedCurrency]['value'];

            if ($calculateCurrencies != 1) {
                $newPrice = $priceToCalculate * ($calculateCurrencies / $this->currencyData[$usedCurrency]['quantity']);
                $newPrice = round($newPrice, 2);
            } else {
                $newPrice = round($priceToCalculate, 2);
            }
            $returnCalculations[$usedCurrency] = $newPrice;
        }

        return $returnCalculations;
    }
}
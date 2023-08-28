<?php

namespace App\Service\Math;

/**
 * Calculator
 */
class Calculator
{
    /**
     * Validate number
     * 
     * @param string $number
     * @return bool
     */
    public function validate(string $number): bool
    {
        return (bool)preg_match('~^-?(?:0|[1-9]\\d*)?(?:\\.\\d*)?$~', $number);
    }

    /**
     * Ceiling with precision
     * 
     * @param string $number
     * @param int $precision
     * @return string
     * 
     * @throws \Exception
     */
    public function ceiling(string $number, int $precision): string
    {
        if (!$this->validate($number)) {
            throw new \Exception('Invalid number.');
        }

        $separatorPosition = strpos($number, '.');

        if ($separatorPosition === false && $precision > 0) {
            return sprintf('%s.%s', $number, str_repeat('0', $precision));
        }

        list($integerPart, $decimalPart) = explode('.', $number);
        $decimalLength = strlen($decimalPart);

        if ($decimalLength <= $precision) {
            return $number . str_repeat('0', $precision - $decimalLength);
        }

        $needIncrement = rtrim(substr($decimalPart, $precision), '0') !== '';
        $decimalPart = substr($decimalPart, 0, $precision);

        if (!$needIncrement) {
            return sprintf('%s.%s', $integerPart, $decimalPart);
        }

        $decimalPart = (string)((int)$decimalPart + 1);

        if (strlen($decimalPart) > $precision) {
            $isNegative = $integerPart[0] === '-';
            $integerPart = $isNegative ? substr($integerPart, 1) : $integerPart;
            $integerPart = (string)((int)$integerPart + 1);
            $integerPart = $isNegative ? "-$integerPart" : $integerPart;
            $decimalPart = str_repeat('0', $precision);
        }

        return sprintf('%s.%s', $integerPart, $decimalPart);
    }
}
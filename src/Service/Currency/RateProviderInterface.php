<?php

namespace App\Service\Currency;

/**
 * Basic rate provider interface
 */
interface RateProviderInterface
{
    /**
     * Get exchange rate by currency code
     * 
     * @param string $currencyCode
     * @return string
     */
    public function getRate(string $currecyCode): string;
}
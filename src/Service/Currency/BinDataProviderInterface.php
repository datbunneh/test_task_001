<?php

namespace App\Service\Currency;

/**
 * Basic bin data provider interface
 */
interface BinDataProviderInterface
{
    /**
     * Get country code by bin
     * 
     * @param string $bin
     * @return string
     */
    public function getCountryCode(string $bin): string;
}
<?php

namespace App\Service\Currency;

/**
 * Abstract rate provider with caching
 */
abstract class AbstractRateProvider implements RateProviderInterface
{
    /**
     * @var array
     */
    private array $parameters;

    /**
     * @var array
     */
    private array $cachedData;

    /**
     * Request data from the provider
     * 
     * @return array
     */
    abstract protected function requestData(): array;

    /**
     * Extract rate value from stored cache
     * 
     * @param string $currencyCode
     * @return string
     */
    abstract protected function extractRate(string $currencyCode): string;

    /**
     * @param array $parameters
     * @return void
     */
    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
    }

    /**
     * @inheritDoc
     */
    public function getRate(string $currencyCode): string
    {
        if (!isset($this->cachedData)) {
            $this->cachedData = $this->requestData();
        }

        return $this->extractRate($currencyCode);
    }

    /**
     * Get parameters
     * 
     * @return array
     */
    protected function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * Get cached data
     * 
     * @return array
     */
    protected function getCachedData(): array
    {
        return $this->cachedData;
    }
}
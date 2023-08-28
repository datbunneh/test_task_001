<?php

namespace App\Service\Currency;

use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Exchange rate provider from "http://api.exchangeratesapi.io"
 */
class ExchangeRatesApiRateProvider extends AbstractRateProvider
{
    /**
     * @param HttpClientInterface
     */
    private HttpClientInterface $httpClient;

    /**
     * @param HttpClientInterface
     * @param array
     */
    public function __construct(HttpClientInterface $httpClient, array $parameters = [])
    {
        $this->httpClient = $httpClient;

        parent::__construct($parameters);
    }

    /**
     * @inheritDoc
     * 
     * @throws \Exception
     */
    protected function requestData(): array
    {
        $parameters = $this->getParameters();

        if (!isset($parameters['accessKey'])) {
            throw new \Exception('Access key is required.');
        }

        if (!isset($parameters['baseCurrency'])) {
            throw new \Exception('Base currency is required.');
        }

        $data = $this->httpClient->request(
            'GET',
            'http://api.exchangeratesapi.io/latest',
            [
                'query' => [
                    'access_key' => $parameters['accessKey'],
                    'base' => $parameters['baseCurrency']
                ]
            ]
        )->toArray();

        if (!is_array($data) || !isset($data['rates']) || !is_array($data['rates'])) {
            throw new \Exception('Invalid response.');
        }

        return $data;
    }

    /**
     * @inheritDoc
     * 
     * @throws \Exception
     */
    protected function extractRate(string $currencyCode): string
    {
        $cachedData = $this->getCachedData();

        if (!isset($cachedData['rates'][$currencyCode])) {
            throw new \Exception('Invalid currency code.');
        }

        return (string)$cachedData['rates'][$currencyCode];
    }
}
<?php

namespace App\Service\Currency;

use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Bin data provider from "https://lookup.binlist.net"
 */
class BinListBinDataProvider implements BinDataProviderInterface
{
    /**
     * @param HttpClientInterface
     */
    private HttpClientInterface $httpClient;

    /**
     * @param HttpClientInterface
     */
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @inheritDoc
     * 
     * @throws \Exception
     */
    public function getCountryCode(string $bin): string
    {
        $data = $this->httpClient->request('GET', "https://lookup.binlist.net/$bin")->toArray();

        if (!is_array($data) || !isset($data['country']['alpha2'])) {
            throw new \Exception('Invalid response.');
        }

        return $data['country']['alpha2'];
    }
}
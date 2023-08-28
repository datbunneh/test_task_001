<?php

declare(strict_types = 1);

use Symfony\Component\HttpClient\CurlHttpClient;
use App\Application;
use App\Service\Math\Calculator;
use App\Service\Config\Provider;
use App\Service\Config\Reader;
use App\Service\Currency\BinListBinDataProvider;
use App\Service\Currency\ExchangeRatesApiRateProvider;

require_once __DIR__ . '/vendor/autoload.php';

$httpClient = new CurlHttpClient();
$configReader = Provider::getGlobalReader();
$application = new Application(
    new Calculator(),
    Provider::getGlobalReader(),
    new BinListBinDataProvider($httpClient),
    new ExchangeRatesApiRateProvider(
        $httpClient,
        [
            'accessKey' => $configReader->get('exchageRatesApiAccessKey'),
            'baseCurrency' => $configReader->get('baseCurrency')
        ]
    )
);

echo $application->execute(...array_slice($argv, 1));
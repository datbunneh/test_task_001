<?php

namespace App;

use App\Service\Math\Calculator;
use App\Service\Config\Reader;
use App\Service\Currency\BinDataProviderInterface;
use App\Service\Currency\RateProviderInterface;

/**
 * Application
 */
class Application
{
    /**
     * @var Calculator
     */
    private Calculator $calculator;

    /**
     * @var Reader
     */
    private Reader $configReader;

    /**
     * @var BinDataProviderInterface
     */
    private BinDataProviderInterface $binDataProvider;

    /**
     * @var RateProviderInterface
     */
    private RateProviderInterface $rateProvider;

    /**
     * @param Calculator $calculator
     * @param Reader $configReader
     * @param BinDataProviderInterface $binDataProvider
     * @param RateProviderInterface $rateProvider
     */
    public function __construct(
        Calculator $calculator,
        Reader $configReader,
        BinDataProviderInterface $binDataProvider,
        RateProviderInterface $rateProvider
    ) {
        $this->calculator = $calculator;
        $this->configReader = $configReader;
        $this->binDataProvider = $binDataProvider;
        $this->rateProvider = $rateProvider;
    }

    /**
     * Execute application
     * 
     * @param string $filePath
     * @return string
     * 
     * @throws \Exception
     */
    public function execute(string $filePath): string
    {
        if (!file_exists($filePath)) {
            throw new \Exception('Input file does not exist.');
        }

        $fileLines = explode("\n", file_get_contents($filePath));
        $output = '';

        foreach ($fileLines as $fileLine) {
            $data = json_decode($fileLine, true);

            if (!is_array($data)
                || !isset($data['bin'])
                || !isset($data['amount'])
                || !isset($data['currency'])
            ) {
                throw new \Exception('Input file format is incorrect.');
            }

            $calculationPrecision = $this->configReader->get('calculationPrecision');
            $rate = $this->rateProvider->getRate($data['currency']);
            $amountFixed = $data['currency'] === $this->configReader->get('baseCurrency') || $rate === 0
                ? $data['amount']
                : bcdiv($data['amount'], $rate, $calculationPrecision);

            $output .= $this->calculator->ceiling(
                bcmul(
                    $amountFixed,
                    in_array($this->binDataProvider->getCountryCode($data['bin']), $this->configReader->get('euCodes'))
                        ? $this->configReader->get('euRate')
                        : $this->configReader->get('nonEuRate'),
                    $calculationPrecision
                ),
                $this->configReader->get('outputPrecision')
            );
            $output .= "\n";
        }

        return $output;
    }
}
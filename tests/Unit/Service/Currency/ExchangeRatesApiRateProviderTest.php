<?php

namespace App\Tests\Unit\Service\Currency;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use App\Service\Currency\ExchangeRatesApiRateProvider;

class ExchangeRatesApiRateProviderTest extends TestCase
{
    /**
     * @var HttpClientInterface|MockObject
     */
    private HttpClientInterface|MockObject $httpClientMock;

    /**
     * @var ResponseInterface|MockObject
     */
    private ResponseInterface|MockObject $responseMock;

    public function testGetRate(): void
    {
        $currencyCode = 'USD';
        $rate = 1.33;

        $this->responseMock->expects($this->once())
            ->method('toArray')
            ->willReturn(['rates' => [$currencyCode => $rate]]);

        $rateProvider = $this->getExchangeRatesApiRateProvider(
            ['accessKey' => 'fakeKey', 'baseCurrency' => 'fakeCurrency']
        );

        $this->assertSame((string)$rate, $rateProvider->getRate($currencyCode));
    }

    public function testGetRateInvalidCurrencyCode(): void
    {
        $this->responseMock->expects($this->once())
            ->method('toArray')
            ->willReturn(['rates' => ['USD' => 5.67]]);

        $rateProvider = $this->getExchangeRatesApiRateProvider(
            ['accessKey' => 'fakeKey', 'baseCurrency' => 'fakeCurrency']
        );

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid currency code.');

        $rateProvider->getRate('GBP');
    }

    public function testGetRateInvalidResponse(): void
    {
        $this->responseMock->expects($this->once())
            ->method('toArray')
            ->willReturn([]);

        $rateProvider = $this->getExchangeRatesApiRateProvider(
            ['accessKey' => 'fakeKey', 'baseCurrency' => 'fakeCurrency']
        );

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid response.');

        $rateProvider->getRate('GBP');
    }

    public function testGetRateBaseCurrencyMissing(): void
    {
        $rateProvider = $this->getExchangeRatesApiRateProvider(['accessKey' => 'fakeKey']);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Base currency is required.');

        $rateProvider->getRate('GBP');
    }

    public function testGetRateAccessKeyMissing(): void
    {
        $rateProvider = $this->getExchangeRatesApiRateProvider([]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Access key is required.');

        $rateProvider->getRate('GBP');
    }

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->responseMock = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $this->httpClientMock = $this->getMockBuilder(HttpClientInterface::class)->getMock();

        $this->httpClientMock->expects($this->any())
            ->method('request')
            ->willReturn($this->responseMock);
    }

    /**
     * @param array $parameters
     * @return ExchangeRatesApiRateProvider
     */
    private function getExchangeRatesApiRateProvider(array $parameters): ExchangeRatesApiRateProvider
    {
        return new ExchangeRatesApiRateProvider($this->httpClientMock, $parameters);
    }
}
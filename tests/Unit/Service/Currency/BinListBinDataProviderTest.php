<?php

namespace App\Tests\Unit\Service\Currency;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use App\Service\Currency\BinListBinDataProvider;

class BinListBinDataProviderTest extends TestCase
{
    /**
     * @var HttpClientInterface|MockObject
     */
    private HttpClientInterface|MockObject $httpClientMock;

    /**
     * @var ResponseInterface|MockObject
     */
    private ResponseInterface|MockObject $responseMock;

    /**
     * @var BinListBinDataProvider
     */
    private BinListBinDataProvider $dataProvider;

    public function testGetCountryCode(): void
    {
        $countryCode = 'FK';

        $this->responseMock->expects($this->once())
            ->method('toArray')
            ->willReturn(['country' => ['alpha2' => $countryCode]]);

        $this->assertSame($countryCode, $this->dataProvider->getCountryCode('12345'));
    }

    public function testGetCountryCodeInvalidResponse(): void
    {
        $this->responseMock->expects($this->once())
            ->method('toArray')
            ->willReturn([]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid response.');

        $this->dataProvider->getCountryCode('12345');
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

        $this->dataProvider = new BinListBinDataProvider($this->httpClientMock);
    }
}
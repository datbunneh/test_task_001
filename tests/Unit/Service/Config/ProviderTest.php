<?php

namespace App\Tests\Unit\Service\Config;

use PHPUnit\Framework\TestCase;
use App\Service\Config\Provider;
use App\Service\Config\Reader;

class ProviderTest extends TestCase
{
    public function testGetGlobalReader(): void
    {
        $this->assertInstanceOf(Reader::class, Provider::getGlobalReader());
    }
}
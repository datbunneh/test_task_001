<?php

namespace App\Tests\Unit\Service\Config;

use PHPUnit\Framework\TestCase;
use App\Service\Config\Reader;

class ReaderTest extends TestCase
{
    public function testGet(): void
    {
        $reader = new Reader(__DIR__ . '/fixtures/config.yaml');

        $this->assertSame('valid', $reader->get('testValue'));
    }

    public function testGetFileNotExists(): void
    {
        $filePath = __DIR__ . '/fixtures/config_invalid.yaml';

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Config file '$filePath' does not exist.");

        new Reader($filePath);
    }

    public function testGetWrongValue(): void
    {
        $key = 'testValueInvalid';
        $reader = new Reader(__DIR__ . '/fixtures/config.yaml');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Specified key '$key' is not present inside the config.");

        $reader->get($key);
    }
}
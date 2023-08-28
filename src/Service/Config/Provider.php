<?php

namespace App\Service\Config;

/**
 * Config provider
 */
class Provider
{
    private const GLOBAL_READER_PATH = __DIR__ . '/../../../config/config.yaml';

    /**
     * Global config reader
     */
    static private Reader $globalReader;

    /**
     * Get global config reader
     * 
     * @return Reader
     */
    static public function getGlobalReader(): Reader
    {
        if (!isset(self::$globalReader)) {
            self::$globalReader = new Reader(self::GLOBAL_READER_PATH);
        }

        return self::$globalReader;
    }
}
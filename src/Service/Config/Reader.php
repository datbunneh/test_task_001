<?php

namespace App\Service\Config;

use Symfony\Component\Yaml\Yaml;

/**
 * Config reader
 */
class Reader
{
    /**
     * @var array
     */
    private array $config;

    /**
     * @param string $filePath
     * 
     * @throws \Exception
     */
    public function __construct(string $filePath)
    {
        if (!is_file($filePath)) {
            throw new \Exception("Config file '$filePath' does not exist.");
        }

        $this->config = Yaml::parse(file_get_contents($filePath));
    }

    /**
     * Get config value
     * 
     * @param string $key
     * @return mixed
     * 
     * @throws \Exception
     */
    public function get(string $key): mixed
    {
        if (!array_key_exists($key, $this->config)) {
            throw new \Exception("Specified key '$key' is not present inside the config.");
        }

        return $this->config[$key];
    }
}
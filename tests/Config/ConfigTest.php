<?php

use Keboola\Component\Config\BaseConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class BaseConfigTest extends TestCase
{

    public function testLoadValidConfig() : void
    {
        $config = new Keboola\Component\Config\BaseConfig(
            json_decode(file_get_contents(__DIR__ . '/fixtures/config_valid.json'), true),
            new \Keboola\OneDriveExtractor\ConfigDefinition()
        );

        $this->assertInstanceOf(BaseConfig::class, $config);

        $this->assertEquals('__id', $config->getValue(['parameters', 'id']));
        $this->assertEquals('__output', $config->getValue(['parameters', 'output']));
    }

    public function testLoadConfigWithMissingId() : void
    {
        $this->expectException(InvalidConfigurationException::class);

        new Keboola\Component\Config\BaseConfig(
            json_decode(file_get_contents(__DIR__ . '/fixtures/config_missing_id.json'), true),
            new \Keboola\OneDriveExtractor\ConfigDefinition()
        );
    }

    public function testLoadConfigWithMissingOutput() : void
    {
        $this->expectException(InvalidConfigurationException::class);

        new Keboola\Component\Config\BaseConfig(
            json_decode(file_get_contents(__DIR__ . '/fixtures/config_missing_id.json'), true),
            new \Keboola\OneDriveExtractor\ConfigDefinition()
        );
    }
}

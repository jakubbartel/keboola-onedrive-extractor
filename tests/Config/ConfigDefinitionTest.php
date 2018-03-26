<?php

namespace Keboola\OneDriveExtractor\Tests\Config;

use Keboola\Component;
use Keboola\OneDriveExtractor;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class ConfigDefinitionTest extends TestCase
{

    public function testLoadValidConfig() : void
    {
        $config = new Component\Config\BaseConfig(
            json_decode(file_get_contents(__DIR__ . '/fixtures/config_valid.json'), true),
            new OneDriveExtractor\ConfigDefinition()
        );

        $this->assertInstanceOf(Component\Config\BaseConfig::class, $config);

        $this->assertEquals('_id_', $config->getValue(['parameters', 'id']));
    }

    public function testLoadConfigWithMissingId() : void
    {
        $this->expectException(InvalidConfigurationException::class);

        new Component\Config\BaseConfig(
            json_decode(file_get_contents(__DIR__ . '/fixtures/config_missing_id.json'), true),
            new OneDriveExtractor\ConfigDefinition()
        );
    }
}

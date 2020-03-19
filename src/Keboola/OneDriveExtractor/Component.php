<?php

declare(strict_types=1);

namespace Keboola\OneDriveExtractor;

use League\Flysystem;
use Keboola\Component\BaseComponent;

class Component extends BaseComponent
{
    protected function getConfigDefinitionClass(): string
    {
        return ConfigDefinition::class;
    }

    public function initExtractor(): Extractor
    {
        $adapter = new FlySystem\Adapter\Local(sprintf('%s%s', $this->getDataDir(), '/out/files'));
        $fileSystem = new Flysystem\Filesystem($adapter);

        return new Extractor(
            $this->getConfig()->getOAuthApiAppKey(),
            $this->getConfig()->getOAuthApiAppSecret(),
            $this->getConfig()->getOAuthApiData(),
            $fileSystem
        );
    }

    public function run(): void
    {
        $extractor = $this->initExtractor();
        $fileParameters = $this->getConfig()->getParameters();
        $extractor->extractFile($fileParameters['id']);
    }
}

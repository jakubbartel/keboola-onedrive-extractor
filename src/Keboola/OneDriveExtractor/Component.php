<?php

namespace Keboola\OneDriveExtractor;

use League\Flysystem;
use Keboola\Component\BaseComponent;

class Component extends BaseComponent
{

    /**
     * @return string
     */
    protected function getConfigDefinitionClass(): string
    {
        return ConfigDefinition::class;
    }

    /**
     * @return Extractor
     */
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

    /**
     * @throws MicrosoftGraphApi\Exception\MissingDownloadUrl intentionally treat this as application
     *         exception so this error is alerted to developer
     * @throws Exception\UserException
     */
    public function run() : void
    {
        $extractor = $this->initExtractor();

        $fileParameters = $this->getConfig()->getParameters();

        $extractor->extractFile($fileParameters['id']);
    }

}

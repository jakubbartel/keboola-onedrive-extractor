<?php declare(strict_types = 1);

namespace Keboola\OneDriveExtractor;

use League\Flysystem;

class Extractor
{

    /**
     * @var Component
     */
    private $keboolaComponent;

    /**
     * @var MicrosoftGraphApi\OAuthProvider
     */
    private $provider;

    /**
     * @var MicrosoftGraphApi\Api
     */
    private $api;

    /**
     * Extractor constructor.
     */
    public function __construct()
    {
        $this->keboolaComponent = new Component();

        $this->initOAuthProvider();
        $this->initOAuthProviderAccessToken();
        $this->initApi();
    }

    /**
     * @return Extractor
     */
    private function initOAuthProviderAccessToken() : self
    {
        $data = $this->keboolaComponent->getConfig()->getOAuthApiData();

        $this->provider->initAccessToken($data);

        return $this;
    }

    /**
     * @return Extractor
     */
    private function initOAuthProvider() : self
    {
        $clientId = $this->keboolaComponent->getConfig()->getOAuthApiAppKey();
        $clientSecret = $this->keboolaComponent->getConfig()->getOAuthApiAppSecret();
        $redirectUri = '';

        $this->provider = new MicrosoftGraphApi\OAuthProvider($clientId, $clientSecret, $redirectUri);

        return $this;
    }

    /**
     * @return Extractor
     */
    private function initApi() : self
    {
        $this->api = new MicrosoftGraphApi\Api($this->provider);

        return $this;
    }

    /**
     * @param string $id input id - can be url to OneDrive file or OneDrive Item Id
     * @param string $output
     * @return MicrosoftGraphApi\File
     */
    private function extractFile(string $id, string $output) : MicrosoftGraphApi\File
    {
        $files = new MicrosoftGraphApi\Files($this->api);

        $id = $files->parseOneDriveItemId($id);

        $fileMetadata = $files->readFileMetadata($id);
        $file = $files->readFile($id);

        $outputName = $output === "" ? $fileMetadata->getOneDriveName() : $output;

        $this->writeFileToOutput($file, $outputName);

        return $file;
    }

    /**
     * @param MicrosoftGraphApi\File $file
     * @param string $output
     * @return Extractor
     */
    private function writeFileToOutput(MicrosoftGraphApi\File $file, string $output) : self
    {
        $outputFilesDir = sprintf('%s%s', $this->keboolaComponent->getDataDir(), '/out/files');

        $adapter = new Flysystem\Adapter\Local($outputFilesDir);
        $fileSystem = new Flysystem\Filesystem($adapter);

        $file->saveToFile($fileSystem, $output);

        return $this;
    }

    /**
     *
     */
    public function run() : void
    {
        $fileParameters = $this->keboolaComponent->getConfig()->getParameters();

        $this->extractFile($fileParameters['id'], $fileParameters['output']);
    }

}

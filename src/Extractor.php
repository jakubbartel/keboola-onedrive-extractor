<?php declare(strict_types = 1);

namespace Keboola\OneDriveExtractor;

use Keboola\Component\BaseComponent;
use League\Flysystem;

class Extractor
{

    /**
     * @var BaseComponent
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
        $this->keboolaComponent = new BaseComponent();

        $this->initOAuthProvider();
        $this->initOAuthProviderAccessToken();
        $this->initApi();
    }

    /**
     * @return Extractor
     */
    private function initOAuthProviderAccessToken() : self
    {
        $data = $this->keboolaComponent->getConfig()->getValue(['authorization', 'oauth_api', 'credentials', '#data']);

        $this->provider->initAccessToken($data);

        return $this;
    }

    /**
     * @return Extractor
     */
    private function initOAuthProvider() : self
    {
        $clientId = $this->keboolaComponent->getConfig()->getValue(['authorization', 'oauth_api', 'id']);
        $clientSecret = $this->keboolaComponent->getConfig()->getValue(['authorization', 'oauth_api', 'credentials', '#appSecret']);
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
     * @param string $id
     * @return MicrosoftGraphApi\File
     */
    private function extractFile(string $id) : MicrosoftGraphApi\File {
        $files = new MicrosoftGraphApi\Files($this->api);

        $file = $files->readFile($id, MicrosoftGraphApi\Files::ONEDRIVE_FILE_TYPE_EXCEL);

        //echo 'File of length ' . strlen($file->getContents()) . ' downloaded' . "\n";

        return $file;
    }

    /**
     * @param MicrosoftGraphApi\File $file
     * @param string $output
     * @return Extractor
     */
    private function writeFileToOutput(MicrosoftGraphApi\File $file, string $output) : self {
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
        $filesConfig = $this->keboolaComponent->getConfig()->getValue(['parameters' ,'oneDriveFiles']);

        foreach($filesConfig as $fileConfig) {
            $file = $this->extractFile($fileConfig['id']);
            $this->writeFileToOutput($file, $fileConfig['output']);
        }
    }

}

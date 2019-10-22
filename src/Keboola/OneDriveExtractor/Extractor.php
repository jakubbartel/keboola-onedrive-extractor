<?php declare(strict_types = 1);

namespace Keboola\OneDriveExtractor;

use League\Flysystem;

class Extractor
{

    /**
     * @var MicrosoftGraphApi\OAuthProvider
     */
    private $provider;

    /**
     * @var MicrosoftGraphApi\Api
     */
    private $api;

    /**
     * @var Flysystem\Filesystem
     */
    private $filesystem;

    /**
     * Extractor constructor.
     *
     * @param string $oAuthAppId
     * @param string $oAuthAppSecret
     * @param string $oAuthData serialized data returned by oAuth API
     * @param Flysystem\Filesystem $filesystem
     */
    public function __construct(
        string $oAuthAppId,
        string $oAuthAppSecret,
        string $oAuthData,
        Flysystem\Filesystem $filesystem
    ) {
        $this->filesystem = $filesystem;

        $this->initOAuthProvider($oAuthAppId, $oAuthAppSecret);
        $this->initOAuthProviderAccessToken($oAuthData);
        $this->initApi();
    }

    /**
     * @param string $oAuthData
     * @return Extractor
     */
    private function initOAuthProviderAccessToken(string $oAuthData) : self
    {
        $this->provider->initAccessToken($oAuthData);

        return $this;
    }

    /**
     * @param string $oAuthAppId
     * @param string $oAuthAppSecret
     * @return Extractor
     */
    private function initOAuthProvider(string $oAuthAppId, string $oAuthAppSecret) : self
    {
        $redirectUri = '';

        $this->provider = new MicrosoftGraphApi\OAuthProvider($oAuthAppId, $oAuthAppSecret, $redirectUri);

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
     * @param MicrosoftGraphApi\File $file
     * @param string $filePathname
     * @return Extractor
     */
    private function writeFileToOutput(MicrosoftGraphApi\File $file, string $filePathname) : self
    {
        $file->saveToFile($this->filesystem, $filePathname);

        return $this;
    }

    /**
     * @param string $link url to File on OneDrive or SharePoint
     * @return MicrosoftGraphApi\File
     * @throws Exception\UserException
     * @throws MicrosoftGraphApi\Exception\MissingDownloadUrl
     * @throws \Exception
     */
    public function extractFile(string $link) : MicrosoftGraphApi\File
    {
        $files = new MicrosoftGraphApi\OneDrive($this->api);

        try {
            $fileMetadata = $files->readFileMetadataByLink($link);
            $file = $files->readFile($fileMetadata);
        } catch(MicrosoftGraphApi\Exception\GenerateAccessTokenFailure $e) {
            throw new Exception\UserException('Microsoft OAuth API token refresh failed, please reset authorization for the extractor configuration');
        } catch(MicrosoftGraphApi\Exception\FileCannotBeLoaded | MicrosoftGraphApi\Exception\InvalidSharingUrl $e) {
            throw new Exception\UserException($e->getMessage());
        } catch(MicrosoftGraphApi\Exception\GatewayTimeout $e) {
            throw new Exception\UserException('Microsoft API timeout, rerun to try again');
        } catch(MicrosoftGraphApi\Exception\AccessTokenNotInitialized $e) {
            throw new \Exception(sprintf("Access token not initialized: %s", $e->getMessage()));
        }

        $this->writeFileToOutput($file, $fileMetadata->getOneDriveName());

        return $file;
    }

}

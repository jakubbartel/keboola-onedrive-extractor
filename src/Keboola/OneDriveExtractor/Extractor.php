<?php

declare(strict_types=1);

namespace Keboola\OneDriveExtractor;

use League\Flysystem;

class Extractor
{
    private MicrosoftGraphApi\OAuthProvider $provider;

    private MicrosoftGraphApi\Api $api;

    private Flysystem\Filesystem $filesystem;

    public function __construct(
        string $oAuthAppId,
        string $oAuthAppSecret,
        string $oAuthData, // serialized data returned by oAuth API
        Flysystem\Filesystem $filesystem
    ) {
        $this->filesystem = $filesystem;

        $this->initOAuthProvider($oAuthAppId, $oAuthAppSecret);
        $this->initOAuthProviderAccessToken($oAuthData);
        $this->initApi();
    }

    private function initOAuthProviderAccessToken(string $oAuthData): self
    {
        $this->provider->initAccessToken($oAuthData);

        return $this;
    }

    private function initOAuthProvider(string $oAuthAppId, string $oAuthAppSecret): self
    {
        $redirectUri = '';

        $this->provider = new MicrosoftGraphApi\OAuthProvider($oAuthAppId, $oAuthAppSecret, $redirectUri);

        return $this;
    }

    private function initApi(): self
    {
        $this->api = new MicrosoftGraphApi\Api($this->provider);

        return $this;
    }

    private function writeFileToOutput(MicrosoftGraphApi\File $file, string $filePathname): self
    {
        $file->saveToFile($this->filesystem, $filePathname);

        return $this;
    }

    public function extractFile(string $link): MicrosoftGraphApi\File
    {
        $files = new MicrosoftGraphApi\OneDrive($this->api);

        try {
            $fileMetadata = $files->readFileMetadataByLink($link);
            $file = $files->readFile($fileMetadata);
        } catch (MicrosoftGraphApi\Exception\GenerateAccessTokenFailure $e) {
            throw new Exception\UserException(
                'Microsoft OAuth API token refresh failed, ' .
                'please reset authorization for the extractor configuration'
            );
        } catch (MicrosoftGraphApi\Exception\FileCannotBeLoaded | MicrosoftGraphApi\Exception\InvalidSharingUrl $e) {
            throw new Exception\UserException($e->getMessage());
        } catch (MicrosoftGraphApi\Exception\GatewayTimeout $e) {
            throw new Exception\UserException('Microsoft API timeout, rerun to try again');
        } catch (MicrosoftGraphApi\Exception\AccessTokenNotInitialized $e) {
            throw new \Exception(sprintf('Access token not initialized: %s', $e->getMessage()));
        }

        $this->writeFileToOutput($file, $fileMetadata->getOneDriveName());

        return $file;
    }
}

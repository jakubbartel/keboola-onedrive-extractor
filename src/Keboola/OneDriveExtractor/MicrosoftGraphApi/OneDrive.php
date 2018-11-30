<?php declare(strict_types = 1);

namespace Keboola\OneDriveExtractor\MicrosoftGraphApi;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class OneDrive
{

    /**
     * @var Api
     */
    private $api;

    /**
     * Files constructor.
     *
     * @param Api $api
     */
    public function __construct(Api $api)
    {
        $this->api = $api;
    }

    /**
     * @param string $link
     * @return FileMetadata
     * @throws Exception\InvalidSharingUrl
     * @throws Exception\MissingDownloadUrl
     */
    public function readFileMetadataByLink(string $link) : FileMetadata
    {
        $shares = new Shares($this->api);

        return $shares->getSharesDriveItemMetadata($link);
    }

    /**
     * @param FileMetadata $oneDriveItemMetadata
     * @return File
     * @throws Exception\FileCannotBeLoaded
     */
    public function readFile(FileMetadata $oneDriveItemMetadata) : File
    {
        $client = new Client();

        try {
            $response = $client->get($oneDriveItemMetadata->getDownloadUrl());
        } catch(RequestException $e) {
            $response = $e->getResponse();

            if($response !== null) {
                throw new Exception\FileCannotBeLoaded(
                    sprintf(
                        'File with id "%s" cannot not be downloaded from OneDrive, returned status code %d on download url',
                        $oneDriveItemMetadata->getOneDriveId(),
                        $response->getStatusCode()
                    )
                );
            } else {
                throw new Exception\FileCannotBeLoaded(
                    sprintf(
                        'File with id "%s" cannot not be downloaded from OneDrive, error when performing GET request %s',
                        $oneDriveItemMetadata->getOneDriveId(),
                        $e->getMessage()
                    )
                );
            }
        }

        if($response->getStatusCode() !== 200) {
            throw new Exception\FileCannotBeLoaded(
                sprintf(
                    'File with id "%s" cannot not be downloaded from OneDrive, returned status code %d on download url',
                    $oneDriveItemMetadata->getOneDriveId(),
                    $response->getStatusCode()
                )
            );
        }

        return File::initByStream($response->getBody());
    }

}

<?php declare(strict_types = 1);

namespace Keboola\OneDriveExtractor\MicrosoftGraphApi;

use GuzzleHttp;
use League\Uri;
use Microsoft\Graph\Exception\GraphException;
use Microsoft\Graph\Model;

class Files
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
     * @param string $oneDriveItemId can be item id or url of the item
     * @return FileMetadata
     * @throws Exception\FileCannotBeLoaded
     */
    public function readFileMetadata(string $oneDriveItemId) : FileMetadata
    {
        try {
            /** @var Model\DriveItem $driveItem */
            $driveItem = $this->api->getApi()
                ->createRequest('GET', sprintf('/me/drive/items/%s', $oneDriveItemId))
                ->setReturnType(Model\DriveItem::class)
                ->execute();
        } catch(GraphException | GuzzleHttp\Exception\ClientException $e) {
            throw new Exception\FileCannotBeLoaded(
                sprintf('File with id "%s" cannot not be loaded from OneDrive', $oneDriveItemId)
            );
        }

        return FileMetadata::initByOneDriveModel($driveItem);
    }

    /**
     * @param string $oneDriveItemId
     * @return File
     * @throws Exception\FileCannotBeLoaded
     */
    public function readFile(string $oneDriveItemId) : File
    {
        try {
            /** @var GuzzleHttp\Psr7\Stream $response */
            $fileContentResponse = $this->api->getApi()
                ->createRequest('GET', sprintf('/me/drive/items/%s/content', $oneDriveItemId))
                ->setReturnType(GuzzleHttp\Psr7\Stream::class)
                ->execute();
        } catch(GraphException | GuzzleHttp\Exception\ClientException $e) {
            throw new Exception\FileCannotBeLoaded(
                sprintf('File with id "%s" cannot not be loaded from OneDrive', $oneDriveItemId)
            );
        }

        return File::initByStream($fileContentResponse);
    }

    /**
     * @param string $id input id - can be url to OneDrive file or OneDrive Item Id
     * @return string
     */
    public function parseOneDriveItemId(string $id) : string
    {
        if(filter_var($id, FILTER_VALIDATE_URL)) {
            return $this->parseOneDriveItemIdFromUrl(Uri\Uri::createFromString($id));
        }

        return $id;
    }

    /**
     * @param Uri\Uri $oneDriveUrl
     * @return string
     * @throws Exception\InvalidOneDriveItemUrl
     */
    private function parseOneDriveItemIdFromUrl(Uri\Uri $oneDriveUrl) : string
    {
        /** @var FileTypes\FileType[] $fileTypes */
        $fileTypes = [
            new FileTypes\Excel(),
        ];

        foreach($fileTypes as $fileType) {
            if($fileType->isFileTypeValidUrl($oneDriveUrl)) {
                $id = $fileType->parseDriveItemIdFromUrl($oneDriveUrl);

                if($id === null) {
                    throw new Exception\InvalidOneDriveItemUrl('Url of OneDrive file is not valid - cannot parse id');
                }

                return $id;
            }
        }

        throw new Exception\InvalidOneDriveItemUrl('Url of OneDrive file is not valid - unsupported file type');
    }

}

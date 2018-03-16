<?php declare(strict_types = 1);

namespace Keboola\OneDriveExtractor\MicrosoftGraphApi;

use GuzzleHttp;
use Microsoft\Graph\Exception\GraphException;

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
     * @throws Exception\FileCannotBeLoaded
     */
    public function readFileMetadataByLink(string $link) : FileMetadata
    {
        $shares = new Shares($this->api);

        return $shares->getSharesDriveItemMetadata($link);
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

}

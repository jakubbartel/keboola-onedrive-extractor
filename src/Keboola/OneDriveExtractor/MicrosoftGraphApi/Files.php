<?php declare(strict_types = 1);

namespace Keboola\OneDriveExtractor\MicrosoftGraphApi;

use GuzzleHttp;
use League\Uri;
use Microsoft\Graph\Exception\GraphException;

class Files
{

    /**
     * @const string
     */
    public const ONEDRIVE_FILE_TYPE_EXCEL = 'Excel';

    /**
     * @const string
     */
    private const ONEDRIVE_EXCEL_ID_GET_PARAM = 'resid';

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
     * @param string $oneDriveFileType
     * @return File
     * @throws Exception\FileCannotBeLoaded
     */
    public function readFile(string $oneDriveItemId, string $oneDriveFileType) : File
    {
        $oneDriveItemId = $this->parseOneDriveItemId($oneDriveItemId, $oneDriveFileType);

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
     * @param string $oneDriveId
     * @param string $oneDriveFileType
     * @return string
     */
    public function parseOneDriveItemId(string $oneDriveId, string $oneDriveFileType) : string
    {
        if(filter_var($oneDriveId, FILTER_VALIDATE_URL)) {
            return $this->parseDriveItemIdFromUrl(Uri\Uri::createFromString($oneDriveId), $oneDriveFileType);
        }

        return $oneDriveId;
    }

    /**
     * @param Uri\Uri $oneDriveUrl
     * @param string $oneDriveFileType
     * @return string
     * @throws Exception\UnsupportedFileType
     */
    private function parseDriveItemIdFromUrl(Uri\Uri $oneDriveUrl, string $oneDriveFileType) : string
    {
        switch($oneDriveFileType) {
            case self::ONEDRIVE_FILE_TYPE_EXCEL:
                $query = new Uri\Components\Query($oneDriveUrl->getQuery());
                $oneDriveId = $query->getParam(self::ONEDRIVE_EXCEL_ID_GET_PARAM);
                break;
            default:
                throw new Exception\UnsupportedFileType($oneDriveFileType);
        }

        return $oneDriveId;
    }

}

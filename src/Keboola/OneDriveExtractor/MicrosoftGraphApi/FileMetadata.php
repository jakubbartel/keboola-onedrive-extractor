<?php declare(strict_types = 1);

namespace Keboola\OneDriveExtractor\MicrosoftGraphApi;

use Microsoft\Graph\Model;

class FileMetadata
{

    /**
     * @var string
     */
    private $oneDriveId;

    /**
     * @var string
     */
    private $oneDriveName;

    /**
     * @var string
     */
    private $downloadUrl;

    /**
     * FileMetadata constructor.
     *
     * @param string $oneDriveId
     * @param string $oneDriveName
     * @param string $downloadUrl
     */
    private function __construct($oneDriveId, $oneDriveName, $downloadUrl)
    {
        $this->oneDriveId = $oneDriveId;
        $this->oneDriveName = $oneDriveName;
        $this->downloadUrl = $downloadUrl;
    }

    /**
     * @param Model\DriveItem $oneDriveItem
     * @return FileMetadata
     */
    public static function initByOneDriveModel(Model\DriveItem $oneDriveItem): self
    {
        return new FileMetadata(
            $oneDriveItem->getId(),
            $oneDriveItem->getName(),
            $oneDriveItem->getProperties()['@microsoft.graph.downloadUrl']
        );
    }

    /**
     * @return string
     */
    public function getOneDriveId() : string
    {
        return $this->oneDriveId;
    }

    /**
     * @return string
     */
    public function getOneDriveName() : string
    {
        return $this->oneDriveName;
    }

    /**
     * @return string
     */
    public function getDownloadUrl() : string
    {
        return $this->downloadUrl;
    }

}

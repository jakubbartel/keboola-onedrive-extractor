<?php

declare(strict_types=1);

namespace Keboola\OneDriveExtractor\MicrosoftGraphApi;

use Keboola\OneDriveExtractor\MicrosoftGraphApi\Exception\MissingDownloadUrl;
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
    private function __construct(string $oneDriveId, string $oneDriveName, string $downloadUrl)
    {
        $this->oneDriveId = $oneDriveId;
        $this->oneDriveName = $oneDriveName;
        $this->downloadUrl = $downloadUrl;
    }

    /**
     * @param Model\DriveItem $oneDriveItem
     * @return FileMetadata
     * @throws MissingDownloadUrl
     */
    public static function initByOneDriveModel(Model\DriveItem $oneDriveItem): self
    {
        $properties = $oneDriveItem->getProperties();

        if (! isset($properties['@microsoft.graph.downloadUrl'])) {
            throw new MissingDownloadUrl();
        }

        return new FileMetadata(
            $oneDriveItem->getId(),
            $oneDriveItem->getName(),
            $properties['@microsoft.graph.downloadUrl']
        );
    }

    public function getOneDriveId(): string
    {
        return $this->oneDriveId;
    }

    public function getOneDriveName(): string
    {
        return $this->oneDriveName;
    }

    public function getDownloadUrl(): string
    {
        return $this->downloadUrl;
    }
}

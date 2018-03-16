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
     * FileMetadata constructor.
     *
     * @param string $oneDriveId
     * @param string $oneDriveName
     */
    private function __construct($oneDriveId, $oneDriveName)
    {
        $this->oneDriveId = $oneDriveId;
        $this->oneDriveName = $oneDriveName;
    }

    /**
     * @param Model\DriveItem $oneDriveItem
     * @return FileMetadata
     */
    public static function initByOneDriveModel(Model\DriveItem $oneDriveItem): self
    {
        return new FileMetadata(
            $oneDriveItem->getId(),
            $oneDriveItem->getName()
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

}

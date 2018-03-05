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
     * @var FileTypes\FileType
     */
    private $fileType;

    /**
     * FileMetadata constructor.
     *
     * @param string $oneDriveId
     * @param string $oneDriveName
     * @param FileTypes\FileType $fileType
     */
    private function __construct($oneDriveId, $oneDriveName, FileTypes\FileType $fileType)
    {
        $this->oneDriveId = $oneDriveId;
        $this->oneDriveName = $oneDriveName;
        $this->fileType = $fileType;
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
            self::determineFileType($oneDriveItem)
        );
    }

    /**
     * @param Model\DriveItem $oneDriveItem
     * @return FileTypes\FileType
     * @throws Exception\UnsupportedFileType
     */
    private static function determineFileType(Model\DriveItem $oneDriveItem): FileTypes\FileType
    {
        /** @var FileTypes\FileType[] $fileTypes */
        $fileTypes = [
            new FileTypes\Excel(),
        ];

        foreach($fileTypes as $fileType) {
            if($fileType->isFileTypeValidMimeType($oneDriveItem->getFile()->getMimeType())) {
                return $fileType;
            }
        }

        throw new Exception\UnsupportedFileType('Unsupported file - unknown MimeType of OneDrive Item');
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
     * @return FileTypes\FileType
     */
    public function getFileType() : FileTypes\FileType
    {
        return $this->fileType;
    }

}

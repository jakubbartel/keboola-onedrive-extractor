<?php

namespace Keboola\OneDriveExtractor\MicrosoftGraphApi\FileTypes;

use League\Uri;

interface FileType
{

    public function parseDriveItemIdFromUrl(Uri\Uri $url): ?string ;

    public function isFileTypeValidUrl(Uri\Uri $url): bool ;

    public function isFileTypeValidMimeType(string $mimeType): bool ;

}

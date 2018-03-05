<?php

namespace Keboola\OneDriveExtractor\MicrosoftGraphApi\FileTypes;

use League\Uri;

class Excel implements FileType
{

    /**
     * @param Uri\Uri $url
     * @return string|null
     */
    public function parseDriveItemIdFromUrl(Uri\Uri $url) : ?string
    {
        if( ! $this->isFileTypeValidUrl($url)) {
            return null;
        }

        $oneDriveItemId = $this->getDriveItemIdQueryValue($url);

        return $oneDriveItemId;
    }

    /**
     * @param Uri\Uri $url
     * @return string|null
     */
    private function getDriveItemIdQueryValue(Uri\Uri $url): ?string
    {
        $query = new Uri\Components\Query($url->getQuery());
        $oneDriveItemId = $query->getParam('resid');

        return $oneDriveItemId;
    }

    /**
     * @param Uri\Uri $url
     * @return bool
     */
    public function isFileTypeValidUrl(Uri\Uri $url) : bool
    {
        $query = new Uri\Components\Query($url->getQuery());

        $app = $query->getParam('app');
        if($app === null || strcasecmp($app, 'Excel') !== 0) {
            return false;
        }

        return true;
    }

    /**
     * @param string $mimeType
     * @return bool
     */
    public function isFileTypeValidMimeType(string $mimeType) : bool
    {
        return preg_match('/\.sheet$/', $mimeType) === 1;
    }
}

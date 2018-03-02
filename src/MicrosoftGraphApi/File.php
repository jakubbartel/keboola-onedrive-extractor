<?php declare(strict_types = 1);

namespace Keboola\OneDriveExtractor\MicrosoftGraphApi;

use League\Flysystem\Filesystem;
use Psr\Http\Message\StreamInterface;

class File
{

    /**
     * @var StreamInterface
     */
    private $stream;

    /**
     * File constructor can be called only by init* methods.
     */
    private function __construct() {}

    /**
     * @param StreamInterface $stream
     * @return File
     */
    public static function initByStream(StreamInterface $stream) : File {
        $file = new File();

        $file->stream = $stream;

        return $file;
    }

    /**
     * @return string
     */
    public function getContents() : string
    {
        return $this->stream->getContents();
    }

    /**
     * @param Filesystem $fileSystem
     * @param string $path
     * @return File
     */
    public function saveToFile(Filesystem $fileSystem, string $path) : self {
        // stream version is preferred but not functional
        //$resource = StreamWrapper::getResource($this->stream);
        //$fileSystem->putStream($path, $resource);

        $fileSystem->put($path, $this->stream->getContents());

        return $this;
    }

}

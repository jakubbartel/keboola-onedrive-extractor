<?php

declare(strict_types=1);

namespace Keboola\OneDriveExtractor\Tests\MicrosoftGraphApi;

use GuzzleHttp;
use Keboola\OneDriveExtractor\MicrosoftGraphApi\File;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{

    public function testSaveFileAndContent(): void
    {
        $string = 'Bad-ass string';

        $stream = fopen('php://memory', 'r+');
        assert($stream !== false);
        fwrite($stream, $string);
        rewind($stream);

        $httpStream = new GuzzleHttp\Psr7\Stream($stream);

        $file = File::initByStream($httpStream);

        $this->assertEquals($string, $file->getContents());

        $a = new \League\Flysystem\Memory\MemoryAdapter();
        $fs = new \League\Flysystem\Filesystem($a);

        $filePath = '/file.txt';

        $file->saveToFile($fs, $filePath);

        $this->assertEquals($string, $fs->read($filePath));
    }
}

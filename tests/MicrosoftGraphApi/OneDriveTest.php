<?php

namespace Keboola\OneDriveExtractor\Tests\MicrosoftGraphApi;

use Keboola\OneDriveExtractor\MicrosoftGraphApi\Api;
use Keboola\OneDriveExtractor\MicrosoftGraphApi\Exception\InvalidOneDriveItemUrl;
use Keboola\OneDriveExtractor\MicrosoftGraphApi\OneDrive;
use Keboola\OneDriveExtractor\MicrosoftGraphApi\OAuthProvider;
use PHPUnit\Framework\TestCase;

class OneDriveTest extends TestCase
{

    public function test(): void
    {
        $a = 3;
        $this->assertEquals(3, $a);
    }

//    public function testParseOneDriveItemIdByUrl() : void
//    {
//        $provider = new OAuthProvider('', '', '');
//        $api = new Api($provider);
//        $files = new OneDrive($api);
//
//        $urlStr = 'https://onedrive.live.com/edit.aspx?cid=33ff70f4269ebfd4&page=view&resid=666&parId=111000&app=excel';
//        $id = $files->parseOneDriveItemId($urlStr);
//        $this->assertEquals('666', $id);
//
//        $urlStr = 'https://onedrive.live.com/edit.aspx?cid=33ff70f4269ebfd4&page=view&resid=999&parId=111000&app=Excel';
//        $id = $files->parseOneDriveItemId($urlStr);
//        $this->assertEquals('999', $id);
//    }
//
//    public function testParseOneDriveItemId() : void
//    {
//        $provider = new OAuthProvider('', '', '');
//        $api = new Api($provider);
//        $files = new OneDrive($api);
//
//        $inId = 'wkfnbefnbj';
//        $id = $files->parseOneDriveItemId($inId);
//
//        $this->assertEquals('wkfnbefnbj', $id);
//    }
//
//    public function testParseOneDriveItemIdByUrlInvalidApp() : void
//    {
//        $provider = new OAuthProvider('', '', '');
//        $api = new Api($provider);
//        $files = new OneDrive($api);
//
//        $this->expectException(InvalidOneDriveItemUrl::class);
//        $this->expectExceptionMessage('Url of OneDrive file is not valid - unsupported file type');
//        $urlStr = 'https://onedrive.live.com/edit.aspx?cid=33ff70f4269ebfd4&page=view&resid=666&parId=111000&app=fok';
//        $files->parseOneDriveItemId($urlStr);
//    }
//
//    public function testParseOneDriveItemIdByUrlMissingApp() : void
//    {
//        $provider = new OAuthProvider('', '', '');
//        $api = new Api($provider);
//        $files = new OneDrive($api);
//
//        $this->expectException(InvalidOneDriveItemUrl::class);
//        $this->expectExceptionMessage('Url of OneDrive file is not valid - unsupported file type');
//        $urlStr = 'https://onedrive.live.com/edit.aspx?cid=33ff70f4269ebfd4&page=view&resid=666&parId=111000';
//        $files->parseOneDriveItemId($urlStr);
//    }
//
//    public function testParseOneDriveItemIdByUrlMissingId() : void
//    {
//        $provider = new OAuthProvider('', '', '');
//        $api = new Api($provider);
//        $files = new OneDrive($api);
//
//        $this->expectException(InvalidOneDriveItemUrl::class);
//        $this->expectExceptionMessage('Url of OneDrive file is not valid - cannot parse id');
//        $urlStr = 'https://onedrive.live.com/edit.aspx?cid=33ff70f4269ebfd4&page=view&parId=111000&app=Excel';
//        $files->parseOneDriveItemId($urlStr);
//    }

}
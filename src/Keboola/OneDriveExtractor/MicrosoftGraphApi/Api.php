<?php declare(strict_types = 1);

namespace Keboola\OneDriveExtractor\MicrosoftGraphApi;

use Microsoft\Graph\Graph;

class Api
{

    /**
     * @var OAuthProvider
     */
    private $provider;

    /**
     * Api constructor.
     *
     * @param OAuthProvider $provider
     */
    public function __construct(OAuthProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * Get Microsoft Graph API with always refreshed access token.
     *
     * @return Graph
     */
    public function getApi(): Graph
    {
        $api = new Graph();

        return $api->setAccessToken($this->provider->getAccessToken());
    }

}

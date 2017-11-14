<?php

namespace Steam;

use Ixudra\Curl\Builder;
use Ixudra\Curl\CurlService;

/**
 * Created by PhpStorm.
 * User: bilal
 * Date: 11/14/17
 * Time: 4:27 PM
 */

class SteamService
{
    protected $apiClient = null;
    protected $curlService = null;
    protected $steamBaseUrl = null;
    protected $authKey = null;
    protected $guards = array();

    public function __construct($baseUrl, $authKey)
    {
        $this->steamBaseUrl = $baseUrl;
        $this->authKey = $authKey;
    }

    /**
     * @return Builder
     */
    public function prepCall($serviceName)
    {
        $this->curlService = new CurlService();

        return $this
            ->curlService
            ->to($this->steamBaseUrl.'/'.$serviceName)
            ->withHeader('Authorization: ' . $this->authKey);
    }

}
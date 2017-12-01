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

    /**
     * @var \Ixudra\Curl\Builder null
     */
    protected $request = null;
    protected $requestData = [];

    protected $guards = array();

    public function __construct($baseUrl, $authKey)
    {
        $this->steamBaseUrl = $baseUrl;
        $this->authKey = $authKey;
    }

    /**
     * @return $this
     */
    public function prepCall($serviceName)
    {
        $this->curlService = new CurlService();
        $this->request = $this
            ->curlService
            ->to($this->steamBaseUrl.'/'.$serviceName)
            ->withHeader('Authorization: ' . $this->authKey);

        return $this;
    }

    /**
     * helper method to populate curl request with data ...
     * @param $importSetupId
     * @param array $campaignRecords
     * @return $this
     */
    public function populate($importSetupId, Array $campaignRecords = [])
    {
        $requestData = array(
            'ImportSetupID' => $importSetupId
        );

        if (count($campaignRecords) > 0) {
            $requestData['CampaignRecords'] = $campaignRecords;
        }

        $this->requestData = $requestData;

        return $this;
    }

    /**
     * create the request and return the response
     * @return array
     */
    public function create()
    {
        $resp = '{}';
        if (array_key_exists('CampaignRecords', $this->requestData)) {
            $resp = $this
                ->request
                ->withContentType('application/json')
                ->withData(json_encode($this->requestData))
                ->post();
        } else {
            $resp = $this->request->withData($this->requestData)->get();
        }

        return json_decode($resp, true);
    }

    
    /**
     * update a request and return the response
     * @return array
     */
    public function update()
    {
        $resp = '{}';
        if (array_key_exists('CampaignRecords', $this->requestData)) {
            $resp = $this
                ->request
                ->withContentType('application/json')
                ->withData(json_encode($this->requestData))
                ->put();
        } else {
            $resp = $this->request->withData($this->requestData)->get();
        }

        return json_decode($resp, true);
    }

}
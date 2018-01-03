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

    protected function sendRequest($type = 'post')
    {
        $resp = '{}';
        if (array_key_exists('CampaignRecords', $this->requestData)) {
            $resp = $this
                ->request
                ->withContentType('application/json')
                ->withData(json_encode($this->requestData))
                ->{$type}();
        } else {
            $resp = $this->request->withData($this->requestData)->get();
        }

        return json_decode($resp, true);
    }

    /**
     * create the request and return the response
     * @return array
     */
    public function create()
    {
        return $this->sendRequest();
    }

    /**
     * update a request and return the response
     * @return array
     */
    public function update()
    {
        return $this->sendRequest('put');
    }

    /**
     * method to soft delete a steam record ...
     * Ref: AB-169 ... status code 113 is used to indicate deleted record.
     */
    public function delete($importSetupId, $contextId, $id)
    {
        return $this->populate($importSetupId,
            [
                [
                    "ContextID" => $contextId,
                    "ID"        => $id,
                    "Status"    => 113,
                    "Data"      => array( // ----- empty object throws an error ... hence this dummy field
                        'test' => 'test'
                    )
                ]
            ]
        )->update();
    }

}
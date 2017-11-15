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
            $records = [];
            foreach ($campaignRecords as $crntCampaignRecord) {
                $records[] = $this->processCampaignRecord($crntCampaignRecord);
            }

            $requestData['CampaignRecords'] = $records;
        }

        $this->requestData = $requestData;

        return $this;
    }

    /**
     * send the request and return the response
     * @return array
     */
    public function send()
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
     * helper method to process campaign record input, to be passed to Steam
     * @param $campaignRecord
     * @return array
     */
    private function processCampaignRecord($campaignRecord)
    {
        $data = $campaignRecord['Data'];

        $processed = array(
            'ContextID' => $campaignRecord['ContextID'],
            'Data' => array(
                'telefoonnummer' => $this->trimStr($data, 'telefoonnummer', 15),
                'mobiel' => $this->trimStr($data, 'mobiel', 15),
                'deeplink' => $this->trimStr($data, 'deeplink', 100),
                'call_type' => $this->trimStr($data, 'call_type', 10),
                'clientid' => $this->trimStr($data, 'clientid', 10)
            )
        );

        return $processed;
    }

    private function trimStr($source, $key, $length)
    {
        return (array_key_exists($key, $source) && strlen($source[$key]) > $length) ? substr($source[$key], 0, $length) : $source[$key];
    }

}
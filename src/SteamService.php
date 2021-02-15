<?php namespace Econtract\Steam;


use Ixudra\Curl\Builder;
use Ixudra\Curl\CurlService;

class SteamService {

    protected $apiClient = null;

    protected $curlService = null;

    protected $steamBaseUrl = null;

    protected $authKey = null;

    /** @var \Ixudra\Curl\Builder */
    protected $request = null;

    protected $requestData = array();

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
     * Helper method to populate curl request with data
     * @param $importSetupId
     * @param array $campaignRecords
     * @return $this
     */
    public function populate($importSetupId, Array $campaignRecords = array())
    {
        $requestData = array(
            'ImportSetupID'         => $importSetupId,
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
     * Create the request and return the response
     * @return array
     */
    public function create()
    {
        return $this->sendRequest();
    }

    /**
     * Update a request and return the response
     * @return array
     */
    public function update()
    {
        return $this->sendRequest('put');
    }

    /**
     * Method to soft delete a steam record
     */
    public function delete($importSetupId, $contextId, $id)
    {
        return $this->populate($importSetupId,
            array(
                array(
                    "ContextID"     => $contextId,
                    "ID"            => $id,
                    "Status"        => 700,         // ----- code for Sale, in future will use 610 if rejected
                    "Data"          => array(       // ----- empty object throws an error ... hence this dummy field
                        'test'          => 'test',
                    )
                )
            )
        )->update();
    }

}

<?php

use Steam\SteamService;

include "vendor/autoload.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

$test = new SteamService(
    'https://io.steam.eu.com/api',
    'Add auth key here ...'
);

/**
 * @var \Ixudra\Curl\CurlService
 */
$resp = $test
    ->prepCall('CampaignRecords')
    ->populate('119046', [
        [
        'ContextID' => 'ExternalID1',
        'Data' => [
            'telefoonnummer' => '',
            'mobiel' => '',
            'deeplink' => '',
            'call_type' => '',
            'clientid' => ''
            ]
        ]
    ])
    ->send();

echo '<pre>';
print_r($resp);
exit;
<?php

use Steam\SteamService;

include "vendor/autoload.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

$service = new SteamService(
    'https://io.steam.eu.com/api',
    'add steam api key ...'
);

$resp = $service
    ->prepCall('CampaignRecords')
    ->delete(122899, 'TextExternal1', 75833);

echo '<pre>';
print_r($resp);
exit;
<?php

use Steam\SteamService;

include "vendor/autoload.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

$test = new SteamService(
    'https://io.steam.eu.com/api',
    'Basic ODM1MDlhZmVhNmYxOjRjZjViYWQ1LTUyODYtNDI3Mi1iYmQ3LTRkNTg3ZThlYmYyMg=='
);

/**
 * @var \Ixudra\Curl\CurlService
 */
$service = $test->prepCall('CampaignRecords');

var_dump($service->get());
exit;
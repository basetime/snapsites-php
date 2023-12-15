<?php
include(__DIR__ . '/../vendor/autoload.php');

use Basetime\Snapsites\Client;
use Basetime\Snapsites\ApiRequest;

$client = new Client('123');
$resp = $client->screenshot('dyNmcmgxd4BFmuffdwCBV0', new ApiRequest([
  'browser' => 'chromium',
  'url' => 'https://avagate.com',
  'type' => 'jpg',
]));
print_r($resp);

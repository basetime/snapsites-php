<?php
include(__DIR__ . '/../vendor/autoload.php');

use Basetime\Snapsites\Client;
use Basetime\Snapsites\ApiRequest;
use GuzzleHttp\Exception\GuzzleException;

$client = new Client('123');
try {
  $resp = $client->screenshot('dyNmcmgxd4BFmuffdwCBV0', new ApiRequest([
    'browser' => 'chromium',
    'url' => 'https://avagate.com',
    'type' => 'jpg',
  ]));
  print_r($resp);
} catch (GuzzleException $e) {
  echo $e->getMessage();
}

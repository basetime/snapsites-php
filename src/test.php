<?php
include(__DIR__ . '/../vendor/autoload.php');

use Basetime\Snapsites\Beacon;
use Basetime\Snapsites\Client;
use Basetime\Snapsites\ApiRequest;
use GuzzleHttp\Exception\GuzzleException;

$client = new Client();
$client->setDebugging(true);
try {
  $resp = $client->screenshot('dyNmcmgxd4BFmuffdwCBV0', '123', new ApiRequest([
    'browser' => 'chromium',
    'url' => 'https://avagate.com',
    'type' => 'jpg',
    'meta' => 'hello_world'
  ]));
  dump($resp);

  /**
   * @var Beacon[] $beacons
   */
  $result = $client->onBeacon($resp->beaconUri, function (array $beacons) {
    dump($beacons);
    $done = 0;
    foreach($beacons as $beacon) {
      if ($beacon->status === 'success') {
        $done++;
      }
    }
    if ($done === count($beacons)) {
      return true;
    }
  });

  dump($result);
} catch (GuzzleException $e) {
  echo $e->getMessage();
} catch (Exception $e) {
  dump($e->getMessage());
}

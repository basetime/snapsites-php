Snapsites PHP
=============
PHP client library for using Snapsites.io.

Making a request to the Snapsites API requires an API key and secret. You can get these from the [Snapsites dashboard](https://snapsites.io/dashboard).

## Installation
Use Composer to install the library.

```bash
composer require basetime/snapsites
```

## Usage
Making a single request.

```php
<?php
use Basetime\Snapsites\Client;
use Basetime\Snapsites\ApiRequest;

$apiSecret = '123';
$endpoint = 'dyNmcmgxd4BFmuffdwCBV0';
$client = new Client();
$resp = $client->screenshotWait($endpoint, $apiSecret, new ApiRequest([
    'url': 'https://avagate.com',
    'type': 'jpg',
]));
echo json_encode($resp);
```

Outputs:
```
Basetime\Snapsites\ApiResponse Object
(
    [id] => 9828e8f1-61e5-42ba-8ac6-665e539c78d4
    [time] => 9037
    [statusUri] => http://dev-api.snapsites.io/dyNmcmgxd4BFmuffdwCBV0/status/9828e8f1-61e5-42ba-8ac6-665e539c78d4
    [beaconUri] => endpoints/dyNmcmgxd4BFmuffdwCBV0-8HcF7rATDipE4c5PCiL3q3-64zwGRCZindv5UXBXtc4fv
    [errors] => []
    [cost] => -0.2
    [balance] => 39.5
    [images] => [
        https://storage.googleapis.com/cdn_snapsites_io/9znViKWbcBj7jRkfJkDnZU.jpeg
    ]
    [pdfs] => []
)
```

Making multiple requests at once.

```php
<?php
use Basetime\Snapsites\Client;
use Basetime\Snapsites\ApiRequest;

$apiSecret = '123';
$endpoint = 'dyNmcmgxd4BFmuffdwCBV0';
$client = new Client();
$resp = $client->batchScreenshotsWait($endpoint, $apiSecret, [
    new ApiRequest([
        'browser': 'firefox',
        'url': 'https://avagate.com',
        'type': 'pdf',
    ]),
    new ApiRequest([
        'browser': 'webkit',
        'url': 'https://google.com',
        'type': 'pdf',
    ]),
]);
echo json_encode($resp);
```

Outputs:
```
Basetime\Snapsites\ApiResponse Object
(
    [id] => 9828e8f1-61e5-42ba-8ac6-665e539c78d4
    [time] => 9037
    [statusUri] => http://dev-api.snapsites.io/dyNmcmgxd4BFmuffdwCBV0/status/9828e8f1-61e5-42ba-8ac6-665e539c78d4
    [beaconUri] => endpoints/dyNmcmgxd4BFmuffdwCBV0-8HcF7rATDipE4c5PCiL3q3-64zwGRCZindv5UXBXtc4fv
    [errors] => []
    [cost] => -0.2
    [balance] => 39.5
    [images] => []
    [pdfs] => [
        https://storage.googleapis.com/cdn_snapsites_io/9znViKWbcBj7jRkfJkDnZU.pdf
        https://storage.googleapis.com/cdn_snapsites_io/4zn09KWbcBj7jikfJkDnZg.pdf
    ]
)
```

Instead of waiting for snapsites to finish generating the screenshot, you can poll for the status of the request or use the beaconUri.

```php
$apiSecret = '123';
$endpoint = 'dyNmcmgxd4BFmuffdwCBV0';
$client = new Client();
$resp = $client->screenshot($endpoint, $apiSecret, new ApiRequest([
    'url': 'https://avagate.com',
    'type': 'jpg',
]));
echo json_encode($resp);

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
```

Response:
```
Basetime\Snapsites\ApiStatus Object
(
  [id]: 1917c524-044d-456b-b7af-4397499dade8
  [time]: 13085
  [cost]: -0.1
  [balance]: 9492.2
  [status]: http://api.snapsites.io/dyNmcmgxd4BFmuffdwCBV0/status/1917c524-044d-456b-b7af-4397499dade8
  [beaconUri]: endpoints/dyNmcmgxd4BFmuffdwCBV0-8HcF7rATDipE4c5PCiL3q3-64zwGRCZindv5UXBXtc4fv
)

[ { message: 'Starting', updatedAt: '2023-12-18T19:22:20.461Z' } ]
[
  {
    message: 'Injecting script.',
    status: 'running',
    updatedAt: '2023-12-18T19:22:21.251Z'
  }
]
[
  {
    message: 'Applying watermark.',
    status: 'running',
    updatedAt: '2023-12-18T19:22:29.006Z'
  }
]
[
  {
    message: 'Uploading to Google Cloud Storage',
    status: 'running',
    updatedAt: '2023-12-18T19:22:30.399Z'
  }
]
[
  {
    message: 'Saved in bucket cdn_snapsites_io',
    status: 'running',
    updatedAt: '2023-12-18T19:22:32.819Z'
  }
]
[
  {
    message: 'Finished.',
    status: 'finished',
    updatedAt: '2023-12-18T19:22:34.823Z'
  }
]
```

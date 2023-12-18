Snapsites PHP
=============
PHP client library for using Snapsites.io.

Making a request to the Snapsites API requires an API key and secret. You can get these from the [Snapsites dashboard](https://snapsites.io/dashboard).

## Usage
Making a single request.

```php
<?php
use Basetime\Snapsites\Client;
use Basetime\Snapsites\ApiRequest;

$apiSecret = '123';
$endpoint = 'dyNmcmgxd4BFmuffdwCBV0';
$client = new Client($apiSecret);
$resp = $client->screenshot($endpoint, new ApiRequest([
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
    [status] => http://dev-api.snapsites.io/dyNmcmgxd4BFmuffdwCBV0/status/9828e8f1-61e5-42ba-8ac6-665e539c78d4
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
$client = new Client($apiSecret);
$resp = $client->batchScreenshots($endpoint, [
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
    [status] => http://dev-api.snapsites.io/dyNmcmgxd4BFmuffdwCBV0/status/9828e8f1-61e5-42ba-8ac6-665e539c78d4
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

Use the `$wait` parameter to wait for the request to complete.

```php
$apiSecret = '123';
$endpoint = 'dyNmcmgxd4BFmuffdwCBV0';
$wait = false;

$client = new Client($apiSecret, $wait);
$resp = $client->screenshot($endpoint, new ApiRequest([
    'url': 'https://avagate.com',
    'type': 'jpg',
]));
echo json_encode($resp);
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
)
```

Use the `status` endpoint to poll for the status of the request.

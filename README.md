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
```json
{
  "id": "1917c524-044d-456b-b7af-4397499dade8",
  "time": 13085,
  "cost": -0.1,
  "balance": 9492.2,
  "status": "http://api.snapsites.io/dyNmcmgxd4BFmuffdwCBV0/status/1917c524-044d-456b-b7af-4397499dade8",
  "images": [
    "https://storage.googleapis.com/cdn_snapsites_io/rhsV7rpKEyb6Ng1KxiDupA.jpeg"
  ],
  "pdfs": []
}
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
        'type': 'jpg',
    ]),
    new ApiRequest([
        'browser': 'webkit',
        'url': 'https://google.com',
        'type': 'jpg',
    ]),
]);
echo json_encode($resp);
```

Outputs:
```json
{
  "id": "1917c524-044d-456b-b7af-4397499dade8",
  "time": 13085,
  "cost": -0.1,
  "balance": 9492.2,
  "status": "http://api.snapsites.io/dyNmcmgxd4BFmuffdwCBV0/status/1917c524-044d-456b-b7af-4397499dade8",
  "images": [
    "https://storage.googleapis.com/cdn_snapsites_io/rhsV7rpKEyb6Ng1KxiDupA.jpeg",
    "https://storage.googleapis.com/cdn_snapsites_io/5hsp4rpKEyb6Ng1KxiDupd.jpeg"
  ],
  "pdfs": []
}
```

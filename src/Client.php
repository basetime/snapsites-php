<?php
namespace Basetime\Snapsites;

use Exception;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;

/**
 * Client library that communicates with the Snapsites API.
 */
class Client
{
  const BASE_URL = 'http://dev-api.snapsites.io';

  /**
   * Whether to enable debugging.
   */
  protected bool $debugging = false;

  /**
   * Sets whether debugging is enabled.
   *
   * @param bool $debugging
   * @return void
   */
  public function setDebugging(bool $debugging): void
  {
    $this->debugging = $debugging;
  }

  /**
   * Sends a screenshot request to snapsites and waits for snapsites to finish generating
   * the screenshots before returning.
   *
   * ```php
   * $resp = $client->screenshotWait('dyNmcmgxd4BFmuffdwCBV0', '123', new ApiRequest([
   *    'browser': 'chromium',
   *    'url': 'https://avagate.com',
   *    'type': 'jpg',
   * ]));
   * print_r(resp);
   *
   * // {
   * //   id: '7473bbe4-b2bf-4858-9a9c-476d302df5b9',
   * //   time: 11094,
   * //   statusUri: 'https://api.snapsites.io/status/7473bbe4-b2bf-4858-9a9c-476d302df5b9',
   * //   beaconUri: 'endpoints/dyNmcmgxd4BFmuffdwCBV0-8HcF7rATDipE4c5PCiL3q3-64zwGRCZindv5UXBXtc4fv',
   * //   errors: [],
   * //   cost: -0.2,
   * //   balance: 1000,
   * //   images: [
   * //     'https://api.snapsites.io/image/123.jpg'
   * //   ],
   * //   pdfs: []
   * // }
   * ```
   *
   * @param string $endpoint The ID of the endpoint to use.
   * @param string $apiSecret The API secret created when the endpoint was created.
   * @param ApiRequest $req The details of the page to screenshot.
   * @throws GuzzleException
   */
  public function screenshotWait(string $endpoint, string $apiSecret, ApiRequest $req): ApiResponse
  {
    $resp = $this->doRequest(
      'POST',
      sprintf('/%s?wait=1', $endpoint),
      $apiSecret,
      $this->createDefaultRequest($req)
    );

    return new ApiResponse($resp);
  }

  /**
   * Sends a screenshot request to snapsites and returns immediately.
   *
   * Unlike the screenshotWait method, this method will return immediately with urls that can
   * be used to check the status of the request.
   *
   * ```php
   *  $resp = $client->screenshot('dyNmcmgxd4BFmuffdwCBV0', '123', new ApiRequest([
   *     'browser': 'chromium',
   *     'url': 'https://avagate.com',
   *     'type': 'jpg',
   *  ]));
   *  print_r(resp);
   *
   *  // {
   *  //   id: '7473bbe4-b2bf-4858-9a9c-476d302df5b9',
   *  //   time: 11094,
   *  //   statusUri: 'https://api.snapsites.io/status/7473bbe4-b2bf-4858-9a9c-476d302df5b9',
   *  //   beaconUri: 'endpoints/dyNmcmgxd4BFmuffdwCBV0-8HcF7rATDipE4c5PCiL3q3-64zwGRCZindv5UXBXtc4fv',
   *  //   errors: [],
   *  //   cost: -0.2,
   *  //   balance: 1000,
   *  // }
   *  ```
   *
   * @param string $endpoint The ID of the endpoint to use.
   * @param string $apiSecret The API secret created when the endpoint was created.
   * @param ApiRequest $req The details of the page to screenshot.
   * @return ApiResponse
   * @throws GuzzleException
   */
  public function screenshot(string $endpoint, string $apiSecret, ApiRequest $req): ApiResponse
  {
    $resp = $this->doRequest(
      'POST',
      sprintf('/%s?wait=0', $endpoint),
      $apiSecret,
      $this->createDefaultRequest($req)
    );

    return new ApiResponse($resp);
  }

  /**
   * Sends a batch of screenshot request to snapsites and waits for snapsites to finish generating
   * the screenshots before returning.
   *
   * ```php
   * $resp = $client->batchScreenshotsWait('dyNmcmgxd4BFmuffdwCBV0', '123', [
   *    new ApiRequest([
   *      url: 'https://avagate.com/splash-1',
   *      type: 'jpg',
   *    ]),
   *    new ApiRequest([
   *      url: 'https://avagate.com/splash-2',
   *      type: 'jpg',
   *    ]),
   * ]);
   * print_r(resp);
   *
   * // {
   * //   id: '7473bbe4-b2bf-4858-9a9c-476d302df5b9',
   * //   time: 11094,
   * //   statusUri: 'https://api.snapsites.io/status/7473bbe4-b2bf-4858-9a9c-476d302df5b9',
   * //   beaconUri: 'endpoints/dyNmcmgxd4BFmuffdwCBV0-8HcF7rATDipE4c5PCiL3q3-64zwGRCZindv5UXBXtc4fv',
   * //   errors: [],
   * //   cost: -0.2,
   * //   balance: 1000,
   * //   images: [
   * //     'https://api.snapsites.io/image/123.jpg',
   * //     'https://api.snapsites.io/image/456.jpg',
   * //   ],
   * //   pdfs: []
   * // }
   * ```
   *
   * @param string $endpoint The ID of the endpoint to use.
   * @param string $apiSecret The API secret created when the endpoint was created.
   * @param ApiRequest[] $req The details of the page to screenshot.
   * @throws GuzzleException
   */
  public function batchScreenshotsWait(string $endpoint, string $apiSecret, array $req): ApiResponse
  {
    $body = [];
    foreach ($req as $k => $r) {
      $body[$k] = $this->createDefaultRequest($r);
    }
    $resp = $this->doRequest(
      'POST',
      sprintf('/%s?wait=1', $endpoint),
      $apiSecret,
      $body
    );

    return new ApiResponse($resp);
  }

  /**
   * Sends a batch of screenshot request to snapsites and returns immediately.
   *
   * Unlike the batchScreenshotsWait method, this method will return immediately with urls that can
   * be used to check the status of the request.
   *
   * @param string $endpoint
   * @param string $apiSecret The API secret created when the endpoint was created.
   * @param array $req
   * @return ApiResponse
   * @throws GuzzleException
   */
  public function batchScreenshots(string $endpoint, string $apiSecret, array $req): ApiResponse
  {
    $body = [];
    foreach ($req as $k => $r) {
      $body[$k] = $this->createDefaultRequest($r);
    }
    $resp = $this->doRequest(
      'POST',
      sprintf('/%s?wait=0', $endpoint),
      $apiSecret,
      $body
    );

    return new ApiResponse($resp);
  }

  /**
   * Gets the status of the given api request.
   *
   * @param string $endpoint The ID of the endpoint to use.
   * @param string $apiSecret The API secret created when the endpoint was created.
   * @param string $id The ID of the request to get the status of.
   * @return ApiStatus
   * @throws GuzzleException
   * @throws Exception
   */
  public function status(string $endpoint, string $apiSecret, string $id): ApiStatus
  {
    $resp = $this->doRequest('GET', sprintf('/%s/status/%s', $endpoint, $id), $apiSecret);

    return new ApiStatus($resp);
  }

  /**
   * Gets the status of all api requests.
   *
   * @param string $endpoint The ID of the endpoint to use.
   * @param string $apiSecret The API secret created when the endpoint was created.
   * @param int $limit The number of statuses to return.
   * @param int $offset The offset to start at.
   * @throws GuzzleException
   * @throws Exception
   */
  public function statusAll(string $endpoint, string $apiSecret, int $limit = 25, int $offset = 0): array
  {
    $resp = $this->doRequest('GET', sprintf('/%s/status?limit=%d&offset=%d', $endpoint, $limit, $offset), $apiSecret);
    $statuses = [];
    foreach($resp as $data) {
      $statuses[] = new PartialApiStatus($data);
    }

    return $statuses;
  }

  /**
   * Returns a value indicating whether the given webhook id and token are valid.
   *
   * @param string $id The webhook ID.
   * @param string $token The webhook token.
   * @return bool
   * @throws GuzzleException
   */
  public function verifyWebhook(string $id, string $token): bool
  {
    $options = [
      RequestOptions::HTTP_ERRORS => false,
      RequestOptions::JSON => [
        'id' => $id,
        'token' => $token
      ]
    ];

    $client = new GuzzleClient();
    $resp = $client->request('POST', self::BASE_URL . '/webhook/verify', $options);
    if ($resp->getStatusCode() !== 200) {
      return false;
    }

    $body = json_decode($resp->getBody(), true);
    return $body['valid'] ?? false;
  }

  /**
   * Listens for beacons and calls the given callback when a beacon is received.
   *
   * The callback will be called with an array of beacons. The callback should
   * return a truthy value to stop listening.
   *
   * @param string $uri The beacon uri.
   * @param callable $callback The callback to call when a beacon is received.
   * @return mixed
   * @throws GuzzleException
   * @throws Exception
   */
  public function onBeacon(string $uri, callable $callback): mixed
  {
    $query = '';
    $baseUrl = $this->debugging
      ? 'http://localhost:9100?ns=telliclick-master'
      : 'https://snapsites.firebaseio.com';
    if (str_contains($baseUrl, '?')) {
      $query = substr($baseUrl, strpos($baseUrl, '?'));
      $baseUrl = substr($baseUrl, 0, strpos($baseUrl, '?'));
    }

    $last = '';
    $client = new GuzzleClient();

    do {
      $resp = $client->request('GET', $baseUrl . '/' . $uri . '.json' . $query);
      $body = (string)$resp->getBody();
      if ($body !== $last) {
        $last = $body;

        $data = json_decode($body, true);
        $beacons = [];
        foreach($data as $d) {
          $beacons[] = new Beacon($d);
        }

        // Any truthy value returned from the callback will stop the loop.
        $result = $callback($beacons);
        if ($result) {
          break;
        }
      }
      sleep(3);
    } while(true);

    return $result;
  }

  /**
   * @throws GuzzleException
   */
  protected function doRequest(string $method, string $path, string $apiSecret, array $body = [])
  {
    $options = [
      RequestOptions::HEADERS => [
        'X-Api-Secret' => $apiSecret
      ]
    ];
    if ($body) {
      $options[RequestOptions::JSON] = $body;
    }

    $client = new GuzzleClient();
    $resp = $client->request($method, self::BASE_URL . $path, $options);

    return json_decode($resp->getBody(), true);
  }

  /**
   * Adds defaults to the given request and returns an array.
   *
   * @param ApiRequest $req The request to add defaults to.
   * @return array
   */
  private function createDefaultRequest(ApiRequest $req): array
  {
    $body = [];
    if ($req->browser) {
      $body['browser'] = $req->browser;
    }
    if ($req->url) {
      $body['url'] = $req->url;
    }
    if ($req->html) {
      $body['html'] = $req->html;
    }
    if ($req->type) {
      $body['type'] = $req->type;
    }
    if ($req->options) {
      $body['options'] = $req->options;
    }
    if ($req->meta) {
      $body['meta'] = $req->meta;
    }

    return $body;
  }
}

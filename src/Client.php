<?php
namespace Basetime\Snapsites;

use Exception;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Factory;

/**
 * Client library that communicates with the Snapsites API.
 */
class Client
{
  const BASE_URL = 'http://dev-api.snapsites.io';

  /**
   * The API secret created when the endpoint was created.
   */
  protected string $apiSecret;

  /**
   * Whether to wait for requests to complete.
   */
  protected bool $wait = true;

  /**
   * Whether to enable debugging.
   */
  protected bool $debugging = false;

  /**
   * Constructor.
   *
   * @param string $apiSecret
   * @param bool $wait
   */
  public function __construct(string $apiSecret, bool $wait = true)
  {
    $this->apiSecret = $apiSecret;
    $this->wait = $wait;
  }

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
   * Takes a screenshot of a page.
   *
   * ```php
   * $resp = $client->screenshot('dyNmcmgxd4BFmuffdwCBV0', new ApiRequest([
   *    'browser': 'chromium',
   *    'url': 'https://avagate.com',
   *    'type': 'jpg',
   * ]));
   * console.log(resp);
   *
   * // {
   * //   id: '7473bbe4-b2bf-4858-9a9c-476d302df5b9',
   * //   time: 11094,
   * //   status: 'https://api.snapsites.io/status/7473bbe4-b2bf-4858-9a9c-476d302df5b9',
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
   * The response values are as follows:
   *
   *  - `id`: The unique id of the request.
   *  - `time`: Number of milliseconds it took to complete the request.
   *  - `status`: URL to poll in order to get the status of the request.
   *  - `cost`: The cost of the request in credits.
   *  - `balance`: The remaining balance in credits.
   *  - `images`: The images generated during the request.
   *  - `pdfs`: The pdfs generated during the request.
   *
   * @param string $endpoint The ID of the endpoint to use.
   * @param ApiRequest $req The details of the page to screenshot.
   * @throws GuzzleException
   */
  public function screenshot(string $endpoint, ApiRequest $req): ApiResponse
  {
    $resp = $this->doRequest(
      'POST',
      sprintf('/%s?wait=%s', $endpoint, $this->wait ? '1' : '0'),
      $this->createDefaultRequest($req)
    );

    return new ApiResponse($resp);
  }

  /**
   * Sends a batch of screenshots to be taken.
   *
   * ```php
   * $resp = $client->screenshot('dyNmcmgxd4BFmuffdwCBV0', [
   *    new ApiRequest([
   *      url: 'https://avagate.com/splash-1',
   *      type: 'jpg',
   *    ]),
   *    new ApiRequest([
   *      url: 'https://avagate.com/splash-2',
   *      type: 'jpg',
   *    ]),
   * ]);
   * console.log(resp);
   *
   * // {
   * //   id: '7473bbe4-b2bf-4858-9a9c-476d302df5b9',
   * //   time: 11094,
   * //   status: 'https://api.snapsites.io/status/7473bbe4-b2bf-4858-9a9c-476d302df5b9',
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
   * @param ApiRequest[] $req The details of the page to screenshot.
   * @throws GuzzleException
   */
  public function batchScreenshots(string $endpoint, array $req): ApiResponse
  {
    $body = [];
    foreach ($req as $k => $r) {
      $body[$k] = $this->createDefaultRequest($r);
    }
    $resp = $this->doRequest(
      'POST',
      sprintf('/%s?wait=%s', $endpoint, $this->wait ? '1' : '0'),
      $body
    );

    return new ApiResponse($resp);
  }

  /**
   * Gets the status of the given api request.
   *
   * @param string $endpoint The ID of the endpoint to use.
   * @param string $id The ID of the request to get the status of.
   * @return ApiStatus
   * @throws GuzzleException
   * @throws Exception
   */
  public function status(string $endpoint, string $id): ApiStatus
  {
    $resp = $this->doRequest('GET', sprintf('/%s/status/%s', $endpoint, $id));

    return new ApiStatus($resp);
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
      ? 'http://localhost:9000?ns=telliclick-master'
      : 'https://snapsites.firebaseio.com';
    if (str_contains($baseUrl, '?')) {
      $query = substr($baseUrl, strpos($baseUrl, '?'));
      $baseUrl = substr($baseUrl, 0, strpos($baseUrl, '?'));
    }

    $last = '';
    $result = null;
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
  protected function doRequest(string $method, string $path, array $body = [])
  {
    $options = [
      'headers' => [
        'X-Api-Secret' => $this->apiSecret
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

    return $body;
  }
}

<?php

namespace Basetime\Snapsites;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;

class Client
{
  const BASE_URL = 'http://dev-api.snapsites.io';

  /**
   * The API secret created when the endpoint was created.
   *
   * @var string
   */
  protected string $apiSecret;

  /**
   * Constructor.
   *
   * @param string $apiSecret
   */
  public function __construct(string $apiSecret)
  {
    $this->apiSecret = $apiSecret;
  }

  /**
   * Takes a screenshot of a page.
   *
   * ```php
   * $resp = $client->screenshot('dyNmcmgxd4BFmuffdwCBV0', [
   *    'url': 'https://avagate.com',
   *    'type': 'jpg',
   * ]);
   * console.log(resp);
   *
   * // {
   * //   id: '7473bbe4-b2bf-4858-9a9c-476d302df5b9',
   * //   time: 11094,
   * //   status: 'https://api.snapsites.io/status/7473bbe4-b2bf-4858-9a9c-476d302df5b9',
   * //   cost: -0.2,
   * //   balance: 1000,
   * //   images: {
   * //     '0': 'https://api.snapsites.io/image/123.jpg'
   * //   },
   * //   pdfs: {}
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
  public function screenshot(string $endpoint, ApiRequest $req)
  {
    $body = [];
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

    $client = new GuzzleClient();
    $resp = $client->post(self::BASE_URL . '/' . $endpoint, [
      RequestOptions::JSON => $body,
      'headers' => [
        'X-Api-Secret' => $this->apiSecret
      ]
    ]);

    return json_decode($resp->getBody());
  }

  /**
   * Sends a batch of screenshots to be taken.
   *
   * ```php
   * $resp = $client->screenshot('dyNmcmgxd4BFmuffdwCBV0', [
   *    'splash-1': [
   *      url: 'https://avagate.com/splash-1',
   *      type: 'jpg',
   *    ],
   *    'splash-2': [
   *      url: 'https://avagate.com/splash-2',
   *      type: 'jpg',
   *    ],
   * ]);
   * console.log(resp);
   *
   * // {
   * //   id: '7473bbe4-b2bf-4858-9a9c-476d302df5b9',
   * //   time: 11094,
   * //   status: 'https://api.snapsites.io/status/7473bbe4-b2bf-4858-9a9c-476d302df5b9',
   * //   cost: -0.2,
   * //   balance: 1000,
   * //   images: {
   * //     'splash-1': 'https://api.snapsites.io/image/123.jpg',
   * //     'splash-2': 'https://api.snapsites.io/image/456.jpg',
   * //   },
   * //   pdfs: {}
   * // }
   * ```
   *
   * @param string endpoint The ID of the endpoint to use.
   * @param ApiRequest[] req The details of the page to screenshot.
   * @throws GuzzleException
   */
  public function batchScreenshots(string $endpoint, array $req)
  {
    $body = [];
    foreach ($req as $k => $r) {
      $body[$k] = [
        'url' => $r->url ?? '',
        'type' => $r->type ?? '',
        'options' => $r->options ?? [],
      ];
    }

    $client = new GuzzleClient();
    $resp = $client->post(self::BASE_URL . '/' . $endpoint, [
      RequestOptions::JSON => $body,
      'headers' => [
        'X-Api-Secret' => $this->apiSecret
      ]
    ]);

    return json_decode($resp->getBody());
  }
}

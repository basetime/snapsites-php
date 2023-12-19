<?php
namespace Basetime\Snapsites;

/**
 * The response from the API.
 */
class ApiResponse {
  /**
   * The ID of the request.
   */
  public string $id;

  /**
   * The time it took to complete the request.
   */
  public int $time;

  /**
   * The status of the request.
   */
  public string $statusUri;

  /**
   * URI of the beacon to get status updates.
   */
  public string $beaconUri;

  /**
   * The errors that occurred during the request.
   */
  public array $errors;

  /**
   * The cost of the request.
   */
  public float $cost;

  /**
   * The balance of the account.
   */
  public float $balance;

  /**
   * The images that were generated.
   */
  public array $images;

  /**
   * The PDFs that were generated.
   */
  public array $pdfs;

  /**
   * Constructor.
   *
   * @param array $data
   */
  public function __construct(array $data)
  {
    $this->validate($data);
    $this->id = $data['id'];
    $this->time = $data['time'];
    $this->statusUri = $data['statusUri'];
    $this->beaconUri = $data['beaconUri'];
    $this->errors = $data['errors'];
    $this->cost = $data['cost'];
    $this->balance = $data['balance'];
    $this->images = $data['images'] ?? [];
    $this->pdfs = $data['pdfs'] ?? [];
  }

  /**
   * Validates the constructor arguments.
   *
   * @param array $data
   * @return void
   */
  private function validate(array $data)
  {
    $required = [
      'id',
      'time',
      'statusUri',
      'beaconUri',
      'errors',
      'cost',
      'balance'
    ];
    foreach($required as $key) {
      if (!isset($data[$key])) {
        throw new \InvalidArgumentException(sprintf('Missing required argument "%s"', $key));
      }
    }
  }
}

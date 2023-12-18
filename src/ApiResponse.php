<?php
namespace Basetime\Snapsites;

/**
 * The response from the API.
 */
class ApiResponse {
  /**
   * The ID of the request.
   *
   * @var string
   */
  public $id;

  /**
   * The time it took to complete the request.
   *
   * @var int
   */
  public $time;

  /**
   * The status of the request.
   *
   * @var string
   */
  public $status;

  /**
   * The errors that occurred during the request.
   *
   * @var array
   */
  public $errors;

  /**
   * The cost of the request.
   *
   * @var float
   */
  public $cost;

  /**
   * The balance of the account.
   *
   * @var float
   */
  public $balance;

  /**
   * The images that were generated.
   *
   * @var array
   */
  public $images;

  /**
   * The PDFs that were generated.
   *
   * @var array
   */
  public $pdfs;

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
    $this->status = $data['status'];
    $this->errors = $data['errors'];
    $this->cost = $data['cost'];
    $this->balance = $data['balance'];
    $this->images = $data['images'];
    $this->pdfs = $data['pdfs'];
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
      'status',
      'errors',
      'cost',
      'balance',
      'images',
      'pdfs',
    ];
    foreach($required as $key) {
      if (!isset($data[$key])) {
        throw new \InvalidArgumentException(sprintf('Missing required argument "%s"', $key));
      }
    }
  }
}

<?php
namespace Basetime\Snapsites;

use DateTime;
use Exception;

/**
 * The status of a request to the API.
 */
class ApiStatus {
  /**
   * @var string
   */
  public $id;

  /**
   * @var string
   */
  public $status;

  /**
   * @var int
   */
  public $currentStep;

  /**
   * @var
   */
  public $totalSteps;

  /**
   * @var float
   */
  public $cost;

  /**
   * @var DateTime
   */
  public $createdAt;

  /**
   * @var DateTime
   */
  public $completedAt;

  /**
   * @var
   */
  public $logs;

  /**
   * @var array
   */
  public $request;

  /**
   * @var string[]
   */
  public $images;

  /**
   * @var string[]
   */
  public $pdfs;

  /**
   * Constructor.
   *
   * @param array $data The response values.
   * @throws Exception
   */
  public function __construct(array $data)
  {
    $this->validate($data);
    $this->id = $data['id'];
    $this->status = $data['status'];
    $this->currentStep = $data['currentStep'];
    $this->totalSteps = $data['totalSteps'];
    $this->cost = $data['cost'];
    $this->createdAt = new DateTime($data['createdAt']);
    $this->completedAt = new DateTime($data['completedAt']);
    $this->logs = $data['logs'];
    $this->request = $data['request'];
    $this->images = $data['images'];
    $this->pdfs = $data['pdfs'];
  }

  /**
   * Validates the constructor args.
   *
   * @param array $data The constructor args.
   * @return void
   * @throws Exception
   */
  private function validate(array $data)
  {
    $required = [
      'id',
      'status',
      'currentStep',
      'totalSteps',
      'cost',
      'createdAt',
      'completedAt',
      'logs',
      'request',
      'images',
      'pdfs',
      ];
    foreach ($required as $r) {
      if (!isset($data[$r])) {
        throw new Exception(sprintf('Missing required field "%s"', $r));
      }
    }
  }
}
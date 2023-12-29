<?php
namespace Basetime\Snapsites;

use DateTime;
use Exception;

/**
 * The status of a request to the API.
 *
 * Does not include the `logs` and `request` properties like the full ApiStatus.
 */
class PartialApiStatus
{
  /**
   * @var string
   */
  public string $id;

  /**
   * @var string
   */
  public string $status;

  /**
   * @var string
   */
  public string $beaconUri;

  /**
   * @var int
   */
  public int $currentStep;

  /**
   * @var int
   */
  public int $totalSteps;

  /**
   * @var float
   */
  public float $cost;

  /**
   * @var DateTime
   */
  public DateTime $createdAt;

  /**
   * @var DateTime
   */
  public DateTime $completedAt;

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
    $this->beaconUri = $data['beaconUri'];
    $this->currentStep = $data['currentStep'];
    $this->totalSteps = $data['totalSteps'];
    $this->cost = $data['cost'];
    $this->createdAt = new DateTime($data['createdAt']);
    $this->completedAt = new DateTime($data['completedAt']);
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
  private function validate(array $data): void
  {
    $required = [
      'id',
      'status',
      'beaconUri',
      'currentStep',
      'totalSteps',
      'cost',
      'createdAt',
      'completedAt',
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

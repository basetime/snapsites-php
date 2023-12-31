<?php
namespace Basetime\Snapsites;

use DateTime;
use Exception;

/**
 * Class Beacon.
 */
class Beacon
{
  /**
   * The status message.
   */
  public string $message;

  /**
   * The status enum.
   */
  public string $status;

  /**
   * The current step.
   */
  public int $currentStep;

  /**
   * The total steps.
   */
  public int $totalSteps;

  /**
   * Additional data.
   */
  public mixed $data;

  /**
   * User supplied metadata that will be a part of the api status.
   *
   * Used by clients to pass along a unique value that they need to associate with the request. They
   * can then find this value in the status response.
   */
  public string $meta;

  /**
   * The time the beacon was updated.
   */
  public DateTime $updatedAt;

  /**
   * @throws Exception
   */
  public function __construct(array $data)
  {
    $this->validate($data);
    $this->message = $data['message'];
    $this->status = $data['status'];
    $this->currentStep = $data['currentStep'] ?? 0;
    $this->totalSteps = $data['totalSteps'] ?? 0;
    $this->data = $data['data'] ?? null;
    $this->meta = $data['meta'];
    $this->updatedAt = new DateTime($data['updatedAt']);
  }

  /**
   * Validate the data.
   *
   * @param array $data
   *
   * @return void
   * @throws Exception
   */
  protected function validate(array $data): void
  {
    if (!isset($data['message'])) {
      throw new Exception('Missing message');
    }
    if (!isset($data['status'])) {
      throw new Exception('Missing status');
    }
    if (!isset($data['currentStep'])) {
      throw new Exception('Missing currentStep');
    }
    if (!isset($data['totalSteps'])) {
      throw new Exception('Missing totalSteps');
    }
    if (!isset($data['meta'])) {
      throw new Exception('Missing meta');
    }
    if (!isset($data['updatedAt'])) {
      throw new Exception('Missing updatedAt');
    }
  }
}

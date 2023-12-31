<?php
namespace Basetime\Snapsites;

/**
 * The details of the request to the API.
 */
class ApiRequest
{
  /**
   * The browser to use.
   *
   * Possible values are 'chromium', 'firefox', 'webkit'.
   */
  public string $browser = 'chromium';

  /**
   * The URL of the page to take a screenshot of.
   */
  public string $url = '';

  /**
   * The HTML of the page to take a screenshot of.
   */
  public string $html = '';

  /**
   * The type of the file to be generated.
   */
  public string $type = '';

  /**
   * Additional options passed to the integrations.
   */
  public array $options = [];

  /**
   * User supplied metadata that will be a part of the api status.
   *
   * Used by clients to pass along a unique value that they need to associate with the request. They
   * can then find this value in the status response.
   */
  public string $meta = '';

  /**
   * Constructor.
   *
   * @param array $options
   */
  public function __construct(array $options)
  {
    if (isset($options['browser'])) {
      $this->browser = $options['browser'];
    }
    if (isset($options['url'])) {
      $this->url = $options['url'];
    }
    if (isset($options['html'])) {
      $this->html = $options['html'];
    }
    if (isset($options['type'])) {
      $this->type = $options['type'];
    }
    if (isset($options['options'])) {
      $this->options = $options['options'];
    }
    if (isset($options['meta'])) {
      $this->meta = $options['meta'];
    }
  }
}

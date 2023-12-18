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
  public $browser = 'chromium';

  /**
   * The URL of the page to take a screenshot of.
   */
  public $url = '';

  /**
   * The HTML of the page to take a screenshot of.
   */
  public $html = '';

  /**
   * The type of the file to be generated.
   */
  public $type = '';

  /**
   * Additional options passed to the integrations.
   */
  public $options = [];

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
  }
}

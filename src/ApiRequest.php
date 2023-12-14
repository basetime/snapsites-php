<?php

namespace Basetime\Snapsites;

/**
 * The details of the request to the API.
 */
class ApiRequest
{
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
     * Constructor.
     *
     * @param array $options
     */
    public function __construct(array $options)
    {
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
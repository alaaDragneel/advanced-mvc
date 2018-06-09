<?php

namespace System\Http;

class Request
{
  /**
   * Url
   *
   * @var string
   */
  private $url;

  /**
   * Base Url
   *
   * @var string
   */
  private $baseUrl;

  /**
   * Prepare Url
   *
   * @return void
   */
  public function prepareUrl()
  {
    $script = dirname($this->server('SCRIPT_NAME'));

    $requestUri = $this->server('REQUEST_URI');

    if (strpos($requestUri, '?') !== false) {
      [$requestUri, $queryString] = explode('?', $requestUri);
    }

    $pattern = preg_quote("/^{$script}/");
    $this->url = preg_replace($pattern, '', $requestUri);

    $requestScheme = is_null($this->server('REQUEST_SCHEME')) ? 'http'  : $this->server('REQUEST_SCHEME');
    $this->baseUrl = $requestScheme . '://' . $this->server('HTTP_HOST') . $script;
  }

  /**
   * Get Value From $_GET By The Given Key
   *
   * @param sting $key
   * @param mixed $default
   * @return mixed
   */
  public function get($key, $default = null)
  {
    return array_get($_GET, $key, $default);
  }

  /**
   * Get Value From $_POST By The Given Key
   *
   * @param sting $key
   * @param mixed $default
   * @return mixed
   */
  public function post($key, $default = null)
  {
    return array_get($_POST, $key, $default);
  }

  /**
   * Get Value From $_SERVER By The Given Key
   *
   * @param sting $key
   * @param mixed $default
   * @return mixed
   */
  public function server($key, $default = null)
  {
    return array_get($_SERVER, $key, $default);
  }

  /**
   * Get Current Request method
   *
   * @return string
   */
  public function method()
  {
    return $this->server('REQUEST_METHOD');    
  }

  /**
   * Get Full Url 
   * 
   * @return string
   */
  public function baseUrl()
  {
    return $this->baseUrl; 
  }

  /**
   * Get Only Relative Url (Clean Url)
   * 
   * @return string
   */
  public function url()
  {
    return $this->url; 
  }
}
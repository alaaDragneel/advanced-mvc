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
   * Uploaded File Containers
   * 
   * @var array $files
   */
  private $files = [];


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

    $this->url = rtrim(preg_replace("#^{$script}#i", '', $requestUri), '/');
    
    // if we go to domain.com it redirect to /404 so we overwrite it with /
    if (!$this->url) {
      $this->url = '/';
    }

    $this->baseUrl = $this->server('REQUEST_SCHEME') . '://' . $this->server('HTTP_HOST') . $script . '/';
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
   * Get The File Uploaded Object By The Given Key
   *
   * @param sting $input
   * @return \System\Http\FileUpload
   */
  public function file($input)
  {
    if (isset($this->files[$input])) {
      return $this->files[$input];
    }

    $uploadedFile = new UploadFile($input);

    $this->files[$input] = $uploadedFile;

    return $this->files[$input]; 
  }

  /**
   * Get All Value From $_REQUEST
   *
   * @return array
   */
  public function all()
  {
    return $_REQUEST;
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
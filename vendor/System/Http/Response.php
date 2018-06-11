<?php

namespace System\Http;

use System\Application;

class Response
{
    /**
     * Application object
     *
     * @var \System\Application
     */
    private $app;

    /**
     * Headers Container That Will Be Send To The Browser
     *
     * @var array
     */
    private $headers = [];

    /**
     * The Content That Will Be Send To The Browser
     *
     * @var string
     */
    private $content = '';

    /**
     * Constructor
     * @param \System\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Set The Response output content
     *
     * @param string $content
     * @return void
     */
    public function setOutput($content)
    {
        $this->content = $content;
    }

    /**
     * Set The Response Headers
     *
     * @param string $content
     * @param mixed $value
     * @return void
     */
    public function setHeader($header, $value)
    {
        $this->headers[$header] = $value;
    }

    /**
     * Send the Response Header And Content
     * Must Send Headers First Before the output
     *
     * @return void
     */
    public function send()
    {
        $this->sendHeaders();

        $this->sendOutput();
    }

    /**
     * Send The Response output
     *
     * @return void
     */
    private function sendOutput()
    {
        echo $this->content;
    }

    /**
     * Send The Response Headers
     *
     * @return void
     */
    private function sendHeaders()
    {
        foreach ($this->headers as $header => $value) {
            header("{$header}:$value");
        }
    }

}

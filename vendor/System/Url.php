<?php

namespace System;

class Url
{
    /** 
     * Application Object 
     * 
     * @var \System\Application
     */
    protected $app;

    /**
     * Constructor
     * 
     * @param \System\Application
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Generate Full Link For The Given Path
     *
     * @param string $path
     * @return string
     */
    public function link($path)
    {
        return $this->app->request->baseUrl() . trim($path, '/');
    }

    /**
     * Redirect To The Given Path
     *
     * @param string $path
     * @return string
     */
    public function redirectTo($path)
    {
        header("location: {$this->link($path)}");
        exit;
    }

    /**
     * Redirect To The Given Path
     *
     * @param string $path
     * @return string
     */
    public function redirect($path)
    {
        $this->redirectTo($path);
    }
}


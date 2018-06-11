<?php

namespace System;

abstract class Controller
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
     * Call Shared Application Objects Dynamically
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->app->get($key); 
    }
}
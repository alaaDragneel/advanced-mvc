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
     * Errors Container
     * 
     * @var array
     */
    protected $errors = [];

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
     * Convert Passed Data To Json
     *
     * @param mixed $data
     * @return string
     */
    public function toJson($data)
    {
        return json_encode($data);
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
<?php

namespace System;

class Application
{
    /**
     * $container
     *
     * @var array
     */
    private $container = [];
    
    /**
     * __construct
     *
     * @param \System\FileSystem $fileSystem
     * @return void
     */
    public function __construct(FileSystem $fileSystem)
    {
        $this->share('file', $fileSystem);

        $this->registerClasses();

        $this->loadHelpers();
        
        dd($this->file);
    }

    /**
     * Register Classes In SPL auto loader register
     *
     * @return void
     */
    private function registerClasses()
    {
        spl_autoload_register([$this, 'load']);
    }

    /**
     * load class throw auto loading
     *
     * @param string $class
     * @return void
     */
    private function load($class)
    {
        if (strpos($class, 'App') === 0) {
            // get The Class From app
            $file = $this->file->toAppPath($class);
        } else {
            // get The Class From vendor
            $file = $this->file->toVendorPath($class);
        }

        if ($this->file->exists($file)) {
            $this->file->call($file);
        }
    }

    /**
     * Load Helpers File
     *
     * @return void
     */
    public function loadHelpers()
    {
        $this->file->loadHelpers();
    }

    /**
     * Get Shared value
     *
     * @param string $key
     * @return bool
     */
    public function get($key)
    {
        return isset($this->container[$key]) ? $this->container[$key] : null;
    }

    /**
     * Share the giving key|value Through The Entire Application
     * 
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function share($key, $value)
    {
        $this->container[$key] = $value;

        return $this;
    }

    /**
     * Get hared Value Dynamically
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
       return $this->get($key); 
    }
}
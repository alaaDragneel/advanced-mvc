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
    }

    /**
     * Run the Application
     * 
     * @return void
     */
    public function run()
    {
        
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
     * Get Shared value with implementing lazy loading
     *
     * @param string $key
     * @return bool
     */
    public function get($key)
    {
        // is not sharing in the container
        if (! $this->isSharing($key) ) {
            // is a core alias class
            if ($this->isCoreAlias($key)) {
                // share the key with new object and it will store in container and make only one object because it already in the container
                $this->share($key, $this->createNewCoreObject($key));
            } else {
                die("<b>{$key}</b> not found in application container");
            }
        }

        // resolve the key from the container
        return $this->container[$key];
    }

    /**
     * String if the given key is shared through the application
     * 
     * @param string $key
     * @return bool
     */
    public function isSharing($key)
    {
        return isset($this->container[$key]);
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
     * Determine if the given key is an alias to core class
     * 
     * @param string $alias
     * @return bool
     */
    private function isCoreAlias($alias)
    {
        $coreClasses = $this->coreClasses();

        return isset($coreClasses[$alias]);
    }

    /**
     * Create new object for the core class based on the given alias
     *
     * @param string $alias
     * @return object
     */
    private function createNewCoreObject($alias)
    {
        $coreClasses = $this->coreClasses();

        $object = $coreClasses[$alias];

        return new $object($this);
    }

    /**
     * Get All Core Classes With its Aliases
     * 
     * @return array
     */
    private function coreClasses()
    {
        return [
            'request'   => 'System\\Http\\Request',
            'response'  => 'System\\Http\\Response',
            'session'   => 'System\\Session',
            'cookie'    => 'System\\Cookie',
            'load'      => 'System\\Loader',
            'html'      => 'System\\Html',
            'db'        => 'System\\Database',
            'view'      => 'System\\View\\ViewFactory',
        ]; 
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
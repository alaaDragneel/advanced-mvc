<?php

namespace System;

class Loader
{
    /**
     * Application Object
     *
     * @var \System\Application
     */
    private $app;

    /**
     * Controllers Container
     *
     * @var array
     */
    private $controllers = [];

    /**
     * Models Container
     *
     * @var array
     */
    private $models = [];

    /**
     * Controller Name Space
     *
     * @var string
     */
    private static $controllerNamespace = 'App\\Controllers\\';

    /**
     * Models Name Space
     *
     * @var string
     */
    private static $modelsNamespace = 'App\\Models\\';

    /**
     * Constructor
     * @param \System\Application $app
     */

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Call The Given Controller With The Given Method
     * and Pass The Given Arguments To The Container Method
     *
     * @param string $controller
     * @param string $method
     * @param array $arguments
     * @return object
     */
    public function action($controller, $method, $arguments)
    {
        $object = $this->controller($controller);
        return call_user_func_array([$object, $method], $arguments);
    }

    /**
     * Call The Given Controller
     *
     * @param string $controller
     * @return object
     */
    public function controller($controller)
    {
        $controller = $this->getControllerName($controller);
        if (! $this->hasController($controller)) {
            $this->addController($controller);
        }
        
        return $this->getController($controller);
    }

    /**
     * Determine if the given class|controller exists
     * in the Controllers container
     *
     * @param string $controller
     * @return boolean
     */
    private function hasController($controller)
    {
        return array_key_exists($controller, $this->controllers);
    }

    /**
     * Create new Object From The Given Controller and Store It
     * In The Controllers Container
     *
     * @param string $controller
     * @return void
     */
    private function addController($controller)
    {
        $this->controllers[$controller] = new $controller($this->app);
    }

    /**
     * Get The Controller Object
     *
     * @param string $controller
     * @return object
     */
    private function getController($controller)
    {
        return $this->controllers[$controller];
    }

    /**
     * Get The Full Class|Controller
     *
     * @param string $controller
     * @return string
     */
    private function getControllerName($controller)
    {
        $controller = static::$controllerNamespace . $controller;
        return str_replace('/', '\\', $controller);
    }

   
}
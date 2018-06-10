<?php

namespace System;

class Route
{
    /**
     * Application
     *
     * @var \System\Application
     */
    private $app;

    /**
     * Routes Container
     * 
     * @var array
     */
    private $routes = [];

    /**
     * Not Fount Url
     *
     * @var string
     */
    private $notFound;

    /**
     * Constructor
     * @param \System\Application $app
     */

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Add New Route
     *
     * @param string $url
     * @param string $action
     * @param string $requestMethod
     * @return void
     */
    private function add($url, $action, $requestMethod = 'GET')
    {
        $route = [
            'url'       => $url,
            'pattern'   => $this->generatePattern($url),
            'action'    => $this->getAction($action),
            'method'    => strtoupper($requestMethod),
        ];

        $this->routes[] = $route;
    }

    /**
     * Add Get Route
     *
     * @param string $url
     * @param string $action
     * @param string $requestMethod
     * @return void
     */
    public function get($url, $action)
    {
        $this->add($url, $action, 'GET');
    }

    /**
     * Add New Post Route
     *
     * @param string $url
     * @param string $action
     * @param string $requestMethod
     * @return void
     */
    public function post($url, $action)
    {
        $this->add($url, $action, 'POST');
    }

    /**
     * Set Not Found Url
     *
     * @param string $url
     * @return void
     */
    public function notFound($url)
    {
        $this->notFound = $url;
    }

    /**
     * Generate a regex pattern For The Given url
     *
     * @param string $url
     * @return string
     */
    private function generatePattern($url)
    {
        $pattern = '#^';

        $pattern .= str_replace(['{text}', '{id}'], ['([a-zA-Z0-9-]+)', '(\d+)'], $url);

        $pattern .= '$#';

        return $pattern;
    }

    /**
     * Get Proper Action
     * $action here is refer to controller@action
     *
     * @param string $action
     * @return string
     */
    private function getAction($action)
    {
        $action  = str_replace('/', '\\', $action);

        // if [ @ ] sign Not Found Return index method by default
        return strpos($action, '@') !== false ? $action : $action . '@index';
    }

    /**
     * Get Proper Route
     *
     * @return array
     */
    public function getProperRoute()
    {
        foreach ($this->routes as $route) {
            if ($this->isMatching($route['pattern'])) {
                $arguments = $this->getArgumentsFrom($route['pattern']);

                // controller@method
                [ $controller, $method ] = explode('@', $route['action']);

                return [$controller, $method, $arguments];
            }
        }
    }

    /**
     * Determine If The Given pattern Matches The Current Request Url
     *
     * @param string $pattern
     * @return boolean
     */
    private function isMatching($pattern)
    {
        return preg_match($pattern, $this->app->request->url());
    }

    /**
     * Get Argument From The Current Request Url based On The Given Pattern
     *
     * @param string $pattern
     * @return array
     */
    private function getArgumentsFrom($pattern)
    {
        // get the matches pattern
        preg_match($pattern, $this->app->request->url(), $matches);
        /*
            dd($matches)
            array[
                    0 => "/posts/alaa-dragneel/21"
                    1 => "alaa-dragneel" will match because we use () match group in out pattern
                    2 => "21" will match because we use () match group in out pattern
                ]
         */
        // remove the first element
        array_shift($matches);

        return $matches;
    }

}
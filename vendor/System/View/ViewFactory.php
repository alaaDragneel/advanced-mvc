<?php

namespace System\View;

use System\Application;

class ViewFactory
{
    /**
     * Application Object
     *
     * @var \System\Application
     */
    private $app;

    /**
     * Constructor
     * @param \System\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Render The Given View Path With Passed Variables And Generate new View Object
     *
     * @param string $viewPath
     * @param array $data
     * @return \System\View\ViewInterface
     */
    public function render($viewPath, array $data = [])
    {
        $viewPath = str_replace('.', '/', $viewPath);
        
        return new View($this->app->file, $viewPath, $data);
    }
}
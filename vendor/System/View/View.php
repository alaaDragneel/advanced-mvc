<?php

namespace System\View;

use System\FileSystem;


class View implements ViewInterface
{
    /**
     * File Object
     *
     * @var \System\File
     */
    private $file;

    /**
     * View Path
     * 
     * @var string
     */
    private $viewPath;

    /**
     * Passed Data [ "Variables" ] To the View Path
     * 
     * @var array
     */
    private $data = [];

    /**
     * The Output From The View File
     * 
     * @var string
     */
    private $output;

    /**
     * View Directory Path
     *
     * @var string
     */
    private static $viewDirectoryPath = 'resources\\views\\';

    /**
     * Constructor
     *
     * @param \System\FileSystem $file
     * @param string $viewPath
     * @param array $data
     * @return void
     */
    public function __construct(FileSystem $file, $viewPath, array $data)
    {
        $this->file = $file;

        $this->preparePath($viewPath);

        $this->data = $data;
    }

    /**
     * Prepare View Path
     *
     * @param string $viewPath
     * @return void
     */
    private function preparePath($viewPath)
    {
        $this->viewPath = $this->file->toBasePath(static::$viewDirectoryPath . $viewPath); // its app .php by default

        if (!$this->viewFileExists()) {
            die('<b>' . $this->viewPath . ' View </b>' . ' Doesn\'t Exists In Views Folder');
        }
    }

    /**
     * Determine If The View File Exists
     *
     * @return void
     */
    private function viewFileExists()
    {
        return $this->file->exists($this->viewPath);
    }

    /**
     * {@inheritDoc}
     */
    public function getOutput()
    {
        if (is_null($this->output)) {
            // stop sending the output to render in the browser and store it in buffer
            ob_start();

            // extract all the array keys to be an variables
            extract($this->data);

            // require the view
            require $this->viewPath;

            // Get The Output Form The Buffer, Then Clean It From The Buffer and store it
            $this->output = ob_get_clean();
        }

        return $this->output;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString()
    {
        return $this->getOutput();
    }
}
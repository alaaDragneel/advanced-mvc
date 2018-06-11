<?php

namespace System;

class FileSystem
{
    /**
     * Directory Separator
     * 
     * @const string
     */
    const DS = DIRECTORY_SEPARATOR;

    /**
     * Root Path
     *
     * @var string
     */
    private $root;

    /**
     * Constructor
     *
     * @param string $root
     * @return void
     */
    public function __construct(string $root)
    {
        $this->root = $root;
    }

    /**
     * Determine whether the given file path exists
     *
     * @param string $file
     * @return bool
     */
    public function exists($file)
    {
        return file_exists($file);
    }

    /**
     * Require The Giving file
     *
     * @param string $file
     * @return bool
     */
    public function call($file)
    {
        return require $file;
    }
    
    /**
     * Require The Giving file || files
     *
     * @param string||array $files
     * @return bool
     */
    public function callArray($files)
    {
        if (! is_array($files)) {
            return require $files;       
        }

        foreach ($files as $file) {
            return require $file;       
        }
    }

    /**
     * Generate full path to App directory
     *
     * @param string $path
     * @return bool
     */
    public function toAppPath($path)
    {
        // use lcfirst because app directory is in lower case
        return $this->to(lcfirst($path) . '.php');    
    }

    /**
     * Generate full path to Vendor directory
     *
     * @param string $path
     * @return bool
     */
    public function toVendorPath($path)
    {
        return $this->to('vendor/' . $path . '.php');  
    }
    
    /**
     * Generate Full Path to Base Directory
     *
     * @param string $path
     * @return bool
     */
    public function toBasePath($path)
    {
        return $this->to($path . '.php');  
    }

    /**
     * Generate full path to the giving path
     *
     * @param string $path
     * @return bool
     */
    public function to($path)
    {
        return $this->root . static::DS .  $this->regeneratePath($path);
    }

    public function loadHelpers()
    {
        $helpersPaths = [$this->toVendorPath('systemHelpers')];
        $this->callArray($helpersPaths);
    }

    /**
     * Regenerate Path by convert ['/', '\\'] to DIRECTORY_SEPARATOR
     *
     * @param string $path
     * @return string
     */
    private function regeneratePath($path)
    {
        return str_replace(['/', '\\'], static::DS, $path);
    }
}
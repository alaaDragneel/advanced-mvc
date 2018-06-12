<?php

namespace System;

class Html
{
    /** 
     * Application Object 
     * 
     * @var \System\Application
     */
    protected $app;

    /**
     * Html Title
     *
     * @var string
     */
    private $title;
    
    /**
     * Html Description
     *
     * @var string
     */
    private $description;
    
    /**
     * Html Keywords
     *
     * @var string
     */
    private $keywords;

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
     * Set Title
     * 
     * @param string $title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get Title
     * 
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set Description
     * 
     * @param string $description
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get Description
     * 
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set Keywords
     * 
     * @param string $keywords
     * @return void
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    }

    /**
     * Get Keywords
     * 
     * @return string
     */
    public function getKeywords()
    {
        return $this->keywords;
    }
}


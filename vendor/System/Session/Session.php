<?php

namespace System\Session;

class Session extends SessionManager
{
    /**
     * Application Object
     *
     * @var \System\Application
     */
    private $app;

    /**
     * __construct
     *
     * @param \System\Application $app
     */
    public function __construct(\System\Application $app)
    {
        $this->app = $app;
        $this->sessionSavePath = $this->app->file->to('storage/sessions');
        parent::__construct();
    }

    /**
     * Start Session
     * However if the Session Finger Print Not Valid It Will be Destroyed
     * 
     * @return void
     */
    public function start()
    {
        
        if (! session_id()) {
            session_start();
            $this->startSessionTime();
        }

        if (! $this->isFingerPrintValid()) {
            $this->close();
        }
    }

    /**
     * Set New Value To Session
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set($key, $value)
    {
        $value = is_object($value) ? serialize($value) : $value;
        $_SESSION[$key] = $value;
    }

    /**
     * Get Value From Session By The Given Key
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $value = array_get($_SESSION, $key, $default);

        $data = @unserialize($value);
        
        if ($data === false) return $value;

        return $data;
    }

    /**
     * Determine if The Session Have The Given Key
     * 
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Remove The Given Key From The Session
     * 
     * @param string $key
     * @return void
     */
    public function remove($key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * Get Value From Session By The Given Key And Remove It
     * 
     * @param string $key
     * @return mixed
     */
    public function pull($key)
    {
        $value = $this->get($key);

        $this->remove($key);

        return $value;
    }

    /**
     * Get All Session Data
     * 
     * @return array
     */
    public function all()
    {
        return $_SESSION;
    }

    /**
     * Destroy Session
     *
     * @return void
     */
    public function close()
    {
        $this->destroySession();
    }
}
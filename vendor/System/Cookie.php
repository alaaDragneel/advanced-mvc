<?php

namespace System;

class Cookie
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
    public function __construct(Application $app)
    {
        $this->app = $app;
    }


    /**
     * Set New Value To Cookie
     *
     * @param string $key
     * @param mixed $value
     * @param int $hours
     * @return void
     */
    public function set($key, $value, $hours = 1800)
    {
        $value = is_object($value) ? serialize($value) : $value;
        setcookie($key, $value, time() + $hours * 3600, '', '', false, true);
    }

    /**
     * Get Value From Cookie By The Given Key
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $value = array_get($_COOKIE, $key, $default);

        $data = @unserialize($value);
        
        if ($data === false) return $value;

        return $data;
    }

    /**
     * Determine if The Cookie Have The Given Key
     * 
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $_COOKIE);
    }

    /**
     * Remove The Given Key From The Cookie
     * 
     * @param string $key
     * @return void
     */
    public function remove($key)
    {
        setcookie($key, null, -1);

        unset($_COOKIE[$key]);
    }

    /**
     * Get All Cookie Data
     * 
     * @return array
     */
    public function all()
    {
        return $_COOKIE;
    }

    /**
     * Destroy Cookie
     *
     * @return void
     */
    public function destroy()
    {
        foreach (array_keys($this->all) as $key) {
            $this->remove($key);
        }

        unset($_COOKIE);
    }
}
<?php

use System\Application;

if (! function_exists('dd')) {
    /**
     * Visualize The Given variable in browser
     *
     * NOTE "[ ...$params ]" For PHP 5.6 OR Above = func_get_args();
     * @param mixed $params
     * @return void
     */
    function dd(...$params)
    {
        echo '<pre>';
        array_map(function ($var) {
            var_dump($var);
        }, $params); // $params = func_get_args
        echo '</pre>';
    }
}

if (! function_exists('array_get')) {
    /**
     * Get The Value Form The Given Array From The Given Key if found
     * otherwise get the default value
     * 
     * @param array $array
     * @param string||int $key
     * @param mixed $default
     */
    function array_get($array, $key, $default = null)
    {
        return isset($array[$key]) ? $array[$key] : $default;
    }
}

if (!function_exists('__e')) {
    /**
     * Escape The Given Value
     * Otherwise get The Default Value
     *
     * @param string $value
     * @return string
     */
    function __e($value)
    {
        return htmlspecialchars($value);
    }
}

if (!function_exists('assets')) {
    /**
     * Generate Full Path For The Given Path in The Public Directory
     *
     * @param string $path
     * @return string
     */
    function assets($path)
    {
        $app = Application::getInstance();

        $path = trim($path, '/');
        
        return $app->url->link("public/{$path}");
    }
}

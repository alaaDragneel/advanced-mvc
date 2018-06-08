<?php

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
            print_r($var);
        }, $params); // $params = func_get_args
        echo '</pre>';
    }

}
<?php

namespace System;

use PDO;
use PDOException;

class Database
{
    
    /**
    * Application object
    *
    * @var \System\Application
    */
    private $app;

    /**
    * PDO Connection
    *
    * @var \PDO
    */
    private static $connection;
    /**
    * Constructor
    * @param \System\Application $app
    */
    public function __construct(Application $app)
    {
        $this->app = $app;

        if (! $this->isConnected()) {
            $this->connect();
        }
    }

    /**
    * Determine If There Is Any Connection To Database
    *
    * @return boolean
    */
    private function isConnected()
    {
        return static::$connection instanceof PDO;   
    }

    /**
    * Connect to Database
    *
    * @return void
    */
    private function connect()
    {
        $connectionData = $this->app->file->call($this->app->file->toBasePath('config'));
        
        extract($connectionData);

        try {
            static::$connection = new PDO("{$driver}:host={$server};dbname={$db_name}", $db_user_name, $db_user_password);

        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    /**
    * Get Database Connection Object PDO Object
    *
    * @return \PDO
    */
    public function connection()
    {
        return static::$connection;
    }
}
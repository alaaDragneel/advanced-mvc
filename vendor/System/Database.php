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
     * Table Name
     *
     * @var string
     */
    private $table;

    /**
     * Data Container
     *
     * @var array
     */
    private $data = [];

    /**
     * Bindings Container
     *
     * @var array
     */
    private $bindings = [];

    /**
    * Last Insert id
    *
    * @var int
    */
    private $lastId;

    /**
    * Wheres
    *
    * @var array
    */
    public function __construct(Application $app)
    {
        $this->app = $app;

        if (!$this->isConnected()) {
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

            static::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

            static::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            static::$connection->exec('SET NAMES utf8mb4');

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

    /**
     * Set The Table Name
     *
     * @param string $table
     * @return $this
     */
    public function table($table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * Set The Table Name
     *
     * @param string $table
     * @return $this
     */
    public function from($table)
    {
        return $this->table($table);
    }

    /**
     * Set The Data That Will Be Stored in Database Table
     *
     * @param mixed $key
     * @param mixed $value
     * @return $this
     */
    public function data($key, $value = null)
    {
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
            // here we pass an key array of associative array but addToBindings work with indexed array to we add array_values 
            // to get only the values
            $this->addToBindings($key); 
        } else {
            $this->data[$key] = $value;
            $this->addToBindings($value); 
        }

        return $this;
    }

    /**
     * Set The Data That Will Be Stored in Database Table
     *
     * @param mixed $key
     * @param mixed $value
     * @return $this
     */
    public function fill($key, $value = null)
    {
        $this->data($key, $value);

        return $this;
    }

    /**
     * Insert Data To Database
     *
     * @param $table
     * @return $this
     */
    public function insert($table = null)
    {
        if ($table) {
            $this->table($table);
        }

        $sql = "INSERT INTO {$this->table} SET ";

        $sql .= $this->setFields();

        $this->query($sql, $this->bindings);

        $this->lastId = static::$connection->lastInsertId();


        return $this;
    }

    /**
     * Set And Insert The Data That Will Be Stored in Database Table
     *
     * @param mixed $key
     * @param mixed $value
     * @return $this
     */
    public function create($key, $value = null)
    {
        $this->data($key, $value);
        $this->insert();

        return $this;
    }

    /**
     * Set And Insert The Data That Will Be Stored in Database Table
     *
     * @param mixed $key
     * @param mixed $value
     * @return $this
     */
    public function save($key, $value = null)
    {
        $this->create($key, $value);

        return $this;
    }


    /**
     * Update Data in Database
     *
     * @param $table
     * @return $this
     */
    public function update($table = null, array $data = [])
    {
        if ($table) {
            $this->table($table);
        }

        if (count($data) > 0) {
            $this->data($data);
        }

        $sql = "UPDATE {$this->table} SET";

        $sql .= $this->setFields();
        
        $this->query($sql, $this->bindings);
        

        return $this;
    }

    /**
     * Set Fields For Insert And UPDATE
     *
     * @return string
     */
    private function setFields()
    {
        $sql = " ";

        foreach (array_keys($this->data) as $key) {
            $sql .= " `{$key}` = ? , ";
        }

        // remove the last "[ Comma ]"
        $sql = rtrim($sql, ' , ');
        
        if ($this->wheres) {
            foreach ($this->wheres as $key => $value) {
                if ($key == 0) {
                    $sql .= " WHERE {$value} ";
                } else {
                    $sql .= " AND {$value} ";
                }
            }
        }

        return $sql;
    }

    /**
     * Add New Where Clause
     *
     * @return $this
     */
    public function where(...$bindings)
    {
        $sql = array_shift($bindings);

        $this->addToBindings($bindings);

        // $this->wheres[] = "{$sql} = ? ";
        $this->wheres[] = $sql;

        return $this;
    }

    /**
     * Execute the Given Sql Statement
     *
     * @return \PDOStatement
     */
    public function query(...$bindings)
    {
        $sql = array_shift($bindings);

        if (count($bindings) == 1 && is_array($bindings[0])) {
            $bindings = $bindings[0];
        }

        try {
            
            $query = $this->connection()->prepare($sql);

            foreach ($bindings as $key => $value) {
                $query->bindValue($key + 1, __e($value));
            }

            $query->execute();

        } catch (PDOException $e) {
            dd($sql, $this->bindings);
            die($e->getMessage());
        }


        return $query;
    }

    /**
     * Get Last Insert Id
     *
     * @return int
     */
    public function lastId()
    {
        return $this->lastId;
    }

    /**
     * Add Given Value To Bindings
     *
     * @param mixed $value
     * @return void
     */
    private function addToBindings($value)
    {
        if (is_array($value)) {
            // merge the old bindings with the new values to not overwrite the old bindings with the new bindings
            // addToBindings work with integer to we add array_values 
            $this->bindings = array_merge($this->bindings, array_values($value));
        } else {
            $this->bindings[] = $value;
        }
    }

}
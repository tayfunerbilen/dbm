<?php

trait Database {

    public function database($dbName)
    {
        $this->dbName = $dbName;
        $this->type = 'db';
        return $this;
    }

    public function use($dbName)
    {
        $query = $this->connection->query('USE ' . $dbName)->fetch();
        return $query;
    }

}

trait Column {

    public function column($name)
    {
        $this->columnName = $name;
        return $this;
    }

    public function int($l = 11)
    {
        $this->columnType = 'int(' . $l . ')';
        return $this;
    }

    public function varchar($l = 255)
    {
        $this->columnType = 'varchar(' . $l . ')';
        return $this;
    }

    public function text()
    {
        $this->columnType = 'text';
        return $this;
    }

    public function ai()
    {
        $this->columnAI = 'AUTO_INCREMENT';
        $this->columnPK = 'PRIMARY KEY (' . $this->columnName . ')';
        return $this;
    }

    public function add()
    {
        $column = $this->columnName . ' ' . $this->columnType . ' ' . $this->columnAI;
        $this->columnName = '';
        $this->columnType = '';
        $this->columnAI = null;
        return $column;
    }
}

trait Table {

    public function table($tableName)
    {
        $this->tableName = $tableName;
        $this->type = 'table';
        return $this;
    }

    public function columns($columns)
    {
        $this->sql = implode(', ', $columns);
        if ($this->columnPK){
            $this->sql .= ', ' . $this->columnPK;
            $this->columnPK = null;
        }
        return $this;
    }
    
}

class Dbm {

    use Database, Table, Column;

    private $connection;
    private $type;
    private $dbName;
    private $tableName;
    private $sql;
    private $columnName;
    private $columnType;
    private $columnAI = null;
    private $columnPK = null;
    private $columns = [];

    public function __construct($username, $password = '')
    {
        $this->connection = new PDO('mysql:host=localhost', $username, $password);
    }

    public function create()
    {
        Switch ($this->type){
            case 'db':
                $this->sql = 'CREATE DATABASE IF NOT EXISTS ' . $this->dbName;
            break;
            case 'table':
                $columns = $this->sql;
                $this->sql = 'CREATE TABLE IF NOT EXISTS ' . $this->tableName . ' (' . $columns . ')';
            break;
        }
        $query = $this->connection->query($this->sql)->fetch(PDO::FETCH_ASSOC);
        return $query;
    }

    public function delete()
    {
        Switch ($this->type){
            case 'db':
                $this->sql = 'DROP DATABASE IF EXISTS ' . $this->dbName;
            break;
            case 'table':
                $this->sql = 'DROP TABLE IF EXISTS ' . $this->tableName;
            break;
        }
        $query = $this->connection->query($this->sql)->fetch(PDO::FETCH_ASSOC);
        return $query;
    }

}

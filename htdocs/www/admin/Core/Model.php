<?php
/**
 *
 *
 *                                              ........................
 *                                           .....',;,,;;;;;,,''...........
 *                                         ..'..........'',;;;;,'........''..
 *                                        .'..................,,,,'.......';,.
 *                                      .''..'''.','..''........',;,,'.....';;'.
 *                                     .,,';;;;;,;;;;;;;;,''......';;'......,;;'.
 *                                   .,;:;;;,''..'...''',,,,,,'.....;;,'.....;;'''.
 *                                 .':;;,.'.....'.'''''......',,,...',;,.....,;,..'.
 *                                .;:'''....'',;;;,'......     ..''..';;'....';,'...'.
 *                               .','....'',;;;,'..              .....';,'...';,.'...'.
 *                              .'.'..'.';:;,'....                  ..';,....';,'..'..'.
 *                              '' .'.',:;,..''..                     .,'....,;''...'.'.
 *                              .'..'';:;'..','.                      .''...,;,..'..',,.
 *                              .'..,,c;...';;.                       .'...';,....'.':,.
 *                               .'.,c;...',:,.                       ....';,'....',;:.
 *                               .',;c,...':;...                      ..',,,......,:;,.
 *                                .;c;....,:;..'.                    .',,,'.....,,:,,.
 *                                .;c;....,:;..',.                 ..',''.....';;;'''.
 *                                 ,c,....,:;...,;'...          ..........'',;;,,'.'.
 *                                 .:;...'';:,...,;,..................',,,;,,,..'..'.
 *                                  ';'...',::'..',;;,...'''',,,,,,,,,;,,,'''..'. ''
 *                                  .'....'',::...'',;;,'......''..''......'..'...'.
 *                                   ..'....';::'....',;;;,''................,,.'..
 *                                     .....'',:c;'......',,;;,;;;,,,,,,;;;;;'...
 *                                         ....';c:,..........',;;;,,;:;;,'..
 *                                             ...';:;'...........''....
 *                                                  .',,,,..........
 *                                                      ........
 *
 * Copyright (c) 2019 Pedro Mello.
 *
 */

abstract class Model
{
    //// Static ////

    private static $connectionPDO;

    protected static function getConnectionPDO() : PDO
    {
        if(!isset(self::$connectionPDO)){

            if( in_array( $_SERVER['REMOTE_ADDR'], array( '127.0.0.1', '::1' ) ) ) {
                $databaseConfig = DATABASE_CONFIG::$staging;
            } else {
                $databaseConfig = DATABASE_CONFIG::$production;
            }

            extract($databaseConfig);
            self::$connectionPDO = new PDO("mysql:host=$host;dbname=$banco", "$login", "$senha", array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'));

            return self::$connectionPDO;
        }
        return self::$connectionPDO;
    }

    protected static function getModelName() : string
    {
        return get_called_class();
    }

    protected static function getTableName() : ?string
    {
        if(static::$tableName != "")
            return static::$tableName;
        return null;
    }

    protected static function getIDName() : ?string
    {
        if(self::getTableName())
            return "id_".self::getTableName();
    }

    protected static function getBindName($columnName) : string
    {
        return ":{$columnName}";
    }

    ///// QUERY METHODS /////

    protected static function getQuerySetup($limit = null, $orderBy = null) : string
    {
        $orderBy = (!is_null($orderBy)) ? " ORDER BY {$orderBy} " : " ";
        $limit = (!is_null($limit)) ? " LIMIT {$limit} " : " ";
        return "{$orderBy} {$limit}";
    }


    ///// STATIC METHODS /////

    public static function getAll($limit = null, $orderBy = null, $debug = false) : ?array
    {
        $tableName = self::getTableName();
        $querySetup = self::getQuerySetup($limit, $orderBy);

        $query = "SELECT * FROM {$tableName} {$querySetup}";

        if($debug) self::debug($query);

        $sth = self::getConnectionPDO()->prepare($query);
        $sth->execute();

        $result = $sth->fetchAll(PDO::FETCH_ASSOC);

        if($result){
            $models = array();
            foreach ($result as $data){
                $modelName = self::getModelName();
                $models[] = new $modelName($data);
            }
            return $models;
        }
        return null;
    }

    public static function getBy($columnName, $value, $limit = null, $orderBy = null, $debug = false) : ?Object
    {
        $tableName = self::getTableName();
        $bindName = self::getBindName($columnName);
        $querySetup = self::getQuerySetup($limit, $orderBy);

        $query = "SELECT * FROM {$tableName} ";
        $query .= "WHERE {$columnName} = {$bindName} {$querySetup}";

        if($debug) self::debug($query);

        $sth = self::getConnectionPDO()->prepare($query);
        $sth->bindParam($bindName, $value);
        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_ASSOC);

        if($result){
            $modelName = self::getModelName();
            return new $modelName($result);
        }
        return null;
    }

    public static function getAllBy($columnName, $value, $limit = null, $orderBy = null, $debug = false) : ?array
    {
        $tableName = self::getTableName();
        $bindName = self::getBindName($columnName);
        $querySetup = self::getQuerySetup($limit, $orderBy);

        $query = "SELECT * FROM {$tableName} ";
        $query .= "WHERE {$columnName} = {$bindName} {$querySetup}";

        if($debug) self::debug($query);

        $sth = self::getConnectionPDO()->prepare($query);
        $sth->bindParam($bindName, $value);
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);

        if($result){
            $models = array();
            foreach ($result as $data){
                $modelName = self::getModelName();
                $models[] = new $modelName($data);
            }
            return $models;
        }
        return null;
    }

    public static function consult($query, $debug = false) : ?array
    {
        if($debug) self::debug($query);

        return self::getConnectionPDO()->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    protected static function debug($query) : void
    {
        echo "<hr/><pre>$query</pre><hr/>";
    }

    ///// OBJECTS METHODS /////

    private $schema = array();

    public function __construct($newSchema, $data = null)
    {

        foreach ($newSchema as $columnName => $type) {
            $this->schema[$columnName] = array("value" => null, "type" => $type);
        }

        if($data){
            foreach ($data as $columnName => $value){
                $this->schema[$columnName]["value"] = $value;
            }
        }
    }

    public function __set($name, $value)
    {
        if(array_key_exists($name, $this->schema))
            $this->schema[$name]["value"] = $value;
    }

    public function __get($name)
    {
        if(array_key_exists($name, $this->schema))
            return $this->schema[$name]["value"];
    }

    private function getInsertQuery() : string
    {
        $tableName = self::getTableName();
        $idName = self::getIDName();
        $idBindName = self::getBindName($idName);

        foreach($this->schema as $columnName => $data){
            if($columnName != $idName && !is_null($data["value"])){
                $columnNames[] = $columnName;
                $bindNames[] = self::getBindName($columnName);
            }
        }

        $columns = implode(', ', $columnNames);
        $binds = implode(', ', $bindNames);

        $query = "INSERT INTO {$tableName} ";
        $query .= "({$columns}) VALUES ({$binds})";

        return $query;
    }

    private function getUpdateQuery() : string
    {
        $tableName = self::getTableName();
        $idName = self::getIDName();
        $idBindName = self::getBindName($idName);

        foreach($this->schema as $columnName => $data){
            if($columnName != $idName && !is_null($data["value"])){
                $bindName = self::getBindName($columnName);
                $filledColumns[] = "{$columnName} = {$bindName}";
            }
        }

        $set = implode(', ', $filledColumns);

        $query = "UPDATE {$tableName} ";
        $query .= "SET {$set} ";
        $query .= "WHERE {$idName} = {$idBindName}";

        return $query;
    }

    public function save($debug = false) : bool
    {
        $idName = self::getIDName();

        if(!is_null($this->schema[$idName]["value"])){
            $query = self::getUpdateQuery();
        }else{
            $query = self::getInsertQuery();
        }

        if($debug) self::debug($query);

        try {
            $sth = self::getConnectionPDO()->prepare($query);

            foreach ($this->schema as $columnName => $data) {
                if(!is_null($data["value"])){
                    $sth->bindValue(self::getBindName($columnName) , $data["value"], $data["type"]);
                }
            }

            $sth->execute();
        }catch (PDOException $e){
            echo $e->getMessage();
            return false;
        }
        return true;
    }


    public function delete($debug = false) : bool
    {
        $idName = self::getIDName();

        if(!is_null($this->schema[$idName]["value"])){
            $tableName = self::getTableName();
            $bindName = self::getBindName($idName);

            $query = "DELETE FROM {$tableName} WHERE {$idName} = {$bindName}";

            if($debug) self::debug($query);

            try {
                $sth = self::getConnectionPDO()->prepare($query);
                $sth->bindParam($bindName, $this->schema[$idName]["value"]);
                $sth->execute();
            }catch (PDOException $e){
                echo $e->getMessage();
                return false;
            }
            return true;
        }
        return false;
    }
}












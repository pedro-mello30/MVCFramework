<?php
class Usuarios_ModelA extends ModelA
{

    protected static $tableName = "admin_users";


    public function __construct($data = null)
    {
        $idName = self::getIDName();

        $schema = array(
            $idName => PDO::PARAM_INT,
            "name" => PDO::PARAM_STR,
            "email" => PDO::PARAM_STR,
            "username" => PDO::PARAM_STR,
            "password" => PDO::PARAM_STR
        );
        parent::__construct($schema, $data);

    }

}
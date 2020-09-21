<?php
class Usuarios_Model extends Model
{
    protected static $tableName = "admin_users";
    protected static $tableLoginHistory = "history_login";

    public function __construct($data = null)
    {
        $idName = self::getIDName();

        $schema = array(
            $idName => PDO::PARAM_INT,
            "name" => PDO::PARAM_STR,
            "email" => PDO::PARAM_STR,
            "username" => PDO::PARAM_STR,
            "date_initiated" => PDO::PARAM_STR,
            "status" => PDO::PARAM_STR,
        );
        parent::__construct($schema, $data);
    }

    public static function getAll() : ?array
    {
        $return = parent::getAll();
        foreach ($return as $r)
        {
            unset($r->schema["password"]);
            unset($r->schema["token"]);
        }

        return $return;
    }

    public static function getLoginHistory($id) : ?array
    {
        $idName = self::getIDName();
        $return = LoginHistory_Model::getAllBy($idName, $id);

        return $return;
    }


}
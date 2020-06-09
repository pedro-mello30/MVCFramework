<?php
class Contato_Model extends Model
{
    protected static $tableName = "contato";

    public function __construct($data = null)
    {
        $idName = self::getIDName();

        $schema = array(
            $idName => PDO::PARAM_INT,
            "nome" => PDO::PARAM_STR,
            "email" => PDO::PARAM_STR,
            "mensagem" => PDO::PARAM_STR,
            "data" => PDO::PARAM_STR
        );
        parent::__construct($schema, $data);
    }
}
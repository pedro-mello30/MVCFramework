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
 * Copyright (c) 2020 Pedro Mello.
 *
 */


class AuthHelperA
{

    private static $tableName = 'admin_users';
    private static $nameColumn = 'name';
    private static $emailColumn = 'email';
    private static $usernameColumn = 'username';
    private static $passwordColumn = 'password';
    private static $tokenColumn = 'token';
    private static $dateColumn = 'date_initiated';
    private static $statusColumn = 'status';
    private static $controllerSuccess = '';
    private static $actionSuccess = '';
    private static $controllerError = '';
    private static $actionError = '';

    private static $connectionPDO;

    private static function getConnectionPDO() : PDO
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

    private static function consultLine($query, $debug = false) : ?array
    {
        if($debug) self::debug($query);

        $sth = self::getConnectionPDO()->prepare($query);
        $sth->execute();
        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    private static function encryptPassword($password) : string
    {
        return hash('sha512', $password);
    }

    private static function debug($query) : void
    {
        echo "<hr/><pre>$query</pre><hr/>";
    }

    public static function signIn($user, $password)
    {

        $querySelect = self::$emailColumn . "," . self::$usernameColumn . "," . self::$statusColumn;
        $queryUser = self::$usernameColumn. " = '{$user}' OR ".self::$emailColumn." = '{$user}'";
        $queryPassword = self::$passwordColumn . " = '" . self::encryptPassword($password) . "'";

        $query = "SELECT {$querySelect} FROM " . self::$tableName;
        $query .= " WHERE ({$queryUser}) AND {$queryPassword}";

        $_SESSION['user'] = self::consultLine($query, true);

    }

    public static function signUp($name, $email, $username, $password)
    {
        $statusRegister = '1';
        $queryColumns = "(".self::$nameColumn . ", " . self::$emailColumn . ", " . self::$usernameColumn;
        $queryColumns .= self::$passwordColumn . ", " . self::$statusColumn . ", " . self::$dateColumn . ")";

        $queryValues = "({$name}, {$email}, {$username},";
        $queryValues .= self::encryptPassword($password) . ", {$statusRegister}, " . date("Y-n-d") . ")";


    }
}
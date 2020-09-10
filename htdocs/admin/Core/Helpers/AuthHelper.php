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

class AuthHelper
{
    private static $userTableName = 'admin_users';
    private static $idColumn = 'id_admin_users';
    private static $nameColumn = 'name';
    private static $emailColumn = 'email';
    private static $usernameColumn = 'username';
    private static $passwordColumn = 'password';
    private static $tokenColumn = 'token';
    private static $dateColumn = 'date_initiated';
    private static $statusColumn = 'status';

    private static $controllerSuccess = 'home';
    private static $actionSuccess = '';
    private static $controllerError = 'home';
    private static $actionError = 'login';

    private static $actionsExceptions = array();

    private static $historyLoginTable = "history_login";

    private static $sessionFieldLoggedIn = "loggedIn";

    private static $connectionPDO;

    const STATUS = array(
        "normal" => 0,
        "peding_review" => 1,
        "suspended" => 2
    );

    /// Database methods ///
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

    private static function consult($query, $debug = false) : ?array
    {
        if($debug) self::debug($query);

        $sth = self::getConnectionPDO()->prepare($query);
        $sth->execute();
        $return =  $sth->fetchAll(PDO::FETCH_ASSOC);
        return ($return) ? $return : null;
    }

    private static function consultLine($query, $debug = false) : ?array
    {
        if($debug) self::debug($query);

        $sth = self::getConnectionPDO()->prepare($query);
        $sth->execute();
        $return = $sth->fetch(PDO::FETCH_ASSOC);
        return ($return) ? $return : null;
    }

    private function buildInsertQuery($tableName, $data) : string
    {

        foreach($data as $columnName => $value){
            if(!is_null($value)){
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

    private function updateDatabase($tableName, $data) : bool
    {
        $idName = self::$idColumn;
        $idBindName = self::getBindName($idName);

        foreach($data as $columnName => $value){
            if($columnName != $idName && !is_null($value)){
                $bindName = self::getBindName($columnName);
                $filledColumns[] = "{$columnName} = {$bindName}";
            }
        }

        $set = implode(', ', $filledColumns);

        $query = "UPDATE {$tableName} ";
        $query .= "SET {$set} ";
        $query .= "WHERE {$idName} = {$idBindName}";

        $sth = self::getConnectionPDO()->prepare($query);

        try{
            foreach ($data as $columnName => $data) {
                if(!is_null($data)){
                    $sth->bindValue(self::getBindName($columnName) , $data);
                }
            }
            $sth->execute();
        }catch (PDOException $e){
            echo $e->getMessage();
            return false;
        }
        return true;
    }

    private function insertIntoDatabase($tableName, $data) : bool
    {

        $sth = self::getConnectionPDO()->prepare(self::buildInsertQuery($tableName, $data));

        try{
            foreach ($data as $columnName => $data) {
                if(!is_null($data)){
                    $sth->bindValue(self::getBindName($columnName) , $data);
                }
            }
            $sth->execute();
        }catch (PDOException $e){
            echo $e->getMessage();
            return false;
        }
        return true;
    }

    protected static function getBindName($columnName) : string
    {
        return ":{$columnName}";
    }

    private static function debug($query) : void
    {
        echo "<hr/><pre>$query</pre><hr/>";
    }

    ///  ///
    public static function signUp($name, $email, $username, $password) : bool
    {
        if(self::existEmail($email) || self::existUsername($username))
            return false;

        $newUser = array(
            self::$nameColumn => $name,
            self::$emailColumn => $email,
            self::$usernameColumn => $username,
            self::$passwordColumn => self::encryptPassword($password),
            self::$tokenColumn => self::generateToken($email),
            self::$statusColumn => self::STATUS['peding_review'] ,
            self::$dateColumn => date("Y-n-d")
        );

        self::sendConfirmEmail($newUser);
        return self::insertIntoDatabase(self::$userTableName, $newUser);
    }

    public static function signIn($user, $password) : void
    {
        $queryUser = self::$usernameColumn. " = '{$user}' OR ".self::$emailColumn." = '{$user}'";
        $queryPassword = self::$passwordColumn . " = '" . self::encryptPassword($password) . "'";

        $query = "SELECT * FROM " . self::$userTableName;
        $query .= " WHERE ({$queryUser}) AND {$queryPassword}";

        $user = self::consultLine($query, false);

        if($user){
            unset($user[self::$passwordColumn]);
            $user[self::$sessionFieldLoggedIn] = true;

            self::registerSignIn($user[self::$idColumn]);

            $_SESSION['user'] = $user;
            RedirectHelper::goToControllerAction(self::$controllerSuccess, self::$actionSuccess);
        }

        RedirectHelper::goToControllerAction(self::$controllerError, self::$actionError);
    }

    public static function signOut() : void
    {
        if(self::isLoggedIn()){
            session_destroy();
            session_regenerate_id();
        }
        RedirectHelper::goToControllerAction(self::$controllerError, self::$actionError);
    }

    public static function confirmEmail($token) : bool
    {
        $consultQuery = "SELECT ".self::$idColumn." FROM ".self::$userTableName." WHERE ".self::$tokenColumn."='{$token}'";
        $user = self::consultLine($consultQuery);

        if($user){
            $data = array(
                self::$idColumn => $user[self::$idColumn],
                self::$tokenColumn => "",
                self::$statusColumn => self::STATUS["normal"]
            );

            return self::updateDatabase(self::$userTableName, $data);
        }
        return false;
    }

    private static function sendConfirmEmail($user) : void
    {
        $variables = array(
            "name" => $user[self::$nameColumn],
            "url" => "http://localhost:8001/home/confirmEmail/" . $user[self::$tokenColumn]
        );

        EmailHelper::make()
            ->addAddress($user[self::$emailColumn], $user[self::$nameColumn])
            ->setSubject("Confirm email")
            ->setAltMessage("This is a 70e7 message.")
            ->setMessageFromHtmlFile("ConfirmEmail.phtml", $variables)
            ->send();
    }

    public static function fogotPassword($email) : bool
    {
        $userId = self::existEmail($email);
        if(!$userId)
            return false;

        $stringToken = $userId.$email;
        $data = array(
            self::$idColumn => $userId,
            self::$emailColumn => $email,
            self::$tokenColumn => self::generateToken($stringToken)
        );

        self::sendForgotPasswordEmail($data);
        return self::updateDatabase(self::$userTableName, $data);
    }

    private static function sendForgotPasswordEmail($user) : void
    {
        $variables = array(
            "name" => '$user[self::$nameColumn]',
            "url" => "http://localhost:8001/home/resetPassword/" . $user[self::$tokenColumn]
        );

        EmailHelper::make()
            ->addAddress($user[self::$emailColumn], "name")
            ->setSubject("Forgot password email.")
            ->setAltMessage("This is a 70e7 message.")
            ->setMessageFromHtmlFile("forgotPasswordEmail.phtml", $variables)
            ->send();
    }

    public static function changePasswordWithToken($token, $newPassword) : bool
    {
        $consultQuery = "SELECT ".self::$idColumn." FROM ".self::$userTableName." WHERE ".self::$tokenColumn."='{$token}'";
        $user = self::consultLine($consultQuery);

        if($user){
            $data = array(
                self::$idColumn => $user[self::$idColumn],
                self::$tokenColumn => "",
                self::$passwordColumn => self::encryptPassword($newPassword)
            );

            return self::updateDatabase(self::$userTableName, $data);
        }

        return false;
    }

    ///  ///
    public static function setActionsExceptions($newActionsExceptions) : void
    {
        self::$actionsExceptions = $newActionsExceptions;
    }

    public static function addActionExcept($newActionExcept) : void
    {
        self::$actionsExceptions[] = $newActionExcept;
    }

    public static function checkLogin() : void
    {
        if(!self::isLoggedIn())
            if(!in_array(RedirectHelper::getCurrentAction(), self::$actionsExceptions))
                RedirectHelper::goToControllerAction(self::$controllerError, self::$actionError);
    }

    /// Utils methods ///

    public static function isLoggedIn() : bool
    {
        return (isset($_SESSION["user"][self::$sessionFieldLoggedIn]) && $_SESSION["user"][self::$sessionFieldLoggedIn] == true) ? true : false;
    }

    public static function existEmail($email) : ?int
    {
        $query = "SELECT ".self::$idColumn.",".self::$emailColumn." FROM ".self::$userTableName." WHERE ".self::$emailColumn."='{$email}'";
        $user = self::consultLine($query);
        return ($user)? $user[self::$idColumn] : null;
    }

    public static function existUsername($username) : ?int
    {
        $query = "SELECT ".self::$idColumn.",".self::$usernameColumn." FROM ".self::$userTableName." WHERE ".self::$usernameColumn."='{$username}'";
        $user = self::consultLine($query);
        return ($user)? $user[self::$idColumn] : null;
    }

    private static function generateToken($string) : string
    {
        return md5($string . time());
    }

    private static function encryptPassword($password) : string
    {
        return hash('sha512', $password);
    }

    /// History login methods ///

    private static function registerSignIn($idUser) : void
    {
        $newSignIn = array(
            self::$idColumn => $idUser,
            "date" => date("Y-n-d"),
            "time" => date("H:i:s"),
            "user_ip" => self::getUserIP(),
            "user_os" => self::getUserOS(),
            "user_browser" => self::getUserBrowser()
        );

        self::insertIntoDatabase(self::$historyLoginTable, $newSignIn);
    }

    public static function getLoginHistory() : ?array
    {
        if(!self::isLoggedIn())
            return null;

        $query = "SELECT * FROM " .self::$historyLoginTable;
        $query .= " WHERE " . self::$idColumn . "=" . $_SESSION["user"][self::$idColumn];

        return self::consult($query);
    }

    private static function getUserIP() : string
    {
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    private static function getUserOS() : string
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $userOS = "Unknown OS Platform";
        $regexArray = array(
            '/windows nt 10/i'     =>  'Windows 10',
            '/windows nt 6.3/i'     =>  'Windows 8.1',
            '/windows nt 6.2/i'     =>  'Windows 8',
            '/windows nt 6.1/i'     =>  'Windows 7',
            '/windows nt 6.0/i'     =>  'Windows Vista',
            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
            '/windows nt 5.1/i'     =>  'Windows XP',
            '/windows xp/i'         =>  'Windows XP',
            '/windows nt 5.0/i'     =>  'Windows 2000',
            '/windows me/i'         =>  'Windows ME',
            '/win98/i'              =>  'Windows 98',
            '/win95/i'              =>  'Windows 95',
            '/win16/i'              =>  'Windows 3.11',
            '/macintosh|mac os x/i' =>  'Mac OS X',
            '/mac_powerpc/i'        =>  'Mac OS 9',
            '/linux/i'              =>  'Linux',
            '/ubuntu/i'             =>  'Ubuntu',
            '/iphone/i'             =>  'iPhone',
            '/ipod/i'               =>  'iPod',
            '/ipad/i'               =>  'iPad',
            '/android/i'            =>  'Android',
            '/blackberry/i'         =>  'BlackBerry',
            '/webos/i'              =>  'Mobile'
        );

        foreach ($regexArray as $regex => $value) {
            if (preg_match($regex, $userAgent)) {
                $userOS    =   $value;
            }
        }
        return $userOS;
    }

    private static function getUserBrowser() : string
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $browser        =   "Unknown Browser";
        $regexArray  =   array(
            '/msie/i'       =>  'Internet Explorer',
            '/firefox/i'    =>  'Firefox',
            '/safari/i'     =>  'Safari',
            '/chrome/i'     =>  'Chrome',
            '/edge/i'       =>  'Edge',
            '/opera/i'      =>  'Opera',
            '/netscape/i'   =>  'Netscape',
            '/maxthon/i'    =>  'Maxthon',
            '/konqueror/i'  =>  'Konqueror',
            '/mobile/i'     =>  'Mobile Browser'
        );

        foreach ($regexArray as $regex => $value) {
            if (preg_match($regex, $userAgent)) {
                $browser    =   $value;
            }
        }
        return $browser;
    }
}





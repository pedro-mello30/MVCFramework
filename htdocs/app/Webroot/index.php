<?php


//   print_r($_SERVER);
	error_reporting(1);
	session_start();

	date_default_timezone_set("Brazil/East");

    if (get_magic_quotes_gpc()) {
        function magicQuotes_awStripslashes(&$value, $key) {$value = stripslashes($value);}
        $gpc = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
        array_walk_recursive($gpc, 'magicQuotes_awStripslashes');
    }

	if (!defined('DS'))
		define('DS', DIRECTORY_SEPARATOR);

	if (!defined('ROOT'))
		define('ROOT', dirname(dirname(dirname(__FILE__))));

	if (!defined('APP_DIR'))
		define('APP_DIR', basename(dirname(dirname(__FILE__))));

	$_SERVER['PHP_SELF'] = str_replace("app/Webroot/index.php", "", $_SERVER['PHP_SELF']);

	define('URL', $_SERVER['PHP_SELF'] );
	define('URL_ADMIN', $_SERVER['PHP_SELF'] . "admin/");
	define('IMG', URL . "app/img/");
	define('JS', URL . "app/js/");
	define('CSS', URL . "app/css/");
	define('FULL_URL', "http://" . $_SERVER['SERVER_NAME'] . URL);

	define( 'LIB', '../../app/Lib/' );
	define( 'FILES',  '../../app/Webroot/files/' );
	define( 'CONTROLLERS', '../../app/Controllers/' );
	define( 'VIEWS', '../../app/Views/' );
	define( 'LAYOUT', '../../app/Views/Layouts/' );
	define( 'MODELS', '../../app/Models/' );
	define( 'HELPERS', '../../app/Core/Helpers/' );
	define( 'CORE', '../../app/Core/' );
	define( 'CONFIG', '../../app/Config/' );

	function __autoload($file)
	{
		if( file_exists(MODELS . $file . ".php"))
			require_once( MODELS . $file . ".php");
		else if( file_exists(HELPERS . $file . ".php"))
			require_once( HELPERS . $file . ".php");
		else if( file_exists(HELPERS . "/Email/Lib/" . $file . ".php"))
			require_once( HELPERS . "/Email/Lib/" . $file . ".php");
		else if( file_exists(CORE . $file . ".php"))
			require_once( CORE . $file . ".php");
		else if( file_exists(CONTROLLERS . $file . "Controller.php"))
			require_once( CONTROLLERS . $file . "Controller.php");
		else if( file_exists(LIB . $file . ".php"))
			require_once( LIB . $file . ".php");
		else if( $file == "DATABASE_CONFIG")
			require_once(CONFIG . "database.php");
		else if( $file == "CRUD")
			require_once( CONTROLLERS . $file.".php");
		else
			die($file . " não encontrado" );
	}

	 $core = new Core();
	 $core -> start();

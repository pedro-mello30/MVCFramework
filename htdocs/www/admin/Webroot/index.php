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

	$_SERVER['PHP_SELF'] = str_replace("admin/Webroot/index.php", "", $_SERVER['PHP_SELF']);

	define('URL', $_SERVER['PHP_SELF'] );
	define('URL_ADMIN', $_SERVER['PHP_SELF'] . "admin/");
	define('IMG', URL . "admin/img/");
	define('JS', URL . "admin/js/");
	define('CSS', URL . "admin/css/");
	define('FULL_URL', "http://" . $_SERVER['SERVER_NAME'] . URL);

	define( 'LIB', '../../admin/Lib/' );
	define( 'FILES',  '../../admin/Webroot/files/' );
	define( 'CONTROLLERS', '../../admin/Controllers/' );
	define( 'VIEWS', '../../admin/Views/' );
	define( 'LAYOUT', '../../admin/Views/Layouts/' );
	define( 'MODELS', '../../admin/Models/' );
	define( 'HELPERS', '../../admin/Core/Helpers/' );
	define( 'CORE', '../../admin/Core/' );
	define( 'CONFIG', '../../admin/Config/' );

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
			require_once( CONFIG . "database.php");
		else if( $file == "CRUD")
			require_once( CONTROLLERS . $file.".php");
		else
			die($file . " não encontrado" );
	}

	 $core = new Core();
	 $core -> start();

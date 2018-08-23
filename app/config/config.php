<?
session_start();

/**
*    Archivo que contiene las variables de configuración del MVC
*/
error_reporting(E_ALL);
ini_set('display_errors', 1);

defined('DOMAIN') or define('DOMAIN', $_SERVER['SERVER_NAME']);
defined('SITE_VERSION') or define('SITE_VERSION', '1.0');
require_once('globals.php');

//Por defecto el usuario no está registrado
if(!isset($GLOBALS['REGISTERED_USER']) || empty($GLOBALS['REGISTERED_USER'])){
  $GLOBALS['REGISTERED_USER'] = false;
}

$GLOBALS['DEFAULT_CONTROLLER'] = 'Home/Home';

//conexión con la base de datos
defined('DATABASE') or define('DATABASE', array(
  'db_type' => 'mysql',
  'db_host' => 'localhost',
  'db_port' => '3306',
  'db_user' => 'root',
  'db_pass' => 'root',
  'db_name' => 'ROOT',
  'db_charset' => 'UTF-8'
));

if(class_exists( 'Controller' ) === false ){
  include_once(CORE_FOLDER.DIRECTORY_SEPARATOR.'Controller.php');
}

?>

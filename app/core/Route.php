<?
/**
*    Clase encargada de parsear la url
*    Obtiene el controlador, la función y los parámetros
*    Carga el controlador, llama a la función y le pasa los parámetros
*
*/

class Route{
  public static $get = array();
  public static $controller = false;
  public static $function = false;
  public static $routes = array();

  public function __construct(){
    include_once(CONFIG_FOLDER.DIRECTORY_SEPARATOR.'routes.php');
    self::$routes = $routes;
    self::parseURL();
  }

  private static function getRoute( $url ){
    //PARSEAR CONTROLADOR Y FUNCION
    $getControlador = false;
    if(strpos($url, '?')){
    	$getControlador = array_filter(explode('?', $url));
    	$getControlador = array_filter(explode('/', $getControlador[0]));
    }else{
    	$getControlador = array_filter(explode('/', $url));
    }

    //valores por defecto para el controlador
    foreach ($getControlador as $value) {
    	if(self::$controller === false){self::$controller = ucfirst(strtolower($value));continue;}
    	if(self::$function === false){self::$function = $value;continue;}
    }
    self::$controller = (self::$controller !== false ? self::$controller : $GLOBALS['DEFAULT_CONTROLLER']);
    self::$function = (self::$function !== false ? self::$function : 'main');
  }

  private static function getParams( $url ){
    //OBTENER PARÁMETROS $_GET
    if($_SERVER['REQUEST_METHOD'] == 'GET' && strpos($url, '?') && strlen($url)-1 > strpos($url, '?')){
    	self::$get = (isset($url) && !empty($url) ? array_reverse(array_filter(explode('?', $_SERVER['REQUEST_URI']))) : false);
    	if(self::$get !== false){
    		parse_str(self::$get[0], self::$get);
    	}
    }
  }

  private static function callController(){

    //echo CONTROLLERS_FOLDER.DIRECTORY_SEPARATOR.self::$controller.'.controller.php<br>';
    //comprobar que el archivo del controlador existe
    if( self::$controller !== false && file_exists(CONTROLLERS_FOLDER.DIRECTORY_SEPARATOR.self::$controller.'.controller.php') ){
      include_once(CONTROLLERS_FOLDER.DIRECTORY_SEPARATOR.self::$controller.'.controller.php');
      //el controlador está en un subdirectorio
      if(strpos(self::$controller, '/')){
        $aux = array_reverse(explode('/', self::$controller));
        self::$controller = $aux[0];
      }
      //comprobar que la clase y la funcion existen
    	if( is_callable(array(htmlspecialchars(self::$controller), self::$function)) ){
    		//llamada a la clase y la funcion
    		call_user_func_array(array(self::$controller, self::$function), self::$get);

      //si no existe el controlador, lanzar error 404
      }else{
        include_once(CORE_FOLDER.DIRECTORY_SEPARATOR.'Controller.php');
    		call_user_func_array(array('Controller', 'error404'), array());
      }
    //comprobar si el controlador tiene un alias
    }else if(self::$controller !== false && array_key_exists(self::$controller, self::$routes)){
      //comprobar si la función tiene un alias
      if(self::$function !== false && array_key_exists(self::$function, self::$routes[self::$controller]['functions'])){
        self::$function = self::$routes[self::$controller]['functions'][self::$function];
      }
      if(isset(self::$routes[self::$controller]['lang'])){
        $GLOBALS['LANGUAGE'] = self::$routes[self::$controller]['lang'];
      }
      self::$controller = self::$routes[self::$controller]['controller'];
      self::callController();

    //si no existe el controlador, lanzar error 404
    }else{
      include_once(CORE_FOLDER.DIRECTORY_SEPARATOR.'Controller.php');
  		call_user_func_array(array('Controller', 'error404'), array());
    }

  }

  private function parseURL(){
    self::getParams($_SERVER['REQUEST_URI']);
    self::getRoute($_SERVER['REQUEST_URI']);
    self::callController();
  }
}
?>

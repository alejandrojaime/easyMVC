<?
/**
*		Clase principal de los controladores
*		Contiene las funciones globales a todos los controladores
*
*/
class Controller
{

	function __construct()
	{
	}

	//función por defecto si no se llama a ninguna del controlador y no existe el main
	public static function default(){
		return false;
	}

	//variables por defecto para el shell
	public static function defaultViewVars(){

		//variables
		$vars = array(
			'lang' => 'es',
			'styles' => array('shell.css'),
			'scripts' => array('shell.js'),
			'BASEURL' => BASEURL,
		);
		return $vars;
	}

	//obtener parámetros $_GET
	public static function get(){
		return $_GET;
	}

	//obtener parámetros $_POST
	public static function post(){
		return $_POST;
	}

	//página de error 404
	public static function error404(){
		$vars = array(
			'controller' => 'Home',
		);
		//ejecutar parseo y pintar resultado
		self::renderView('404.html', $vars);
	}

	//devuelve un modelo al controlador
	public static function loadModel($model = false){
		if($model === false){return false;}
		require_once CORE_FOLDER.DIRECTORY_SEPARATOR.'Model.php';
		require_once MODELS_FOLDER.DIRECTORY_SEPARATOR.$model.'.model.php';

		//el controlador está en un subdirectorio
		if(strpos($model, '/')){
			$aux = array_reverse(explode('/', $model));
			$model = $aux[0];
		}
		return new $model();
	}

	//renderiza la view con twig y el array de parámetros
	public static function renderView($view, $array = array()){
		//cargar twig para el parseo de la template
		require_once LIBRARIES_FOLDER.'/Twig/vendor/autoload.php';
		$loader = new Twig_Loader_Filesystem(VIEWS_FOLDER);
		$twig = new Twig_Environment($loader);

		$combinedVars = array_merge_recursive(self::defaultViewVars(), $array);
		//cargo las carpetas de las views dependiendo del idioma seleccionado
		echo $twig->render($view, $combinedVars);
	}

	//devuelve el PHPMailer para enviar emails
	public static function mailer(){
		require_once LIBRARIES_FOLDER.'/PHPMailer/vendor/autoload.php';
		$mailer = new PHPMailer\PHPMailer\PHPMailer();
		return $mailer;
	}

	public static function requestCredentials($redirect_url = false){
		if($redirect_url !== false){
			$_SESSION['AUTH_REFERER'] = $redirect_url;
		}elseif(!isset($_SESSION['AUTH_REFERER']) || empty($_SESSION['AUTH_REFERER'])){
			if(isset($_SERVER["HTTP_REFERER"]) && !empty($_SERVER["HTTP_REFERER"])){
				$_SESSION['AUTH_REFERER'] = $_SERVER["HTTP_REFERER"];
			}else{
				$_SESSION['AUTH_REFERER'] = 'https://tuweb.pro';
			}
		}

		if(!isset($GLOBALS['REGISTERED_USER']) || empty($GLOBALS['REGISTERED_USER']) || $GLOBALS['REGISTERED_USER'] !== true){
			header('Location:'.BASEURL.'/authentication/');
	    exit;
		}
	}
}
?>

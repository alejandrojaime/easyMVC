<?
/**
* Clase Home
* Encargada de pintar la página principal.
* Es el controlador por defecto en caso de que no se esté llamando a ninguno
*/


class Home extends Controller
{

	function __construct()
	{
	}

	public static function main(){

		//variables
		$model = self::loadModel('Fotos');
		$image = $model::getMainImage(124250);
		
		echo '<pre>'.print_r($image, true).'</pre>';

		$vars = array(
			'styles' => array('home.css'),
			'head' => array('description' => 'Site metadescription'),
			'image' => $image,
			'controller' => 'Home',
		);
		
		//ejecutar parseo y pintar resultado
		self::renderView('home.html', $vars);
	}
}

?>

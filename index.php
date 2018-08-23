<?php
//echo phpinfo();exit;
/**
*	BASE DEL MVC
*
*	Se consideran parámetros todas las palabras separadas por / en la url
* 	@param 1: string El controlador
* 	@param 2: string Función a ejecutar en el controlador
* 	@param 3+: string Todos los parámetros para la función del controlador
*
*	@author Alejandro Jaime <alejandrojaime.j@gmail.com>
*	@since 04/04/2018
*	@version 1.0
*/
include_once('app/config/config.php');
require_once(CORE_FOLDER.'/Route.php');
new Route;
?>

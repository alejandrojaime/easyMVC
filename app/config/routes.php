<?
/**
*    Routing del MVC
*    En este array se configura el nombre de los controladores y la ruta de dónde encontrarlo
*    También se pueden definir alias para un controlador y sus funciones
*
*/

$routes = array(
  'Inicio' => array(
    'lang'=>'ES',
    'controller' => 'Home/Home',
    'functions' => array(
        'main' => 'main',
    )
  )
);
?>

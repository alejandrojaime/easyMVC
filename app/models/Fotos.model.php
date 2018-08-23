<?php
class Fotos extends Model{
  function __construct(){
    parent::__construct();
  }
  public static function getMainImage($alojId){
    $query = 'SELECT Id, Imagen, ImagenP, ImagenG, Titulo FROM FOTOS WHERE RAlojamiento_id=:alojId AND visible=1 ORDER BY favorita DESC, pos limit 1';
    $stm = self::$db->prepare($query);
  	$stm->execute(array('alojId'=>$alojId));
  	return $stm->fetchAll(PDO::FETCH_ASSOC);
  }
}
?>

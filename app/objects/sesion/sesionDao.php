<?php
include_once($GLOBALS['configuration']['path_app_admin_objects']."sesion/sesion.php");
interface  SesionDao{
	public function alta (Sesion $sesion);
	public function eliminar ();
	public function borrarDatos ();
	public function get ();
	public function guardar ($nombre,$apellido,$email);
	public function guardarUsuario ($usuario);

}
?>

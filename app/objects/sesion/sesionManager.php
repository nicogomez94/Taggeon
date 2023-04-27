<?php
interface  SesionManager{
	public function crear($usuario,$pass);
	public function validar ($array);
	public function validarPublic ($array);
	public function cerrar ();
}
?>

<?php
interface  SellerManager{
	public function crear();
	public function actualizar();
	public function eliminar();
	public function get();
	public function getByIdSesion();
	public function getByIdUsuario($idUsuario);
	public function listar();
	public function getOptionTipoTel ($cod);
	public function getOptionPais ($cod);
	public function existeSeller ($id);
}
?>

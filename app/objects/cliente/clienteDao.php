<?php
include_once($GLOBALS['configuration']['path_app_admin_objects']."cliente/cliente.php");
interface  ClienteDao{
   public function alta (Cliente $cliente);
   public function editar (Cliente $cliente);
   public function eliminar ($id,$idUsuario);
   public function get ($id,$idUsuario);
   public function getByIdUsuario ($idUsuario);
   public function getList();
   public function existeEmail ($email);
   public function existeEmailMod ($email,$id);
	public function existeCliente ($id);
}
?>

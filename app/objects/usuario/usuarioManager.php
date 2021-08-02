<?php
interface  UsuarioManager{
	public function crear($usuario_var,$pass,$pass2,$perfil);
	public function crearByCliente($usuario_var,$pass,$perfil,$idUsuarioCliente);
	public function actualizar($id,$usuario_var);
	public function actualizarPass();
	public function eliminar($id);
	public function eliminarBeneficiarioByCliente($id);
	public function getIdUsuarioBeneficiarioByCliente($id);
	public function get($id);
	public function getByUsr();
	public function listar();
	public function recuperar();
	public function cambiarPassByCliente();
	public function clienteIsOwnerBeneficiario ($idCliente,$idBeneficiario);
	public function actualizarImagenPerfil(); //new
	public function actualizarPerfil(); //new
	public function getUsuarioByEmail($email); //new
	public function getUsuarioPublic(); //new
	public function getUsuarioBySesion(); //new
	public function actualizarTokenMP(); //new
	public function getTokenMP(); //new

	
}
?>

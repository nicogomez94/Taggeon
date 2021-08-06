<?php
include_once($GLOBALS['configuration']['path_app_admin_objects']."usuario/usuario.php");
interface  UsuarioDao{
   public function alta (Usuario $usuario);
   public function altaByCliente (Usuario $usuario,$idUsuarioCliente);
   public function editar (Usuario $usuario);
	public function actualizarPass($idUsuario,$perfil,$pass);
   public function eliminar ($id);
   public function eliminarBeneficiarioByCliente ($id);
   public function getIdUsuarioBeneficiarioByCliente ($id);
   public function get ($id);
   public function getByUsr ($usr);
   public function existeUsuario ($usr);
   public function existeUsuarioUpdate ($usr,$id);
   public function getList();
   //public function getUsrClienteByEmail($email);
	public function updateRecuperarCliente ($idParam,$usuarioParam,$hashParam);
	public function updatePasswordByCliente ($idParam,$usuarioParam,$hashParam,$passwordParam,$perfilParam);
   public function clienteIsOwnerBeneficiario ($idCliente,$idBeneficiario);
   public function validPassOld($idUsuario,$perfil,$pass);
   public function existeemail ($email); //nueva
   public function getUsuarioByEmail($email); //nueva
   public function actualizarPerfilUsuario($id,$usuario,$perfil); //nueva
	public function actualizarPerfilDatosPersonales($id,$nombre,$apellido,$usuario,$email,$perfil); //nueva
	public function getUsuarioPublic($id_usuario); //nueva
   public function getUsuarioBySesion (); //nueva
   public function isAdmin (); //nueva
   public function actualizarTokenMP ($json,$token); //nueva
	public function getTokenMP($id_carrito); //new

}
?>

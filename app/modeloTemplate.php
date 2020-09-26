<?php
include 'objects/util/configuration.php';
include_once($GLOBALS['configuration']['path_app_admin_objects']."util/template.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."sesion/sesionManagerImpl.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."usuario/usuarioManagerImpl.php");

$error = "";
$sesionManager = new SesionManagerImpl();
$usuarioManager = new UsuarioManagerImpl();

if ($sesionManager->validarPublic(array('anonymous'))){
	$contenido = new Template("recuperar-pass-mail");
	$hash    = (isset($_REQUEST['hash']))  ? $_REQUEST['hash'] : ""; 
	$usuario = (isset($_REQUEST['usuario']))  ? $_REQUEST['usuario'] : ""; 
	$idUsuario = (isset($_REQUEST['idUsuario']))  ? $_REQUEST['idUsuario'] : ""; 
	$id = (isset($_REQUEST['id']))  ? $_REQUEST['id'] : ""; 

	$contenido->asigna_variables(array(
			"hash" => $hash,
			"usuario" => $usuario,
			"idUsuario" => $idUsuario,
			"id" => $id,
			));
	$contenidoString = $contenido->muestra();
	echo $contenidoString;
}else{
   $error = $sesionManager->getMsj();
   $contenidoString = Template::sostenedor_error($error);
   echo $contenidoString;

}
Database::Connect()->close();
exit;

?>

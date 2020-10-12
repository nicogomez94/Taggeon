<?php
include_once($GLOBALS['configuration']['path_app_admin_objects']."util/header.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."usuario/usuarioManagerImpl.php");


if ($perfil=='picker' || $perfil == 'seller' || $perfil == ''){
	//$pathTemplate = $GLOBALS['configuration']['path_templates'];
	$nameTemplate = preg_replace ("/^\//", "", $url);
	$nameTemplate = preg_replace ("/\.html?$/i", "", $nameTemplate);
    //$usuarioManager = new UsuarioManagerImpl();
    //$id        = $GLOBALS['sesionG']['id'];
	$contenido = new Template($nameTemplate);
	//$contenido->asigna_variables(array(
    //        "usuario" => $GLOBALS['sesionG']['usuario'],
    //        "nombre" => $GLOBALS['sesionG']['nombre'],
    //        "apellido" => $GLOBALS['sesionG']['apellido'],
    //        "contacto" => $GLOBALS['sesionG']['email'],
    //        "url_editar" => "/editar-usuario.html",
    //        "foto-perfil" => $fotoPerfil, //fotoPerfil definida en header.php
    //        "perfil" => $perfil
	//		));
    $contenidoString = $contenido->muestra();
    //HEADER
    echo $contenidoStringHeader;
    //FIN HEADER
	echo $contenidoString;
}else{
    //HEADER
    echo $contenidoStringHeader;
    //FIN HEADER
   $contenidoString = Template::sostenedor_error("Permiso denegado {$perfil}");
   echo $contenidoString;
}
Database::Connect()->close();
exit;

?>
<?php
include_once($GLOBALS['configuration']['path_app_admin_objects']."util/header.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."usuario/usuarioManagerImpl.php");


if ($perfil=='picker' || $perfil == 'seller'){
    //$usuarioManager = new UsuarioManagerImpl();
    $id        = $GLOBALS['sesionG']['id'];
    $fotoPerfil = '';

    if (file_exists("/var/www/imagen_perfil/$id")) {
        $fp = fopen("/var/www/imagen_perfil/$id", 'r');
        while(!feof($fp)) {
            $fotoPerfil .= fgets($fp);
        }
        fclose($fp);
    } else {
        if (file_exists("/var/www/imagen_perfil/generica")) {
            $fp = fopen("/var/www/imagen_perfil/generica", 'r');
            while(!feof($fp)) {
                $fotoPerfil .= fgets($fp);
            }
            fclose($fp);
        }
    }
	$contenido = new Template(" ");
	$contenido->asigna_variables(array(
            "usuario" => $GLOBALS['sesionG']['usuario'],
            "nombre" => $GLOBALS['sesionG']['nombre'],
            "apellido" => $GLOBALS['sesionG']['apellido'],
            "contacto" => $GLOBALS['sesionG']['email'],
            "url_editar" => "/editar-usuario.html",
            "foto-perfil" => $fotoPerfil,
            "perfil" => $perfil
			));
    $contenidoString = $contenido->muestraDesdeVariable($htmlContent);
    //HEADER
    echo $contenidoStringHeader;
    //FIN HEADER
	echo $contenidoString;
}else{
    //HEADER
    echo $contenidoStringHeader;
    //FIN HEADER
   $contenidoString = Template::sostenedor_error("Permiso denegado");
   echo $contenidoString;
}
Database::Connect()->close();
exit;

?>

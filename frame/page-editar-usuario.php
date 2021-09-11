<?php
include_once($GLOBALS['configuration']['path_app_admin_objects']."util/header.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."usuario/usuarioManagerImpl.php");



if ($perfil=='picker' || $perfil == 'seller'){
    //$usuarioManager = new UsuarioManagerImpl();
    $id        = $GLOBALS['sesionG']['id'];

	$contenido = new Template(" ");
    $tokenMercadoPago = 0;
    if (isset($GLOBALS['sesionG']['tokenMercadoPago']) && $GLOBALS['sesionG']['tokenMercadoPago'] != ''){
        $tokenMercadoPago = 1;
    }
    
	$contenido->asigna_variables(array(
            "tokenMercadoPago" => $tokenMercadoPago,
            "usuario" => $GLOBALS['sesionG']['usuario'],
            "nombre" => $GLOBALS['sesionG']['nombre'],
            "apellido" => $GLOBALS['sesionG']['apellido'],
            "contacto" => $GLOBALS['sesionG']['email'],
            "url_editar" => "/editar-usuario.html",
            "foto-perfil" => $fotoPerfil, //fotoPerfil definida en header.php
            "perfil" => $perfil
			));
    $contenidoString = $contenido->muestraDesdeVariable($htmlContent);
    //HEADER
    echo $contenidoStringHeader;
    //FIN HEADER
	echo $contenidoString;
}else{
    header("Location: ".$GLOBALS['configuration']['redirect_home']);
}
Database::Connect()->close();
exit;

?>

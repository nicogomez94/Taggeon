<?php
include_once("template.php");
include_once("configuration.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."intereses/InteresesManager.php");


$perfil   = isset($perfil) ? $perfil : '';

    $fotoPerfil = '';
if ($perfil == 'seller' || $perfil == 'picker'){
	$contenidoHeader = new Template('header_esp_usuario');
	$reemplazoPerfil = ($perfil == 'seller') ? ' Seller' : '';
	$id        = $GLOBALS['sesionG']['idUsuario'];
	$usuarioPerfil = $GLOBALS['sesionG']['usuario'];

    if (file_exists("/var/www/html/imagen_perfil/$id.png")) {
    	$fotoPerfil = "/imagen_perfil/$id.png";
    } else {
    	$fotoPerfil = "/imagen_perfil/generica.png";
	if ($usuarioPerfil == $GLOBALS['sesionG']['email']){
		$usuarioPerfil = $GLOBALS['sesionG']['nombre'];
	}   
    }
 
    $menu = '';
    if ($perfil == 'seller'){
        $menu = <<<STR
        <a href="/ampliar-producto.html">Mis Productos</a><br>
        <a href="/mis-publicaciones.html">Mis Publicaciones</a><br>
        <a href="/mis-compras.html">Mis Compras</a><br>
        <a href="/mis-ventas.html">Mis Ventas</a><br>
STR;
    }
    if ($perfil == 'picker'){
        $menu = <<<STR
        <a href="/mis-publicaciones.html">Mis Publicaciones</a><br>
        <a href="/mis-compras.html">Mis Compras</a><br>

STR;
    }

    $idUserMP        = $GLOBALS['sesionG']['idUsuario'];
    $urlMP = "https://auth.mercadopago.com.ar/authorization?client_id=8374534224864099&response_type=code&platform_id=mp&state=$idUserMP&redirect_uri=https://taggeon.com/";
	
    $objIntereses = new InteresesManager();
    $intereses = $objIntereses->getListIntereses();
    $intereses = json_encode($intereses);


	$contenidoHeader->asigna_variables(array(
        "url-mp"     => $urlMP,
		"perfil"         => $reemplazoPerfil,
		"foto-perfil"    => $fotoPerfil,
		"menu"           => $menu,
		"nombre-usuario" => $usuarioPerfil,
        "intereses"      => $intereses
		));
}else{
	$contenidoHeader = new Template('header_esp');
}
$contenidoStringHeader = $contenidoHeader->muestra();
?>

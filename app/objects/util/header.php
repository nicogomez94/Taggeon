<?php
include_once("template.php");
include_once("configuration.php");
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

    $urlMP = "https://auth.mercadopago.com.ar/authorization?client_id=3352741419059189&response_type=code&platform_id=mp&state=$id&redirect_uri=https://ec2-3-135-36-159.us-east-2.compute.amazonaws.com/";
	
	$contenidoHeader->asigna_variables(array(
        "url-mp"     => $urlMP,
		"perfil"         => $reemplazoPerfil,
		"foto-perfil"    => $fotoPerfil,
		"menu"           => $menu,
		"nombre-usuario" => $usuarioPerfil
		));
}else{
	$contenidoHeader = new Template('header_esp');
}
$contenidoStringHeader = $contenidoHeader->muestra();
?>

<?php
include_once("template.php");
include_once("configuration.php");
$perfil   = isset($perfil) ? $perfil : '';

if ($perfil == 'seller' || $perfil == 'picker'){
	$contenidoHeader = new Template('header_esp_usuario');
	$reemplazoPerfil = ($perfil == 'seller') ? ' Seller' : '';
	$id        = $GLOBALS['sesionG']['idUsuario'];
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
	$usuarioPerfil = $GLOBALS['sesionG']['usuario'];
	if ($usuarioPerfil == $GLOBALS['sesi)onG']['email']{
		$usuarioPerfil = $GLOBALS['sesionG']['nombre'];
    }
 
    $menu = '';
    if ($perfil == 'seller'){
        $menu = <<<STR
        <a href="/ampliar-producto.html">Mis Productos</a><br>
        <a href="/mis-publicaciones.html">Mis Publicaciones</a><br>
STR;
    }
    if ($perfil == 'picker'){
        $menu = <<<STR
        <a href="/mis-publicaciones.html">Mis Publicaciones</a><br>
STR;
    }
	
	$contenidoHeader->asigna_variables(array(
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

<?php
include_once("template.php");
include_once("configuration.php");
$perfil   = isset($perfil) ? $perfil : '';

if ($perfil == 'seller' || $perfil == 'picker'){
	$contenidoHeader = new Template('header_esp_usuario');
	$reemplazoPerfil = ($perfil == 'seller') ? ' Seller' : '';
	$contenidoHeader->asigna_variables(array(
		"perfil" => $reemplazoPerfil
		));
}else{
	$contenidoHeader = new Template('header_esp');
}
$contenidoStringHeader = $contenidoHeader->muestra();
?>

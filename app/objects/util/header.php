<?php
include_once("template.php");
include_once("configuration.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."intereses/InteresesManager.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."publicacion/PublicacionManager.php");


$perfil   = isset($perfil) ? $perfil : '';

    $fotoPerfil = '';
    $publicacionManager = new PublicacionManager();
    $escena = $publicacionManager->getListEscena(); 
    $escena = json_encode($escena,JSON_INVALID_UTF8_IGNORE);
    $escena2 = $publicacionManager->getListEscena2(); 
    $escena2 = json_encode($escena2);
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
        <a href="/ampliar-producto.html"><i class="icon-header fas fa-tags"></i>Mis Productos</a><br>
        <a href="/mis-publicaciones.html"><i class="icon-header fas fa-th-list"></i>Mis Publicaciones</a><br>
        <a href="/mis-compras.html"><i class="icon-header fas fa-shopping-basket"></i>Mis Compras</a><br>
        <a href="/mis-ventas.html"><i class="icon-header fas fa-store"></i>Mis Ventas</a>
        <a href="/metricas.html"><i class="icon-header fas fa-shopping-basket"></i>Métricas</a><br>
STR;
    }
    if ($perfil == 'picker'){
        $menu = <<<STR
        <a href="/mis-publicaciones.html"><i class="icon-header fas fa-th-list"></i>Mis Publicaciones</a><br>
        <a href="/mis-compras.html"><i class="icon-header fas fa-shopping-basket"></i>Mis Compras</a><br>
        <a href="/metricas.html"><i class="icon-header fas fa-shopping-basket"></i>Métricas</a><br>

STR;
    }

    if ($GLOBALS['sesionG']['usuario'] == 'poepe@gmail.com' || $GLOBALS['sesionG']['usuario'] ==  'nico15@gmail.com'){

      $menu .= <<<STR
        <a href="/producto-importar-categorias.html"><i class="icon-header fas fa-th-list"></i>Importar categorías</a><br>

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
		"intereses"      => $intereses,
		"escena"         => $escena,
		"escena2"         => $escena2
		));
}else{
	$contenidoHeader = new Template('header_esp');
	$contenidoHeader->asigna_variables(array(
		"escena"         => $escena,
		"escena2"         => $escena2
		));
}
$contenidoStringHeader = $contenidoHeader->muestra();
?>

<?php
include_once("template.php");
include_once("configuration.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."intereses/InteresesManager.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."publicacion/PublicacionManager.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."notificaciones/NotificacionesManager.php");


$perfil   = isset($perfil) ? $perfil : '';

    $fotoPerfil = '';
    $publicacionManager = new PublicacionManager();
    $escena = $publicacionManager->getListEscena(); 
    $escena = json_encode($escena,JSON_INVALID_UTF8_IGNORE);
    $escena2 = $publicacionManager->getListEscena2(); 
    $escena2 = json_encode($escena2);
    $randomtime=time();


    $escena  = isset($_GET["escena"]) ? $_GET["escena"] : 'ARQUITECTURA';
    $columnas = $publicacionManager->getColumnasCategoria($escena); 
    $columnas = json_encode($columnas);

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
        <div><a href="/ampliar-producto.html"><i class="icon-header fas fa-tags"></i>Mis Productos</a></div>
        <div><a href="/mis-publicaciones.html"><i class="icon-header fas fa-th-list"></i>Mis Publicaciones</a></div>
        <div><a href="/mis-compras.html"><i class="icon-header fas fa-shopping-basket"></i>Mis Compras</a></div>
        <div><a href="/mis-ventas.html"><i class="icon-header fas fa-store"></i>Mis Ventas</a></div>
        <div><a href="/metricas-tagger.html"><i class="icon-header fas fa-chart-pie"></i>Métricas - Tagger</a></div>
        <div><a href="/metricas-seller.html"><i class="icon-header fas fa-chart-pie"></i>Métricas - Seller</a></div>
STR;
    }
    if ($perfil == 'picker'){
        $menu = <<<STR
        <div><a href="/mis-publicaciones.html"><i class="icon-header fas fa-th-list"></i>Mis Publicaciones</a></div>
        <div><a href="/mis-compras.html"><i class="icon-header fas fa-shopping-basket"></i>Mis Compras</a></div>
        <div><a href="/metricas.html"><i class="icon-header fas fa-chart-pie"></i>Métricas</a></div>
STR;
    }

    if ($GLOBALS['sesionG']['usuario'] == 'nicolasgomez94@gmail.com' || $GLOBALS['sesionG']['usuario'] ==  'nico15@gmail.com'){

      $menu .= <<<STR
        <div><a href="/metricas-admin.html"><i class="icon-header fas fa-chart-pie"></i>Métricas ADMIN</a></div>
        <div><a href="/producto-importar-categorias.html"><i class="icon-header fas fa-chart-pie"></i>Importar categorías</a></div>
STR;
    }

    $idUserMP        = $GLOBALS['sesionG']['idUsuario'];
    $urlMP = $GLOBALS['configuration_mp']['url_authorization']."?client_id=".$GLOBALS['configuration_mp']['client_id']."&response_type=code&platform_id=mp&state=$idUserMP&redirect_uri=".$GLOBALS['configuration_mp']['redirect_uri']; 
	
    $objIntereses = new InteresesManager();
    $intereses = $objIntereses->getListIntereses();
    $intereses = json_encode($intereses,JSON_INVALID_UTF8_IGNORE);


    $notificacionesManager = new NotificacionesManager();
    $notificaciones = $notificacionesManager->getListNotificaciones();
    $notificaciones = json_encode($notificaciones,JSON_INVALID_UTF8_IGNORE);

    //$test = "1213"

	$contenidoHeader->asigna_variables(array(
        //"test"           => $test
	"categoriaEstilo"   => $columnas,
        "randomtime" => $randomtime,
        "notificaciones" => $notificaciones,
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
	"categoriaEstilo"   => $columnas,
        "randomtime" => $randomtime,
		"escena"         => $escena,
		"escena2"         => $escena2
		));
}
$contenidoStringHeader = $contenidoHeader->muestra();
?>

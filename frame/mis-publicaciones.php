<?php
include_once($GLOBALS['configuration']['path_app_admin_objects']."util/footer.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."util/header.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."publicacion/PublicacionManager.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."seguidores/SeguidoresManager.php");

if ($perfil=='seller' || $perfil=='picker'){
    
    $publicacionManager = new PublicacionManager();
    $seguidoresManager = new SeguidoresManager();
    
    $tokenMercadoPago = 0;
    if (isset($GLOBALS['sesionG']['tokenMercadoPago']) && $GLOBALS['sesionG']['tokenMercadoPago'] != ''){
        $tokenMercadoPago = 1;
    }

    $jsonData = array(
        "tokenMercadoPago" => $tokenMercadoPago,
	    "perfil"        => $perfil,
        "usuario" => $GLOBALS['sesionG']['usuario'],
        "nombre"      => $GLOBALS['sesionG']['nombre'],
        "apellido"    => $GLOBALS['sesionG']['apellido'],
        "contacto" => $GLOBALS['sesionG']['email'],
      #  "publicaciones"     => $publicacionManager->getListPublicacion(),
        "seguidores"     => $seguidoresManager->getListSeguidores(),
        "seguidos"     => $seguidoresManager->getListSeguidos()
        
    );
    $jsonData = json_encode($jsonData,JSON_INVALID_UTF8_IGNORE);
    $menuperfil = $GLOBALS['menuperfil'][$perfil];
    $contenido = new Template($nameTemplate);
	$contenido->asigna_variables(array(
            "json" => $jsonData,
            "usuario" => $GLOBALS['sesionG']['usuario'],
            "nombre" => $GLOBALS['sesionG']['nombre']." ".$GLOBALS['sesionG']['apellido'],
            "apellido" => $GLOBALS['sesionG']['apellido'],
            "contacto" => $GLOBALS['sesionG']['email'],
            "url_editar" => "/editar-usuario.html",
            "menu-perfil" => _menuPerfil($fotoPerfil,$menuperfil),
            "foto-perfil" => $fotoPerfil //fotoPerfil definida en header.php

			));
    $contenidoString = $contenido->muestra();
    //HEADER
    echo $contenidoStringHeader;
    //FIN HEADER
	echo $contenidoString;
echo $contenidoStringFooter;
}else{
//    //HEADER
//    echo $contenidoStringHeader;
//    //FIN HEADER
//   $contenidoString = Template::sostenedor_error("Permiso denegado {$perfil}");
//   echo $contenidoString;
echo $contenidoStringFooter;
//
   header("Location: ".$GLOBALS['configuration']['redirect_home']);

}
Database::Connect()->close();
exit;

?>

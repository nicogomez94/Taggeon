<?php
include_once($GLOBALS['configuration']['path_app_admin_objects']."util/header.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."usuario/usuarioManagerImpl.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."seguidores/SeguidoresManager.php");



if ($perfil=='picker' || $perfil == 'seller'){
    $seguidoresManager = new SeguidoresManager();

    //$usuarioManager = new UsuarioManagerImpl();
    $tokenMercadoPago = 0;
    if (isset($GLOBALS['sesionG']['tokenMercadoPago']) && $GLOBALS['sesionG']['tokenMercadoPago'] != ''){
        $tokenMercadoPago = 1;
    }
    
    $jsonData = array(
        "tokenMercadoPago" => $tokenMercadoPago,
        "usuario" => $GLOBALS['sesionG']['usuario'],
        "nombre"      => $GLOBALS['sesionG']['nombre'],
        "apellido"    => $GLOBALS['sesionG']['apellido'],
        "contacto" => $GLOBALS['sesionG']['email'],
        "seguidores"     => $seguidoresManager->getListSeguidores(),
        "seguidos"     => $seguidoresManager->getListSeguidos()
    );
    $jsonData = json_encode($jsonData);
    //$urlEditar = ($perfil == 'seller') ? "/editar-usuario-seller.html": '/editar-usuario.html';
    
    $menuperfil = $GLOBALS['menuperfil'][$perfil];

	$contenido = new Template(" ");
	$contenido->asigna_variables(array(
            "json" => $jsonData,
            "usuario" => $GLOBALS['sesionG']['usuario'],
            "nombre" => $GLOBALS['sesionG']['nombre']." ".$GLOBALS['sesionG']['apellido'],
            "apellido" => $GLOBALS['sesionG']['apellido'],
            "contacto" => $GLOBALS['sesionG']['email'],
            "url_editar" => "/editar-usuario.html",
            "menuperfil" => $menuperfil,
            "foto-perfil" => $fotoPerfil //fotoPerfil definida en header.php
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

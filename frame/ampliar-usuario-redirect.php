<?php
include_once($GLOBALS['configuration']['path_app_admin_objects']."util/footer.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."util/header.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."usuario/usuarioManagerImpl.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."seguidores/SeguidoresManager.php");



if ($perfil=='picker' || $perfil == 'seller'){
    $seguidoresManager = new SeguidoresManager();

    $usuarioManager = new UsuarioManagerImpl();
    $listUsr = $usuarioManager->getUsuarioPublic();
    $nombrepublic = (isset($listUsr['nombre']))  ? $listUsr['nombre'] : "";
    $apellidopublic = (isset($listUsr['apellido']))  ? $listUsr['apellido'] : "";
    $emailpublic = (isset($listUsr['email']))  ? $listUsr['email'] : "";
    $jsonData = array(
        "nombre"      => $nombrepublic,
        "apellido"    => $apellidopublic,
        "contacto" => $emailpublic,
        "seguidores"     => $seguidoresManager->getListSeguidoresPublic(),
        "publicaciones"     => $publicacionManager->getListPublicacionPublic(),
        "seguidos"     => $seguidoresManager->getListSeguidosPublic()
    );
    
    $jsonData = json_encode($jsonData,JSON_INVALID_UTF8_IGNORE);
    $menuperfil = $GLOBALS['menuperfil'][$perfil];
    $idEditar = isset($_GET["id"]) ? $_GET["id"] : '';
    $contenido = new Template($nameTemplate);


    $id = (isset($_GET['id_usuario']))  ? $_GET['id_usuario'] : "";

    $fotoPerfil = '';
    if (file_exists("/var/www/html/imagen_perfil/$id.png")) {
        $fotoPerfil = "/imagen_perfil/$id.png";
    } else {
        $fotoPerfil = "/imagen_perfil/generica.png";
    }

    $contenido->asigna_variables(array(
        "json" => $jsonData,
        "usuario" => $GLOBALS['sesionG']['usuario'],
        "nombre" => $GLOBALS['sesionG']['nombre']." ".$GLOBALS['sesionG']['apellido'],
        "nombrepublic"  => $nombrepublic." ".$apellidopublic,
        "apellido" => $GLOBALS['sesionG']['apellido'],
        "apellidopublic" => $apellidopublic,
        "contacto" => $GLOBALS['sesionG']['email'],
        "url_editar" => "/editar-usuario.html",
            "menu-perfil" => _menuPerfil($fotoPerfil,$menuperfil),
        "foto-perfil" => $fotoPerfil
        
        ));
    
    

    $contenidoString = $contenido->muestra();
    //HEADER
    echo $contenidoStringHeader;
    //FIN HEADER
	echo $contenidoString;
echo $contenidoStringFooter;
}else{
    header("Location: ".$GLOBALS['configuration']['redirect_home']);
}
Database::Connect()->close();
exit;

?>

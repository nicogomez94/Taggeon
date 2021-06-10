<?php
include_once($GLOBALS['configuration']['path_app_admin_objects']."util/header.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."usuario/usuarioManagerImpl.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."seguidores/SeguidoresManager.php");



if ($perfil=='picker' || $perfil == 'seller'){
    $seguidoresManager = new SeguidoresManager();

    $usuarioManager = new UsuarioManagerImpl();
    $listUsr = $usuarioManager->getUsuarioPublic();
    $nombre = (isset($listUsr['nombre']))  ? $listUsr['nombre'] : "";
    $apellido = (isset($listUsr['apellido']))  ? $listUsr['apellido'] : "";
    $email = (isset($listUsr['email']))  ? $listUsr['email'] : "";
    $jsonData = array(
        "nombre"      => $nombre,
        "apellido"    => $apellido,
        "contacto" => $email,
        "seguidores"     => $seguidoresManager->getListSeguidoresPublic(),
        "seguidos"     => $seguidoresManager->getListSeguidosPublic()
    );
    
    $jsonData = json_encode($jsonData);
    $menuperfil = '';
    $idEditar = isset($_GET["id"]) ? $_GET["id"] : '';
    $contenido = new Template($nameTemplate);


    $id = (isset($_GET['id_usuario']))  ? $_GET['id_usuario'] : "";

    $fotoPerfil = '';
    if (file_exists("/var/www/html/imagen_perfil/$id.png")) {
        $fotoPerfil = "/var/www/html/imagen_perfil/$id.png";
    } else {
        $fotoPerfil = "/var/www/html/imagen_perfil/generica.png";
    }

    $contenido->asigna_variables(array(
        "json" => $jsonData,
        "usuario" => $GLOBALS['sesionG']['usuario'],
        "nombre" => $GLOBALS['sesionG']['nombre']." ".$GLOBALS['sesionG']['apellido'],
        "apellido" => $GLOBALS['sesionG']['apellido'],
        "contacto" => $GLOBALS['sesionG']['email'],
        "url_editar" => "/editar-usuario.html",
        "menuperfil" => $menuperfil,
        "foto-perfil" => $fotoPerfil
        
        ));
    
    

    $contenidoString = $contenido->muestra();
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

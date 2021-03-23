<?php

include_once("/var/www/html/app/objects/util/configuration.php");

include_once($GLOBALS['configuration']['path_app_admin_objects']."util/template.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."sesion/sesionManagerImpl.php");


$sesionManager = new SesionManagerImpl();

$perfil = "";
$url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : ""; 
$arrayUrl = explode('?', $url, 2);
$url = $arrayUrl[0];
$patron = '/(html|htm|\/|\.html\?.+)$/';
if (preg_match($patron, $url)){
	if ($sesionManager->validar(array('picker','seller','admin','superadmin','editor'))){
		$perfil = $GLOBALS['sesionG']['perfil'];
	}
}
$language = $GLOBALS['sesionG']['language'];


if ($url == '/' || $url == '/index.htm'){
	$url = '/index.html';
}else if ($url == '/editar-usuario-seller.html' || $url == '/editar-usuario.html'){
	$url = '/editar-usuario.html';
}else if ($url == '/ampliar-usuario-seller.html' || $url == '/ampliar-usuario.html'){
	$url = '/ampliar-usuario.html';
}else{
	$nameTemplate = preg_replace ("/^\//", "", $url);
	$nameTemplate = preg_replace ("/\.html.*$/i", "", $nameTemplate);
	$nameTemplateSesion = $GLOBALS['configuration']['path_app_frame'].$nameTemplate.".php"; 
	if (file_exists($nameTemplateSesion) ){
		require("./frame/{$nameTemplate}.php");
		Database::Connect()->close();
		exit;
	}else if (file_exists($GLOBALS['configuration']['path_templates']."/{$nameTemplate}.html") ){

		require('./frame/page-template.php');
		Database::Connect()->close();
		exit;
	}

}
$urlBase = Database::escape($url);


$tituloContent = "";
$htmlContent   = "";
$tituloContent2 = "";
$htmlContent2   = "";
$idTemplateContent    = 0;

	$sql =<<<SQL
		SELECT  * 
		FROM   post
		where url like $urlBase
SQL;

$resultado=Database::Connect()->query($sql);
while($rowEmp=mysqli_fetch_array($resultado)){
	$tituloContent = $rowEmp['titulo_esp'];
	if (empty($tituloContent)){
		$tituloContent = "";
	}			
	$htmlContent = $rowEmp['html_esp'];
	if (empty($htmlContent)){
		$htmlContent = "";
	}			
	$idTemplateContent = $rowEmp['idTemplate'];
	if (empty($idTemplateContent)){
		$idTemplateContent = 0;
	}			
}

if($idTemplateContent == 1){
	require('./frame/page-index.php');
}else if ($idTemplateContent == 2){
	require('./frame/page-editar-usuario.php');
}else if ($idTemplateContent == 3){
	require('./frame/page-editar-usuario-seller.php');
}else if ($idTemplateContent == 4){
	require('./frame/page-ampliar-usuario.php');
}else if ($idTemplateContent == 99){
	require('./frame/page-default.php');
}else {
	$tituloContent = "P&aacute;gina no encontrada.";
	$htmlContent = "P&aacute;gina no encontrada.";
	require('./frame/page-default.php');
}

Database::Connect()->close();
exit;

?>

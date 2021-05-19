<?php
$id = (isset($_GET['id_usuario']))  ? $_GET['id_usuario'] : "";

$fotoPerfil = '';
if (file_exists("/var/www/imagen_perfil/$id")) {
	$fp = fopen("/var/www/imagen_perfil/$id", 'r');
	while(!feof($fp)) {
		$fotoPerfil = fgets($fp);
	}
	fclose($fp);
} else {
	if (file_exists("/var/www/imagen_perfil/generica")) {
		$fp = fopen("/var/www/imagen_perfil/generica", 'r');
		while(!feof($fp)) {
			$fotoPerfil = fgets($fp);
		}
		fclose($fp);
	}
}
echo $fotoPerfil;
?>

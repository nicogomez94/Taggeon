<?php
for ($i = 1; $i <= 500; $i++) {
$nombreFile = $i;
$nombreFile = 'generica';
if (file_exists('/var/www/html/imagen_perfil/'.$nombreFile)) {
	$file = file('/var/www/html/imagen_perfil/'.$nombreFile);

	$foto = '';
	foreach ($file as $num => $linea) {
		$foto .= $linea;
	}
	$base_to_php = explode(',', $foto);
	$data = base64_decode($base_to_php[1]);
	$filepath = "/var/www/html/imagen_perfil/$nombreFile.png"; // or image.jpg
	file_put_contents($filepath,$data);
}
}
?>

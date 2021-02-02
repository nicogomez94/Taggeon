<?php
for ($i = 1; $i <= 400; $i++) {
$nombreFile = $i;
if (file_exists('/var/www/html/productos_img/'.$nombreFile)) {
	$file = file('/var/www/html/productos_img/'.$nombreFile);

	$foto = '';
	foreach ($file as $num => $linea) {
		$foto .= $linea;
	}
	$base_to_php = explode(',', $foto);
	$data = base64_decode($base_to_php[1]);
	$filepath = "/var/www/html/productos_img/$nombreFile.png"; // or image.jpg
	file_put_contents($filepath,$data);
}
}
?>

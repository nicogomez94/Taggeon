<?php

$get  = var_dump($_GET); // Element 'foo' is string(1) "a"
$post = var_dump($_POST); // Element 'bar' is string(1) "b"
$req  = var_dump($_REQUEST); // Does not contain elements 'foo' or 'bar'

$myfile = fopen("/var/www/html/newfile.txt", "a+") or die("Unable to open file!");
if ($_REQUEST){
        $resultados = print_r($_REQUEST, true); 
        fwrite($myfile, "REQUEST: $resultados\n");
}else{
	fwrite($myfile, "Sin Request\n");
}
if ($_POST){
        $resultados = print_r($_POST, true);
        fwrite($myfile, "POST: $resultados\n");
}else{
	fwrite($myfile, "Sin POST\n");
}
if ($_GET){
        $resultados = print_r($_GET, true); 
        fwrite($myfile, "GET: $resultados\n");
}else{
	fwrite($myfile, "Sin GET");
}


fclose($myfile);


?>

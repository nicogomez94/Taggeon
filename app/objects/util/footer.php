<?php
include_once("template.php");
$myAccount = '<a href="#" onclick="irAConsultas();">my account</a><br>';
if ($GLOBALS['sesionG']['language'] == 'esp'){
	$myAccount = '<a href="#" onclick="irAConsultas();">mi cuenta</a><br>';
}

    $randomtime=time();
$templateFooter = "footer";
if ($GLOBALS['sesionG']['language'] == 'esp'){
	$templateFooter = "footer_esp";
}
$contenidoFooter = new Template($templateFooter);

$contenidoFooter->asigna_variables(array(
        "randomtime" => $randomtime,
		"myaccount" => $myAccount,
		"cierrefooter" => $GLOBALS['configuration']['scriptCierreFoot']
		));
$contenidoStringFooter = $contenidoFooter->muestra();
?>

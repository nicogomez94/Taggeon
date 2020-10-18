<?php
include '/var/www/configuration_pass.php';

function getBienvenida (){
	$perfil =  $GLOBALS['sesionG']['perfil'];
	if ($perfil == 'cliente'){
		return $GLOBALS['sesionG']['nombre']." - ".$perfil."<br>";
	}else if($perfil == 'beneficiario'){
		return $GLOBALS['sesionG']['nombre']." - ".$perfil."<br>";
	}else if($perfil == 'retailer'){
		return $GLOBALS['sesionG']['nombre']." - ".$perfil."<br>";
	}else if ($perfil == 'admin'){
		return $GLOBALS['sesionG']['usuario']." - ".$perfil."<br>";
	}else if ($perfil == 'superadmin'){
		return $GLOBALS['sesionG']['usuario']." - ".$perfil."<br>";
	}
	return "";
	
}

global $menu;
$menu['cliente'] =<<<STR
 <a href="/index.html">Home</a> 
 | <a href="make_a_purchase.php">make a purchase</a> 
 | <a href="cliente_listarBeneficiarios.php">beneficiarios</a> 
 | <a href="listar_transacciones.php">consultas</a> 
 | <a href="public_editar_cliente.php">modificaciones</a> 
 | <a href="editar_pass.php">cambiar password</a> 
 | <a href="logout.php">Cerrar Sesion</a> 
STR;

$menu['beneficiario'] =<<<STR
 <a href="/index.html">Home</a> 
 | <a href="listar_cuenta_corriente.php">consultas</a> 
 | <a href="public_editar_beneficiario.php">modificaciones</a> 
 | <a href="editar_pass.php">cambiar password</a> 
 | <a href="logout.php">Cerrar Sesion</a> 
STR;

$menu['retailer'] =<<<STR
 <a href="/index.html">Home</a> 
 | <a href="public_editar_retailer.php">Editar</a> 
 | <a href="editar_pass.php">Cambiar Pass</a> 
 | <a href="listado_gerente.php">Listado Cuenta Corriente</a> 
 | <a href="logout.php">Cerrar Sesion</a> 
STR;

$menu['admin'] =<<<STR
 <a href="/index.html">Home</a> 
 | <a href="listar_clientes.php">Listado Clientes</a> 
 | <a href="listar_beneficiarios.php">Listado Beneficiarios</a> 
 | <a href="listar_retailer.php">Listado Retailer</a> 
 | <a href="listar_cuenta_corriente.php">Listado Cuenta Corriente</a> 
 | <a href="logout.php">Cerrar Sesion</a> 

STR;

 //| <a href="listar_usuarios.php">Listado Usuarios</a> 
$menu['superadmin'] =<<<STR
 <a href="/index.html">Home</a> 
 | <a href="listar_clientes.php">Listado Clientes</a> 
 | <a href="listar_beneficiarios.php">Listado Beneficiarios</a> 
 | <a href="listar_retailer.php">Listado Retailer</a> 
 | <a href="listar_cuenta_corriente.php">Listado Cuenta Corriente</a> 
 | <a href="logout.php">Cerrar Sesion</a> 
STR;


global $codTipoTel;
$codTipoTel['celular'] = 'Mobile';
$codTipoTel['oficina'] = 'Office';
$codTipoTel['casa']    = 'Home Phone';

global $tieneInternet;
$tieneInternet['si'] = 'Si';
$tieneInternet['no'] = 'No';
$tieneInternet['algunos'] = 'Algunos';

global $tieneSistGiftCard;
$tieneSistGiftCard['si'] = 'Si';
$tieneSistGiftCard['no'] = 'No';

global $comoFactura;
$comoFactura['manual'] = 'Manual';
$comoFactura['digital'] = 'Digital';
$comoFactura['regFiscal'] = 'Registradora Fiscal';

global $codPais;
//Codificacion paises
$codPais[''] = 'Seleccionar Pais';
$codPais['USA'] = 'United States';

global $codPaisBeneficiario;
//Codificacion paises
$codPaisBeneficiario['DOM'] = 'República Dominicana';

global $codPaisRetailer;
//Codificacion paises
$codPaisRetailer['DOM'] = 'República Dominicana';


function _trim ($param){
	$patrones = array ('/(\t+)/','/(\n+)/','/(\r+)/','/(\0+)/','/(\x0B+)/','/( +)/');
	$cadena = preg_replace($patrones, " ", $param);
	$cadena = trim($cadena);
	return $cadena;
}

function validSqlInjection ($param){
	#if (preg_match_all('/(select|delete|where|insert|update|order|from|create|null)/i', $param,$coincidencia)){
	if (preg_match_all('/(select|delete|where|insert|update| order|from|create|null)/i', $param,$coincidencia)){
		$ret = "";
		$array = $coincidencia[0];
		foreach($array as $v){
			if ($ret != ''){
				$ret .= ", ";
			}
			$ret .= $v;
		}
		return $ret;
	}
	return '';
}

global $ciudadDOM;
$ciudadDOM['DOM1'] = 'Azua de Compostela';
$ciudadDOM['DOM2'] = 'Bajos de Haina';
$ciudadDOM['DOM3'] = 'Baní';
$ciudadDOM['DOM4'] = 'Boca Chica';
$ciudadDOM['DOM5'] = 'Bonao';
$ciudadDOM['DOM6'] = 'Concepción de La Vega';
$ciudadDOM['DOM7'] = 'Constanza';
$ciudadDOM['DOM8'] = 'Cotuí';
$ciudadDOM['DOM9'] = 'Esperanza';
$ciudadDOM['DOM10'] = 'Hato Mayor del Rey';
$ciudadDOM['DOM11'] = 'Hig&uuml;ey';
$ciudadDOM['DOM12'] = 'Jarabacoa';
$ciudadDOM['DOM13'] = 'La Romana';
$ciudadDOM['DOM14'] = 'Las Matas de Farfán';
$ciudadDOM['DOM15'] = 'Los Alcarrizos';
$ciudadDOM['DOM16'] = 'Mao';
$ciudadDOM['DOM17'] = 'Moca';
$ciudadDOM['DOM18'] = 'Monte Plata';
$ciudadDOM['DOM19'] = 'Nagua';
$ciudadDOM['DOM20'] = 'Pedro Brand';
$ciudadDOM['DOM21'] = 'Salcedo';
$ciudadDOM['DOM22'] = 'San Antonio de Guerra';
$ciudadDOM['DOM23'] = 'San Cristóbal';
$ciudadDOM['DOM24'] = 'San Felipe de Puerto Plata';
$ciudadDOM['DOM25'] = 'San Francisco de Macorís';
$ciudadDOM['DOM26'] = 'San Ignacio de Sabaneta';
$ciudadDOM['DOM27'] = 'San José de Las Matas';
$ciudadDOM['DOM28'] = 'San José de Ocoa';
$ciudadDOM['DOM29'] = 'San Juan de la Maguana';
$ciudadDOM['DOM30'] = 'San Pedro de Macorís';
$ciudadDOM['DOM31'] = 'Santa Bárbara de Samaná';
$ciudadDOM['DOM32'] = 'Santa Cruz de Barahona';
$ciudadDOM['DOM33'] = 'Santa Cruz de El Seibo';
$ciudadDOM['DOM34'] = 'Santiago de los Caballeros';
$ciudadDOM['DOM35'] = 'Santo Domingo';
$ciudadDOM['DOM36'] = 'Santo Domingo Este';
$ciudadDOM['DOM37'] = 'Santo Domingo Norte';
$ciudadDOM['DOM38'] = 'Santo Domingo Oeste';
$ciudadDOM['DOM39'] = 'Sosúa';
$ciudadDOM['DOM40'] = 'Tamboril';
$ciudadDOM['DOM41'] = 'Villa Altagracia';
$ciudadDOM['DOM42'] = 'Villa Bisonó';
$ciudadDOM['DOM43'] = 'Yaguate';
$ciudadDOM['DOM44'] = 'Yamasá';

global $pciaDOM;
$pciaDOM['DOM1'] = 'Azua';
$pciaDOM['DOM2'] = 'San Cristóbal';
$pciaDOM['DOM3'] = 'Peravia';
$pciaDOM['DOM4'] = 'Santo Domingo';
$pciaDOM['DOM5'] = 'Monseñor Nouel';
$pciaDOM['DOM6'] = 'La Vega';
$pciaDOM['DOM7'] = 'La Vega';
$pciaDOM['DOM8'] = 'Sánchez Ramírez';
$pciaDOM['DOM9'] = 'Valverde';
$pciaDOM['DOM10'] = 'Hato Mayor';
$pciaDOM['DOM11'] = 'La Altagracia';
$pciaDOM['DOM12'] = 'La Vega';
$pciaDOM['DOM13'] = 'La Romana';
$pciaDOM['DOM14'] = 'San Juan';
$pciaDOM['DOM15'] = 'Santo Domingo';
$pciaDOM['DOM16'] = 'Valverde';
$pciaDOM['DOM17'] = 'Espaillat';
$pciaDOM['DOM18'] = 'Monte Plata';
$pciaDOM['DOM19'] = 'María Trinidad Sánchez';
$pciaDOM['DOM20'] = 'Santo Domingo';
$pciaDOM['DOM21'] = 'Hermanas Mirabal';
$pciaDOM['DOM22'] = 'Santo Domingo';
$pciaDOM['DOM23'] = 'San Cristóbal';
$pciaDOM['DOM24'] = 'Puerto Plata';
$pciaDOM['DOM25'] = 'Duarte';
$pciaDOM['DOM26'] = 'Santiago Rodríguez';
$pciaDOM['DOM27'] = 'Santiago';
$pciaDOM['DOM28'] = 'San José de Ocoa';
$pciaDOM['DOM29'] = 'San Juan';
$pciaDOM['DOM30'] = 'San Pedro de Macorís';
$pciaDOM['DOM31'] = 'Samaná';
$pciaDOM['DOM32'] = 'Barahona';
$pciaDOM['DOM33'] = 'El Seibo';
$pciaDOM['DOM34'] = 'Santiago';
$pciaDOM['DOM35'] = 'Distrito Nacional';
$pciaDOM['DOM36'] = 'Santo Domingo';
$pciaDOM['DOM37'] = 'Santo Domingo';
$pciaDOM['DOM38'] = 'Santo Domingo';
$pciaDOM['DOM39'] = 'Puerto Plata';
$pciaDOM['DOM40'] = 'Santiago';
$pciaDOM['DOM41'] = 'San Cristóbal';
$pciaDOM['DOM42'] = 'Santiago';
$pciaDOM['DOM43'] = 'San Cristóbal';
$pciaDOM['DOM44'] = 'Monte Plata';

global $codPaisTipoCambio;
$codPaisTipoCambio['DOM'] = 'RD';

global $codPaisTelDOM;
$codPaisTelDOM['1809'] = '+1 809';
$codPaisTelDOM['1829'] = '+1 829';
$codPaisTelDOM['1849'] = '+1 849';

################
##INGLES
################
global $mensajes;
$mensajes['ref_captcha_eng'] = 'The security code is incorrect, please try again.';
$mensajes['ref_err_eng'] = 'unknown error.';

#/var/www/html/app/objects/cuentaCorriente/cuentaCorrienteManagerImpl.php
$mensajes['ref_1_eng']  = 'The beneficiary has reached the maximum amount allowed per month.'; 
$mensajes['ref_2_eng']  = 'You can send up to U$S 500.- per month.'; 
$mensajes['ref_3_eng']  = 'You can send up to U$S 250.- per transaction.'; 
$mensajes['ref_4_eng']  = 'The dollar field is incorrect. i.e. format: 158.09.';  
$mensajes['ref_5_eng']  = 'The dollar field has  sql commands.';
$mensajes['ref_6_eng']  = 'The local currency field is incorrect. i.e. format: 158.09.';
$mensajes['ref_7_eng']  = 'The local currency field has sql commands.';
$mensajes['ref_8_eng']  = 'Error, beneficiary has not been defined.';
$mensajes['ref_9_eng']  = 'The beneficiary&#39;s id has sql commands.';
$mensajes['ref_10_eng'] = 'Error the client is incorrect.';
$mensajes['ref_11_eng'] = 'The client&#39;s id has sql commands.';
$mensajes['ref_12_eng'] = 'Error id detail.';
$mensajes['ref_13_eng'] = 'The id detail has sql commands.';
$mensajes['ref_14_eng'] = 'The field From step 3 (summary) has sql commands.';
$mensajes['ref_15_eng'] = 'The field From step 3 (summary) can have up to 64 characters.';
$mensajes['ref_16_eng'] = 'The field From step 3 (summary) has characters that are not allowed.';
$mensajes['ref_17_eng'] = 'The field From step 3 (summary) has sql commands.';
$mensajes['ref_18_eng'] = 'The field To step 3 (summary) can have up to 64 characters.';
$mensajes['ref_19_eng'] = 'The field To step 3 (summary) has characters  that are not allowed.';
$mensajes['ref_20_eng'] =  'The field Message step 3 (summary) has sql commands.';
$mensajes['ref_21_eng'] =  'The field Message step 3 (summary) can have up to 512 characters.';
$mensajes['ref_22_eng'] = 'The field Message step 3 (summary) has characters  that are not allowed.';
$mensajes['ref_23_eng'] = 'Error the beneficiary is incorrect.';
$mensajes['ref_24_eng'] = 'Error you are not the owner of the beneficiary.';
$mensajes['ref_25_eng'] = 'Error the retailer is incorrect.';
$mensajes['ref_26_eng'] = "The exchange rate is incorrect. Click  on a href=\"#\" onclick=\"setMoneda('irPaso3');\">here</a> 'to obtain a new amount.";
$mensajes['ref_27_eng'] = 'The currency U$S exchange to local currency is incorrect.';
$mensajes['ref_28_eng'] = 'The local currency amount must be multiple of 100.';
$mensajes['ref_29_eng'] = 'Payment cannot be made. The transaction has expired or is payed.';
$mensajes['ref_30_eng'] = 'Error.Payment could not be made.';
$mensajes['ref_31_eng'] = 'terms please confirm that you have read and accepted MandaSeguro.com&#39;s terms and conditions.';
$mensajes['ref_32_eng'] = 'Error not the owner of that beneficiary.';
$mensajes['ref_33_eng'] = 'Error incorrect transaction.';
$mensajes['ref_34_eng'] = 'Transaction ID.';
$mensajes['ref_346_eng'] = 'The dollar amount must be greater than or equal to USD 35.';
$mensajes['ref_347_eng'] = 'The card num has sql commands';
$mensajes['ref_348_eng'] = 'Error the card num is incorrect.';
$mensajes['ref_349_eng'] = 'The expiration date has sql commands';
$mensajes['ref_350_eng'] = 'Error the expiration date is incorrect. i.e. format: mm/yy.';
$mensajes['ref_351_eng'] = 'Error the expiration date (mm) is incorrect. i.e. format: mm/yy.';
$mensajes['ref_352_eng'] = 'Error the expiration date (yy) is incorrect. i.e. format: mm/yy.';
$mensajes['ref_353_eng'] = 'The security code has sql commands';
$mensajes['ref_354_eng'] = 'Error the security code is incorrect.';
$mensajes['ref_355_eng'] = 'The first name has sql commands';
$mensajes['ref_356_eng'] = 'The first name is incorrect.';
$mensajes['ref_357_eng'] = 'The last name has sql commands';
$mensajes['ref_358_eng'] = 'The last name is incorrect.';
$mensajes['ref_359_eng'] = 'The first name can have up to 50 characters.';
$mensajes['ref_360_eng'] = 'The last name can have up to 50 characters.';
$mensajes['ref_361_eng'] = 'The billing address has sql commands';
$mensajes['ref_362_eng'] = 'The billing address is incorrect.';
$mensajes['ref_363_eng'] = 'The billing address can have up to 50 characters.';
$mensajes['ref_364_eng'] = 'The billing address line2 has sql commands';
$mensajes['ref_365_eng'] = 'The billing address line2 is incorrect.';
$mensajes['ref_366_eng'] = 'The billing address line2 can have up to 50 characters.';
$mensajes['ref_367_eng'] = 'The billing address country has sql commands';
$mensajes['ref_368_eng'] = 'The billing address country is incorrect.';
$mensajes['ref_369_eng'] = 'The billing address country can have up to 50 characters.';
$mensajes['ref_370_eng'] = 'The city field has sql commands';
$mensajes['ref_371_eng'] = 'The city field is incorrect.';
$mensajes['ref_372_eng'] = 'The city field can have up to 40 characters.';
$mensajes['ref_373_eng'] = 'The State field has sql commands';
$mensajes['ref_374_eng'] = 'The State field is incorrect.';
$mensajes['ref_375_eng'] = 'The State field can have up to 40 characters.';
$mensajes['ref_376_eng'] = 'The zip code field has sql commands';
$mensajes['ref_377_eng'] = 'The zip code field is incorrect.';
$mensajes['ref_378_eng'] = 'The zip code field can have up to 20 characters.';
$mensajes['ref_379_eng'] = 'Error the date of birth (mm) is incorrect. i.e. format: mm/dd/yyyy.';
$mensajes['ref_380_eng'] = 'Error the date of birth (dd) is incorrect. i.e. format: mm/dd/yyyy.';
$mensajes['ref_381_eng'] = 'Error the date of birth (yyyy) is incorrect. i.e. format: mm/dd/yyyy.';
$mensajes['ref_382_eng'] = 'Error the date of birth is incorrect. i.e. format: mm/dd/yyyy.';
$mensajes['ref_383_eng'] = 'Error: Must comply with age requirement (18 years or older).';
$mensajes['ref_384_eng'] = 'The phone number is incorrect. i.e. format: 1231231234';
$mensajes['ref_385_eng'] = 'The phone number can have up to 25 digits.';
$mensajes['ref_409_eng'] = 'The retailer id has sql commands.';
$mensajes['ref_410_eng']  = 'Error the retailer is incorrect.';
$mensajes['ref_411_eng']  = 'incorrect pin';

#/app/objects/cuentaCorriente/cuentaCorrienteDaoImpl.php
$mensajes['ref_35_eng'] = 'Error cab current account could not be registered.';
$mensajes['ref_36_eng'] = 'Error det current account could not be registered.';
$mensajes['ref_37_eng'] = 'Error data base Reference: gtmcbm.';
$mensajes['ref_38_eng'] = 'Error data base Reference: gtmbbm.';
$mensajes['ref_38_2_eng'] = 'Error Status of Payment approval pending. Transaction id could not be updated.';
$mensajes['ref_38_3_eng'] = 'Error Status of Payment  could not be updated. Error in transaction id.';
$mensajes['ref_38_4_eng'] = 'Error Status of Payment Completed of transaction id could not be updated.';
$mensajes['ref_408_eng'] = 'Error Status of Cancel of transaction id could not be updated.';

#/var/www/html/app/objects/beneficiario/beneficiarioDaoImpl.php
$mensajes['ref_39_eng'] = 'Error beneficiary could not be registered.';
$mensajes['ref_40_eng'] = 'Error beneficiary could not be updated User id.';
$mensajes['ref_42_eng'] = 'Error beneficiary id could not be deleted.';
$mensajes['ref_44_eng'] = 'Money could not be sent to beneficiary. Please verify that beneficiary country is open for business with MandaSeguro.com.';

#/var/www/html/app/objects/beneficiario/beneficiarioManagerImpl.php
$mensajes['ref_45_eng'] = ' You must at least register email or phone number in order to create the beneficiary.';
$mensajes['ref_46_eng'] = 'You are not properly authorized to modify the beneficiary.';
$mensajes['ref_48_eng'] = 'You are not properly authorized to delete the client.';
$mensajes['ref_50_eng'] = 'The user id has  sql commands.';
$mensajes['ref_51_eng'] = 'Error user id.';
$mensajes['ref_52_eng'] = 'The beneficiary id has sql commands.';
$mensajes['ref_53_eng'] = 'Error beneficiary id.';
$mensajes['ref_54_eng'] = 'The name has sql commands.';
$mensajes['ref_55_eng'] = 'The name can have up to 30 characters.';
$mensajes['ref_56_eng'] = 'The name has characters that are not allowed.';
$mensajes['ref_57_eng'] = 'The last name has sql commands.';
$mensajes['ref_58_eng'] = 'The last name is mandatory.';
$mensajes['ref_59_eng'] = 'The last name can have up to 30 characters.';
$mensajes['ref_60_eng'] = 'The last name has characters that are not allowed.';
$mensajes['ref_61_eng'] = 'The email has sql commands.';
$mensajes['ref_62_eng'] = 'The email must have more than 5 characters.';
$mensajes['ref_63_eng'] = 'The email can have up to 64 characters.';
$mensajes['ref_64_eng'] = 'The email format is incorrect or contains characters that are not allowed.';
$mensajes['ref_65_eng'] = 'The city field has  sql commands.';
$mensajes['ref_66_eng'] = 'The city field must have more than 2 characters.';
$mensajes['ref_67_eng'] = 'The city field  can have up to 30 characters.';
$mensajes['ref_68_eng'] = 'The city field has characters that are not allowed.';
$mensajes['ref_69_eng'] = 'The State field has sql commands.';
$mensajes['ref_70_eng'] = 'The State field must have more than 2 characters.';
$mensajes['ref_71_eng'] = 'The State field can have up to 30 characters.';
$mensajes['ref_72_eng'] = 'The State field has characters that are not allowed.';
$mensajes['ref_73_eng'] = 'The State field has sql commands.';
$mensajes['ref_74_eng'] = 'The State field can have up to 30 characters.';
$mensajes['ref_75_eng'] = 'The State field has characters that are not allowed.';
$mensajes['ref_76_eng'] = 'The zip code field has sql commands.';
$mensajes['ref_77_eng'] = 'The zip code field  can have up to 12 characters.';
$mensajes['ref_78_eng'] = 'The zip code field has characters that are not allowed.';
$mensajes['ref_79_eng'] = 'The country field has sql commands.';
$mensajes['ref_80_eng'] = 'At the moment you can register beneficiaries form the Dominican Republic.';
$mensajes['ref_81_eng'] = 'The country field is incorrect.';
$mensajes['ref_82_eng'] = 'The country field is mandatory.';
$mensajes['ref_83_eng'] = 'The country area code field has sql commands.';
$mensajes['ref_84_eng'] = 'The city area code field has sql commands.';
$mensajes['ref_85_eng'] = 'The phone number  field has sql commands.';
$mensajes['ref_86_eng'] = 'The phone number type field has sql commands.';
$mensajes['ref_87_eng'] = 'The phone number type field is incorrect.';
$mensajes['ref_88_eng'] = 'The phone number type field is incorrect.';
$mensajes['ref_89_eng'] = 'The phone number field is incorrect.';
$mensajes['ref_90_eng'] = 'The phone number city area code field is incorrect.';
$mensajes['ref_91_eng'] = 'The phone number country area code field is incorrect.';
$mensajes['ref_92_eng'] = 'The address field line 1 has sql commands.';
$mensajes['ref_93_eng'] = 'The address field line 1 can have up to 30 characters.';
$mensajes['ref_94_eng'] = 'The address field line 1 has characters that are not allowed.';
$mensajes['ref_95_eng'] = 'The address field line 2 has sql commands.';
$mensajes['ref_96_eng'] = 'The address field line 2 can have up to 30 characters.';
$mensajes['ref_97_eng'] = 'The city field 2 has sql commands.';
$mensajes['ref_98_eng'] = 'The city field 2  must have more than 2 characters.';
$mensajes['ref_99_eng'] = 'The city field 2  can have up to 30 characters.';
$mensajes['ref_100_eng'] = 'The city field 2 has characters that are not allowed.';
$mensajes['ref_101_eng'] = 'The country field 2 has sql commands.';
$mensajes['ref_102_eng'] = 'The country field 2 is incorrect. At the moment you can register beneficiaries form the Dominican Republic.';
$mensajes['ref_103_eng'] = 'The country field 2 is incorrect.';
$mensajes['ref_104_eng'] = 'The country field 2 is mandatory.';
$mensajes['ref_105_eng'] = 'The term parameter has sql commands.';
$mensajes['ref_106_eng'] = 'The term parameter must have more than 1 character.';
$mensajes['ref_107_eng'] = 'The term parameter can have up to 30 characters.';
$mensajes['ref_108_eng'] = 'The term parameter  has characters that are not allowed.';
$mensajes['ref_108_1_eng'] = 'The name is mandatory.';
$mensajes['ref_108_2_eng'] = 'terms please confirm that you have read and accepted MandaSeguro.com&#39;s terms and conditions';
$mensajes['ref_344_eng'] = 'Error the phone number already exists.';
$mensajes['ref_345_eng'] = 'The Cel Phone number field needs to have 7 digits.';

#/var/www/html/app/objects/ciudad/ciudadManagerImpl.php
$mensajes['ref_109_eng'] = 'The city parameter has sql commands.';
$mensajes['ref_110_eng'] = 'The city parameter is mandatory.';
$mensajes['ref_111_eng'] = 'The city parameter can have up to 30 characters.';
$mensajes['ref_112_eng'] = 'The city parameter has characters that are not allowed.';

#/var/www/html/app/objects/cliente/clienteDaoImpl.php
$mensajes['ref_113_eng'] = 'Error the client could not be registered.';
$mensajes['ref_114_eng'] = 'Error the client user id could not be updated.';
$mensajes['ref_115_eng'] = 'Error the client id could not be deleted.';

#/var/www/html/app/objects/cliente/clienteManagerImpl.php
$mensajes['ref_116_eng'] = 'Your emails do not match. Please try again..';
$mensajes['ref_117_eng'] = 'The email is already registered.';
$mensajes['ref_120_eng'] = 'You do not have the necessary permission to modify the data of client.';
$mensajes['ref_121_eng'] = 'You are not allowed to delete the client.';
$mensajes['ref_122_eng'] = 'The client id has sql commands.';
$mensajes['ref_123_eng'] = 'Error client id.';
$mensajes['ref_124_eng'] = 'The name has sql commands.';
$mensajes['ref_125_eng'] = 'The name can have up to 30 characters.';
$mensajes['ref_126_eng'] = 'The name has characters that are not allowed.';
$mensajes['ref_127_eng'] = 'The last name has sql commands.';
$mensajes['ref_128_eng'] = 'The last name is mandatory.';
$mensajes['ref_129_eng'] = 'The last name can have up to 30 characters.';
$mensajes['ref_130_eng'] = 'The last name has characters that are not allowed.';
$mensajes['ref_131_eng'] = 'The email has sql commands.';
$mensajes['ref_132_eng'] = 'The email must have more than 5 characters.';
$mensajes['ref_133_eng'] = 'The email can have up to 64 characters.';
$mensajes['ref_134_eng'] = 'The email format is incorrect or contains characters that are not allowed.';
$mensajes['ref_135_eng'] = 'The city field has sql commands.';
$mensajes['ref_136_eng'] = 'The city field must have more than 2 characters.';
$mensajes['ref_137_eng'] = 'The city field can have up to 30 characters.';
$mensajes['ref_138_eng'] = 'The city field has characters that are not allowed.';
$mensajes['ref_139_eng'] = 'The city field has sql commands.';
$mensajes['ref_140_eng'] = 'The State field is mandatory.';
$mensajes['ref_141_eng'] = 'The State field can have up to 30 characters.';
$mensajes['ref_142_eng'] = 'The State field has characters that are not allowed.';
$mensajes['ref_143_eng'] = 'The zip code field has sql commands.';
$mensajes['ref_144_eng'] = 'The zip code field can have up to 12 characters.';
$mensajes['ref_145_eng'] = 'The zip code field has characters that are not allowed.';
$mensajes['ref_146_eng'] = 'The country field has sql commands.';
$mensajes['ref_147_eng'] = 'At present, clients from the United States can be registered.';
$mensajes['ref_148_eng'] = 'The country field is incorrect.';
$mensajes['ref_149_eng'] = 'The country field is mandatory.';
$mensajes['ref_150_eng'] = 'The telephone1 country field has sql commands.';
$mensajes['ref_151_eng'] = 'The telephone1 city field has sql commands.';
$mensajes['ref_152_eng'] = 'The telephone1  field has sql commands.';
$mensajes['ref_153_eng'] = 'The telephone1 type field has sql commands.';
$mensajes['ref_154_eng'] = 'The telephone1 type field is incorrect.';
$mensajes['ref_155_eng'] = 'The telephone1 type field is incorrect.';
$mensajes['ref_156_eng'] = 'The telephone1 field is incorrect.';
$mensajes['ref_157_eng'] = 'The telephone1 city field is incorrect.';
$mensajes['ref_158_eng'] = 'The telephone1 country  field is incorrect.';
$mensajes['ref_159_eng'] = 'The telephone2 country field has sql commands.';
$mensajes['ref_160_eng'] = 'The telephone2 city field has sql commands.';
$mensajes['ref_161_eng'] = 'The telephone2  field has sql commands.';
$mensajes['ref_162_eng'] = 'The telephone2 type field has sql commands.';
$mensajes['ref_163_eng'] = 'The telephone2 type field is incorrect.';
$mensajes['ref_164_eng'] = 'The telephone2 type field is incorrect.';
$mensajes['ref_165_eng'] = 'The telephone2 field is incorrect.';
$mensajes['ref_166_eng'] = 'The telephone2 city field is incorrect.';
$mensajes['ref_167_eng'] = 'The telephone2 country  field is incorrect.';
$mensajes['ref_167_1_eng'] = 'terms please confirm that you have read and accepted MandaSeguro.com&#39;s terms and conditions.';
$mensajes['ref_167_2_eng'] = 'The name is mandatory.';

#/var/www/html/app/objects/monedaCambio/monedaCambioDaoImpl.php
$mensajes['ref_168_eng'] = 'Error the exchange rate could not be registered.';
$mensajes['ref_169_eng'] = 'Error the exchange rate could not be updated.';
$mensajes['ref_170_eng'] = 'Error the exchange rate id could not be deleted.';
$mensajes['ref_171_eng'] = 'The country selected is not open for business with MandaSeguro.com.';

#/var/www/html/app/objects/monedaCambio/monedaCambioManagerImpl.php
$mensajes['ref_172_eng'] = 'The exchange rate field has sql commands.';
$mensajes['ref_173_eng'] = 'The exchange rate field is incorrect. i.e. of format: 158.09.';
$mensajes['ref_174_eng'] = 'The user id has sql commands.';
$mensajes['ref_175_eng'] = 'Error user id .';
$mensajes['ref_176_eng'] = 'The id has sql commands.';
$mensajes['ref_177_eng'] = 'Error id.';
$mensajes['ref_178_eng'] = 'The country field has sql commands.';
$mensajes['ref_179_eng'] = 'The country field is incorrect.';
$mensajes['ref_180_eng'] = 'The country field is mandatory.';

#/var/www/html/app/objects/retailer/retailerDaoImpl.php
$mensajes['ref_181_eng'] = 'Error retailer could not be registered.';
$mensajes['ref_182_eng'] = 'Error the retailer user id could not be updated.';
$mensajes['ref_183_eng'] = 'Error the retailer id could not be deleted.';

#/var/www/html/app/objects/retailer/retailerManagerImpl.php
$mensajes['ref_184_eng'] = 'Not allowed to modify the retailer.';
$mensajes['ref_185_eng'] = 'Not allowed to delete the retailer.';
$mensajes['ref_186_eng'] = 'The client id has sql commands.';
$mensajes['ref_187_eng'] = 'Error client id.';
$mensajes['ref_188_eng'] = 'The corporate name field has sql commands.';
$mensajes['ref_189_eng'] = 'The corporate name field is mandatory.';
$mensajes['ref_190_eng'] = 'The corporate name field can have up to 128 characters.';
$mensajes['ref_191_eng'] = 'The corporate name field has characters that are not allowed.';
$mensajes['ref_192_eng'] = 'The retail store name field has sql commands.';
$mensajes['ref_193_eng'] = 'The retail store name field is mandatory.';
$mensajes['ref_194_eng'] = 'The retail store name field can have up to 128 characters.';
$mensajes['ref_195_eng'] = 'The retail store name field has characters that are not allowed.';
$mensajes['ref_196_eng'] = 'The tax id number field has sql commands.';
$mensajes['ref_197_eng'] = 'The tax id number field is mandatory.';
$mensajes['ref_198_eng'] = 'The tax id number field can have up to 128 characters.';
$mensajes['ref_199_eng'] = 'The tax id number field has characters that are not allowed.';
$mensajes['ref_200_eng'] = 'The name has sql commands.';
$mensajes['ref_201_eng'] = 'The name is mandatory.';
$mensajes['ref_202_eng'] = 'The name can have up to 30 characters.';
$mensajes['ref_203_eng'] = 'The name has characters that are not allowed.';
$mensajes['ref_204_eng'] = 'The last name has sql commands.';
$mensajes['ref_205_eng'] = 'The last name is mandatory.';
$mensajes['ref_206_eng'] = 'The last name can have up to 30 characters.';
$mensajes['ref_207_eng'] = 'The last name has characters that are not allowed.';
$mensajes['ref_208_eng'] = 'The email has sql commands.';
$mensajes['ref_209_eng'] =  'The email must have more than 5 characters.';
$mensajes['ref_210_eng'] = 'The email can have up to 64 characters.';
$mensajes['ref_211_eng'] = 'The email format is incorrect or contains characters that are not allowed.';
$mensajes['ref_212_eng'] = 'The city field has sql commands.';
$mensajes['ref_213_eng'] = 'The city field must have more than 2 characters.';
$mensajes['ref_214_eng'] = 'The city field can have up to 30 characters.';
$mensajes['ref_215_eng'] = 'The city field has characters that are not allowed.';
$mensajes['ref_216_eng'] = 'The State field has sql commands.';
$mensajes['ref_217_eng'] = 'The State field is mandatory.';
$mensajes['ref_218_eng'] = 'The State field can have up to 30 characters.';
$mensajes['ref_219_eng'] = 'The State field has characters that are not allowed.';
$mensajes['ref_220_eng'] = 'The zip code field has sql commands.';
$mensajes['ref_221_eng'] = 'The zip code field is mandatory';
$mensajes['ref_222_eng'] = 'The zip code field  can have up to 30 characters.';
$mensajes['ref_223_eng'] = 'The zip code field has characters that are not allowed.';
$mensajes['ref_224_eng'] = 'The country field has sql commands.';
$mensajes['ref_225_eng'] = 'At present retailers from the Dominican Republic can register.';
$mensajes['ref_226_eng'] = 'The country field is incorrect.';
$mensajes['ref_227_eng'] = 'The country field is mandatory.';
$mensajes['ref_228_eng'] = 'The telephone1 country field has sql commands.';
$mensajes['ref_229_eng'] = 'The telephone1 city field has sql commands.';
$mensajes['ref_230_eng'] = 'The telephone1 field has sql commands.';
$mensajes['ref_231_eng'] = 'The telephone1 type field has sql commands.';
$mensajes['ref_232_eng'] = 'The telephone1 type field is incorrect.';
$mensajes['ref_233_eng'] = 'The telephone1 type field is incorrect.';
$mensajes['ref_234_eng'] = 'The telephone1 field is incorrect.';
$mensajes['ref_235_eng'] = 'The telephone1 city field  is incorrect.';
$mensajes['ref_236_eng'] = 'The telephone1 country field  is incorrect.';
$mensajes['ref_237_eng'] = 'The telephone2 country field has sql commands.';
$mensajes['ref_238_eng'] = 'The telephone2 city field has sql commands.';
$mensajes['ref_239_eng'] = 'The telephone2 field has sql commands.';
$mensajes['ref_240_eng'] = 'The telephone2 type field has sql commands.';
$mensajes['ref_241_eng'] = 'The telephone2 type field is incorrect.';
$mensajes['ref_242_eng'] = 'The telephone2 type field is incorrect.';
$mensajes['ref_243_eng'] = 'The telephone2 field is incorrect.';
$mensajes['ref_244_eng'] = 'The telephone2 city field is incorrect.';
$mensajes['ref_245_eng'] = 'The telephone2 country field is incorrect.';
$mensajes['ref_246_eng'] = 'The type of store field has sql commands.';
$mensajes['ref_247_eng'] = 'The type of store field is mandatory.';
$mensajes['ref_248_eng'] = 'The type of store field can have up to 30 characters.';
$mensajes['ref_249_eng'] = 'The type of store field has characters that are not allowed.';
$mensajes['ref_250_eng'] = 'The address line 1 field has sql commands.';
$mensajes['ref_251_eng'] = 'The address line 1 field  must have more than 2 characters.';
$mensajes['ref_252_eng'] = 'The address line 1 field  can have up to 30 characters.';
$mensajes['ref_253_eng'] = 'The address line 1 field  has characters that are not allowed.';
$mensajes['ref_254_eng'] = 'The address line 2 field has sql commands.';
$mensajes['ref_255_eng'] = 'The address line 2 field  must have more than 2 characters';
$mensajes['ref_256_eng'] = 'The address line 2 field  can have up to 30 characters.';
$mensajes['ref_257_eng'] = 'The address line 2 field  has characters that are not allowed.';
$mensajes['ref_258_eng'] = 'The city 2 field has sql commands.';
$mensajes['ref_259_eng'] = 'The city 2 field must have more than 2 characters.';
$mensajes['ref_260_eng'] = 'The city 2 field can have up to 30 characters.';
$mensajes['ref_261_eng'] = 'The city 2 field has characters that are not allowed.';
$mensajes['ref_262_eng'] = 'The country 2 field has sql commands.';
$mensajes['ref_263_eng'] = 'The country 2 field is incorrect. At present retailers from the Dominican Republic can register.';
$mensajes['ref_264_eng'] = 'The country 2 field is incorrect.';
$mensajes['ref_265_eng'] = 'The country 2 field is mandatory.';
$mensajes['ref_266_eng'] = 'The name of bank field has sql commands.';
$mensajes['ref_267_eng'] = 'The name of bank field is mandatory.';
$mensajes['ref_268_eng'] = 'The name of bank field  can have up to 128 characters.';
$mensajes['ref_269_eng'] = 'The name of bank field has characters that are not allowed.';
$mensajes['ref_270_eng'] = 'The account number field has sql commands.';
$mensajes['ref_271_eng'] = 'The account number field is mandatory.';
$mensajes['ref_272_eng'] = 'The account number field can have up to 64 characters.';
$mensajes['ref_273_eng'] = 'The account number field has characters that are not allowed.';
$mensajes['ref_274_eng'] = 'The account holder field has sql commands.';
$mensajes['ref_275_eng'] = 'The account holder field is mandatory.';
$mensajes['ref_276_eng'] = 'The account holder field can have up to 128 characters.';
$mensajes['ref_277_eng'] = 'The account holder field has characters that are not allowed.';
$mensajes['ref_278_eng'] = 'The other data of the account field has sql commands.';
$mensajes['ref_279_eng'] = 'The other data of the account field is mandatory.';
$mensajes['ref_280_eng'] = 'The other data of the account field can have up to 512 characters.';
$mensajes['ref_281_eng'] = 'The other data of the account field has characters that are not allowed.';
$mensajes['ref_282_eng'] = 'The do you have internet at the store field has sql commands.';
$mensajes['ref_283_eng'] = 'The do you have internet at the store field is incorrect.';
$mensajes['ref_284_eng'] = 'The do you have internet at the store field is mandatory.';
$mensajes['ref_285_eng'] = 'The do you have any Gift Card system field has sql commands.';
$mensajes['ref_286_eng'] = 'The do you have any Gift Card system field is incorrect.';
$mensajes['ref_287_eng'] = 'The do you have any Gift Card system field is mandatory.';
$mensajes['ref_288_eng'] = 'The how do you invoice your customers field has sql commands.';
$mensajes['ref_289_eng'] = 'The how do you invoice your customers field is incorrect:';
$mensajes['ref_290_eng'] = 'Error in the how do you invoice your customers field.';
$mensajes['ref_291_eng'] = 'The user id has sql commands.';
$mensajes['ref_292_eng'] = 'Error user id.';

#/var/www/html/app/objects/sesion/sesionManagerImpl.php
$mensajes['ref_293_eng'] = 'Incorrect user name or password.';
$mensajes['ref_294_eng'] = 'Permission denied.';
$mensajes['ref_295_eng'] = 'Incorrect session.';
$mensajes['ref_296_eng'] = 'Permission denied.';

#/var/www/html/app/objects/usuario/usuarioDaoImpl.php
$mensajes['ref_297_eng'] = 'Error user could not be registered.';
$mensajes['ref_299_eng'] = 'Error user id could not be updated.';
$mensajes['ref_300_eng'] = 'Error user id password could not be updated.';
$mensajes['ref_301_eng'] = 'Error user id could not be deleted.';
$mensajes['ref_303_eng'] = 'Error the data base to retrieve the user/password could not be updated.';
$mensajes['ref_304_eng'] = 'Error the new password could not be updated.';
$mensajes['ref_305_eng'] = 'Password could not be changed. Why: the recovery link or user does not exist.';

#/var/www/html/app/objects/usuario/usuarioManagerImpl.php
$mensajes['ref_306_eng'] = 'This username is already in use.';
$mensajes['ref_307_eng'] = 'Password cannot be blank.';
$mensajes['ref_308_eng'] = 'Password is not the same as password2.';
$mensajes['ref_309_eng'] = 'The profile is incorrect.';
$mensajes['ref_310_eng'] = 'The client id is incorrect.';
$mensajes['ref_311_eng'] = 'The user you want to create is in use.';
$mensajes['ref_312_eng'] = 'Password cannot be blank.';
$mensajes['ref_313_eng'] = 'The user name is already in use.';
$mensajes['ref_314_eng'] = 'Password cannot be blank.';
$mensajes['ref_315_eng'] = 'Password is not the same as password2.';
$mensajes['ref_316_eng'] = 'Not allowed to delete the user.';
$mensajes['ref_317_eng'] = 'Not allowed to delete the user.';
$mensajes['ref_318_eng'] = 'Not allowed to obtain user id.';
$mensajes['ref_319_eng'] = 'You cannot delete the user because you did not create it.';
$mensajes['ref_320_eng'] = 'The user has sql commands.';
$mensajes['ref_321_eng'] = 'The user must have more than 2 characters.';
$mensajes['ref_322_eng'] = 'The user can have up to 25 characters.';
$mensajes['ref_323_eng'] = 'Error user.  Vowels, numbers and the following symbols can be used: -_.@';
$mensajes['ref_324_eng'] = 'The profile has sql commands.';
$mensajes['ref_325_eng'] = 'Error user. The profile is incorrect.';
$mensajes['ref_326_eng'] = 'The user id has sql commands.';
$mensajes['ref_327_eng'] = 'Error user id.';
$mensajes['ref_328_eng'] = 'The password has sql commands.';
$mensajes['ref_329_eng'] = 'The password must have more than 5 characters.';
$mensajes['ref_330_eng'] = 'The password can have up to 12 characters.';
$mensajes['ref_331_eng'] = 'Error password. The password can contain vowels and numbers.';
$mensajes['ref_332_eng'] = 'The email you signed in is not registered.';
$mensajes['ref_333_eng'] = 'Password cannot be blank.';
$mensajes['ref_334_eng'] = 'Password is not the same as password2.';
$mensajes['ref_335_eng'] = 'Incorrect Hash.';
$mensajes['ref_336_eng'] = 'The email has sql commands.';
$mensajes['ref_337_eng'] = 'The email must have more than 5 characters.';
$mensajes['ref_338_eng'] = 'The email can have up to 64 characters.';
$mensajes['ref_339_eng'] = 'The email format is incorrect or contains characters that are not allowed.';
$mensajes['ref_340_eng'] = 'Hash has sql commands.';
$mensajes['ref_341_eng'] = 'Incorrect hash.';

#/var/www/html/app/objects/util/email.php
$mensajes['ref_342_eng'] = ' You must authorize the sending of email before doing so.';
$mensajes['ref_392_eng'] = 'Could not send the email.';

#eliminar_beneficiarioByCliente.php
$mensajes['ref_343_eng'] = 'successfully removed';

#/app/objects/content/contentManagerImpl.php
$mensajes['ref_386_eng'] = 'html (esp) has sql commands.';
$mensajes['ref_387_eng'] = 'titulo (esp) has sql commands.';
$mensajes['ref_388_eng'] = 'html (eng) has sql commands.';
$mensajes['ref_389_eng'] = 'title (eng) has sql commands.';
$mensajes['ref_390_eng'] = 'the content id has sql commands.';
$mensajes['ref_391_eng'] = 'Error the content could not be updated.';

#/var/www/html/app/public_recuperar.php
$mensajes['ref_393_eng'] = 'Check your email account for the Password Recovery email.';

#/var/www/html/app/objects/sesionRetailer/sesionRetailerManagerImpl.php
$mensajes['ref_394_eng'] = 'Unique key for exchange incorrect.';
$mensajes['ref_395_eng'] = 'Permission denied.';
$mensajes['ref_396_eng'] = 'Incorrect session.';

#/var/www/html/app/objects/mandaCheck/mandaCheckManagerImpl.php
$mensajes['ref_397_eng'] = 'Error incorrect #transaction.';
$mensajes['ref_398_eng'] = 'The #transaction has sql commands.';
$mensajes['ref_399_eng'] = 'the transaction not found';
$mensajes['ref_400_eng'] = 'The email has sql commands.';
$mensajes['ref_401_eng'] = 'The email format is incorrect or contains characters that are not allowed.';
$mensajes['ref_402_eng'] = 'Error identity Card is incorrect. i.e. of format: 001-0772076-5.';
$mensajes['ref_403_eng'] = 'Identity Card has sql commands.';
$mensajes['ref_404_eng'] = 'Incorrect PIN.';
$mensajes['ref_405_eng'] = 'Please confirm that you agree to the contract.';
$mensajes['ref_406_eng'] = 'incorrect hash';

#/var/www/html/app/objects/mandaCheck/mandaCheckDaoImpl.php
$mensajes['ref_407_eng'] = 'Error Status of MandaChecks completed. Transaction id could not be updated.';

################
##ESPANOL
################
$mensajes['ref_err_esp'] = 'error desconocido.';
$mensajes['ref_captcha_esp'] = 'El código de seguridad es incorrecto. Por favor intente nuevamente.';

#/var/www/html/app/objects/cuentaCorriente/cuentaCorrienteManagerImpl.php
$mensajes['ref_1_esp']  = 'El beneficiario alcanzo el monto máximo permitido por mes.';
$mensajes['ref_2_esp']  = 'Se puede enviar hasta USD 500 por mes.';
$mensajes['ref_3_esp']  = 'Se puede enviar hasta USD 250 por transacción.';
$mensajes['ref_4_esp']  = 'El campo dolar es incorrecto. Ej. de formato: 158.09.';
$mensajes['ref_5_esp']  = 'El campo dolar tiene sentencias de sql';
$mensajes['ref_6_esp']  = 'El campo monto local es incorrecto. Ej. de formato: 158.09.';
$mensajes['ref_7_esp']  = 'El campo monto local tiene sentencias de sql';
$mensajes['ref_8_esp']  = 'Error no se definio el beneficiario.';
$mensajes['ref_9_esp']  = 'El id beneficiario tiene sentencias de sql';
$mensajes['ref_10_esp'] = 'Error el cliente es incorrecto.';
$mensajes['ref_11_esp'] = 'El id cliente tiene sentencias de sql';
$mensajes['ref_12_esp'] = 'Error id detalle.';
$mensajes['ref_13_esp'] = 'El id detalle tiene sentencias de sql';
$mensajes['ref_14_esp'] = 'El campo De del paso 3 (resumen) tiene sentencias de sql';
$mensajes['ref_15_esp'] = 'El campo De del paso 3 (resumen) debe tener hasta 64 caracteres';
$mensajes['ref_16_esp'] = 'El campo De del paso 3 (resumen) tiene caracteres no permitidos';
$mensajes['ref_17_esp'] = 'El campo Para del paso 3 (resumen) tiene sentencias de sql';
$mensajes['ref_18_esp'] = 'El campo Para del paso 3 (resumen) debe tener hasta 64 caracteres';
$mensajes['ref_19_esp'] = 'El campo Para del paso 3 (resumen) tiene caracteres no permitidos.';
$mensajes['ref_20_esp'] = 'El campo Mensaje del paso 3 (resumen) tiene sentencias de sql';
$mensajes['ref_21_esp'] = 'El campo Mensaje del paso 3 (resumen) debe tener hasta 512 caracteres';
$mensajes['ref_22_esp'] = 'El campo Mensaje del paso 3 (resumen) tiene caracteres no permitidos.';
$mensajes['ref_23_esp'] = 'Error el beneficiario es incorrecto.';
$mensajes['ref_24_esp'] = 'Error usted no es el owner del beneficiario.';
$mensajes['ref_25_esp'] = 'Error el retailer es incorrecto.';
$mensajes['ref_26_esp'] = "El tipo de cambio es incorrecto. Haga click <a href=\"#\" onclick=\"setMoneda('irPaso3');\">aquí</a> para obtener el nuevo valor.";
$mensajes['ref_27_esp'] = 'La conversión dolar a moneda local es incorrecta.';
$mensajes['ref_28_esp'] = 'El monto local debe ser múltiplo de 100.';
$mensajes['ref_29_esp'] = 'No se puede realizar el pago. La transacción se encuentra vencida o paga.';
$mensajes['ref_30_esp'] = 'Err: No se pudo realizar el pago.';
$mensajes['ref_31_esp'] = 'terms aplease confirm that you read and accept MandaSeguro.com&#39;s terms and conditions';
$mensajes['ref_32_esp'] = 'Error no es el owner del beneficiario.';
$mensajes['ref_33_esp'] = 'Error transacción incorrecta.';
$mensajes['ref_34_esp'] = 'Transaction ID:';
$mensajes['ref_346_esp'] = 'El monto dolar debe ser mayor o igual a 35.';
$mensajes['ref_347_esp'] = 'El número de la tarjeta tiene sentencias sql';
$mensajes['ref_348_esp'] = 'Error el número de la tarjeta es incorrecto.';
$mensajes['ref_349_esp'] = 'La fecha de validez tiene&nbsp; sentencias sql';
$mensajes['ref_350_esp'] = 'Error la fecha de validez es incorrecta. ej. formato: mm/yy (mes/año).';
$mensajes['ref_351_esp'] = 'Error la fecha de validez (mm=mes) es incorrecta. ej. formato: mm/yy (mes/año).';
$mensajes['ref_352_esp'] = 'Error la fecha de validez (yy=año) es incorrecta. ej. formato: mm/yy (mes/año).';
$mensajes['ref_353_esp'] = 'El código de seguridad tiene sentencias sql';
$mensajes['ref_354_esp'] = 'Error &nbsp;el código de seguridad es incorrecto.';
$mensajes['ref_355_esp'] = 'El nombre tiene sentencias sql';
$mensajes['ref_356_esp'] = 'El nombre es incorrecto.';
$mensajes['ref_357_esp'] = 'El apellido tiene sentencias sql';
$mensajes['ref_358_esp'] = 'El apellido es incorrecto.';
$mensajes['ref_359_esp'] = ' El nombre puede tener hasta 50 caracteres.';
$mensajes['ref_360_esp'] = 'El apellido puede tener hasta 50 caracteres.';
$mensajes['ref_361_esp'] = 'El domicilio de facturación tiene sentencias sql';
$mensajes['ref_362_esp'] = 'El domicilio de facturación está incorrecto.';
$mensajes['ref_363_esp'] = 'El domicilio de facturación puede tener hasta 50 caracteres.';
$mensajes['ref_364_esp'] = 'La línea 2 del domicilio de facturación tiene sentencias sql';
$mensajes['ref_365_esp'] = 'La línea 2 del domicilio de facturación está incorrecta.';
$mensajes['ref_366_esp'] = 'La línea 2 del domicilio de facturación puede tener hasta 50 caracteres.';
$mensajes['ref_367_esp'] = 'El país del domicilio de facturación tiene sentencias sql';
$mensajes['ref_368_esp'] = 'El país del domicilio de facturación&nbsp; es incorrecto.';
$mensajes['ref_369_esp'] = 'El país del domicilio de facturación&nbsp; puede tener hasta 50 caracteres.';
$mensajes['ref_370_esp'] = 'El campo &ldquo;ciudad&ldquo; tiene sentencias sql';
$mensajes['ref_371_esp'] = 'El campo &ldquo;ciudad&ldquo; es incorrecto.';
$mensajes['ref_372_esp'] = 'El campo &ldquo;ciudad&ldquo; puede tener hasta 40 caracteres.';
$mensajes['ref_373_esp'] = 'El campo &ldquo;estado&ldquo; tiene sentencias sql';
$mensajes['ref_374_esp'] = 'El campo &ldquo;estado&ldquo; es incorrecto.';
$mensajes['ref_375_esp'] = 'El campo &ldquo;estado&ldquo; &nbsp;puede tener hasta 40 caracteres.';
$mensajes['ref_376_esp'] = 'El campo &ldquo;código postal&rdquo; tiene sentencias sql';
$mensajes['ref_377_esp'] = 'El campo &ldquo;código postal&rdquo; es incorrecto.';
$mensajes['ref_378_esp'] = 'El campo &ldquo;código postal&rdquo; puede tener hasta 20 caracteres.';
$mensajes['ref_379_esp'] = 'Error la fecha de nacimiento (mm=mes) es incorrecta. ej. formato: mm/dd/yyyy (mes/día/año).';
$mensajes['ref_380_esp'] = 'Error la fecha de nacimiento (dd=día) es incorrecta. ej. formato: mm/dd/yyyy (mes/día/año).';
$mensajes['ref_381_esp'] = 'Error la fecha de nacimiento (yyyy=año) es incorrecta. ej. formato: mm/dd/yyyy (mes/día/año).';
$mensajes['ref_382_esp'] = 'Error la fecha de nacimiento es incorrecta. ej. formato: mm/dd/yyyy (mes/día/año).';
$mensajes['ref_383_esp'] = 'Error: Debe cumplir con el requisito de edad (mayor de 18 años).';
$mensajes['ref_384_esp'] = 'El número de teléfono es incorrecto. ej. formato: 1231231234';
$mensajes['ref_385_esp'] = 'El número de teléfono puede tener hasta 25 dígitos.';
$mensajes['ref_409_esp'] = 'El id retailer tiene sentencias de sql';
$mensajes['ref_410_esp']  = 'Error el retailer es incorrect.';
$mensajes['ref_411_esp']  = 'pin incorrecto';

#/app/objects/cuentaCorriente/cuentaCorrienteDaoImpl.php
$mensajes['ref_35_esp'] = 'Error no se pudo dar de alta cuenta corriente cab.';
$mensajes['ref_36_esp'] = 'Error no se pudo dar de alta cuenta corriente det.';
$mensajes['ref_37_esp'] = 'Error base de datos Referencia: gtmcbm.';
$mensajes['ref_38_esp'] = 'Error base de datos Referencia: gtmbbm.';
$mensajes['ref_38_2_esp'] = 'Error no se pudo actualizar el estado Payment approval pending de la transaccion id';
$mensajes['ref_38_3_esp'] = 'Error no se pudo actualizar el estado Payment Error de la transaccion id';
$mensajes['ref_38_4_esp'] = 'Error no se pudo actualizar el estado Payment completed de la transaccion id';
$mensajes['ref_408_esp'] = 'Error no se pudo actualizar el estado Cancel de la transaccion id';

#/var/www/html/app/objects/beneficiario/beneficiarioDaoImpl.php
$mensajes['ref_39_esp'] = 'Error no se pudo dar de alta el beneficiario.';
$mensajes['ref_40_esp'] = 'Error no se pudo actualizar el beneficiario idUsuario';
$mensajes['ref_42_esp'] = 'Error no se pudo borrar el beneficiario id';
$mensajes['ref_44_esp'] = 'No se puede enviar dinero al beneficiario. Verifique que el país del beneficiario se encuentre habilitado para operar en mandaseguro.';

#/var/www/html/app/objects/beneficiario/beneficiarioManagerImpl.php
$mensajes['ref_45_esp'] = 'Debe completar al menos el email o el telefono para poder crear el beneficiario.';
$mensajes['ref_46_esp'] = 'No tiene permisos suficientes para modificar el beneficiario.';
$mensajes['ref_48_esp'] = 'No tiene permisos para eliminar cliente.';
$mensajes['ref_50_esp'] = 'El id usuario tiene sentencias de sql';
$mensajes['ref_51_esp'] = 'Error id usuario.';
$mensajes['ref_52_esp'] = 'El id beneficiario tiene sentencias de sql';
$mensajes['ref_53_esp'] = 'Error id beneficiario.';
$mensajes['ref_54_esp'] = 'El nombre tiene sentencias de sql';
$mensajes['ref_55_esp'] = 'El nombre debe tener hasta 30 caracteres';
$mensajes['ref_56_esp'] = 'El nombre tiene caracteres no permitidos.';
$mensajes['ref_57_esp'] = 'El apellido tiene sentencias de sql';
$mensajes['ref_58_esp'] = 'El apellido es obligatorio.';
$mensajes['ref_59_esp'] = 'El apellido debe tener hasta 30 caracteres';
$mensajes['ref_60_esp'] = 'El apellido tiene caracteres no permitidos.';
$mensajes['ref_61_esp'] = 'El email tiene sentencias de sql';
$mensajes['ref_62_esp'] = 'El email debe tener mas de 5 caracteres';
$mensajes['ref_63_esp'] = 'El email debe tener hasta 64 caracteres';
$mensajes['ref_64_esp'] = 'El formato del email es incorrecto o contiene caracteres no permitidos.';
$mensajes['ref_65_esp'] = 'El campo ciudad tiene sentencias de sql';
$mensajes['ref_66_esp'] = 'El campo ciudad debe tener mas de 2 caracteres';
$mensajes['ref_67_esp'] = 'El campo ciudad debe tener hasta 30 caracteres';
$mensajes['ref_68_esp'] = 'El campo ciudad tiene caracteres no permitidos.';
$mensajes['ref_69_esp'] = 'El campo estado tiene sentencias de sql';
$mensajes['ref_70_esp'] = 'El campo estado debe tener mas de 2 caracteres';
$mensajes['ref_71_esp'] = 'El campo estado debe tener hasta 30 caracteres';
$mensajes['ref_72_esp'] = 'El campo estado tiene caracteres no permitidos.';
$mensajes['ref_73_esp'] = 'El campo estado tiene sentencias de sql';
$mensajes['ref_74_esp'] = 'El campo estado debe tener hasta 30 caracteres';
$mensajes['ref_75_esp'] = 'El campo estado tiene caracteres no permitidos.';
$mensajes['ref_76_esp'] = 'El campo código postal tiene sentencias de sql';
$mensajes['ref_77_esp'] = 'El campo código postal debe tener hasta 12 caracteres';
$mensajes['ref_78_esp'] = 'El campo código postal tiene caracteres no permitidos.';
$mensajes['ref_79_esp'] = 'El campo país tiene sentencias de sql';
$mensajes['ref_80_esp'] = 'Actualmente se pueden registrar beneficiarios de República Dominicana.';
$mensajes['ref_81_esp'] = 'El campo país es incorrecto.';
$mensajes['ref_82_esp'] = 'El campo país es obligatorio.';
$mensajes['ref_83_esp'] = 'El campo teléfono cod país tiene sentencias de sql';
$mensajes['ref_84_esp'] = 'El campo teléfono ciudad tiene sentencias de sql';
$mensajes['ref_85_esp'] = 'El campo teléfono tiene sentencias de sql';
$mensajes['ref_86_esp'] = 'El campo teléfono tipo tiene sentencias de sql';
$mensajes['ref_87_esp'] = 'El campo teléfono tipo es incorrecto.';
$mensajes['ref_88_esp'] = 'El campo teléfono tipo es incorrecto.';
$mensajes['ref_89_esp'] = 'El campo  número de teléfono es incorrecto.';
$mensajes['ref_90_esp'] = 'El campo teléfono ciudad es incorrecto.';
$mensajes['ref_91_esp'] = 'El campo teléfono cod país es incorrecto.';
$mensajes['ref_92_esp'] = 'El campo dirección linea 1 tiene sentencias de sql';
$mensajes['ref_93_esp'] = 'El campo dirección linea 1 debe tener hasta 30 caracteres';
$mensajes['ref_94_esp'] = 'El campo dirección linea 1 tiene caracteres no permitidos.';
$mensajes['ref_95_esp'] = 'El campo dirección linea 2 tiene sentencias de sql';
$mensajes['ref_96_esp'] = 'El campo dirección linea 2 debe tener hasta 30 caracteres';
$mensajes['ref_97_esp'] = 'El campo ciudad 2 tiene sentencias de sql';
$mensajes['ref_98_esp'] = 'El campo ciudad 2 debe tener mas de 2 caracteres';
$mensajes['ref_99_esp'] = 'El campo ciudad 2 debe tener hasta 30 caracteres';
$mensajes['ref_100_esp'] = 'El campo ciudad 2 tiene caracteres no permitidos.';
$mensajes['ref_101_esp'] = 'El campo país 2 tiene sentencias de sql';
$mensajes['ref_102_esp'] = 'El campo país 2 es incorrecto. Actualmente se pueden registrar beneficiarios de República Dominicana.';
$mensajes['ref_103_esp'] = 'El campo país 2 es incorrecto.';
$mensajes['ref_104_esp'] = 'El campo país 2 es obligatorio.';
$mensajes['ref_105_esp'] = 'El parametro term tiene sentencias de sql';
$mensajes['ref_106_esp'] = 'El parametro term debe tener mas de 1 caracter';
$mensajes['ref_107_esp'] = 'El parametro term debe tener hasta 30 caracteres';
$mensajes['ref_108_esp'] = 'El parametro term tiene caracteres no permitidos.';
$mensajes['ref_108_1_esp'] = 'El nombre es obligatorio.';
$mensajes['ref_108_2_esp'] = 'terms aplease confirm that you read and accept MandaSeguro.com&#39;s terms and conditions';
$mensajes['ref_344_esp'] = 'Error el número de teléfono ya existe.';
$mensajes['ref_345_esp'] = 'El número de teléfono debe tener 7 dígitos.';

#/var/www/html/app/objects/ciudad/ciudadManagerImpl.php
$mensajes['ref_109_esp'] = 'El param ciudad tiene sentencias de sql';
$mensajes['ref_110_esp'] = 'El param ciudad es obligatorio';
$mensajes['ref_111_esp'] = 'El param ciudad debe tener hasta 30 caracteres';
$mensajes['ref_112_esp'] = 'El param ciudad tiene caracteres no permitidos.';

#/var/www/html/app/objects/cliente/clienteDaoImpl.php
$mensajes['ref_113_esp'] = 'Error no se pudo dar de alta el usuario.';
$mensajes['ref_114_esp'] = 'Error no se pudo actualizar el usuario.';
$mensajes['ref_115_esp'] = 'Error no se pudo borrar el usuario.';

#/var/www/html/app/objects/cliente/clienteManagerImpl.php
$mensajes['ref_116_esp'] = 'El email confirmado es incorrecto.';
$mensajes['ref_117_esp'] = 'El email ya se encuentra en uso.';
$mensajes['ref_120_esp'] = 'No tiene permisos suficientes para modificar el cliente.';
$mensajes['ref_121_esp'] = 'No tiene permisos para eliminar cliente.';
$mensajes['ref_122_esp'] = 'El id cliente tiene sentencias de sql';
$mensajes['ref_123_esp'] = 'Error id cliente.';
$mensajes['ref_124_esp'] = 'El nombre tiene sentencias de sql';
$mensajes['ref_125_esp'] = 'El nombre debe tener hasta 30 caracteres';
$mensajes['ref_126_esp'] = 'El nombre tiene caracteres no permitidos.';
$mensajes['ref_127_esp'] = 'El apellido tiene sentencias de sql';
$mensajes['ref_128_esp'] = 'El apellido es obligatorio.';
$mensajes['ref_129_esp'] = 'El apellido debe tener hasta 30 caracteres';
$mensajes['ref_130_esp'] = 'El apellido tiene caracteres no permitidos.';
$mensajes['ref_131_esp'] = 'El email tiene sentencias de sql';
$mensajes['ref_132_esp'] = 'El email debe tener mas de 5 caracteres';
$mensajes['ref_133_esp'] = 'El email debe tener hasta 64 caracteres';
$mensajes['ref_134_esp'] = 'El formato del email es incorrecto o contiene caracteres no permitidos.';
$mensajes['ref_135_esp'] = 'El campo ciudad tiene sentencias de sql';
$mensajes['ref_136_esp'] = 'El campo ciudad debe tener mas de 2 caracteres';
$mensajes['ref_137_esp'] = 'El campo ciudad debe tener hasta 30 caracteres';
$mensajes['ref_138_esp'] = 'El campo ciudad tiene caracteres no permitidos.';
$mensajes['ref_139_esp'] = 'El campo estado tiene sentencias de sql';
$mensajes['ref_140_esp'] = 'El campo estado es obligatorio.';
$mensajes['ref_141_esp'] = 'El campo estado debe tener hasta 30 caracteres';
$mensajes['ref_142_esp'] = 'El campo estado tiene caracteres no permitidos.';
$mensajes['ref_143_esp'] = 'El campo código postal tiene sentencias de sql';
$mensajes['ref_144_esp'] = 'El campo código postal debe tener hasta 12 caracteres';
$mensajes['ref_145_esp'] = 'El campo código postal tiene caracteres no permitidos.';
$mensajes['ref_146_esp'] = 'El campo país tiene sentencias de sql';
$mensajes['ref_147_esp'] = 'Actualmente se pueden registrar clientes de United States.';
$mensajes['ref_148_esp'] = 'El campo país es incorrecto.';
$mensajes['ref_149_esp'] = 'El campo país es obligatorio.';
$mensajes['ref_150_esp'] = 'El campo telefono1 país tiene sentencias de sql';
$mensajes['ref_151_esp'] = 'El campo telefono1 ciudad tiene sentencias de sql';
$mensajes['ref_152_esp'] = 'El campo telefono1 tiene sentencias de sql';
$mensajes['ref_153_esp'] = 'El campo telefono1 tipo tiene sentencias de sql';
$mensajes['ref_154_esp'] = 'El campo telefono1 tipo es incorrecto.';
$mensajes['ref_155_esp'] = 'El campo telefono1 tipo es incorrecto.';
$mensajes['ref_156_esp'] = 'El campo telefono1 es incorrecto.';
$mensajes['ref_157_esp'] = 'El campo telefono1 ciudad es incorrecto.';
$mensajes['ref_158_esp'] = 'El campo telefono1 país es incorrecto.';
$mensajes['ref_159_esp'] = 'El campo telefono2 país tiene sentencias de sql';
$mensajes['ref_160_esp'] = 'El campo telefono2 ciudad tiene sentencias de sql';
$mensajes['ref_161_esp'] = 'El campo telefono2 tiene sentencias de sql';
$mensajes['ref_162_esp'] = 'El campo telefono2 tipo tiene sentencias de sql';
$mensajes['ref_163_esp'] = 'El campo telefono2 tipo es incorrecto.';
$mensajes['ref_164_esp'] = 'El campo telefono2 tipo es incorrecto.';
$mensajes['ref_165_esp'] = 'El campo telefono2 es incorrecto.';
$mensajes['ref_166_esp'] = 'El campo telefono2 ciudad es incorrecto.';
$mensajes['ref_167_esp'] = 'El campo telefono2 país es incorrecto.';
$mensajes['ref_167_1_esp'] = 'terms aplease confirm that you read and accept MandaSeguro.com&#39;s terms and conditions';
$mensajes['ref_167_2_esp'] = 'El nombre es obligatorio.';

#/var/www/html/app/objects/monedaCambio/monedaCambioDaoImpl.php
$mensajes['ref_168_esp'] = 'Error no se pudo dar de alta el tipo de cambio.';
$mensajes['ref_169_esp'] = 'Error no se pudo actualizar el tipo de cambio.';
$mensajes['ref_170_esp'] = 'Error no se pudo borrar el tipo de cambio id';
$mensajes['ref_171_esp'] = 'El país seleccionado no se encuentre habilitado para operar en mandaseguro.';

#/var/www/html/app/objects/monedaCambio/monedaCambioManagerImpl.php
$mensajes['ref_172_esp'] = 'El campo cambio tiene sentencias de sql';
$mensajes['ref_173_esp'] = 'El campo cambio es incorrecto. Ej. de formato: 158.09.';
$mensajes['ref_174_esp'] = 'El id usuario tiene sentencias de sql';
$mensajes['ref_175_esp'] = 'Error id usuario.';
$mensajes['ref_176_esp'] = 'El id tiene sentencias de sql';
$mensajes['ref_177_esp'] = 'Error id.';
$mensajes['ref_178_esp'] = 'El campo país tiene sentencias de sql';
$mensajes['ref_179_esp'] = 'El campo país es incorrecto.';
$mensajes['ref_180_esp'] = 'El campo país es obligatorio.';

#/var/www/html/app/objects/retailer/retailerDaoImpl.php
$mensajes['ref_181_esp'] = 'Error no se pudo dar de alta el retailer.';
$mensajes['ref_182_esp'] = 'Error no se pudo actualizar el retailer idUsuario';
$mensajes['ref_183_esp'] = 'Error no se pudo borrar el retailer id';

#/var/www/html/app/objects/retailer/retailerManagerImpl.php
$mensajes['ref_184_esp'] = 'No tiene permisos suficientes para modificar el retailer.';
$mensajes['ref_185_esp'] = 'No tiene permisos para eliminar retailer.';
$mensajes['ref_186_esp'] = 'El id cliente tiene sentencias de sql';
$mensajes['ref_187_esp'] = 'Error id cliente.';
$mensajes['ref_188_esp'] = 'El campo razón social tiene sentencias de sql';
$mensajes['ref_189_esp'] = 'El campo razón social es obligatorio.';
$mensajes['ref_190_esp'] = 'El campo razón social debe tener hasta 128 caracteres.';
$mensajes['ref_191_esp'] = 'El campo razón social tiene caracteres no permitidos.';
$mensajes['ref_192_esp'] = 'El campo nombre del negocio tiene sentencias de sql';
$mensajes['ref_193_esp'] = 'El campo nombre del negocio es obligatorio.';
$mensajes['ref_194_esp'] = 'El campo nombre del negocio debe tener hasta 128 caracteres.';
$mensajes['ref_195_esp'] = 'El campo nombre del negocio tiene caracteres no permitidos.';
$mensajes['ref_196_esp'] = 'El campo identificación tributaria tiene sentencias de sql';
$mensajes['ref_197_esp'] = 'El campo identificación tributaria es obligatorio.';
$mensajes['ref_198_esp'] = 'El campo identificación tributaria debe tener hasta 128 caracteres.';
$mensajes['ref_199_esp'] = 'El campo identificación tributaria tiene caracteres no permitidos.';
$mensajes['ref_200_esp'] = 'El nombre tiene sentencias de sql';
$mensajes['ref_201_esp'] = 'El nombre es obligatorio.';
$mensajes['ref_202_esp'] = 'El nombre debe tener hasta 30 caracteres';
$mensajes['ref_203_esp'] = 'El nombre tiene caracteres no permitidos.';
$mensajes['ref_204_esp'] = 'El apellido tiene sentencias de sql';
$mensajes['ref_205_esp'] = 'El apellido es obligatorio.';
$mensajes['ref_206_esp'] = 'El apellido debe tener hasta 30 caracteres';
$mensajes['ref_207_esp'] = 'El apellido tiene caracteres no permitidos.';
$mensajes['ref_208_esp'] = 'El email tiene sentencias de sql';
$mensajes['ref_209_esp'] = 'El email debe tener mas de 5 caracteres';
$mensajes['ref_210_esp'] = 'El email debe tener hasta 64 caracteres';
$mensajes['ref_211_esp'] = 'El formato del email es incorrecto o contiene caracteres no permitidos.';
$mensajes['ref_212_esp'] = 'El campo ciudad tiene sentencias de sql';
$mensajes['ref_213_esp'] = 'El campo ciudad debe tener mas de 2 caracteres';
$mensajes['ref_214_esp'] = 'El campo ciudad debe tener hasta 30 caracteres';
$mensajes['ref_215_esp'] = 'El campo ciudad tiene caracteres no permitidos.';
$mensajes['ref_216_esp'] = 'El campo estado tiene sentencias de sql';
$mensajes['ref_217_esp'] = 'El campo estado es obligatorio.';
$mensajes['ref_218_esp'] = 'El campo estado debe tener hasta 30 caracteres';
$mensajes['ref_219_esp'] = 'El campo estado tiene caracteres no permitidos.';
$mensajes['ref_220_esp'] = 'El campo código postal tiene sentencias de sql';
$mensajes['ref_221_esp'] = 'El campo código postal es obligatorio';
$mensajes['ref_222_esp'] = 'El campo código postal debe tener hasta 30 caracteres';
$mensajes['ref_223_esp'] = 'El campo código postal tiene caracteres no permitidos.';
$mensajes['ref_224_esp'] = 'El campo país tiene sentencias de sql';
$mensajes['ref_225_esp'] = 'Actualmente se pueden registrar retailers de República Dominicana.';
$mensajes['ref_226_esp'] = 'El campo país es incorrecto.';
$mensajes['ref_227_esp'] = 'El campo país es obligatorio.';
$mensajes['ref_228_esp'] = 'El campo telefono1 país tiene sentencias de sql';
$mensajes['ref_229_esp'] = 'El campo telefono1 ciudad tiene sentencias de sql';
$mensajes['ref_230_esp'] = 'El campo telefono1 tiene sentencias de sql';
$mensajes['ref_231_esp'] = 'El campo telefono1 tipo tiene sentencias de sql';
$mensajes['ref_232_esp'] = 'El campo telefono1 tipo es incorrecto.';
$mensajes['ref_233_esp'] = 'El campo telefono1 tipo es incorrecto.';
$mensajes['ref_234_esp'] = 'El campo telefono1 es incorrecto.';
$mensajes['ref_235_esp'] = 'El campo telefono1 ciudad es incorrecto.';
$mensajes['ref_236_esp'] = 'El campo telefono1 país es incorrecto.';
$mensajes['ref_237_esp'] = 'El campo telefono2 país tiene sentencias de sql';
$mensajes['ref_238_esp'] = 'El campo telefono2 ciudad tiene sentencias de sql';
$mensajes['ref_239_esp'] = 'El campo telefono2 tiene sentencias de sql';
$mensajes['ref_240_esp'] = 'El campo telefono2 tipo tiene sentencias de sql';
$mensajes['ref_241_esp'] = 'El campo telefono2 tipo es incorrecto.';
$mensajes['ref_242_esp'] = 'El campo telefono2 tipo es incorrecto.';
$mensajes['ref_243_esp'] = 'El campo telefono2 es incorrecto.';
$mensajes['ref_244_esp'] = 'El campo telefono2 ciudad es incorrecto.';
$mensajes['ref_245_esp'] = 'El campo telefono2 país es incorrecto.';
$mensajes['ref_246_esp'] = 'El campo rubro tiene sentencias de sql';
$mensajes['ref_247_esp'] = 'El campo rubro es obligatorio.';
$mensajes['ref_248_esp'] = 'El campo rubro debe tener hasta 30 caracteres';
$mensajes['ref_249_esp'] = 'El campo rubro tiene caracteres no permitidos.';
$mensajes['ref_250_esp'] = 'El campo dirección linea 1 tiene sentencias de sql';
$mensajes['ref_251_esp'] = 'El campo dirección linea 1 debe tener mas de 2 caracteres';
$mensajes['ref_252_esp'] = 'El campo dirección linea 1 debe tener hasta 30 caracteres';
$mensajes['ref_253_esp'] = 'El campo dirección linea 1 tiene caracteres no permitidos.';
$mensajes['ref_254_esp'] = 'El campo dirección linea 2 tiene sentencias de sql';
$mensajes['ref_255_esp'] = 'El campo dirección linea 2 debe tener mas de 2 caracteres';
$mensajes['ref_256_esp'] = 'El campo dirección linea 2 debe tener hasta 30 caracteres';
$mensajes['ref_257_esp'] = 'El campo dirección linea 2 tiene caracteres no permitidos.';
$mensajes['ref_258_esp'] = 'El campo ciudad 2 tiene sentencias de sql';
$mensajes['ref_259_esp'] = 'El campo ciudad 2 debe tener mas de 2 caracteres';
$mensajes['ref_260_esp'] = 'El campo ciudad 2 debe tener hasta 30 caracteres';
$mensajes['ref_261_esp'] = 'El campo ciudad 2 tiene caracteres no permitidos.';
$mensajes['ref_262_esp'] = 'El campo país 2 tiene sentencias de sql';
$mensajes['ref_263_esp'] = 'El campo país 2 es incorrecto. Actualmente se pueden registrar retailers de República Dominicana.';
$mensajes['ref_264_esp'] = 'El campo país 2 es incorrecto.';
$mensajes['ref_265_esp'] = 'El campo país 2 es obligatorio.';
$mensajes['ref_266_esp'] = 'El campo nombre del banco tiene sentencias de sql';
$mensajes['ref_267_esp'] = 'El campo nombre del banco es obligatorio.';
$mensajes['ref_268_esp'] = 'El campo nombre del banco debe tener hasta 128 caracteres.';
$mensajes['ref_269_esp'] = 'El campo nombre del banco tiene caracteres no permitidos.';
$mensajes['ref_270_esp'] = 'El campo número de cuenta tiene sentencias de sql';
$mensajes['ref_271_esp'] = 'El campo número de cuenta es obligatorio.';
$mensajes['ref_272_esp'] = 'El campo número de cuenta debe tener hasta 64 caracteres.';
$mensajes['ref_273_esp'] = 'El campo número de cuenta tiene caracteres no permitidos.';
$mensajes['ref_274_esp'] = 'El campo titular de la cuenta tiene sentencias de sql';
$mensajes['ref_275_esp'] = 'El campo titular de la cuenta es obligatorio.';
$mensajes['ref_276_esp'] = 'El campo titular de la cuenta debe tener hasta 128 caracteres.';
$mensajes['ref_277_esp'] = 'El campo titular de la cuenta tiene caracteres no permitidos.';
$mensajes['ref_278_esp'] = 'El campo otros datos de la cuenta tiene sentencias de sql';
$mensajes['ref_279_esp'] = 'El campo otros datos de la cuenta es obligatorio.';
$mensajes['ref_280_esp'] = 'El campo otros datos de la cuenta debe tener hasta 512 caracteres.';
$mensajes['ref_281_esp'] = 'El campo otros datos de la cuenta tiene caracteres no permitidos.';
$mensajes['ref_282_esp'] = 'El campo tiene internet en el local de venta, tiene sentencias de sql';
$mensajes['ref_283_esp'] = 'El campo tiene internet en el local de venta es incorrecto.';
$mensajes['ref_284_esp'] = 'El campo tiene internet en el local de venta es obligatorio.';
$mensajes['ref_285_esp'] = 'El campo tiene algún sistema de Gift Card, tiene sentencias de sql';
$mensajes['ref_286_esp'] = 'El campo tiene algún sistema de Gift Card es incorrecto.';
$mensajes['ref_287_esp'] = 'El campo tiene algún sistema de Gift Card es obligatorio.';
$mensajes['ref_288_esp'] = 'El campo como facturan sus clientes, tiene sentencias de sql';
$mensajes['ref_289_esp'] = 'El campo como facturan sus clientes es incorrecto:';
$mensajes['ref_290_esp'] = 'Error en el campo como facturan sus clientes.';
$mensajes['ref_291_esp'] = 'El id usuario tiene sentencias de sql';
$mensajes['ref_292_esp'] = 'Error id usuario.';

#/var/www/html/app/objects/sesion/sesionManagerImpl.php
$mensajes['ref_293_esp'] = 'Usuario o pass incorrecto.';
$mensajes['ref_294_esp'] = 'Permiso denegado.';
$mensajes['ref_295_esp'] = 'Sesión incorrecta.';
$mensajes['ref_296_esp'] = 'Permiso denegado.';

#/var/www/html/app/objects/usuario/usuarioDaoImpl.php
$mensajes['ref_297_esp'] = 'Error no se pudo dar de alta el usuario.';
$mensajes['ref_299_esp'] = 'Error no se pudo actualizar el usuario id:';
$mensajes['ref_300_esp'] = 'Error no se pudo actualizar el pass usuario id:';
$mensajes['ref_301_esp'] = 'Error no se pudo borrar el usuario id:';
$mensajes['ref_303_esp'] = 'Error no se pudo actualizar la base de datos para recuperar la clave/usuario.';
$mensajes['ref_304_esp'] = 'Error no se pudo actualizar password nueva.';
$mensajes['ref_305_esp'] = 'No se pudo cambiar las password. Motivos: El link de recuperación o usuario no existen.';

#/var/www/html/app/objects/usuario/usuarioManagerImpl.php
$mensajes['ref_306_esp'] = 'El usuario que se quiere crear se encuentra en uso.';
$mensajes['ref_307_esp'] = 'Password no puede ser blanco';
$mensajes['ref_308_esp'] = 'Password no es igual al password2';
$mensajes['ref_309_esp'] = 'El perfil es incorrecto.';
$mensajes['ref_310_esp'] = 'El id del cliente es incorrecto.';
$mensajes['ref_311_esp'] = 'El usuario que se quiere crear se encuentra en uso.';
$mensajes['ref_312_esp'] = 'Password no puede ser blanco';
$mensajes['ref_313_esp'] = 'El nombre de usuario ya se encuentra en uso.';
$mensajes['ref_314_esp'] = 'Password no puede ser blanco';
$mensajes['ref_315_esp'] = 'Password no es igual al password2';
$mensajes['ref_316_esp'] = 'No tiene permisos para eliminar usuario.';
$mensajes['ref_317_esp'] = 'No tiene permisos para eliminar usuario.';
$mensajes['ref_318_esp'] = 'No tiene permisos para obtener el id de usuario.';
$mensajes['ref_319_esp'] = 'No puede borrar el usuario porque no es el creador.';
$mensajes['ref_320_esp'] = 'El usuario tiene sentencias de sql';
$mensajes['ref_321_esp'] = 'El usuario debe tener mas de 2 caracteres';
$mensajes['ref_322_esp'] = 'El usuario debe tener hasta 25 caracteres';
$mensajes['ref_323_esp'] = 'Error usuario. Se permiten vocales, números y los siguiente símbolos: -_.@';
$mensajes['ref_324_esp'] = 'El perfil tiene sentencias de sql';
$mensajes['ref_325_esp'] = 'Error usuario. El perfil es incorrecto.';
$mensajes['ref_326_esp'] = 'El id usuario tiene sentencias de sql';
$mensajes['ref_327_esp'] = 'Error id usuario.';
$mensajes['ref_328_esp'] = 'El password tiene sentencias de sql';
$mensajes['ref_329_esp'] = 'El password debe tener mas de 5 caracteres';
$mensajes['ref_330_esp'] = 'El password debe tener hasta 12 caracteres';
$mensajes['ref_331_esp'] = 'Error password. El password puede contener vocales y números.';
$mensajes['ref_332_esp'] = 'El email que ingreso no se encuentra registrado.';
$mensajes['ref_333_esp'] = 'Password no puede ser blanco';
$mensajes['ref_334_esp'] = 'Password no es igual al password2';
$mensajes['ref_335_esp'] = 'Hash incorrecto.';
$mensajes['ref_336_esp'] = 'El email tiene sentencias de sql';
$mensajes['ref_337_esp'] = 'El email debe tener mas de 5 caracteres';
$mensajes['ref_338_esp'] = 'El email debe tener hasta 64 caracteres';
$mensajes['ref_339_esp'] = 'El formato del email es incorrecto o contiene caracteres no permitidos.';
$mensajes['ref_340_esp'] = 'El hash tiene sentencias de sql:';
$mensajes['ref_341_esp'] = 'El hash es incorrecto.';

#/var/www/html/app/objects/util/email.php
$mensajes['ref_342_esp'] = 'Debe habilitar el envio de email antes de enviar.';
$mensajes['ref_392_esp'] = 'No se pudo enviar el email.';

#eliminar_beneficiarioByCliente.php
$mensajes['ref_343_esp'] = 'se elimino con éxito';

#/app/objects/content/contentManagerImpl.php
$mensajes['ref_386_esp'] = 'html (esp) tiene sentencias de sql.';
$mensajes['ref_387_esp'] = 'titulo (esp) tiene sentencias de sql.';
$mensajes['ref_388_esp'] = 'html (eng) tiene sentencias de sql.';
$mensajes['ref_389_esp'] = 'title (eng) tiene sentencias de sql.';
$mensajes['ref_390_esp'] = 'the content id has sql commands.';
$mensajes['ref_391_esp'] = 'Error no se pudo actualizar el contenido.';

#/var/www/html/app/public_recuperar.php
$mensajes['ref_393_esp'] = 'Revise su cuenta de correo para recuperar la clave.';

#/var/www/html/app/objects/sesionRetailer/sesionRetailerManagerImpl.php
$mensajes['ref_394_esp'] = 'Clave única para intercambio incorrecto.';
$mensajes['ref_395_esp'] = 'Permiso denegado.';
$mensajes['ref_396_esp'] = 'Sesión incorrecta.';

#/var/www/html/app/objects/mandaCheck/mandaCheckManagerImpl.php
$mensajes['ref_397_esp'] = 'Error el núm de transacción es incorrecto.';
$mensajes['ref_398_esp'] = 'El núm de transacción tiene sentencias sql.';
$mensajes['ref_399_esp'] = 'No se encontró la transacción.';
$mensajes['ref_400_esp'] = 'El email tiene sentencias de sql';
$mensajes['ref_401_esp'] = 'El formato del email es incorrecto o contiene caracteres no permitidos.';
$mensajes['ref_402_esp'] = 'El campo Cédula de identidad es incorrecto. Ej. de formato: 001-0772076-5.';
$mensajes['ref_403_esp'] = 'El campo Cédula de identidad tiene sentencias sql.';
$mensajes['ref_404_esp'] = 'El PIN es incorrecto.';
$mensajes['ref_405_esp'] = 'Por favor confirmar que está de acuerdo con el contrato.';
$mensajes['ref_406_esp'] = 'El Hash de validación es incorrecto.';

#/var/www/html/app/objects/mandaCheck/mandaCheckDaoImpl.php
$mensajes['ref_407_esp'] = 'Error no se pudo actualizar el estado MandaChecks completed de la transaccion id ';


global $configArrayEstado;
$configArrayEstado[] = "Payment approval pending";
$configArrayEstado[] = "Payment pending";
$configArrayEstado[] = "Payment Error";
$configArrayEstado[] = "Payment completed";
$configArrayEstado[] = "Cancel";
$configArrayEstado[] = "Expired";
$configArrayEstado[] = "MandaChecks available";
$configArrayEstado[] = "MandaChecks completed";

global $configArrayEstadoRetailer;
$configArrayEstadoRetailer[] = "Payment completed";
$configArrayEstadoRetailer[] = "Cancel";
$configArrayEstadoRetailer[] = "MandaChecks available";
$configArrayEstadoRetailer[] = "MandaChecks completed";

global $configArrayRubro;
$configArrayRubro[] = "Ferretería";
$configArrayRubro[] = "Almacen";
$configArrayRubro[] = "Farmacia";
?>

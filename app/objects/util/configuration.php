<?php
include_once("/var/www/configuration_pass.php");

function _menuPerfil ($fotoPerfilParam,$menuperfilParam){
    $contenido = new Template("menu-perfil");
	$contenido->asigna_variables(array(
            "nombre" => $GLOBALS['sesionG']['nombre']." ".$GLOBALS['sesionG']['apellido'],
            "menuperfil" => $menuperfilParam

			));
    $retcontenidoString = $contenido->muestra();
    return $retcontenidoString;
}

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


global $menuperfil;
$menuperfil['picker'] = <<<STR
<a href="/favoritos.html">Favoritos</a>
<a href="/mis-compras.html">Compras</a>
STR;

$menuperfil['seller'] = <<<STR
<a href="/favoritos.html">Favoritos</a>
<a href="/mis-ventas.html">Ventas</a>
<a href="/mis-compras.html">Compras</a>
<a href="/mis-publicaciones.html">Mis Publicaciones</a>
<a href="/ampliar-producto.html">Mis Productos</a>
STR;


################
##ESPANOL
################
global $mensajes;
$mensajes['ref_err_esp'] = 'error desconocido.';
$mensajes['ref_captcha_esp'] = 'El código de seguridad es incorrecto. Por favor intente nuevamente.';


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
$mensajes[''] = 'El id usuario tiene sentencias de sql';
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

function getMsjConf ($ref){
	$language = 'esp'; #eng o esp
	$ret = isset ($GLOBALS['mensajes']["ref_".$ref."_".$language]) ? $GLOBALS['mensajes']["ref_".$ref."_".$language] :  "Referencia del error desconocida.$ref";
	return $ret;
}



?>

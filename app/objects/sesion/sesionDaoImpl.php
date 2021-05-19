<?php
include_once($GLOBALS['configuration']['path_app_admin_objects']."sesion/sesionDao.php");
class  SesionDaoImpl implements SesionDao{
   
	public function __construct(){
   } 

	public function alta (Sesion $sesion){
		$llave_md5 = md5($this->generarHashSesion().time().rand());
		$arrayMd5 = $this->splitCadena($llave_md5);

		$_SESSION["usuario"]  = $this->cifrar($sesion->getUsuario(),$llave_md5); 
		$_SESSION["identify"] = $sesion->getIdentify();
		$_SESSION["perfil"]   = $this->cifrar($sesion->getPerfil(),$llave_md5);
		$_SESSION["hash"]     = $arrayMd5[0];
		$_SESSION["idusuario"]  = $this->cifrar($sesion->getIdUsuario(),$llave_md5);
		if ($sesion->getObj()){
			$_SESSION["email"]     =  $this->cifrar($sesion->getObj()->getEmail(),$llave_md5);
			$_SESSION["apellido"]  =  $this->cifrar($sesion->getObj()->getApellido(),$llave_md5);
			$_SESSION["nombre"]    =  $this->cifrar($sesion->getObj()->getNombre(),$llave_md5);
			$_SESSION["id"]        =  $this->cifrar($sesion->getObj()->getId(),$llave_md5);
		}else{
			if ($sesion->getPerfil() == 'superadmin'){
				$_SESSION["nombre"]  = $this->cifrar("admin",$llave_md5);
			}else{
				$_SESSION["nombre"]  = $this->cifrar($sesion->getPerfil(),$llave_md5);
			}
		}
		$recordarme  = isset($_REQUEST["recordarme"]) ? $_REQUEST["recordarme"] : '';
		$recordarme  = 'recordarme'; #se harcodeo hasta que agreguen el recordarme en el frontend
		$sessionCookieExpireTime = time() + 3600*24*365;
		if ($recordarme == 'recordarme'){
			setcookie("hash", $arrayMd5[1],$sessionCookieExpireTime,"/");
			setcookie(session_name(), $_COOKIE[session_name()], $sessionCookieExpireTime,"/");
		}else{
			setcookie("hash", $arrayMd5[1],0,"/");
			setcookie(session_name(), $_COOKIE[session_name()],0,"/");
		}
		if ($sesion->getPerfil() == 'seller'){
			setcookie("language", 'esp',$sessionCookieExpireTime,"/");
	   }
   }


   public function guardar ($nombre,$apellido,$email){
	$hash      =  isset($_SESSION["hash"]) ? $_SESSION["hash"] : "";
	$hash2 = (isset($_COOKIE['hash'])) ? $_COOKIE['hash'] : "";

	$llave_md5 = $hash.$hash2;

	$_SESSION["email"]     =  $this->cifrar($email,$llave_md5);
	$_SESSION["apellido"]  =  $this->cifrar($apellido,$llave_md5);
	$_SESSION["nombre"]    =  $this->cifrar($nombre,$llave_md5);

	$GLOBALS['sesionG']['nombre'] =  $nombre;
	$GLOBALS['sesionG']['apellido'] = $apellido;
	$GLOBALS['sesionG']['email'] = $email;

  }

  public function guardarUsuario ($usuario){
	$hash      =  isset($_SESSION["hash"]) ? $_SESSION["hash"] : "";
	$hash2 = (isset($_COOKIE['hash'])) ? $_COOKIE['hash'] : "";

	$llave_md5 = $hash.$hash2;

	$_SESSION["usuario"]   = $this->cifrar($usuario,$llave_md5); 
	
	$GLOBALS['sesionG']['usuario'] = $usuario;

  }

	public function eliminar (){
		setcookie("hash","");
		//setcookie(session_name(),"");
   	$_SESSION = array();
      session_destroy ();
   }
	
	public function borrarDatos (){
		setcookie("hash","");
		$_SESSION["usuario"]   = ""; 
		$_SESSION["identify"]  = "";
		$_SESSION["perfil"]    = "";
		$_SESSION["hash"]      = "";
		$_SESSION["idusuario"] = "";
		$_SESSION["nombre"]    =  "";
		$_SESSION["apellido"]    =  "";
		$_SESSION["email"]    =  "";
		$_SESSION["id"]        =  "";
   }

	public function get (){
		$usuario  =  isset($_SESSION["usuario"]) ? $_SESSION["usuario"] : "";
		$identify =  isset($_SESSION["identify"]) ? $_SESSION["identify"] : "";
		$perfil   =  isset($_SESSION["perfil"]) ? $_SESSION["perfil"] : "";
		$idUsuario =  isset($_SESSION["idusuario"]) ? $_SESSION["idusuario"] : "";
		$hash      =  isset($_SESSION["hash"]) ? $_SESSION["hash"] : "";
		$hash2 = (isset($_COOKIE['hash'])) ? $_COOKIE['hash'] : "";

		$llave_md5 = $hash.$hash2;
		$GLOBALS['sesionG']['nombre'] = (isset($_SESSION['nombre'])) ? $this->descifrar($_SESSION['nombre'],$llave_md5) : "";
		$GLOBALS['sesionG']['apellido'] = (isset($_SESSION['apellido'])) ? $this->descifrar($_SESSION['apellido'],$llave_md5) : "";
		$GLOBALS['sesionG']['email'] = (isset($_SESSION['email'])) ? $this->descifrar($_SESSION['email'],$llave_md5) : "";
		$GLOBALS['sesionG']['id'] = (isset($_SESSION['id'])) ? $this->descifrar($_SESSION['id'],$llave_md5) : "";
		$sesion = new Sesion();
		$sesion->setUsuario($this->descifrar($usuario,$llave_md5));	
		$sesion->setIdentify($identify);	
		$sesion->setPerfil($this->descifrar($perfil,$llave_md5));	
		$sesion->setIdUsuario($this->descifrar($idUsuario,$llave_md5));	
		
		return $sesion;
   }

	private function cifrar ($str,$llave_md5){
		if ($str == ''){
			return "";
		}
		$llave_md5 = md5($llave_md5.$_SERVER['REMOTE_ADDR']);
		$cifrador = mcrypt_module_open(MCRYPT_DES, "", MCRYPT_MODE_ECB, "");
		$maximo_tamanyo_vector_inicio = mcrypt_enc_get_iv_size($cifrador);

		//ECB ignora el vector inicio, pero hay que ponerlo
		$vector_inicio = mcrypt_create_iv($maximo_tamanyo_vector_inicio, MCRYPT_RAND );
		$maximo_tamanyo_llave = mcrypt_enc_get_key_size($cifrador);

		$llave = substr($llave_md5, 0, $maximo_tamanyo_llave);

		mcrypt_generic_init($cifrador, $llave, $vector_inicio);
		$str_cifrado = mcrypt_generic($cifrador, $str);
		
		mcrypt_generic_deinit($cifrador);
		mcrypt_module_close($cifrador);
		
		$str_64 = base64_encode($str_cifrado);
		return $str_64;
	}


	function descifrar ($str_cifrado,$llave_md5){
		if ($str_cifrado == ''){
			return "";
		}
		$llave_md5 = md5($llave_md5.$_SERVER['REMOTE_ADDR']);
		$str_64 = base64_decode($str_cifrado);
		
		$cifrador = mcrypt_module_open(MCRYPT_DES, "", MCRYPT_MODE_ECB, "");
		$maximo_tamanyo_vector_inicio = mcrypt_enc_get_iv_size($cifrador);

		//ECB ignora el vector inicio, pero hay que ponerlo
		$vector_inicio = mcrypt_create_iv($maximo_tamanyo_vector_inicio, MCRYPT_RAND );
		$maximo_tamanyo_llave = mcrypt_enc_get_key_size($cifrador);

		$llave = substr($llave_md5, 0, $maximo_tamanyo_llave);

		mcrypt_generic_init($cifrador, $llave, $vector_inicio);
		$str = mdecrypt_generic($cifrador, $str_64);
		mcrypt_generic_deinit($cifrador);
		mcrypt_module_close($cifrador);

		return chop($str);
	}
	
	private function generarHashSesion(){
		$clave = $GLOBALS['configuration']['clave_sesion'];
		return $clave;
	}


	private function splitCadena ($str){
		$cant = strlen($str);
		$parte1 = (int) ($cant/2);
		$cadena1 = substr($str, 0, $parte1);
		$cadena2 = substr($str, $parte1, $cant);
		return array($cadena1,$cadena2);	
	}
}
?>

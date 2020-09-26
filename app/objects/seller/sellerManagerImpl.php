<?php
include_once($GLOBALS['configuration']['path_app_admin_objects']."seller/seller.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."seller/sellerManager.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."seller/sellerDaoImpl.php");

class SellerManagerImpl implements  SellerManager{
	private $sellerDao;
	private $status    = "";
	private $msj       = "";
	private $paginador = "";

   public function __construct(){
		$this->sellerDao = new SellerDaoImpl();
   } 
	
	public function getPaginador(){
		return $this->paginador;
	}	

	private  function setPaginador($paginador){
		$this->paginador = $paginador;
	}

	public function getStatus(){
		return $this->status;
	}	

	private  function setStatus($status){
		$this->status = $status;
	}
	
	public function getMsj(){
		return $this->msj;
	}	

	private function setMsj($msj){
		$this->msj = $msj;
	}

	private function getSellerDao(){
		return $this->sellerDao;
	}
	
	public function crear(){
		$usuario_var = isset($_POST["mail"]) ? $_POST["mail"] : '';
		$pass = isset($_POST["pass"]) ? $_POST["pass"] : '';
		$pass2 = isset($_POST["cpass"]) ? $_POST["cpass"] : '';

		$usuario = new Usuario();
		$usuario->setUsuario($usuario_var);
		$usuario->setPass($pass);
		$usuario->setPass2($pass2);
		$usuario->setPerfil("seller");

		$seller = new Seller();
		$seller->setUsuario($usuario);
		$nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : '';
		$seller->setNombre(_trim($nombre));
		$apellido = isset($_POST["apellido"]) ? $_POST["apellido"] : '';
		$seller->setApellido(_trim($apellido));
		$email = isset($_POST["mail"]) ? $_POST["mail"] : '';
		$confirmarEmail  = isset($_POST["mail"]) ? $_POST["mail"] : '';
		$seller->setEmail(_trim($email));
		$ciudad = isset($_POST["ciudad"]) ? $_POST["ciudad"] : '';
		$seller->setCiudad(_trim($ciudad));
		$estado = isset($_POST["estado"]) ? $_POST["estado"] : '';
		$seller->setEstado(_trim($estado));
		$codigoPostal = isset($_POST["codigoPostal"]) ? $_POST["codigoPostal"] : '';
		$seller->setCodigoPostal(_trim($codigoPostal));
		$pais = isset($_POST["pais"]) ? $_POST["pais"] : '';
		$seller->setPais(_trim($pais));
		$telefono1Pais = isset($_POST["telefono1Pais"]) ? $_POST["telefono1Pais"] : '';
		$seller->setTelefono1Pais(_trim($telefono1Pais));
		$telefono1Ciudad = isset($_POST["telefono1Ciudad"]) ? $_POST["telefono1Ciudad"] : '';
		$seller->setTelefono1Ciudad(_trim($telefono1Ciudad));
		$telefono1 = isset($_POST["telefono1"]) ? $_POST["telefono1"] : '';
		$seller->setTelefono1(_trim($telefono1));
		$telefono1Tipo = isset($_POST["telefono1Tipo"]) ? $_POST["telefono1Tipo"] : '';
		$seller->setTelefono1Tipo(_trim($telefono1Tipo));
		$telefono2Pais = isset($_POST["telefono2Pais"]) ? $_POST["telefono2Pais"] : '';
		$seller->setTelefono2Pais(_trim($telefono2Pais));
		$telefono2Ciudad = isset($_POST["telefono2Ciudad"]) ? $_POST["telefono2Ciudad"] : '';
		$seller->setTelefono2Ciudad(_trim($telefono2Ciudad));
		$telefono2 = isset($_POST["telefono2"]) ? $_POST["telefono2"] : '';
		$seller->setTelefono2(_trim($telefono2));
		$telefono2Tipo = isset($_POST["telefono2Tipo"]) ? $_POST["telefono2Tipo"] : '';
		$seller->setTelefono2Tipo(_trim($telefono2Tipo));

		//validar
	
		

		$perfil = $GLOBALS['sesionG']['perfil'];
		if ($perfil == "anonymous"){
			#prograrmar captcha de google

			#include_once $_SERVER['DOCUMENT_ROOT'] . '/app/captcha/securimage.php';
			#$securimage = new Securimage();
			#if ($securimage->check($_REQUEST['captcha_code']) == false) {
			#	$this->setStatus("error");
			#	$this->setMsj(getMsjConf('captcha'));
			#	return $seller;
			#}
			#$read = isset($_POST["readterminos"]) ? $_POST["readterminos"] : '';
			#if ($read != "leido"){
			#	$this->setStatus("error");
			#	$this->setMsj(getMsjConf('108_2'));
			#	return $seller;
			#}
		}

		$this->validarNombre($seller->getNombre());
		if ($this->getStatus() != 'ok'){
			return $seller;
		}
		
		$this->validarApellido($seller->getApellido());
		if ($this->getStatus() != 'ok'){
			return $seller;
		}
		
		$this->validarEmail($seller->getEmail());
		if ($this->getStatus() != 'ok'){
			return $seller;
		}
		
		if ($seller->getEmail() != $confirmarEmail){
			$this->setStatus('error');
			$this->setMsj(getMsjConf('116'));
			return $seller;
		}

		if ($this->getSellerDao()->existeEmail($seller->getEmail())){
			$this->setStatus('error');
			$this->setMsj(getMsjConf('117'));
			return $seller;
		}

		if ($perfil != "anonymous"){
			$this->validarCiudad($seller->getCiudad());
			if ($this->getStatus() != 'ok'){
				return $seller;
			}

			$this->validarEstado($seller->getEstado());
			if ($this->getStatus() != 'ok'){
				return $seller;
			}
			$this->validarCodigoPostal($seller->getCodigoPostal());
			if ($this->getStatus() != 'ok'){
				return $seller;
			}
			$this->validarPais($seller->getPais());
			if ($this->getStatus() != 'ok'){
				return $seller;
			}
			
			$this->validarTelefono1($seller->getTelefono1Pais(),$seller->getTelefono1Ciudad(),$seller->getTelefono1(),$seller->getTelefono1Tipo());
			if ($this->getStatus() != 'ok'){
				return $seller;
			}
			
			$this->validarTelefono2($seller->getTelefono2Pais(),$seller->getTelefono2Ciudad(),$seller->getTelefono2(),$seller->getTelefono2Tipo());
			if ($this->getStatus() != 'ok'){
				return $seller;
			}
		}
		
		$usuarioManager = new UsuarioManagerImpl();
		$usuario = $usuarioManager->crear($usuario_var,$pass,$pass2,"seller");
		$seller->setUsuario($usuario);
		
		if ($usuarioManager->getStatus() == 'ok'){
			$this->getSellerDao()->alta($seller);
			if ($this->getSellerDao()->getStatus() == "error"){
				$this->setStatus("error");
				$this->setMsj($this->getSellerDao()->getMsj());
			}else{
				$seller->setId($this->getSellerDao()->getMsj());
				$this->setStatus("ok");
				include_once($GLOBALS['configuration']['path_app_admin_objects']."util/email.php");
				$objEmail = new Email();
				$objEmail->setEnviar(true);
				$objEmail->enviarEmailAltaUsuario('Se dio de alta',$seller->getEmail());
			}
		}else{
			$error = $usuarioManager->getMsj();
			$this->setStatus("error");
			$this->setMsj($error);
		}

		return $seller;
	}

	public function actualizar(){
		$perfil = $GLOBALS['sesionG']['perfil'];
		$usuario_var = isset($_POST["usuario"]) ? $_POST["usuario"] : '';
		$id = "";
		$idUsuario = "";
		if ($perfil == "picker"){
			$id        = $GLOBALS['sesionG']['id'];
			$idUsuario = $GLOBALS['sesionG']['idUsuario'];
		}else if ($perfil == "admin" || $perfil == "superadmin"){
			$id        = isset($_POST["id"]) ? $_POST["id"] : '';
			$idUsuario = isset($_POST["idUsuario"]) ? $_POST["idUsuario"] : '';
		}

		$usuario = new Usuario();
		$usuario->setUsuario($usuario_var);
		$usuario->setId($idUsuario);

		$seller = new Seller();
		$seller->setUsuario($usuario);
		$seller->setIdUsuario($idUsuario);
		$seller->setId($id);
		$nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : '';
		$seller->setNombre(_trim($nombre));
		$apellido = isset($_POST["apellido"]) ? $_POST["apellido"] : '';
		$seller->setApellido(_trim($apellido));
		$email = isset($_POST["email"]) ? $_POST["email"] : '';
		$confirmarEmail  = isset($_POST["confirmaremail"]) ? $_POST["confirmaremail"] : '';
		$seller->setEmail(_trim($email));
		$ciudad = isset($_POST["ciudad"]) ? $_POST["ciudad"] : '';
		$seller->setCiudad(_trim($ciudad));
		$estado = isset($_POST["estado"]) ? $_POST["estado"] : '';
		$seller->setEstado(_trim($estado));
		$codigoPostal = isset($_POST["codigoPostal"]) ? $_POST["codigoPostal"] : '';
		$seller->setCodigoPostal(_trim($codigoPostal));
		$pais = isset($_POST["pais"]) ? $_POST["pais"] : '';
		$seller->setPais(_trim($pais));
		$telefono1Pais = isset($_POST["telefono1Pais"]) ? $_POST["telefono1Pais"] : '';
		$seller->setTelefono1Pais(_trim($telefono1Pais));
		$telefono1Ciudad = isset($_POST["telefono1Ciudad"]) ? $_POST["telefono1Ciudad"] : '';
		$seller->setTelefono1Ciudad(_trim($telefono1Ciudad));
		$telefono1 = isset($_POST["telefono1"]) ? $_POST["telefono1"] : '';
		$seller->setTelefono1(_trim($telefono1));
		$telefono1Tipo = isset($_POST["telefono1Tipo"]) ? $_POST["telefono1Tipo"] : '';
		$seller->setTelefono1Tipo(_trim($telefono1Tipo));
		$telefono2Pais = isset($_POST["telefono2Pais"]) ? $_POST["telefono2Pais"] : '';
		$seller->setTelefono2Pais(_trim($telefono2Pais));
		$telefono2Ciudad = isset($_POST["telefono2Ciudad"]) ? $_POST["telefono2Ciudad"] : '';
		$seller->setTelefono2Ciudad(_trim($telefono2Ciudad));
		$telefono2 = isset($_POST["telefono2"]) ? $_POST["telefono2"] : '';
		$seller->setTelefono2(_trim($telefono2));
		$telefono2Tipo = isset($_POST["telefono2Tipo"]) ? $_POST["telefono2Tipo"] : '';
		$seller->setTelefono2Tipo(_trim($telefono2Tipo));

		//validar
		$this->validarId($seller->getId());
		if ($this->getStatus() != 'ok'){
			return $seller;
		}
		$this->validarNombre($seller->getNombre());
		if ($this->getStatus() != 'ok'){
			return $seller;
		}
		
		$this->validarApellido($seller->getApellido());
		if ($this->getStatus() != 'ok'){
			return $seller;
		}
		
		$this->validarEmail($seller->getEmail());
		if ($this->getStatus() != 'ok'){
			return $seller;
		}

		/*
		if ($seller->getEmail() != $confirmarEmail){
			$this->setStatus('error');
			$this->setMsj(getMsjConf('116'));
			return $seller;
		}
		*/

		if ($this->getSellerDao()->existeEmailMod($seller->getEmail(),$seller->getId())){
			$this->setStatus('error');
			$this->setMsj(getMsjConf('117'));
			return $seller;
		}

		$this->validarCiudad($seller->getCiudad());
		if ($this->getStatus() != 'ok'){
			return $seller;
		}

		$this->validarEstado($seller->getEstado());
		if ($this->getStatus() != 'ok'){
			return $seller;
		}
		
		$this->validarCodigoPostal($seller->getCodigoPostal());
		if ($this->getStatus() != 'ok'){
			return $seller;
		}

		$this->validarPais($seller->getPais());
		if ($this->getStatus() != 'ok'){
			return $seller;
		}
		
		$this->validarTelefono1($seller->getTelefono1Pais(),$seller->getTelefono1Ciudad(),$seller->getTelefono1(),$seller->getTelefono1Tipo());
		if ($this->getStatus() != 'ok'){
			return $seller;
		}
		
		$this->validarTelefono2($seller->getTelefono2Pais(),$seller->getTelefono2Ciudad(),$seller->getTelefono2(),$seller->getTelefono2Tipo());
		if ($this->getStatus() != 'ok'){
			return $seller;
		}

		if ($perfil != "picker" && $perfil != "admin" && $perfil != "superadmin"){
			$this->setStatus("error");
			$this->setMsj(getMsjConf('120')); 
			return $seller;
		}

		$usuarioManager = new UsuarioManagerImpl();
		$usuario = $usuarioManager->actualizar($seller->getUsuario()->getId(),$seller->getUsuario()->getUsuario());
		$seller->setUsuario($usuario);

		if ($usuarioManager->getStatus() != "ok"){
			$this->setStatus("error");
			$this->setMsj($usuarioManager->getMsj());
		}else{
			$this->getSellerDao()->editar($seller);	
			if ($this->getSellerDao()->getStatus() != "ok"){
				$this->setStatus("error");
				$this->setMsj($this->getSellerDao()->getMsj());
			}else{
				$this->setStatus("ok");
				$this->setMsj(""); 
			}
		}
		return $seller;
	}

	public function eliminar(){
		$perfil    = $GLOBALS['sesionG']['perfil'];
		$idUsuario = "";
		$id = "";

		if ($perfil == "seller"){
			$idUsuario = $GLOBALS['sesionG']['idUsuario'];
			$id        = $GLOBALS['sesionG']['id'];
		}else{
			$perfil     = isset($_POST["perfil"]) ? $_POST["perfil"] : '';
			$idUsuario  = isset($_POST["idUsuario"]) ? $_POST["idUsuario"] : '';
			$id         = isset($_POST["id"]) ? $_POST["id"] : '';
		}
		
		$usuarioManager = new UsuarioManagerImpl();
		$usuarioManager->eliminar($idUsuario);
		
		if ($usuarioManager->getStatus() != "ok"){
			$this->setStatus("error");
			$this->setMsj($usuarioManager->getMsj());
		}else{
			$this->getSellerDao()->eliminar($id,$idUsuario);	
			if ($this->getSellerDao()->getStatus() != "ok"){
				$this->setStatus("error");
				$this->setMsj($this->getSellerDao()->getMsj());
			}else{
				$this->setStatus("ok");
				$this->setMsj("");
			}
		}
	}

	public function get(){
		$idUsuario  = isset($_GET["idUsuario"]) ? $_GET["idUsuario"] : '';
		$usuarioManager = new UsuarioManagerImpl();
		$usuario = $usuarioManager->get($idUsuario);

		$id  = isset($_GET["id"]) ? $_GET["id"] : '';
		$seller = new Seller();
		$seller->setId($id);

		if ($usuarioManager->getStatus() != 'ok'){
			$seller->setUsuario($usuario);
			return $seller;
		}else{
			$this->validarId($seller->getId());
			if ($this->getStatus() != 'ok'){
				$seller->setUsuario($usuario);
				return $seller;
			}else{
				$seller =  $this->getSellerDao()->get($seller->getId(),$usuario->getId());
				$seller->setUsuario($usuario);
				return $seller;
			}
		}
	}
	
	public function getByIdSesion(){
		$idUsuario = $GLOBALS['sesionG']['idUsuario'];
		$usuarioManager = new UsuarioManagerImpl();
		$usuario = $usuarioManager->get($idUsuario);
		
		$id  = $GLOBALS['sesionG']['id'];
		$seller = new Seller();
		$seller->setId($id);
		
		if ($usuarioManager->getStatus() != 'ok'){
			$seller->setUsuario($usuario);
			return $seller;
		}else{
			$this->validarId($seller->getId());
			if ($this->getStatus() != 'ok'){
				$seller->setUsuario($usuario);
				return $seller;
			}else{
				$seller =  $this->getSellerDao()->get($seller->getId(),$usuario->getId());
				$seller->setUsuario($usuario);
				return $seller;
			}
		}
	}
	
	
	public function getByIdUsuario($idUsuario){
		$this->validarId($idUsuario);
		if ($this->getStatus() != 'ok'){
			$seller = new Seller();
			$usuario = new Usuario();
			$seller->setUsuario($usuario);
			return $seller;
		}else{
			$seller =  $this->getSellerDao()->getByIdUsuario($idUsuario);
			return $seller;
		}
	}
	
	public function listar(){
		$list = $this->getSellerDao()->getList();

		$filas = "";	
		foreach ($list as $clave=>$seller){
			$contenido = new Template("cliente_listado_filas");
			$contenido->asigna_variables(array(
				"path"      => $GLOBALS['configuration']['path_html'],
				"id"       => $seller->getId(),
				"idUsuario"   => $seller->getIdUsuario(),
				"nombre"   => $seller->getNombre(),
				"apellido" => $seller->getApellido(),
				"telefono" => $seller->getTelefono1Pais()."&nbsp;".$seller->getTelefono1Ciudad()."&nbsp;".$seller->getTelefono1()."&nbsp;".$seller->getTelefono1Tipo()
			));
			$contenidoString = $contenido->muestra();
			$filas .= $contenidoString;
		}
		$this->setPaginador("");
		return $filas;
	}
	
	public function getOptionTipoTel($cod){
		$cod = (isset($cod)) ? $cod : ''; 
		$option = '';
		foreach ($GLOBALS['codTipoTel'] as $c=>$v){
			if ($cod == $c){
				$option .= "<option value=\"".$c."\" selected=\"selected\">".$v."</option>";
			}else{
				$option .= "<option value=\"".$c."\">".$v."</option>";
			}	
		}
		return $option;
	}
	
	public function getOptionPais($cod){
		$cod = isset($cod) ? $cod : ''; 
		$option = "<option value=\"\"></option>";
		foreach ($GLOBALS['codPais'] as $c=>$v){
			if ($cod == $c){
				$option .= "<option value=\"".$c."\" selected=\"selected\">".$v."</option>";
			}else{
				$option .= "<option value=\"".$c."\">".$v."</option>";
			}	
		}
		return $option;
	}
	
	private function validarId ($param){
		$this->setStatus("error");
		$this->setMsj("");
		$validSql = validSqlInjection($param);
		if ($validSql != ''){
			$this->setMsj(getMsjConf('122')." $validSql.");
		}else{
			$patron = '/^[1-9][0-9]*$/';
			if (preg_match($patron, $param)){
				$this->setStatus("ok");
			}else{
				$this->setMsj(getMsjConf('123'));
			}
		}
	}
	
	private function validarNombre ($param){
		$this->setStatus("error");
		$this->setMsj("");
		$validSql = validSqlInjection($param);
		if ($validSql != ''){
			$this->setMsj(getMsjConf('124')." $validSql.");
		}else{
			$param = _trim($param);
			if (strlen($param)<1){
				$this->setMsj(getMsjConf('108_1'));
			}else if(strlen($param)>30){
				$this->setMsj(getMsjConf('125'));
			}else{
				$patron = '/^([a-z ]+)$/i';
				if (preg_match($patron, $param)){
					$this->setStatus("ok");
				}else{
					$this->setMsj(getMsjConf('126'));
				}
			}
		}
	}
	
	private function validarApellido ($param){
		$this->setStatus("error");
		$this->setMsj("");
		$validSql = validSqlInjection($param);
		if ($validSql != ''){
			$this->setMsj(getMsjConf('127')." $validSql.");
		}else{
			$param = _trim($param);
			if (strlen($param)<1){
				$this->setMsj(getMsjConf('128'));
			}else if(strlen($param)>30){
				$this->setMsj(getMsjConf('129'));
			}else{
				$patron = '/^([a-z ]+)$/i';
				if (preg_match($patron, $param)){
					$this->setStatus("ok");
				}else{
					$this->setMsj(getMsjConf('130'));
				}
			}
		}
	}
	
	private function validarEmail ($param){
		$this->setStatus("error");
		$this->setMsj("");
		$validSql = validSqlInjection($param);
		if ($validSql != ''){
			$this->setMsj(getMsjConf('131')." $validSql.");
		}else{
			$param = _trim($param);
			if (strlen($param)<6){
				$this->setMsj(getMsjConf('132'));
			}else if(strlen($param)>64){
				$this->setMsj(getMsjConf('133'));
			}else{
				$patron = '/^[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/';
				if (preg_match($patron, $param)){
					$this->setStatus("ok");
				}else{
					$this->setMsj(getMsjConf('134'));
				}
			}
		}
	}
	
	private function validarCiudad ($param){
		$this->setStatus("error");
		$this->setMsj("");
		$validSql = validSqlInjection($param);
		if ($validSql != ''){
			$this->setMsj(getMsjConf('135')." $validSql.");
		}else{
			$param = _trim($param);
			if (strlen($param)<3){
				$this->setMsj(getMsjConf('136'));
			}else if(strlen($param)>30){
				$this->setMsj(getMsjConf('137'));
			}else{
				$patron = '/^(.+)$/i';
				if (preg_match($patron, $param)){
					$this->setStatus("ok");
				}else{
					$this->setMsj(getMsjConf('138'));
				}
			}
		}
	}

	private function validarEstado ($param){
		$this->setStatus("error");
		$this->setMsj("");
		$validSql = validSqlInjection($param);
		if ($validSql != ''){
			$this->setMsj(getMsjConf('139')." $validSql.");
		}else{
			$param = _trim($param);
			if (strlen($param)<1){
				$this->setMsj(getMsjConf('140'));
			}else if(strlen($param)>30){
				$this->setMsj(getMsjConf('141'));
			}else{
				$patron = '/^(.+)$/i';
				if (preg_match($patron, $param)){
					$this->setStatus("ok");
				}else{
					$this->setMsj(getMsjConf('142'));
				}
			}
		}
	}
	
	private function validarCodigoPostal ($param){
		$this->setStatus("error");
		$this->setMsj("");
		if (_trim($param) != ''){
			$validSql = validSqlInjection($param);
			if ($validSql != ''){
				$this->setMsj(getMsjConf('143')." $validSql.");
			}else{
				$param = _trim($param);
				if(strlen($param)>12){
					$this->setMsj(getMsjConf('144'));
				}else{
					$patron = '/^([0-9]+)$/i';
					if (preg_match($patron, $param)){
						$this->setStatus("ok");
					}else{
						$this->setMsj(getMsjConf('145'));
					}
				}
			}
		}else{
			$this->setStatus("ok");
		}
	}
	
	private function validarPais ($param){
		$this->setStatus("error");
		$this->setMsj("");
		$validSql = validSqlInjection($param);
		if ($validSql != ''){
			$this->setMsj(getMsjConf('146')." $validSql.");
		}else{
			$patron = '/^.+$/i';
			if (preg_match($patron, $param)){
				if(isset($GLOBALS['codPais'][$param])){
					if ($param == 'USA'){
						$this->setStatus("ok");
					}else{
						$this->setMsj(getMsjConf('147'));
					}
				}else{
					$this->setMsj(getMsjConf('148'));
				}
			}else{
				$this->setMsj(getMsjConf('149'));
			}
		}
	}
	
	private function validarTelefono1 ($paramPais,$paramCiudad,$paramTel,$paramTipo){
		$this->setStatus("error");
		$this->setMsj("");
		if (_trim($paramPais) != '' || _trim($paramCiudad) != '' || _trim($paramTel) != '' || _trim($paramTipo) != ''){
			$validSql = validSqlInjection($paramPais);
			if ($validSql != ''){
				$this->setMsj(getMsjConf('150')." $validSql.");
			}else{
				$validSql = validSqlInjection($paramCiudad);
				if ($validSql != ''){
					$this->setMsj(getMsjConf('151')." $validSql.");
				}else{
					$validSql = validSqlInjection($paramTel);
					if ($validSql != ''){
						$this->setMsj(getMsjConf('152')." $validSql.");
					}else{
						$validSql = validSqlInjection($paramTipo);
						if ($validSql != ''){
							$this->setMsj(getMsjConf('153')." $validSql.");
						}else{
							//valido telefono1 pa&iacute;s
							$patron = '/^[0-9]{1,5}$/i';
							if (preg_match($patron, $paramPais)){
								//valido telefono1 ciudad 
								$patron = '/^[0-9]{2,5}$/i';
								if (preg_match($patron, $paramCiudad)){
									//valido telefono1
									$patron = '/^[0-9]{4,10}$/i';
									if (preg_match($patron, $paramTel)){
										//valido telefono1 tipo
										$patron = '/^.+$/i';
										if (preg_match($patron, $paramTipo)){
											if(isset($GLOBALS['codTipoTel'][$paramTipo])){
												$this->setStatus("ok");
											}else{
												$this->setMsj(getMsjConf('154'));
											}
										}else{
											$this->setMsj(getMsjConf('154'));
										}
									}else{
										$this->setMsj(getMsjConf('156'));
									}
								}else{
									$this->setMsj(getMsjConf('157'));
								}
							}else{
								$this->setMsj(getMsjConf('158'));
							}
						}
					}
				}
			}
		}else{
			$this->setStatus("ok");
		}
	}
	
	private function validarTelefono2 ($paramPais,$paramCiudad,$paramTel,$paramTipo){
		$this->setStatus("error");
		$this->setMsj("");
		if (_trim($paramPais) != '' || _trim($paramCiudad) != '' || _trim($paramTel) != '' || _trim($paramTipo) != ''){
			$validSql = validSqlInjection($paramPais);
			if ($validSql != ''){
				$this->setMsj(getMsjConf('159')." $validSql.");
			}else{
				$validSql = validSqlInjection($paramCiudad);
				if ($validSql != ''){
					$this->setMsj(getMsjConf('160')." $validSql.");
				}else{
					$validSql = validSqlInjection($paramTel);
					if ($validSql != ''){
						$this->setMsj(getMsjConf('161')." $validSql.");
					}else{
						$validSql = validSqlInjection($paramTipo);
						if ($validSql != ''){
							$this->setMsj(getMsjConf('162')." $validSql.");
						}else{
							//valido telefono2 pa&iacute;s
							$patron = '/^[0-9]{1,5}$/i';
							if (preg_match($patron, $paramPais)){
								//valido telefono2 ciudad 
								$patron = '/^[0-9]{2,5}$/i';
								if (preg_match($patron, $paramCiudad)){
									//valido telefono2
									$patron = '/^[0-9]{4,10}$/i';
									if (preg_match($patron, $paramTel)){
										//valido telefono2 tipo
										$patron = '/^.+$/i';
										if (preg_match($patron, $paramTipo)){
											if(isset($GLOBALS['codTipoTel'][$paramTipo])){
												$this->setStatus("ok");
											}else{
												$this->setMsj(getMsjConf('163'));
											}
										}else{
											$this->setMsj(getMsjConf('163'));
										}
									}else{
										$this->setMsj(getMsjConf('165'));
									}
								}else{
									$this->setMsj(getMsjConf('166'));
								}
							}else{
								$this->setMsj(getMsjConf('167'));
							}
						}
					}
				}
			}
		}else{
			$this->setStatus("ok");
		}
	}
	
	public function existeSeller ($param){
		$this->validarId($param);
		if ($this->getStatus() == 'ok'){
			if ($this->getSellerDao()->existeSeller($param)){
				return true;
			}else{
				if ($this->getSellerDao()->getStatus() == "error"){
					$this->setStatus("error");
					$this->setMsj($this->getSellerDao()->getMsj());
				}else{
					$this->setStatus("ok");
					$this->setMsj("");
				}
				return false;
			}	
		}else{
			$this->setStatus("ok");
			$this->setMsj("");
			return false;
		}
	}
	
}
?>

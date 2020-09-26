<?php
include_once($GLOBALS['configuration']['path_app_admin_objects']."cliente/cliente.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."cliente/clienteManager.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."cliente/clienteDaoImpl.php");

class ClienteManagerImpl implements  ClienteManager{
	private $clienteDao;
	private $status    = "";
	private $msj       = "";
	private $paginador = "";

   public function __construct(){
		$this->clienteDao = new ClienteDaoImpl();
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

	private function getClienteDao(){
		return $this->clienteDao;
	}
	
	public function crear(){
		$usuario_var = isset($_POST["mail"]) ? $_POST["mail"] : '';
		$pass = isset($_POST["pass"]) ? $_POST["pass"] : '';
		$pass2 = isset($_POST["cpass"]) ? $_POST["cpass"] : '';

		$usuario = new Usuario();
		$usuario->setUsuario($usuario_var);
		$usuario->setPass($pass);
		$usuario->setPass2($pass2);
		$usuario->setPerfil("picker");

		$cliente = new Cliente();
		$cliente->setUsuario($usuario);
		$nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : '';
		$cliente->setNombre(_trim($nombre));
		$apellido = isset($_POST["apellido"]) ? $_POST["apellido"] : '';
		$cliente->setApellido(_trim($apellido));
		$email = isset($_POST["mail"]) ? $_POST["mail"] : '';
		$confirmarEmail  = isset($_POST["mail"]) ? $_POST["mail"] : '';
		$cliente->setEmail(_trim($email));
		$ciudad = isset($_POST["ciudad"]) ? $_POST["ciudad"] : '';
		$cliente->setCiudad(_trim($ciudad));
		$estado = isset($_POST["estado"]) ? $_POST["estado"] : '';
		$cliente->setEstado(_trim($estado));
		$codigoPostal = isset($_POST["codigoPostal"]) ? $_POST["codigoPostal"] : '';
		$cliente->setCodigoPostal(_trim($codigoPostal));
		$pais = isset($_POST["pais"]) ? $_POST["pais"] : '';
		$cliente->setPais(_trim($pais));
		$telefono1Pais = isset($_POST["telefono1Pais"]) ? $_POST["telefono1Pais"] : '';
		$cliente->setTelefono1Pais(_trim($telefono1Pais));
		$telefono1Ciudad = isset($_POST["telefono1Ciudad"]) ? $_POST["telefono1Ciudad"] : '';
		$cliente->setTelefono1Ciudad(_trim($telefono1Ciudad));
		$telefono1 = isset($_POST["telefono1"]) ? $_POST["telefono1"] : '';
		$cliente->setTelefono1(_trim($telefono1));
		$telefono1Tipo = isset($_POST["telefono1Tipo"]) ? $_POST["telefono1Tipo"] : '';
		$cliente->setTelefono1Tipo(_trim($telefono1Tipo));
		$telefono2Pais = isset($_POST["telefono2Pais"]) ? $_POST["telefono2Pais"] : '';
		$cliente->setTelefono2Pais(_trim($telefono2Pais));
		$telefono2Ciudad = isset($_POST["telefono2Ciudad"]) ? $_POST["telefono2Ciudad"] : '';
		$cliente->setTelefono2Ciudad(_trim($telefono2Ciudad));
		$telefono2 = isset($_POST["telefono2"]) ? $_POST["telefono2"] : '';
		$cliente->setTelefono2(_trim($telefono2));
		$telefono2Tipo = isset($_POST["telefono2Tipo"]) ? $_POST["telefono2Tipo"] : '';
		$cliente->setTelefono2Tipo(_trim($telefono2Tipo));

		//validar
	
		

		$perfil = $GLOBALS['sesionG']['perfil'];
		if ($perfil == "anonymous"){
			#prograrmar captcha de google

			#include_once $_SERVER['DOCUMENT_ROOT'] . '/app/captcha/securimage.php';
			#$securimage = new Securimage();
			#if ($securimage->check($_REQUEST['captcha_code']) == false) {
			#	$this->setStatus("error");
			#	$this->setMsj(getMsjConf('captcha'));
			#	return $cliente;
			#}
			#$read = isset($_POST["readterminos"]) ? $_POST["readterminos"] : '';
			#if ($read != "leido"){
			#	$this->setStatus("error");
			#	$this->setMsj(getMsjConf('108_2'));
			#	return $cliente;
			#}
		}

		$this->validarNombre($cliente->getNombre());
		if ($this->getStatus() != 'ok'){
			return $cliente;
		}
		
		$this->validarApellido($cliente->getApellido());
		if ($this->getStatus() != 'ok'){
			return $cliente;
		}
		
		$this->validarEmail($cliente->getEmail());
		if ($this->getStatus() != 'ok'){
			return $cliente;
		}
		
		if ($cliente->getEmail() != $confirmarEmail){
			$this->setStatus('error');
			$this->setMsj(getMsjConf('116'));
			return $cliente;
		}

		if ($this->getClienteDao()->existeEmail($cliente->getEmail())){
			$this->setStatus('error');
			$this->setMsj(getMsjConf('117'));
			return $cliente;
		}

		if ($perfil != "anonymous"){
			$this->validarCiudad($cliente->getCiudad());
			if ($this->getStatus() != 'ok'){
				return $cliente;
			}

			$this->validarEstado($cliente->getEstado());
			if ($this->getStatus() != 'ok'){
				return $cliente;
			}
			$this->validarCodigoPostal($cliente->getCodigoPostal());
			if ($this->getStatus() != 'ok'){
				return $cliente;
			}
			$this->validarPais($cliente->getPais());
			if ($this->getStatus() != 'ok'){
				return $cliente;
			}
			
			$this->validarTelefono1($cliente->getTelefono1Pais(),$cliente->getTelefono1Ciudad(),$cliente->getTelefono1(),$cliente->getTelefono1Tipo());
			if ($this->getStatus() != 'ok'){
				return $cliente;
			}
			
			$this->validarTelefono2($cliente->getTelefono2Pais(),$cliente->getTelefono2Ciudad(),$cliente->getTelefono2(),$cliente->getTelefono2Tipo());
			if ($this->getStatus() != 'ok'){
				return $cliente;
			}
		}
		
		$usuarioManager = new UsuarioManagerImpl();
		$usuario = $usuarioManager->crear($usuario_var,$pass,$pass2,"picker");
		$cliente->setUsuario($usuario);
		
		if ($usuarioManager->getStatus() == 'ok'){
			$this->getClienteDao()->alta($cliente);
			if ($this->getClienteDao()->getStatus() == "error"){
				$this->setStatus("error");
				$this->setMsj($this->getClienteDao()->getMsj());
			}else{
				$cliente->setId($this->getClienteDao()->getMsj());
				$this->setStatus("ok");
				include_once($GLOBALS['configuration']['path_app_admin_objects']."util/email.php");
				$objEmail = new Email();
				$objEmail->setEnviar(true);
				$objEmail->enviarEmailAltaUsuario('Se dio de alta',$cliente->getEmail());
			}
		}else{
			$error = $usuarioManager->getMsj();
			$this->setStatus("error");
			$this->setMsj($error);
		}

		return $cliente;
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

		$cliente = new Cliente();
		$cliente->setUsuario($usuario);
		$cliente->setIdUsuario($idUsuario);
		$cliente->setId($id);
		$nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : '';
		$cliente->setNombre(_trim($nombre));
		$apellido = isset($_POST["apellido"]) ? $_POST["apellido"] : '';
		$cliente->setApellido(_trim($apellido));
		$email = isset($_POST["email"]) ? $_POST["email"] : '';
		$confirmarEmail  = isset($_POST["confirmaremail"]) ? $_POST["confirmaremail"] : '';
		$cliente->setEmail(_trim($email));
		$ciudad = isset($_POST["ciudad"]) ? $_POST["ciudad"] : '';
		$cliente->setCiudad(_trim($ciudad));
		$estado = isset($_POST["estado"]) ? $_POST["estado"] : '';
		$cliente->setEstado(_trim($estado));
		$codigoPostal = isset($_POST["codigoPostal"]) ? $_POST["codigoPostal"] : '';
		$cliente->setCodigoPostal(_trim($codigoPostal));
		$pais = isset($_POST["pais"]) ? $_POST["pais"] : '';
		$cliente->setPais(_trim($pais));
		$telefono1Pais = isset($_POST["telefono1Pais"]) ? $_POST["telefono1Pais"] : '';
		$cliente->setTelefono1Pais(_trim($telefono1Pais));
		$telefono1Ciudad = isset($_POST["telefono1Ciudad"]) ? $_POST["telefono1Ciudad"] : '';
		$cliente->setTelefono1Ciudad(_trim($telefono1Ciudad));
		$telefono1 = isset($_POST["telefono1"]) ? $_POST["telefono1"] : '';
		$cliente->setTelefono1(_trim($telefono1));
		$telefono1Tipo = isset($_POST["telefono1Tipo"]) ? $_POST["telefono1Tipo"] : '';
		$cliente->setTelefono1Tipo(_trim($telefono1Tipo));
		$telefono2Pais = isset($_POST["telefono2Pais"]) ? $_POST["telefono2Pais"] : '';
		$cliente->setTelefono2Pais(_trim($telefono2Pais));
		$telefono2Ciudad = isset($_POST["telefono2Ciudad"]) ? $_POST["telefono2Ciudad"] : '';
		$cliente->setTelefono2Ciudad(_trim($telefono2Ciudad));
		$telefono2 = isset($_POST["telefono2"]) ? $_POST["telefono2"] : '';
		$cliente->setTelefono2(_trim($telefono2));
		$telefono2Tipo = isset($_POST["telefono2Tipo"]) ? $_POST["telefono2Tipo"] : '';
		$cliente->setTelefono2Tipo(_trim($telefono2Tipo));

		//validar
		$this->validarId($cliente->getId());
		if ($this->getStatus() != 'ok'){
			return $cliente;
		}
		$this->validarNombre($cliente->getNombre());
		if ($this->getStatus() != 'ok'){
			return $cliente;
		}
		
		$this->validarApellido($cliente->getApellido());
		if ($this->getStatus() != 'ok'){
			return $cliente;
		}
		
		$this->validarEmail($cliente->getEmail());
		if ($this->getStatus() != 'ok'){
			return $cliente;
		}

		/*
		if ($cliente->getEmail() != $confirmarEmail){
			$this->setStatus('error');
			$this->setMsj(getMsjConf('116'));
			return $cliente;
		}
		*/

		if ($this->getClienteDao()->existeEmailMod($cliente->getEmail(),$cliente->getId())){
			$this->setStatus('error');
			$this->setMsj(getMsjConf('117'));
			return $cliente;
		}

		$this->validarCiudad($cliente->getCiudad());
		if ($this->getStatus() != 'ok'){
			return $cliente;
		}

		$this->validarEstado($cliente->getEstado());
		if ($this->getStatus() != 'ok'){
			return $cliente;
		}
		
		$this->validarCodigoPostal($cliente->getCodigoPostal());
		if ($this->getStatus() != 'ok'){
			return $cliente;
		}

		$this->validarPais($cliente->getPais());
		if ($this->getStatus() != 'ok'){
			return $cliente;
		}
		
		$this->validarTelefono1($cliente->getTelefono1Pais(),$cliente->getTelefono1Ciudad(),$cliente->getTelefono1(),$cliente->getTelefono1Tipo());
		if ($this->getStatus() != 'ok'){
			return $cliente;
		}
		
		$this->validarTelefono2($cliente->getTelefono2Pais(),$cliente->getTelefono2Ciudad(),$cliente->getTelefono2(),$cliente->getTelefono2Tipo());
		if ($this->getStatus() != 'ok'){
			return $cliente;
		}

		if ($perfil != "picker" && $perfil != "admin" && $perfil != "superadmin"){
			$this->setStatus("error");
			$this->setMsj(getMsjConf('120')); 
			return $cliente;
		}

		$usuarioManager = new UsuarioManagerImpl();
		$usuario = $usuarioManager->actualizar($cliente->getUsuario()->getId(),$cliente->getUsuario()->getUsuario());
		$cliente->setUsuario($usuario);

		if ($usuarioManager->getStatus() != "ok"){
			$this->setStatus("error");
			$this->setMsj($usuarioManager->getMsj());
		}else{
			$this->getClienteDao()->editar($cliente);	
			if ($this->getClienteDao()->getStatus() != "ok"){
				$this->setStatus("error");
				$this->setMsj($this->getClienteDao()->getMsj());
			}else{
				$this->setStatus("ok");
				$this->setMsj(""); 
			}
		}
		return $cliente;
	}

	public function eliminar(){
		$perfil    = $GLOBALS['sesionG']['perfil'];
		$idUsuario = "";
		$id = "";

		if ($perfil == "picker"){
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
			$this->getClienteDao()->eliminar($id,$idUsuario);	
			if ($this->getClienteDao()->getStatus() != "ok"){
				$this->setStatus("error");
				$this->setMsj($this->getClienteDao()->getMsj());
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
		$cliente = new Cliente();
		$cliente->setId($id);

		if ($usuarioManager->getStatus() != 'ok'){
			$cliente->setUsuario($usuario);
			return $cliente;
		}else{
			$this->validarId($cliente->getId());
			if ($this->getStatus() != 'ok'){
				$cliente->setUsuario($usuario);
				return $cliente;
			}else{
				$cliente =  $this->getClienteDao()->get($cliente->getId(),$usuario->getId());
				$cliente->setUsuario($usuario);
				return $cliente;
			}
		}
	}
	
	public function getByIdSesion(){
		$idUsuario = $GLOBALS['sesionG']['idUsuario'];
		$usuarioManager = new UsuarioManagerImpl();
		$usuario = $usuarioManager->get($idUsuario);
		
		$id  = $GLOBALS['sesionG']['id'];
		$cliente = new Cliente();
		$cliente->setId($id);
		
		if ($usuarioManager->getStatus() != 'ok'){
			$cliente->setUsuario($usuario);
			return $cliente;
		}else{
			$this->validarId($cliente->getId());
			if ($this->getStatus() != 'ok'){
				$cliente->setUsuario($usuario);
				return $cliente;
			}else{
				$cliente =  $this->getClienteDao()->get($cliente->getId(),$usuario->getId());
				$cliente->setUsuario($usuario);
				return $cliente;
			}
		}
	}
	
	
	public function getByIdUsuario($idUsuario){
		$this->validarId($idUsuario);
		if ($this->getStatus() != 'ok'){
			$cliente = new Cliente();
			$usuario = new Usuario();
			$cliente->setUsuario($usuario);
			return $cliente;
		}else{
			$cliente =  $this->getClienteDao()->getByIdUsuario($idUsuario);
			return $cliente;
		}
	}
	
	public function listar(){
		$list = $this->getClienteDao()->getList();

		$filas = "";	
		foreach ($list as $clave=>$cliente){
			$contenido = new Template("cliente_listado_filas");
			$contenido->asigna_variables(array(
				"path"      => $GLOBALS['configuration']['path_html'],
				"id"       => $cliente->getId(),
				"idUsuario"   => $cliente->getIdUsuario(),
				"nombre"   => $cliente->getNombre(),
				"apellido" => $cliente->getApellido(),
				"telefono" => $cliente->getTelefono1Pais()."&nbsp;".$cliente->getTelefono1Ciudad()."&nbsp;".$cliente->getTelefono1()."&nbsp;".$cliente->getTelefono1Tipo()
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
	
	public function existeCliente ($param){
		$this->validarId($param);
		if ($this->getStatus() == 'ok'){
			if ($this->getClienteDao()->existeCliente($param)){
				return true;
			}else{
				if ($this->getClienteDao()->getStatus() == "error"){
					$this->setStatus("error");
					$this->setMsj($this->getClienteDao()->getMsj());
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

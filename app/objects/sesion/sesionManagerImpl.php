<?php
include_once($GLOBALS['configuration']['path_app_admin_objects']."sesion/sesion.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."sesion/sesionManager.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."sesion/sesionDaoImpl.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."usuario/usuarioManagerImpl.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."cliente/clienteManagerImpl.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."seller/sellerManagerImpl.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."seller/sellerDaoImpl.php");

class  SesionManagerImpl implements  SesionManager{
	private $sesionDao;
	private $status   = "";
	private $msj      = "";

   public function __construct(){
		session_start ();

		
		$GLOBALS['sesionG']['language'] = (isset($_COOKIE['language'])) ? $_COOKIE['language'] : "";
		if ($GLOBALS['sesionG']['language'] != 'esp' && $GLOBALS['sesionG']['language'] != 'eng'){
			$GLOBALS['sesionG']['language'] = $GLOBALS['configuration']['languageDefault'];
		}
		$sessionCookieExpireTime = time() + 3600*24*365;
		setcookie("language", $GLOBALS['sesionG']['language'],$sessionCookieExpireTime,"/");
		$this->sesionDao = new SesionDaoImpl();
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

	private function getSesionDao(){
		return $this->sesionDao;
	}

	public function crear($usuario,$pass){
		$usuarioManager = new UsuarioManagerImpl();	
		$objUsuario = $usuarioManager->getByUsr();
		$idUsuario = $objUsuario->getId();
		$perfil    = $objUsuario->getPerfil();
		$password  = $objUsuario->getPass();
		$usr       = $objUsuario->getUsuario(); 
		$identify  =  md5($GLOBALS['configuration']['clave_identify'].$idUsuario.$perfil);

		$pass = md5($GLOBALS['configuration']['clave_pass'].$pass.$perfil);

		if ($password==$pass){
			$sesion = new Sesion();
			$sesion->setIdUsuario($idUsuario);
			$sesion->setUsuario($usr);
			$sesion->setPerfil($perfil);
			$sesion->setIdentify($identify);

			if ($perfil == "picker"){
				$obj = new ClienteManagerImpl();
				$sesion->setObj($obj->getByIdUsuario($idUsuario));
			}else if ($perfil == "seller"){
				$obj = new SellerManagerImpl();
				$sesion->setObj($obj->getByIdUsuario($idUsuario));
			}else if ($perfil != "admin" && $perfil != "superadmin" && $perfil != "editor"){
				echo "Error grave. Perfil desconocido.";
				Database::Connect()->close();
				exit;
			}
			$this->getSesionDao()->alta($sesion);
			$this->setStatus("ok");
			$this->setMsj("");
		}else{
			$this->setStatus("error");
			$this->setMsj(getMsjConf('293'));
		}
	}

	public function validar ($array){
		$sesion = $this->getSesionDao()->get();
		$idUsuario = $sesion->getIdUsuario();
		$perfil    = $sesion->getPerfil();
		$usr       = $sesion->getUsuario(); 


		$identify  = $sesion->getIdentify();
		$identify2  =  md5($GLOBALS['configuration']['clave_identify'].$idUsuario.$perfil);
		if ($identify == $identify2){
			foreach ($array as $c){
				if ($perfil == $c){
					$this->setStatus("ok");
					$this->setMsj("");
					$GLOBALS['sesionG']['perfil'] = $perfil;
					$GLOBALS['sesionG']['idUsuario'] = $idUsuario;
					$GLOBALS['sesionG']['usuario'] = $usr;
					if ($perfil == 'seller'){
						$obj = new SellerDaoImpl();
						$GLOBALS['sesionG']['tokenMercadoPago'] = $obj->get($GLOBALS['sesionG']['id'],$idUsuario)->getTokenMercadoPago();
					}
					return true;
				}
		}
         $this->setStatus("error");
         $this->setMsj(getMsjConf('294'));
		}else{
			$this->setStatus("error");
			$this->setMsj(getMsjConf('295'));
		}
		$this->cerrar();	
		return false;
   }

	public function validarPublic ($array){
		$this->getSesionDao()->borrarDatos();
		foreach ($array as $c){
			if ($c == 'anonymous'){
				$GLOBALS['sesionG']['perfil'] = "anonymous";	
				$this->setStatus("ok");
				$this->setMsj("");
				return true;
			}
		}
      $this->setStatus("error");
      $this->setMsj(getMsjConf('294'));
		return false;
   }


	public function cerrar (){
		$this->getSesionDao()->eliminar();
   }


}
?>

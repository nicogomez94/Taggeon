<?php
include_once($GLOBALS['configuration']['path_app_admin_objects']."usuario/usuarioDao.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."util/database.php");
class  UsuarioDaoImpl implements UsuarioDao{
	private $status    = "";
	private $msj       = "";
   
	public function __construct(){
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

	public function alta (Usuario $objUsuario){
		$usuario     = Database::escape($objUsuario->getUsuario());
		$pass        = md5($GLOBALS['configuration']['clave_pass'].$objUsuario->getPass().$objUsuario->getPerfil());
		$pass        = Database::escape($pass);
		$perfil      = Database::escape($objUsuario->getPerfil());
 		$fechaAlta   = "CURRENT_TIMESTAMP";

		
		$sql =<<<SQL
			INSERT INTO `usuario`(`usuario`, `pass`, `perfil`, `fechaAlta`, `recuperar`) 
			VALUES ( $usuario,$pass,$perfil,$fechaAlta,'true')
SQL;
       if (!mysqli_query(Database::Connect(),$sql)){
			$this->setStatus("error");
			$this->setMsj(getMsjConf('297'));
			$this->setMsj(Database::Connect()->error);
       }else{
			$id = mysqli_insert_id(Database::Connect());
			$this->setMsj($id);
			$this->setStatus("ok");
       }

   }
	
	public function altaByCliente (Usuario $objUsuario,$idUsuarioCliente){
		$usuario     = Database::escape($objUsuario->getUsuario());
		$pass        = md5($GLOBALS['configuration']['clave_pass'].$objUsuario->getPass().$objUsuario->getPerfil());
		$pass        = Database::escape($pass);
		$perfil      = Database::escape($objUsuario->getPerfil());
 		$fechaAlta   = "CURRENT_TIMESTAMP";
		$idUsuarioClienteBase = Database::escape($idUsuarioCliente);
		
		$sql =<<<SQL
			INSERT INTO `usuario`(`usuario`, `pass`, `perfil`, `fechaAlta`, `owner`) 
			VALUES ( $usuario,$pass,$perfil,$fechaAlta,$idUsuarioClienteBase)
SQL;
       if (!mysql_query($sql,Database::Connect())){
			$this->setStatus("error");
			$this->setMsj(getMsjConf('297'));
       }else{
			$id = mysql_insert_id(Database::Connect());
			$this->setMsj($id);
			$this->setStatus("ok");
       }

   }

   public function getUsuarioByEmail ($email){
	$emailquote = database::escape($email);
	$sql =<<<sql
	select `usuario`.id, `usuario`.usuario, `usuario`.perfil, `usuario_picker`.id as id_picker,`usuario_picker`.email as email_picker,
	`usuario_seller`.id as id_seller,`usuario_seller`.email as email_seller 
	 FROM  `usuario` RIGHT join `usuario_picker` ON  `usuario`.id = `usuario_picker`.`idUsuario` 
	 LEFT JOIN `usuario_seller` ON  `usuario`.id = `usuario_seller`.`idUsuario`
	 
	 where 
		 `usuario`.`eliminar`   = 0  AND
		 (`usuario_picker`.email = $emailquote OR `usuario_seller`.email = $emailquote)
	 LIMIT 1
sql;

	$resultado=Database::Connect()->query($sql);
	$usuario = '';

	while($rowEmp=mysqli_fetch_array($resultado)){
		$usuario = $rowEmp['usuario'];
	  	if (empty($usuario)){
			$usuario = "";
	  	}
	}
	return $usuario;


}

	public function editar (Usuario $objUsuario){
		$id          = $objUsuario->getId();
		$usuario     = Database::escape($objUsuario->getUsuario());
		
		$idBase = Database::escape($id);
		$sql =<<<SQL
			UPDATE `usuario` SET
					 `usuario`=$usuario
			WHERE `id` = $idBase 
			      AND `eliminar` = 0 
SQL;

       if (!mysql_query($sql,Database::Connect())){
			$this->setStatus("error");
			$this->setMsj(getMsjConf('299')." $id.");
       }else{
			$this->setStatus("ok");
			$this->setMsj("");
       }
   }

	public function actualizarPass($idUsuario,$perfil,$pass){
		$pass          = md5($GLOBALS['configuration']['clave_pass'].$pass.$perfil);
		$passBase      = Database::escape($pass);
		$idUsuarioBase = Database::escape($idUsuario);
		$perfilBase    = Database::escape($perfil);
		
		$sql =<<<SQL
			UPDATE `usuario` SET
					 `pass`=$passBase
			WHERE `id` = $idUsuarioBase 
					AND `perfil` = $perfilBase
			      AND `eliminar` = 0 
SQL;

       if (!mysqli_query(Database::Connect(),$sql)){
			$this->setStatus("error");
			$this->setMsj(getMsjConf('300')." $idUsuario.");
       }else{
			$this->setStatus("ok");
			$this->setMsj("");
       }
   }

 

   public function validPassOld($idUsuario,$perfil,$pass){
		$pass          = md5($GLOBALS['configuration']['clave_pass'].$pass.$perfil);
		$passBase      = Database::escape($pass);
		$idUsuarioBase = Database::escape($idUsuario);

		$objUsuario = new Usuario();
		$sql =<<<SQL
			SELECT * FROM `usuario`
			WHERE `pass` = $passBase AND
				   `id` = $idUsuarioBase
			LIMIT 1
SQL;

		
		$resultado=Database::Connect()->query($sql);

		while($rowEmp=mysqli_fetch_array($resultado)){
			return true;
		}
		return false;
	}


	public function eliminar ($id){
		$idBase = Database::escape($id);
		$sql =<<<SQL
			UPDATE `usuario` SET
					 `eliminar`=1
			WHERE `id` = $idBase 
			      AND `eliminar` = 0 
SQL;
       if (!mysqli_query(Database::Connect(),$sql)){
			$this->setStatus("error");
			$this->setMsj(getMsjConf('301')." $id.");
       }else{
			$this->setStatus("ok");
			$this->setMsj("");
       }
   }

	public function eliminarBeneficiarioByCliente ($id){
		$idBase = Database::escape($id);
		$sql =<<<SQL
			UPDATE `usuario` SET
					 `eliminar`=1
			WHERE `id` = $idBase 
SQL;
       if (!mysql_query($sql,Database::Connect())){
			$this->setStatus("error");
			$this->setMsj(getMsjConf('301')." $id.");
       }else{
			$this->setStatus("ok");
			$this->setMsj("");
       }
   }
	
	public function getIdUsuarioBeneficiarioByCliente ($id){
		$idBase = Database::escape($id);
		$sql =<<<SQL
			SELECT `usuario`.`id` , `usuario`.`owner`
			FROM `usuario` , `beneficiario`
			WHERE `beneficiario`.`id` = $idBase
					AND `beneficiario`.`idUsuario` = `usuario`.`id`
SQL;
		$resultado=mysql_query($sql,Database::Connect());
		
		$list = Array();
		$idUsuario = 0;
		$idOwner   = 0;
		while($rowEmp=mysql_fetch_array($resultado)){
			$idUsuario = $rowEmp['id'];
      	if (empty($idUsuario)){
				$idUsuario = 0;
      	}
			
			$idOwner = $rowEmp['owner'];
      	if (empty($idOwner)){
				$idOwner = 0;
      	}
			
		}
		$list[] = array("idUsuario" => $idUsuario, "idOwner" => $idOwner);
		return $list;
   }

	public function get ($id){
		$idBase = Database::escape($id);
		$objUsuario = new Usuario();
		$sql =<<<SQL
			SELECT * FROM `usuario`
			WHERE `id` = $idBase 
			      AND `eliminar` = 0 
SQL;
		$resultado=mysql_query($sql,Database::Connect());
		
		while($rowEmp=mysql_fetch_array($resultado)){
			$id = $rowEmp['id'];
      	if (empty($id)){
				$id = "";
      	}
			
			$usuario = $rowEmp['usuario'];
      	if (empty($usuario)){
				$usuario = "";
      	}
			
			$pass = $rowEmp['pass'];
      	if (empty($pass)){
				$pass = "";
      	}
			
			$perfil = $rowEmp['perfil'];
      	if (empty($perfil)){
				$perfil = "";
      	}

			$fechaAlta = $rowEmp['fechaAlta'];
      	if (empty($fechaAlta)){
				$fechaAlta = "";
      	}

			$fechaUpdate = $rowEmp['fechaUpdate'];
      	if (empty($fechaUpdate)){
				$fechaUpdate = "";
      	}

			$objUsuario->setId($id);
			$objUsuario->setUsuario($usuario);
			$objUsuario->setPass($pass);
			$objUsuario->setPerfil($perfil);
			$objUsuario->setFechaAlta($fechaAlta);
			$objUsuario->setFechaUpdate($fechaUpdate);
		}
		return $objUsuario;
   }

	public function getByUsr ($usr){
		$usrBase = Database::escape($usr);
		$objUsuario = new Usuario();
		$sql =<<<SQL
			SELECT * FROM `usuario`
			WHERE `usuario` = $usrBase 
			      AND `eliminar` = 0 
SQL;
		$resultado=mysqli_query(Database::Connect(),$sql);
		
		while($rowEmp=mysqli_fetch_array($resultado)){
			$id = $rowEmp['id'];
      	if (empty($id)){
				$id = "";
      	}
			
			$usuario = $rowEmp['usuario'];
      	if (empty($usuario)){
				$usuario = "";
      	}
			
			$pass = $rowEmp['pass'];
      	if (empty($pass)){
				$pass = "";
      	}
			
			$perfil = $rowEmp['perfil'];
      	if (empty($perfil)){
				$perfil = "";
      	}

			$fechaAlta = $rowEmp['fechaAlta'];
      	if (empty($fechaAlta)){
				$fechaAlta = "";
      	}

			$fechaUpdate = $rowEmp['fechaUpdate'];
      	if (empty($fechaUpdate)){
				$fechaUpdate = "";
      	}

			$objUsuario->setId($id);
			$objUsuario->setUsuario($usuario);
			$objUsuario->setPass($pass);
			$objUsuario->setPerfil($perfil);
			$objUsuario->setFechaAlta($fechaAlta);
			$objUsuario->setFechaUpdate($fechaUpdate);
		}
		return $objUsuario;
   }
	
	public function existeUsuario ($usr){
		$usrBase = Database::escape($usr);
		$objUsuario = new Usuario();
		$sql =<<<SQL
			SELECT * FROM `usuario`
			WHERE `usuario` = $usrBase 
			LIMIT 1
SQL;
		$resultado=Database::Connect()->query($sql);
	
		$ret = false;	
		while($rowEmp=mysqli_fetch_array($resultado)){
			$ret = true;
		}
		return $ret;
   }
	
	public function existeUsuarioUpdate ($usr,$id){
		$usrBase = Database::escape($usr);
		$idBase  = Database::escape($id);
		$objUsuario = new Usuario();
		$sql =<<<SQL
			SELECT * FROM `usuario`
			WHERE `usuario` = $usrBase AND
				   `id` <> $idBase
			LIMIT 1
SQL;
		$resultado=mysql_query($sql,Database::Connect());
	
		$ret = false;	
		while($rowEmp=mysql_fetch_array($resultado)){
			$ret = true;
		}
		return $ret;
   }
	
	public function getList (){
		$usuarioList = Array();

		$sql =<<<SQL
			SELECT * FROM `usuario`
			WHERE `eliminar` = 0 
SQL;
		$resultado=mysql_query($sql,Database::Connect());
		
		while($rowEmp=mysql_fetch_array($resultado)){
			$id = $rowEmp['id'];
      	if (empty($id)){
				$id = "";
      	}
			
			$usuario = $rowEmp['usuario'];
      	if (empty($usuario)){
				$usuario = "";
      	}
			
			$pass = $rowEmp['pass'];
      	if (empty($pass)){
				$pass = "";
      	}
			
			$perfil = $rowEmp['perfil'];
      	if (empty($perfil)){
				$perfil = "";
      	}

			$fechaAlta = $rowEmp['fechaAlta'];
      	if (empty($fechaAlta)){
				$fechaAlta = "";
      	}

		$fechaUpdate = $rowEmp['fechaUpdate'];
      	if (empty($fechaUpdate)){
				$fechaUpdate = "";
      	}

			$objUsuario = new Usuario();
			$objUsuario->setId($id);
			$objUsuario->setUsuario($usuario);
			$objUsuario->setPass($pass);
			$objUsuario->setPerfil($perfil);
			$objUsuario->setFechaAlta($fechaAlta);
			$objUsuario->setFechaUpdate($fechaUpdate);
			$usuarioList[] = $objUsuario;
		}
		return $usuarioList;
   }

   public function existeEmail ($email){
	$emailquote = database::escape($email);
	$sql =<<<sql
	select `usuario`.id, `usuario`.usuario, `usuario`.perfil, `usuario_picker`.id as id_picker,`usuario_picker`.email as email_picker,
	`usuario_seller`.id as id_seller,`usuario_seller`.email as email_seller 
	 FROM  `usuario` RIGHT join `usuario_picker` ON  `usuario`.id = `usuario_picker`.`idUsuario` 
	 LEFT JOIN `usuario_seller` ON  `usuario`.id = `usuario_seller`.`idUsuario`
	 
	 where 
		 `usuario`.`eliminar`   = 0  AND
		 (`usuario_picker`.email = $emailquote OR `usuario_seller`.email = $emailquote)
	 LIMIT 1
sql;

	$resultado=Database::Connect()->query($sql);
	$list = Array();
	
	while($rowEmp=mysqli_fetch_array($resultado)){
		$id = $rowEmp['id'];
		if (empty($id)){
			$id = "";
		}
		$list[] = $id;

		$perfil = $rowEmp['perfil'];
		if (empty($perfil)){
		  $perfil = "";
	   }
		
		$usuario = $rowEmp['usuario'];
	  	if (empty($usuario)){
			$usuario = "";
	  	}
		$list[] = $usuario;
		
		$idUsuario = $rowEmp['id_'.$perfil];
	  	if (empty($idUsuario)){
			$idUsuario = "";
	 	}
		$list[] = $idUsuario;

		$list[] = $email;
		$list[] = $perfil;
	}
	return $list;


}


	
	public function updateRecuperarCliente ($idParam,$usuarioParam,$hashParam){
		$id          = Database::escape($idParam);
		$usuario     = Database::escape($usuarioParam);
		$hash        = Database::escape($hashParam);
 		$fechaRecuperar = "CURRENT_TIMESTAMP";
		
		$sql =<<<SQL
			UPDATE `usuario` SET
					 `fecha_recuperar`=$fechaRecuperar,
					 `recuperar` = $hash
			WHERE `id` = $id 
			      AND `usuario`.`eliminar` = 0 
			      AND `usuario`.`usuario`  = $usuario 
			      AND `usuario`.`perfil`   in ('seller','picker') 
SQL;

       if (!mysqli_query(Database::Connect(),$sql)){
			$this->setStatus("error");
			$this->setMsj(getMsjConf('303'));
       }else{
			$this->setStatus("ok");
			$this->setMsj("");
       }
   }
	
	public function updatePasswordByCliente ($idParam,$usuarioParam,$hashParam,$passwordParam,$perfil){
		$id          = Database::escape($idParam);
		$usuario     = Database::escape($usuarioParam);
		$hash        = Database::escape($hashParam);
		$password    = md5($GLOBALS['configuration']['clave_pass'].$passwordParam.$perfil);
		$password    = Database::escape($password);
 		$fechaRecuperar = "CURRENT_TIMESTAMP";
		
		$sql =<<<SQL
			UPDATE `usuario` SET
					 `fecha_recuperar`=$fechaRecuperar,
					 `recuperar` = '',
					 `pass` = $password
			WHERE `id` = $id 
			      AND `usuario`.`eliminar`  = 0 
			      AND `usuario`.`usuario`   = $usuario 
			      AND `usuario`.`recuperar` = $hash 
			      AND `usuario`.`perfil`    in ('seller','picker') 
SQL;
     $resultado = mysqli_query(Database::Connect(),$sql);
       if (!$resultado){
			$this->setStatus("error");
			$this->setMsj(getMsjConf('304'));
       }else{
			//$num_rows = mysql_num_rows($resultado);
			$num_rows = mysqli_affected_rows(Database::Connect());
			if ($num_rows > 0){
				$this->setStatus("ok");
				$this->setMsj("");
			}else{
				$this->setStatus("error");
				$this->setMsj(getMsjConf('305'));
			}
       }
   }

	public function clienteIsOwnerBeneficiario ($idCliente,$idBeneficiario){
		$idClienteBase = Database::escape($idCliente);
		$idBeneficiarioBase = Database::escape($idBeneficiario);
		$sql =<<<SQL
					SELECT usr1.id 
					FROM `cliente`, `usuario` usr1, `beneficiario`, `usuario` usr2 
					WHERE usr1.`eliminar` = 0 AND 
							usr1.`perfil` = 'beneficiario' AND 
							usr1.`id` = `beneficiario`.`idUsuario` AND 
							usr2.`eliminar` = 0 AND 
							usr2.`perfil` = 'cliente' AND 
							usr2.`id` = `cliente`.`idUsuario` AND 
							usr1.`owner` = usr2.`id` AND 
               		`cliente`.`id`      = $idClienteBase             AND
               		`beneficiario`.`id` = $idBeneficiarioBase
SQL;
		$resultado=mysql_query($sql,Database::Connect());

		$ret = false;	
		while($rowEmp=mysql_fetch_array($resultado)){
			$ret = true;

		}
		return $ret;
	}







	public function actualizarPerfilDatosPersonales($id,$nombre,$apellido,$usuario,$email,$perfil){
		$idBase      = Database::escape($id);
		$nombreBase = Database::escape($nombre);
		$apellidoBase = Database::escape($apellido);
		$usuarioBase    = Database::escape($usuario);
		$emailBase    = Database::escape($email);
		$perfilBase    = Database::escape($perfil);
		$sql =<<<SQL
			UPDATE `usuario_$perfil` SET
					 `nombre`=$nombreBase,
					 `apellido`=$apellidoBase,
					 `email`=$emailBase
			WHERE `idUsuario` = $idBase
				  AND `eliminar` = 0 
	SQL;
		
		if (mysqli_query(Database::Connect(), $sql)){
			$num_rows = Database::Connect()->affected_rows;
			if ($num_rows > 0){
				$this->setStatus("ok");
				$this->setMsj("Los datos personales se actualizaron con éxito.");
				return true;
			}
		}
		$this->setStatus("error");
		$this->setMsj("No se pudo actualizar los datos personales.".Database::Connect()->error);
		return true;
	}
	
	public function actualizarPerfilUsuario($id,$usuario,$perfil){
		$idBase      = Database::escape($id);
		$usuarioBase    = Database::escape($usuario);
		$perfilBase    = Database::escape($perfil);
		
		$mensajeUsuario = '';
		$sql =<<<SQL
			UPDATE `usuario` SET
				 `usuario`=$usuarioBase,
				 `usrUpdate`=$idBase
			WHERE `id` = $idBase
			  AND `perfil` = $perfilBase
			  AND `eliminar` = 0 
	SQL;
		if (mysqli_query(Database::Connect(), $sql)){
			$num_rows = Database::Connect()->affected_rows;
			if ($num_rows > 0){
				$this->setStatus("ok");
				$this->setMsj("El nombre de usuario se actualizó con éxito.");
				return true;
			}
		}
		$this->setStatus("error");
		//$this->setMsj("No se pudo actualizar el usuario. $sql".Database::Connect()->error);
		$this->setMsj("No se pudo actualizar el nombre de usuario.");
		return false;
	}

}
?>

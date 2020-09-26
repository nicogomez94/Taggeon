<?php
include_once($GLOBALS['configuration']['path_app_admin_objects']."cliente/clienteDao.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."util/database.php");
class  ClienteDaoImpl implements ClienteDao{
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


	public function alta (Cliente $cliente){
		$idUsuario = Database::escape($cliente->getUsuario()->getId());
		$nombre = Database::escape($cliente->getNombre());
		$apellido = Database::escape($cliente->getApellido());
		$email = Database::escape($cliente->getEmail());
		$ciudad = Database::escape($cliente->getCiudad());
		$estado = Database::escape($cliente->getEstado());
		$codigoPostal = Database::escape($cliente->getCodigoPostal());
		$pais = Database::escape($cliente->getPais());
		$telefono1Pais = Database::escape($cliente->getTelefono1Pais());
		$telefono1Ciudad = Database::escape($cliente->getTelefono1Ciudad());
		$telefono1 = Database::escape($cliente->getTelefono1());
		$telefono1Tipo = Database::escape($cliente->getTelefono1Tipo());
		$telefono2Pais = Database::escape($cliente->getTelefono2Pais());
		$telefono2Ciudad = Database::escape($cliente->getTelefono2Ciudad());
		$telefono2 = Database::escape($cliente->getTelefono2());
		$telefono2Tipo = Database::escape($cliente->getTelefono2Tipo());

 		$fechaAlta = "CURRENT_TIMESTAMP";

		$sql =<<<SQL
			INSERT INTO `usuario_picker`(idUsuario,`nombre`,`apellido`,`email`,`ciudad`,`estado`,`codigoPostal`,`pais`,`telefono1Pais`,`telefono1Ciudad`,`telefono1`,`telefono1Tipo`,`telefono2Pais`,`telefono2Ciudad`,`telefono2`,`telefono2Tipo`,`fechaAlta`) 
			VALUES ($idUsuario,$nombre,$apellido,$email,$ciudad,$estado,$codigoPostal,$pais,$telefono1Pais,$telefono1Ciudad,$telefono1,$telefono1Tipo,$telefono2Pais,$telefono2Ciudad,$telefono2,$telefono2Tipo,$fechaAlta)
SQL;
       
		if (!mysqli_query(Database::Connect(),$sql)){
			$this->setStatus("error");
			$this->setMsj(getMsjConf('113'));
			
       }else{
			$id = mysqli_insert_id(Database::Connect());
			$this->setMsj($id);
			$this->setStatus("ok");
       }

   }

	public function editar (Cliente $cliente){
		$idUsuario = $cliente->getUsuario()->getId();
		$id = $cliente->getId();
		$nombre = Database::escape($cliente->getNombre());
		$apellido = Database::escape($cliente->getApellido());
		$email = Database::escape($cliente->getEmail());
		$ciudad = Database::escape($cliente->getCiudad());
		$estado = Database::escape($cliente->getEstado());
		$codigoPostal = Database::escape($cliente->getCodigoPostal());
		$pais = Database::escape($cliente->getPais());
		$telefono1Pais = Database::escape($cliente->getTelefono1Pais());
		$telefono1Ciudad = Database::escape($cliente->getTelefono1Ciudad());
		$telefono1 = Database::escape($cliente->getTelefono1());
		$telefono1Tipo = Database::escape($cliente->getTelefono1Tipo());
		$telefono2Pais = Database::escape($cliente->getTelefono2Pais());
		$telefono2Ciudad = Database::escape($cliente->getTelefono2Ciudad());
		$telefono2 = Database::escape($cliente->getTelefono2());
		$telefono2Tipo = Database::escape($cliente->getTelefono2Tipo());

		$idBase = Database::escape($id);
		$idUsuarioBase = Database::escape($idUsuario);
		$sql =<<<SQL
			UPDATE `usuario_picker` SET 
					`nombre`=$nombre,`apellido`=$apellido,`email`=$email,`ciudad`=$ciudad,`estado`=$estado,`codigoPostal`=$codigoPostal,`pais`=$pais,`telefono1Pais`=$telefono1Pais,`telefono1Ciudad`=$telefono1Ciudad,`telefono1`=$telefono1,`telefono1Tipo`=$telefono1Tipo,`telefono2Pais`=$telefono2Pais,`telefono2Ciudad`=$telefono2Ciudad,`telefono2`=$telefono2,`telefono2Tipo`=$telefono2Tipo
			WHERE `idUsuario` = $idUsuarioBase AND
					 `id` = $idBase  
SQL;
       if (!mysqli_query(Database::Connect(),$sql)){
			$this->setStatus("error");
			$this->setMsj(getMsjConf('114')." $idUsuario.");
       }else{
			$this->setStatus("ok");
			$this->setMsj("");
       }

   }

	public function eliminar ($id,$idUsuario){
		$idBase = Database::escape($id);
		$idUsuarioBase = Database::escape($idUsuario);
		$sql =<<<SQL
			UPDATE `usuario_picker` SET
					 `eliminar`=1
			WHERE `id` = $idBase 
			      AND `eliminar` = 0 
			      AND `idUsuario` = $idUsuarioBase
SQL;
       if (!mysqli_query(Database::Connect(),$sql)){
			$this->setStatus("error");
			$this->setMsj(getMsjConf('115')." $id.");
       }else{
			$this->setStatus("ok");
			$this->setMsj("");
       }

   }

	public function get ($idParam,$idUsuarioParam){
		$cliente = new Cliente();
		$idBase = Database::escape($idParam);
		$idUsuarioBase = Database::escape($idUsuarioParam);
		$sql =<<<SQL
			SELECT `usuario_picker`.* 
			FROM `usuario_picker`, `usuario` 
			WHERE `usuario`.`eliminar` = 0 AND
					`usuario`.`perfil` = 'picker' AND
				   `usuario`.`id` = `usuario_picker`.`idUsuario` AND
					`usuario_picker`.`idUsuario` = $idUsuarioBase AND
					`usuario_picker`.`id` = $idBase
SQL;

		$resultado=Database::Connect()->query($sql);
		
		while($rowEmp=mysqli_fetch_array($resultado)){
			$id = $rowEmp['id'];
      	if (empty($id)){
				$id = "";
      	}

			$idUsuario = $rowEmp['idUsuario'];
      	if (empty($idUsuario)){
				$idUsuario = "";
      	}

			$fechaAlta = $rowEmp['fechaAlta'];
      	if (empty($fechaAlta)){
				$fechaAlta = "";
      	}

			$fechaUpdate = $rowEmp['fechaUpdate'];
      	if (empty($fechaUpdate)){
				$fechaUpdate = "";
      	}

			$nombre = $rowEmp['nombre'];
      	if (empty($nombre)){
				$nombre = "";
      	}
			$apellido = $rowEmp['apellido'];
      	if (empty($apellido)){
				$apellido = "";
      	}			
			$email = $rowEmp['email'];
      	if (empty($email)){
				$email = "";
      	}			
			$ciudad = $rowEmp['ciudad'];
      	if (empty($ciudad)){
				$ciudad = "";
      	}			$estado = $rowEmp['estado'];
      	if (empty($estado)){
				$estado = "";
      	}			$codigoPostal = $rowEmp['codigoPostal'];
      	if (empty($codigoPostal)){
				$codigoPostal = "";
      	}			$pais = $rowEmp['pais'];
      	if (empty($pais)){
				$pais = "";
      	}			$telefono1Pais = $rowEmp['telefono1Pais'];
      	if (empty($telefono1Pais)){
				$telefono1Pais = "";
      	}			$telefono1Ciudad = $rowEmp['telefono1Ciudad'];
      	if (empty($telefono1Ciudad)){
				$telefono1Ciudad = "";
      	}			$telefono1 = $rowEmp['telefono1'];
      	if (empty($telefono1)){
				$telefono1 = "";
      	}			$telefono1Tipo = $rowEmp['telefono1Tipo'];
      	if (empty($telefono1Tipo)){
				$telefono1Tipo = "";
      	}			$telefono2Pais = $rowEmp['telefono2Pais'];
      	if (empty($telefono2Pais)){
				$telefono2Pais = "";
      	}			$telefono2Ciudad = $rowEmp['telefono2Ciudad'];
      	if (empty($telefono2Ciudad)){
				$telefono2Ciudad = "";
      	}			$telefono2 = $rowEmp['telefono2'];
      	if (empty($telefono2)){
				$telefono2 = "";
      	}			$telefono2Tipo = $rowEmp['telefono2Tipo'];
      	if (empty($telefono2Tipo)){
				$telefono2Tipo = "";
      	}
			$cliente->setId($id);
			$cliente->setIdUsuario($idUsuario);
			$cliente->setFechaAlta($fechaAlta);
			$cliente->setFechaUpdate($fechaUpdate);
					$cliente->setNombre($nombre);
		$cliente->setApellido($apellido);
		$cliente->setEmail($email);
		$cliente->setCiudad($ciudad);
		$cliente->setEstado($estado);
		$cliente->setCodigoPostal($codigoPostal);
		$cliente->setPais($pais);
		$cliente->setTelefono1Pais($telefono1Pais);
		$cliente->setTelefono1Ciudad($telefono1Ciudad);
		$cliente->setTelefono1($telefono1);
		$cliente->setTelefono1Tipo($telefono1Tipo);
		$cliente->setTelefono2Pais($telefono2Pais);
		$cliente->setTelefono2Ciudad($telefono2Ciudad);
		$cliente->setTelefono2($telefono2);
		$cliente->setTelefono2Tipo($telefono2Tipo);

		}
		return $cliente;
   }

	public function getByIdUsuario ($idUsuarioParam){
		$cliente = new Cliente();
		$idUsuarioBase = Database::escape($idUsuarioParam);
		$sql =<<<SQL
			SELECT `usuario_picker`.* 
			FROM `usuario_picker`, `usuario` 
			WHERE `usuario`.`eliminar` = 0 AND
					`usuario`.`perfil` = 'picker' AND
				   `usuario`.`id` = `usuario_picker`.`idUsuario` AND
					`usuario_picker`.`idUsuario` = $idUsuarioBase
SQL;

		$resultado=Database::Connect()->query($sql);

		while($rowEmp=mysqli_fetch_array($resultado)){
			$id = $rowEmp['id'];
      	if (empty($id)){
				$id = "";
      	}

			$idUsuario = $rowEmp['idUsuario'];
      	if (empty($idUsuario)){
				$idUsuario = "";
      	}

			$fechaAlta = $rowEmp['fechaAlta'];
      	if (empty($fechaAlta)){
				$fechaAlta = "";
      	}

			$fechaUpdate = $rowEmp['fechaUpdate'];
      	if (empty($fechaUpdate)){
				$fechaUpdate = "";
      	}

						$nombre = $rowEmp['nombre'];
      	if (empty($nombre)){
				$nombre = "";
      	}			$apellido = $rowEmp['apellido'];
      	if (empty($apellido)){
				$apellido = "";
      	}			
			$email = $rowEmp['email'];
      	if (empty($email)){
				$email = "";
      	}			
			$ciudad = $rowEmp['ciudad'];
      	if (empty($ciudad)){
				$ciudad = "";
      	}			$estado = $rowEmp['estado'];
      	if (empty($estado)){
				$estado = "";
      	}			$codigoPostal = $rowEmp['codigoPostal'];
      	if (empty($codigoPostal)){
				$codigoPostal = "";
      	}			$pais = $rowEmp['pais'];
      	if (empty($pais)){
				$pais = "";
      	}			$telefono1Pais = $rowEmp['telefono1Pais'];
      	if (empty($telefono1Pais)){
				$telefono1Pais = "";
      	}			$telefono1Ciudad = $rowEmp['telefono1Ciudad'];
      	if (empty($telefono1Ciudad)){
				$telefono1Ciudad = "";
      	}			$telefono1 = $rowEmp['telefono1'];
      	if (empty($telefono1)){
				$telefono1 = "";
      	}			$telefono1Tipo = $rowEmp['telefono1Tipo'];
      	if (empty($telefono1Tipo)){
				$telefono1Tipo = "";
      	}			$telefono2Pais = $rowEmp['telefono2Pais'];
      	if (empty($telefono2Pais)){
				$telefono2Pais = "";
      	}			$telefono2Ciudad = $rowEmp['telefono2Ciudad'];
      	if (empty($telefono2Ciudad)){
				$telefono2Ciudad = "";
      	}			$telefono2 = $rowEmp['telefono2'];
      	if (empty($telefono2)){
				$telefono2 = "";
      	}			$telefono2Tipo = $rowEmp['telefono2Tipo'];
      	if (empty($telefono2Tipo)){
				$telefono2Tipo = "";
      	}
			$cliente->setId($id);
			$cliente->setIdUsuario($idUsuario);
			$cliente->setFechaAlta($fechaAlta);
			$cliente->setFechaUpdate($fechaUpdate);
					$cliente->setNombre($nombre);
		$cliente->setApellido($apellido);
		$cliente->setEmail($email);
		$cliente->setCiudad($ciudad);
		$cliente->setEstado($estado);
		$cliente->setCodigoPostal($codigoPostal);
		$cliente->setPais($pais);
		$cliente->setTelefono1Pais($telefono1Pais);
		$cliente->setTelefono1Ciudad($telefono1Ciudad);
		$cliente->setTelefono1($telefono1);
		$cliente->setTelefono1Tipo($telefono1Tipo);
		$cliente->setTelefono2Pais($telefono2Pais);
		$cliente->setTelefono2Ciudad($telefono2Ciudad);
		$cliente->setTelefono2($telefono2);
		$cliente->setTelefono2Tipo($telefono2Tipo);

		}
		return $cliente;
   }
	
	public function getList (){
		$list = Array();
		$sql =<<<SQL
			SELECT `usuario_picker`.* 
			FROM `usuario_picker`, `usuario` 
			WHERE `usuario`.`eliminar` = 0 AND
					`usuario`.`perfil` = 'picker' AND
				   `usuario`.`id` = `usuario_picker`.`idUsuario`
SQL;

		$resultado=mysqli_query(Database::Connect(),$sql);
		
		while($rowEmp=mysqli_fetch_array($resultado)){
			$cliente = new Cliente();
			$id = $rowEmp['id'];
      	if (empty($id)){
				$id = "";
      	}

			$idUsuario = $rowEmp['idUsuario'];
      	if (empty($idUsuario)){
				$idUsuario = "";
      	}

			$fechaAlta = $rowEmp['fechaAlta'];
      	if (empty($fechaAlta)){
				$fechaAlta = "";
      	}

			$fechaUpdate = $rowEmp['fechaUpdate'];
      	if (empty($fechaUpdate)){
				$fechaUpdate = "";
      	}

						$nombre = $rowEmp['nombre'];
      	if (empty($nombre)){
				$nombre = "";
      	}			$apellido = $rowEmp['apellido'];
      	if (empty($apellido)){
				$apellido = "";
      	}			
			$email = $rowEmp['email'];
      	if (empty($email)){
				$email = "";
      	}			

			$ciudad = $rowEmp['ciudad'];
      	if (empty($ciudad)){
				$ciudad = "";
      	}			$estado = $rowEmp['estado'];
      	if (empty($estado)){
				$estado = "";
      	}			$codigoPostal = $rowEmp['codigoPostal'];
      	if (empty($codigoPostal)){
				$codigoPostal = "";
      	}			$pais = $rowEmp['pais'];
      	if (empty($pais)){
				$pais = "";
      	}			$telefono1Pais = $rowEmp['telefono1Pais'];
      	if (empty($telefono1Pais)){
				$telefono1Pais = "";
      	}			$telefono1Ciudad = $rowEmp['telefono1Ciudad'];
      	if (empty($telefono1Ciudad)){
				$telefono1Ciudad = "";
      	}			$telefono1 = $rowEmp['telefono1'];
      	if (empty($telefono1)){
				$telefono1 = "";
      	}			$telefono1Tipo = $rowEmp['telefono1Tipo'];
      	if (empty($telefono1Tipo)){
				$telefono1Tipo = "";
      	}			$telefono2Pais = $rowEmp['telefono2Pais'];
      	if (empty($telefono2Pais)){
				$telefono2Pais = "";
      	}			$telefono2Ciudad = $rowEmp['telefono2Ciudad'];
      	if (empty($telefono2Ciudad)){
				$telefono2Ciudad = "";
      	}			$telefono2 = $rowEmp['telefono2'];
      	if (empty($telefono2)){
				$telefono2 = "";
      	}			$telefono2Tipo = $rowEmp['telefono2Tipo'];
      	if (empty($telefono2Tipo)){
				$telefono2Tipo = "";
      	}
			$cliente->setId($id);
			$cliente->setIdUsuario($idUsuario);
			$cliente->setFechaAlta($fechaAlta);
			$cliente->setFechaUpdate($fechaUpdate);
					$cliente->setNombre($nombre);
		$cliente->setApellido($apellido);
		$cliente->setEmail($email);
		$cliente->setCiudad($ciudad);
		$cliente->setEstado($estado);
		$cliente->setCodigoPostal($codigoPostal);
		$cliente->setPais($pais);
		$cliente->setTelefono1Pais($telefono1Pais);
		$cliente->setTelefono1Ciudad($telefono1Ciudad);
		$cliente->setTelefono1($telefono1);
		$cliente->setTelefono1Tipo($telefono1Tipo);
		$cliente->setTelefono2Pais($telefono2Pais);
		$cliente->setTelefono2Ciudad($telefono2Ciudad);
		$cliente->setTelefono2($telefono2);
		$cliente->setTelefono2Tipo($telefono2Tipo);

			$list[] = $cliente;
		}
		return $list;
   }
	
	public function existeemail ($email){
		$emailquote = database::escape($email);
		$sql =<<<sql
			select `usuario_picker`.id,`usuario_picker`.email 
			from `usuario_picker`, `usuario` 
			where `usuario`.`eliminar` = 0               and
					`usuario`.`perfil` = 'picker'         and
				   `usuario`.`id` = `usuario_picker`.`idusuario` and
					`usuario_picker`.email = $emailquote
			limit 1
sql;

		$resultado=Database::Connect()->query($sql);
		
		$id = 0;
		$emailbase = "";
		while($rowemp=mysqli_fetch_array($resultado)){
			$id = $rowemp['id'];
      	if (empty($id)){
				$id = 0;
      	}
			$emailbase = $rowemp['email'];
      	if (empty($emailbase)){
				$emailbase = "";
      	}
		}
		if ($id > 0 && $emailbase == $email){
			return true;
		}else{
			return false;
		}
   }

	public function existeemailMod ($email,$idParam){
		$emailquote = database::escape($email);
		$idquote = database::escape($idParam);
		$sql =<<<sql
			select `usuario_picker`.id,`usuario_picker`.email 
			from `usuario_picker`, `usuario` 
			where `usuario`.`eliminar` = 0               and
					`usuario`.`perfil` = 'picker'         and
				   `usuario`.`id` = `usuario_picker`.`idusuario` and
					`usuario_picker`.email = $emailquote          and
					`usuario_picker`.`id` != $idquote
			limit 1
sql;

		$resultado=mysqli_query(Database::Connect(),$sql);
		
		$id = 0;
		$emailbase = "";
		while($rowemp=mysqli_fetch_array($resultado)){
			$id = $rowemp['id'];
      	if (empty($id)){
				$id = 0;
      	}
			$emailbase = $rowemp['email'];
      	if (empty($emailbase)){
				$emailbase = "";
      	}
		}
		if ($id > 0 && $emailbase == $email && $id != $idParam){
			return true;
		}else{
			return false;
		}
   }
	
	public function existeCliente ($id){
		$idBase = Database::escape($id);
		$sql =<<<SQL
			SELECT `usuario_picker`.* 
			FROM `usuario_picker`, `usuario` 
			WHERE `usuario`.`eliminar` = 0 AND
					`usuario`.`perfil` = 'picker' AND
				   `usuario`.`id` = `usuario_picker`.`idUsuario` AND
					`usuario_picker`.`id` = $idBase
SQL;
		$resultado=mysqli_query(Database::Connect(),$sql);

		$ret = false;	
		while($rowEmp=mysqli_fetch_array($resultado)){
			$ret = true;

		}
		return $ret;
   }

}
?>

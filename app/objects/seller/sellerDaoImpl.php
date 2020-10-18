<?php
include_once($GLOBALS['configuration']['path_app_admin_objects']."seller/sellerDao.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."util/database.php");
class  SellerDaoImpl implements SellerDao{
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


	public function alta (Seller $seller){
		$idUsuario = Database::escape($seller->getUsuario()->getId());
		$nombre = Database::escape($seller->getNombre());
		$apellido = Database::escape($seller->getApellido());
		$email = Database::escape($seller->getEmail());
		$ciudad = Database::escape($seller->getCiudad());
		$estado = Database::escape($seller->getEstado());
		$codigoPostal = Database::escape($seller->getCodigoPostal());
		$pais = Database::escape($seller->getPais());
		$telefono1Pais = Database::escape($seller->getTelefono1Pais());
		$telefono1Ciudad = Database::escape($seller->getTelefono1Ciudad());
		$telefono1 = Database::escape($seller->getTelefono1());
		$telefono1Tipo = Database::escape($seller->getTelefono1Tipo());
		$telefono2Pais = Database::escape($seller->getTelefono2Pais());
		$telefono2Ciudad = Database::escape($seller->getTelefono2Ciudad());
		$telefono2 = Database::escape($seller->getTelefono2());
		$telefono2Tipo = Database::escape($seller->getTelefono2Tipo());

 		$fechaAlta = "CURRENT_TIMESTAMP";

		$sql =<<<SQL
			INSERT INTO `usuario_seller`(idUsuario,`nombre`,`apellido`,`email`,`ciudad`,`estado`,`codigoPostal`,`pais`,`telefono1Pais`,`telefono1Ciudad`,`telefono1`,`telefono1Tipo`,`telefono2Pais`,`telefono2Ciudad`,`telefono2`,`telefono2Tipo`,`fechaAlta`) 
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

	public function editar (Seller $seller){
		$idUsuario = $seller->getUsuario()->getId();
		$id = $seller->getId();
		$nombre = Database::escape($seller->getNombre());
		$apellido = Database::escape($seller->getApellido());
		$email = Database::escape($seller->getEmail());
		$ciudad = Database::escape($seller->getCiudad());
		$estado = Database::escape($seller->getEstado());
		$codigoPostal = Database::escape($seller->getCodigoPostal());
		$pais = Database::escape($seller->getPais());
		$telefono1Pais = Database::escape($seller->getTelefono1Pais());
		$telefono1Ciudad = Database::escape($seller->getTelefono1Ciudad());
		$telefono1 = Database::escape($seller->getTelefono1());
		$telefono1Tipo = Database::escape($seller->getTelefono1Tipo());
		$telefono2Pais = Database::escape($seller->getTelefono2Pais());
		$telefono2Ciudad = Database::escape($seller->getTelefono2Ciudad());
		$telefono2 = Database::escape($seller->getTelefono2());
		$telefono2Tipo = Database::escape($seller->getTelefono2Tipo());

		$idBase = Database::escape($id);
		$idUsuarioBase = Database::escape($idUsuario);
		$sql =<<<SQL
			UPDATE `usuario_seller` SET 
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
			UPDATE `usuario_seller` SET
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
		$seller = new Seller();
		$idBase = Database::escape($idParam);
		$idUsuarioBase = Database::escape($idUsuarioParam);
		$sql =<<<SQL
			SELECT `usuario_seller`.* 
			FROM `usuario_seller`, `usuario` 
			WHERE `usuario`.`eliminar` = 0 AND
					`usuario`.`perfil` = 'seller' AND
				   `usuario`.`id` = `usuario_seller`.`idUsuario` AND
					`usuario_seller`.`idUsuario` = $idUsuarioBase AND
					`usuario_seller`.`id` = $idBase
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
			$seller->setId($id);
			$seller->setIdUsuario($idUsuario);
			$seller->setFechaAlta($fechaAlta);
			$seller->setFechaUpdate($fechaUpdate);
					$seller->setNombre($nombre);
		$seller->setApellido($apellido);
		$seller->setEmail($email);
		$seller->setCiudad($ciudad);
		$seller->setEstado($estado);
		$seller->setCodigoPostal($codigoPostal);
		$seller->setPais($pais);
		$seller->setTelefono1Pais($telefono1Pais);
		$seller->setTelefono1Ciudad($telefono1Ciudad);
		$seller->setTelefono1($telefono1);
		$seller->setTelefono1Tipo($telefono1Tipo);
		$seller->setTelefono2Pais($telefono2Pais);
		$seller->setTelefono2Ciudad($telefono2Ciudad);
		$seller->setTelefono2($telefono2);
		$seller->setTelefono2Tipo($telefono2Tipo);

		}
		return $seller;
   }

	public function getByIdUsuario ($idUsuarioParam){
		$seller = new Seller();
		$idUsuarioBase = Database::escape($idUsuarioParam);
		$sql =<<<SQL
			SELECT `usuario_seller`.* 
			FROM `usuario_seller`, `usuario` 
			WHERE `usuario`.`eliminar` = 0 AND
					`usuario`.`perfil` = 'seller' AND
				   `usuario`.`id` = `usuario_seller`.`idUsuario` AND
					`usuario_seller`.`idUsuario` = $idUsuarioBase
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
			$seller->setId($id);
			$seller->setIdUsuario($idUsuario);
			$seller->setFechaAlta($fechaAlta);
			$seller->setFechaUpdate($fechaUpdate);
					$seller->setNombre($nombre);
		$seller->setApellido($apellido);
		$seller->setEmail($email);
		$seller->setCiudad($ciudad);
		$seller->setEstado($estado);
		$seller->setCodigoPostal($codigoPostal);
		$seller->setPais($pais);
		$seller->setTelefono1Pais($telefono1Pais);
		$seller->setTelefono1Ciudad($telefono1Ciudad);
		$seller->setTelefono1($telefono1);
		$seller->setTelefono1Tipo($telefono1Tipo);
		$seller->setTelefono2Pais($telefono2Pais);
		$seller->setTelefono2Ciudad($telefono2Ciudad);
		$seller->setTelefono2($telefono2);
		$seller->setTelefono2Tipo($telefono2Tipo);

		}
		return $seller;
   }
	
	public function getList (){
		$list = Array();
		$sql =<<<SQL
			SELECT `usuario_seller`.* 
			FROM `usuario_seller`, `usuario` 
			WHERE `usuario`.`eliminar` = 0 AND
					`usuario`.`perfil` = 'seller' AND
				   `usuario`.`id` = `usuario_seller`.`idUsuario`
SQL;

		$resultado=mysqli_query(Database::Connect(),$sql);
		
		while($rowEmp=mysqli_fetch_array($resultado)){
			$seller = new Seller();
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
			$seller->setId($id);
			$seller->setIdUsuario($idUsuario);
			$seller->setFechaAlta($fechaAlta);
			$seller->setFechaUpdate($fechaUpdate);
					$seller->setNombre($nombre);
		$seller->setApellido($apellido);
		$seller->setEmail($email);
		$seller->setCiudad($ciudad);
		$seller->setEstado($estado);
		$seller->setCodigoPostal($codigoPostal);
		$seller->setPais($pais);
		$seller->setTelefono1Pais($telefono1Pais);
		$seller->setTelefono1Ciudad($telefono1Ciudad);
		$seller->setTelefono1($telefono1);
		$seller->setTelefono1Tipo($telefono1Tipo);
		$seller->setTelefono2Pais($telefono2Pais);
		$seller->setTelefono2Ciudad($telefono2Ciudad);
		$seller->setTelefono2($telefono2);
		$seller->setTelefono2Tipo($telefono2Tipo);

			$list[] = $seller;
		}
		return $list;
   }
	
	public function existeemail ($email){
		$emailquote = database::escape($email);
		$sql =<<<sql
			select `usuario_picker`.id,`usuario_picker`.email 
			from `usuario_picker`, `usuario` 
			where 
			   `usuario`.`id` = `usuario_picker`.`idusuario` and
			   `usuario_picker`.email = $emailquote
			UNION
			select `usuario_seller`.id,`usuario_seller`.email 
			from `usuario_seller`, `usuario` 
			where 
			   `usuario`.`id` = `usuario_seller`.`idusuario` and
			   `usuario_seller`.email = $emailquote
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
			select `usuario_seller`.id,`usuario_seller`.email 
			from `usuario_seller`, `usuario` 
			where `usuario`.`eliminar` = 0               and
					`usuario`.`perfil` = 'seller'         and
				   `usuario`.`id` = `usuario_seller`.`idusuario` and
					`usuario_seller`.email = $emailquote          and
					`usuario_seller`.`id` != $idquote
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
	
	public function existeSeller ($id){
		$idBase = Database::escape($id);
		$sql =<<<SQL
			SELECT `usuario_seller`.* 
			FROM `usuario_seller`, `usuario` 
			WHERE `usuario`.`eliminar` = 0 AND
					`usuario`.`perfil` = 'seller' AND
				   `usuario`.`id` = `usuario_seller`.`idUsuario` AND
					`usuario_seller`.`id` = $idBase
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

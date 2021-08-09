<?php
include_once($GLOBALS['configuration']['path_app_admin_objects']."usuario/usuarioDao.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."util/database.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."carrito/CarritoManager.php");

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

   public function getUsuarioPublic ($id_usuario){
	$id_usuarioquote = database::escape($id_usuario);
	$sql =<<<sql
        SELECT  idUsuario,nombre,apellido,email
		FROM    `usuario_picker`
		WHERE `usuario_picker`.`eliminar` = 0 AND idUsuario=$id_usuarioquote
		UNION
	
		SELECT idUsuario,nombre,apellido,email
		FROM `usuario_seller`
		WHERE `usuario_seller`.`eliminar` = 0  AND idUsuario=$id_usuarioquote
sql;

	$resultado = Database::Connect()->query($sql);
	$list = array();

	while ($rowEmp = mysqli_fetch_array($resultado)) {
		$list[] = $rowEmp;
	}
	return $list;
}



public function getUsuarioBySesion (){
		$id_usuario = $GLOBALS['sesionG']['idUsuario']; 
	$id_usuarioquote = database::escape($id_usuario);
	$sql =<<<sql
        SELECT `envio_codigo_postal`, `envio_ciudad_localidad`, `envio_numero`, `envio_direccion`
		FROM    `usuario`
		WHERE `usuario`.eliminar = 0 AND id=$id_usuarioquote
	
sql;

	$resultado = Database::Connect()->query($sql);
	$list = array();

	while ($rowEmp = mysqli_fetch_array($resultado)) {
		$list[] = $rowEmp;
	}

	
	return $list;
}

public function isAdmin (){
	$id_usuario = $GLOBALS['sesionG']['idUsuario']; 
$id_usuarioquote = database::escape($id_usuario);
$sql =<<<sql
	SELECT `admin`
	FROM    `usuario`
	WHERE `usuario`.eliminar = 0 AND id=$id_usuarioquote

sql;

$ret = 0;
$resultado = Database::Connect()->query($sql);

while ($rowEmp = mysqli_fetch_array($resultado)) {
	$ret = $rowEmp['admin'];
}


return $ret;
}


public function getUsuarioByEmail ($email){
	$emailquote = database::escape($email);
	$sql =<<<sql
SELECT `usuario`.usuario
FROM `usuario`
WHERE 
     `usuario`.`eliminar`   = 0 AND 
     `usuario`.id IN (
     	        SELECT  `usuario_picker`.idUsuario
		FROM    `usuario_picker`
		WHERE `usuario_picker`.`eliminar` = 0 AND `usuario_picker`.email = $emailquote
		UNION
		SELECT `usuario_seller`.idUsuario
		FROM `usuario_seller`
		WHERE `usuario_seller`.`eliminar` = 0 AND `usuario_seller`.email = $emailquote
     )
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

public function getTokenMP ($idCarrito){

	//validar
	$idCarritoBD = Database::escape($idCarrito);

	$sql =<<<SQL
		SELECT `acces_token`
		FROM usuario_seller
		WHERE `idUsuario` IN (SELECT distinct id_vendedor FROM carrito_detalle WHERE id_carrito = $idCarritoBD) 
			  AND `eliminar` = 0 AND acces_token is not null
		UNION
		SELECT `acces_token`
		FROM `usuario_picker`
		WHERE `idUsuario` IN (SELECT distinct id_vendedor FROM carrito_detalle WHERE id_carrito = $idCarritoBD) 
			  AND `eliminar` = 0 AND acces_token is not null
SQL;
//echo $sql;
$fp = fopen("/var/www/html/log.txt", 'a');
fwrite($fp, $sql);
fclose($fp);

$resultado=Database::Connect()->query($sql);
$tokenMP = '';

while($rowEmp=mysqli_fetch_array($resultado)){
	$tokenMP = $rowEmp['acces_token'];
	  if (empty($tokenMP)){
		$tokenMP = "";
	  }
}
return $tokenMP;

}

public function actualizarTokenMP ($json,$token){
	$idUsuario = $_GET['state'];
	$tokenBD      = Database::escape($token);

	$jsonBD      = Database::escape($json);
	$idUsuarioBD = Database::escape($idUsuario);

	$sql =<<<SQL
		UPDATE `usuario_seller` SET
				 `acces_token`=$tokenBD,`jsonmercadopago`=$jsonBD
		WHERE `idUsuario` = $idUsuarioBD 
			  AND `eliminar` = 0 
SQL;

$fp = fopen("/var/www/html/log.txt", 'a');
fwrite($fp, "\n$sql\n");
fclose($fp);

    if (!mysqli_query(Database::Connect(), $sql)) {
		$this->setStatus("error");
		$this->setMsj("error al actualizar token seller");
   }else{
		$this->setStatus("ok");
		$this->setMsj("");
		$sql =<<<SQL
		UPDATE `usuario_picker` SET
				 `acces_token`=$tokenBD,`jsonmercadopago`=$jsonBD
		WHERE `idUsuario` = $idUsuarioBD 
			  AND `eliminar` = 0 
SQL;

$fp = fopen("/var/www/html/log.txt", 'a');
fwrite($fp, "\n$sql\n");
fclose($fp);
    	if (!mysqli_query(Database::Connect(), $sql)) {


				$this->setStatus("error");
				$this->setMsj("error al actualizar token picker");
   		}else{
				$this->setStatus("ok");
				$this->setMsj("");
   		}
	   
   }
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
		select `usuario_seller`.id as id_usuario,`usuario`.usuario,
                               `usuario`.`perfil`, `usuario`.`id`
                from `usuario_seller`, `usuario`
                where
                                   `usuario`.`id` = `usuario_seller`.`idusuario` and
                                    `usuario_seller`.email = $emailquote 
		UNION
                select `usuario_picker`.id as id_usuario,`usuario`.usuario,
                               `usuario`.`perfil`, `usuario`.`id`
               from `usuario_picker`, `usuario`
               where
                                   `usuario`.`id` = `usuario_picker`.`idusuario` and
                                  `usuario_picker`.email = $emailquote        
sql;

	$resultado=Database::Connect()->query($sql);
	$list = Array();
	
	while($rowEmp=mysqli_fetch_array($resultado)){
		$id = $rowEmp['id'];
		if (empty($id)){
			$id = "";
		}

		$perfil = $rowEmp['perfil'];
		if (empty($perfil)){
		  $perfil = "";
	   	}
		
		$usuario = $rowEmp['usuario'];
	  	if (empty($usuario)){
			$usuario = "";
	  	}
		
		$idUsuario = $rowEmp['id_usuario'];
	  	if (empty($idUsuario)){
			$idUsuario = "";
	 	}
		$list[] = $id;
		$list[] = $usuario;
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

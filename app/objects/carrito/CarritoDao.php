<?php
//include_once("../util/database.php");
class  CarritoDao
{
	private $status    = "";
	private $msj       = "";

	public function __construct()
	{
	}

	public function getStatus()
	{
		return $this->status;
	}

	private  function setStatus($status)
	{
		$this->status = $status;
	}

	public function getMsj()
	{
		return $this->msj;
	}

	private function setMsj($msj)
	{
		$this->msj = $msj;
	}


	public function altaCarrito(array $data)
	{

        $tipo = isset($data["tipo"]) ? $data["tipo"] : '';
        $tipoDB = Database::escape($tipo);
        $subtotal = isset($data["subtotal"]) ? $data["subtotal"] : '';
        $subtotalDB = Database::escape($subtotal);
        $total = isset($data["total"]) ? $data["total"] : '';
        $totalDB = Database::escape($total);
        $envio_nombre_apellido = isset($data["envio_nombre_apellido"]) ? $data["envio_nombre_apellido"] : '';
        $envio_nombre_apellidoDB = Database::escape($envio_nombre_apellido);
        $envio_codigo_postal = isset($data["envio_codigo_postal"]) ? $data["envio_codigo_postal"] : '';
        $envio_codigo_postalDB = Database::escape($envio_codigo_postal);
        $envio_ciudad_localidad = isset($data["envio_ciudad_localidad"]) ? $data["envio_ciudad_localidad"] : '';
        $envio_ciudad_localidadDB = Database::escape($envio_ciudad_localidad);
        $email = isset($data["email"]) ? $data["email"] : '';
        $emailDB = Database::escape($email);
        $notas = isset($data["notas"]) ? $data["notas"] : '';
        $notasDB = Database::escape($notas);
		$usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        
		$sql = <<<SQL
			INSERT INTO carrito (id_tipo, subtotal, total, envio_nombre_apellido, envio_codigo_postal, envio_ciudad_localidad, email, notas,usuario_alta)  
			VALUES ($tipoDB, $subtotalDB, $totalDB, $envio_nombre_apellidoDB, $envio_codigo_postalDB, $envio_ciudad_localidadDB, $emailDB, $notasDB,$usuarioAltaDB)
SQL;

		if (!mysqli_query(Database::Connect(), $sql)) {
			$this->setStatus("ERROR");
			$this->setMsj("$sql" . Database::Connect()->error);
		} else {
			$id = mysqli_insert_id(Database::Connect());
			$this->setMsj($id);
			$this->setStatus("OK");
			return true;
		}

		return false;
	}


	public function editarCarrito(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		$idDB = Database::escape($id);
		$usuario = $GLOBALS['sesionG']['idUsuario'];
        $usuarioDB = Database::escape($usuario);

        $tipo = isset($data["tipo"]) ? $data["tipo"] : '';
        $tipoDB = Database::escape($tipo);
        $subtotal = isset($data["subtotal"]) ? $data["subtotal"] : '';
        $subtotalDB = Database::escape($subtotal);
        $total = isset($data["total"]) ? $data["total"] : '';
        $totalDB = Database::escape($total);
        $envio_nombre_apellido = isset($data["envio_nombre_apellido"]) ? $data["envio_nombre_apellido"] : '';
        $envio_nombre_apellidoDB = Database::escape($envio_nombre_apellido);
        $envio_codigo_postal = isset($data["envio_codigo_postal"]) ? $data["envio_codigo_postal"] : '';
        $envio_codigo_postalDB = Database::escape($envio_codigo_postal);
        $envio_ciudad_localidad = isset($data["envio_ciudad_localidad"]) ? $data["envio_ciudad_localidad"] : '';
        $envio_ciudad_localidadDB = Database::escape($envio_ciudad_localidad);
        $email = isset($data["email"]) ? $data["email"] : '';
        $emailDB = Database::escape($email);
        $notas = isset($data["notas"]) ? $data["notas"] : '';
        $notasDB = Database::escape($notas);

        $sql = <<<SQL
			UPDATE
			    `carrito`
			SET
			    `usuario_editar` = $usuarioDB,
id_`tipo` = $tipoDB, `subtotal` = $subtotalDB, `total` = $totalDB, `envio_nombre_apellido` = $envio_nombre_apellidoDB, `envio_codigo_postal` = $envio_codigo_postalDB, `envio_ciudad_localidad` = $envio_ciudad_localidadDB, `email` = $emailDB, `notas` = $notasDB
			    WHERE
					`id` = $idDB AND
					`usuario_alta` = $usuarioDB
SQL;

        if (!mysqli_query(Database::Connect(), $sql)) {
            $this->setStatus("ERROR");
            $this->setMsj("$sql" . Database::Connect()->error);
        } else {
            $this->setStatus("OK");
            return true;
        }

        return false;


	}

	public function eliminarCarrito(array $data)
    {
        $id = isset($data["id"]) ? $data["id"] : '';
        $idDB = Database::escape($id);
        $usuario = $GLOBALS['sesionG']['idUsuario'];
        $usuarioDB = Database::escape($usuario);

        $sql = <<<SQL
UPDATE
    `carrito`
SET
    `usuario_editar` = $usuarioDB,
    `eliminar` = 1
WHERE
`id` = $idDB AND
`usuario_alta` = $usuarioDB
SQL;

        if (!mysqli_query(Database::Connect(), $sql)) {
            $this->setStatus("ERROR");
            $this->setMsj("$sql" . Database::Connect()->error);
        } else {
            $this->setStatus("OK");
            return true;
        }

        return false;
    }

	public function existeId($id)
    {
        $id = isset($id) ?   $id : '';
        $idDB = Database::escape($id);
        $usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        
        $sql = <<<SQL
                        SELECT * FROM carrito
                        WHERE 
                            id = $idDB AND
                            (eliminar = 0 OR eliminar is null) AND
                            usuario_alta = $usuarioAltaDB
SQL;

        $resultado = mysqli_query(Database::Connect(), $sql);
        $row_cnt = mysqli_num_rows($resultado);
        if ($row_cnt == 1) {
            $this->setStatus("OK");
            return true;
        }

        $this->setStatus("ERROR");
        $this->setMsj("No se puede editar. Motivo: No existe o no tiene permisos.");
        return false;
	}
	
	public function getListCarrito()
    {
        $usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $sql = <<<sql
        SELECT
		*
    	FROM
		`carrito`
		WHERE
        (`carrito`.eliminar = 0 OR `carrito`.eliminar IS NULL) AND `carrito`.usuario_alta = $usuarioAltaDB
sql;
        $resultado = Database::Connect()->query($sql);
        $list = array();

        while ($rowEmp = mysqli_fetch_array($resultado)) {
            $list[] = $rowEmp;
        }
        return $list;
	}

	public function getCarrito($id)
    {
        $usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $id = isset($id) ?   $id : '';
        $idDB = Database::escape($id);
        $sql = <<<sql
		SELECT
		*
    	FROM
        `carrito`
    	WHERE
			carrito.id=$idDB AND 
        (`carrito`.eliminar = 0 OR `carrito`.eliminar IS NULL) AND `carrito`.usuario_alta = $usuarioAltaDB
sql;
        $resultado = Database::Connect()->query($sql);
        $row_cnt = mysqli_num_rows($resultado);
        $list = array();
        if ($row_cnt <= 0) {
            $this->setStatus("ERROR");
            $this->setMsj("No se encontraron resultados o no tiene permisos para editar.");
            return $list;
        }


        while ($rowEmp = mysqli_fetch_array($resultado)) {
            $list[] = $rowEmp;
        }
        $this->setStatus("ok");
        return $list;
    }

                
    public function getListTipo()
    {
        $sql = <<<sql
                    SELECT
                    `id`,
                    `nombre`
                FROM
                    `tipo`
                WHERE
                    eliminar=0 OR eliminar is null
sql;

        $resultado = Database::Connect()->query($sql);
        $list = array();

        while ($rowEmp = mysqli_fetch_array($resultado)) {
            $list[] = $rowEmp;
        }
        return $list;
    }


                public function existeTipo($id_tipo)
                {
                    $id_tipo = isset($id_tipo) ?   $id_tipo : '';
                    $id_tipoDB = Database::escape($id_tipo);      
            
                    $sql = <<<SQL
                        SELECT *FROM tipo
                        WHERE 
                            id = $id_tipoDB AND
                            (eliminar = 0 OR eliminar is null);
SQL;            

                    $resultado=mysqli_query(Database::Connect(), $sql);
                    $row_cnt = mysqli_num_rows($resultado);
                    if ($row_cnt == 1){
                        $this->setStatus("OK");
                        return true;
                    }
                    
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo tipo es incorrecto.");
                    return false;

                }

                public function eliminarDetalle(array $data)
                {
                    $id = isset($data["id"]) ? $data["id"] : '';
                    $idDB = Database::escape($id);
                    $usuario = $GLOBALS['sesionG']['idUsuario'];
                    $usuarioDB = Database::escape($usuario);
                    $sql = <<<SQL
            UPDATE
                `carrito_detalle`
            SET
                `usuario_editar` = $usuarioDB,
                `eliminar` = 1
            WHERE
            `id_producto` = $idDB AND
            `usuario_alta` = $usuarioDB
            SQL;
            
                    if (!mysqli_query(Database::Connect(), $sql)) {
                        $this->setStatus("ERROR");
                        $this->setMsj("$sql" . Database::Connect()->error);
                    } else {
                        $this->setStatus("OK");
                        return true;
                    }
            
                    return false;
                }
            
                public function altaDetalle(array $data)
                {
                    $id_carrito = isset($data["id_carrito"]) ?   $data["id_carrito"] : '';
                    $id_carritoDB = Database::escape($id_carrito);      
            
                    $detalle = isset($data["detalle"]) ?  $data["detalle"] : '';
                    $detalleDB = Database::escape($detalle);      

                    $sql = <<<SQL
                        INSERT INTO carrito_detalle (id_carrito,detalle) 
                        VALUES ($id_carritoDB,$detalleDB)
SQL;
            
                    if (!mysqli_query(Database::Connect(), $sql)) {
                        $this->setStatus("ERROR");
                        $this->setMsj("$sql" . Database::Connect()->error);
                    } else {
                        $id = mysqli_insert_id(Database::Connect());
                        $this->setMsj($id);
                        $this->setStatus("OK");
                        return true;
                    }
            
                    return false;
                }

}

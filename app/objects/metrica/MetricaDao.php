<?php
//include_once("../util/database.php");
class  MetricaDao
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


	public function altaMetrica(array $data)
	{

        $carrito_detalle = isset($data["carrito_detalle"]) ? $data["carrito_detalle"] : '';
        $carrito_detalleDB = Database::escape($carrito_detalle);
        $rol_usuario = isset($data["rol_usuario"]) ? $data["rol_usuario"] : '';
        $rol_usuarioDB = Database::escape($rol_usuario);
        $comision_porc = isset($data["comision_porc"]) ? $data["comision_porc"] : '';
        $comision_porcDB = Database::escape($comision_porc);
        $comision = isset($data["comision"]) ? $data["comision"] : '';
        $comisionDB = Database::escape($comision);
        $pago_id = isset($data["pago_id"]) ? $data["pago_id"] : '';
        $pago_idDB = Database::escape($pago_id);
		$usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        
		$sql = <<<SQL
			INSERT INTO metrica (id_carrito_detalle, rol_usuario, comision_porc, comision, pago_id,usuario_alta)  
			VALUES ($carrito_detalleDB, $rol_usuarioDB, $comision_porcDB, $comisionDB, $pago_idDB,$usuarioAltaDB)
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


	public function editarMetrica(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		$idDB = Database::escape($id);
		$usuario = $GLOBALS['sesionG']['idUsuario'];
        $usuarioDB = Database::escape($usuario);

        $carrito_detalle = isset($data["carrito_detalle"]) ? $data["carrito_detalle"] : '';
        $carrito_detalleDB = Database::escape($carrito_detalle);
        $rol_usuario = isset($data["rol_usuario"]) ? $data["rol_usuario"] : '';
        $rol_usuarioDB = Database::escape($rol_usuario);
        $comision_porc = isset($data["comision_porc"]) ? $data["comision_porc"] : '';
        $comision_porcDB = Database::escape($comision_porc);
        $comision = isset($data["comision"]) ? $data["comision"] : '';
        $comisionDB = Database::escape($comision);
        $pago_id = isset($data["pago_id"]) ? $data["pago_id"] : '';
        $pago_idDB = Database::escape($pago_id);

        $sql = <<<SQL
			UPDATE
			    `metrica`
			SET
			    `usuario_editar` = $usuarioDB,
id_`carrito_detalle` = $carrito_detalleDB, `rol_usuario` = $rol_usuarioDB, `comision_porc` = $comision_porcDB, `comision` = $comisionDB, `pago_id` = $pago_idDB
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

	public function eliminarMetrica(array $data)
    {
        $id = isset($data["id"]) ? $data["id"] : '';
        $idDB = Database::escape($id);
        $usuario = $GLOBALS['sesionG']['idUsuario'];
        $usuarioDB = Database::escape($usuario);

        $sql = <<<SQL
UPDATE
    `metrica`
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
                        SELECT * FROM metrica
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
	
	public function getListMetrica()
    {
        $usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $sql = <<<sql
        SELECT
		*
    	FROM
		`metrica`
		WHERE
        (`metrica`.eliminar = 0 OR `metrica`.eliminar IS NULL) AND `metrica`.usuario_alta = $usuarioAltaDB
sql;
        $resultado = Database::Connect()->query($sql);
        $list = array();

        while ($rowEmp = mysqli_fetch_array($resultado)) {
            $list[] = $rowEmp;
        }
        return $list;
	}

	public function getMetrica($id)
    {
        $usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $id = isset($id) ?   $id : '';
        $idDB = Database::escape($id);
        $sql = <<<sql
		SELECT
		*
    	FROM
        `metrica`
    	WHERE
			metrica.id=$idDB AND 
        (`metrica`.eliminar = 0 OR `metrica`.eliminar IS NULL) AND `metrica`.usuario_alta = $usuarioAltaDB
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

                
    public function getListCarrito_detalle()
    {
        $sql = <<<sql
                    SELECT
                    `id`,
                    `nombre`
                FROM
                    `carrito_detalle`
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


                public function existeCarrito_detalle($id_carrito_detalle)
                {
                    $id_carrito_detalle = isset($id_carrito_detalle) ?   $id_carrito_detalle : '';
                    $id_carrito_detalleDB = Database::escape($id_carrito_detalle);      
            
                    $sql = <<<SQL
                        SELECT *FROM carrito_detalle
                        WHERE 
                            id = $id_carrito_detalleDB AND
                            (eliminar = 0 OR eliminar is null);
SQL;            

                    $resultado=mysqli_query(Database::Connect(), $sql);
                    $row_cnt = mysqli_num_rows($resultado);
                    if ($row_cnt == 1){
                        $this->setStatus("OK");
                        return true;
                    }
                    
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo carrito_detalle es incorrecto.");
                    return false;

                }


}

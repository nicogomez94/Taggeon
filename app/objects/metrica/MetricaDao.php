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


		public function altaMetrica($carrito_detalle,$rol_usuario,$comision_porc,$comision,$pago_id,$usuario)
		{

		$carrito_detalleDB = Database::escape($carrito_detalle);
		$rol_usuarioDB = Database::escape($rol_usuario);
		$comision_porcDB = Database::escape($comision_porc);
		$comisionDB = Database::escape($comision);
		$pago_idDB = Database::escape($pago_id);
		$usuarioAltaDB = Database::escape($usuario);
		
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
		
		public function getListMetricaTotal()
	    {
		$usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $sql = <<<sql
SELECT sum(m.comision) as total
FROM `carrito` as c INNER JOIN  `carrito_detalle` as cd ON c.id = cd.id_carrito 
     inner join usuario as u ON cd.`usuario_alta` = u.id
     INNER JOIN usuario as u2 ON cd.id_vendedor = u2.id
     INNER JOIN usuario as u3 ON cd.id_usuario_publicador = u3.id
     INNER JOIN metrica m ON  m.id_carrito_detalle = cd.id
WHERE
    (c.eliminar is null or c.eliminar = 0)
    AND (cd.eliminar is null or cd.eliminar = 0)
    AND c.estado is not null 
    AND c.estado = 4
    AND m.usuario_alta = $usuarioAltaDB 
sql;
//echo $sql;

        $resultado = Database::Connect()->query($sql);
	$total = 0;
	$rowEmp = mysqli_fetch_array($resultado);
        $total = $rowEmp['total'];
        return $total;
}



		public function getListMetricaTotalPendiente()
	    {
		$usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $sql = <<<sql
SELECT sum(m.comision) as total
FROM `carrito` as c INNER JOIN  `carrito_detalle` as cd ON c.id = cd.id_carrito 
     inner join usuario as u ON cd.`usuario_alta` = u.id
     INNER JOIN usuario as u2 ON cd.id_vendedor = u2.id
     INNER JOIN usuario as u3 ON cd.id_usuario_publicador = u3.id
     INNER JOIN metrica m ON  m.id_carrito_detalle = cd.id
WHERE
    (c.eliminar is null or c.eliminar = 0)
    AND (cd.eliminar is null or cd.eliminar = 0)
    AND c.estado is not null 
    AND c.estado = 4
    AND m.usuario_alta = $usuarioAltaDB AND m.estado is null 
sql;
//echo $sql;

        $resultado = Database::Connect()->query($sql);
	$total = 0;
	$rowEmp = mysqli_fetch_array($resultado);
        $total = $rowEmp['total'];
        return $total;
}


		public function getListMetrica()
	    {
		$usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $sql = <<<sql
SELECT cd.id as id_detalle, c.estado,cd.id_carrito as operacion,u.id as id_usuario_comprador, u.usuario as usuario_comprador,
u2.id as id_usuario_vendedor,u2.usuario as usuario_vendedor, 
u3.id as id_usuario_taggeador,u3.usuario as usuario_taggeador,
cd.id_producto, cd.nombre_producto, cd.cantidad, cd.precio,cd.total as costo_venta
,m.rol_usuario, m.comision_porc, m.comision, m.pago_id, m.fecha_alta, m.estado,m.motivo
FROM `carrito` as c INNER JOIN  `carrito_detalle` as cd ON c.id = cd.id_carrito 
     inner join usuario as u ON cd.`usuario_alta` = u.id
     INNER JOIN usuario as u2 ON cd.id_vendedor = u2.id
     INNER JOIN usuario as u3 ON cd.id_usuario_publicador = u3.id
     INNER JOIN metrica m ON  m.id_carrito_detalle = cd.id
WHERE
    (c.eliminar is null or c.eliminar = 0)
    AND (cd.eliminar is null or cd.eliminar = 0)
    AND c.estado is not null 
    AND c.estado = 4
    AND m.usuario_alta = $usuarioAltaDB 
ORDER BY cd.fecha_alta DESC

sql;
//echo $sql;

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



		
		public function getListMetricaTotalTagger()
	    {
		$usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $sql = <<<sql
SELECT sum(m.comision) as total
FROM `carrito` as c INNER JOIN  `carrito_detalle` as cd ON c.id = cd.id_carrito 
     inner join usuario as u ON cd.`usuario_alta` = u.id
     INNER JOIN usuario as u2 ON cd.id_vendedor = u2.id
     INNER JOIN usuario as u3 ON cd.id_usuario_publicador = u3.id
     INNER JOIN metrica m ON  m.id_carrito_detalle = cd.id
WHERE
    (c.eliminar is null or c.eliminar = 0)
    AND (cd.eliminar is null or cd.eliminar = 0)
    AND c.estado is not null 
    AND c.estado = 4
    AND m.usuario_alta = $usuarioAltaDB 
    AND m.rol_usuario = 'taggeador'
sql;
//echo $sql;

        $resultado = Database::Connect()->query($sql);
	$total = 0;
	$rowEmp = mysqli_fetch_array($resultado);
        $total = $rowEmp['total'];
        return $total;
}



		public function getListMetricaTotalPendienteTagger()
	    {
		$usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $sql = <<<sql
SELECT sum(m.comision) as total
FROM `carrito` as c INNER JOIN  `carrito_detalle` as cd ON c.id = cd.id_carrito 
     inner join usuario as u ON cd.`usuario_alta` = u.id
     INNER JOIN usuario as u2 ON cd.id_vendedor = u2.id
     INNER JOIN usuario as u3 ON cd.id_usuario_publicador = u3.id
     INNER JOIN metrica m ON  m.id_carrito_detalle = cd.id
WHERE
    (c.eliminar is null or c.eliminar = 0)
    AND (cd.eliminar is null or cd.eliminar = 0)
    AND c.estado is not null 
    AND c.estado = 4
    AND m.usuario_alta = $usuarioAltaDB AND m.estado is null 
    AND m.rol_usuario = 'taggeador'
sql;
//echo $sql;

        $resultado = Database::Connect()->query($sql);
	$total = 0;
	$rowEmp = mysqli_fetch_array($resultado);
        $total = $rowEmp['total'];
		$total = isset($total) ?   $total : 0;

        return $total;
}


		public function getListMetricaTagger()
	    {
		$usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $sql = <<<sql
SELECT cd.id as id_detalle, c.estado,cd.id_carrito as operacion,u.id as id_usuario_comprador, u.usuario as usuario_comprador,
u2.id as id_usuario_vendedor,u2.usuario as usuario_vendedor, 
u3.id as id_usuario_taggeador,u3.usuario as usuario_taggeador,
cd.id_producto, cd.nombre_producto, cd.cantidad, cd.precio,cd.total as costo_venta
,m.rol_usuario, m.comision_porc, m.comision, m.pago_id, m.fecha_alta, m.estado,m.motivo
FROM `carrito` as c INNER JOIN  `carrito_detalle` as cd ON c.id = cd.id_carrito 
     inner join usuario as u ON cd.`usuario_alta` = u.id
     INNER JOIN usuario as u2 ON cd.id_vendedor = u2.id
     INNER JOIN usuario as u3 ON cd.id_usuario_publicador = u3.id
     INNER JOIN metrica m ON  m.id_carrito_detalle = cd.id
WHERE
    (c.eliminar is null or c.eliminar = 0)
    AND (cd.eliminar is null or cd.eliminar = 0)
    AND c.estado is not null 
    AND c.estado = 4
    AND m.usuario_alta = $usuarioAltaDB  
    AND m.rol_usuario = 'taggeador'
ORDER BY cd.fecha_alta DESC

sql;
//echo $sql;

        $resultado = Database::Connect()->query($sql);
        $list = array();

        while ($rowEmp = mysqli_fetch_array($resultado)) {
            $list[] = $rowEmp;
        }
        return $list;
	}

		
		public function getListMetricaTotalSeller()
	    {
		$usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $sql = <<<sql
SELECT sum(m.comision) as total
FROM `carrito` as c INNER JOIN  `carrito_detalle` as cd ON c.id = cd.id_carrito 
     inner join usuario as u ON cd.`usuario_alta` = u.id
     INNER JOIN usuario as u2 ON cd.id_vendedor = u2.id
     INNER JOIN usuario as u3 ON cd.id_usuario_publicador = u3.id
     INNER JOIN metrica m ON  m.id_carrito_detalle = cd.id
WHERE
    (c.eliminar is null or c.eliminar = 0)
    AND (cd.eliminar is null or cd.eliminar = 0)
    AND c.estado is not null 
    AND c.estado = 4
    AND m.usuario_alta = $usuarioAltaDB 
    AND m.rol_usuario = 'vendedor'
sql;
//echo $sql;

        $resultado = Database::Connect()->query($sql);
	$total = 0;
	$rowEmp = mysqli_fetch_array($resultado);
        $total = $rowEmp['total'];
        return $total;
}



		public function getListMetricaTotalPendienteSeller()
	    {
		$usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $sql = <<<sql
SELECT sum(m.comision) as total
FROM `carrito` as c INNER JOIN  `carrito_detalle` as cd ON c.id = cd.id_carrito 
     inner join usuario as u ON cd.`usuario_alta` = u.id
     INNER JOIN usuario as u2 ON cd.id_vendedor = u2.id
     INNER JOIN usuario as u3 ON cd.id_usuario_publicador = u3.id
     INNER JOIN metrica m ON  m.id_carrito_detalle = cd.id
WHERE
    (c.eliminar is null or c.eliminar = 0)
    AND (cd.eliminar is null or cd.eliminar = 0)
    AND c.estado is not null 
    AND c.estado = 4
    AND m.usuario_alta = $usuarioAltaDB AND m.estado is null 
    AND m.rol_usuario = 'vendedor'
sql;
//echo $sql;

        $resultado = Database::Connect()->query($sql);
	$total = 0;
	$rowEmp = mysqli_fetch_array($resultado);
        $total = $rowEmp['total'];
        return $total;
}


		public function getListMetricaSeller()
	    {
		$usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $sql = <<<sql
SELECT cd.id as id_detalle, c.estado,cd.id_carrito as operacion,u.id as id_usuario_comprador, u.usuario as usuario_comprador,
u2.id as id_usuario_vendedor,u2.usuario as usuario_vendedor, 
u3.id as id_usuario_taggeador,u3.usuario as usuario_taggeador,
cd.id_producto, cd.nombre_producto, cd.cantidad, cd.precio,cd.total as costo_venta
,m.rol_usuario, m.comision_porc, m.comision, m.pago_id, m.fecha_alta, m.estado,m.motivo
FROM `carrito` as c INNER JOIN  `carrito_detalle` as cd ON c.id = cd.id_carrito 
     inner join usuario as u ON cd.`usuario_alta` = u.id
     INNER JOIN usuario as u2 ON cd.id_vendedor = u2.id
     INNER JOIN usuario as u3 ON cd.id_usuario_publicador = u3.id
     INNER JOIN metrica m ON  m.id_carrito_detalle = cd.id
WHERE
    (c.eliminar is null or c.eliminar = 0)
    AND (cd.eliminar is null or cd.eliminar = 0)
    AND c.estado is not null 
    AND c.estado = 4
    AND m.usuario_alta = $usuarioAltaDB  
    AND m.rol_usuario = 'vendedor'
ORDER BY cd.fecha_alta DESC

sql;
//echo $sql;

        $resultado = Database::Connect()->query($sql);
        $list = array();

        while ($rowEmp = mysqli_fetch_array($resultado)) {
            $list[] = $rowEmp;
        }
        return $list;
	}

		
		public function getListMetricaTotalAdmin()
	    {
		$usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $sql = <<<sql
SELECT sum(m.comision) as total
FROM `carrito` as c INNER JOIN  `carrito_detalle` as cd ON c.id = cd.id_carrito 
     inner join usuario as u ON cd.`usuario_alta` = u.id
     INNER JOIN usuario as u2 ON cd.id_vendedor = u2.id
     INNER JOIN usuario as u3 ON cd.id_usuario_publicador = u3.id
     INNER JOIN metrica m ON  m.id_carrito_detalle = cd.id
WHERE
    (c.eliminar is null or c.eliminar = 0)
    AND (cd.eliminar is null or cd.eliminar = 0)
    AND c.estado is not null 
    AND c.estado = 4
    AND m.usuario_alta = $usuarioAltaDB 
    AND m.rol_usuario = 'market'
sql;
//echo $sql;

        $resultado = Database::Connect()->query($sql);
	$total = 0;
	$rowEmp = mysqli_fetch_array($resultado);
        $total = $rowEmp['total'];
        return $total;
}



		public function getListMetricaTotalPendienteAdmin()
	    {
		$usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $sql = <<<sql
SELECT sum(m.comision) as total
FROM `carrito` as c INNER JOIN  `carrito_detalle` as cd ON c.id = cd.id_carrito 
     inner join usuario as u ON cd.`usuario_alta` = u.id
     INNER JOIN usuario as u2 ON cd.id_vendedor = u2.id
     INNER JOIN usuario as u3 ON cd.id_usuario_publicador = u3.id
     INNER JOIN metrica m ON  m.id_carrito_detalle = cd.id
WHERE
    (c.eliminar is null or c.eliminar = 0)
    AND (cd.eliminar is null or cd.eliminar = 0)
    AND c.estado is not null 
    AND c.estado = 4
    AND m.usuario_alta = $usuarioAltaDB AND m.estado is null 
    AND m.rol_usuario = 'market'
sql;
//echo $sql;

        $resultado = Database::Connect()->query($sql);
	$total = 0;
	$rowEmp = mysqli_fetch_array($resultado);
        $total = $rowEmp['total'];
        return $total;
}


		public function getListMetricaAdmin()
	    {
		$usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $sql = <<<sql
SELECT cd.id as id_detalle, c.estado,cd.id_carrito as operacion,u.id as id_usuario_comprador, u.usuario as usuario_comprador,
u2.id as id_usuario_vendedor,u2.usuario as usuario_vendedor, 
u3.id as id_usuario_taggeador,u3.usuario as usuario_taggeador,
cd.id_producto, cd.nombre_producto, cd.cantidad, cd.precio,cd.total as costo_venta
,m.rol_usuario, m.comision_porc, m.comision, m.pago_id, m.fecha_alta, m.estado,m.motivo
FROM `carrito` as c INNER JOIN  `carrito_detalle` as cd ON c.id = cd.id_carrito 
     inner join usuario as u ON cd.`usuario_alta` = u.id
     INNER JOIN usuario as u2 ON cd.id_vendedor = u2.id
     INNER JOIN usuario as u3 ON cd.id_usuario_publicador = u3.id
     INNER JOIN metrica m ON  m.id_carrito_detalle = cd.id
WHERE
    (c.eliminar is null or c.eliminar = 0)
    AND (cd.eliminar is null or cd.eliminar = 0)
    AND c.estado is not null 
    AND c.estado = 4

ORDER BY cd.fecha_alta DESC
sql;
//    AND m.usuario_alta = $usuarioAltaDB  
//     AND m.rol_usuario = 'market'
//echo $sql;

        $resultado = Database::Connect()->query($sql);
        $list = array();

        while ($rowEmp = mysqli_fetch_array($resultado)) {
            $list[] = $rowEmp;
        }
        return $list;
	}


	public function getListPedidosTaggerAdmin()
	    {
        $sql = <<<sql
SELECT
*
FROM 
solicitudRetiroDinero s
WHERE
    (s.eliminar is null or s.eliminar = 0)
ORDER BY s.fecha_alta DESC

sql;
//echo $sql;

        $resultado = Database::Connect()->query($sql);
        $list = array();

        while ($rowEmp = mysqli_fetch_array($resultado)) {
            $list[] = $rowEmp;
        }
        return $list;
	}


    
	public function getListPedidosTagger()
	    {
		$usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $sql = <<<sql
SELECT
*
FROM 
solicitudRetiroDinero s
WHERE
    (s.eliminar is null or s.eliminar = 0)
    AND s.usuario_alta=$usuarioAltaDB
ORDER BY s.fecha_alta DESC

sql;
//echo $sql;

        $resultado = Database::Connect()->query($sql);
        $list = array();

        while ($rowEmp = mysqli_fetch_array($resultado)) {
            $list[] = $rowEmp;
        }
        return $list;
	}


    
 

public function altaSolicitudRetiroDinero($monto)
{

$usuario = $GLOBALS['sesionG']['idUsuario'];
$usuarioAltaDB = Database::escape($usuario);
$montoDB = Database::escape($monto);

    $sql = <<<SQL
        INSERT INTO solicitudRetiroDinero (monto, usuario_alta)  
        VALUES ($montoDB,$usuarioAltaDB)
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





    public function actualizarMetricaIdRetiro($idSolicitudRetiroDinero)
    {
    $idSolicitudRetiroDineroDB = Database::escape($idSolicitudRetiroDinero);

    $usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
    $usuarioAltaDB = Database::escape($usuarioAlta);
    $sql = <<<sql
    update metrica set estado="PENDIENTE",idSolicitudRetiroDinero=$idSolicitudRetiroDineroDB
where (eliminar is null or eliminar = 0)
AND estado is  null 
AND usuario_alta = $usuarioAltaDB 
AND rol_usuario = 'taggeador'
sql;
//echo $sql;
if (!mysqli_query(Database::Connect(), $sql)) {
    $this->setStatus("ERROR");
    $this->setMsj("$sql" . Database::Connect()->error);
} else {
    $this->setStatus("OK");
    return true;
}
return false;
}


public function actualizarMetricaIdRetiroEnviado($idSolicitudRetiroDinero)
{
$idSolicitudRetiroDineroDB = Database::escape($idSolicitudRetiroDinero);

$usuario = $GLOBALS['sesionG']['idUsuario'];
$usuarioDB = Database::escape($usuario);


$sql = <<<sql
update metrica set estado="Finalizado"
,usuario_editar=$usuarioDB
where 
(eliminar is null or eliminar = 0)
AND idSolicitudRetiroDinero=$idSolicitudRetiroDineroDB
sql;

//echo $sql;
if (!mysqli_query(Database::Connect(), $sql)) {
$this->setStatus("ERROR");
$this->setMsj("$sql" . Database::Connect()->error);
} else {
$this->setStatus("OK");
return true;
}
return false;
}

public function actualizarEnviarDineroIdFinalizado($idSolicitudRetiroDinero)
{
$idSolicitudRetiroDineroDB = Database::escape($idSolicitudRetiroDinero);

$usuario = $GLOBALS['sesionG']['idUsuario'];
$usuarioDB = Database::escape($usuario);


$sql = <<<sql
update solicitudRetiroDinero set estado="Finalizado"
,usuario_editar=$usuarioDB
where 
(eliminar is null or eliminar = 0)
AND id=$idSolicitudRetiroDineroDB
sql;

//echo $sql;
if (!mysqli_query(Database::Connect(), $sql)) {
$this->setStatus("ERROR");
$this->setMsj("$sql" . Database::Connect()->error);
} else {
$this->setStatus("OK");
return true;
}
return false;
}


public function existePedidoByIdAndMonto($id,$monto)
	    {
		$id = isset($id) ?   $id : '';
		$idDB = Database::escape($id);
		$monto = isset($monto) ?   $monto : 0;

		$montoDB = Database::escape($monto);

		$sql = <<<SQL
            SELECT *
            FROM solicitudRetiroDinero s
            WHERE
                (s.eliminar is null or s.eliminar = 0)
                AND s.id = $idDB 
                AND s.monto=$montoDB
                AND s.estado is null

SQL;

		$resultado = mysqli_query(Database::Connect(), $sql);
		$row_cnt = mysqli_num_rows($resultado);
		if ($row_cnt == 1) {
		    $this->setStatus("OK");
		    return true;
		}

        if ($row_cnt == 0) {
		    $this->setStatus("ERROR");
    		$this->setMsj("No se encontro la solicitud o ya se encuentra finalizada.");
		    return false;
		}

        
        if ($row_cnt > 1) {
		    $this->setStatus("ERROR");
    		$this->setMsj("Existe varias solicitudes con el mismo id y monto.");
		    return false;
		}
}


public function existePedidoSolicitado()
	    {
        $usuario = $GLOBALS['sesionG']['idUsuario'];
		$usuarioDB = Database::escape($usuario);
		$sql = <<<SQL
            SELECT *
            FROM solicitudRetiroDinero s
            WHERE
                (s.eliminar is null or s.eliminar = 0)
                AND s.usuario_alta=$usuarioDB
                AND s.estado is null
SQL;
//echo $sql;
		$resultado = mysqli_query(Database::Connect(), $sql);
		$row_cnt = mysqli_num_rows($resultado);

        if ($row_cnt == 0) {
		    $this->setStatus("OK");
		    return true;
		}

        
        if ($row_cnt > 0) {
		    $this->setStatus("ERROR");
    		$this->setMsj("Existe una solicitud de retiro. No se puede generar otra solicitud cuando existe una pendiente.");
		    return false;
		}
}


}

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

        $publicacion = isset($data["publicacion"]) ? $data["publicacion"] : '';
        $publicacionDB = Database::escape($publicacion);
        $producto = isset($data["producto"]) ? $data["producto"] : '';
        $productoDB = Database::escape($producto);
        $precio = isset($data["precio"]) ? $data["precio"] : '';
        $precioDB = Database::escape($precio);
        $comision_porc = isset($data["comision_porc"]) ? $data["comision_porc"] : '';
        $comision_porcDB = Database::escape($comision_porc);
        $comision = isset($data["comision"]) ? $data["comision"] : '';
        $comisionDB = Database::escape($comision);
        $carrito_detalle = isset($data["carrito_detalle"]) ? $data["carrito_detalle"] : '';
        $carrito_detalleDB = Database::escape($carrito_detalle);
        $carrito = isset($data["carrito"]) ? $data["carrito"] : '';
        $carritoDB = Database::escape($carrito);
        $usuario = isset($data["usuario"]) ? $data["usuario"] : '';
        $usuarioDB = Database::escape($usuario);
        $fecha_transaccion = isset($data["fecha_transaccion"]) ? $data["fecha_transaccion"] : '';
        $fecha_transaccionDB = Database::escape($fecha_transaccion);
        $objeto_producto = isset($data["objeto_producto"]) ? $data["objeto_producto"] : '';
        $objeto_productoDB = Database::escape($objeto_producto);
        $objeto_publicacion = isset($data["objeto_publicacion"]) ? $data["objeto_publicacion"] : '';
        $objeto_publicacionDB = Database::escape($objeto_publicacion);
        $objeto_carrito = isset($data["objeto_carrito"]) ? $data["objeto_carrito"] : '';
        $objeto_carritoDB = Database::escape($objeto_carrito);
        $objeto_carrito_detalle = isset($data["objeto_carrito_detalle"]) ? $data["objeto_carrito_detalle"] : '';
        $objeto_carrito_detalleDB = Database::escape($objeto_carrito_detalle);
        $objeto_usuario = isset($data["objeto_usuario"]) ? $data["objeto_usuario"] : '';
        $objeto_usuarioDB = Database::escape($objeto_usuario);
        $fecha_pago = isset($data["fecha_pago"]) ? $data["fecha_pago"] : '';
        $fecha_pagoDB = Database::escape($fecha_pago);
        $id_pago = isset($data["id_pago"]) ? $data["id_pago"] : '';
        $id_pagoDB = Database::escape($id_pago);
        $estado = isset($data["estado"]) ? $data["estado"] : '';
        $estadoDB = Database::escape($estado);
        $observacion = isset($data["observacion"]) ? $data["observacion"] : '';
        $observacionDB = Database::escape($observacion);
		$usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        
		$sql = <<<SQL
			INSERT INTO metrica (id_publicacion, id_producto, precio, comision_porc, comision, id_carrito_detalle, id_carrito, id_usuario, fecha_transaccion, objeto_producto, objeto_publicacion, objeto_carrito, objeto_carrito_detalle, objeto_usuario, fecha_pago, id_pago, estado, observacion,usuario_alta)  
			VALUES ($publicacionDB, $productoDB, $precioDB, $comision_porcDB, $comisionDB, $carrito_detalleDB, $carritoDB, $usuarioDB, $fecha_transaccionDB, $objeto_productoDB, $objeto_publicacionDB, $objeto_carritoDB, $objeto_carrito_detalleDB, $objeto_usuarioDB, $fecha_pagoDB, $id_pagoDB, $estadoDB, $observacionDB,$usuarioAltaDB)
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

        $publicacion = isset($data["publicacion"]) ? $data["publicacion"] : '';
        $publicacionDB = Database::escape($publicacion);
        $producto = isset($data["producto"]) ? $data["producto"] : '';
        $productoDB = Database::escape($producto);
        $precio = isset($data["precio"]) ? $data["precio"] : '';
        $precioDB = Database::escape($precio);
        $comision_porc = isset($data["comision_porc"]) ? $data["comision_porc"] : '';
        $comision_porcDB = Database::escape($comision_porc);
        $comision = isset($data["comision"]) ? $data["comision"] : '';
        $comisionDB = Database::escape($comision);
        $carrito_detalle = isset($data["carrito_detalle"]) ? $data["carrito_detalle"] : '';
        $carrito_detalleDB = Database::escape($carrito_detalle);
        $carrito = isset($data["carrito"]) ? $data["carrito"] : '';
        $carritoDB = Database::escape($carrito);
        $usuario = isset($data["usuario"]) ? $data["usuario"] : '';
        $usuarioDB = Database::escape($usuario);
        $fecha_transaccion = isset($data["fecha_transaccion"]) ? $data["fecha_transaccion"] : '';
        $fecha_transaccionDB = Database::escape($fecha_transaccion);
        $objeto_producto = isset($data["objeto_producto"]) ? $data["objeto_producto"] : '';
        $objeto_productoDB = Database::escape($objeto_producto);
        $objeto_publicacion = isset($data["objeto_publicacion"]) ? $data["objeto_publicacion"] : '';
        $objeto_publicacionDB = Database::escape($objeto_publicacion);
        $objeto_carrito = isset($data["objeto_carrito"]) ? $data["objeto_carrito"] : '';
        $objeto_carritoDB = Database::escape($objeto_carrito);
        $objeto_carrito_detalle = isset($data["objeto_carrito_detalle"]) ? $data["objeto_carrito_detalle"] : '';
        $objeto_carrito_detalleDB = Database::escape($objeto_carrito_detalle);
        $objeto_usuario = isset($data["objeto_usuario"]) ? $data["objeto_usuario"] : '';
        $objeto_usuarioDB = Database::escape($objeto_usuario);
        $fecha_pago = isset($data["fecha_pago"]) ? $data["fecha_pago"] : '';
        $fecha_pagoDB = Database::escape($fecha_pago);
        $id_pago = isset($data["id_pago"]) ? $data["id_pago"] : '';
        $id_pagoDB = Database::escape($id_pago);
        $estado = isset($data["estado"]) ? $data["estado"] : '';
        $estadoDB = Database::escape($estado);
        $observacion = isset($data["observacion"]) ? $data["observacion"] : '';
        $observacionDB = Database::escape($observacion);

        $sql = <<<SQL
			UPDATE
			    `metrica`
			SET
			    `usuario_editar` = $usuarioDB,
id_`publicacion` = $publicacionDB, id_`producto` = $productoDB, `precio` = $precioDB, `comision_porc` = $comision_porcDB, `comision` = $comisionDB, id_`carrito_detalle` = $carrito_detalleDB, id_`carrito` = $carritoDB, id_`usuario` = $usuarioDB, `fecha_transaccion` = $fecha_transaccionDB, `objeto_producto` = $objeto_productoDB, `objeto_publicacion` = $objeto_publicacionDB, `objeto_carrito` = $objeto_carritoDB, `objeto_carrito_detalle` = $objeto_carrito_detalleDB, `objeto_usuario` = $objeto_usuarioDB, `fecha_pago` = $fecha_pagoDB, `id_pago` = $id_pagoDB, `estado` = $estadoDB, `observacion` = $observacionDB
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

                
    public function getListPublicacion()
    {
        $sql = <<<sql
                    SELECT
                    `id`,
                    `nombre`
                FROM
                    `publicacion`
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
    public function getListProducto()
    {
        $sql = <<<sql
                    SELECT
                    `id`,
                    `nombre`
                FROM
                    `producto`
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
    public function getListCarrito()
    {
        $sql = <<<sql
                    SELECT
                    `id`,
                    `nombre`
                FROM
                    `carrito`
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
    public function getListUsuario()
    {
        $sql = <<<sql
                    SELECT
                    `id`,
                    `nombre`
                FROM
                    `usuario`
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


                public function existePublicacion($id_publicacion)
                {
                    $id_publicacion = isset($id_publicacion) ?   $id_publicacion : '';
                    $id_publicacionDB = Database::escape($id_publicacion);      
            
                    $sql = <<<SQL
                        SELECT *FROM publicacion
                        WHERE 
                            id = $id_publicacionDB AND
                            (eliminar = 0 OR eliminar is null);
SQL;            

                    $resultado=mysqli_query(Database::Connect(), $sql);
                    $row_cnt = mysqli_num_rows($resultado);
                    if ($row_cnt == 1){
                        $this->setStatus("OK");
                        return true;
                    }
                    
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo publicacion es incorrecto.");
                    return false;

                }

                public function existeProducto($id_producto)
                {
                    $id_producto = isset($id_producto) ?   $id_producto : '';
                    $id_productoDB = Database::escape($id_producto);      
            
                    $sql = <<<SQL
                        SELECT *FROM producto
                        WHERE 
                            id = $id_productoDB AND
                            (eliminar = 0 OR eliminar is null);
SQL;            

                    $resultado=mysqli_query(Database::Connect(), $sql);
                    $row_cnt = mysqli_num_rows($resultado);
                    if ($row_cnt == 1){
                        $this->setStatus("OK");
                        return true;
                    }
                    
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo producto es incorrecto.");
                    return false;

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

                public function existeCarrito($id_carrito)
                {
                    $id_carrito = isset($id_carrito) ?   $id_carrito : '';
                    $id_carritoDB = Database::escape($id_carrito);      
            
                    $sql = <<<SQL
                        SELECT *FROM carrito
                        WHERE 
                            id = $id_carritoDB AND
                            (eliminar = 0 OR eliminar is null);
SQL;            

                    $resultado=mysqli_query(Database::Connect(), $sql);
                    $row_cnt = mysqli_num_rows($resultado);
                    if ($row_cnt == 1){
                        $this->setStatus("OK");
                        return true;
                    }
                    
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo carrito es incorrecto.");
                    return false;

                }

                public function existeUsuario($id_usuario)
                {
                    $id_usuario = isset($id_usuario) ?   $id_usuario : '';
                    $id_usuarioDB = Database::escape($id_usuario);      
            
                    $sql = <<<SQL
                        SELECT *FROM usuario
                        WHERE 
                            id = $id_usuarioDB AND
                            (eliminar = 0 OR eliminar is null);
SQL;            

                    $resultado=mysqli_query(Database::Connect(), $sql);
                    $row_cnt = mysqli_num_rows($resultado);
                    if ($row_cnt == 1){
                        $this->setStatus("OK");
                        return true;
                    }
                    
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo usuario es incorrecto.");
                    return false;

                }


}

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

		$usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        
		$sql = <<<SQL
			INSERT INTO carrito (usuario_alta)  
			VALUES ($usuarioAltaDB)
SQL;

		if (!mysqli_query(Database::Connect(), $sql)) {
			$this->setStatus("ERROR");
            $this->setMsj("$sql" . Database::Connect()->error);
            return false;
		} else {
			$id = mysqli_insert_id(Database::Connect());
			$this->setMsj($id);
			$this->setStatus("OK");
			return true;
		}

		return false;
    }
    public function getIdCarrito3()
    {
        $usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $sql = <<<sql
		SELECT
		*
    	FROM
        `carrito`
    	WHERE
        `carrito`.usuario_alta = $usuarioAltaDB AND
        (`carrito`.eliminar IS NULL OR `carrito`.eliminar = 0) AND 
        estado = 2
        ORDER BY id desc
        LIMIT 1
sql;

//echo $sql;


        $resultado = Database::Connect()->query($sql);

        $id_carrito = 0;
        if ($rowEmp = mysqli_fetch_array($resultado)){
            $id_carrito = isset($rowEmp["id"]) ? $rowEmp["id"] : 0;
        }


        return $id_carrito;
    }
	public function getIdCarrito2()
    {
        $usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $sql = <<<sql
		SELECT
		*
    	FROM
        `carrito`
    	WHERE
        `carrito`.usuario_alta = $usuarioAltaDB AND
        (`carrito`.eliminar IS NULL OR `carrito`.eliminar = 0) AND 
        estado = 1
        ORDER BY id desc
        LIMIT 1
sql;
        $resultado = Database::Connect()->query($sql);

        $id_carrito = 0;
        if ($rowEmp = mysqli_fetch_array($resultado)){
            $id_carrito = isset($rowEmp["id"]) ? $rowEmp["id"] : 0;
        }


        return $id_carrito;
    }
    
	public function getIdCarrito()
    {
        $usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $sql = <<<sql
		SELECT
		*
    	FROM
        `carrito`
    	WHERE
        `carrito`.usuario_alta = $usuarioAltaDB AND
        (`carrito`.eliminar IS NULL OR `carrito`.eliminar = 0) AND 
        (estado is null OR estado <= 0 )
        ORDER BY id desc
        LIMIT 1
sql;
        $resultado = Database::Connect()->query($sql);

        $id_carrito = 0;
        if ($rowEmp = mysqli_fetch_array($resultado)){
            $id_carrito = isset($rowEmp["id"]) ? $rowEmp["id"] : 0;
        }


        return $id_carrito;
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


    public function getListCompras(array $data)	
    {
        $id = isset($data["id"]) ? $data["id"] : '';
        $where = '';
        $estado = isset($data["estado"]) ? $data["estado"] : '';
        $estadoDB = Database::escape($estado);

        $where = "carrito.estado = $estadoDB";

	$paginador = '';
        if ($id != ''){
            $idDB = Database::escape($id);
            $where .= " AND carrito.id = $idDB";
	}else{
		$offset = isset($_GET["cant"]) ? $_GET["cant"] : 0;
		if (!preg_match('/^[0-9]+$/i', $offset)) {
			$offset = 0;
		}
		$limit = 50;
                $paginador = " LIMIT $offset,$limit";

	}

        $usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);

        $sql = <<<sql
        SELECT
		envio_direccion,envio_numero,
            `envio_nombre_apellido`, `envio_codigo_postal`, `envio_ciudad_localidad`, `estado`, `email`, `notas`,producto.usuario_alta as vendedor,carrito.id as id_carrito, carrito_detalle.cantidad, carrito_detalle.precio, carrito_detalle.nombre_producto, carrito_detalle.id_producto, carrito_detalle.total,  min(producto_foto.id) as foto_id,sum(carrito_detalle.total) as carrito_total,sum(carrito_detalle.total) as carrito_subtotal
        FROM
                `carrito`
                LEFT JOIN
                carrito_detalle ON carrito.id = carrito_detalle.id_carrito AND
                (carrito_detalle.eliminar = 0 OR carrito_detalle.eliminar IS NULL)
                LEFT JOIN
                producto
        ON 
        `carrito_detalle`.id_producto = producto.id
                    LEFT JOIN
                producto_foto
            ON
                `carrito_detalle`.id_producto = producto_foto.id_producto AND (producto_foto.eliminar = 0 OR producto_foto.eliminar IS NULL)
                
                
                        WHERE
                (`carrito`.eliminar = 0 OR `carrito`.eliminar IS NULL) AND
                `carrito`.usuario_alta = $usuarioAltaDB                AND
                $where
                GROUP BY
		envio_direccion,envio_numero,
`envio_nombre_apellido`, `envio_codigo_postal`, `envio_ciudad_localidad`, `estado`, `email`, `notas`, producto.usuario_alta,carrito.id, carrito_detalle.cantidad, carrito_detalle.precio, carrito_detalle.nombre_producto, carrito_detalle.id_producto, carrito_detalle.total
     $paginador
sql;
//echo $sql;
        $resultado = Database::Connect()->query($sql);
        $list = array();
        $whereVendedor = '';
        while ($rowEmp = mysqli_fetch_array($resultado)) {
            $vendedor = isset($rowEmp["vendedor"]) ? $rowEmp["vendedor"] : 0;
            if ($vendedor > 0){
                if ($whereVendedor != ''){
                    $whereVendedor .= ',';
                }
                $whereVendedor .= $vendedor;
            }

            $nombreFile = isset($data["foto_id"]) ? $data["foto_id"] : '-';

            if (file_exists('/var/www/html/productos_img/'.$nombreFile.'.jpg')) {
                $rowEmp['existe_foto'] =1;
            }else{
                $rowEmp['existe_foto'] = 0;
            }
            $list[] = $rowEmp;
        }

        $list2 = array();


        if ($whereVendedor != ''){

            $sql = <<<sql
            SELECT
                idUsuario,
                nombre,
                apellido,
                email,
                ciudad,
                estado,
                codigoPostal,
                pais,
                telefono1Pais,
                telefono1Ciudad,
                telefono1,
                telefono1Tipo,
                telefono2Pais,
                telefono2Ciudad,
                telefono2,
                telefono2Tipo
            FROM
                usuario_seller
            where idUsuario IN ($whereVendedor)
sql;

    //echo $sql;
            $resultado = Database::Connect()->query($sql);
            
            while ($rowEmp = mysqli_fetch_array($resultado)) {
                $list2[] = $rowEmp;
            }
    
    
        }

        $this->setMsj($list2);


        return $list;
	}

    public function getListComprasAdmin(array $data)	
    {
        $id = isset($data["id"]) ? $data["id"] : '';
        $where = '';
        $estado = isset($data["estado"]) ? $data["estado"] : '';
        $estadoDB = Database::escape($estado);

        $where = " AND carrito.estado = $estadoDB";

	$paginador = '';
        if ($id != ''){
            $idDB = Database::escape($id);
            $where .= " AND carrito.id = $idDB";
	}else{
		$offset = isset($_GET["cant"]) ? $_GET["cant"] : 0;
		if (!preg_match('/^[0-9]+$/i', $offset)) {
			$offset = 0;
		}
		$limit = 50;
                $paginador = " LIMIT $offset,$limit";

	}


        $sql = <<<sql
        SELECT
		envio_direccion,envio_numero,
            `envio_nombre_apellido`, `envio_codigo_postal`, `envio_ciudad_localidad`, `estado`, `email`, `notas`,producto.usuario_alta as vendedor,carrito.id as id_carrito, carrito_detalle.cantidad, carrito_detalle.precio, carrito_detalle.nombre_producto, carrito_detalle.id_producto, carrito_detalle.total,  min(producto_foto.id) as foto_id,sum(carrito_detalle.total) as carrito_total,sum(carrito_detalle.total) as carrito_subtotal
        FROM
                `carrito`
                LEFT JOIN
                carrito_detalle ON carrito.id = carrito_detalle.id_carrito AND
                (carrito_detalle.eliminar = 0 OR carrito_detalle.eliminar IS NULL)
                LEFT JOIN
                producto
        ON 
        `carrito_detalle`.id_producto = producto.id
                    LEFT JOIN
                producto_foto
            ON
                `carrito_detalle`.id_producto = producto_foto.id_producto AND (producto_foto.eliminar = 0 OR producto_foto.eliminar IS NULL)
                
                
                        WHERE
                (`carrito`.eliminar = 0 OR `carrito`.eliminar IS NULL) 
                $where
                GROUP BY
		envio_direccion,envio_numero,
`envio_nombre_apellido`, `envio_codigo_postal`, `envio_ciudad_localidad`, `estado`, `email`, `notas`, producto.usuario_alta,carrito.id, carrito_detalle.cantidad, carrito_detalle.precio, carrito_detalle.nombre_producto, carrito_detalle.id_producto, carrito_detalle.total
$paginador
sql;
//echo $sql;
        $resultado = Database::Connect()->query($sql);
        $list = array();
        $whereVendedor = '';
        while ($rowEmp = mysqli_fetch_array($resultado)) {
            $vendedor = isset($rowEmp["vendedor"]) ? $rowEmp["vendedor"] : 0;
            if ($vendedor > 0){
                if ($whereVendedor != ''){
                    $whereVendedor .= ',';
                }
                $whereVendedor .= $vendedor;
            }

            $nombreFile = isset($data["foto_id"]) ? $data["foto_id"] : '-';

            if (file_exists('/var/www/html/productos_img/'.$nombreFile.'.jpg')) {
                $rowEmp['existe_foto'] =1;
            }else{
                $rowEmp['existe_foto'] = 0;
            }
            $list[] = $rowEmp;
        }

        $list2 = array();


        if ($whereVendedor != ''){

            $sql = <<<sql
            SELECT
                idUsuario,
                nombre,
                apellido,
                email,
                ciudad,
                estado,
                codigoPostal,
                pais,
                telefono1Pais,
                telefono1Ciudad,
                telefono1,
                telefono1Tipo,
                telefono2Pais,
                telefono2Ciudad,
                telefono2,
                telefono2Tipo
            FROM
                usuario_seller
            where idUsuario IN ($whereVendedor)
sql;

    //echo $sql;
            $resultado = Database::Connect()->query($sql);
            
            while ($rowEmp = mysqli_fetch_array($resultado)) {
                $list2[] = $rowEmp;
            }
    
    
        }

        $this->setMsj($list2);


        return $list;
	}


public function getListVentas(array $data)	
    {
        $id = isset($data["id"]) ? $data["id"] : '';
        $where = '';
        $estado = isset($data["estado"]) ? $data["estado"] : '';
        $estadoDB = Database::escape($estado);

        $where = "carrito.estado = $estadoDB";

	$paginador = '';
        if ($id != ''){
            $idDB = Database::escape($id);
            $where .= " AND carrito.id = $idDB";
	}else{
		$offset = isset($_GET["cant"]) ? $_GET["cant"] : 0;
		if (!preg_match('/^[0-9]+$/i', $offset)) {
			$offset = 0;
		}
		$limit = 50;
                $paginador = " LIMIT $offset,$limit";

	}

        $usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);

        $sql = <<<sql
        SELECT
		envio_direccion,envio_numero,
`envio_nombre_apellido`, `envio_codigo_postal`, `envio_ciudad_localidad`, `estado`, `email`, `notas`, carrito.usuario_alta as comprador,carrito.id as id_carrito, carrito_detalle.cantidad, carrito_detalle.precio, carrito_detalle.nombre_producto, carrito_detalle.id_producto, carrito_detalle.total,  min(producto_foto.id) as foto_id,sum(carrito_detalle.total) as carrito_total,sum(carrito_detalle.total) as carrito_subtotal
        FROM
                `carrito`
                LEFT JOIN
                carrito_detalle ON carrito.id = carrito_detalle.id_carrito AND
                (carrito_detalle.eliminar = 0 OR carrito_detalle.eliminar IS NULL)
                LEFT JOIN
                    producto
            ON 
            `carrito_detalle`.id_producto = producto.id
                LEFT JOIN
                producto_foto
            ON
                `carrito_detalle`.id_producto = producto_foto.id_producto AND (producto_foto.eliminar = 0 OR producto_foto.eliminar IS NULL)
                
                
            WHERE
                (`carrito`.eliminar = 0 OR `carrito`.eliminar IS NULL) AND
                producto.usuario_alta = $usuarioAltaDB                AND
                $where
                GROUP BY
		envio_direccion,envio_numero,
`envio_nombre_apellido`, `envio_codigo_postal`, `envio_ciudad_localidad`, `estado`, `email`, `notas`, carrito.usuario_alta,carrito.id, carrito_detalle.cantidad, carrito_detalle.precio, carrito_detalle.nombre_producto, carrito_detalle.id_producto, carrito_detalle.total
    LIMIT $offset,$limit
sql;
//echo $sql;
        $resultado = Database::Connect()->query($sql);
        $list = array();
        $whereComprador = '';
        while ($rowEmp = mysqli_fetch_array($resultado)) {
            $comprador = isset($rowEmp["comprador"]) ? $rowEmp["comprador"] : 0;
            if ($comprador > 0){
                if ($whereComprador != ''){
                    $whereComprador .= ',';
                }
                $whereComprador .= $comprador;
            }


            $nombreFile = isset($data["foto_id"]) ? $data["foto_id"] : '-';

            if (file_exists('/var/www/html/productos_img/'.$nombreFile.'.jpg')) {
                $rowEmp['existe_foto'] =1;
            }else{
                $rowEmp['existe_foto'] = 0;
            }
            $list[] = $rowEmp;
        }



        $list2 = array();


        if ($whereComprador != ''){

            $sql = <<<sql
            (
                SELECT
                    idUsuario,
                    nombre,
                    apellido,
                    email,
                    ciudad,
                    estado,
                    codigoPostal,
                    pais,
                    telefono1Pais,
                    telefono1Ciudad,
                    telefono1,
                    telefono1Tipo,
                    telefono2Pais,
                    telefono2Ciudad,
                    telefono2,
                    telefono2Tipo
                 FROM
                        usuario_picker
                WHERE idUsuario IN ($whereComprador)
            )
            UNION
            (
                 SELECT
                     idUsuario,
                     nombre,
                     apellido,
                     email,
                     ciudad,
                     estado,
                     codigoPostal,
                     pais,
                     telefono1Pais,
                     telefono1Ciudad,
                     telefono1,
                     telefono1Tipo,
                     telefono2Pais,
                     telefono2Ciudad,
                     telefono2,
                     telefono2Tipo
                 FROM
                     usuario_seller
                 WHERE idUsuario IN ($whereComprador)
            )
sql;

    //echo $sql;
            $resultado = Database::Connect()->query($sql);
            
            while ($rowEmp = mysqli_fetch_array($resultado)) {
                $list2[] = $rowEmp;
            }
    
    
        }

        $this->setMsj($list2);



        return $list;
	}

    public function getListVentasAdmin(array $data)	
    {
        $id = isset($data["id"]) ? $data["id"] : '';
        $where = '';
        $estado = isset($data["estado"]) ? $data["estado"] : '';
        $estadoDB = Database::escape($estado);

        $where = " AND carrito.estado = $estadoDB";

	$paginador = '';
        if ($id != ''){
            $idDB = Database::escape($id);
            $where .= " AND carrito.id = $idDB";
	}else{
		$offset = isset($_GET["cant"]) ? $_GET["cant"] : 0;
		if (!preg_match('/^[0-9]+$/i', $offset)) {
			$offset = 0;
		}
		$limit = 50;
                $paginador = " LIMIT $offset,$limit";

	}


        $sql = <<<sql
        SELECT
		envio_direccion,envio_numero,
`envio_nombre_apellido`, `envio_codigo_postal`, `envio_ciudad_localidad`, `estado`, `email`, `notas`, carrito.usuario_alta as comprador,carrito.id as id_carrito, carrito_detalle.cantidad, carrito_detalle.precio, carrito_detalle.nombre_producto, carrito_detalle.id_producto, carrito_detalle.total,  min(producto_foto.id) as foto_id,sum(carrito_detalle.total) as carrito_total,sum(carrito_detalle.total) as carrito_subtotal
        FROM
                `carrito`
                LEFT JOIN
                carrito_detalle ON carrito.id = carrito_detalle.id_carrito AND
                (carrito_detalle.eliminar = 0 OR carrito_detalle.eliminar IS NULL)
                LEFT JOIN
                    producto
            ON 
            `carrito_detalle`.id_producto = producto.id
                LEFT JOIN
                producto_foto
            ON
                `carrito_detalle`.id_producto = producto_foto.id_producto AND (producto_foto.eliminar = 0 OR producto_foto.eliminar IS NULL)
                
                
            WHERE
                (`carrito`.eliminar = 0 OR `carrito`.eliminar IS NULL) 
                $where
                GROUP BY
		envio_direccion,envio_numero,
`envio_nombre_apellido`, `envio_codigo_postal`, `envio_ciudad_localidad`, `estado`, `email`, `notas`, carrito.usuario_alta,carrito.id, carrito_detalle.cantidad, carrito_detalle.precio, carrito_detalle.nombre_producto, carrito_detalle.id_producto, carrito_detalle.total
           $paginador
sql;
//echo $sql;
        $resultado = Database::Connect()->query($sql);
        $list = array();
        $whereComprador = '';
        while ($rowEmp = mysqli_fetch_array($resultado)) {
            $comprador = isset($rowEmp["comprador"]) ? $rowEmp["comprador"] : 0;
            if ($comprador > 0){
                if ($whereComprador != ''){
                    $whereComprador .= ',';
                }
                $whereComprador .= $comprador;
            }


            $nombreFile = isset($data["foto_id"]) ? $data["foto_id"] : '-';

            if (file_exists('/var/www/html/productos_img/'.$nombreFile.'.jpg')) {
                $rowEmp['existe_foto'] =1;
            }else{
                $rowEmp['existe_foto'] = 0;
            }
            $list[] = $rowEmp;
        }



        $list2 = array();


        if ($whereComprador != ''){

            $sql = <<<sql
            (
                SELECT
                    idUsuario,
                    nombre,
                    apellido,
                    email,
                    ciudad,
                    estado,
                    codigoPostal,
                    pais,
                    telefono1Pais,
                    telefono1Ciudad,
                    telefono1,
                    telefono1Tipo,
                    telefono2Pais,
                    telefono2Ciudad,
                    telefono2,
                    telefono2Tipo
                 FROM
                        usuario_picker
                WHERE idUsuario IN ($whereComprador)
            )
            UNION
            (
                 SELECT
                     idUsuario,
                     nombre,
                     apellido,
                     email,
                     ciudad,
                     estado,
                     codigoPostal,
                     pais,
                     telefono1Pais,
                     telefono1Ciudad,
                     telefono1,
                     telefono1Tipo,
                     telefono2Pais,
                     telefono2Ciudad,
                     telefono2,
                     telefono2Tipo
                 FROM
                     usuario_seller
                 WHERE idUsuario IN ($whereComprador)
            )
sql;

    //echo $sql;
            $resultado = Database::Connect()->query($sql);
            
            while ($rowEmp = mysqli_fetch_array($resultado)) {
                $list2[] = $rowEmp;
            }
    
    
        }

        $this->setMsj($list2);



        return $list;
	}



	public function getListCarrito2()	
    {
        $usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $sql = <<<sql
        SELECT
                carrito_detalle.id_publicacion, carrito.id as id_carrito, carrito_detalle.cantidad, carrito_detalle.precio, carrito_detalle.nombre_producto, carrito_detalle.id_producto, carrito_detalle.total,  min(producto_foto.id) as foto,sum(carrito_detalle.total) as carrito_total,sum(carrito_detalle.total) as carrito_subtotal
        FROM    
        `carrito`
        LEFT JOIN
        carrito_detalle ON carrito.id = carrito_detalle.id_carrito AND
        (carrito_detalle.eliminar = 0 OR carrito_detalle.eliminar IS NULL)
            LEFT JOIN
        producto_foto
    ON
        `carrito_detalle`.id_producto = producto_foto.id_producto AND (producto_foto.eliminar = 0 OR producto_foto.eliminar IS NULL)
        
        
                WHERE
        (`carrito`.eliminar = 0 OR `carrito`.eliminar IS NULL) AND
        `carrito`.usuario_alta = $usuarioAltaDB                AND
        estado = 1 
        GROUP BY
        carrito_detalle.id_publicacion, carrito.id, carrito_detalle.cantidad, carrito_detalle.precio, carrito_detalle.nombre_producto, carrito_detalle.id_producto, carrito_detalle.total
        order by id_carrito desc limit 1
sql;
//echo $sql;

        $resultado = Database::Connect()->query($sql);
        $list = array();

        while ($rowEmp = mysqli_fetch_array($resultado)) {
            $list[] = $rowEmp;
        }
        return $list;
	}
	public function getListCarritoAll()
    {
        $usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $sql = <<<sql
SELECT
               carrito_detalle.id_publicacion, carrito.id as id_carrito, carrito_detalle.cantidad, carrito_detalle.precio, carrito_detalle.nombre_producto, carrito_detalle.id_producto, carrito_detalle.total,  min(producto_foto.id) as foto,sum(carrito_detalle.total) as carrito_total,sum(carrito_detalle.total) as carrito_subtotal
        FROM
        `carrito`
        LEFT JOIN
        carrito_detalle ON carrito.id = carrito_detalle.id_carrito AND
        (carrito_detalle.eliminar = 0 OR carrito_detalle.eliminar IS NULL)
            LEFT JOIN
        producto_foto
    ON
        `carrito_detalle`.id_producto = producto_foto.id_producto AND (producto_foto.eliminar = 0 OR producto_foto.eliminar IS NULL)
        
        
                WHERE
        (`carrito`.eliminar = 0 OR `carrito`.eliminar IS NULL) AND
        `carrito`.usuario_alta = $usuarioAltaDB                AND
        (estado is null OR estado != 4 )
        GROUP BY
        carrito_detalle.id_publicacion, carrito.id, carrito_detalle.cantidad, carrito_detalle.precio, carrito_detalle.nombre_producto, carrito_detalle.id_producto, carrito_detalle.total
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
        $usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
	$id = isset($_GET["id_carrito"]) ? $_GET["id_carrito"] : '';
        $idDB = Database::escape($id);
        $sql = <<<sql
SELECT
               carrito_detalle.id_publicacion, carrito.id as id_carrito, carrito_detalle.cantidad, carrito_detalle.precio, carrito_detalle.nombre_producto, carrito_detalle.id_producto, carrito_detalle.total,  min(producto_foto.id) as foto,sum(carrito_detalle.total) as carrito_total,sum(carrito_detalle.total) as carrito_subtotal
        FROM
        `carrito`
        LEFT JOIN
        carrito_detalle ON carrito.id = carrito_detalle.id_carrito AND
        (carrito_detalle.eliminar = 0 OR carrito_detalle.eliminar IS NULL)
            LEFT JOIN
        producto_foto
    ON
        `carrito_detalle`.id_producto = producto_foto.id_producto AND (producto_foto.eliminar = 0 OR producto_foto.eliminar IS NULL)
        
        
                WHERE
        (`carrito`.eliminar = 0 OR `carrito`.eliminar IS NULL) AND
        `carrito`.usuario_alta = $usuarioAltaDB                AND
        (estado is null OR estado !=4  )
         AND carrito.id = $idDB
        GROUP BY
        carrito_detalle.id_publicacion, carrito.id, carrito_detalle.cantidad, carrito_detalle.precio, carrito_detalle.nombre_producto, carrito_detalle.id_producto, carrito_detalle.total
sql;

        $resultado = Database::Connect()->query($sql);
        $list = array();

        while ($rowEmp = mysqli_fetch_array($resultado)) {
            $list[] = $rowEmp;
        }
        return $list;
	}
                public function eliminarDetalle(array $data)
                {
                    $id = isset($data["id_carrito"]) ? $data["id_carrito"] : '';
                    $idDB = Database::escape($id);
                    $id_producto = isset($data["id_producto"]) ? $data["id_producto"] : '';
                    $idProductoDB = Database::escape($id_producto);
                    $usuario = $GLOBALS['sesionG']['idUsuario'];
                    $usuarioDB = Database::escape($usuario);
                    $sql = <<<SQL
            UPDATE
                `carrito_detalle`
            SET
                `usuario_editar` = $usuarioDB,
                `eliminar` = 1
            WHERE
            `id_carrito` = $idDB AND
            `id_producto` = $idProductoDB AND
            `usuario_alta` = $usuarioDB
            SQL;
            
                    if (!mysqli_query(Database::Connect(), $sql)) {
                        $this->setStatus("ERROR");
                        $this->setMsj("$sql" . Database::Connect()->error);
                        return false;

                        
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

                    $id_producto = isset($data["id_producto"]) ? $data["id_producto"] : '';
                    $idProductoDB = Database::escape($id_producto);

                    $id_publicacion = isset($data["id_publicacion"]) ? $data["id_publicacion"] : '';
                    $idPublicacionDB = Database::escape($id_publicacion);

                    
                    $id_usuario_publicador = isset($data["id_usuario_publicador"]) ? $data["id_usuario_publicador"] : '';
                    $id_usuario_publicadorDB = Database::escape($id_usuario_publicador);

                    $usuario = $GLOBALS['sesionG']['idUsuario'];
                    $usuarioDB = Database::escape($usuario);

                    $nombre_producto = isset($data["nombre_producto"]) ?  $data["nombre_producto"] : '';
                    $nombreProductoDB = Database::escape($nombre_producto);      

                    $precio = isset($data["precio"]) ?  $data["precio"] : '';
                    $precioDB = Database::escape($precio);      


                    $cantidad = isset($data["cantidad"]) ?  $data["cantidad"] : '';
                    $cantidadDB = Database::escape($cantidad);      


                    $total = isset($data["total"]) ?  $data["total"] : '';
                    $totalDB = Database::escape($total);      

                    $id_vendedor = isset($data["id_vendedor"]) ?  $data["id_vendedor"] : '';
                    $id_vendedorDB = Database::escape($id_vendedor);      

                    $comision_porcentaje_tienda = isset($data["comision_porcentaje_tienda"]) ?  $data["comision_porcentaje_tienda"] : '';
                    $comision_porcentaje_tiendaBD = Database::escape($comision_porcentaje_tienda);      

                    $comision_porcentaje_taggeador = isset($data["comision_porcentaje_taggeador"]) ?  $data["comision_porcentaje_taggeador"] : '';
                    $comision_porcentaje_taggeadorBD = Database::escape($comision_porcentaje_taggeador);      

                    $total_tienda = isset($data["total_tienda"]) ?  $data["total_tienda"] : '';
                    $total_tiendaBD = Database::escape($total_tienda);      

                    $total_vendedor = isset($data["total_vendedor"]) ?  $data["total_vendedor"] : '';
                    $total_vendedorBD = Database::escape($total_vendedor);      

                    $total_taggeador = isset($data["total_taggeador"]) ?  $data["total_taggeador"] : '';
                    $total_taggeadorBD = Database::escape($total_taggeador);      

                    $sql = <<<SQL
                        INSERT INTO carrito_detalle (id_carrito,id_producto,usuario_alta,cantidad,precio,total,nombre_producto,id_publicacion,id_usuario_publicador,id_vendedor, comision_porcentaje_tienda, comision_porcentaje_taggeador, total_tienda, total_vendedor, total_taggeador) 
                        VALUES ($id_carritoDB,$idProductoDB,$usuarioDB,$cantidadDB,$precioDB,$totalDB,$nombreProductoDB,$idPublicacionDB,$id_usuario_publicadorDB,$id_vendedorDB,$comision_porcentaje_tiendaBD,$comision_porcentaje_taggeadorBD,$total_tiendaBD,$total_vendedorBD,$total_taggeadorBD)
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
                public function cambiarEstadoCarrito3(array $data)
                {
                    $id = isset($data["id_carrito"]) ? $data["id_carrito"] : '';
                    $idDB = Database::escape($id);
                    $usuario = $GLOBALS['sesionG']['idUsuario'];
                    $usuarioDB = Database::escape($usuario);
                    $estado = isset($data["estado"]) ? $data["estado"] : '';
                    $estadoDB = Database::escape($estado);
            
            
                    $sql = <<<SQL
                        UPDATE
                            `carrito`
                        SET
                            `usuario_editar` = $usuarioDB,`estado` = $estadoDB
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
                public function cambiarEstadoCarrito2(array $data)
    {
        $id = isset($data["id_carrito"]) ? $data["id_carrito"] : '';
        $idDB = Database::escape($id);
		$usuario = $GLOBALS['sesionG']['idUsuario'];
        $usuarioDB = Database::escape($usuario);

        $envio_nombre_apellido = isset($data["envio_nombre_apellido"]) ? $data["envio_nombre_apellido"] : '';
        $envio_nombre_apellidoDB = Database::escape($envio_nombre_apellido);
        $envio_codigo_postal = isset($data["envio_codigo_postal"]) ? $data["envio_codigo_postal"] : '';
        $envio_codigo_postalDB = Database::escape($envio_codigo_postal);
        $envio_ciudad_localidad = isset($data["envio_ciudad_localidad"]) ? $data["envio_ciudad_localidad"] : '';
        $envio_ciudad_localidadDB = Database::escape($envio_ciudad_localidad);

        $envio_numero = isset($data["envio_numero"]) ? $data["envio_numero"] : '';
        $envio_numeroDB = Database::escape($envio_numero);

        
        $envio_direccion = isset($data["envio_direccion"]) ? $data["envio_direccion"] : '';
        $envio_direccionDB = Database::escape($envio_direccion);
 
 

        $email = isset($data["email"]) ? $data["email"] : '';
        $emailDB = Database::escape($email);
        $notas = isset($data["notas"]) ? $data["notas"] : '';
        $notasDB = Database::escape($notas);
        $estado = isset($data["estado"]) ? $data["estado"] : '';
        $estadoDB = Database::escape($estado);

        $sql = <<<SQL
			UPDATE
			    `carrito`
			SET
			    `usuario_editar` = $usuarioDB,`estado` = $estadoDB,
`envio_nombre_apellido` = $envio_nombre_apellidoDB, 
`envio_codigo_postal` = $envio_codigo_postalDB, `envio_ciudad_localidad` = $envio_ciudad_localidadDB, 
`envio_numero` = $envio_numeroDB, `envio_direccion` = $envio_direccionDB, 
`email` = $emailDB, `notas` = $notasDB
WHERE
`id` = $idDB AND
`usuario_alta` = $usuarioDB
SQL;

        if (!mysqli_query(Database::Connect(), $sql)) {
            $this->setStatus("ERROR");
            $this->setMsj("$sql" . Database::Connect()->error);
        } else {
            $sql = <<<SQL
			UPDATE
			    `usuario`
			SET
			    `usrUpdate` = $usuarioDB, 
                `envio_codigo_postal` = $envio_codigo_postalDB, `envio_ciudad_localidad` = $envio_ciudad_localidadDB, 
                `envio_numero` = $envio_numeroDB, `envio_direccion` = $envio_direccionDB,
                `envio_mail` = $emailDB, `envio_notas` = $notasDB, `envio_nombre_apellido` = $envio_nombre_apellidoDB
            WHERE
                `id` = $usuarioDB
SQL;

            if (!mysqli_query(Database::Connect(), $sql)) {
                $this->setStatus("ERROR");
                $this->setMsj("$sql" . Database::Connect()->error);
            } else {
                $this->setStatus("OK");
                return true;
            }
        }

        return false;






    }
                public function cambiarEstadoCarrito(array $data)
    {
        $id = isset($data["id_carrito"]) ? $data["id_carrito"] : '';
        $idDB = Database::escape($id);
        $estado = isset($data["estado"]) ? $data["estado"] : '';
        $estadoDB = Database::escape($estado);
        $usuario = $GLOBALS['sesionG']['idUsuario'];
        $usuarioDB = Database::escape($usuario);

        $sql = <<<SQL
UPDATE
    `carrito`
SET
    `usuario_editar` = $usuarioDB,
    `estado` = $estadoDB
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

    public function cambiarEstadoCarritoMayor3(array $data)
    {
        $id = isset($data["id_carrito"]) ? $data["id_carrito"] : '';
        $idDB = Database::escape($id);
        $estado = isset($data["estado"]) ? $data["estado"] : '';
        $estadoDB = Database::escape($estado);
        $usuario = $GLOBALS['sesionG']['idUsuario'];
        $usuarioDB = Database::escape($usuario);

        $sql = <<<SQL
UPDATE
    `carrito`
SET
    `usuario_editar` = $usuarioDB,
    `estado` = $estadoDB
WHERE
`id` = $idDB AND
`usuario_alta` = $usuarioDB AND
`estado` >=  2
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




    
	public function getCarrito($data)	
    {
        $usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $id_carrito = isset($data["id_carrito"]) ? $data["id_carrito"] : 0;
        $idCarritoBD = Database::escape($id_carrito);




        $sql = <<<sql
SELECT
    envio_direccion,
    envio_numero,
    `envio_nombre_apellido`,
    `envio_codigo_postal`,
    `envio_ciudad_localidad`,
    `estado`,
    `email`,
    `notas`,
    producto.usuario_alta AS vendedor,
    carrito.id AS id_carrito,
    carrito_detalle.cantidad,
    carrito_detalle.precio,
    carrito_detalle.nombre_producto,
    carrito_detalle.id_producto,
    carrito_detalle.total,
    MIN(producto_foto.id) AS foto_id,
    SUM(carrito_detalle.total) AS carrito_total,
    SUM(carrito_detalle.total) AS carrito_subtotal
FROM
    `carrito`
LEFT JOIN
    carrito_detalle
ON
    carrito.id = carrito_detalle.id_carrito AND(
        carrito_detalle.eliminar = 0 OR carrito_detalle.eliminar IS NULL
    )
LEFT JOIN
    producto
ON
    `carrito_detalle`.id_producto = producto.id
LEFT JOIN
    producto_foto
ON
    `carrito_detalle`.id_producto = producto_foto.id_producto AND(
        producto_foto.eliminar = 0 OR producto_foto.eliminar IS NULL
    )
WHERE
    (
        `carrito`.eliminar = 0 OR `carrito`.eliminar IS NULL
    ) AND `carrito`.usuario_alta = $usuarioAltaDB AND `carrito`.id = $idCarritoBD 
GROUP BY
    envio_direccion,
    envio_numero,
    `envio_nombre_apellido`,
    `envio_codigo_postal`,
    `envio_ciudad_localidad`,
    `estado`,
    `email`,
    `notas`,
    producto.usuario_alta,
    carrito.id,
    carrito_detalle.cantidad,
    carrito_detalle.precio,
    carrito_detalle.nombre_producto,
    carrito_detalle.id_producto,
    carrito_detalle.total
sql;
//echo $sql;

        $resultado = Database::Connect()->query($sql);
        $list = array();

        while ($rowEmp = mysqli_fetch_array($resultado)) {
            $list[] = $rowEmp;
        }
        return $list;
	}

	public function getVendedorByIdCarrito($id_carrito)	
    {
        $id_carrito = isset($data["id_carrito"]) ? $data["id_carrito"] : 0;
        $idCarritoBD = Database::escape($id_carrito);


        $sql = <<<sql
SELECT
	    producto.usuario_alta AS vendedor
FROM
    `carrito`
LEFT JOIN
    carrito_detalle
ON
    carrito.id = carrito_detalle.id_carrito AND(
        carrito_detalle.eliminar = 0 OR carrito_detalle.eliminar IS NULL
    )
LEFT JOIN
    producto
ON
    `carrito_detalle`.id_producto = producto.id
WHERE
    (
        `carrito`.eliminar = 0 OR `carrito`.eliminar IS NULL
    ) AND `carrito`.id = $idCarritoBD 
LIMIT 1
sql;
//echo $sql;

        $resultado = Database::Connect()->query($sql);

	$vendedor = 0;
        while ($rowEmp = mysqli_fetch_array($resultado)) {
            $vendedor = $rowEmp["vendedor"];
        }
        return $vendedor;
	}

}

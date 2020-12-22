<?php
//include_once("../util/database.php");
class  ProductoDao
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


    public function altaProducto(array $data)
    {

        $titulo = isset($data["titulo"]) ? $data["titulo"] : '';
        $tituloDB = Database::escape($titulo);
        //$categoria = isset($data["categoria"]) ? $data["categoria"] : '';
        //$categoriaDB = Database::escape($categoria);
        $rubro = isset($data["rubro"]) ? $data["rubro"] : '';
        $rubroDB = Database::escape($rubro);
        $marca = isset($data["marca"]) ? $data["marca"] : '';
        $marcaDB = Database::escape($marca);
        $precio = isset($data["precio"]) ? $data["precio"] : '';
        $precioDB = Database::escape($precio);
        $stock = isset($data["stock"]) ? $data["stock"] : '';
        $stockDB = Database::escape($stock);
        $envio = isset($data["envio"]) ? $data["envio"] : '';
        $envioDB = Database::escape($envio);
        $garantia = isset($data["garantia"]) ? $data["garantia"] : '';
        $garantiaDB = Database::escape($garantia);
        $descr_producto = isset($data["descr_producto"]) ? $data["descr_producto"] : '';
        $descr_productoDB = Database::escape($descr_producto);
        $color = isset($data["color"]) ? $data["color"] : '';
        $colorDB = Database::escape($color);
        $usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $sql = <<<SQL
			INSERT INTO producto (titulo, id_rubro, marca, precio, envio, garantia, descr_producto, color,usuario_alta,stock)  
			VALUES ($tituloDB, $rubroDB, $marcaDB, $precioDB, $envioDB, $garantiaDB, $descr_productoDB, $colorDB,$usuarioAltaDB,$stockDB)
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


    public function editarProducto(array $data)
    {
        $id = isset($data["id"]) ? $data["id"] : '';
        $idDB = Database::escape($id);
        $usuario = $GLOBALS['sesionG']['idUsuario'];
        $usuarioDB = Database::escape($usuario);

        $titulo = isset($data["titulo"]) ? $data["titulo"] : '';
        $tituloDB = Database::escape($titulo);
        //$categoria = isset($data["categoria"]) ? $data["categoria"] : '';
        //$categoriaDB = Database::escape($categoria);
        $rubro = isset($data["rubro"]) ? $data["rubro"] : '';
        $rubroDB = Database::escape($rubro);
        $marca = isset($data["marca"]) ? $data["marca"] : '';
        $marcaDB = Database::escape($marca);
        $precio = isset($data["precio"]) ? $data["precio"] : '';
        $precioDB = Database::escape($precio);
        $stock = isset($data["stock"]) ? $data["stock"] : '';
        $stockDB = Database::escape($stock);
        $envio = isset($data["envio"]) ? $data["envio"] : '';
        $envioDB = Database::escape($envio);
        $garantia = isset($data["garantia"]) ? $data["garantia"] : '';
        $garantiaDB = Database::escape($garantia);
        $descr_producto = isset($data["descr_producto"]) ? $data["descr_producto"] : '';
        $descr_productoDB = Database::escape($descr_producto);
        $color = isset($data["color"]) ? $data["color"] : '';
        $colorDB = Database::escape($color);


        $sql = <<<SQL
UPDATE
    `producto`
SET
    `titulo` = $tituloDB,
    `id_rubro` = $rubroDB,
    `marca` = $marcaDB,
    `precio` = $precioDB,
    `envio` = $envioDB,
    `garantia` = $garantiaDB,
    `descr_producto` = $descr_productoDB,
    `color` = $colorDB,
    `usuario_editar` = $usuarioDB,
    `stock` = $stockDB
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

    public function eliminarProducto(array $data)
    {
        $id = isset($data["id"]) ? $data["id"] : '';
        $idDB = Database::escape($id);
        $usuario = $GLOBALS['sesionG']['idUsuario'];
        $usuarioDB = Database::escape($usuario);

        $sql = <<<SQL
UPDATE
    `producto`
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

    public function eliminarProductoFoto(array $data)
    {
        $id = isset($data["id"]) ? $data["id"] : '';
        $idDB = Database::escape($id);
        $usuario = $GLOBALS['sesionG']['idUsuario'];
        $usuarioDB = Database::escape($usuario);
        $sql = <<<SQL
UPDATE
    `producto_foto`
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

  
    public function existeCategoria($id_categoria)
    {
        $id_categoria = isset($id_categoria) ?   $id_categoria : '';
        $id_categoriaDB = Database::escape($id_categoria);

        $sql = <<<SQL
                        SELECT *FROM categoria
                        WHERE 
                            id = $id_categoriaDB AND
                            (eliminar = 0 OR eliminar is null);
SQL;

        $resultado = mysqli_query(Database::Connect(), $sql);
        $row_cnt = mysqli_num_rows($resultado);
        if ($row_cnt == 1) {
            $this->setStatus("OK");
            return true;
        }

        $this->setStatus("ERROR");
        $this->setMsj("El campo categoria es incorrecto.");
        return false;
    }

    public function existeRubro($id_rubro)
    {
        $id_rubro = isset($id_rubro) ?   $id_rubro : '';
        $id_rubroDB = Database::escape($id_rubro);

        $sql = <<<SQL
                        SELECT *FROM rubro
                        WHERE 
                            id = $id_rubroDB AND
                            (eliminar = 0 OR eliminar is null);
SQL;

        $resultado = mysqli_query(Database::Connect(), $sql);
        $row_cnt = mysqli_num_rows($resultado);
        if ($row_cnt == 1) {
            $this->setStatus("OK");
            return true;
        }

        $this->setStatus("ERROR");
        $this->setMsj("El campo rubro es incorrecto.");
        return false;
    }

    public function altaFoto(array $data)
    {
        $id_producto = isset($data["id_producto"]) ?   $data["id_producto"] : '';
        $id_productoDB = Database::escape($id_producto);

        $foto = isset($data["foto"]) ?  $data["foto"] : '';
        $fotoDB = Database::escape("/productos_img/");

        $usuario = $GLOBALS['sesionG']['idUsuario'];
        $usuarioDB = Database::escape($usuario);

        $sql = <<<SQL
                        INSERT INTO producto_foto (id_producto,foto,usuario_alta) 
                        VALUES ($id_productoDB,$fotoDB,$usuarioDB)
SQL;

        if (!mysqli_query(Database::Connect(), $sql)) {
            $this->setStatus("ERROR");
            $this->setMsj(Database::Connect()->error);
        } else {
            $id = mysqli_insert_id(Database::Connect());
            $fp = fopen("/var/www/html/productos_img/$id", 'w');
            fwrite($fp, $foto);
            fclose($fp);
            $this->setMsj($id);
            $this->setStatus("OK");
            return true;
        }

        return false;
    }
    
    public function existeEnvio($id_envio)
    {
        $id_envio = isset($id_envio) ?   $id_envio : '';
        $id_envioDB = Database::escape($id_envio);

        $sql = <<<SQL
                        SELECT *FROM envio
                        WHERE 
                            id = $id_envioDB AND
                            (eliminar = 0 OR eliminar is null);
SQL;

        $resultado = mysqli_query(Database::Connect(), $sql);
        $row_cnt = mysqli_num_rows($resultado);
        if ($row_cnt == 1) {
            $this->setStatus("OK");
            return true;
        }

        $this->setStatus("ERROR");
        $this->setMsj("El campo envio es incorrecto.");
        return false;
    }

    public function existeGarantia($id_garantia)
    {
        $id_garantia = isset($id_garantia) ?   $id_garantia : '';
        $id_garantiaDB = Database::escape($id_garantia);

        $sql = <<<SQL
                        SELECT *FROM garantia
                        WHERE 
                            id = $id_garantiaDB AND
                            (eliminar = 0 OR eliminar is null);
SQL;

        $resultado = mysqli_query(Database::Connect(), $sql);
        $row_cnt = mysqli_num_rows($resultado);
        if ($row_cnt == 1) {
            $this->setStatus("OK");
            return true;
        }

        $this->setStatus("ERROR");
        $this->setMsj("El campo garantia es incorrecto.");
        return false;
    }



    public function getListCategoria()
    {
        $sql = <<<sql
                    SELECT
                    `id`,
                    `nombre`
                FROM
                    `categoria`
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
    public function getListRubro()
    {
        $sql = <<<sql
        SELECT
            `id`,
            `nombre`,
            `id_categoria`
        FROM
            `rubro`
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
        $usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $sql = <<<sql
        SELECT
        `producto`.`id`,
        `producto`.`titulo`,
        `producto`.`id_rubro`,
        `producto`.`marca`,
        `producto`.`precio`,
        `producto`.`envio`,
        `producto`.`garantia`,
        `producto`.`descr_producto`,
        `producto`.`color`,
        `producto`.`stock`,
        min(producto_foto.id) as foto
    FROM
        `producto`
    LEFT JOIN
        producto_foto
    ON
        `producto`.id = producto_foto.id_producto AND (producto_foto.eliminar = 0 OR producto_foto.eliminar IS NULL)
    WHERE
        (`producto`.eliminar = 0 OR `producto`.eliminar IS NULL) AND `producto`.usuario_alta = $usuarioAltaDB
    group by         `producto`.`id`,
    `producto`.`titulo`,
    `producto`.`id_rubro`,
    `producto`.`marca`,
    `producto`.`precio`,
    `producto`.`envio`,
    `producto`.`garantia`,
    `producto`.`descr_producto`,
    `producto`.`color`,
    `producto`.`stock`
sql;
        $resultado = Database::Connect()->query($sql);
        $list = array();

        while ($rowEmp = mysqli_fetch_array($resultado)) {
            $list[] = $rowEmp;
        }
        return $list;
    }

    public function searchProductosPicker()
    {
        
        $input = isset($data["input"]) ? $data["input"] : '';
        $inputDB = Database::escape("%$input%");

        $sql = <<<sql
        SELECT
        `producto`.`id`,
        `producto`.`titulo`,
        `producto`.`id_rubro`,
        `producto`.`marca`,
        `producto`.`precio`,
        `producto`.`envio`,
        `producto`.`garantia`,
        `producto`.`descr_producto`,
        `producto`.`color`,
        `producto`.`stock`,
        min(producto_foto.id) as foto
    FROM
        `producto`
    LEFT JOIN
        producto_foto
    ON
        `producto`.id = producto_foto.id_producto AND (producto_foto.eliminar = 0 OR producto_foto.eliminar IS NULL)
    WHERE
        (`producto`.eliminar = 0 OR `producto`.eliminar IS NULL) 
        AND titulo COLLATE UTF8_GENERAL_CI LIKE $inputDB
    group by         `producto`.`id`,
    `producto`.`titulo`,
    `producto`.`id_rubro`,
    `producto`.`marca`,
    `producto`.`precio`,
    `producto`.`envio`,
    `producto`.`garantia`,
    `producto`.`descr_producto`,
    `producto`.`color`,
    `producto`.`stock`
sql;

        if (!mysqli_query(Database::Connect(), $sql)) {
            $this->setStatus("ERROR");
            $this->setMsj("$sql" . Database::Connect()->error);
        } else {
            $resultado = Database::Connect()->query($sql);
            $list = array();
    
    
            while ($rowEmp = mysqli_fetch_array($resultado)) {
                $list[] = $rowEmp;
            }
    
            $this->setStatus("OK");
            $this->setMsj($list);

            return true;
        }

        return false;
    }



    public function searchProductosSeller()
    {
        $usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        
        $input = isset($data["input"]) ? $data["input"] : '';
        $inputDB = Database::escape("%$input%");

        $sql = <<<sql
        SELECT
        `producto`.`id`,
        `producto`.`titulo`,
        `producto`.`id_rubro`,
        `producto`.`marca`,
        `producto`.`precio`,
        `producto`.`envio`,
        `producto`.`garantia`,
        `producto`.`descr_producto`,
        `producto`.`color`,
        `producto`.`stock`,
        min(producto_foto.id) as foto
    FROM
        `producto`
    LEFT JOIN
        producto_foto
    ON
        `producto`.id = producto_foto.id_producto AND (producto_foto.eliminar = 0 OR producto_foto.eliminar IS NULL)
    WHERE
        (`producto`.eliminar = 0 OR `producto`.eliminar IS NULL) AND `producto`.usuario_alta = $usuarioAltaDB
        AND titulo COLLATE UTF8_GENERAL_CI LIKE $inputDB
    group by         `producto`.`id`,
    `producto`.`titulo`,
    `producto`.`id_rubro`,
    `producto`.`marca`,
    `producto`.`precio`,
    `producto`.`envio`,
    `producto`.`garantia`,
    `producto`.`descr_producto`,
    `producto`.`color`,
    `producto`.`stock`
sql;

        if (!mysqli_query(Database::Connect(), $sql)) {
            $this->setStatus("ERROR");
            $this->setMsj("$sql" . Database::Connect()->error);
        } else {
            $resultado = Database::Connect()->query($sql);
            $list = array();
    
    
            while ($rowEmp = mysqli_fetch_array($resultado)) {
                $list[] = $rowEmp;
            }
    
            $this->setStatus("OK");
            $this->setMsj($list);

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
                        SELECT * FROM producto
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
        $this->setMsj("EL producto que quiere editar no existe o no tiene permisos para editarlo.");
        return false;
    }

    public function getProducto($id)
    {
        $usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $id = isset($id) ?   $id : '';
        $idDB = Database::escape($id);
        $sql = <<<sql
        SELECT
        `producto`.`id`,
        `producto`.`titulo`,
        `producto`.`id_rubro`,
        `producto`.`marca`,
        `producto`.`precio`,
        `producto`.`envio`,
        `producto`.`garantia`,
        `producto`.`descr_producto`,
        `producto`.`color`,
        `producto`.`stock`,
       GROUP_CONCAT(producto_foto.id) as foto

    FROM
        `producto`
    LEFT JOIN
        producto_foto
    ON
        `producto`.id = producto_foto.id_producto AND (producto_foto.eliminar = 0 OR producto_foto.eliminar IS NULL)
    WHERE
	producto.id=$idDB AND 
        (`producto`.eliminar = 0 OR `producto`.eliminar IS NULL) AND `producto`.usuario_alta = $usuarioAltaDB
    group by         `producto`.`id`,
    `producto`.`titulo`,
    `producto`.`id_rubro`,
    `producto`.`marca`,
    `producto`.`precio`,
    `producto`.`envio`,
    `producto`.`garantia`,
    `producto`.`descr_producto`,
    `producto`.`color`,
    `producto`.`stock`
sql;
        $resultado = Database::Connect()->query($sql);
        $row_cnt = mysqli_num_rows($resultado);
        $list = array();
        if ($row_cnt <= 0) {
            $this->setStatus("ERROR");
            $this->setMsj("No se encontró el producto o no tiene permisos para editar.");
            return $list;
        }


        while ($rowEmp = mysqli_fetch_array($resultado)) {
            $list[] = $rowEmp;
        }
        $this->setStatus("ok");
        return $list;
    }
    public function getFoto($id)
    {
        $usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $id = isset($id) ?   $id : '';
        $idDB = Database::escape($id);
        $sql = <<<sql
        SELECT
         producto_foto.foto
    FROM
        producto_foto
    WHERE
        producto_foto.id=$idDB AND (producto_foto.eliminar = 0 OR producto_foto.eliminar IS NULL)
	AND producto_foto.usuario_alta = $usuarioAltaDB
sql;
        $resultado = Database::Connect()->query($sql);

	$foto = '';
        while ($rowEmp = mysqli_fetch_array($resultado)) {
            $foto = $rowEmp['foto'];
        }
        return $foto;
    }

}

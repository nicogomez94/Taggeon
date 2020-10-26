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
        $categoria = isset($data["categoria"]) ? $data["categoria"] : '';
        $categoriaDB = Database::escape($categoria);
        $rubro = isset($data["rubro"]) ? $data["rubro"] : '';
        $rubroDB = Database::escape($rubro);
        $marca = isset($data["marca"]) ? $data["marca"] : '';
        $marcaDB = Database::escape($marca);
        $precio = isset($data["precio"]) ? $data["precio"] : '';
        $precioDB = Database::escape($precio);
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
			INSERT INTO producto (titulo, id_rubro, marca, precio, envio, garantia, descr_producto, color,usuario_alta)  
			VALUES ($tituloDB, $rubroDB, $marcaDB, $precioDB, $envioDB, $garantiaDB, $descr_productoDB, $colorDB,$usuarioAltaDB)
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

        $titulo = isset($data["titulo"]) ? $data["titulo"] : '';
        $tituloDB = Database::escape($titulo);
        $categoria = isset($data["categoria"]) ? $data["categoria"] : '';
        $categoriaDB = Database::escape($categoria);
        $rubro = isset($data["rubro"]) ? $data["rubro"] : '';
        $rubroDB = Database::escape($rubro);
        $marca = isset($data["marca"]) ? $data["marca"] : '';
        $marcaDB = Database::escape($marca);
        $precio = isset($data["precio"]) ? $data["precio"] : '';
        $precioDB = Database::escape($precio);
        $envio = isset($data["envio"]) ? $data["envio"] : '';
        $envioDB = Database::escape($envio);
        $garantia = isset($data["garantia"]) ? $data["garantia"] : '';
        $garantiaDB = Database::escape($garantia);
        $descr_producto = isset($data["descr_producto"]) ? $data["descr_producto"] : '';
        $descr_productoDB = Database::escape($descr_producto);
        $color = isset($data["color"]) ? $data["color"] : '';
        $colorDB = Database::escape($color);

        $sql = <<<SQL

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
        $fotoDB = Database::escape($foto);

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
        `id`,
        `titulo`,
        `id_rubro`,
        `marca`,
        `precio`,
        `envio`,
        `garantia`,
        `descr_producto`,
        `color`,
        `usuario_alta`,
        `usuario_editar`,
        `fecha_alta`,
        `fecha_update`,
        `eliminar`
    FROM
        `producto`
    WHERE
         eliminar=0 OR eliminar is null AND usuario_alta= $usuarioAltaDB

sql;

        $resultado = Database::Connect()->query($sql);
        $list = array();

        while ($rowEmp = mysqli_fetch_array($resultado)) {
            $list[] = $rowEmp;
        }
        return $list;
    }
}

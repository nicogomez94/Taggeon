<?php
//include_once("../util/database.php");
class  ComentarioproductoDao
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


	public function altaComentarioproducto(array $data)
	{

        $comentario = isset($data["comentario"]) ? $data["comentario"] : '';
        $comentarioDB = Database::escape($comentario);
        $producto = isset($data["producto"]) ? $data["producto"] : '';
        $productoDB = Database::escape($producto);
		$usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        
		$sql = <<<SQL
			INSERT INTO comentarioproducto (comentario, id_producto,usuario_alta)  
			VALUES ($comentarioDB, $productoDB,$usuarioAltaDB)
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


	public function editarComentarioproducto(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		$idDB = Database::escape($id);
		$usuario = $GLOBALS['sesionG']['idUsuario'];
        $usuarioDB = Database::escape($usuario);

        $comentario = isset($data["comentario"]) ? $data["comentario"] : '';
        $comentarioDB = Database::escape($comentario);
        $producto = isset($data["producto"]) ? $data["producto"] : '';
        $productoDB = Database::escape($producto);

        $sql = <<<SQL
			UPDATE
			    `comentarioproducto`
			SET
			    `usuario_editar` = $usuarioDB,
`comentario` = $comentarioDB, id_`producto` = $productoDB
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

	public function eliminarComentarioproducto(array $data)
    {
        $id = isset($data["id"]) ? $data["id"] : '';
        $idDB = Database::escape($id);
        $usuario = $GLOBALS['sesionG']['idUsuario'];
        $usuarioDB = Database::escape($usuario);

        $sql = <<<SQL
UPDATE
    `comentarioproducto`
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
                        SELECT * FROM comentarioproducto
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
	
	public function getListComentarioproducto()
    {
	$paginador = '';
	$offset = isset($_GET["cant"]) ? $_GET["cant"] : 0;
	if (!preg_match('/^[0-9]+$/i', $offset)) {
		$offset = 0;
	}
	$limit = 5;
        $paginador = " LIMIT $offset,$limit";
	$id = isset($_GET["id_producto"]) ? $_GET["id_producto"] : '';
        $sql = <<<sql
SELECT * FROM (
        SELECT
		`comentarioproducto`.*,
    u.*
    	FROM
		`comentarioproducto`
INNER JOIN
    (
    SELECT
        idUsuario AS idUsuarioComentario,
        nombre AS nombre_usuario,
        apellido AS apellido_usuario
    FROM
        usuario_picker
    UNION
SELECT
    idUsuario AS idUsuarioComentario,
    nombre AS nombre_usuario,
    apellido AS apellido_usuario
FROM
    usuario_seller
) AS u
ON
    u.idUsuarioComentario = `comentarioproducto`.`usuario_alta`
		WHERE
     id_producto = $id AND
	(`comentarioproducto`.eliminar = 0 OR `comentarioproducto`.eliminar IS NULL) 
) as paginadorcomentarios
$paginador
sql;
        $resultado = Database::Connect()->query($sql);
        $list = array();

        while ($rowEmp = mysqli_fetch_array($resultado)) {
            $list[] = $rowEmp;
        }
        return $list;
	}

	public function getComentarioproducto($id)
    {
        $usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $id = isset($id) ?   $id : '';
        $idDB = Database::escape($id);
        $sql = <<<sql
		SELECT
		`comentarioproducto`.*,
    u.*
    	FROM
        `comentarioproducto`
INNER JOIN
    (
    SELECT
        idUsuario AS idUsuarioComentario,
        nombre AS nombre_usuario,
        apellido AS apellido_usuario
    FROM
        usuario_picker
    UNION
SELECT
    idUsuario AS idUsuarioComentario,
    nombre AS nombre_usuario,
    apellido AS apellido_usuario
FROM
    usuario_seller
) AS u
ON
    u.idUsuarioComentario = `comentarioproducto`.`usuario_alta`
    	WHERE
			comentarioproducto.id=$idDB AND 
        (`comentarioproducto`.eliminar = 0 OR `comentarioproducto`.eliminar IS NULL) AND `comentarioproducto`.usuario_alta = $usuarioAltaDB
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


}

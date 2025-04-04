<?php
//include_once("../util/database.php");
class  ComentarioDao
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


	public function altaComentario(array $data)
	{


        $publicacion = isset($data["publicacion"]) ? $data["publicacion"] : '';
        $publicacionDB = Database::escape($publicacion);

        $comentario = isset($data["comentario"]) ? $data["comentario"] : '';
        $comentarioDB = Database::escape($comentario);

		$usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        
		$sql = <<<SQL
			INSERT INTO comentario ( id_publicacion,  comentario,usuario_alta)  
			VALUES ($publicacionDB,  $comentarioDB, $usuarioAltaDB)
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


	public function editarComentario(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		$idDB = Database::escape($id);
		$usuario = $GLOBALS['sesionG']['idUsuario'];
        $usuarioDB = Database::escape($usuario);

        $usuario = isset($data["usuario"]) ? $data["usuario"] : '';
        $usuarioDB = Database::escape($usuario);
        $publicacion = isset($data["publicacion"]) ? $data["publicacion"] : '';
        $publicacionDB = Database::escape($publicacion);

        $comentario = isset($data["comentario"]) ? $data["comentario"] : '';
        $comentarioDB = Database::escape($comentario);


        $sql = <<<SQL
			UPDATE
			    `comentario`
			SET
			    `usuario_editar` = $usuarioDB,
id_`usuario` = $usuarioDB, id_`publicacion` = $publicacionDB,  `comentario` = $comentarioDB
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

	public function eliminarComentario(array $data)
    {
        $id = isset($data["id"]) ? $data["id"] : '';
        $idDB = Database::escape($id);
        $usuario = $GLOBALS['sesionG']['idUsuario'];
        $usuarioDB = Database::escape($usuario);

        $sql = <<<SQL
UPDATE
    `comentario`
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
                        SELECT * FROM comentario
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
	
	public function getListComentario()
    {
	$paginador = '';
	$offset = isset($_GET["cant"]) ? $_GET["cant"] : 0;
	if (!preg_match('/^[0-9]+$/i', $offset)) {
		$offset = 0;
	}
	$limit = 5;
        $paginador = " LIMIT 0,$limit";
        $usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
	$id = isset($_GET["id_publicacion"]) ? $_GET["id_publicacion"] : '';
        $sql = <<<sql
SELECT * FROM (
SELECT
    `comentario`.*,
    u.*
FROM
    `comentario`
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
    u.idUsuarioComentario = `comentario`.`usuario_alta`
WHERE
     id_publicacion = $id AND
    (
        `comentario`.eliminar = 0 OR `comentario`.eliminar IS NULL
    )

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

	public function getComentario($id)
    {
        $usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $id = isset($id) ?   $id : '';
        $idDB = Database::escape($id);
        $sql = <<<sql
		SELECT
		`comentario`.*,
    u.*
    	FROM
        `comentario`
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
    u.idUsuarioComentario = `comentario`.`usuario_alta`
    	WHERE
			comentario.id=$idDB AND 
        (`comentario`.eliminar = 0 OR `comentario`.eliminar IS NULL) AND `comentario`.usuario_alta = $usuarioAltaDB
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

                

}

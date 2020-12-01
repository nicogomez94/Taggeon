<?php
//include_once("../util/database.php");
class  PublicacionDao
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


	public function altaPublicacion(array $data)
	{

        $publicacion_nombre = isset($data["publicacion_nombre"]) ? $data["publicacion_nombre"] : '';
        $publicacion_nombreDB = Database::escape($publicacion_nombre);
        $publicacion_categoria = isset($data["publicacion_categoria"]) ? $data["publicacion_categoria"] : '';
        $publicacion_categoriaDB = Database::escape($publicacion_categoria);
        $publicacion_descripcion = isset($data["publicacion_descripcion"]) ? $data["publicacion_descripcion"] : '';
        $publicacion_descripcionDB = Database::escape($publicacion_descripcion);
		$usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $publicacion_pid = isset($data["publicacion_pid"]) ? $data["publicacion_pid"] : '';
        $publicacion_pidDB = Database::escape($publicacion_pid);
        
		$sql = <<<SQL
			INSERT INTO publicacion (publicacion_nombre, id_publicacion_categoria, publicacion_descripcion,usuario_alta,pid)  
			VALUES ($publicacion_nombreDB, $publicacion_categoriaDB, $publicacion_descripcionDB,$usuarioAltaDB,$publicacion,$publicacion_pidDB)
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


	public function editarPublicacion(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		$idDB = Database::escape($id);
		$usuario = $GLOBALS['sesionG']['idUsuario'];
        $usuarioDB = Database::escape($usuario);

        $publicacion_nombre = isset($data["publicacion_nombre"]) ? $data["publicacion_nombre"] : '';
        $publicacion_nombreDB = Database::escape($publicacion_nombre);
        $publicacion_categoria = isset($data["publicacion_categoria"]) ? $data["publicacion_categoria"] : '';
        $publicacion_categoriaDB = Database::escape($publicacion_categoria);
        $publicacion_descripcion = isset($data["publicacion_descripcion"]) ? $data["publicacion_descripcion"] : '';
        $publicacion_descripcionDB = Database::escape($publicacion_descripcion);
        $publicacion_pid = isset($data["publicacion_pid"]) ? $data["publicacion_pid"] : '';
        $publicacion_pidDB = Database::escape($publicacion_pid);

        $sql = <<<SQL
			UPDATE
			    `publicacion`
			SET
			    `usuario_editar` = $usuarioDB, pid=$publicacion_pidDB,
`publicacion_nombre` = $publicacion_nombreDB, id_`publicacion_categoria` = $publicacion_categoriaDB, `publicacion_descripcion` = $publicacion_descripcionDB
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

	public function eliminarPublicacion(array $data)
    {
        $id = isset($data["id"]) ? $data["id"] : '';
        $idDB = Database::escape($id);
        $usuario = $GLOBALS['sesionG']['idUsuario'];
        $usuarioDB = Database::escape($usuario);

        $sql = <<<SQL
UPDATE
    `publicacion`
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
                        SELECT * FROM publicacion
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
	#FUNCIONESDAOELIMINARHIJOS#
	
                public function existePublicacion_categoria($id_publicacion_categoria)
                {
                    $id_publicacion_categoria = isset($id_publicacion_categoria) ?   $id_publicacion_categoria : '';
                    $id_publicacion_categoriaDB = Database::escape($id_publicacion_categoria);      
            
                    $sql = <<<SQL
                        SELECT *FROM publicacion_categoria
                        WHERE 
                            id = $id_publicacion_categoriaDB AND
                            (eliminar = 0 OR eliminar is null);
SQL;            

                    $resultado=mysqli_query(Database::Connect(), $sql);
                    $row_cnt = mysqli_num_rows($resultado);
                    if ($row_cnt == 1){
                        $this->setStatus("OK");
                        return true;
                    }
                    
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo publicacion_categoria es incorrecto.");
                    return false;

                }

                public function eliminarPublicacion_foto(array $data)
                {
                    $id = isset($data["id"]) ? $data["id"] : '';
                    $idDB = Database::escape($id);
                    $usuario = $GLOBALS['sesionG']['idUsuario'];
                    $usuarioDB = Database::escape($usuario);
                    $sql = <<<SQL
            UPDATE
                `publicacion_publicacion_foto`
            SET
                `usuario_editar` = $usuarioDB,
                `eliminar` = 1
            WHERE
            `id_publicacion` = $idDB AND
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
            
                public function altaPublicacion_foto(array $data)
                {
                    $id_publicacion = isset($data["id_publicacion"]) ?   $data["id_publicacion"] : '';
                    $id_publicacionDB = Database::escape($id_publicacion);      
            
                    $publicacion_foto = isset($data["publicacion_foto"]) ?  $data["publicacion_foto"] : '';
                    $publicacion_fotoDB = Database::escape($publicacion_foto);      

                    $sql = <<<SQL
                        INSERT INTO publicacion_publicacion_foto (id_publicacion,publicacion_foto) 
                        VALUES ($id_publicacionDB,$publicacion_fotoDB)
SQL;
            
                    if (!mysqli_query(Database::Connect(), $sql)) {
                        $this->setStatus("ERROR");
                        $this->setMsj("$sql" . Database::Connect()->error);
                    } else {
                        $id = mysqli_insert_id(Database::Connect());
                                        $fp = fopen("/var/www/html/publicaciones_img/$id", 'w');
                                        fwrite($fp, $publicacion_foto);
                                        fclose($fp);

                        $this->setMsj($id);
                        $this->setStatus("OK");
                        return true;
                    }
            
                    return false;
                }



                public function getPublicacion($id)
                {
                    $usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
                    $usuarioAltaDB = Database::escape($usuarioAlta);
                    $id = isset($id) ?   $id : '';
                    $idDB = Database::escape($id);
                    $sql = <<<sql
                    SELECT
                    `publicacion`.`id`, `publicacion_nombre`, `id_publicacion_categoria`, 
                    `publicacion_descripcion`,pid,
                   GROUP_CONCAT(publicacion_publicacion_foto.id) as foto
            
                FROM
                    `publicacion`
                LEFT JOIN
                    publicacion_publicacion_foto
                ON
                    `publicacion`.id = publicacion_publicacion_foto.id_publicacion AND (publicacion_publicacion_foto.eliminar = 0 OR publicacion_publicacion_foto.eliminar IS NULL)
                WHERE
                publicacion.id=$idDB AND 
                    (`publicacion`.eliminar = 0 OR `publicacion`.eliminar IS NULL) AND `publicacion`.usuario_alta = $usuarioAltaDB
                group by         
                `publicacion`.`id`, `publicacion_nombre`, `id_publicacion_categoria`, `publicacion_descripcion`,pid
            sql;
                    $resultado = Database::Connect()->query($sql);
                    $row_cnt = mysqli_num_rows($resultado);
                    $list = array();
                    if ($row_cnt <= 0) {
                        $this->setStatus("ERROR");
                        $this->setMsj("No se encontró la publicación o no tiene permisos para editar.");
                        return $list;
                    }
            
            
                    while ($rowEmp = mysqli_fetch_array($resultado)) {
                        $list[] = $rowEmp;
                    }
                    $this->setStatus("ok");
                    return $list;
                }

                public function getListCategoria()
                {
                    $sql = <<<sql
                                SELECT
                                `id`,
                                `nombre`
                            FROM
                                `publicacion_categoria`
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
        $usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $sql = <<<sql
        SELECT
        `publicacion`.`id`, `publicacion_nombre`, `id_publicacion_categoria`, 
	`publicacion_descripcion`,pid,
        min(publicacion_publicacion_foto.id) as foto
    FROM
        `publicacion`
    LEFT JOIN
        publicacion_publicacion_foto
    ON
        `publicacion`.id = publicacion_publicacion_foto.id_publicacion AND (publicacion_publicacion_foto.eliminar = 0 OR publicacion_publicacion_foto.eliminar IS NULL)
    WHERE
        (`publicacion`.eliminar = 0 OR `publicacion`.eliminar IS NULL) AND `publicacion`.usuario_alta = $usuarioAltaDB
    group by         
    `publicacion`.`id`, `publicacion_nombre`, `id_publicacion_categoria`, 
    `publicacion_descripcion`,pid
sql;
        $resultado = Database::Connect()->query($sql);
        $list = array();

        while ($rowEmp = mysqli_fetch_array($resultado)) {
            $list[] = $rowEmp;
        }
        return $list;
    }


}

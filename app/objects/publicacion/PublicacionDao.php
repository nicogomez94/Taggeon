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
        $escena_sel = isset($data["escena_sel"]) ? $data["escena_sel"] : '';
        $escena_selDB = Database::escape($escena_sel);

        $subescena1 = isset($data["subescena1"]) ? $data["subescena1"] : '';
        $subescena1DB = Database::escape($subescena1);

        $subescena_json = isset($data["subescena_json"]) ? $data["subescena_json"] : '';
        $subescena_jsonDB = Database::escape($subescena_json);

        $publicacion_descripcion = isset($data["publicacion_descripcion"]) ? $data["publicacion_descripcion"] : '';
        $publicacion_descripcionDB = Database::escape($publicacion_descripcion);
		$usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $publicacion_pid = isset($data["data_pines"]) ? $data["data_pines"] : '';
        $publicacion_pidDB = Database::escape($publicacion_pid);
        $aspect_ratio = isset($data["aspect_ratio"]) ? $data["aspect_ratio"] : '';
        $aspect_ratioDB = Database::escape($aspect_ratio);
        
		$sql = <<<SQL
INSERT INTO publicacion (publicacion_nombre, publicacion_descripcion,usuario_alta,pid,aspect_ratio,subescena1,subescena_json,escena_sel)  
VALUES ($publicacion_nombreDB, $publicacion_descripcionDB,$usuarioAltaDB,$publicacion_pidDB,$aspect_ratioDB,$subescena1DB,$subescena_jsonDB,$escena_selDB)
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
        $publicacion_descripcion = isset($data["publicacion_descripcion"]) ? $data["publicacion_descripcion"] : '';
        $publicacion_descripcionDB = Database::escape($publicacion_descripcion);
	$publicacion_pid = isset($data["data_pines"]) ? $data["data_pines"] : '';
        $publicacion_pidDB = Database::escape($publicacion_pid);
        $escena_sel = isset($data["escena_sel"]) ? $data["escena_sel"] : '';
        $escena_selDB = Database::escape($escena_sel);

        $subescena1 = isset($data["subescena1"]) ? $data["subescena1"] : '';
        $subescena1DB = Database::escape($subescena1);

        $subescena_json = isset($data["subescena_json"]) ? $data["subescena_json"] : '';
        $subescena_jsonDB = Database::escape($subescena_json);

        $sql = <<<SQL
			UPDATE
			    `publicacion`
			SET
			    `usuario_editar` = $usuarioDB, pid=$publicacion_pidDB,
`publicacion_nombre` = $publicacion_nombreDB, subescena1=$subescena1DB,subescena_json=$subescena_jsonDB,escena_sel=$escena_selDB, `publicacion_descripcion` = $publicacion_descripcionDB
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
                    $publicacion_fotoDB = Database::escape("/publicaciones_img/");      

                    $sql = <<<SQL
                        INSERT INTO publicacion_publicacion_foto (id_publicacion,publicacion_foto) 
                        VALUES ($id_publicacionDB,$publicacion_fotoDB)
SQL;
            
                    if (!mysqli_query(Database::Connect(), $sql)) {
                        $this->setStatus("ERROR");
                        $this->setMsj("$sql");
                    } else {
                        $id = mysqli_insert_id(Database::Connect());
                        //$fp = fopen("/var/www/html/publicaciones_img/$id", 'w');
                        //fwrite($fp, $publicacion_foto);
                        //fclose($fp);
                        
                        $base_to_php = explode(',', $publicacion_foto);
                        if (count($base_to_php) == 2){
                            $data = base64_decode($base_to_php[1]);
                            $filepath = "/var/www/html/publicaciones_img/$id.png";
                            file_put_contents($filepath,$data);
                        }


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
                    `publicacion`.`id`, `publicacion_nombre`,subescena1,subescena_json,escena_sel, 
                    `publicacion_descripcion`,pid,aspect_ratio
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
                `publicacion`.`id`, `publicacion_nombre`, subescena1,subescena_json,escena_sel, `publicacion_descripcion`,pid,aspect_ratio
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

                public function getPublicacionByIdNoti($id)
                {
                    $id = isset($id) ?   $id : '';
                    $idDB = Database::escape($id);
                    $sql =<<<sql
                    SELECT
		    `publicacion`.`id`, `publicacion_nombre`,
                   `publicacion`.usuario_alta,usuarios.nombre
            
                FROM
                    `publicacion`
        LEFT JOIN
            (SELECT nombre,idUsuario FROM usuario_seller us UNION SELECT nombre,idUsuario FROM usuario_picker) as usuarios
        ON
            `publicacion`.usuario_alta = usuarios.idUsuario    
                WHERE
                publicacion.id=$idDB AND 
                    (`publicacion`.eliminar = 0 OR `publicacion`.eliminar IS NULL)
sql;
                    $resultado = Database::Connect()->query($sql);
                    $list = array();
            
            
                    while ($rowEmp = mysqli_fetch_array($resultado)) {
                        $list[] = $rowEmp;
                    }
                    return $list;
                }
                public function getPublicacionById($id)
                {
                    $id = isset($id) ?   $id : '';
                    $idDB = Database::escape($id);
                    $sql =<<<sql
                    SELECT
                    `publicacion`.`id`, `publicacion_nombre`, subescena1,subescena_json,escena_sel, 
                    `publicacion_descripcion`,pid,aspect_ratio,
                   GROUP_CONCAT(publicacion_publicacion_foto.id) as foto,`publicacion`.usuario_alta
            
                FROM
                    `publicacion`
                LEFT JOIN
                    publicacion_publicacion_foto
                ON
                    `publicacion`.id = publicacion_publicacion_foto.id_publicacion AND (publicacion_publicacion_foto.eliminar = 0 OR publicacion_publicacion_foto.eliminar IS NULL)
                WHERE
                publicacion.id=$idDB AND 
                    (`publicacion`.eliminar = 0 OR `publicacion`.eliminar IS NULL)
                group by         
                `publicacion`.`id`, `publicacion_nombre`, subescena1,subescena_json,escena_sel, `publicacion_descripcion`,pid,usuario_alta,aspect_ratio
sql;
                    $resultado = Database::Connect()->query($sql);
                    $list = array();
            
            
                    while ($rowEmp = mysqli_fetch_array($resultado)) {
                        $list[] = $rowEmp;
                    }
                    return $list;
                }


                public function getPublicacionByIdYProducto($data)
                {

                    $id_producto = isset($data["id_producto"]) ? $data["id_producto"] : 0;
                    $id = isset($data["id_publicacion"]) ? $data["id_publicacion"] : 0;
                    $idDB = Database::escape($id);

                    $sql =<<<sql
                    SELECT
                    `publicacion`.`id`, `publicacion_nombre`, subescena1,subescena_json,escena_sel, 
                    `publicacion_descripcion`,pid,aspect_ratio,
                   GROUP_CONCAT(publicacion_publicacion_foto.id) as foto,`publicacion`.usuario_alta
            
                FROM
                    `publicacion`
                LEFT JOIN
                    publicacion_publicacion_foto
                ON
                    `publicacion`.id = publicacion_publicacion_foto.id_publicacion AND (publicacion_publicacion_foto.eliminar = 0 OR publicacion_publicacion_foto.eliminar IS NULL)
                WHERE
                (`publicacion`.pid like '%\"name\":\"$id_producto\"%') AND
                publicacion.id=$idDB AND 
                    (`publicacion`.eliminar = 0 OR `publicacion`.eliminar IS NULL)
                group by         
                `publicacion`.`id`, `publicacion_nombre`, subescena1,subescena_json,escena_sel, `publicacion_descripcion`,pid,usuario_alta,aspect_ratio
sql;
                    $resultado = Database::Connect()->query($sql);
                    $list = array();
            
            
                    while ($rowEmp = mysqli_fetch_array($resultado)) {
                        $list[] = $rowEmp;
                    }
                    return $list;
                }



                public function getListEscena()
                {
                    $sql = <<<sql
                                SELECT
                                `id`,
                                `nombre`,id_padre
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
		
		public function getListEscena2()
                {
                    $sql = <<<sql
                                SELECT
                                `id`,
                                `nombre`,id_padre
                            FROM
                                `publicacion_categoria2`
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
    public function searchSubEscena2($data)
    {
        
        $input = isset($data["id"]) ? $data["id"] : '';
        $inputDB = Database::escape("$input");

        $sql = <<<sql
SELECT
    id,
    nombre
FROM
    publicacion_categoria2
WHERE
    id_padre = $inputDB AND (
        publicacion_categoria2.eliminar IS NULL OR publicacion_categoria2.eliminar = 0
    )
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

    public function searchSubEscena($data)
    {
        
        $input = isset($data["id"]) ? $data["id"] : '';
        $inputDB = Database::escape("$input");

        $sql = <<<sql
SELECT
    id,
    nombre
FROM
    publicacion_categoria
WHERE
    id_padre = $inputDB AND (
        publicacion_categoria.eliminar IS NULL OR publicacion_categoria.eliminar = 0
    )
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
    public function getListPublicacionIndexPaginador()
    {

        $usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);

	$offset = isset($_GET["cant"]) ? $_GET["cant"] : 0;
	if (!preg_match('/^[0-9]+$/i', $offset)) {
		$offset = 0;
	}
        $limit = 50;


        $sql = <<<sql
SELECT distinct s.*,pc3.nombre_subescena1  from 
(
        SELECT
        1 as orden,
        `publicacion`.`id`,
        `publicacion_nombre`,
        subescena1,subescena_json,escena_sel,
        `publicacion_descripcion`,
        pid,
        aspect_ratio,
        MIN(
            publicacion_publicacion_foto.id
        ) AS foto,
        f.id_publicacion AS favorito,
        mg.id_publicacion AS megusta,
               usuarios.nombre AS nombre_publicador,
            usuarios.idUsuario AS id_publicador
    FROM
        `publicacion`
    LEFT JOIN
        publicacion_publicacion_foto
    ON
        `publicacion`.id = publicacion_publicacion_foto.id_publicacion AND(
            publicacion_publicacion_foto.eliminar = 0 OR publicacion_publicacion_foto.eliminar IS NULL
        )
    LEFT JOIN
        favorito f
    ON
        `publicacion`.id = f.id_publicacion AND f.id_usuario =  $usuarioAltaDB
    LEFT JOIN
        megusta mg
    ON
        `publicacion`.id = mg.id_publicacion AND mg.id_usuario =  $usuarioAltaDB
        LEFT JOIN
            (SELECT nombre,idUsuario FROM usuario_seller us UNION SELECT nombre,idUsuario FROM usuario_picker) as usuarios
        ON
            `publicacion`.usuario_alta = usuarios.idUsuario    
    WHERE
        (
            `publicacion`.eliminar = 0 OR `publicacion`.eliminar IS NULL
        ) AND
    `publicacion`.`id` in (
SELECT distinct c.id_publicacion FROM click c where c.usuario_alta = $usuarioAltaDB
)

    GROUP BY
	orden,
        `publicacion`.`id`,
        `publicacion_nombre`,
        subescena1,subescena_json,escena_sel,
        `publicacion_descripcion`,
        pid,
        aspect_ratio,
        favorito,
        megusta,
            usuarios.nombre,
            usuarios.idUsuario
UNION
        SELECT
        2 as orden,
        `publicacion`.`id`,
        `publicacion_nombre`,
        subescena1,subescena_json,escena_sel,
        `publicacion_descripcion`,
        pid,
        aspect_ratio,
        MIN(
            publicacion_publicacion_foto.id
        ) AS foto,
        f.id_publicacion AS favorito,
        mg.id_publicacion AS megusta,
               usuarios.nombre AS nombre_publicador,
            usuarios.idUsuario AS id_publicador
    FROM
        `publicacion`
    LEFT JOIN
        publicacion_publicacion_foto
    ON
        `publicacion`.id = publicacion_publicacion_foto.id_publicacion AND(
            publicacion_publicacion_foto.eliminar = 0 OR publicacion_publicacion_foto.eliminar IS NULL
        )
    LEFT JOIN
        favorito f
    ON
        `publicacion`.id = f.id_publicacion AND f.id_usuario =  $usuarioAltaDB
    LEFT JOIN
        megusta mg
    ON
        `publicacion`.id = mg.id_publicacion AND mg.id_usuario =  $usuarioAltaDB
        LEFT JOIN
            (SELECT nombre,idUsuario FROM usuario_seller us UNION SELECT nombre,idUsuario FROM usuario_picker) as usuarios
        ON
            `publicacion`.usuario_alta = usuarios.idUsuario    
    WHERE
        (
            `publicacion`.eliminar = 0 OR `publicacion`.eliminar IS NULL
        ) AND
    `publicacion`.`id` not in (
SELECT distinct c.id_publicacion FROM click c where c.usuario_alta = $usuarioAltaDB
)

    GROUP BY
	orden,
        `publicacion`.`id`,
        `publicacion_nombre`,
        subescena1,subescena_json,escena_sel,
        `publicacion_descripcion`,
        pid,
        aspect_ratio,
        favorito,
        megusta,
            usuarios.nombre,
            usuarios.idUsuario
 ) 
as s 
INNER JOIN (select id, id_padre, nombre as nombre_subescena1 from publicacion_categoria UNION select id,id_padre, nombre as nombre_subescena1  from publicacion_categoria2) as pc3 ON pc3.id_padre is null  AND s.subescena1 = pc3.id
    LIMIT $offset,$limit
sql;
//	echo $sql;exit;
        $resultado = Database::Connect()->query($sql);
        $list = array();

        while ($rowEmp = mysqli_fetch_array($resultado)) {
            $id_publicador = isset($rowEmp["id_publicador"]) ? $rowEmp["id_publicador"] : '';
            $rowEmp['foto_perfil'] = $id_publicador;

            #INICIO COMENTARIOS
            $idPublicacion = isset($rowEmp["id"]) ? $rowEmp["id"] : '';
            $idPublicacionBD = Database::escape($idPublicacion);
            $sql2 = <<<sql
                SELECT 
		`comentario`.*,
    u.*
                FROM comentario
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
                WHERE id_publicacion=$idPublicacionBD
order by fecha_alta desc
sql;
   //         echo $sql2;
    
            $resultado2 = Database::Connect()->query($sql2);
            $list2 = array();
            while ($rowEmp2 = mysqli_fetch_array($resultado2)) {
                $list2[] = $rowEmp2;
            }
            #FIN COMENTARIOS
            $rowEmp['comentarios'] = $list2;
            $list[] = $rowEmp;
        }
        return $list;
    }
               
    public function getListPublicacionIndexDinamico()
    {

	   return [];
        $usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);

	$offset = isset($_GET["cant"]) ? $_GET["cant"] : 0;
	if (!preg_match('/^[0-9]+$/i', $offset)) {
		$offset = 0;
	}
        $limit = 50;


        $sql = <<<sql
        SELECT
        `publicacion`.`id`,
        `publicacion_nombre`,
        subescena1,subescena_json,escena_sel,
        `publicacion_descripcion`,
        pid,
        aspect_ratio,
        MIN(
            publicacion_publicacion_foto.id
        ) AS foto,
        f.id_publicacion AS favorito,
        mg.id_publicacion AS megusta,
               usuarios.nombre AS nombre_publicador,
            usuarios.idUsuario AS id_publicador
    FROM
        `publicacion`
    LEFT JOIN
        publicacion_publicacion_foto
    ON
        `publicacion`.id = publicacion_publicacion_foto.id_publicacion AND(
            publicacion_publicacion_foto.eliminar = 0 OR publicacion_publicacion_foto.eliminar IS NULL
        )
    LEFT JOIN
        favorito f
    ON
        `publicacion`.id = f.id_publicacion AND f.id_usuario =  $usuarioAltaDB
    LEFT JOIN
        megusta mg
    ON
        `publicacion`.id = mg.id_publicacion AND mg.id_usuario =  $usuarioAltaDB
        LEFT JOIN
            (SELECT nombre,idUsuario FROM usuario_seller us UNION SELECT nombre,idUsuario FROM usuario_picker) as usuarios
        ON
            `publicacion`.usuario_alta = usuarios.idUsuario    
    WHERE
        (
            `publicacion`.eliminar = 0 OR `publicacion`.eliminar IS NULL
        ) AND
    `publicacion`.`id` in (select id_publicacion from click where usuario_alta = $usuarioAltaDB)
    GROUP BY
        `publicacion`.`id`,
        `publicacion_nombre`,
        subescena1,subescena_json,escena_sel,
        `publicacion_descripcion`,
        pid,
        aspect_ratio,
        favorito,
        megusta,
            usuarios.nombre,
            usuarios.idUsuario
    order by publicacion.fecha_alta desc
    LIMIT $offset,$limit
sql;
        $resultado = Database::Connect()->query($sql);
        $list = array();

        while ($rowEmp = mysqli_fetch_array($resultado)) {
            $id_publicador = isset($rowEmp["id_publicador"]) ? $rowEmp["id_publicador"] : '';
            $rowEmp['foto_perfil'] = $id_publicador;

            #INICIO COMENTARIOS
            $idPublicacion = isset($rowEmp["id"]) ? $rowEmp["id"] : '';
            $idPublicacionBD = Database::escape($idPublicacion);
            $sql2 = <<<sql
                SELECT 
		`comentario`.*,
    u.*
                FROM comentario
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
                WHERE id_publicacion=$idPublicacionBD
order by fecha_alta desc
sql;
            //echo $sql2;
    
            $resultado2 = Database::Connect()->query($sql2);
            $list2 = array();
            while ($rowEmp2 = mysqli_fetch_array($resultado2)) {
                $list2[] = $rowEmp2;
            }
            #FIN COMENTARIOS
            $rowEmp['comentarios'] = $list2;
            $list[] = $rowEmp;
        }
        return $list;
    }
    public function getListPublicacionIndex()
    {
        $id = isset($_GET["id"]) ? $_GET["id"] : '';
        $idDB = Database::escape($id);

        $usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);


        $sql = <<<SQL
			UPDATE
			    `click`
			SET `contador` = `contador` + 1,usuario_editar = $usuarioAltaDB
			    WHERE
                `id_publicacion` = $idDB AND usuario_alta = $usuarioAltaDB
SQL;

$mysqli = Database::Connect();

// Perform queries and print out affected rows
$mysqli->query($sql);
    $row_cnt =  $mysqli->affected_rows;

    
    if ($row_cnt <= 0) {
        $sql = <<<SQL
        INSERT INTO click (id_publicacion, contador,usuario_alta)  
        VALUES ($idDB, '1',$usuarioAltaDB)
SQL;

        if (!mysqli_query(Database::Connect(), $sql)) {
	
    	}
    }

	$offset = isset($_GET["cant"]) ? $_GET["cant"] : 0;
	if (!preg_match('/^[0-9]+$/i', $offset)) {
		$offset = 0;
	}
        $limit = 50;


        $sql = <<<sql
        SELECT
        `publicacion`.`id`,
        `publicacion_nombre`,
        subescena1,subescena_json,escena_sel,
        `publicacion_descripcion`,
        pid,
        aspect_ratio,
        MIN(
            publicacion_publicacion_foto.id
        ) AS foto,
        f.id_publicacion AS favorito,
        mg.id_publicacion AS megusta,
               usuarios.nombre AS nombre_publicador,
            usuarios.idUsuario AS id_publicador
    FROM
        `publicacion`
    LEFT JOIN
        publicacion_publicacion_foto
    ON
        `publicacion`.id = publicacion_publicacion_foto.id_publicacion AND(
            publicacion_publicacion_foto.eliminar = 0 OR publicacion_publicacion_foto.eliminar IS NULL
        )
    LEFT JOIN
        favorito f
    ON
        `publicacion`.id = f.id_publicacion AND f.id_usuario =  $usuarioAltaDB
    LEFT JOIN
        megusta mg
    ON
        `publicacion`.id = mg.id_publicacion AND mg.id_usuario =  $usuarioAltaDB
        LEFT JOIN
            (SELECT nombre,idUsuario FROM usuario_seller us UNION SELECT nombre,idUsuario FROM usuario_picker) as usuarios
        ON
            `publicacion`.usuario_alta = usuarios.idUsuario    
    WHERE
        (
            `publicacion`.eliminar = 0 OR `publicacion`.eliminar IS NULL
        )
    GROUP BY
        `publicacion`.`id`,
        `publicacion_nombre`,
        subescena1,subescena_json,escena_sel,
        `publicacion_descripcion`,
        pid,
        aspect_ratio,
        favorito,
        megusta,
            usuarios.nombre,
            usuarios.idUsuario
    order by publicacion.fecha_alta desc
    LIMIT $offset,$limit
sql;
#echo $sql;
        $resultado = Database::Connect()->query($sql);
        $list = array();

        while ($rowEmp = mysqli_fetch_array($resultado)) {
            $id_publicador = isset($rowEmp["id_publicador"]) ? $rowEmp["id_publicador"] : '';
            $rowEmp['foto_perfil'] = $id_publicador;

            #INICIO COMENTARIOS
            $idPublicacion = isset($rowEmp["id"]) ? $rowEmp["id"] : '';
            $idPublicacionBD = Database::escape($idPublicacion);
            $sql2 = <<<sql
                SELECT 
		`comentario`.*,
    u.*
                FROM comentario
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
                WHERE id_publicacion=$idPublicacionBD
order by fecha_alta desc
sql;
            //echo $sql2;
    
            $resultado2 = Database::Connect()->query($sql2);
            $list2 = array();
            while ($rowEmp2 = mysqli_fetch_array($resultado2)) {
                $list2[] = $rowEmp2;
            }
            #FIN COMENTARIOS
            $rowEmp['comentarios'] = $list2;
            $list[] = $rowEmp;
        }
        return $list;
    }



    public function getListPublicacionFavoritos()
    {
        $usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $sql = <<<sql
        SELECT
        `publicacion`.`id`,
        `publicacion_nombre`,
        subescena1,subescena_json,escena_sel,
        `publicacion_descripcion`,
        pid,
        aspect_ratio,
        MIN(
            publicacion_publicacion_foto.id
        ) AS foto,
        f.id_publicacion AS favorito,
        mg.id_publicacion AS megusta,
           usuarios.nombre AS nombre_publicador,
        usuarios.idUsuario AS id_publicador
    FROM
        `publicacion`
    LEFT JOIN
        publicacion_publicacion_foto
    ON
        `publicacion`.id = publicacion_publicacion_foto.id_publicacion AND(
            publicacion_publicacion_foto.eliminar = 0 OR publicacion_publicacion_foto.eliminar IS NULL
        )
    LEFT JOIN
        favorito f
    ON
        `publicacion`.id = f.id_publicacion AND f.id_usuario = $usuarioAltaDB
    LEFT JOIN
        megusta mg
    ON
        `publicacion`.id = mg.id_publicacion AND mg.id_usuario = $usuarioAltaDB
    LEFT JOIN
        (SELECT nombre,idUsuario FROM usuario_seller us UNION SELECT nombre,idUsuario FROM usuario_picker) as usuarios
    ON
        `publicacion`.usuario_alta = usuarios.idUsuario
    WHERE
        (
            `publicacion`.eliminar = 0 OR `publicacion`.eliminar IS NULL
        )
    GROUP BY
        `publicacion`.`id`,
        `publicacion_nombre`,
        subescena1,subescena_json,escena_sel,
        `publicacion_descripcion`,
        pid,
        aspect_ratio,
        favorito,
        megusta,
        usuarios.nombre,
        usuarios.idUsuario
sql;
//echo $sql;

        $resultado = Database::Connect()->query($sql);
        $list = array();

        while ($rowEmp = mysqli_fetch_array($resultado)) {
            $list[] = $rowEmp;
        }
        return $list;
    }

    public function getListPublicacionPublic($usuarioAlta)
    {
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $sql = <<<sql
        SELECT
        `publicacion`.`id`,
        `publicacion_nombre`,
        subescena1,subescena_json,escena_sel,
        `publicacion_descripcion`,
        pid,
        aspect_ratio,
        MIN(
            publicacion_publicacion_foto.id
        ) AS foto,
        f.id_publicacion AS favorito,
        mg.id_publicacion AS megusta,
                   usuarios.nombre AS nombre_publicador,
                usuarios.idUsuario AS id_publicador
    FROM
        `publicacion`
    LEFT JOIN
        publicacion_publicacion_foto
    ON
        `publicacion`.id = publicacion_publicacion_foto.id_publicacion AND(
            publicacion_publicacion_foto.eliminar = 0 OR publicacion_publicacion_foto.eliminar IS NULL
        )
    LEFT JOIN
        favorito f
    ON
        `publicacion`.id = f.id_publicacion AND f.id_usuario = $usuarioAltaDB
    LEFT JOIN
	megusta mg
    ON
        `publicacion`.id = mg.id_publicacion AND mg.id_usuario = $usuarioAltaDB
        
            LEFT JOIN
                (SELECT nombre,idUsuario FROM usuario_seller us UNION SELECT nombre,idUsuario FROM usuario_picker) as usuarios
            ON
                `publicacion`.usuario_alta = usuarios.idUsuario    
    WHERE
        (
            `publicacion`.eliminar = 0 OR `publicacion`.eliminar IS NULL
        ) AND `publicacion`.usuario_alta = $usuarioAltaDB
    GROUP BY
        `publicacion`.`id`,
        `publicacion_nombre`,
        subescena1,subescena_json,escena_sel,
        `publicacion_descripcion`,
        pid,
        aspect_ratio,
        favorito,
	megusta,
                usuarios.nombre,
                usuarios.idUsuario
sql;
//echo $sql;
        $resultado = Database::Connect()->query($sql);
        $list = array();

        while ($rowEmp = mysqli_fetch_array($resultado)) {
            $list[] = $rowEmp;
        }
        return $list;
    }

    public function getListPublicacion()
    {
	$offset = isset($_GET["cant"]) ? $_GET["cant"] : 0;
	if (!preg_match('/^[0-9]+$/i', $offset)) {
		$offset = 0;
	}
        $limit = 5;
        $usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $sql = <<<sql
        SELECT
        `publicacion`.`id`,
        `publicacion_nombre`,
        subescena1,subescena_json,escena_sel,
        `publicacion_descripcion`,
        pid,
        aspect_ratio,
        MIN(
            publicacion_publicacion_foto.id
        ) AS foto,
        f.id_publicacion AS favorito,
        mg.id_publicacion AS megusta,
                   usuarios.nombre AS nombre_publicador,
                usuarios.idUsuario AS id_publicador
    FROM
        `publicacion`
    LEFT JOIN
        publicacion_publicacion_foto
    ON
        `publicacion`.id = publicacion_publicacion_foto.id_publicacion AND(
            publicacion_publicacion_foto.eliminar = 0 OR publicacion_publicacion_foto.eliminar IS NULL
        )
    LEFT JOIN
        favorito f
    ON
        `publicacion`.id = f.id_publicacion AND f.id_usuario = $usuarioAltaDB
    LEFT JOIN
        megusta mg
    ON
        `publicacion`.id = mg.id_publicacion AND mg.id_usuario = $usuarioAltaDB
        
            LEFT JOIN
                (SELECT nombre,idUsuario FROM usuario_seller us UNION SELECT nombre,idUsuario FROM usuario_picker) as usuarios
            ON
                `publicacion`.usuario_alta = usuarios.idUsuario    
    WHERE
        (
            `publicacion`.eliminar = 0 OR `publicacion`.eliminar IS NULL
        ) AND `publicacion`.usuario_alta = $usuarioAltaDB
    GROUP BY
        `publicacion`.`id`,
        `publicacion_nombre`,
        subescena1,subescena_json,escena_sel,
        `publicacion_descripcion`,
        pid,
        aspect_ratio,
	favorito,
	megusta,
                usuarios.nombre,
                usuarios.idUsuario
    LIMIT $offset,$limit
sql;
//echo $sql;
        $resultado = Database::Connect()->query($sql);
        $list = array();

        while ($rowEmp = mysqli_fetch_array($resultado)) {
            $list[] = $rowEmp;
        }
        return $list;
    }


    public function searchIndex(array $data)
    {
        $input = isset($data["input"]) ? $data["input"] : '';
        $inputDB = Database::escape("%$input%");

        $usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);




        $sql = <<<sql
        SELECT id FROM `producto` WHERE titulo like $inputDB OR descr_producto like $inputDB
sql;
        $resultado = Database::Connect()->query($sql);
        $list = array();

        $whereProducto = '';
        while ($rowEmp = mysqli_fetch_array($resultado)) {
            $id_producto = isset($rowEmp["id"]) ? $rowEmp["id"] : next;
            if ($whereProducto != ''){
                $whereProducto .= " OR ";
            }
            $whereProducto .=  "(`publicacion`.pid like '%\"name\":\"$id_producto\"%')";
        }

        if ($whereProducto != ''){
            $whereProducto = " OR ($whereProducto)";
        }
	$paginador = '';
	$offset = isset($_GET["cant"]) ? $_GET["cant"] : 0;
	if (!preg_match('/^[0-9]+$/i', $offset)) {
		$offset = 0;
	}
	$limit = 50;
        $paginador = " LIMIT $offset,$limit";

        $sql = <<<sql
        SELECT
        `publicacion`.`id`,
        `publicacion_nombre`,
        subescena1,subescena_json,escena_sel,
        `publicacion_descripcion`,
        pid,
        aspect_ratio,
        MIN(
            publicacion_publicacion_foto.id
        ) AS foto,
        f.id_publicacion AS favorito,
        mg.id_publicacion AS megusta,
               usuarios.nombre AS nombre_publicador,
            usuarios.idUsuario AS id_publicador
    FROM
        `publicacion`
    LEFT JOIN
        publicacion_publicacion_foto
    ON
        `publicacion`.id = publicacion_publicacion_foto.id_publicacion AND(
            publicacion_publicacion_foto.eliminar = 0 OR publicacion_publicacion_foto.eliminar IS NULL
        )
    LEFT JOIN
        favorito f
    ON
        `publicacion`.id = f.id_publicacion AND f.id_usuario =  $usuarioAltaDB
    LEFT JOIN
        megusta mg
    ON
        `publicacion`.id = mg.id_publicacion AND mg.id_usuario =  $usuarioAltaDB
        LEFT JOIN
            (SELECT nombre,idUsuario FROM usuario_seller us UNION SELECT nombre,idUsuario FROM usuario_picker) as usuarios
        ON
            `publicacion`.usuario_alta = usuarios.idUsuario    
    WHERE
        (
            `publicacion`.eliminar = 0 OR `publicacion`.eliminar IS NULL
        ) AND
        (`publicacion_nombre` LIKE $inputDB OR 
         `publicacion_descripcion` LIKE $inputDB
         $whereProducto
        )

    GROUP BY
        `publicacion`.`id`,
        `publicacion_nombre`,
        subescena1,subescena_json,escena_sel,
        `publicacion_descripcion`,
        pid,
        aspect_ratio,
        favorito,
        megusta,
            usuarios.nombre,
            usuarios.idUsuario
	$paginador
sql;
//echo $sql;
        $resultado = Database::Connect()->query($sql);
        $list = array();

        while ($rowEmp = mysqli_fetch_array($resultado)) {
            $id_publicador = isset($rowEmp["id_publicador"]) ? $rowEmp["id_publicador"] : '';
            $rowEmp['foto_perfil'] = $id_publicador;
            $list[] = $rowEmp;
        }
        return $list;

    }



}

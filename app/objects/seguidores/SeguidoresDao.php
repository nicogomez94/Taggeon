<?php
//include_once("../util/database.php");
class  SeguidoresDao
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


	public function altaSeguidores(array $data)
	{

        $usuarioAlta = isset($data["id_publicador"]) ? $data["id_publicador"] : '';
        $usuarioAltaDB = Database::escape($usuarioAlta);
		$seguidor = $GLOBALS['sesionG']['idUsuario'];
        $seguidorDB = Database::escape($seguidor);
        
		$sql = <<<SQL
			INSERT INTO seguidores (id_usuario,id_seguidor,usuario_alta)  
			VALUES ($usuarioAltaDB, $seguidorDB,$usuarioAltaDB)
SQL;

		if (!mysqli_query(Database::Connect(), $sql)) {
			$this->setStatus("ERROR");
			$this->setMsj("$sql" . Database::Connect()->error);
		} else {
			#$id = mysqli_insert_id(Database::Connect());
			$this->setMsj("OK");
			$this->setStatus("OK");
			return true;
		}

		return false;
	}


	public function editarSeguidores(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		$idDB = Database::escape($id);
		$usuario = $GLOBALS['sesionG']['idUsuario'];
        $usuarioDB = Database::escape($usuario);

        $usuario = isset($data["usuario"]) ? $data["usuario"] : '';
        $usuarioDB = Database::escape($usuario);
        $seguidor = isset($data["seguidor"]) ? $data["seguidor"] : '';
        $seguidorDB = Database::escape($seguidor);
        $request_uri = isset($data["request_uri"]) ? $data["request_uri"] : '';
        $request_uriDB = Database::escape($request_uri);

        $sql = <<<SQL
			UPDATE
			    `seguidores`
			SET
			    `usuario_editar` = $usuarioDB,
id_`usuario` = $usuarioDB, id_`seguidor` = $seguidorDB, `request_uri` = $request_uriDB
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

	public function eliminarSeguidores(array $data)
    {
        $id = isset($data["id"]) ? $data["id"] : '';
        $idDB = Database::escape($id);
        $usuario = $GLOBALS['sesionG']['idUsuario'];
        $usuarioDB = Database::escape($usuario);

        $sql = <<<SQL
UPDATE
    `seguidores`
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
                        SELECT * FROM seguidores
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
	
	public function getListSeguidores()
    {
        $usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $sql = <<<sql
        SELECT
		*
    	FROM
		`seguidores`
		WHERE
        (`seguidores`.eliminar = 0 OR `seguidores`.eliminar IS NULL) AND `seguidores`.usuario_alta = $usuarioAltaDB
sql;
        $resultado = Database::Connect()->query($sql);
        $list = array();

        while ($rowEmp = mysqli_fetch_array($resultado)) {
            $list[] = $rowEmp;
        }
        return $list;
	}

	public function getSeguidores($id)
    {
        $usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $id = isset($id) ?   $id : '';
        $idDB = Database::escape($id);
        $sql = <<<sql
		SELECT
		*
    	FROM
        `seguidores`
    	WHERE
			seguidores.id=$idDB AND 
        (`seguidores`.eliminar = 0 OR `seguidores`.eliminar IS NULL) AND `seguidores`.usuario_alta = $usuarioAltaDB
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
    public function getListSeguidor()
    {
        $sql = <<<sql
                    SELECT
                    `id`,
                    `nombre`
                FROM
                    `seguidor`
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

                public function existeSeguidor($id_publicador)
                {
                    $id_publicador = isset($id_publicador) ?   $id_publicador : '';
                    $id_publicadorDB = Database::escape($id_publicador);      
            
                    $sql = <<<SQL
                        SELECT * FROM usuario
                        WHERE 
                            id = $id_publicadorDB AND
                            (eliminar = 0 OR eliminar is null);
SQL;            

                    $resultado=mysqli_query(Database::Connect(), $sql);
                    $row_cnt = mysqli_num_rows($resultado);
                    if ($row_cnt == 1){
                        $this->setStatus("OK");
                        return true;
                    }
                    
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo id_publicador es incorrecto.");
                    return false;

                }


}

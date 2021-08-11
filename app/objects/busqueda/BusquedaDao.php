<?php
//include_once("../util/database.php");
class  BusquedaDao
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


	public function altaBusqueda(array $data)
	{

        $search = isset($data["input"]) ? $data["input"] : '';
        $searchDB = Database::escape($search);



        $sql = <<<SQL
			UPDATE
			    `busqueda`
			SET `contador` = `contador` + 1
			    WHERE
                `search` = $searchDB
SQL;

$mysqli = Database::Connect();

// Perform queries and print out affected rows
$mysqli->query($sql);
    $row_cnt =  $mysqli->affected_rows;

    $fp = fopen("/var/www/html/log.txt", 'a');
    fwrite($fp, "\nrow_cnt $row_cnt\n");
    fclose($fp);
    
    if ($row_cnt <= 0) {
        $sql = <<<SQL
        INSERT INTO busqueda (search, contador)  
        VALUES ($searchDB, '1')
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

    return true;
 
	}


	public function editarBusqueda(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		$idDB = Database::escape($id);
		$usuario = $GLOBALS['sesionG']['idUsuario'];
        $usuarioDB = Database::escape($usuario);

        $search = isset($data["search"]) ? $data["search"] : '';
        $searchDB = Database::escape($search);
        $contador = isset($data["contador"]) ? $data["contador"] : '';
        $contadorDB = Database::escape($contador);

        $sql = <<<SQL
			UPDATE
			    `busqueda`
			SET
			    `usuario_editar` = $usuarioDB,
`search` = $searchDB, `contador` = $contadorDB
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

	public function eliminarBusqueda(array $data)
    {
        $id = isset($data["id"]) ? $data["id"] : '';
        $idDB = Database::escape($id);
        $usuario = $GLOBALS['sesionG']['idUsuario'];
        $usuarioDB = Database::escape($usuario);

        $sql = <<<SQL
UPDATE
    `busqueda`
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
                        SELECT * FROM busqueda
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
	
	public function getListBusqueda()
    {
        $usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $sql = <<<sql
        SELECT
		*
    	FROM
		`busqueda`
		WHERE
        (`busqueda`.eliminar = 0 OR `busqueda`.eliminar IS NULL) AND `busqueda`.usuario_alta = $usuarioAltaDB
sql;
        $resultado = Database::Connect()->query($sql);
        $list = array();

        while ($rowEmp = mysqli_fetch_array($resultado)) {
            $list[] = $rowEmp;
        }
        return $list;
	}

	public function getBusqueda($id)
    {
        $usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $id = isset($id) ?   $id : '';
        $idDB = Database::escape($id);
        $sql = <<<sql
		SELECT
		*
    	FROM
        `busqueda`
    	WHERE
			busqueda.id=$idDB AND 
        (`busqueda`.eliminar = 0 OR `busqueda`.eliminar IS NULL) AND `busqueda`.usuario_alta = $usuarioAltaDB
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

    public function search($data)
    {
        
        $input = isset($data["input"]) ? $data["input"] : '';
        $inputDB = Database::escape("%$input%");

        $sql = <<<sql
        SELECT
        search
    FROM
    busqueda
    WHERE
        search  LIKE $inputDB
    order by contador desc
sql;
        if (!mysqli_query(Database::Connect(), $sql)) {
            $this->setStatus("ERROR");
            $this->setMsj(Database::Connect()->error);
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




}

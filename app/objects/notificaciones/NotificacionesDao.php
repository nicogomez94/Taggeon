<?php
//include_once("../util/database.php");
class  NotificacionesDao
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


	public function altaNotificaciones(array $data)
	{

        $request_uri = isset($data["request_uri"]) ? $data["request_uri"] : '';
        $request_uriDB = Database::escape($request_uri);
        $seguidor = isset($data["seguidor"]) ? $data["seguidor"] : '';
        $seguidorDB = Database::escape($seguidor);
        $venta = isset($data["venta"]) ? $data["venta"] : '';
        $ventaDB = Database::escape($venta);
        $compra = isset($data["compra"]) ? $data["compra"] : '';
        $compraDB = Database::escape($compra);
        $favorito = isset($data["favorito"]) ? $data["favorito"] : '';
        $favoritoDB = Database::escape($favorito);
		$usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        
		$sql = <<<SQL
			INSERT INTO notificaciones (request_uri, seguidor, venta, compra, favorito,usuario_alta)  
			VALUES ($request_uriDB, $seguidorDB, $ventaDB, $compraDB, $favoritoDB,$usuarioAltaDB)
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


	public function editarNotificaciones(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		$idDB = Database::escape($id);
		$usuario = $GLOBALS['sesionG']['idUsuario'];
        $usuarioDB = Database::escape($usuario);

        $request_uri = isset($data["request_uri"]) ? $data["request_uri"] : '';
        $request_uriDB = Database::escape($request_uri);
        $seguidor = isset($data["seguidor"]) ? $data["seguidor"] : '';
        $seguidorDB = Database::escape($seguidor);
        $venta = isset($data["venta"]) ? $data["venta"] : '';
        $ventaDB = Database::escape($venta);
        $compra = isset($data["compra"]) ? $data["compra"] : '';
        $compraDB = Database::escape($compra);
        $favorito = isset($data["favorito"]) ? $data["favorito"] : '';
        $favoritoDB = Database::escape($favorito);

        $sql = <<<SQL
			UPDATE
			    `notificaciones`
			SET
			    `usuario_editar` = $usuarioDB,
`request_uri` = $request_uriDB, `seguidor` = $seguidorDB, `venta` = $ventaDB, `compra` = $compraDB, `favorito` = $favoritoDB
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

	public function eliminarNotificaciones(array $data)
    {
        $id = isset($data["id"]) ? $data["id"] : '';
        $idDB = Database::escape($id);
        $usuario = $GLOBALS['sesionG']['idUsuario'];
        $usuarioDB = Database::escape($usuario);

        $sql = <<<SQL
UPDATE
    `notificaciones`
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
                        SELECT * FROM notificaciones
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
	
	public function getListNotificaciones()
    {
        $usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $sql = <<<sql
        SELECT
		*
    	FROM
		`notificaciones`
		WHERE
        (`notificaciones`.eliminar = 0 OR `notificaciones`.eliminar IS NULL) AND `notificaciones`.usuario_alta = $usuarioAltaDB
sql;
        $resultado = Database::Connect()->query($sql);
        $list = array();

        while ($rowEmp = mysqli_fetch_array($resultado)) {
            $list[] = $rowEmp;
        }
        return $list;
	}

	public function getNotificaciones($id)
    {
        $usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $id = isset($id) ?   $id : '';
        $idDB = Database::escape($id);
        $sql = <<<sql
		SELECT
		*
    	FROM
        `notificaciones`
    	WHERE
			notificaciones.id=$idDB AND 
        (`notificaciones`.eliminar = 0 OR `notificaciones`.eliminar IS NULL) AND `notificaciones`.usuario_alta = $usuarioAltaDB
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





}

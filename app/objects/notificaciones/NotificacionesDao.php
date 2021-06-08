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

        $tipo_notificacion = isset($data["tipo_notificacion"]) ? $data["tipo_notificacion"] : '';
        $tipo_notificacionDB = Database::escape($tipo_notificacion);


        $json_notificacion = isset($data["json_notificacion"]) ? $data["json_notificacion"] : '';
        $json_notificacion = json_encode($json_notificacion);
        $json_notificacionDB = Database::escape($json_notificacion);

        $usuario_notificacion = isset($data["usuario_notificacion"]) ? $data["usuario_notificacion"] : '';
        $usuario_notificacionDB = Database::escape($usuario_notificacion);

		$usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);

        
		$sql = <<<SQL
INTO
    `notificaciones`(
        `tipo_notificacion`, `json_notificacion`, `usuario_notificacion`, `usuario_alta`
    )
VALUES(
    $tipo_notificacionDB,$json_notificacionDB,$usuario_notificacionDB,$usuarioAltaDB
)
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
        $nombre_venta = isset($data["nombre_venta"]) ? $data["nombre_venta"] : '';
        $nombre_ventaDB = Database::escape($nombre_venta);
        $tipo_venta = isset($data["tipo_venta"]) ? $data["tipo_venta"] : '';
        $tipo_ventaDB = Database::escape($tipo_venta);
        $id_venta = isset($data["id_venta"]) ? $data["id_venta"] : '';
        $id_ventaDB = Database::escape($id_venta);
        $compra = isset($data["compra"]) ? $data["compra"] : '';
        $compraDB = Database::escape($compra);
        $favorito = isset($data["favorito"]) ? $data["favorito"] : '';
        $favoritoDB = Database::escape($favorito);

        $sql = <<<SQL
			UPDATE
			    `notificaciones`
			SET
			    `usuario_editar` = $usuarioDB,
`request_uri` = $request_uriDB, `seguidor` = $seguidorDB, `nombre_venta` = $nombre_ventaDB, `tipo_venta` = $tipo_ventaDB, `id_venta` = $id_ventaDB, `compra` = $compraDB, `favorito` = $favoritoDB
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
        (`notificaciones`.eliminar = 0 OR `notificaciones`.eliminar IS NULL) 
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

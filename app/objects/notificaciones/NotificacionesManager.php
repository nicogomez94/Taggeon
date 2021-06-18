<?php
include_once("NotificacionesDao.php");
include_once("/var/www/html/app/objects/publicacion/PublicacionManager.php");
class  NotificacionesManager
{
	private $notificacionesDao;
	private $status    = "";
	private $msj       = "";

	public function __construct()
	{
		$this->notificacionesDao = new NotificacionesDao();
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

	private function validarNotificaciones(array $data) {
		$var_accion = isset($data["accion"]) ? $data["accion"] : '';
		if ($var_accion == 'editar') {
			$id = isset($data["id"]) ? $data["id"] : '';
			if ($this->existeId($id) === false) {
				return false;
			}
		}

		return true;

	}



	public function agregarNotificaciones(array $data)
	{
		$tipo = $data['tipo_notificacion'];
		if ($tipo == 'favorito'){
			$publicacionManager = new PublicacionManager();
			$publicacion = $publicacionManager->getPublicacionById($data);
			if (isset($publicacion[0])){
				$data['json_notificacion'] = $publicacion[0];
				$data['usuario_notificacion'] = $publicacion[0]['usuario_alta'];
			}else{
				return false;
			}
		}else if ($tipo == 'seguidores'){
			$idUsuario = $data['id_publicador'];

			if ($this->validarId($idUsuario) === false){
				return false;
			}
	
			$data['json_notificacion']    = $data;
			$data['usuario_notificacion'] = $idUsuario;
		}else if ($tipo == 'vendedor' || $tipo == 'taggeador'){
		}else{
			return false;
		}

		if ($this->notificacionesDao->altaNotificaciones($data) === false) {
			return false;
		}
		#$idNotificaciones = $this->notificacionesDao->getMsj();
		return true;
	}

	public function modificarNotificaciones(array $data)
	{
		if ($this->validarNotificaciones($data) === false) {
			return false;
		}


		if ($this->notificacionesDao->editarNotificaciones($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->notificacionesDao->getMsj());
		} else {

			$this->setStatus("OK");
			$this->setMsj($this->notificacionesDao->getMsj());
			return true;
		}
	}

	public function eliminarNotificaciones(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		if ($this->validarId($id) === false){
			return false;
		}


		if ($this->notificacionesDao->eliminarNotificaciones($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->notificacionesDao->getMsj());
		} else {

			$this->setStatus("OK");
			$this->setMsj($this->notificacionesDao->getMsj());
		}
	}

	private function validarId ($param){
		$this->setStatus("error");
		$this->setMsj("");
		$validSql = validSqlInjection($param);
		if ($validSql != ''){
			$this->setMsj("Error validación: $validSql.");
		}else{
			$patron = '/^[1-9][0-9]*$/';
			if (preg_match($patron, $param)){
				$this->setStatus("ok");
				return true;
			}else{
				$this->setMsj("El campo id es incorrecto.");
			}
		}
		return false;
	}

	private function validarIdUsuario ($param){
		$this->setStatus("error");
		$this->setMsj("");
		$validSql = validSqlInjection($param);
		if ($validSql != ''){
			$this->setMsj("Error validación: $validSql.");
		}else{
			$patron = '/^[1-9][0-9]*$/';
			if (preg_match($patron, $param)){
				$this->setStatus("ok");
				return true;
			}else{
				$this->setMsj("El campo usuario es incorrecto.");
			}
		}
		return false;
	}

	private function existeId($id)
	{
		$id = isset($id) ? $id : '';
		if ($this->validarId($id) === false) {
			return false;
		}
		if ($this->notificacionesDao->existeId($id) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->productoDao->getMsj());
			return false;
		}
		return true;
	}

	public function getNotificaciones(array $data)
	{

		$id = isset($data["id"]) ? $data["id"] : '';
		if ($this->existeId($id) === false) {
			return [];
		}
	
		$notificaciones = $this->notificacionesDao->getNotificaciones($id);
		if ($this->notificacionesDao->getStatus() != 'ok') {
			$this->setStatus("ERROR");
			$this->setMsj($this->notificacionesDao->getMsj());
			return [];
		}
		$this->setStatus("ok");
		return $notificaciones;
	}

	public function getListNotificaciones()
	{
		$ret =  $this->notificacionesDao->getListNotificaciones();
		return $ret;
	}



        
            private function validarRequest_uri($request_uri)
            {
                $this->setStatus("ERROR");
                $this->setMsj("El campo request_uri es incorrecto.");
                return false;
            }        
            private function validarSeguidor($seguidor)
            {
                if (! preg_match('/^\w+$/i', $seguidor)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo seguidor es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }        
            private function validarNombre_venta($nombre_venta)
            {
                if (! preg_match('/^\w+$/i', $nombre_venta)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo nombre_venta es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }        
            private function validarTipo_venta($tipo_venta)
            {
                if (! preg_match('/^\w+$/i', $tipo_venta)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo tipo_venta es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }                
 
            private function validarId_venta($id_venta)
            {
                if (!preg_match('/^\d+(\.\d{1,2})?$/', $id_venta)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo id_venta es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }        
            private function validarCompra($compra)
            {
                if (! preg_match('/^\w+$/i', $compra)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo compra es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }        
            private function validarFavorito($favorito)
            {
                if (! preg_match('/^\w+$/i', $favorito)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo favorito es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }

}

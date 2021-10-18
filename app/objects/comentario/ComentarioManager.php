<?php
include_once("ComentarioDao.php");
include_once("/var/www/html/app/objects/notificaciones/NotificacionesManager.php");

class  ComentarioManager
{
	private $comentarioDao;
	private $status    = "";
	private $msj       = "";

	public function __construct()
	{
		$this->comentarioDao = new ComentarioDao();
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

	private function validarComentario(array $data) {
		$var_accion = isset($data["accion"]) ? $data["accion"] : '';
		if ($var_accion == 'editar') {
			$id = isset($data["id"]) ? $data["id"] : '';
			if ($this->existeId($id) === false) {
				return false;
			}
		}

	    $publicacion = isset($data["publicacion"]) ? $data["publicacion"] : '';
	    if ($this->validarPublicacion($publicacion) === false){
	     return false;
	    }
	    $comentario = isset($data["comentario"]) ? $data["comentario"] : '';
	    if ($this->validarComentarioPrivate($comentario) === false){
	     return false;
	    }
	    
        if ($this->comentarioDao->existePublicacion($publicacion) === false) {
            $this->setStatus("ERROR");
            $this->setMsj($this->comentarioDao->getMsj());
            return false;
        }


	}



	public function agregarComentario(array $data)
	{

		if ($this->validarComentario($data) === false) {
			return false;
		}


		if ($this->comentarioDao->altaComentario($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->comentarioDao->getMsj());
		} else {
			$idComentario = $this->comentarioDao->getMsj();
			$this->setStatus("OK");
			$this->setMsj($idComentario);


			$notiManager = new NotificacionesManager();
			$data['tipo_notificacion'] = 'comentario';
			
			if ($notiManager->agregarNotificaciones($data) === false) {
				$this->setStatus("ERROR");
				$this->setMsj("No se pudo enviar la notificacion");
			}
		}
	}

	public function modificarComentario(array $data)
	{
		if ($this->validarComentario($data) === false) {
			return false;
		}


		if ($this->comentarioDao->editarComentario($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->comentarioDao->getMsj());
		} else {

			$this->setStatus("OK");
			$this->setMsj($this->comentarioDao->getMsj());
			return true;
		}
	}

	public function eliminarComentario(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		if ($this->validarId($id) === false){
			return false;
		}


		if ($this->comentarioDao->eliminarComentario($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->comentarioDao->getMsj());
		} else {

			$this->setStatus("OK");
			$this->setMsj($this->comentarioDao->getMsj());
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

	private function existeId($id)
	{
		$id = isset($id) ? $id : '';
		if ($this->validarId($id) === false) {
			return false;
		}
		if ($this->comentarioDao->existeId($id) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->comentarioDao->getMsj());
			return false;
		}
		return true;
	}

	public function getComentario(array $data)
	{

		$id = isset($data["id"]) ? $data["id"] : '';
		if ($this->existeId($id) === false) {
			return [];
		}
	
		$comentario = $this->comentarioDao->getComentario($id);
		if ($this->comentarioDao->getStatus() != 'ok') {
			$this->setStatus("ERROR");
			$this->setMsj($this->comentarioDao->getMsj());
			return [];
		}
		$this->setStatus("ok");
		return $comentario;
	}

	public function getListComentario()
	{
		$ret =  $this->comentarioDao->getListComentario();
		return $ret;
	}

   
        
            private function validarUsuario($usuario)
            {
                if (!is_numeric($usuario)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo usuario es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }        
            private function validarPublicacion($publicacion)
            {
                if (!is_numeric($publicacion)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo publicacion es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }        
      
            private function validarComentarioPrivate($comentario)
            {
                if (! preg_match('/^.+$/i', $comentario)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo comentario es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }        

}

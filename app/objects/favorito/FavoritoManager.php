<?php
include_once("FavoritoDao.php");
include_once("/var/www/html/app/objects/notificaciones/NotificacionesManager.php");

class  FavoritoManager
{
	private $favoritoDao;
	private $status    = "";
	private $msj       = "";

	public function __construct()
	{
		$this->favoritoDao = new FavoritoDao();
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

	private function validarFavorito(array $data) {
		$var_accion = isset($data["accion"]) ? $data["accion"] : '';

	    $publicacion = isset($data["id"]) ? $data["id"] : '';
	    if ($this->validarPublicacion($publicacion) === false){
	     return false;
	    }

        if ($this->favoritoDao->existePublicacion($publicacion) === false) {
            $this->setStatus("ERROR");
            $this->setMsj($this->favoritoDao->getMsj());
            return false;
        }

	}



	public function agregarFavorito(array $data)
	{

		if ($this->validarFavorito($data) === false) {
			return false;
		}


		if ($this->favoritoDao->altaFavorito($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->favoritoDao->getMsj());
		} else {
			$idFavorito = $this->favoritoDao->getMsj();
			$this->setStatus("OK");
			$this->setMsj($idFavorito);
			
			$notiManager = new NotificacionesManager();
			$data['tipo_notificacion'] = 'favorito';
			
			if ($notiManager->agregarNotificaciones($data) === false) {
				$this->setStatus("ERROR");
				$this->setMsj("No se pudo enviar la notificacion");
				
			}

		}
	}

	public function modificarFavorito(array $data)
	{
		if ($this->validarFavorito($data) === false) {
			return false;
		}


		if ($this->favoritoDao->editarFavorito($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->favoritoDao->getMsj());
		} else {

			$this->setStatus("OK");
			$this->setMsj($this->favoritoDao->getMsj());
			return true;
		}
	}

	public function eliminarFavorito(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		if ($this->validarId($id) === false){
			return false;
		}


		if ($this->favoritoDao->eliminarFavorito($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->favoritoDao->getMsj());
		} else {

			$this->setStatus("OK");
			$this->setMsj($this->favoritoDao->getMsj());
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
		if ($this->productoDao->existeId($id) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->productoDao->getMsj());
			return false;
		}
		return true;
	}

	public function getFavorito(array $data)
	{

		$id = isset($data["id"]) ? $data["id"] : '';
		if ($this->existeId($id) === false) {
			return [];
		}
	
		$favorito = $this->favoritoDao->getFavorito($id);
		if ($this->favoritoDao->getStatus() != 'ok') {
			$this->setStatus("ERROR");
			$this->setMsj($this->favoritoDao->getMsj());
			return [];
		}
		$this->setStatus("ok");
		return $favorito;
	}



                public function getListUsuario()
                {
                    return $this->claseDao->getListUsuario();
                }                
                public function getListPublicacion()
                {
                    return $this->claseDao->getListPublicacion();
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
            private function validarRequest_uri($request_uri)
            {
                $this->setStatus("ERROR");
                $this->setMsj("El campo request_uri es incorrecto.");
                return false;
            }
}

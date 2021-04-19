<?php
include_once("PublicacionDao.php");
class  PublicacionManager
{
	private $publicacionDao;
	private $status    = "";
	private $msj       = "";

	public function __construct()
	{
		$this->publicacionDao = new PublicacionDao();
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

	private function validarPublicacion(array $data) {
		$var_accion = isset($data["accion"]) ? $data["accion"] : '';
		if ($var_accion == 'editar') {
			$id = isset($data["id"]) ? $data["id"] : '';
			if ($this->existeId($id) === false) {
				return false;
			}
		}

	    $publicacion_nombre = isset($data["publicacion_nombre"]) ? $data["publicacion_nombre"] : '';
	    if ($this->validarPublicacion_nombre($publicacion_nombre) === false){
	     return false;
	    }
	    $publicacion_categoria = isset($data["publicacion_categoria"]) ? $data["publicacion_categoria"] : '';
	    if ($this->validarPublicacion_categoria($publicacion_categoria) === false){
	     return false;
	    }
	    $publicacion_descripcion = isset($data["publicacion_descripcion"]) ? $data["publicacion_descripcion"] : '';
	    if ($this->validarPublicacion_descripcion($publicacion_descripcion) === false){
	     return false;
	    }
	    $pid = isset($data["data_pines"]) ? $data["data_pines"] : '';
	    if ($this->validarPublicacion_pid($pid) === false){
	     return false;
	    }

        if ($this->publicacionDao->existePublicacion_categoria($publicacion_categoria) === false) {
            $this->setStatus("ERROR");
            $this->setMsj($this->publicacionDao->getMsj());
            return false;
       	}

	    $publicacion_foto = isset($data["foto_base64"]) ? $data["foto_base64"] : '';
	    if ($this->validarPublicacion_foto($publicacion_foto) === false){
	      return false;
	    }

	}



	public function agregarPublicacion(array $data)
	{

		if ($this->validarPublicacion($data) === false) {
			return false;
		}


		if ($this->publicacionDao->altaPublicacion($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->publicacionDao->getMsj());
		} else {
			$idPublicacion = $this->publicacionDao->getMsj();

			
	            $valor = isset($data["foto_base64"]) ? $data["foto_base64"] : '';
                    $dataPublicacion_foto = array(
                        "id_publicacion" => $idPublicacion,
                        "publicacion_foto"        => $valor
                    );

                    if ( $this->publicacionDao->altaPublicacion_foto($dataPublicacion_foto) === false) {
                        $this->setStatus("ERROR");
                        $this->setMsj($this->publicacionDao->getMsj());
                        return false;
                    }
			
			$this->setStatus("OK");
			$this->setMsj($idPublicacion);
		}
	}

	public function modificarPublicacion(array $data)
	{
		if ($this->validarPublicacion($data) === false) {
			return false;
		}


		if ($this->publicacionDao->editarPublicacion($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->publicacionDao->getMsj());
		} else {
                if ($this->publicacionDao->eliminarPublicacion_foto($data) === false) {
                    $this->setStatus("ERROR");
                    $this->setMsj("No se pudo eactualizar Publicacion_foto");
                    return false;
                } else {
                    $idPublicacion = isset($data["id"]) ? $data["id"] : '';
	            $valor = isset($data["foto_base64"]) ? $data["foto_base64"] : '';
                        $dataPublicacion_foto = array(
                            "id_publicacion" => $idPublicacion,
                            "publicacion_foto"        => $valor
                        );
    
                        if ($this->publicacionDao->altaPublicacion_foto($dataPublicacion_foto) === false) {
                            $this->setStatus("ERROR");
                            $this->setMsj($this->publicacionDao->getMsj());
                            return false;
                        }
                }
			$this->setStatus("OK");
			$this->setMsj($this->publicacionDao->getMsj());
			return true;
		}
	}

	public function eliminarPublicacion(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		if ($this->validarId($id) === false){
			return false;
		}


		if ($this->publicacionDao->eliminarPublicacion($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->publicacionDao->getMsj());
		} else {
                if ($this->publicacionDao->eliminarPublicacion_foto($data) === false) {
                    $this->setStatus("ERROR");
                    $this->setMsj($this->publicacionDao->getMsj());
                    return false;
                } 
			$this->setStatus("OK");
			$this->setMsj($this->publicacionDao->getMsj());
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
		if ($this->publicacionDao->existeId($id) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->publicacionDao->getMsj());
			return false;
		}
		return true;
	}

	

	        
            private function validarPublicacion_nombre($publicacion_nombre)
            {
                if (! preg_match('/^.+$/i', $publicacion_nombre)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El nombre de la publicacón es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }        
            private function validarPublicacion_categoria($publicacion_categoria)
            {
                if (!is_numeric($publicacion_categoria)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo categoria  $publicacion_categoria es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }        
            private function validarPublicacion_foto($publicacion_foto)
            {
                if (! preg_match('/^data.*$/i', $publicacion_foto)){
                    $this->setStatus("ERROR");
                    $this->setMsj("La foto es incorrecta.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }        
            private function validarPublicacion_pid($publicacion_descripcion)
            {
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
			}
            private function validarPublicacion_descripcion($publicacion_descripcion)
            {
                if (! preg_match('/^.+$/im', $publicacion_descripcion)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo publicación es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
			}
			
			public function getPublicacion(array $data)
			{
		
				$id = isset($data["id"]) ? $data["id"] : '';
				if ($this->existeId($id) === false) {
					return [];
				}
			
				$publicacion = $this->publicacionDao->getPublicacion($id);
				if ($this->publicacionDao->getStatus() != 'ok') {
					$this->setStatus("ERROR");
					$this->setMsj($this->publicacionDao->getMsj());
					return [];
				}
				$this->setStatus("ok");
				return $publicacion;
			}

			public function getListCategoria()
			{
				return $this->publicacionDao->getListCategoria();
			}

			public function getListPublicacion()
			{
				$ret =  $this->publicacionDao->getListPublicacion();
				return $ret;
			}
			public function getListPublicacionIndex()
			{
				$ret =  $this->publicacionDao->getListPublicacionIndex();
				return $ret;
			}
		
}

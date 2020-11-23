<?php
include_once("PublicacionDao.php");
class  PublicacionManager
{
	private $publicacionDao;
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


	public function agregarPublicacion(array $data)
	{
		
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
		
		$this->publicacionDao = new PublicacionDao();

		
                    if ($this->publicacionDao->existePublicacion_categoria($publicacion_categoria) === false) {
                        $this->setStatus("ERROR");
                        $this->setMsj($this->publicacionDao->getMsj());
                        return false;
                    }

		if ($this->publicacionDao->altaPublicacion($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->publicacionDao->getMsj());
		} else {
			$idPublicacion = $this->publicacionDao->getMsj();

			
                foreach ($_POST["publicacion_foto"] as $valor) {
                    $valor = isset($valor) ?  $valor : '';
		            if ($this->validarPublicacion_foto( $valor) === false){
			            return false;
		            }
                    $dataPublicacion_foto = array(
                        "id_publicacion" => $idProducto,
                        "publicacion_foto"        => $valor
                    );

                    if ( $this->publicacionDao->altaPublicacion_foto($dataPublicacion_foto) === false) {
                        $this->setStatus("ERROR");
                        $this->setMsj($this->publicacionDao->getMsj());
                        return false;
                    }
                }
                
			
			$this->setStatus("OK");
			$this->setMsj($idPublicacion);
		}
	}

	public function modificarPublicacion(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		if ($this->validarId($id) === false){
			return false;
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
		$this->publicacionDao = new PublicacionDao();


		if ($this->publicacionDao->editarPublicacion($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->publicacionDao->getMsj());
		} else {
			$this->setStatus("OK");
			$this->setMsj($this->publicacionDao->getMsj());
		}
	}

	public function eliminarPublicacion(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		if ($this->validarId($id) === false){
			return false;
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
		$this->publicacionDao = new PublicacionDao();


		if ($this->publicacionDao->eliminarPublicacion($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->publicacionDao->getMsj());
		} else {
			$this->setStatus("OK");
			$this->setMsj($this->publicacionDao->getMsj());
		}
	}

	public function getPublicacion(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		if ($this->validarId($id) === false){
			return false;
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
				$this->publicacionDao = new PublicacionDao();


				if ($this->publicacionDao->getPublicacion($data) === false) {
					$this->setStatus("ERROR");
					$this->setMsj($this->publicacionDao->getMsj());
				} else {
					$this->setStatus("OK");
					$this->setMsj($this->publicacionDao->getMsj());
				}
	}

	public function listarPublicacion(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		if ($this->validarId($id) === false){
			return false;
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
				$this->publicacionDao = new PublicacionDao();


				if ($this->publicacionDao->listarPublicacion($data) === false) {
					$this->setStatus("ERROR");
					$this->setMsj($this->publicacionDao->getMsj());
				} else {
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
	        
            private function validarPublicacion_nombre($publicacion_nombre)
            {
                if (! preg_match('/^\w+$/i', $publicacion_nombre)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo publicacion_nombre es incorrecto.");
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
                    $this->setMsj("El campo publicacion_categoria es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }        
            private function validarPublicacion_foto($publicacion_foto)
            {
                if (! preg_match('/^\w+$/i', $publicacion_foto)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo publicacion_foto es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }        
            private function validarPublicacion_descripcion($publicacion_descripcion)
            {
                if (! preg_match('/^\w+$/i', $publicacion_descripcion)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo publicacion_descripcion es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }
}

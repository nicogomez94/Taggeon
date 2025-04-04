<?php
include_once("PublicacionDao.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."busqueda/BusquedaManager.php");

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
	    $publicacion_descripcion = isset($data["publicacion_descripcion"]) ? $data["publicacion_descripcion"] : '';
	    if ($this->validarPublicacion_descripcion($publicacion_descripcion) === false){
	     return false;
	    }
	    $pid = isset($data["data_pines"]) ? $data["data_pines"] : '';
	    if ($this->validarPublicacion_pid($pid) === false){
	     return false;
		}
		/*nico*/
		$aspect_ratio = isset($data["aspect_ratio"]) ? $data["aspect_ratio"] : '';
	    if ($this->validarPublicacion_aspect_ratio($aspect_ratio) === false){
	     return false;
	    }

	    $publicacion_foto = isset($data["foto_base64"]) ? $data["foto_base64"] : '';
	    if ($this->validarPublicacion_foto($publicacion_foto) === false){
	      return false;
	    }
	    $estilo = isset($data["ESTILO"]) ? $data["ESTILO"] : '';
	    if ($this->validarEstilo($estilo) === false){
	     return false;
	    }

	}



	public function agregarPublicacion(array $data)
	{
	    	$estilo = isset($data["ESTILO"]) ? $data["ESTILO"] : '';
		$estiloArray = explode("-",$estilo);
		$estilo = $estiloArray[0];
		$data["ESTILO"] = isset($estilo) ? $estilo : '';

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

	    	$estilo = isset($data["ESTILO"]) ? $data["ESTILO"] : '';
		$estiloArray = explode("-",$estilo);
		$estilo = $estiloArray[0];
		$data["ESTILO"] = isset($estilo) ? $estilo : '';

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
    
                        if ($this->publicacionDao->editarPublicacion_foto($dataPublicacion_foto) === false) {
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
	private function validarEscena ($param){
		$this->setStatus("error");
		$this->setMsj("");
		$validSql = validSqlInjection($param);
		if ($validSql != ''){
			$this->setMsj("Error validación: $validSql.");
		}else{
			$patron = '/^ARQUITECTURA$/i';
			if (preg_match($patron, $param)){
				$this->setStatus("ok");
				return true;
			}else{
				$this->setMsj("El campo escena es incorrecto.");
			}
		}
		return false;
	}
	private function validarEstilo ($param){
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
				$this->setMsj("El campo estilo es incorrecto.");
			}
		}
		return false;
	}

	private function validarIdEstilo ($param){
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
				$this->setMsj("El campo estilo es incorrecto.");
			}
		}
		return false;
	}

	private function validarIdCat ($param){
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
				$this->setMsj("El campo categoria es incorrecto.");
			}
		}
		return false;
	}

	private function validarCantidad ($param){
		$this->setStatus("error");
		$this->setMsj("");
		$validSql = validSqlInjection($param);
		if ($validSql != ''){
			$this->setMsj("Error validación: $validSql.");
		}else{
			$patron = '/^[0-9]+$/';
			if (preg_match($patron, $param)){
				$this->setStatus("ok");
				return true;
			}else{
				$this->setMsj("El campo cantidad es incorrecto.");
			}
		}
		return false;
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
			//nico
			private function validarPublicacion_aspect_ratio($aspect_ratio)
            {
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

			public function getPublicacionByIdNoti(array $data)
			{
				$id = isset($data["id"]) ? $data["id"] : '';
				if ($id == ''){
					$id = isset($data["id_publicacion"]) ? $data["id_publicacion"] : '';
				}
				if ($this->validarId($id) === false) {
					return [];
				}
				
			
				$publicacion = $this->publicacionDao->getPublicacionByIdNoti($id);
				return $publicacion;
			}

			public function getPublicacionById(array $data)
			{
				$id = isset($data["id"]) ? $data["id"] : '';
				if ($id == ''){
					$id = isset($data["id_publicacion"]) ? $data["id_publicacion"] : '';
				}
				if ($this->validarId($id) === false) {
					return [];
				}
				
			
				$publicacion = $this->publicacionDao->getPublicacionById($id);
				return $publicacion;
			}

			public function getPublicacionByIdYProducto(array $data)
			{
				$id = isset($data["id_publicacion"]) ? $data["id_publicacion"] : '';

				if ($this->validarId($id) === false) {
					return [];
				}
				
				if (!is_numeric($data["id_producto"])){
					return [];
				}
			
				$publicacion = $this->publicacionDao->getPublicacionByIdYProducto($data);
				return $publicacion;
	 }
	public function searchSubEscena2(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		if ($this->validarId($id) === false) {
			return false;
		}

		if ($this->publicacionDao->searchSubEscena2($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->publicacionDao->getMsj());
			return false;
		} else {
			$this->setStatus("OK");
			$this->setMsj($this->publicacionDao->getMsj());
			return 'ok';
		}
	}
	public function searchSubEscena(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		if ($this->validarId($id) === false) {
			return false;
		}

		if ($this->publicacionDao->searchSubEscena($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->publicacionDao->getMsj());
			return false;
		} else {
			$this->setStatus("OK");
			$this->setMsj($this->publicacionDao->getMsj());
			return 'ok';
		}
	}

			public function getListEscena2()
			{
				return $this->publicacionDao->getListEscena2();
			}
			public function getListEscena()
			{
				return $this->publicacionDao->getListEscena();
			}
                     public function getListPublicacion()
                       {
                               $ret =  $this->publicacionDao->getListPublicacion();
                               return $ret;
                       }

                     public function getListPublicacionPublic()
                       {
				$id_usuario  = isset($_GET["id_usuario"]) ? $_GET["id_usuario"] : '';
                               $ret =  $this->publicacionDao->getListPublicacionPublic($id_usuario);
                               return $ret;
                       }
			public function getListAmpliarublicacionIndexPaginador()
			{
				$ret =  $this->publicacionDao->getListAmpliarPublicacionIndexPaginador();
				return $ret;
			}
			public function getListPublicacionEscenaCategoriaEstilo()
			{
				$id_categoria = isset($_GET["cat"]) ? $_GET["cat"] : '';
				if ($this->validarIdCat($id_categoria) === false) {
					return array();
				}
				$id_estilo = isset($_GET["estilo"]) ? $_GET["estilo"] : '';
				if ($this->validarIdEstilo($id_estilo) === false) {
					return array();
				}
				$escena = isset($_GET["escena"]) ? $_GET["escena"] : '';
			        if ($this->validarEscena($escena) === false){
					return array();
			        }

				$cantidad = isset($_GET["cant"]) ? $_GET["cant"] : 0;
				if ($this->validarCantidad($cantidad) === false) {
					return array();
				}

				$ret =  $this->publicacionDao->getListPublicacionEscenaCategoriaEstilo();
				return $ret;
			}
			public function getListPublicacionIndexPaginador()
			{
				$ret =  $this->publicacionDao->getListPublicacionIndexPaginador();
				return $ret;
			}

			public function getListPublicacionIndexDinamico()
			{
				$ret =  $this->publicacionDao->getListPublicacionIndexDinamico();
				return $ret;
			}

			public function getListAmpliarPublicacionIndex()
			{
				$ret =  $this->publicacionDao->getListAmpliarPublicacionIndex();
				return $ret;
			}

			public function getListPublicacionIndex()
			{
				$id = isset($data["id"]) ? $data["id"] : '';
				if ($this->existeId($id) === false) {
					return [];
				}
				$ret =  $this->publicacionDao->getListPublicacionIndex();
				return $ret;
			}

			public function getListPublicacionFavoritos()
			{
				$ret =  $this->publicacionDao->getListPublicacionFavoritos();
				return $ret;
			}


			public function searchIndex(array $data)
			{
				$input = isset($data["input"]) ? $data["input"] : '';
				if ($this->validarInputSearch($input) === false) {
					return [];
				}
		
				$list = $this->publicacionDao->searchIndex($data);

		
				if (count($list)>0){
					$obj = new BusquedaManager();
					$obj->agregarBusqueda($data);
				}
				return $list; 
			}

			private function validarInputSearch($input)
			{
				if (!preg_match('/^.+$/i', $input)) {
					$this->setStatus("ERROR");
					$this->setMsj("El campo input es incorrecto.");
					return false;
				}
				$this->setStatus("OK");
				$this->setMsj("");
				return true;
			}
			public function getColumnasCategoria($escena)
			{
			    if ($this->validarEscena($escena) === false){
					return array();
			    }

				return $this->publicacionDao->getColumnasCategoria($escena);
			}
		
		
}

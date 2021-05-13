<?php
include_once("SeguidoresDao.php");
class  SeguidoresManager
{
	private $seguidoresDao;
	private $status    = "";
	private $msj       = "";

	public function __construct()
	{
		$this->seguidoresDao = new SeguidoresDao();
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

	private function validarSeguidores(array $data) {
		$var_accion = isset($data["accion"]) ? $data["accion"] : '';
		if ($var_accion == 'editar') {
			$id = isset($data["id"]) ? $data["id"] : '';
			if ($this->existeId($id) === false) {
				return false;
			}
		}


	    $publicador = isset($data["id_publicador"]) ? $data["id_publicador"] : '';
	    if ($this->validarSeguidor($publicador) === false){
	     return false;
	    }

        if ($this->seguidoresDao->existeSeguidor($publicador) === false) {
            $this->setStatus("ERROR");
            $this->setMsj($this->seguidoresDao->getMsj());
            return false;
        }

	}



	public function agregarSeguidores(array $data)
	{

		if ($this->validarSeguidores($data) === false) {
			return false;
		}


		if ($this->seguidoresDao->altaSeguidores($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->seguidoresDao->getMsj());
		} else {
			$idSeguidores = $this->seguidoresDao->getMsj();

			
			
			$this->setStatus("OK");
			$this->setMsj($idSeguidores);
		}
	}

	public function modificarSeguidores(array $data)
	{
		if ($this->validarSeguidores($data) === false) {
			return false;
		}


		if ($this->seguidoresDao->editarSeguidores($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->seguidoresDao->getMsj());
		} else {

			$this->setStatus("OK");
			$this->setMsj($this->seguidoresDao->getMsj());
			return true;
		}
	}

	public function eliminarSeguidores(array $data)
	{
		$id = isset($data["id_publicador"]) ? $data["id_publicador"] : '';
		if ($this->validarId($id) === false){
			return false;
		}


		if ($this->seguidoresDao->eliminarSeguidores($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->seguidoresDao->getMsj());
		} else {

			$this->setStatus("OK");
			$this->setMsj($this->seguidoresDao->getMsj());
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

	public function getListSeguidores()
	{
		$ret =  $this->seguidoresDao->getListSeguidores();
		return $ret;
	}

	public function getListSeguidos()
	{
		$ret =  $this->seguidoresDao->getListSeguidos();
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
            private function validarSeguidor($seguidor)
            {
                if (!is_numeric($seguidor)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo seguidor es incorrecto.");
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

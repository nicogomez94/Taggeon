<?php
include_once("MegustaDao.php");
class  MegustaManager
{
	private $megustaDao;
	private $status    = "";
	private $msj       = "";

	public function __construct()
	{
		$this->megustaDao = new MegustaDao();
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

	private function validarMegusta(array $data) {
		$var_accion = isset($data["accion"]) ? $data["accion"] : '';
		if ($var_accion == 'editar') {
			$id = isset($data["id"]) ? $data["id"] : '';
			if ($this->existeId($id) === false) {
				return false;
			}
		}

	    $usuario = isset($data["usuario"]) ? $data["usuario"] : '';
	    if ($this->validarUsuario($usuario) === false){
	     return false;
	    }
	    $publicacion = isset($data["publicacion"]) ? $data["publicacion"] : '';
	    if ($this->validarPublicacion($publicacion) === false){
	     return false;
	    }

        if ($this->megustaDao->existeUsuario($usuario) === false) {
            $this->setStatus("ERROR");
            $this->setMsj($this->megustaDao->getMsj());
            return false;
        }
        if ($this->megustaDao->existePublicacion($publicacion) === false) {
            $this->setStatus("ERROR");
            $this->setMsj($this->megustaDao->getMsj());
            return false;
        }

	}



	public function agregarMegusta(array $data)
	{

		if ($this->validarMegusta($data) === false) {
			return false;
		}


		if ($this->megustaDao->altaMegusta($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->megustaDao->getMsj());
		} else {
			$idMegusta = $this->megustaDao->getMsj();

			
			
			$this->setStatus("OK");
			$this->setMsj($idMegusta);
		}
	}

	public function modificarMegusta(array $data)
	{
		if ($this->validarMegusta($data) === false) {
			return false;
		}


		if ($this->megustaDao->editarMegusta($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->megustaDao->getMsj());
		} else {

			$this->setStatus("OK");
			$this->setMsj($this->megustaDao->getMsj());
			return true;
		}
	}

	public function eliminarMegusta(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		if ($this->validarId($id) === false){
			return false;
		}


		if ($this->megustaDao->eliminarMegusta($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->megustaDao->getMsj());
		} else {

			$this->setStatus("OK");
			$this->setMsj($this->megustaDao->getMsj());
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

	public function getMegusta(array $data)
	{

		$id = isset($data["id"]) ? $data["id"] : '';
		if ($this->existeId($id) === false) {
			return [];
		}
	
		$megusta = $this->megustaDao->getMegusta($id);
		if ($this->megustaDao->getStatus() != 'ok') {
			$this->setStatus("ERROR");
			$this->setMsj($this->megustaDao->getMsj());
			return [];
		}
		$this->setStatus("ok");
		return $megusta;
	}

	public function getListMegusta()
	{
		$ret =  $this->megustaDao->getListMegusta();
		return $ret;
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
}

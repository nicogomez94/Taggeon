<?php
include_once("InteresesDao.php");
class  InteresesManager
{
	private $interesesDao;
	private $status    = "";
	private $msj       = "";

	public function __construct()
	{
		$this->interesesDao = new InteresesDao();
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

	private function validarIntereses(array $data) {
		$var_accion = isset($data["accion"]) ? $data["accion"] : '';
		if ($var_accion == 'editar') {
			$id = isset($data["id"]) ? $data["id"] : '';
			if ($this->existeId($id) === false) {
				return false;
			}
		}

	    $publicacion_categoria = isset($data["publicacion_categoria"]) ? $data["publicacion_categoria"] : '';
	    if ($this->validarPublicacion_categoria($publicacion_categoria) === false){
	     return false;
	    }

        if ($this->interesesDao->existePublicacion_categoria($publicacion_categoria) === false) {
            $this->setStatus("ERROR");
            $this->setMsj($this->interesesDao->getMsj());
            return false;
        }

	}



	public function agregarIntereses(array $data)
	{

		if ($this->validarIntereses($data) === false) {
			return false;
		}


		if ($this->interesesDao->altaIntereses($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->interesesDao->getMsj());
		} else {
			$idIntereses = $this->interesesDao->getMsj();

			
			
			$this->setStatus("OK");
			$this->setMsj($idIntereses);
		}
	}

	public function modificarIntereses(array $data)
	{
		if ($this->validarIntereses($data) === false) {
			return false;
		}


		if ($this->interesesDao->editarIntereses($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->interesesDao->getMsj());
		} else {

			$this->setStatus("OK");
			$this->setMsj($this->interesesDao->getMsj());
			return true;
		}
	}

	public function eliminarIntereses(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		if ($this->validarId($id) === false){
			return false;
		}


		if ($this->interesesDao->eliminarIntereses($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->interesesDao->getMsj());
		} else {

			$this->setStatus("OK");
			$this->setMsj($this->interesesDao->getMsj());
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

	public function getIntereses(array $data)
	{

		$id = isset($data["id"]) ? $data["id"] : '';
		if ($this->existeId($id) === false) {
			return [];
		}
	
		$intereses = $this->interesesDao->getIntereses($id);
		if ($this->interesesDao->getStatus() != 'ok') {
			$this->setStatus("ERROR");
			$this->setMsj($this->interesesDao->getMsj());
			return [];
		}
		$this->setStatus("ok");
		return $intereses;
	}

	public function getListIntereses()
	{
		$ret =  $this->interesesDao->getListIntereses();
		return $ret;
	}


                public function getListPublicacion_categoria()
                {
                    return $this->claseDao->getListPublicacion_categoria();
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
}

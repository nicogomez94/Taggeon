<?php
include_once("RubroDao.php");
class  RubroManager
{
	private $rubroDao;
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


	public function agregarRubro(array $data)
	{
		
		            $nombre = isset($data["nombre"]) ? $data["nombre"] : '';
		            if ($this->validarNombre($nombre) === false){
			            return false;
		            }
		            $categoria = isset($data["categoria"]) ? $data["categoria"] : '';
		            if ($this->validarCategoria($categoria) === false){
			            return false;
		            }
		
		$this->rubroDao = new RubroDao();

		
                    if ($this->rubroDao->existeCategoria($categoria) === false) {
                        $this->setStatus("ERROR");
                        $this->setMsj($this->rubroDao->getMsj());
                        return false;
                    }

		if ($this->rubroDao->altaRubro($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->rubroDao->getMsj());
		} else {
			$idRubro = $this->rubroDao->getMsj();

			
			
			$this->setStatus("OK");
			$this->setMsj($idRubro);
		}
	}

	public function modificarRubro(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		if ($this->validarId($id) === false){
			return false;
		}

		
		            $nombre = isset($data["nombre"]) ? $data["nombre"] : '';
		            if ($this->validarNombre($nombre) === false){
			            return false;
		            }
		            $categoria = isset($data["categoria"]) ? $data["categoria"] : '';
		            if ($this->validarCategoria($categoria) === false){
			            return false;
		            }
		$this->rubroDao = new RubroDao();


		if ($this->rubroDao->editarRubro($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->rubroDao->getMsj());
		} else {
			$this->setStatus("OK");
			$this->setMsj($this->rubroDao->getMsj());
		}
	}

	public function eliminarRubro(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		if ($this->validarId($id) === false){
			return false;
		}
		
		            $nombre = isset($data["nombre"]) ? $data["nombre"] : '';
		            if ($this->validarNombre($nombre) === false){
			            return false;
		            }
		            $categoria = isset($data["categoria"]) ? $data["categoria"] : '';
		            if ($this->validarCategoria($categoria) === false){
			            return false;
		            }
		$this->rubroDao = new RubroDao();


		if ($this->rubroDao->eliminarRubro($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->rubroDao->getMsj());
		} else {
			$this->setStatus("OK");
			$this->setMsj($this->rubroDao->getMsj());
		}
	}

	public function getRubro(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		if ($this->validarId($id) === false){
			return false;
		}
				
		            $nombre = isset($data["nombre"]) ? $data["nombre"] : '';
		            if ($this->validarNombre($nombre) === false){
			            return false;
		            }
		            $categoria = isset($data["categoria"]) ? $data["categoria"] : '';
		            if ($this->validarCategoria($categoria) === false){
			            return false;
		            }
				$this->rubroDao = new RubroDao();


				if ($this->rubroDao->getRubro($data) === false) {
					$this->setStatus("ERROR");
					$this->setMsj($this->rubroDao->getMsj());
				} else {
					$this->setStatus("OK");
					$this->setMsj($this->rubroDao->getMsj());
				}
	}

	public function listarRubro(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		if ($this->validarId($id) === false){
			return false;
		}
				
		            $nombre = isset($data["nombre"]) ? $data["nombre"] : '';
		            if ($this->validarNombre($nombre) === false){
			            return false;
		            }
		            $categoria = isset($data["categoria"]) ? $data["categoria"] : '';
		            if ($this->validarCategoria($categoria) === false){
			            return false;
		            }
				$this->rubroDao = new RubroDao();


				if ($this->rubroDao->listarRubro($data) === false) {
					$this->setStatus("ERROR");
					$this->setMsj($this->rubroDao->getMsj());
				} else {
					$this->setStatus("OK");
					$this->setMsj($this->rubroDao->getMsj());
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
	        
            private function validarNombre($nombre)
            {
                if (! preg_match('/^\w+$/i', $nombre)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo nombre es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }        
            private function validarCategoria($categoria)
            {
                if (!is_numeric($categoria)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo categoria es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }
}

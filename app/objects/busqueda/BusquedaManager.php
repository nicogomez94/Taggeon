<?php
include_once("BusquedaDao.php");

class  BusquedaManager
{
	private $busquedaDao;
	private $status    = "";
	private $msj       = "";

	public function __construct()
	{
		$this->busquedaDao = new BusquedaDao();
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

	private function validarBusqueda(array $data) {
		$var_accion = isset($data["accion"]) ? $data["accion"] : '';
		if ($var_accion == 'editar') {
			$id = isset($data["id"]) ? $data["id"] : '';
			if ($this->existeId($id) === false) {
				return false;
			}
		}

	    $search = isset($data["input"]) ? $data["input"] : '';
	    if ($this->validarSearch($search) === false){
	     return false;
	    }
	}

	public function search(array $data)
	{
		$input = isset($data["input"]) ? $data["input"] : '';
		if ($this->validarInputSearch($input) === false) {
			return false;
		}

		if ($this->busquedaDao->search($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->busquedaDao->getMsj());
			return false;
		} else {
			$this->setStatus("OK");
			$this->setMsj($this->busquedaDao->getMsj());
			return 'ok';
		}
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

	public function agregarBusqueda(array $data)
	{


		if ($this->validarBusqueda($data) === false) {
			return false;
		}
		if ($this->busquedaDao->altaBusqueda($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->busquedaDao->getMsj());
		} else {
			
			$idBusqueda = $this->busquedaDao->getMsj();
			$this->setStatus("OK");
			$this->setMsj($idBusqueda);
		}
	}

	public function modificarBusqueda(array $data)
	{
		if ($this->validarBusqueda($data) === false) {
			return false;
		}


		if ($this->busquedaDao->editarBusqueda($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->busquedaDao->getMsj());
		} else {

			$this->setStatus("OK");
			$this->setMsj($this->busquedaDao->getMsj());
			return true;
		}
	}

	public function eliminarBusqueda(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		if ($this->validarId($id) === false){
			return false;
		}


		if ($this->busquedaDao->eliminarBusqueda($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->busquedaDao->getMsj());
		} else {

			$this->setStatus("OK");
			$this->setMsj($this->busquedaDao->getMsj());
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

	public function getBusqueda(array $data)
	{

		$id = isset($data["id"]) ? $data["id"] : '';
		if ($this->existeId($id) === false) {
			return [];
		}
	
		$busqueda = $this->busquedaDao->getBusqueda($id);
		if ($this->busquedaDao->getStatus() != 'ok') {
			$this->setStatus("ERROR");
			$this->setMsj($this->busquedaDao->getMsj());
			return [];
		}
		$this->setStatus("ok");
		return $busqueda;
	}

	public function getListBusqueda()
	{
		$ret =  $this->busquedaDao->getListBusqueda();
		return $ret;
	}



        
            private function validarSearch($search)
            {
                if (! preg_match('/^\w+$/i', $search)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo search es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }    

private function validarContador($contador)
{
    if (!preg_match('/^\d+$/', $contador)){
        $this->setStatus("ERROR");
        $this->setMsj("El campo contador es incorrecto.");
        return false;
    }
    $this->setStatus("OK");
    $this->setMsj("");
    return true;
}
}

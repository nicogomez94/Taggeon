<?php
include_once("ComentarioproductoDao.php");
class  ComentarioproductoManager
{
	private $comentarioproductoDao;
	private $status    = "";
	private $msj       = "";

	public function __construct()
	{
		$this->comentarioproductoDao = new ComentarioproductoDao();
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

	private function validarComentarioproducto(array $data) {
		$var_accion = isset($data["accion"]) ? $data["accion"] : '';
		if ($var_accion == 'editar') {
			$id = isset($data["id"]) ? $data["id"] : '';
			if ($this->existeId($id) === false) {
				return false;
			}
		}

	    $comentario = isset($data["comentario"]) ? $data["comentario"] : '';
	    if ($this->validarComentario($comentario) === false){
	     return false;
	    }
	    $producto = isset($data["producto"]) ? $data["producto"] : '';
	    if ($this->validarProducto($producto) === false){
	     return false;
	    }

        if ($this->comentarioproductoDao->existeProducto($producto) === false) {
            $this->setStatus("ERROR");
            $this->setMsj($this->comentarioproductoDao->getMsj());
            return false;
        }

	}



	public function agregarComentarioproducto(array $data)
	{

		if ($this->validarComentarioproducto($data) === false) {
			return false;
		}


		if ($this->comentarioproductoDao->altaComentarioproducto($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->comentarioproductoDao->getMsj());
		} else {
			$idComentarioproducto = $this->comentarioproductoDao->getMsj();

			
			
			$this->setStatus("OK");
			$this->setMsj($idComentarioproducto);
		}
	}

	public function modificarComentarioproducto(array $data)
	{
		if ($this->validarComentarioproducto($data) === false) {
			return false;
		}


		if ($this->comentarioproductoDao->editarComentarioproducto($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->comentarioproductoDao->getMsj());
		} else {

			$this->setStatus("OK");
			$this->setMsj($this->comentarioproductoDao->getMsj());
			return true;
		}
	}

	public function eliminarComentarioproducto(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		if ($this->validarId($id) === false){
			return false;
		}


		if ($this->comentarioproductoDao->eliminarComentarioproducto($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->comentarioproductoDao->getMsj());
		} else {

			$this->setStatus("OK");
			$this->setMsj($this->comentarioproductoDao->getMsj());
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

	public function getComentarioproducto(array $data)
	{

		$id = isset($data["id"]) ? $data["id"] : '';
		if ($this->existeId($id) === false) {
			return [];
		}
	
		$comentarioproducto = $this->comentarioproductoDao->getComentarioproducto($id);
		if ($this->comentarioproductoDao->getStatus() != 'ok') {
			$this->setStatus("ERROR");
			$this->setMsj($this->comentarioproductoDao->getMsj());
			return [];
		}
		$this->setStatus("ok");
		return $comentarioproducto;
	}

	public function getListComentarioproducto()
	{
		$id = isset($_GET["id_producto"]) ? $_GET["id_producto"] : '';
		if ($this->validarId($id) === false){
			return false;
		}
		$ret =  $this->comentarioproductoDao->getListComentarioproducto();
		return $ret;
	}


                public function getListProducto()
                {
                    return $this->comentarioproductoDao->getListProducto();
                }                

        
            private function validarComentario($comentario)
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
            private function validarProducto($producto)
            {
                if (!is_numeric($producto)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo producto es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }
}

<?php
include_once("CarritoDao.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."producto/ProductoManager.php");
class  CarritoManager
{
	private $carritoDao;
	private $productoManager;
	private $status    = "";
	private $msj       = "";

	public function __construct()
	{
		$this->carritoDao = new CarritoDao();
		$this->productoManager = new ProductoManager();
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

	private function validarCarrito(array $data) {
		$var_accion = isset($data["accion"]) ? $data["accion"] : '';
		if ($var_accion == 'editar') {
			$id = isset($data["id"]) ? $data["id"] : '';
			if ($this->existeId($id) === false) {
				return false;
			}
		}

	    $tipo = isset($data["tipo"]) ? $data["tipo"] : '';
	    if ($this->validarTipo($tipo) === false){
	     return false;
	    }
	    $subtotal = isset($data["subtotal"]) ? $data["subtotal"] : '';
	    if ($this->validarSubtotal($subtotal) === false){
	     return false;
	    }
	    $total = isset($data["total"]) ? $data["total"] : '';
	    if ($this->validarTotal($total) === false){
	     return false;
	    }
	    $envio_nombre_apellido = isset($data["envio_nombre_apellido"]) ? $data["envio_nombre_apellido"] : '';
	    if ($this->validarEnvio_nombre_apellido($envio_nombre_apellido) === false){
	     return false;
	    }
	    $envio_codigo_postal = isset($data["envio_codigo_postal"]) ? $data["envio_codigo_postal"] : '';
	    if ($this->validarEnvio_codigo_postal($envio_codigo_postal) === false){
	     return false;
	    }
	    $envio_ciudad_localidad = isset($data["envio_ciudad_localidad"]) ? $data["envio_ciudad_localidad"] : '';
	    if ($this->validarEnvio_ciudad_localidad($envio_ciudad_localidad) === false){
	     return false;
	    }
	    $email = isset($data["email"]) ? $data["email"] : '';
	    if ($this->validarEmail($email) === false){
	     return false;
	    }
	    $notas = isset($data["notas"]) ? $data["notas"] : '';
	    if ($this->validarNotas($notas) === false){
	     return false;
	    }

        if ($this->carritoDao->existeTipo($tipo) === false) {
            $this->setStatus("ERROR");
            $this->setMsj($this->carritoDao->getMsj());
            return false;
        }

	}

	public function agregarCarrito(array $data)
	{

		$data["id_carrito"] = $this->carritoDao->getIdCarrito();

		if (!is_numeric($data["id_carrito"])){
			$this->setStatus("ERROR");
			$this->setMsj("El id de carrito es incorrecto.");
			return false;
		}

		if ($data["id_carrito"] <= 0){
			if ($this->carritoDao->altaCarrito($data) === false) {
				$this->setStatus("ERROR");
				$this->setMsj($this->carritoDao->getMsj());
				return false;
			}else{
				$data["id_carrito"] = $this->carritoDao->getMsj();
			}
		}

		if (!is_numeric($data["id_carrito"])){
			$this->setStatus("ERROR");
			$this->setMsj("El id ". $data["id_carrito"] ."de carrito generado es incorrecto.");
			return false;
		}

		if ($data["id_carrito"] <= 0){
			$this->setStatus("ERROR");
			$this->setMsj("El id ". $data["id_carrito"] ."de carrito generado es incorrecto.");
			return false;
		}


		#valida el manager de producto el id_producto
		$data["id_producto"] = isset($data["id"]) ? $data["id"] : '';
		$dataProducto = $this->productoManager->getProductoCarrito($data);
		if ($this->productoManager->getStatus() != 'ok'){
			$this->setStatus("ERROR");
			$this->setMsj($this->productoManager->getMsj());
			return false;
		}
		$data["precio"]          = isset($dataProducto["precio"]) ? $dataProducto["precio"] : 0;
		$data["nombre_producto"] = isset($dataProducto["nombre_producto"]) ? $dataProducto["nombre_producto"] : '';
		
	    $data["cantidad"] = isset($data["cantidad"]) ? $data["cantidad"] : 0;
	    if ($this->validarCantidad($data["cantidad"]) === false){
	     return false;
		}

		if (is_numeric($data["cantidad"]) && $data["cantidad"] <= 0){
			$this->setStatus("OK");
			$this->setMsj($data["id_producto"]);
			return true;
		}
	    
		$data["total"] = 0;
		if (is_numeric($data["precio"]) && $data["precio"] > 0){
			$data["total"]  = $data["precio"] * $data["cantidad"];
		}

		if (is_numeric($data["total"]) && $data["total"] <= 0){
			$this->setStatus("ERROR");
			$this->setMsj("No se puede calcular el total del producto.");
			return false;
		}

		if ($this->carritoDao->eliminarDetalle($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj("No se pudo actualizar el carrito. Comuniquese con el administrador");
			return false;
		} 
        
        if ( $this->carritoDao->altaDetalle($data) === false) {
            $this->setStatus("ERROR");
            $this->setMsj($this->carritoDao->getMsj());
            return false;
        }
                
		$this->setStatus("OK");
		$this->setMsj($data["id_carrito"]);
	}

	public function guardarCarrito(array $data)
	{

		if ($this->validarCarrito($data) === false) {
			return false;
		}


		if ($this->carritoDao->altaCarrito($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->carritoDao->getMsj());
		} else {
			$idCarrito = $this->carritoDao->getMsj();

			
                foreach ($_POST["detalle"] as $valor) {
                    $valor = isset($valor) ?  $valor : '';
		            if ($this->validarDetalle( $valor) === false){
			            return false;
		            }
                    $dataDetalle = array(
                        "id_carrito" => $idCarrito,
                        "detalle"        => $valor
                    );

                    if ( $this->carritoDao->altaDetalle($dataDetalle) === false) {
                        $this->setStatus("ERROR");
                        $this->setMsj($this->carritoDao->getMsj());
                        return false;
                    }
                }
                
			
			$this->setStatus("OK");
			$this->setMsj($idCarrito);
		}
	}

	public function modificarCarrito(array $data)
	{
		if ($this->validarCarrito($data) === false) {
			return false;
		}


		if ($this->carritoDao->editarCarrito($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->carritoDao->getMsj());
		} else {
                if ($this->carritoDao->eliminarDetalle($data) === false) {
                    $this->setStatus("ERROR");
                    $this->setMsj("No se pudo eactualizar Detalle");
                    return false;
                } else {
                    $idCarrito = isset($data["id"]) ? $data["id"] : '';
                    foreach ($_POST["detalle"] as $valor) {
                        $valor = isset($valor) ?  $valor : '';
                        $dataDetalle = array(
                            "id_carrito" => $idCarrito,
                            "detalle"        => $valor
                        );
    
                        if ($this->carritoDao->altaDetalle($dataDetalle) === false) {
                            $this->setStatus("ERROR");
                            $this->setMsj($this->carritoDao->getMsj());
                            return false;
                        }
                    }
                }
			$this->setStatus("OK");
			$this->setMsj($this->carritoDao->getMsj());
			return true;
		}
	}

	public function eliminarCarrito(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		if ($this->validarId($id) === false){
			return false;
		}


		if ($this->carritoDao->eliminarCarrito($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->carritoDao->getMsj());
		} else {
                if ($this->carritoDao->eliminarDetalle($data) === false) {
                    $this->setStatus("ERROR");
                    $this->setMsj($this->carritoDao->getMsj());
                    return false;
                } 
			$this->setStatus("OK");
			$this->setMsj($this->carritoDao->getMsj());
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

	private function existeIdProducto($id_producto)
	{

		return true;
	}

	public function getCarrito(array $data)
	{

		$id = isset($data["id_carrito"]) ? $data["id"] : '';
		if ($this->existeId($id) === false) {
			return [];
		}
	
		$carrito = $this->carritoDao->getCarrito($id);
		if ($this->carritoDao->getStatus() != 'ok') {
			$this->setStatus("ERROR");
			$this->setMsj($this->carritoDao->getMsj());
			return [];
		}
		$this->setStatus("ok");
		return $carrito;
	}

	public function getListCarrito()
	{
		$ret =  $this->publicacionDao->getListCarrito();
		return $ret;
	}


                public function getListTipo()
                {
                    return $this->claseDao->getListTipo();
                }                

        
            private function validarCantidad($cant)
            {
                if (!is_numeric($cant)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo cantidad es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }                
 
            private function validarSubtotal($subtotal)
            {
                if (!preg_match('/^\d+(\.\d{1,2})?$/', $subtotal)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo subtotal es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }                
 
            private function validarTotal($total)
            {
                if (!preg_match('/^\d+(\.\d{1,2})?$/', $total)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo total es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }        
            private function validarEnvio_nombre_apellido($envio_nombre_apellido)
            {
                if (! preg_match('/^\w+$/i', $envio_nombre_apellido)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo envio_nombre_apellido es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }        
            private function validarEnvio_codigo_postal($envio_codigo_postal)
            {
                if (! preg_match('/^\w+$/i', $envio_codigo_postal)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo envio_codigo_postal es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }        
            private function validarEnvio_ciudad_localidad($envio_ciudad_localidad)
            {
                $this->setStatus("ERROR");
                $this->setMsj("El campo envio_ciudad_localidad es incorrecto.");
                return false;
            }        
            private function validarEmail($email)
            {
                $this->setStatus("ERROR");
                $this->setMsj("El campo email es incorrecto.");
                return false;
            }        
            private function validarNotas($notas)
            {
                $this->setStatus("ERROR");
                $this->setMsj("El campo notas es incorrecto.");
                return false;
            }        
            private function validarDetalle($detalle)
            {
                if (! preg_match('/^\w+$/i', $detalle)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo detalle es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }
}

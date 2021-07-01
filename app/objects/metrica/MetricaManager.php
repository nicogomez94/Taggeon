<?php
include_once("MetricaDao.php");
class  MetricaManager
{
	private $metricaDao;
	private $status    = "";
	private $msj       = "";

	public function __construct()
	{
		$this->metricaDao = new MetricaDao();
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

	private function validarMetrica(array $data) {
		$var_accion = isset($data["accion"]) ? $data["accion"] : '';
		if ($var_accion == 'editar') {
			$id = isset($data["id"]) ? $data["id"] : '';
			if ($this->existeId($id) === false) {
				return false;
			}
		}

	    $publicacion = isset($data["publicacion"]) ? $data["publicacion"] : '';
	    if ($this->validarPublicacion($publicacion) === false){
	     return false;
	    }
	    $producto = isset($data["producto"]) ? $data["producto"] : '';
	    if ($this->validarProducto($producto) === false){
	     return false;
	    }
	    $precio = isset($data["precio"]) ? $data["precio"] : '';
	    if ($this->validarPrecio($precio) === false){
	     return false;
	    }
	    $comision_porc = isset($data["comision_porc"]) ? $data["comision_porc"] : '';
	    if ($this->validarComision_porc($comision_porc) === false){
	     return false;
	    }
	    $comision = isset($data["comision"]) ? $data["comision"] : '';
	    if ($this->validarComision($comision) === false){
	     return false;
	    }
	    $carrito_detalle = isset($data["carrito_detalle"]) ? $data["carrito_detalle"] : '';
	    if ($this->validarCarrito_detalle($carrito_detalle) === false){
	     return false;
	    }
	    $carrito = isset($data["carrito"]) ? $data["carrito"] : '';
	    if ($this->validarCarrito($carrito) === false){
	     return false;
	    }
	    $usuario = isset($data["usuario"]) ? $data["usuario"] : '';
	    if ($this->validarUsuario($usuario) === false){
	     return false;
	    }
	    $fecha_transaccion = isset($data["fecha_transaccion"]) ? $data["fecha_transaccion"] : '';
	    if ($this->validarFecha_transaccion($fecha_transaccion) === false){
	     return false;
	    }
	    $objeto_producto = isset($data["objeto_producto"]) ? $data["objeto_producto"] : '';
	    if ($this->validarObjeto_producto($objeto_producto) === false){
	     return false;
	    }
	    $objeto_publicacion = isset($data["objeto_publicacion"]) ? $data["objeto_publicacion"] : '';
	    if ($this->validarObjeto_publicacion($objeto_publicacion) === false){
	     return false;
	    }
	    $objeto_carrito = isset($data["objeto_carrito"]) ? $data["objeto_carrito"] : '';
	    if ($this->validarObjeto_carrito($objeto_carrito) === false){
	     return false;
	    }
	    $objeto_carrito_detalle = isset($data["objeto_carrito_detalle"]) ? $data["objeto_carrito_detalle"] : '';
	    if ($this->validarObjeto_carrito_detalle($objeto_carrito_detalle) === false){
	     return false;
	    }
	    $objeto_usuario = isset($data["objeto_usuario"]) ? $data["objeto_usuario"] : '';
	    if ($this->validarObjeto_usuario($objeto_usuario) === false){
	     return false;
	    }
	    $fecha_pago = isset($data["fecha_pago"]) ? $data["fecha_pago"] : '';
	    if ($this->validarFecha_pago($fecha_pago) === false){
	     return false;
	    }
	    $id_pago = isset($data["id_pago"]) ? $data["id_pago"] : '';
	    if ($this->validarId_pago($id_pago) === false){
	     return false;
	    }
	    $estado = isset($data["estado"]) ? $data["estado"] : '';
	    if ($this->validarEstado($estado) === false){
	     return false;
	    }
	    $observacion = isset($data["observacion"]) ? $data["observacion"] : '';
	    if ($this->validarObservacion($observacion) === false){
	     return false;
	    }

        if ($this->metricaDao->existePublicacion($publicacion) === false) {
            $this->setStatus("ERROR");
            $this->setMsj($this->metricaDao->getMsj());
            return false;
        }
        if ($this->metricaDao->existeProducto($producto) === false) {
            $this->setStatus("ERROR");
            $this->setMsj($this->metricaDao->getMsj());
            return false;
        }
        if ($this->metricaDao->existeCarrito_detalle($carrito_detalle) === false) {
            $this->setStatus("ERROR");
            $this->setMsj($this->metricaDao->getMsj());
            return false;
        }
        if ($this->metricaDao->existeCarrito($carrito) === false) {
            $this->setStatus("ERROR");
            $this->setMsj($this->metricaDao->getMsj());
            return false;
        }
        if ($this->metricaDao->existeUsuario($usuario) === false) {
            $this->setStatus("ERROR");
            $this->setMsj($this->metricaDao->getMsj());
            return false;
        }

	}



	public function agregarMetrica(array $data)
	{

		if ($this->validarMetrica($data) === false) {
			return false;
		}


		if ($this->metricaDao->altaMetrica($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->metricaDao->getMsj());
		} else {
			$idMetrica = $this->metricaDao->getMsj();

			
			
			$this->setStatus("OK");
			$this->setMsj($idMetrica);
		}
	}

	public function modificarMetrica(array $data)
	{
		if ($this->validarMetrica($data) === false) {
			return false;
		}


		if ($this->metricaDao->editarMetrica($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->metricaDao->getMsj());
		} else {

			$this->setStatus("OK");
			$this->setMsj($this->metricaDao->getMsj());
			return true;
		}
	}

	public function eliminarMetrica(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		if ($this->validarId($id) === false){
			return false;
		}


		if ($this->metricaDao->eliminarMetrica($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->metricaDao->getMsj());
		} else {

			$this->setStatus("OK");
			$this->setMsj($this->metricaDao->getMsj());
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

	public function getMetrica(array $data)
	{

		$id = isset($data["id"]) ? $data["id"] : '';
		if ($this->existeId($id) === false) {
			return [];
		}
	
		$metrica = $this->metricaDao->getMetrica($id);
		if ($this->metricaDao->getStatus() != 'ok') {
			$this->setStatus("ERROR");
			$this->setMsj($this->metricaDao->getMsj());
			return [];
		}
		$this->setStatus("ok");
		return $metrica;
	}

	public function getListMetrica()
	{
		$ret =  $this->publicacionDao->getListMetrica();
		return $ret;
	}


                public function getListPublicacion()
                {
                    return $this->claseDao->getListPublicacion();
                }                
                public function getListProducto()
                {
                    return $this->claseDao->getListProducto();
                }                
                public function getListCarrito_detalle()
                {
                    return $this->claseDao->getListCarrito_detalle();
                }                
                public function getListCarrito()
                {
                    return $this->claseDao->getListCarrito();
                }                
                public function getListUsuario()
                {
                    return $this->claseDao->getListUsuario();
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
 
            private function validarPrecio($precio)
            {
                if (!preg_match('/^\d+(\.\d{1,2})?$/', $precio)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo precio es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }                
 
            private function validarComision_porc($comision_porc)
            {
                if (!preg_match('/^\d+(\.\d{1,2})?$/', $comision_porc)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo comision_porc es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }                
 
            private function validarComision($comision)
            {
                if (!preg_match('/^\d+(\.\d{1,2})?$/', $comision)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo comision es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }        
            private function validarCarrito_detalle($carrito_detalle)
            {
                if (!is_numeric($carrito_detalle)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo carrito_detalle es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }        
            private function validarCarrito($carrito)
            {
                if (!is_numeric($carrito)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo carrito es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
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
            private function validarFecha_transaccion($fecha_transaccion)
            {
                $this->setStatus("ERROR");
                $this->setMsj("El campo fecha_transaccion es incorrecto.");
                return false;
            }        
            private function validarObjeto_producto($objeto_producto)
            {
                if (! preg_match('/^\w+$/i', $objeto_producto)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo objeto_producto es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }        
            private function validarObjeto_publicacion($objeto_publicacion)
            {
                if (! preg_match('/^\w+$/i', $objeto_publicacion)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo objeto_publicacion es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }        
            private function validarObjeto_carrito($objeto_carrito)
            {
                if (! preg_match('/^\w+$/i', $objeto_carrito)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo objeto_carrito es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }        
            private function validarObjeto_carrito_detalle($objeto_carrito_detalle)
            {
                if (! preg_match('/^\w+$/i', $objeto_carrito_detalle)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo objeto_carrito_detalle es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }        
            private function validarObjeto_usuario($objeto_usuario)
            {
                if (! preg_match('/^\w+$/i', $objeto_usuario)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo objeto_usuario es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }        
            private function validarFecha_pago($fecha_pago)
            {
                $this->setStatus("ERROR");
                $this->setMsj("El campo fecha_pago es incorrecto.");
                return false;
            }    

private function validarId_pago($id_pago)
{
    if (!preg_match('/^\d+$/', $id_pago)){
        $this->setStatus("ERROR");
        $this->setMsj("El campo id_pago es incorrecto.");
        return false;
    }
    $this->setStatus("OK");
    $this->setMsj("");
    return true;
}        
            private function validarEstado($estado)
            {
                if (! preg_match('/^\w+$/i', $estado)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo estado es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }        
            private function validarObservacion($observacion)
            {
                if (! preg_match('/^\w+$/i', $observacion)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo observacion es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }
}

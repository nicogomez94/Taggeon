<?php
include_once("MetricaDao.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."carrito/CarritoDao.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."producto/ProductoManager.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."publicacion/PublicacionManager.php");

class  MetricaManager
{
	private $metricaDao;
	private $status    = "";
	private $msj       = "";

	public function __construct()
	{
		$this->metricaDao = new MetricaDao();
		$this->carritoDao = new CarritoDao();
		$this->productoManager = new ProductoManager();
		$this->publicacionManager = new PublicacionManager();
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

	    $carrito_detalle = isset($data["carrito_detalle"]) ? $data["carrito_detalle"] : '';
	    if ($this->validarCarrito_detalle($carrito_detalle) === false){
	     return false;
	    }
	    $rol_usuario = isset($data["rol_usuario"]) ? $data["rol_usuario"] : '';
	    if ($this->validarRol_usuario($rol_usuario) === false){
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
	    $pago_id = isset($data["pago_id"]) ? $data["pago_id"] : '';
	    if ($this->validarPago_id($pago_id) === false){
	     return false;
	    }

        if ($this->metricaDao->existeCarrito_detalle($carrito_detalle) === false) {
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

	public function getListMetricaTotalPendiente()
	{
		$ret =  $this->metricaDao->getListMetricaTotalPendiente();
		return $ret;
	}
	public function getListMetricaTotal()
	{
		$ret =  $this->metricaDao->getListMetricaTotal();
		return $ret;
	}
	public function getListMetrica()
	{
		$ret =  $this->metricaDao->getListMetrica();
		return $ret;
	}


                public function getListCarrito_detalle()
                {
                    return $this->claseDao->getListCarrito_detalle();
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
            private function validarRol_usuario($rol_usuario)
            {
                if (! preg_match('/^\w+$/i', $rol_usuario)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo rol_usuario es incorrecto.");
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
                $this->setStatus("ERROR");
                $this->setMsj("El campo comision es incorrecto.");
                return false;
            }    

private function validarPago_id($pago_id)
{
    if (!preg_match('/^\d+$/', $pago_id)){
        $this->setStatus("ERROR");
        $this->setMsj("El campo pago_id es incorrecto.");
        return false;
    }
    $this->setStatus("OK");
    $this->setMsj("");
    return true;
}


	public function procesarMetrica($id,$usuarioAlta,$idMP)
	{
		if (!isset($id)){
			$id = isset($usuarioAlta) ? $usuarioAlta : '';
		}
		if ($this->validarId($id) === false){
			return false;
		}
		$carrito =  $this->carritoDao->getCarritoMPMetrica($id,$usuarioAlta);
		$str = '';
		foreach ($carrito as $hashAux){
			$idCarritoDetalle = $hashAux['id_detalle'];
			$costoVenta = $hashAux['costo_venta'];
			$idVendedor = $hashAux['id_usuario_vendedor'];
			$idMarket   = $GLOBALS['configuration']['id_market'];
			$idTaggeador   = $hashAux['id_usuario_taggeador'];
			$comisionTaggeador =  (float)$GLOBALS['configuration']['comision_taggeador'];
			$comisionMarket    =  (float)$GLOBALS['configuration']['comision_market'];
			$totalComisionMarket = 0;
			$totalComisionTaggeador = 0;
			$totalComisionVendedor = 0;


			if ($idVendedor == $idTaggeador){
				$comisionMarket =  (float)($comisionTaggeador + $comisionMarket);
				$comisionTaggeador = 0;
			}
			$comisionVendedor = (float) (100 - $comisionTaggeador - $comisionMarket);

			$totalComisionTaggeador = (float)(($costoVenta * $comisionTaggeador)/100); 
			$totalComisionMarket = (float)(($costoVenta * $comisionMarket)/100); 
			$totalComisionVendedor = (float)(($costoVenta * $comisionVendedor)/100); 

			logError("costoventa $costoVenta");
			logError("totalComisionTaggeador: $totalComisionTaggeador");
			logError("totalComisionVendedor $totalComisionVendedor");
			logError("totalComisionMarket $totalComisionMarket");

			if ($this->metricaDao->altaMetrica($idCarritoDetalle,'vendedor',$comisionVendedor,$totalComisionVendedor,$idMP,$idVendedor) === false) {
				$this->setStatus("ERROR");
				$this->setMsj($this->metricaDao->getMsj());
				return false;
			}
			if ($this->metricaDao->altaMetrica($idCarritoDetalle,'market',$comisionMarket,$totalComisionMarket,$idMP,$idMarket) === false) {
				$this->setStatus("ERROR");
				$this->setMsj($this->metricaDao->getMsj());
				return false;
			}

			if ($idVendedor != $idTaggeador){
				if ($this->metricaDao->altaMetrica($idCarritoDetalle,'taggeador',$comisionTaggeador,$totalComisionTaggeador,$idMP,$idTaggeador) === false) {
					$this->setStatus("ERROR");
					$this->setMsj($this->metricaDao->getMsj());
					return false;
				}

			}

		}
		$this->setStatus("ok");
		$this->setMsj("");
		return true;

	}
	public function getListMetricaTotalPendienteTagger()
	{
		$ret =  $this->metricaDao->getListMetricaTotalPendienteTagger();
		return $ret;
	}
	public function getListMetricaTotalTagger()
	{
		$ret =  $this->metricaDao->getListMetricaTotalTagger();
		return $ret;
	}
	public function getListMetricaTagger()
	{
		$ret =  $this->metricaDao->getListMetricaTagger();
		return $ret;
	}
	public function getListMetricaTotalPendienteSeller()
	{
		$ret =  $this->metricaDao->getListMetricaTotalPendienteSeller();
		return $ret;
	}
	public function getListMetricaTotalSeller()
	{
		$ret =  $this->metricaDao->getListMetricaTotalSeller();
		return $ret;
	}
	public function getListMetricaSeller()
	{
		$ret =  $this->metricaDao->getListMetricaSeller();
		return $ret;
	}
	public function getListMetricaTotalPendienteAdmin()
	{
		$ret =  $this->metricaDao->getListMetricaTotalPendienteAdmin();
		return $ret;
	}
	public function getListMetricaTotalAdmin()
	{
		$ret =  $this->metricaDao->getListMetricaTotalAdmin();
		return $ret;
	}
	public function getListMetricaAdmin()
	{
		$ret =  $this->metricaDao->getListMetricaAdmin();
		return $ret;
	}
	public function solicitudRetiro(array $data)
	{
		$total = $this->getListMetricaTotalTagger();
		$this->setStatus("OK");
		$this->setMsj($total);
	}
	public function confirmarSolicitudRetiro(array $data)
	{
		$total = $this->getListMetricaTotalTagger();
		$totalParam = isset($data["total"]) ? $data["total"] : '';
		if ($total == $totalParam){
			$this->setStatus("OK");
			$this->setMsj('ok');
		}else{
		    $this->setStatus("ERROR");
		    $this->setMsj("El monto a retirar es incorrecto");
		}
	}
}

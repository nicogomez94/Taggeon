<?php
include_once("CarritoDao.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."producto/ProductoManager.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."publicacion/PublicacionManager.php");
include_once("/var/www/html/app/objects/notificaciones/NotificacionesManager.php");


class  CarritoManager
{
	private $carritoDao;
	private $productoManager;
	private $publicacionManager;

	private $status    = "";
	private $msj       = "";

	public function __construct()
	{
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

	public function agregarCarrito(array $data)
	{
	#	$dataAux = array();
	#	$dataAux["estado"] = 2;
	#	$carritosHuerfanos = $this->carritoDao->getListCompras($dataAux);

	#	$dataAux["estado"] = 1;
	#	$carritosEstado1 = $this->carritoDao->getListCompras($dataAux);
	#	
	#	if (count($carritosHuerfanos)>0 || count($carritosEstado1)>0){
	#		$this->setStatus("ERROR");
	#		$this->setMsj("No se puede agregar el producto. Existe un carrito abierto. Cancele o finalice el mismo para poder continuar.");
	#		return false;
	#	}


		#$vendedorActual = $this->carritoDao->getVendedorByIdCarrito($data["id_carrito"]);
		#$vendedorActual = isset($vendedorActual) ? $vendedorActual : '';

		#if ( $vendedorActual !=  $data["id_vendedor"] ){
		#	$this->setStatus("ERROR");
		#	$this->setMsj("El producto que quiere agregar pertenece a otro vendedor. Finalize el carrito y vuelva a intentarlo.");
		#	return false;

		#}

		#valida el manager de producto el id_producto
		$data["id_producto"] = isset($data["id"]) ? $data["id"] : '';
		$dataProducto = $this->productoManager->getProductoCarrito($data);
		if ($this->productoManager->getStatus() != 'ok'){
			$this->setStatus("ERROR");
			$this->setMsj($this->productoManager->getMsj());
			return false;
		}

		$data["id_publicacion"] = isset($data["id_publicacion"]) ? $data["id_publicacion"] : '';
		$data["id_vendedor"] = isset($dataProducto["usuario_alta"]) ? $dataProducto["usuario_alta"] : '';

		$data["id_carrito"] = $this->carritoDao->getIdCarritoByVendedor($data["id_vendedor"]);

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

		if (!is_numeric($data["id_publicacion"])){
			$this->setStatus("ERROR");
			$this->setMsj("La publicación es incorrecto.");
			return false;
		}

		$dataPublicacion       = $this->publicacionManager->getPublicacionByIdYProducto($data);
		$data["id_usuario_publicador"]  =  (isset($dataPublicacion[0]) && isset($dataPublicacion[0]['usuario_alta'])) ? $dataPublicacion[0]['usuario_alta'] : 0;

		$data["precio"]          = isset($dataProducto["precio"]) ? $dataProducto["precio"] : 0;
		$data["nombre_producto"] = isset($dataProducto["titulo"]) ? $dataProducto["titulo"] : '';
		
	    $data["cantidad"] = isset($data["cantidad"]) ? $data["cantidad"] : 0;
	    if ($this->validarCantidad($data["cantidad"]) === false){
	     return false;
		}

		if (is_numeric($data["cantidad"]) && $data["cantidad"] <= 0){
			if ($this->carritoDao->eliminarDetalle($data) === false) {
				$this->setStatus("ERROR");
				$this->setMsj("No se pudo actualizar el carrito. Comuniquese con el administrador");
				return false;
			} 
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

	public function actualizarStock($id,$usuarioAlta)
	{
		if (!isset($id)){
			$id = isset($usuarioAlta) ? $usuarioAlta : '';
		}
		if ($this->validarId($id) === false){
			return false;
		}
		$carrito =  $this->carritoDao->getCarritoMP($id,$usuarioAlta);
		$str = '';
		foreach ($carrito as $hashAux){
			$cantidad = $hashAux['cantidad'];
			$nombreProducto =  $hashAux['nombre_producto'];
			$idProd   = $hashAux['id_producto'];
			$data["id_producto"] = isset($idProd) ? $idProd : '';
			$dataProducto = $this->productoManager->getProductoCarrito($data);
			if ($this->productoManager->getStatus() != 'ok'){
				$this->setStatus("ERROR");
				$this->setMsj($this->productoManager->getMsj());
				return false;
			}else{
				$patron = '/^[1-9][0-9]*$/';
				if (!preg_match($patron, $cantidad)){
					$this->setStatus("ERROR");
					$this->setMsj("El campo cantidad del producto $nombre_producto es incorrecto.");
					return false;
				}
				$carrito =  $this->carritoDao->actualizarStock($idProd,$cantidad);
				if ($this->carritoDao->getStatus() != 'OK'){
					$this->setStatus("ERROR");
					$this->setMsj($this->carritoDao->getMsj());
					return false;
				}
			}

		}
		$this->setStatus("ok");
		$this->setMsj("");
		return true;

	}
	public function validarStock($id,$usuarioAlta)
	{
		if (!isset($id)){
			$id = isset($data["id_carrito"]) ? $data["id_carrito"] : '';
		}
		if ($this->validarId($id) === false){
			return false;
		}
		$carrito =  $this->carritoDao->getCarritoMP($id,$usuarioAlta);
		$str = '';
		foreach ($carrito as $hashAux){
			$cantidad = $hashAux['cantidad'];
			$nombreProducto =  $hashAux['nombre_producto'];
			$idProd   = $hashAux['id_producto'];
			$data["id_producto"] = isset($idProd) ? $idProd : '';
			$dataProducto = $this->productoManager->getProductoCarrito($data);
			if ($this->productoManager->getStatus() != 'ok'){
				$this->setStatus("ERROR");
				$this->setMsj($this->productoManager->getMsj());
				return false;
			}else{
				$stock = isset($dataProducto['stock']) ? $dataProducto['stock'] : 0;
				if ($stock < 0 || $cantidad > $stock){
					$this->setStatus("ERROR");
					$this->setMsj("El producto $nombreProducto no tiene el stock sufuciente que desea comprar. Stock disponible: $stock");
					return false;
					
				}
			}

		}
		$this->setStatus("ok");
		$this->setMsj("");
		return true;

	}

	public function eliminarCarrito(array $data)
	{
		$id = isset($data["id_carrito"]) ? $data["id_carrito"] : '';
		if ($this->validarId($id) === false){
			return false;
		}

		$data["id"]= $id;

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


	public function finalizarPago (array $data)
	{
		$idCarrito = isset($data["id_carrito"]) ? $data["id_carrito"] : '';
		$data["id_carrito"] = $this->carritoDao->getIdCarrito3();

		if (!is_numeric($data["id_carrito"])){
			$this->setStatus("ERROR");
			$this->setMsj("El id de carrito es incorrecto.");
			return false;
		}

		if ($data["id_carrito"] <= 0){
			$this->setStatus("ERROR");
			$this->setMsj("No se encontro el carrito.");
			return false;
 		}

		if ($this->validarId($idCarrito) === false){
			return false;
		}

		if ($data["id_carrito"] != $idCarrito){
			$this->setStatus("ERROR");
			$this->setMsj("El id ". $idCarrito ." de carrito  es incorrecto.");
			return false;
		}

		$data["estado"] = 3;

		if ($this->carritoDao->cambiarEstadoCarrito3($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->carritoDao->getMsj());
			return false;

		} else {
			$this->setStatus("OK");
			$this->setMsj($this->carritoDao->getMsj());
			return true;

		}
	}


	public function finalizarCarrito2(array $data)
	{
	        if ($this->validarCarrito($data) === false) {
			return false;
		}
		$idCarrito = isset($data["id_carrito"]) ? $data["id_carrito"] : '';

		$data["id_carrito"] = $this->carritoDao->getIdCarrito($idCarrito);

		if (!is_numeric($data["id_carrito"])){
			$this->setStatus("ERROR");
			$this->setMsj("El id de carrito es incorrecto.");
			return false;
		}

		if ($data["id_carrito"] <= 0){
			$this->setStatus("ERROR");
			$this->setMsj("No se encontro el carrito.");
			return false;
 		}

		if ($this->validarId($idCarrito) === false){
			return false;
		}

		if ($data["id_carrito"] != $idCarrito){
			$this->setStatus("ERROR");
			$this->setMsj("El id ". $idCarrito ." de carrito  es incorrecto.");
			return false;
		}

		$data["estado"] = 2;

		if ($this->carritoDao->cambiarEstadoCarrito2($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->carritoDao->getMsj());
		} else {
			$this->setStatus("OK");
			$this->setMsj($this->carritoDao->getMsj());
		}
	}

	public function finalizarCarrito(array $data)
	{
		$idCarrito = isset($data["id_carrito"]) ? $data["id_carrito"] : '';

		$data["id_carrito"] = $this->carritoDao->getIdCarrito($idCarrito);
		if (!is_numeric($data["id_carrito"])){
			$this->setStatus("ERROR");
			$this->setMsj("El id de carrito es incorrecto.");
			return false;
		}
		

		if ($data["id_carrito"] <= 0){
			$this->setStatus("ERROR");
			$this->setMsj("No se encontro el carrito.");
			return false;
 		}

		$data["estado"] = 1;

		if ($this->carritoDao->cambiarEstadoCarrito($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->carritoDao->getMsj());
		} else {
			$this->setStatus("OK");
			$this->setMsj($this->carritoDao->getMsj());
		}
	}

	public function finalizarCompra(array $data)
	{
		$idCarrito = isset($data["id_carrito"]) ? $data["id_carrito"] : '';
		$data["id_carrito"] = $this->carritoDao->getIdCarrito();

		if (!is_numeric($data["id_carrito"])){
			$this->setStatus("ERROR");
			$this->setMsj("El id de carrito es incorrecto.");
			return false;
		}

		if ($data["id_carrito"] <= 0){
			$this->setStatus("ERROR");
			$this->setMsj("No se encontro el carrito.");
			return false;
 		}

		if ($this->validarId($idCarrito) === false){
			return false;
		}

		if ($data["id_carrito"] != $idCarrito){
			$this->setStatus("ERROR");
			$this->setMsj("El id ". $idCarrito ."de carrito  es incorrecto.");
			return false;
		}

		$data["estado"] = 3;

		if ($this->carritoDao->cambiarEstadoCarrito($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->carritoDao->getMsj());
		} else {
			$this->setStatus("OK");
			$this->setMsj($this->carritoDao->getMsj());
		}
	}

	public function cambiarEstadoMayor3(array $data,$estado)
	{
		$data["id_carrito"] = isset($data["id_carrito"]) ? $data["id_carrito"] : '';

		if (!is_numeric($data["id_carrito"])){
			$this->setStatus("ERROR");
			$this->setMsj("El id de carrito es incorrecto.");
			return false;
		}

		if ($data["id_carrito"] <= 0){
			$this->setStatus("ERROR");
			$this->setMsj("No se encontro el carrito.");
			return false;
 		}

		if ($this->validarId($data["id_carrito"]) === false){
			return false;
		}
		$data["estado"] = $estado;

		if ($this->carritoDao->cambiarEstadoCarritoMayor3($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->carritoDao->getMsj());
		} else {
			$this->setStatus("OK");
			$this->setMsj($this->carritoDao->getMsj());


            $id = isset($data["id_carrito"]) ? $data["id_carrito"] : '';
			$carrito = $this->carritoDao->getCarrito($data);
			$data = isset($carrito[0]) ? $carrito[0] : [];
			$envio_nombre_apellido = isset($data["envio_nombre_apellido"]) ? $data["envio_nombre_apellido"] : '';
			$envio_codigo_postal = isset($data["envio_codigo_postal"]) ? $data["envio_codigo_postal"] : '';
			$envio_ciudad_localidad = isset($data["envio_ciudad_localidad"]) ? $data["envio_ciudad_localidad"] : '';
			$email = isset($data["email"]) ? $data["email"] : '';
			$notas = isset($data["notas"]) ? $data["notas"] : '';

        $bodymail = <<<SQL
Hola $envio_nombre_apellido, se creo la orden $id.\n
Datos del envio: codigo postal $envio_codigo_postal. Localidad: $envio_ciudad_localidad.\n
Notas: $notas
SQL;
			include_once($GLOBALS['configuration']['path_app_admin_objects']."util/email.php");
			$objEmail = new Email();
			$objEmail->setEnviar(true);
			$objEmail->enviarEmailCarrito($bodymail,$email);


			#$contenido = new Template("compras-mail");
#
			#$contenido->asigna_variables(array(
			#		"fecha"   => date('d/m/Y', time())
			#));
			#$contenidoString = $contenido->muestra();
			#$usuario = $GLOBALS['sesionG']['usuario'];
			#$objEmail->enviarEmailCarritoHtml($bodymail,$contenidoString,$email,$usuario);


			$notiManager = new NotificacionesManager();


			foreach ($carrito as &$fila) {
				$data['json_notificacion']    = $fila;
				if (isset($fila['id_usuario_publicador'])){
					$data['usuario_notificacion'] = $fila['id_usuario_publicador'];
					$data['tipo_notificacion'] = 'taggeador';
					$notiManager->agregarNotificaciones($data);
				}

				if (isset($fila['vendedor'])){
					$data['usuario_notificacion'] = $fila['vendedor'];
					$data['tipo_notificacion'] = 'vendedor';
					$notiManager->agregarNotificaciones($data);
				}

			}
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

	public function getListCarritoAll()
	{
		$ret =  $this->carritoDao->getListCarritoAll();
		return $ret;
	}

	public function getListCarrito()
	{

		$id = isset($_GET["id_carrito"]) ? $_GET["id_carrito"] : '';
                if (!is_numeric($id)){
                    return [];
                }


		$ret =  $this->carritoDao->getListCarrito($id);
		return $ret;
	}


	public function getListCarrito2(){
		$id = isset($_GET["id_carrito"]) ? $_GET["id_carrito"] : '';
                if (!is_numeric($id)){
                    return [];
                }
		$ret =  $this->carritoDao->getListCarrito2();
		return $ret;
	}

	public function getListCarritoHuerfano(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		if ($id != ''){
			if ($this->validarId($id) === false){
				return [];
			}
		}
		$data["estado"] = 2;
		$ret =  $this->carritoDao->getListCompras($data);
		$this->setMsj($this->carritoDao->getMsj());
		return $ret;
	}

	public function getListCarritoPantallaDomicilio(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		if ($id != ''){
			if ($this->validarId($id) === false){
				return [];
			}
		}
		$data["estado"] = 1;
		$ret =  $this->carritoDao->getListCompras($data);
		$this->setMsj($this->carritoDao->getMsj());
		return $ret;
	}


	public function getListCompras(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		if ($id != ''){
			if ($this->validarId($id) === false){
				return [];
			}
		}
		$data["estado"] = 4;
		$ret =  $this->carritoDao->getListCompras($data);
		$this->setMsj($this->carritoDao->getMsj());
		return $ret;
	}

	public function getListComprasAdmin(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		if ($id != ''){
			if ($this->validarId($id) === false){
				return [];
			}
		}
		$data["estado"] = 4;
		$ret =  $this->carritoDao->getListComprasAdmin($data);
		$this->setMsj($this->carritoDao->getMsj());
		return $ret;
	}


	public function getAmpliarCompra(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		if ($this->validarId($id) === false){
			return false;
		}
		$data["estado"] = 2;

		$ret =  $this->carritoDao->getListCompras($data);
		$this->setMsj($this->carritoDao->getMsj());

		return $ret;
	}

	public function getAmpliarCompraFinalizada(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		if ($this->validarId($id) === false){
			return false;
		}
		$data["estado"] = 4;

		$ret =  $this->carritoDao->getListCompras($data);
		$this->setMsj($this->carritoDao->getMsj());

		return $ret;
	}

	public function getListVentas(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		if ($id != ''){
			if ($this->validarId($id) === false){
				return [];
			}
		}
		$data["estado"] = 4;
		$ret =  $this->carritoDao->getListVentas($data);
		$this->setMsj($this->carritoDao->getMsj());
		return $ret;
	}


	public function getListVentasAdmin(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		if ($id != ''){
			if ($this->validarId($id) === false){
				return [];
			}
		}
		$data["estado"] = 4;
		$ret =  $this->carritoDao->getListVentasAdmin($data);
		$this->setMsj($this->carritoDao->getMsj());
		return $ret;
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
                if (! preg_match('/^.+$/i', $envio_nombre_apellido)){
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
                if (! preg_match('/^.+$/i', $envio_codigo_postal)){
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
                if (! preg_match('/^.+$/i', $envio_ciudad_localidad)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo envio_ciudad_localidad es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }        
            private function validarEmail($email)
            {
		$patron = '/^[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/';
		if (!preg_match($patron, $email)){
		    $this->setStatus("ERROR");
		    $this->setMsj("El campo email es incorrecto.");
		    return false;
		}
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }        
            private function validarNotas($notas)
            {
                if (! preg_match('/^.+$/i', $notas)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo notas es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
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

private function validarCarrito(array $data) {
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


}



}

<?php
include_once("ProductoDao.php");
class  ProductoManager
{
	private $productoDao;
	private $status    = "";
	private $msj       = "";

	public function __construct()
	{
		$this->productoDao = new ProductoDao();
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

	private function validarProducto(array $data)
	{
		$var_accion = isset($data["accion"]) ? $data["accion"] : '';
		if ($var_accion == 'editar') {
			$id = isset($data["id"]) ? $data["id"] : '';
			if ($this->existeId($id) === false) {
				return false;
			}
		}

		$titulo = isset($data["titulo"]) ? $data["titulo"] : '';
		if ($this->validarTitulo($titulo) === false) {
			return false;
		}
		$categoria = isset($data["categoria"]) ? $data["categoria"] : '';
		if ($this->validarCategoria($categoria) === false) {
			return false;
		}
		$rubro = isset($data["rubro"]) ? $data["rubro"] : '';
		if ($this->validarRubro($rubro) === false) {
			return false;
		}
		$marca = isset($data["marca"]) ? $data["marca"] : '';
		if ($this->validarMarca($marca) === false) {
			return false;
		}
		$precio = isset($data["precio"]) ? $data["precio"] : '';
		if ($this->validarPrecio($precio) === false) {
			return false;
		}
		$stock = isset($data["stock"]) ? $data["stock"] : '';
		if ($this->validarStock($stock) === false) {
			return false;
		}
		$envio = isset($data["envio"]) ? $data["envio"] : '';
		if ($this->validarEnvio($envio) === false) {
			return false;
		}
		$garantia = isset($data["garantia"]) ? $data["garantia"] : '';
		if ($this->validarGarantia($garantia) === false) {
			return false;
		}
		$descr_producto = isset($data["descr_producto"]) ? $data["descr_producto"] : '';
		if ($this->validarDescr_producto($descr_producto) === false) {
			return false;
		}
		$color = isset($data["color"]) ? $data["color"] : '';
		if ($this->validarColor($color) === false) {
			return false;
		}




		if ($this->productoDao->existeCategoria($categoria) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->productoDao->getMsj());
			return false;
		}
		if ($this->productoDao->existeRubro($rubro) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->productoDao->getMsj());
			return false;
		}
	}
	public function agregarProducto(array $data)
	{

		if ($this->validarProducto($data) === false) {
			return false;
		}



		if ($this->productoDao->altaProducto($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->productoDao->getMsj());
		} else {
			$idProducto = $this->productoDao->getMsj();


			foreach ($_POST["base"] as $valor) {
				$valor = isset($valor) ?  $valor : '';
				$dataFoto = array(
					"id_producto" => $idProducto,
					"foto"        => $valor
				);

				if ($this->productoDao->altaFoto($dataFoto) === false) {
					$this->setStatus("ERROR");
					$this->setMsj($this->productoDao->getMsj());
					return false;
				}
			}


			$this->setStatus("OK");
			$this->setMsj($idProducto);
		}
	}

	public function importarProducto(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		if ($this->existeId($id) === false) {
			return false;
		}
	
		$file = isset($_POST["file"]) ? $_POST["file"] : '';
		if ($file == ''){
			$this->setStatus("error");
			$this->setMsj("Se importaron 0 registros de 0.");
			return false;
		}

		$fp = fopen("/var/www/html/producto/$id", 'w');
		fwrite($fp, $file);
		fclose($fp);
	}
	public function modificarProducto(array $data)
	{
		if ($this->validarProducto($data) === false) {
			return false;
		}


		if ($this->productoDao->editarProducto($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->productoDao->getMsj());
			return false;
		} else {
			if ($this->productoDao->eliminarProductoFoto($data) === false) {
				$this->setStatus("ERROR");
				$this->setMsj("No se actualizaron las fotos");
				return false;
			} else {
				$idProducto = isset($data["id"]) ? $data["id"] : '';
				foreach ($_POST["base"] as $valor) {
					$valor = isset($valor) ?  $valor : '';
					$dataFoto = array(
						"id_producto" => $idProducto,
						"foto"        => $valor
					);

					if ($this->productoDao->altaFoto($dataFoto) === false) {
						$this->setStatus("ERROR");
						$this->setMsj($this->productoDao->getMsj());
						return false;
					}
				}
				$this->setStatus("OK");
				$this->setMsj($this->productoDao->getMsj());
				return true;
			}


		}
	}

	public function eliminarProducto(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		if ($this->validarId($id) === false) {
			return false;
		}


		if ($this->productoDao->eliminarProducto($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->productoDao->getMsj());
			return false;
		} else {
			if ($this->productoDao->eliminarProductoFoto($data) === false) {
				$this->setStatus("ERROR");
				$this->setMsj($this->productoDao->getMsj());
				return false;
			} else {
				$this->setStatus("OK");
				$this->setMsj($this->productoDao->getMsj());
				return 'ok';
			}
		}
	}



	private function validarId($param)
	{
		$this->setStatus("error");
		$this->setMsj("");
		$validSql = validSqlInjection($param);
		if ($validSql != '') {
			$this->setMsj("Error validación: $validSql.");
		} else {
			$patron = '/^[1-9][0-9]*$/';
			if (preg_match($patron, $param)) {
				$this->setStatus("ok");
				return true;
			} else {
				$this->setMsj("El campo id es incorrecto.");
			}
		}
		return false;
	}

	private function validarTitulo($titulo)
	{
		if (!preg_match('/^\w+$/i', $titulo)) {
			$this->setStatus("ERROR");
			$this->setMsj("El campo titulo es incorrecto.");
			return false;
		}
		$this->setStatus("OK");
		$this->setMsj("");
		return true;
	}
	private function validarCategoria($categoria)
	{
		if (!is_numeric($categoria)) {
			$this->setStatus("ERROR");
			$this->setMsj("El campo categoria es incorrecto.");
			return false;
		}
		$this->setStatus("OK");
		$this->setMsj("");
		return true;
	}
	private function validarRubro($rubro)
	{
		if (!is_numeric($rubro)) {
			$this->setStatus("ERROR");
			$this->setMsj("El campo rubro es incorrecto.");
			return false;
		}
		$this->setStatus("OK");
		$this->setMsj("");
		return true;
	}
	private function validarMarca($marca)
	{
		if (!preg_match('/^\w+$/i', $marca)) {
			$this->setStatus("ERROR");
			$this->setMsj("El campo marca es incorrecto.");
			return false;
		}
		$this->setStatus("OK");
		$this->setMsj("");
		return true;
	}


	private function validarPrecio($precio)
	{
		if (!preg_match('/^\d+(\.\d{1,2})?$/', $precio)) {
			$this->setStatus("ERROR");
			$this->setMsj("El campo precio es incorrecto.");
			return false;
		}
		$this->setStatus("OK");
		$this->setMsj("");
		return true;
	}
	private function validarStock($envio)
	{
		if (!is_numeric($envio)) {
			$this->setStatus("ERROR");
			$this->setMsj("El campo stock es incorrecto.");
			return false;
		}
		$this->setStatus("OK");
		$this->setMsj("");
		return true;
	}
	private function validarEnvio($envio)
	{
		if (!is_numeric($envio)) {
			$this->setStatus("ERROR");
			$this->setMsj("El campo envio es incorrecto.");
			return false;
		}
		$this->setStatus("OK");
		$this->setMsj("");
		return true;
	}
	private function validarGarantia($garantia)
	{
		if (!is_numeric($garantia)) {
			$this->setStatus("ERROR");
			$this->setMsj("El campo garantia es incorrecto.");
			return false;
		}
		$this->setStatus("OK");
		$this->setMsj("");
		return true;
	}
	private function validarDescr_producto($descr_producto)
	{
		if (!preg_match('/^\w+$/i', $descr_producto)) {
			$this->setStatus("ERROR");
			$this->setMsj("El campo descr_producto es incorrecto.");
			return false;
		}
		$this->setStatus("OK");
		$this->setMsj("");
		return true;
	}
	private function validarColor($color)
	{
		if (!preg_match('/^color-\w+$/i', $color)) {
			$this->setStatus("ERROR");
			$this->setMsj("El campo color es incorrecto.");
			return false;
		}
		$this->setStatus("OK");
		$this->setMsj("");
		return true;
	}


	public function getListCategoria()
	{
		return $this->productoDao->getListCategoria();
	}
	public function getListRubro()
	{
		return $this->productoDao->getListRubro();
	}
	public function getListProducto()
	{
		return $this->productoDao->getListProducto();
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

	public function getProducto(array $data)
	{

		$id = isset($data["id"]) ? $data["id"] : '';
		if ($this->existeId($id) === false) {
			return [];
		}
	
		$producto = $this->productoDao->getProducto($id);
		if ($this->productoDao->getStatus() != 'ok') {
			$this->setStatus("ERROR");
			$this->setMsj($this->productoDao->getMsj());
			return [];
		}
		$this->setStatus("ok");
		return $producto;
	}
	public function getFoto(array $data)
	{

		$id = isset($data["foto"]) ? $data["foto"] : '';
	
		return $this->productoDao->getFoto($id);
	}
}

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
		$subcategoria1 = isset($data["subcategoria1"]) ? $data["subcategoria1"] : '';
		if ($subcategoria1 != ''){
			if ($this->validarCategoria($subcategoria1) === false) {
				return false;
			}
			if ($this->productoDao->existeCategoria($subcategoria1) === false) {
				$this->setStatus("ERROR");
				$this->setMsj($this->productoDao->getMsj());
				return false;
			}
		}
		$subcategoria2 = isset($data["subcategoria2"]) ? $data["subcategoria2"] : '';
		if ($subcategoria2 != ''){
			if ($this->validarCategoria($subcategoria2) === false) {
				return false;
			}
			if ($this->productoDao->existeCategoria($subcategoria2) === false) {
				$this->setStatus("ERROR");
				$this->setMsj($this->productoDao->getMsj());
				return false;
			}
		}

		$subcategoria3 = isset($data["subcategoria3"]) ? $data["subcategoria3"] : '';
		if ($subcategoria3 != ''){
			if ($this->validarCategoria($subcategoria3) === false) {
				return false;
			}

			if ($this->productoDao->existeCategoria($subcategoria3) === false) {
				$this->setStatus("ERROR");
				$this->setMsj($this->productoDao->getMsj());
				return false;
			}
	
		}

		$subcategria4 = isset($data["subcategria4"]) ? $data["subcategria4"] : '';
		if ($subcategria4 != ''){
			if ($this->validarCategoria($subcategria4) === false) {
				return false;
			}

			if ($this->productoDao->existeCategoria($subcategria4) === false) {
				$this->setStatus("ERROR");
				$this->setMsj($this->productoDao->getMsj());
				return false;
			}
	
		}

		$subcategria5 = isset($data["subcategria5"]) ? $data["subcategria5"] : '';
		if ($subcategria5 != ''){
			if ($this->validarCategoria($subcategria5) === false) {
				return false;
			}

			if ($this->productoDao->existeCategoria($subcategria5) === false) {
				$this->setStatus("ERROR");
				$this->setMsj($this->productoDao->getMsj());
				return false;
			}
	
		}

		$subcategria6 = isset($data["subcategria6"]) ? $data["subcategria6"] : '';
		if ($subcategria6 != ''){
			if ($this->validarCategoria($subcategria6) === false) {
				return false;
			}

			if ($this->productoDao->existeCategoria($subcategria6) === false) {
				$this->setStatus("ERROR");
				$this->setMsj($this->productoDao->getMsj());
				return false;
			}
	
		}

		$subcategria7 = isset($data["subcategria7"]) ? $data["subcategria7"] : '';
		if ($subcategria7 != ''){
			if ($this->validarCategoria($subcategria7) === false) {
				return false;
			}

			if ($this->productoDao->existeCategoria($subcategria7) === false) {
				$this->setStatus("ERROR");
				$this->setMsj($this->productoDao->getMsj());
				return false;
			}
	
		}

		$subcategria8 = isset($data["subcategria8"]) ? $data["subcategria8"] : '';
		if ($subcategria8 != ''){
			if ($this->validarCategoria($subcategria8) === false) {
				return false;
			}

			if ($this->productoDao->existeCategoria($subcategria8) === false) {
				$this->setStatus("ERROR");
				$this->setMsj($this->productoDao->getMsj());
				return false;
			}
	
		}

		$subcategria9 = isset($data["subcategria9"]) ? $data["subcategria9"] : '';
		if ($subcategria9 != ''){
			if ($this->validarCategoria($subcategria9) === false) {
				return false;
			}

			if ($this->productoDao->existeCategoria($subcategria9) === false) {
				$this->setStatus("ERROR");
				$this->setMsj($this->productoDao->getMsj());
				return false;
			}
	
		}

		$subcategria10 = isset($data["subcategria10"]) ? $data["subcategria10"] : '';
		if ($subcategria10 != ''){
			if ($this->validarCategoria($subcategria10) === false) {
				return false;
			}

			if ($this->productoDao->existeCategoria($subcategria10) === false) {
				$this->setStatus("ERROR");
				$this->setMsj($this->productoDao->getMsj());
				return false;
			}
	
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

			if (isset ($_POST["base"])){

				foreach ($_POST["base"] as $valor) {
					if(!isset($valor)){continue;}
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

			}

			$this->setStatus("OK");
			$this->setMsj($idProducto);
		}
	}
	public function agregarCategoriaProducto(array $data)
	{
		$categoria = isset($data["categoria"]) ? $data["categoria"] : '';
		if ($categoria == ''){
			$this->setStatus("ERROR");
			$this->setMsj("categoria incorrecta");
			return true;
		}
		$idCategoria = $this->productoDao->getIdCategoriaByNombre($categoria);
		if ($idCategoria == 0){
			$idCategoria = $this->productoDao->insertarCategoria($categoria);
		}

		if ($idCategoria == 0){
			$this->setStatus("ERROR");
			$this->setMsj("Categoria incorrecta.");
			return false;
		}

		$subcategoria1 = isset($data["subcategoria1"]) ? $data["subcategoria1"] : '';
		if ($subcategoria1 == ''){
			$this->setStatus("OK");
			$this->setMsj("");
			return true;
		}
		$idSubcategoria1 = $this->productoDao->getIdSubCategoriaByNombre($subcategoria1,$idCategoria);
		if ($idSubcategoria1 == 0){
			$idSubcategoria1 = $this->productoDao->insertarSubCategoria($subcategoria1,$idCategoria);
		}

		if ($idSubcategoria1 == 0){
			$this->setStatus("OK");
			$this->setMsj("");
			return true;
		}
		
		$subcategoria2 = isset($data["subcategoria2"]) ? $data["subcategoria2"] : '';
		if ($subcategoria2 == ''){
			$this->setStatus("OK");
			$this->setMsj("");
			return true;
		}
		$idSubcategoria2 = $this->productoDao->getIdSubCategoriaByNombre($subcategoria2,$idSubcategoria1);
		if ($idSubcategoria2 == 0 ){
			$idSubcategoria2 = $this->productoDao->insertarSubCategoria($subcategoria2,$idSubcategoria1);
		}

		if ($idSubcategoria2 == 0){
			$this->setStatus("OK");
			$this->setMsj("");
			return true;
		}

		$subcategoria3 = isset($data["subcategoria3"]) ? $data["subcategoria3"] : '';
		if ($subcategoria3 == ''){
			$this->setStatus("OK");
			$this->setMsj("");
			return true;
		}
		$idSubcategoria3 = $this->productoDao->getIdSubCategoriaByNombre($subcategoria3,$idSubcategoria2);
		if ($idSubcategoria3 == 0){
			$idSubcategoria3 = $this->productoDao->insertarSubCategoria($subcategoria3,$idSubcategoria2);
		}

		if ($idSubcategoria3 == 0){
			$this->setStatus("OK");
			$this->setMsj("");
			return true;
		}

		$subcategoria4 = isset($data["subcategoria4"]) ? $data["subcategoria4"] : '';
		if ($subcategoria4 == ''){
			$this->setStatus("OK");
			$this->setMsj("");
			return true;
		}
		$idSubcategoria4 = $this->productoDao->getIdSubCategoriaByNombre($subcategoria4,$idSubcategoria3);
		if ($idSubcategoria4 == 0){
			$idSubcategoria4 = $this->productoDao->insertarSubCategoria($subcategoria4,$idSubcategoria3);
		}

		if ($idSubcategoria4 == 0){
			$this->setStatus("OK");
			$this->setMsj("");
			return true;
		
		}

		$subcategoria5 = isset($data["subcategoria5"]) ? $data["subcategoria5"] : '';
		if ($subcategoria5 == ''){
			$this->setStatus("OK");
			$this->setMsj("");
			return true;
		}
		$idSubcategoria5 = $this->productoDao->getIdSubCategoriaByNombre($subcategoria5,$idSubcategoria4);
		if ($idSubcategoria5 == 0){
			$idSubcategoria5 = $this->productoDao->insertarSubCategoria($subcategoria5,$idSubcategoria4);
		}

		if ($idSubcategoria5 == 0){
			$this->setStatus("OK");
			$this->setMsj("");
			return true;
		
		}

		$subcategoria6 = isset($data["subcategoria6"]) ? $data["subcategoria6"] : '';
		if ($subcategoria6 == ''){
			$this->setStatus("OK");
			$this->setMsj("");
			return true;
		}
		$idSubcategoria6 = $this->productoDao->getIdSubCategoriaByNombre($subcategoria6,$idSubcategoria5);
		if ($idSubcategoria6 == 0){
			$idSubcategoria6 = $this->productoDao->insertarSubCategoria($subcategoria6,$idSubcategoria5);
		}

		if ($idSubcategoria6 == 0){
			$this->setStatus("OK");
			$this->setMsj("");
			return true;
		
		}

		$subcategoria7 = isset($data["subcategoria7"]) ? $data["subcategoria7"] : '';
		if ($subcategoria7 == ''){
			$this->setStatus("OK");
			$this->setMsj("");
			return true;
		}
		$idSubcategoria7 = $this->productoDao->getIdSubCategoriaByNombre($subcategoria7,$idSubcategoria6);
		if ($idSubcategoria7 == 0){
			$idSubcategoria7 = $this->productoDao->insertarSubCategoria($subcategoria7,$idSubcategoria6);
		}

		if ($idSubcategoria7 == 0){
			$this->setStatus("OK");
			$this->setMsj("");
			return true;
		
		}

		$subcategoria8 = isset($data["subcategoria8"]) ? $data["subcategoria8"] : '';
		if ($subcategoria8 == ''){
			$this->setStatus("OK");
			$this->setMsj("");
			return true;
		}
		$idSubcategoria8 = $this->productoDao->getIdSubCategoriaByNombre($subcategoria8,$idSubcategoria7);
		if ($idSubcategoria8 == 0){
			$idSubcategoria8 = $this->productoDao->insertarSubCategoria($subcategoria8,$idSubcategoria7);
		}

		if ($idSubcategoria8 == 0){
			$this->setStatus("OK");
			$this->setMsj("");
			return true;
		
		}

		$subcategoria9 = isset($data["subcategoria9"]) ? $data["subcategoria9"] : '';
		if ($subcategoria9 == ''){
			$this->setStatus("OK");
			$this->setMsj("");
			return true;
		}
		$idSubcategoria9 = $this->productoDao->getIdSubCategoriaByNombre($subcategoria9,$idSubcategoria8);
		if ($idSubcategoria9 == 0){
			$idSubcategoria9 = $this->productoDao->insertarSubCategoria($subcategoria9,$idSubcategoria8);
		}

		if ($idSubcategoria9 == 0){
			$this->setStatus("OK");
			$this->setMsj("");
			return true;
		
		}

		$subcategoria10 = isset($data["subcategoria10"]) ? $data["subcategoria10"] : '';
		if ($subcategoria10 == ''){
			$this->setStatus("OK");
			$this->setMsj("");
			return true;
		}
		$idSubcategoria10 = $this->productoDao->getIdSubCategoriaByNombre($subcategoria10,$idSubcategoria9);
		if ($idSubcategoria10 == 0){
			$idSubcategoria10 = $this->productoDao->insertarSubCategoria($subcategoria10,$idSubcategoria9);
		}

		if ($idSubcategoria10 == 0){
			$this->setStatus("OK");
			$this->setMsj("");
			return true;
		
		}

		$this->setStatus("OK");
		$this->setMsj("");
		return true;
	}

	public function importarProducto(array $data)
	{
	
		$file = isset($data["file"]) ? $data["file"] : '';
		if ($file == ''){
			$this->setStatus("error");
			$this->setMsj("Se importaron 0 registros de 0.");
			return false;
		}else{
			$var = file_get_contents($file);
			$data = str_getcsv($var, "\n"); //parse the rows
			$var = '';
			$fila = 0;
			$filaImportadas = 0;
			foreach($data as &$row) {

				$fila++;
				$datacol = str_getcsv($row, ";"); //parse the items in rows
				if (count($datacol) != 8){
				    $this->setStatus("error");
				    $filaSiguiente = $filaImportadas + 1;
				    $this->setMsj("Se importo hasta la linea $filaImportadas incluida. Error en la linea $filaSiguiente -> El formato correcto es: titulo;precio;stock;color;marca;envio;garantia;descripcion. Ejemplo: zapatillas;123;2;rojo;topper;1;1;sin descripción. La fila que se quiere importar es: $row");

			        return false;

				}
		        $dataNew["titulo"] = isset($datacol[0]) ? $datacol[0] : '';
				$dataNew["precio"] = isset($datacol[1]) ? $datacol[1] : '';;
				$dataNew["stock"] = isset($datacol[2]) ? $datacol[2] : '';
				$dataNew["color"] = isset($datacol[3]) ?  $datacol[3] : '';
				$dataNew["marca"] = isset($datacol[4]) ? $datacol[4] : '';
				$dataNew["envio"] = isset($datacol[5]) ?  $datacol[5] : '';
				$dataNew["garantia"] = isset($datacol[6]) ?  $datacol[6] : '';
				$dataNew["descr_producto"] = isset($datacol[7]) ?  $datacol[7] : '';

				$dataNew["categoria"] = '1';
 				$this->agregarProducto($dataNew);
				if ($this->getStatus() != 'OK') {
				    $this->setMsj("Se importo hasta la línea $filaImportadas incluida. Error: ".$this->getMsj());
			            return false;
				}
				$filaImportadas++;
			 }

			$this->setStatus("OK");
			$this->setMsj("Se importaron $filaImportadas registros de $fila");
			return true;
		}

	}
	public function importarCategoria(array $data)
	{
	
		$file = isset($data["file"]) ? $data["file"] : '';
		if ($file == ''){
			$this->setStatus("error");
			$this->setMsj("El csv esta vacio o no se agrego en el formulario.");
			return false;
		}else{
			$var = file_get_contents($file);
			$data = str_getcsv($var, "\n"); //parse the rows
			$var = '';
			$fila = 0;
			$filaImportadas = 0;
			foreach($data as &$row) {
				$fila++;
				$datacol = str_getcsv($row, ";"); //parse the items in rows
		                $dataNew["categoria"] = isset($datacol[0]) ? $datacol[0] : '';
				$dataNew["subcategoria1"] = isset($datacol[1]) ? $datacol[1] : '';;
				$dataNew["subcategoria2"] = isset($datacol[2]) ? $datacol[2] : '';
				$dataNew["subcategoria3"] = isset($datacol[3]) ?  $datacol[3] : '';
				$dataNew["subcategoria4"] = isset($datacol[4]) ?  $datacol[4] : '';
				$dataNew["subcategoria5"] = isset($datacol[5]) ?  $datacol[5] : '';
				$dataNew["subcategoria6"] = isset($datacol[6]) ?  $datacol[6] : '';
				$dataNew["subcategoria7"] = isset($datacol[7]) ?  $datacol[7] : '';
				$dataNew["subcategoria8"] = isset($datacol[8]) ?  $datacol[8] : '';
				$dataNew["subcategoria9"] = isset($datacol[9]) ?  $datacol[9] : '';
				$dataNew["subcategoria10"] = isset($datacol[10]) ?  $datacol[10] : '';
 				$this->agregarCategoriaProducto($dataNew);
				if ($this->getStatus() != 'OK') {
				    $this->setMsj("Se importo hasta la línea $filaImportadas incluida. Error: ".$this->getMsj());
			            return false;
				}
				$filaImportadas++;
			 }

			$this->setStatus("OK");
			$this->setMsj("Se importaron $filaImportadas registros de $fila");
			return true;
		}

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
					if(!isset($valor)){continue;}
					
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

	public function searchSubCategoria(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		if ($this->validarId($id) === false) {
			return false;
		}

		if ($this->productoDao->searchSubCategoria($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->productoDao->getMsj());
			return false;
		} else {
			$this->setStatus("OK");
			$this->setMsj($this->productoDao->getMsj());
			return 'ok';
		}
	}



	public function searchProductoSeller(array $data)
	{
		$input = isset($data["input"]) ? $data["input"] : '';
		if ($this->validarInputSearch($input) === false) {
			return false;
		}

		if ($this->productoDao->searchProductosSeller($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->productoDao->getMsj());
			return false;
		} else {
			$this->setStatus("OK");
			$this->setMsj($this->productoDao->getMsj());
			return 'ok';
		}
	}

	public function searchProductoPicker(array $data)
	{
		$input = isset($data["input"]) ? $data["input"] : '';
		if ($this->validarInputSearch($input) === false) {
			return false;
		}

		if ($this->productoDao->searchProductosPicker($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->productoDao->getMsj());
			return false;
		} else {
			$this->setStatus("OK");
			$this->setMsj($this->productoDao->getMsj());
			return 'ok';
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

	private function validarTitulo($titulo)
	{
		if (!preg_match('/^.+$/i', $titulo)) {
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
	private function validarMarca($marca)
	{
		if (!preg_match('/^.+$/i', $marca)) {
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
		if (!preg_match('/^.+$/im', $descr_producto)) {
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
		if (!preg_match('/^.+$/i', $color)) {
			$this->setStatus("ERROR");
			$this->setMsj("El campo color es incorrecto.");
			return false;
		}
		$this->setStatus("OK");
		$this->setMsj("");
		return true;
	}

	public function getListCategoriaPadre()
	{
		return $this->productoDao->getListCategoriaPadre();
	}

	public function getListCategoria()
	{
		return $this->productoDao->getListCategoria();
	}
	public function getListProducto()
	{
		$ret =  $this->productoDao->getListProducto();
		return $ret;
	}
	public function getListProductoIndex()
	{
		$ret =  $this->productoDao->getListProductoIndex();
		return $ret;
	}

	public function existeId($id)
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
	
	public function existeIdCarrito($id)
	{
		$id = isset($id) ? $id : '';
		if ($this->validarId($id) === false) {
			return false;
		}
		if ($this->productoDao->existeIdCarrito($id) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->productoDao->getMsj());
			return false;
		}
		return true;
	}

	public function getProductoCarrito(array $data)
	{

		$id = isset($data["id_producto"]) ? $data["id_producto"] : '';
		if ($this->existeIdCarrito($id) === false) {
			return [];
		}
	
		$producto = $this->productoDao->getProductoCarrito($id);
		if ($this->productoDao->getStatus() != 'ok') {
			$this->setStatus("ERROR");
			$this->setMsj($this->productoDao->getMsj());
			return [];
		}
		$this->setStatus("ok");
		return $producto;
	}
	
	public function getProductosByIdPublicacion(array $data)
	{

		$id = isset($data["id"]) ? $data["id"] : '';
		if ($this->validarId($id) === false) {
			return [];
		}
	
		$producto = $this->productoDao->getProductosByIdPublicacion($id);
		if ($this->productoDao->getStatus() != 'ok') {
			$this->setStatus("ERROR");
			$this->setMsj($this->productoDao->getMsj());
			return [];
		}
		$this->setStatus("ok");
		return $producto;
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

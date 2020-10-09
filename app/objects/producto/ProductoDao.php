<?php
//include_once("../util/database.php");
class  ProductoDao
{
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


	public function altaProducto(array $data)
	{
		        
                $titulo = isset($data["titulo"]) ? $data["titulo"] : '';
                $tituloDB = Database::escape($titulo);        
                $categoria = isset($data["categoria"]) ? $data["categoria"] : '';
                $categoriaDB = Database::escape($categoria);        
                $rubro = isset($data["rubro"]) ? $data["rubro"] : '';
                $rubroDB = Database::escape($rubro);        
                $marca = isset($data["marca"]) ? $data["marca"] : '';
                $marcaDB = Database::escape($marca);        
                $precio = isset($data["precio"]) ? $data["precio"] : '';
                $precioDB = Database::escape($precio);        
                $envio = isset($data["envio"]) ? $data["envio"] : '';
                $envioDB = Database::escape($envio);        
                $garantia = isset($data["garantia"]) ? $data["garantia"] : '';
                $garantiaDB = Database::escape($garantia);        
                $descr_producto = isset($data["descr_producto"]) ? $data["descr_producto"] : '';
                $descr_productoDB = Database::escape($descr_producto);        
                $color = isset($data["color"]) ? $data["color"] : '';
                $colorDB = Database::escape($color);

		$sql = <<<SQL
			INSERT INTO producto (titulo, id_categoria, id_rubro, marca, precio, envio, garantia, descr_producto, color)  
			VALUES ($tituloDB, $categoriaDB, $rubroDB, $marcaDB, $precioDB, $envioDB, $garantiaDB, $descr_productoDB, $colorDB)
SQL;

		if (!mysqli_query(Database::Connect(), $sql)) {
			$this->setStatus("ERROR");
			$this->setMsj("$sql" . Database::Connect()->error);
		} else {
			$id = mysqli_insert_id(Database::Connect());
			$this->setMsj($id);
			$this->setStatus("OK");
			return true;
		}

		return false;
	}


	public function editarProducto(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		$idDB = Database::escape($id);
		        
                $titulo = isset($data["titulo"]) ? $data["titulo"] : '';
                $tituloDB = Database::escape($titulo);        
                $categoria = isset($data["categoria"]) ? $data["categoria"] : '';
                $categoriaDB = Database::escape($categoria);        
                $rubro = isset($data["rubro"]) ? $data["rubro"] : '';
                $rubroDB = Database::escape($rubro);        
                $marca = isset($data["marca"]) ? $data["marca"] : '';
                $marcaDB = Database::escape($marca);        
                $precio = isset($data["precio"]) ? $data["precio"] : '';
                $precioDB = Database::escape($precio);        
                $envio = isset($data["envio"]) ? $data["envio"] : '';
                $envioDB = Database::escape($envio);        
                $garantia = isset($data["garantia"]) ? $data["garantia"] : '';
                $garantiaDB = Database::escape($garantia);        
                $descr_producto = isset($data["descr_producto"]) ? $data["descr_producto"] : '';
                $descr_productoDB = Database::escape($descr_producto);        
                $color = isset($data["color"]) ? $data["color"] : '';
                $colorDB = Database::escape($color);

		$sql = <<<SQL

	SQL;

		if (!mysqli_query(Database::Connect(), $sql)) {
			$this->setStatus("ERROR");
			$this->setMsj("$sql" . Database::Connect()->error);
		} else {
			$id = mysqli_insert_id(Database::Connect());
			$this->setMsj($id);
			$this->setStatus("OK");
			return true;
		}

		return false;
	}

	public function eliminarProducto(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		$idDB = Database::escape($id);
		        
                $titulo = isset($data["titulo"]) ? $data["titulo"] : '';
                $tituloDB = Database::escape($titulo);        
                $categoria = isset($data["categoria"]) ? $data["categoria"] : '';
                $categoriaDB = Database::escape($categoria);        
                $rubro = isset($data["rubro"]) ? $data["rubro"] : '';
                $rubroDB = Database::escape($rubro);        
                $marca = isset($data["marca"]) ? $data["marca"] : '';
                $marcaDB = Database::escape($marca);        
                $precio = isset($data["precio"]) ? $data["precio"] : '';
                $precioDB = Database::escape($precio);        
                $envio = isset($data["envio"]) ? $data["envio"] : '';
                $envioDB = Database::escape($envio);        
                $garantia = isset($data["garantia"]) ? $data["garantia"] : '';
                $garantiaDB = Database::escape($garantia);        
                $descr_producto = isset($data["descr_producto"]) ? $data["descr_producto"] : '';
                $descr_productoDB = Database::escape($descr_producto);        
                $color = isset($data["color"]) ? $data["color"] : '';
                $colorDB = Database::escape($color);

		$sql = <<<SQL

	SQL;

		if (!mysqli_query(Database::Connect(), $sql)) {
			$this->setStatus("ERROR");
			$this->setMsj("$sql" . Database::Connect()->error);
		} else {
			$id = mysqli_insert_id(Database::Connect());
			$this->setMsj($id);
			$this->setStatus("OK");
			return true;
		}

		return false;
	}

	public function getProducto(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		$idDB = Database::escape($id);
		        
                $titulo = isset($data["titulo"]) ? $data["titulo"] : '';
                $tituloDB = Database::escape($titulo);        
                $categoria = isset($data["categoria"]) ? $data["categoria"] : '';
                $categoriaDB = Database::escape($categoria);        
                $rubro = isset($data["rubro"]) ? $data["rubro"] : '';
                $rubroDB = Database::escape($rubro);        
                $marca = isset($data["marca"]) ? $data["marca"] : '';
                $marcaDB = Database::escape($marca);        
                $precio = isset($data["precio"]) ? $data["precio"] : '';
                $precioDB = Database::escape($precio);        
                $envio = isset($data["envio"]) ? $data["envio"] : '';
                $envioDB = Database::escape($envio);        
                $garantia = isset($data["garantia"]) ? $data["garantia"] : '';
                $garantiaDB = Database::escape($garantia);        
                $descr_producto = isset($data["descr_producto"]) ? $data["descr_producto"] : '';
                $descr_productoDB = Database::escape($descr_producto);        
                $color = isset($data["color"]) ? $data["color"] : '';
                $colorDB = Database::escape($color);

		$sql = <<<SQL

	SQL;

		if (!mysqli_query(Database::Connect(), $sql)) {
			$this->setStatus("ERROR");
			$this->setMsj("$sql" . Database::Connect()->error);
		} else {
			$id = mysqli_insert_id(Database::Connect());
			$this->setMsj($id);
			$this->setStatus("OK");
			return true;
		}

		return false;
	}

	public function listarProducto(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		$idDB = Database::escape($id);
		        
                $titulo = isset($data["titulo"]) ? $data["titulo"] : '';
                $tituloDB = Database::escape($titulo);        
                $categoria = isset($data["categoria"]) ? $data["categoria"] : '';
                $categoriaDB = Database::escape($categoria);        
                $rubro = isset($data["rubro"]) ? $data["rubro"] : '';
                $rubroDB = Database::escape($rubro);        
                $marca = isset($data["marca"]) ? $data["marca"] : '';
                $marcaDB = Database::escape($marca);        
                $precio = isset($data["precio"]) ? $data["precio"] : '';
                $precioDB = Database::escape($precio);        
                $envio = isset($data["envio"]) ? $data["envio"] : '';
                $envioDB = Database::escape($envio);        
                $garantia = isset($data["garantia"]) ? $data["garantia"] : '';
                $garantiaDB = Database::escape($garantia);        
                $descr_producto = isset($data["descr_producto"]) ? $data["descr_producto"] : '';
                $descr_productoDB = Database::escape($descr_producto);        
                $color = isset($data["color"]) ? $data["color"] : '';
                $colorDB = Database::escape($color);

		$sql = <<<SQL

	SQL;

		if (!mysqli_query(Database::Connect(), $sql)) {
			$this->setStatus("ERROR");
			$this->setMsj("$sql" . Database::Connect()->error);
		} else {
			$id = mysqli_insert_id(Database::Connect());
			$this->setMsj($id);
			$this->setStatus("OK");
			return true;
		}

		return false;
	}

	
                public function existeCategoria($id_categoria)
                {
                    $id_categoria = isset($id_categoria) ?   $id_categoria : '';
                    $id_categoriaDB = Database::escape($id_categoria);      
            
                    $sql = <<<SQL
                        SELECT *FROM categoria
                        WHERE 
                            id = $id_categoriaDB AND
                            (eliminar = 0 OR eliminar is null);
SQL;            

                    $resultado=mysqli_query(Database::Connect(), $sql);
                    $row_cnt = mysqli_num_rows($resultado);
                    if ($row_cnt == 1){
                        $this->setStatus("OK");
                        return true;
                    }
                    
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo categoria es incorrecto.");
                    return false;

                }

                public function existeRubro($id_rubro)
                {
                    $id_rubro = isset($id_rubro) ?   $id_rubro : '';
                    $id_rubroDB = Database::escape($id_rubro);      
            
                    $sql = <<<SQL
                        SELECT *FROM rubro
                        WHERE 
                            id = $id_rubroDB AND
                            (eliminar = 0 OR eliminar is null);
SQL;            

                    $resultado=mysqli_query(Database::Connect(), $sql);
                    $row_cnt = mysqli_num_rows($resultado);
                    if ($row_cnt == 1){
                        $this->setStatus("OK");
                        return true;
                    }
                    
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo rubro es incorrecto.");
                    return false;

                }

                public function altaFoto(array $data)
                {
                    $id_producto = isset($data["id_producto"]) ?   $data["id_producto"] : '';
                    $id_productoDB = Database::escape($id_producto);      
            
                    $foto = isset($data["foto"]) ?  $data["foto"] : '';
                    $fotoDB = Database::escape($foto);      

                    $sql = <<<SQL
                        INSERT INTO producto_foto (id_producto,foto) 
                        VALUES ($id_productoDB,$fotoDB)
SQL;
            
                    if (!mysqli_query(Database::Connect(), $sql)) {
                        $this->setStatus("ERROR");
                        $this->setMsj("$sql" . Database::Connect()->error);
                    } else {
                        $id = mysqli_insert_id(Database::Connect());
                        $this->setMsj($id);
                        $this->setStatus("OK");
                        return true;
                    }
            
                    return false;
                }

}

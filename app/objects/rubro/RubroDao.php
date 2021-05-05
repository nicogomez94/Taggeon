<?php
//include_once("../util/database.php");
class  RubroDao
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


	public function altaRubro(array $data)
	{
		        
                $nombre = isset($data["nombre"]) ? $data["nombre"] : '';
                $nombreDB = Database::escape($nombre);        
                $categoria = isset($data["categoria"]) ? $data["categoria"] : '';
                $categoriaDB = Database::escape($categoria);

		$sql = <<<SQL
			INSERT INTO rubro (nombre, id_categoria)  
			VALUES ($nombreDB, $categoriaDB)
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


	public function editarRubro(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		$idDB = Database::escape($id);
		        
                $nombre = isset($data["nombre"]) ? $data["nombre"] : '';
                $nombreDB = Database::escape($nombre);        
                $categoria = isset($data["categoria"]) ? $data["categoria"] : '';
                $categoriaDB = Database::escape($categoria);

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

	public function eliminarRubro(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		$idDB = Database::escape($id);
		        
                $nombre = isset($data["nombre"]) ? $data["nombre"] : '';
                $nombreDB = Database::escape($nombre);        
                $categoria = isset($data["categoria"]) ? $data["categoria"] : '';
                $categoriaDB = Database::escape($categoria);

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

	public function getRubro(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		$idDB = Database::escape($id);
		        
                $nombre = isset($data["nombre"]) ? $data["nombre"] : '';
                $nombreDB = Database::escape($nombre);        
                $categoria = isset($data["categoria"]) ? $data["categoria"] : '';
                $categoriaDB = Database::escape($categoria);

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

	public function listarRubro(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		$idDB = Database::escape($id);
		        
                $nombre = isset($data["nombre"]) ? $data["nombre"] : '';
                $nombreDB = Database::escape($nombre);        
                $categoria = isset($data["categoria"]) ? $data["categoria"] : '';
                $categoriaDB = Database::escape($categoria);

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


}

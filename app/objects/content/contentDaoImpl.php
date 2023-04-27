<?php
include_once($GLOBALS['configuration']['path_app_admin_objects']."content/contentDao.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."util/database.php");
class  ContentDaoImpl implements ContentDao{
	private $status    = "";
	private $msj       = "";

	public function __construct(){
   } 
	
	public function getStatus(){
		return $this->status;
	}	

	private  function setStatus($status){
		$this->status = $status;
	}
	
	public function getMsj(){
		return $this->msj;
	}	

	private function setMsj($msj){
		$this->msj = $msj;
	}


	public function editar (Content $content){
		$idUsuario = $content->getUsuario()->getId();
		$id = $content->getId();
		$title = Database::escape($content->getTitle());
		$htmlIng = Database::escape($content->getHtmlIng());
		$titulo = Database::escape($content->getTitulo());
		$htmlEsp = Database::escape($content->getHtmlEsp());

		$idBase = Database::escape($id);
		$idUsuarioBase = Database::escape($idUsuario);
		$sql =<<<SQL
			UPDATE `post` SET 
					`title`=$title,`html`=$htmlIng,`titulo_esp`=$titulo,`html_esp`=$htmlEsp
			WHERE 
					 `id` = $idBase  
SQL;
       if (!mysql_query($sql,Database::Connect())){
			$this->setStatus("error");
			$this->setMsj(getMsjConf('391'));
       }else{
			$this->setStatus("ok");
			$this->setMsj("");
       }

   }

	public function get ($idParam){
		$content = new Content();
		$idBase = Database::escape($idParam);
		$sql =<<<SQL
			SELECT * 
			FROM `post` 
			WHERE 
					`eliminar` = 0 AND
					`id` = $idBase
SQL;

		$resultado=mysql_query($sql,Database::Connect());
		
		while($rowEmp=mysql_fetch_array($resultado)){
			$id = $rowEmp['id'];
      	if (empty($id)){
				$id = "";
      	}

			$idUsuario = $rowEmp['idUsuario'];
      	if (empty($idUsuario)){
				$idUsuario = "";
      	}

			$fechaAlta = $rowEmp['fechaAlta'];
      	if (empty($fechaAlta)){
				$fechaAlta = "";
      	}

			$fechaUpdate = $rowEmp['fechaUpdate'];
      	if (empty($fechaUpdate)){
				$fechaUpdate = "";
      	}
			
			$url = $rowEmp['url'];
      	if (empty($url)){
				$url = "";
      	}

			$title = $rowEmp['title'];
      	if (empty($title)){
				$title = "";
      	}
			$htmlIng = $rowEmp['html'];
      	if (empty($htmlIng)){
				$htmlIng = "";
      	}			
			$titulo = $rowEmp['titulo_esp'];
      	if (empty($titulo)){
				$titulo = "";
      	}			
			$htmlEsp = $rowEmp['html_esp'];
      	if (empty($htmlEsp)){
				$htmlEsp = "";
      	}		
			$content->setId($id);
			$content->setIdUsuario($idUsuario);
			$content->setFechaAlta($fechaAlta);
			$content->setFechaUpdate($fechaUpdate);
			$content->setUrl($url);
			$content->setTitle($title);
			$content->setHtmlIng($htmlIng);
			$content->setTitulo($titulo);
			$content->setHtmlEsp($htmlEsp);

		}
		return $content;
   }

	
	public function getList (){
		$list = Array();
		$sql =<<<SQL
			SELECT * 
			FROM `post`
			WHERE `eliminar` = 0
		  ORDER BY url DESC 
SQL;

		$resultado=mysql_query($sql,Database::Connect());
		
		while($rowEmp=mysql_fetch_array($resultado)){
			$content = new Content();

			$id = $rowEmp['id'];
      	if (empty($id)){
				$id = "";
      	}

			$idUsuario = $rowEmp['idUsuario'];
      	if (empty($idUsuario)){
				$idUsuario = "";
      	}

			$fechaAlta = $rowEmp['fechaAlta'];
      	if (empty($fechaAlta)){
				$fechaAlta = "";
      	}

			$fechaUpdate = $rowEmp['fechaUpdate'];
      	if (empty($fechaUpdate)){
				$fechaUpdate = "";
      	}
			
			$url = $rowEmp['url'];
      	if (empty($url)){
				$url = "";
      	}

			$title = $rowEmp['title'];
      	if (empty($title)){
				$title = "";
      	}
			$htmlIng = $rowEmp['html'];
      	if (empty($htmlIng)){
				$htmlIng = "";
      	}			
			$titulo = $rowEmp['titulo_esp'];
      	if (empty($titulo)){
				$titulo = "";
      	}			
			$htmlEsp = $rowEmp['html_esp'];
      	if (empty($htmlEsp)){
				$htmlEsp = "";
      	}		
			$content->setId($id);
			$content->setIdUsuario($idUsuario);
			$content->setFechaAlta($fechaAlta);
			$content->setFechaUpdate($fechaUpdate);
			$content->setUrl($url);
			$content->setTitle($title);
			$content->setHtmlIng($htmlIng);
			$content->setTitulo($titulo);
			$content->setHtmlEsp($htmlEsp);

			$list[] = $content;
		}
		return $list;
   }
	

}
?>

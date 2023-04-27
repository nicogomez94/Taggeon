<?php
include_once($GLOBALS['configuration']['path_app_admin_objects']."content/content.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."content/contentManager.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."content/contentDaoImpl.php");

class ContentManagerImpl implements  ContentManager{
	private $contentDao;
	private $status    = "";
	private $msj       = "";
	private $paginador = "";

   public function __construct(){
		$this->contentDao = new ContentDaoImpl();
   } 
	
	public function getPaginador(){
		return $this->paginador;
	}	

	private  function setPaginador($paginador){
		$this->paginador = $paginador;
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

	private function getContentDao(){
		return $this->contentDao;
	}
	

	public function actualizar(){
		$perfil = $GLOBALS['sesionG']['perfil'];
		$id        = $GLOBALS['sesionG']['id'];
		$idUsuario = $GLOBALS['sesionG']['idUsuario'];

		$usuario = new Usuario();
		$usuario->setId($idUsuario);

		$content = new Content();
		$content->setUsuario($usuario);
		$content->setIdUsuario($idUsuario);
		
		$id = isset($_POST["id"]) ? $_POST["id"] : '';
		$content->setId($id);

		$title = isset($_POST["title"]) ? $_POST["title"] : '';
		$content->setTitle(_trim($title));

		$htmlIng = isset($_POST["html_ing"]) ? $_POST["html_ing"] : '';
		$content->setHtmlIng(_trim($htmlIng));

		$titulo = isset($_POST["titulo"]) ? $_POST["titulo"] : '';
		$content->setTitulo(_trim($titulo));

		$htmlEsp = isset($_POST["html_esp"]) ? $_POST["html_esp"] : '';
		$content->setHtmlEsp(_trim($htmlEsp));
/*
		//validar
		$this->validarId($content->getId());
		if ($this->getStatus() != 'ok'){
			return $content;
		}
		$this->validarTitle($content->getTitle());
		if ($this->getStatus() != 'ok'){
			return $content;
		}
		
		$this->validarHtmlIng($content->getHtmlIng());
		if ($this->getStatus() != 'ok'){
			return $content;
		}
		
		$this->validarTitulo($content->getTitulo());
		if ($this->getStatus() != 'ok'){
			return $content;
		}

		$this->validarHtmlEsp($content->getHtmlEsp());
		if ($this->getStatus() != 'ok'){
			return $content;
		}
		*/
		$this->getContentDao()->editar($content);	
		if ($this->getContentDao()->getStatus() != "ok"){
			$this->setStatus("error");
			$this->setMsj($this->getContentDao()->getMsj());
		}else{
			$this->setStatus("ok");
			$this->setMsj(""); 
		}
		return $content;
	}

	public function get(){
		$id  = isset($_GET["id"]) ? $_GET["id"] : '';
		$content = new Content();
		$content->setId($id);

		$this->validarId($content->getId());
		if ($this->getStatus() == 'ok'){
			$content =  $this->getContentDao()->get($content->getId());
		}
		return $content;
	}
	
	public function listar(){
		$list = $this->getContentDao()->getList();

		$filas = "";	
		foreach ($list as $clave=>$content){
			$contenido = new Template("content_filas");
			$contenido->asigna_variables(array(
				"id"      => $content->getId(),
				"title"   => $content->getTitle(),
				"htmlIng" => $content->getHtmlIng(),
				"titulo"  => $content->getTitulo(),
				"htmlEsp" => $content->getHtmlEsp(),
				"url"     => $content->getUrl(),
			));
			$contenidoString = $contenido->muestra();
			$filas .= $contenidoString;
		}
		$this->setPaginador("");
		return $filas;
	}
	
	private function validarId ($param){
		$this->setStatus("error");
		$this->setMsj("");
		$validSql = validSqlInjection($param);
		if ($validSql != ''){
			$this->setMsj(getMsjConf('390')." $validSql.");
		}else{
			$this->setStatus("ok");
		}
	}
	
	private function validarTitle ($param){
		$this->setStatus("error");
		$this->setMsj("");
		$validSql = validSqlInjection($param);
		if ($validSql != ''){
			$this->setMsj(getMsjConf('389')." $validSql.");
		}else{
			$this->setStatus("ok");
		}
	}
	
	private function validarHtmlIng ($param){
		$this->setStatus("error");
		$this->setMsj("");
		$validSql = validSqlInjection($param);
		if ($validSql != ''){
			$this->setMsj(getMsjConf('388')." $validSql.");
		}else{
			$this->setStatus("ok");
		}
	}
	
	private function validarTitulo ($param){
		$this->setStatus("error");
		$this->setMsj("");
		$validSql = validSqlInjection($param);
		if ($validSql != ''){
			$this->setMsj(getMsjConf('387')." $validSql.");
		}else{
			$this->setStatus("ok");
		}
	}
	
	private function validarHtmlEsp ($param){
		$this->setStatus("error");
		$this->setMsj("");
		$validSql = validSqlInjection($param);
		if ($validSql != ''){
			$this->setMsj(getMsjConf('386')." $validSql.");
		}else{
			$this->setStatus("ok");
		}
	}

}
?>

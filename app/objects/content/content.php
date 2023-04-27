<?php
class Content{
	private $id = "";
	private $idUsuario = "";
	private $url = "";
	private $title = "";
	private $htmlIng = "";
	private $titulo = "";
	private $htmlEsp = "";
	private $fechaAlta = "";
	private $fechaUpdate = "";

	private $usuario;
   public function __construct(){
		$this->usuario = new Usuario();
		$this->getUsuario()->setPerfil('content');
   } 
	public function getUsuario(){
		return $this->usuario;
	}
	
	public function setUsuario($usuario){
		$this->usuario = $usuario;
	}

	public function getId(){
		return $this->id;
	}
	
	public function setId($id){
		$this->id = $id;
	}
	public function getIdUsuario(){
		return $this->idUsuario;
	}
	
	public function setIdUsuario($idUsuario){
		$this->idUsuario = $idUsuario;
	}

	public function getUrl(){
		return $this->url;
	}
	
	public function setUrl($url){
		$this->url = $url;
	}

	public function getTitle(){
		return $this->title;
	}
	
	public function setTitle($title){
		$this->title = $title;
	}
	public function getHtmlIng(){
		return $this->htmlIng;
	}
	
	public function setHtmlIng($htmlIng){
		$this->htmlIng = $htmlIng;
	}
	public function getTitulo(){
		return $this->titulo;
	}
	
	public function setTitulo($titulo){
		$this->titulo = $titulo;
	}
	public function getHtmlEsp(){
		return $this->htmlEsp;
	}
	
	public function setHtmlEsp($htmlEsp){
		$this->htmlEsp = $htmlEsp;
	}
	public function getFechaAlta(){
		return $this->fechaAlta;
	}
	
	public function setFechaAlta($fechaAlta){
		$this->fechaAlta = $fechaAlta;
	}
	public function getFechaUpdate(){
		return $this->fechaUpdate;
	}
	
	public function setFechaUpdate($fechaUpdate){
		$this->fechaUpdate = $fechaUpdate;
	}

}
?>

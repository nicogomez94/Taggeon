<?php
class Usuario{
	private $id = "";
	private $usuario = "";
	private $pass = "";
	private $pass2 = "";
	private $perfil = "";
	private $fechaAlta = "";
	private $fechaUpdate = "";

   public function __construct(){

   } 

	public function getId(){
		return $this->id;
	}
	
	public function setId($id){
		$this->id = $id;
	}
	public function getUsuario(){
		return $this->usuario;
	}
	
	public function setUsuario($usuario){
		$this->usuario = $usuario;
	}
	public function getPass(){
		return $this->pass;
	}
	
	public function setPass($pass){
		$this->pass = $pass;
	}

	public function getPass2(){
		return $this->pass2;
	}
	
	public function setPass2($pass2){
		$this->pass2 = $pass2;
	}
	public function getPerfil(){
		return $this->perfil;
	}
	
	public function setPerfil($perfil){
		$this->perfil = $perfil;
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

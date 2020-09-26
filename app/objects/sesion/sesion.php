<?php

class Sesion{
	private $usuario   = "";
	private $identify  = "";
	private $perfil    = "";
	private $idUsuario = "";
	private $obj;

   public function __construct(){
   } 

	public function getUsuario(){
		return $this->usuario;
	}
	
	public function setUsuario($usuario){
		$this->usuario = $usuario;
	}

	public function getIdentify(){
		return $this->identify;
	}

	public function setIdentify($identify){
		$this->identify = $identify;
	}


	public function getPerfil(){
		return $this->perfil;
	}

	public function setPerfil($perfil){
		$this->perfil = $perfil;
	}
	
	public function getIdUsuario(){
		return $this->idUsuario;
	}

	public function setIdUsuario($idUsuario){
		$this->idUsuario = $idUsuario;
	}
	
	public function getObj(){
		return $this->obj;
	}

	public function setObj($obj){
		$this->obj = $obj;
	}

}
?>

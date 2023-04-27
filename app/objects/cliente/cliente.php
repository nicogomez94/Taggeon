<?php
/*
REMP
		"id" =>$cliente->getId(),
		"idUsuario" =>$cliente->getIdUsuario(),
		"nombre" =>$cliente->getNombre(),
		"apellido" =>$cliente->getApellido(),
		"ciudad" =>$cliente->getCiudad(),
		"estado" =>$cliente->getEstado(),
		"codigoPostal" =>$cliente->getCodigoPostal(),
		"pais" =>$cliente->getPais(),
		"telefono1Pais" =>$cliente->getTelefono1Pais(),
		"telefono1Ciudad" =>$cliente->getTelefono1Ciudad(),
		"telefono1" =>$cliente->getTelefono1(),
		"telefono1Tipo" =>$cliente->getTelefono1Tipo(),
		"telefono2Pais" =>$cliente->getTelefono2Pais(),
		"telefono2Ciudad" =>$cliente->getTelefono2Ciudad(),
		"telefono2" =>$cliente->getTelefono2(),
		"telefono2Tipo" =>$cliente->getTelefono2Tipo(),
		"fechaAlta" =>$cliente->getFechaAlta(),
		"fechaUpdate" =>$cliente->getFechaUpdate(),


GET
		$id = $cliente->getId();
		$idUsuario = $cliente->getIdUsuario();
		$nombre = $cliente->getNombre();
		$apellido = $cliente->getApellido();
		$ciudad = $cliente->getCiudad();
		$estado = $cliente->getEstado();
		$codigoPostal = $cliente->getCodigoPostal();
		$pais = $cliente->getPais();
		$telefono1Pais = $cliente->getTelefono1Pais();
		$telefono1Ciudad = $cliente->getTelefono1Ciudad();
		$telefono1 = $cliente->getTelefono1();
		$telefono1Tipo = $cliente->getTelefono1Tipo();
		$telefono2Pais = $cliente->getTelefono2Pais();
		$telefono2Ciudad = $cliente->getTelefono2Ciudad();
		$telefono2 = $cliente->getTelefono2();
		$telefono2Tipo = $cliente->getTelefono2Tipo();
		$fechaAlta = $cliente->getFechaAlta();
		$fechaUpdate = $cliente->getFechaUpdate();

SET
		$cliente->setId($id);
		$cliente->setIdUsuario($idUsuario);
		$cliente->setNombre($nombre);
		$cliente->setApellido($apellido);
		$cliente->setCiudad($ciudad);
		$cliente->setEstado($estado);
		$cliente->setCodigoPostal($codigoPostal);
		$cliente->setPais($pais);
		$cliente->setTelefono1Pais($telefono1Pais);
		$cliente->setTelefono1Ciudad($telefono1Ciudad);
		$cliente->setTelefono1($telefono1);
		$cliente->setTelefono1Tipo($telefono1Tipo);
		$cliente->setTelefono2Pais($telefono2Pais);
		$cliente->setTelefono2Ciudad($telefono2Ciudad);
		$cliente->setTelefono2($telefono2);
		$cliente->setTelefono2Tipo($telefono2Tipo);
		$cliente->setFechaAlta($fechaAlta);
		$cliente->setFechaUpdate($fechaUpdate);

*/
class Cliente{
	private $id = "";
	private $idUsuario = "";
	private $nombre = "";
	private $apellido = "";
	private $email = "";
	private $ciudad = "";
	private $estado = "";
	private $codigoPostal = "";
	private $pais = "";
	private $telefono1Pais = "";
	private $telefono1Ciudad = "";
	private $telefono1 = "";
	private $telefono1Tipo = "";
	private $telefono2Pais = "";
	private $telefono2Ciudad = "";
	private $telefono2 = "";
	private $telefono2Tipo = "";
	private $fechaAlta = "";
	private $fechaUpdate = "";
	private $tokenMercadoPago = "";

	private $usuario;
   public function __construct(){
		$this->usuario = new Usuario();
		$this->getUsuario()->setPerfil('picker');
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
	public function getNombre(){
		return $this->nombre;
	}
	
	public function setNombre($nombre){
		$this->nombre = $nombre;
	}
	public function getApellido(){
		return $this->apellido;
	}
	
	public function setApellido($apellido){
		$this->apellido = $apellido;
	}
	public function getEmail(){
		return $this->email;
	}
	
	public function setEmail($email){
		$this->email = $email;
	}
	public function getCiudad(){
		return $this->ciudad;
	}
	
	public function setCiudad($ciudad){
		$this->ciudad = $ciudad;
	}
	public function getEstado(){
		return $this->estado;
	}
	
	public function setEstado($estado){
		$this->estado = $estado;
	}
	public function getCodigoPostal(){
		return $this->codigoPostal;
	}
	
	public function setCodigoPostal($codigoPostal){
		$this->codigoPostal = $codigoPostal;
	}
	public function getPais(){
		return $this->pais;
	}
	
	public function setPais($pais){
		$this->pais = $pais;
	}
	public function getTelefono1Pais(){
		return $this->telefono1Pais;
	}
	
	public function setTelefono1Pais($telefono1Pais){
		$this->telefono1Pais = $telefono1Pais;
	}
	public function getTelefono1Ciudad(){
		return $this->telefono1Ciudad;
	}
	
	public function setTelefono1Ciudad($telefono1Ciudad){
		$this->telefono1Ciudad = $telefono1Ciudad;
	}
	public function getTelefono1(){
		return $this->telefono1;
	}
	
	public function setTelefono1($telefono1){
		$this->telefono1 = $telefono1;
	}
	public function getTelefono1Tipo(){
		return $this->telefono1Tipo;
	}
	
	public function setTelefono1Tipo($telefono1Tipo){
		$this->telefono1Tipo = $telefono1Tipo;
	}
	public function getTelefono2Pais(){
		return $this->telefono2Pais;
	}
	
	public function setTelefono2Pais($telefono2Pais){
		$this->telefono2Pais = $telefono2Pais;
	}
	public function getTelefono2Ciudad(){
		return $this->telefono2Ciudad;
	}
	
	public function setTelefono2Ciudad($telefono2Ciudad){
		$this->telefono2Ciudad = $telefono2Ciudad;
	}
	public function getTelefono2(){
		return $this->telefono2;
	}
	
	public function setTelefono2($telefono2){
		$this->telefono2 = $telefono2;
	}
	public function getTelefono2Tipo(){
		return $this->telefono2Tipo;
	}
	
	public function setTelefono2Tipo($telefono2Tipo){
		$this->telefono2Tipo = $telefono2Tipo;
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

	public function getTokenMercadoPago(){
		return $this->tokenMercadoPago;
	}
	
	public function setTokenMercadoPago($tokenMercadoPago){
		$this->tokenMercadoPago = $tokenMercadoPago;
	}

}
?>

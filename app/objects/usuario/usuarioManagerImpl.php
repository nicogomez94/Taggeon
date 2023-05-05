<?php
include_once($GLOBALS['configuration']['path_app_admin_objects']."usuario/usuario.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."usuario/usuarioManager.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."usuario/usuarioDaoImpl.php");
include_once($GLOBALS['configuration']['path_app_admin_objects']."util/template.php");


class  UsuarioManagerImpl implements  UsuarioManager{
	private $usuarioDao;
	private $status    = "";
	private $msj       = "";
	private $paginador = "";

   public function __construct(){
		$this->usuarioDao = new UsuarioDaoImpl();
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

	private function getUsuarioDao(){
		return $this->usuarioDao;
	}
	
	public function crear($usuario_var,$pass,$pass2,$perfil){
		$usuario = new Usuario();
		$usuario->setUsuario(_trim($usuario_var));
		$usuario->setPass($pass);
		$usuario->setPass2($pass2);
		$usuario->setPerfil($perfil);

		
		//validar
		$this->validarUsuario($usuario->getUsuario());
		if ($this->getStatus() != 'ok'){
			return $usuario;
		}
		
		$this->validarPerfil($usuario->getPerfil());
		if ($this->getStatus() != 'ok'){
			return $usuario;
		}

		if ($this->getUsuarioDao()->existeUsuario($usuario->getUsuario())){
			$this->setStatus("error");
			$this->setMsj(getMsjConf('306'));
			return $usuario;
		}
		
		$this->validarPass($usuario->getPass());
		if ($this->getStatus() != 'ok'){
			return $usuario;
		}

		if ($pass == ''){
			$this->setStatus("error");
			$this->setMsj(getMsjConf('307'));
			return $usuario;
		}
		if($pass != $pass2){
			$this->setStatus("error");
			$this->setMsj(getMsjConf('308'));
			return $usuario;
		}
		
		$this->getUsuarioDao()->alta($usuario);

		if ($this->getUsuarioDao()->getStatus() == "error"){
			$this->setStatus("error");
			$this->setMsj($this->getUsuarioDao()->getMsj());
		}else{
			$usuario->setId($this->getUsuarioDao()->getMsj());
			$this->setStatus("ok");
		}
		return $usuario;
	}

	public function getTokenMP(){

		
		$carritoDao = new CarritoDao();
		$data["id_carrito"] = $carritoDao->getIdCarrito3();

		if (!is_numeric($data["id_carrito"])){
			$this->setStatus("ERROR");
			$this->setMsj("El id de carrito es incorrecto.");
			return 0;
		}

		if ($data["id_carrito"] <= 0){
			$this->setStatus("ERROR");
			$this->setMsj("No se encontro el carrito.");
			return 0;
 		}

		 $idCarrito = isset($_POST["id_carrito"]) ? $_POST["id_carrito"] : '';

		if ($data["id_carrito"] != $idCarrito){
			$this->setStatus("ERROR");
			$this->setMsj("El id ". $idCarrito ." de carrito  es incorrecto.");
			return 0;
		}
		
		return 	$this->getUsuarioDao()->getTokenMP($idCarrito);

	}
	public function desvincularTokenMP(){
		//validar
		$idUserMP = $GLOBALS['sesionG']['idUsuario'];

			$this->getUsuarioDao()->desvincularTokenMP($idUserMP);
		$this->setMsj($this->getUsuarioDao()->getMsj());
		$this->setStatus($this->getUsuarioDao()->getStatus());
	}

	public function actualizarTokenMP(){
		//validar
		$idUserMP = $GLOBALS['sesionG']['idUsuario'];
		$stateMP  = $_GET['state'];

		if ($idUserMP == $stateMP){
			$codeMP      = $_GET['code'];
			$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,  $GLOBALS['configuration_mp']['url_token']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
$post = array(
	'client_secret' => $GLOBALS['configuration_mp']['client_secret'],
    'grant_type' => "authorization_code",
    'code' => "$codeMP",
    'redirect_uri' => $GLOBALS['configuration_mp']['redirect_uri']
);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

$headers = array();
$headers[] =  $GLOBALS['configuration_mp']['cookie'];
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);

//var_dump($result);exit;
$obj = json_decode($result, false);
$token = $obj->access_token;
$ret = json_encode($result);
//Database::Connect()->close();
//echo $ret;
//exit;

			$this->getUsuarioDao()->actualizarTokenMP($ret,$token);
		}

	}


	public function crearByCliente($usuario_var,$pass,$perfil,$idUsuarioCliente){
		$usuario = new Usuario();
		$usuario->setUsuario(_trim($usuario_var));
		$usuario->setPass($pass);
		$usuario->setPass2($pass);
		$usuario->setPerfil($perfil);
		
		//validar
		$this->validarPerfil($usuario->getPerfil());
		if ($this->getStatus() != 'ok'){
			return $usuario;
		}
		if ($usuario->getPerfil() != "beneficiario"){
			$this->setStatus("error");
			$this->setMsj(getMsjConf('309'));
			return $usuario;
		}

		$this->validarId($idUsuarioCliente);
		if ($this->getStatus() != 'ok'){
			//Reescribo la respuesta de error
			$this->setMsj(getMsjConf('310'));
			return $usuario;
		}
		
		$this->validarUsuario($usuario->getUsuario());
		if ($this->getStatus() != 'ok'){
			return $usuario;
		}
		
		if ($this->getUsuarioDao()->existeUsuario($usuario->getUsuario())){
			$this->setStatus("error");
			$this->setMsj(getMsjConf('306'));
			return $usuario;
		}
		
		$this->validarPass($usuario->getPass());
		if ($this->getStatus() != 'ok'){
			return $usuario;
		}

		if ($pass == ''){
			$this->setStatus("error");
			$this->setMsj(getMsjConf('307'));
			return $usuario;
		}

		$this->getUsuarioDao()->altaByCliente($usuario,$idUsuarioCliente);

		if ($this->getUsuarioDao()->getStatus() == "error"){
			$this->setStatus("error");
			$this->setMsj($this->getUsuarioDao()->getMsj());
		}else{
			$usuario->setId($this->getUsuarioDao()->getMsj());
			$this->setStatus("ok");
		}
		return $usuario;
	}


	public function actualizar($id,$usuario_var){
		$usuario = new Usuario();
		$usuario->setId($id);
		$usuario->setUsuario(_trim($usuario_var));

		//validar
		$this->validarUsuario($usuario->getUsuario());
		if ($this->getStatus() != 'ok'){
			return $usuario;
		}
		
		$this->validarId($usuario->getId());
		if ($this->getStatus() != 'ok'){
			return $usuario;
		}

		if ($this->getUsuarioDao()->existeUsuarioUpdate($usuario->getUsuario(),$usuario->getId())){
			$this->setStatus("error");
			$this->setMsj(getMsjConf('313'));
			return $usuario;
		}

		$this->getUsuarioDao()->editar($usuario);

		if ($this->getUsuarioDao()->getStatus() != "ok"){
			$this->setStatus("error");
			$this->setMsj($this->getUsuarioDao()->getMsj()); 
		}else{
			$this->setStatus("ok");
			$this->setMsj(""); 
		}
		return $usuario;
	}

	public function actualizarPass(){
		$pass  = isset($_POST["cambiar_pass_new"]) ? $_POST["cambiar_pass_new"] : '';
		$pass2  = isset($_POST["cambiar_pass_new2"]) ? $_POST["cambiar_pass_new2"] : '';
		$passOld  = isset($_POST["cambiar_pass_ant"]) ? $_POST["cambiar_pass_ant"] : '';
		
		$this->validarPass($pass);
		if ($this->getStatus() != 'ok'){
			return false;
		}
		if ($pass == ''){
			$this->setStatus("error");
			$this->setMsj(getMsjConf('307'));
			return false;
		}
		if($pass != $pass2){
			$this->setStatus("error");
			$this->setMsj(getMsjConf('308'));
			return false;
		}

		$perfil    = $GLOBALS['sesionG']['perfil'];
		$idUsuario = "";
		if ($perfil == "seller"  || $perfil == "picker"){
			$idUsuario = $GLOBALS['sesionG']['idUsuario'];
		}else{
			$perfil     = isset($_POST["perfil"]) ? $_POST["perfil"] : '';
			$idUsuario  = isset($_POST["idUsuario"]) ? $_POST["idUsuario"] : '';
		}

		$this->validarPerfil($perfil);
		if ($this->getStatus() != 'ok'){
			return false;
		}

		$this->validarId($idUsuario);
		if ($this->getStatus() != 'ok'){
			return false;
		}


		$this->validarPassOld($idUsuario,$perfil,$passOld);
		if ($this->getStatus() != 'ok'){
			return false;
		}



		$this->getUsuarioDao()->actualizarPass($idUsuario,$perfil,$pass);

		if ($this->getUsuarioDao()->getStatus() != "ok"){
			$this->setStatus("error");
			$this->setMsj($this->getUsuarioDao()->getMsj()); 
		}else{
			$this->setStatus("ok");
			$this->setMsj(""); 
			return true;
		}
		return false;
	}

	private function validarPassOld ($idUsuario,$perfil,$pass){
		$this->setStatus("error");
		$this->setMsj("La contraseña anterior es incorrecta.");

		if ($this->getUsuarioDao()->validPassOld($idUsuario,$perfil,$pass)){
			$this->setStatus("ok");
			$this->setMsj("");
		}
	}
	
	public function actualizarImagenPerfil(){
		$perfil    = $GLOBALS['sesionG']['perfil'];
		$idUsuario = "";
		$id = "";

		if ($perfil == "picker" || $perfil == "seller"){
			$idUsuario = $GLOBALS['sesionG']['idUsuario'];
			$id        = $GLOBALS['sesionG']['id'];
		}else{
			$id         = isset($_POST["id"]) ? $_POST["id"] : '';
		}

		$this->validarId($id);
		if ($this->getStatus() != 'ok'){
			return false;
		}
		$foto         = isset($_POST["file"]) ? $_POST["file"] : '';
		if ($foto == ''){
			$this->setStatus("error");
			$this->setMsj("No se pudo actualizar la foto.");
			return false;
		}


		$base_to_php = explode(',', $foto);
		if (count($base_to_php) == 2){
			$data = base64_decode($base_to_php[1]);
			$filepath = "/var/www/html/imagen_perfil/$idUsuario.png";
			file_put_contents($filepath,$data);
		}


	}


	public function actualizarPerfil(){
		$perfil    = $GLOBALS['sesionG']['perfil'];
		if ($perfil != 'seller' && $perfil != 'picker'){
			$this->setStatus("error");
			$this->setMsj("Perfil incorrecto."); 
			return false;
		}

		$id        = $GLOBALS['sesionG']['idUsuario'];
		$nombre   = isset($_POST["nombre-editar"]) ? $_POST["nombre-editar"]      : '';
		$apellido = isset($_POST["apellido-editar"]) ? $_POST["apellido-editar"]  : '';
		$usuario  = isset($_POST["nombre-usuario"]) ? $_POST["nombre-usuario"]    : '';
		$email    = isset($_POST["contacto-mail"]) ? $_POST["contacto-mail"]      : '';

		$nombre   = _trim($nombre);
		$apellido = _trim($apellido);
		$usuario  = _trim($usuario);
		$email    = _trim($email);


		if ($GLOBALS['sesionG']['nombre'] == $nombre && $GLOBALS['sesionG']['apellido'] == $apellido && $GLOBALS['sesionG']['email'] == $email && $GLOBALS['sesionG']['usuario'] == $usuario){
			$this->setStatus("error");
			$this->setMsj("La solicitud se proceso con éxito."); 
			return false;
			
		}

		$this->validarUsuario($usuario);
		if ($this->getStatus() != 'ok'){
			return $usuario;
		}

		$this->validarId($id);
		if ($this->getStatus() != 'ok'){
			return false;
		}

		$this->validarNombre($nombre);
		if ($this->getStatus() != 'ok'){
			return false;
		}
		
		$this->validarApellido($apellido);
		if ($this->getStatus() != 'ok'){
			return false;
		}
		
		$this->validarEmail($email);
		if ($this->getStatus() != 'ok'){
			return false;
		}


		if ($GLOBALS['sesionG']['email'] != $email){
			$list = $this->getUsuarioDao()->existeEmail($email);
			$result = count($list);
			if ($result>0){
				$this->setStatus("error");
				$this->setMsj("El email no esta disponible.");
				return false;
			}
		}

		include_once($GLOBALS['configuration']['path_app_admin_objects']."sesion/sesionDaoImpl.php");
		$sesionDao = new SesionDaoImpl();
		$mensajeReturn = "";
		if ($GLOBALS['sesionG']['usuario'] != $usuario){
			if ($this->getUsuarioDao()->existeUsuario($usuario)){
				$this->setStatus("error");
				$this->setMsj("El usuario no esta disponible.");
				return false;
			}

			$this->getUsuarioDao()->actualizarPerfilUsuario($id,$usuario,$perfil);
			$mensajeReturn = $this->getUsuarioDao()->getMsj();

			if ($this->getUsuarioDao()->getStatus() != "ok"){
				$this->setStatus("error");
				$this->setMsj($mensajeReturn);
				return false;
			}else{
				$sesionDao->guardarUsuario($usuario);
				if ($GLOBALS['sesionG']['nombre'] == $nombre && $GLOBALS['sesionG']['apellido'] == $apellido && $GLOBALS['sesionG']['email'] == $email){
					$this->setStatus("ok");
					$this->setMsj($mensajeReturn);
					return true;
				}
			}
		}


		$this->getUsuarioDao()->actualizarPerfilDatosPersonales($id,$nombre,$apellido,$usuario,$email,$perfil);

		if ($this->getUsuarioDao()->getStatus() != "ok"){
			if ($mensajeReturn != ""){
				$mensajeReturn .= " ";
			}
			$mensajeReturn .= $this->getUsuarioDao()->getMsj();
			$this->setStatus("error");
			$this->setMsj($mensajeReturn);
			return false;
		}else{
			$sesionDao->guardar($nombre,$apellido,$email);
			$this->setStatus("ok");
			$this->setMsj($this->getUsuarioDao()->getMsj());
			return true;
		}
	}

private function validarNombre ($param){
		$this->setStatus("error");
		$this->setMsj("");
		$validSql = validSqlInjection($param);
		if ($validSql != ''){
			$this->setMsj(getMsjConf('124')." $validSql.");
		}else{
			$param = _trim($param);
			if (strlen($param)<1){
				$this->setMsj(getMsjConf('108_1'));
			}else if(strlen($param)>30){
				$this->setMsj(getMsjConf('125'));
			}else{
				$patron = '/^.+$/i';
				if (preg_match($patron, $param)){
					$this->setStatus("ok");
				}else{
					$this->setMsj(getMsjConf('126'));
				}
			}
		}
	}
	
	private function validarApellido ($param){
		$this->setStatus("error");
		$this->setMsj("");
		$validSql = validSqlInjection($param);
		if ($validSql != ''){
			$this->setMsj(getMsjConf('127')." $validSql.");
		}else{
			$param = _trim($param);
			if (strlen($param)<1){
				$this->setMsj(getMsjConf('128'));
			}else if(strlen($param)>30){
				$this->setMsj(getMsjConf('129'));
			}else{
				$patron = '/^.+$/i';
				if (preg_match($patron, $param)){
					$this->setStatus("ok");
				}else{
					$this->setMsj(getMsjConf('130'));
				}
			}
		}
	}

	public function eliminar($id){
		$perfil = $GLOBALS['sesionG']['perfil'];
		
		if ($perfil != 'admin' && $perfil != 'superadmin' && $perfil != 'seller' && $perfil != 'picker'){
			$this->setStatus("error");
			$this->setMsj(getMsjConf('316'));
			return false;
		}
		$this->validarId($id);
		if ($this->getStatus() != 'ok'){
			return false;
		}
		$this->getUsuarioDao()->eliminar($id);

		if ($this->getUsuarioDao()->getStatus() != "ok"){
			$this->setStatus("error");
			$this->setMsj($this->getUsuarioDao()->getMsj());
			return false;
		}else{
			$this->setStatus("ok");
			$this->setMsj(""); 
			return true;
		}
	}

	public function eliminarBeneficiarioByCliente($id){
		$perfil = $GLOBALS['sesionG']['perfil'];

		if ($perfil != 'picker'){
			$this->setStatus("error");
			$this->setMsj(getMsjConf('316'));
			return false;
		}
		$this->validarId($id);
		if ($this->getStatus() != 'ok'){
			return false;
		}

		if ($this->getIdUsuarioBeneficiarioByCliente($id)){
			$this->getUsuarioDao()->eliminarBeneficiarioByCliente($this->getMsj());
			if ($this->getUsuarioDao()->getStatus() != "ok"){
				$this->setStatus("error");
				$this->setMsj($this->getUsuarioDao()->getMsj());
				return false;
			}else{
				$this->setStatus("ok");
				$this->setMsj(""); 
				return true;
			}
		}else{
			return false;
		}

	}

	public function getIdUsuarioBeneficiarioByCliente($id){
		$perfil = $GLOBALS['sesionG']['perfil'];

		if ($perfil != 'picker'){
			$this->setStatus("error");
			$this->setMsj(getMsjConf('318'));
			return false;
		}
		$this->validarId($id);
		if ($this->getStatus() != 'ok'){
			return false;
		}
		
		$list = $this->getUsuarioDao()->getIdUsuarioBeneficiarioByCliente($id);
		if ($list[0]['idUsuario'] > 0 & $list[0]['idOwner'] == $GLOBALS['sesionG']['idUsuario']){
			$this->setStatus("ok");
			$this->setMsj($list[0]['idUsuario']);
			return true;
		}
		$this->setStatus("error");
		$this->setMsj(getMsjConf('319'));
		return false;
	}
	
	public function get($id){
		$this->validarId($id);
		if ($this->getStatus() != 'ok'){
			$usuarioObj = new Usuario();
			return $usuarioObj;
		}
		return $this->getUsuarioDao()->get($id);
	}

	public function getUsuarioByEmail($email){
		$this->validarEmail($email);
		if ($this->getStatus() != 'ok'){
			return  false;
		}
		return $this->getUsuarioDao()->getUsuarioByEmail($email);
	}

	public function getByUsr(){
		$email  = isset($_POST["mail"]) ? $_POST["mail"] : '';
		$usuario  = $this->getUsuarioByEmail($email);
		$this->validarUsuario($usuario);
		if ($this->getStatus() != 'ok'){
			$usuarioObj = new Usuario();
			return $usuarioObj;
		}
		return $this->getUsuarioDao()->getByUsr($usuario);
	}


	public function getUsuarioBySesion(){
		return $this->getUsuarioDao()->getUsuarioBySesion();
	}

	
	public function getUsuarioPublic(){
		$id_usuario  = isset($_GET["id_usuario"]) ? $_GET["id_usuario"] : '';
		
		return $this->getUsuarioDao()->getUsuarioPublic($id_usuario)[0];
	}
	
	
	public function listar(){
		$usuarioList = $this->getUsuarioDao()->getList();
		$filas = "";	
		foreach ($usuarioList as $clave=>$usuario){
			$contenido = new Template("usuario_listado_filas");
			$contenido->asigna_variables(array(
				"path"      => $GLOBALS['configuration']['path_html'],
				"id"        => $usuario->getId(),
				"usuario"   => $usuario->getUsuario(),
				"perfil"    => $usuario->getPerfil(),
				"fechaAlta" => $usuario->getFechaAlta(),
				"fechaUpdate" => $usuario->getFechaUpdate()
			));
			$contenidoString = $contenido->muestra();
			$filas .= $contenidoString;
		}
		$this->setPaginador("");
		return $filas;
	}
	
	private function validarUsuario ($param){
		$this->setStatus("error");
		$this->setMsj("");
		$validSql = validSqlInjection($param);
		if ($validSql != ''){
			$this->setMsj(getMsjConf('320')." $validSql.");
		}else{
			$param = _trim($param);
			if (strlen($param)<3){
				$this->setMsj(getMsjConf('321'));
			}else if(strlen($param)>64){
				$this->setMsj(getMsjConf('322'));
			}else{
				$patron = '/^([a-z0-9@\._-]+)$/i';
				if (preg_match($patron, $param)){
					$this->setStatus("ok");
				}else{
					$this->setMsj(getMsjConf('323'));
				}
			}
		}
	}
	
	private function validarPerfil ($param){
		$this->setStatus("error");
		$this->setMsj("");
		$validSql = validSqlInjection($param);
		if ($validSql != ''){
			$this->setMsj(getMsjConf('324')." $validSql.");
		}else{
			$patron = '/^(picker|seller)$/';
			if (preg_match($patron, $param)){
				$this->setStatus("ok");
			}else{
				$this->setMsj(getMsjConf('325'));
			}
		}
	}
	
	private function validarId ($param){
		$this->setStatus("error");
		$this->setMsj("");
		$validSql = validSqlInjection($param);
		if ($validSql != ''){
			$this->setMsj(getMsjConf('326')." $validSql.");
		}else{
			$patron = '/^[1-9][0-9]*$/';
			if (preg_match($patron, $param)){
				$this->setStatus("ok");
			}else{
				$this->setMsj(getMsjConf('327'));
			}
		}
	}
	
	private function validarPass ($param){
		$this->setStatus("error");
		$this->setMsj("");
		$validSql = validSqlInjection($param);
		if ($validSql != ''){
			$this->setMsj(getMsjConf('328')." $validSql.");
		}else{
			if (strlen($param)<6){
				$this->setMsj(getMsjConf('329'));
			}else if(strlen($param)>12){
				$this->setMsj(getMsjConf('330'));
			}else{
				$patron = '/^.+$/i';
				if (preg_match($patron, $param)){
					$this->setStatus("ok");
				}else{
					$this->setMsj(getMsjConf('331'));
				}
			}
		}
	}

	public function recuperar(){
		$email = isset($_POST["email"]) ? $_POST["email"] : '';
		$email = _trim($email);
		
		$this->validarEmail($email);
		if ($this->getStatus() != 'ok'){
			return Array();
		}
		
		$list = $this->getUsuarioDao()->existeEmail($email);
		$result = count($list);
		if ($result>0){
			if ($this->getUsuarioDao()->getStatus() == "error"){
				$this->setStatus("error");
				$this->setMsj($this->getUsuarioDao()->getMsj());
				return Array();
			}else{
				$id        = $list[0];
				$usuario   = $list[1];
				$idUsuario = $list[2];
				$para      = $list[3];
				$perfil    = $list[4];
				$hash  =  md5($GLOBALS['configuration']['clave_recuperar'].$id.$usuario.$perfil.$id);

				$this->getUsuarioDao()->updateRecuperarCliente($id,$usuario,$hash);
				if ($this->getUsuarioDao()->getStatus() == "error"){
					$this->setStatus("error");
					$this->setMsj($this->getUsuarioDao()->getMsj());
					return Array();
				}else{
					$hostActualEmail = $GLOBALS['configuration']['hostActualEmail'];
					$link = "$hostActualEmail/app/public_recuperar_pass.php?action=recuperar&usuario=$usuario&hash=$hash&idUsuario=$idUsuario&id=$id&perfil=$perfil";
					$mensaje  = "Usuario: $usuario\n";
					$mensaje .= "Haga click para recuperar la clave.\n";
					$mensaje .= "$link\n";

					include_once($GLOBALS['configuration']['path_app_admin_objects']."util/email.php");
					$objEmail = new Email();
					$objEmail->setEnviar(true);
					#$objEmail->enviarEmailRecuperar($mensaje,$para);

					$contenido = new Template("news_recuperacion_clave");

					$contenido->asigna_variables(array(
						"usuario" => $usuario,
						"link"    => $link,
						"fecha"   => date('d/m/Y', time()),
					   "hostActual" => $hostActualEmail
					));
					$contenidoString = $contenido->muestra();

					$objEmail->enviarEmailRecuperarHtml($mensaje,$contenidoString,$para,$usuario);
					if ($objEmail->getStatus() == "error"){
						$this->setStatus("error");
						$this->setMsj($objEmail->getMsj());
						return Array();
					}else{
						$this->setStatus("ok");
						$this->setMsj("");
						return $list;
					}
				}
			}
		}else{
			$this->setStatus("error");
			$this->setMsj(getMsjConf('332'));
			return Array();
		}
	}

	public function cambiarPassByCliente(){
		$hash    = (isset($_POST['hash']))  ? $_POST['hash'] : ""; 
		$hash = _trim($hash);
		$usuario = (isset($_POST['usuario']))  ? $_POST['usuario'] : ""; 
		$usuario = _trim($usuario);
		$idUsuario = (isset($_POST['idUsuario']))  ? $_POST['idUsuario'] : ""; 
		$idUsuario = _trim($idUsuario);
		$id = (isset($_POST['id']))  ? $_POST['id'] : ""; 
		$id = _trim($id);
		$password = (isset($_POST['password']))  ? $_POST['password'] : ""; 
		$password = _trim($password);
		$password2 = (isset($_POST['password2']))  ? $_POST['password2'] : ""; 
		$password2 = _trim($password2);

		$perfil = (isset($_POST['perfil']))  ? $_POST['perfil'] : ""; 
		$perfil = _trim($perfil);
		
		
		//validar
		$this->validarPerfil($perfil);
		if ($this->getStatus() != 'ok'){
			return false;
		}

		$this->validarPass($password);
		if ($this->getStatus() != 'ok'){
			return false;
		}

		if ($password == ''){
			$this->setStatus("error");
			$this->setMsj(getMsjConf('307'));
			return false;
		}
		if($password != $password2){
			$this->setStatus("error");
			$this->setMsj(getMsjConf('308'));
			return false;
		}
		
		$this->validarId($id);
		if ($this->getStatus() != 'ok'){
			return false;
		}
		
		$this->validarUsuario($usuario);
		if ($this->getStatus() != 'ok'){
			return false;
		}
		//Valido por las dudas el idUsuario con el metodo validarId
		$this->validarId($idUsuario);
		if ($this->getStatus() != 'ok'){
			return false;
		}
		
		$this->validarHash($hash);
		if ($this->getStatus() != 'ok'){
			return false;
		}
	
		$hashValid  =  md5($GLOBALS['configuration']['clave_recuperar'].$id.$usuario.$perfil.$id);		
		if($hash != $hashValid){
			$this->setStatus("error");
			$this->setMsj(getMsjConf('335'));
			return false;
		}
		
		$this->getUsuarioDao()->updatePasswordByCliente($id,$usuario,$hash,$password,$perfil);
		if ($this->getUsuarioDao()->getStatus() == "error"){
			$this->setStatus("error");
			$this->setMsj($this->getUsuarioDao()->getMsj());
			return false;
		}else{
			$this->setStatus("ok");
			$this->setMsj("");
			return true;
		}
	}
	
	private function validarEmail ($param){
		$this->setStatus("error");
		$this->setMsj("");
		$validSql = validSqlInjection($param);
		if ($validSql != ''){
			$this->setMsj(getMsjConf('336')." $validSql.");
		}else{
			$param = _trim($param);
			if (strlen($param)<6){
				$this->setMsj(getMsjConf('337'));
			}else if(strlen($param)>64){
				$this->setMsj(getMsjConf('338'));
			}else{
				$patron = '/^[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/';
				if (preg_match($patron, $param)){
					$this->setStatus("ok");
				}else{
					$this->setMsj(getMsjConf('339'));
				}
			}
		}
	}
	
	private function validarHash ($param){
		$this->setStatus("error");
		$this->setMsj("");
		$validSql = validSqlInjection($param);
		if ($validSql != ''){
			$this->setMsj(getMsjConf('340')." $hash.");
		}else{
			$param = _trim($param);
			if (strlen($param)!=32){
				$this->setMsj(getMsjConf('341'));
			}else{
				$this->setStatus("ok");
			}
		}
	}
	
	public function clienteIsOwnerBeneficiario ($param1,$param2){
		$this->validarId($param1);
		if ($this->getStatus() == 'ok'){
			$this->validarId($param2);
			if ($this->getStatus() == 'ok'){
				if ($this->getUsuarioDao()->clienteIsOwnerBeneficiario($param1,$param2)){
					return true;
				}else{
					if ($this->getUsuarioDao()->getStatus() == "error"){
						$this->setStatus("error");
						$this->setMsj($this->getRetailerDao()->getMsj());
					}else{
						$this->setStatus("ok");
						$this->setMsj("");
					}
					return false;
				}
			}else{
				$this->setStatus("ok");
				$this->setMsj("");
				return false;
			}
		}else{
			$this->setStatus("ok");
			$this->setMsj("");
			return false;
		}
	}

	public function isAdmin(){
		return $this->getUsuarioDao()->isAdmin();
	}
}
?>

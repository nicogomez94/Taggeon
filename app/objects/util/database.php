<?php

class Database{
   // Contenedor Instancia de la Clase
	private static $database;
 
   private function __construct(){
		$databaseName = $GLOBALS['configuration']['db'];
		$serverName = $GLOBALS['configuration']['host'];
		$databaseUser = $GLOBALS['configuration']['user'];
		$databasePassword = $GLOBALS['configuration']['pass'];
		$databasePort = $GLOBALS['configuration']['port'];
		if (!isset($this->connection))
		{
			$conexion = new mysqli($serverName, $databaseUser, $databasePassword,$databaseName);
                        if ($conexion->connect_errno) {
                                echo "Error al conectarse con la base de datos";
                                exit;
                        }else{
                                $this->connection = $conexion;
                        }
		}
   }
	
	public static function Connect()	{
		if (!isset(self::$database)){
         $c = __CLASS__;
         self::$database = new $c;
		}
		return self::$database->connection;
	}
	
	public static function escape ($var){
		$ret = '';
		if (is_array($var)){
			foreach ($var as $c){
				if ($ret != ''){
					$ret .= ";";
				}
				$ret .= $c;
			}
		}else{
			$ret = $var;
		}
		$ret = mysqli_real_escape_string(Database::Connect(),$ret); 
		return "'$ret'";
	}

	public function desconectar (){
		Database::Connect()->close();
	}
}
?>

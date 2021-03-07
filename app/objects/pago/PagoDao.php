<?php
//include_once("../util/database.php");
class  PagoDao
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


	public function altaPago(array $data)
	{

        $email = isset($data["email"]) ? $data["email"] : '';
        $emailDB = Database::escape($email);
        $docType = isset($data["docType"]) ? $data["docType"] : '';
        $docTypeDB = Database::escape($docType);
        $docNumber = isset($data["docNumber"]) ? $data["docNumber"] : '';
        $docNumberDB = Database::escape($docNumber);
        $cardholderName = isset($data["cardholderName"]) ? $data["cardholderName"] : '';
        $cardholderNameDB = Database::escape($cardholderName);
        $cardExpirationMonth = isset($data["cardExpirationMonth"]) ? $data["cardExpirationMonth"] : '';
        $cardExpirationMonthDB = Database::escape($cardExpirationMonth);
        $cardExpirationYear = isset($data["cardExpirationYear"]) ? $data["cardExpirationYear"] : '';
        $cardExpirationYearDB = Database::escape($cardExpirationYear);
        $cardNumber = isset($data["cardNumber"]) ? $data["cardNumber"] : '';
        $cardNumberDB = Database::escape($cardNumber);
        $securityCode = isset($data["securityCode"]) ? $data["securityCode"] : '';
        $securityCodeDB = Database::escape($securityCode);
        $banco = isset($data["banco"]) ? $data["banco"] : '';
        $bancoDB = Database::escape($banco);
        $tarjeta = isset($data["tarjeta"]) ? $data["tarjeta"] : '';
        $tarjetaDB = Database::escape($tarjeta);
        $cuotas = isset($data["cuotas"]) ? $data["cuotas"] : '';
        $cuotasDB = Database::escape($cuotas);
        $cod_segurida_varchar = isset($data["cod_segurida_varchar"]) ? $data["cod_segurida_varchar"] : '';
        $cod_segurida_varcharDB = Database::escape($cod_segurida_varchar);
		$usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        
		$sql = <<<SQL
			INSERT INTO pago (email, docType, docNumber, cardholderName, cardExpirationMonth, cardExpirationYear, cardNumber, securityCode, banco, tarjeta, cuotas, cod_segurida_varchar,usuario_alta)  
			VALUES ($emailDB, $docTypeDB, $docNumberDB, $cardholderNameDB, $cardExpirationMonthDB, $cardExpirationYearDB, $cardNumberDB, $securityCodeDB, $bancoDB, $tarjetaDB, $cuotasDB, $cod_segurida_varcharDB,$usuarioAltaDB)
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


	public function editarPago(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		$idDB = Database::escape($id);
		$usuario = $GLOBALS['sesionG']['idUsuario'];
        $usuarioDB = Database::escape($usuario);

        $email = isset($data["email"]) ? $data["email"] : '';
        $emailDB = Database::escape($email);
        $docType = isset($data["docType"]) ? $data["docType"] : '';
        $docTypeDB = Database::escape($docType);
        $docNumber = isset($data["docNumber"]) ? $data["docNumber"] : '';
        $docNumberDB = Database::escape($docNumber);
        $cardholderName = isset($data["cardholderName"]) ? $data["cardholderName"] : '';
        $cardholderNameDB = Database::escape($cardholderName);
        $cardExpirationMonth = isset($data["cardExpirationMonth"]) ? $data["cardExpirationMonth"] : '';
        $cardExpirationMonthDB = Database::escape($cardExpirationMonth);
        $cardExpirationYear = isset($data["cardExpirationYear"]) ? $data["cardExpirationYear"] : '';
        $cardExpirationYearDB = Database::escape($cardExpirationYear);
        $cardNumber = isset($data["cardNumber"]) ? $data["cardNumber"] : '';
        $cardNumberDB = Database::escape($cardNumber);
        $securityCode = isset($data["securityCode"]) ? $data["securityCode"] : '';
        $securityCodeDB = Database::escape($securityCode);
        $banco = isset($data["banco"]) ? $data["banco"] : '';
        $bancoDB = Database::escape($banco);
        $tarjeta = isset($data["tarjeta"]) ? $data["tarjeta"] : '';
        $tarjetaDB = Database::escape($tarjeta);
        $cuotas = isset($data["cuotas"]) ? $data["cuotas"] : '';
        $cuotasDB = Database::escape($cuotas);
        $cod_segurida_varchar = isset($data["cod_segurida_varchar"]) ? $data["cod_segurida_varchar"] : '';
        $cod_segurida_varcharDB = Database::escape($cod_segurida_varchar);

        $sql = <<<SQL
			UPDATE
			    `pago`
			SET
			    `usuario_editar` = $usuarioDB,
`email` = $emailDB, `docType` = $docTypeDB, `docNumber` = $docNumberDB, `cardholderName` = $cardholderNameDB, `cardExpirationMonth` = $cardExpirationMonthDB, `cardExpirationYear` = $cardExpirationYearDB, `cardNumber` = $cardNumberDB, `securityCode` = $securityCodeDB, `banco` = $bancoDB, `tarjeta` = $tarjetaDB, `cuotas` = $cuotasDB, `cod_segurida_varchar` = $cod_segurida_varcharDB
			    WHERE
					`id` = $idDB AND
					`usuario_alta` = $usuarioDB
SQL;

        if (!mysqli_query(Database::Connect(), $sql)) {
            $this->setStatus("ERROR");
            $this->setMsj("$sql" . Database::Connect()->error);
        } else {
            $this->setStatus("OK");
            return true;
        }

        return false;


	}

	public function eliminarPago(array $data)
    {
        $id = isset($data["id"]) ? $data["id"] : '';
        $idDB = Database::escape($id);
        $usuario = $GLOBALS['sesionG']['idUsuario'];
        $usuarioDB = Database::escape($usuario);

        $sql = <<<SQL
UPDATE
    `pago`
SET
    `usuario_editar` = $usuarioDB,
    `eliminar` = 1
WHERE
`id` = $idDB AND
`usuario_alta` = $usuarioDB
SQL;

        if (!mysqli_query(Database::Connect(), $sql)) {
            $this->setStatus("ERROR");
            $this->setMsj("$sql" . Database::Connect()->error);
        } else {
            $this->setStatus("OK");
            return true;
        }

        return false;
    }

	public function existeId($id)
    {
        $id = isset($id) ?   $id : '';
        $idDB = Database::escape($id);
        $usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        
        $sql = <<<SQL
                        SELECT * FROM pago
                        WHERE 
                            id = $idDB AND
                            (eliminar = 0 OR eliminar is null) AND
                            usuario_alta = $usuarioAltaDB
SQL;

        $resultado = mysqli_query(Database::Connect(), $sql);
        $row_cnt = mysqli_num_rows($resultado);
        if ($row_cnt == 1) {
            $this->setStatus("OK");
            return true;
        }

        $this->setStatus("ERROR");
        $this->setMsj("No se puede editar. Motivo: No existe o no tiene permisos.");
        return false;
	}
	
	public function getListPago()
    {
        $usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $sql = <<<sql
        SELECT
		*
    	FROM
		`pago`
		WHERE
        (`pago`.eliminar = 0 OR `pago`.eliminar IS NULL) AND `pago`.usuario_alta = $usuarioAltaDB
sql;
        $resultado = Database::Connect()->query($sql);
        $list = array();

        while ($rowEmp = mysqli_fetch_array($resultado)) {
            $list[] = $rowEmp;
        }
        return $list;
	}

	public function getPago($id)
    {
        $usuarioAlta = $GLOBALS['sesionG']['idUsuario'];
        $usuarioAltaDB = Database::escape($usuarioAlta);
        $id = isset($id) ?   $id : '';
        $idDB = Database::escape($id);
        $sql = <<<sql
		SELECT
		*
    	FROM
        `pago`
    	WHERE
			pago.id=$idDB AND 
        (`pago`.eliminar = 0 OR `pago`.eliminar IS NULL) AND `pago`.usuario_alta = $usuarioAltaDB
sql;
        $resultado = Database::Connect()->query($sql);
        $row_cnt = mysqli_num_rows($resultado);
        $list = array();
        if ($row_cnt <= 0) {
            $this->setStatus("ERROR");
            $this->setMsj("No se encontraron resultados o no tiene permisos para editar.");
            return $list;
        }


        while ($rowEmp = mysqli_fetch_array($resultado)) {
            $list[] = $rowEmp;
        }
        $this->setStatus("ok");
        return $list;
    }





}

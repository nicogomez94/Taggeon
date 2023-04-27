<?php
include_once("PagoDao.php");
class  PagoManager
{
	private $pagoDao;
	private $status    = "";
	private $msj       = "";

	public function __construct()
	{
		$this->pagoDao = new PagoDao();
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

	private function validarPago(array $data) {
		$var_accion = isset($data["accion"]) ? $data["accion"] : '';
		if ($var_accion == 'editar') {
			$id = isset($data["id"]) ? $data["id"] : '';
			if ($this->existeId($id) === false) {
				return false;
			}
		}

	    $email = isset($data["email"]) ? $data["email"] : '';
	    if ($this->validarEmail($email) === false){
	     return false;
	    }
	    $docType = isset($data["docType"]) ? $data["docType"] : '';
	    if ($this->validarDocType($docType) === false){
	     return false;
	    }
	    $docNumber = isset($data["docNumber"]) ? $data["docNumber"] : '';
	    if ($this->validarDocNumber($docNumber) === false){
	     return false;
	    }
	    $cardholderName = isset($data["cardholderName"]) ? $data["cardholderName"] : '';
	    if ($this->validarCardholderName($cardholderName) === false){
	     return false;
	    }
	    $cardExpirationMonth = isset($data["cardExpirationMonth"]) ? $data["cardExpirationMonth"] : '';
	    if ($this->validarCardExpirationMonth($cardExpirationMonth) === false){
	     return false;
	    }
	    $cardExpirationYear = isset($data["cardExpirationYear"]) ? $data["cardExpirationYear"] : '';
	    if ($this->validarCardExpirationYear($cardExpirationYear) === false){
	     return false;
	    }
	    $cardNumber = isset($data["cardNumber"]) ? $data["cardNumber"] : '';
	    if ($this->validarCardNumber($cardNumber) === false){
	     return false;
	    }
	    $securityCode = isset($data["securityCode"]) ? $data["securityCode"] : '';
	    if ($this->validarSecurityCode($securityCode) === false){
	     return false;
	    }
	    $banco = isset($data["banco"]) ? $data["banco"] : '';
	    if ($this->validarBanco($banco) === false){
	     return false;
	    }
	    $tarjeta = isset($data["tarjeta"]) ? $data["tarjeta"] : '';
	    if ($this->validarTarjeta($tarjeta) === false){
	     return false;
	    }
	    $cuotas = isset($data["cuotas"]) ? $data["cuotas"] : '';
	    if ($this->validarCuotas($cuotas) === false){
	     return false;
	    }
	    $cod_segurida_varchar = isset($data["cod_segurida_varchar"]) ? $data["cod_segurida_varchar"] : '';
	    if ($this->validarCod_segurida_varchar($cod_segurida_varchar) === false){
	     return false;
	    }


	}



	public function agregarPago(array $data)
	{

		if ($this->validarPago($data) === false) {
			return false;
		}


		if ($this->pagoDao->altaPago($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->pagoDao->getMsj());
		} else {
			$idPago = $this->pagoDao->getMsj();

			
			
			$this->setStatus("OK");
			$this->setMsj($idPago);
		}
	}

	public function modificarPago(array $data)
	{
		if ($this->validarPago($data) === false) {
			return false;
		}


		if ($this->pagoDao->editarPago($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->pagoDao->getMsj());
		} else {

			$this->setStatus("OK");
			$this->setMsj($this->pagoDao->getMsj());
			return true;
		}
	}

	public function eliminarPago(array $data)
	{
		$id = isset($data["id"]) ? $data["id"] : '';
		if ($this->validarId($id) === false){
			return false;
		}


		if ($this->pagoDao->eliminarPago($data) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->pagoDao->getMsj());
		} else {

			$this->setStatus("OK");
			$this->setMsj($this->pagoDao->getMsj());
		}
	}

	private function validarId ($param){
		$this->setStatus("error");
		$this->setMsj("");
		$validSql = validSqlInjection($param);
		if ($validSql != ''){
			$this->setMsj("Error validación: $validSql.");
		}else{
			$patron = '/^[1-9][0-9]*$/';
			if (preg_match($patron, $param)){
				$this->setStatus("ok");
				return true;
			}else{
				$this->setMsj("El campo id es incorrecto.");
			}
		}
		return false;
	}

	private function existeId($id)
	{
		$id = isset($id) ? $id : '';
		if ($this->validarId($id) === false) {
			return false;
		}
		if ($this->productoDao->existeId($id) === false) {
			$this->setStatus("ERROR");
			$this->setMsj($this->productoDao->getMsj());
			return false;
		}
		return true;
	}

	public function getPago(array $data)
	{

		$id = isset($data["id"]) ? $data["id"] : '';
		if ($this->existeId($id) === false) {
			return [];
		}
	
		$pago = $this->pagoDao->getPago($id);
		if ($this->pagoDao->getStatus() != 'ok') {
			$this->setStatus("ERROR");
			$this->setMsj($this->pagoDao->getMsj());
			return [];
		}
		$this->setStatus("ok");
		return $pago;
	}

	public function getListPago()
	{
		$ret =  $this->publicacionDao->getListPago();
		return $ret;
	}



        
            private function validarEmail($email)
            {
                if (! preg_match('/^\w+$/i', $email)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo email es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }        
            private function validarDocType($docType)
            {
                if (! preg_match('/^\w+$/i', $docType)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo docType es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }        
            private function validarDocNumber($docNumber)
            {
                if (! preg_match('/^\w+$/i', $docNumber)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo docNumber es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }        
            private function validarCardholderName($cardholderName)
            {
                if (! preg_match('/^\w+$/i', $cardholderName)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo cardholderName es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }        
            private function validarCardExpirationMonth($cardExpirationMonth)
            {
                if (! preg_match('/^\w+$/i', $cardExpirationMonth)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo cardExpirationMonth es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }        
            private function validarCardExpirationYear($cardExpirationYear)
            {
                if (! preg_match('/^\w+$/i', $cardExpirationYear)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo cardExpirationYear es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }        
            private function validarCardNumber($cardNumber)
            {
                if (! preg_match('/^\w+$/i', $cardNumber)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo cardNumber es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }        
            private function validarSecurityCode($securityCode)
            {
                if (! preg_match('/^\w+$/i', $securityCode)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo securityCode es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }        
            private function validarBanco($banco)
            {
                if (! preg_match('/^\w+$/i', $banco)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo banco es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }        
            private function validarTarjeta($tarjeta)
            {
                if (! preg_match('/^\w+$/i', $tarjeta)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo tarjeta es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }        
            private function validarCuotas($cuotas)
            {
                if (! preg_match('/^\w+$/i', $cuotas)){
                    $this->setStatus("ERROR");
                    $this->setMsj("El campo cuotas es incorrecto.");
                    return false;
                }
                $this->setStatus("OK");
                $this->setMsj("");
                return true;
            }        
            private function validarCod_segurida_varchar($cod_segurida_varchar)
            {
                $this->setStatus("ERROR");
                $this->setMsj("El campo cod_segurida_varchar es incorrecto.");
                return false;
            }
}

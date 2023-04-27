<?php
class Template{
	function Template($template_file){
		$this->tpl_file =  $GLOBALS['configuration']['path_templates'] . $template_file . '.html';
	}
	function asigna_variables($vars){
		$this->vars= (empty($this->vars)) ? $vars : array();
	}
	function muestra(){
		
		if (!($this->fd = @fopen($this->tpl_file, 'r')))
		{
			echo "error al abrir la plantilla " . $this->tpl_file;
		} else{
			$this->template_file = fread($this->fd, filesize($this->tpl_file));
			fclose($this->fd);
			if ( isset($this->vars)){

				reset ($this->vars);
				foreach ($this->vars as $clave=>$valor){
					$this->template_file = preg_replace ("{\{$clave\}}", "$valor", $this->template_file);
					
				}
			}
			return $this->template_file;
		}
	}

	
	function muestraDesdeVariable($templateStr){
		if ( isset($this->vars)){

			reset ($this->vars);
			foreach ($this->vars as $clave=>$valor){
				$templateStr= preg_replace ("{\{$clave\}}", "$valor", $templateStr);
			}
		}
	
		return $templateStr;
	}
	
	function sostenedor_error($error){
		$miplantilla=new Template("error");
		$miplantilla->asigna_variables(array(
			'ERROR' => $error) 
		);
		$contenidoString = $miplantilla->muestra();
		return $contenidoString;
	}
	
   function alta_cliente_ok($error,$htmlOnLoad){
	   $htmlOnLoad = (isset($htmlOnLoad))  ? $htmlOnLoad : "";
		$miplantilla=new Template("error");
		$miplantilla->asigna_variables(array(
			'ERROR' => $error) 
		);
		$return = "";
		$contenidoString = $miplantilla->muestra();
		
		//HEADER
		include_once($GLOBALS['configuration']['path_app_admin_objects']."util/header.php");
		// Agregar snippet para conversion tracking al header
		$contenidoStringHeader.=$GLOBALS['configuration']['adwords_conversion_registration'];
		$return .= $contenidoStringHeader;
		//FIN HEADER

		$return .= $contenidoString;

		//FOOTER
		include_once($GLOBALS['configuration']['path_app_admin_objects']."util/footer.php");
		$return .= $contenidoStringFooter;
		//FIN FOOTER
		return $return;
	}
};



?>

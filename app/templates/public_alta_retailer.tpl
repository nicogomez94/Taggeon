<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Retailer</title>
<link rel="stylesheet" type="text/css" href="{path}css/view.css" media="all">
<script type="text/javascript" src="{path}libreria/view.js"></script>
</head>
<body id="main_body" onload="document.getElementById('captcha').src = '{path}captcha/securimage_show.php?' + Math.random(); return false;">
{bienvenida}
{menu}	
	
	<img id="top" src="{path}imagenes/top.png" alt="">
	<div id="form_container">
	
		<h1><a>Retailer</a></h1>
		<form id="form_465844" class="appnitro"  method="post" action="public_alta_retailer.php">
					<div class="form_description">
					  <h2>Nuevo Retailer</h2>
			<font color="red">{error}</font>
		</div>						
		<ul >
      <li id="li_1" >
          <label class="description" for="element_1">Usuario / User</label>
		<div>
			<input id="element_1" name="usuario" class="element text medium" type="text" maxlength="255" value="{usuario}"/> 
		</div> 
		</li>
        <li id="li_3" >
		<label class="description" for="element_3">Contrase&ntilde;a / Password</label>
		<div>
			<input id="element_3" name="pass" class="element text medium" type="password" maxlength="255" value="{password}"/> 
		</div> 
		</li>
        <li id="li_3" >
		<label class="description" for="element_3">Re-Contras&ntilde;a / Re-Password</label>
		<div>
			<input id="element_3" name="pass2" class="element text medium" type="password" maxlength="255" value="{confirmarPassword}"/> 
		</div> 
		</li>
        
       <li class="section_break">
			<h3></h3>

		</li>
            
      <li id="li_1" >
		<label class="description" for="element_1">Raz&oacute;n Social </label>
		<div>
			<input id="element_1" name="razonSocial" class="element text medium" type="text" maxlength="255" value="{razonSocial}"/> 
		</div> 
		</li>
			
		<li id="li_1" >
		<label class="description" for="element_1">Nombre del negocio </label>
		<div>
			<input id="element_1" name="nombreNegocio" class="element text medium" type="text" maxlength="255" value="{nombreNegocio}"/> 
		</div> 
		</li>		
        <li id="li_1" >
		<label class="description" for="element_1">Identificaci&oacute;n Tributaria </label>
		<div>
			<input id="element_1" name="identificacionTributaria" class="element text medium" type="text" maxlength="255" value="{identificacionTributaria}"/> 
		</div> 
		</li>
        <li id="li_10" >
		<label class="description" for="element_10">Pa&iacute;s</label>
		<div>
			<select class="element select medium" id="element_7_6" name="pais" > 
				{pais}
			</select>
		</div> 
		</li>		
       <li id="li_6" >
		<label class="description" for="element_6">Ciudad </label>
		<div>
			<input id="element_6" name="ciudad" class="element text medium" type="text" maxlength="255" value="{ciudad}"/> 
		</div> 
		</li>	
        <li id="li_6" >
		<label class="description" for="element_6">Rubro </label>
		<div>
			<input id="element_6" name="rubro" class="element text medium" type="text" maxlength="255" value="{rubro}"/> 
		</div> 
		</li>
        
        <li class="section_break">
			<h3></h3>

		</li>
	  <li id="li_5" >
		<label class="description" for="element_5">Datos de la persona de contacto </label>
		<span>
			<input id="element_5_1" name="nombre" class="element text" maxlength="255" size="14" value="{nombre}"/>
			<label>Nombre</label>
		</span>
		<span>
			<input id="element_5_2" name="apellido" class="element text" maxlength="255" size="14" value="{apellido}"/>
			<label>Apellido</label>
		</span> 
		</li>		
		<li id="li_4" >
		<label class="description" for="element_4">Email </label>
		<div>
			<input id="element_4" name="email" class="element text medium" type="text" maxlength="255" value="{email}"/> 
		</div> 
		</li>		
		<li id="li_3" >
		<label class="description" for="element_3">Telefono de contacto 1</label>
		<span>
			<input id="element_3_1" name="telefono1Pais" class="element text" size="3" maxlength="8" value="{telefono1Pais}" type="text"> -
			<label for="element_3_1">(###)</label>
		</span>
		<span>
			<input id="element_3_2" name="telefono1Ciudad" class="element text" size="3" maxlength="8" value="{telefono1Ciudad}" type="text"> -
			<label for="element_3_2">###</label>
		</span>
		<span>
	 		<input id="element_3_3" name="telefono1" class="element text" size="9" maxlength="24" value="{telefono1}" type="text">
			<label for="element_3_3">####</label>
		</span>
 		<span>
			<select class="element select medium" id="element_14" name="telefono1Tipo" style="width: 150px;"> 
				{telefono1Tipo}			
			</select>
		</span>
		</li>	

		<li id="li_3" >
		<label class="description" for="element_3">Telefono de contacto 2</label>
		<span>
			<input id="element_3_1" name="telefono2Pais" class="element text" size="3" maxlength="8" value="{telefono2Pais}" type="text"> -
			<label for="element_3_1">(###)</label>
		</span>
		<span>
			<input id="element_3_2" name="telefono2Ciudad" class="element text" size="3" maxlength="8" value="{telefono2Ciudad}" type="text"> -
			<label for="element_3_2">###</label>
		</span>
		<span>
	 		<input id="element_3_3" name="telefono2" class="element text" size="9" maxlength="24" value="{telefono2}" type="text">
			<label for="element_3_3">####</label>
		</span>
 		<span>
			<select class="element select medium" id="element_14" name="telefono2Tipo" style="width: 150px;"> 
				{telefono2Tipo}			
			</select>
		</span>
		</li>	
      <li class="section_break">
			<h3></h3>

		</li>
        
        	<li id="li_7" >
		<label class="description" for="element_7">Direcci&oacute;n  administrativas </label>
		
		<div>
			<input id="element_7_1" name="calle1" class="element text large" value="{calle1}" type="text">
			<label for="element_7_1">Linea 1</label>
		</div>
	
		<div>
			<input id="element_7_2" name="calle2" class="element text large" value="{calle2}" type="text">
			<label for="element_7_2">Line 2</label>
		</div>
	
		<div class="left">
			<input id="element_7_3" name="ciudad2" class="element text medium" value="{ciudad2}" type="text">
			<label for="element_7_3">Ciudad</label>
		</div>
	
		<div class="right">
			<input id="element_7_4" name="estado" class="element text medium" value="{estado}" type="text">
			<label for="element_7_4">Estado</label>
		</div>
	
		<div class="left">
			<input id="element_7_5" name="codigoPostal" class="element text medium" maxlength="15" value="{codigoPostal}" type="text">
			<label for="element_7_5">C&oacute;digo Postal</label>
		</div>
	
		<div class="right">
		  <select class="element select medium" id="element_7_6" name="pais2"> 
				{pais2}
			</select>
		<label for="element_7_6">Pa&iacute;s</label>
	</div> 
		</li>	
                
			  <li class="section_break">
			<h3>Datos de la cuenta</h3>
			<p></p>
		</li>		<li id="li_11" >
		<label class="description" for="element_11">Nombre del Banco </label>
		<div>
			<input id="element_11" name="nombreBanco" class="element text medium" type="text" maxlength="255" value="{nombreBanco}"/> 
		</div> 
		</li>		<li id="li_12" >
		<label class="description" for="element_12">N&uacute;mero de cuenta </label>
		<div>
			<input id="element_12" name="numeroCuenta" class="element text medium" type="text" maxlength="255" value="{numeroCuenta}"/> 
		</div> 
		</li>		
        
        <li id="li_13" >
		<label class="description" for="element_13">Titular de la cuenta </label>
		<div>
			<input id="element_13" name="titularCuenta" class="element text medium" type="text" maxlength="255" value="{titularCuenta}"/> 
		</div> 
		</li>		
        <li id="li_12" >
		<label class="description" for="element_12">Otros datos de la cuenta </label>
		<div>
			<input id="element_12" name="otrosDatosCuenta" class="element text medium" type="text" maxlength="255" value="{otrosDatosCuenta}"/> 
		</div> 
		</li>
        <li id="li_15" >
		<label class="description" for="element_15">Tiene internet en el local de venta </label>
		<span>
		{tieneInternet}
		</span> 
		</li>		<li id="li_16" >
		<label class="description" for="element_16">Tiene algun sistema de Gift Card </label>
		<span>
		{tieneSistGiftCard}
		</span> 
		</li>		<li id="li_17" >
		<label class="description" for="element_17">Como facturan a sus clientes? </label>
		<span>
		{comoFactura}
		</span> 
		</li>
		<li>
			<img id="captcha" src="/app/imagenes/ui-anim_basic_16x16.gif" alt="CAPTCHA Image" />
			<input type="text" name="captcha_code" size="10" maxlength="4" />
			<a href="#" onclick="document.getElementById('captcha').src = '{path}captcha/securimage_show.php?' + Math.random(); return false"><img width="32" height="32" border="0" align="bottom" onclick="this.blur()" alt="Reload Image" src="{path}captcha/images/refresh.png"></a>

		</li>
		
		<li class="buttons">
			    <input type="hidden" name="accion" value="guardar" />
			    
				<input id="saveForm" class="button_text" type="submit" name="submit" value="Submit" />
		</li>
			</ul>
		</form>	

	</div>
	<img id="bottom" src="{path}imagenes/bottom.png" alt="">
</body>
</html>

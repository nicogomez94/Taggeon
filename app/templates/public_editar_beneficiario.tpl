<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Beneficiario</title>
<link rel="stylesheet" type="text/css" href="{path}css/view.css" media="all">
<script type="text/javascript" src="{path}libreria/view.js"></script>
</head>
<body id="main_body">
{bienvenida}
{menu}	
	
	<img id="top" src="{path}imagenes/top.png" alt="">
	<div id="form_container">
	
		<h1><a>Beneficiario</a></h1>
		<form id="form_465818" class="appnitro"  method="post" action="public_editar_beneficiario.php">
					<div class="form_description">
			<h2>Nuevo / New Beneficiario</h2>
			<font color="red">{error}</font>
		</div>						
			<ul >
            <li id="li_1" >
              <label class="description" for="element_1">Usuario / User</label>
		<div>
			<input id="element_1" name="usuario" class="element text medium" type="text" maxlength="255" value="{usuario}"/> 
		</div> 
		</li>
      <li class="section_break">
			<h3></h3>

		</li>
	<li id="li_5" >
		<label class="description" for="element_5">Nombre / Name</label>
		<div>
			<input id="element_5" name="nombre" class="element text medium" type="text" maxlength="255" value="{nombre}"/> 
		</div> 
		</li>		<li id="li_8" >
		<label class="description" for="element_8">Apellido / Last Name</label>
		<div>
			<input id="element_8" name="apellido" class="element text medium" type="text" maxlength="255" value="{apellido}"/> 
		</div> 
		</li>
        <li id="li_10" >
		<label class="description" for="element_10">Pa&iacute;s / Country</label>
		<div>
			<select class="element select medium" id="element_7_6" name="pais" > 
				{pais}
			</select>
		</div> 
		</li>		<li id="li_11" >
		<label class="description" for="element_11">Ciudad / City</label>
		<div>
			<input id="element_11" name="ciudad" class="element text medium" type="text" maxlength="255" value="{ciudad}"/> 
		</div> 
		</li>
        <li class="section_break">
			<h3>Datos de Contacto / Contact data:</h3>
			<p></p>
		</li>
        <li id="li_13" >
		<label class="description" for="element_13">E-mail </label>
		<div>
			<input id="element_13" name="email" class="element text medium" type="text" maxlength="255" value="{email}"/> 
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

      <li id="li_7" >
		<label class="description" for="element_7">Direcci&oacute;n / Address:</label>
		
		<div>
			<input id="element_7_1" name="calle1" class="element text large" value="{calle1}" type="text">
			<label for="element_7_1">Linea 1 / Line 1</label>
		</div>
	
		<div>
			<input id="element_7_2" name="calle2" class="element text large" value="{calle2}" type="text">
			<label for="element_7_2">Linea 2 / Line 2</label>
		</div>
	
		<div class="left">
			<input id="element_7_3" name="ciudad2" class="element text medium" value="{ciudad2}" type="text">
			<label for="element_7_3">Ciudad / City</label>
		</div>
	
		<div class="right">
			<input id="element_7_4" name="estado" class="element text medium" value="{estado}" type="text">
			<label for="element_7_4">Estado / State</label>
		</div>
	
		<div class="left">
			<input id="element_7_5" name="codigoPostal" class="element text medium" maxlength="15" value="{codigoPostal}" type="text">
			<label for="element_7_5">C&oacute;digo Postal / Zip Code</label>
		</div>
	
		<div class="right">
			<select class="element select medium" id="element_7_6" name="pais2" > 
				{pais2}
			</select>
		<label for="element_7_6">Pa&iacute;s / Country</label>
	</div> 
		</li>
        

					<li class="buttons">
			    <input type="hidden" name="accion" value="guardar" />
			    <input type="hidden" name="id" value="{id}" />
			    <input type="hidden" name="idUsuario" value="{idUsuario}" />
				<input id="saveForm" class="button_text" type="submit" name="submit" value="Submit" />
		</li>
			</ul>
		</form>	

	</div>
	<img id="bottom" src="{path}imagenes/bottom.png" alt="">
</body>
</html>

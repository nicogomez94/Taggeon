<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Nuevo Cliente / New Customer</title>
<link rel="stylesheet" type="text/css" href="{path}css/view.css" media="all">
<script type="text/javascript" src="{path}libreria/view.js"></script>

<link type="text/css" href="{path}css/jquery-ui-1.8.23.custom.css" rel="stylesheet" />
<script type="text/javascript" src="{path}libreria/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="{path}libreria/jquery-ui.min.js"></script>
<script type="text/javascript" src="{path}libreria/libreria.js"></script>

<script>
	$(function() {
		$( "#ciudad" )
			.keypress(function(){
				var paramPais = $( "#pais");
				if (paramPais.val() == 'USA'){
					$( "#ciudad" ).autocomplete({
						source: "{path}searchCiudad.php",
						minLength: 2,
						select: function( event, resp ) {
							if (resp.item){
									 document.getElementById('estado').value = resp.item.state;
							}
						},
						change: function( event, resp ) {
							if (resp.item){
									 document.getElementById('estado').value = resp.item.state;
							}
						}
					});
				}
			});
   });
</script>

</head>
<body id="main_body">
{bienvenida}
{menu}	
	<img id="top" src="{path}imagenes/top.png" alt="">
	<div id="form_container">
	
		<h1><a>Cliente</a></h1>
		<form id="form_465818" class="appnitro"  method="post" action="alta_cliente.php">
					<div class="form_description">
			<h2>Nuevo Cliente / New Customer</h2>
			<font color="red">{error}</font>
		</div>						
			<ul >
		<li id="li_5" >
		<label class="description" for="nombre">Nombre / Name</label>
		<div>
			<input id="nombre" name="nombre" class="element text medium" type="text" maxlength="255" value="{nombre}"/> 
		</div> 
		</li>		
		<li id="li_8" >
		<label class="description" for="apellido">Apellido / Last Name</label>
		<div>
			<input id="apellido" name="apellido" class="element text medium" type="text" maxlength="255" value="{apellido}"/> 
		</div> 
		</li>
		<li id="li_8" >
		<label class="description" for="email">Email</label>
		<div>
			<input id="email" name="email" class="element text medium" type="text" maxlength="255" value="{email}"/> 
		</div> 
		</li>
     
		<li id="li_8" >
		<label class="description" for="confirmaremail">Confirma Email</label>
		<div>
			<input id="confirmaremail" name="confirmaremail" class="element text medium" type="text" maxlength="255" value="{confirmaremail}"/> 
		</div> 
		</li>
     
		<li id="li_5" >
		<label class="description" for="usuario">Usuario</label>
		<div>
			<input id="usuario" name="usuario" class="element text medium" type="text" maxlength="255" value="{usuario}"/> 
		</div> 
		</li>		
       <li id="li_3" >
		<label class="description" for="pass">Password</label>
		<div>
			<input id="pass" name="pass" class="element text medium" type="password" maxlength="255" value="{password}"/> 
		</div> 
		</li>
        <li id="li_3" >
		<label class="description" for="pass2">Confirmar Password / Confirmed Password</label>
		<div>
			<input id="pass2" name="pass2" class="element text medium" type="password" maxlength="255" value="{confirmarPassword}"/> 
		</div> 
		</li>
		<li id="li_11" >
			<label class="description" for="ciudad">Ciudad / City</label>
		<div>
			<input id="ciudad" name="ciudad" class="element text medium" type="text" maxlength="255" value="{ciudad}"/> 
		</div> 
		</li>
		<li id="li_11" >
			<label class="description" for="estado">Estado / State</label>
		<div>
			<input id="estado" name="estado" class="element text medium" type="text" maxlength="255" value="{estado}"/> 
		</div> 
		</li>
		<li id="li_11" >
			<label class="description" for="codigoPostal">C&oacute;digo Postal / Zip Code</label>
		<div>
			<input id="codigoPostal" name="codigoPostal" class="element text medium" type="text" maxlength="255" value="{codigoPostal}"/> 
		</div> 
		</li>
      <li id="li_10" >
			<label class="description" for="pais">Pa&iacute;s / Country</label>
		<div>
			<select class="element select medium" id="pais" name="pais" > 
				{pais}
			</select>
		</div> 
		</li>

		<li id="li_3" >
		<label class="description" for="telefono1Pais">Telefono de contacto 1 / #Contact Phone</label>
		<span>
			<input id="telefono1Pais" name="telefono1Pais" class="element text" size="3" maxlength="8" value="{telefono1Pais}" type="text"> -
			<label for="telefono1Pais">(###)</label>
		</span>
		<span>
			<input id="telefono1Ciudad" name="telefono1Ciudad" class="element text" size="3" maxlength="8" value="{telefono1Ciudad}" type="text"> -
			<label for="telefono1Ciudad">###</label>
		</span>
		<span>
	 		<input id="telefono1" name="telefono1" class="element text" size="9" maxlength="24" value="{telefono1}" type="text">
			<label for="telefono1">####</label>
		</span>
 		<span>
			<select class="element select medium" id="telefono1Tipo" name="telefono1Tipo" style="width: 150px;"> 
				{telefono1Tipo}			
			</select>
		</span>
		</li>	

		<li id="li_3" >
		<label class="description" for="telefono2Pais">Telefono de contacto 2 / #Contact Phone</label>
		<span>
			<input id="telefono2Pais" name="telefono2Pais" class="element text" size="3" maxlength="8" value="{telefono2Pais}" type="text"> -
			<label for="telefono2Pais">(###)</label>
		</span>
		<span>
			<input id="telefono2Ciudad" name="telefono2Ciudad" class="element text" size="3" maxlength="8" value="{telefono2Ciudad}" type="text"> -
			<label for="telefono2Ciudad">###</label>
		</span>
		<span>
	 		<input id="telefono2" name="telefono2" class="element text" size="9" maxlength="24" value="{telefono2}" type="text">
			<label for="telefono2">####</label>
		</span>
 		<span>
			<select class="element select medium" id="telefono2Tipo" name="telefono2Tipo" style="width: 150px;"> 
				{telefono2Tipo}			
			</select>
		</span>
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

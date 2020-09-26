<section style=" background-image: url(/wp-content/themes/mandaseguro/img/bg_proceso_large_blank-02.png);background-repeat:no-repeat;color:#686464; font-size:14px;height:1500px;" class="contenedor_procesos">
<div style"height:400px;"="" id="form_container">
<div id="form_container" style"height:400px;">
	
        <h1 id="paso1" class="titulo_procesos titulo_activo" style="z-index:10;background-color: #5F5F5F;">alta retailer</h1>
		<form id="form_465844" class="appnitro"  method="post" action="alta_retailer.php">
					<div class="form_description">
			<h2 class="descripcion_procesos" style="left:50px; font-weight:700; color:#686464;">complete el formulario</h2>
			<font color="red"  class="error">* campos obligatorios</font>
		</div>						
			<div class="cuestionario" style=" margin-left:-40px;">
			{error}
		  	<table width="795" border="0" align="center" cellpadding="0" cellspacing="2">
		    <tr align="left">
				<td width="100%">
          <label class="description" for="element_1">Usuario / User</label>
		<div>
			<input id="element_1" name="usuario" class="element text medium" type="text" maxlength="255" value="{usuario}"/> 
		</div> 
		<label class="description" for="element_3">Contrase&ntilde;a / Password</label>
		<div>
			<input id="element_3" name="pass" class="element text medium" type="password" maxlength="255" value="{password}"/> 
		</div> 
		<label class="description" for="element_3">Re-Contras&ntilde;a / Re-Password</label>
		<div>
			<input id="element_3" name="pass2" class="element text medium" type="password" maxlength="255" value="{confirmarPassword}"/> 
		</div> 
            
		<label class="description" for="element_1">Raz&oacute;n Social </label>
		<div>
			<input id="element_1" name="razonSocial" class="element text medium" type="text" maxlength="255" value="{razonSocial}"/> 
		</div> 
		<label class="description" for="element_1">Nombre del negocio </label>
		<div>
			<input id="element_1" name="nombreNegocio" class="element text medium" type="text" maxlength="255" value="{nombreNegocio}"/> 
		</div> 
		<label class="description" for="element_1">Identificaci&oacute;n Tributaria </label>
		<div>
			<input id="element_1" name="identificacionTributaria" class="element text medium" type="text" maxlength="255" value="{identificacionTributaria}"/> 
		</div> 
		<label class="description" for="element_10">Pa&iacute;s</label>
		<div>
			<select class="element select medium" id="element_7_6" name="pais" > 
				{pais}
			</select>
		</div> 
		<label class="description" for="element_6">Ciudad </label>
		<div>
			<input id="element_6" name="ciudad" class="element text medium" type="text" maxlength="255" value="{ciudad}"/> 
		</div> 
		<label class="description" for="element_6">Rubro </label>
		<div>
			<select class="element select medium" id="element_14" name="rubro" style="width: 150px;"> 
				{rubro}			
			</select>
		</div> 
        
		<h3>Datos de la persona de contacto </h3>
		<label>Nombre</label><br>
		<span>
			<input id="element_5_1" name="nombre" class="element text" maxlength="255" size="14" value="{nombre}"/>
		</span>
		<br><label>Apellido</label><br>
		<span>
			<input id="element_5_2" name="apellido" class="element text" maxlength="255" size="14" value="{apellido}"/>
		</span> 
		<br><label class="description" for="element_4">Email </label><br>
		<div>
			<input id="element_4" name="email" class="element text medium" type="text" maxlength="255" value="{email}"/> 
		</div> 
		<label class="description" for="element_3">Telefono de contacto 1</label><br>
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

		<br><label class="description" for="element_3">Telefono de contacto 2</label><br>
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
			<h3></h3>

        
		<h3>Direcci&oacute;n  administrativas </h3>
		
		<label for="element_7_1">Linea 1</label>
		<div>
			<input id="element_7_1" name="calle1" class="element text large" value="{calle1}" type="text">
		</div>
	
		<label for="element_7_2">Line 2</label>
		<div>
			<input id="element_7_2" name="calle2" class="element text large" value="{calle2}" type="text">
		</div>
	
		<label for="element_7_3">Ciudad</label>
		<div class="left">
			<input id="element_7_3" name="ciudad2" class="element text medium" value="{ciudad2}" type="text">
		</div>
	
		<label for="element_7_4">Estado</label>
		<div class="right">
			<input id="element_7_4" name="estado" class="element text medium" value="{estado}" type="text">
		</div>
	
		<label for="element_7_5">C&oacute;digo Postal</label>
		<div class="left">
			<input id="element_7_5" name="codigoPostal" class="element text medium" maxlength="15" value="{codigoPostal}" type="text">
		</div>
	
		<label for="element_7_6">Pa&iacute;s</label>
		<div class="right">
		  <select class="element select medium" id="element_7_6" name="pais2"> 
				{pais2}
			</select>
		</div> 
                
			<h3>Datos de la cuenta</h3>
		<label class="description" for="element_11">Nombre del Banco </label>
		<div>
			<input id="element_11" name="nombreBanco" class="element text medium" type="text" maxlength="255" value="{nombreBanco}"/> 
		</div> 
		<label class="description" for="element_12">N&uacute;mero de cuenta </label>
		<div>
			<input id="element_12" name="numeroCuenta" class="element text medium" type="text" maxlength="255" value="{numeroCuenta}"/> 
		</div> 
        
		<label class="description" for="element_13">Titular de la cuenta </label>
		<div>
			<input id="element_13" name="titularCuenta" class="element text medium" type="text" maxlength="255" value="{titularCuenta}"/> 
		</div> 
		<label class="description" for="element_12">Otros datos de la cuenta </label>
		<div>
			<input id="element_12" name="otrosDatosCuenta" class="element text medium" type="text" maxlength="255" value="{otrosDatosCuenta}"/> 
		</div> 
		<br>
		<label class="description" for="element_15">Tiene internet en el local de venta </label>
		<span>
		{tieneInternet}
		</span> 
		<br>
		<br>
		<label class="description" for="element_16">Tiene algun sistema de Gift Card </label>
		<span>
		{tieneSistGiftCard}
		</span> 
		<br>
		<br>
		<label class="description" for="element_17">Como facturan a sus clientes? </label>
		<span>
		{comoFactura}
		</span> 
			
		<br>
		<br>
			    <input type="hidden" name="accion" value="guardar" />
			    
				<input id="saveForm" class="button_text" type="submit" name="submit" value="continuar" />
	          </td>
	        </tr>
			</table>
		</div>
		</form>	
	</div>
</section><!--end secciones -->

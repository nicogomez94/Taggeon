<section style=" background-image: url(/wp-content/themes/mandaseguro/img/bg_proceso_large_blank-02.png);background-repeat:no-repeat;color:#686464; font-size:14px;height:900px;" class="contenedor_procesos">
<div style"height:400px;"="" id="form_container">
<div id="form_container" style"height:400px;">
	
        <h1 id="paso1" class="titulo_procesos titulo_activo" style="z-index:10;background-color: #5F5F5F;">editar beneficiario</h1>
		<form id="form_465818" class="appnitro"  method="post" action="editar_beneficiario.php">
			    <input type="hidden" name="accion" value="guardar" />
			    <input type="hidden" name="id" value="{id}" />
			    <input type="hidden" name="idUsuario" value="{idUsuario}" />
					<div class="form_description">
			<h2 class="descripcion_procesos" style="left:50px; font-weight:700; color:#686464;">complete el formulario</h2>
			<font color="red"  class="error">* campos obligatorios</font>
		</div>						
			<div class="cuestionario" style=" margin-left:-40px;">
			{error}
		  	<table width="795" border="0" align="center" cellpadding="0" cellspacing="2">
		    <tr align="left">
				<td width="100%">
		        <label class="description" for="nombre">*</label></span><label class="description" for="nombre">nombre / name</label>
		      <div>
		        <input name="nombre" type="text" class="element text medium" id="nombre" value="{nombre}" size="30" maxlength="255" tabindex="1"/>
	          </div>
		      <div>
		        <label class="description" for="pais"> pa&iacute;s / country</label>
	          </div>
		      <div>
					<select  style="width:150px;" name="pais" class="element select medium" id="pais" >
            		{pais}
        	 		</select>
	          </div>
              <label class="description" for="element_1">Usuario / User</label>
		<div>
			<input id="element_1" name="usuario" class="element text medium" type="text" maxlength="255" value="{usuario}"/> 
		</div> 
			<h3></h3>

		<label class="description" for="element_8">Apellido / Last Name</label>
		<div>
			<input id="element_8" name="apellido" class="element text medium" type="text" maxlength="255" value="{apellido}"/> 
		</div> 
		<label class="description" for="element_11">Ciudad / City</label>
		<div>
			<input id="element_11" name="ciudad" class="element text medium" type="text" maxlength="255" value="{ciudad}"/> 
		</div> 
			<h3>Datos de Contacto / Contact data:</h3>
			<p></p>
		<label class="description" for="element_13">E-mail </label>
		<div>
			<input id="element_13" name="email" class="element text medium" type="text" maxlength="255" value="{email}"/> 
		</div> 
		<br>
		<label class="description" for="element_3">Telefono de contacto</label><br>
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
		<br><br>
		<label class="description" for="element_7">Direcci&oacute;n / Address:</label>
		
		<div>
			<label for="element_7_1">Linea 1 / Line 1</label><br>
			<input id="element_7_1" name="calle1" class="element text large" value="{calle1}" type="text">
		</div>
	
		<div>
			<label for="element_7_2">Linea 2 / Line 2</label><br>
			<input id="element_7_2" name="calle2" class="element text large" value="{calle2}" type="text">
		</div>
	
		<div class="left">
			<label for="element_7_3">Ciudad / City</label><br>
			<input id="element_7_3" name="ciudad2" class="element text medium" value="{ciudad2}" type="text">
		</div>
	
		<div class="right">
			<label for="element_7_4">Estado / State</label><br>
			<input id="element_7_4" name="estado" class="element text medium" value="{estado}" type="text">
		</div>
	
		<div class="left">
			<label for="element_7_5">C&oacute;digo Postal / Zip Code</label><br>
			<input id="element_7_5" name="codigoPostal" class="element text medium" maxlength="15" value="{codigoPostal}" type="text">
		</div>
	
		<div class="right">
		<label for="element_7_6">Pa&iacute;s / Country</label><br>
			<select class="element select medium" id="element_7_6" name="pais2" > 
				{pais2}
			</select>
	</div> 
        

				<br><br><input id="saveForm" class="button_text" type="submit" name="submit" value="continuar" />
				</td>
	        </tr>
			</table>
			</div>
		</form>	
	</div>
</section><!--end secciones -->

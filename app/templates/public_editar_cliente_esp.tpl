<section style=" background-image: url(/wp-content/themes/mandaseguro/img/bg_proceso_large_blank-02.png);background-repeat:no-repeat;color:#686464; font-size:14px;height:300px;" class="contenedor_procesos">
<div style"height:400px;"="" id="form_container">
<div id="form_container" style"height:400px;">
	
        <h1 id="paso1" class="titulo_procesos titulo_activo" style="z-index:10;background-color: #5F5F5F;">configuración de la cuenta</h1>
		<form id="form_465818" class="appnitro"  method="post" action="public_editar_cliente.php">
							 <input type="hidden" name="accion" value="guardar" />
							 <input type="hidden" name="id" value="{id}" />
							 <input type="hidden" name="idUsuario" value="{idUsuario}" />
		<div class="form_description">
			<h2 class="descripcion_procesos" style="left:50px; font-weight:700; color:#686464;">complete el formulario y empiece a usar MandaSeguro.com
     </h2>
			<font color="red"  class="error">* campos obligatorios</font>
		</div>						
		<ul class="cuestionario" style=" margin-left:-40px;">
		{error}
		  <table width="795" border="0" align="center" cellpadding="0" cellspacing="2">
		    <tr align="left">
		      <td width="89px"><span class="items_procesos"> <span class="campo_obligatorio">
		        <label class="description" for="nombre">*</label></span><label class="description" for="nombre">nombre</label>
	          </span></td>
		      <td width="281px"><span class="items_procesos">
		        <input name="nombre" type="text" class="element text medium" id="nombre" value="{nombre}" size="30" maxlength="255" tabindex="1"/>
	          </span></td>
		      <td width="122px"><span class="items_procesos">
		        <label class="description" for="pais"> país</label>
	          </span></td>
		      <td width="293px"><span class="items_procesos">
					<select  style="width:150px;" name="pais" class="element select medium" id="pais" >
            		{pais}
        	 		</select>
	          </td>
	        </tr>
		    <tr align="left">
		      <td><span class="items_procesos">
		        <label class="description" for="apellido"><span class="campo_obligatorio">*</span>apellido</label>
	          </span></td>
		      <td><input name="apellido" type="text" class="element text medium" id="apellido" value="{apellido}" size="30" maxlength="255" tabindex="2"/></td>
		      <td><span class="items_procesos pass">
		        <label class="description" for="ciudad">ciudad</label>
	          </span></td>
		      <td><span class="items_procesos pass">
		        <input id="ciudad" name="ciudad" class="element text medium" type="text" maxlength="255" value="{ciudad}"  style="width: 130px;" tabindex="7"/>
	          </span></td>
	        </tr>
		    <tr align="left">
		      <td><span class="items_procesos">
		        <label class="description" for="email"><span class="campo_obligatorio">*</span>e-mail</label>
	          </span></td>
		      <td><span class="items_procesos">
		        <input name="email" type="text" class="element text medium" id="email" value="{email}" size="30" maxlength="255" tabindex="3"/>
	          </span></td>
		      <td><span class="items_procesos pass">
		        <label class="description" for="estado">estado</label>
	          </span></td>
		      <td><span class="items_procesos pass">
		        <input id="estado" name="estado" class="element text medium" type="text" maxlength="255" value="{estado}"  style="width: 130px;" tabindex="7"/>
	          </span></td>
	        </tr>
		    <tr align="left">
		      <td ><span class="items_procesos">
		        <label class="description" for="usuario"><span class="campo_obligatorio">*</span>nombre de usuario</label>
	          </span></td>
		      <td ><span class="items_procesos">
		        <input name="usuario" type="text" class="element text medium" id="usuario" value="{usuario}" size="30" maxlength="255" tabindex="5"/>
	          </span></td>
		      <td><span class="items_procesos pass">
		        <label class="description" for="ciudad">código postal</label>
	          </span></td>
		      <td><span class="items_procesos pass">
		        <input id="codigoPostal" name="codigoPostal" class="element text medium" type="text" maxlength="64" value="{codigoPostal}"  style="width: 130px;" tabindex="7"/>
	          </span></td>
	        </tr>
		    <tr align="left">
		      <td ><span class="items_procesos">
		        <label class="description" for="usuario">#teléfono de contacto</label>
	          </span></td>
		      <td ><span class="items_procesos">
		        <input name="telefono1Pais" type="text" class="element text" id="telefono1Pais" value="{telefono1Pais}" size="3" maxlength="3" style="width:35px;" tabindex="5"/>
		        <input name="telefono1Ciudad" type="text" class="element text" id="telefono1Ciudad" value="{telefono1Ciudad}" size="3" maxlength="8" style="width:35px;" tabindex="5"/>
		        <input name="telefono1" type="text" class="element text" id="telefono1" value="{telefono1}" size="9" maxlength="24" style="width:85px;" tabindex="5"/>
				  <select class="element select medium" id="element_14" name="telefono1Tipo" style="width: 80px;"> 
				   {telefono1Tipo}			
			     </select>
	          </span></td>
		      <td colspan="2" rowspan="2" align="rigth" valign="middle">
							<li class="item_proceso_btn" >
								<input id="saveForm2" class="button_text" type="submit" name="saveForm" value="continuar" tabindex="9" style="float:left;margin-top: 10px;margin-left:10px;"/>
							</li>
	         </td>
	        </tr>
		    <tr align="left">
		      <td ><span class="items_procesos">
		        <label class="description" for="usuario">#teléfono de contacto 2</label>
	          </span></td>
		      <td ><span class="items_procesos">
		        <input name="telefono2Pais" type="text" class="element text" id="telefono2Pais" value="{telefono2Pais}" size="3" maxlength="3" style="width:35px;" tabindex="5"/>
		        <input name="telefono2Ciudad" type="text" class="element text" id="telefono2Ciudad" value="{telefono2Ciudad}" size="3" maxlength="8" style="width:35px;" tabindex="5"/>
		        <input name="telefono2" type="text" class="element text" id="telefono2" value="{telefono2}" size="9" maxlength="24" style="width:85px;" tabindex="5"/>
				  <select class="element select medium" id="element_14" name="telefono2Tipo" style="width: 80px;"> 
				   {telefono2Tipo}			
			     </select>
	          </span></td>
	        </tr>
	      </table>
        </ul>
		</form>	

	</div>


</section><!--end secciones -->

<section class="contenedor_procesos"  style="background-image: url(/wp-content/themes/mandaseguro/img/bg_proceso_large-02.png); height:500px; text-align:justify;">
<div id="form_container" >
	
        <h1 id="paso1" class="titulo_procesos titulo_activo" style="z-index:10;background-color: #5F5F5F;">Validaci&oacute;n de datos</h1>
		<form id="form_465818" class="appnitro"  method="post" action="index_retailer.php">
		<div class="form_description">
			<h2 class="descripcion_procesos" style="left:50px; font-weight:700; color:#686464;">&nbsp;</h2>
		</div>						
		<ul class="cuestionario" style=" margin-left:-40px;">
		{error}
		  <table width="795" border="0" align="center" cellpadding="0" cellspacing="2">
		    <tr align="left">
		      <td colspan="2" class="det_transaccion_retailer">
		        Transacci&oacute;n {id_transaccion}
         	</td>
			</tr>
		    <tr align="left">
		      <td colspan="2" class="det2_transaccion_retailer">
		        Beneficiario: {nombre} {apellido}
         	</td>
			</tr>
		    <tr align="left">
		      <td colspan="2" class="introduccion_retailer">
					&nbsp;
            </td>
	       </tr>
		    <tr align="left">
		      <td colspan="2" class="introduccion_retailer">
		        1) Solicite C&eacute;dula de Identidad al cliente
            </td>
	       </tr>
		    <tr align="left">
		      <td colspan="2" class="sub_introduccion_retailer">
					Verifique nombre y apellido y complete el n&uacute;mero de C&eacute;dula de Identidad.
            </td>
	       </tr>
		    <tr align="left">
		      <td width="130px"><span class="items_procesos"> <span class="campo_obligatorio">
		        <label class="description" for="documento">*</label></span><label class="description" for="documento">C&eacute;dula de Identidad</label>
	          </span>
				</td>
	         </td>
		      <td><span class="items_procesos">
		        <input name="documento" type="text" class="element text medium" id="documento" value="{documento}" size="30" maxlength="13" tabindex="2"   placeholder="XXX-XXXXXXX-X"/> 

	          </span></td>
	        </tr>
		    <tr align="left">
		      <td colspan="2" class="introduccion_retailer">
		        2) Solicite al cliente su casilla de email / correo&nbsp;elector&oacute;nico
            </td>
	       </tr>
		    <tr align="left">
		      <td ><span class="items_procesos"> 
		        <label class="description" for="email">email</label>
	          </span></td>
		      <td><span class="items_procesos">
		        <input name="email" type="text" class="element text medium" id="email" value="{email}" size="30"  tabindex="3"   placeholder="cliente@mail.com" /> 
	          </span></td>
	        </tr>
		    <tr align="left">
		      <td colspan="2" class="introduccion_retailer">
		        3) Solicite el PIN al Beneficiario.
            </td>
	       </tr>
		    <tr align="left">
		      <td colspan="2" class="sub_introduccion_retailer">
					El mismo fue enviado al beneficiario en un mensaje de texto en su celular o en su casilla de email.
            </td>
	       </tr>
		    <tr align="left">
		      <td><span class="items_procesos"> <span class="campo_obligatorio">
		        <label class="description" for="clave_beneficiario">*</label></span><label class="description" for="clave_beneficiario">PIN</label>
	          </span></td>
		      <td><span class="items_procesos">
		        <input name="clave_beneficiario" type="password" class="element text medium" id="clave_beneficiario" value="{clave_beneficiario}" size="30" maxlength="6" tabindex="3" placeholder="XXXXXX"/>
	          </span></td>
	        </tr>
		    	<tr align="left">
					<td colspan="2"><span class="campo_obligatorio">
					  <label class="description" for="documento">* Campo obligatorio</label>
					 </span>
					</td>
				</tr>
	        <tr>
		      <td align="left" colspan="2" valign="middle">
					<div style="width:100%; margin:auto;">
		        		<li class="items_procesos pass"> 
							<li class="item_proceso_btn"  style="width:450px;" >
								<input type="hidden" name="accion" value="canjear" />
								<input type="hidden" id="id_transaccion" name="id_transaccion" value="{id_transaccion}" />
								<input type="hidden" id="hidden_nombre_beneficiario" name="hidden_nombre_beneficiario" value="{nombre}" />
								<input type="hidden" id="hidden_apellido_beneficiario" name="hidden_apellido_beneficiario" value="{apellido}" />
								<input type="hidden" id="hidden_monto_beneficiario" name="hidden_monto_beneficiario" value="{monto}" />
								<input type="hidden" id="hidden_hash" name="hidden_hash" value="{hash}" />
								<input id="saveForm2" class="button_text" type="submit" name="saveForm" value="canejear" style="float:left;margin-top: 10px;" tabindex="6"/>
							</li>
		          	</li>
	          	</div>
				</td>
	        </tr>
				<tr align="left">
				  <td colspan="2" class="cerrar_sesion_retailer" height="60px" valign="bottom">
				  		No quiero hacer el canje en este momento
				  </td>
				</tr>
				<tr>
					<td align="left" colspan="2">
						<div style="float: left; margin: 0px 0px 0px 0px; position: absolute; z-index: 19; width: 130px;" class="item_proceso_btn">
									<input type="button" onclick="cerrarSesionRetailer();" value="Cerrar Sesi&oacute;n" name="logout" class="button_text" id="logout">
						</div>
					</td>
	        </tr>
	      </table>
        </ul>
		</form>	

	</div>
</section><!--end secciones -->

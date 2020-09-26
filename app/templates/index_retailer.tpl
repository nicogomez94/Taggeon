<section class="contenedor_procesos"  style="background-image: url(/wp-content/themes/mandaseguro/img/bg_proceso_large-02.png); height:300px; text-align:justify;">
<div id="form_container" >
	
        <h1 id="paso1" class="titulo_procesos titulo_activo" style="z-index:10;background-color: #5F5F5F;">Canje de MandaChecks</h1>
		<form id="form_465818" class="appnitro"  method="post" action="index_retailer.php">
		<div class="form_description">
			<h2 class="descripcion_procesos" style="left:50px; font-weight:700; color:#686464;">&nbsp;</h2>
		</div>						
		<ul class="cuestionario" style=" margin-left:-40px;">
		{error}
		  <table width="795" border="0" align="center" cellpadding="2" cellspacing="2">
		    <tr align="left">
		      <td colspan="3" class="introduccion_retailer">
		       	Solicite el N&uacute;mero de Transacci&oacute;n al beneficiario 
            </td>
	       </tr>
		    <tr align="left">
		      <td colspan="3" class="sub_introduccion_retailer">
					El mismo fue enviado al beneficiario en un mensaje de texto en su celular o en su casilla de email.
            </td>
	       </tr>
		    <tr align="left">
		      <td width="80px">
					<span class="items_procesos"> 
		        		<label class="description" for="id_transaccion">Transacci&oacute;n</label>
	          	</span>
				</td>
		      <td><span class="items_procesos">
		        <input name="id_transaccion" type="text" class="element text medium" id="id_transaccion" value="{id_transaccion}" size="30" maxlength="50" tabindex="1"/> 
	          </span></td>
				<td>
					<div style="width:100%; margin:auto;">
		        		<li class="items_procesos pass"> 
							<li class="item_proceso_btn"  style="width:450px;" >
								 <input type="hidden" name="accion" value="buscar" />
								<input id="saveForm2" class="button_text" type="submit" name="saveForm" value="buscar" style="float:left;margin-top: 0px;">
							</li>
		          	</li>
	          	</div>
				</td>
	        </tr>
		    <tr align="left">
		      <td colspan="3" class="cerrar_sesion_retailer" height="60px" valign="bottom">
					No quiero hacer el canje en este momento
				</td>
	        </tr>
		    <tr align="left">
		      <td colspan="3">
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

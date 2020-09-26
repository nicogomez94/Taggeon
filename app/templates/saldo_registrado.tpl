<section class="contenedor_procesos"  style="background-image: url(/wp-content/themes/mandaseguro/img/bg_proceso_large-02.png); height:300px; text-align:justify;">
<div id="form_container" >
	
        <h1 id="paso1" class="titulo_procesos titulo_activo" style="z-index:10;background-color: #5F5F5F;">Homer Retailer</h1>
		<form id="form_465818" class="appnitro"  method="post" action="index_retailer.php">
		<div class="form_description">
			<h2 class="descripcion_procesos" style="left:50px; font-weight:700; color:#686464;">&nbsp;</h2>
		</div>						
		<ul class="cuestionario" style=" margin-left:-40px;">
		  <table width="795" border="0" align="center" cellpadding="0" cellspacing="2">
		    <tr align="left">
		      <td class="introduccion_retailer">
						El saldo ya fue registrado en los Estados de Cuenta de MandaSeguro.
            </td>
	       </tr>
		    <tr align="left">
				<td>
					<div style="width:100%; margin:auto;">
		        		<li class="items_procesos pass"> 
							<li class="item_proceso_btn"  style="width:450px;" >
								 <input type="hidden" name="accion" value="inicio" />
								<input id="saveForm2" class="button_text" type="submit" name="saveForm" value="Canjear otra transacci&oacute;n" style="float:left;margin-top: 0px;">
							</li>
		          	</li>
	          	</div>
				</td>
	       </tr>
		    <tr align="left">
		      <td class="cerrar_sesion_retailer" height="60px" valign="bottom">
					No quiero hacer el canje en este momento
				</td>
	        </tr>
		    <tr align="left">
		      <td>
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

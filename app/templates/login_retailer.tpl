<section class="contenedor_procesos registrese" >
<div id="form_container">
	<h1 id="paso1" class="titulo_procesos titulo_activo" style="z-index:10;background-color: #5F5F5F;">login comercio</h1>
	<form class="appnitro"  method="post" action="login_retailer.php">
	<input type="hidden" name="accion" value="Entrar">
	<input type="hidden" name="redirect" value="{redirect}">
	<div class="form_description">
		<h2 class="descripcion_procesos" style="left:50px; font-weight:700; color:#686464;">&nbsp;</h2>
	</div>						
	<ul class="cuestionario" style=" margin-left:-40px;">
	  <table width="795" border="0" align="center" cellpadding="0" cellspacing="2">
    		<tr>
		      <td align="left" colspan="2"> 
					<font color="red" >{error}</font>
				</td>
          </tr>
		    <tr>
		      <td align="left" colspan="2">
					<span class="items_procesos"> 
		        		<h2 class="description" for="password">Clave &Uacute;nica para Canje de MandaChecks</h2>
	          	</span>
				</td>
	       </tr>
		    <tr align="left">
		      <td colspan="3" class="sub_introduccion_retailer" valign="top">
					Esta clave es personal del operador del Comercio que est&aacute; realizando el Canje. Si no la tiene, por favor comun&iacute;quese con su supervisor.
            </td>
	       </tr>
		    <tr>
		      <td align="left">
					<span class="items_procesos">
		        		<input name="password" type="password" class="element text medium" id="password" value="{pass}" size="30" maxlength="12" tabindex="1"/>
	          	</span>
				</td>
		      <td align="left">
					<div style="width: 580px;float: right; margin-top: -10px"><li class="item_proceso_btn"><input type="submit" value="login" tabindex="4"/></li></div>
				</td>
	       </tr>
	      </table>
        </ul>
		</form>	
	</div>
</section><!--end secciones -->

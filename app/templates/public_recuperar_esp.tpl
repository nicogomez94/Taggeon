<section class="contenedor_procesos registrese" >
<div id="form_container" style"height:350px;">
	
        <h1 id="paso1" class="titulo_procesos titulo_activo" style="z-index:10;background-color: #5F5F5F;">Recuperar contraseña/usuario</h1>
		<form id="form_465818" class="appnitro"  method="post" action="public_recuperar.php">
		<div class="form_description">
			<h2 class="descripcion_procesos" style="left:50px; font-weight:700; color:#686464;">&nbsp;
     </h2>
			<font color="red"  class="error">* campos obligatorios</font>
		</div>						
		<ul class="cuestionario" style=" margin-left:-40px;">
		{error}
		  <table width="795" border="0" align="center" cellpadding="0" cellspacing="2">
		    <tr align="left">
		      <td width="60px"><span class="items_procesos"> <span class="campo_obligatorio">
		        <label class="description" for="email">*</label></span><label class="description" for="email">E-mail</label>
	          </span></td>
		      <td><span class="items_procesos">
		        <input name="email" type="text" class="element text medium" id="email" value="{email}" size="30" maxlength="255" tabindex="1"/>
	          </span></td>
	        </tr>
	        <tr>
		      <td align="left" colspan="2" valign="middle">
					<div style="width:100%; margin:auto;">
		        		<li class="items_procesos pass"> 
							<img id="captcha" src="/app/imagenes/ui-anim_basic_16x16.gif" alt="CAPTCHA Image"  class="item_captcha"/>
							<input type="text" id="captcha_code" name="captcha_code" size="10" maxlength="4"  class="item_captcha"  style="position:relative; top:-10px;" tabindex="2"/>
					 		<a href="#captcha_code" onclick="setCaptcha();return false;"><img width="20" height="20" border="0" align="bottom" onclick="this.blur();" alt="Reload Image" src="/app/captcha/images/refresh.png"  class="item_captcha" style="top:-5px;"></a>
							<li class="item_proceso_btn"  style="width:450px;" >
								<input type="hidden" name="accion" value="recuperar" />
								<input id="saveForm2" class="button_text" type="submit" name="saveForm" value="continuar" style="float:left;margin-top: 62px;" tabindex="3"/>
							</li>
		          	</li>
	          	</div>
				</td>
	        </tr>
	      </table>
		<p>&nbsp;</p>
        </ul>
		</form>	

	</div>
</section><!--end secciones -->

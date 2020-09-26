<section class="contenedor_procesos registrese" style="height: 390px;">
<div id="form_container" style"height:350px;">
	
        <h1 id="paso1" class="titulo_procesos titulo_activo" style="z-index:10;background-color: #5F5F5F;">Edit password</h1>
		<form id="form_465818" class="appnitro"  method="post" action="editar_pass.php">
		<div class="form_description">
			<h2 class="descripcion_procesos" style="left:50px; font-weight:700; color:#686464;">&nbsp;
     </h2>
			<font color="red"  class="error">* mandatory information</font>
		</div>						
		<ul class="cuestionario" style=" margin-left:-40px;">
		{error}
		  <table width="795" border="0" align="center" cellpadding="0" cellspacing="2">
		    <tr align="left">
		      <td width="100px"><span class="items_procesos"> <span class="campo_obligatorio">
		        <label class="description" for="password">*</label></span><label class="description" for="pass">password</label>
	          </span></td>
		      <td><span class="items_procesos">
		        <input name="pass" type="password" class="element text medium" id="pass" value="{pass}" size="30" maxlength="12" tabindex="1"/> (More than 5 characters)
	          </span></td>
	        </tr>
		    <tr align="left">
		      <td><span class="items_procesos"> <span class="campo_obligatorio">
		        <label class="description" for="password2">*</label></span><label class="description" for="pass2">confirm password</label>
	          </span></td>
		      <td><span class="items_procesos">
		        <input name="pass2" type="password" class="element text medium" id="pass2" value="{pass2}" size="30" maxlength="12" tabindex="2"/>
	          </span></td>
	        </tr>
	        <tr>
		      <td align="left" colspan="2" valign="middle">
					<div style="width:100%; margin:auto;">
		        		<li class="items_procesos pass"> 
							<img id="captcha" src="/app/imagenes/ui-anim_basic_16x16.gif" alt="CAPTCHA Image"  class="item_captcha"  style="width: 150px;height: 56px;"/>
							<input type="text" id="captcha_code" name="captcha_code" size="10" maxlength="4"  class="item_captcha"  style="position:relative; top:-10px;" tabindex="3"/>
					 		<a href="#captcha_code" onclick="setCaptcha();return false;"><img width="20" height="20" border="0" align="bottom" onclick="this.blur();" alt="Reload Image" src="/app/captcha/images/refresh.png"  class="item_captcha" style="top:-5px;"></a>
							<li class="item_proceso_btn"  style="width:450px;" >
								 <input type="hidden" name="accion" value="guardar" />
								 <input type="hidden" name="idUsuario" value="{idUsuario}" />
								 <input type="hidden" name="perfil" value="{perfil}" />
								<input id="saveForm2" class="button_text" type="submit" name="saveForm" value="continue" style="float:left;margin-top: 40px;" tabindex="4"/>
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

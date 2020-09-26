<section class="contenedor_procesos registrese" >
<div id="form_container" style"height:350px;">
	
        <h1 id="paso1" class="titulo_procesos titulo_activo" style="z-index:10;background-color: #5F5F5F;">sign up and create your new account</h1>
		<form id="form_465818" class="appnitro"  method="post" action="public_alta_cliente.php">
		<div class="form_description">
			<h2 class="descripcion_procesos" style="left:50px; font-weight:700; color:#686464;">fill in the form and start using MandaSeguro.com
     </h2>
			<font color="red"  class="error">* required fields</font>
		</div>						
		<ul class="cuestionario" style=" margin-left:-40px;">
		{error}
		  <table width="795" border="0" align="center" cellpadding="0" cellspacing="2">
		    <tr align="left">
		      <td width="89px"><span class="items_procesos"> <span class="campo_obligatorio">
		        <label class="description" for="nombre">*</label></span><label class="description" for="nombre">first name</label>
	          </span></td>
		      <td width="281px"><span class="items_procesos">
		        <input name="nombre" type="text" class="element text medium" id="nombre" value="{nombre}" size="30" maxlength="255" tabindex="1"/>
	          </span></td>
		      <td width="122px"><span class="items_procesos">
		        <label class="description" for="usuario"><span class="campo_obligatorio">*</span>username</label>
	          </span></td>
		      <td width="293px"><span class="items_procesos">
		        <input name="usuario" type="text" class="element text medium" id="usuario" value="{usuario}" size="30" maxlength="255" tabindex="5"/>
	          </span></td>
	        </tr>
		    <tr align="left">
		      <td><span class="items_procesos">
		        <label class="description" for="apellido"><span class="campo_obligatorio">*</span>last name</label>
	          </span></td>
		      <td><input name="apellido" type="text" class="element text medium" id="apellido" value="{apellido}" size="30" maxlength="255" tabindex="2"/></td>
		      <td><span class="items_procesos pass">
		        <label class="description" for="pass"><span class="items_procesos"> <span class="campo_obligatorio"> *</span></span>password</label>
	          </span></td>
		      <td><span class="items_procesos pass">
		        <input id="pass" name="pass" class="element text medium" type="password" maxlength="12" value="{password}" style="width: 130px;" tabindex="6"/>&nbsp;(more than 5 characters)
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
		        <label class="description" for="pass2"><span class="items_procesos"> <span class="campo_obligatorio"> *</span></span>confirm password</label>
	          </span></td>
		      <td><span class="items_procesos pass">
		        <input id="pass2" name="pass2" class="element text medium" type="password" maxlength="12" value="{confirmarPassword}"  style="width: 130px;" tabindex="7"/>
	          </span></td>
	        </tr>
		    <tr align="left">
		      <td valign="top"><span class="items_procesos">
		        <label class="description" for="confirmaremail"><span class="campo_obligatorio">*</span>confirm e-mail</label>
	          </span></td>
		      <td valign="top"><span class="items_procesos">
		        <input name="confirmaremail" type="text" class="element text medium" id="confirmaremail" value="{confirmaremail}" size="30" maxlength="255" tabindex="4"/>
	          </span></td>
		      <td colspan="2" align="center">
              <div style="font-size:12px; color:#666; text-align:Left;"><input id="readterminos" name="readterminos" type="checkbox" value="leido" />I read and accept <a href="/pop_legales/index.html" style="text-decoration:underline;" class="click">MandaSeguro.com's terms and conditions</a></div>

					<div style="width:400px; margin:auto;">
		        		<li class="items_procesos pass"> 
							<img id="captcha" src="/app/imagenes/ui-anim_basic_16x16.gif" alt="CAPTCHA Image"  class="item_captcha" width="150" height="60"/>
							<input type="text" id="captcha_code" name="captcha_code" size="10" maxlength="4"  class="item_captcha"  style="position:relative; top:-20px;" tabindex="8"/>
					 		<a href="#captcha_code" onclick="setCaptcha();return false;"><img width="20" height="20" border="0" align="bottom" onclick="this.blur();" alt="Reload Image" src="/app/captcha/images/refresh.png"  class="item_captcha" style="top:-15px;"></a>
							<li class="item_proceso_btn"  style="width:450px;" >
								<input type="hidden" name="accion" value="guardar" />
								<input id="saveForm2" class="button_text" type="submit" name="saveForm" value="continue" style="float:left;margin-top: 33px;" tabindex="9"/>
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

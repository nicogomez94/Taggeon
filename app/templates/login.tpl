<section class="contenedor_procesos registrese" >
<div id="form_container" style"height:350px;">
	<h1 id="paso1" class="titulo_procesos titulo_activo" style="z-index:10;background-color: #5F5F5F;">login</h1>
	<form class="appnitro"  method="post" action="login.php">
	<input type="hidden" name="accion" value="Entrar">
	<div class="form_description">
		<h2 class="descripcion_procesos" style="left:50px; font-weight:700; color:#686464;">&nbsp;</h2>
	</div>						
	<ul class="cuestionario" style=" margin-left:-40px;">
	  <table width="795" border="0" align="center" cellpadding="0" cellspacing="2">
    		<tr>
		      <td colspan="2" align="left"> 
					<font color="red" >{error}</font>
				</td>
          </tr>
		    <tr>
		      <td colspan="2" align="left"> 
					<span class="items_procesos"> 
		        		<label class="description" for="usuario">user</label>
	          	</span><br>
					<span class="items_procesos">
		        		<input name="usuario" type="text" class="element text medium" id="usuario" value="{usuario}" size="30" maxlength="128" tabindex="1"/>
	          	</span>
				</td>
          </tr>
		    <tr>
		      <td align="left" colspan="2">
					<span class="items_procesos"> 
		        		<label class="description" for="password">password</label>
	          	</span><br>
					<span class="items_procesos">
		        		<input name="password" type="password" class="element text medium" id="password" value="{pass}" size="30" maxlength="12" tabindex="2"/>
	          	</span>
				</td>
	       </tr>
		    <tr>
		      <td width="100px" align="left">
					<span class="items_procesos"> 
						<label class="description" for="recordarme">remember me</label>
	          	</span><br>
		       	<input type="checkbox" name="recordarme" value="recordarme"> 
				</td>
		      <td align="left">
					<div style="width: 580px;float: right;"><li class="item_proceso_btn"><input type="submit" value="login" tabindex="4"/></li></div>
				</td>
	        </tr>
	        <tr>
		      <td align="left" colspan="2" valign="middle">
					<div class="olvide_clave" style="width: 200px;float: left;"><div class="flecha"><a href="/app/public_recuperar.php">forgot my password/username<img src="/wp-content/themes/mandaseguro/img/flecha.png" width="10" height="10" border="0"></a></div></div>
				</td>
	        </tr>
	      </table>
        </ul>
		</form>	
	</div>
</section><!--end secciones -->

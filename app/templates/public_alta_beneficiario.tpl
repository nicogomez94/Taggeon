<section class="contenedor_procesos registrese">
	<div id="form_container">
		<h1 id="paso1" class="titulo_procesos titulo_activo" style="z-index:10;background-color: #5F5F5F;">registrate y crea tu cuenta de beneficiario</h1>
		<form id="form_465818" class="appnitro"  method="post" action="public_alta_beneficiario.php">
			<div class="form_description">
				<h2 class="descripcion_procesos" style="left:50px;">completa el formulario<font color="red"  class="error" style="left:10px; top:0px;">{error}</font>
            </h2>
				<font color="red"  class="error">* campos obligatorios</font>
			</div>						
        
			<ul class="cuestionario">
				<div class="cuest_float">
            	<li id="li_5" class="items_procesos" >
               	<span class="campo_obligatorio">
                  	<label class="description" for="nombre">*</label>
                  </span>
                  <label class="description" for="nombre">nombre</label>
                  <div>
							<input name="nombre" type="text" class="element text medium" id="nombre" value="{nombre}" size="40" maxlength="255"/>
						</div> 
               </li>		
               <li id="li_8"  class="items_procesos" >
               	<label class="description" for="apellido"> <span class="campo_obligatorio"> *</span>apellido</label>
                  <div>
							<input name="apellido" type="text" class="element text medium" id="apellido" value="{apellido}" size="40" maxlength="255"/>
						</div> 
               </li>
               <li id="li_8"  class="items_procesos">
               	<label class="description" for="email"> <span class="campo_obligatorio"> *</span>email</label>
               	<div>
                  	<input name="email" type="text" class="element text medium" id="email" value="{email}" size="40" maxlength="255"/> 
                  </div> 
               </li>
               <li id="li_5"  class="items_procesos">
               	<label class="description" for="usuario"> <span class="campo_obligatorio"> *</span>usuario</label>
                  <div>
                  	<input name="usuario" type="text" class="element text medium" id="usuario" value="{usuario}" size="40" maxlength="255"/> 
                  </div> 
               </li>		
               <li id="li_3"  class="items_procesos pass">
               	<label class="description" for="pass"><span class="items_procesos"> <span class="campo_obligatorio"> *</span></span>pass</label>
                  <div>
                  	<input id="pass" name="pass" class="element text medium" type="password" maxlength="255" value="{password}"/> 
                  </div> 
               </li>
               <li id="li_3"  class="items_procesos pass">
               	<label class="description" for="pass2"><span class="items_procesos"> <span class="campo_obligatorio"> *</span></span>confirmar pass</label>
                  <div>
                  	<input id="pass2" name="pass2" class="element text medium" type="password" maxlength="255" value="{confirmarPassword}"/> 
                  </div> 
               </li>
            </div>		
        		<div class="cuest_float2">
            	<li id="li_10"  class="items_procesos pass" style="clear:both;" >
               	<label class="description" for="element_10"><span class="campo_obligatorio">*</span>país</label>
               	<div>
                  	<select class="element select medium" id="element_7_6" name="pais" style="width:180px;"> 
                     	{pais}
                     </select>
                  </div> 
              	</li>		
              
					<li id="li_11"  class="items_procesos pass">
               	<label class="description" for="element_11"><span class="campo_obligatorio">*</span>ciudad</label>
                  <div>
                  	<input id="element_11" name="ciudad" class="element text medium" type="text" maxlength="255" value="{ciudad}"/> 
                  </div> 
               </li>
                    
               <li id="li_11"  class="items_procesos pass">
               	<label class="description" for="element_13"><span class="campo_obligatorio">*</span>e-mail</label>
                  <div>
                  	<input id="element_13" name="email" class="element text medium" type="text" maxlength="255" value="{email}"/>
                  </div> 
               </li>
                    
            	<li id="li_3"  class="items_procesos"  style="clear:both;">
               	<label class="description" for="element_3">tel. contacto 1</label>
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
            	</li>	
            	<li id="li_7"  class="items_procesos"  style="clear:both;">
               	<label class="seccion_formulario" for="element_7">dirección</label>
                  <div >
                  	<label for="element_7_1">linea 1</label>
                     <input id="element_7_1" name="calle1" class="element text large" value="{calle1}" type="text">
                     <label for="element_7_8">linea 2</label>
                     <input id="element_7_2" name="calle2" class="element text large" value="{calle2}" type="text">
                     <span class="left"><span class="campo_obligatorio">*</span>ciudad</span><span class="left">
                     <input id="element_7_7" name="element_7_3" class="element text medium" value="{ciudad2}" type="text">
                     </span>
						</div>
                  <div class="right">
                  	<label for="element_7_4">estado</label>
                     <input id="element_7_4" name="element_7_" class="element text medium" value="{estado}" type="text">
                     <span class="left">
                        <label for="element_7_10">código postal</label>
                     </span>
							<span class="left">
                      	<input id="element_7_5" name="element_7_2" class="element text medium" maxlength="15" value="{codigoPostal}" type="text">
                     </span>
                     <label for="element_7_2"><span class="campo_obligatorio">*</span>país</label>
                     <select class="element select medium" id="pais2" name="pais2" style="width:150px;">
                     	{pais2}
                     </select>
                  </div>
                  <div></div>
               </li>
               
					<li class="items_procesos pass">
						<img id="captcha" src="/app/imagenes/ui-anim_basic_16x16.gif" alt="CAPTCHA Image"  class="item_captcha"/>
						<input type="text" name="captcha_code" id="captcha_code" size="10" maxlength="4"  class="item_captcha"  style="position:relative; top:-10px;"/>
						<a href="#" onclick="document.getElementById('captcha').src = '{path}captcha/securimage_show.php?' + Math.random(); return false"><img width="32" height="32" border="0" align="bottom" onclick="this.blur()" alt="Reload Image" src="{path}captcha/images/refresh.png"  class="item_captcha"></a>
					</li>
        
					<li class="item_proceso_btn" >
			    		<input type="hidden" name="accion" value="guardar" />
						<input id="saveForm" class="button_text" type="submit" name="submit" value="continuar" style="float:left;"/>
                	<div style=" top:20px; left:10px; font-size:11px; position:relative; float:left;"><a href="#">*Politicas de privacidad</a></div>
               </li>
          	</div>
 			</ul>
		</form>	
	</div>
</section><!--end secciones -->

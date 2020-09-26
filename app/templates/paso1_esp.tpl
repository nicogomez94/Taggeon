 <section class="contenedor_procesos" style=" background-image:url(/wp-content/themes/mandaseguro/img/bg_proceso_large-02.png);height: auto !important;display: inline-block;left: 35px;position: relative;" >
	<div id="form_container">

		<h1 id="paso1" class="titulo_procesos titulo_activo" style="z-index:10;background-color: #5F5F5F;"><a href="#beneficiario" class="proceso">1.  indicar beneficiario</a></h1>
<h1 id="paso2" class="titulo_procesos" style="z-index:9; background-color:#333;"><a href="#montos" class="proceso">2. indica monto y comercios</a></h1>
<h1 id="paso3" class="titulo_procesos" style="z-index:8; background-color: #666;"><a href="#resumen" class="proceso">3.resumen operación y pago</a></h1>
		
        <!--indica beneficiario -->
		<div id="beneficiario" class="proceso_contenidos" >
      	<div class="form_description" style="top: -20px; position: relative;">
				<h2 class="descripcion_procesos" style="padding: 10px 10px 0px 10px;width: 810px;margin: -20px;">
        <span style="font-weight: 700;color: #686464;font-size: 15px;">con MandaSeguro.com puedes enviar MandaChecks a quien quieras en tu país de origen</span><br />
		<div style="font-weight: 400;color: #686464;font-size: 14px; margin:10px 0px 0px 30px;">- para agregar un nuevo beneficiario, completa los campos vacíos y haz click en el botón "nuevo beneficiario", sus datos quedarán registrados en tu libreta de beneficiarios para próximos envíos.<br />
                - para indicar a quien quieres ayudar hoy, primero selecciona el beneficiario de tu libreta y luego haz click en el botón "continuar"<br />
                - si necesitas modificar los datos de un beneficiario, primero seleccionalo y luego haz click en "modificar"</div>
        <div style="height:30px;"><font color="red" style="left: 30px !important;"><div id="error_paso1" align="center">{error}</div></font></div>
        </h2>
			</div>
       	<ul class="cuestionario" style="top:-10px;position: relative;">        
        <!--seleccionador de usuarios-->
        <div id="buscar_beneficiario" class="cuest_float" style="left:-20px;">
        <div id="li_1" class="items_procesos pass" style="width:200px;">
        <span class="seccion_formulario" style="font-size:12px;">lista de beneficiario</span>
        <div id="beneficiarios_registrados"></div>
        </div>
        <!-- end seleccionador de usuarios-->
        <!--formulario-->           
         <div id="li_3"  class="items_procesos pass" style="width:620px; top:5px;">
           <table width="600" border="0" cellspacing="0" cellpadding="0">
  <tr valign="middle">
    <td height="40" colspan="3" class="campo_obligatorio">importante: los nombres de los beneficiarios deben coincidir exactamente con los nombres ID que aparecen en tu lista de beneficiarios.<br />*campos obligatorios</td>
  </tr>
  <tr valign="middle">
    <td height="20"><span class="campo_obligatorio">*</span>nombre</td>
    <td height="20"><span class="campo_obligatorio">*</span>apellido</td>
    <td height="20">&nbsp;</td>
  </tr>
  <tr valign="middle">
    <td height="30"><input id="nombre" name="nombre" class="element text medium" type="text" maxlength="255" value="" disabled="disabled"/></td>
    <td height="30"><input id="apellido" name="apellido" class="element text medium" type="text" maxlength="255" value="" disabled="disabled"/></td>
    <td height="30">&nbsp;</td>
  </tr>
  <tr valign="middle">
    <td height="20"><span class="campo_obligatorio">*</span>país</td>
    <td height="20"><span class="campo_obligatorio">*</span>ciudad</td>
    <td height="20"><span class="campo_obligatorio">*</span>estado</td>
  </tr>
  <tr valign="middle">
    <td height="30"><select id="pais" class="element select medium" name="pais" style="width:150px;" disabled="disabled" onchange="setCiudadByPais('div_ciudad',this.value,'','');setCodPaisTel('div_tel_cod_pais',this.value,'','');">
					{pais}
               </select></td>
    <td height="30"><div id="div_ciudad"><select id="ciudad" class="element select medium" name="ciudad" style="width:150px;" disabled="disabled"  onchange="setProvinciaByPaisAndCiudad('div_pcia',document.getElementById('pais').value,this.value,'','');">
               </select></div></td>
    <td height="30"><div id="div_pcia"><select id="provincia" class="element select medium" name="provincia" style="width:150px;" disabled="disabled">
               </select></div></td>
  </tr>
  <tr valign="middle">
    <td height="20">e-mail</td>
    <td height="20" colspan="2"><span class="campo_obligatorio">*</span>teléfono celular<br />
      <span class="campo_obligatorio">(asegúrese que el teléfono celular es correcto.)</span></td>
  </tr>
  <tr valign="middle">
    <td height="30"><input id="email" name="email" class="element text medium" type="text" maxlength="255" value="" disabled="disabled"/></td>
    <td height="30" colspan="2">
    <span style="float:left; margin-right:10px;">código del país</span>
    <div id="div_tel_cod_pais" style="float: left; margin-right:10px;"><select id="telefono1Pais" class="element select medium" name="telefono1Pais" style="width:80px;" disabled="disabled"></select></div>
    <span style="float:left; margin-right:10px;">número</span>
    <input id="telefono1" name="telefono1" class="element text" size="9" maxlength="7" type="text" style="width:80px;" disabled="disabled"/></td>
  </tr>
   <tr valign="middle">
    <td height="auto" colspan="3"> <!--captcha--> 
<div id="captchaVista" style="visibility:hidden;">
  
        <li class="items_procesos pass">
		<div style="float: left;"><img id="captcha" src="/app/imagenes/ui-anim_basic_16x16.gif" alt="CAPTCHA Image"/></div>
		<div style="float: left;height: 80px;">
        <input type="text" id="captcha_code" name="captcha_code" size="10" maxlength="4" style="position: absolute; bottom: 0px;width: 120px;margin-left: 10px;" disabled="disabled">
        <a href="#captcha_code" onclick="setCaptcha(); return false;" style="position: absolute;bottom: 0px;left: 360px;">
        <img width="20" height="20" border="0" align="bottom" onclick="this.blur();" alt="Reload Image" src="/app/captcha/images/refresh.png">
        </a>
        </div>		
		</li>
        
        
        
</div>
<script type="text/javascript">
setCaptcha();
</script>

         <!--end captcha--></td>
    </tr>
    <tr valign="middle">
    <td height="30" colspan="3">  <!--botones-->
         <li class="item_proceso_btn" style="clear:both;" >

<input type="hidden" name="idUsuario" id="idUsuario" value="" />
<input id="nuevo" class="button_text btn_info boton_proceso_subcolor" type="button" name="nuevo" value="nuevo beneficiario" style="float:left;margin-left:5px;" onclick="newBeneficiario();"/>
<input id="modificar" class="button_text btn_info boton_proceso_subcolor" type="button" name="modificar" value="modificar" style="float:left;margin-left:5px;" onclick="modificarBeneficiario();"/>
<input id="eliminar" class="button_text btn_info boton_proceso_subcolor" type="button" name="eliminar" value="eliminar" style="float:left;" onclick="eliminarBeneficiario();"/>
<input id="seleccionar" class="button_text btn_info" type="button" name="seleccionar" value="continuar" style="float: right;margin-right:20px;" onclick="irPaso2();"/>

<input id="aceptar_new" class="button_text btn_info" type="button" name="aceptar_new" value="guardar nuevo beneficiario" style="position:absolute;visibility:hidden;float:left;margin-left:5px;" onclick="altaBeneficiarioByCliente();"/>
<input id="cancelar_new" class="button_text btn_info" type="button" name="cancelar_new" value="cancelar" style="position:absolute;visibility:hidden;float:left;" onclick="defaultBotonesPaso1();"/>

<input id="aceptar_modificar" class="button_text btn_info" type="button" name="aceptar_modificar" value="aceptar" style="position:absolute;visibility:hidden;float:left;margin-left:5px;" onclick="modificarBeneficiarioByCliente();"/>
<input id="cancelar_modificar" class="button_text btn_info" type="button" name="cancelar_modificar" value="cancelar" style="position:absolute;visibility:hidden;float:left;" onclick="cancelarModBenefPaso1();"/>

<input id="aceptar_eliminar" class="button_text btn_info" type="button" name="aceptar_eliminar" value="aceptar" style="position:absolute;visibility:hidden;float:left;margin-left:5px;" onclick="eliminarBeneficiarioByCliente();"/>
<input id="cancelar_eliminar" class="button_text btn_info" type="button" name="cancelar_eliminar" value="cancelar" style="position:absolute;visibility:hidden;float:left;" onclick="cancelarEliminarBenefPaso1();"/>

         </li>
         <!--end botones--></td>
    </tr>
</table>
		
          
         
         
         </div>
         <!--end formulario-->         
         
         <div style="left:0px; font-size:11px; position:relative; float:left;"><a href="/pop_legales/index.html" class="click" style="text-decoration:underline;">*terminos y condiciones</a></div>	
         
                     
        </div>	
        </ul></div>
        <!--end indica beneficiario -->


        <!--montos -->        
        <div id="montos" class="proceso_contenidos" style="display:none; height:450px;">
		  <div id="procesar_paso2"></div>
        <div style="width:800px; height:200px; clear:both; font-size:14px; color: #686464;margin:10px 0px 0px 30px; top: 10px;position: relative;">
        <p style="font-weight:700;">1. ¿dónde quieres que se utilicen los MandaChecks?</p>
        
        <p>en MandaSeguro.com sabemos que quieres ayudar a tu familia a satisfacer sus necesidades más importantes,  <br />
        por eso incorporamos comercios que ofrecen una excelente variedad y calidad de productos en categorías claves.</p>
        
        <p>selecciona en que tipo de comercio preferirías que tu beneficiario disponga de sus MandaChecks <br />y luego selecciona la tienda que creas le resulte más conveniente.</p>
        
       
        <div id="conten_btnes_comercios">
        
         <a href="#supermercados" class="btn_com" style="background-image:url(/wp-content/themes/mandaseguro/img_esp/btn_monto_ov-03.png);" id="1"></a>
         <a href="#farmacias" class="btn_com" style="background-image:url(/wp-content/themes/mandaseguro/img_esp/btn_monto_ov-04.png);" id="2"></a>
         <a href="#ferreterias" class="btn_com" style="background-image:url(/wp-content/themes/mandaseguro/img_esp/btn_monto_ov-05.png);" id="3"></a>
         <div id="seleccionador"></div>
        </div>
		</div>
        
        
        <div class="form_description" style="top: -10px; position: relative;">
			<h2 class="descripcion_procesos" style="position:relative; float:left; font-weight:700; color:#686464;">2. ahora indica cuanto dinero quieres enviar</h2>
        </div>		
 		<div class="cuest_float" style="position: relative;left: 50px;padding: 7px;">
                    
                   
                   <div style="position:relative; float:left; margin:0px 20px 0px 0px;"> 
                    <li class="items_procesos pass">RD$</li>
                    <div style="clear:both; z-index:1; position:relative;"><input name="inputMonedaLocal" id="inputMonedaLocal" type="text" onchange="changeMonedaDolares();"  style="width: 100px;"/></div> 
                   </div> 
                   
                   <div style="position:relative; float:left; margin:0px 20px 0px 0px;"> 
                    <li class="items_procesos pass">tipo de cambio </li>
						  <div style="clear:both; font-size:14px; color:#333;top:5px; position:relative;" id="divTipoCambio"></div>
                   </div> 
                   
						<div style="position:relative; float:left; margin:0px 20px 0px 0px;"> 
                    <li class="items_procesos pass">dólares</li>
                    <div style="clear:both; z-index:1; position:relative;"><input name="inputMonedaDolares" id="inputMonedaDolares" type="text" onchange="changeMonedaLocal();" style="width: 100px;"/></div> 
                    <input type="hidden" name="inputTipoCambio" id="inputTipoCambio"/>
						</div>

			   <div class="item_proceso_btn" style="position:relative; float:left; margin:0px 20px 0px 0px;">
					<input id="continuar3" class="button_text" type="button" name="continuar3" value="continuar" onclick="irPaso3();"/></div> 
        
        </div>        
        <div style="width:800px; clear:both; font-size:14px; color: #686464;margin:10px 0px 0px 30px; top: 10px;position: relative;">
        <p style="font-weight:700;margin-bottom: 0px;">puedes poner el monto en dólares ($USD) o en pesos dominicanos (RD$). el monto en RD$ debe ser múltiplo de 100.
</p>
        <p style="margin-top: 0px;">recuerda que el envío no tiene ningún gasto adicional.<br />
        el tipo de cambio es establecido por el comercio seleccionado.</p></div>
       
       
        
        
        
        
        </div>
        <!--end montos-->
<script type="text/javascript">
setMoneda();
</script>
        

        <!--resumen-->        
        <div id="resumen" class="proceso_contenidos" style="display: none; border:dashed 1px #333; top:10px; position:relative; width:750px;-webkit-border-radius: 10px;-moz-border-radius: 10px;border-radius: 10px; height:450px; margin-bottom:30px;">

        <div class="form_description" style="top: -20px; position: relative;">
		  <font color="red" style="left:10px; top:0px; margin-top: 0px; margin-bottom: 0px; margin-left: 80px;"><div id="error_confirmar" align="center">{error}</div></font>
		<h2 class="descripcion_procesos" style="font-weight:700; color:#686464;margin-top: -10px;"><span style="color:#FF8D1C;">listo! </span> este es el resumen de la operación.</h2>
        </div>						
		<div class="cuestionario items_procesos" style="top: 0px;position: relative; left:20px; font-size:14px;">
		  <div id="div_resumen">       
          <table width="446" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="240">estaremos enviando a:</td>
              <td width="206">xxx</td>
            </tr>
            <tr>
              <td>MandaChecks por valor de RD$ :</td>
              <td>xxx pesos dominicanos</td>
            </tr>
            <tr>
              <td>para ser utilizados en la tienda :</td>
              <td>xxx</td>
            </tr>
            <tr>
              <td>equivalentes a:</td>
              <td>xxx dólares</td>
            </tr>
            <tr>
              <td colspan="2" align="left"><p style="font-size:12px;">esta transacción no tiene ningún otro gasto adicional. </p></td>
            </tr>
          </table>
		  </div>
         <div class="item_proceso_btn" style="position: relative;top: -20px;">
			
            <input id="saveForm" class="button_text boton_proceso_subcolor" type="button" name="modificar" value="modificar" style="clear:both;" onclick="irPaso2();"/>            <input id="saveForm" class="button_text" type="button" name="continuar4" value="pagar" onclick="irPaso4Confirmar();"/>   
             </div>
          
          <div style="position: relative;float: right;right: 80px;margin-left: 20px;bottom: -110px;">
          <!-- (c) 2005, 2013. Authorize.Net is a registered trademark of CyberSource Corporation --> <div class="AuthorizeNetSeal"> <script type="text/javascript" language="javascript">var ANS_customer_id="e5875fbf-ae6e-4365-8a30-1e12ee690693";</script> <script type="text/javascript" language="javascript" src="//verify.authorize.net/anetseal/seal.js" ></script> <a href="http://www.authorize.net/" id="AuthorizeNetText" target="_blank" style="color: #999 !important;">Procesador de pago</a> </div>
          </div>
         <div id="resumenForm" style="border:dashed 1px #FF8D1C; font-size:13px; top:10px; background-color:#FFF; position:relative; width:400px; padding:10px;-webkit-border-radius: 10px;-moz-border-radius: 10px;border-radius: 10px;  float:left; visibility: hidden; margin: -10px auto;"> 
           <table width="415" border="0" cellspacing="0" cellpadding="0">
          <tr>
				<td colspan="2"><div id="resumenFormTitulo"></div><span style="font-size:12px; font-style:italic; color:#F60; top: 5px; position: relative; "> opcional</span></td>
           </tr>
          <tr>
            <td><p>mensaje:</p></td>
            <td><label for="textarea"></label>
            <textarea name="resumenMensaje" id="resumenMensaje" cols="45" rows="5" style="width:260px;" onKeyUp="return maximaLongitud(this,500)"></textarea></td>
          </tr>
        </table>       
        <input type="hidden" name="hiddenvalidTransaccion" id="hiddenvalidTransaccion"/>
        <input type="hidden" name="hiddenidCliente" id="hiddenidCliente"/>
        <input type="hidden" name="hiddenidDetalle" id="hiddenidDetalle"/>
        <input type="hidden" name="hiddenidBeneficiario" id="hiddenidBeneficiario"/>
        <input type="hidden" name="hiddenidRetailer" id="hiddenidRetailer"/>
        <input type="hidden" name="hiddenmonto" id="hiddenmonto"/>
        <input type="hidden" name="resumenDe" id="resumenDe"/>
        <input type="hidden" name="resumenPara" id="resumenPara"/>
		</div> 
        
         </div>
        </div>
        <!--end resumen-->

        <!--pago-->
        <div id="pago" class="proceso_contenidos" style="display: none; border:dashed 1px #333; top:10px; position:relative; width:750px;-webkit-border-radius: 10px;-moz-border-radius: 10px;border-radius: 10px; height:250px; margin-bottom:30px;">
        <div class="form_description" style="top: -10px; position: relative;">
			<h2 class="descripcion_procesos"><font color="red" style="left:10px; top:0px;">{error}</font></h2>
	  	  </div>						
		  <div class="cuestionario items_procesos" style="top: 0px;position: relative; font-size:14px; text-align:center">
			  <p style="font-weight:700;"><span style="color:#FF8D1C;">gracias</span> por escoger MandaSeguro.com</p>
			  <p>si deseas hacer otra transacción, puede empezar aquí</p>
			  <li class="item_proceso_btn" >
					<input id="saveForm" class="button_text" type="button" name="otra_trans" value="hacer otra transacción" style="margin-top: 0px;" onclick="enviarDinero();"/>
			  </li>
			  <p>de lo contrario, esperamos verte pronto en MandaSeguro.com</p>
			  <li class="item_proceso_btn" >
					<input id="saveForm" class="button_text" type="button" name="logout" value="salir" style="margin-top: 0px;" onclick="document.location.href= '{hostActual}/app/logout.php';"/>
			  </li>
		  </div>
	     </div>
        <!--end pago-->


	</div>
</section><!--end secciones -->

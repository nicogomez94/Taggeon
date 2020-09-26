 <section class="contenedor_procesos" style=" background-image:url(/wp-content/themes/mandaseguro/img/bg_proceso_large-02.png);height: auto !important;display: inline-block;left: 35px;position: relative;" >
	<div id="form_container">

		<h1 id="paso1" class="titulo_procesos titulo_activo" style="z-index:10;background-color: #5F5F5F;"><a href="#beneficiario" class="proceso">1.  indicate beneficiary</a></h1>
<h1 id="paso2" class="titulo_procesos" style="z-index:9; background-color:#333;"><a href="#montos" class="proceso">2. determine amount and retailer</a></h1>
<h1 id="paso3" class="titulo_procesos" style="z-index:8; background-color: #666;"><a href="#resumen" class="proceso">3.summary of payment</a></h1>
		
        <!--indica beneficiario -->
		<div id="beneficiario" class="proceso_contenidos" >
      	<div class="form_description" style="top: -20px; position: relative;">
				<h2 class="descripcion_procesos" style="padding: 10px 10px 0px 10px;width: 810px;margin: -20px;">
        <span style="font-weight: 700;color: #686464;font-size: 15px;">with MandaSeguro.com you can send MandaChecks to your loved ones in their home country</span><br />
		<div style="font-weight: 400;color: #686464;font-size: 14px; margin:10px 0px 0px 30px;">- to add a new beneficiary, fill in the form below and click "new beneficiary", his/her data will be registered on your beneficiaries list for future purchases.<br />
                - to indicate who to help today, select a beneficiary from your list and click "select"<br />
                - if you need to modify your beneficiaries' information, first select the beneficiary and then click "modify"</div>
        <div style="height:30px;"><font color="red" style="left: 30px !important;"><div id="error_paso1" align="center">{error}</div></font></div>
        </h2>
			</div>
       	<ul class="cuestionario" style="top:-10px;position: relative;">        
        <!--seleccionador de usuarios-->
        <div id="buscar_beneficiario" class="cuest_float" style="left:-20px;">
        <div id="li_1" class="items_procesos pass" style="width:200px;">
        <span class="seccion_formulario" style="font-size:12px;">registered beneficiaries list</span>
        <div id="beneficiarios_registrados"></div>
        </div>
        <!-- end seleccionador de usuarios-->
        <!--formulario-->           
         <div id="li_3"  class="items_procesos pass" style="width:620px; top:5px;">
           <table width="600" border="0" cellspacing="0" cellpadding="0">
  <tr valign="middle">
    <td height="40" colspan="3" class="campo_obligatorio">important: the names of the beneficiaries must exactly match the names as they appear on your beneficiaries' ID.<br />*required fields</td>
  </tr>
  <tr valign="middle">
    <td height="20"><span class="campo_obligatorio">*</span>first name</td>
    <td height="20"><span class="campo_obligatorio">*</span>last name</td>
    <td height="20">&nbsp;</td>
  </tr>
  <tr valign="middle">
    <td height="30"><input id="nombre" name="nombre" class="element text medium" type="text" maxlength="255" value="" disabled="disabled"/></td>
    <td height="30"><input id="apellido" name="apellido" class="element text medium" type="text" maxlength="255" value="" disabled="disabled"/></td>
    <td height="30">&nbsp;</td>
  </tr>
  <tr valign="middle">
    <td height="20"><span class="campo_obligatorio">*</span>country</td>
    <td height="20"><span class="campo_obligatorio">*</span>city</td>
    <td height="20"><span class="campo_obligatorio">*</span>state</td>
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
    <td height="20" colspan="2"><span class="campo_obligatorio">*</span>cel. phone<br />
      <span class="campo_obligatorio">(please verify that the beneficiary's cel phone you are providing is correct.)</span></td>
  </tr>
  <tr valign="middle">
    <td height="30"><input id="email" name="email" class="element text medium" type="text" maxlength="255" value="" disabled="disabled"/></td>
    <td height="30" colspan="2">
    <span style="float:left; margin-right:10px;">country code</span>
    <div id="div_tel_cod_pais" style="float: left; margin-right:10px;"><select id="telefono1Pais" class="element select medium" name="telefono1Pais" style="width:80px;" disabled="disabled"></select></div>
    <span style="float:left; margin-right:10px;">number</span>
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
<input id="nuevo" class="button_text btn_info boton_proceso_subcolor" type="button" name="nuevo" value="new beneficiary" style="float:left;margin-left:5px;" onclick="newBeneficiario();"/>
<input id="modificar" class="button_text btn_info boton_proceso_subcolor" type="button" name="modificar" value="modify" style="float:left;margin-left:5px;" onclick="modificarBeneficiario();"/>
<input id="eliminar" class="button_text btn_info boton_proceso_subcolor" type="button" name="eliminar" value="remove" style="float:left;" onclick="eliminarBeneficiario();"/>
<input id="seleccionar" class="button_text btn_info" type="button" name="seleccionar" value="continue" style="float: right;margin-right:20px;" onclick="irPaso2();"/>

<input id="aceptar_new" class="button_text btn_info" type="button" name="aceptar_new" value="save new beneficiary" style="position:absolute;visibility:hidden;float:left;margin-left:5px;" onclick="altaBeneficiarioByCliente();"/>
<input id="cancelar_new" class="button_text btn_info" type="button" name="cancelar_new" value="cancel" style="position:absolute;visibility:hidden;float:left;" onclick="defaultBotonesPaso1();"/>

<input id="aceptar_modificar" class="button_text btn_info" type="button" name="aceptar_modificar" value="accept" style="position:absolute;visibility:hidden;float:left;margin-left:5px;" onclick="modificarBeneficiarioByCliente();"/>
<input id="cancelar_modificar" class="button_text btn_info" type="button" name="cancelar_modificar" value="cancel" style="position:absolute;visibility:hidden;float:left;" onclick="cancelarModBenefPaso1();"/>

<input id="aceptar_eliminar" class="button_text btn_info" type="button" name="aceptar_eliminar" value="accept" style="position:absolute;visibility:hidden;float:left;margin-left:5px;" onclick="eliminarBeneficiarioByCliente();"/>
<input id="cancelar_eliminar" class="button_text btn_info" type="button" name="cancelar_eliminar" value="cancel" style="position:absolute;visibility:hidden;float:left;" onclick="cancelarEliminarBenefPaso1();"/>

         </li>
         <!--end botones--></td>
    </tr>
</table>
		
          
         
          <div id="popup_btn_nuevo" class="popup_btn"><div class="texto_pop_btn">adds a new user</div></div>    
          <div id="popup_btn_seleccionar" class="popup_btn">
            <div class="texto_pop_btn">select the user you want to send money</div></div>    
          <div id="popup_btn_modificar" class="popup_btn"><div class="texto_pop_btn">modifies the data of one of your beneficiaries</div></div>    
          <div id="popup_btn_eliminar" class="popup_btn"><div class="texto_pop_btn" style="top: 0px;">are you sure you want to delete a "beneficiary" of your book?</div></div>    
       
         
         </div>
         <!--end formulario-->         
         
         <div style="left:0px; font-size:11px; position:relative; float:left;"><a href="/pop_legales/index.html" class="click" style="text-decoration:underline;">*terms and conditions</a></div>	
         
                     
        </div>	
        </ul></div>
        <!--end indica beneficiario -->


        <!--montos -->        
        <div id="montos" class="proceso_contenidos" style="display:none; height:450px;">
		  <div id="procesar_paso2"></div>
        <div style="width:800px; height:200px; clear:both; font-size:14px; color: #686464;margin:10px 0px 0px 30px; top: 10px;position: relative;">
        <p style="font-weight:700;">1. where would you like the MandaChecks to be used?</p>
        
        <p>MandaSeguro.com knows that you want to help your family and friends in their basic and most important needs. <br />
        that's the reason why we have a broad network of retailers that offers excellent quality and variety of products in key categories. </p>
        
        <p>just select  the retailer of your choice where you would like your family or friends to use the MandaChecks by clicking on <br />
          one of the buttons below. then choose the retail store of your convenience from the box on the right.<br /></p>
        
       
        <div id="conten_btnes_comercios">
        
         <a href="#supermercados" class="btn_com" style="background-image:url(/wp-content/themes/mandaseguro/img/btn_monto_ov-03.png);" id="1"></a>
         <a href="#farmacias" class="btn_com" style="background-image:url(/wp-content/themes/mandaseguro/img/btn_monto_ov-04.png);" id="2"></a>
         <a href="#ferreterias" class="btn_com" style="background-image:url(/wp-content/themes/mandaseguro/img/btn_monto_ov-05.png);" id="3"></a>
         <div id="seleccionador"></div>
        </div>
		</div>
        
        
        <div class="form_description" style="top: -10px; position: relative;">
			<h2 class="descripcion_procesos" style="position:relative; float:left; font-weight:700; color:#686464;">2. indicate the face value of the MandaCheck</h2>
        </div>		
 		<div class="cuest_float" style="position: relative;left: 50px;padding: 7px;">
                    
                   
                   <div style="position:relative; float:left; margin:0px 20px 0px 0px;"> 
                    <li class="items_procesos pass">RD$</li>
                    <div style="clear:both; z-index:1; position:relative;"><input name="inputMonedaLocal" id="inputMonedaLocal" type="text" onchange="changeMonedaDolares();"  style="width: 100px;"/></div> 
                   </div> 
                   
                   <div style="position:relative; float:left; margin:0px 20px 0px 0px;"> 
                    <li class="items_procesos pass">exchange rate </li>
						  <div style="clear:both; font-size:14px; color:#333;top:5px; position:relative;" id="divTipoCambio"></div>
                   </div> 
                   
						<div style="position:relative; float:left; margin:0px 20px 0px 0px;"> 
                    <li class="items_procesos pass">us dollars</li>
                    <div style="clear:both; z-index:1; position:relative;"><input name="inputMonedaDolares" id="inputMonedaDolares" type="text" onchange="changeMonedaLocal();" style="width: 100px;"/></div> 
                    <input type="hidden" name="inputTipoCambio" id="inputTipoCambio"/>
						</div>

			   <div class="item_proceso_btn" style="position:relative; float:left; margin:0px 20px 0px 0px;">
					<input id="continuar3" class="button_text" type="button" name="continuar3" value="continue" onclick="irPaso3();"/></div> 
        
        </div>        
        <div style="width:800px; clear:both; font-size:14px; color: #686464;margin:10px 0px 0px 30px; top: 10px;position: relative;">
        <p style="font-weight:700;margin-bottom: 0px;">you can set the amount in us dollars ($USD) or in dominican pesos (RD$). the amount in RD$ should be multiple of 100. 
</p>
        <p style="margin-top: 0px;">just remember that you do not have to pay extra fees or charges.<br />
        the exchange rate is determined by the retail store you have chosen.</p></div>
       
       
        
        
        
        
        </div>
        <!--end montos-->
<script type="text/javascript">
setMoneda();
</script>
        

        <!--resumen-->        
        <div id="resumen" class="proceso_contenidos" style="display: none; border:dashed 1px #333; top:10px; position:relative; width:750px;-webkit-border-radius: 10px;-moz-border-radius: 10px;border-radius: 10px; height:450px; margin-bottom:30px;">

        <div class="form_description" style="top: -20px; position: relative;">
		  <font color="red" style="left:10px; top:0px; margin-top: 0px; margin-bottom: 0px; margin-left: 80px;"><div id="error_confirmar" align="center">{error}</div></font>
		<h2 class="descripcion_procesos" style="font-weight:700; color:#686464;margin-top: -10px;"><span style="color:#FF8D1C;">done! </span> this is the summary of your purchase.</h2>
        </div>						
		<div class="cuestionario items_procesos" style="top: 0px;position: relative; left:20px; font-size:14px;">
		  <div id="div_resumen">       
          <table width="446" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="240">we will be sending :</td>
              <td width="206">xxx</td>
            </tr>
            <tr>
              <td>MandaChecks with a face value of RD$ :</td>
              <td>xxx dominican pesos</td>
            </tr>
            <tr>
              <td>to be redeemed at :</td>
              <td>xxx</td>
            </tr>
            <tr>
              <td>equivalent to the US$:</td>
              <td>xxx</td>
            </tr>
            <tr>
              <td colspan="2" align="left"><p style="font-size:12px;">this transaction has  no additional fees or charges. </p></td>
            </tr>
          </table>
		  </div>
         <div class="item_proceso_btn" style="position: relative;top: -20px;">
			
            <input id="saveForm" class="button_text boton_proceso_subcolor" type="button" name="modificar" value="modify" style="clear:both;" onclick="irPaso2();"/>            <input id="saveForm" class="button_text" type="button" name="continuar4" value="pay" onclick="irPaso4Confirmar();"/>   
             </div>
          
          <div style="position: relative;float: right;right: 80px;margin-left: 20px;bottom: -110px;">
          <!-- (c) 2005, 2013. Authorize.Net is a registered trademark of CyberSource Corporation --> <div class="AuthorizeNetSeal"> <script type="text/javascript" language="javascript">var ANS_customer_id="e5875fbf-ae6e-4365-8a30-1e12ee690693";</script> <script type="text/javascript" language="javascript" src="//verify.authorize.net/anetseal/seal.js" ></script> <a href="http://www.authorize.net/" id="AuthorizeNetText" target="_blank" style="color: #999 !important;">Payment Processing</a> </div>
          </div>
         <div id="resumenForm" style="border:dashed 1px #FF8D1C; font-size:13px; top:10px; background-color:#FFF; position:relative; width:400px; padding:10px;-webkit-border-radius: 10px;-moz-border-radius: 10px;border-radius: 10px;  float:left; visibility: hidden; margin: -10px auto;"> 
           <table width="415" border="0" cellspacing="0" cellpadding="0">
          <tr>
				<td colspan="2"><div id="resumenFormTitulo"></div><span style="font-size:12px; font-style:italic; color:#F60; top: 5px; position: relative; "> optional</span></td>
           </tr>
          <tr>
            <td><p>message:</p></td>
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
			  <p style="font-weight:700;"><span style="color:#FF8D1C;">thank you</span> for choosing MandaSeguro.com</p>
			  <p>if you wish to make another transaction, you can start right here</p>
			  <li class="item_proceso_btn" >
					<input id="saveForm" class="button_text" type="button" name="otra_trans" value="make another transaction" style="margin-top: 0px;" onclick="enviarDinero();"/>
			  </li>
			  <p>otherwise, we hope to see you again soon at MandaSeguro.com</p>
			  <li class="item_proceso_btn" >
					<input id="saveForm" class="button_text" type="button" name="logout" value="logout" style="margin-top: 0px;" onclick="document.location.href= '{hostActual}/app/logout.php';"/>
			  </li>
		  </div>
	     </div>
        <!--end pago-->


	</div>
</section><!--end secciones -->

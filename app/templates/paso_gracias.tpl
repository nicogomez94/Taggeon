 <section class="contenedor_procesos" style=" background-image:url(/wp-content/themes/mandaseguro/img/bg_proceso_large-02.png);height: auto !important;display: inline-block;left: 35px;position: relative;" >
	<div id="form_container">

		<h1 id="paso1" class="titulo_procesos titulo_activo" style="z-index:10;background-color: #5F5F5F;"><a href="#beneficiario" class="proceso">thank you for choosing MandaSeguro.com</a></h1>
		
        <!--pago-->
        <div id="pago" class="proceso_contenidos" style="border:dashed 1px #333; top:10px; position:relative; width:750px;-webkit-border-radius: 10px;-moz-border-radius: 10px;border-radius: 10px; height:250px; margin-bottom:30px;">
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

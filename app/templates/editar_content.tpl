<script type="text/javascript" src="/app/libreria/tinymce/tinymce.min.js"></script>
<script language="javascript" type="text/javascript">
	tinyMCE.init({
		force_br_newlines : true,
		force_p_newlines : false,
		forced_root_block : '',
		convert_urls : false,
		//mode  : "textareas",
		menubar : false,
		plugins: [
			 "code",
		   "advlist autolink lists link image charmap print preview anchor",
		   "searchreplace visualblocks code fullscreen",
		   "insertdatetime media table contextmenu paste moxiemanager",
		],
		toolbar: "code | insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist num"

   });
</script>
<section class="contenedor_procesos" style=" background-image: url(/wp-content/themes/mandaseguro/img/bg_proceso_large_blank-02.png);background-repeat:no-repeat;color:#686464; font-size:14px;height: auto;
">
<div id="form_container">
      <h1 id="paso1" class="titulo_procesos titulo_activo" style="z-index:10;background-color: #5F5F5F;">Edit {url}<h1>
		<form class="appnitro"  method="post" action="editar_content.php">
		<div class="form_description">
			<h2 class="descripcion_procesos" style="left:50px; font-weight:700; color:#686464;">&nbsp;
     </h2>
		</div>						
		<ul class="cuestionario" style=" margin-left:-40px;">
		{error}
		  <table width="795" border="0" align="center" cellpadding="0" cellspacing="2">
		    
			 <tr align="left">
		      <td width="100px"><span class="items_procesos"> 
		        <label class="description" for="title">title</label>
	         </td>
		      <td><span class="items_procesos">
		        <input name="title" type="text" class="element text medium" id="title" value="{title}" size="30" tabindex="1">
	          </span></td>
	        </tr>
		    <tr align="left">
		      <td width="100px"><span class="items_procesos"> 
		        <label class="description" for="html_ing">html (eng)</label>
	         </td>
		      <td><span class="items_procesos">
					<textarea name="html_ing" cols="90" rows="10" class="element text medium" id="html_ing" tabindex="2">{htmlIng}</textarea>
	          </span></td>
	        </tr>
			 
			 <tr align="left">
		      <td width="100px"><span class="items_procesos"> 
		        <label class="description" for="titulo">titulo</label>
	         </td>
		      <td><span class="items_procesos">
		        <input name="titulo" type="text" class="element text medium" id="titulo" value="{titulo}" size="30" tabindex="3">
	          </span></td>
	        </tr>
		    <tr align="left">
		      <td width="100px"><span class="items_procesos"> 
		        <label class="description" for="html_esp">html (esp)</label>
	         </td>
		      <td><span class="items_procesos">
					<textarea name="html_esp" cols="90" rows="10" class="element text medium" id="html_esp" tabindex="4">{htmlEsp}</textarea>
	          </span></td>
	        </tr>

	        <tr>
		      <td align="left" colspan="2" valign="middle">
					<div style="width:100%; margin:auto;">
		        		<li class="items_procesos pass"> 
							<li class="item_proceso_btn"  style="width:450px;" >
								 <input type="hidden" name="accion" value="guardar" />
								 <input type="hidden" name="id" value="{id}" />
								<input id="saveForm2" class="button_text" type="submit" name="saveForm" value="guardar" style="float:left;margin-top: 62px;" tabindex="4"/>
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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Nuevo Usuario</title>
<link rel="stylesheet" type="text/css" href="{path}css/view.css" media="all">
<script type="text/javascript" src="{path}libreria/view.js"></script>
</head>
<body id="main_body">
{bienvenida}
{menu}	
	
	<img id="top" src="{path}imagenes/top.png" alt="">
	<div id="form_container">
	
		<h1><a>Cliente</a></h1>
		<form id="form_465818" class="appnitro"  method="post" action="alta_usuario.php">
		<div class="form_description">
			<h2>Nuevo Usuario</h2>
			<font color="red">{error}</font>
		</div>						
		<ul >
		<li id="li_5" >
		<label class="description" for="element_5">Usuario</label>
		<div>
			<input id="element_5" name="usuario" class="element text medium" type="text" maxlength="255" value="{usuario}"/> 
		</div> 
		</li>
		<li id="li_5" >
		<label class="description" for="element_5">Password</label>
		<div>
			<input id="element_5" name="pass" class="element text medium" type="text" maxlength="255" value="{pass}"/> 
		</div> 
		</li>
		<li id="li_5" >
		<label class="description" for="element_5">Password2</label>
		<div>
			<input id="element_5" name="pass2" class="element text medium" type="text" maxlength="255" value="{pass2}"/> 
		</div> 
		</li>
		<li id="li_5" >
		<label class="description" for="element_5">Perfil</label>
		<div>
			<input id="element_5" name="perfil" class="element text medium" type="text" maxlength="255" value="{perfil}"/> 
		</div> 
		</li>
		<li class="buttons">
			    <input type="hidden" name="accion" value="guardar" />
				<input id="saveForm" class="button_text" type="submit" name="submit" value="Submit" />
		</li>
			</ul>
		</form>	

	</div>
	<img id="bottom" src="{path}imagenes/bottom.png" alt="">
</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
<title>{titulo}</title>
<meta name="description" content="{descripcion}">
<link rel="shortcut icon" href="/app/imagenes/icono_MS16x16.ico">
<script type="text/javascript" src="/wp-content/themes/mandaseguro/js/modernizr.custom.89456.js"></script>

<!--[if lt IE 7]>
<script src="/ie7/ie7-standard-p.js" type="text/javascript">
</script>
<![endif]-->
<!--[if lte IE 8]>
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700,900" /> 
<![endif]-->

<link href="/wp-content/themes/mandaseguro/style.css" rel="stylesheet" type="text/css">
<link rel='stylesheet' id='camera-css'  href='/wp-content/themes/mandaseguro/css/camera.css' type='text/css' media='all'> 
<link rel="stylesheet" type="text/css" href="/wp-content/themes/mandaseguro/css/skin_carousel.css" />
<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700,900' rel='stylesheet' type='text/css'>
<style type="text/css">
body,td,th {
	font-family: "Source Sans Pro", sans-serif;
}

</style>

<link type="text/css" href="{path}css/jquery-ui-1.8.23.custom.css" rel="stylesheet" />
<script type="text/javascript" src="/wp-content/themes/mandaseguro/js/jquery-1.8.1.js"></script>
<script type="text/javascript" src="/wp-content/themes/mandaseguro/js/jquery.easing.min.js"></script>
<script type='text/javascript' src='/wp-content/themes/mandaseguro/js/jquery.mobile.customized.min.js'></script>
<script type='text/javascript' src='/wp-content/themes/mandaseguro/js/camera.min.js'></script> 
<script type='text/javascript' src='/wp-content/themes/mandaseguro/js/animation.js'></script> 
<script type="text/javascript" src="/wp-content/themes/mandaseguro/js/jquery.jcarousel.min.js"></script>  
<script type="text/javascript" src="{path}libreria/view.js"></script>
<script type="text/javascript" src="{path}libreria/javascript-acordeon.js"></script>
<link href="{path}css/estilo.css" rel="stylesheet" type="text/css">
<link href="{path}css/estilos-filtro.css" rel="stylesheet" type="text/css">
<link href="{path}css/format-acordeon.css" rel="stylesheet" type="text/css">


<script>
	{scriptHeader}	

	var perfil = "{perfilactual}";
	var hostActual = '{hostActual}';
	var languageActual = '{languageActual}';

	function registrarUsuario (){
		if (perfil == 'cliente'){
			$(document).ready(function(){
				var url='{hostActual}/errores/pop_reg_usr_cliente.html';
				$('#contenido_popup').load(url,function() {$('.contenedor_pop_ups').fadeIn(400);});
				return false;
		
			});						
		}else if (perfil == 'beneficiario'){
			$(document).ready(function(){
				var url='{hostActual}/errores/pop_reg_usr_benef.html';
				$('#contenido_popup').load(url,function() {$('.contenedor_pop_ups').fadeIn(400);});
				return false;
		
			});						
		}else if (perfil == 'retailer'){
			$(document).ready(function(){
				var url='{hostActual}/errores/pop_reg_usr_retailer.html';
				$('#contenido_popup').load(url,function() {$('.contenedor_pop_ups').fadeIn(400);});
				return false;
		
			});						
		}else if (perfil == 'admin'){
			$(document).ready(function(){
				var url='{hostActual}/errores/pop_reg_usr_admin.html';
				$('#contenido_popup').load(url,function() {$('.contenedor_pop_ups').fadeIn(400);});
				return false;
		
			});						
		}else if (perfil == 'superadmin'){
			$(document).ready(function(){
				var url='{hostActual}/errores/pop_reg_usr_super_admin.html';
				$('#contenido_popup').load(url,function() {$('.contenedor_pop_ups').fadeIn(400);});
				return false;
		
			});						
		}else{
			document.location.href= '{hostActual}/app/public_alta_cliente.php';
		}
	}

	function enviarDinero (registrate) {
		if (registrate && registrate == 'Registrate'){
			registrarUsuario();	
		}else{
			if (perfil == 'cliente'){
				document.location.href= '{hostActual}/app/make_a_purchase.php';
			}else if (perfil == 'beneficiario' || perfil == 'retailer' || perfil == 'admin' || perfil == 'superadmin'){
				$(document).ready(function(){
					var url='{hostActual}/errores/pop_error_enviar_dinero.html';
					$('#contenido_popup').load(url,function() {$('.contenedor_pop_ups').fadeIn(400);});
					return false;
		
				});						
			}else{
				$(document).ready(function(){
					var url='{hostActual}/pop_registrese.html';
					$('#contenido_popup').load(url,function() {$('.contenedor_pop_ups').fadeIn(400);});
					return false;
		
				});						
			}
		}
	}

	function cerrarPopup () {
		$(document).ready(function(){
			$('.contenedor_pop_ups').fadeOut(400, function(){$('.cerrar').css('left','0px');});
		});
	}

	function cerrarSesionRetailer () {
		document.location.href= '{hostActual}/app/logout_retailer.php';
	}
	function cerrarSesion () {
		document.location.href= '{hostActual}/app/logout.php';
	}
	
	function irABeneficiarios () {
		if (perfil == 'cliente'){
			document.location.href= '{hostActual}/app/make_a_purchase.php';
		}else{
			if (perfil == 'beneficiario'){
				$(document).ready(function(){
					var url='{hostActual}/errores/pop_reg_usr_benef.html';
					$('#contenido_popup').load(url,function() {$('.contenedor_pop_ups').fadeIn(400);});
					return false;
			
				});						
			}else if (perfil == 'retailer'){
				$(document).ready(function(){
					var url='{hostActual}/errores/pop_reg_usr_retailer.html';
					$('#contenido_popup').load(url,function() {$('.contenedor_pop_ups').fadeIn(400);});
					return false;
			
				});						
			}else if (perfil == 'admin'){
				$(document).ready(function(){
					var url='{hostActual}/errores/pop_reg_usr_admin.html';
					$('#contenido_popup').load(url,function() {$('.contenedor_pop_ups').fadeIn(400);});
					return false;
			
				});						
			}else if (perfil == 'superadmin'){
				$(document).ready(function(){
					var url='{hostActual}/errores/pop_reg_usr_super_admin.html';
					$('#contenido_popup').load(url,function() {$('.contenedor_pop_ups').fadeIn(400);});
					return false;
			
				});						
			}else{
				abrirLogin('{hostActual}/pop_login.html');
			}
		}
	}
	
	function irAConsultas () {
		if (perfil == 'cliente'){
			document.location.href= '{hostActual}/app/listar_transacciones.php';
		}else{
			$(document).ready(function(){
				var url='{hostActual}/errores/pop_ir_consultas.html';
				$('#contenido_popup').load(url,function() {$('.contenedor_pop_ups').fadeIn(400);});
				return false;
		
			});						
		}
	}
	
	function irAModificaciones () {
		if (perfil == 'cliente'){
			document.location.href= '{hostActual}/app/public_editar_cliente.php';
		}else{
			$(document).ready(function(){
				var url='{hostActual}/errores/pop_ir_modificaciones.html';
				$('#contenido_popup').load(url,function() {$('.contenedor_pop_ups').fadeIn(400);});
				return false;
		
			});						
		}
	}
</script>
                                                                     
                                                                     
{scriptCierre}
</head>

<body id="main_body" {htmlOnLoad}>

<!--pop_ups -->
<div class="contenedor_pop_ups">
<div class="pop_up_home">
<div class="cerrar"><a href="#" class="btn_cerrar"><img src="/wp-content/themes/mandaseguro/img/cerrar-04.png" width="19" height="19"></a></div>
<div id="contenido_popup"></div>
</div>
</div><!--end pop_ups -->

<!--pop_ups -->
<div class="contenedor_pop_ups2">
<div class="pop_up_home2">
<div id="contenido_popup2"></div>
</div>
</div><!--end pop_ups -->

<div class="contenedor_general">


<header>
<div class="logo"><a href="/"><img src="/wp-content/themes/mandaseguro/img_esp/logo_mandaseguro.jpg" width="361" height="102"></a></div><!--end logo -->
<div class="contenedor_menu">
{boton_logout}
<div class="idioma">
</div><!--end idioma -->
<div class="menu">
<ul>
{bienvenido}
{registrarse}
<li><a href="#" >MandaCheck</a>
		<ul>
        <li><img src="/wp-content/themes/mandaseguro/img/top_menu-03.png" width="106" height="11" align="left"></li>
        <li><a onclick="enviarDinero();" href="#">hacer una transacción</a></li>
        <li><a href="/pop_comofunciona.html" class="click">cómo funciona</a></li>
        <li><a href="/seguridad_proteccion/seguridad-y-proteccion.html">seguridad y protección</a></li>
        <li><a href="/comercios/comercios.html">comercios adheridos</a></li>    
        </ul>
 </li>
	
<li class="barra_menu">|</li>
<li><a href="#" >Mi cuenta</a>
		<ul>
        <li><img src="/wp-content/themes/mandaseguro/img/top_menu-03.png" width="106" height="11" align="left"></li>
			{cajaLogin}
			{menuMiCuenta}
        </ul>
        
</li>
<li class="barra_menu">|</li>
<li><a href="#" >Comercios</a>
		<ul>
        <li><img src="/wp-content/themes/mandaseguro/img/top_menu-03.png" width="106" height="11" align="left"></li>
        <li><a href="/comercios/comercios.html">comercios adheridos   </a></li>
        <li><a href="/comercios/por-que-adherir-mi-comercio.html">porqué adherir mi comercio? </a></li>
        </ul>
</li>
<li class="barra_menu">|</li>
<li><a href="#" >Ayuda</a>
		<ul>
        <li><img src="/wp-content/themes/mandaseguro/img/top_menu-03.png" width="106" height="11" align="left"></li>
        <li><a href="/ayuda.html">contáctanos</a></li>
        <li><a href="/faq.html">preguntas frecuentes</a></li>
        </ul>
</li>

</ul>
</div><!--end menu -->
</div><!--end contenedor_menu -->

</header><!--end header -->



<section class="contenedor_banner">
<a href="#"><div class="flag_banner">
<div class="btn info_up" style="position: absolute;z-index: 1;"><img src="/wp-content/themes/mandaseguro/img_esp/flag_banner-08.png" width="36" height="254"></div>
<div class="btn"><img src="/wp-content/themes/mandaseguro/img_esp/flag_banner-click.png" width="36" height="254"></div>
<div class="info_flag"><img src="/wp-content/themes/mandaseguro/img_esp/info_banner-09.jpg" width="927" height="254"></div>
</div></a>
<div class="fluid_container">      
		<img src="/wp-content/themes/mandaseguro/img/banner_extra.jpg" border="0"/>
</div><!-- ENDfluid_container -->
</section><!--end contenedor_banner -->



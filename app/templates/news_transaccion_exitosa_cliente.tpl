<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href='http://fonts.googleapis.com/css?family=Arimo:400,700' rel='stylesheet' type='text/css'>
<title>transacci&oacute;n confirmada cliente</title>

<style type="text/css">
/****** EMAIL CLIENT BUG FIXES - NO TOCAR ********/

.ExternalClass {width:100%;}
.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {
line-height: 100%;
}
body {-webkit-text-size-adjust:none; -ms-text-size-adjust:none;} 
body {margin:0; padding:0;} 
table td {border-collapse:collapse;}	

p {margin:0; padding:0; margin-bottom:0;}
h1, h2, h3, h4, h5, h6 {
color: #666;
line-height: 100%; 
} 
a, a:link {
color: #666;
text-decoration: underline;
}

body, #body_style {
background:#FFF;
min-height:650px;
color: #666;
font-family:Arial, Helvetica, sans-serif;
font-size:12px;
} 
span.yshortcuts { color: #666; background-color:none; border:none;}
span.yshortcuts:hover,
span.yshortcuts:active,
span.yshortcuts:focus {color: #666; background-color:none; border:none;}

/*Optional:*/
a:visited {color: #666; text-decoration: none} 
a:focus   {color: #666; text-decoration: underline}  
a:hover   {color: #333; text-decoration: underline}  

/*Optimizing for mobil devices - (optional)*/
@media only screen and (max-device-width: 480px) {
body[yahoo] #container1 {display:block !important}  /*example style	*/
body[yahoo] p {font-size: 10px} /*example style*/
}		

@media only screen and (min-device-width: 768px) and (max-device-width: 1024px)  {
body[yahoo] #container1 {display:block !important} /*example style*/ 
body[yahoo] p {font-size: 10px} /*example style*/						
}
/****** END BUG FIXES ********/			   
</style>
</head>
<body style="background:#FFF; min-height:650px; color: #666; font-family:Arial, Helvetica, sans-serif; font-size:12px" 
alink="#FF0000" link="#FF0000" bgcolor="#FFF" text="#333" yahoo="fix"> 
<!--PAGE WRAPPER-->
<div id="body_style" style="padding:15px;"> 
<table cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%; color: #666;"> 
<tr>
<td valign="top"  background="{hostActual}/news_transaccion_exitosa_cliente/img/bg_lines.png" bgcolor="#FFF" style=" background-image:url(img/bg_lines.png); background-repeat:repeat; padding:25px 0px;">
<table width="651" border="0" cellspacing="0" cellpadding="0" align="center">



<!-- HEADER -->
<tr>
<td width="651" height="128" style="width:651px; height:128px;"><img src="{hostActual}/news_transaccion_exitosa_cliente/img/news_header_procesos.png" alt="MandaSeguro.com" width="651" height="128" style="display:block;" border="0"/></td>
</tr>
<!-- END HEADER -->


<!-- FRANJA COLOR -->
<tr>
<td width="651" height="74" background="{hostActual}/news_transaccion_exitosa_cliente/img/news_franja_azul.png" style="width:651px; height:74px; background-repeat:no-repeat;">
<table width="510" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
<td width="50" height="64" valign="middle" style="height:64px; width:50px;"><img src="{hostActual}/news_transaccion_exitosa_cliente/img/news_ico_asessor_blanco.png" alt="" width="37" height="42" style="display:block;" border="0"/></td>
<td width="463" valign="middle" style="font-size:20px;letter-spacing:0px; line-height:18px; color:#FFF; font-weight:bold;">tu transacci&oacute;n ha sido confirmada.<br /><span style="font-weight:normal; font-size:14px;">n&uacute;mero de transacci&oacute;n: {idTransaccion}</span></td>
</tr>
</table>
</td>
</tr>
<!-- END FRANJA COLOR -->


<!-- INFORMACION -->
<tr>
<td width="651" background="{hostActual}/news_transaccion_exitosa_cliente/img/news_bg_blanco.png" style="background-repeat:repeat;width:651px; height:auto;">
<table width="460" border="0" align="center" cellpadding="0" cellspacing="0" style="font-size:13px;">
<tr>
<td width="460" height="20"></td>
</tr>
<tr>
<td style="text-align:justify;">felicitaciones {nombreCliente}<br />
hemos recibido la siguiente solicitud de tu parte</td>
</tr>
<tr>
<td>&nbsp;</td>
</tr>
<tr>
<td>
<div style="border-radius: 5px; -moz-border-radius: 5px; -webkit-border-radius: 5px;width:420px; height:140px; margin:auto; background-color:#F8F8F8; padding:20px;border: dashed 1px #CCC; ">


enviar a: <strong>{nombreBeneficiario}</strong>
<br />
MandaChecks por un valor equivalente a: <strong>${monto} pesos dominicanos*</strong>
<br />
para compras en la tienda: <a href="#" style="color: #F38E00;"><strong>{nombreRetailer}</strong></a>
<br />
los mismos ser&aacute;n cancelados con <strong>u$s {montoDolar} d&oacute;lares.</strong>
<br /><br />
{nombreBeneficiario} recibir&aacute; un n&uacute;mero PIN en su casilla de email y/o celular. <br />
con ese PIN, el n&uacute;mero de transacci&oacute;n y su ID, podr&aacute; reclamar sus <strong>MandaChecks</strong> para compras en la tienda <a href="#" style="color: #F38E00;"><strong>{nombreRetailer}</strong></a> m&aacute;s cercana.
<br /><br />

</div>
<br />
con esto puede comprar lo que necesita sin ning&uacute;n gasto adicional.
<br /><br /><br />
</td>
</tr>
<tr>
<td align="center">
<strong style="font-size:18px; color:#F38E00; text-align:center;">&iexcl;gracias por elegir MandaSeguro.com!</strong>
<a href="#"><img src="{hostActual}/news_transaccion_exitosa_cliente/img/news_btn_ayudar.png" alt="" width="203" height="61" style="display:block;" border="0" align="absmiddle"  /></a></td>
</tr>
<tr>
<td style="font-size:10px;">* el tipo de cambio aplicable es determinado por un banco independiente a la compra de los MandaChecks, y est&aacute; determinado de acuerdo a las normas vigentes en los pa&iacute;ses involucrados</td>
</tr>
<tr>
<td>&nbsp;</td>
</tr>
</table>
</td>
</tr>
<!-- END INFORMACION -->


<!-- FOOTER -->
<tr>
<td width="651" height="141" valign="top" background="{hostActual}/news_transaccion_exitosa_cliente/img/news_bg_footer_azul.png" style="background-repeat:no-repeat;width:651px; height:141px; display:block;">
<table width="510" border="0" align="center" cellpadding="0" cellspacing="0" style="display:block;">
<tr>
<td width="114" height="27" style="width:114px; height:28px; color:#FFF; font-size:12px; font-weight:bold;"><span style="position:relative; top:0px;">&iexcl;pasa la voz!</span></td>
<td width="396" height="27" style="width:396px; height:28px; inline-height:0px">
<a href="#"><img src="{hostActual}/news_transaccion_exitosa_cliente/img/news_ico_twitter.png" alt="twitter" width="29" height="28" border="0" style="display:block; float:left;"/></a>
<a href="#"><img src="{hostActual}/news_transaccion_exitosa_cliente/img/news_ico_facebook.png" alt="facebook" width="29" height="28" border="0" style="display:block; float:left;"/></a>
<a href="#"><img src="{hostActual}/news_transaccion_exitosa_cliente/img/news_ico_linkedin.png" alt="linkedin" width="29" height="28" border="0" style="display:block; float:left;"/></a>
<a href="#"><img src="{hostActual}/news_transaccion_exitosa_cliente/img/news_ico_mail.png" alt="enviar via email" width="29" height="28" border="0" style="display:block; float:left;"/></a>
</td>
</tr>
<tr>
<td height="37" colspan="2" style="font-size:11px; height:37px;">necesitas ayuda? escr&iacute;benos a  <strong><a href="mailto:info@mandaseguro.com" style="color:#666;">info@mandaseguro.com</a></strong> o ll&aacute;manos SIN CARGO al <strong>1-(888) 873-6170</strong>
</td>
</table>
</td>
</tr>
<!-- FOOTER -->




</table>
</td>
</tr>
</table>
</div>
<!--END PAGE WRAPPER-->
</body>
</html>

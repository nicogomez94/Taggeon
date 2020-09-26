<div class="caja" style="width:900px">
<div class="sup"></div>
<div class="label">Transacciones hist&oacute;ricas</div>
<div class="msg" style="width:900px">{error}</div>
<div class="msg2"><div id="wrapper">
	<div class="accordionButton">Filtros</div>
  	<div style="display: none;" class="accordionContent">
<div class="filtro">
  <form id="form_listado_admin" name="form_listado_admin" method="post" action="/app/listar_cuenta_corriente.php">
  <div class="fila1" style="width: 100%;">
    <select name="estado" class="filtro-select" id="estado" onchange="postFormListadoAdmin();" style="width: 150px;">
		{optionEstado}
    </select>
	<input type="text" id="search_string_fecha" value="" placeholder="Fecha"/>
	<input type="text" id="search_string" value="" placeholder="Cliente"/>
  </div>
  <div class="fila2" style="width: 100%;">
	<input type="text" id="search_string_beneficiario" value="" placeholder="Beneficiario"/>
	<input type="text" id="search_string_retailer" value="" placeholder="Negocio"/>
  </div>
  </form>
</div>	
	</div>
</div></div>

<div class="lista_r" style="height:300px;width:900px;overflow-x:auto;overflow-y:auto">
  <table width="1100px" border="0" cellspacing="0" cellpadding="0" id="listado_cc">
    <tr>
      <td width="107px" height="30" align="center"><div class="ref-ben4_r">Transacci&oacute;n</div></td>
      <td width="107px" height="30" align="center"><div class="ref-ben4_r">Fecha</div></td>
      <td width="111px" align="center"><div class="ref-ben4_r">Cliente</div></td>
      <td width="107px" align="center"><div class="ref-ben4_r">Beneficiario</div></td>
      <td width="107px" align="center"><div class="ref-ben4_r">Comercio</div></td>
      <td width="65px" align="center"><div class="ref-ben2_r">RD $</div></td>
      <td width="65px" align="center"><div class="ref-ben2_r">US$</div></td>
      <td width="65px" align="center"><div class="ref-ben2_r">PIN</div></td>
      <td width="83px" align="center"><div class="ref-ben2_r2">Estado</div></td>
      <td width="83px" align="center"><div class="ref-ben2_r2">Acci&oacute;n</div></td>
    </tr>
    {listado}
  </table>
</div>
<div class="paginador">{paginador}</div>
</div>
<br>

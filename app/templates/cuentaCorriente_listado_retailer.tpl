<div class="caja">
<div class="sup"></div>
<div class="label">Listado Gerente</div>
<div class="msg">{error}</div>

<div class="msg2"><div id="wrapper">
	<div class="accordionButton">Estados</div>
  	<div style="display: none;" class="accordionContent">
<div class="filtro">
  <form id="form_listado_gerente" name="form_listado_gerente" method="post" action="/app/listado_gerente.php">
  <div class="fila1" style="width: 100%;">
    <select name="estado" class="filtro-select" id="estado" onchange="postFormListadoGerente();" style="width: 150px;">
		{optionEstado}
    </select>
  </div>
  </form>
</div>	
	</div>
</div></div>

<div class="lista_r">
  <table width="750" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="112" height="30" align="center"><div class="ref-ben4_r">Transacci&oacute;n</div></td>
      <td width="112" height="30" align="center"><div class="ref-ben4_r">Fecha</div></td>
      <td width="112" align="center"><div class="ref-ben4_r">Beneficiario</div></td>
      <td width="68" align="center"><div class="ref-ben2_r">RD$</div></td>
      <td width="84" align="center"><div class="ref-ben2_r2">Estado</div></td>
    </tr>
    {listado}
  </table>
</div>
<div class="paginador">{paginador}</div>
</div>
<br>

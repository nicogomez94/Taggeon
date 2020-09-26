<tr>
      <td id="txt-lista">{id}</td>
      <td id="txt-lista">{usuario}</td>
      <td id="txt-lista">{perfil}</td>
      <td id="txt-lista">{fechaAlta}</td>
      <td id="txt-lista">{fechaUpdate}</td>
      <td height="40" align="center" valign="middle" id="txt-lista">
      <a href="editar_usuario.php?id={id}&accion=editar"><img src="{path}imagenes/editar.png" title="Editar usuario {id}" alt="Editar usuario {id}" border="0" /></a>
      <a href="eliminar_usuario.php?id={id}&accion=eliminar"><img src="{path}imagenes/borrar.png" title="Eliminar usuario {id}" alt="Eliminar usuario {id}" border="0" /></a>
      <a href="editar_pass.php?&idUsuario={id}&perfil={perfil}"><img src="{path}imagenes/editar-pass.png" title="Cambiar pass usuario {id}" alt="Cambiar pass usuario {id}" border="0" /></a>
      </td>
</tr>

<?php

function eliminar_producto($db_user, $cod_producto, $cod_usuario){   
  $condicion = "  grabo = '$cod_usuario' AND cod_producto = '$cod_producto'";
  $resultado = $db_user->borrar("productos",$condicion);
  $db_user->desconectarse();
  return $resultado;
}



?>

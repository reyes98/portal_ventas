<?php

function actualizar_producto($db_user, $valores, $cod_producto, $cod_usuario){   
  $campos = implode(',', $valores);  
  $condicion = "  grabo = '$cod_usuario' AND cod_producto = '$cod_producto'";
  $resultado = $db_user->actualizar("productos",$campos,$condicion);
  $db_user->desconectarse();
  return $resultado;
}



?>

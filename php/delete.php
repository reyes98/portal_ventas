<?php

function eliminar_producto($db_user, $cod_producto, $cod_usuario){   
  $condicion = "  grabo = '$cod_usuario' AND cod_producto = '$cod_producto'";
  $resultado = $db_user->borrar("productos",$condicion);
  $db_user->desconectarse();
  return $resultado;
}

function eliminar_producto_carrito($db_user, $cod_producto, $cod_usuario, $vendedor){   
  $condicion = "  cod_usuario = '$cod_usuario' AND cod_producto = '$cod_producto' AND vendedor = '$vendedor'";
  $resultado = $db_user->borrar("carrito",$condicion);
  $db_user->desconectarse();
  return $resultado;
}

function vaciar_carrito($db_user,$cod_usuario){   
  $condicion = "  cod_usuario = '$cod_usuario'";
  $resultado = $db_user->borrar("carrito",$condicion);
  $db_user->desconectarse();
  return $resultado;
}

?>

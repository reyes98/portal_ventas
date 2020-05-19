<?php

function crear_usuario($db_user, $valores){  
  $resultado = $db_user->insertar('usuarios',$valores);
  $db_user->desconectarse();
  return $resultado;
}

function asignar_rol($db_user, $cod_usuario, $rol){
  $resultado = $db_user->insertar('roles_usuario', $rol);
  $db_user->desconectarse();
  return $resultado;
}

function insertar_producto($db_user, $valores){
  $resultado = $db_user->insertar('productos', $valores);
  $db_user->desconectarse();
  return $resultado;
}

function insertar_factura($db_user, $campos,$valores){
  $res_f = $db_user->insertar("facturas".$campos, $valores);  
  return $res_f;
}
function insertar_factura_d($db_user, $valores){
  $res_f = $db_user->insertar("facturas_d".$campos, $valores);
  $db_user->desconectarse();
  return $res_f;
}



function insertar_pedido($db_user, $campos,$valores,$prods){
  $res_f = $db_user->insertar("pedidos".$campos, $valores);
  if ($res_f) {
  
  }
  //$resultado = $db_user->insertar('pedidos', $valores);
  $db_user->desconectarse();
  return $res_f;
}




?>

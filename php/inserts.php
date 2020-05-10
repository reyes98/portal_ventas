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

function insertar_remision($db_user, $valores){
  $resultado = $db_user->insertar('remisiones', $valores);
  $db_user->desconectarse();
  return $resultado;
}

function insertar_detalle_remision($db_user, $valores){
  $resultado = $db_user->insertar('remisiones_d', $valores);
  $db_user->desconectarse();
  return $resultado;
}


?>

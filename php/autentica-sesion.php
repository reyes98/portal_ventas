<?php

function equivalencia($entra){
  $cad1= " abcdefghijklmnopqrstuvwxyz01234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  $cad2= " nopqrstuvwxyz01234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklm";
  
  $sale="";
  $n=strlen($entra);
  for ($i=0;$i<=$n-1;$i++) {
    $car=$entra[$i];
    $p=strpos($cad1, $car);
    if (!$p=="") {
      $car=$cad2[$p];
    }
    $sale.=$car;
  }
  return $sale;
}

function usuario_existente($db_user, $cod_usuario){
  $resultado = $db_user->buscar("usuarios","cod_usuario = '$cod_usuario'");
  if($resultado){    
    return true; 
  }  

  return false; 
}


function passCorrecto($db_user, $cod_usuario, $passw){  
  $resultado = $db_user->buscar("usuarios","cod_usuario = '$cod_usuario'");
  $correcto=false;
  if($resultado){
    $row = $resultado[0];
    $correcto=password_verify($passw, $row['password']);    
  }  
  
  return $correcto;
}

function validar_clave($clave){
  if(strlen($clave) < 6){    
    return false;
  }
  if(strlen($clave) > 20){    
    return false;
  }
  /*if (!preg_match('[a-z]',$clave)){
    echo "La clave debe tener al menos una letra minúscula";
    return false;
  }
  if (!preg_match('[A-Z]',$clave)){
    echo "La clave debe tener al menos una letra mayúscula";
    return false;
  }
  if (!preg_match('[0-9]',$clave)){
    echo "La clave debe tener al menos un caracter numérico";
    return false;
  }*/
  return true;
}

?>



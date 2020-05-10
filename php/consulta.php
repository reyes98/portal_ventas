<?php 
include_once('conexion.php');

$db_user= new  Db();
if($resultado=$db_user->buscar("usuarios","1"))
 echo json_encode($resultado);
else{
	echo"no results :(";
}

$db_user->desconectarse();

?>



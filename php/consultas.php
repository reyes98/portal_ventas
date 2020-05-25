<?php

function consultar_productos($db_user, $valores){   
	$campos = " p.grabo, u.nombre, p.cod_producto, p.descripcion, p.precio, p.peso, p.marca ";
	$tablas = " productos as p
	INNER JOIN usuarios as u on u.cod_usuario = p.grabo ";
	$resultado = $db_user->buscar_parm($campos,$tablas,$valores);
	$db_user->desconectarse();
	return $resultado;
}

function ver_producto($db_user, $valores){   
	$resultado = $db_user->buscar("productos",$valores);
	$db_user->desconectarse();
	return $resultado;
}

function ver_carrito($db_user, $valores){
	$campos = "p.cod_producto, p.descripcion, c.cantidad*p.peso as peso, c.cantidad*p.precio as precio, p.marca";
	$tablas = " carrito as c 
	INNER JOIN productos as p on p.cod_producto = c.cod_producto ";
	$resultado = $db_user->buscar_parm("productos",$valores);
	$db_user->desconectarse();
	return $resultado;
}

?>

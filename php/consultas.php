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

function categorias($db_user){
	return $db_user->buscar("categoria_productos","1");
}

function ver_carrito($db_user, $valores){
	$campos = "p.cod_producto, car.vendedor as cod_vendedor,v.nombre as vendedor, p.descripcion, p.precio, sum(car.cantidad) as cantidad, (sum(car.cantidad) * p.precio) as precio_parcial";
	$tablas = " carrito as car
	INNER JOIN usuarios as c on c.cod_usuario = car.cod_usuario
	INNER JOIN usuarios as v on car.vendedor = v.cod_usuario
	INNER JOIN productos as p on p.cod_producto = car.cod_producto AND car.vendedor = p.grabo";
	$resultado = $db_user->buscar_parm($campos, $tablas,$valores);
	$db_user->desconectarse();
	return $resultado;
}

function ver_pedido($db_user, $valores){
	$campos = "p.cod_pedido, f.cliente, f.vendedor, f.direccion ";
	$tablas = " pedidos as p
	INNER JOIN facturas as f on p.cod_factura = f.cod_factura";
	$resultado = $db_user->buscar_parm($campos, $tablas,$valores);
	$db_user->desconectarse();
	return $resultado;
}

?>

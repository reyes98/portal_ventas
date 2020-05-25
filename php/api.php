<?php 
//operacion
$op=isset($_GET["op"])?$_GET["op"]:NULL;
//funcion
$fun=isset($_GET["fun"])?$_GET["fun"]:NULL;

include('conexion.php');
$db_user = new  Db();
switch ($op) {
	//ejecuta inicio
	case 'inicio':
	include("autentica-sesion.php");
	$cod_usuario=isset($_POST['cod_usuario'])?$_POST['cod_usuario']:"NULL";	
	$password=isset($_POST['password'])?$_POST['password']:"NULL";
	$response= array();
	if (usuario_existente($db_user,$cod_usuario)) {
		if (passCorrecto($db_user,$cod_usuario,$password)) {
			array_push($response, array("cod_usuario"=>$cod_usuario,"estado"=>"1","msj"=>"inicio de sesion correcto"));
			echo json_encode(array("server_response"=>$response));
		}else{
			array_push($response, array("estado"=>"0","msj"=>"Clave incorrecta"));
			echo json_encode(array("server_response"=>$response));
		}
	}else{
		array_push($response, array("estado"=>"0","msj"=>"usuario '$cod_usuario' no existe"));
		echo json_encode(array("server_response"=>$response));
	}

	exit();
	break;
	//ejecuta insserts
	case 'insert':
	include('inserts.php');

	//crear usuario
	if($fun=="crear_usuario"){
		include('autentica-sesion.php');
		$cod_usuario=isset($_POST['cod_usuario'])?$_POST['cod_usuario']:"NULL";
		$response= array();	
		if (!usuario_existente($db_user, $cod_usuario)) {
			$password=isset($_POST['password'])?$_POST['password']:"NULL";
			if (validar_clave($password)) {
				$hash = password_hash($password, PASSWORD_DEFAULT);

				$tipo_id=isset($_POST['tipo_id'])?$_POST['tipo_id']:"NULL";
				$identificacion=isset($_POST['identificacion'])?$_POST['identificacion']:"NULL";
				$nombre=isset($_POST['nombre'])?$_POST['nombre']:"NULL";
				$direccion=isset($_POST['direccion'])?$_POST['direccion']:"NULL";
				$tel_fijo=isset($_POST['tel_fijo'])?$_POST['tel_fijo']:"NULL";
				$tel_movil=isset($_POST['tel_movil'])?$_POST['tel_movil']:"NULL";
				$email=isset($_POST['email'])?$_POST['email']:"NULL";

				$estado='A';
				$eps=isset($_POST['eps'])?$_POST['eps']:"NULL";
				$foto=isset($_POST['foto'])?$_POST['foto']:"NULL";
				$valores= "'$cod_usuario', '$tipo_id', '$identificacion', '$nombre', '$direccion', '$tel_fijo', '$tel_movil', '$email', '$hash', '$estado', '$eps', '$foto', now(), NULL, NULL ";				

				if(crear_usuario($db_user, $valores)){
					array_push($response, array("estado"=>"1","cod_usuario"=>$cod_usuario,"msj"=>"usuario creado"));
				}else{
					array_push($response, array("estado"=>"0","cod_usuario"=>$cod_usuario,"msj"=>"usuario no creado"));
				}
				echo json_encode(array("server_response"=>$response));							
			}			
		}else{
			array_push($response, array("estado"=>"0","msj"=>"usuario no creado"));
			echo json_encode(array("server_response"=>$response));
		}		
		exit();
	}
	//crear producto
	if ($fun=="crear_producto") {
		$cod_producto = isset($_POST['cod_producto'])?$_POST['cod_producto']:"NULL";
		$cod_usuario = isset($_POST['cod_usuario'])?$_POST['cod_usuario']:"NULL";
		$descripcion = isset($_POST['descripcion'])?$_POST['descripcion']:"NULL";
		$cod_barras = isset($_POST['cod_barras'])?$_POST['cod_barras']:"NULL";
		$categoria = isset($_POST['categoria'])?$_POST['categoria']:"NULL";
		$precio = isset($_POST['precio'])?$_POST['precio']:"NULL";
		$peso = isset($_POST['peso'])?$_POST['peso']:"NULL";
		$unidad = isset($_POST['unidad'])?$_POST['unidad']:"NULL";
		$marca = isset($_POST['marca'])?$_POST['marca']:"NULL";
		$modelo = isset($_POST['modelo'])?$_POST['modelo']:"NULL";
		$info_tecnica = isset($_POST['info_tecnica'])?$_POST['info_tecnica']:"NULL";
		
		$valores= " '$cod_producto', '$cod_usuario', '$descripcion', '$cod_barras', '$categoria', $precio, $peso, $unidad, '$marca', '$modelo', '$info_tecnica', 'A', now(), NULL";
		$response= array();
		if (insertar_producto($db_user,$valores)) {
			array_push($response, array("estado"=>"1","cod_producto"=>$cod_producto,"msj"=>"el producto '$cod_producto' ha sido agregado"));
		}else{
			array_push($response, array("estado"=>"0","cod_producto"=>$cod_producto,"msj"=>"producto '$cod_producto' no se pudo agregar"));
		}
		echo json_encode(array("server_response"=>$response));
		exit();
	}

	//agregar al carrito
	if ($fun=="agregar_al_carrito") {
		$cod_usuario = isset($_POST['cod_usuario'])?$_POST['cod_usuario']:"NULL";
		$vendedor = isset($_POST['vendedor'])?$_POST['vendedor']:"NULL";
		$producto = isset($_POST['producto'])?$_POST['producto']:"NULL";
		$cantidad = isset($_POST['cantidad'])?$_POST['cantidad']:"NULL";		
		$valores="null, '$cod_usuario', '$vendedor', '$producto', $cantidad, CURRENT_TIMESTAMP() ";
		$response=array();
		if (insertar_al_carrito($db_user, $valores)) {
			array_push($response, array("estado"=>"1","producto"=>$producto,"msj"=>"El producto '$producto' se agregó al carrito"));
		}else{
			array_push($response, array("estado"=>"0","msj"=>"No se pudo agregar al carrito"));
		}
				
		echo json_encode(array("server_response"=>$response));
		exit();
	}

	//Nuevo pedido
	if ($fun=="nuevo_pedido") {
		$campos ="(cliente";
		$carrito = array();
		$carrito = isset($_POST['carrito'])?$_POST['carrito']:NULL;	
		
		if ($carrito) {			
			$cliente = isset($_POST['cliente'])?$_POST['cliente']:"NULL";
			$valores="'$cliente'";		
			$subtotal = isset($_POST['subtotal'])?$_POST['subtotal']:"NULL";
			if ($subtotal != "NULL"){
				$valores.= ", '$subtotal'";
				$campos.=", subtotal";
			} 

			$valor_domicilio = isset($_POST['valor_domicilio'])?$_POST['valor_domicilio']:"NULL";
			if ($valor_domicilio != "NULL"){
				$valores.= ", '$valor_domicilio'";
				$campos.=", valor_domicilio";
			}

			$total = isset($_POST['total'])?$_POST['total']:"NULL";
			if ($total != "NULL"){
				$valores.= ", '$total'";
				$campos.=", total";
			}

			$direccion = isset($_POST['direccion'])?$_POST['direccion']:"NULL";
			if ($direccion != "NULL"){
				$valores.= ", '$direccion'";
				$campos.=", direccion";
			}

			$tel_fijo = isset($_POST['tel_fijo'])?$_POST['tel_fijo']:"NULL";
			if ($tel_fijo != "NULL"){
				$valores.= ", '$tel_fijo'";
				$campos.=", tel_fijo";
			}

			$tel_movil = isset($_POST['tel_movil'])?$_POST['tel_movil']:"NULL";
			if ($tel_movil != "NULL"){
				$valores.= ", '$tel_movil'";
				$campos.=", tel_movil";
			}

			$email = isset($_POST['email'])?$_POST['email']:"NULL";
			if ($email != "NULL"){
				$valores.= ", '$email'";
				$campos.=", email";
			}

			$vendedor = isset($_POST['vendedor'])?$_POST['vendedor']:"NULL";
			if ($vendedor != "NULL"){
				$valores.= ", '$vendedor'";
				$campos.=", vendedor";
			}

			$observaciones = isset($_POST['observaciones'])?$_POST['observaciones']:"NULL";
			if ($observaciones != "NULL"){
				$valores.= ", '$observaciones'";
				$campos.=", observaciones";
			}

			$vehiculo = isset($_POST['vehiculo'])?$_POST['vehiculo']:"NULL";
			if ($vehiculo != "NULL"){
				$valores.= ", '$vehiculo'";
				$campos.=", vehiculo";
			}

			$domicilio = isset($_POST['domicilio'])?$_POST['domicilio']:"NULL";
			if ($domicilio != "NULL"){
				$valores.= ", '$domicilio'";
				$campos.=", domicilio";
			}

			$firma = isset($_POST['firma'])?$_POST['firma']:"NULL";		
			if ($firma != "NULL"){
				$valores.= ", '$firma'";
				$campos.=", firma";
			}

			$nombre_firma = isset($_POST['nombre_firma'])?$_POST['nombre_firma']:"NULL";
			if ($nombre_firma != "NULL"){
				$valores.= ", '$nombre_firma'";
				$campos.=", nombre_firma";
			}
			$valores.=", now()";
			$campos.=", fecha_alta)";			

			$response= array();
			$db_user->iniciar_transaccion();
			if (insertar_factura($db_user, $campos, $valores)) {
				$fac = $db_user->last_id();
				$rem= "NULL";
				$detalle_f = "";
				foreach ($carrito as $item => $arr) {
					if ($detalle_f==""){
						$detalle_f.= " ($fac ,$item";
					}else{
						$detalle_f.= ", ($fac, $item";
					}
					foreach ($arr as $key => $value) {				
						$detalle_f.= ", '$value'"; 
					}
					$detalle_f.= ")"; 
				}
				
				if (insertar_factura_d($db_user, $detalle_f)) {
					if (insertar_pedido($db_user,"(cod_factura, cod_remision)", "$fac, $rem")) {
						$db_user->commit();
						$db_user->desconectarse();
						array_push($response, array("estado"=>"1","msj"=>"Pedido completado","fac"=>$fac));
					} else {
						$db_user->rollback();
						array_push($response, array("estado"=>"0","msj"=>"No se pudo agregar ese pedido"));
					}					
					
				}else{
					$db_user->rollback();
					array_push($response, array("estado"=>"0","msj"=>"No se pudo agregar ese pedido"));
				}				
			}else{
				$db_user->rollback();
				array_push($response, array("estado"=>"0","msj"=>"No se pudo agregar ese pedido"));
			}
		}		
		echo json_encode(array("server_response"=>$response));
		exit();
	}
	break;
	//-------------------------------------------------------------------
	//ejecuta select
	case 'select':
	include("consultas.php");
	if ($fun=="lista_productos") {
		$cod_usuario = isset($_POST['cod_usuario'])?$_POST['cod_usuario']:"NULL";
		$valores="p.estado = 'A'";		
		$cod_producto = isset($_POST['cod_producto'])?$_POST['cod_producto']:"NULL";
		$descripcion = isset($_POST['descripcion'])?$_POST['descripcion']:"NULL";
		$cod_barras = isset($_POST['cod_barras'])?$_POST['cod_barras']:"NULL";
		$categoria = isset($_POST['categoria'])?$_POST['categoria']:"NULL";
		$marca = isset($_POST['marca'])?$_POST['marca']:"NULL";
		if ($cod_usuario!="NULL") {$valores.=" AND grabo ='$cod_usuario'";}
		if ($cod_producto!="NULL") {$valores.=" AND cod_producto like '%$cod_producto%'";}		
		if ($descripcion!="NULL") {$valores.=" AND descripcion like '%$descripcion%'";}		
		if ($cod_barras!="NULL") {$valores.=" AND cod_barras ='$cod_barras'";}		
		if ($categoria!="NULL") {$valores.=" AND categoria = $categoria";}		
		if ($marca!="NULL") {$valores.=" AND marca like '%$marca%'";}		
		$resultado = consultar_productos($db_user, $valores);
		$response = array();
		
		foreach ($resultado as $key => $value) {
			array_push($response, array($key => $value));				
		}
		
		echo json_encode(array("server_response"=>$response));		
		exit();
	}

	//ver la información de un producto
	if ($fun=="ver_producto") {
		$cod_usuario = isset($_POST['cod_usuario'])?$_POST['cod_usuario']:"NULL";
		$cod_producto = isset($_POST['cod_producto'])?$_POST['cod_producto']:"NULL";
		$valores="grabo = '$cod_usuario' AND cod_producto = '$cod_producto'";		
		
		$resultado = ver_producto($db_user, $valores);
		$response = array();
		
		foreach ($resultado as $key => $value) {
			array_push($response, array($key => $value));				
		}
		
		echo json_encode(array("server_response"=>$response));		
		exit();
	}

	break;
	//ejecuta update
	case 'update':
	include("update.php");
	//actualiza producto
	if ($fun=="actualizar_producto") {
		$cod_producto = isset($_POST['cod_producto'])?$_POST['cod_producto']:"NULL";
		$cod_usuario = isset($_POST['cod_usuario'])?$_POST['cod_usuario']:"NULL";
		$descripcion = isset($_POST['descripcion'])?$_POST['descripcion']:"NULL";
		$cod_barras = isset($_POST['cod_barras'])?$_POST['cod_barras']:"NULL";
		$categoria = isset($_POST['categoria'])?$_POST['categoria']:"NULL";
		$precio = isset($_POST['precio'])?$_POST['precio']:"NULL";
		$peso = isset($_POST['peso'])?$_POST['peso']:"NULL";
		$unidad = isset($_POST['unidad'])?$_POST['unidad']:"NULL";
		$marca = isset($_POST['marca'])?$_POST['marca']:"NULL";
		$modelo = isset($_POST['modelo'])?$_POST['modelo']:"NULL";
		$info_tecnica = isset($_POST['info_tecnica'])?$_POST['info_tecnica']:"NULL";
		$campos = array();
		$response= array();
		if ($cod_producto!="NULL") {
			if ($descripcion!="NULL") {$campos['descripcion'] = " descripcion = '$descripcion'";}		
			if ($cod_barras!="NULL") {$campos['cod_barras'] = " cod_barras = '$cod_barras'";}		
			if ($categoria!="NULL") {$campos['categoria'] = " categoria = '$categoria'";}	
			if ($precio!="NULL") {$campos['precio'] = " precio = '$precio'";}
			if ($peso!="NULL") {$campos['peso'] = " peso = '$peso'";}
			if ($unidad!="NULL") {$campos['unidad'] = " unidad = '$unidad'";}
			if ($marca!="NULL") {$campos['marca'] = " marca = '$marca'";}
			if ($modelo!="NULL") {$campos['modelo'] = " modelo = '$modelo'";}
			if ($info_tecnica!="NULL") {$campos['info_tecnica'] = " info_tecnica = '$info_tecnica'";}
			$campos['fecha_modificacion'] = " fecha_modificacion = now()";
			if (actualizar_producto($db_user,$campos, $cod_producto, $cod_usuario)) {
				array_push($response, array("estado"=>"1","cod_producto"=>$cod_producto,"msj"=>"el producto '$cod_producto' ha sido actualizado"));
			}else{
				array_push($response, array("estado"=>"0","cod_producto"=>$cod_producto,"msj"=>"el producto '$cod_producto' no se pudo actualizar"));
			}
		}else{
			array_push($response, array("estado"=>"0","msj"=>"tienes que especificar el producto a actualizar"));
		}	
		
		echo json_encode(array("server_response"=>$response));
		exit();
	}

	break;
	//ejecuta delete
	case 'delete':
	include("delete.php");
	if ($fun=="eliminar_producto") {
		$cod_producto = isset($_POST['cod_producto'])?$_POST['cod_producto']:"NULL";
		$cod_usuario = isset($_POST['cod_usuario'])?$_POST['cod_usuario']:"NULL";
		
		$response= array();
		if ($cod_producto!="NULL") {			
			if (eliminar_producto($db_user,$cod_producto, $cod_usuario)) {
				array_push($response, array("estado"=>"1","cod_producto"=>$cod_producto,"msj"=>"el producto '$cod_producto' ha sido eliminado"));
			}else{
				array_push($response, array("estado"=>"0","cod_producto"=>$cod_producto,"msj"=>"el producto '$cod_producto' no se pudo eliminar"));
			}
		}else{
			array_push($response, array("estado"=>"0","msj"=>"tienes que especificar el producto a elminar"));
		}	
		
		echo json_encode(array("server_response"=>$response));
		exit();
	}

	break;
	
}
exit();
?>

<?php 
$op=isset($_GET["op"])?$_GET["op"]:NULL;
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
	break;
	//-------------------------------------------------------------------
	//ejecuta select
	case 'select':
	include("consultas.php");
	if ($fun=="lista_productos") {
		$cod_usuario = isset($_POST['cod_usuario'])?$_POST['cod_usuario']:"NULL";
		$valores="cod_usuario = '$cod_usuario'";		
		$cod_producto = isset($_POST['cod_producto'])?$_POST['cod_producto']:"NULL";
		$descripcion = isset($_POST['descripcion'])?$_POST['descripcion']:"NULL";
		$cod_barras = isset($_POST['cod_barras'])?$_POST['cod_barras']:"NULL";
		$categoria = isset($_POST['categoria'])?$_POST['categoria']:"NULL";
		$marca = isset($_POST['marca'])?$_POST['marca']:"NULL";
		if ($cod_producto!="NULL") {$valores.=" AND cod_producto = '$cod_producto'";}		
		if ($descripcion!="NULL") {$valores.=" AND descripcion = '$descripcion'";}		
		if ($cod_barras!="NULL") {$valores.=" AND cod_barras = '$cod_barras'";}		
		if ($categoria!="NULL") {$valores.=" AND categoria = $categoria";}		
		if ($marca!="NULL") {$valores.=" AND marca = '$marca'";}
		
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

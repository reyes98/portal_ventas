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
		if (usuario_existente($db_user,$cod_usuario)) {
			if (passCorrecto($db_user,$cod_usuario,$password)) {
				echo "correcto";
			}else{
				echo "contraseña erronea";
			}
		}else{
			echo "usuario no existe";
		}
	break;
	//ejecuta insserts
	case 'insert':
	include('inserts.php');

	//crear usuario
	if($fun=="crear_usuario"){
		include('autentica-sesion.php');
		$cod_usuario=isset($_POST['cod_usuario'])?$_POST['cod_usuario']:"NULL";	
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
					echo "Usuario creado exitoamente";
				}else{
					echo "No se pudo registar el usuario $cod_usuario :(";
				}
				exit();
				
			}			
		}else{
			echo "El usuario '$cod_usuario' ya existe :(";
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
		
		if (insertar_producto($db_user,$valores)) {
			echo "El producto $cod_producto ha sido guardado en tu lista de productos";
		}else{
			echo "No se pudo guardar el producto $cod_producto en tu lista";
		}
	}
	break;
	//-------------------------------------------------------------------
	//ejecuta select
	case 'select':

	break;
	//ejecuta update
	case 'update':

	break;
	//ejecuta delete
	case 'delete':

	break;
	
}
?>

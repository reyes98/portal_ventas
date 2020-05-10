<?php 
//El parametro de $extension determina qué tipo de imagen no se borrará, por ejemplo: si es jpg, significa que la imagen con extensión .jpg se queda en el servidor y si existen imágenes con el mismo nombre pero con extensión png o gif, se eliminarán. Con esta función evito tener imágenes duplicadas con didtintas extensiones para cada perfil. La función file_exists evalua si un archivo existe y la funcion unlink borra un archivo del servidor
function borrar_imagenes($ruta,$extension){
	switch ($extension){
		case '.jpg':
		if (file_exists($ruta."png"))
			unlink($ruta."png");	
		if (file_exists($ruta."gif"))
			unlink($ruta."gif");	
		break;
		case '.gif':
		if (file_exists($ruta."png"))
			unlink($ruta."png");	
		if (file_exists($ruta."jpg"))
			unlink($ruta."jpg");	
		break;
		case '.png':
		if (file_exists($ruta."jpg"))
			unlink($ruta."jpg");	
		if (file_exists($ruta."gif"))
			unlink($ruta."gif");	
		break;
	}
}

//Función para subir la imagen del perfil del usuario
function subir_imagen($tipo,$imagen,$email){
	//strstr($cadena1,$cadena2)	sirve para evaluar si en la primera cadena de texto existe la segunda cadena de texto
	// Si dentro del tipo del archivo se encuentra la palabra image, significa que el archivo es una imagen
	if (strstr($tipo,"image")) {
		//El archivo si es una imagen
		//para saber de que tipo de extensión es la imagen
		if (strstr($tipo,"jpeg"))
			$extension=".jpg";
		else if (strstr($tipo, ".gif"))
			$extension=".gif";
		else if (strstr($tipo, "png"))
			$extension=".png";
		//para saber si la imagen tiene el ancho correcto que es de 420px	
		$tam_img = getimagesize($imagen);
		$ancho_img=$tam_img[0];
		$alto_img=$tam_img[1];
		$ancho_img_deseado=420;
		//Si la imagen tiene el ancho mayor a 420px su tamaño 
		if ($ancho_img>$ancho_img_deseado) {
			//reajustamos, por una regla de 3, obtengo el alto de la imagen de manera proporcional al ancho nuevo que será de 420
			$nuevo_ancho_img=$ancho_img_deseado;
			$nuevo_alto_img=($alto_img/$ancho_img)*$nuevo_ancho_img;
			//Creo una imagen en color real con las nuevas dimensiones
			$img_reajustada=imagecreatetruecolor($nuevo_ancho_img, $nuevo_alto_img);
			//Creo una imagen basada en la original, dependiendo de su extensión es el tipo que crearé
			switch ($extension){
				case ".jpg":
				$img_original=imagecreatefromjpeg($imagen);
					//Reajusto la imagen nueva con respecto a la original
				imagecopyresampled($img_reajustada, $img_original, 0, 0, 0, 0, $nuevo_ancho_img, $nuevo_alto_img, $ancho_img, $alto_img);
					//Guardo la imagen reescalada en el servidor
				$nombre_img_ext="../img/fotos/".$email.$extension;
				$nombre_img="../img/fotos/".$email;
				imagejpeg($img_reajustada,$nombre_img_ext,100);
				borrar_imagenes($nombre_img,".jpg");
				break;
				case ".gif":
				$img_original=imagecreatefromgif($imagen);
					//Reajusto la imagen nueva con respecto a la original
				imagecopyresampled($img_reajustada, $img_original, 0, 0, 0, 0, $nuevo_ancho_img, $nuevo_alto_img, $ancho_img, $alto_img);
					//Guardo la imagen reescalada en el servidor
				$nombre_img_ext="../img/fotos/".$email.$extension;
				$nombre_img="../img/fotos/".$email;
				imagegif($img_reajustada,$nombre_img_ext,100);
				borrar_imagenes($nombre_img,".gif");
				break;
				case ".png":
				$img_original=imagecreatefrompng($imagen);
					//Reajusto la imagen nueva con respecto a la original
				imagecopyresampled($img_reajustada, $img_original, 0, 0, 0, 0, $nuevo_ancho_img, $nuevo_alto_img, $ancho_img, $alto_img);
					//Guardo la imagen reescalada en el servidor
				$nombre_img_ext="../img/fotos/".$email.$extension;
				$nombre_img="../img/fotos/".$email;
				imagepng($img_reajustada,$nombre_img_ext,100);
				borrar_imagenes($nombre_img,".png");
				break;
			}
		} else {
			// no se reajusta y se sube
			// guardo la ruta que tendra en el servidor la imagen
			$destino="../img/fotos/".$email.$extension;
			// se sube la foto
			move_uploaded_file($imagen, $destino) or die ("no se pudo subir la imagen");
			// ejecuto la funcion para borrar posibles imágenes dobles para el perfil
			$nombre_img="../img/fotos/".$email;
			borrar_imagenes($nombre_img,$extension);
		}
			// Asigno el nombre de la foto que se guardará en la BD como cadena de texto
		$imagen=$email.$extension;
		return $imagen;
	} else {
		return false;
	}
}

function extension_archivo($tipo){
	switch ($tipo) {
		case 'image':
		if (strstr($tipo,"jpeg"))
			$extension=".jpg";
		else if (strstr($tipo, ".gif"))
			$extension=".gif";
		else if (strstr($tipo, "png"))
			$extension=".png";
		break;
		case 'officedocument':
		if (strstr($tipo,"word"))
			$extension=".docx";
		else if (strstr($tipo, "spreadsheet"))
			$extension=".xlsx";
		break;
		case 'powerpoint':
		$extension=".ppt";
		break;
		case 'pdf':
		$extension=".pdf";
		break;
		
	}
	return $extension;
}
//Función para subir archivos
function subir_archivo($tipo,$archivo,$dest){
	if (strstr($tipo,"image")) {
		if (strstr($tipo,"jpeg"))
			$extension=".jpg";
		else if (strstr($tipo, ".gif"))
			$extension=".gif";
		else if (strstr($tipo, "png"))
			$extension=".png";
	}


	if (strstr($tipo,"officedocument")) {
		//El archivo si es una imagen
		//para saber de que tipo de extensión es la imagen
		if (strstr($tipo,"word"))
			$extension=".docx";
		else if (strstr($tipo, "spreadsheet"))
			$extension=".xlsx";
	}
	if (strstr($tipo,"powerpoint"))
		$extension=".ppt";

	if (strstr($tipo,"pdf"))
		$extension=".pdf";

	if (isset($extension)) {
		$nombre=$dest.$extension;
		$destino="../img/articulos/".$nombre;
				// se sube el archivo
		move_uploaded_file($archivo, $destino) or die ("no se pudo subir el archivo");
			// Asigno el nombre del archivo a guardar n la BD como cadena de texto
		return $nombre;
	}
	else
		return FALSE;
}
?>
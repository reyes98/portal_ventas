<?php
class  Db{
  //uso Local
  private $bd_ruta ="portal_ventas";
  private $servidor ="localhost";
  private $user ="root";
  private $password =""; 
  //


  public $conexion;

  public function __construct(){
  	$this->conexion = new mysqli($this->servidor, $this->user, $this->password,$this->bd_ruta)
  	or die(mysql_error());
  	$this->conexion->set_charset("utf8");
  }

    //Last ID
  public function last_id(){
    return $this->conexion->insert_id;
  }
    //COMMIT A LOS CAMBIOS
  public function commit(){
    return $this->conexion->commit();
  }

    //INICIA TRANSACCIÓN
  public function iniciar_transaccion(){
    return $this->conexion->begin_transaction();
  }

  //INICIA ROLLBACK
  public function rollback(){
    return $this->conexion->rollback();
  }

    //INSERTAR
  public function insertar($tabla, $datos){
  	$resultado = $this->conexion->query("INSERT INTO $tabla VALUES ($datos)") or die($this->conexion->error);

  	if($resultado)
  		return true;
  	return false;
  }

    //INSERTAR
  public function insertar_multiple($tabla, $datos){
    $resultado = $this->conexion->query("INSERT INTO $tabla VALUES $datos;") or die($this->conexion->error);

    if($resultado)
      return true;
    return false;
  }
    //BORRAR
  public function borrar($tabla, $condicion){    
  	$resultado  =   $this->conexion->query("DELETE FROM $tabla WHERE $condicion;") or die($this->conexion->error);
  	if($resultado)
  		return true;
  	return false;
  }
    //ACTUALIZAR
  public function actualizar($tabla, $campos, $condicion){    
  	$resultado  =   $this->conexion->query("UPDATE $tabla SET $campos WHERE $condicion;") or die($this->conexion->error);
  	if($resultado)
  		return true;
  	return false;        
  } 
    //BUSCAR
  public function buscar($tabla, $condicion){
  	$resultado = $this->conexion->query("SELECT * FROM $tabla WHERE $condicion") or die($this->conexion->error);
  	if($resultado){
  		return $resultado->fetch_all(MYSQLI_ASSOC);
  	}
  	return false;
  }

    //BUSCAR
  public function buscar_parm($campos, $tabla, $condicion){
    $resultado = $this->conexion->query("SELECT $campos FROM $tabla WHERE $condicion") or die($this->conexion->error);
    if($resultado){
      return $resultado->fetch_all(MYSQLI_ASSOC);
    }
    return false;
  }

    //DESCONECTARSE
  public function desconectarse(){
    mysqli_close($this->conexion);
  }
  
}	

?>



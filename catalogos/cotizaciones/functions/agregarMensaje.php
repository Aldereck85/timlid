<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

$token = $_POST["csr_token_7ALF1"];

if(empty($_SESSION['token_ld10d'])) {
  echo "fallo";
  return;
}

if (!hash_equals($_SESSION['token_ld10d'], $token)) {
  echo "fallo";
  return;
}

require_once('../../../include/db-conn.php');
$json = new \stdClass();

  $mensaje = $_POST['Mensaje'];
  $cotizacion = $_POST['Cotizacion'];
  $fecha = $_POST['Fecha'];

  $fechaarray = explode(" ",$fecha);
  $fechasolo = explode("/",$fechaarray[0]);
  $fechacorrecta = $fechasolo[2]."-".$fechasolo[1]."-".$fechasolo[0]." ".$fechaarray[1];

  try{

    $stmt = $conn->prepare('INSERT INTO mensajes_cotizacion (FKCotizacion,TipoUsuario, Mensaje, FechaAgregado) VALUES (:cotizacion,2,:mensaje, :fecha)');
    $stmt->bindValue(':cotizacion',$cotizacion);
    $stmt->bindValue(':mensaje',$mensaje);
    $stmt->bindValue(':fecha',$fechacorrecta);
    if($stmt->execute()){
      $idMensaje = $conn->lastInsertId();
      $json->idMensaje = $idMensaje;
      $json->estatus = "exito";
      $json = json_encode($json);
      echo $json;
    }
    else{
      $json->estatus = "fallo";
      $json = json_encode($json);
      echo $json;
    }
  }catch(PDOException $ex){
    echo $ex->getMessage();
  }
 ?>

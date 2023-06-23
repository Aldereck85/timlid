<?php
session_start();
require_once('../../../include/db-conn.php');

  $nota = $_POST['Nota'];
  $idNota = $_POST['idNota'];
  $fecha = $_POST['Fecha'];

  $fechaarray = explode(" ",$fecha);
  $fechasolo = explode("/",$fechaarray[0]);
  $fechacorrecta = $fechasolo[2]."-".$fechasolo[1]."-".$fechasolo[0]." ".$fechaarray[1];

  try{

    $stmt = $conn->prepare('UPDATE bitacora_notas SET Nota = :nota, FechaModificacion = :fecha WHERE PKBitacoraNotas = :idNota');
    $stmt->bindValue(':nota',$nota);
    $stmt->bindValue(':fecha',$fechacorrecta);
    $stmt->bindValue(':idNota',$idNota);
    if($stmt->execute())
      echo "exito";
      else
        echo "fallo";
  }catch(PDOException $ex){
    echo $ex->getMessage();
  }
 ?>

<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

$token = $_POST['token_4s45us'];

if(empty($_SESSION['token_ld10d'])) {
    echo "fallo";
    return;           
}

if (!hash_equals($_SESSION['token_ld10d'], $token)) {
    echo "fallo";
    return;
}


require_once('../../../include/db-conn.php');
  
  $id = $_POST['idEquipo'];
  try{
    $query = sprintf("DELETE FROM equipos WHERE PKEquipo=?");
    $stmt = $conn->prepare($query);
    
    if($stmt->execute(array($id))){
      echo "exito";
    }
    else{
      echo "fallo";
    }
    
  }catch(PDOException $ex){
    //echo $ex->getMessage();
    echo "fallo";
  }

?>

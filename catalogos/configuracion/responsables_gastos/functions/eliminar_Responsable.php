<?php
session_start();
if(isset($_SESSION["Usuario"])){
  require_once('../../../../include/db-conn.php');
  if(isset($_POST['id'])){
    $id = $_POST['id'];
    try{
      //DELETE SUBCATEGORIA
      $stmt = $conn->prepare("DELETE FROM relacion_tipo_empleado WHERE id = ?");
        if ($stmt->execute(array($id))){
          echo "1";
        }else{
          echo "0";
        }
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  } else {
    echo 'No hay id';
  }
} else {
  echo 'No hay usuario';
}
?>

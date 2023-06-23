<?php
session_start();

if(isset($_SESSION["Usuario"])){
  require_once('../../../include/db-conn.php');
  
    $estado = $_POST['Estado'];
    try{
      $stmt = $conn->prepare('UPDATE usuarios set Estado = :estado WHERE PKUsuario = :id');
      $stmt->bindValue(':estado',$estado);
      $stmt->bindValue(':id',$_SESSION['PKUsuario']);
      $stmt->execute();
      
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
}
?>

<?php
  session_start();
  date_default_timezone_set('America/Mexico_City');
  if(isset($_SESSION["Usuario"]) && ($_SESSION['FKRol'] == 1 || $_SESSION['FKRol'] == 4)){
    require_once('../../../include/db-conn.php');
      if(isset($_POST['id'])){
        $id = $_POST['id'];
        $fechaBit = "";
        $fechaBit = date('Y-m-d');

        try{
          //$conn->beginTransaction();
          $stmt = $conn->prepare("UPDATE orden_compra SET Estatus = :estatus WHERE PKOrdenCompra = :id");
          $stmt->bindValue(':estatus',1);
          $stmt->bindValue(':id', $id, PDO::PARAM_INT);
          $stmt->execute();

          $stmt = $conn->prepare("INSERT INTO bitacora_compras (FKUsuario, Fecha_Movimiento, FKMensaje, FKOrdenCompra) VALUES (:usuario,:fecha,:mensaje,:compra)");
          $stmt->bindValue(':usuario',$_SESSION['PKUsuario']);
          $stmt->bindValue(':fecha',$fechaBit);
          $stmt->bindValue(':mensaje',1);
          $stmt->bindValue(':compra',$id);
          $stmt->execute();
          //header('Location:../index.php');
        }catch(PDOException $ex){
          //echo $ex->getMessage();
          //$conn->rollBack();
        }

      }
  }
  //header('Location:../index.php');
?>

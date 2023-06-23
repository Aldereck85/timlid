<?php
  session_start();
  //date_default_timezone_set('America/Mexico_City');
  if(isset($_SESSION["Usuario"]) && ($_SESSION['FKRol'] == 1 || $_SESSION['FKRol'] == 4)){
    require_once('../../../include/db-conn.php');
    if(isset($_POST['idOrdenCompraC'])){
      $id = $_POST['idOrdenCompraC'];
      $observaciones = $_POST['txaMotivo'];

      try{
        $stmt = $conn->prepare('UPDATE orden_compra SET Estatus = :estatus, Observaciones = :observaciones WHERE PKOrdenCompra = :id');
        $stmt->bindValue(':estatus',3);
        $stmt->bindValue('observaciones')
        $stmt->bindValue(':id',$id);
        $stmt->execute();

        $stmt = $conn->prepare("INSERT INTO bitacora_compras (FKUsuario, Fecha_Movimiento, FKMensaje, FKOrdenCompra) VALUES (:usuario,:fecha,:mensaje,:compra)");
        $stmt->bindValue(':usuario',$_SESSION['PKUsuario']);
        $stmt->bindValue(':fecha',date('Y-m-d'));
        $stmt->bindValue(':mensaje',16);
        $stmt->bindValue(':compra',$id);
        $stmt->execute();
      }catch(PDOException $ex){
        echo $ex->getMessage();
      }
      header('Location:../index.php');
    }
  }



?>

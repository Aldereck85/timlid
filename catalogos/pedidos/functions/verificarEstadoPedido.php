<?php
  require_once('../../../include/db-conn.php');

  if(isset($_POST['idOrdenPedido'])){
    $id =  $_POST['idOrdenPedido'];

        try{
          $stmt = $conn->prepare('SELECT estatus_orden_pedido_id FROM orden_pedido_por_sucursales WHERE id = :id');
          $stmt->execute(array(':id'=>$id));
          $row = $stmt->fetch();
          echo $row['estatus_orden_pedido_id'];

        }catch(Exception $e){
          echo $e->getMessage();
          exit;
        }
    }
   ?>

<?php
  session_start();

  $jwt_ruta = "../../../";
  require_once '../../jwt.php';

  $token = $_POST["csr_token_8UY8N"];

  if(empty($_SESSION['token_ld10d'])) {
      echo "fallo";
      return;
  }

  if (!hash_equals($_SESSION['token_ld10d'], $token)) {
      echo "fallo";
      return;
  }
  
  require_once('../../../include/db-conn.php');
  date_default_timezone_set('America/Mexico_City');

  if(isset($_POST['idOrdenPedido'])){
    $id =  $_POST['idOrdenPedido'];
    $FKUsuario = $_POST['FKUsuario'];
    $FechaIngreso = date("Y-m-d H:i:s");

        try{

          $stmt = $conn->prepare('SELECT estatus_orden_pedido_id FROM orden_pedido_por_sucursales WHERE id = :id AND empresa_id = '.$_SESSION['IDEmpresa']);
          $stmt->execute(array(':id'=>$id));
          $row = $stmt->fetch();

          if($row['estatus_orden_pedido_id'] == 1 || $row['estatus_orden_pedido_id'] == 2){

              $stmt = $conn->prepare('UPDATE orden_pedido_por_sucursales SET estatus_orden_pedido_id = 7 WHERE id = :id AND empresa_id = '.$_SESSION['IDEmpresa']);
              
              if($stmt->execute(array(':id'=>$id))){

                $stmt = $conn->prepare("INSERT INTO bitacora_orden_pedido (usuario_id, created_at, updated_at, mensaje_id, orden_pedido_id) VALUES (:fkusuario, :fechamovimiento, :fechamovimientoupdate,:fkmensaje, :fkordenpedido)");
                $stmt->bindValue(':fkusuario',$FKUsuario);
                $stmt->bindValue(':fechamovimiento',$FechaIngreso);
                $stmt->bindValue(':fechamovimientoupdate',$FechaIngreso);
                $stmt->bindValue(':fkmensaje', 23);
                $stmt->bindValue(':fkordenpedido',$id);
                $stmt->execute(); 

                echo "exito";
              }
              else{
                echo "fallo";
              }
          }
          else{
              echo "fallo-cancelacion";
          }
          
        }catch(Exception $ex){
          echo $ex->getMessage();
        }
    }
?>

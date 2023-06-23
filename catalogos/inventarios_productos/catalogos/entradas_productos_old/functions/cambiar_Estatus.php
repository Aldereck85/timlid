<?php
  session_start();
  date_default_timezone_set('America/Mexico_City');
  if(isset($_SESSION["Usuario"]) && ($_SESSION['FKRol'] == 1 || $_SESSION['FKRol'] == 4)){
    require_once('../../../include/db-conn.php');
      if(isset($_GET['id'])){
        $id = $_GET['id'];
        $estatus = $_GET['estatus'];
        $fechaEntrega = "";
        $importe = 0;
        if($estatus == 3 || $estatus == 4){
          $fechaEntrega = date('Y-m-d');
        }

        $purchase = [];
        $product = [];
        $price = [];
        $pieces = [];
        try{
          //$conn->beginTransaction();
          $stmt = $conn->prepare("UPDATE orden_compra SET Estatus = :estatus, Fecha_Entrega = :fecha WHERE PKOrdenCompra = :id");
          $stmt->bindValue(':estatus',$estatus);
          $stmt->bindValue(':fecha',$fechaEntrega);
          $stmt->bindValue(':id', $id, PDO::PARAM_INT);
          $stmt->execute();
          switch ($estatus) {
            case 2:
                $stmt = $conn->prepare('INSERT INTO bitacora_compras (FKUsuario,Fecha_Movimiento,FKMensaje,FKOrdenCompra) VALUES (:usuario,:fecha,:mensaje,:compras)');
                $stmt->bindValue(':usuario',$_SESSION['PKUsuario']);
                $stmt->bindValue(':fecha',date('Y-m-d'));
                $stmt->bindValue(':mensaje',1);
                $stmt->bindValue(':compras',$id, PDO::PARAM_INT);
                $stmt->execute();
              break;
            case 3:
                $stmt = $conn->prepare('INSERT INTO bitacora_compras (FKUsuario,Fecha_Movimiento,FKMensaje,FKOrdenCompra) VALUES (:usuario,:fecha,:mensaje,:compras)');
                $stmt->bindValue(':usuario',$_SESSION['PKUsuario']);
                $stmt->bindValue(':fecha',date('Y-m-d'));
                $stmt->bindValue(':mensaje',2);
                $stmt->bindValue(':compras',$id, PDO::PARAM_INT);
                $stmt->execute();
              break;
            case 4:
                $stmt = $conn->prepare('INSERT INTO bitacora_compras (FKUsuario,Fecha_Movimiento,FKMensaje,FKOrdenCompra) VALUES (:usuario,:fecha,:mensaje,:compras)');
                $stmt->bindValue(':usuario',$_SESSION['PKUsuario']);
                $stmt->bindValue(':fecha',date('Y-m-d'));
                $stmt->bindValue(':mensaje',3);
                $stmt->bindValue(':compras',$id, PDO::PARAM_INT);
                $stmt->execute();
              break;
          }

          if($estatus == 3 || $estatus == 4){
            $stmt = $conn->prepare('SELECT * FROM compras_tmp WHERE FKOrdenCompra = :id');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $rowCount = $stmt->rowCount();
            $x = 0;
            while($x < $rowCount){
              array_push($purchase, $stmt->fetch()['FKOrdenCompra']);
              $x++;
            }
            $stmt->execute();
            $x = 0;
            while($x < $rowCount){
              array_push($product, $stmt->fetch()['FKProducto']);
              $x++;
            }
            $stmt->execute();
            $x = 0;
            while($x < $rowCount){
              array_push($price, $stmt->fetch()['Precio_Unitario']);
              $x++;
            }
            $stmt->execute();
            $x = 0;
            while($x < $rowCount){
              array_push($pieces, $stmt->fetch()['Cantidad']);
              $x++;
            }
            for ($i=0; $i < count($purchase); $i++) {
              $stmt = $conn->prepare('INSERT INTO compras_productos (Cantidad,FKProducto,FKOrdenCompra,Precio_Unitario) VALUES (:cantidad,:producto,:compra,:precio)');
              $stmt->bindValue(':cantidad',$pieces[$i]);
              $stmt->bindValue(':producto',$product[$i]);
              $stmt->bindValue(':compra',$purchase[$i]);
              $stmt->bindValue(':precio',$price[$i]);
              $r = $stmt->execute();
              if($r){
                $stmt = $conn->prepare('DELETE FROM compras_tmp WHERE FKOrdenCompra = :id');
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $stmt = $conn->prepare('SELECT * FROM inventario WHERE FKProducto = :id');
                $stmt->bindValue(':id', $product[$i], PDO::PARAM_INT);
                $stmt->execute();
                $ban = $stmt->rowCount();
                if($ban > 0){
                  $row = $stmt->fetch();
                  $cantidad = $row['Existencias'] + $pieces[$i];
                  $stmt = $conn->prepare('UPDATE inventario SET Existencias = :cantidad WHERE FKProducto = :id');
                  $stmt->bindValue(':cantidad',$cantidad);
                  $stmt->bindValue(':id', $product[$i], PDO::PARAM_INT);
                  $stmt->execute();
                }else{
                  $stmt = $conn->prepare('INSERT INTO inventario (Existencias,FKProducto) VALUES (:existencia,:producto)');
                  $stmt->bindValue(':existencia',$pieces[$i]);
                  $stmt->bindValue(':producto',$product[$i]);
                  $stmt->execute();
                }
                $importe += (double)$price[$i]* (double)$pieces[$i];
                $stmt = $conn->prepare('UPDATE orden_compra SET Importe = :importe WHERE PKOrdenCompra = :id');
                $stmt->bindValue(':importe',$importe);
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
              }
            }
          }

          /*
          if($estatus == 2 || $estatus == 3){
            $fechaTermino = date("Y-m-d H:i:s");
            $stmt = $conn->prepare("INSERT INTO bitacora_compras (FKUsuario,Fecha_Movimiento,FKMensaje) VALUES (:user,:fecha,:mensaje)");
            $stmt->bindValue(':user', $_SESSION["Usuario"]);
            $stmt->bindValue(':fecha', $fecha);
            $stmt->bindValue(':mensaje', 1);
            $stmt->execute();
          }*/
          //$conn->commit();

          header('Location:../index.php');
        }catch(PDOException $ex){
          echo $ex->getMessage();
          $conn->rollBack();
        }

      }
  }
  header('Location:../index.php');
?>

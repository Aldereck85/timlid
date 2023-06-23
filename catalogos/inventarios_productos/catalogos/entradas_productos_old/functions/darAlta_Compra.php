<?php
session_start();
    require_once('../../../include/db-conn.php');
    //$productos = [];
    $referencia = $_POST['txtReferencia'];
    $fechaEmision = $_POST['txtFechaEmision'];
    $proveedor = $_POST['cmbProveedor'];
    $ordenCompra = $_POST['cmbOrdenCompra'];
    $observaciones = $_POST['txaObservaciones'];
    //productos de oc
    $ids = $_POST['txtIdProductos'];
    $precios = $_POST['txtPrecios'];
    $cantidades = $_POST['txtCantidades'];
    $unidades = $_POST['txtUnidades'];
    $importes = $_POST['txtImportes'];
    $total = 0;

    for ($i=0; $i < count($importes); $i++) {
      $total += $importes[$i];
    }
    $total = $total * 1.16;
      try{
        //Agregar compra
        $stmt = $conn->prepare('INSERT INTO compras_productos (Referencia,Fecha_de_Emision,Importe,FKOrdenCompra,Estatus,Observaciones) VALUES (:referencia,:fecha,:importe,:ordenCompra,:estatus,:observaciones)');
        $stmt->bindValue(':referencia',$referencia);
        $stmt->bindValue(':fecha',$fechaEmision);
        $stmt->bindValue(':ordenCompra',$ordenCompra);
        $stmt->bindValue(':importe',$total);
        $stmt->bindValue(':estatus',1);
        $stmt->bindValue(':observaciones',$observaciones);
        $stmt->execute();
        $idLast = $conn->lastInsertId();

        for ($i=0; $i < count($ids); $i++) {
          $stmt = $conn->prepare('SELECT PKProducto FROM productos WHERE PKProducto = :id');
          $stmt->bindValue(':id',$ids[$i]);
          $stmt->execute();
          while($row = $stmt->fetch()){
            $productos[] = $row['PKProducto'];
          }
        }

        $stmt = $conn->prepare('UPDATE orden_compra SET Fecha_Entrega = :fecha WHERE PKOrdenCompra = :id');
        $stmt->bindValue(':fecha',$fechaEmision);
        $stmt->bindValue(':id',$ordenCompra);
        $stmt->execute();

        for ($i=0; $i < count($ids); $i++) {
          $stmt = $conn->prepare('INSERT INTO productos_cc (FKCompra,FKProducto,Cantidad_Recibida,Precio_Unitario,Importe) VALUES (:compra,:producto,:cantidad,:precio,:importe)');
          $stmt->bindValue(':compra',$idLast);
          $stmt->bindValue(':producto',$productos[$i]);
          $stmt->bindValue(':cantidad',$cantidades[$i]);
          $stmt->bindValue(':precio',$precios[$i]);
          $stmt->bindValue(':importe',$importes[$i]);
          $stmt->execute();
        }

        for ($i=0; $i < count($productos); $i++) {
          $stmt = $conn->prepare('SELECT * FROM inventario WHERE FKProducto = :id');
          $stmt->bindValue(':id',$productos[$i]);
          $stmt->execute();
          $rowCount = $stmt->rowCount();
          $row = $stmt->fetch();
          if($rowCount > 0){

            $cantidad = $row['Existencias'] + $cantidades[$i];
            $stmt1 = $conn->prepare('UPDATE inventario SET Existencias = :cantidad WHERE FKProducto = :id');
            $stmt1->bindValue(':cantidad',$cantidad);
            $stmt1->bindValue(':id',$productos[$i]);
            $stmt1->execute();

          }else{
            $stmt1 = $conn->prepare('INSERT INTO inventario (FKProducto, Existencias) VALUES (:id,:cantidad)');
            $stmt1->bindValue(':id',$productos[$i]);
            $stmt1->bindValue(':cantidad',$cantidades[$i]);
            $stmt1->execute();
          }
          $sumatoria = 0;
          $stmt = $conn->prepare('SELECT SUM(po.Cantidad) AS suma FROM productos_oc AS po
                                  WHERE po.FKOrdenCompra = :id');
          $stmt->bindValue(':id',$ordenCompra);
          $stmt->execute();
          $row = $stmt->fetch();
          $cantidadTotal = $row['suma'];
          $stmt = $conn->prepare('SELECT SUM(pc.Cantidad_Recibida) AS suma FROM productos_cc AS pc
                                  LEFT JOIN compras_productos AS cp ON pc.FKCompra = cp.PKCompra
                                  WHERE cp.FKOrdenCompra = :id');
          $stmt->bindValue(':id',$ordenCompra);
          $stmt->execute();
          $row = $stmt->fetch();
          $cantidadRecibida = $row['suma'];
          /*
          while($row = $stmt->fetch()){
            $sumatoria += $row['Cantidad_Recibida'];
            $porcentaje = ($sumatoria/$row['Cantidad'])*100;
          }*/

        }
        $porcentaje = round(($cantidadRecibida/$cantidadTotal)*100);


        if($porcentaje >= 100){
          $stmt1 = $conn->prepare('UPDATE orden_compra SET Estatus= :estatus WHERE PKOrdenCompra = :id');
          $stmt1->bindValue(':id',$ordenCompra);
          $stmt1->bindValue(':estatus',2);
          $stmt1->execute();
        }

        //Agregar alerta de movimiento
        $stmt = $conn->prepare('INSERT INTO bitacora_compras_productos (FKUsuario,Fecha_Movimiento,FKMensaje,FKCompraProducto) VALUES (:usuario,:fecha,:mensaje,:compra)');
        $stmt->bindValue(':usuario',$_SESSION['PKUsuario']);
        $stmt->bindValue(':fecha',date('Y-m-d'));
        $stmt->bindValue(':mensaje',10);
        $stmt->bindValue(':compra',$idLast);
        $stmt->execute();

      //echo $idLast;

    }catch(PDOException $ex){
      echo $ex->getMessage();
    }

?>

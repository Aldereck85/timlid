<?php
  require_once('../../../include/db-conn.php');
  $id = (int)$_POST['txtId'];
  $pieza = (int)$_POST['cmbPieza'];
  $cantidad = (int)$_POST['txtCantidad'];

  try{

    $stmt = $conn->prepare('SELECT COUNT(*) FROM piezas_por_producto WHERE FKProductoFabricado = :id AND FKPiezaFabricada= :pieza');
    $stmt->execute(array(':id' => $id, ':pieza' => $pieza));
    $number_of_rows = $stmt->fetchColumn();
    // Si la pieza ya esta ligada al producto
    if($number_of_rows > 0){
      try{
        $stmt = $conn->prepare('SELECT CantidadPiezas FROM piezas_por_producto WHERE FKProductoFabricado = :id AND FKPiezaFabricada= :pieza');
        $stmt->execute(array(':id' => $id, ':pieza' => $pieza));
        $row = $stmt->fetch();
        $cantidadPiezas =  (int)$row['CantidadPiezas'];

        try{
            $totalPiezas = $cantidadPiezas + $cantidad;
            $stmt = $conn->prepare('UPDATE piezas_por_producto set CantidadPiezas= :totalPiezas WHERE FKProductoFabricado = :id AND FKPiezaFabricada= :pieza');
            $stmt->bindValue(':totalPiezas',$totalPiezas);
            $stmt->bindValue(':pieza',$pieza);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
          }catch(Exception $e){
            echo $e->getMessage();
          }
      }catch(Exception $e){
        echo $e->getMessage();
      }
    }else{
      // Si la pieza esta siendo agregada por primera vez
      try{
        $stmt = $conn->prepare('INSERT INTO piezas_por_producto (FKProductoFabricado,FKPiezaFabricada,CantidadPiezas)
        VALUES(:producto,:pieza,:cantidad)');
        $stmt->bindValue(':producto',$id);
        $stmt->bindValue(':pieza',$pieza);
        $stmt->bindValue(':cantidad',$cantidad);
        $stmt->execute();
      }catch(Exception $e){
        echo $e->getMessage();
      }
    }


  }catch(Exception $e){
    echo $e->getMessage();
  }

  /*try{
    $stmt = $conn->prepare('SELECT COUNT(*) FROM productos_en_envio WHERE FKProducto = :id AND FKFactura = :factura AND FKEnvio = :fkenvio');
    $stmt->execute(array(':id' => $idProducto, ':factura' => $factura, ':fkenvio' => $folio));
    $number_of_rows = $stmt->fetchColumn();
    if($number_of_rows > 0)
    {
      try{
        $stmt = $conn->prepare('SELECT PKProductoEnvio,Cajas_por_enviar,Piezas_por_enviar FROM productos_en_envio WHERE FKProducto = :id AND FKFactura = :factura');
        $stmt->execute(array(':id' => $idProducto, ':factura' => $factura));
        $row = $stmt->fetch();
        $idProductoEnviado =  (int)$row['PKProductoEnvio'];
        $cajasEnviadas = (int)$row['Cajas_por_enviar'];
        $piezasEnviadas = (int)$row['Piezas_por_enviar'];

        try{
            $cjsEnviadas = $cajasEnviadas + $cantidad;
            $pzsEnviadas  = $piezasEnviadas + $cantidadPiezas;
            //echo $cjsEnviadas." ".$pzsEnviadas." ".$idProductoEnviado."<br>";
            $stmt = $conn->prepare('UPDATE productos_en_envio set Cajas_por_enviar= :cjsEnviadas,Piezas_por_enviar= :pzsEnviadas WHERE PKProductoEnvio = :idEnvio');
            $stmt->bindValue(':cjsEnviadas',$cjsEnviadas);
            $stmt->bindValue(':pzsEnviadas',$pzsEnviadas);
            $stmt->bindValue(':idEnvio', $idProductoEnviado, PDO::PARAM_INT);
            $stmt->execute();
          }catch(Exception $e){
            echo $e->getMessage();
          }

      }catch(Exception $e){
        echo $e->getMessage();
      }
    }
    else{
      $stmt = $conn->prepare('INSERT INTO piezas_por_producto (FKProductoFabricado,FKPiezaFabricada,CantidadPiezas)
      VALUES(:producto,:pieza,:cantidad)');
      $stmt->bindValue(':producto',$id);
      $stmt->bindValue(':pieza',$pieza);
      $stmt->bindValue(':cantidad',$cantidad);
      $stmt->execute();
    }
  }catch(Exception $e){
    echo $e->getMessage();
  }*/
 ?>

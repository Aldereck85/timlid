<?php
  require_once('../../../include/db-conn.php');
  $folio = (int)$_POST['txtId'];
  $factura= (int)$_POST['txtFolio'];
  $idProducto = (int)$_POST['cmbProducto'];
  $cantidad = (int)$_POST['txtCantidad'];
  if( isset($_POST['txtCantidadPiezas']) ){
    $cantidadPiezas = (int)$_POST['txtCantidadPiezas'];
  }else{
    $cantidadPiezas = 0;
  }


  try{

    /********************************************/
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

        //echo $idProductoEnviado." ".$cajasEnviadas." ".$piezasEnviadas;

      }catch(Exception $e){
        echo $e->getMessage();
      }
    }
    else{
      $stmt = $conn->prepare('INSERT INTO productos_en_envio (FKEnvio,FKProducto,Cajas_por_enviar,Piezas_por_enviar,FKFactura)
      VALUES(:envio,:producto,:cajas,:piezas_por_enviar,:factura)');
      $stmt->bindValue(':envio',$folio);
      $stmt->bindValue(':producto',$idProducto);
      $stmt->bindValue(':cajas',$cantidad);
      $stmt->bindValue(':piezas_por_enviar',$cantidadPiezas);
      $stmt->bindValue(':factura',$factura);
      $stmt->execute();
    }

    /********************************************/

  }catch(Exception $e){
    echo $e->getMessage();
  }
 ?>

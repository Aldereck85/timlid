<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

$token = $_POST['token'];
$form = $_POST['form'];
foreach($form as $f){
  if($f['name'] === 'inp_productos[]'){
    $inp_productos[] = $f['value'];
  }
}
foreach($form as $f){
  if($f['name'] === 'inp_piezas[]'){
    $inp_piezas[] = $f['value'];
  }
}
if(!empty($_SESSION['token_ld10d'])) {
    if (!hash_equals($_SESSION['token_ld10d'], $token)) {
        echo "error-general";
    }
    else{

          require_once('../../../include/db-conn.php');

          $idOrdenPedido = $_POST['id_orden_pedido'];
          $tipo_pedido = $_POST['tipo_pedido'];
          $idCliente = $_POST['cmbCliente']; 
          $idSucursalDestino = $_POST['cmbSucursalDestino']; 
          $idSucursalOrigen = $_POST['cmbSucursalOrigen']; 


          if($tipo_pedido == 1 || $tipo_pedido == 2){
              if(trim($_POST['cmbCliente']) == "" || trim($_POST['cmbCliente']) == 0){
                $idCliente = 0;
              }
              else{
                $idCliente = $_POST['cmbCliente']; 
              }

              if(trim($_POST['cmbSucursalDestino']) == "" || trim($_POST['cmbSucursalDestino']) == 0){
                $idSucursalDestino = 0; 
              }
              else{
                $idSucursalDestino = $_POST['cmbSucursalDestino']; 
              }
            }

          $Observaciones = $_POST['txtObservaciones'];
          $FKUsuario = $_SESSION["PKUsuario"];

          try{
            $conn->beginTransaction();

            date_default_timezone_set('America/Mexico_City');
            $fecha_modificacion = date("Y-m-d H:i:s");

            if($tipo_pedido == 3){
                $stmt = $conn->prepare("UPDATE orden_pedido_por_sucursales SET observaciones = :observaciones, fecha_modificacion = :fechamodificacion, usuario_edito_id = :usuario_edito_id WHERE empresa_id = :empresa_id AND id = :id");
                $stmt->bindValue(':observaciones',$Observaciones);
                $stmt->bindValue(':fechamodificacion',$fecha_modificacion);
                $stmt->bindValue(':usuario_edito_id',$FKUsuario);
                $stmt->bindValue(':empresa_id',$_SESSION['IDEmpresa']);
                $stmt->bindValue(':id',$idOrdenPedido);
                $stmt->execute();
            }
            else{
                $stmt = $conn->prepare("UPDATE orden_pedido_por_sucursales SET observaciones = :observaciones, fecha_modificacion = :fechamodificacion, usuario_edito_id = :usuario_edito_id, sucursal_origen_id = :sucursal_origen_id, sucursal_destino_id = :sucursal_salida_id, cliente_id = :cliente_id WHERE empresa_id = :empresa_id AND id = :id");
                $stmt->bindValue(':observaciones',$Observaciones);
                $stmt->bindValue(':fechamodificacion',$fecha_modificacion);
                $stmt->bindValue(':usuario_edito_id',$FKUsuario);
                $stmt->bindValue(':sucursal_origen_id',$idSucursalOrigen);
                $stmt->bindValue(':sucursal_salida_id',$idSucursalDestino);
                $stmt->bindValue(':cliente_id',$idCliente);
                $stmt->bindValue(':empresa_id',$_SESSION['IDEmpresa']);
                $stmt->bindValue(':id',$idOrdenPedido);
                $stmt->execute();
            }

            $stmt = $conn->prepare("INSERT INTO bitacora_orden_pedido (usuario_id, mensaje_id, orden_pedido_id, created_at, updated_at) VALUES (:fkusuario, :mensaje_id, :orden_pedido_id, :fecha_creacion, :fecha_modificacion)");
            $stmt->bindValue(':fkusuario',$FKUsuario);
            $stmt->bindValue(':mensaje_id',18);
            $stmt->bindValue(':orden_pedido_id', $idOrdenPedido);
            $stmt->bindValue(':fecha_creacion',$_POST['txtFechaGeneracion']);
            $stmt->bindValue(':fecha_modificacion',$fecha_modificacion);
            $stmt->execute(); 

            $cuenta = count($inp_productos);
            $piezas_array = $inp_piezas;
            $producto_array = $inp_productos;


            $stmt = $conn->prepare("DELETE FROM detalle_orden_pedido_por_sucursales WHERE orden_pedido_id = ".$idOrdenPedido);
            $stmt->execute();


            for($x = 0 ; $x < $cuenta; $x++){

              $stmt = $conn->prepare("INSERT INTO detalle_orden_pedido_por_sucursales (producto_id, cantidad_pedida, cantidad_surtida, cantidad_entregada, orden_pedido_id ) VALUES (:producto_id, :cantidad_pedido, :cantidad_surtida, :cantidad_entregada, :orden_pedido_id)");
              $stmt->bindValue(':producto_id',$producto_array[$x]);
              $stmt->bindValue(':cantidad_pedido',$piezas_array[$x]);
              $stmt->bindValue(':cantidad_surtida',0);
              $stmt->bindValue(':cantidad_entregada',0);
              $stmt->bindValue(':orden_pedido_id',$idOrdenPedido);
              $stmt->execute();

            }

            if($conn->commit()){
              echo "exito";
            }
          }catch(PDOException $ex){
            echo $ex->getMessage();
            $conn->rollBack(); 
          }
    }
}
else{
    echo "error-general";
}

?>

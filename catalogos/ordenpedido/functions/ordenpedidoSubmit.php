<?php
session_start();
//print_r($_POST);
$jwt_ruta = "../../../";
require_once '../../jwt.php';

$token = $_POST["csr_token_78L4"];

if(!empty($_SESSION['token_ld10d'])) {
    if (!hash_equals($_SESSION['token_ld10d'], $token)) {
        echo "error-general";
    }
    else{

          require_once('../../../include/db-conn.php');


          $idSucursalOrigen = $_POST['cmbSucursalOrigen']; 

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

          if(trim($_POST['cmbVendedor']) == "" || trim($_POST['cmbVendedor']) == 0){
            $idVendedor = 0; 
          }
          else{
            $idVendedor = $_POST['cmbVendedor']; 
          }

          $FechaGeneracion = $_POST['txtFechaGeneracion'];
          $FechaModificacion = $_POST['txtFechaGeneracion'];
          $FechaEntrega = $_POST['txtFechaEntrega'];
          $Observaciones = $_POST['txtObservaciones'];
          $FKUsuario = $_SESSION["PKUsuario"];

          try{
            $conn->beginTransaction();

            //obtener el ultimo id generado por empresa
            $stmt = $conn->prepare("SELECT id_orden_pedido_empresa FROM orden_pedido_por_sucursales WHERE empresa_id = :empresa_id ORDER BY id_orden_pedido_empresa DESC LIMIT 1");
            $stmt->bindValue(':empresa_id',$_SESSION['IDEmpresa']);
            $stmt->execute();
            $rowidordenpedido = $stmt->fetch();
            $idordenpedidoempresa = $rowidordenpedido['id_orden_pedido_empresa'] + 1;

            date_default_timezone_set('America/Mexico_City');
            $fecha_alta = date("Y-m-d H:i:s");
            $stmt = $conn->prepare("INSERT INTO orden_pedido_por_sucursales (id_orden_pedido_empresa, observaciones, fecha_captura, fecha_modificacion, tipo_orden_pedido, fecha_entrega, numero_cotizacion, numero_venta_directa, usuario_creo_id, usuario_edito_id, vendedor_id, sucursal_origen_id, sucursal_destino_id, cliente_id, empresa_id, estatus_orden_pedido_id, manual ) VALUES (:id_orden_pedido_empresa, :observaciones, :fechacaptura, :fechamodificacion, :tipo_orden_pedido, :fecha_entrega, :numero_cotizacion, :numero_venta_directa, :usuario_creo_id, :usuario_edito_id, :vendedor_id, :sucursal_origen_id, :sucursal_salida_id, :cliente_id, :empresa_id, :estatus_orden_pedido_id, 1)");
            $stmt->bindValue(':id_orden_pedido_empresa',$idordenpedidoempresa);
            $stmt->bindValue(':observaciones',$Observaciones);
            $stmt->bindValue(':fechacaptura',$fecha_alta);
            $stmt->bindValue(':fechamodificacion',$fecha_alta);
            $stmt->bindValue(':tipo_orden_pedido',3);
            $stmt->bindValue(':fecha_entrega',$FechaEntrega);
            $stmt->bindValue(':numero_cotizacion', "");
            $stmt->bindValue(':numero_venta_directa', "");
            $stmt->bindValue(':usuario_creo_id', $FKUsuario);
            $stmt->bindValue(':usuario_edito_id',$FKUsuario);
            $stmt->bindValue(':vendedor_id', $idVendedor);
            $stmt->bindValue(':sucursal_origen_id',$idSucursalOrigen);
            $stmt->bindValue(':sucursal_salida_id',$idSucursalDestino);
            $stmt->bindValue(':cliente_id',$idCliente);
            $stmt->bindValue(':empresa_id',$_SESSION['IDEmpresa']);
            $stmt->bindValue(':estatus_orden_pedido_id', 1);
            $stmt->execute();

            $idOrdenPedido = $conn->lastInsertId();

            $stmt = $conn->prepare("INSERT INTO bitacora_orden_pedido (usuario_id, mensaje_id, orden_pedido_id, created_at, updated_at) VALUES (:fkusuario, :mensaje_id, :orden_pedido_id, :fecha_creacion, :fecha_modificacion)");
            $stmt->bindValue(':fkusuario',$FKUsuario);
            $stmt->bindValue(':mensaje_id',17);
            $stmt->bindValue(':orden_pedido_id', $idOrdenPedido);
            $stmt->bindValue(':fecha_creacion',$fecha_alta);
            $stmt->bindValue(':fecha_modificacion',$fecha_alta);
            $stmt->execute(); 

            $cuenta = count($_POST['inp_productos']);

            $piezas_array = $_POST['inp_piezas'];
            $producto_array = $_POST['inp_productos'];

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
              $idordenpedidoempresazero = str_pad($idordenpedidoempresa, 10, "0", STR_PAD_LEFT);
              echo $idordenpedidoempresazero;
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

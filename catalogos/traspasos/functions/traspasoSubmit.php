<?php
session_start();
date_default_timezone_set('America/Mexico_City');
//print_r($_POST);
$jwt_ruta = "../../../";
require_once '../../jwt.php';

$token = $_POST["csr_token_78L4"];

if (!empty($_SESSION['token_ld10d'])) {
  if (!hash_equals($_SESSION['token_ld10d'], $token)) {
    echo "error-general";
  } else {

    require_once('../../../include/db-conn.php');


    $idSucursalOrigen = $_POST['cmbSucursalOrigen'];

    if (trim($_POST['cmbSucursalDestino']) == "" || trim($_POST['cmbSucursalDestino']) == 0) {
      $idSucursalDestino = 0;
    } else {
      $idSucursalDestino = $_POST['cmbSucursalDestino'];
    }

    $FechaGeneracion = $_POST['txtFechaGeneracion'];
    $FechaModificacion = $_POST['txtFechaGeneracion'];
    $Observaciones = $_POST['txtObservaciones'];
    $FKUsuario = $_SESSION["PKUsuario"];

    try {
      $conn->beginTransaction();

      //obtener el ultimo id generado por empresa
      $stmt = $conn->prepare("SELECT id_orden_pedido_empresa FROM orden_pedido_por_sucursales WHERE empresa_id = :empresa_id ORDER BY id_orden_pedido_empresa DESC LIMIT 1");
      $stmt->bindValue(':empresa_id', $_SESSION['IDEmpresa']);
      $stmt->execute();
      $rowidordenpedido = $stmt->fetch();
      $idordenpedidoempresa = $rowidordenpedido['id_orden_pedido_empresa'] + 1;

      date_default_timezone_set('America/Mexico_City');
      $fecha_alta = date("Y-m-d H:i:s");
      $stmt = $conn->prepare("INSERT INTO orden_pedido_por_sucursales (id_orden_pedido_empresa,  tipo_pedido, observaciones, fecha_captura, fecha_modificacion, numero_cotizacion, numero_venta_directa, usuario_creo_id, usuario_edito_id, sucursal_origen_id, sucursal_destino_id, cliente_id, empresa_id, estatus_orden_pedido_id ) VALUES (:id_orden_pedido_empresa, :tipo_pedido, :observaciones, :fechacaptura, :fechamodificacion, :numero_cotizacion, :numero_venta_directa, :usuario_creo_id, :usuario_edito_id, :sucursal_origen_id, :sucursal_salida_id, :cliente_id, :empresa_id, :estatus_orden_pedido_id )");
      $stmt->bindValue(':id_orden_pedido_empresa', $idordenpedidoempresa);
      $stmt->bindValue(':tipo_pedido', 1);
      $stmt->bindValue(':observaciones', $Observaciones);
      $stmt->bindValue(':fechacaptura', $fecha_alta);
      $stmt->bindValue(':fechamodificacion', $fecha_alta);
      $stmt->bindValue(':numero_cotizacion', "");
      $stmt->bindValue(':numero_venta_directa', "");
      $stmt->bindValue(':usuario_creo_id', $FKUsuario);
      $stmt->bindValue(':usuario_edito_id', $FKUsuario);
      $stmt->bindValue(':sucursal_origen_id', $idSucursalOrigen);
      $stmt->bindValue(':sucursal_salida_id', $idSucursalDestino);
      $stmt->bindValue(':cliente_id', 0);
      $stmt->bindValue(':empresa_id', $_SESSION['IDEmpresa']);
      $stmt->bindValue(':estatus_orden_pedido_id', 5);
      $stmt->execute();

      $idOrdenPedido = $conn->lastInsertId();

      $stmt = $conn->prepare("INSERT INTO bitacora_orden_pedido (usuario_id, mensaje_id, orden_pedido_id, created_at, updated_at) VALUES (:fkusuario, :mensaje_id, :orden_pedido_id, :fecha_creacion, :fecha_modificacion)");
      $stmt->bindValue(':fkusuario', $FKUsuario);
      $stmt->bindValue(':mensaje_id', 17);
      $stmt->bindValue(':orden_pedido_id', $idOrdenPedido);
      $stmt->bindValue(':fecha_creacion', $fecha_alta);
      $stmt->bindValue(':fecha_modificacion', $fecha_alta);
      $stmt->execute();

      $cuenta = count($_POST['inp_productos']);

      $piezas_array = $_POST['inp_piezas'];
      $producto_array = $_POST['inp_productos'];
      $lotes_array = $_POST['inp_lotes'];

      $cuentaLotes = count($_POST['inp_lotes']);

      for ($x = 0; $x < $cuenta; $x++) {
        $stmt = $conn->prepare("SELECT caducidad, clave_producto FROM existencia_por_productos WHERE sucursal_id = :sucursal_id AND producto_id = :producto_id AND numero_lote = :numero_lote");
        $stmt->bindValue(':sucursal_id', $idSucursalOrigen);
        $stmt->bindValue(':producto_id', $producto_array[$x]);
        $stmt->bindValue(':numero_lote', $lotes_array[$x]);
        $stmt->execute();
        $rowExist = $stmt->fetch();
    
        $stmt = $conn->prepare("INSERT INTO inventario_salida_por_sucursales (clave, numero_lote, numero_serie, cantidad, fecha_salida, folio_salida, cantidad_entrada, observaciones, tipo_salida, orden_pedido_id, usuario_creo_id, sucursal_id, caducidad, is_movimiento, estatus) VALUES (:clave, :numero_lote, '', :cantidad, :fecha_salida, :folio_salida, :cantidad_entrada, :observaciones, 6, :orden_pedido_id, :usuario_creo_id, :sucursal_id, :caducidad, 0, 0)");
        $stmt->bindValue(':clave', $rowExist['clave_producto']);
        $stmt->bindValue(':numero_lote', $lotes_array[$x]);
        $stmt->bindValue(':cantidad', $piezas_array[$x]);
        $stmt->bindValue(':fecha_salida', $fecha_alta);
        $stmt->bindValue(':folio_salida', strval($idordenpedidoempresa).'-1');
        $stmt->bindValue(':cantidad_entrada', $piezas_array[$x]);
        $stmt->bindValue(':observaciones', $Observaciones);
        $stmt->bindValue(':orden_pedido_id', $idOrdenPedido);
        $stmt->bindValue(':usuario_creo_id', $FKUsuario);
        $stmt->bindValue(':sucursal_id', $idSucursalOrigen);
        $stmt->bindValue(':caducidad', $rowExist['caducidad']);
        $stmt->execute();
        $salida_id = $conn->lastInsertId();
        /* echo "id salida: " . $salida_id . "<br>";
        echo "id sucursal origen: " . $idSucursalOrigen . "<br>";
        echo "id producto: " . $producto_array[$x] . "<br>";
        echo "lote: " . $lotes_array[$x] . "<br>";
        echo "cantidad: " . $piezas_array[$x] . "<br>"; */
        
        $stmt = $conn->prepare("UPDATE existencia_por_productos SET existencia = existencia - :existencia WHERE sucursal_id = :sucursal_id AND producto_id = :producto_id AND numero_lote = :numero_lote");
        $stmt->bindValue(':sucursal_id', $idSucursalOrigen);
        $stmt->bindValue(':producto_id', $producto_array[$x]);
        $stmt->bindValue(':numero_lote', $lotes_array[$x]);
        $stmt->bindValue(':existencia', $piezas_array[$x]);
        $stmt->execute();

        $stmt = $conn->prepare("SELECT concat('TP',LPAD(convert(ifnull(max(SUBSTRING(ieps.folio_entrada,3)),'0'),UNSIGNED ) + 1,6,'0')) as folio_entrada FROM inventario_entrada_por_sucursales ieps INNER JOIN sucursales s on ieps.sucursal_id = s.id WHERE s.empresa_id = :empresa_id AND ieps.ajuste_id IS NULL AND ieps.cambio_lote_id IS NULL");
        $stmt->bindValue(':empresa_id', $_SESSION['IDEmpresa']);
        $stmt->execute();
        $rowFolio = $stmt->fetch();
        /* echo "folio entrada: " . $rowFolio['folio_entrada'] . "<br>"; */

        $stmt = $conn->prepare("INSERT INTO inventario_entrada_por_sucursales (fecha_captura, cantidad, numero_documento, tipo_entrada, clave, numero_lote, numero_serie, inventario_salida_id, cantidad_salida, usuario_creo_id, sucursal_origen_id, sucursal_id, observaciones, orden_pedido_id, folio_entrada, is_movimiento) VALUES (:fecha_captura, :cantidad, '', 6, :clave, :numero_lote, :numero_serie, :inventario_salida_id, :cantidad_salida, :usuario_creo_id, :sucursal_origen_id, :sucursal_id, :observaciones, :orden_pedido_id, :folio_entrada, 0)");
        $stmt->bindValue(':fecha_captura', $fecha_alta);
        $stmt->bindValue(':cantidad', $piezas_array[$x]);
        $stmt->bindValue(':clave', $rowExist['clave_producto']);
        $stmt->bindValue(':numero_lote', $lotes_array[$x]);
        $stmt->bindValue(':numero_serie', '');
        $stmt->bindValue(':inventario_salida_id', $salida_id);
        $stmt->bindValue(':cantidad_salida', $piezas_array[$x]);
        $stmt->bindValue(':usuario_creo_id', $FKUsuario);
        $stmt->bindValue(':sucursal_origen_id', $idSucursalOrigen);
        $stmt->bindValue(':sucursal_id', $idSucursalDestino);
        $stmt->bindValue(':observaciones', $Observaciones);
        $stmt->bindValue(':orden_pedido_id', $idOrdenPedido);
        $stmt->bindValue(':folio_entrada', $rowFolio['folio_entrada']);
        $stmt->execute();
       /*  echo "id sucursal destino: " . $idSucursalDestino . "<br>"; */

        $stmt = $conn->prepare("SELECT id FROM existencia_por_productos WHERE sucursal_id = :sucursal_id AND producto_id = :producto_id AND numero_lote = :numero_lote");
        $stmt->bindValue(':sucursal_id', $idSucursalDestino);
        $stmt->bindValue(':producto_id', $producto_array[$x]);
        $stmt->bindValue(':numero_lote', $lotes_array[$x]);
        $stmt->execute();
        $numExt = $stmt->rowCount();
     /*    echo "rowCount existencias: " . $numExt . "<br>"; */

        if($numExt < 1){
            $stmt = $conn->prepare("INSERT INTO existencia_por_productos (numero_lote, numero_serie, caducidad, existencia, sucursal_id, producto_id, clave_producto) VALUES (:numero_lote, '', :caducidad, :existencia, :sucursal_id, :producto_id, :clave_producto)");
            $stmt->bindValue(':numero_lote', $lotes_array[$x]);
            $stmt->bindValue(':caducidad', $rowExist['caducidad']);
            $stmt->bindValue(':existencia', $piezas_array[$x]);
            $stmt->bindValue(':sucursal_id', $idSucursalDestino);
            $stmt->bindValue(':producto_id', $producto_array[$x]);
            $stmt->bindValue(':clave_producto', $rowExist['clave_producto']);
            $stmt->execute();
            /* echo "simon $numExt es menor a 1" . "<br>"; */
        }else{
            $stmt = $conn->prepare("UPDATE existencia_por_productos SET existencia = existencia + :existencia WHERE sucursal_id = :sucursal_id AND producto_id = :producto_id AND numero_lote = :numero_lote");
            $stmt->bindValue(':sucursal_id', $idSucursalDestino);
            $stmt->bindValue(':producto_id', $producto_array[$x]);
            $stmt->bindValue(':numero_lote', $lotes_array[$x]);
            $stmt->bindValue(':existencia', $piezas_array[$x]);
            $stmt->execute();
            /* echo "nel $numExt es mayor a 1" . "<br>"; */
        }

      }

      $stmt = $conn->prepare("INSERT INTO detalle_orden_pedido_por_sucursales (
        producto_id, 
        cantidad_pedida, 
        cantidad_surtida, 
        cantidad_entregada, 
        orden_pedido_id 
      ) 
      SELECT 
        p.PKProducto, 
        (select sum(isps.cantidad) from inventario_salida_por_sucursales isps where isps.clave = p.ClaveInterna and isps.orden_pedido_id = :orden_pedido_id) as cantidad, 
        (select sum(isps.cantidad) from inventario_salida_por_sucursales isps where isps.clave = p.ClaveInterna and isps.orden_pedido_id = :orden_pedido_id2) as cantidad,
        (select sum(isps.cantidad) from inventario_salida_por_sucursales isps where isps.clave = p.ClaveInterna and isps.orden_pedido_id = :orden_pedido_id3) as cantidad,
        isps.orden_pedido_id
      from 
        inventario_salida_por_sucursales isps inner join productos p on p.ClaveInterna = isps.clave and isps.orden_pedido_id = :orden_pedido_id4   
        group by p.PKProducto");
      $stmt->bindValue(':orden_pedido_id', $idOrdenPedido);
      $stmt->bindValue(':orden_pedido_id2', $idOrdenPedido);
      $stmt->bindValue(':orden_pedido_id3', $idOrdenPedido);
      $stmt->bindValue(':orden_pedido_id4', $idOrdenPedido);
      $stmt->execute();

      /* NOTIFICACIONES */
      $timestamp = date('Y-m-d H:i:s');
      /* SELECCIONAMOS LOS USUARIOS DE ESA COTIZACION */
      $stmt = $conn->prepare('SELECT id FROM usuarios WHERE empresa_id = :empresaId AND role_id = :roleId');
      $stmt->execute([':empresaId' => $_SESSION['IDEmpresa'], ':roleId' => 6]);
      $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

      foreach ($empleados as $empleado) {
        /* INSERTAMOS LA NOTIFICACION EN LA BD */
        $stmt = $conn->prepare('INSERT INTO notificaciones (tipo_notificacion, detalle_tipo_notificacion, id_elemento, created_at, usuario_recibe) VALUES (:tipoNot, :detaleNot, :idElem, :fecha, :usrRecibe)');
        $stmt->execute([':tipoNot' => 6, ':detaleNot' => 13, ':idElem' => $idOrdenPedido, ':fecha' => $timestamp, ':usrRecibe' => $empleado['id']]);
      }

      if ($conn->commit()) {
        $idordenpedidoempresazero = str_pad($idordenpedidoempresa, 10, "0", STR_PAD_LEFT);
        echo '{"numeroOrdenEmpresa": "'.$idordenpedidoempresazero.'", "idOrden": "'.$idOrdenPedido.'"}';
      }
    } catch (PDOException $ex) {
      echo $ex->getMessage();
      $conn->rollBack();
    }
  }
} else {
  echo "error-general";
}

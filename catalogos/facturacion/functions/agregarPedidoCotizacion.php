<?php
session_start();

require_once('../../../include/db-conn.php');
date_default_timezone_set('America/Mexico_City');

if (isset($_REQUEST['idCotizacion'])) {
  $id =  $_REQUEST['idCotizacion'];
  $FKUsuario = $_SESSION["PKUsuario"];
  $FechaIngreso = date("Y-m-d H:i:s");

  try {
    $conn->beginTransaction();

    $stmt = $conn->prepare('UPDATE cotizacion SET estatus_cotizacion_id = 2,flujo_almacen = 1 WHERE PKCotizacion = :id AND empresa_id = ' . $_SESSION['IDEmpresa']);

    if ($stmt->execute(array(':id' => $id))) {

      $stmt = $conn->prepare("INSERT INTO bitacora_cotizaciones (FKUsuario, Fecha_Movimiento, FKMensaje, FKCotizacion) VALUES (:fkusuario, :fechamovimiento, :fkmensaje, :fkcotizacion)");
      $stmt->bindValue(':fkusuario', $FKUsuario);
      $stmt->bindValue(':fechamovimiento', $FechaIngreso);
      $stmt->bindValue(':fkmensaje', 16);
      $stmt->bindValue(':fkcotizacion', $id);
      $stmt->execute();

      ///GENERACION ORDEN DE PEDIDO
      //obtener numero de cotizacion
      $stmt = $conn->prepare("SELECT c.id_cotizacion_empresa, c.FKSucursal, c.FKCliente, s.activar_inventario FROM cotizacion as c LEFT JOIN sucursales as s ON c.FKSucursal = s.id WHERE c.empresa_id = :empresa_id AND c.PKCotizacion = :cotizacion_id");
      $stmt->bindValue(':empresa_id', $_SESSION['IDEmpresa']);
      $stmt->bindValue(':cotizacion_id', $id);
      $stmt->execute();
      $row_cotizacion = $stmt->fetch();
      $id_cotizacion_empresa = $row_cotizacion['id_cotizacion_empresa'];
      $id_sucursal_origen = $row_cotizacion['FKSucursal'];
      $id_cliente = $row_cotizacion['FKCliente'];
      $activar_inventario = $row_cotizacion['activar_inventario'];

      $stmt = $conn->prepare("SELECT id  FROM orden_pedido_por_sucursales WHERE empresa_id = :empresa_id AND numero_cotizacion = :cotizacion_id");
      $stmt->bindValue(':empresa_id', $_SESSION['IDEmpresa']);
      $stmt->bindValue(':cotizacion_id', $id); //se busca el id unico de la cotizacion por empresa
      $stmt->execute();
      $existe = $stmt->rowCount();
      $row_orden_pedido = $stmt->fetch();

      //comprueba si la cotizacion tiene solo servicios, si es así ésta no genera pedido
      $stmt = $conn->prepare("SELECT p.FKTipoProducto from productos p 
                                inner join detalle_cotizacion dc on p.PKProducto = dc.FKProducto
                              where dc.FKCotizacion = :numero_cotizacion 
                              group by FKTipoProducto;");
      $stmt->bindValue(':numero_cotizacion', $id);
      $stmt->execute();
      $tiposProdRes = $stmt->rowCount();
      $tipoProdObtenido = $stmt->fetch()['FKTipoProducto'];

      if($tiposProdRes == 1 && $tipoProdObtenido == 5){
        $continua = 0;
      }else{
        $continua = 1;
      }


      if ($activar_inventario == 1 && $continua == 1) {
        if ($existe < 1) {
          
          //obtener el ultimo id generado por empresa
          $stmt = $conn->prepare("SELECT id_orden_pedido_empresa FROM orden_pedido_por_sucursales WHERE empresa_id = :empresa_id ORDER BY id_orden_pedido_empresa DESC LIMIT 1");
          $stmt->bindValue(':empresa_id', $_SESSION['IDEmpresa']);
          $stmt->execute();
          $rowidordenpedido = $stmt->fetch();
          $idordenpedidoempresa = $rowidordenpedido['id_orden_pedido_empresa'] + 1;

          $FKUsuario = $_SESSION["PKUsuario"];

          $fecha_alta = date("Y-m-d H:i:s");
          $stmt = $conn->prepare("INSERT INTO orden_pedido_por_sucursales (id_orden_pedido_empresa, fecha_captura, fecha_modificacion, tipo_pedido, numero_cotizacion, numero_venta_directa, usuario_creo_id, usuario_edito_id, sucursal_origen_id, sucursal_destino_id, cliente_id, empresa_id, estatus_orden_pedido_id, estatus_factura_id ) VALUES (:id_orden_pedido_empresa, :fechacaptura, :fechamodificacion, :tipo_pedido, :numero_cotizacion, :numero_venta_directa, :usuario_creo_id, :usuario_edito_id, :sucursal_origen_id, :sucursal_salida_id, :cliente_id, :empresa_id, :estatus_orden_pedido_id, :estatus_factura_id)");
          $stmt->bindValue(':id_orden_pedido_empresa', $idordenpedidoempresa);
          $stmt->bindValue(':fechacaptura', $fecha_alta);
          $stmt->bindValue(':fechamodificacion', $fecha_alta);
          $stmt->bindValue(':tipo_pedido', 3);
          $stmt->bindValue(':numero_cotizacion', $id);
          $stmt->bindValue(':numero_venta_directa', "");
          $stmt->bindValue(':usuario_creo_id', $FKUsuario);
          $stmt->bindValue(':usuario_edito_id', $FKUsuario);
          $stmt->bindValue(':sucursal_origen_id', $id_sucursal_origen);
          $stmt->bindValue(':sucursal_salida_id', 0);
          $stmt->bindValue(':cliente_id', $id_cliente);
          $stmt->bindValue(':empresa_id', $_SESSION['IDEmpresa']);
          $stmt->bindValue(':estatus_orden_pedido_id', 1);
          $stmt->bindValue(':estatus_factura_id', 2);
          $stmt->execute();

          $idOrdenPedido = $conn->lastInsertId();

          $stmt = $conn->prepare("INSERT INTO bitacora_orden_pedido (usuario_id, mensaje_id, orden_pedido_id, created_at, updated_at) VALUES (:fkusuario, :mensaje_id, :orden_pedido_id, :fecha_creacion, :fecha_modificacion)");
          $stmt->bindValue(':fkusuario', $FKUsuario);
          $stmt->bindValue(':mensaje_id', 17);
          $stmt->bindValue(':orden_pedido_id', $idOrdenPedido);
          $stmt->bindValue(':fecha_creacion', $fecha_alta);
          $stmt->bindValue(':fecha_modificacion', $fecha_alta);
          $stmt->execute();

          //Obtener los detalles de la cotizacion
          $stmt = $conn->prepare("SELECT Cantidad, FKProducto FROM detalle_cotizacion WHERE FKCotizacion = :cotizacion_id");
          $stmt->bindValue(':cotizacion_id', $id);
          $stmt->execute();
          $row_detalle = $stmt->fetchAll();

          foreach ($row_detalle as $rd) {

            $stmt = $conn->prepare("INSERT INTO detalle_orden_pedido_por_sucursales (producto_id, cantidad_pedida, cantidad_surtida, cantidad_entregada, orden_pedido_id ) VALUES (:producto_id, :cantidad_pedido, :cantidad_surtida, :cantidad_entregada, :orden_pedido_id)");
            $stmt->bindValue(':producto_id', $rd["FKProducto"]);
            $stmt->bindValue(':cantidad_pedido', $rd["Cantidad"]);
            $stmt->bindValue(':cantidad_surtida', 0);
            $stmt->bindValue(':cantidad_entregada', 0);
            $stmt->bindValue(':orden_pedido_id', $idOrdenPedido);
            $stmt->execute();
          }

          // NOTIFICACIONES 
          $timestamp = date('Y-m-d H:i:s');

          // SELECCIONAMOS LOS USUARIOS DE ESA COTIZACION 
          $stmt = $conn->prepare('SELECT FKUsuarioCreacion, FKUsuarioEdicion FROM cotizacion WHERE PKCotizacion = :cotizacion');
          $stmt->execute([':cotizacion' => $id]);
          $res = $stmt->fetch(PDO::FETCH_ASSOC);

          // INSERTAMOS LA NOTIFICACION EN LA BD 
          $queryNot = 'INSERT INTO notificaciones (tipo_notificacion, detalle_tipo_notificacion, id_elemento, created_at, usuario_creo, usuario_recibe) VALUES (:tipoNot, :detaleNot, :idElem, :fecha, :usrCreo, :usrRecibe)';
          if ($res['FKUsuarioCreacion'] === $res['FKUsuarioEdicion']) {
            $stmt = $conn->prepare($queryNot);
            $stmt->execute([':tipoNot' => 4, ':detaleNot' => 6, ':idElem' => $id, ':fecha' => $timestamp, ':usrCreo' => $_SESSION['PKUsuario'], ':usrRecibe' => $res['FKUsuarioCreacion']]);
          } else {
            $stmt = $conn->prepare($queryNot);
            $stmt->execute([':tipoNot' => 4, ':detaleNot' => 6, ':idElem' => $id, ':fecha' => $timestamp, ':usrCreo' => $_SESSION['PKUsuario'], ':usrRecibe' => $res['FKUsuarioCreacion']]);

            $stmt = $conn->prepare($queryNot);
            $stmt->execute([':tipoNot' => 4, ':detaleNot' => 6, ':idElem' => $id, ':fecha' => $timestamp, ':usrCreo' => $_SESSION['PKUsuario'], ':usrRecibe' => $res['FKUsuarioEdicion']]);
          }

          // SELECCIONAMOS LOS USUARIOS DE TIPO ALMACEN 
          $stmt = $conn->prepare('SELECT id FROM usuarios WHERE empresa_id = :empresaId AND role_id = :roleId');
          $stmt->execute([':empresaId' => $_SESSION['IDEmpresa'], ':roleId' => 6]);
          $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

          foreach ($empleados as $empleado) {
            // INSERTAMOS LA NOTIFICACION EN LA BD 
            $stmt = $conn->prepare('INSERT INTO notificaciones (tipo_notificacion, detalle_tipo_notificacion, id_elemento, created_at, usuario_recibe) VALUES (:tipoNot, :detaleNot, :idElem, :fecha, :usrRecibe)');
            $stmt->execute([':tipoNot' => 6, ':detaleNot' => 13, ':idElem' => $idOrdenPedido, ':fecha' => $timestamp, ':usrRecibe' => $empleado['id']]);
          }

        } else {
          //en caso de que ya se haya dado una orden de pedido
          $stmt = $conn->prepare('UPDATE orden_pedido_por_sucursales SET estatus_orden_pedido_id = 2 WHERE id = :idOrdenPedido');
          $stmt->execute(array(':idOrdenPedido' => $row_orden_pedido['id']));
        }
      }
      ///FIN GENERACION ORDEN DE PEDIDO

      if ($conn->commit()) {
        echo "exito";

      } else {
        $conn->rollBack();
        echo "fallo";
      }
    } else {
      $conn->rollBack();
      echo "fallo";
    }
  } catch (Exception $ex) {
    echo $ex->getMessage();
    $conn->rollBack();
    echo "fallo";
    exit;
  }
}

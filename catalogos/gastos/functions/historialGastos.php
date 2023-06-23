<?php
session_start();
require_once '../../../include/db-conn.php';
$idempresa = $_SESSION["IDEmpresa"];
$cuenta = 0;
if (isset($_REQUEST['cuenta'])) {
    $cuenta = $_REQUEST['cuenta'];
}

$table = "";
$fila = 0;
if ($cuenta == 0) {
    $stmt = $conn->prepare("SELECT 
                                mcbe.folio,
                                mcbe.PKMovimiento,
                                mcbe.Fecha,
                                mcbe.Descripcion,
                                mcbe.Retiro,
                                mcbe.Saldo,
                                mcbe.Referencia,
                                CONCAT(emp.Nombres, ' ', emp.PrimerApellido, ' ', emp.SegundoApellido) AS Responsable,
                                mcbe.Comprobado,
                                mcbe.cuenta_origen_id,
                                cbe.Nombre,
                                p.NombreComercial,
                                mcbe.tipo_movimiento_id,
                                ps.identificador_pago,
                                ps.idpagos,
                                ps.tipo_pago,
                                cg.Nombre as nombreCategoria
                            FROM movimientos_cuentas_bancarias_empresa mcbe
                                INNER JOIN cuentas_bancarias_empresa cbe
                                ON mcbe.cuenta_origen_id=cbe.PKCuenta
                                LEFT JOIN empleados emp
                                ON mcbe.FKResponsable = emp.PKEmpleado
                                left JOIN relacion_tipo_empleado rte
                                ON emp.PKEmpleado = rte.empleado_id
                                left join proveedores p on mcbe.FKProveedor = p.PKProveedor
                                left join pagos ps on mcbe.id_pago = ps.idpagos
                                left join categoria_gastos cg on  mcbe.FKCategoria = cg.PKCategoria
                            WHERE cbe.empresa_id = ? 
                            AND (mcbe.tipo_movimiento_id=2 or (mcbe.tipo_movimiento_id=5 and ps.tipo_movimiento = 1))
                            AND cbe.tipo_cuenta!=2
                            group by mcbe.PKMovimiento
                            ORDER BY mcbe.PKMovimiento DESC");
    $stmt->bindParam(1, $idempresa, PDO::PARAM_INT);
    //$stmt->bindParam(2, $idempresa, PDO::PARAM_INT);
    $stmt->execute();
} else {
    $stmt = $conn->prepare("SELECT 
                                mcbe.folio,
                                mcbe.PKMovimiento,
                                mcbe.Fecha,
                                mcbe.Descripcion,
                                mcbe.Retiro,
                                mcbe.Saldo,
                                mcbe.Referencia,
                                CONCAT(emp.Nombres, ' ', emp.PrimerApellido, ' ', emp.SegundoApellido) AS Responsable,
                                mcbe.Comprobado,
                                mcbe.cuenta_origen_id,
                                cbe.Nombre,
                                p.NombreComercial,
                                mcbe.tipo_movimiento_id,
                                ps.identificador_pago,
                                ps.idpagos,
                                ps.tipo_pago,
                                cg.Nombre as nombreCategoria
                            FROM movimientos_cuentas_bancarias_empresa mcbe
                                INNER JOIN cuentas_bancarias_empresa cbe
                                ON mcbe.cuenta_origen_id=cbe.PKCuenta
                                LEFT JOIN empleados emp
                                ON mcbe.FKResponsable = emp.PKEmpleado
                                LEFT JOIN relacion_tipo_empleado rte
                                ON emp.PKEmpleado = rte.empleado_id
                                left join proveedores p on mcbe.FKProveedor = p.PKProveedor
                                left join pagos ps on mcbe.id_pago = ps.idpagos
                                left join categoria_gastos cg on  mcbe.FKCategoria = cg.PKCategoria
                            WHERE cbe.empresa_id = ? 
                            AND (mcbe.tipo_movimiento_id=2 or (mcbe.tipo_movimiento_id=5 and ps.tipo_movimiento = 1))
                            AND cbe.tipo_cuenta!=2
                            AND mcbe.cuenta_origen_id = ?
                            group by mcbe.PKMovimiento
                            ORDER BY mcbe.PKMovimiento DESC");
    //$stmt->bindParam(1, $idempresa, PDO::PARAM_INT);
    $stmt->bindParam(1, $idempresa, PDO::PARAM_INT);
    $stmt->bindParam(2, $cuenta, PDO::PARAM_INT);
    $stmt->execute();
}


$est = "";
while ($row = $stmt->fetch()) {
    if ($row['Retiro'] == null) {
        $retiro = $row['Retiro'];
    } else {
        $retiro = "$" . number_format($row['Retiro'], 2);
    }

    if ($row['Saldo'] == null) {
        $saldo = $row['Saldo'];
    } else {
        $saldo = "$" . number_format($row['Saldo'], 2);
    }
    $fila++;
    if ($row['Comprobado'] == "0") {
        $comprobar = '<label for=\"' . $row['PKMovimiento'] . '-' . $row['cuenta_origen_id'] . '\" class=\"pointer referencia\">Sin comprobar <i class=\"fas fa-cloud-upload-alt\"></i></label><input accept=\"image/*, .pdf, .xlsx, .xml\" id=\"' . $row['PKMovimiento'] . '-' . $row['cuenta_origen_id'] . '\" name=\"file-input\" type=\"file\" onchange=\"subirReferencia(this);\" style=\"display:none\"/><input  class=\"btnEdit\" type=\"hidden\" id=\"' . $fila . '\">';
    } else {
        $comprobar = '<i class=\"fas fa-check-circle referencia\"></i>';
    }
    //SI HAY UNA REFERENCIA
    $ref = "";
    if((int)$row['tipo_movimiento_id'] === 2){
        if (str_ends_with($row['Referencia'], 'jpg') || str_ends_with($row['Referencia'], 'jpeg') || str_ends_with($row['Referencia'], 'png')) {
            $ref = ' <div id=\"contenedor-centrado\" > <a target=\"_blank\" href=\"' . $_ENV['RUTA_ARCHIVOS_READ'] . $idempresa . '/img' . '/' . str_replace('"', '\"',str_replace(["\r", "\n"], "", $row['Referencia'])) . '\">' . "" . str_replace('"', '\"',str_replace(["\r", "\n"], "", $row['Referencia'])) . '</a> </div>';
        } else {
            $ref = ' <div id=\"contenedor-centrado\" > <a target=\"_blank\" href=\"' . $_ENV['RUTA_ARCHIVOS_READ'] . $idempresa . '/archivos' . '/' . str_replace('"', '\"',str_replace(["\r", "\n"], "", $row['Referencia'])) . '\">' . "" . str_replace('"', '\"',str_replace(["\r", "\n"], "", $row['Referencia'])) . '</a> </div>';
        }
    } else {
        $ref = ' <div id=\"contenedor-centrado\" > <a target=\"_blank\" href=\"../pagos/ver.php?id='.$row['idpagos'].'&pagoLibre='.$row['tipo_pago'].'\">' . "" . $row['identificador_pago'] . '</a> </div>';
    }

    /* if ($row['Comprobado'] == "0") {
        $editar = '<i class=\"fas fa-edit pointer\" id=\"' . $row['PKMovimiento'] . '\" data-toggle=\"modal\" data-target=\"#editar_Gasto\" onclick=\"obtenerIdGastoEditar(' . $row['PKMovimiento'] . ');\"></i>';
        $eliminar = '<i class=\"fas fa-trash-alt pointer ml-3\" id=\"' . $row['PKMovimiento'] . '\" data-toggle=\"modal\" data-target=\"#eliminar_Gasto\" onclick=\"obtenerIdGastoEliminar(' . $row['PKMovimiento'] . ');\"></i>';
    } else {
        $editar = '';
        $eliminar = '';
    } */
    if($row['folio']!== null && $row['folio'] !== 2){
        if ($row['Comprobado'] == "0") {
            $folio = '<a class=\"pointer\" id=\"' . $row['PKMovimiento'] . '\" data-toggle=\"modal\" data-target=\"#editar_Gasto\" onclick=\"obtenerIdGastoEditar(' . $row['PKMovimiento'] . ');\">' . $row['folio'] . '</a>';
        } else {
            $folio = $row['folio'];
        }
    }else {
        $folio = 'S/N';
    }
    str_replace('"','\"',str_replace(['\r','\n'],"",$row['Descripcion']));
    $table .=
        '{"Id":"' . $row['PKMovimiento'] . '",
      "Folio":"' . $folio . '",
      "Nombre":"' . str_replace('"', '\"',str_replace(["\r", "\n"], "", $row['Nombre'])) . '",
      "Proveedor":"' . str_replace('"', '\"',str_replace(["\r", "\n"], "", $row['NombreComercial'])) . '",
      "Fecha":"' . $row['Fecha'] . '",
      "Descripcion":"' . str_replace('"', '\"',str_replace(["\r", "\n"], "", $row['Descripcion'])) . '",
      "Retiro":"' . $retiro . '",
      "Responsable":"' .str_replace('"', '\"',str_replace(["\r", "\n"], "",  $row['Responsable'])) . '",
      "Referencia":"' . $ref . '",
      "Comprobado":"' . $comprobar . '",
      "Categoria":"' . $row['nombreCategoria'] . '"},';
}

$table = substr($table, 0, strlen($table) - 1);
echo '{"data":[' . $table . ']}';

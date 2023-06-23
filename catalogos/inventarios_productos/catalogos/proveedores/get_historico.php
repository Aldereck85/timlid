<?php
function GetEvn()
{
    include "../../../../include/db-conn.php";
    $appUrl = $_ENV['APP_URL'] ?? 'https://app.timlid.com/';
    return ['server' => $appUrl];
}

require_once('../../../../include/db-conn.php');
session_start();
$empresa = $_SESSION["IDEmpresa"];
/* $consulta = $_GET["consulta"]; */

$proveedor_id = $_POST["proveedor_id"];

$toggle;

$stmt;
  $stmt = $conn->prepare("SELECT  pr.NombreComercial, pr.Monto_credito, cp.id as idCuenta, tipo_estatus, f.id as idFactura, folio_factura,num_serie_factura,
  subtotal,importe,fecha_factura, DATEDIFF(SYSDATE(), cp.fecha_vencimiento) as vencimiento, cp.fecha_vencimiento,estatus_factura, cp.saldo_insoluto 
    FROM cuentas_por_pagar as cp 
      inner join proveedores as pr on pr.PKProveedor = cp.proveedor_id 
      inner join estatus_factura as stat on cp.estatus_factura = stat.id
      inner join facturacion f on cp.folio_factura = f.folio
       where pr.empresa_id = $empresa and cp.proveedor_id = $proveedor_id
       group by idCuenta;");


/* $stmt = $conn->prepare("SELECT PKProveedor,  NombreComercial FROM proveedores Where empresa_id = $empresa"); */
/* $stmt = $conn->prepare("SELECT * from (
    Select pr.NombreComercial, PKProveedor, cp.folio_factura, cp.fecha_vencimiento  FROM cuentas_por_pagar as cp inner join proveedores 
                        as pr  ON cp.proveedor_id = pr.PKProveedor order by cp.fecha_vencimiento) as tabale group by NombreComercial"); */
 $stmt->execute();


/* $stmt = $conn->prepare("SELECT id, folio_factura, num_serie_factura, subtotal, importe, fecha_factura, 
fecha_vencimiento,estatus_factura, NombreComercial 
FROM proveedores where Dias_credito between 0 and 30 and NombreComercial = Esteritam");
$stmt->execute(); */


$envVariables = GetEvn();
$appUrl = $envVariables['server'];

$table = "";
$respuesta['total'] = 0;

$totalVencidas = 0;
$totalCorriente = 0;

$creditoAsignado = 0;
$creditoDisponible = 0;


while (($row = $stmt->fetch()) !== false) {
    
    $respuesta['total'] = $respuesta['total'] + $row['saldo_insoluto'];

    $creditoAsignado = $row['Monto_credito'];
    //semaforo de fecha de vencimiento

    $fechaVencimiento = date("Y-m-d", strtotime($row['fecha_vencimiento']));
    $fechaActual = date("Y-m-d");

    if ($fechaActual > $fechaVencimiento) {
        $row['fecha_vencimiento'] = '<span class=\"badge badge-danger\" style=\"font-size:1rem;font-family: Montserrat, sans-serif\">' . $row['fecha_vencimiento'] . '</span>';
        $totalVencidas += $row['saldo_insoluto'];
    } else {
        $row['fecha_vencimiento'] = '<span class=\"badge badge-success\" style=\"font-size:1rem;font-family: Montserrat, sans-serif\">' . $row['fecha_vencimiento'] . '</span>';
        $totalCorriente += $row['saldo_insoluto'];
    }


    if($row['fecha_vencimiento']){
      $row['fecha_vencimiento'] = date("Y-m-d", strtotime($row['fecha_vencimiento']));
    }else{
      $row['fecha_vencimiento'] = "Desconocida";
      $row['vencimiento'] = false;
    }
    
    $row['fecha_factura'] = date("Y-m-d", strtotime($row['fecha_factura']));
    
    if($row['estatus_factura']==1){
      $row['estatus_factura']= '<span class=\"left-dot blue-light-dot\">Pendiente de pago</span>';
    }elseif($row['estatus_factura']==0){
      $row['estatus_factura']= '<span class=\"left-dot blue-light-dot\">Pendiente de pago</span>';
    }elseif($row['estatus_factura']==2){
      $row['estatus_factura']= '<span class=\"left-dot turquoise-dot\">Desviación</span>';
    }elseif($row['estatus_factura']==3){
      $row['estatus_factura']= '<span class=\"left-dot gray-dot\"> Revisado por finanzas</span>';
    }elseif($row['estatus_factura']==4){
      $row['estatus_factura']= '<span class=\"left-dot orange-dot\">Parcialmente pagada</span>';
    }elseif($row['estatus_factura']==5){
      $row['estatus_factura']= '<span class=\"left-dot green-dot\">Pagada</span>';
    }elseif($row['estatus_factura']==6){
      $row['estatus_factura']= '<span class=\"left-dot blue-light-dot\">Registro Manual</span>';
    } 
    $row['importe'] = '<div style=\"text-align: right;\">$' .number_format($row['importe'],2).'</div>';
    $row['saldo_insoluto'] = '<div style=\"text-align: right;\">$' .number_format($row['saldo_insoluto'],2).'</div>';
  if($row['vencimiento']>0){
    $row['vencimiento']= '<span class=\"left-dot red-dot\">' .$row['vencimiento']. ' dias'. '</span>';
  }elseif($row['vencimiento']===0){
    $row['vencimiento']= '<div class=\"left-dot yellow-dot\">Hoy</div>';
  }elseif($row['vencimiento']<0){
    $row['vencimiento']= '<div class=\"left-dot green-dot\">' .abs($row['vencimiento']). ' dias'. '</div>';
  }elseif($row['vencimiento']==false){
    $row['vencimiento']= '<div class=\"left-dot gray-dot\">Desconocido</div>';
  }
  /* $acciones = '<input type=\"hidden\" id=\"hddId-'.$row['id'].'\">'; */
  /* TODO: REVISAR EL ENLACE */
  $enlace = '<a href=\"' . $appUrl . 'catalogos/cuentas_pagar/editar.php?id=' . $row['idCuenta'] . '\">'.$row['folio_factura'].'</a>';

    /* Guardamos en un JSON los datos de la consulta  */
    $table.='{"Id":"'.$row['idCuenta'].
        '","Folio de Factura":"'.$enlace.
        '","Fecha de Factura":"'.$row['fecha_factura'].
        '","Fecha de Vencimiento":"'.$row['fecha_vencimiento'].
        '","Vencimiento":"'.$row['vencimiento'].
        '","Importe":"'.$row['importe'].
        '","saldo_insoluto":"'.$row['saldo_insoluto'].
        '","Estatus":"'.$row['estatus_factura'].
        '"},'; 
    //,"Acciones":"'.'"
  }
//echo '{"data":['.$table.']}';
    $table = substr($table, 0, strlen($table) - 1);
        $creditoDisponible = $creditoAsignado - floatval($respuesta['total']);
        $respuesta['total'] = number_format($respuesta['total'], 2);
        echo '{"data":[' . $table . '], "total":"' . $respuesta['total'] . '", "creditoA": "' . number_format($creditoAsignado, 2) . '", "creditoD": "' . number_format($creditoDisponible, 2) . '" , "cuentasV": "' . number_format($totalVencidas, 2) . '", "cuentasC": "' . number_format($totalCorriente, 2) . '"}';
 ?>
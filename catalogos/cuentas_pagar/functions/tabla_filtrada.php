<?php
require_once('../../../include/db-conn.php');
/* Optenemsos el id del proveedor que enviamos de la pantalla anterior */
$proveedor_id = ($_GET['proveedor_id']);
/* VAr periodo que fue enviada dependiendo */
$periodo = ($_GET['periodo']);
/* Optenemsos la fecha from */
$_from = ($_GET['Ffrom']);
/* Optenemos la fecha To */
$_to = ($_GET['Fto']);
/* Var toggle que me envia 0 o 1 dependiendo si es vencida o al corriente */
$toggle = ($_GET['toggle']);
$_periodo = 0;
$status;
session_start();
$empresa = $_SESSION["IDEmpresa"];

$fecha_filtro = "";
if($_from == "f"){
  $fecha_filtro = "and (cp.fecha_factura <= ('$_to') )";
}elseif($_to == "f"){
  $fecha_filtro = "and (cp.fecha_factura >= ('$_from') )";
}else{
  $fecha_filtro = "and (cp.fecha_factura BETWEEN ('$_from') and ('$_to'))";
}
//echo($_from.$_to);
/* Si se envia un 1 es la consulta para las CUentas Al Corriente */
if ($toggle == 1) {
  $status = 1;
  $toggle = "fecha_vencimiento";
  $fecha = "cp.fecha_factura";
  /*  */
  /* Si recibe 00 quiere decir que el periodo es +90 por lo que ejecutara esta consulta */
  /*  */
  if ($periodo == "00") {

    $stmt = $conn->prepare("SELECT cp.id, cp.proveedor_id, tipo_estatus, cp.folio_factura, DATEDIFF(SYSDATE(), fecha_vencimiento) as vencimiento, cp.num_serie_factura, cp.subtotal, cp.importe, cp.fecha_factura,
    cp.fecha_vencimiento,cp.estatus_factura, pr.NombreComercial FROM proveedores as pr  
    left JOIN  cuentas_por_pagar as cp ON cp.proveedor_id = pr.PKProveedor and cp.estatus_factura!=7
    left join estatus_factura as stat on cp.estatus_factura = stat.id
    WHERE (cp.estatus_factura = $status or 2) and (pr.empresa_id = $empresa) $fecha_filtro and  
    DATEDIFF($toggle, SYSDATE()) < 90  and DATEDIFF(SYSDATE(), fecha_vencimiento) <= 0 and cp.proveedor_id = $proveedor_id GROUP BY cp.id ");
  
  }else{
  /*  */
  /* Si NO quiere decir que el periodo se tiene que evaluar para saber por donde comenzar el rango */
  /*  */
    switch($periodo){
      case "30":
        $_periodo = 0;
        break;
      case "60":
        $_periodo = 31;
        break;
      case "90":
        $_periodo = 61;
        break;
    }

    $stmt = $conn->prepare("SELECT cp.id, cp.proveedor_id, tipo_estatus, cp.folio_factura, DATEDIFF(SYSDATE(), fecha_vencimiento) as vencimiento,
     cp.num_serie_factura, cp.subtotal, cp.importe, cp.fecha_factura,
     cp.fecha_vencimiento,cp.estatus_factura, pr.NombreComercial FROM proveedores as pr  
    left JOIN  cuentas_por_pagar as cp ON cp.proveedor_id = pr.PKProveedor and cp.estatus_factura!=7
    left join estatus_factura as stat on cp.estatus_factura = stat.id
    WHERE  cp.proveedor_id = $proveedor_id and (cp.estatus_factura = $status or 2) and (pr.empresa_id = $empresa) $fecha_filtro and 
    DATEDIFF($toggle, SYSDATE()) between $_periodo and $periodo and DATEDIFF(SYSDATE(), fecha_vencimiento) <= 0   GROUP BY cp.id ");
  }


/*  */                                            /*  */
/* Si se envia un 0 es para las cuentas VENCIDAS */
/*  */                                            /*  */
}elseif($toggle==0){
  $status = 2;
  $toggle = "fecha_vencimiento";
  $fecha = "cp.fecha_vencimiento";
  /*  */
  /* Si recibe 00 quiere decir que el periodo es +90 por lo que ejecutara esta consulta */
  /*  */
  if ($periodo == "00") {

    $stmt = $conn->prepare("SELECT cp.id, cp.proveedor_id, tipo_estatus, cp.folio_factura, DATEDIFF(SYSDATE(), $toggle) as vencimiento, cp.num_serie_factura, cp.subtotal, cp.importe, cp.fecha_factura,
    cp.fecha_vencimiento,cp.estatus_factura, pr.NombreComercial FROM proveedores as pr  
    left JOIN  cuentas_por_pagar as cp ON cp.proveedor_id = pr.PKProveedor and cp.estatus_factura!=7
    left join estatus_factura as stat on cp.estatus_factura = stat.id
    WHERE and (pr.empresa_id = $empresa) $fecha_filtro and DATEDIFF(SYSDATE(), $toggle) >= 90 and cp.proveedor_id = $proveedor_id  GROUP BY cp.id ");
  /*  */
  /* Si NO quiere decir que el periodo se tiene que evaluar para saber por donde comenzar el rango */
  /*  */
  }else{
    switch($periodo){
      case "30":
        $_periodo = 0;
        break;
      case "60":
        $_periodo = 31;
        break;
      case "90":
        $_periodo = 61;
        break;
    }

    $stmt = $conn->prepare("SELECT cp.id, cp.proveedor_id,tipo_estatus, DATEDIFF(SYSDATE(), $toggle) as vencimiento, cp.folio_factura, cp.num_serie_factura, 
    cp.subtotal, cp.importe, cp.fecha_factura,cp.fecha_vencimiento,cp.estatus_factura, pr.NombreComercial 
    FROM proveedores as pr  
    left JOIN  cuentas_por_pagar as cp ON cp.proveedor_id = pr.PKProveedor and cp.estatus_factura!=7
    left join estatus_factura as stat on cp.estatus_factura = stat.id
    WHERE cp.proveedor_id = $proveedor_id and DATEDIFF(SYSDATE(), $toggle) 
    between $_periodo  and $periodo and (pr.empresa_id = $empresa) $fecha_filtro GROUP BY cp.id ");
  }
}

/* Ejecutamos cualquiera de las consultas */
$stmt->execute();




$table = "";
while (($row = $stmt->fetch()) !== false) {

  /* Formatea con colores el status que tenga la cuenta por pagar */
  if ($row['estatus_factura'] == 1) {
    $row['estatus_factura'] = '<span class=\"left-dot blue-light-dot\">Pendiente de pago</span>';;
  } elseif ($row['estatus_factura'] == 0) {
    $row['estatus_factura'] = '<span class=\"left-dot blue-light-dot\">Pendiente de pago</span>';
  } elseif ($row['estatus_factura'] == 2) {
    $row['estatus_factura'] = '<span class=\"left-dot turquoise-dot\">Desviación</span>';
  } elseif ($row['estatus_factura'] == 3) {
    $row['estatus_factura'] = '<span class=\"left-dot gray-dot\">Revisado por finanzas</span>';
  } elseif ($row['estatus_factura'] == 4) {
    $row['estatus_factura'] = '<span class=\"left-dot orange-dot\">Parcialmente pagada</span>';
  } elseif ($row['estatus_factura'] == 5) {
    $row['estatus_factura'] = '<span class=\"left-dot green-dot\">Pagada</span>';
  } elseif ($row['estatus_factura'] == 6) {
    $row['estatus_factura'] = '<span class=\"left-dot blue-light-dot\">Registro Manual</span>';
  }

  if ($row['vencimiento'] >= 0) {
  }

  /* Formateamos los valores  como fechas y monedas*/
  if ($row['fecha_vencimiento']) {
    $row['fecha_vencimiento'] = date("Y-m-d", strtotime($row['fecha_vencimiento']));
  } else {
    $row['fecha_vencimiento'] = "Desconocida";
    $row['vencimiento'] = false;
  }
    $row['fecha_factura'] = date("Y-m-d", strtotime($row['fecha_factura']));
    $row['importe'] = '<div style=\"text-align: right;\">$' .number_format($row['importe'],2).'</div>';
    $row['subtotal'] = '<div style=\"text-align: right;\">$' .number_format($row['subtotal'],2).'</div>';
    

    if ($row['vencimiento'] > 0) {
      $row['vencimiento'] = '<span class=\"left-dot red-dot\">' . $row['vencimiento'] . '</span>';
    } elseif ($row['vencimiento'] === 0) {
      $row['vencimiento'] = '<span class=\"left-dot yellow-dot\">Hoy</span>';
    } elseif ($row['vencimiento'] < 0) {
      $row['vencimiento'] = '<span class=\"left-dot green-dot\">' . abs($row['vencimiento']) . '</span>';
    } elseif ($row['vencimiento'] == false) {
      $row['vencimiento'] = '<span class=\"left-dot gray-dot\">Desconocido</span>';
    }
    $acciones = '<a class=\"edit-tabs-371\" href=\"editar.php?id=' . $row['id'] . '\"><i class=\"fas fa-edit\"></i></a>';

    $nombreComercial = str_replace('"', '\"', $row['NombreComercial']);

    /* Guardamos en un JSON los datos de la consulta  */
      $table.='{"Proveedor":"'.$nombreComercial.'",
      "Folio_Factura":"'.$row['folio_factura'].'",
      "Serie_Factura":"'.$row['num_serie_factura'].'",
      "Subtotal":"'.$row['subtotal'].'",
      "Importe":"'.$row['importe'].'",
      "Fecha_Factura":"'.$row['fecha_factura'].'",
      "Vence":"'.$row['fecha_vencimiento'].'",
      "Estatus":"'.$row['estatus_factura'].'",
      "vencimiento":"'.$row['vencimiento'].'",
      "Editar":"'.$acciones.'"},'; 
    //,"Acciones":"'.'"
  }
$table = substr($table, 0, strlen($table) - 1);
echo '{"data":[' . $table . ']}';

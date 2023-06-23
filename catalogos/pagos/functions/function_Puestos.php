<?php
require_once('../../../include/db-conn.php');
/* Optenemsos el id del proveedor que enviamos de la pantalla anterior */
$proveedor_id = ($_GET['proveedor_id']);
/* VAr periodo que fue enviada dependiendo */
$periodo = ($_GET['periodo']);
/* Var toggle que me envia 0 o 1 dependiendo si es vencida o al corriente */
$toggle = ($_GET['toggle']);
$_periodo = 0;
$status;

/* Si se envia un 1 es la consulta para las CUentas Al Corriente */
if($toggle==1){
  $status = 1;
  $toggle = "fecha_vencimiento";
  $fecha = "cp.fecha_factura";
  /*  */
  /* Si recibe 00 quiere decir que el periodo es +90 por lo que ejecutara esta consulta */
  /*  */
  if($periodo == "00"){

    $stmt = $conn->prepare("SELECT cp.id, cp.proveedor_id, tipo_estatus, cp.folio_factura, DATEDIFF(SYSDATE(), fecha_vencimiento) as vencimiento, cp.num_serie_factura, cp.subtotal, cp.importe, cp.fecha_factura,
    cp.fecha_vencimiento,cp.estatus_factura, pr.NombreComercial FROM proveedores as pr  
    left JOIN  cuentas_por_pagar as cp ON cp.proveedor_id = pr.PKProveedor and cp.estatus_factura!=7
    left join estatus_factura as stat on cp.estatus_factura = stat.id
    WHERE (cp.estatus_factura = $status or 2) and cp.proveedor_id= $proveedor_id and 
    DATEDIFF($toggle, SYSDATE()) < 90 and DATEDIFF(SYSDATE(), fecha_vencimiento) <= 0 GROUP BY cp.id ");
  
  }else{
  /*  */
  /* Si NO quiere decir que el periodo se tiene que evaluar para saber por donde comenzar el rango */
  /*  */
    switch($periodo){
      case "30":
        $_periodo=0;
        break;
      case "60":
        $_periodo=31;
        break;
      case "90":
        $_periodo=61;
        break;
    }
  
    $stmt = $conn->prepare("SELECT cp.id, cp.proveedor_id, tipo_estatus, cp.folio_factura, DATEDIFF(SYSDATE(), fecha_vencimiento) as vencimiento,
     cp.num_serie_factura, cp.subtotal, cp.importe, cp.fecha_factura,
     cp.fecha_vencimiento,cp.estatus_factura, pr.NombreComercial FROM proveedores as pr  
    left JOIN  cuentas_por_pagar as cp ON cp.proveedor_id = pr.PKProveedor and cp.estatus_factura!=7
    left join estatus_factura as stat on cp.estatus_factura = stat.id
    WHERE (cp.estatus_factura = $status or 2) and cp.proveedor_id= $proveedor_id and 
    DATEDIFF($toggle, SYSDATE()) between $_periodo and $periodo and DATEDIFF(SYSDATE(), fecha_vencimiento) <= 0 GROUP BY cp.id ");
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
  if($periodo == "00"){

    $stmt = $conn->prepare("SELECT cp.id, cp.proveedor_id, tipo_estatus, cp.folio_factura, DATEDIFF(SYSDATE(), $toggle) as vencimiento, cp.num_serie_factura, cp.subtotal, cp.importe, cp.fecha_factura,
    cp.fecha_vencimiento,cp.estatus_factura, pr.NombreComercial FROM proveedores as pr  
    left JOIN  cuentas_por_pagar as cp ON cp.proveedor_id = pr.PKProveedor and cp.estatus_factura!=7
    left join estatus_factura as stat on cp.estatus_factura = stat.id
    WHERE  cp.proveedor_id= $proveedor_id and DATEDIFF(SYSDATE(), $toggle) >= 90 GROUP BY cp.id ");
  /*  */
  /* Si NO quiere decir que el periodo se tiene que evaluar para saber por donde comenzar el rango */
  /*  */
  }else{
    switch($periodo){
      case "30":
        $_periodo=0;
        break;
      case "60":
        $_periodo=31;
        break;
      case "90":
        $_periodo=61;
        break;
    }
  
    $stmt = $conn->prepare("SELECT cp.id, cp.proveedor_id,tipo_estatus, DATEDIFF(SYSDATE(), $toggle) as vencimiento, cp.folio_factura, cp.num_serie_factura, 
    cp.subtotal, cp.importe, cp.fecha_factura,cp.fecha_vencimiento,cp.estatus_factura, pr.NombreComercial 
    FROM proveedores as pr  
    left JOIN  cuentas_por_pagar as cp ON cp.proveedor_id = pr.PKProveedor and cp.estatus_factura!=7
    left join estatus_factura as stat on cp.estatus_factura = stat.id
    WHERE  cp.proveedor_id= $proveedor_id and DATEDIFF(SYSDATE(), $toggle) 
    between $_periodo and $periodo GROUP BY cp.id ");
  }
}

/* Ejecutamos cualquiera de las consultas */
$stmt->execute();




$table="";
//href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Vehiculo\" class=\"btn btn-primary\" onclick=\"obtenerIdVehiculoEditar('.$row['PKVehiculo'].');\"><i class=\"fas fa-edit\"></i> Editar
//href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Vehiculo\" class=\"btn btn-danger\" onclick=\"obtenerIdVehiculoEliminar('.$row['PKVehiculo'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar
while (($row = $stmt->fetch()) !== false) {

  /* Formatea con colores el status que tenga la cuenta por pagar */
  if($row['estatus_factura']==1){
    $row['estatus_factura']= '<div style=\"background:#1cc88a;padding:5px;color:white!important;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;width:90%;\"><center>' .$row["tipo_estatus"]. '</center></div>';
  }elseif($row['estatus_factura']==0){
    $row['estatus_factura']= '<div style=\"background:#6c757d;padding:5px;color:white!important;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;width:90%;\"><center>' .$row["tipo_estatus"]. '</center></div>';
  }elseif($row['estatus_factura']==2){
    $row['estatus_factura']= '<div style=\"background:#2a96a5;padding:5px;color:white!important;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;width:90%;\"><center>' .$row["tipo_estatus"]. '</center></div>';
  }elseif($row['estatus_factura']==3){
    $row['estatus_factura']= '<div style=\"background:#6c757d;padding:5px;color:white!important;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;width:90%;\"><center>' .$row["tipo_estatus"]. '</center></div>';
  }elseif($row['estatus_factura']==4){
    $row['estatus_factura']= '<div style=\"background:#e74a3b;padding:5px;color:white!important;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;width:90%;\"><center>' .$row["tipo_estatus"]. '</center></div>';
  }elseif($row['estatus_factura']==5){
    $row['estatus_factura']= '<div style=\"background:#e74a3b;padding:5px;color:white!important;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;width:90%;\"><center>' .$row["tipo_estatus"]. '</center></div>';
  }elseif($row['estatus_factura']==6){
    $row['estatus_factura']= '<div style=\"background:#e74a3b;padding:5px;color:white!important;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;width:90%;\"><center>' .$row["tipo_estatus"]. '</center></div>';
  } 

  if($row['vencimiento']>=0){

  }

  /* Formateamos los valores  como fechas y monedas*/
   $row['fecha_vencimiento'] = date("Y-m-d", strtotime($row['fecha_vencimiento']));
    $row['fecha_factura'] = date("Y-m-d", strtotime($row['fecha_factura']));
    $row['importe'] = "$" .number_format($row['importe'],2);
    $row['subtotal'] = "$" .number_format($row['subtotal'],2);

    if($row['vencimiento']>0){
      $row['vencimiento']= '<div style=\"background:#e74a3b;padding:5px;color:white!important;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;width:90%;\"><center>' .($row['vencimiento']).  ' dias </center></div>';
    }elseif($row['vencimiento']==0){
      $row['vencimiento']= '<div style=\"background:#ffc107;padding:5px;color:white!important;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;width:90%;\"><center>' .$row['vencimiento']. ' dias </center></div>';
    }elseif($row['vencimiento']<0){
      $row['vencimiento']= '<div style=\"background:#1cc88a;padding:5px;color:white!important;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;width:90%;\"><center>' .abs($row['vencimiento']). ' dias </center></div>';
    }
    $row['NombreComercial']= $row['NombreComercial'].' <a class=\"edit-tabs-371\" href=\"editar.php?id='.$row['id'].'\"><img class=\"edit-icon\" href=\"editar.php?id='.$row['id'].'\" id=\"edit-icon-\" src=\"../../img/timdesk/edit.svg\"></a>';

    /* Guardamos en un JSON los datos de la consulta  */
      $table.='{"Proveedor":"'.$row['NombreComercial'].'",
      "Folio_Factura":"'.$row['folio_factura'].'",
      "Serie_Factura":"'.$row['num_serie_factura'].'",
      "Subtotal":"'.$row['subtotal'].'",
      "Importe":"'.$row['importe'].'",
      "Fecha_Factura":"'.$row['fecha_factura'].'",
      "Vence":"'.$row['fecha_vencimiento'].'",
      "Estatus":"'.$row['estatus_factura'].'",
      "vencimiento":"'.$row['vencimiento'].'",
      "Editar":"'.'<a id=\"editarcp\" href=\"editar.php?id='.$row['id'].'\" title=\"Ver Datos\" > VER </a>'.'"},'; 
    //,"Acciones":"'.'"
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>

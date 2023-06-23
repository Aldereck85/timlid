<?php
require_once('../../../include/db-conn.php');
session_start();
$empresa = $_SESSION["IDEmpresa"];
/* $consulta = $_GET["consulta"]; */

  /* Optenemsos la fecha from */
  $_from = ($_GET['Ffrom']);
  /* Optenemos la fecha To */
  $_to = ($_GET['Fto']);
  $_proveedor = ($_GET['proveedor_id']);

$toDo=0;
//Proveedor entre fechas
if($_proveedor!="f" && $_to!="f" && $_from!="f"){
  $toDo = 1;
  /* Optenemsos la fecha from */
  $_from = ($_GET['Ffrom']);
  /* Optenemos la fecha To */
  $_to = ($_GET['Fto']);
  $_proveedor = ($_GET['proveedor_id']);
  //proveedor antes de fecha
}elseif($_proveedor!="f" && $_to!="f"){
  $toDo = 2;
    /* Optenemsos la fecha from */
    $_from = ('2000-01-01');
    /* Optenemos la fecha To */
    $_to = ($_GET['Fto']);
    $_proveedor = ($_GET['proveedor_id']);
  //proveedor despues de fecha
}elseif($_proveedor!="f" && $_from!="f"){
  $toDo = 3;
  /* Optenemsos la fecha from */
  /* Optenemos la fecha To */
  $_to = "9999-12-31";
  $_proveedor = ($_GET['proveedor_id']);
  //Solo proveedor
}elseif($_proveedor!="f"){
  $toDo = 4;
  /* Optenemsos la fecha from */
  $_from = '1500-01-01';
  /* Optenemos la fecha To */
  $_to = '9999-12-31';
  $_proveedor = ($_GET['proveedor_id']);
  //Todo entre fechas
}elseif(($_to)!="f" && $_from!="f"){
  $toDo = 5;
  /* Optenemsos la fecha from */
  $_from = ($_GET['Ffrom']);
  /* Optenemos la fecha To */
  $_to = ($_GET['Fto']);
  //Todo despues de fecha
}elseif($_from!="f"){
  $toDo = 6;
  /* Optenemsos la fecha from */
  $_from = ($_GET['Ffrom']);
  /* Optenemos la fecha To */
  $_to = '9999-12-31';
  //Todo antes de fecha
}elseif($_to!="f"){
  $toDo = 7;
  /* Optenemsos la fecha from */
  $_from = '1500-01-01';
  /* Optenemos la fecha To */
  $_to = ($_GET['Fto']);
}elseif($_proveedor=="f"){
  $toDo = 8;
  /* Optenemsos la fecha from */
  $_from = '1500-01-01';
  /* Optenemos la fecha To */
  $_to = '9999-12-31';
  //Todo entre fechas
}
//print($toDo." ".$_from." ".$_proveedor." ".$_to);
$stmt;
if($toDo == 1||$toDo ==2||$toDo ==3||$toDo ==4){
  $stmt = $conn->prepare("SELECT  pr.NombreComercial, cp.id, tipo_estatus, folio_factura,num_serie_factura,
  subtotal,importe,fecha_factura, DATEDIFF(SYSDATE(), fecha_vencimiento) as vencimiento, fecha_vencimiento,estatus_factura, cp.saldo_insoluto 
    FROM cuentas_por_pagar as cp 
      inner join proveedores as pr on pr.PKProveedor = cp.proveedor_id 
      inner join estatus_factura as stat on cp.estatus_factura = stat.id
       where pr.empresa_id = $empresa and (cp.fecha_factura BETWEEN ('$_from') and ('$_to') ) and cp.proveedor_id = $_proveedor and cp.estatus_factura!=7;");
       $stmt->execute();
}elseif($toDo == 5||$toDo ==6||$toDo ==7||$toDo ==8){
  $stmt = $conn->prepare("SELECT  pr.NombreComercial, cp.id, tipo_estatus, folio_factura,num_serie_factura,
  subtotal,importe,fecha_factura, DATEDIFF(SYSDATE(), fecha_vencimiento) as vencimiento, fecha_vencimiento,estatus_factura, cp.saldo_insoluto 
    FROM cuentas_por_pagar as cp 
      inner join proveedores as pr on pr.PKProveedor = cp.proveedor_id 
      inner join estatus_factura as stat on cp.estatus_factura = stat.id
       where pr.empresa_id = $empresa and (cp.fecha_factura BETWEEN ('$_from') and ('$_to') ) and cp.estatus_factura!=7;");
       $stmt->execute();
}elseif($toDo == 10){

}

//print_r($stmt);

/* $consulta = $_GET["toDo"]; */

$toggle;


  


/* $stmt = $conn->prepare("SELECT PKProveedor,  NombreComercial FROM proveedores Where empresa_id = $empresa"); */
/* $stmt = $conn->prepare("SELECT * from (
    Select pr.NombreComercial, PKProveedor, cp.folio_factura, cp.fecha_vencimiento  FROM cuentas_por_pagar as cp inner join proveedores 
                        as pr  ON cp.proveedor_id = pr.PKProveedor order by cp.fecha_vencimiento) as tabale group by NombreComercial"); */



/* $stmt = $conn->prepare("SELECT id, folio_factura, num_serie_factura, subtotal, importe, fecha_factura, 
fecha_vencimiento,estatus_factura, NombreComercial 
FROM proveedores where Dias_credito between 0 and 30 and NombreComercial = Esteritam");
$stmt->execute(); */


$table="";
while (($row = $stmt->fetch()) !== false) {

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
  $row['importe'] = "$" .number_format($row['importe'],2);
  if($row['vencimiento']>0){
    $row['vencimiento']= '<span class=\"left-dot red-dot\">' .$row['vencimiento']. ' dias'. '</span>';
  }elseif($row['vencimiento']===0){
    $row['vencimiento']= '<class=\"left-dot yellow-dot\">Hoy</div>';
  }elseif($row['vencimiento']<0){
    $row['vencimiento']= '<div class=\"left-dot green-dot\">' .abs($row['vencimiento']). ' dias'. '</div>';
  }elseif($row['vencimiento']==false){
    $row['vencimiento']= '<div class=\"left-dot gray-dot\">Desconocido</div>';
  }
  $acciones = ' <a class=\"edit-tabs-371\" href=\"editar.php?id='.$row['id'].'\"><i class=\"fas fa-edit\"></i></a>';

  $nombreComercial = str_replace('"', '\"', $row['NombreComercial']);

    /* Guardamos en un JSON los datos de la consulta  */
    $table.='{"Proveedor":"'.$nombreComercial.
        '","Id":"'.$row['id'].
        '","Folio de Factura":"'.$row['folio_factura'].
        '","Fecha de Factura":"'.$row['fecha_factura'].
        '","Estatus":"'.$row['estatus_factura'].
        '","Fecha de Vencimiento":"'.$row['fecha_vencimiento'].
        '","Importe":"'.$row['importe'].
        '","saldo_insoluto":"'.$row['saldo_insoluto'].
        '","Vencimiento":"'.$row['vencimiento'].
        '"},'; 
    //,"Acciones":"'.'"
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>
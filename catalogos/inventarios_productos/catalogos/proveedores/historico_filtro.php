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

$envVariables = GetEvn();
$appUrl = $envVariables['server'];
/* $consulta = $_GET["consulta"]; */

  /* Optenemsos la fecha from */
  $_from = ($_POST['Ffrom']);
  /* Optenemos la fecha To */
  $_to = ($_POST['Fto']);
  $_proveedor = ($_POST['proveedor_id']);

//Proveedor entre fechas
if($_proveedor!="f" && $_to!="f" && $_from!="f"){
  /* Optenemsos la fecha from */
  $_from = ($_POST['Ffrom']);
  /* Optenemos la fecha To */
  $_to = ($_POST['Fto']);
  $_proveedor = ($_POST['proveedor_id']);
  //proveedor antes de fecha
}elseif($_proveedor!="f" && $_to!="f"){
    /* Optenemsos la fecha from */
    $_from = ('2000-01-01');
    /* Optenemos la fecha To */
    $_to = ($_POST['Fto']);
    $_proveedor = ($_POST['proveedor_id']);
  //proveedor despues de fecha
}elseif($_proveedor!="f" && $_from!="f"){
  /* Optenemsos la fecha from */
  /* Optenemos la fecha To */
  $_to = "9999-12-31";
  $_proveedor = ($_POST['proveedor_id']);
  //Solo proveedor
}elseif($_proveedor!="f"){
  /* Optenemsos la fecha from */
  $_from = '1500-01-01';
  /* Optenemos la fecha To */
  $_to = '9999-12-31';
  $_proveedor = ($_POST['proveedor_id']);
  //Todo entre fechas
}elseif(($_to)!="f" && $_from!="f"){
  /* Optenemsos la fecha from */
  $_from = ($_POST['Ffrom']);
  /* Optenemos la fecha To */
  $_to = ($_POST['Fto']);
  //Todo despues de fecha
}elseif($_from!="f"){
  /* Optenemsos la fecha from */
  $_from = ($_POST['Ffrom']);
  /* Optenemos la fecha To */
  $_to = '9999-12-31';
  //Todo antes de fecha
}elseif($_to!="f"){
  /* Optenemsos la fecha from */
  $_from = '1500-01-01';
  /* Optenemos la fecha To */
  $_to = ($_POST['Fto']);
}elseif($_proveedor=="f"){
  /* Optenemsos la fecha from */
  $_from = '1500-01-01';
  /* Optenemos la fecha To */
  $_to = '9999-12-31';
  //Todo entre fechas
}
//print($toDo." ".$_from." ".$_proveedor." ".$_to);
$stmt;
$stmt = $conn->prepare("SELECT  pr.NombreComercial, cp.id, tipo_estatus, folio_factura,num_serie_factura,
subtotal,importe,fecha_factura, DATEDIFF(SYSDATE(), fecha_vencimiento) as vencimiento, fecha_vencimiento,estatus_factura, cp.saldo_insoluto  
FROM cuentas_por_pagar as cp 
    inner join proveedores as pr on pr.PKProveedor = cp.proveedor_id 
    inner join estatus_factura as stat on cp.estatus_factura = stat.id
    where pr.empresa_id = $empresa and (cp.fecha_factura BETWEEN ('$_from') and ('$_to') ) and cp.proveedor_id = $_proveedor
       group by cp.id;");
    $stmt->execute();

//print_r($stmt);

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
  $enlace = '<a href=\"' . $appUrl . 'catalogos/cuentas_pagar/editar.php?id=' . $row['id'] . '\">'.$row['folio_factura'].'</a>';

    /* Guardamos en un JSON los datos de la consulta  */
    $table.='{"Id":"'.$row['id'].
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
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>
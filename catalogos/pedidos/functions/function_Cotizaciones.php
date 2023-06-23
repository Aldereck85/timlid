<?php
require_once('../../../include/db-conn.php');

$stmt = $conn->prepare('SELECT c.PKCotizacion, c.ImporteTotal, df.RFC, cl.Nombre_comercial,c.Estatus,c.FechaVencimiento FROM cotizacion as c INNER JOIN clientes as cl ON cl.PKCliente = c.FKCliente LEFT JOIN domicilio_fiscal as df ON df.PKDomicilioFiscal = c.FKDomicilioFiscal');
$stmt->execute();
$row = $stmt->fetchAll();
$table="";



foreach($row as $r) {

  //1 Generada - Pendiente
  //2 Vencida
  //3 Aceptada
  //4 Facturada
  $estatus = $r['Estatus'];

  if($estatus == "Pendiente"){
    if(strtotime(date("Y-m-d"))  >  strtotime($r['FechaVencimiento'])){
        $stmt = $conn->prepare('UPDATE cotizacion SET Estatus = "Vencida" WHERE PKCotizacion = :id'); 
        $stmt->execute(array(':id'=>$r['PKCotizacion']));
        $estatus = "Vencida";
    }
  }
  
  switch ($estatus) {
    case 'Pendiente':
        $estatus = "<div style='background:#6c757d;padding:5px;color:white;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;width:70%;'><center>Pendiente</center></div>";
        break;
    case 'Vencida':
        $estatus = "<div style='background:#ffc107;padding:5px;color:white;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;width:70%;'><center>Vencida</center></div>";
        break;
    case 'Aceptada':
        $estatus = "<div style='background:#28a745;padding:5px;color:white;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;width:70%;'><center>Aceptada</center></div>";
        break;
    case 'Facturada':
        $estatus = "<div style='background:#17a2b8;padding:5px;color:white;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;width:70%;'><center>Facturada</center></div>";
        break;
    case 'Cancelada':
        $estatus = "<div style='background:#dc3545;padding:5px;color:white;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;width:70%;'><center>Cancelada</center></div>";
        break;
    }
    $factura= '<a class=\"btn btn-info\" href=\"functions/facturar.php?id='.$r['PKCotizacion'].'\"><i class=\"fas fa-file-invoice\"></i> Facturar</a>&nbsp;&nbsp;';

    $enlace = '<a href=\"functions/ver_Cotizacion.php?id='.$r['PKCotizacion'].'\">'.$r['Nombre_comercial'] .'</a>'; 
    $table.='{"Referencia":"'.$r['PKCotizacion'].'","Nombre":"'.$enlace.'","RFC":"'.$r['RFC'].'","Importe":"'.$r['ImporteTotal'].'","Estatus":"'.$estatus.'"},';
  }
  $table = substr($table,0,strlen($table)-1);
echo '{"data":['.$table.']}';
 ?>
<?php
require_once('../../../include/db-conn.php');

$stmt = $conn->prepare('SELECT f.PKFactura, f.Importe, f.Fecha_de_Emision, f.Estatus, df.Razon_Social, df.RFC FROM facturas as f LEFT JOIN domicilio_fiscal as df ON df.PKDomicilioFiscal = f.FKDomiciliofiscal ORDER BY FIELD(f.Estatus,"Pagado") DESC ');
$stmt->execute();
$table="";
while (($row = $stmt->fetch()) !== false) {

    $cambiarEstatus = '<div class=\"dropdown\"><button class=\"btn btn-primary dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\"> Estatus</button><div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\"><a class=\"dropdown-item\"  href=\"functions/cambiar_Estatus.php?id='.$row['PKFactura'].'&estatus=Cancelado\"><i class=\"fas fa-times\"></i> Cancelado</a><a class=\"dropdown-item\"  href=\"functions/cambiar_Estatus.php?id='.$row['PKFactura'].'&estatus=Pendiente\"><i class=\"far fa-hourglass\"></i> Pendiente</a><a class=\"dropdown-item\" href=\"functions/cambiar_Estatus.php?id='.$row['PKFactura'].'&estatus=Pagado\"><i class=\"fas fa-check\"></i> Pagado</a></div>';

    $ficha = '<a class=\"btn btn-success\" href=\"functions/ver_Factura.php?id='.$row['PKFactura'].'\"> <i class=\"far fa-id-card\"></i> Ver</a>&nbsp;';
    /*if ($row['Estatus'] != "Cancelado"){
      $borrar ='<a class=\"btn btn-danger\" href=\"functions/cancelar_factura.php?id='.$row['PKFactura'].'\"><i class=\"fas fa-user-times\"></i> Cancelar</a>';
    }else{*/
      $borrar = "";
    //}

    switch ($row['Estatus']) {
      case 'Pendiente':
          $color = '#f0ad4e';
          break;
      case 'Cancelado':
          $color = '#d9534f';
          break;
      case 'Pagado':
          $color = '#5cb85c';
          break;
    }

    $estatus = "<div style='background:".$color.";padding:5px;color:white;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;'><center>".$row['Estatus']."</center></div>";
    

    $table.='{"Cambiar estatus":"'.$cambiarEstatus.'","Folio":"'.$row['PKFactura'].'","Cliente":"'.$row['Razon_Social'].'","RFC":"'.$row['RFC'].'","Importe":"'.$row['Importe'].'","Fecha":"'.$row['Fecha_de_Emision'].'","Estatus":"'.$estatus.'","Acciones":"'.$ficha.$borrar.'"},';
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
?>

<?php
  require_once('../../../include/db-conn.php');
  $color ="";
  $table ="";
  $no = 1;
  $stmt = $conn->prepare('SELECT * FROM facturacion AS f
                          LEFT JOIN domicilio_fiscal AS df ON f.FKDomicilioFiscal = df.PKDomicilioFiscal
                          ');

  $stmt->execute();

  while($row = $stmt->fetch()){
    if(is_null($row['Estatus']) || $row['Estatus'] == ""){
      $estatus = "No se ha asignado un estatus";
    }else{
    	switch ($row['Estatus']) {
          case 'Pendiente':
              $color = '#f0ad4e';
              break;
          case 'Cancelado':
              $color = '#d9534f';
              break;
          case 'Cobrado':
              $color = '#5cb85c';
              break;

      }
  	  $estatus = "<div style='background:".$color.";padding:5px;color:white;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;'><center>".$row['Estatus']."</center></div>";
    }
    switch ($row['FKTipoCFDI']){
      case 1:
        $tipoCFDI = 'Factura';
        break;
      case 2:
        $tipoCFDI = 'Factura para hosteles';
        break;
      case 3:
        $tipoCFDI = 'Recibo de honorarios';
        break;
      case 4:
        $tipoCFDI = 'Nota de cargo';
        break;
      case 5:
        $tipoCFDI = 'Donativo';
        break;
      case 6:
        $tipoCFDI = 'Recibo de arredamiento';
        break;
      case 7:
        $tipoCFDI = 'Nota de credito';
        break;
      case 8:
        $tipoCFDI = 'Nota de devolucion';
        break;
      case 9:
        $tipoCFDI = 'Carta porte';
        break;
    }
    $saldo = "$".number_format($row['Total'],2);
    $funciones = '<div class=\"dropdown\"><button class=\"btn btn-secondary btnMargin dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\"><i class=\"fas fa-tools\"></i> Operaciones</button><div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\"><a class=\"dropdown-item\" href=\"functions/getPDF.php?filename='.$row['Folio'].'&uid='.$row['UUID'].'\"><i class=\"far fa-file-pdf fa-2x\"></i><span style=\"font-weight:bold;\">  PDF</span></a><a class=\"dropdown-item\" href=\"functions/getXML.php?filename='.$row['Folio'].'&uid='.$row['UUID'].'\"><i class=\"far fa-file-code fa-2x\"></i><span style=\"font-weight:bold\"> XML</span></a><a class=\"dropdown-item\" href=\"functions/CancelarCFDI.php?uid='.$row['UUID'].'\"><i class=\"fas fa-times fa-2x\"></i><span style=\"font-weight:bold\">  Cancelar</span></a><a class=\"dropdown-item\"  href=\"functions/cambiar_Estatus.php?id='.$row['PKFacturacion'].'&estatus=Pendiente\"><i class=\"far fa-hourglass fa-2x\"></i> <span style=\"font-weight:bold\">Pendiente</span></a><a class=\"dropdown-item\" href=\"functions/cambiar_Estatus.php?id='.$row['PKFacturacion'].'&estatus=Pagado\"><i class=\"fas fa-check fa-2x\"></i> <span style=\"font-weight:bold\">Pagado</span></a></div></div>';
    $fecha = date('d/m/Y',strtotime($row['Fecha_Timbrado']));
    $table.='{"id":"'.$row['PKFacturacion'].'","Folio":"<a href=\"#\">'.$row['Folio'].'</a>","Tipo":"'.$tipoCFDI.'","Razon social":"'.$row['Razon_Social'].'","Saldo":"'.$saldo.'","Fecha de timbrado":"'.$fecha.'","Estatus":"'.$estatus.'","Acciones":"'.$funciones.'"},';
    $no++;
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
?>

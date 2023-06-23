<?php
require '../../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

$token = $_POST['csr_token_UT5JP'];

if(empty($_SESSION['token_ld10d'])) {
    header('Location: ../finiquito_liquidacion.php');
    return;           
}

if (!hash_equals($_SESSION['token_ld10d'], $token)) {
    header('Location: ../finiquito_liquidacion.php');
    return;
} 

require_once('../../../include/db-conn.php');

date_default_timezone_set('America/Mexico_City');

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$idNominaEmpleado = 309;//$_POST['idNominaEmpleado'];

$stmt = $conn->prepare("SELECT ne.*,n.*, np.tipo_id, DATE_FORMAT(n.fecha_inicio, '%d/%m/%Y') as fechaInicio, DATE_FORMAT(n.fecha_fin, '%d/%m/%Y') as fechaFin, DATE_FORMAT(n.fecha_pago, '%d/%m/%Y') as fechaPago, e.PKEmpleado, e.Nombres, e.PrimerApellido, e.SegundoApellido,dle.FechaIngreso, DATE_FORMAT(dle.FechaIngreso, '%d/%m/%Y') as FechaIngresoFormat, e.RFC, dme.NSS, p.puesto,t.Turno, dle.Sueldo,pp.DiasPago, s.sucursal, pp.Periodo 
  FROM nomina_empleado as ne 
  INNER JOIN empleados as e ON e.PKEmpleado = ne.FKEmpleado 
  INNER JOIN datos_laborales_empleado as dle ON dle.FKEmpleado = e.PKEmpleado 
  INNER JOIN periodo_pago as pp ON pp.PKPeriodo_pago = dle.FKPeriodo 
  LEFT JOIN datos_medicos_empleado as dme ON dme.FKEmpleado = e.PKEmpleado 
  LEFT JOIN puestos as p ON p.id = dle.FKPuesto LEFT JOIN turnos as t ON t.PKTurno = dle.FKTurno 
  LEFT JOIN nomina as n ON n.id = ne.FKNomina LEFT JOIN nomina_principal as np ON np.id = n.fk_nomina_principal 
  LEFT JOIN sucursales as s ON s.id = np.sucursal_id 
  WHERE ne.PKNomina = :idNominaEmpleado ");
$stmt->bindValue(':idNominaEmpleado',$idNominaEmpleado);
$stmt->execute();
$datosNomina = $stmt->fetch();
//echo "<pre>",print_r($datosNomina),"</pre>";

$nombreEmpleado = $datosNomina['Nombres'].' '.$datosNomina['PrimerApellido'].' '.$datosNomina['SegundoApellido'];
$idEmpleado = $datosNomina['PKEmpleado'];


//Declara el ancho de la columna
$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(40);
$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(18);
$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(15);
$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(15);
$spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(40);
$spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(15);
$spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(15);
$spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(15);

$spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(40);
$spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(15);
$spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(15);
$spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(15);

//Declaracion de estilos para ngritas
$styleTitle = [
      'font' => [
          'bold' => true
      ]
  ];


  $titulo_general = "NOMINA NO.".$datosNomina['no_nomina'];
  $spreadsheet->getActiveSheet()->mergeCells("A1:D1");
  
  $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('A1')->applyFromArray($styleTitle);
  $sheet->setCellValue('A1', $titulo_general);  

  $spreadsheet->getActiveSheet()->getStyle('E1')->applyFromArray($styleTitle);
  $sheet->setCellValue('E1', "Fecha inicio:");
  $sheet->setCellValue('F1', $datosNomina['fechaInicio']);

  $spreadsheet->getActiveSheet()->getStyle('G1')->applyFromArray($styleTitle);
  $sheet->setCellValue('G1', "Fecha fin:");
  $sheet->setCellValue('H1', $datosNomina['fechaFin']);

  $spreadsheet->getActiveSheet()->getStyle('I1')->applyFromArray($styleTitle);
  $sheet->setCellValue('I1', "Fecha pago:");
  $sheet->setCellValue('J1', $datosNomina['fechaPago']);

  $spreadsheet->getActiveSheet()->getStyle('A3')->applyFromArray($styleTitle);
  $sheet->setCellValue('A3', "Nombre: "); 
  $sheet->setCellValue('B3', $nombreEmpleado);

  $fila = 4;

  if($datosNomina['tipo_id'] == 1){
    $tipo_nomina = "Ordinaria";
  }
  else{
    $tipo_nomina = "Extraordinaria";
  }
  $spreadsheet->getActiveSheet()->getStyle('A'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('A'.$fila, "Tipo nómina: "); 
  $sheet->setCellValue('B'.$fila, $tipo_nomina); 

  $spreadsheet->getActiveSheet()->getStyle('D'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('D'.$fila, "Sucursal: "); 
  $sheet->setCellValue('E'.$fila, $datosNomina['sucursal']); 

  $spreadsheet->getActiveSheet()->getStyle('G'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('G'.$fila, "Periodicidad: "); 
  $sheet->setCellValue('H'.$fila, $datosNomina['Periodo']); 
  $fila++;

  $spreadsheet->getActiveSheet()->getStyle('A'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('A'.$fila, "Turno: "); 
  $sheet->setCellValue('B'.$fila, $datosNomina['Turno']); 

  $spreadsheet->getActiveSheet()->getStyle('D'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('D'.$fila, "NSS: "); 
  $sheet->setCellValue('E'.$fila, $datosNomina['NSS']); 

  $spreadsheet->getActiveSheet()->getStyle('G'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('G'.$fila, "Puesto: "); 
  $sheet->setCellValue('H'.$fila, $datosNomina['puesto']); 
  $fila++;

  $spreadsheet->getActiveSheet()->getStyle('A'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('A'.$fila, "RFC: "); 
  $sheet->setCellValue('B'.$fila, $datosNomina['RFC']); 

  $spreadsheet->getActiveSheet()->getStyle('D'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('D'.$fila, "Fecha de ingreso: "); 
  $sheet->setCellValue('E'.$fila, $datosNomina['FechaIngresoFormat']); 
  $fila++;

  $spreadsheet->getActiveSheet()->mergeCells("A".$fila.":D".$fila);
  $spreadsheet->getActiveSheet()->getStyle('A'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('A'.$fila, "PERCEPCIONES");

  $spreadsheet->getActiveSheet()->mergeCells("G".$fila.":J".$fila);
  $spreadsheet->getActiveSheet()->getStyle('G'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('G'.$fila, "OTROS PAGOS");

  $spreadsheet->getActiveSheet()->mergeCells("M".$fila.":P".$fila);
  $spreadsheet->getActiveSheet()->getStyle('M'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('M'.$fila, "DEDUCCIONES");
  $fila++;

  $spreadsheet->getActiveSheet()->getStyle('A'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('A'.$fila, "Concepto");
  $spreadsheet->getActiveSheet()->getStyle('G'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('G'.$fila, "Concepto");
  $spreadsheet->getActiveSheet()->getStyle('M'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('M'.$fila, "Concepto");

  $spreadsheet->getActiveSheet()->getStyle('B'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('B'.$fila, "Imp. Gravado");
  $spreadsheet->getActiveSheet()->getStyle('H'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('H'.$fila, "Imp. Gravado");
  $spreadsheet->getActiveSheet()->getStyle('N'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('N'.$fila, "Imp. Gravado");

  $spreadsheet->getActiveSheet()->getStyle('C'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('C'.$fila, "Imp. Exento");
  $spreadsheet->getActiveSheet()->getStyle('I'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('I'.$fila, "Imp. Exento");
  $spreadsheet->getActiveSheet()->getStyle('O'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('O'.$fila, "Imp. Exento");

  $spreadsheet->getActiveSheet()->getStyle('D'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('D'.$fila, "Total");
  $spreadsheet->getActiveSheet()->getStyle('J'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('J'.$fila, "Total");
  $spreadsheet->getActiveSheet()->getStyle('P'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('P'.$fila, "Total");
  $fila++;

  $stmt = $conn->prepare("SELECT * FROM detalle_nomina_percepcion_empleado as dnpe INNER JOIN relacion_tipo_percepcion as rtp ON rtp.tipo_percepcion_id = dnpe.relacion_tipo_percepcion_id AND rtp.empresa_id = :empresa_id 
    INNER JOIN relacion_concepto_percepcion as rcp ON dnpe.relacion_concepto_percepcion_id = rcp.id INNER JOIN tipo_percepcion as tp ON tp.id = rtp.tipo_percepcion_id WHERE dnpe.nomina_empleado_id = :idNominaEmpleado ");
  $stmt->bindValue(':empresa_id',$_SESSION['IDEmpresa']);
  $stmt->bindValue(':idNominaEmpleado',$idNominaEmpleado);
  $stmt->execute();
  $datosPercepciones = $stmt->fetchAll();
  //echo "<pre>",print_r($datosPercepciones),"</pre>";

  $total_percepciones = 0.00; $total_percepciones_gravado = 0.00; $total_percepciones_exento = 0.00;
  $total_deducciones = 0.00;
  $total_otros_pagos = 0.00;
  $fila_perc = $fila;
  $fila_op = $fila;
  $fila_ded = $fila;
  $inicio_alineacion1 = $fila;
  
  foreach($datosPercepciones as $dp){

    $sheet->setCellValue('A'.$fila_perc, $dp['clave']." - ".$dp['codigo']." - ".$dp['concepto_nomina']);
    $sheet->setCellValue('B'.$fila_perc, number_format($dp['importe'],2));
    $sheet->setCellValue('C'.$fila_perc, number_format($dp['importe_exento'],2));

    $total_concepto = $dp['importe'] + $dp['importe_exento'];

    $sheet->setCellValue('D'.$fila_perc, number_format($total_concepto,2));
    $fila_perc++;

    $total_percepciones = $total_percepciones + $total_concepto;
    $total_percepciones_gravado = $total_percepciones_gravado + $dp['importe'];
    $total_percepciones_exento = $total_percepciones_exento + $dp['importe_exento'];
  }

  if($datosNomina['SAE'] > 0){

    $sheet->setCellValue('A'.$fila_perc, "Subsidio al empleo");
    $sheet->setCellValue('B'.$fila_perc, 0.00);
    $sheet->setCellValue('C'.$fila_perc, 0.00);
    $sheet->setCellValue('D'.$fila_perc, number_format($datosNomina['SAE'],2));
    $fila_perc++;

    $total_percepciones = $total_percepciones + $datosNomina['SAE'];
  }


  //OTROS PAGOS
  $stmt = $conn->prepare("SELECT * 
    FROM detalle_otros_pagos_nomina_empleado as dnope 
    INNER JOIN otros_pagos as op ON op.id = dnope.otros_pagos_id 
    INNER JOIN relacion_concepto_otros_pagos as rcop ON dnope.relacion_concepto_otros_pagos_id = rcop.id AND rcop.empresa_id = :empresa_id 
    WHERE dnope.nomina_empleado_id = :idNominaEmpleado ");
  $stmt->bindValue(':empresa_id',$_SESSION['IDEmpresa']);
  $stmt->bindValue(':idNominaEmpleado',$idNominaEmpleado);
  $stmt->execute();
  $datosOtrosPagos = $stmt->fetchAll();
  //echo "<pre>",print_r($datosOtrosPagos),"</pre>";


  foreach($datosOtrosPagos as $dop){

    $sheet->setCellValue('G'.$fila_op, $dop['codigo']." - ".$dop['codigo']." - ".$dop['concepto_nomina']);
    $sheet->setCellValue('H'.$fila_op, number_format($dop['importe'],2));
    $sheet->setCellValue('I'.$fila_op, 0.00);

    $total_concepto = $dop['importe'];

    $sheet->setCellValue('J'.$fila_op, number_format($total_concepto,2));
    $fila_op++;

    $total_otros_pagos = $total_otros_pagos + $dop['importe'];
  }
  //OTROS PAGOS

  //DEDUCCIONES
  $stmt = $conn->prepare("SELECT * FROM detalle_nomina_deduccion_empleado as dnde INNER JOIN relacion_tipo_deduccion as rtd ON rtd.tipo_deduccion_id = dnde.relacion_tipo_deduccion_id AND rtd.empresa_id = :empresa_id INNER JOIN relacion_concepto_deduccion as rcd ON dnde.relacion_concepto_deduccion_id = rcd.id INNER JOIN tipo_deduccion as td ON td.id = rtd.tipo_deduccion_id WHERE dnde.nomina_empleado_id = :idNominaEmpleado ");
  $stmt->bindValue(':empresa_id',$_SESSION['IDEmpresa']);
  $stmt->bindValue(':idNominaEmpleado',$idNominaEmpleado);
  $stmt->execute();
  $datosDeducciones = $stmt->fetchAll();
  //echo "<pre>",print_r($datosDeducciones),"</pre>";

  if($datosNomina['ISR'] > 0){

    $stmt = $conn->prepare("SELECT tp.codigo, tp.concepto, rtd.clave FROM tipo_deduccion as tp INNER JOIN relacion_tipo_deduccion as rtd ON rtd.tipo_deduccion_id = tp.id AND rtd.empresa_id = ".$_SESSION['IDEmpresa']." WHERE tp.id = 2 ");
    $stmt->execute();
    $datosISR = $stmt->fetch();

    $sheet->setCellValue('M'.$fila_ded, $datosISR['clave']." - ".$datosISR['codigo']." - ".$datosISR['concepto']);
    $sheet->setCellValue('N'.$fila_ded, number_format($datosNomina['ISR'],2));
    $sheet->setCellValue('O'.$fila_ded, 0.00);
    $sheet->setCellValue('P'.$fila_ded, number_format($datosNomina['ISR'],2));
    $fila_ded++;

    $total_deducciones = $total_deducciones + $datosNomina['ISR'];
  }

  if($datosNomina['cuotaIMSS'] > 0){

    $stmt = $conn->prepare("SELECT tp.codigo, tp.concepto, rtd.clave FROM tipo_deduccion as tp INNER JOIN relacion_tipo_deduccion as rtd ON rtd.tipo_deduccion_id = tp.id AND rtd.empresa_id = ".$_SESSION['IDEmpresa']." WHERE tp.id = 1 ");
    $stmt->execute();
    $datosIMSS = $stmt->fetch();

    $sheet->setCellValue('M'.$fila_ded, $datosIMSS['clave']." - ".$datosIMSS['codigo']." - ".$datosIMSS['concepto']);
    $sheet->setCellValue('N'.$fila_ded, number_format($datosNomina['cuotaIMSS'],2));
    $sheet->setCellValue('O'.$fila_ded, 0.00);
    $sheet->setCellValue('P'.$fila_ded, number_format($datosNomina['cuotaIMSS'],2));
    $fila_ded++;

    $total_deducciones = $total_deducciones + $datosNomina['cuotaIMSS'];
  }

  foreach($datosDeducciones as $dd){

    $sheet->setCellValue('M'.$fila_ded, $dd['clave']." - ".$dd['codigo']." - ".$dd['concepto_nomina']);
    $sheet->setCellValue('N'.$fila_ded, number_format($dd['importe'],2));
    $sheet->setCellValue('O'.$fila_ded, 0.00);

    $total_concepto = $dd['importe'];

    $sheet->setCellValue('P'.$fila_ded, number_format($total_concepto,2));
    $fila_ded++;

    $total_deducciones = $total_deducciones + $dd['importe'];
  }
  //DEDUCCIONES

  if($fila_ded > $fila_perc){

    if($fila_ded > $fila_op){
      $fila = $fila_ded + 1;
    }
    else{
      $fila = $fila_op + 1;
    }
  }
  else{
    if($fila_perc > $fila_op){
      $fila = $fila_perc + 1;
    }
    else{
      $fila = $fila_op + 1;
    }
  }

  $final_alineacion1 = $fila;
  $sheet->getStyle('B'.$inicio_alineacion1.':D'.$final_alineacion1)->getAlignment()->setHorizontal('right');
  $sheet->getStyle('H'.$inicio_alineacion1.':J'.$final_alineacion1)->getAlignment()->setHorizontal('right');

  $spreadsheet->getActiveSheet()->getStyle('A'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('A'.$fila, "TOTAL");
  $spreadsheet->getActiveSheet()->getStyle('B'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('B'.$fila, number_format($total_percepciones_gravado,2));
  $spreadsheet->getActiveSheet()->getStyle('C'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('C'.$fila, number_format($total_percepciones_exento,2));
  $spreadsheet->getActiveSheet()->getStyle('D'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('D'.$fila, number_format($total_percepciones,2));

  $spreadsheet->getActiveSheet()->getStyle('G'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('G'.$fila, "TOTAL");
  $spreadsheet->getActiveSheet()->getStyle('J'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('J'.$fila, number_format($total_otros_pagos,2));

  $spreadsheet->getActiveSheet()->getStyle('M'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('M'.$fila, "TOTAL");
  $spreadsheet->getActiveSheet()->getStyle('P'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('P'.$fila, number_format($total_deducciones,2));
  $sheet->getStyle('P'.$fila)->getAlignment()->setHorizontal('right');


  $fila = $fila + 2;

  $total_neto = $total_percepciones + $total_otros_pagos - $total_deducciones;

  $spreadsheet->getActiveSheet()->getStyle('M'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('M'.$fila, "TOTAL NETO");
  $spreadsheet->getActiveSheet()->getStyle('P'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('P'.$fila, number_format($total_neto,2));

  $sheet->getStyle('P'.$fila)->getAlignment()->setHorizontal('right');

  $cadena = str_replace(" ", "_", trim($nombreEmpleado));

  $archivo = "nomina_".$cadena;


$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="'.$archivo.'.xlsx"');
$writer->save("php://output");
?>
<?php
  require_once('../../../include/db-conn.php');

  if(isset($_GET['idempleado'])){
    $idEmpleado =  $_GET['idempleado'];
  }
  $stmt = $conn->prepare("SELECT PKVacaciones, FKEmpleado, Dias_de_Vacaciones_Tomados, DATE_FORMAT(FechaIni,'%d/%m/%Y') as FechaIni, DATE_FORMAT(FechaFin,'%d/%m/%Y') as FechaFin, Prima_Vacacional, Total_Vacaciones FROM vacaciones WHERE FKEmpleado =".$idEmpleado."  AND (YEAR(FechaIni) = YEAR(CURDATE()) OR YEAR(FechaIni) = YEAR(CURDATE()) -1)");
  $stmt->execute();
  $table="";
  while (($row = $stmt->fetch()) !== false) {
    $recibo = '<button type=\"button\" class=\"btn btn-primary mostrarRecibo\" id=\"'.$row['PKVacaciones'].'\" ><i class=\"far fa-sticky-note\"></i> Recibo</button>&nbsp;&nbsp;';
    $delete ='<a class=\"btn btn-danger\" href=\"functions/eliminar_vacaciones.php?id='.$row['PKVacaciones'].'\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';
    $total_salario = number_format(floatval(round($row['Total_Vacaciones'] - $row['Prima_Vacacional'],2)),2,'.','');

    //number_format ( float $number , int $decimals = 0 , string $dec_point = "." , string $thousands_sep = "," )
    $table.='{"PKVacaciones":"'.$row['PKVacaciones'].'","Dias de vacaciones":"'.$row['Dias_de_Vacaciones_Tomados'].'","Fecha Inicial":"'.$row['FechaIni'].'","Fecha Final":"'.$row['FechaFin'].'","Total Salario":"'.$total_salario.'","Prima Vacacional":"'.number_format($row['Prima_Vacacional'],2,'.','').'","Total Vacaciones":"'.number_format($row['Total_Vacaciones'],2,'.','').'","Acciones":"'.$recibo.$delete.'"},';
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
?>
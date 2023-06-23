<?php
  require_once('../../../include/db-conn.php');
  
  $json = new \stdClass();
  $PKVacaciones =  $_GET['PKVacaciones'];

  $stmt = $conn->prepare("SELECT * FROM vacaciones WHERE PKVacaciones = :id");
  $stmt->bindValue(':id',$PKVacaciones);
  $stmt->execute();
  $row = $stmt->fetch();   
  
  $mes = array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
  
  $fecha_ini = explode('-', $row['FechaIni']);
  $mes_nombre_ini = $mes[$fecha_ini[1]-1];
  $fecha_ini_com =$fecha_ini[2].' de '.$mes_nombre_ini.' del '.$fecha_ini[0];

  $fecha_fin = explode('-', $row['FechaFin']);
  $mes_nombre_fin = $mes[$fecha_fin[1]-1];
  $fecha_fin_com =$fecha_fin[2].' de '.$mes_nombre_fin.' del '.$fecha_fin[0];

  $fecha_completa = $fecha_ini_com.' al '.$fecha_fin_com;
  
  $json->periodo_vacaciones = $fecha_completa;
  $json->dias_vacaciones = $row['Dias_de_Vacaciones_Tomados'];
  $json->sueldo_vacaciones = number_format($row['Total_Vacaciones'] - $row['Prima_Vacacional'],2,'.','');
  $json->prima_vacacional = $row['Prima_Vacacional'];
  $json->sueldo_total = $row['Total_Vacaciones'];
  
  $json = json_encode($json);

  echo $json;


?>

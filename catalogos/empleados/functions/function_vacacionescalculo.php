<?php
  require_once('../../../include/db-conn.php');
  
  $json = new \stdClass();
  $fkEmpleado =  $_GET['fkEmpleado'];
  $dias_vacaciones =  $_GET['dias'];
  $sueldoSemanal =  $_GET['sueldoSemanal'];

  $sueldoDiario = $sueldoSemanal / 7;

  $total_vacaciones = floatval($sueldoDiario * $dias_vacaciones);

  $json->salario_vacaciones = number_format($total_vacaciones,2,'.','');
  $prima_vacacional = floatval(round($total_vacaciones * 0.25,2));
  $json->prima_vacacional = $prima_vacacional;
  $json->total_vacaciones = floatval(round($total_vacaciones + $prima_vacacional,2));

  $json = json_encode($json);

  echo $json;


?>

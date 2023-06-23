<?php
  $fecha1 = new DateTime('10:30:00');
  $fecha2 = new DateTime('14:35:44');
  $intervalo = $fecha1->diff($fecha2);
  //echo $intervalo->h.' horas<br>';
  //echo $intervalo->i.' minutos<br>';
  //echo $intervalo->s.' segundos<br>';
  $hola = $intervalo->h." Horas ".$intervalo->i." minutos ".$intervalo->s." segundos ";
  echo $hola;

  $diaSemEn = date('D',strtotime('2019-08-15'));
  echo $diaSemEn;
 ?>

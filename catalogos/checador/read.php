<?php
require_once('../../include/db-conn.php');

$idChecador = array();
$fecha = array();
$hora = array();
$fh = fopen('checador.dat', 'r');
$i = 0;
while(!feof($fh))
{
  $line= fgets($fh);
  $idChecador[$i] = preg_split("/[\s]+/", $line)[1];
  $fecha[$i] = preg_split("/[\s]+/", $line)[2];
  $hora[$i] = preg_split("/[\s]+/", $line)[3];
  $stmt = $conn->prepare('INSERT INTO checador (IdChecador,Fecha,Hora)VALUES(:idChecador,:fecha,:hora)');
  $stmt->bindValue(':idChecador',$idChecador[$i]);
  $stmt->bindValue(':fecha',$fecha[$i]);
  $stmt->bindValue(':hora',$hora[$i]);
  $stmt->execute();
  $i++;
}
  fclose($fh);



?>

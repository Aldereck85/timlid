<?php
session_start();

if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 1 || $_SESSION["FKRol"] == 4)){
  require_once('funcion_calculovacaciones.php');
  
  $idEmpleado = $_GET['id'];
  calculoVacaciones($idEmpleado, 0);
}else {
header("location:../../dashboard.php");
}
  
?>

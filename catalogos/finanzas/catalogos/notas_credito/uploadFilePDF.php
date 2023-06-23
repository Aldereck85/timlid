<?php
session_start();
$PKEmpresa = $_SESSION["IDEmpresa"];
include "../../../../include/db-conn.php";

if(isset($_FILES['file']))
{
	$archivo = $_FILES['file'];
	$time = time();
	$fileName = $archivo["name"];
	
  	$newName = $time.'.PDF';

	$ruta = $_ENV['RUTA_ARCHIVOS_WRITE'].$PKEmpresa.'/archivos'.'/';

	move_uploaded_file($archivo['tmp_name'], $ruta.$newName);

	//Retorno
	echo $newName;
}

?>
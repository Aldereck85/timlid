<?php
session_start();
$PKEmpresa = $_SESSION["IDEmpresa"];
include "../../../../include/db-conn.php";

if(isset($_POST["url"]))
{
	$url = $_POST["url"];
	$id = $_POST["id"];

	$ruta = $_ENV['RUTA_ARCHIVOS_WRITE'].$PKEmpresa.'/archivos'.'/';
   
	if($url !='')
	{
		$org_file = $ruta.$url;
		$destination= $ruta."notaCredito_".$id.".pdf";
		rename( $org_file , $destination);

		echo $destination;
	}
}

?>
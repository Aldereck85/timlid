<?php
session_start();
$PKEmpresa = $_SESSION["IDEmpresa"];
include "../../../../include/db-conn.php";

if(isset($_POST["image"]))
{
	$url = $_POST["image"];
	$id = $_POST["id"];

	$rutaTemp = $_ENV['RUTA_ARCHIVOS_WRITE'].$PKEmpresa.'/temp'.'/';
	$rutaImg = $_ENV['RUTA_ARCHIVOS_WRITE'].$PKEmpresa.'/img'.'/';
   
	if($url !='')
	{
		$org_image=$rutaTemp.$url;
		$destination=$rutaImg."p_".$id.".jpg";
		rename( $org_image , $destination);
	}

	unlink($rutaTemp.$url);

}

?>
<?php
session_start();
if(isset($_POST["url"]))
{
	$url = $_POST["url"];
	$id = $_POST["id"];
	$PKEmpresa = $_SESSION["IDEmpresa"];

	$fileName="poliza_".$id.".pdf";

	if($url !='')
	{
		$org_file = __DIR__."/../../../../file_server/" . $PKEmpresa ."/archivos/logistica/vehiculos/pdfPolizas/".$url;
		$destination=__DIR__."/../../../../file_server/" . $PKEmpresa ."/archivos/logistica/vehiculos/pdfPolizas/".$fileName;
		rename( $org_file , $destination);

		echo $fileName;
	}
}

?>
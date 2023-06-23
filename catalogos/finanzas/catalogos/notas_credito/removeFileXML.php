<?php
session_start();
$PKEmpresa = $_SESSION["IDEmpresa"];
include "../../../../include/db-conn.php";

if(isset($_POST["url"]))
{
	$url = $_POST["url"];
	$id = $_POST["id"];
   
	$ruta_t = $_ENV['RUTA_ARCHIVOS_WRITE'].$PKEmpresa.'/temp'.'/';
	$ruta = $_ENV['RUTA_ARCHIVOS_WRITE'].$PKEmpresa.'/archivos'.'/';

	if($url !='')
	{
		$org_xml= $ruta_t.$url;
		$destination= $ruta."/notaCredito_".$id.".xml";
		rename( $org_xml , $destination);

		

		echo $destination;
	}

	/*unlink("xmlCarga/".$url);*/
}

?>
<?php
session_start();
if(isset($_FILES["file-0"]))
{
	$archivo = $_FILES["file-0"];
	$PKEmpresa = $_SESSION["IDEmpresa"];
	$fileName = $archivo["name"];

	//crea la ruta en el servidor si no se tiene aún
	$path = __DIR__."/../../../../file_server/" . $PKEmpresa ."/archivos/logistica/vehiculos/pdfPolizas/";
	if (!file_exists($path)) {
		mkdir($path, 0777, true);
	}

	move_uploaded_file($archivo['tmp_name'], __DIR__."/../../../../file_server/" . $PKEmpresa ."/archivos/logistica/vehiculos/pdfPolizas/".$fileName);

	//Retorno
	echo $fileName;
}

?>
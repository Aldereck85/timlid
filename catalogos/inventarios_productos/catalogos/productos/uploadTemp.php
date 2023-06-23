<?php
session_start();
$PKEmpresa = $_SESSION["IDEmpresa"];
include "../../../../include/db-conn.php";

if(isset($_POST["image"]))
{
	$data = $_POST["image"];

	$image_array_1 = explode(";", $data);

	$image_array_2 = explode(",", $image_array_1[1]);

	$data = base64_decode($image_array_2[1]);

	$imageName = time() . '.jpg';

	$ruta = $_ENV['RUTA_ARCHIVOS_WRITE'].$PKEmpresa.'/temp'.'/';

	file_put_contents($ruta.$imageName, $data);

	//Retorno
	echo $imageName;


	if(isset($_POST["imagenSubir"]))
		$imagenSubir = $_POST["imagenSubir"];
	else
		$imagenSubir = "";

	if($imagenSubir != "")
		unlink($ruta.$imagenSubir);


	//Eliminar archivos con mas de un día en el temporal
	$dir = opendir($ruta);
	while($f = readdir($dir))
	{
		if((time()-filemtime($ruta.$f) > 3600*24) and !(is_dir($ruta.$f)))
		unlink($ruta.$f);
	}
	closedir($dir);
	

}

?>
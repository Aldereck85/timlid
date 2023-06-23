<?php
session_start();
$PKEmpresa = $_SESSION["IDEmpresa"];
include "../../../../include/db-conn.php";

if(isset($_POST["url"]))
{
	$url = $_POST["url"];
	$ruta = $_ENV['RUTA_ARCHIVOS_WRITE'].$PKEmpresa.'/temp'.'/';

	if($url !='')
	{
		unlink($ruta.$url);
		echo $ruta.$url;
	}

	$dir = opendir($ruta);
	while($f = readdir($dir))
	{
		if((time()-filemtime($ruta.$f) > 3600*24) and !(is_dir($ruta.$f)))
		unlink($ruta.$f);
	}
	closedir($dir);
}

?>
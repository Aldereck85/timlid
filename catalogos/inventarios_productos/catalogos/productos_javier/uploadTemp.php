<?php

if(isset($_POST["image"]))
{
	$data = $_POST["image"];

	$image_array_1 = explode(";", $data);

	$image_array_2 = explode(",", $image_array_1[1]);

	$data = base64_decode($image_array_2[1]);

	$imageName = time() . '.jpg';

	file_put_contents("temp/".$imageName, $data);

	//Retorno
	echo $imageName;


	if(isset($_POST["imagenSubir"]))
		$imagenSubir = $_POST["imagenSubir"];
	else
		$imagenSubir = "";

	if($imagenSubir != "")
		unlink("temp/".$imagenSubir);


	//Eliminar archivos con mas de un día en el temporal
	$dir = opendir('temp/');
	while($f = readdir($dir))
	{
		if((time()-filemtime('temp/'.$f) > 3600*24) and !(is_dir('temp/'.$f)))
		unlink('temp/'.$f);
	}
	closedir($dir);
	

}

?>
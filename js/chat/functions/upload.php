<?php 
//ini_set('display_errors', 0);
//ini_set('display_startup_errors', 0);
//error_reporting(E_ALL);
/* Getting file name */
//var_dump($_FILES);
$filename = $_FILES['file']['name'];
$partesruta = pathinfo($filename);
$identificador = round(microtime(true));

$json = new \stdClass();

$json->res = -1;

$nombrearchivo = str_replace(" ", "_", $partesruta['filename']);

$nombrefinal = $nombrearchivo.'_'.$identificador.'.'.$partesruta['extension'];
/* Location */
$location = "upload/".$nombrefinal; 
$uploadOk = 1; 

if($partesruta['extension'] == "jpg" || $partesruta['extension'] == "jpeg" || $partesruta['extension'] == "gif" || $partesruta['extension'] == "png" || $partesruta['extension'] == "tiff" || $partesruta['extension'] == "tif" || $partesruta['extension'] == "bmp" || $partesruta['extension'] == "svg")
{
   if($_FILES['file']['size'] > 4000000){
   	$json->res = 1;
   }      
}
else{
	if($_FILES['file']['size'] > 20000000){
   	$json->res = 2;
   }  
}

if($json->res != 1 && $json->res != 2){
	if($uploadOk == 0){ 
	   $json->res = 0; 
	}else{ 
	   /* Upload file */
	   if(move_uploaded_file($_FILES['file']['tmp_name'], $location)){ 
	   	  $json->res = 4;
	   	  $json->location = $location;
	   }else{ 
	      $json->res = 0; 
	   } 
	} 
}

$json = json_encode($json);
echo $json;
?> 
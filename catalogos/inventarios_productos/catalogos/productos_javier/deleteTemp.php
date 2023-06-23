<?php

if(isset($_POST["image"]))
{
	$url = $_POST["image"];

	$id = $_POST["id"];
   
	if($url !='')
	{
		$org_image="temp/".$url;
		$destination="../../../../imgProd/p_".$id.".jpg";
		rename( $org_image , $destination);
	}

	unlink("temp/".$url);

}

?>
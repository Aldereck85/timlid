<?php 
  
/* Getting file name */
$filename = $_FILES['file']['name'];
$partesruta = pathinfo($filename);
$identificador = round(microtime(true));

$nombrefinal = $partesruta['filename'].'_'.$identificador.'.'.$partesruta['extension'];
/* Location */
$location = "upload/".$nombrefinal; 
$uploadOk = 1; 
  
if($uploadOk == 0){ 
   echo 0; 
}else{ 
   /* Upload file */
   if(move_uploaded_file($_FILES['file']['tmp_name'], $location)){ 
      echo $location; 
   }else{ 
      echo 0; 
   } 
} 
?> 
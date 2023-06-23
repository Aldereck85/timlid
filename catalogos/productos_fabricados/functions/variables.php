<?php
  //echo "hola<br>";

  for($x = 1;$x <4;$x++)
  {
    ${"myArray" . $x} = [];
    //$myArray = [];
  }
  $myArray1[] = "Hola";
  $myArray2[] = "como";
  $myArray3[] = "estas?";
  echo $myArray1[0]."<br>";
  echo $myArray2[0]."<br>";
  echo $myArray3[0]."<br>";
?>

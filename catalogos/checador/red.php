<?php
/*$personalinfo = file("checador.dat");

$firstname = $personalinfo[0];
print("<p> $firstname </p>");*/

/*$file = fopen("checador.dat", "r") or exit("Unable to open the file!");
while(!feof($file))
{
  echo fgets($file). "<br />";
}
  fclose($file);*/

  // Escribir un fichero en un array. En este ejemplo iremos a través de HTTP para
// obtener el código fuente HTML de un URL.





/*
$line = 'Joe Jack Jill Jimmy Jerom Jolly Jecob Jason Jasper';
$cols = explode(' ', $line);

echo $cols[1]; // Will return "Jack"

*/


////////la mejor opcion para acceder a las diferentes columnas

/*
$fh = fopen('checador.dat', 'r'); // open input file
$line= fgets($fh); // get a line from the file
$idChecador = (preg_split("/[\s]+/", $line))[3]; // get 2nd column in a split by whitespace
echo $idChecador;
fclose($fh); // close file
*/


/*
  $ddate = "2019-07-22";
  $date = new DateTime($ddate);
  $week = $date->format("W");
  echo "Weeknummer: $week";*/

  //print date('H:i');
  /*$var = date('H:i');

  $ThatTime ="10:08";
  if ($var >= strtotime($ThatTime)) {
    echo $var;
  }else{
    echo $ThatTime;
  }*/

echo date('Y-m-d', strtotime('first friday of january 2019'));



?>

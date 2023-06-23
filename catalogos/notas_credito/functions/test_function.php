<?php
session_start();
$ruta_api = "../../../";
$empresa = $_SESSION["IDEmpresa"];
$userid = $_SESSION["PKUsuario"];
require_once($ruta_api.'include/db-conn.php');
require_once($ruta_api.'include/functions_api_facturation.php');
require_once $ruta_api . 'vendor/facturapi/facturapi-php/src/Facturapi.php';
$api = new API();
$dbh;

$folioMax;

$strinfolio = "";

$query = sprintf("SELECT folion_nota as idMax FROM notas_cuentas_por_cobrar where empresa_id = $empresa ORDER BY fecha_captura desc limit 1;");
$stmt = $conn->prepare($query);
$stmt->execute();
$folioMax = $stmt->fetchAll();
if($folioMax){
    $folioMax = $folioMax[0]["idMax"];
}else{
$folioMax = 1;
}

if($folioMax != 1){
    $intFolio = intval($folioMax);
    $tamaño = (strlen($intFolio));
    $count = 1;
    while($count<=(6-$tamaño)){
        $strinfolio .="0";
        $count++;
    }
    $strinfolio .= $folioMax + 1;
  //  echo($strinfolio);
}else{
    $strinfolio = "000001";
}

echo($strinfolio);
?>

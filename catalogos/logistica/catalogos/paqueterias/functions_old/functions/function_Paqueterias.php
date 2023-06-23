<?php
session_start();
require_once('../../../include/db-conn.php');

$stmt = $conn->prepare('SELECT p.*, dfp.*, ef.Estado est FROM proveedores p
                        INNER JOIN domicilio_fiscal_proveedor dfp ON p.PKProveedor = dfp.FKProveedor
                        INNER JOIN estados_federativos ef ON dfp.Estado = ef.PKEstado
                        WHERE p.tipo = 2 AND estatus = 1 AND empresa_id = :id_empresa');
$stmt->bindValue(":id_empresa",$_SESSION['IDEmpresa']);
$stmt->execute();
$table="";
$no = 1;

while (($row = $stmt->fetch()) !== false) {
    $numInt = "S/N";

    if(isset($row['Numero_interior']) && !empty($row['Numero_interior'])){
      $numInt = $row['Numero_interior'];
    }
    
    $acciones = '<i class=\"permission-view-edit\"><img class=\"btnEdit\" onclick=\"obtenerIdPaqueteriaEditar('.$row['PKProveedor'].');\" src=\"../../img/timdesk/edit.svg\"></i>';

    $table.='{
      "No":"'.$no.'",
      "Razon Social":"'.$row['Razon_Social'].'",
      "Email":"'.$row['Email'].'",
      "RFC":"'.$row['RFC'].'",
      "Calle":"'.$row['Calle'].'",
      "Numero exterior":"'.$row['Numero_exterior'].'",
      "Interior":"'.$numInt.'",
      "Colonia":"'.$row['Colonia'].'",
      "Municipio":"'.$row['Municipio'].'",
      "Estado":"'.$row['est'].'",
      "Acciones":"'.$acciones.'",
      "Codigo Postal":"'.$row['CP'].'"},';
    $no++;
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>

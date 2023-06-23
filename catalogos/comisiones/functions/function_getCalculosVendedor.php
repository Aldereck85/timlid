<?php 
session_start();
require_once('../../../include/db-conn.php');
/*Recupera los cálculos del cálculo dependiendo los parametros que se hayan filtrado*/

$empresa = $_SESSION["IDEmpresa"];
$idVendedor = $_REQUEST["idVendedor"];


$stmt = $conn->prepare('SELECT c.id as idComision, 
                                c.folio as folio, 
                                c.fecha_registro as fecha, 
                                c.monto_calculado as monto_calculado, 
                                c.monto_ingresado as monto_ingresado,
                                c.saldo_insoluto as saldo_insoluto, 
                                c.estatus as estatus 
                        FROM comisiones c 
                        WHERE c.id_empleado=:idVendedor and c.id_empresa=:empresa');
$stmt->bindValue(":empresa",$empresa);
$stmt->bindValue(":idVendedor",$idVendedor);
$stmt->execute();

$table="";

while (($row = $stmt->fetch()) !== false) {

    $row['folio']='<a style=\"cursor:pointer\" href=\"../comisiones/detalle_calculo.php?idComision='.$row['idComision'].'&idVendedor='.$idVendedor.'\">'.$row['folio'].'</a>';

    if($row['estatus']==1){
        $row['estatus']= '<span class=\"left-dot red-dot\">Pendiente de pago</span>';
    }else if($row['estatus']==2){
        $row['estatus']= '<span class=\"left-dot green-dot\">Pagado</span>';
    }else if($row['estatus']==3){
        $row['estatus']= '<span class=\"left-dot gray-dot\">Parcialmente pagado</span>';
    }

    $row['monto_calculado'] = number_format($row['monto_calculado'], 2, '.', ' ');
    $row['monto_ingresado'] = number_format($row['monto_ingresado'], 2, '.', ' ');
    $row['saldo_insoluto'] = number_format($row['saldo_insoluto'], 2, '.', ' ');

    //llena la tabla en formato json
    $table.= '{"folio":"'.$row['folio'].'",
        "fecha":"'.date("d-m-Y", strtotime($row['fecha'])).'",
        "monto_calculado":"$'.$row['monto_calculado'].'",
        "monto_ingresado":"$'.$row['monto_ingresado'].'",
        "saldo_insoluto":"$'.$row['saldo_insoluto'].'",
        "estatus":"'.$row['estatus'].'"},';
}

$table = substr($table,0,strlen($table)-1);
echo '{"data":['.$table.']}'; 
?>
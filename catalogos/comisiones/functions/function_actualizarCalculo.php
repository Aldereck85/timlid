<?php
session_start();
require_once('../../../include/db-conn.php');
/*Actualiza un calculo de comisiones*/ 

function GetEvn()
{
    include "../../../include/db-conn.php";
    $appUrl = $_ENV['APP_URL'] ?? 'https://app.timlid.com/';
    return ['server' => $appUrl];
}

$envVariables = GetEvn();
$appUrl = $envVariables['server'];
$id = $_SESSION["PKUsuario"];
$fecha_desde = $_REQUEST["fecha_desde"];
$fecha_hasta = $_REQUEST["fecha_hasta"];
$porcentaje = $_REQUEST["porcentaje"];
$porcentaje = $porcentaje/100;
$monto_calculado = $_REQUEST["monto_calculado"];
$monto_ingresado = $_REQUEST["monto_ingresado"];
$facturas_seleccionadas = $_REQUEST["facturas_seleccionadas"];
$totalMA = $_REQUEST["totalMA"];
$idComision = $_REQUEST["idComision"];

$saldo_insoluto = $monto_ingresado - $totalMA;

try{
    $conn->beginTransaction();

    $stmt=$conn->prepare('SELECT estatus FROM comisiones where id=:idComision');
    $stmt->bindValue(":idComision",$idComision);
    $stmt->execute();
    $estatusAnt = $stmt->fetchAll();
    $estatus=$estatusAnt[0]['estatus'];

    if($saldo_insoluto == 0){
        $estatus = 2;
    }

    $stmt->closeCursor();


    $stmt=$conn->prepare('DELETE FROM detalle_comision_factura where id_comision=:idComision');
    $stmt->bindValue(":idComision",$idComision);

    $stmt->execute();
    $stmt->closeCursor();

    $stmt=$conn->prepare('DELETE FROM detalle_comision_venta where id_comision=:idComision');
    $stmt->bindValue(":idComision",$idComision);

    $stmt->execute();
    $stmt->closeCursor();

    $query=sprintf('CALL spu_calculo_comision(?,?,?,?,?,?,?,?,?)');
    $stmt = $conn->prepare($query);
    $stmt->execute(array($idComision, $fecha_desde, $fecha_hasta, $id, $porcentaje, $monto_calculado, $monto_ingresado, $saldo_insoluto, $estatus));
    $stmt->closeCursor();

    foreach ($facturas_seleccionadas as $fac => $f){

        //inserta detalle de factura o venta según sea el caso
        if($f['tipoDoc'] == "1"){
            $stmt2 = $conn->prepare('INSERT INTO detalle_comision_factura (id_comision,id_factura,monto_comisionado) 
            VALUES (:idcomision,:idfac,:montoCal);');
            $stmt2->execute(array(":idcomision" => $idComision,":idfac" => $f['idFactura'],":montoCal" => $f['MontoCom']));
            $aux = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else if($f['tipoDoc'] == "2"){
            $stmt2 = $conn->prepare('INSERT INTO detalle_comision_venta (id_comision,id_venta,monto_comisionado) 
            VALUES (:idcomision,:idVenta,:montoCal);');
            $stmt2->execute(array(":idcomision" => $idComision,":idVenta" => $f['idFactura'],":montoCal" => $f['MontoCom']));
            $aux = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
    
    $conn->commit();
    $data['result']=1;
}catch(Exception $e){
    $conn->rollBack();
    $data['result']=$e;
    }

echo json_encode($data); 
?>
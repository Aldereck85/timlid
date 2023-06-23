<?php
session_start();
require_once('../../../include/db-conn.php');
/*Elimina un calculo de comisiones*/ 

function GetEvn()
{
    include "../../../include/db-conn.php";
    $appUrl = $_ENV['APP_URL'] ?? 'https://app.timlid.com/';
    return ['server' => $appUrl];
}

$envVariables = GetEvn();
$appUrl = $envVariables['server'];

$idComision = $_REQUEST["idComision"];

//valida el estatus de la comision, solo se puuede eliminar si su estatus está en "pendiente de pago"
$stmt=$conn->prepare('SELECT estatus from comisiones where id = :idComision');
$stmt->bindValue(":idComision",$idComision);
$stmt->execute();
$res = $stmt->fetchAll();

if($res[0]['estatus'] != 1){
    $data['result']=0;
}else{
    try{
        $conn->beginTransaction();
    
        $stmt=$conn->prepare('DELETE FROM detalle_comision_factura where id_comision=:idComision');
        $stmt->bindValue(":idComision",$idComision);
        $stmt->execute();
        $stmt->closeCursor();

        $stmt=$conn->prepare('DELETE FROM detalle_comision_venta where id_comision=:idComision');
        $stmt->bindValue(":idComision",$idComision);
        $stmt->execute();
        $stmt->closeCursor();
    
        $stmt=$conn->prepare('DELETE FROM comision_abonos where id_comision=:idComision');
        $stmt->bindValue(":idComision",$idComision);
        $stmt->execute();
        $stmt->closeCursor();
        
        $stmt=$conn->prepare('DELETE FROM comisiones where id=:idComision');
        $stmt->bindValue(":idComision",$idComision);
        $stmt->execute();
    
        
        if($stmt->rowCount() > 0) {
            $conn->commit();
            $data['result']=1;
        }
    }catch(Exception $e){
        $conn->rollBack();
        $data['result']=$e;
    }
}

echo json_encode($data); 
?>
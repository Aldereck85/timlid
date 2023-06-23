<?php
session_start();
require_once('../../../include/db-conn.php');
$idEmpresa=$_SESSION['IDEmpresa'];
$idFactura=$_REQUEST['id'];
try{
    $stmt = $conn->prepare("SELECT f.estatus, f.cliente_id from facturacion as f where empresa_id=:idEmpresa and id=:idFactura and f.prefactura = 0;");
    $stmt->bindValue(":idEmpresa",$idEmpresa);
    $stmt->bindValue(":idFactura",$idFactura);
    $stmt->execute();

    $res = $stmt->fetchAll();
    $estatus=$res[0]['estatus'];
    $cliente_id=$res[0]['cliente_id'];

    $stmt->closeCursor();

    $arr_estatusAceptados=array(1,2,3);

    //estructura de variable de sesion "accion - id factura - id cliente".
    //accion 1: redirecciona al index de recepcion de pagos y filtra la factura.
    //accion 2: redirecciona a un nuevo pago con la factura ya cargada en pantalla.
    if(in_array($estatus,$arr_estatusAceptados)){
        $data['idFactura']=$idFactura;
        $data['cliente_id']=$cliente_id;
        $data['estatus']="ok";
    }else{
        $data['result']="La factura está cancelada";
        $data['estatus']="cancelada";
    }

}catch(Exception $e){
    $data['estatus']="err";
    $data['result']=$e->getMessage();
}


echo json_encode($data);
?>
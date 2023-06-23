<?php
session_start();
require_once('../../../include/db-conn.php');

if(isset($_REQUEST['is_invoice'])){
    $idEmpresa=$_SESSION['IDEmpresa'];
    $idFactura=$_REQUEST['id'];
    $isInvoice=$_REQUEST['is_invoice'];

    try{

        if($isInvoice == 1){
            $query = ("SELECT f.estatus, f.cliente_id from facturacion as f where empresa_id=:idEmpresa and id=:idFactura and f.prefactura = 0;");
        }else{
            $query = ("SELECT vd.estatus_cuentaCobrar as estatus, vd.FKCliente as cliente_id from ventas_directas as vd where vd.empresa_id=:idEmpresa and vd.empresa_id !=6 and vd.PKVentaDirecta=:idFactura;");
        }

        $stmt = $conn->prepare($query);
        $stmt->bindValue(":idEmpresa",$idEmpresa);
        $stmt->bindValue(":idFactura",$idFactura);
        $stmt->execute();
    
        $res = $stmt->fetchAll();
        $estatus=$res[0]['estatus'];
        $cliente_id=$res[0]['cliente_id'];
    
        $stmt->closeCursor();
        
        //estructura de variable de sesion "accion - id factura - id cliente".
        //accion 1: redirecciona al index de recepcion de pagos y filtra la factura.
        //accion 2: redirecciona a un nuevo pago con la factura ya cargada en pantalla.
        if($estatus != 4){
            if($estatus==2||$estatus==3){           
                $data['result']=1;
            }else if($estatus==1){
                $data['result']=2;
            }
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
}
?>
<?php
session_start();
require_once('../../../include/db-conn.php');

    $idEmpresa=$_SESSION['IDEmpresa'];
    $idFactura=$_REQUEST['id'];

    try{

        $query = ("SELECT cp.estatus_factura, cp.proveedor_id 
                        from cuentas_por_pagar cp 
                        inner join sucursales s on s.id = cp.sucursal_id
                    where s.empresa_id=:idEmpresa and cp.id=:idFactura and cp.estatus_factura !=7;");
        
        $stmt = $conn->prepare($query);
        $stmt->bindValue(":idEmpresa",$idEmpresa);
        $stmt->bindValue(":idFactura",$idFactura);
        $stmt->execute();
    
        $res = $stmt->fetchAll();
        $estatus=$res[0]['estatus_factura'];
        $proveedor_id=$res[0]['proveedor_id'];
    
        $stmt->closeCursor();
        
        if($estatus != 5){
            $data['idFactura']=$idFactura;
            $data['proveedor_id']=$proveedor_id;
            $data['estatus']="ok"; 
        }else{
            $data['result']="La cuenta está pagada";
            $data['estatus']="pagada";
        }
    
    }catch(Exception $e){
        $data['estatus']="err";
        $data['result']=$e->getMessage();
    }
    
    echo json_encode($data);

?>
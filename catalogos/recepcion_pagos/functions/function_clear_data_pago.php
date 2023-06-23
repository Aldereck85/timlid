<?php
session_start();
$ruta_api = "../../../";
$empresa = $_SESSION["IDEmpresa"];
require_once($ruta_api.'include/db-conn.php');

$folio = $_REQUEST['folio'];

    $stmt=$conn->prepare('SELECT idpagos from pagos where identificador_pago = :folio and tipo_movimiento = 0 and estatus = 1 and empresa_id = :empresa');
    $stmt->bindValue(":folio",$folio);
    $stmt->bindValue(":empresa",$empresa);
    $stmt->execute();
    $numRows = $stmt->rowCount();
    $idPago = $stmt->fetchAll();
    $stmt->closeCursor();
    $data['result'] = 'ok';
    if($numRows == 1){
        try {  
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $conn->beginTransaction();
                $query = sprintf("DELETE from pagos_data_temporal where idpagos = :id");
                $stmt = $conn->prepare($query);
                $stmt->bindValue(":id",$idPago[0]['idpagos']);
                $stmt->execute();
                $stmt->closeCursor();
        
                $query = sprintf("DELETE from detalle_Pago_Temporal where id_pago = :id");
                $stmt = $conn->prepare($query);
                $stmt->bindValue(":id",$idPago[0]['idpagos']);
                $stmt->execute();
                $stmt->closeCursor();
        
                $data['result'] = 'pago data deleted';
        } catch (Exception $e) {
            $conn->rollBack();
            $data['result-Rollback'] = "Fallo: " . $e->getMessage();
        }
    }
    

echo json_encode($data);
?>
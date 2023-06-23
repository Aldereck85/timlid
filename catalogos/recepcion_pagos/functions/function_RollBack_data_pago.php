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
    $idPago = $stmt->fetchAll();
    $stmt->closeCursor();

    try {  
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $conn->beginTransaction();
        $query = sprintf("DELETE from pagos where idpagos = :id");
        $stmt = $conn->prepare($query);
        $stmt->bindValue(":id",$idPago[0]['idpagos']);
        $stmt->execute();
        $stmt->closeCursor();

        $stmt=$conn->prepare('INSERT into pagos (Select * from pagos_data_temporal where idpagos = :idPago and tipo_movimiento = 0 and estatus = 1 and empresa_id = :empresa )');
        $stmt->bindValue(":idPago",$idPago[0]['idpagos']);
        $stmt->bindValue(":empresa",$empresa);
        $stmt->execute();
        $stmt->closeCursor();

        $query = sprintf("DELETE from movimientos_cuentas_bancarias_empresa where id_pago = :id");
        $stmt = $conn->prepare($query);
        $stmt->bindValue(":id",$idPago[0]['idpagos']);
        $stmt->execute();
        $stmt->closeCursor();

        $stmt=$conn->prepare('INSERT into movimientos_cuentas_bancarias_empresa (Select * from detalle_Pago_Temporal where id_pago = :idPago and estatus = 1)');
        $stmt->bindValue(":idPago",$idPago[0]['idpagos']);
        $stmt->execute();
        $conn->commit();
        $stmt->closeCursor();
    $data['result-Rollback'] = 'pago RollBack';
    } catch (Exception $e) {
    $conn->rollBack();
    $data['result-Rollback'] = "Fallo: " . $e->getMessage();
    }
      
    

echo json_encode($data);
?>
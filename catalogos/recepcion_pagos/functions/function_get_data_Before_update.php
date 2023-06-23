<?php
//funcion para recuperar los datos del pago antes de intentar hacer la substitución, ésto por si falla para devolver los datos a su estado anterior
require_once('../../../include/db-conn.php');
session_start();
$empresa = $_SESSION["IDEmpresa"];
$folio = $_REQUEST['folio'];

try {  
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
    $conn->beginTransaction();
        //recupera datos del pago
        $stmt=$conn->prepare('SELECT idpagos from pagos where identificador_pago = :folio and tipo_movimiento = 0 and estatus = 1 and empresa_id = :empresa');
        $stmt->bindValue(":folio",$folio);
        $stmt->bindValue(":empresa",$empresa);
        $stmt->execute();
        $numRows = $stmt->rowCount();
        $idPago = $stmt->fetchAll();

        if($numRows == 1){
            //elimina los datos existentes del pago dentro de la tabla temporal
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

            $stmt=$conn->prepare('INSERT into pagos_data_temporal (Select * from pagos where idpagos = :idPago and tipo_movimiento = 0 and estatus = 1 and empresa_id = :empresa )');
            $stmt->bindValue(":idPago",$idPago[0]['idpagos']);
            $stmt->bindValue(":empresa",$empresa);
            $stmt->execute();

            $stmt=$conn->prepare('INSERT into detalle_Pago_Temporal (Select * from movimientos_cuentas_bancarias_empresa where id_pago = :idPago and estatus = 1)');
            $stmt->bindValue(":idPago",$idPago[0]['idpagos']);
            $stmt->execute();
        }
    $conn->commit();
    $data['status'] = 'ok';
  } catch (Exception $e) {
    $conn->rollBack();
    $data['status'] = 'err';
    $data['result'] = "Fallo: " . $e->getMessage();
  }

echo json_encode($data); 
?>
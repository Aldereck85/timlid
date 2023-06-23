<?php
require_once '../../../include/db-conn.php';

$idCliente = $_POST['idCliente'];

if (isset($_POST['idCliente'])) {
    try {
        $stmt = $conn->prepare("SELECT razon_social
                                  FROM clientes WHERE PKCliente = :idCliente ");
        $stmt->bindValue(":idCliente", $idCliente);
        $stmt->execute();
        $row = $stmt->fetchAll();

        /*if (count($row) > 0) {
            $cadena .= "<option value='0' >Escoge una razón social</option>";
            foreach ($row as $r) {
                $cadena .= "<option value='" . $r['PKDomicilioFiscal'] . "' >" . $r['Razon_Social'] . "</option>";
            }
        } else {
            $cadena .= "<option value='0' >Sin razón social</option>";
        }*/
        if (count($row) > 0) {
            $cadena = $row[0]['razon_social'];
        }
        else{
            $cadena = "";
        }

        echo $cadena;

    } catch (PDOException $ex) {
        echo $ex->getMessage();
    }
}

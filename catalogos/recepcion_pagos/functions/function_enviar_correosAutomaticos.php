<?php
    session_start();
    $ruta_api = "../../../";
    $empresa = $_SESSION["IDEmpresa"];
    require_once($ruta_api.'include/db-conn.php');
    require_once($ruta_api.'include/functions_api_facturation.php');
    require_once $ruta_api . 'vendor/facturapi/facturapi-php/src/Facturapi.php';
    $api = new API();
    $folioPago=$_REQUEST['folio'];

    $query = sprintf("select key_company_api from empresas where PKEmpresa = :id");
    $stmt = $conn->prepare($query);
    $stmt->bindValue(":id", $_SESSION['IDEmpresa']);
    $stmt->execute();
    $emp = $stmt->fetchAll();

    $query = sprintf('SELECT id_api, cliente_id from facturas_pagos where folio_pago = "'.$folioPago.'" and estatus=1 and empresa_id= "'.$_SESSION['IDEmpresa'].'"');
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $fact = $stmt->fetchAll();

    $query = sprintf("SELECT 
                        cl.PKCliente as id,
                        dcc.Email as email,
                        dcc.EmailComplementoPago
                      FROM
                      clientes cl
                      LEFT JOIN dato_contacto_cliente dcc ON dcc.FKCliente = cl.PKCliente
                      WHERE cl.PKCliente=:id
                      AND dcc.EmailComplementoPago=1
                    ");
    $stmt = $conn->prepare($query);
    $stmt->bindValue(":id", $fact[0]['cliente_id']);
    $stmt->execute();
    $row = $stmt->fetchAll();    

    $correosContactos = array();
    foreach($row as $rowCorreos){
        array_push($correosContactos, $rowCorreos['email']);
    }

    if (count($correosContactos) < 2) {
      $mensaje = $api->sendEmailInvoice($emp[0]['key_company_api'], $fact[0]['id_api'], $correosContactos[0]);
    } else {
      $mensaje = $api->sendMoreEmailInvoice($emp[0]['key_company_api'], $fact[0]['id_api'], $correosContactos);
    }

    return $mensaje;
?>
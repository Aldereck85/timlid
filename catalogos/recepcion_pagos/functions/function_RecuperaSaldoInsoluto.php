<?php
    require_once('../../../include/db-conn.php');
    session_start();
    $empresa = $_SESSION["IDEmpresa"];
    $_idPago = $_POST['_idPago'];
    $_id_factura = $_POST['_id_factura'];
    $_importe = $_POST['_importe'];
    $_is_invoice = $_POST['_is_invoice'];

    $query=sprintf("CALL spc_ValidarInsoluto_cpc(?,?,?,?,?);");
    $stmt = $conn->prepare($query);
    $stmt->execute(array($_idPago, $_id_factura, $_SESSION['IDEmpresa'], $_importe, $_is_invoice));

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!empty($row)){
        $userData = $row;
        $data['result'] = $row['cumpleCondicion'];
        $data['limite'] = $row['limite'];

    }else{
        $data['status'] = 'err';
        $data['result'] = '';
        $data['limite'] = '';

    }

    //returns data as JSON format
    echo json_encode($data); 
    

 ?>
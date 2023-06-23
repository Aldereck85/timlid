<?php
require_once('../../../include/db-conn.php');

    //Optener los datos para la pantalla de editar
    $empresa = $_SESSION["IDEmpresa"];
    if(isset($_POST['funcion'],$_POST['user_id']) && !empty($_POST['funcion']) && !empty($_POST['user_id'])) {
        $funcion = $_POST['funcion'];
        $user_id = $_POST['user_id'];
        //En función del parámetro que nos llegue ejecutamos una función u otra
        switch($funcion) {
            case '1': 
                $stmt = $conn->prepare("SELECT id, folio_factura, num_serie_factura, subtotal,iva,ieps, importe, fecha_factura, 
                    fecha_vencimiento,estatus_factura, NombreComercial FROM cuentas_por_pagar as cp LEFT JOIN proveedores 
                    as pr ON cp.proveedor_id = pr.PKProveedor
                    WHERE id='$user_id' and cp.estatus_factura!=7 and pr.empresa_id = $empresa");
                    $stmt->execute();
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    /* Convertir a numero y agregar signo $ */
                    $row['importe'] = number_format($row['importe'],2);
                    /* La linea de abajo quita lo que recien se agrego para que paresca monesa, (los comas y el signo $) */
                    /* $row['importe'] = filter_var($row['importe'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); */
                    $row['subtotal'] = number_format($row['subtotal'],2);
                    $row['iva'] = number_format($row['iva'],2);
                    $row['ieps'] = number_format($row['ieps'],2);

                    if(count($row) > 0){
                        $userData = $row;
                        $data['status'] = 'ok';
                        $data['result'] = $userData;
                    }else{
                        $data['status'] = 'err';
                        $data['result'] = '';
                    }
                    
                    //returns data as JSON format
                    echo json_encode($data);
                /* $a -> accion1(); */
                break;
            case 'funcion2': 
                $b -> accion2();
                break;
        }
    }
 ?>
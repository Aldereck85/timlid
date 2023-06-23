<?php
require_once('../../../include/db-conn.php');

    //Optener los datos para la pantalla de editar
    session_start();
    $empresa = $_SESSION["IDEmpresa"];
    if(isset($_POST['funcion'],$_POST['cuenta_id']) && !empty($_POST['funcion']) && !empty($_POST['cuenta_id'])) {
        $funcion = $_POST['funcion'];
        $cuenta_id = $_POST['cuenta_id'];
        //En función del parámetro que nos llegue ejecutamos una función u otra
        switch($funcion) {
            case '1': 
                $stmt = $conn->prepare("SELECT id, folio_factura, num_serie_factura, subtotal,iva,ieps, importe, fecha_factura, 
                    fecha_vencimiento,estatus_factura, NombreComercial,cat.Nombre categoria,cat.PKCategoria cat_id,subcat.Nombre subcategoria,subcat.PKSubcategoria subcat_id, comentarios FROM cuentas_por_pagar as cp LEFT JOIN proveedores 
                    as pr ON cp.proveedor_id = pr.PKProveedor
                    LEFT JOIN categoria_gastos cat on cp.categoria_id = cat.PKCategoria 
                    LEFT JOIN subcategorias_gastos subcat on cp.subcategoria_id = subcat.PKSubcategoria 
                    WHERE id='$cuenta_id' and pr.empresa_id = $empresa and cp.estatus_factura!=7");
                    $stmt->execute();
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    

                    if(!empty($row)){
                        /* Convertir a numero y agregar signo $ */
                    $row['importe'] = number_format($row['importe'],2);
                    /* La linea de abajo quita lo que recien se agrego para que paresca monesa, (los comas y el signo $) */
                    /* $row['importe'] = filter_var($row['importe'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); */
                    $row['subtotal'] = number_format($row['subtotal'],2);
                    $row['iva'] = number_format($row['iva'],2);
                    $row['ieps'] = number_format($row['ieps'],2);
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
            case '2': 
                $cuenta_id = $_POST['cuenta_id'];
                $stmt = $conn->prepare("SELECT cpp.folio_factura,cpp.num_serie_factura,cpp.subtotal,cpp.iva,cpp.ieps,cpp.descuento,cpp.importe,cpp.fecha_factura,cpp.estatus_factura,cpp.tipo_documento,pv.NombreComercial,cpp.proveedor_id, sc.sucursal,sc.id as id_sucursal,cat.Nombre categoria,cat.PKCategoria cat_id,subcat.Nombre subcategoria, subcat.PKSubcategoria subcat_id, comentarios
                from cuentas_por_pagar as cpp 
                inner join proveedores as pv on pv.PKProveedor = cpp.proveedor_id 
                inner join sucursales as sc on sc.id = sucursal_id 
                LEFT JOIN categoria_gastos cat on cp.categoria_id = cat.PKCategoria 
                LEFT JOIN subcategorias_gastos subcat on cp.subcategoria_id = subcat.PKSubcategoria 
                where cpp.id = $cuenta_id");
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if(!empty($row)){
                    //Comvierte el timestamp a yyddmm
                    $timestamp = strtotime($row['fecha_factura']);
                    $row['fecha_factura'] = date("Y-m-d", $timestamp);
                    /* Convertir a numero y agregar signo $ */
                //$row['importe'] = number_format($row['importe'],2,'.', ' ');
                /* La linea de abajo quita lo que recien se agrego para que paresca monesa, (los comas y el signo $) */
                /* $row['importe'] = filter_var($row['importe'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); */
                //$row['subtotal'] = number_format($row['subtotal'],2,'.', ' ');
                //$row['iva'] = number_format($row['iva'],2,'.', ' ');
                //$row['ieps'] = number_format($row['ieps'],2,'.', ' ');
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
        }
    }
 ?>
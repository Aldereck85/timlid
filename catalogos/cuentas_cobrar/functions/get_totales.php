<?php
    session_start();
    require_once('../../../include/db-conn.php');
    $sql = "";
    $sql1 = "";
    $filter = isset($_POST['selection']) ? (int)$_POST['selection'] : null;
    $initialDate = $_POST['fecha_desde'];
    $finalDate = $_POST['fecha_hasta'];
    $date_now = date('Y-m-d');
    if($initialDate !== null && $initialDate !== ''){
        if($finalDate !== null && $finalDate !== ''){
            $sql .= " and f.fecha_timbrado between '" . $initialDate . "' and '" . $finalDate . "'";
            $sql1 .= " and vd.created_at between '" . $initialDate . "' and '" . $finalDate . "'";
        } else {
            $sql .= " and created_at between '" . $initialDate . "' and " . $date_now  . "'";
            $sql1 .= " and Fecha between '" . $initialDate . "' and " . $date_now  . "'";
        }
    } else {
        if($finalDate !== null && $finalDate !== ''){
            $sql .= " and f.fecha_timbrado between '1985-01-11' and '" . $finalDate  . "'";
            $sql1 .= " and vd.created_at between '1985-01-11' and '" . $finalDate  . "'";
        } else {
            $sql .= " and f.fecha_timbrado between '1985-01-11' and '" . $date_now  . "'";
            $sql1 .= " and vd.created_at between '1985-01-11' and '" . $date_now  . "'";
        }
    }
    switch ($filter) {
        case 1:
            
            $query = sprintf("SELECT * from (
                                    select sum(f.saldo_insoluto) total_facturado, 1 as tipo 
                                    from facturacion f 
                                        inner join clientes as c on f.cliente_id = c.PKCliente  
                                    where (DATEDIFF(if(f.fecha_vencimiento is not null,f.fecha_vencimiento, date_add(fecha_timbrado, interval c.Dias_credito day)), SYSDATE())>=0) 
                                            and f.empresa_id=:idEmpresa 
                                            and f.estatus in (1,2)
                                            and f.prefactura = 0
                                
                                    union

                                    select sum(vd.saldo_insoluto_venta) total_facturado, 2 as tipo 
                                    from ventas_directas vd 
                                        inner join clientes as c on vd.FKCliente = c.PKCliente 
                                    where (DATEDIFF(if(vd.FechaVencimiento is not null, vd.FechaVencimiento, date_add(vd.created_at, interval c.Dias_credito day)), SYSDATE())>=0) 
                                            and vd.empresa_id=:idEmpresa1 
                                            and vd.empresa_id !=6 
                                            and vd.estatus_factura_id not in (1,2) 
                                            and vd.estatus_cuentaCobrar in (1,2)
                                ) totales");
            $stmt = $conn->prepare($query);
            $stmt->bindValue(":idEmpresa",$_SESSION['IDEmpresa']);
            $stmt->bindValue(":idEmpresa1",$_SESSION['IDEmpresa']);
            $stmt->execute();
            $totales = $stmt->fetchAll(PDO::FETCH_OBJ);
        break;
        case 2:
            
            $query = sprintf("SELECT * from (
                    select sum(f.saldo_insoluto) total_facturado, 1 as tipo 
                    from facturacion f 
                        inner join clientes as c on f.cliente_id = c.PKCliente 
                    where (DATEDIFF(SYSDATE(), if(f.fecha_vencimiento is not null,f.fecha_vencimiento, date_add(fecha_timbrado, interval c.Dias_credito day)))>0) 
                            and f.empresa_id=:idEmpresa 
                            and f.estatus  in (1,2) 
                            and f.prefactura = 0
                
                    union

                    select SUM(vd.saldo_insoluto_venta) total_facturado, 2 as tipo 
                    from ventas_directas vd 
                        inner join clientes as c on vd.FKCliente = c.PKCliente 
                    where (DATEDIFF(SYSDATE(), if(vd.FechaVencimiento is not null,vd.FechaVencimiento, date_add(vd.created_at, interval c.Dias_credito day)))>0) 
                            and vd.empresa_id=:idEmpresa1 
                            and vd.empresa_id !=6 
                            and vd.estatus_factura_id not in (1,2)
                            and vd.estatus_cuentaCobrar in (1,2)
                ) totales");
            $stmt = $conn->prepare($query);
            $stmt->bindValue(":idEmpresa",$_SESSION['IDEmpresa']);
            $stmt->bindValue(":idEmpresa1",$_SESSION['IDEmpresa']);
            $stmt->execute();
            $totales = $stmt->fetchAll(PDO::FETCH_OBJ);
        break;
        case 3:
            $query = sprintf("SELECT * from (
                    select sum(f.saldo_insoluto) total_facturado, 1 as tipo 
                    from facturacion f 
                    where f.empresa_id = :idEmpresa AND (f.estatus = 1 OR f.estatus = 2) $sql
                
                    union

                    select sum(vd.saldo_insoluto_venta) total_facturado, 2 as tipo
                    from ventas_directas vd 
                    where vd.empresa_id = :idEmpresa1 and (vd.estatus_factura_id = 3 OR vd.estatus_factura_id = 4 OR vd.estatus_factura_id = 5) $sql1
                ) totales
            ");
            $stmt = $conn->prepare($query);
            $stmt->bindValue(":idEmpresa",$_SESSION['IDEmpresa']);
            $stmt->bindValue(":idEmpresa1",$_SESSION['IDEmpresa']);
            $stmt->execute();
            $totales = $stmt->fetchAll(PDO::FETCH_OBJ);
        break;
        default:
            $query = sprintf("SELECT * from (
                    select sum(f.saldo_insoluto) total_facturado, 1 as tipo  
                    from facturacion f 
                        inner join clientes as c on f.cliente_id = c.PKCliente 
                    where f.empresa_id = :idEmpresa AND (f.estatus = 1 OR f.estatus = 2)
                
                    union

                    select sum(vd.saldo_insoluto_venta) total_facturado, 2 as tipo 
                    from ventas_directas vd 
                        inner join clientes as c on vd.FKCliente = c.PKCliente 
                    where vd.empresa_id = :idEmpresa1 
                            and (vd.estatus_factura_id = 3 OR vd.estatus_factura_id = 4 OR vd.estatus_factura_id = 5)
                            and vd.estatus_cuentaCobrar in (1,2)

                ) totales
            ");
            $stmt = $conn->prepare($query);
            $stmt->bindValue(":idEmpresa",$_SESSION['IDEmpresa']);
            $stmt->bindValue(":idEmpresa1",$_SESSION['IDEmpresa']);
            $stmt->execute();
            $totales = $stmt->fetchAll(PDO::FETCH_OBJ);
        break;
    }

    if($stmt->rowCount() < 2){
        if($stmt->rowCount() == 1){
            if($totales[0]->tipo == 1){
                $aux1 = $totales[0]->total_facturado;
            }else{
                $aux2 = $totales[0]->total_facturado;
            }
        }else{
            $aux1 = 0;
            $aux2 = 0;
        }
    }else{
        $aux1 = $totales[0]->total_facturado;
        $aux2 = $totales[1]->total_facturado;
    }
    
    $aux = 
    [
        'total_facturado'=>'$'.number_format($aux1,2),
        'total_noFacturado'=>'$'.number_format($aux2,2)
    ];
    
echo json_encode($aux);
<?php

    class conection
    { //Llamado al archivo de la conexión.
    
      static public function getDb($path)
      {
          include $path."include/db-conn.php";
          return $conn;
      }
      
    }

    class get_data
    {
        static function getDataIncomeExpenses()
        {
            $db = conection::getDb('../../');

            $query = sprintf("select sum(p.total) total_ventas from 
                                pagos p inner join movimientos_cuentas_bancarias_empresa m on p.idpagos=m.id_pago
                            where p.tipo_movimiento=0 and p.empresa_id=:empresa_id and p.estatus=1");
            $stmt = $db->prepare($query);
            $stmt->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
            $stmt->execute();
            $ventas = $stmt->fetchAll(PDO::FETCH_OBJ);

            $query = sprintf("select sum(mcbe.Retiro) total_gastos from movimientos_cuentas_bancarias_empresa mcbe
                inner join cuentas_bancarias_empresa cbe on mcbe.cuenta_origen_id = cbe.PKCuenta
            where cbe.empresa_id = :empresa_id and mcbe.id_pago is not null");
            $stmt = $db->prepare($query);
            $stmt->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
            $stmt->execute();
            $gastos = $stmt->fetchAll(PDO::FETCH_OBJ);

            $total_general = (double)$ventas[0]->total_ventas - (double)$gastos[0]->total_gastos;

            return ["total_ventas"=>"$".number_format($ventas[0]->total_ventas,2),"total_gastos"=>"$".number_format($gastos[0]->total_gastos,2),"total_general"=>"$".number_format($total_general,2)];
        }

        static function getDataWorkingCapital()
        {
            $db = conection::getDb('../../');
            # total intventario
            $query = sprintf("select 
                sum(
                    if(
                        epp.existencia > 0, (epp.existencia * cvp.CostoCompra),0)
                ) total_inventario
                from existencia_por_productos epp
                inner join productos p on epp.producto_id = p.PKProducto
                inner join costo_venta_producto cvp on p.PKProducto = cvp.FKProducto
            where p.empresa_id = :empresa_id and p.estatus = 1");
            $stmt = $db->prepare($query);
            $stmt->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
            $stmt->execute();
            $inventario = $stmt->fetchAll(PDO::FETCH_OBJ);
            # total cuentas por cobrar
            $query = sprintf("select sum(total_cobrar) total_cobrar from(
                select sum(f.saldo_insoluto) total_cobrar from facturacion as f
                where f.empresa_id=:empresa_id and f.prefactura = 0 and f.estatus not in (4)
                
                union
                
                select sum( vd.saldo_insoluto_venta) total_cobrar from ventas_directas vd
                where vd.empresa_id=:empresa_id1 and vd.estatus_cuentaCobrar NOT IN (4) and vd.estatus_factura_id not in (1,2)
                ) total_cobrar
            ;");
            $stmt = $db->prepare($query);
            $stmt->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
            $stmt->bindValue(":empresa_id1",$_SESSION['IDEmpresa']);
            $stmt->execute();
            $cobrar = $stmt->fetchAll(PDO::FETCH_OBJ);
            # total cuentas bancarias
            $query = sprintf("select sum(total_cuentas) total_cuentas from (
                                select sum(cch.Saldo_Inicial) total_cuentas from cuentas_bancarias_empresa cbe
                                inner join cuentas_cheques cch on cbe.PKCuenta = cch.FKCuenta
                                where cbe.empresa_id = :empresa_id
                                
                                union
                            
                                select sum(cch.SaldoInicialCaja) total_cuentas from cuentas_bancarias_empresa cbe
                                inner join cuenta_caja_chica cch on cbe.PKCuenta = cch.FKCuenta
                                where cbe.empresa_id = :empresa_id1
                            
                                union 
                                
                                select sum(cch.Saldo_Inicial) total_cuentas from cuentas_bancarias_empresa cbe
                                inner join cuentas_otras cch on cbe.PKCuenta = cch.FKCuenta
                                where cbe.empresa_id = :empresa_id2
                            ) totales_cuentas");
            $stmt = $db->prepare($query);
            $stmt->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
            $stmt->bindValue(":empresa_id1",$_SESSION['IDEmpresa']);
            $stmt->bindValue(":empresa_id2",$_SESSION['IDEmpresa']);
            $stmt->execute();
            $cuentas = $stmt->fetchAll(PDO::FETCH_OBJ);
            # total cuentas por pagar
            $query = sprintf("select sum(if(cp.estatus_factura <> 5,cp.saldo_insoluto,0)) total_pagado
                    from cuentas_por_pagar as cp 
                        inner join proveedores as pr on pr.PKProveedor = cp.proveedor_id
                    where pr.empresa_id = :empresa_id
                    order by fecha_captura desc");
            $stmt = $db->prepare($query);
            $stmt->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
            $stmt->execute();
            $pagar=$stmt->fetchAll(PDO::FETCH_OBJ);
            # total cuenta Credito
            $query = sprintf("select sum(cch.Limite_Credito - cch.Credito_Utilizado) total_cuentasCredito from cuentas_bancarias_empresa cbe
                                inner join cuentas_credito cch on cbe.PKCuenta = cch.FKCuenta
                                where cbe.empresa_id = :empresa_id");
            $stmt = $db->prepare($query);
            $stmt->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
            $stmt->execute();
            $cuentasCredito = $stmt->fetchAll(PDO::FETCH_OBJ);

            $total_general = 
            ($inventario[0]->total_inventario +
            $cobrar[0]->total_cobrar +
            $cuentas[0]->total_cuentas) -
            $pagar[0]->total_pagado -
            $cuentasCredito[0]->total_cuentasCredito;

            return ["inventario"=>"$".number_format($inventario[0]->total_inventario,2),"cuentas_cobrar"=>"$".number_format($cobrar[0]->total_cobrar,2),"cuentasBancarias"=>"$".number_format($cuentas[0]->total_cuentas,2),"cuentas_pagar"=>"$".number_format($pagar[0]->total_pagado,2),"cuentasCredito"=>"$".number_format($cuentasCredito[0]->total_cuentasCredito,2),"total_general"=>"$".number_format($total_general,2)];
        }

        static function getDataIncomeExpensesFilter($initialDate,$finalDate)
        {
            session_start();
            $db = conection::getDb('../../../');

            $sql = "";
            $sql1 = "";
            $date_now = date('Y-m-d');
            if($initialDate !== null && $initialDate !== ''){
                if($finalDate !== null && $finalDate !== ''){
                    $sql .= " and created_at between '" . $initialDate . "' and '" . $finalDate . "'";
                    $sql1 .= " and Fecha between '" . $initialDate . "' and '" . $finalDate . "'";
                } else {
                    $sql .= " and created_at between '" . $initialDate . "' and '" . $date_now  . "'";
                    $sql1 .= " and Fecha between '" . $initialDate . "' and '" . $date_now  . "'";
                }
            } else {
                if($finalDate !== null && $finalDate !== ''){
                    $sql .= " and created_at between '1985-01-11' and '" . $finalDate  . "'";
                    $sql1 .= " and Fecha between '1985-01-11' and '" . $finalDate  . "'";
                } else {
                    $sql .= " and created_at between '1985-01-11' and '" . $date_now  . "'";
                    $sql1 .= " and Fecha between '1985-01-11' and '" . $date_now  . "'";
                }
            }

            $query = sprintf("select sum(p.total) total_ventas from 
                                pagos p inner join movimientos_cuentas_bancarias_empresa m on p.idpagos=m.id_pago
                            where p.tipo_movimiento=0 and p.empresa_id=:empresa_id and p.estatus=1 $sql1");
            $stmt = $db->prepare($query);
            $stmt->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
            $stmt->execute();
            $ventas = $stmt->fetchAll(PDO::FETCH_OBJ);

            $query = sprintf("select sum(mcbe.Retiro) total_gastos from movimientos_cuentas_bancarias_empresa mcbe
                inner join cuentas_bancarias_empresa cbe on mcbe.cuenta_origen_id = cbe.PKCuenta
            where cbe.empresa_id = :empresa_id and mcbe.id_pago is not null $sql1");
            $stmt = $db->prepare($query);
            $stmt->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
            $stmt->execute();
            $gastos = $stmt->fetchAll(PDO::FETCH_OBJ);

            $total_general = (double)$ventas[0]->total_ventas - (double)$gastos[0]->total_gastos;

            return ["total_ventas"=>"$".number_format($ventas[0]->total_ventas,2),"total_gastos"=>"$".number_format($gastos[0]->total_gastos,2),"total_general"=>"$".number_format($total_general,2)];
        }

        static function getDataWorkingCapitalFilter($initialDate,$finalDate)
        {
            $db = conection::getDb('../../../');

            $sql = "";
            $sql1 = "";
            $date_now = date('Y-m-d');
            if($initialDate !== null && $initialDate !== ''){
                if($finalDate !== null && $finalDate !== ''){
                    $sql .= " and created_at between '" . $initialDate . "' and '" . $finalDate . "'";
                    $sql1 .= " and Fecha between '" . $initialDate . "' and '" . $finalDate . "'";
                } else {
                    $sql .= " and created_at between '" . $initialDate . "' and " . $date_now  . "'";
                    $sql1 .= " and Fecha between '" . $initialDate . "' and " . $date_now  . "'";
                }
            } else {
                if($finalDate !== null && $finalDate !== ''){
                    $sql .= " and created_at between '1985-01-11' and '" . $finalDate  . "'";
                    $sql1 .= " and Fecha between '1985-01-11' and '" . $finalDate  . "'";
                } else {
                    $sql .= " and created_at between '1985-01-11' and '" . $date_now  . "'";
                    $sql1 .= " and Fecha between '1985-01-11' and '" . $date_now  . "'";
                }
            }
            # total intventario
            $query = sprintf("select 
                sum(
                    if(
                        epp.existencia > 0, (epp.existencia * cvp.CostoCompra),0)
                ) total_inventario
                from existencia_por_productos epp
                inner join productos p on epp.producto_id = p.PKProducto
                inner join costo_venta_producto cvp on p.PKProducto = cvp.FKProducto
            where p.empresa_id = :empresa_id and p.estatus = 1");
            $stmt = $db->prepare($query);
            $stmt->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
            $stmt->execute();
            $inventario = $stmt->fetchAll(PDO::FETCH_OBJ);
            # total cuentas por cobrar
            $query = sprintf("select sum(total_cobrar) total_cobrar from(
                select sum(f.saldo_insoluto) total_cobrar from facturacion as f
                where f.empresa_id=:empresa_id and f.prefactura = 0 and f.estatus not in (4)
                
                union
                
                select sum( vd.saldo_insoluto_venta) total_cobrar from ventas_directas vd
                where vd.empresa_id=:empresa_id1 and vd.estatus_cuentaCobrar NOT IN (4) and vd.estatus_factura_id not in (1,2)
                ) total_cobrar
            ;");
            $stmt = $db->prepare($query);
            $stmt->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
            $stmt->bindValue(":empresa_id1",$_SESSION['IDEmpresa']);
            $stmt->execute();
            $cobrar = $stmt->fetchAll(PDO::FETCH_OBJ);
            # total cuentas bancarias
            $query = sprintf("select sum(mcbe.Saldo) total_cuentas from movimientos_cuentas_bancarias_empresa mcbe
                inner join cuentas_bancarias_empresa cbe on mcbe.cuenta_origen_id = cbe.PKCuenta
                where cbe.empresa_id = :empresa_id and mcbe.estatus = 1 and cbe.tipo_cuenta NOT IN (2)");
            $stmt = $db->prepare($query);
            $stmt->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
            $stmt->execute();
            $cuentas = $stmt->fetchAll(PDO::FETCH_OBJ);
            # total cuentas por pagar
            $query = sprintf("select sum(if(cp.estatus_factura <> 5,cp.saldo_insoluto,0)) total_pagado
                    from cuentas_por_pagar as cp 
                        inner join proveedores as pr on pr.PKProveedor = cp.proveedor_id 
                        
                    where pr.empresa_id = :empresa_id
                    order by fecha_captura desc");
            $stmt = $db->prepare($query);
            $stmt->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
            $stmt->execute();
            $pagar=$stmt->fetchAll(PDO::FETCH_OBJ);
            # total cuenta Credito
            $query = sprintf("select sum(mcbe.Saldo) total_cuentasCredito from movimientos_cuentas_bancarias_empresa mcbe
                inner join cuentas_bancarias_empresa cbe on mcbe.cuenta_origen_id = cbe.PKCuenta
                where cbe.empresa_id = :empresa_id and mcbe.estatus = 1 and cbe.tipo_cuenta = 2");
            $stmt = $db->prepare($query);
            $stmt->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
            $stmt->execute();
            $cuentasCredito = $stmt->fetchAll(PDO::FETCH_OBJ);

            $total_general = 
            $inventario[0]->total_inventario +
            $cobrar[0]->total_cobrar +
            $cuentas[0]->total_cuentas -
            $pagar[0]->total_pagado -
            $cuentasCredito[0]->total_cuentasCredito;

            return ["inventario"=>"$".number_format($inventario[0]->total_inventario,2),"cuentas_cobrar"=>"$".number_format($cobrar[0]->total_cobrar,2),"cuentasBancarias"=>"$".number_format($cuentas[0]->total_cuentas,2),"cuentas_pagar"=>"$".number_format($pagar[0]->total_pagado,2),"cuentasCredito"=>"$".number_format($cuentasCredito[0]->total_cuentasCredito,2),"total_general_capital"=>"$".number_format($total_general,2)];
        }

        static function getGeneralFilterData($initialDate,$finalDate){
            $ingresos_egresos = get_data::getDataIncomeExpensesFilter($initialDate,$finalDate);
            $capital_trabajo = get_data::getDataWorkingCapitalFilter($initialDate,$finalDate);
            return array_merge($ingresos_egresos,$capital_trabajo);
        }

        static function getGenerateExpenseReport($year, $months, $initialDate, $finalDate){
            session_start();
            $db = conection::getDb('../../../');
            require_once('../../../vendor/shuchkin/simplexlsxgen/src/SimpleXLSXGen.php');
            date_default_timezone_set('America/Mexico_City');
            $months = explode(',', $months);

            $yearComplete = $year == '' ? date('Y') : $year;

            if($initialDate != '' || $finalDate != ''){
                //define rango de fechas
                $from = $initialDate == '' ? '2020-01-01' : $initialDate; 
                $to = $finalDate == '' ? date('Y-m-d') : $finalDate; 
                $nameDoc = "Rango";

                $stmt = $db->prepare("call spc_Info_ReporteGastos(:date_now, :date_now1, :empresa_id)");
                $stmt->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
                $stmt->bindValue(":date_now",$from);
                $stmt->bindValue(":date_now1",$to);
                $stmt->execute();

                for ($i = 0; $i < $stmt->columnCount(); $i++) {
                    $meta = $stmt->getColumnMeta($i);
                    $headers[] = "<style bgcolor=\"#c0c0c0\"><b>".$meta['name']."</b></style>";
                }

            }elseif(in_array("0", $months) || empty($months) || $year != 0){
                $query = "SELECT
                                if(subcat.PKSubcategoria = 1, cat.Nombre, subcat.nombre) as Categoria,
                                SUM(CASE WHEN MONTH(m.fecha)=1 THEN m.Retiro ELSE 0 END) AS Enero,
                                SUM(CASE WHEN MONTH(m.fecha)=2 THEN m.Retiro ELSE 0 END) AS Febrero,
                                SUM(CASE WHEN MONTH(m.fecha)=3 THEN m.Retiro ELSE 0 END) AS Marzo,
                                SUM(CASE WHEN MONTH(m.fecha)=4 THEN m.Retiro ELSE 0 END) AS Abril,
                                SUM(CASE WHEN MONTH(m.fecha)=5 THEN m.Retiro ELSE 0 END) AS Mayo,
                                SUM(CASE WHEN MONTH(m.fecha)=6 THEN m.Retiro ELSE 0 END) AS Junio,
                                SUM(CASE WHEN MONTH(m.fecha)=7 THEN m.Retiro ELSE 0 END) AS Julio,
                                SUM(CASE WHEN MONTH(m.fecha)=8 THEN m.Retiro ELSE 0 END) AS Agosto,
                                SUM(CASE WHEN MONTH(m.fecha)=9 THEN m.Retiro ELSE 0 END) AS Septiembre,
                                SUM(CASE WHEN MONTH(m.fecha)=10 THEN m.Retiro ELSE 0 END) AS Octubre,
                                SUM(CASE WHEN MONTH(m.fecha)=11 THEN m.Retiro ELSE 0 END) AS Noviembre,
                                SUM(CASE WHEN MONTH(m.fecha)=12 THEN m.Retiro ELSE 0 END) AS Diciembre,
                                SUM(CASE WHEN year(m.fecha)= :date_now THEN m.Retiro ELSE 0 END) AS Total
                            from movimientos_cuentas_bancarias_empresa m
                                inner join cuentas_bancarias_empresa c on m.cuenta_origen_id = c.PKCuenta
                                inner join subcategorias_gastos subcat on m.FKSubcategoria = subcat.PKSubcategoria
                                inner join categoria_gastos cat on subcat.FKCategoria = cat.PKCategoria
                                left join pagos p on m.id_pago = p.idpagos
                            where 
                                c.empresa_id = :empresa_id and 
                                c.tipo_cuenta != 2 and 
                                (m.tipo_movimiento_id=2 or (m.tipo_movimiento_id=5 and p.tipo_movimiento = 1)) and 
                                m.estatus=1 and
                                year(fecha) = :date_now1
                            group by 
                                cat.PKCategoria, subcat.PKSubcategoria
                            order by cat.PKCategoria, categoria asc; 
                            ";
                $stmt = $db->prepare($query);
                $stmt->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
                $stmt->bindValue(":date_now",$yearComplete);
                $stmt->bindValue(":date_now1",$yearComplete);
                $stmt->execute();

                $nameDoc = $yearComplete;

                $headers = array("<style bgcolor=\"#c0c0c0\"><b>Categoria</b></style>", "<style bgcolor=\"#c0c0c0\"><b>Enero</b></style>","<style bgcolor=\"#c0c0c0\"><b>Febrero</b></style>", "<style bgcolor=\"#c0c0c0\"><b>Marzo</b></style>", "<style bgcolor=\"#c0c0c0\"><b>Abril</b></style>", "<style bgcolor=\"#c0c0c0\"><b>Mayo</b></style>", "<style bgcolor=\"#c0c0c0\"><b>Junio</b></style>", "<style bgcolor=\"#c0c0c0\"><b>Julio</b></style>", "<style bgcolor=\"#c0c0c0\"><b>Agosto</b></style>", "<style bgcolor=\"#c0c0c0\"><b>Septiembre</b></style>", "<style bgcolor=\"#c0c0c0\"><b>Octubre</b></style>", "<style bgcolor=\"#c0c0c0\"><b>Noviembre</b></style>", "<style bgcolor=\"#c0c0c0\"><b>Diciembre</b></style>", "<style bgcolor=\"#c0c0c0\"><b>Total</b></style>");
            }else{

                $nameDoc = "por_meses";
                $nameMonths = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
                
                $headers[] = "<style bgcolor=\"#c0c0c0\"><b>Categoria</b></style>";

                $monthsQuery='';
                $inMonths='';
                foreach($months as $month){
                    $monthsQuery.='SUM(CASE WHEN MONTH(m.fecha)='.$month.' THEN m.Retiro ELSE 0 END) AS '.$nameMonths[$month-1].', ';
                    $headers[] = "<style bgcolor=\"#c0c0c0\"><b>".$nameMonths[$month-1]."</b></style>";
                    $inMonths.=$month.',';
                }
                $inMonths = substr($inMonths, 0, strlen($inMonths) - 1);
                $query = "SELECT
                                if(subcat.PKSubcategoria = 1, cat.Nombre, subcat.nombre) as Categoria,".
                                $monthsQuery
                                ."SUM(CASE WHEN MONTH(m.fecha) in (".$inMonths.") THEN m.Retiro ELSE 0 END) AS Total
                            from movimientos_cuentas_bancarias_empresa m
                                inner join cuentas_bancarias_empresa c on m.cuenta_origen_id = c.PKCuenta
                                inner join subcategorias_gastos subcat on m.FKSubcategoria = subcat.PKSubcategoria
                                inner join categoria_gastos cat on subcat.FKCategoria = cat.PKCategoria
                                left join pagos p on m.id_pago = p.idpagos
                            where 
                                c.empresa_id = :empresa_id and 
                                c.tipo_cuenta != 2 and 
                                (m.tipo_movimiento_id=2 or (m.tipo_movimiento_id=5 and p.tipo_movimiento = 1)) and 
                                m.estatus=1 and
                                year(fecha) = :date_now
                            group by 
                                cat.PKCategoria, subcat.PKSubcategoria
                            order by cat.PKCategoria, categoria asc; 
                            ";
                $stmt = $db->prepare($query);
                $stmt->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
                $stmt->bindValue(":date_now",$yearComplete);
                $stmt->execute();
                $headers[] = "<style bgcolor=\"#c0c0c0\"><b>Total</b></style>";
            }

            
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);

            //Arreglo de las cabeceras del excel
            $book[] = $headers;
            foreach($data as $row){
            $book[] =  $row;
            }
            //Mostrar las cabeceras siempre
            $xlsx = Shuchkin\SimpleXLSXGen::fromArray( $book, 'Reporte gastos '. $nameDoc);
            $xlsx->downloadAs('Reporte_gastos_'.$nameDoc.'.xlsx');
        }

        static function getChart()
        {
            session_start();
            $db = conection::getDb('../../../');

            $query = sprintf("
            select * from (	
                select 
                    sum(total) total,
                    if(month(fecha_registro)=1,'Enero',
                        if(month(fecha_registro)=2,'Febrero',
                            if(month(fecha_registro)=3,'Marzo',
                                if(month(fecha_registro)=4,'Abril',
                                    if(month(fecha_registro)=5,'Mayo',
                                        if(month(fecha_registro)=6,'Junio',
                                            if(month(fecha_registro)=7,'Julio',
                                                if(month(fecha_registro)=8,'Agosto',
                                                    if(month(fecha_registro)=9,'Septiembre',
                                                        if(month(fecha_registro)=10,'Octubre',
                                                            if(month(fecha_registro)=11,'Noviembre',
                                                                if(month(fecha_registro)=12,'Diciembre',null)
                                                            )
                                                        )
                                                    )
                                                )
                                            )
                                        )
                                    )
                                )	
                            )
                        )
                    ) mes
                from pagos p
                inner join movimientos_cuentas_bancarias_empresa m on p.idpagos = m.id_pago
                where 
                    p.empresa_id = :empresa_id and
                    p.tipo_movimiento = 0 and
                    year(p.fecha_registro) = :date_now and
                    p.estatus = 1
                GROUP BY 
                    month(fecha_registro)
            
                UNION
            
                select 
                    (sum(mcbe.Retiro)*(-1)) total,
                    if(month(Fecha)=1,'Enero',
                            if(month(Fecha)=2,'Febrero',
                                if(month(Fecha)=3,'Marzo',
                                    if(month(Fecha)=4,'Abril',
                                        if(month(Fecha)=5,'Mayo',
                                            if(month(Fecha)=6,'Junio',
                                                if(month(Fecha)=7,'Julio',
                                                    if(month(Fecha)=8,'Agosto',
                                                        if(month(Fecha)=9,'Septiembre',
                                                            if(month(Fecha)=10,'Octubre',
                                                                if(month(Fecha)=11,'Noviembre',
                                                                    if(month(Fecha)=12,'Diciembre',null)
                                                                )
                                                            )
                                                        )
                                                    )
                                                )
                                            )
                                        )
                                    )	
                                )
                            )
                        ) mes
                from movimientos_cuentas_bancarias_empresa mcbe
                inner join cuentas_bancarias_empresa cbe on mcbe.cuenta_origen_id = cbe.PKCuenta
                where 
                    cbe.empresa_id = :empresa_id1 and 
                    mcbe.estatus = 1 and 
                    year(Fecha) = :date_now1
                GROUP BY 
                    month(Fecha)
            ) ventas_gastos");
            $stmt = $db->prepare($query);
            $stmt->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
            $stmt->bindValue(":empresa_id1",$_SESSION['IDEmpresa']);
            $stmt->bindValue(":date_now",date('Y'));
            $stmt->bindValue(":date_now1",date('Y'));
            $stmt->execute();
            $ventas_gastos = $stmt->fetchAll(PDO::FETCH_OBJ);

            $labels = [];
            $dataset = [];
            $data_venta = [];
            $data_gasto = [];
            $textdata_venta = "";
            //$data_gasto = "";
            foreach($ventas_gastos as $r){
                array_push($labels,$r->mes);
                
            }
            $labels = array_values(array_unique($labels));
            
            $texLabels = "";
            for($i = 0; $i<count($labels);$i++)
            {
                $texLabels .= "$labels[$i],";
            }
            $texLabels = substr($texLabels, 0, strlen($texLabels) - 1);
            

            foreach ($ventas_gastos as $r) {
                $color = get_data::rand_color();
                if((double)$r->total > 0){
                    if(in_array('Ingresos',array_column($dataset,'label'))){
                        $dataset[array_search('Ingresos', array_column($dataset, 'label'))]['data'] += [$r->mes => $r->total];
                    } else {
                        $dataset[] = 
                        [
                            'label' =>'Ingresos',
                            'data'=>[
                                $r->mes =>$r->total,
                            ],
                            'borderColor' => $color,
                            'backgroundColor' => $color
                        ];
                    }
                }else{
                    if(in_array('Gastos',array_column($dataset, 'label'))){
                        
                        $dataset[array_search('Gastos', array_column($dataset, 'label'))]['data'] += [$r->mes => $r->total];
                    } else {
                        $dataset[] = 
                        [
                            'label'=>'Gastos',
                            'data'=>[
                                $r->mes => $r->total,
                            ],
                            'borderColor' => $color,
                            'backgroundColor' => $color
                        ];
                    }
                    
                }
            }
            return ["labels"=>$labels,"dataset"=>$dataset];
        }
        static function rand_color()
        {
            return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
        }

        static function getChartFilter($initialDate,$finalDate)
        {
            session_start();
            $db = conection::getDb('../../../');
            $sql = "";
            $sql1 = "";
            $date_now = date('Y-m-d');
            if($initialDate !== null && $initialDate !== ''){
                if($finalDate !== null && $finalDate !== ''){
                    $sql .= " and fecha_registro between '" . $initialDate . "' and '" . $finalDate . "'";
                    $sql1 .= " and Fecha between '" . $initialDate . "' and '" . $finalDate . "'";
                } else {
                    $sql .= " and fecha_registro between '" . $initialDate . "' and '" . $date_now  . "'";
                    $sql1 .= " and Fecha between '" . $initialDate . "' and '" . $date_now  . "'";
                }
            } else {
                if($finalDate !== null && $finalDate !== ''){
                    $sql .= " and fecha_registro between '1985-01-11' and '" . $finalDate  . "'";
                    $sql1 .= " and Fecha between '1985-01-11' and '" . $finalDate  . "'";
                } else {
                    $sql .= " and fecha_registro between '1985-01-11' and '" . $date_now  . "'";
                    $sql1 .= " and Fecha between '1985-01-11' and '" . $date_now  . "'";
                }
            }
            $query = sprintf("
            select * from (	
                select 
                    sum(total) total,
                    if(month(fecha_registro)=1,'Enero',
                        if(month(fecha_registro)=2,'Febrero',
                            if(month(fecha_registro)=3,'Marzo',
                                if(month(fecha_registro)=4,'Abril',
                                    if(month(fecha_registro)=5,'Mayo',
                                        if(month(fecha_registro)=6,'Junio',
                                            if(month(fecha_registro)=7,'Julio',
                                                if(month(fecha_registro)=8,'Agosto',
                                                    if(month(fecha_registro)=9,'Septiembre',
                                                        if(month(fecha_registro)=10,'Octubre',
                                                            if(month(fecha_registro)=11,'Noviembre',
                                                                if(month(fecha_registro)=12,'Diciembre',null)
                                                            )
                                                        )
                                                    )
                                                )
                                            )
                                        )
                                    )
                                )	
                            )
                        )
                    ) mes
                from pagos p
                inner join movimientos_cuentas_bancarias_empresa m on p.idpagos = m.id_pago
                where 
                    p.tipo_movimiento = 0 and
                    p.empresa_id = :empresa_id and
                    p.estatus = 1
                    $sql
                GROUP BY 
                    month(created_at)
            
                UNION
            
                select 
                    (sum(mcbe.Retiro)*(-1)) total,
                    if(month(Fecha)=1,'Enero',
                            if(month(Fecha)=2,'Febrero',
                                if(month(Fecha)=3,'Marzo',
                                    if(month(Fecha)=4,'Abril',
                                        if(month(Fecha)=5,'Mayo',
                                            if(month(Fecha)=6,'Junio',
                                                if(month(Fecha)=7,'Julio',
                                                    if(month(Fecha)=8,'Agosto',
                                                        if(month(Fecha)=9,'Septiembre',
                                                            if(month(Fecha)=10,'Octubre',
                                                                if(month(Fecha)=11,'Noviembre',
                                                                    if(month(Fecha)=12,'Diciembre',null)
                                                                )
                                                            )
                                                        )
                                                    )
                                                )
                                            )
                                        )
                                    )	
                                )
                            )
                        ) mes
                from movimientos_cuentas_bancarias_empresa mcbe
                inner join cuentas_bancarias_empresa cbe on mcbe.cuenta_origen_id = cbe.PKCuenta
                where 
                    cbe.empresa_id = :empresa_id1 and 
                    mcbe.estatus = 1
                    $sql1
                GROUP BY 
                    month(Fecha)
            ) ventas_gastos");
            $stmt = $db->prepare($query);
            $stmt->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
            $stmt->bindValue(":empresa_id1",$_SESSION['IDEmpresa']);
            
            $stmt->execute();
            $ventas_gastos = $stmt->fetchAll(PDO::FETCH_OBJ);

            $labels = [];
            $dataset = [];
            
            foreach($ventas_gastos as $r){
                array_push($labels,$r->mes);
                
            }
            $labels = array_values(array_unique($labels));
            
            foreach ($ventas_gastos as $r) {
                $color = get_data::rand_color();
                if((double)$r->total > 0){
                    if(in_array('Ingresos',array_column($dataset,'label'))){
                        $dataset[array_search('Ingresos', array_column($dataset, 'label'))]['data'] += [$r->mes => $r->total];
                    } else {
                        $dataset[] = 
                        [
                            'label' =>'Ingresos',
                            'data'=>[
                                $r->mes =>$r->total,
                            ],
                            'borderColor' => $color,
                            'backgroundColor' => $color
                        ];
                    }
                }else{
                    if(in_array('Gastos',array_column($dataset, 'label'))){
                        
                        $dataset[array_search('Gastos', array_column($dataset, 'label'))]['data'] += [$r->mes => $r->total];
                    } else {
                        $dataset[] = 
                        [
                            'label'=>'Gastos',
                            'data'=>[
                                $r->mes => $r->total,
                            ],
                            'borderColor' => $color,
                            'backgroundColor' => $color
                        ];
                    }
                    
                }
            }
            return ["labels"=>$labels,"dataset"=>$dataset];
        }
        
        static function getDataGeneralUtilities($initialDate,$finalDate)
        {
            $db = conection::getDb('../../../');
            $actualYear = date('Y');
            $sql = "";

            if(
                ($initialDate !== null && $initialDate !== "") &&
                ($finalDate  !== null && $finalDate !== ""))
                {
                    $sql .= " v.created_at between :initialDate and :finalDate";
                } else if(
                    ($finalDate === null || $finalDate === "") and
                    ($initialDate !== null && $initialDate !== "")
                ){
                    $sql .= " v.created_at between :initialDate and now()";
                } else if(
                    ($initialDate === null || $initialDate === "") and
                    $finalDate  !== null && $finalDate !== ""
                ){
                    $sql .= " v.created_at between '2019-01-01' and :finalDate";
                } else {
                    $sql .= " year(v.created_at) = :year";
                }

            $query = sprintf("
                select 
                    p.nombre,
                    sum(dv.Cantidad) cantidad,
                    dv.Precio precio_venta,
                    if(cvp.CostoCompra is null,'',cvp.CostoCompra) precio_compra,
                    if(cvp.CostoCompra is null,'',dv.Precio-cvp.CostoCompra) utilidad_unitaria,
                    if(cvp.CostoCompra is null,'',(dv.Precio-cvp.CostoCompra)/100) utilidad_porcentaje_unitaria,
                    if(cvp.CostoCompra is null,'',(dv.Precio-cvp.CostoCompra)*sum(dv.Cantidad)) utilidad_total,
                    if(cvp.CostoCompra is null,'',((dv.Precio-cvp.CostoCompra)*sum(dv.Cantidad))/100) utilidad_porcentaje_total
                from ventas_directas v
                inner join detalle_venta_directa dv on v.PKVentaDirecta = dv.FKVentaDirecta
                inner join productos p on dv.FKProducto = p.PKProducto
                inner join costo_venta_producto cvp on p.PKProducto = cvp.FKProducto
                where
                    v.empresa_id = :empresa_id and
                    $sql
                group by p.PKProducto");
            
            $stmt = $db->prepare($query);
            $stmt->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
            if(
                ($initialDate !== null && $initialDate !== "") &&
                ($finalDate  !== null && $finalDate !== ""))
                {
                    $stmt->bindValue(":initialDate",date("Y-m-d",strtotime($initialDate)));
                    $stmt->bindValue(":finalDate",date("Y-m-d",strtotime($finalDate)));
                } else if(
                    ($finalDate === null || $finalDate === "") and
                    ($initialDate !== null && $initialDate !== "")
                ){
                    $stmt->bindValue(":initialDate",date("Y-m-d",strtotime($initialDate)));
                } else if(
                    ($initialDate === null || $initialDate === "") and
                    $finalDate  !== null && $finalDate !== ""
                ){
                    $stmt->bindValue(":finalDate",date("Y-m-d",strtotime($finalDate)));
                } else {
                    $stmt->bindValue(":year",$actualYear);
                }
            
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
    }

    
<?php
session_start();
date_default_timezone_set('America/Mexico_City');
$user = $_SESSION["Usuario"];

class conectar
{ //Llamado al archivo de la conexión.

    public function getDb()
    {
        include "../../../include/db-conn.php";
        return $conn;
    }
}

function GetEvn()
{
    include "../../../include/db-conn.php";
    $appUrl = $_ENV['APP_URL'] ?? 'https://app.timlid.com/';
    return ['server' => $appUrl];
}

class get_data
{

    /////////////////////////TABLAS//////////////////////////////
    public function getReportesTable($vendedor, $cliente, $estado, $fechaInic, $fechaFin, $mestxt)
    {
        $con = new conectar();
        $db = $con->getDb();
        $envVariables = GetEvn();
        $appUrl = $envVariables['server'];
        $where1 = "";
        $where2 = "";

        $PKEmpresa = $_SESSION["IDEmpresa"];
        $conditions = "";
        $conditions1 = "";
        $values = [':idEmpresa' => $PKEmpresa];

        if ($vendedor != "todos") {
            $conditions .= ' AND f.empleado_id = :idVendedor';
            $conditions1 .= 'AND v.empleado_id = :idVendedor';
            $values[':idVendedor'] = $vendedor;
        }

        if ($cliente != "todos") {
            $conditions .= ' AND f.cliente_id = :idCliente';
            $conditions1 .= ' AND v.FKCliente = :idCliente';
            $values[':idCliente'] = $cliente;
        }

        if ($estado != "todos") {
            $conditions .= ' AND c.estado_id = :idEstado';
            $conditions1 .= ' AND c.estado_id = :idEstado';
            $values[':idEstado'] = $estado;
        }

        $queryMes = "";
        $queryMes1 = "";
        //$mes = $mes == "000" ? "" : " and month(f.fecha) = " . $mes;
        /*         echo $mestxt;
        return; */
        //return $mestxt;
        ///Si no selecciona ningun mes, no filtra por mes
        if ($mestxt == "010") {
            $queryMes = "";
            $queryMes1 = "";
            //Crea el where de fechas
            $date_from = $fechaInic;/*  == "000" ? "" : $fechaInic; */
            $date_to = $fechaFin; /* == "000" ? "" : $fechaFin; */

            $fecha_actual = date("Y-m-d");
            if ($date_from != 000 && $date_to != 000) {

                $where1 .= " AND f.fecha_timbrado BETWEEN  '$date_from' AND '$date_to'";
                $where2 .= " AND v.created_at BETWEEN  '$date_from' AND '$date_to'";
            } elseif ($date_to != 000) {

                /* $where1 .= " AND f.fecha_timbrado <= '$date_to'"; */
                $fechaInic = date("Y-m-d", strtotime($date_to . " - 1 month"));
                $where1 .= " AND f.fecha_timbrado BETWEEN  '$fechaInic' AND '$date_to'";
                $where2 .= " AND v.created_at BETWEEN '$fechaInic' AND '$date_to'";
            } elseif ($date_from != 000) {

                /* $where1 .= " AND f.fecha_timbrado >= '$date_from'"; */
                $fechaFin = date('Y-m-d');
                $where1 .= " AND f.fecha_timbrado BETWEEN  '$date_from' AND '$fechaFin'";
                $where2 .= " AND v.created_at BETWEEN  '$date_from' AND '$fechaFin'";
            } else {
                $fechaInic = date("Y-m-d", strtotime($fecha_actual . " - 1 month"));
                $fechaFin = $fecha_actual;
                $where1 .= " AND f.fecha_timbrado BETWEEN  '$fechaInic' AND '$fechaFin'";
                $where2 .= " AND v.created_at BETWEEN  '$fechaInic' AND '$fechaFin'";
            }
        } else {
            //Si selecciona un mes, filtra por mes
            //Array de meses seleccionados
            $mes = array();
            $mes = $mestxt; //explode(",", $mestxt);
            $queryMes = "";
            $queryMes1 = "";
            //Si en el mes seleccionado viene el codigo de "000" filtra con todos los meses del año actual.
            if (array_search("000", $mes) !== false) {
                $year = date("Y");
                $queryMes = " and (year(f.fecha_timbrado) = " . $year . ")";
                $queryMes1 = " and (year(v.created_at) = " . $year . ")";
            } else {
                //Si en el mes seleccionado no viene el codigo de "000" filtra con los meses seleccionados. 
                foreach ($mes as $key => $value) {
                    if ($queryMes == "") {
                        $queryMes .= " and (((month(f.fecha_timbrado) = " . $value . ")";
                        $queryMes1 .= " and (((month(v.created_at) = " . $value . ")";
                    } else {
                        $queryMes .= " or (month(f.fecha_timbrado) = " . $value . ")";
                        $queryMes1 .= " and (((month(v.created_at) = " . $value . ")";
                    }
                }
                //Delimita el año al año actual
                $year = date("Y");
                $queryMes .= ") and (year(f.fecha_timbrado) = " . $year . "))";
                $queryMes1 .= ") and (year(v.created_at) = " . $year . "))";
                //$mes = " and month(f.fecha) = " . $mes;
            }
        }
        $where1 .= $queryMes;
        $where2 .= $queryMes1;

        //Month name to spanish
        $queryIdioma = "SET lc_time_names = 'es_MX'";
        $stmt = $db->prepare($queryIdioma);
        $stmt->execute();


        $query = "SELECT
        SUM(f.total_facturado) AS total,
        f.fecha_timbrado AS fecha,
        MONTHNAME(f.fecha_timbrado) as mes,
        f.estatus AS estatus,
        f.cliente_id AS idCliente,
        f.empresa_id AS idEmpresa,
        f.empleado_id AS idVendedor,
        c.NombreComercial AS cliente,
        c.estado_id AS idEstado,
        c.PKCliente,
        IFNULL( CONCAT(e.Nombres, ' ', e.PrimerApellido),'Sin Vendedor') AS vendedor,
        ef.Estado
      FROM
        facturacion AS f
        LEFT JOIN clientes AS c ON f.cliente_id = c.PKCliente
        LEFT JOIN empleados AS e ON f.empleado_id = e.PKEmpleado
        LEFT JOIN estados_federativos as ef on c.estado_id = ef.PKEstado
        WHERE f.empresa_id = :idEmpresa $where1 AND (f.estatus = 1 OR f.estatus = 2 OR f.estatus = 3) $conditions GROUP BY";
        $stmtDatatable = $db->prepare($query . ' f.empleado_id, f.cliente_id');
        $stmtDatatable->execute($values);
        $datatable = $stmtDatatable->fetchAll(PDO::FETCH_ASSOC);

        $stmtChart = $db->prepare($query . ' f.empleado_id, f.cliente_id, f.fecha_timbrado ORDER BY f.fecha_timbrado ASC');
        $stmtChart->execute($values);
        $chart = $stmtChart->fetchAll(PDO::FETCH_ASSOC);
        /* print_r($query);
    return; */
        $query1 = sprintf("
                          select 
                            SUM(v.Importe) total_ventas,
                            v.created_at AS fecha,
                            MONTHNAME(v.created_at) as mes,
                            e.PKEmpleado AS idVendedor,
                            c.PKCliente AS idCliente,
                            IFNULL( CONCAT(e.Nombres, ' ', e.PrimerApellido),'Sin Vendedor') AS vendedor,
                            c.NombreComercial AS cliente,
                            ef.Estado
                          from 
                            ventas_directas as v 
                          left join clientes AS c on v.FKCliente = c.PKCliente
                          left join empleados as e on v.empleado_id = e.PKEmpleado
                          left join estados_federativos as ef on c.estado_id = ef.PKEstado
                          where v.empresa_id = :idEmpresa and v.estatus_factura_id <> 2 $where2 $conditions1
                          group by");
        $stmt1 = $db->prepare($query1 . ' v.empleado_id, v.FKCliente');
        $stmt1->execute($values);
        $datatable1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);

        $stmtChart1 = $db->prepare($query1 . ' v.empleado_id, v.FKCliente, v.created_at ORDER BY v.created_at ASC');
        $stmtChart1->execute($values);
        $chart1 = $stmtChart1->fetchAll(PDO::FETCH_ASSOC);
        /*  */
        $table = [];
        $table1 = [];

        for ($i=0; $i < count($datatable); $i++) { 
            if(in_array($datatable[$i]['idVendedor'], array_column($table, 'idVendedor')) && in_array($datatable[$i]['idCliente'], array_column($table, 'PKCliente'))){
                $table[array_search($datatable[$i]['idVendedor'], array_column($table, 'idVendedor')) && array_search($datatable[$i]['idCliente'], array_column($table, 'PKCliente'))]['total'] += $datatable[$i]['total'];
            } else {
                array_push($table,['idVendedor'=>$datatable[$i]['idVendedor'],'vendedor'=>$datatable[$i]['vendedor'],'cliente'=>$datatable[$i]['cliente'],'PKCliente'=>$datatable[$i]['idCliente'],'total'=>$datatable[$i]['total'],'Estado'=>$datatable[$i]['Estado'],'mes'=>$datatable[$i]['mes'],'total_pendiente'=>0]);
            }
        }
        for ($j=0; $j < count($datatable1); $j++){    
            if(in_array($datatable1[$j]['idVendedor'], array_column($table, 'idVendedor')) && in_array($datatable1[$j]['idCliente'], array_column($table, 'PKCliente'))){
                $table[array_search($datatable1[$j]['idVendedor'], array_column($table, 'idVendedor')) && array_search($datatable1[$j]['idCliente'], array_column($table, 'PKCliente'))]['total_pendiente'] += $datatable1[$j]['total_ventas'];
            } else {
                array_push($table,['idVendedor'=>$datatable1[$j]['idVendedor'],'vendedor'=>$datatable1[$j]['vendedor'],'cliente'=>$datatable1[$j]['cliente'],'PKCliente'=>$datatable1[$j]['idCliente'],'total'=>0,'Estado'=>$datatable1[$j]['Estado'],'mes'=>$datatable1[$j]['mes'],'total_pendiente'=>$datatable1[$j]['total_ventas']]);
            }
            
        }

        for ($i=0; $i < count($chart); $i++) { 
            if(in_array($chart[$i]['idVendedor'], array_column($table1, 'idVendedor')) && in_array($chart[$i]['idCliente'], array_column($table1, 'PKCliente'))){
                $table1[array_search($chart[$i]['idVendedor'], array_column($table1, 'idVendedor')) && array_search($chart[$i]['idCliente'], array_column($table1, 'PKCliente'))]['total'] += $chart[$i]['total'];
            } else {
                array_push($table1,['idVendedor'=>$chart[$i]['idVendedor'],'fecha'=>$chart[$i]['fecha'],'vendedor'=>$chart[$i]['vendedor'],'cliente'=>$chart[$i]['cliente'],'PKCliente'=>$chart[$i]['idCliente'],'total'=>$chart[$i]['total'],'Estado'=>$chart[$i]['Estado'],'mes'=>$chart[$i]['mes'],'total_pendiente'=>0]);
            }
        }

        for ($i=0; $i < count($chart1); $i++) { 
            if(in_array($chart1[$i]['idVendedor'], array_column($table1, 'idVendedor')) && in_array($chart1[$i]['idCliente'], array_column($table1, 'PKCliente'))){
                $table1[array_search($chart1[$i]['idVendedor'], array_column($table1, 'idVendedor')) && array_search($chart1[$i]['idCliente'], array_column($table1, 'PKCliente'))]['total_pendiente'] += $chart1[$i]['total_ventas'];
            } else {
                array_push($table1,['idVendedor'=>$chart1[$i]['idVendedor'],'fecha'=>$chart1[$i]['fecha'],'vendedor'=>$chart1[$i]['vendedor'],'cliente'=>$chart1[$i]['cliente'],'PKCliente'=>$chart1[$i]['idCliente'],'total'=>0,'Estado'=>$chart1[$i]['Estado'],'mes'=>$chart1[$i]['mes'],'total_pendiente'=>$chart1[$i]['total_ventas']]);
            }
        }

        ///Si el filtro aplicado no es por rango, se filtra por meses
        if ($mestxt == "010") {
            $resLabels = setLabels($fechaInic, $fechaFin);
            $labels = $resLabels['labels'];
            $labelsData = $resLabels['labelsData'];
            $response = ['datatable' => [], 'chart' => ['labels' => $labels, 'dataAll' => $table1]];
        } else {
            /* echo($mestxt . "-" . $fechaInic . "-" . $fechaFin);
            return "KO"; */
            $resLabels = setLabelsM($mestxt);
            $labels = $resLabels['labels'];
            $labelsData = $resLabels['labelsData'];
            $response = ['datatable' => [], 'chart' => ['labels' => $labels, 'dataAll' => $table1]];
        }

        foreach ($table as $item) {
            //link para detalle del cliente
            $item['cliente'] = '<a style="cursor:pointer" href="' . $appUrl . 'catalogos/clientes/catalogos/clientes/detalles_cliente.php?c=' . $item['PKCliente'] . '">' . $item['cliente'] . '</a>';

            array_push($response['datatable'], ['vendedor' => $item['vendedor'], 'cliente' => $item['cliente'], 'importe' => '$ ' . number_format($item['total'], 2, ".", ","),'total_pendiente'=> '$ ' . number_format($item['total_pendiente'], 2, ".", ","),'total_global'=> '$ ' . number_format($item['total']+$item['total_pendiente'], 2, ".", ","), 'estado' => $item['Estado'], 'mes' => $item['mes'], 'acciones' => '']);
            
        }

        /* print_r(($chart)); */
        /* return; */
        if ($vendedor == "todos" && $cliente == "todos") {
            $chars = setCharts($table1, $labelsData, 'no-filters');
            $response['chart']['data']['clientes'] = $chars['clientes'];
            $response['chart']['data']['vendedores'] = $chars['vendedores'];
        }

        if (($vendedor != "todos" && $cliente == "todos") || ($vendedor == "todos" && $cliente != "todos")) {
            $chars = setCharts($table1, $labelsData, 'one-filter');
            $response['chart']['data']['vendedor'] = $chars['vendedor'];
            $response['chart']['data']['cliente'] = $chars['clientes'];
        }

        if ($vendedor != "todos" && $cliente != "todos") {
            $chars = setCharts($table1, $labelsData, 'both-filters');
            $response['chart']['data']['cliente'] = $chars['cliente'];
        }

        return $response;
    }

    function getTotalsReporting($vendedor, $cliente, $estado, $fechaInic, $fechaFin, $mestxt)
    {
        $con = new conectar();
        $db = $con->getDb();
        $envVariables = GetEvn();
        $appUrl = $envVariables['server'];
        $where1 = "";
        $where2 = "";

        $PKEmpresa = $_SESSION["IDEmpresa"];
        $conditions = "";
        $conditions1 = "";
        $values = [':idEmpresa' => $PKEmpresa];

        if ($vendedor != "todos") {
            $conditions = ' AND f.empleado_id = :idVendedor';
            $conditions1 = 'AND v.empleado_id = :idVendedor';
            $values[':idVendedor'] = $vendedor;
        }

        if ($cliente != "todos") {
            $conditions .= ' AND f.cliente_id = :idCliente';
            $conditions1 = 'AND v.FKCliente = :idCliente';
            $values[':idCliente'] = $cliente;
        }

        if ($estado != "todos") {
            $conditions .= ' AND c.estado_id = :idEstado';
            $conditions1 .= ' AND c.estado_id = :idEstado';
            $values[':idEstado'] = $estado;
        }

        $queryMes = "";
        $queryMes1 = "";
        //$mes = $mes == "000" ? "" : " and month(f.fecha) = " . $mes;
        /*         echo $mestxt;
        return; */
        //return $mestxt;
        ///Si no selecciona ningun mes, no filtra por mes
        if ($mestxt == "010") {
            $queryMes = "";
            $queryMes1 = "";
            //Crea el where de fechas
            $date_from = $fechaInic;/*  == "000" ? "" : $fechaInic; */
            $date_to = $fechaFin; /* == "000" ? "" : $fechaFin; */

            $fecha_actual = date("Y-m-d");
            if ($date_from != 000 && $date_to != 000) {

                $where1 .= " AND f.fecha_timbrado BETWEEN  '$date_from' AND '$date_to'";
                $where2 .= " AND v.created_at BETWEEN  '$date_from' AND '$date_to'";
            } elseif ($date_to != 000) {

                /* $where1 .= " AND f.fecha_timbrado <= '$date_to'"; */
                $fechaInic = date("Y-m-d", strtotime($date_to . " - 1 month"));
                $where1 .= " AND f.fecha_timbrado BETWEEN  '$fechaInic' AND '$date_to'";
                $where2 .= " AND v.created_at BETWEEN '$fechaInic' AND '$date_to'";
            } elseif ($date_from != 000) {

                /* $where1 .= " AND f.fecha_timbrado >= '$date_from'"; */
                $fechaFin = date('Y-m-d');
                $where1 .= " AND f.fecha_timbrado BETWEEN  '$date_from' AND '$fechaFin'";
                $where2 .= " AND v.created_at BETWEEN  '$date_from' AND '$fechaFin'";
            } else {
                $fechaInic = date("Y-m-d", strtotime($fecha_actual . " - 1 month"));
                $fechaFin = $fecha_actual;
                $where1 .= " AND f.fecha_timbrado BETWEEN  '$fechaInic' AND '$fechaFin'";
                $where2 .= " AND v.created_at BETWEEN  '$fechaInic' AND '$fechaFin'";
            }
        } else {
            //Si selecciona un mes, filtra por mes
            //Array de meses seleccionados
            $mes = array();
            $mes = $mestxt; //explode(",", $mestxt);
            $queryMes = "";
            $queryMes1 = "";
            //Si en el mes seleccionado viene el codigo de "000" filtra con todos los meses del año actual.
            if (array_search("000", $mes) !== false) {
                $year = date("Y");
                $queryMes = " and (year(f.fecha_timbrado) = " . $year . ")";
                $queryMes1 = " and (year(v.created_at) = " . $year . ")";
            } else {
                //Si en el mes seleccionado no viene el codigo de "000" filtra con los meses seleccionados. 
                foreach ($mes as $key => $value) {
                    if ($queryMes == "") {
                        $queryMes .= " and (((month(f.fecha_timbrado) = " . $value . ")";
                        $queryMes1 .= " and (((month(v.created_at) = " . $value . ")";
                    } else {
                        $queryMes .= " or (month(f.fecha_timbrado) = " . $value . ")";
                        $queryMes1 .= " and (((month(v.created_at) = " . $value . ")";
                    }
                }
                //Delimita el año al año actual
                $year = date("Y");
                $queryMes .= ") and (year(f.fecha_timbrado) = " . $year . "))";
                $queryMes1 .= ") and (year(v.created_at) = " . $year . "))";
                //$mes = " and month(f.fecha) = " . $mes;
            }
        }
        $where1 .= $queryMes;
        $where2 .= $queryMes1;

        //Month name to spanish
        $queryIdioma = "SET lc_time_names = 'es_MX'";
        $stmt = $db->prepare($queryIdioma);
        $stmt->execute();


        $query = "SELECT
        SUM(f.total_facturado) AS total
      FROM
        facturacion AS f
        LEFT JOIN clientes AS c ON f.cliente_id = c.PKCliente
        LEFT JOIN empleados AS e ON f.empleado_id = e.PKEmpleado
        LEFT JOIN estados_federativos as ef on c.estado_id = ef.PKEstado
        WHERE f.empresa_id = :idEmpresa $where1 AND (f.estatus = 1 OR f.estatus = 2 OR f.estatus = 3) $conditions";
        $stmtDatatable = $db->prepare($query);
        $stmtDatatable->execute($values);
        $datatable = $stmtDatatable->fetchAll(PDO::FETCH_ASSOC);
        
        $query1 = sprintf("
                          select 
                            SUM(v.Importe) total_ventas
                          from 
                            ventas_directas as v 
                          left join clientes AS c on v.FKCliente = c.PKCliente
                          left join empleados as e on v.empleado_id = e.PKEmpleado
                          left join estados_federativos as ef on c.estado_id = ef.PKEstado
                          where v.empresa_id = :idEmpresa and v.estatus_factura_id <> 2 $where2 $conditions1
                          ");
        $stmt1 = $db->prepare($query1);
        $stmt1->execute($values);
        $datatable1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);

        return ["total_facturado" => '$ '. number_format($datatable[0]['total'],2), "total_no_facturado"=> '$ ' . number_format($datatable1[0]['total_ventas'],2),"total_global"=>'$ ' . number_format(($datatable[0]['total'] + $datatable1[0]['total_ventas']),2)];
    }

    //JAVIER RAMIREZ
    public function getVentaDirectaTable($isPermissionsEdit, $isPermissionsDelete)
    {
        $con = new conectar();
        $db = $con->getDb();
        $envVariables = GetEvn();
        $appUrl = $envVariables['server'];

        $table = "";
        $PKEmpresa = $_SESSION["IDEmpresa"];

        $stmt = $db->prepare('call spc_Tabla_VentasDirectas_Consulta(?)');
        $stmt->execute(array($PKEmpresa));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $nombreComercial = str_replace('"', '\"', $r['NombreComercial']);

            $Id = $r['PKVentaDirecta'];
            $Referencia = $r['Referencia'];
            $rfc = $r['rfc'];
            $FechaCreacion = $r['FechaCreacion'];
            $FechaVencimiento = $r['FechaVencimiento'];
            $importe = number_format($r['Importe'], 2);

            $FKEstatusVenta = $r['FKEstatusVenta'];
            $EstatusVenta = $r['EstatusVenta'];
            $isInventario = $r['isInventario'];
            $estatusOrdenPedido = $r['estatusOrdenPedido'];
            $numTiposProd = $r['numTiposProd'];
            $tipoProd = $r['tipoProdVenta'];
            //$isServicio = $r['isServicio'];

            $EstatusFactura = $r['estatusFactura'];
            $EstatusFacturaID = $r['estatus_factura_id'];

            $colorEstatus = '';
            $cierreEstatus = '</span>';
            //$acciones = '';
            $Facturar = $r['vendedor'];
            if($r['estatus'] != ''){
                $Estado = $r['estatus'];
            }else{
                $Estado = $r['estatus_cuentaCobrar'];
            }

            //añade una etiqueta segun el estado de la factura
            if($Estado==2){
                  $Estado= '<span class=\"left-dot orange-dot\">Parcialmente Pagada</span>';
            }elseif($Estado==3){
                $Estado= '<span class=\"left-dot green-dot\">Pagada</span>';
            }elseif($Estado==4){
                $Estado= '<span class=\"left-dot blue-light-dot\">Pendiente de Pago</span>';
            }elseif($Estado==5){
                $Estado= '<span class=\"left-dot blue-light-dot\">Pendiente de Pago</span>';
            }else{
                $Estado= '<span class=\"left-dot blue-light-dot\">Pendiente de Pago</span>';
            }

            if ($estatusOrdenPedido == '1' || $estatusOrdenPedido == '2' || $estatusOrdenPedido == '0') {
                $colorEstatus = '<span class=\"left-dot turquoise-dot\">';

                //$acciones = '<i><input type=\"hidden\" value=\"' . md5($Id) . '\" id=\"inp-' . $Id . '\">';

                /* if ($isPermissionsDelete == '1') {
                    $acciones = $acciones . '';
                }

                if ($isPermissionsEdit == '1') {
                    $acciones = $acciones . '<i onclick=\"obtenerVer(' . $Id . ');\" class=\"fas fa-clipboard-list pointer\"></i>';
                } */

                //$acciones = $acciones . '</i>';

                if ($isInventario == '1') {
                    if ($estatusOrdenPedido == '1') {
                        $EstatusVenta = 'Nueva';
                    } else if ($estatusOrdenPedido == '2') {
                        $EstatusVenta = 'Nueva FD';
                    } else if ($estatusOrdenPedido == '0') {
                        if ($FKEstatusVenta == '6') {
                            $EstatusVenta = 'Factura pendiente';
                        } else if ($FKEstatusVenta == '2') {
                            $EstatusVenta = 'Facturada';
                        }
                    }
                }
            } else if ($estatusOrdenPedido == '3' || $estatusOrdenPedido == '4') {
                $colorEstatus = '<span class=\"left-dot yellow-dot\">';
                //$acciones = '<input type=\"hidden\" value=\"' . md5($Id) . '\" id=\"inp-' . $Id . '\"><i onclick=\"obtenerVer(' . $Id . ');\" class=\"fas fa-clipboard-list pointer\"></i>';

                if ($isInventario == '1') {
                    if ($estatusOrdenPedido == '3') {
                        $EstatusVenta = 'Parcialmente surtida';
                    } else if ($estatusOrdenPedido == '4') {
                        $EstatusVenta = 'Parcialmente surtida FD';
                    }
                }
            } else if ($estatusOrdenPedido == '9') {
                $colorEstatus = '<span class=\"left-dot gray-dot\">';
                //$acciones = '<input type=\"hidden\" value=\"' . md5($Id) . '\" id=\"inp-' . $Id . '\"> <i onclick=\"obtenerVer(' . $Id . ');\" class=\"fas fa-clipboard-list pointer\"></i>';

            } else if ($EstatusVenta == 'Cerrada' || $estatusOrdenPedido == '7' || $estatusOrdenPedido == '8') {
                $colorEstatus = '<span class=\"left-dot red-dot\">';
                //$acciones = '<input type=\"hidden\" value=\"' . md5($Id) . '\" id=\"inp-' . $Id . '\"> <i onclick=\"obtenerVer(' . $Id . ');\" class=\"fas fa-clipboard-list pointer\"></i>';

                if ($estatusOrdenPedido == '8') {
                    $EstatusVenta = 'Cancelada';
                }
            } else if ($estatusOrdenPedido == '5' || $estatusOrdenPedido == '6') {
                $colorEstatus = '<span class=\"left-dot green-dot\">';
                //$acciones = '<input type=\"hidden\" value=\"' . md5($Id) . '\" id=\"inp-' . $Id . '\"> <i onclick=\"obtenerVer(' . $Id . ');\" class=\"fas fa-clipboard-list pointer\"></i>';

                if ($isInventario == '1') {
                    if ($estatusOrdenPedido == '5') {
                        $EstatusVenta = 'Surtida completa';
                    } else if ($estatusOrdenPedido == '6') {
                        $EstatusVenta = 'Surtida completa FD';
                    }
                }
            }

            if ($EstatusFacturaID == 1 || $EstatusFacturaID == 2 || $EstatusFacturaID == 9 || $EstatusFacturaID == 10) {
                //$acciones .= '<br><i data-toggle=\"modal\" data-target=\"#copyVenta\" onclick=\"idCopear(' . $Id . ');\" class=\"fas fa-copy pointer\"></i>';
            }

            switch ($EstatusFacturaID) {
                case '1':
                    $EstatusFactura = "<span class='left-dot turquoise-dot'>" . $EstatusFactura . "</span>";
                    break;
                case '2':
                    $EstatusFactura = "<span class='left-dot turquoise-dot'>" . $EstatusFactura . "</span>";
                    break;
                case '3':
                    $EstatusFactura = "<span class='left-dot yellow-dot'>" . $EstatusFactura . "</span>";
                    break;
                case '4':
                    $EstatusFactura = "<span class='left-dot yellow-dot'>" . $EstatusFactura . "</span>";
                    break;
                case '5':
                    $EstatusFactura = "<span class='left-dot green-dot'>" . $EstatusFactura . "</span>";
                    break;
                case '6':
                    $EstatusFactura = "<span class='left-dot red-dot'>" . $EstatusFactura . "</span>";
                    break;
            }

            //link para detalle del cliente
            //$nombreComercial = '<a style=\"cursor:pointer\" href=\"' . $appUrl . 'catalogos/clientes/catalogos/clientes/detalles_cliente.php?c=' . $r['PKCliente'] . '\">' . $nombreComercial . '</a>';

            $html = "<a id='detalle_venta' href='ver_ventas.php?vd=" . $Id . "' data-id='" . $Id . "'> " . $Referencia . " </a>";
            $table .= '{"Id":"' . $Id . '",
                  "Referencia":"' . $html . '",
                  "Cliente":"' . $nombreComercial . '",
                  "RFC":"' . $rfc . '",
                  "FechaEmision":"' . $FechaCreacion . '",
                  "FechaVencimiento":"' . $FechaVencimiento . '",
                  "EstatusPago":"' . $Estado . '",
                  "Importe":"' . '$' . $importe . '",
                  "Facturar":"' . $Facturar . '",
                  "EstatusFactura":"' . $EstatusFactura . '",
                  "Acciones":"",
                  "EstatusVenta":"' . $colorEstatus . $EstatusVenta . $cierreEstatus . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getVentasDirectasTempTable($pkUsuario, $pkSucursal)
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";
        $decimas = 2;

        $query = sprintf('call spc_Tabla_VentasDirectasTemp_Consulta(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkUsuario, $pkSucursal));
        $array = $stmt->fetchAll();

        /* Buscar el mayor numero de decimales en los precios unitarios */
        foreach ($array as $p) {
            $precio1 = $p['precio'];

            /* Separar parte entera del decimal */
            $explPreecio1 = explode(".", $precio1);
            /* convertir la parte decimal a una array */
            $precio_split1 = str_split($explPreecio1[1]);
            /* Guarda donde se detecta la ultima parte decimal */
            $flagdecimales1 = 2;
            /* Cuenta las posisiones del array recorridas */
            $count1 = 0;

            foreach ($precio_split1 as $numero1) {
                $count1++;

                if ($numero1 == 0) {
                } else {
                    /* Si el numero que encontro no es 0 guarda la posision en la posision de decimal */
                    $flagdecimales1 = $count1;
                }
            }
            if ($flagdecimales1 > $decimas) {
                $decimas = $flagdecimales1;
            }
        }

        foreach ($array as $r) {
            $id = $r['id'];
            $nombre = $r['nombre'];
            $clave = $r['clave'];

            if ($r['clave'] == '') {
                $producto = str_replace('"', '\"', $r['nombre']);
            } else {
                $producto = str_replace('"', '\"', $r['clave']) . ' - ' . str_replace('"', '\"', $r['nombre']);;
            }

            $cantidad = $r['cantidad'];

            if ($r['unidadMedida'] == '') {
                $unidadMedida = '(Sin unidad de medida)';
            } else {
                $unidadMedida = $r['unidadMedida'];
            }

            $precio = $r['precio'];
            $importe = $r['importe'];
            $impuestos = $r['impuestos'];
            $existencias = $r['existencia'];

            /* Mostrar solo los decimales usados */
            /*  */  /*  */ /*  */ /*  */ /*  */ /*  */
            /* Separar parte entera del decimal */
            //    $explPreecio = explode(".",$precio);
            /* convertir la parte decimal a una array */
            //    $precio_split = str_split($explPreecio[1]);
            /* Guarda donde se detecta la ultima parte decimal */
            //    $flagdecimales = 2;
            /* Cuenta las posisiones del array recorridas */
            //    $count=0;

            //        foreach($precio_split as $numero){
            //            $count++;
            //    
            //            if($numero == 0){

            //            }else{
            /* Si el numero que encontro no es 0 guarda la posision en la posision de decimal */
            //                $flagdecimales = $count;
            //           }
            //        }
            /* Convierte a decimal tomando en cuenta los decimales encontrados */
            $precio = number_format($precio, $decimas, ".", "");
            $importe = number_format($importe, $decimas, ".", ",");

            $etiquetaI = '<label class=\"textTable\">';
            $etiquetaF = '</label>';
            $acciones = '<i><img class=\"btnEdit\"  onclick=\"obtenerIdVentaDirectaTempEliminar(' . $id . ');\" src=\"../../../../img/timdesk/delete.svg\"></i>';

            $cantidadEditable = ' <div class=\"input-group\"><input class=\"form-control textTable border-0\" type=\"number\" value=\"' . $cantidad . '\" onchange=\"validarCantidad(' . $id . ',' . $cantidad . ','.$existencias.');\" id=\"cantidad-' . $id . '\"> <input type=\"hidden\" value=\"1\" id=\"cantidadHis-' . $id . '\"> <i><img src=\"../../../../img/timdesk/alerta.svg\" style=\"display: none;\" id=\"notaCantidad-' . $id . '\" width=30px></i></div>';
            $precioEditable = '   <div class=\"input-group\"><input class=\"form-control textTable border-0\" type=\"number\" value=\"' . $precio . '\" onchange=\"validarCantidad(' . $id . ');\" id=\"precio-' . $id . '\"> <i><img src=\"../../../../img/timdesk/alerta.svg\" style=\"display: none;\" id=\"notaPrecio-' . $id . '\" width=30px></i></div>';
            $table .= '{"producto_id":"'.$r['producto'].'",
                "Id":"' . $etiquetaI . $id . $etiquetaF . '",
                "Producto":"' . $etiquetaI . $producto . $etiquetaF . '",
                "cantidad_real":"'.$cantidad.'",
                "Cantidad":"' . $cantidadEditable . '",
                "UnidadMedida":"' . $etiquetaI . $unidadMedida . $etiquetaF . '",
                "PrecioUnitario":"' . $etiquetaI . /* '$ ' . */ $precioEditable . $etiquetaF . '",
                "Impuestos":"' . $etiquetaI . $impuestos . $etiquetaF . '",
                "Importe":"' . '$' . $etiquetaI . $importe . $etiquetaF . '",
                "stock":"'.$existencias.'",
                "Existencias":"' . $etiquetaI . $existencias . $etiquetaF . '",
                "Acciones":"' . $acciones . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getVentaDirectaTableEdit($pkOrden, $rmdID)
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";
        $usuario = $_SESSION['PKUsuario'];
        $query = sprintf('call spc_Tabla_VentasDirectas_ConsultaEdit(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkOrden, $usuario, $rmdID));
        $array = $stmt->fetchAll();

        $decimas = 2;

        /* Buscar el mayor numero de decimales en los precios unitarios */
        foreach ($array as $p) {
            $precio1 = $p['precio'];

            /* Separar parte entera del decimal */
            $explPreecio1 = explode(".", $precio1);
            /* convertir la parte decimal a una array */
            $precio_split1 = str_split($explPreecio1[1]);
            /* Guarda donde se detecta la ultima parte decimal */
            $flagdecimales1 = 2;
            /* Cuenta las posisiones del array recorridas */
            $count1 = 0;

            foreach ($precio_split1 as $numero1) {
                $count1++;

                if ($numero1 == 0) {
                } else {
                    /* Si el numero que encontro no es 0 guarda la posision en la posision de decimal */
                    $flagdecimales1 = $count1;
                }
            }
            if ($flagdecimales1 > $decimas) {
                $decimas = $flagdecimales1;
            }
        }

        foreach ($array as $r) {
            $id = $r['id'];
            $idDetalle = $r['idDetalle'];
            $nombre = $r['nombre'];
            $clave = $r['clave'];

            if ($r['clave'] == '') {
                $producto = $r['nombre'];
            } else {
                $producto = $r['clave'] . ' - ' . $r['nombre'];
            }

            $cantidad = $r['cantidad'];

            if ($r['unidadMedida'] == '') {
                $unidadMedida = '(Sin unidad de medida)';
            } else {
                $unidadMedida = $r['unidadMedida'];
            }

            $precio = $r['precio'];
            $importe = $r['importe'];
            $impuestos = $r['impuestos'];
            $minima = $r['minima'];

            /* Mostrar solo los decimales usados */
            /*  */  /*  */ /*  */ /*  */ /*  */ /*  */
            /* Separar parte entera del decimal */
            $explPreecio = explode(".", $precio);
            /* convertir la parte decimal a una array */
            $precio_split = str_split($explPreecio[1]);
            /* Guarda donde se detecta la ultima parte decimal */
            $flagdecimales = 2;
            /* Cuenta las posisiones del array recorridas */
            $count = 0;

            foreach ($precio_split as $numero) {
                $count++;

                if ($numero == 0) {
                } else {
                    /* Si el numero que encontro no es 0 guarda la posision en la posision de decimal */
                    $flagdecimales = $count;
                }
            }
            /* Convierte a decimal tomando en cuenta los decimales encontrados */
            $precio = number_format($precio, $decimas, ".", "");
            $importe = number_format($importe, $decimas, ".", ",");

            $existencias = $r['existencia'];

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';
            $acciones = '<i class=\"fas fa-trash-alt\" onclick=\"obtenerIdVentaDirectaEditEliminar(' . $idDetalle . ');\"></i>';

            $cantidadEditable = ' <div class=\"input-group\"><input class=\"form-control textTable border-0\" type=\"number\" value=\"' . $cantidad . '\" onchange=\"validarCantidad(' . $idDetalle . ');\" id=\"cantidad-' . $idDetalle . '\"> <input type=\"hidden\" value=\"' . $minima . '\" id=\"cantidadHis-' . $idDetalle . '\"> <i><img src=\"../../../../img/timdesk/alerta.svg\" style=\"display: none;\" id=\"notaCantidad-' . $idDetalle . '\" width=30px></i></div>';
            $precioEditable = ' <div class=\"input-group\"><input class=\"form-control textTable border-0\" type=\"number\" value=\"' . $precio . '\" onchange=\"validarCantidad(' . $idDetalle . ');\" id=\"precio-' . $idDetalle . '\"> <i><img src=\"../../../../img/timdesk/alerta.svg\" style=\"display: none;\" id=\"notaPrecio-' . $idDetalle . '\" width=30px></i></div>';
            $table .= '{"Id":"' . $etiquetaI . $idDetalle . $etiquetaF . '",
                "Producto":"' . $etiquetaI . $producto . $etiquetaF . '",
                "Cantidad":"' . $cantidadEditable . '",
                "UnidadMedida":"' . $etiquetaI . $unidadMedida . $etiquetaF . '",
                "PrecioUnitario":"' . $etiquetaI ./*  '$' .  */ $precioEditable . $etiquetaF . '",
                "Impuestos":"' . $etiquetaI . $impuestos . $etiquetaF . '",
                "Importe":"' . '$ ' . $etiquetaI . $importe . $etiquetaF . '",
                "Existencias":"' . $etiquetaI . $existencias . $etiquetaF . '",
                "Acciones":"' . $acciones . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getVentaDirectaTableVer($pkOrden)
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";

        $query = sprintf('call spc_Tabla_VentasDirectas_ConsultaEditVer(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkOrden));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id = $r['id'];
            $idDetalle = $r['idDetalle'];
            $nombre = $r['nombre'];
            $clave = $r['clave'];

            if ($r['clave'] == '') {
                $producto = str_replace('"', '\"', $r['nombre']);
            } else {
                $producto = str_replace('"', '\"', $r['clave'] . ' - ' . $r['nombre']);
            }

            $cantidad = $r['cantidad'];

            if ($r['unidadMedida'] == '') {
                $unidadMedida = '(Sin unidad de medida)';
            } else {
                $unidadMedida = $r['unidadMedida'];
            }

            $precio = $r['precio'];
            $importe = $r['importe'];
            $impuestos = $r['impuestos'];
            $minima = $r['minima'];

            $precio = number_format($precio, 2, ".", ",");
            $importe = number_format($importe, 2, ".", ",");
            $existencias = $r['existencia'];

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';
            $acciones = '';

            $cantidadEditable = ' <div class=\"input-group\"><span>' . $cantidad . '</span>';

            $table .= '{"Id":"' . $etiquetaI . $idDetalle . $etiquetaF . '",
                "Producto":"' . $etiquetaI . $producto . $etiquetaF . '",
                "Cantidad":"' . $cantidadEditable . '",
                "UnidadMedida":"' . $etiquetaI . $unidadMedida . $etiquetaF . '",
                "PrecioUnitario":"' . $etiquetaI . '$ ' . $precio . $etiquetaF . '",
                "Impuestos":"' . $etiquetaI . $impuestos . $etiquetaF . '",
                "Importe":"' . $etiquetaI . '$ ' . $importe . $etiquetaF . '",
                "Existencias":"' . $etiquetaI . $existencias . $etiquetaF . '",
                "Acciones":"' . $acciones . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }
    /////////////////////////COMBOS//////////////////////////////
    public function getClienteCombo()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_Clientes_VentaDirecta(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getProductoCombo($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        $query = sprintf("call spc_Combo_Productos_VentaDirecta(?,?)");
        $stmt = $db->prepare($query);
        $stmt->execute(array($value, $PKuser));

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getProductoComboEdit($value, $pkVenta)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        $query = sprintf("call spc_Combo_Productos_VentaDirectaEdit(?,?,?)");
        $stmt = $db->prepare($query);
        $stmt->execute(array($value, $PKuser, $pkVenta));

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getTodosProductosCombo($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];
        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf("call spc_Combo_Productos_VentaDirectaAll(?,?,?)");

        $stmt = $db->prepare($query);
        $stmt->execute(array($value, $PKEmpresa, $PKuser));

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getTodosProductosComboEdit($value, $pkVenta)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];
        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf("call spc_Combo_Productos_VentaDirectaAllEdit(?,?,?,?)");

        $stmt = $db->prepare($query);
        $stmt->execute(array($value, $PKEmpresa, $PKuser, $pkVenta));

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getSucursalCombo()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_Sucursales_VentaDirecta(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCmbVendedor()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_Vendedor(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbRegimen()
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Combo_Regimen()');
        $stmt = $db->prepare($query);
        $stmt->execute();
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbEstados($PKPais)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('SELECT PKEstado, Estado FROM estados_federativos WHERE FKPais = ?');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKPais));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbMedioContacto()
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Combo_MediosContactoCliente(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($_SESSION['IDEmpresa']));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function resetProducts($pkVentaDirecta)
    {
        $con = new conectar();
        $db = $con->getDb();

        /* Reset a los productos preeliminados */

        /* SELECCIONAMOS LOS USUARIOS DE TIPO ALMACEN */
        $stmt = $db->prepare("UPDATE detalle_venta_directa SET estatus=1 WHERE FKVentaDirecta = :PkVenta");
        $stmt->execute(array('PkVenta' => $pkVentaDirecta));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function clearEdicionestemp($pkVentaDirecta)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call clear_edicionesTemp(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkVentaDirecta));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $array;
    }

    public function getCmbCondicionPago()
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Combo_CondicionPago()');
        $stmt = $db->prepare($query);
        $stmt->execute();
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbDireccionesEnvio($pkCliente)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf("call spc_Combo_Clientes_DireccionesEnvio(?)");
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkCliente));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);
        $array[]=$_SESSION['IDEmpresa'];
        return $array;
    }
    /////////////////////////DATOS PARA EDICIÓN//////////////////////////////
    public function getDatosVentaDirectaEdit($PKVentaDirecta)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Datos_VentaDirecta_General(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKVentaDirecta));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getRelationTicketSale($PKVentaDirecta)
    {   
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf("select * from relacion_tickets_ventas where venta_id = :id");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id",$PKVentaDirecta);
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function getDatosVentaDirectaPDF($PKVentaDirecta, $PKUsuario)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Datos_VentaDirecta_PDF(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKVentaDirecta, $PKUsuario));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDatosImpuVentaDirectaPDF($PKVentaDirecta)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Info_VentaDirecta_ImpuestosPDF(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKVentaDirecta));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDatosProdVentaDirectaPDF($PKVentaDirecta)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Info_VentaDirecta_ProductosPDF(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKVentaDirecta));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }
    /////////////////////////VALIDACIONES//////////////////////////////
    public function validarProductoVentaDirecta($pkProducto, $pkUsuario, $pkCliente)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_ValidarUnicoProductoVentaDirecta(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkProducto, $pkUsuario, $pkCliente));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarProductoVentaDirectaEdit($pkProducto, $pkVentaDirecta, $pkCliente)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_ValidarUnicoProductoVentaDirectaEdit(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkProducto, $pkVentaDirecta, $pkCliente));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarSucursalInventario($pkSucursal)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_ValidarSucursalInventarioAct(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkSucursal));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarEstadoVentaDirecta($pkVenta)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_ValidarEstadoVentaDirecta(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkVenta));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarPermisos($pkPantalla)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["Usuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        }

        $query = sprintf('call spc_Validar_Permisos(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKuser, $pkPantalla));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }
    /////////////////////////INFO//////////////////////////////
    public function getReferencia()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Info_VentaDirecta_Referencia(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        $VDReferencia = $stmt->fetch();

        return $VDReferencia['PKVentaDirecta'];
    }

    public function getFechaEmision()
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Info_VentaDirecta_FechaEmision()');
        $stmt = $db->prepare($query);
        $stmt->execute();
        $FechaEmision = $stmt->fetch();
        $cantidadRegistros = $stmt->rowCount();
        if ($cantidadRegistros > 0) {
            $Fecha = $FechaEmision['FechaEmision'];
        } else {
            $Fecha = "Error al obtener la fecha";
        }
        return $Fecha;
    }

    public function getFechaVencimientoMin()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Info_Venta_FechaVencimientoMin(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        $FechaVencimientoMin = $stmt->fetch();
        $cantidadRegistros = $stmt->rowCount();
        if ($cantidadRegistros > 0) {
            $Fecha = $FechaVencimientoMin['FechaVencimientoMin'];
        } else {
            $Fecha = "Error al obtener la fecha";
        }
        return $Fecha;
    }

    public function getSubTotalVentaDirectaTemp($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Info_VentaDirectaTemp_Subtotal(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getSubTotalVentaDirectaEdit($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $usuario = $_SESSION['PKUsuario'];
        $query = sprintf('call spc_Info_VentaDirectaEdit_Subtotal(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value, $usuario));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        /* foreach($array as $a){
            $array[$a['Subtotal']] = $Subtotal;
        } */

        return $array;
    }
    public function getSubTotalVentaDirectaVer($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $usuario = $_SESSION['PKUsuario'];
        $query = sprintf('call spc_Info_VentaDirectaVer_Subtotal(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value, $usuario));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getImpuestoVentaDirectaTemp($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Info_VentaDirectaTemp_Impuestos(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getImpuestoVentaDirectaEdit($value, $isEdit)
    {
        $con = new conectar();
        $db = $con->getDb();

        $pkusuario = $_SESSION['PKUsuario'];
        $query = sprintf('call spc_Info_VentaDirectaEdit_Impuestos(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value, $isEdit, $pkusuario));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }
    public function getImpuestoVentaDirectaEdit_v2($value, $isEdit)
    {
        $con = new conectar();
        $db = $con->getDb();
        $pkusuario = $_SESSION['PKUsuario'];

        if ($isEdit == 0) {
            ///Consultar el IEPS de los productos de la venta Array(FKProducto => Total IEPS (Monto fijo o tasa) ) 
            $stmtIEPS = $db->prepare(" SELECT ((if(i.FKTipoImporte = 2,(dvd.Cantidad * ivd.Tasa),((dvd.Cantidad * dvd.Precio) * (ivd.Tasa / 100)))) ) as TotalIEPS, dvd.FKProducto as id from 
        ventas_directas vd 
        LEFT JOIN detalle_venta_directa dvd on vd.PKVentaDirecta = dvd.FKVentaDirecta
        LEFT JOIN productos p on dvd.FKProducto = p.PKProducto 
        LEFT JOIN impuestos_venta_directa ivd on vd.PKVentaDirecta = ivd.FKVentaDirecta and dvd.FKProducto = ivd.FKProducto
        left join impuesto i on ivd.FKImpuesto = i.PKImpuesto
        where (vd.PKVentaDirecta = :idVentaDirecta) and (ivd.Impuesto = 'IEPS' or ivd.Impuesto = 'IEPS (Monto fijo)') ;");
            $stmtIEPS->execute(array(':idVentaDirecta' => $value));
            $rowIEPS = $stmtIEPS->fetchAll();
        } else {
            $stmtIEPS = $db->prepare(" SELECT ((if(i.FKTipoImporte = 2,((SELECT IFNULL(edv.cantidad,dvd.Cantidad)) * ivd.Tasa),(((SELECT IFNULL(edv.cantidad,dvd.Cantidad)) * IFNULL(edv.precio,dvd.Precio)) * (ivd.Tasa / 100)))) ) as TotalIEPS, dvd.FKProducto as id from 
            ventas_directas vd 
            LEFT JOIN detalle_venta_directa dvd on vd.PKVentaDirecta = dvd.FKVentaDirecta
            LEFT JOIN edicion_detalle_venta edv on edv.FKDetalleVentaDirecta = dvd.PKDetalleVentaDirecta
            LEFT JOIN productos p on dvd.FKProducto = p.PKProducto 
            LEFT JOIN impuestos_venta_directa ivd on vd.PKVentaDirecta = ivd.FKVentaDirecta and dvd.FKProducto = ivd.FKProducto
            left join impuesto i on ivd.FKImpuesto = i.PKImpuesto
            where (vd.PKVentaDirecta = :idVentaDirecta) and (ivd.Impuesto = 'IEPS' or ivd.Impuesto = 'IEPS (Monto fijo)') 
            UNION
		 select if(i.FKTipoImporte = 2,vdt.Cantidad * ip.Tasa,(vdt.Cantidad * vdt.Precio) * (ip.Tasa / 100)) as TotalIEPS2, vdt.FKProducto
				from ventas_directas_temp vdt
					inner join productos p on vdt.FKProducto = p.PKProducto  
			inner join info_fiscal_productos ifp on p.PKProducto = ifp.FKProducto
            inner join impuestos_productos ip on ifp.PKInfoFiscalProducto = ip.FKInfoFiscalProducto
			inner join impuesto i on ip.FKImpuesto = i.PKImpuesto
			inner join tipos_impuestos ti on i.FKTipoImpuesto = ti.PKTipoImpuesto
            left join costo_especial_producto_cliente cepc on p.PKProducto = cepc.FKProducto and vdt.FKCliente = cepc.FKCliente
            left join costo_venta_producto cvp on p.PKProducto = cvp.FKProducto
					where vdt.FKUsuario = :usr and (i.Nombre = 'IEPS' or i.Nombre = 'IEPS (Monto fijo)') ;");
            $stmtIEPS->execute(array(':idVentaDirecta' => $value, ':usr' => $pkusuario));
            $rowIEPS = $stmtIEPS->fetchAll();
        }





        //Consulta los impuestos de la venta
        $pkusuario = $_SESSION['PKUsuario'];
        $query = sprintf('call spc_Info_VentaDirectaEdit_Impuestos(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value, $isEdit, $pkusuario));
        $array = $stmt->fetchAll();


        if (!empty($rowIEPS)) {
            //Recorre el arreglo de IEPS de productos
            foreach ($rowIEPS as $result) {
                $count = 0;
                ///Recorre el array de impuestos de productos de la venta
                foreach ($array as $impuestos) {
                    /// Si el producto en ambos arreglos es igual y el tipo de impuesto en el array impuestos es un IVA
                    if (($result['id'] == $impuestos['id']) && $impuestos['nombre'] == "IVA") {
                        /// El Total Impuesto es igual a lo que ya tenia mas el iva el ieps de ese producto ya sea en tasa o en cuota
                        $impuestos['totalImpuesto'] = floatval($impuestos['totalImpuesto']) + (floatval($result['TotalIEPS']) * (floatval($impuestos['tasa']) / 100));
                        $array[$count]['totalImpuesto'] = $impuestos['totalImpuesto'];
                    }


                    $count++;
                }
            }
        }

        ///Agrupar los impuestos 
        $result = array_reduce($array, function ($carry, $item, $count = 0) {
            $count++;
            ///Quita los productos que no tienen ningun impuesto
            if ($item['id'] == null) {
            } else {
                ///se recorre el array, si el array aux carry no contiene el nombre del impuesto lo crea.
                if (!isset($carry[$item['nombre']])) {
                    $carry[$item['nombre']] = ['nombre' => $item['nombre'], 'totalImpuesto' => $item['totalImpuesto'], 'tasa' => $item['tasa'], 'id' => $item['id'], 'tipo' => $item['tipo'], 'tipo' => $item['tipo'], 'tipoImp' => $item['tipoImp']];
                } else {
                    ///Si ya lo tiene
                    ////Si la tasa del impuesto del mismo nombre que contiene $carry es igual a la del nuevo de array suma los totales impuestos. 
                    if ($carry[$item['nombre']]['tasa'] == $item['tasa']) {
                        $carry[$item['nombre']]['totalImpuesto'] += $item['totalImpuesto'];
                    } else {
                        ///Si es una tasa diferente crea un nuevo array concatenando el contador para que sea una posision del result diferente
                        $carry[$item['nombre'] . $count] = ['nombre' => $item['nombre'], 'totalImpuesto' => $item['totalImpuesto'], 'tasa' => $item['tasa'], 'id' => $item['id'], 'tipo' => $item['tipo']];
                    }
                }
            }

            return $carry;
        });

        return $result;
    }

    public function getTotalVentaDirectaTemp($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Info_VentaDirectaTemp_Total(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getTotalVentaDirectaEdit($value, $isEdit)
    {
        $con = new conectar();
        $db = $con->getDb();
        $usuario = $_SESSION['PKUsuario'];
        $query = sprintf('call spc_Info_VentaDirectaEdit_Total(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value, $isEdit, $usuario));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getPrecioCliente($value, $value1, $value2)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf("call spc_Info_VentaDirecta_PrecioProductoCliente(?,?,?)");
        $stmt = $db->prepare($query);
        $stmt->execute(array($value, $value1, $value2));

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getInventarioSucursal($pkSucursal, $pkProducto)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf("call spc_Info_VentaDirecta_StockInvenSuc(?,?)");
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkSucursal, $pkProducto));

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getVendedorCliente($pkCliente)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf("call spc_Datos_Clientes_Vendedor(?)");
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkCliente));

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getDireccionesEnviosCliente($pkCliente)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION['IDEmpresa'];

        $query = sprintf("call spc_Datos_Clientes_DireccionesEnvioPred(?,?)");
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkCliente, $PKEmpresa));

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    //END JAVIER RAMIREZ

    public function getProductsStock($value,$value1)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf("select id, existencia, numero_lote,clave_producto from existencia_por_productos where producto_id = :id and sucursal_id = :sucursal_id order by id asc");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id",$value);
        $stmt->bindValue(":sucursal_id",$value1);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}

class save_data
{
    //JAVIER RAMIREZ
    public function saveVentaDirectaTemp($idproducto, $cantidad, $pkUsuario, $PKCliente, $precio, $precioEsp, $randomID)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('call spi_VentaDirecta_Temp_Agregar(?,?,?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($idproducto, $cantidad, $pkUsuario, $PKCliente, $precio, $precioEsp, $randomID));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveVentaDirectaEdit($idproducto, $cantidad, $pkVentaDirecta, $PKCliente, $precio, $precioEsp, $randomID)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spi_VentaDirecta_Edit_Agregar(?,?,?,?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($idproducto, $cantidad, $pkVentaDirecta, $PKCliente, $precio, $precioEsp, $PKuser, $randomID));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveVentaDirecta($referencia, $fechaEmision, $fechaVencimiento, $cliente, $sucursal, $importe, $pkUsuario, $notasInternas, $notasCliente, $vendedor, $moneda, $subtotal, $direccionEnvioCliente, $condicionPago, $idrandomOp,$afectar_inventario)
    {
        $con = new conectar();
        $db = $con->getDb();
        $db1 = $con->getDb();
        $get_data = new get_data();
        $save_data = new save_data();
        $update_data = new edit_data();
        $noti = new notificaciones();

        $pkOrden = 0;
        $PKEmpresa = $_SESSION["IDEmpresa"];

        try {
            $query = sprintf('call spi_VentaDirecta_Agregar(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($cliente, $sucursal, $importe, $fechaEmision, $fechaVencimiento, $pkUsuario, $pkOrden, $notasInternas, $notasCliente, $PKEmpresa, $vendedor, $moneda, $subtotal, $direccionEnvioCliente, $condicionPago, $idrandomOp, $afectar_inventario));
            $array = $stmt->fetchAll();
             
            //foreach ($array as $r) {
            $PKVentaDirecta = $array[0]['PKVenta'];
            $idPedido = $array[0]['idPedido'];
            $folio = $array[0]['folio_pedido'];
            $stock_active = $array[0]['stock_active'];
            //}

            if((int)$afectar_inventario === 1){
                if((int) $stock_active === 1){
                    $query = sprintf("select p.PKProducto as id,dop.cantidad_pedida,ClaveInterna as clave from detalle_orden_pedido_por_sucursales as dop 
                                        inner join productos as p on dop.producto_id = p.PKProducto
                                    where dop.orden_pedido_id = :id");
                    $stmt = $db1->prepare($query);
                    $stmt->bindValue(":id",$idPedido);
                    $stmt->execute();
                    $prod = $stmt->fetchAll(PDO::FETCH_OBJ);
                    $resto = 0;
                    $arr = [];
                    $arr1 = [];
                    $lotes = [];
                    
                    for($i = 0; $i < count($prod); $i++){
                        $cantidad = $prod[$i]->cantidad_pedida;
                        $arr = $get_data->getProductsStock($prod[$i]->id,$sucursal);
                        $arr1 = $get_data->getProductsStock($prod[$i]->id,$sucursal);
                        
                        for($j = 0; $j < count($arr); $j++){
                
                            if((double)$cantidad > 0){
                            
                            if((double)$arr[$j]->existencia >= (double)$cantidad){
                                (double)$resto += (double)$cantidad - (double)$resto;
                                
                                (double)$arr[$j]->existencia = (double)$arr[$j]->existencia - (double)$resto;
                                (double)$cantidad = (double)$cantidad - (double)$resto;
                            } else {
                                (double)$resto += (double)$arr[$j]->existencia - (double)$resto;
                                (double)$cantidad = (double)$cantidad - (double)$arr[$j]->existencia;
                                (double)$arr[$j]->existencia = (double)$arr[$j]->existencia - (double)$resto;
                                
                            }
                            }
                        }
                        
                        for ($j=0; $j < count($arr); $j++) { 
                            if((double)$arr[$j]->existencia !== (double)$arr1[$j]->existencia)
                            {
                                (double)$cantidad = (double)$arr1[$j]->existencia - (double)$arr[$j]->existencia;
                                $lotes[] = ["clave_producto"=>$arr[$j]->clave_producto,"lote"=>$arr[$j]->numero_lote,"cantidad"=>(double)$cantidad];
                            }
                            
                            $query = sprintf("UPDATE existencia_por_productos set existencia = :stock where id = :id");
                            $stmt = $db1->prepare($query);
                            $stmt->bindValue(":stock",(double)$arr[$j]->existencia);
                            $stmt->bindValue(":id",$arr[$j]->id);
                            $stmt->execute();
                        }
                    }

                    for ($i=0; $i < count($lotes); $i++) { 
                        $save_data->saveSalida($lotes[$i]['clave_producto'],$lotes[$i]['lote'],(double)$lotes[$i]['cantidad'],$array[0]['folio_pedido'] . "-1",$idPedido,$sucursal);
                    }
                }
                $update_data->updateVentaEstatusInventario($PKVentaDirecta);
                $update_data->updateVentaPedidoEstatusInventario($PKVentaDirecta);
            }
           
            $data[0] = ['status' => $status, 'id' => $PKVentaDirecta, 'idPedido' => $idPedido];
            $noti->setnotification($idPedido);

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

  function saveSalida($clave,$lote,$cantidad,$folio,$pedido,$sucursal)
  {
    $con = new conectar();
    $db = $con->getDb();

    try {
      $db->beginTransaction();

      $query = sprintf("
          insert into inventario_salida_por_sucursales 
              (
                  clave,
                  numero_lote,
                  cantidad,
                  fecha_salida,
                  folio_salida,
                  cantidad_facturada,
                  tipo_salida,
                  orden_pedido_id,
                  usuario_creo_id,
                  sucursal_id,
                  estatus
              ) values (
                  :clave,
                  :numero_lote,
                  :cantidad,
                  now(),
                  :folio_salida,
                  :cantidad_facturada,
                  :tipo_salida,
                  :orden_pedido_id,
                  :usuario_creo_id,
                  :sucursal_id,
                  :estatus
              )
      ");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":clave",$clave);
      $stmt->bindValue(":numero_lote",$lote);
      $stmt->bindValue(":cantidad",$cantidad);
      $stmt->bindValue(":folio_salida",$folio);
      $stmt->bindValue(":cantidad_facturada",$cantidad);
      $stmt->bindValue(":tipo_salida",1);
      $stmt->bindValue(":orden_pedido_id",$pedido);
      $stmt->bindValue(":usuario_creo_id",$_SESSION['PKUsuario']);
      $stmt->bindValue(":sucursal_id",$sucursal);
      $stmt->bindValue(":estatus",2);
      $stmt->execute();
      $estatus = $db->commit();
      return ["estatus"=>$estatus];
    } catch(PDOException $e) {
        $db->rollback();
        return "Error: " . $e->getMessage();
    }
  }

    public function saveProd_VentaDirecta($nombre, $clave, $tipo, $cliente, $is_from_Facturacion, $claveSat, $unidadSat, $existencia, $idSucursal, $idImpuestosArray, $tasaImpuestosArray)
    {
        $con = new conectar();
        $db = $con->getDb();

        $pkUsuario = $_SESSION["PKUsuario"];
        $PKEmpresa = $_SESSION["IDEmpresa"];

        try {
            $query = sprintf('SELECT PKMarcaProducto 
                            FROM marcas_productos 
                            where empresa_id = :id_empresa and estatus = 1 
                            order by rand() limit 1 ');
            $stmt = $stmt = $db->prepare($query);
            $stmt->bindValue(":id_empresa",$PKEmpresa);
            $status = $stmt->execute();
            $fkMarcaProducto = $stmt->fetch()['0'];

            if($tipo == 10){
                $gastoFijo = 1;
            }else{
                $gastoFijo = 0;
            }

            $query = sprintf('call spi_prod_agregar_from_ventas(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($nombre, strtoupper($clave), null, 1, $fkMarcaProducto, null, $tipo, 1, null, $pkUsuario, 0, 1, 0, 1, 100, null, 100, null, 100, $PKEmpresa, 0, 0, 0, $cliente, 1, 100, 1, 100, $is_from_Facturacion, $claveSat, $unidadSat, $gastoFijo));
            $array = $stmt->fetchAll();
            $idProducto = $array[0][0];
            $stmt->closeCursor();

            $con = new conectar();
            $db = $con->getDb();

            if($idSucursal != null && $idSucursal != 0){

                if(trim($existencia) != ''){

                    $query = sprintf('SELECT activar_inventario FROM sucursales WHERE id = ?');
                    $stmt = $db->prepare($query);
                    $stmt->execute(array($idSucursal));
                    $inventario = $stmt->fetch();

                    if($inventario['activar_inventario'] == 1){
                        $query = sprintf('INSERT INTO existencia_por_productos (existencia_minima, existencia_maxima, punto_reorden, numero_lote, numero_serie, caducidad, existencia, sucursal_id, producto_id, clave_producto) VALUES (0,0,0,"","","0000-00-00",?,?,?,?)');
                        $stmt = $db->prepare($query);
                        $stmt->execute(array($existencia, $idSucursal, $idProducto, $clave));
                    }
                }
            }

            if($is_from_Facturacion == 0){

                $query = sprintf('insert into info_fiscal_productos ( FKClaveSAT, FKProducto, FKClaveSATUnidad )  VALUES (?,?,?)');
                $stmt = $db->prepare($query);
                $stmt->execute(array(1, $idProducto, $unidadSat));
                $idInfoFiscal = $db->lastInsertId();    

                $contador = 0;
                $cantidadImp = count($idImpuestosArray);

                if($cantidadImp > 0){

                    $arrayTasas = array();

                    foreach($tasaImpuestosArray as $tas){
                        $arrayTasas[$contador] = $tas;
                        $contador++;
                    }

                    $contador = 0;
                    foreach($idImpuestosArray as $imp){

                        $query = sprintf('insert into impuestos_productos ( FKInfoFiscalProducto, FKImpuesto, Tasa )  VALUES (?,?,?)');
                        $stmt = $db->prepare($query);
                        $stmt->execute(array($idInfoFiscal, $imp, $arrayTasas[$contador]));

                        $contador++;
                        
                    }    
                }    
            }

            if($is_from_Facturacion == 1){

                $query = sprintf('SELECT PKInfoFiscalProducto FROM info_fiscal_productos WHERE FKProducto = ?');
                $stmt = $db->prepare($query);
                $stmt->execute(array($idProducto));
                $infoFiscal = $stmt->fetch();
                $idInfoFiscal = $infoFiscal['PKInfoFiscalProducto'];

                $contador = 0;
                $cantidadImp = count($idImpuestosArray);

                if($cantidadImp > 0){

                    $arrayTasas = array();

                    foreach($tasaImpuestosArray as $tas){
                        $arrayTasas[$contador] = $tas;
                        $contador++;
                    }

                    $contador = 0;
                    foreach($idImpuestosArray as $imp){

                        $query = sprintf('insert into impuestos_productos ( FKInfoFiscalProducto, FKImpuesto, Tasa )  VALUES (?,?,?)');
                        $stmt = $db->prepare($query);
                        $stmt->execute(array($idInfoFiscal, $imp, $arrayTasas[$contador]));

                        $contador++;
                        
                    }    
                }   

            }
            
            
           
            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveCliente_VentaDirecta($nombreComercial, $medioContactoCliente, $vendedor, $montoCredito, $diasCredito, $telefono, $email, $estatus, $razonSocial, $rfc, $pais, $estado, $cp, $pkRazon, $regimen)
    {
        $con = new conectar();
        $db = $con->getDb();

        $pkCliente = '0';
        $PKuser = $_SESSION["PKUsuario"];
        $PKEmpresa = $_SESSION["IDEmpresa"];

        try {
            //si no tiene vendedor le asigna uno por defecto de la empresa
            if($vendedor == 0){
                $query = sprintf('SELECT 
                                    e.PKEmpleado  
                                from empleados e
                                    inner join relacion_tipo_empleado rte on e.PKEmpleado = rte.empleado_id
                                where rte.tipo_empleado_id = "1" and e.empresa_id = :empresa_id and e.is_generic = 0 and (e.estatus = 1)
                                order by rand() limit 1;');
                $stmt = $stmt = $db->prepare($query);
                $stmt->bindValue(":empresa_id",$PKEmpresa);
                $status = $stmt->execute();
                $vendedor = $stmt->fetch()['0'];
            }

            $query = sprintf('INSERT INTO clientes (NombreComercial, Telefono, Email, Monto_credito, Dias_credito, razon_social, rfc, codigo_postal, estatus_prospecto_id, pais_id, estado_id, empresa_id, medio_contacto_id, usuario_creacion_id, usuario_edicion_id, created_at, updated_at, estatus, empleado_id, regimen_fiscal_id)
            VALUES (?,?,?,?,?,?,?,?,4,?,?,?,?,?,?,(SELECT NOW()),(SELECT NOW()),?,?,?);');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array(strtoupper($nombreComercial), $telefono, $email, $montoCredito, $diasCredito, strtoupper($razonSocial), $rfc, $cp, $pais, $estado, $PKEmpresa, $medioContactoCliente, $PKuser, $PKuser, $estatus, $vendedor, $regimen));
            $PKCliente = $stmt->fetch()['0'];

            $data[0] = ['status' => $status, 'id' => $PKCliente];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveVentaCopyTableTemp($pkVentaDirecta, $randomID)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spi_VentaDirecta_Copy_Agregar(?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($pkVentaDirecta, $PKuser, $randomID));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }
    //END JAVIER RAMIREZ
}

class edit_data
{
    //JAVIER RAMIREZ
    public function editVentaDirectaTemp($idproducto, $cantidad, $pkUsuario, $pkCliente, $newPrecio)
    {
        $con = new conectar();
        $db = $con->getDb();
        try {
            $query = sprintf('call spu_VentaDirecta_Temp_Actualizar(?,?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($idproducto, $cantidad, $pkUsuario, $pkCliente, $newPrecio));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editVentaDirectaEdit($idproducto, $cantidad, $pkVentaDirecta, $pkCliente)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_VentaDirecta_Edit_Actualizar(?,?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($idproducto, $cantidad, $pkVentaDirecta, $pkCliente, $PKuser));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editVentaDirectaTempCantidad($idVentaTemp, $cantidad, $precio)
    {
        $con = new conectar();
        $db = $con->getDb();
        try {
            $query = sprintf('call spu_VentaDirecta_Temp_ActualizarCantidad(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($idVentaTemp, $cantidad, $precio));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editVentaDirectaEditCantidad($idVentaDetalle, $cantidad, $precio)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_VentaDirecta_Edit_ActualizarCantidad(?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($idVentaDetalle, $cantidad, $precio, $PKuser));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editVentaDirecta($referencia, $fechaEmision, $fechaVencimiento, $cliente, $sucursal, $importe, $pkVentaDirecta, $notasInternas, $notasCliente, $vendedor, $moneda, $subtotal, $direccionEnvioCliente, $condicionPago, $IdOprdm)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];
        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_VentaDirecta_Actualizar(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($referencia, $PKEmpresa, $cliente, $sucursal, $importe, $fechaEmision, $fechaVencimiento, $pkVentaDirecta, $notasInternas, $notasCliente, $PKuser, $vendedor, $moneda, $subtotal, $direccionEnvioCliente, $condicionPago, $IdOprdm));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editEstatusVentaDirecta($PKVentaDirecta, $Estado)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_VentaDirecta_ActualizarEstado(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKVentaDirecta, $Estado, $PKuser));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editVentaDirectaFD($PKVentaDirecta, $isFD)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_VentaDirecta_OrdenPedido_FD(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKVentaDirecta, $isFD, $PKuser));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function updateVentaEstatusInventario($PKVentaDirecta)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf("update ventas_directas set FKEstatusVenta = 6 where PKVentaDirecta = :id");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id",$PKVentaDirecta);
        $stmt->execute();
    }

    public function updateVentaPedidoEstatusInventario($PKVentaDirecta)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf("update orden_pedido_por_sucursales set estatus_orden_pedido_id = 6 where numero_venta_directa = :id");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id",$PKVentaDirecta);
        $stmt->execute();
    }
    //END JAVIER RAMIREZ
}

class delete_data
{
    //JAVIER RAMIREZ
    public function deleteVentaDirectaTempAll($PKUsuario)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('call spd_EliminarVentaDirectaTempAll(?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKUsuario));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function deleteVentaDirectaTemp($PKVentaDirectaTemp)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('call spd_EliminarVentaDirectaTemp(?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKVentaDirectaTemp));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function deleteVentaDirectaEdit($PKDetalleVentaDirecta, $PKVenta)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spd_EliminarVentaDirectaEdit(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKVenta, $PKDetalleVentaDirecta, $PKuser));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function deleteVentaDirecta($PKVentaDirecta)
    {
        $con = new conectar();
        $db = $con->getDb();
        $delete_data = new delete_data();
        $PKuser = $_SESSION["PKUsuario"];
        
        try {
            $delete_data->deleteStockVenta($PKVentaDirecta);
            
            $query = sprintf('call spd_EliminarVentaDirecta(?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKVentaDirecta, $PKuser));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function deleteStockVenta($PKVentaDirecta)
    {
        
        $con1 = new conectar();
        $db1 = $con1->getDb();

        $query0 = sprintf("select s.id salida_id, s.clave, s.numero_lote, s.cantidad, p.id pedido_id,s.sucursal_id from ventas_directas v
                            inner join orden_pedido_por_sucursales p on v.PKVentaDirecta = p.numero_venta_directa
                            inner join inventario_salida_por_sucursales s on p.id = s.orden_pedido_id
                        where v.PKVentaDirecta = :id");
        $stmt0 = $db1->prepare($query0);
        $stmt0->bindValue(":id",$PKVentaDirecta);
        $stmt0->execute();
        $arr = $stmt0->fetchAll(PDO::FETCH_OBJ);

        if(count($arr) > 0)
        {
            foreach($arr as $r)
            {
                $query1 = sprintf("update existencia_por_productos e set 
                        e.existencia = (e.existencia + :cant)
                      where e.clave_producto = :clave and e.sucursal_id = :sucursal and e.numero_lote = :lote");
                $stmt1 = $db1->prepare($query1);
                $stmt1->bindValue(":cant",$r->cantidad);
                $stmt1->bindValue(":clave",$r->clave);
                $stmt1->bindValue(":sucursal",$r->sucursal_id);
                $stmt1->bindValue(":lote",$r->numero_lote);
                $stmt1->execute();

            }

            $query2 = sprintf("delete from inventario_salida_por_sucursales where orden_pedido_id = :id");
            $stmt2 = $db1->prepare($query2);
            $stmt2->bindValue(":id", $arr[0]->pedido_id);
            $stmt2->execute();

            $query3 = sprintf("delete from orden_pedido_por_sucursales where id = :id");
            $stmt3 = $db1->prepare($query3);
            $stmt3->bindValue(":id", $arr[0]->pedido_id);
            $stmt3->execute();
        }
        
    }
    //END JAVIER RAMIREZ
}

class notificaciones
{

    public function setnotification($idelemento)
    {
        $con = new conectar();
        $db = $con->getDb();

        /* NOTIFICACIONES */
        $timestamp = date('Y-m-d H:i:s');
        /* SELECCIONAMOS LOS USUARIOS DE TIPO ALMACEN */
        $stmt = $db->prepare('SELECT id FROM usuarios WHERE empresa_id = :empresaId AND role_id = :roleId');
        $stmt->execute([':empresaId' => $_SESSION['IDEmpresa'], ':roleId' => 6]);
        $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($empleados as $empleado) {
            /* INSERTAMOS LA NOTIFICACION EN LA BD */
            $stmt = $db->prepare('INSERT INTO notificaciones (tipo_notificacion, detalle_tipo_notificacion, id_elemento, created_at, usuario_recibe) VALUES (:tipoNot, :detaleNot, :idElem, :fecha, :usrRecibe)');
            $stmt->execute([':tipoNot' => 6, ':detaleNot' => 13, ':idElem' => $idelemento, ':fecha' => $timestamp, ':usrRecibe' => $empleado['id']]);
        }
    }
}


function setLabels($fechaInic, $fechaFin)
{
    /* $fechaInic = $fechaInic == '' ? date('Y-m-d') : $fechaInic; */
    $labelsData = [];
    $labels = [];
    $fechaInic = strtotime($fechaInic);
    $fechaFin = strtotime($fechaFin);
    $fechaInic = date('Y-m-d', $fechaInic);
    $fechaFin = date('Y-m-d', $fechaFin);
    while ($fechaInic <= $fechaFin) {
        $fecha = setMont($fechaInic);
        array_push($labels, $fecha);
        $labelsData[$fecha] = 0;
        $fechaInic = date('Y-m-d', strtotime("+1 months", strtotime($fechaInic)));
    }
    return ['labels' => $labels, 'labelsData' => $labelsData];
}

function setLabelsM($arrMes)
{
    $labelsData = [];
    $labels = [];
    foreach ($arrMes as $mes) {
        $fecha = date("Y-" . $mes . "-01");
        $fecha = setMont($fecha);
        array_push($labels, $fecha);
        $labelsData[$fecha] = 0;
    }
    return ['labels' => $labels, 'labelsData' => $labelsData];
}

function setMont($date)
{
    $fecha = '';
    switch (date('m', strtotime($date))) {
        case '01':
            $fecha = 'Enero - ' . date('Y', strtotime($date));
            break;
        case '02':
            $fecha = 'Febrero - ' . date('Y', strtotime($date));
            break;
        case '03':
            $fecha = 'Marzo - ' . date('Y', strtotime($date));
            break;
        case '04':
            $fecha = 'Abril - ' . date('Y', strtotime($date));
            break;
        case '05':
            $fecha = 'Mayo - ' . date('Y', strtotime($date));
            break;
        case '06':
            $fecha = 'Junio - ' . date('Y', strtotime($date));
            break;
        case '07':
            $fecha = 'Julio - ' . date('Y', strtotime($date));
            break;
        case '08':
            $fecha = 'Agosto - ' . date('Y', strtotime($date));
            break;
        case '09':
            $fecha = 'Septiembre - ' . date('Y', strtotime($date));
            break;
        case '10':
            $fecha = 'Octubre - ' . date('Y', strtotime($date));
            break;
        case '11':
            $fecha = 'Noviembre - ' . date('Y', strtotime($date));
            break;
        case '12':
            $fecha = 'Diciembre - ' . date('Y', strtotime($date));
            break;
        default:
            break;
    }
    return $fecha;
}

function rand_color()
{
    return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
}

function setCharts($chart, $labelsData, $filter)
{
    if ($filter === 'no-filters' || $filter === 'one-filter') {
        $objectResultOne = [];
        $arrayResultOne = [];
        $onjectResultTwo = [];
        $arrayResultTwo = [];
        $arrayNameOne = 'cliente';
        $arrayNameTwo = 'vendedor';
    } elseif ($filter === 'both-filters') {
        $objectResultOne = [];
        $arrayResultOne = [];
        $arrayNameOne = 'cliente';
    }
    
/*     print_r($labelsData);
    echo '<br>';
    print_r($chart);
    return; */
    /* print_r($chart);
    return; */
    foreach ($chart as $item) {
        $dataLabel = setMont(date('Y-m-d', strtotime($item['fecha'])));

        if (!array_key_exists($item[$arrayNameOne], $objectResultOne)) {
            $color = rand_color();
            $objectResultOne[$item[$arrayNameOne]] = ['label' => $item[$arrayNameOne], 'data' => $labelsData, 'borderColor' => $color, 'backgroundColor' => $color];
        }
        $objectResultOne[$item[$arrayNameOne]]['data'][$dataLabel] += (floatval($item['total']) + floatval($item['total_pendiente']));
                /* $objectResultOne[$item[$arrayNameOne]];
        $objectResultOne[$item[$arrayNameOne]]['data'][$dataLabel];
        $item['total']; */


        if ($filter === 'no-filters' || $filter === 'one-filter') {
            if (!array_key_exists($item[$arrayNameTwo], $onjectResultTwo)) {
                $color = rand_color();
                $onjectResultTwo[$item[$arrayNameTwo]] = ['label' => $item[$arrayNameTwo], 'data' => $labelsData, 'borderColor' => $color, 'backgroundColor' => $color];
            }
            $onjectResultTwo[$item[$arrayNameTwo]]['data'][$dataLabel] += (floatval($item['total']) + floatval($item['total_pendiente']));
        }
    }

    foreach ($objectResultOne as $item) {
        array_push($arrayResultOne, $item);
    }

    if ($filter === 'no-filters' || $filter === 'one-filter') {
        foreach ($onjectResultTwo as $item) {
            array_push($arrayResultTwo, $item);
        }
    }

    if ($filter === 'no-filters') {
        return ['clientes' => $arrayResultOne, 'vendedores' => $arrayResultTwo];
    } elseif ($filter === 'one-filter') {
        return ['clientes' => $arrayResultOne, 'vendedor' => $arrayResultTwo];
    } elseif ($filter === 'both-filters') {
        return ['cliente' => $arrayResultOne];
    }
}

function setChartsVededor($chart, $labelsData)
{
    $vendedorObj = [];
    $vendedorArray = [];
    $clientesObj = [];
    $clientesArray = [];
    foreach ($chart as $item) {
        $dataLabel = setMont(date('Y-m-d', strtotime($item['fecha'])));

        if (!array_key_exists($item['vendedor'], $vendedorObj)) {
            $color = rand_color();
            $vendedorObj[$item['vendedor']] = ['label' => $item['vendedor'], 'data' => $labelsData, 'borderColor' => $color, 'backgroundColor' => $color];
        }
        $vendedorObj[$item['vendedor']]['data'][$dataLabel] += floatval($item['total_global']);

        if (!array_key_exists($item['cliente'], $clientesObj)) {
            $color = rand_color();
            $clientesObj[$item['cliente']] = ['label' => $item['cliente'], 'data' => $labelsData, 'borderColor' => $color, 'backgroundColor' => $color];
        }
        $clientesObj[$item['cliente']]['data'][$dataLabel] += (floatval($item['total']) + floatval($item['total_pendiente']));
    }

    foreach ($vendedorObj as $item) {
        array_push($vendedorArray, $item);
    }

    foreach ($clientesObj as $item) {
        array_push($clientesArray, $item);
    }

    return ['vendedor' => $vendedorArray, 'clientes' => $clientesArray];
}

//$ejemplo = new get_data();
//$ejemplo->getReportesTable("todos", "todos", "2021-01-01", "2022-01-01");
//var_dump($ejemplo->getReportesTable("todos", "todos", "2021-01-01", "2022-01-01"));

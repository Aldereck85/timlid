<?php

use Stripe\Product;

session_start();
date_default_timezone_set('America/Mexico_City');
require_once('../../../../vendor/shuchkin/simplexlsxgen/src/SimpleXLSXGen.php');


class conectar
{
    public function getDb()
    {
        include "../../../../include/db-conn.php";
        return $conn;
    }
}

class Get_datas
{
    public function loadCmbClient()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_Clientes_Reportes(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));

        return $stmt->fetchALL(PDO::FETCH_OBJ);
    }

    public function loadCmbVendedor()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_Vendedor_Reportes(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));

        return $stmt->fetchALL(PDO::FETCH_OBJ);
    }

    public function loadCmbEstados()
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Estados_Reportes()');
        $stmt = $db->prepare($query);
        $stmt->execute(array());

        return $stmt->fetchALL(PDO::FETCH_OBJ);
    }

    public function loadCmbProductos()
    {
        $con = new conectar();
        $db = $con->getDb();
        $PKEmpresa = $_SESSION["IDEmpresa"];
        $query = sprintf('call spc_Combo_ProductosReportes(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));

        return $stmt->fetchALL(PDO::FETCH_OBJ);
    }

    public function loadCmbMarcas()
    {
        $con = new conectar();
        $db = $con->getDb();
        $PKEmpresa = $_SESSION["IDEmpresa"];
        $query = sprintf('call spc_Combo_MarcaReportes(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));

        return $stmt->fetchALL(PDO::FETCH_OBJ);
    }

    public function loadDataReport($cliente, $vendedor, $estado, $producto, $marca, $date_from, $date_to, $mestxt)
    {
        /* First Query´s */
        $PKEmpresa = " f.empresa_id = " . $_SESSION["IDEmpresa"];
        $cliente = $cliente == "000" ? "" : " and f.cliente_id = " . $cliente;
        $vendedor = $vendedor == "000" ? "" : " and f.empleado_id = " . $vendedor;
        $estado = $estado == "000" ? "" : " and c.estado_id = " . $estado;
        $producto = $producto == "000" ? "" : " and producto_id = " . $producto;
        $marca1 = $marca == "000" ? "0=0" : $marca;
        $marca = $marca == "000" ? "" : " and p.FKMarcaProducto = " . $marca;
        $date_from = $date_from == "000" ? "" : $date_from;
        $date_to = $date_to == "000" ? "" : $date_to;
        /// _empresa  10  and f.empleado_id = 101238 
        $where1 = $PKEmpresa . $cliente . $vendedor . $producto;

        $table1 = "";
        $nameFile = "";

        ///Suma 1 dia a la fecha date_to para incluir el dia seleccionado como limite superior 
        $date_to = $date_to == "" ? "" : date('Y-m-d', strtotime($date_to . ' + 1 day'));

        $hoy = date("Y-m-d H:i:s");

        //$mestxt = implode(',', $mestxt);
        $nameFile .= $hoy . " " . $_SESSION['NombreEmpresa'] . " " . $_SESSION['UsuarioNombre'];

        $queryMes = "";

        ///Si no selecciona ningun mes, no filtra por mes
        if ($mestxt == "010") {
            $queryMes = "";
        } else {
            //Si selecciona un mes, filtra por mes
            //Array de meses seleccionados
            $mes = array();
            $mes = $mestxt;//explode(",", $mestxt);
            $queryMes = "";
            //Si en el mes seleccionado viene el codigo de "000" filtra con todos los meses del año actual.
            if (array_search("000", $mes) !== false) {
                $year = date("Y");
                $queryMes = " and (year(f.fecha_timbrado) = " . $year . ")";
            } else {
                //Si en el mes seleccionado no viene el codigo de "000" filtra con los meses seleccionados. 
                foreach ($mes as $key => $value) {
                    if ($queryMes == "") {
                        $queryMes .= " and (((month(f.fecha_timbrado) = " . $value . ")";
                    } else {
                        $queryMes .= " or (month(f.fecha_timbrado) = " . $value . ")";
                    }
                }
                //Delimita el año al año actual
                $year = date("Y");
                $queryMes .= ") and (year(f.fecha_timbrado) = " . $year . "))";
                //$mes = " and month(f.fecha) = " . $mes;
            }
        }




        /// _empresa  10  and f.empleado_id = 101238 
        $where1 = $PKEmpresa . $cliente . $vendedor . $producto . $queryMes;
        $where2 = $estado . $marca;


        if ($date_from != '' && $date_to != '') {
            $where1 .= " AND f.fecha_timbrado BETWEEN  '$date_from' AND '$date_to'";
        } elseif ($date_to != '') {
            $where1 .= " AND f.fecha_timbrado <= '$date_to'";
        } elseif ($date_from != '') {
            $where1 .= " AND f.fecha_timbrado >= '$date_from'";
        }

        $con = new conectar();
        $db = $con->getDb();
        $query = sprintf('call spc_Info_ReporteVentas_Detalles(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($where1, $where2, $marca1));


        ///REcorre la respuesta de la BD
        while (($r = $stmt->fetch()) !== false) {
            if ($r[0]) {
                $r['CLIENTE'] = str_replace('"','',$r['CLIENTE']);
                $table1 .= '{"factura":"' . $r['FACTURA'] .
                    '","folio":"' . $r['SERIE'] . " " . $r['FOLIO'] .
                    '","estado":"' . $r['ESTADO'] .
                    '","cliente":"' . $r['CLIENTE'] .
                    '","asesor":"' . $r['ASESOR'] .
                    '","fecha":"' . $r['FECHA DE TIMBRADO'] .
                    '"},';
            } else {
                $table1 = "";
            }
        }
        $table1 = substr($table1, 0, strlen($table1) - 1);
        echo '{"data":[' . $table1 . ']}';
    }

    /* Carga Tabla de Reporte de Productos */
    public function loadDataReportP($cliente, $vendedor, $estado, $producto, $marca, $date_from, $date_to, $mestxt)
    {
        $PKEmpresa = " f.empresa_id = " . $_SESSION["IDEmpresa"];
        $cliente1 = $cliente == "000" ? "" : " and f.cliente_id = " . $cliente;
        $vendedor1 = $vendedor == "000" ? "" : " and f.empleado_id = " . $vendedor;
        $estado = $estado == "000" ? "" : " and c.estado_id = " . $estado;
        $producto1 = $producto == "000" ? "" : " and producto_id = " . $producto;
        //$marca1 = $marca == "000" ? "0=0" : " and mp.PKMarcaProducto = " . $marca;
        $marca = $marca == "000" ? "" : " and p.FKMarcaProducto = " . $marca;
        $date_from = $date_from == "000" ? "" : $date_from;
        $date_to = $date_to == "000" ? "" : $date_to;
        /// _empresa  10  and f.empleado_id = 101238 
        
        $PKEmpresa2 = " vd.empresa_id = " . $_SESSION["IDEmpresa"];
        $cliente2 = $cliente == "000" ? "" : " and vd.FKCliente = " . $cliente;
        $vendedor2 = $vendedor == "000" ? "" : " and vd.empleado_id = " . $vendedor;
        $producto2 = $producto == "000" ? "" : " and FKProducto = " . $producto;
        
        $table1 = "";



        $nameFile = "";

        ///Suma 1 dia a la fecha date_to para incluir el dia seleccionado como limite superior 
        $date_to = $date_to == "" ? "" : date('Y-m-d', strtotime($date_to . ' + 1 day'));



        $hoy = date("Y-m-d H:i:s");

        //$mestxt = implode(',', $mestxt);
        $nameFile .= $hoy . " " . $_SESSION['NombreEmpresa'] . " " . $_SESSION['UsuarioNombre'];

        $queryMes = "";
        $queryMes2 = "";
        //$mes = $mes == "000" ? "" : " and month(f.fecha) = " . $mes;

        ///Si no selecciona ningun mes, no filtra por mes
        if ($mestxt == "010") {
            $queryMes = "";
            $queryMes2 = "";
        } else {
            //Si selecciona un mes, filtra por mes
            //Array de meses seleccionados
            $mes = array();
            $mes = $mestxt;//explode(",", $mestxt);
            $queryMes = "";
            $queryMes2 = "";
            //Si en el mes seleccionado viene el codigo de "000" filtra con todos los meses del año actual.
            if (array_search("000", $mes) !== false) {
                $year = date("Y");
                $queryMes = " and (year(f.fecha_timbrado) = " . $year . ")";
                $queryMes2 = " and (year(vd.created_at) = " . $year . ")";
            } else {
                //Si en el mes seleccionado no viene el codigo de "000" filtra con los meses seleccionados. 
                foreach ($mes as $key => $value) {
                    if ($queryMes == "") {
                        $queryMes .= " and (((month(f.fecha_timbrado) = " . $value . ")";
                    } else {
                        $queryMes .= " or (month(f.fecha_timbrado) = " . $value . ")";
                    }
                }
                foreach ($mes as $key => $value) {
                    if ($queryMes2 == "") {
                        $queryMes2 .= " and (((month(vd.created_at) = " . $value . ")";
                    } else {
                        $queryMes2 .= " or (month(vd.created_at) = " . $value . ")";
                    }
                }
                //Delimita el año al año actual
                $year = date("Y");
                $queryMes .= ") and (year(f.fecha_timbrado) = " . $year . "))";
                $queryMes2 .= ") and (year(vd.created_at) = " . $year . "))";
                //$mes = " and month(f.fecha) = " . $mes;
            }
        }

        $where1 = $PKEmpresa . $cliente1 . $vendedor1 . $producto1 . $estado . $marca . $queryMes;
        $where2 = $PKEmpresa2 . $cliente2 . $vendedor2 . $producto2 . $estado . $marca . $queryMes2;

        $nameFile .= $hoy . " " . $_SESSION['NombreEmpresa'] . " " . $_SESSION['UsuarioNombre'];


        /// _empresa  10  and f.empleado_id = 101238 
        /*         $where1 = $PKEmpresa . $cliente . $vendedor . $producto; */



        if ($date_from != '' && $date_to != '') {
            $where1 .= " AND f.fecha_timbrado BETWEEN  '$date_from' AND '$date_to'";
        } elseif ($date_to != '') {
            $where1 .= " AND f.fecha_timbrado <= '$date_to'";
        } elseif ($date_from != '') {
            $where1 .= " AND f.fecha_timbrado >= '$date_from'";
        }else{
            $where1 .= " AND year(f.fecha_timbrado) = year(now())";
        }
        if ($date_from != '' && $date_to != '') {
            $where2 .= " AND vd.created_at BETWEEN  '$date_from' AND '$date_to'";
        } elseif ($date_to != '') {
            $where2 .= " AND vd.created_at <= '$date_to'";
        } elseif ($date_from != '') {
            $where2 .= " AND vd.created_at >= '$date_from'";
        }else{
            $where2 .= " AND year(vd.created_at) = year(now())";
        }

        $con = new conectar();
        $db = $con->getDb();
        $query = sprintf('call spc_Info_ReporteVentas_Productos(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($where1,$where2));


        ///REcorre la respuesta de la BD
        while (($r = $stmt->fetch()) !== false) {
            if ($r[0]) {
                $table1 .= '{"Producto":"' . str_replace('"', '\"', str_replace(["\r", "\n"], "", $r['Nombre'])) . " - " . str_replace('"', '\"', str_replace(["\r", "\n"], "", $r['Clave Interna'])) .
                    '","Marca":"' . str_replace('"', '\"', str_replace(["\r", "\n"], "", $r['Marca de Producto'])) .
                    '","Piezas":"' . $r['Piezas'] .
                    '","Total":"' . '$ ' . number_format($r['Total Sin Impuestos'], 2, ".", ",") .
                    '"},';
            } else {
                $table1 = "";
            }
        }
        $table1 = substr($table1, 0, strlen($table1) - 1);
        echo '{"data":[' . $table1 . ']}';
    }

    function getTotalSales()
    {
        $con = new conectar();
        $db = $con->getDb();
        $anioActual = date('Y');
        $month_name = $db->prepare('SET lc_time_names = "es_MX"');
        $month_name->execute();
        $query = sprintf("
            select 
                concat(ucase(mid(mes,1,1)),mid(mes,2)) mes,
                sum(total) total,
                mes_id 
            from (
                    select 
                        MONTHNAME(v.created_at) mes,
                        sum(Importe) total,
                        month(v.created_at) mes_id
                    from ventas_directas v 
                    where 
                        v.empresa_id = :empresa_id and 
                        year(v.created_at) = :anioActual
                    group by month(v.created_at)
                
                    union
                
                    select 
                        MONTHNAME(f.fecha_timbrado)	mes,
                        sum(f.total_facturado) total,
                        month(f.fecha_timbrado) mes_id
                    from facturacion f 
                    where 
                        f.empresa_id = :empresa_id1 and 
                        year(f.fecha_timbrado) = :anioActual1
                    group by month(f.fecha_timbrado)
            ) ventas_totales_mes
            group by mes
            order by mes_id
        ");
        
        $stmt = $db->prepare($query);
        $stmt->bindValue(':empresa_id',$_SESSION['IDEmpresa']);
        $stmt->bindValue(':anioActual',$anioActual);
        $stmt->bindValue(':empresa_id1',$_SESSION['IDEmpresa']);
        $stmt->bindValue(':anioActual1',$anioActual);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    function getChartSales()
    {
        $get_data = new Get_datas();
        $arr = $get_data->getTotalSales();
        $datos = [];
        $labels = [];
        foreach($arr as $r)
        {
            $labels[] = $r->mes;
            $datos[] = array(
                'label' => $r->mes,
                'data'=>array($r->total),
                'backgroundColor'=>sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
                'borderColor' => sprintf('#%06X', mt_rand(0, 0xFFFFFF))
            );
        }
        return ['labels'=>$labels,'datos'=>$datos];
        // $configuracion = array(
        //     'type' => 'bar',
        //     'data' => array(
        //         'labels' => $labels,
        //         'datasets' => $datos
        //     ),
        //     'options' => array(
        //         'tooltips' => array(
        //             'callbacks' => array(
        //                 'label' => 'function(context) 
        //                 { 
        //                     let label = context.dataset.label || "";

        //                     if (label) {
        //                         label += ": ";
        //                     }

        //                     if (context.parsed.y !== null) {
        //                         label += new Intl.NumberFormat("es-MX", { style:"currency", currency: "MXN" }).format(context.parsed.y);
        //                     }
        //                 }'
        //             )
        //         )
        //     )
        // );
        // return $configuracion;
    }
}


class save_datas
{
}

class Update_datas
{
}

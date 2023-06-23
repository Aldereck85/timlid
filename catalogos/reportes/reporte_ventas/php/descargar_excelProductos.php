<?php
require_once('../../../../include/db-conn.php');
require_once('../../../../vendor/shuchkin/simplexlsxgen/src/SimpleXLSXGen.php');
session_start();
class conectar
{
    public function getDb()
    {
        include "../../../../include/db-conn.php";
        return $conn;
    }
}

$con = new conectar();
$db = $con->getDb();
$db2 = $con->getDb();


$cliente = $_REQUEST['cliente_id'];
$vendedor = $_REQUEST['vendedor_id'];
$estado = $_REQUEST['estado_id'];
$producto = $_REQUEST['producto_id'];
$marca = $_REQUEST['marca_id'];
$date_from = $_REQUEST['date_from'];
$date_to = $_REQUEST['date_to'];
$PKEmpresa = " f.empresa_id = " . $_SESSION["IDEmpresa"];
$txtMes = $_REQUEST['mes'];


$mes = $txtMes == "" ? "010" : $txtMes;
$PKEmpresa = " (f.empresa_id = " . $_SESSION["IDEmpresa"] . ")";
$cliente = $cliente == "000" ? "" : " and (f.cliente_id = " . $cliente . ")";
$vendedor = $vendedor == "000" ? "" : " and (f.empleado_id = " . $vendedor . ")";
$estado = $estado == "000" ? "" : "and (c.estado_id = " . $estado . ")";
$producto = $producto == "000" ? "" : " and (producto_id = " . $producto . ")";
$marca1 = $marca == "000" ? "0=0" : " and mp.PKMarcaProducto = " . $marca;
$marca = $marca == "000" ? "" : " and (p.FKMarcaProducto = " . $marca . ")";
$date_from = $date_from == "000" ? "" : $date_from;
$date_to = $date_to == "000" ? "" : $date_to;
/// _empresa  10  and f.empleado_id = 101238 

$table1 = "";

$queryMes = "";
//$mes = $mes == "000" ? "" : " and month(f.fecha) = " . $mes;

///Si no selecciona ningun mes, no filtra por mes
if($mes == "010"){
    $queryMes = "";
}else{
    //Si selecciona un mes, filtra por mes
    //Array de meses seleccionados
    $mes = array();
    $mes = explode(",",$_REQUEST['mes']);
    $queryMes = "";
    //Si en el mes seleccionado viene el codigo de "000" filtra con todos los meses del año actual.
    if(array_search("000",$mes) !== false){
        $year = date("Y");
        $queryMes = " and (year(f.fecha_timbrado) = ".$year.")";
    }else{
        //Si en el mes seleccionado no viene el codigo de "000" filtra con los meses seleccionados. 
        foreach ($mes as $key => $value) {
            if($queryMes == ""){
                $queryMes .= " and ((month(f.fecha_timbrado) = " . $value.")";
            }else{
                $queryMes .= " or (month(f.fecha_timbrado) = " . $value.")";
            }
            
        }
        //Delimita el año al año actual
        $year = date("Y");
        $queryMes .= " and (year(f.fecha_timbrado) = ".$year."))";
        //$mes = " and month(f.fecha) = " . $mes;
    }
}





$where1 = $PKEmpresa . $cliente . $vendedor . $producto . $estado  . $marca . $queryMes;



$nameFile = "";

///Suma 1 dia a la fecha date_to para incluir el dia seleccionado como limite superior 
$date_to = $date_to == "000" ? "" : date('Y-m-d', strtotime($date_to . ' + 1 day'));



$hoy = date("Y-m-d H:i:s");


$nameFile .= $hoy . " " . $_SESSION['NombreEmpresa'] . " " . $_SESSION['UsuarioNombre'];


/// _empresa  10  and f.empleado_id = 101238 
/* $where1 = $PKEmpresa . $cliente . $vendedor . $producto; */



if ($date_from != 000 && $date_to != 000) {
    $where1 .= " AND f.fecha_timbrado BETWEEN  '$date_from' AND '$date_to'";
} elseif ($date_to != 000) {
    $where1 .= " AND f.fecha_timbrado <= '$date_to'";
} elseif ($date_from != 000) {
    $where1 .= " AND f.fecha_timbrado >= '$date_from'";
}

$query = sprintf('call spc_Info_ReporteVentas_Productos(?)');
$stmt = $db->prepare($query);
$stmt->execute(array($where1));
$table1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();
$tabla = array();

$flag = false;
$headers = array();
$values = array();
foreach ($table1 as $r) {
    foreach ($r as $k => $v) {
        /* Define los titulos */
        if (!$flag) {

            $headers[] = "<style bgcolor=\"#FFFF00\"><b>" . $k . "</b></style>";
        }
        /* Da formato de moneda */
        if ($k == "Total Sin Impuestos") {
            $r["Total Sin Impuestos"] =  number_format($v, 2, '.', '');
        }elseif($k == "Descuento"){
            $r["Descuento"] =  number_format($v, 2, '.', '');
        }
    }
    $values[] = $r;

    $flag = true;
}
/* print_r($values);
            return; */
$tabla[] = $headers;
$tabla = array_merge($tabla, $values);
$nameFile .= " " . $date_from . " " . $date_to;


// Create new PHPExcel object con la tabla por marcas
$query = sprintf('call spc_Info_ReporteVentas_ProductosXMarca(?)');
$stmt = $conn->prepare($query);
$stmt->execute(array($where1));
$table2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();
$tablaXMarca = array();

$flag = false;
$headers2 = array();
$values2 = array();
foreach ($table2 as $r) {

    // display field/column names as first row
    foreach ($r as $k => $v) {
        if (!$flag) {
            $headers2[] = "<style bgcolor=\"#FFFF00\"><b>" . $k . "</b></style>";
        }
        /* Da formato de moneda */
        if ($k == "Total Sin Impuestos") {
            $r["Total Sin Impuestos"] =  number_format($v, 2, '.', '');
        }elseif($k == "Descuento"){
            $r["Descuento"] =  number_format($v, 2, '.', '');
        }
    }
    $values2[] = $r;
    $flag = true;
}
$tablaXMarca[] = $headers2;
$tablaXMarca = array_merge($tablaXMarca, $values2);

// Create new PHPExcel object con la tabla por categorias
$query = sprintf('call spc_Info_ReporteVentas_ProductosXCategoria(?)');
$stmt = $db2->prepare($query);
$stmt->execute(array($where1));
$table3 = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();
$tablaXCategoria = array();

$flag = false;
$headers3 = array();
$values3 = array();
foreach ($table3 as $r) {

        // display field/column names as first row
        foreach ($r as $k => $v) {
            if(!$flag){
                $headers3[] = "<style bgcolor=\"#FFFF00\"><b>" . $k . "</b></style>";
            }
            /* Da formato de moneda */
            if ($k == "Total Sin Impuestos") {
                $r["Total Sin Impuestos"] =  number_format($v, 2, '.', '');
            }elseif($k == "Descuento"){
                $r["Descuento"] =  number_format($v, 2, '.', '');
            }
        }
        $values3[] = $r;
        $flag = true;

}
$tablaXCategoria[] = $headers3;
$tablaXCategoria = array_merge($tablaXCategoria, $values3);

//print_r($table1);

$xlsx = Shuchkin\SimpleXLSXGen::fromArray($tabla, "Reporte por Productos");
$xlsx->addSheet($tablaXMarca, 'Reporte por Marcas');
$xlsx->addSheet($tablaXCategoria, 'Reporte por Categorias');
$xlsx->downloadAs('Reporte de Ventas ' . $nameFile . '.xlsx');

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

$cliente = $_REQUEST['cliente_id'];
$vendedor = $_REQUEST['vendedor_id'];
$estado = $_REQUEST['estado_id'];
$producto = $_REQUEST['producto_id'];
$marca = $_REQUEST['marca_id'];
$date_from = $_REQUEST['date_from'];
$date_to = $_REQUEST['date_to'];
$PKEmpresa = " f.empresa_id = " . $_SESSION["IDEmpresa"];
$txtMes = $_REQUEST['mes'];


//$nameFile = $PKEmpresa."&".$cliente."&".$vendedor."&".$estado."&".$producto."&".$marca."&".$date_from."&".$date_to;
$nameFile = "";

$mes = $txtMes == "" ? "010" : $txtMes;
$cliente = $cliente == "000" ? "" : " and f.cliente_id = " . $cliente;

$vendedor = $vendedor == "000" ? "" : " and f.empleado_id = " . $vendedor;

$estado = $estado == "000" ? "" : "and c.estado_id = " . $estado;

$producto = $producto == "000" ? "" : " and producto_id = " . $producto;

$marca1 = $marca == "000" ? "0=0" : $marca;
$marca = $marca == "000" ? "" : " and p.FKMarcaProducto = " . $marca;

$date_from = $date_from == "000" ? "" : $date_from;
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




///Suma 1 dia a la fecha date_to para incluir el dia seleccionado como limite superior 
$date_to = $date_to == "000" ? "" : date('Y-m-d', strtotime($date_to . ' + 1 day'));



$hoy = date("Y-m-d H:i:s");


$nameFile .= $hoy . " " . $_SESSION['NombreEmpresa'] . " " . $_SESSION['UsuarioNombre'];


/// _empresa  10  and f.empleado_id = 101238 
$where1 = $PKEmpresa . $cliente . $vendedor . $producto . $queryMes;
$where2 = $estado . $marca;




if ($date_from != 000 && $date_to != 000) {
    $where1 .= " AND f.fecha_timbrado BETWEEN  '$date_from' AND '$date_to'";
} elseif ($date_to != 000) {
    $where1 .= " AND f.fecha_timbrado <= '$date_to'";
} elseif ($date_from != 000) {
    $where1 .= " AND f.fecha_timbrado >= '$date_from'";
}
/* echo $where1 ."--" . $where2;
return; */

$query = sprintf('call spc_Info_ReporteVentas_Detalles(?,?,?)');
$stmt = $conn->prepare($query);
$stmt->execute(array($where1, $where2, $marca1));

$all = $stmt->fetchALL(PDO::FETCH_OBJ);
$stmt->closeCursor();
$stmt = null;
//$conn = null;

$flagItent = true;
$titles = [];
$header = [];
$rows = [];
$table = [];
///REcorre la respuesta de la BD
foreach ($all as $r) {
    ///Despues de la septima clave vienen las marcas, bandera para contarlas
    $flagMarca = 0;
    $TotalFactura = 0;
    $TotalFacturaNoIMPS = 0;
    $flagStatus = 0;

    //Divide el arreglo en las 3 segciones despues de los 7 secciones que no cambian, 
    //osea guarda las marcasde la empresa que vienen en el reporte
    $tamañofila = (count((array)($r)));

    $tamaño = ((count((array)($r)) - 9) / 3);
    ///REcorre cada row de la respuesta Clave = (Cliente,serie,folio,etc) Valor = El contenido 
    foreach ($r as $clave => $valor) {
        $flagMarca++;
        ///Si el status es 4 osea cancelada la pone cancelada y no la calcula
        if ($flagMarca == 1) {
            $flagStatus = $valor == 4 ? '1' : '0';
        }

        If($clave!= "estatus"){
        //La primera ves que entra se crea el array de titulos
        if ($flagItent) {
            $titles[] = "<style bgcolor=\"#FFFF00\"><b>" . $clave . "<b></style>";
            //array_push($titles,$clave);
        }

        //No suman cuando la factura esta en estatus 4
        if ($flagStatus == '1') {
            ///Despues de la septima clave vienen las marcas, bandera para contarlas
            if (($flagMarca > (9))) {
                //$TotalFactura += doubleval(0);
                $rows[] = "<style bgcolor=\"#BA4353\"><b> 0 <b></style>";
            } else {
                $rows[] = "<style bgcolor=\"#BA4353\"><b>" . $valor . "<b></style>";
            }
            ///Si sumna a los Totales si no esta en 4
        } else {
            ///Despues de la septima clave vienen las marcas, bandera para contarlas
            if (($flagMarca > (9 + ($tamaño * 2)))) {
                $TotalFactura += doubleval($valor);
                $rows[] = number_format($valor, 2, '.', '');
            } elseif (($flagMarca <= (($tamañofila - $tamaño))) && ($flagMarca > (9 + ($tamaño)))) {
                $TotalFacturaNoIMPS += doubleval($valor);
                $rows[] = number_format($valor, 2, '.', '');
            } else {
                $rows[] = $valor;
            }
        } 
        }

    }
    //Pone el total rojos tambien si el status es cancelada
    if($flagStatus == '1') {
        $rows[] = "<style bgcolor=\"#BA4353\"><b>" . $TotalFactura."<b></style>";
        $rows[] = "<style bgcolor=\"#BA4353\"><b>" . $TotalFacturaNoIMPS."<b></style>";;
    }else{
        $rows[] = $TotalFactura;
        $rows[] = $TotalFacturaNoIMPS;
    }
    if ($flagItent) {
        $flagItent = false;
        $titles[] = "<style bgcolor=\"#FFFF00\"><b>TOTAL<b></style>";
        $titles[] = "<style bgcolor=\"#FFFF00\"><b>TOTAL SIN IMPUESTOS<b></style>";
        $table[] = $titles;
        //array_push($table,$titles);
    }
    $table[] = $rows;
    //array_push($table,$rows);
    $rows = [];
}



$query2 = sprintf('call spc_Info_ReporteVentas_Detalles_Categoria(?,?,?)');
$stmt = $db->prepare($query2);
$stmt->execute(array($where1, $where2, $marca1));

$all2 = $stmt->fetchALL(PDO::FETCH_OBJ);
$stmt->closeCursor();
$flagItent = true;
$titles = [];
$header = [];
$rows = [];
$table2 = [];

foreach ($all2 as $r) {
    ///Despues de la septima clave vienen las marcas, bandera para contarlas
    $flagMarca = 0;
    $TotalFactura = 0;
    $TotalFacturaNoIMPS = 0;
    $flagStatus = 0;

    //Divide el arreglo en las 3 segciones despues de los 7 secciones que no cambian, 
    //osea guarda las marcasde la empresa que vienen en el reporte
    $tamañofila = (count((array)($r)));

    $tamaño = ((count((array)($r)) - 9) / 3);
    ///REcorre cada row de la respuesta Clave = (Cliente,serie,folio,etc) Valor = El contenido 
    foreach ($r as $clave => $valor) {
        $flagMarca++;
        ///Si el status es 4 osea cancelada la pone cancelada y no la calcula
        if ($flagMarca == 1) {
            $flagStatus = $valor == 4 ? '1' : '0';
        }

        If($clave!= "estatus"){
        //La primera ves que entra se crea el array de titulos
        if ($flagItent) {
            $titles[] = "<style bgcolor=\"#FFFF00\"><b>" . $clave . "<b></style>";
            //array_push($titles,$clave);
        }

        //No suman cuando la factura esta en estatus 4
        if ($flagStatus == '1') {
            ///Despues de la septima clave vienen las marcas, bandera para contarlas
            if (($flagMarca > (9))) {
                //$TotalFactura += doubleval(0);
                $rows[] = "<style bgcolor=\"#BA4353\"><b> 0 <b></style>";
            } else {
                $rows[] = "<style bgcolor=\"#BA4353\"><b>" . $valor . "<b></style>";
            }
            ///Si sumna a los Totales si no esta en 4
        } else {
            ///Despues de la septima clave vienen las marcas, bandera para contarlas
            if (($flagMarca > (9 + ($tamaño * 2)))) {
                $TotalFactura += doubleval($valor);
                $rows[] = number_format($valor, 2, '.', '');
            } elseif (($flagMarca <= (($tamañofila - $tamaño))) && ($flagMarca > (9 + ($tamaño)))) {
                $TotalFacturaNoIMPS += doubleval($valor);
                $rows[] = number_format($valor, 2, '.', '');
            } else {
                $rows[] = $valor;
            }
        } 
        }

    }
    
    //Pone el total rojos tambien si el status es cancelada
    if($flagStatus == '1') {
        $rows[] = "<style bgcolor=\"#BA4353\"><b>" . $TotalFactura."<b></style>";
        $rows[] = "<style bgcolor=\"#BA4353\"><b>" . $TotalFacturaNoIMPS."<b></style>";;
    }else{
        $rows[] = $TotalFactura;
        $rows[] = $TotalFacturaNoIMPS;
    }

    if ($flagItent) {
        $flagItent = false;
        $titles[] = "<style bgcolor=\"#FFFF00\"><b>TOTAL<b></style>";
        $titles[] = "<style bgcolor=\"#FFFF00\"><b>TOTAL SIN IMPUESTOS<b></style>";
        $table2[] = $titles;
        //array_push($table,$titles);
    }
    $table2[] = $rows;
    //array_push($table,$rows);
    $rows = [];
}
// print_r($table);

$xlsx = Shuchkin\SimpleXLSXGen::fromArray($table,"Reporte por Marcas");
$xlsx->addSheet($table2, 'Reporte por Categorias');
$xlsx->downloadAs('Reporte de Ventas ' . $nameFile . '.xlsx');

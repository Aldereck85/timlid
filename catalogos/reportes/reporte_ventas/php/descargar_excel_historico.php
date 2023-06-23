<?php
require_once('../../../../include/db-conn.php');
require_once('../../../../vendor/shuchkin/simplexlsxgen/src/SimpleXLSXGen.php');
session_start();
ini_set('error_reporting', E_ALL );
ini_set('display_errors', 1 );

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

$cliente = $_POST['cliente_id'];
$vendedor = $_POST['vendedor_id'];
$estado = $_POST['estado_id'];
$año = $_POST['año'];

$nameFile = "";

$cliente1 = $cliente == "todos" ? "" : " and f.cliente_id = " . $cliente;
$cliente2 = $cliente == "todos" ? "" : " and v.FKCliente = " . $_POST['cliente_id'];

$vendedor = $vendedor == "todos" ? "" : " and c.empleado_id = " . $vendedor;

$estado = $estado == "todos" ? "" : " and c.estado_id = " . $estado;

$año1 = $año == "todos" ? "" : " and year(f.fecha_timbrado) = " . $año;
$año2 = $año == "todos" ? "" : " and year(v.created_at) = " . $año;

$hoy = date("Y-m-d H:i:s");

$nameFile .= $hoy . " " . $_SESSION['NombreEmpresa'] . " " . $_SESSION['UsuarioNombre'];


/// _empresa  10  and f.empleado_id = 101238 
$where1 = $cliente1 . $vendedor . $estado . $año1;
$where2 = $cliente2 . $vendedor . $estado . $año2;

$query = sprintf("SELECT c.razon_social as CLIENTE, 
                        ef.Estado AS ESTADO,
                        c.zona AS REGION,
                        concat(e.Nombres,' ', e.PrimerApellido) as EJECUTIVO,
                        SUM(CASE WHEN MONTH(f.fecha_timbrado)=1 THEN f.total_facturado ELSE 0 END) AS ENERO_FACTURADO,
                        SUM(CASE WHEN MONTH(f.fecha_timbrado)=2 THEN f.total_facturado ELSE 0 END) AS FEBRERO_FACTURADO,
                        SUM(CASE WHEN MONTH(f.fecha_timbrado)=3 THEN f.total_facturado ELSE 0 END) AS MARZO_FACTURADO,
                        SUM(CASE WHEN MONTH(f.fecha_timbrado)=4 THEN f.total_facturado ELSE 0 END) AS ABRIL_FACTURADO,
                        SUM(CASE WHEN MONTH(f.fecha_timbrado)=5 THEN f.total_facturado ELSE 0 END) AS MAYO_FACTURADO,
                        SUM(CASE WHEN MONTH(f.fecha_timbrado)=6 THEN f.total_facturado ELSE 0 END) AS JUNIO_FACTURADO,
                        SUM(CASE WHEN MONTH(f.fecha_timbrado)=7 THEN f.total_facturado ELSE 0 END) AS JULIO_FACTURADO,
                        SUM(CASE WHEN MONTH(f.fecha_timbrado)=8 THEN f.total_facturado ELSE 0 END) AS AGOSTO_FACTURADO,
                        SUM(CASE WHEN MONTH(f.fecha_timbrado)=9 THEN f.total_facturado ELSE 0 END) AS SEPTIEMBRE_FACTURADO,
                        SUM(CASE WHEN MONTH(f.fecha_timbrado)=10 THEN f.total_facturado ELSE 0 END) AS OCTUBRE_FACTURADO,
                        SUM(CASE WHEN MONTH(f.fecha_timbrado)=11 THEN f.total_facturado ELSE 0 END) AS NOVIEMBRE_FACTURADO,
                        SUM(CASE WHEN MONTH(f.fecha_timbrado)=12 THEN f.total_facturado ELSE 0 END) AS DICIEMBRE_FACTURADO,
                        SUM(f.total_facturado) AS TOTAL
                FROM    facturacion as f
                    inner join clientes as c on c.PKCliente = f.cliente_id
                    inner join estados_federativos as ef on ef.PKEstado = c.estado_id
                    left join empleados as e on e.PKEmpleado = c.empleado_id
                where f.estatus != 4 and f.empresa_id = :empresa $where1
                GROUP   BY f.cliente_id;");
$stmt = $conn->prepare($query);
$stmt->bindValue(":empresa", $_SESSION["IDEmpresa"]);
$stmt->execute();

$all = $stmt->fetchALL(PDO::FETCH_OBJ);
$stmt->closeCursor();
$stmt = null;

$query1 = sprintf("
                    select 
                        c.razon_social AS CLIENTE,
                        ef.Estado AS ESTADO,
                        c.zona AS REGION,
                        IFNULL( concat(e.Nombres,' ', e.PrimerApellido),'Sin Vendedor') as EJECUTIVO,
                        SUM(CASE WHEN MONTH(v.created_at)=1 THEN v.Importe ELSE 0 END) AS ENERO_NO_FACTURADO,
                        SUM(CASE WHEN MONTH(v.created_at)=2 THEN v.Importe ELSE 0 END) AS FEBRERO_NO_FACTURADO,
                        SUM(CASE WHEN MONTH(v.created_at)=3 THEN v.Importe ELSE 0 END) AS MARZO_NO_FACTURADO,
                        SUM(CASE WHEN MONTH(v.created_at)=4 THEN v.Importe ELSE 0 END) AS ABRIL_NO_FACTURADO,
                        SUM(CASE WHEN MONTH(v.created_at)=5 THEN v.Importe ELSE 0 END) AS MAYO_NO_FACTURADO,
                        SUM(CASE WHEN MONTH(v.created_at)=6 THEN v.Importe ELSE 0 END) AS JUNIO_NO_FACTURADO,
                        SUM(CASE WHEN MONTH(v.created_at)=7 THEN v.Importe ELSE 0 END) AS JULIO_NO_FACTURADO,
                        SUM(CASE WHEN MONTH(v.created_at)=8 THEN v.Importe ELSE 0 END) AS AGOSTO_NO_FACTURADO,
                        SUM(CASE WHEN MONTH(v.created_at)=9 THEN v.Importe ELSE 0 END) AS SEPTIEMBRE_NO_FACTURADO,
                        SUM(CASE WHEN MONTH(v.created_at)=10 THEN v.Importe ELSE 0 END) AS OCTUBRE_NO_FACTURADO,
                        SUM(CASE WHEN MONTH(v.created_at)=11 THEN v.Importe ELSE 0 END) AS NOVIEMBRE_NO_FACTURADO,
                        SUM(CASE WHEN MONTH(v.created_at)=12 THEN v.Importe ELSE 0 END) AS DICIEMBRE_NO_FACTURADO,
                        SUM(v.Importe) total_ventas
                    from ventas_directas as v 
                    left join clientes AS c on v.FKCliente = c.PKCliente
                    left join empleados as e on v.empleado_id = e.PKEmpleado
                    left join estados_federativos as ef on c.estado_id = ef.PKEstado
                    where v.empresa_id = :idEmpresa and v.estatus_factura_id <> 2 $where2
                    group by v.FKCliente");
                    
$stmt1 = $db->prepare($query1);
$stmt1->bindValue(":idEmpresa", $_SESSION["IDEmpresa"]);
$stmt1->execute();
$all1 = $stmt1->fetchAll(PDO::FETCH_OBJ);

$table1 = [];

for ($i=0; $i < count($all); $i++) {
    if(in_array($all[$i]->CLIENTE, array_column($table1, 'CLIENTE'))){
        $table1[array_search($all[$i]->CLIENTE, array_column($table1, 'CLIENTE'))]->TOTAL += $all[$i]->TOTAL;
    } else {
        array_push($table1,[
            'CLIENTE'=>$all[$i]->CLIENTE,
            'ESTADO'=>$all[$i]->ESTADO,
            'REGION'=>$all[$i]->REGION,
            'EJECUTIVO'=>$all[$i]->EJECUTIVO,
            'ENERO_FACTURADO'=>$all[$i]->ENERO_FACTURADO,
            'ENERO_NO_FACTURADO'=>0,
            'TOTAL_ENERO'=>$all[$i]->ENERO_FACTURADO,
            'FEBRERO_FACTURADO'=>$all[$i]->FEBRERO_FACTURADO,
            'FEBRERO_NO_FACTURADO'=>0,
            'TOTAL_FEBRERO'=>$all[$i]->FEBRERO_FACTURADO,
            'MARZO_FACTURADO'=>$all[$i]->MARZO_FACTURADO,
            'MARZO_NO_FACTURADO'=>0,
            'TOTAL_MARZO'=>$all[$i]->MARZO_FACTURADO,
            'ABRIL_FACTURADO'=>$all[$i]->ABRIL_FACTURADO,
            'ABRIL_NO_FACTURADO'=>0,
            'TOTAL_ABRIL'=>$all[$i]->ABRIL_FACTURADO,
            'MAYO_FACTURADO'=>$all[$i]->MAYO_FACTURADO,
            'MAYO_NO_FACTURADO'=>0,
            'TOTAL_MAYO'=>$all[$i]->MAYO_FACTURADO,
            'JUNIO_FACTURADO'=>$all[$i]->JUNIO_FACTURADO,
            'JUNIO_NO_FACTURADO'=>0,
            'TOTAL_JUNIO'=>$all[$i]->JUNIO_FACTURADO,
            'JULIO_FACTURADO'=>$all[$i]->JULIO_FACTURADO,
            'JULIO_NO_FACTURADO'=>0,
            'TOTAL_JULIO'=>$all[$i]->JULIO_FACTURADO,
            'AGOSTO_FACTURADO'=>$all[$i]->AGOSTO_FACTURADO,
            'AGOSTO_NO_FACTURADO'=>0,
            'TOTAL_AGOSTO'=>$all[$i]->AGOSTO_FACTURADO,
            'SEPTIEMBRE_FACTURADO'=>$all[$i]->SEPTIEMBRE_FACTURADO,
            'SEPTIEMBRE_NO_FACTURADO'=>0,
            'TOTAL_SEPTIEMBRE'=>$all[$i]->SEPTIEMBRE_FACTURADO,
            'OCTUBRE_FACTURADO'=>$all[$i]->OCTUBRE_FACTURADO,
            'OCTUBRE_NO_FACTURADO'=>0,
            'TOTAL_OCTUBRE'=>$all[$i]->OCTUBRE_FACTURADO,
            'NOVIEMBRE_FACTURADO'=>$all[$i]->NOVIEMBRE_FACTURADO,
            'NOVIEMBRE_NO_FACTURADO'=>0,
            'TOTAL_NOVIEMBRE'=>$all[$i]->NOVIEMBRE_FACTURADO,
            'DICIEMBRE_FACTURADO'=>$all[$i]->DICIEMBRE_FACTURADO,
            'DICIEMBRE_NO_FACTURADO'=>0,
            'TOTAL_DICIEMBRE'=>$all[$i]->DICIEMBRE_FACTURADO,
            'TOTAL'=>$all[$i]->TOTAL,
            'TOTAL_NO_FACTURADO'=>0,
            'TOTAL_GLOBAL'=>$all[$i]->TOTAL
        ]);
    }
}

for ($i=0; $i < count($all1); $i++) {
    if(in_array($all1[$i]->CLIENTE, array_column($table1, 'CLIENTE'))){
        $table1[array_search($all1[$i]->CLIENTE, array_column($table1, 'CLIENTE'))]['TOTAL_NO_FACTURADO'] += $all1[$i]->total_ventas;
        $table1[array_search($all1[$i]->CLIENTE, array_column($table1, 'CLIENTE'))]['TOTAL_GLOBAL'] += $all1[$i]->total_ventas;
        $table1[array_search($all1[$i]->CLIENTE, array_column($table1, 'CLIENTE'))]['ENERO_NO_FACTURADO'] += $all1[$i]->ENERO_NO_FACTURADO;
        $table1[array_search($all1[$i]->CLIENTE, array_column($table1, 'CLIENTE'))]['FEBRERO_NO_FACTURADO'] += $all1[$i]->FEBRERO_NO_FACTURADO;
        $table1[array_search($all1[$i]->CLIENTE, array_column($table1, 'CLIENTE'))]['MARZO_NO_FACTURADO'] += $all1[$i]->MARZO_NO_FACTURADO;
        $table1[array_search($all1[$i]->CLIENTE, array_column($table1, 'CLIENTE'))]['ABRIL_NO_FACTURADO'] += $all1[$i]->ABRIL_NO_FACTURADO;
        $table1[array_search($all1[$i]->CLIENTE, array_column($table1, 'CLIENTE'))]['MAYO_NO_FACTURADO'] += $all1[$i]->MAYO_NO_FACTURADO;
        $table1[array_search($all1[$i]->CLIENTE, array_column($table1, 'CLIENTE'))]['JUNIO_NO_FACTURADO'] += $all1[$i]->JUNIO_NO_FACTURADO;
        $table1[array_search($all1[$i]->CLIENTE, array_column($table1, 'CLIENTE'))]['JULIO_NO_FACTURADO'] += $all1[$i]->JULIO_NO_FACTURADO;
        $table1[array_search($all1[$i]->CLIENTE, array_column($table1, 'CLIENTE'))]['AGOSTO_NO_FACTURADO'] += $all1[$i]->AGOSTO_NO_FACTURADO;
        $table1[array_search($all1[$i]->CLIENTE, array_column($table1, 'CLIENTE'))]['SEPTIEMBRE_NO_FACTURADO'] += $all1[$i]->SEPTIEMBRE_NO_FACTURADO;
        $table1[array_search($all1[$i]->CLIENTE, array_column($table1, 'CLIENTE'))]['OCTUBRE_NO_FACTURADO'] += $all1[$i]->OCTUBRE_NO_FACTURADO;
        $table1[array_search($all1[$i]->CLIENTE, array_column($table1, 'CLIENTE'))]['NOVIEMBRE_NO_FACTURADO'] += $all1[$i]->NOVIEMBRE_NO_FACTURADO;
        $table1[array_search($all1[$i]->CLIENTE, array_column($table1, 'CLIENTE'))]['DICIEMBRE_NO_FACTURADO'] += $all1[$i]->DICIEMBRE_NO_FACTURADO;
        $table1[array_search($all1[$i]->CLIENTE, array_column($table1, 'CLIENTE'))]['TOTAL_ENERO'] += $all1[$i]->ENERO_NO_FACTURADO;
        $table1[array_search($all1[$i]->CLIENTE, array_column($table1, 'CLIENTE'))]['TOTAL_FEBRERO'] += $all1[$i]->FEBRERO_NO_FACTURADO;
        $table1[array_search($all1[$i]->CLIENTE, array_column($table1, 'CLIENTE'))]['TOTAL_MARZO'] += $all1[$i]->MARZO_NO_FACTURADO;
        $table1[array_search($all1[$i]->CLIENTE, array_column($table1, 'CLIENTE'))]['TOTAL_ABRIL'] += $all1[$i]->ABRIL_NO_FACTURADO;
        $table1[array_search($all1[$i]->CLIENTE, array_column($table1, 'CLIENTE'))]['TOTAL_MAYO'] += $all1[$i]->MAYO_NO_FACTURADO;
        $table1[array_search($all1[$i]->CLIENTE, array_column($table1, 'CLIENTE'))]['TOTAL_JUNIO'] += $all1[$i]->JUNIO_NO_FACTURADO;
        $table1[array_search($all1[$i]->CLIENTE, array_column($table1, 'CLIENTE'))]['TOTAL_JULIO'] += $all1[$i]->JULIO_NO_FACTURADO;
        $table1[array_search($all1[$i]->CLIENTE, array_column($table1, 'CLIENTE'))]['TOTAL_AGOSTO'] += $all1[$i]->AGOSTO_NO_FACTURADO;
        $table1[array_search($all1[$i]->CLIENTE, array_column($table1, 'CLIENTE'))]['TOTAL_SEPTIEMBRE'] += $all1[$i]->SEPTIEMBRE_NO_FACTURADO;
        $table1[array_search($all1[$i]->CLIENTE, array_column($table1, 'CLIENTE'))]['TOTAL_OCTUBRE'] += $all1[$i]->OCTUBRE_NO_FACTURADO;
        $table1[array_search($all1[$i]->CLIENTE, array_column($table1, 'CLIENTE'))]['TOTAL_NOVIEMBRE'] += $all1[$i]->NOVIEMBRE_NO_FACTURADO;
        $table1[array_search($all1[$i]->CLIENTE, array_column($table1, 'CLIENTE'))]['TOTAL_DICIEMBRE'] += $all1[$i]->DICIEMBRE_NO_FACTURADO;
    } else {
        array_push($table1,[
            'CLIENTE'=>$all1[$i]->CLIENTE,
            'ESTADO'=>$all1[$i]->ESTADO,
            'REGION'=>$all1[$i]->REGION,
            'EJECUTIVO'=>$all1[$i]->EJECUTIVO,
            'ENERO_FACTURADO'=>0,
            'ENERO_NO_FACTURADO'=>$all1[$i]->ENERO_NO_FACTURADO,
            'TOTAL_ENERO'=>$all1[$i]->ENERO_NO_FACTURADO,
            'FEBRERO_FACTURADO'=>0,
            'FEBRERO_NO_FACTURADO'=>$all1[$i]->FEBRERO_NO_FACTURADO,
            'TOTAL_FEBRERO'=>$all1[$i]->FEBRERO_NO_FACTURADO,
            'MARZO_FACTURADO'=>0,
            'MARZO_NO_FACTURADO'=>$all1[$i]->MARZO_NO_FACTURADO,
            'TOTAL_MARZO'=>$all1[$i]->MARZO_NO_FACTURADO,
            'ABRIL_FACTURADO'=>0,
            'ABRIL_NO_FACTURADO'=>$all1[$i]->ABRIL_NO_FACTURADO,
            'TOTAL_ABRIL'=>$all1[$i]->ABRIL_NO_FACTURADO,
            'MAYO_FACTURADO'=>0,
            'MAYO_NO_FACTURADO'=>$all1[$i]->MAYO_NO_FACTURADO,
            'TOTAL_MAYO'=>$all1[$i]->MAYO_NO_FACTURADO,
            'JUNIO_FACTURADO'=>0,
            'JUNIO_NO_FACTURADO'=>$all1[$i]->JUNIO_NO_FACTURADO,
            'TOTAL_JUNIO'=>$all1[$i]->JUNIO_NO_FACTURADO,
            'JULIO_FACTURADO'=>0,
            'JULIO_NO_FACTURADO'=>$all1[$i]->JULIO_NO_FACTURADO,
            'TOTAL_JULIO'=>$all1[$i]->JULIO_NO_FACTURADO,
            'AGOSTO_FACTURADO'=>0,
            'AGOSTO_NO_FACTURADO'=>$all1[$i]->AGOSTO_NO_FACTURADO,
            'TOTAL_AGOSTO'=>$all1[$i]->AGOSTO_NO_FACTURADO,
            'SEPTIEMBRE_FACTURADO'=>0,
            'SEPTIEMBRE_NO_FACTURADO'=>$all1[$i]->SEPTIEMBRE_NO_FACTURADO,
            'TOTAL_SEPTIEMBRE'=>$all1[$i]->SEPTIEMBRE_NO_FACTURADO,
            'OCTUBRE_FACTURADO'=>0,
            'OCTUBRE_NO_FACTURADO'=>$all1[$i]->OCTUBRE_NO_FACTURADO,
            'TOTAL_OCTUBRE'=>$all1[$i]->OCTUBRE_NO_FACTURADO,
            'NOVIEMBRE_FACTURADO'=>0,
            'NOVIEMBRE_NO_FACTURADO'=>$all1[$i]->NOVIEMBRE_NO_FACTURADO,
            'TOTAL_NOVIEMBRE'=>$all1[$i]->NOVIEMBRE_NO_FACTURADO,
            'DICIEMBRE_FACTURADO'=>0,
            'DICIEMBRE_NO_FACTURADO'=>$all1[$i]->DICIEMBRE_NO_FACTURADO,
            'TOTAL_DICIEMBRE'=>$all1[$i]->DICIEMBRE_NO_FACTURADO,
            'TOTAL'=>0,
            'TOTAL_NO_FACTURADO'=>$all1[$i]->total_ventas,
            'TOTAL_GLOBAL'=>$all1[$i]->total_ventas
        ]);
    }
}

$flagItent = true;
$months = [
    '<center><style bgcolor="#FFFF00";><b>CLIENTE<b></style><center>',
    '<center><style bgcolor="#FFFF00";><b>ESTADO<b></style><center>',
    '<center><style bgcolor="#FFFF00";><b>REGION<b></style><center>',
    '<center><style bgcolor="#FFFF00";><b>EJECUTIVO<b></style><center>',
    '<center><style bgcolor="#FFFF00";><b>ENERO<b></style><center>',
    '<center><style bgcolor="#FFFF00";></style><center>',
    '<center><style bgcolor="#FFFF00";></style><center>',
    '<center><style bgcolor="#FFFF00";><b>FEBRERO</b></style><center>',
    '<center><style bgcolor="#FFFF00";></style><center>',
    '<center><style bgcolor="#FFFF00";></style><center>',
    '<center><style bgcolor="#FFFF00";><b>MARZO</b></style><center>',
    '<center><style bgcolor="#FFFF00";></style><center>',
    '<center><style bgcolor="#FFFF00";></style><center>',
    '<center><style bgcolor="#FFFF00";><b>ABRIL</b></style><center>',
    '<center><style bgcolor="#FFFF00";></style><center>',
    '<center><style bgcolor="#FFFF00";></style><center>',
    '<center><style bgcolor="#FFFF00";><b>MAYO</b></style><center>',
    '<center><style bgcolor="#FFFF00";></style><center>',
    '<center><style bgcolor="#FFFF00";></style><center>',
    '<center><style bgcolor="#FFFF00";><b>JUNIO</b></style><center>',
    '<center><style bgcolor="#FFFF00";></style><center>',
    '<center><style bgcolor="#FFFF00";></style><center>',
    '<center><style bgcolor="#FFFF00";><b>JULIO</b></style><center>',
    '<center><style bgcolor="#FFFF00";></style><center>',
    '<center><style bgcolor="#FFFF00";></style><center>',
    '<center><style bgcolor="#FFFF00";><b>AGOSTO</b></style><center>',
    '<center><style bgcolor="#FFFF00";></style><center>',
    '<center><style bgcolor="#FFFF00";></style><center>',
    '<center><style bgcolor="#FFFF00";><b>SEPTIEMBRE</b></style><center>',
    '<center><style bgcolor="#FFFF00";></style><center>',
    '<center><style bgcolor="#FFFF00";></style><center>',
    '<center><style bgcolor="#FFFF00";><b>OCTUBRE</b></style><center>',
    '<center><style bgcolor="#FFFF00";></style><center>',
    '<center><style bgcolor="#FFFF00";></style><center>',
    '<center><style bgcolor="#FFFF00";><b>NOVIEMBRE</b></style><center>',
    '<center><style bgcolor="#FFFF00";></style><center>',
    '<center><style bgcolor="#FFFF00";></style><center>',
    '<center><style bgcolor="#FFFF00";><b>DICIEMBRE</b></style><center>',
    '<center><style bgcolor="#FFFF00";></style><center>',
    '<center><style bgcolor="#FFFF00";></style><center>',
    '<center><style bgcolor="#FFFF00";></style><center>',
    '<center><style bgcolor="#FFFF00";></style><center>',
    '<center><style bgcolor="#FFFF00";></style><center>'
];
$titles = [];
$table = [];
$enero_total_facturado = 0;
$enero_total_no_facturado = 0;
$enero_total = 0;
$febrero_total_facturado = 0;
$febrero_total_no_facturado = 0;
$febrero_total = 0;
$marzo_total_facturado = 0;
$marzo_total_no_facturado = 0;
$marzo_total = 0;
$abril_total_facturado = 0;
$abril_total_no_facturado = 0;
$abril_total = 0;
$mayo_total_facturado = 0;
$mayo_total_no_facturado = 0;
$mayo_total = 0;
$junio_total_facturado = 0;
$junio_total_no_facturado = 0;
$junio_total = 0;
$julio_total_facturado = 0;
$julio_total_no_facturado = 0;
$julio_total = 0;
$agosto_total_facturado = 0;
$agosto_total_no_facturado = 0;
$agosto_total = 0;
$septiembre_total_facturado = 0;
$septiembre_total_no_facturado = 0;
$septiembre_total = 0;
$octubre_total_facturado = 0;
$octubre_total_no_facturado = 0;
$octubre_total = 0;
$noviembre_total_facturado = 0;
$noviembre_total_no_facturado = 0;
$noviembre_total = 0;
$diciembre_total_facturado = 0;
$diciembre_total_no_facturado = 0;
$diciembre_total = 0;
$total_facturado = 0;
$total_no_facturado = 0;
$total_global = 0;
///Recorre la respuesta de la BD
foreach ($table1 as $r) {
    $r['ENERO_FACTURADO'] = $r['ENERO_FACTURADO'] == 0 ? 0 : $r['ENERO_FACTURADO'];
    $r['ENERO_NO_FACTURADO'] = $r['ENERO_NO_FACTURADO'] == 0 ? 0 : $r['ENERO_NO_FACTURADO'];
    $r['FEBRERO_FACTURADO'] = $r['FEBRERO_FACTURADO'] == 0 ? 0 : $r['FEBRERO_FACTURADO'];
    $r['FEBRERO_NO_FACTURADO'] = $r['FEBRERO_NO_FACTURADO'] == 0 ? 0 : $r['FEBRERO_NO_FACTURADO'];
    $r['MARZO_FACTURADO'] = $r['MARZO_FACTURADO'] == 0 ? 0 : $r['MARZO_FACTURADO'];
    $r['MARZO_NO_FACTURADO'] = $r['MARZO_NO_FACTURADO'] == 0 ? 0 : $r['MARZO_NO_FACTURADO'];
    $r['ABRIL_FACTURADO'] = $r['ABRIL_FACTURADO'] == 0 ? 0 : $r['ABRIL_FACTURADO'];
    $r['ABRIL_NO_FACTURADO'] = $r['ABRIL_NO_FACTURADO'] == 0 ? 0 : $r['ABRIL_NO_FACTURADO'];
    $r['MAYO_FACTURADO'] = $r['MAYO_FACTURADO'] == 0 ? 0 : $r['MAYO_FACTURADO'];
    $r['MAYO_NO_FACTURADO'] = $r['MAYO_NO_FACTURADO'] == 0 ? 0 : $r['MAYO_NO_FACTURADO'];
    $r['JUNIO_FACTURADO'] = $r['JUNIO_FACTURADO'] == 0 ? 0 : $r['JUNIO_FACTURADO'];
    $r['JUNIO_NO_FACTURADO'] = $r['JUNIO_NO_FACTURADO'] == 0 ? 0 : $r['JUNIO_NO_FACTURADO'];
    $r['JULIO_FACTURADO'] = $r['JULIO_FACTURADO'] == 0 ? 0 : $r['JULIO_FACTURADO'];
    $r['JULIO_NO_FACTURADO'] = $r['JULIO_NO_FACTURADO'] == 0 ? 0 : $r['JULIO_NO_FACTURADO'];
    $r['AGOSTO_FACTURADO'] = $r['AGOSTO_FACTURADO'] == 0 ? 0 : $r['AGOSTO_FACTURADO'];
    $r['AGOSTO_NO_FACTURADO'] = $r['AGOSTO_NO_FACTURADO'] == 0 ? 0 : $r['AGOSTO_NO_FACTURADO'];
    $r['SEPTIEMBRE_FACTURADO'] = $r['SEPTIEMBRE_FACTURADO'] == 0 ? 0 : $r['SEPTIEMBRE_FACTURADO'];
    $r['SEPTIEMBRE_NO_FACTURADO'] = $r['SEPTIEMBRE_NO_FACTURADO'] == 0 ? 0 : $r['SEPTIEMBRE_NO_FACTURADO'];
    $r['OCTUBRE_FACTURADO'] = $r['OCTUBRE_FACTURADO'] == 0 ? 0 : $r['OCTUBRE_FACTURADO'];
    $r['OCTUBRE_NO_FACTURADO'] = $r['OCTUBRE_NO_FACTURADO'] == 0 ? 0 : $r['OCTUBRE_NO_FACTURADO'];
    $r['NOVIEMBRE_FACTURADO'] = $r['NOVIEMBRE_FACTURADO'] == 0 ? 0 : $r['NOVIEMBRE_FACTURADO'];
    $r['NOVIEMBRE_NO_FACTURADO'] = $r['NOVIEMBRE_NO_FACTURADO'] == 0 ? 0 : $r['NOVIEMBRE_NO_FACTURADO'];
    $r['DICIEMBRE_FACTURADO'] = $r['DICIEMBRE_FACTURADO'] == 0 ? 0 : $r['DICIEMBRE_FACTURADO'];
    $r['DICIEMBRE_NO_FACTURADO'] = $r['DICIEMBRE_NO_FACTURADO'] == 0 ? 0 : $r['DICIEMBRE_NO_FACTURADO'];
        
    if($flagItent){
        foreach ($r as $clave => $valor) {
            //La primera ves que entra se crea el array de titulos
            if ($flagItent) {
                $titles[] = "<center><style bgcolor=\"#FFFF00\";><b>" . str_replace("_"," ",$clave) . "<b></style><center>";
            }    
        }
        $flagItent = false;
        $table[] = $months;
        $table[] = $titles;

        $table[] = $r;
    }else{
        $table[] = $r;
    }
    $enero_total_facturado += $r['ENERO_FACTURADO'];
    $enero_total_no_facturado += $r['ENERO_NO_FACTURADO'];
    $enero_total += $r['ENERO_FACTURADO'] + $r['ENERO_NO_FACTURADO'];;
    $febrero_total_facturado += $r['FEBRERO_NO_FACTURADO'];
    $febrero_total_no_facturado += $r['FEBRERO_NO_FACTURADO'];
    $febrero_total += $r['FEBRERO_NO_FACTURADO'] + $r['FEBRERO_NO_FACTURADO'];
    $marzo_total_facturado += $r['MARZO_FACTURADO'];
    $marzo_total_no_facturado += $r['MARZO_NO_FACTURADO'];
    $marzo_total += $r['MARZO_FACTURADO'] + $r['MARZO_NO_FACTURADO'];
    $abril_total_facturado += $r['ABRIL_FACTURADO'];
    $abril_total_no_facturado += $r['ABRIL_NO_FACTURADO'];
    $abril_total += $r['ABRIL_FACTURADO'] + $r['ABRIL_NO_FACTURADO'];
    $mayo_total_facturado += $r['MAYO_FACTURADO'];
    $mayo_total_no_facturado += $r['MAYO_NO_FACTURADO'];
    $mayo_total += $r['MAYO_FACTURADO'] + $r['MAYO_NO_FACTURADO'];
    $junio_total_facturado += $r['JUNIO_FACTURADO'];
    $junio_total_no_facturado += $r['JUNIO_NO_FACTURADO'];
    $junio_total += $r['JUNIO_FACTURADO'] + $r['JUNIO_NO_FACTURADO'];
    $julio_total_facturado += $r['JULIO_FACTURADO'];
    $julio_total_no_facturado += $r['JULIO_NO_FACTURADO'];
    $julio_total += $r['JULIO_FACTURADO'] + $r['JULIO_NO_FACTURADO'];
    $agosto_total_facturado += $r['AGOSTO_FACTURADO'];
    $agosto_total_no_facturado += $r['AGOSTO_NO_FACTURADO'];
    $agosto_total += $r['AGOSTO_FACTURADO'] + $r['AGOSTO_NO_FACTURADO'];
    $septiembre_total_facturado += $r['SEPTIEMBRE_FACTURADO'];
    $septiembre_total_no_facturado += $r['SEPTIEMBRE_NO_FACTURADO'];
    $septiembre_total += $r['SEPTIEMBRE_FACTURADO'] + $r['SEPTIEMBRE_NO_FACTURADO'];
    $octubre_total_facturado += $r['OCTUBRE_FACTURADO'];
    $octubre_total_no_facturado += $r['OCTUBRE_NO_FACTURADO'];
    $octubre_total += $r['OCTUBRE_FACTURADO'] + $r['OCTUBRE_NO_FACTURADO'];
    $noviembre_total_facturado += $r['NOVIEMBRE_FACTURADO'];
    $noviembre_total_no_facturado += $r['NOVIEMBRE_NO_FACTURADO'];
    $noviembre_total += $r['NOVIEMBRE_FACTURADO'] + $r['NOVIEMBRE_NO_FACTURADO'];
    $diciembre_total_facturado += $r['DICIEMBRE_FACTURADO'];
    $diciembre_total_no_facturado += $r['DICIEMBRE_NO_FACTURADO'];
    $diciembre_total += $r['DICIEMBRE_FACTURADO'] + $r['DICIEMBRE_NO_FACTURADO'];
    $total_facturado += $r['TOTAL'];
    $total_no_facturado += $r['TOTAL_NO_FACTURADO'];
    $total_global += $r['TOTAL_GLOBAL'];
}
$subtotals = [
    '',
    '',
    '',
    '<center><style bgcolor="#FFFF00";><b>TOTALES<b></style><center>',
    $enero_total_facturado,
    $enero_total_no_facturado,
    $enero_total,
    $febrero_total_facturado,
    $febrero_total_no_facturado,
    $febrero_total,
    $marzo_total_facturado,
    $marzo_total_no_facturado,
    $marzo_total,
    $abril_total_facturado,
    $abril_total_no_facturado,
    $abril_total,
    $mayo_total_facturado,
    $mayo_total_no_facturado,
    $mayo_total,
    $junio_total_facturado,
    $junio_total_no_facturado,
    $junio_total,
    $julio_total_facturado,
    $julio_total_no_facturado,
    $julio_total,
    $agosto_total_facturado,
    $agosto_total_no_facturado,
    $agosto_total,
    $septiembre_total_facturado,
    $septiembre_total_no_facturado,
    $septiembre_total,
    $octubre_total_facturado,
    $octubre_total_no_facturado,
    $octubre_total,
    $noviembre_total_facturado,
    $noviembre_total_no_facturado,
    $noviembre_total,
    $diciembre_total_facturado,
    $diciembre_total_no_facturado,
    $diciembre_total,
    $total_facturado,
    $total_no_facturado,
    $total_global
];
$table[] = [];
$table[] = $subtotals;


$xlsx = Shuchkin\SimpleXLSXGen::fromArray($table,"Reporte Histórico Ventas")
    ->mergeCells('A1:A2')
    ->mergeCells('B1:B2')
    ->mergeCells('C1:C2')
    ->mergeCells('D1:D2')
    ->mergeCells('E1:G1')
    ->mergeCells('H1:J1')
    ->mergeCells('K1:M1')
    ->mergeCells('N1:P1')
    ->mergeCells('Q1:S1')
    ->mergeCells('T1:V1')
    ->mergeCells('W1:Y1')
    ->mergeCells('Z1:AB1')
    ->mergeCells('AC1:AE1')
    ->mergeCells('AF1:AH1')
    ->mergeCells('AI1:AK1')
    ->mergeCells('AL1:AN1')
    ->downloadAs('Reporte Histórico Ventas ' . $nameFile . '.xlsx')
    ;
//$xlsx->downloadAs('Reporte Histórico Ventas ' . $nameFile . '.xlsx');

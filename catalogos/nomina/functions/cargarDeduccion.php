<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

$token = $_POST['csr_token_UT5JP'];

if(empty($_SESSION['token_ld10d'])) {
    echo "fallo";
    return;           
}

if (!hash_equals($_SESSION['token_ld10d'], $token)) {
    echo "fallo";
    return;
}

$json = new \stdClass();
require_once '../../../include/db-conn.php';


$idDeduccion = $_POST['idDeduccion'];

$stmt = $conn->prepare("SELECT td.id, td.codigo, td.concepto, rtd.clave FROM tipo_deduccion as td LEFT JOIN relacion_tipo_deduccion as rtd ON rtd.tipo_deduccion_id = td.id AND rtd.empresa_id = ".$_SESSION['IDEmpresa']." WHERE td.id = :idDeduccion");
$stmt->bindValue(":idDeduccion", $idDeduccion);
$stmt->execute();
$deduccion = $stmt->fetch();

/*
$stmt = $conn->prepare("SELECT id, tipo_base, cantidad FROM relacion_base_deduccion WHERE tipo_deduccion_id = :idDeduccion AND empresa_id = ".$_SESSION['IDEmpresa']);
$stmt->bindValue(":idDeduccion", $idDeduccion);
$stmt->execute();
$existe = $stmt->rowCount();
$row_deduccion = $stmt->fetch();

if($existe > 0){
    $tipo_base = $row_deduccion['tipo_base'];
    $cantidad_base = $row_deduccion['cantidad'];
}
else{
    $tipo_base = 0;
    $cantidad_base = 0;
}

$stmt = $conn->prepare("SELECT rsd.id, s.sucursal, rsd.tipo_base, rsd.cantidad FROM relacion_sucursal_deduccion as rsd INNER JOIN sucursales as s ON s.id = rsd.sucursal_id WHERE rsd.tipo_deduccion_id = :tipo_deduccion_id  AND rsd.empresa_id = :empresa_id");
$stmt->bindValue(":tipo_deduccion_id", $idDeduccion);
$stmt->bindValue(":empresa_id", $_SESSION['IDEmpresa']);
$stmt->execute();
$row_sucursales= $stmt->fetchAll();

$sucursales = "";
foreach ($row_sucursales as $r) {

    if($r['tipo_base'] == 1){
        $tipo = "%";
        $cantidad_bs = number_format($r['cantidad'],0,'.',',');
    }
    else{
        $tipo = "$";
        $cantidad_bs = $r['cantidad'];
    }
    
    $sucursales .= '<div class="row" id="sucursal_'.$r['id'].'">
                      <div class="col-lg-9">'.$r['sucursal'].' - '.$tipo.' - '.$cantidad_bs.'</div>
                      <div class="col-lg-3 text-right"><i class="fas fa-trash-alt pointer" onclick="eliminarSucursal('.$r['id'].');"></i></div>
                    </div>';
}

if($tipo_base == 1){
    $cantidad = number_format($cantidad_base,0,'.',',');
}
else{
    $cantidad = $cantidad_base;
}*/

$json->concepto = $deduccion['concepto'];
$json->clave = $deduccion['clave'];
/*$json->existe_global = $existe;
$json->tipo_base = $tipo_base;
$json->cantidad_base = $cantidad;
$json->sucursales = $sucursales;*/
$json = json_encode($json);
echo $json;
?>

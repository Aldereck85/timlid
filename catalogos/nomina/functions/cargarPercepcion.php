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


$idPercepcion = $_POST['idPercepcion'];

$stmt = $conn->prepare("SELECT tp.id, tp.codigo, tp.concepto, rtp.clave FROM tipo_percepcion as tp LEFT JOIN relacion_tipo_percepcion as rtp ON rtp.tipo_percepcion_id = tp.id AND rtp.empresa_id = ".$_SESSION['IDEmpresa']." WHERE tp.id = :idPercepcion");
$stmt->bindValue(":idPercepcion", $idPercepcion);
$stmt->execute();
$percepcion = $stmt->fetch();

$stmt = $conn->prepare("SELECT id, tipo_base, cantidad FROM relacion_base_percepcion WHERE relacion_concepto_percepcion_id = :idPercepcion AND empresa_id = ".$_SESSION['IDEmpresa']);
$stmt->bindValue(":idPercepcion", $idPercepcion);
$stmt->execute();
$existe = $stmt->rowCount();
$row_percepcion = $stmt->fetch();

if($existe > 0){
    $tipo_base = $row_percepcion['tipo_base'];
    $cantidad_base = $row_percepcion['cantidad'];
}
else{
    $tipo_base = 0;
    $cantidad_base = 0;
}

$stmt = $conn->prepare("SELECT rsp.id, s.sucursal, rsp.tipo_base, rsp.cantidad FROM relacion_sucursal_percepcion as rsp INNER JOIN sucursales as s ON s.id = rsp.sucursal_id WHERE rsp.relacion_concepto_percepcion_id = :tipo_percepcion_id  AND rsp.empresa_id = :empresa_id");
$stmt->bindValue(":tipo_percepcion_id", $idPercepcion);
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
}

$json->concepto = $percepcion['concepto'];
$json->clave = $percepcion['clave'];
$json->existe_global = $existe;
$json->tipo_base = $tipo_base;
$json->cantidad_base = $cantidad;
$json->sucursales = $sucursales;
$json = json_encode($json);
echo $json;
?>

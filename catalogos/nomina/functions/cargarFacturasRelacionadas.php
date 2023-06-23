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

$idEmpleado = $_POST['idEmpleado'];
$tipo = $_POST['tipo']; //1 ordinaria 2 extraordinaria
require_once '../../../include/db-conn.php';

echo '<option value="0" disabled selected>Selecciona un cfdi</option>';

$stmt = $conn->prepare('SELECT bcen.id, bcen.idFactura, bcen.uuid 
        FROM bitacora_cfdi_eliminado_nomina as bcen 
        INNER JOIN nomina_empleado as ne ON ne.PKNomina = bcen.nomina_empleado_id
        INNER JOIN nomina as n ON n.id = ne.FKNomina
        INNER JOIN nomina_principal as np ON n.fk_nomina_principal = np.id AND np.tipo_id = :tipo
        WHERE ne.FKEmpleado = :empleado_id ORDER BY bcen.id');
$stmt->bindValue(":tipo",$tipo);
$stmt->bindValue(":empleado_id",$idEmpleado);
$stmt->execute();
$bitacora = $stmt->fetchAll();
foreach ($bitacora as $b) {
    echo '<option value="'.$b['uuid'].'&'.$b["id"].'&'.$b["idFactura"].'">'.$b["uuid"].'</option>';
}



?>

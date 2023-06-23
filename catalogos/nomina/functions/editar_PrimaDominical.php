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

require_once '../../../include/db-conn.php';

date_default_timezone_set('America/Mexico_City');

$primaDominical = $_POST['primaDominical'];
$idNominaEmpleado = $_POST['idNominaEmpleado'];
$idEmpleado = $_POST['idEmpleado'];
$idNomina = $_POST['idNomina'];
$fecha = date("Y-m-d H:i:s");
$idUsuario = $_SESSION['PKUsuario'];
$idEmpresa = $_SESSION['IDEmpresa'];
$idDetalleNomina = $_POST['idDetalleNomina'];

try {
    $stmt = $conn->prepare('UPDATE detalle_nomina_percepcion_empleado SET  importe = :importe, fecha_edicion = :fecha_edicion, usuario_edicion = :usuario_edicion WHERE id = :idDetalleNomina');
    $stmt->bindValue(':importe', $primaDominical);
    $stmt->bindValue(':fecha_edicion', $fecha);
    $stmt->bindValue(':usuario_edicion', $idUsuario);
    $stmt->bindValue(':idDetalleNomina', $idDetalleNomina);


    if($stmt->execute()){

      $modo = 2;//para agregar o restar cantidades adicionales al total de percepciones 
      require_once("calculoImpuestos.php");

      echo "exito";
    }else{
      echo "fallo";
    }
    
    
} catch (PDOException $ex) {
    echo "fallo"; //echo $ex->getMessage();
}

?>

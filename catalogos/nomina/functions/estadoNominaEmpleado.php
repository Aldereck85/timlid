<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

date_default_timezone_set('America/Mexico_City');

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

$idNomina = $_POST['idNomina'];
$idEmpleado = $_POST['idEmpleado'];
$activo = $_POST['activo'];
$idEmpresa = $_SESSION['IDEmpresa'];

try {

    $stmt = $conn->prepare('UPDATE nomina_empleado SET Exento = :activo WHERE FKEmpleado = :FKEmpleado AND FKNomina = :idNomina ');
    $stmt->bindValue(':activo', $activo);
    $stmt->bindValue(':FKEmpleado', $idEmpleado);
    $stmt->bindValue(':idNomina', $idNomina);

    if($stmt->execute()){

      $stmt = $conn->prepare(' SELECT SUM(estadoTimbrado) as total_timbrado, COUNT(PKNomina)  as total_empleados FROM nomina_empleado WHERE FKNomina = :idNomina AND Exento = 0');
      $stmt->bindValue(':idNomina', $idNomina);
      $stmt->execute();
      $nomina_completa = $stmt->fetch();

      if($nomina_completa['total_timbrado'] == $nomina_completa['total_empleados']){
        $stmt = $conn->prepare(' UPDATE nomina SET estatus = 2 WHERE id = :idNomina AND empresa_id = :idEmpresa');
        $stmt->bindValue(':idNomina', $idNomina);
        $stmt->bindValue(':idEmpresa', $idEmpresa);
        $stmt->execute();
      }
      else{
        $stmt = $conn->prepare(' UPDATE nomina SET estatus = 1 WHERE id = :idNomina AND empresa_id = :idEmpresa');
        $stmt->bindValue(':idNomina', $idNomina);
        $stmt->bindValue(':idEmpresa', $idEmpresa);
        $stmt->execute();
      }

      echo "exito";
    }else{
      echo "fallo";
    }
    
} catch (PDOException $ex) {
    echo "fallo"; //$ex->getMessage();
}

?>

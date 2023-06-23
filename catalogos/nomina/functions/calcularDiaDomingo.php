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


$idEmpleado = $_POST['idEmpleado'];

try {
    $stmt = $conn->prepare('SELECT dle.Sueldo, pp.DiasPago FROM datos_laborales_empleado as dle  INNER JOIN periodo_pago as pp ON pp.PKPeriodo_pago = dle.FKPeriodo WHERE FKEmpleado = :idEmpleado');
    $stmt->bindValue(':idEmpleado', $idEmpleado);
    
    if($stmt->execute()){
        $datosEmpleado = $stmt->fetch();
        $salarioDiario = bcdiv(($datosEmpleado['Sueldo']/$datosEmpleado['DiasPago']) * .25 ,1,2);

        echo number_format($salarioDiario,2); 
    }
    else{
        echo "fallo"; 
    }
    


    

} catch (PDOException $ex) {
    echo "fallo"; //$ex->getMessage();
}

?>

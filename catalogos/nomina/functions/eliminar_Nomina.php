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

try {

    $stmt = $conn->prepare('SELECT COUNT(id) as numero_registros, SUM(estatus) as sum_estatus FROM nomina WHERE fk_nomina_principal = :fk_nomina_principal');
    $stmt->bindValue(':fk_nomina_principal', $idNomina);
    $stmt->execute();
    $row = $stmt->fetch();

    if($row['sum_estatus'] > $row['numero_registros']){
        echo "fallo-cancelacion";
        return;
    }

    $stmt = $conn->prepare('SELECT ne.estadoTimbrado FROM nomina_principal as np INNER JOIN nomina as n ON n.fk_nomina_principal = np.id INNER JOIN nomina_empleado as ne ON ne.FKNomina = n.id WHERE np.id = :idNomina');
    $stmt->bindValue(':idNomina', $idNomina);
    $stmt->execute();
    $row = $stmt->fetchAll();

    $facturasTimbradas = 0;
    foreach($row as $r){
        if($r['estadoTimbrado'] == 1){
            $facturasTimbradas++;
        }
    }

    if($facturasTimbradas > 0){
        echo "fallo-cancelacion";
        return;
    }

    $stmt = $conn->prepare('DELETE FROM nomina_principal WHERE id = :idNomina');
    $stmt->bindValue(':idNomina', $idNomina);

    if($stmt->execute()){
      echo "exito";
    }else{
      echo "fallo";
    }
    
} catch (PDOException $ex) {
    echo "fallo"; //$ex->getMessage();
}

?>

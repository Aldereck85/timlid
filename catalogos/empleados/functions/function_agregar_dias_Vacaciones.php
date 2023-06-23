<?php
session_start();

if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 1 || $_SESSION["FKRol"] == 4)){

require_once('../../../include/db-conn.php');

$dias_vacaciones_agrega = $_POST['dias_trabajo'];
$fkEmpleado = $_POST['IdEmpleado'];

  try{

    $stmt = $conn->prepare('SELECT Dias_de_Vacaciones FROM datos_laborales_empleado WHERE FKEmpleado = :FKEmpleado');
    $stmt->bindValue(':FKEmpleado',$fkEmpleado);
    $stmt->execute();
    $row = $stmt->fetch();
    $dias_vacaciones_ant = $row['Dias_de_Vacaciones'];

    $dias_vacaciones_final = $dias_vacaciones_ant + $dias_vacaciones_agrega;

    $stmt = $conn->prepare('UPDATE  datos_laborales_empleado SET Dias_de_Vacaciones = :dias_vacaciones WHERE FKEmpleado = :FKEmpleado');
    $stmt->bindValue(':dias_vacaciones',$dias_vacaciones_final);
    $stmt->bindValue(':FKEmpleado',$fkEmpleado);
    
    if($stmt->execute()){
        echo "Exito";
    }
    else{
        echo "Fallo";
    }

 }catch(PDOException $ex){
    echo "Fallo";//$ex->getMessage();
 }

}else {
header("location:../../dashboard.php");
}
  
?>

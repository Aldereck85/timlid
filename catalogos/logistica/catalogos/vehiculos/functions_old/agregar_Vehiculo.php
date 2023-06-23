<?php
session_start();

if (isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 1)) {
    require_once '../../../include/db-conn.php';
        $linea = $_POST['txtLinea'];
        $marca = $_POST['txtMarca'];
        $serie = $_POST['txtSerie'];
        $placas = $_POST['txtPlacas'];
        $modelo = $_POST['txtModelo'];
        $puertas = $_POST['txtPuertas'];
        $cilindros = $_POST['txtCilindros'];
        $odometro = $_POST['txtOdometro'];
        $kilometro = $_POST['txtKilometros'];
        $motor = $_POST['txtMotor'];
        $color = $_POST['txtColor'];
        $combustible = $_POST['txtCombustible'];
        $transmision = $_POST['txtTransmision'];
        $usuario = $_POST['cmbUsuario'];
        //echo $sueldo;
        try {
            $stmt = $conn->prepare('INSERT INTO vehiculos (Linea,Serie,Marca,Placas,Color,Modelo,Puertas,Cilindros,Odometro,Kilometraje_para_Servicio,Motor,Combustible,Transmision, FKUsuario) VALUES(:linea,:serie,:marca,:placas,:color,:modelo,:puertas,:cilindros,:odometro,:kilometro,:motor,:combustible,:transmision, :usuario)');
            $stmt->bindValue(':linea', $linea);
            $stmt->bindValue(':serie', $serie);
            $stmt->bindValue(':marca', $marca);
            $stmt->bindValue(':placas', $placas);
            $stmt->bindValue(':color', $color);
            $stmt->bindValue(':modelo', $modelo);
            $stmt->bindValue(':puertas', $puertas);
            $stmt->bindValue(':cilindros', $cilindros);
            $stmt->bindValue(':odometro', $odometro);
            $stmt->bindValue(':kilometro', $kilometro);
            $stmt->bindValue(':motor', $motor);
            $stmt->bindValue(':combustible', $combustible);
            $stmt->bindValue(':transmision', $transmision);
            $stmt->bindValue(':usuario', $usuario);
            if($stmt->execute()){
              echo "exito";
            }else{
              echo "fallo";
            }
            
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
   
} else {
    header("location:../../dashboard.php");
}

?>

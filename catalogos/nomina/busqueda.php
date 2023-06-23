<?php
    require_once('../../include/db-conn.php');
    $fechaHoy = date('Y-m-d', time());
    $fecha = $fechaHoy." 06:00:00";
    $stmt = $conn->prepare('SELECT * FROM semanas_checador WHERE FechaInicio <= :fechaInicio AND FechaTermino >= :fechaTermino');
    $stmt->bindValue(':fechaInicio',$fecha);
    $stmt->bindValue(':fechaTermino',$fecha);
    $stmt->execute();
    $row = $stmt->fetch();
    $semana = $row['PKChecador'];
    echo "Semana = ".$semana;

    //SELECT * FROM semanas_checador WHERE FechaInicio <= '2019-08-29' AND FechaTermino >= '2019-08-29'
?>



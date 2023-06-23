<?php
session_start();
require_once '../../../../include/db-conn.php';
date_default_timezone_set('America/Mexico_City');
$now = date("Y-m-d H:i:s");
$id = (int) $_POST['idTurnoU'];
$turno = $_POST['txtTurnoU'];
$entrada = $_POST['txtEntradaU'];
$salida = $_POST['txtSalidaU'];
$diasC = $_POST['cmbDiasU'];
$dias = $_POST['cmbDiasU'];
$usuario = (int) $_REQUEST['usuario'];
$array2 = $dias[0];
$hrsTrabajadas = $_REQUEST['hrsTrabajadas'];
$tipo_jornada = $_REQUEST['tipo_jornada'];

$json = json_decode($diasC);

$diasTrabajados = 0;
foreach($json as $j){

    if($j){
      $diasTrabajados++;
    }

}

$comida = $_POST['txtComidaU'];
try {
    $stmt = $conn->prepare('UPDATE turnos set Turno = :turno, tipo_jornada_id = :tipo_jornada_id, Entrada = :entrada, Salida = :salida,Dias_de_trabajo = :dias, Num_Dias_Trabajo = :Num_Dias_Trabajo, TiempoComida = :comida,updated_at = :updated_at, usuario_edicion_id = :usuario, HorasTrabajo = :horasTrabajo WHERE PKTurno = :id');
    $stmt->bindValue(':turno', $turno);
    $stmt->bindValue(':tipo_jornada_id', $tipo_jornada);
    $stmt->bindValue(':entrada', $entrada);
    $stmt->bindValue(':salida', $salida);
    $stmt->bindValue(':dias', $diasC);  
    $stmt->bindValue(':Num_Dias_Trabajo', $diasTrabajados); 
    $stmt->bindValue(':comida', $comida);
    $stmt->bindValue(':updated_at', $now);
    $stmt->bindValue(':horasTrabajo', $hrsTrabajadas);
    $stmt->bindValue(':usuario', $usuario, PDO::PARAM_INT);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    if ($stmt->execute()) {
         echo "1";
    } else {
         echo "0";
    }
} catch (PDOException $ex) {
    echo $ex->getMessage();
}

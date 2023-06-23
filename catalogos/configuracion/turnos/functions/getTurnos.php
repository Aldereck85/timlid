<?php
session_start();

if (isset($_POST['id'])) {
    require_once '../../../../include/db-conn.php';
    $json = new \stdClass();
    $id = $_POST['id'];

    $stmt = $conn->prepare('SELECT *, DATE_FORMAT(TiempoComida,"%H:%i") TComida FROM turnos WHERE PKTurno = :id');
    $stmt->execute(array(':id' => $id));
    //$stmt->execute();
    $turno = $stmt->fetch(PDO::FETCH_ASSOC);
    $turno['Dias_de_trabajo'] = json_decode($turno['Dias_de_trabajo'], true);

    $stmt = $conn->prepare("SELECT id, tipo_jornada FROM tipo_jornada");
    $stmt->execute();
    $row = $stmt->fetchAll();
    $tipo_jornada = "";
    foreach ($row as $r) { //Mostrar usuarios
      $tipo_jornada.= '<option value="'.$r['id'].'" ';

        if($turno['tipo_jornada_id'] == $r['id']){
            $tipo_jornada.= 'selected';
        }

      $tipo_jornada.= ' >'.$r['tipo_jornada'].'</option>';
    }

    $turno['tipo_jornada_id'] = $tipo_jornada; 

    echo json_encode($turno);
}

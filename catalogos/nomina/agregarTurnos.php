<?php 
session_start();

date_default_timezone_set('America/Mexico_City');

require_once '../../include/db-conn.php';

$dias_trabajo = '{"lunes":true,"martes":true,"miercoles":true,"jueves":true,"viernes":true,"sabado":true,"domingo":false}';
$num_dias_trabajo = 6;
$horas_trabajo = 8;
$tiempo_comida = "00:00:00";
$estatus = 1;
$creacion_fecha = date("Y-m-d H:i:s");
$usuario = $_SESSION['PKUsuario'];
$idEmpresa =  $_SESSION['IDEmpresa'];

$stmt = $conn->prepare("SELECT * FROM turnos WHERE Turno = 'Diurna' OR Turno = 'Diurno' AND empresa_id = :empresa_id AND estatus = 1");
$stmt->bindValue(':empresa_id', $idEmpresa );
$stmt->execute();
$cantDiurno = $stmt->rowCount();

if($cantDiurno < 1){

    $turno = "Diurno";
    $entrada = "06:00:00";
    $salida = "14:00:00";
    $tipo_jornada_id = 1;
    

    $stmt = $conn->prepare('INSERT INTO turnos (Turno, Entrada, Salida, Dias_de_trabajo, Num_Dias_Trabajo, HorasTrabajo, TiempoComida, tipo_jornada_id, empresa_id, estatus, created_at, updated_at, usuario_creacion_id, usuario_edicion_id) VALUES (:Turno, :Entrada, :Salida, :Dias_de_trabajo, :Num_Dias_Trabajo, :HorasTrabajo, :TiempoComida, :tipo_jornada_id, :empresa_id, :estatus, :created_at, :updated_at, :usuario_creacion_id, :usuario_edicion_id)');
    $stmt->bindValue(':Turno', $turno );
    $stmt->bindValue(':Entrada', $entrada );
    $stmt->bindValue(':Salida', $salida );
    $stmt->bindValue(':Dias_de_trabajo', $dias_trabajo);
    $stmt->bindValue(':Num_Dias_Trabajo', $num_dias_trabajo );
    $stmt->bindValue(':HorasTrabajo', $horas_trabajo );
    $stmt->bindValue(':TiempoComida', $tiempo_comida );
    $stmt->bindValue(':tipo_jornada_id', $tipo_jornada_id );
    $stmt->bindValue(':empresa_id', $idEmpresa );
    $stmt->bindValue(':estatus', $estatus );
    $stmt->bindValue(':created_at', $creacion_fecha );
    $stmt->bindValue(':updated_at', $creacion_fecha );
    $stmt->bindValue(':usuario_creacion_id', $usuario );
    $stmt->bindValue(':usuario_edicion_id', $usuario );
    $stmt->execute();
}

$stmt = $conn->prepare("SELECT * FROM turnos WHERE Turno = 'Nocturna' OR Turno = 'Nocturno' AND empresa_id = :empresa_id AND estatus = 1");
$stmt->bindValue(':empresa_id', $idEmpresa );
$stmt->execute();
$cantDiurno = $stmt->rowCount();

if($cantDiurno < 1){

    $turno = "Nocturno";
    $entrada = "22:00:00";
    $salida = "06:00:00";
    $tipo_jornada_id = 2;
    

    $stmt = $conn->prepare('INSERT INTO turnos (Turno, Entrada, Salida, Dias_de_trabajo, Num_Dias_Trabajo, HorasTrabajo, TiempoComida, tipo_jornada_id, empresa_id, estatus, created_at, updated_at, usuario_creacion_id, usuario_edicion_id) VALUES (:Turno, :Entrada, :Salida, :Dias_de_trabajo, :Num_Dias_Trabajo, :HorasTrabajo, :TiempoComida, :tipo_jornada_id, :empresa_id, :estatus, :created_at, :updated_at, :usuario_creacion_id, :usuario_edicion_id)');
    $stmt->bindValue(':Turno', $turno );
    $stmt->bindValue(':Entrada', $entrada );
    $stmt->bindValue(':Salida', $salida );
    $stmt->bindValue(':Dias_de_trabajo', $dias_trabajo);
    $stmt->bindValue(':Num_Dias_Trabajo', $num_dias_trabajo );
    $stmt->bindValue(':HorasTrabajo', $horas_trabajo );
    $stmt->bindValue(':TiempoComida', $tiempo_comida );
    $stmt->bindValue(':tipo_jornada_id', $tipo_jornada_id );
    $stmt->bindValue(':empresa_id', $idEmpresa );
    $stmt->bindValue(':estatus', $estatus );
    $stmt->bindValue(':created_at', $creacion_fecha );
    $stmt->bindValue(':updated_at', $creacion_fecha );
    $stmt->bindValue(':usuario_creacion_id', $usuario );
    $stmt->bindValue(':usuario_edicion_id', $usuario );
    $stmt->execute();
}


$stmt = $conn->prepare("SELECT * FROM turnos WHERE Turno = 'Mixta' OR Turno = 'Mixto' AND empresa_id = :empresa_id AND estatus = 1");
$stmt->bindValue(':empresa_id', $idEmpresa );
$stmt->execute();
$cantDiurno = $stmt->rowCount();

if($cantDiurno < 1){

    $turno = "Mixto";
    $entrada = "10:00:00";
    $salida = "18:00:00";
    $tipo_jornada_id = 3;
    

    $stmt = $conn->prepare('INSERT INTO turnos (Turno, Entrada, Salida, Dias_de_trabajo, Num_Dias_Trabajo, HorasTrabajo, TiempoComida, tipo_jornada_id, empresa_id, estatus, created_at, updated_at, usuario_creacion_id, usuario_edicion_id) VALUES (:Turno, :Entrada, :Salida, :Dias_de_trabajo, :Num_Dias_Trabajo, :HorasTrabajo, :TiempoComida, :tipo_jornada_id, :empresa_id, :estatus, :created_at, :updated_at, :usuario_creacion_id, :usuario_edicion_id)');
    $stmt->bindValue(':Turno', $turno );
    $stmt->bindValue(':Entrada', $entrada );
    $stmt->bindValue(':Salida', $salida );
    $stmt->bindValue(':Dias_de_trabajo', $dias_trabajo);
    $stmt->bindValue(':Num_Dias_Trabajo', $num_dias_trabajo );
    $stmt->bindValue(':HorasTrabajo', $horas_trabajo );
    $stmt->bindValue(':TiempoComida', $tiempo_comida );
    $stmt->bindValue(':tipo_jornada_id', $tipo_jornada_id );
    $stmt->bindValue(':empresa_id', $idEmpresa );
    $stmt->bindValue(':estatus', $estatus );
    $stmt->bindValue(':created_at', $creacion_fecha );
    $stmt->bindValue(':updated_at', $creacion_fecha );
    $stmt->bindValue(':usuario_creacion_id', $usuario );
    $stmt->bindValue(':usuario_edicion_id', $usuario );
    $stmt->execute();
}

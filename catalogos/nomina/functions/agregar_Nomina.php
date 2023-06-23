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

$idSucursal = $_POST['idSucursal'];
$idPeriodo = $_POST['idPeriodo'];
$idTipo = $_POST['idTipo'];
$fechaPago = $_POST['fechaPago'];
$fechaIni = $_POST['fechaIni'];
$fechaFin = $_POST['fechaFin'];
$ajustarMesCalendario = $_POST['ajustarmescalendario'];
$usuario = $_SESSION['PKUsuario'];
$fecha = date("Y-m-d H:i:s");
$idEmpresa = $_SESSION['IDEmpresa'];
$nominaConfidencial = $_POST['nominaConfidencial'];

function getAnio($fecha){
   $timestamp = strtotime($fecha);
   $anioActual = date("Y", $timestamp);

   return $anioActual;
}

function getMonth($fecha){
   $timestamp = strtotime($fecha);
   $mesActual = date("m", $timestamp);

   return $mesActual;
}

function getDay($fecha){
   $timestamp = strtotime($fecha);
   $diaActual = date("d", $timestamp);

   return $diaActual;
}

function getLastDay($fecha){
    $date = strtotime($fecha);
    $lastdate = strtotime(date("Y-m-t", $date ));  
    //$day = date("l", $lastdate);
      
    return $lastdate;
}

$stmt = $conn->prepare('SELECT id FROM nomina_principal WHERE YEAR(fecha_ini) = YEAR(:fecha_ini) AND sucursal_id = :sucursal_id AND periodo_id = :periodo_id AND tipo_id = :tipo_id AND empresa_id = :empresa_id AND confidencial = :confidencial');
$stmt->bindValue(':fecha_ini', $fechaIni);
$stmt->bindValue(':sucursal_id', $idSucursal);
$stmt->bindValue(':periodo_id', $idPeriodo);
$stmt->bindValue(':tipo_id', $idTipo);
$stmt->bindValue(':empresa_id', $idEmpresa);
$stmt->bindValue(':confidencial', $nominaConfidencial);
$stmt->execute();
$existe_nomina = $stmt->rowCount();

if($existe_nomina > 0){
    echo "existe_nomina";
    return;
}

try {

    $conn->beginTransaction();

    $stmt = $conn->prepare('SELECT no_nomina FROM nomina_principal WHERE empresa_id = '.$idEmpresa.' ORDER BY no_nomina DESC LIMIT 1');
    $stmt->execute();
    $row = $stmt->fetch();
    $no_nomina_empresa = $row['no_nomina'] + 1;

    $stmt = $conn->prepare('INSERT INTO nomina_principal (no_nomina, fecha_ini, sucursal_id, periodo_id, tipo_id, usuario_creacion_id, usuario_edicion_id, created_at, updated_at, empresa_id, confidencial) VALUES(:no_nomina, :fecha_ini, :sucursal_id, :periodo_id, :tipo_id, :usuario_creacion_id, :usuario_edicion_id, :created_at, :updated_at, :empresa_id, :confidencial)');
    $stmt->bindValue(':no_nomina', $no_nomina_empresa);
    $stmt->bindValue(':fecha_ini', $fechaIni);
    $stmt->bindValue(':sucursal_id', $idSucursal);
    $stmt->bindValue(':periodo_id', $idPeriodo);
    $stmt->bindValue(':tipo_id', $idTipo);
    $stmt->bindValue(':usuario_creacion_id', $usuario);
    $stmt->bindValue(':usuario_edicion_id', $usuario);
    $stmt->bindValue(':created_at', $fecha);
    $stmt->bindValue(':updated_at', $fecha);
    $stmt->bindValue(':empresa_id', $idEmpresa);
    $stmt->bindValue(':confidencial', $nominaConfidencial);
    $stmt->execute();

    $idNomina = $conn->lastInsertId();

    if($idTipo == 1){

                if($idPeriodo == 1){
                    $agregarDias = 6;
                }
                if($idPeriodo == 2){
                    $agregarDias = 13;
                }
                if($idPeriodo == 3){
                    $agregarDias = 14; //para reconocer que es por quincena
                }
                if($idPeriodo == 4){
                    $agregarDias = 2; //para reconocer que es por mes
                }

                $contador = 0;
                $periodoContador = 1;
                $fechaInicialPeriodo = $fechaIni;
                $ultima_nomina = 0;
                $idNominaAnterior = 0;
                $quincenaIni = 0;
                $ejecutaQuincena = 1;
                $anioInicialNomina = getAnio($fechaInicialPeriodo);
                /*echo "periodo ".$idPeriodo." --- ".$ajustarMesCalendario."<br>";
                return;*/
                do{ 
                    if(($idPeriodo == 1 || $idPeriodo == 2) || ($idPeriodo == 3 && $ajustarMesCalendario == 1)){

                        $anioNominaInicioActual = getAnio($fechaInicialPeriodo);

                        $fechaFinalPeriodo = date('Y-m-d', strtotime($fechaInicialPeriodo. ' + ' . $agregarDias . ' days'));

                        $anioNominaFinalActual = getAnio($fechaFinalPeriodo);

                        $mesActual = getMonth($fechaFinalPeriodo);

                        if(getMonth($fechaFinalPeriodo) > getMonth($fechaInicialPeriodo) || strtotime($fechaFinalPeriodo) > getLastDay($fechaInicialPeriodo) || strtotime($fechaFinalPeriodo) == getLastDay($fechaInicialPeriodo)){
                            $ultima_nomina = 1;
                        }                     
                        
                       if($idPeriodo == 3 && $ajustarMesCalendario == 1){

                            if($contador == 0 ){
                                if(!($anioInicialNomina != $anioNominaInicioActual  && $anioInicialNomina != $anioNominaFinalActual)){
                                    $stmt = $conn->prepare('INSERT INTO nomina (no_nomina, no_empleados, fecha_pago, fecha_inicio, fecha_fin, usuario_creacion_id, usuario_edicion_id, created_at, updated_at, total, estatus, empresa_id, ultima_nomina, fk_nomina_principal, id_nomina_anterior) VALUES(:no_nomina, :no_empleados, :fecha_pago, :fecha_inicio, :fecha_fin, :usuario_creacion_id, :usuario_edicion_id, :created_at, :updated_at, :total, :estatus, :empresa_id, :ultima_nomina, :fk_nomina_principal, :id_nomina_anterior)');
                                    $stmt->bindValue(':no_nomina', $periodoContador);
                                    $stmt->bindValue(':no_empleados', 0);
                                    $stmt->bindValue(':fecha_pago', $fechaFinalPeriodo);
                                    $stmt->bindValue(':fecha_inicio', $fechaInicialPeriodo);
                                    $stmt->bindValue(':fecha_fin', $fechaFinalPeriodo);
                                    $stmt->bindValue(':usuario_creacion_id', $usuario);
                                    $stmt->bindValue(':usuario_edicion_id', $usuario);
                                    $stmt->bindValue(':created_at', $fecha);
                                    $stmt->bindValue(':updated_at', $fecha);
                                    $stmt->bindValue(':total', 0);
                                    $stmt->bindValue(':estatus', 1);
                                    $stmt->bindValue(':empresa_id', $idEmpresa);
                                    $stmt->bindValue(':ultima_nomina', $ultima_nomina);
                                    $stmt->bindValue(':fk_nomina_principal', $idNomina);
                                    $stmt->bindValue(':id_nomina_anterior', $idNominaAnterior);
                                    $stmt->execute();

                                    $idNominaAnterior = $conn->lastInsertId();
                                }

                            }
                        }

                        if($idPeriodo == 1 || $idPeriodo == 2){

                                $stmt = $conn->prepare('INSERT INTO nomina (no_nomina, no_empleados, fecha_pago, fecha_inicio, fecha_fin, usuario_creacion_id, usuario_edicion_id, created_at, updated_at, total, estatus, empresa_id, ultima_nomina, fk_nomina_principal, id_nomina_anterior) VALUES(:no_nomina, :no_empleados, :fecha_pago, :fecha_inicio, :fecha_fin, :usuario_creacion_id, :usuario_edicion_id, :created_at, :updated_at, :total, :estatus, :empresa_id, :ultima_nomina, :fk_nomina_principal, :id_nomina_anterior)');
                                $stmt->bindValue(':no_nomina', $periodoContador);
                                $stmt->bindValue(':no_empleados', 0);
                                $stmt->bindValue(':fecha_pago', $fechaFinalPeriodo);
                                $stmt->bindValue(':fecha_inicio', $fechaInicialPeriodo);
                                $stmt->bindValue(':fecha_fin', $fechaFinalPeriodo);
                                $stmt->bindValue(':usuario_creacion_id', $usuario);
                                $stmt->bindValue(':usuario_edicion_id', $usuario);
                                $stmt->bindValue(':created_at', $fecha);
                                $stmt->bindValue(':updated_at', $fecha);
                                $stmt->bindValue(':total', 0);
                                $stmt->bindValue(':estatus', 1);
                                $stmt->bindValue(':empresa_id', $idEmpresa);
                                $stmt->bindValue(':ultima_nomina', $ultima_nomina);
                                $stmt->bindValue(':fk_nomina_principal', $idNomina);
                                $stmt->bindValue(':id_nomina_anterior', $idNominaAnterior);
                                $stmt->execute();

                                $idNominaAnterior = $conn->lastInsertId();
                         
                        }

                        $ultima_nomina = 0;
                        $fechaPagoPeriodo = $fechaFinalPeriodo;            
                        $fechaInicialPeriodo = date('Y-m-d', strtotime($fechaFinalPeriodo. ' + 1 days'));
                        $periodoContador++;

                        if( $anioInicialNomina != $anioNominaInicioActual  || $anioInicialNomina != $anioNominaFinalActual ){
                            $contador = 1;
                        }

                    }

                    if($idPeriodo == 3 && $ajustarMesCalendario == 0){

                        if($periodoContador == 1){
                            $mesAnterior = getMonth($fechaInicialPeriodo);
                        }
                        else{
                            $mesAnterior = getMonth($fechaFinalPeriodo);
                        }

                        if($quincenaIni == 0){
                            $diaActual = getDay($fechaIni);

                            if($diaActual > 14){
                                $ejecutaQuincena = 0;
                            }

                            $quincenaIni = 1;

                            $fechaFinalPeriodo = date('Y-m-d',getLastDay($fechaInicialPeriodo));
                        }

                        $mesActual = getMonth($fechaFinalPeriodo);

                        if($ejecutaQuincena == 1){

                            if($periodoContador == 1){
                                $mesAnterior = getMonth($fechaInicialPeriodo);
                            }
                            else{
                                $mesAnterior = getMonth($fechaFinalPeriodo);
                            }

                            $fechaFinalPeriodo = date('Y-m-d', strtotime($fechaInicialPeriodo. ' + 14 days'));

                            $mesActual = getMonth($fechaFinalPeriodo);

                            if(!($mesAnterior == 12 && $mesActual == 1)){
                                $stmt = $conn->prepare('INSERT INTO nomina (no_nomina, no_empleados, fecha_pago, fecha_inicio, fecha_fin, usuario_creacion_id, usuario_edicion_id, created_at, updated_at, total, estatus, empresa_id, ultima_nomina, fk_nomina_principal) VALUES(:no_nomina, :no_empleados, :fecha_pago, :fecha_inicio, :fecha_fin, :usuario_creacion_id, :usuario_edicion_id, :created_at, :updated_at, :total, :estatus, :empresa_id, :ultima_nomina, :fk_nomina_principal)');
                                $stmt->bindValue(':no_nomina', $periodoContador);
                                $stmt->bindValue(':no_empleados', 0);
                                $stmt->bindValue(':fecha_pago', $fechaFinalPeriodo);
                                $stmt->bindValue(':fecha_inicio', $fechaInicialPeriodo);
                                $stmt->bindValue(':fecha_fin', $fechaFinalPeriodo);
                                $stmt->bindValue(':usuario_creacion_id', $usuario);
                                $stmt->bindValue(':usuario_edicion_id', $usuario);
                                $stmt->bindValue(':created_at', $fecha);
                                $stmt->bindValue(':updated_at', $fecha);
                                $stmt->bindValue(':total', 0);
                                $stmt->bindValue(':estatus', 1);
                                $stmt->bindValue(':empresa_id', $idEmpresa);
                                $stmt->bindValue(':ultima_nomina', 0);
                                $stmt->bindValue(':fk_nomina_principal', $idNomina);
                                $stmt->execute();
                            }
                    //echo "Perido: ".$periodoContador." ---- ".$fechaInicialPeriodo." ----- ".$fechaFinalPeriodo."<br>";
                                $periodoContador++;
                                $fechaInicialPeriodo = date('Y-m-d', strtotime($fechaFinalPeriodo. ' + 1 days'));
                                $fechaFinalPeriodo = date('Y-m-d',getLastDay($fechaInicialPeriodo));
                        }
                        
                        if(!($mesAnterior == 12 && $mesActual == 1)){
                            $stmt = $conn->prepare('INSERT INTO nomina (no_nomina, no_empleados, fecha_pago, fecha_inicio, fecha_fin, usuario_creacion_id, usuario_edicion_id, created_at, updated_at, total, estatus, empresa_id, ultima_nomina, fk_nomina_principal) VALUES(:no_nomina, :no_empleados, :fecha_pago, :fecha_inicio, :fecha_fin, :usuario_creacion_id, :usuario_edicion_id, :created_at, :updated_at, :total, :estatus, :empresa_id, :ultima_nomina, :fk_nomina_principal)');
                            $stmt->bindValue(':no_nomina', $periodoContador);
                            $stmt->bindValue(':no_empleados', 0);
                            $stmt->bindValue(':fecha_pago', $fechaFinalPeriodo);
                            $stmt->bindValue(':fecha_inicio', $fechaInicialPeriodo);
                            $stmt->bindValue(':fecha_fin', $fechaFinalPeriodo);
                            $stmt->bindValue(':usuario_creacion_id', $usuario);
                            $stmt->bindValue(':usuario_edicion_id', $usuario);
                            $stmt->bindValue(':created_at', $fecha);
                            $stmt->bindValue(':updated_at', $fecha);
                            $stmt->bindValue(':total', 0);
                            $stmt->bindValue(':estatus', 1);
                            $stmt->bindValue(':empresa_id', $idEmpresa);
                            $stmt->bindValue(':ultima_nomina', 1);
                            $stmt->bindValue(':fk_nomina_principal', $idNomina);
                            $stmt->execute();
                        }
            //echo "Perido: ".$periodoContador." ---- ".$fechaInicialPeriodo." ----- ".$fechaFinalPeriodo."<br>";
                        $periodoContador++;
                        $fechaInicialPeriodo = date('Y-m-d', strtotime($fechaFinalPeriodo. ' + 1 days'));

                        if($periodoContador > 23){
                            $contador = 1;
                        }

                        if($mesAnterior == 12 && $mesActual == 1){
                            $contador = 1;
                        }

                        $ejecutaQuincena = 1;

                    }

                    if($idPeriodo == 4){

                        if($periodoContador == 1){
                            $mesAnterior = getMonth($fechaInicialPeriodo);
                        }
                        else{
                            $mesAnterior = getMonth($fechaFinalPeriodo);
                        }

                        $fechaFinalPeriodo = date('Y-m-d',getLastDay($fechaInicialPeriodo));

                        $mesActual = getMonth($fechaFinalPeriodo);

                        if(!($mesAnterior == 12 && $mesActual == 1)){
                        
                                $stmt = $conn->prepare('INSERT INTO nomina (no_nomina, no_empleados, fecha_pago, fecha_inicio, fecha_fin, usuario_creacion_id, usuario_edicion_id, created_at, updated_at, total, estatus, empresa_id, ultima_nomina, fk_nomina_principal) VALUES(:no_nomina, :no_empleados, :fecha_pago, :fecha_inicio, :fecha_fin, :usuario_creacion_id, :usuario_edicion_id, :created_at, :updated_at, :total, :estatus, :empresa_id, :ultima_nomina, :fk_nomina_principal)');
                                $stmt->bindValue(':no_nomina', $periodoContador);
                                $stmt->bindValue(':no_empleados', 0);
                                $stmt->bindValue(':fecha_pago', $fechaFinalPeriodo);
                                $stmt->bindValue(':fecha_inicio', $fechaInicialPeriodo);
                                $stmt->bindValue(':fecha_fin', $fechaFinalPeriodo);
                                $stmt->bindValue(':usuario_creacion_id', $usuario);
                                $stmt->bindValue(':usuario_edicion_id', $usuario);
                                $stmt->bindValue(':created_at', $fecha);
                                $stmt->bindValue(':updated_at', $fecha);
                                $stmt->bindValue(':total', 0);
                                $stmt->bindValue(':estatus', 1);
                                $stmt->bindValue(':empresa_id', $idEmpresa);
                                $stmt->bindValue(':ultima_nomina', 0);
                                $stmt->bindValue(':fk_nomina_principal', $idNomina);
                                $stmt->execute();
                        }
            //echo "Perido: ".$periodoContador." ---- ".$fechaInicialPeriodo." ----- ".$fechaFinalPeriodo."<br>";
                        $periodoContador++;
                        $fechaInicialPeriodo = date('Y-m-d', strtotime($fechaFinalPeriodo. ' + 1 days'));

                        if($periodoContador > 12){
                            $contador = 1;
                        }

                        if($mesAnterior == 12 && $mesActual == 1){
                            $contador = 1;
                        }

                    }

                    
                }while($contador < 1);
                
                if($conn->commit()){
                  echo "exito";
                }else{
                  echo "fallo";
                }
    }
    //fin idTipo
    if($idTipo == 2){

        $periodoContador = 1;

        $stmt = $conn->prepare('INSERT INTO nomina (no_nomina, no_empleados, fecha_pago, fecha_inicio, fecha_fin, usuario_creacion_id, usuario_edicion_id, created_at, updated_at, total, estatus, empresa_id, ultima_nomina, fk_nomina_principal, id_nomina_anterior) VALUES(:no_nomina, :no_empleados, :fecha_pago, :fecha_inicio, :fecha_fin, :usuario_creacion_id, :usuario_edicion_id, :created_at, :updated_at, :total, :estatus, :empresa_id, :ultima_nomina, :fk_nomina_principal, :id_nomina_anterior)');
                        $stmt->bindValue(':no_nomina', $periodoContador);
                        $stmt->bindValue(':no_empleados', 0);
                        $stmt->bindValue(':fecha_pago', $fechaPago);
                        $stmt->bindValue(':fecha_inicio', $fechaIni);
                        $stmt->bindValue(':fecha_fin', $fechaFin);
                        $stmt->bindValue(':usuario_creacion_id', $usuario);
                        $stmt->bindValue(':usuario_edicion_id', $usuario);
                        $stmt->bindValue(':created_at', $fecha);
                        $stmt->bindValue(':updated_at', $fecha);
                        $stmt->bindValue(':total', 0);
                        $stmt->bindValue(':estatus', 1);
                        $stmt->bindValue(':empresa_id', $idEmpresa);
                        $stmt->bindValue(':ultima_nomina', 0);
                        $stmt->bindValue(':fk_nomina_principal', $idNomina);
                        $stmt->bindValue(':id_nomina_anterior', 0);
                        $stmt->execute();

        if($conn->commit()){
            echo "exito";
        }else{
            echo "fallo";
        }

    }

    
} catch (PDOException $ex) {
    echo "fallo"; 
    $conn->rollBack();
    //echo $ex->getMessage();
}

?>
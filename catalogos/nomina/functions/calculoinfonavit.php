<?php
$creditoInfonavit = $CuotaFija;
//echo "creditoInfonavit ".$creditoInfonavit." <br>";
/* $fechaAplicacion se declara en agregarCreditoInfonavit
$fechaSuspension se declara en agregarCreditoInfonavit */

//echo $idNomina." --- ".$idNominaEmpleado;

function getBimestreCorrespondiente($fecha){
    $tiempo=strtotime($fecha);
    $mes=date("m",$tiempo);

    if($mes == 1 || $mes == 2){
        $bimestre = 1;
    }
    if($mes == 3 || $mes == 4){
        $bimestre = 2;
    }
    if($mes == 5 || $mes == 6){
        $bimestre = 3;
    }
    if($mes == 7 || $mes == 8){
        $bimestre = 4;
    }
    if($mes == 9 || $mes == 10){
        $bimestre = 5;
    }
    if($mes == 11 || $mes == 12){
        $bimestre = 6;
    }

    return $bimestre;
}

function getUltimoDiaMes($fecha){
    $datestring = $fecha;
    $date = strtotime($datestring);
    $lastdate = strtotime(date("Y-m-t", $date ));
    $diaFinal = date("d", $lastdate);
    
    return $diaFinal;
}

function getMesAnio($fecha, $dia){
    $datestring = $fecha;
    $date = strtotime($datestring);
    $lastdate = strtotime(date("Y-m-t", $date ));
    $anioFinal = date("Y", $lastdate);
    $mesFinal = date("m", $lastdate);
    $fechaMostrar = $anioFinal."-".$mesFinal."-".$dia;
    
    return $fechaMostrar;
}

function getDiasDelBimestre($fecha){
    
    $tiempo=strtotime($fecha);
    $mes=date("m",$tiempo);
    $anio=date("Y",$tiempo);

    if (($mes % 2) == 0) {
        //es par
        $mesActual = $mes - 1;

        if($mesActual < 10){
            $mesActual = "0".strval($mesActual);
        }

        //echo "mesActual ".$mesActual;
        $diaInicial = $anio.'-'.$mesActual.'-01';
        
        $ultimoDiaMes = getUltimoDiaMes($anio.'-'.$mes.'-01'); 

        //echo "mes par ",$mes;
        /*if($mes < 10){
            $mes = "0" + $mes;
        }*/
        //echo "mes par ",$mes;
        $diaFinal = $anio.'-'.$mes.'-'.$ultimoDiaMes;

    } else {
        //es impar
        $diaInicial = $anio.'-'.$mes.'-01';
        $mesActual = $mes + 1;
//echo "mes impar ",$mesActual;
        $ultimoDiaMes = getUltimoDiaMes($anio.'-'.$mesActual.'-01'); 
        if($mesActual < 10){
            $mesActual = "0" + $mesActual;
        }
        //echo "mes impar ",$mesActual;
        $diaFinal = $anio.'-'.$mesActual.'-'.$ultimoDiaMes;
    }


    return array($diaInicial, $diaFinal);
}

function getDiasBimestre($fechaIni, $fechaFin){
    $tiempoIni = new DateTime($fechaIni);
    //echo "fecha ini ".$fechaIni." fechfin ".$fechaFin;
    $tiempoFin = new DateTime($fechaFin);

    $diferencia = $tiempoFin->diff($tiempoIni)->format("%a") + 1;

    return $diferencia;
}


$stmt = $conn->prepare('SELECT fecha_inicio, fecha_fin FROM nomina WHERE id = :idNomina AND empresa_id = :idEmpresa');
$stmt->bindValue(':idNomina', $idNomina);  
$stmt->bindValue(':idEmpresa', $idEmpresa);
$stmt->execute();

$rowDatosNominaEsp = $stmt->fetch();
$fechaInicioNomina = $rowDatosNominaEsp['fecha_inicio'];
$fechaFinNomina = $rowDatosNominaEsp['fecha_fin'];

$fechaAplicacionTiempo = strtotime($fechaAplicacion);
$fechaInicioNominaTiempo = strtotime($fechaInicioNomina);
$fechaFinNominaTiempo = strtotime($fechaFinNomina);

$aplicarCreditoInfonavit = 0;
$cambiarFechaInicio = 0;

//echo $fechaInicioNomina." -- ".$fechaAplicacion."<br>";

if($fechaInicioNominaTiempo >= $fechaAplicacionTiempo){
    //echo "esto tendria que aplicar <br>";
    $aplicarCreditoInfonavit = 1;
    //echo $fechaSuspension." fecha susp  <br>";
    if(($fechaSuspension != "" || $fechaSuspension != null) && $fechaSuspension != "0000-00-00"){
        //echo "MMMMMM ";
        $fechaSuspensionTiempo = strtotime($fechaSuspension);


        if($fechaInicioNominaTiempo > $fechaSuspensionTiempo){
            $aplicarCreditoInfonavit = 0;
            $estado = 3;
        }
        else{
            if($fechaFinNominaTiempo > $fechaSuspensionTiempo){
                $fechaFinNomina = $fechaSuspension;
                $estado = 3;
            }
        }
    }
}
else{

    if($fechaInicioNominaTiempo <= $fechaAplicacionTiempo && $fechaFinNominaTiempo >= $fechaAplicacionTiempo){
        $aplicarCreditoInfonavit = 1;
        $cambiarFechaInicio = 1;
    }
}

//echo "aplicar credito ".$aplicarCreditoInfonavit." <br>";
if($aplicarCreditoInfonavit == 1){
    $stmt = $conn->prepare('SELECT FKPeriodo FROM datos_laborales_empleado WHERE FKEmpleado = :idEmpleado');
    $stmt->bindValue(':idEmpleado', $idEmpleado);
    $stmt->execute();
    $rowDatosPeriodoEsp = $stmt->fetch();
    $tipoPeriodo = $rowDatosPeriodoEsp['FKPeriodo']; //semanal, catorcenal, quincenal y mensual.
    //echo "tipoPeriodo ".$tipoPeriodo;


    //if($tipoPeriodo == 1 || $tipoPeriodo == 2){
        if($cambiarFechaInicio == 1){
            $fechaInicioNomina = $fechaAplicacion;
        }

        $bimestreIni = getBimestreCorrespondiente($fechaInicioNomina);
        $bimestreFin = getBimestreCorrespondiente($fechaFinNomina);

        if($bimestreIni != $bimestreFin){

            $arrayDiasBimestreInicial = getDiasDelBimestre($fechaInicioNomina);
            //print_r($arrayDiasBimestreInicial);
            //echo "arry ".$arrayDiasBimestreInicial[0]." -- ".$arrayDiasBimestreInicial[1]."<br>";
            $diasBimestreInicial = getDiasBimestre($arrayDiasBimestreInicial[0], $arrayDiasBimestreInicial[1]);
            
            $ultimoDiaMesFechaInicioNomina = getUltimoDiaMes($fechaInicioNomina);
            
            $fechaFinFechaInicioNomina = getMesAnio($fechaInicioNomina, $ultimoDiaMesFechaInicioNomina);
            //echo "fechaFinFechaInicioNomina ".$fechaFinFechaInicioNomina."<br>";
            $diasCotizadosInicial = getDiasBimestre($fechaInicioNomina, $fechaFinFechaInicioNomina);
            $cantidadAplicarInicial = (($creditoInfonavit * 2) / $diasBimestreInicial) * $diasCotizadosInicial;

            $arrayDiasBimestreFinal = getDiasDelBimestre($fechaFinNomina);
            //print_r($arrayDiasBimestreFinal);
            $diasBimestreFinal = getDiasBimestre($arrayDiasBimestreFinal[0], $arrayDiasBimestreFinal[1]);
            
            //echo "ultimo dias final ".$ultimoDiaMesFechaFinalNomina." -- "; 
            $fechaInicioPeriodoFinal = getMesAnio($fechaFinNomina, "01");
            //echo "fechaInicioPeriodoFinal ".$fechaInicioPeriodoFinal."<br>";
            $diasCotizadosFinal = getDiasBimestre($fechaInicioPeriodoFinal, $fechaFinNomina);
            $cantidadAplicarFinal = (($creditoInfonavit * 2) / $diasBimestreFinal) * $diasCotizadosFinal;

            $cantidadAplicar = $cantidadAplicarInicial + $cantidadAplicarFinal;
            //echo "cant 1: ".$cantidadAplicar."<br>";
        }
        else{
            //echo "creditoInfonavit ".$creditoInfonavit."<br>";
            $diasCotizados = getDiasBimestre($fechaInicioNomina, $fechaFinNomina);

            $arrayDiasBimestre = getDiasDelBimestre($fechaInicioNomina);
            $diasBimestre = getDiasBimestre($arrayDiasBimestre[0], $arrayDiasBimestre[1]);
            
            /*echo "diasBimestre ".$diasBimestre."<br>";
            echo "diasCotizados ".$diasCotizados."<br>";*/
            $cantidadAplicar = (($creditoInfonavit * 2) / $diasBimestre) * $diasCotizados;
            //echo "cant : ".$cantidadAplicar."<br>";
        }
    //}
     
     /*Se calcula así:
      (((Cuota fija x 2) ÷ Días del bimestre) x Días del bimestre cotizados) + 
      Seguro de daños a la vivienda = Deducción.

     40.6779

    */
    $valorCreditoInfonavitaAplicar = $cantidadAplicar;
}
else{
    $valorCreditoInfonavitaAplicar = 0;
}   
?>
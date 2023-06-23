<?php
          require_once('../../include/db-conn.php');

          $json = new \stdClass();
          $id =  $_POST['id'];
          $horasExtras =  $_POST['horasExtras'];
          $dobleTurno = $_POST['dobleTurno'];
          $salarioSemanal = $_POST['salarioSemanal'];
          $bono = $_POST['bono'];
          $diasTrabajados = $_POST['diasTrabajados'];
          
          $descuentoImproductividad = $_POST['descuentoImproductividad'];
          $descuentoDeudaInterna = $_POST['descuentoDeudaInterna'];
          $descuentoInfonavit = $_POST['descuentoInfonavit'];

          //calculo impuestos
          $stmt = $conn->prepare('SELECT cantidad FROM parametros WHERE descripcion = "Factor_mes" OR descripcion = "Dias_Aguinaldo"  OR descripcion = "Prima_Vacacional" OR descripcion = "UMA" OR descripcion = "Salario_Minimo_Nacional" ORDER BY PKParametros Asc');
          $stmt->execute();
          $row_parametros = $stmt->fetchAll();
          $UMA = $row_parametros[0]['cantidad'];
          $factor_mes = $row_parametros[4]['cantidad'];
          $dias_aguinaldo = $row_parametros[1]['cantidad'];
          $prima_vacacional_tasa = $row_parametros[2]['cantidad'] / 100;
          $salario_minimo = $row_parametros[3]['cantidad'];

          $semanaImpuesto = round($diasTrabajados / 7);
          $salarioTiempoExtra = $horasExtras + $dobleTurno;
          $salarioTiempoExtraLimite = ($horasExtras + $dobleTurno) / 2;
          $limite_excento_salarioExtra = $UMA * 5 * $semanaImpuesto;
    
          if($salarioTiempoExtraLimite > $limite_excento_salarioExtra){
            $salarioTiempoExtraBase =  $salarioTiempoExtra - $limite_excento_salarioExtra;
          }
          else{
            $salarioTiempoExtraBase = $salarioTiempoExtraLimite;
          }
          
          $salarioTiempoExtraBaseImpuesto = number_format($salarioTiempoExtraBase + $bono,2);
          
          $sueldoBaseImpuestos = bcdiv($salarioSemanal + $salarioTiempoExtraBaseImpuesto - $descuentoImproductividad - $descuentoDeudaInterna,1,2);
          $totalPercepciones = bcdiv($salarioSemanal + $salarioTiempoExtra + $bono - $descuentoImproductividad - $descuentoDeudaInterna,1,2);;
          $sueldoDiario = number_format($sueldoBaseImpuestos / $diasTrabajados,2);     
          $base = bcdiv($sueldoDiario * $factor_mes,1,2);

          $stmt = $conn->prepare("SELECT * FROM pagos_provisionales_mensuales WHERE Limite_inferior <= :impuestogravablemin AND Limite_superior >= :impuestogravablesup ");
          $stmt->bindValue(':impuestogravablemin',$base);
          $stmt->bindValue(':impuestogravablesup',$base);
          $stmt->execute();
          $row_limite = $stmt->fetch();
          
          $Limite_inferior = $row_limite['Limite_inferior'];
          $excedente_limite_inferior = number_format($base - $Limite_inferior, 2, '.', '');
          $porcentaje_tabla = $row_limite['Porcentaje_sobre_limite_inferior'];
          $impuesto_marginal = number_format($excedente_limite_inferior * ($porcentaje_tabla/100), 2, '.', '');
          $cuota_fija = $row_limite['Cuota_fija'];
          $ISRDeterminado = $impuesto_marginal + $cuota_fija;

          $stmt = $conn->prepare("SELECT * FROM subsidio_empleo WHERE IngresoMinimo <= :ingresominimo AND IngresoMaximo >= :ingresomaximo ");
          $stmt->bindValue(':ingresominimo',$base);
          $stmt->bindValue(':ingresomaximo',$base);
          $stmt->execute();
          $row_subsidio = $stmt->fetch();

          $subsidioMensual = $row_subsidio['SubsidioMensual'];
          $subsidioAplicable = number_format(($subsidioMensual / $factor_mes) * $diasTrabajados, 2, '.', '');
          $ISRRetenido = number_format($ISRDeterminado, 2, '.', '');
          $ISRDiario = number_format((($ISRRetenido / $factor_mes) * $diasTrabajados) - $subsidioAplicable, 2, '.', '');
          ///BG11  y verificar del ISR de periodos anteriores BT11
    
          require_once('../../functions/funcion_calculovacaciones.php');

          $sueldoDiarioBaseSDI = number_format($salarioSemanal / $diasTrabajados,2);
          $factorSDI = bcdiv(1 + ($dias_aguinaldo + ($dias_vacaciones * $prima_vacacional_tasa)) / 365,1,4);
          $SDI = bcdiv($sueldoDiarioBaseSDI * $factorSDI,1,2);

          /*CUOTAS MENSUALES*/
          $stmt = $conn->prepare("SELECT * FROM cuotas_mensuales");
          $stmt->execute();
          $row_cuotasmensuales = $stmt->fetchAll();
 
          $impuestoCF = number_format($UMA * 7 * ($row_cuotasmensuales[0]['cantidad']/100),2);

          if($SDI > $UMA*3){
            $impuestoExcPat = number_format(($SDI - ($UMA*3)) * 11 * ($row_cuotasmensuales[1]['cantidad']/100), 2, '.', '');
            $impuestoExcObr = number_format(($SDI - ($UMA*3)) * 7 * ($row_cuotasmensuales[2]['cantidad']/100), 2, '.', '');
          }
          else {
            $impuestoExcPat = 0.00;
            $impuestoExcObr = 0.00;
          }

          $impuestoPDPat = number_format($SDI * 7 * ($row_cuotasmensuales[3]['cantidad']/100), 2, '.', '');
          $impuestoPDObr = number_format($SDI * 7 * ($row_cuotasmensuales[4]['cantidad']/100), 2, '.', '');
          $impuestoGMPat = number_format($SDI * 7 * ($row_cuotasmensuales[5]['cantidad']/100), 2, '.', '');
          $impuestoGMObr = number_format($SDI * 7 * ($row_cuotasmensuales[6]['cantidad']/100), 2, '.', '');
          $impuestoRT = number_format($SDI * 7 * ($row_cuotasmensuales[7]['cantidad']/100), 2, '.', '');
          $impuestoIVPat = number_format($SDI * 7 * ($row_cuotasmensuales[8]['cantidad']/100), 2, '.', '');
          $impuestoIVObr = number_format($SDI * 7 * ($row_cuotasmensuales[9]['cantidad']/100), 2, '.', '');
          $impuestoGPS = number_format($SDI * 7 * ($row_cuotasmensuales[10]['cantidad']/100), 2, '.', '');
          $sumapatronalMensual = $impuestoCF + $impuestoExcPat +  $impuestoPDPat + $impuestoGMPat + $impuestoRT + $impuestoIVPat + $impuestoGPS;
          $sumaobreraMensual = $impuestoExcObr + $impuestoPDObr + $impuestoGMObr + $impuestoIVObr;
          $cuota_mensual = $sumapatronalMensual + $sumaobreraMensual;
          /*CUOTAS MENSUALES*/

          /*CUOTAS BIMESTRALES*/
          $stmt = $conn->prepare("SELECT * FROM cuotas_bimestrales");
          $stmt->execute();
          $row_cuotasbimestrales = $stmt->fetchAll();

          $impuestoRETIRO = number_format($SDI * 7 * ($row_cuotasbimestrales[0]['cantidad']/100), 2, '.', '');
          $impuestoCYVPat = number_format($SDI * 7 * ($row_cuotasbimestrales[1]['cantidad']/100), 2, '.', '');
          $impuestoCYVObr = number_format($SDI * 7 * ($row_cuotasbimestrales[2]['cantidad']/100), 2, '.', '');
          $impuestoAPPatron = number_format($SDI * 7 * ($row_cuotasbimestrales[3]['cantidad']/100), 2, '.', '');

          $sumapatronalBimestral = $impuestoRETIRO + $impuestoCYVPat +  $impuestoAPPatron;
          $sumaobreraBimestral = $impuestoCYVObr;
          $cuota_Bimestral = $sumapatronalBimestral + $sumaobreraBimestral;
          
          if($SDI <= $salario_minimo)
          {
            $cuota_obrero = 0.00;
          }
          else{
            $cuota_obrero = $sumaobreraMensual + $sumaobreraBimestral; 
          }

          $json->cuota_obrero = number_format($cuota_obrero,2,'.','');
          /*CUOTAS BIMESTRALES*/

          $ISRSemana = $ISRDiario;
          $json->ISRSemana = $ISRSemana; 
          $neto_a_pagar = number_format($totalPercepciones - $ISRSemana - $cuota_obrero - $descuentoInfonavit,2,'.','');
          $json->neto_a_pagar = $neto_a_pagar;

          $json = json_encode($json);
          echo $json;

        function AgregarTiempos($times) {
            global $minutosAc, $horasAc;
            foreach ($times as $time) {
                list($hora, $minuto) = explode(':', $time);
                $minutosAc+= $hora * 60;
                $minutosAc+= $minuto;
            }
        
            $horasAc = floor($minutosAc/ 60);
            $minutosAc-= $horasAc * 60;
            
            return sprintf('%02d:%02d', $horasAc, $minutosAc);
        }
?>
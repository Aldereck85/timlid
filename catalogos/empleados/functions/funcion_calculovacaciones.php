<?php
    function esBisiesto($year=NULL) {
            $year = ($year==NULL)? date('Y'):$year;
            return ( ($year%4 == 0 && $year%100 != 0) || $year%400 == 0 );
    }

    function calculoVacaciones($idEmpleado, $modo){
      require_once('../../../include/db-conn.php');

      try{

        $conn->beginTransaction();

        /*CALCULO DIAS DE VACACIONES*/
        $where = "";
        if($modo == 1){
          $where = " WHERE e.PKEmpleado = '".$idEmpleado."'";
        }
        $stmt = $conn->prepare("SELECT e.PKEmpleado, de.Fecha_Ingreso, va.Estatus, va.DiasAgregados 
                                    FROM empleados as e 
                                      INNER JOIN datos_laborales_empleado as de ON de.FKEmpleado = e.PKEmpleado
                                      LEFT JOIN vacaciones_agregadas as va ON va.FKEmpleado = e.PKEmpleado AND va.Anio = YEAR(curdate())".$where);
        $stmt->execute();
        $row = $stmt->fetchAll();

        foreach ($row as $datos_vacaciones) {
            
            $fechaIngreso = $datos_vacaciones['Fecha_Ingreso'];
            $fechaFinal = date("Y-m-d");
            $datetime1 = new DateTime($fechaIngreso); // Fecha inicial
            $datetime2 = new DateTime($fechaFinal); // Fecha actual
            $interval = $datetime1->diff($datetime2);
            $num_dias_antiguedad = $interval->format('%a');

            $fechaIngresoComoEntero = strtotime($fechaIngreso);
            $fechaFinalComoEntero = strtotime($fechaFinal);
            $num_anios = 0;
            $num_dias = 0;

            if(date("m",$fechaIngresoComoEntero) == date("m",$fechaFinalComoEntero) && date("d",$fechaIngresoComoEntero) == date("d",$fechaFinalComoEntero)){

                for($x = date("Y",$fechaIngresoComoEntero);$x < date("Y",$fechaFinalComoEntero);$x++){  
                  $num_anios++;
                }

                $json->num_anios = number_format($num_anios,3,'.','');
            }
            else{
                for($x = date("Y",$fechaIngresoComoEntero);$x <= date("Y",$fechaFinalComoEntero);$x++){
                  if(esBisiesto($x)){
                    $num_dias = $num_dias + 366;
                  }
                  else{
                    $num_dias = $num_dias + 365;
                  }
                  $num_anios++;
                }
                $factor_anios = $num_dias/$num_anios;
                $anios_trabajados = number_format($num_dias_antiguedad / $factor_anios,3,'.','');
            }

            $Fecha_Ingreso = date_create($datos_vacaciones['Fecha_Ingreso']);
            $Fecha_Ingreso_Anio_Actual = date_create($Fecha_Ingreso->format(date('Y').'-m-d'));

            if($anios_trabajados > 1){
                if(date('Y-m-d') > $Fecha_Ingreso_Anio_Actual->format('Y-m-d')){
                  if($datos_vacaciones['Estatus'] == "" || $datos_vacaciones['Estatus'] == 0){

                    switch ($anios_trabajados)
                    {
                      case ($anios_trabajados > 0 && $anios_trabajados < 2):
                        $dias_vacaciones = 6;
                        break;
                      case ($anios_trabajados > 2 && $anios_trabajados < 3):
                        $dias_vacaciones = 8;
                        break;
                      case ($anios_trabajados > 3 && $anios_trabajados < 4):
                        $dias_vacaciones = 10;
                        break;
                      case ($anios_trabajados > 4 && $anios_trabajados < 5):
                        $dias_vacaciones = 12;
                        break;
                      case ($anios_trabajados > 5 && $anios_trabajados < 10):
                        $dias_vacaciones = 14;
                        break;
                      case ($anios_trabajados > 10 && $anios_trabajados < 15):
                        $dias_vacaciones = 16;
                        break;
                      case ($anios_trabajados > 15 && $anios_trabajados < 20):
                        $dias_vacaciones = 18;
                        break;
                      case ($anios_trabajados > 20 && $anios_trabajados < 25):
                        $dias_vacaciones = 20;
                        break;
                      case ($anios_trabajados > 25 && $anios_trabajados < 30):
                        $dias_vacaciones = 22;
                        break;
                      case ($anios_trabajados > 30 && $anios_trabajados < 35):
                        $dias_vacaciones = 24;
                        break;
                      case ($anios_trabajados > 35 && $anios_trabajados < 40):
                        $dias_vacaciones = 26;
                        break;
                      case ($anios_trabajados > 40 && $anios_trabajados < 45):
                        $dias_vacaciones = 28;
                        break;
                      case ($anios_trabajados > 45 && $anios_trabajados < 50):
                        $dias_vacaciones = 30;
                        break;
                    }

                    $stmt = $conn->prepare('SELECT Dias_de_Vacaciones FROM datos_laborales_empleado WHERE FKEmpleado = :FKEmpleado');
                    $stmt->bindValue(':FKEmpleado',$datos_vacaciones['PKEmpleado']);
                    $stmt->execute();
                    $row = $stmt->fetch();
                    $dias_vacaciones_ant = $row['Dias_de_Vacaciones'];

                    $dias_vacaciones_final = $dias_vacaciones_ant + $dias_vacaciones;

                    $stmt = $conn->prepare('UPDATE  datos_laborales_empleado SET Dias_de_Vacaciones = :dias_vacaciones WHERE FKEmpleado = :FKEmpleado');
                    $stmt->bindValue(':dias_vacaciones',$dias_vacaciones_final);
                    $stmt->bindValue(':FKEmpleado',$datos_vacaciones['PKEmpleado']);
                    $stmt->execute();

                    $stmt = $conn->prepare('INSERT INTO vacaciones_agregadas (Anio, DiasAgregados, Estatus, FKEmpleado) VALUES (:anio, :dias_agregados, :estatus, :FKEmpleado) ');
                    $stmt->bindValue(':anio', date('Y'));
                    $stmt->bindValue(':dias_agregados',$dias_vacaciones);
                    $stmt->bindValue(':estatus', 1);
                    $stmt->bindValue(':FKEmpleado',$datos_vacaciones['PKEmpleado']);
                    $stmt->execute();
                  }
                }
            }

        }

        $conn->commit(); 

        if($modo == 0)
          header('Location:../vacaciones.php?id='.$idEmpleado.'&estatus=1');
      }catch(PDOException $ex){
        echo $ex->getMessage();
      }

    }

?>

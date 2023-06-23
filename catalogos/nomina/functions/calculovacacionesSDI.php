<?php

    try{

      $stmt = $conn->prepare("SELECT diasagregados FROM vacaciones_agregadas WHERE anio = YEAR(curdate()) AND empleado_id = :empleado_id ");
      $stmt->bindValue(":empleado_id", $idEmpleado);
      $stmt->execute();
      $dias_vac = $stmt->fetch();
      $cuenta_dias_vac = $stmt->rowCount();

      if($cuenta_dias_vac > 0){
        $dias_vacaciones = $dias_vac['diasagregados'];
      }
      else{


        /*CALCULO DIAS DE VACACIONES*/
        $where = " WHERE e.PKEmpleado = '".$idEmpleado."'";
        $stmt = $conn->prepare("SELECT e.PKEmpleado, de.FechaIngreso, va.diasagregados 
                                    FROM empleados as e 
                                      INNER JOIN datos_laborales_empleado as de ON de.FKEmpleado = e.PKEmpleado
                                      LEFT JOIN vacaciones_agregadas as va ON va.empleado_id = e.PKEmpleado AND va.anio = YEAR(curdate())".$where);
        $stmt->execute();
        $row_vac = $stmt->fetchAll();


        foreach ($row_vac as $datos_vacaciones) {
          
              $fechaIngreso = $datos_vacaciones['FechaIngreso'];
              $fechaFinal = date("Y-m-d");
              $datetime1 = new DateTime($fechaIngreso); // Fecha inicial
              $datetime2 = new DateTime($fechaFinal); // Fecha actual
              $interval = $datetime1->diff($datetime2);
              $num_dias_antiguedad = $interval->format('%a');

              $fechaIngresoComoEntero = strtotime($fechaIngreso);
              $fechaFinalComoEntero = strtotime($fechaFinal);
              $num_anios = 0;
              $num_dias = 0;
              $anios_trabajados = 0;


              if(date("m",$fechaIngresoComoEntero) == date("m",$fechaFinalComoEntero) && date("d",$fechaIngresoComoEntero) == date("d",$fechaFinalComoEntero)){

                  for($x = date("Y",$fechaIngresoComoEntero);$x < date("Y",$fechaFinalComoEntero);$x++){  
                    $num_anios++;
                  }

                  //$json->num_anios = number_format($num_anios,3,'.','');
              }
              elseif(date("m",$fechaIngresoComoEntero) != date("m",$fechaFinalComoEntero) || date("d",$fechaIngresoComoEntero) != date("d",$fechaFinalComoEntero)){

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
              elseif($fechaFinalComoEntero >= $fechaIngresoComoEntero){
                $anios_trabajados = 0;
              }

                $Fecha_Ingreso = date_create($datos_vacaciones['FechaIngreso']);
                $Fecha_Ingreso_Anio_Actual = date_create($Fecha_Ingreso->format(date('Y').'-m-d'));

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
          }

          $fecha_agregar1 = date("Y-m-d");
          $anio_actual = date("Y");
          $stmt = $conn->prepare("INSERT INTO vacaciones_agregadas (anio, diasagregados, diasrestantes, empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion)  VALUES ( :anio, :diasagregados, :diasrestantes, :empleado_id, :fecha_alta, :fecha_edicion, :usuario_alta, :usuario_edicion ) ");
          $stmt->bindValue(":anio", $anio_actual);
          $stmt->bindValue(":diasagregados", $dias_vacaciones);
          $stmt->bindValue(":diasrestantes", $dias_vacaciones);
          $stmt->bindValue(":empleado_id", $idEmpleado);
          $stmt->bindValue(":fecha_alta", $fecha_agregar1);
          $stmt->bindValue(":fecha_edicion", $fecha_agregar1);
          $stmt->bindValue(":usuario_alta", $_SESSION['PKUsuario']);
          $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
          $stmt->execute();

      }
      

      

    }catch(PDOException $ex){
      echo $ex->getMessage();
    }

?>

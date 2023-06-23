<?php
session_start();

  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 1 || $_SESSION["FKRol"] == 4)){

  require_once('../../../include/db-conn.php');
  $json = new \stdClass();
  
    $bandera = false;
    $fkEmpleado = $_POST['fkEmpleado'];
    $dias_vacacionesIngreso = $_POST['dias_vacaciones'];
    $fecha_ini = $_POST['txtFechaInicio'];
    $fecha_fin = $_POST['txtFechaTermino'];
    $dias_trabajo = $_POST['dias_trabajo'];

    //VALIDA SI EL RANGO DE FECHAS ES EL CORRECTO Y QUE LA FECHA DE TERMINO ES DESPUES DE LA INICIAL
    $fechaini = new DateTime($fecha_ini);
    $fechafin = new DateTime($fecha_fin);
    $num_dias = 0;
    $num_dias_fin_sem = 0;
    
    while( $fechaini <= $fechafin){
            
        $num_dias++;
        if($dias_trabajo == 5){
                if($fechaini->format('l')== 'Saturday' || $fechaini->format('l')== 'Sunday'){
                    $num_dias_fin_sem++;
                }
        }
        if($dias_trabajo == 6){
                if($fechaini->format('l')== 'Sunday'){
                    $num_dias_fin_sem++;
                }
        }
        $fechaini->modify("+1 days");
    }

    $num_dias_vacaciones = $num_dias - $num_dias_fin_sem;
    
    $json->error = "0";
    if(strtotime($fecha_ini)  > strtotime($fecha_fin)){
        $json->error = "2";
        $json = json_encode($json);
        echo $json;
        return;
    }
    elseif($num_dias_vacaciones != $dias_vacacionesIngreso){
        $json->error = "1";
        $json = json_encode($json);
        echo $json;
        return;
    }

    if($dias_vacacionesIngreso != 0)
        $bandera = true;

    if($bandera){
      
      try{
        $stmt = $conn->prepare('INSERT INTO permiso_vacaciones (DiasVacaciones, FechaIni, FechaFin, Estatus, FKEmpleado) values (:dias_vacaciones,:fechaini, :fechafin, :estatus,:FKEmpleado)');
        $stmt->bindValue(':dias_vacaciones',$dias_vacacionesIngreso);
        $stmt->bindValue(':fechaini',$fecha_ini);
        $stmt->bindValue(':fechafin',$fecha_fin);
        $stmt->bindValue(':estatus', 0);
        $stmt->bindValue(':FKEmpleado',$fkEmpleado);
        $stmt->execute();

        $last_id = $conn->lastInsertId();
        $json->last_id = $last_id;

        $mes = array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
  
        $fecha_ini = explode('-', $fecha_ini);
        $mes_nombre_ini = $mes[$fecha_ini[1]-1];
        $fecha_ini_com =$fecha_ini[2].' de '.$mes_nombre_ini.' del '.$fecha_ini[0];

        $fecha_fin = explode('-', $fecha_fin);
        $mes_nombre_fin = $mes[$fecha_fin[1]-1];
        $fecha_fin_com =$fecha_fin[2].' de '.$mes_nombre_fin.' del '.$fecha_fin[0];

        $fecha_completa = $fecha_ini_com.' al '.$fecha_fin_com;
        $json->fecha = $fecha_completa;

        $json = json_encode($json);
        echo $json;

      }catch(PDOException $ex){
        echo $ex->getMessage();
      }
    }
  }else {
    header("location:../../dashboard.php");
  }
  
?>

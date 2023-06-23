
<?php
require_once('../../../include/db-conn.php');

//$stmt = $conn->prepare('SELECT PKTurno, Turno, Entrada, Salida, Dias_de_trabajo, DATE_FORMAT(TiempoComida, '%H:%i') TiempoComida FROM turnos');
$stmt = $conn->prepare('SELECT PKTurno, Turno, DATE_FORMAT(Entrada,"%H:%i") Entrada, DATE_FORMAT(Salida,"%H:%i") Salida, Dias_de_trabajo, DATE_FORMAT(TiempoComida,"%H:%i") TComida from turnos');
$stmt->execute();
$table="";



//href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Turno\" class=\"btn btn-primary\" onclick=\"obtenerIdTurnoEditar('.$row['PKTurno'].');\"><i class=\"fas fa-edit\"></i> Editar
//href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Turno\" class=\"btn btn-danger\" onclick=\"obtenerIdTurnoEliminar('.$row['PKTurno'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar

while (($row = $stmt->fetch()) !== false) {
    //$edit = '<a class=\"btn btn-primary\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Turno\" class=\"btn btn-primary\" onclick=\"obtenerIdTurnoEditar('.$row['PKTurno'].');\"><i class=\"fas fa-edit\"></i> Editar</a>&nbsp;&nbsp;';
		//$delete ='<a class=\"btn btn-danger\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Turno\" class=\"btn btn-danger\" onclick=\"obtenerIdTurnoEliminar('.$row['PKTurno'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';
    
    $dias = "";
    $stack = array();
    $id = $row['PKTurno'];
    $stmt2 = $conn->prepare('SELECT Dias FROM dias_turno WHERE FKTurno = :id');
    $stmt2->execute(array(':id'=>$id));
    while (($row2 = $stmt2->fetch()) !== false) {
      array_push($stack, $row2['Dias']);
    }
    $dias_selected = count($stack);
    $sem = array(1,2,3,4,5,6,7);
    $dif = array_diff($sem, $stack);
    $dia_uno_c = current($dif);
    $dia_ultimo_c = end($dif);
    $dia_uno = current($stack);
    $dia_ultimo = end($stack);
    if($dias_selected === 5){
      if($dia_uno_c == 1 && $dia_ultimo_c == 4){
        $dias = "Mar,  Mie,  Vie,  Sab,  Dom";
      }else if($dia_uno_c == 3 && $dia_ultimo_c == 7){
        $dias = "Lun,  Mar,  Jue,  Vie,  Sab";
      }else if($dia_uno_c == 1 && $dia_ultimo_c == 2){
        $dias = "Mie,  Jue,  Vie,  Sab,  Dom";
      }else if($dia_uno_c == 2 && $dia_ultimo_c == 3){
        $dias = "Lun,  Jue,  Vie,  Sab,  Dom";
      }else if($dia_uno_c == 4 && $dia_ultimo_c == 7){
        $dias = "Lun,  Mar,  Mie,  Vie,  Sab";
      }else if($dia_uno_c == 5 && $dia_ultimo_c == 7){
        $dias = "Lun,  Mar,  Mie,  Jue,  Sab";
      }else if($dia_uno_c == 2 && $dia_ultimo_c == 6){
        $dias = "Martes a sábado";
      }else if($dia_uno_c == 3 && $dia_ultimo_c == 7){
        $dias = "Miercoles a domingo";
      }else if($dia_uno_c == 3 && $dia_ultimo_c == 7){
        $dias = "Lun,  Mar,  Jue,  Vie,  Sab";
      }else if ($dia_uno == 1 && $dia_ultimo == 5){
        $dias = "Lunes a viernes";
      }else if ($dia_uno == 2 && $dia_ultimo == 6){
        $dias = "Martes a sábado";
      }else if ($dia_uno == 3 && $dia_ultimo == 7){
        $dias = "Miercoles a domingo";
      }
      /*foreach($dif as $d){
        if($d == 2){
          $dias = "Lun,  Mie,  Jue,  Vie,  Sab";
        }else if($d == 3){
          $dias = "Lun,  Mar,  Jue,  Vie,  Sab";
        }else if($d == 4){
          $dias = "Lun,  Mar,  Mie,  Vie,  Sab";
        }else if($d == 5){
          $dias = "Lun,  Mar,  Mie,  Jue,  Sab";
        }else if($dia_uno == 2 && $dia_ultimo == 6){
          $dias = "Martes a sábado";
        }else if($dia_uno == 3 && $dia_ultimo == 7){
          $dias = "Miercoles a domingo";
        }
      }*/
    }else if(count($dif) > 0){
      foreach($dif as $d){
        if($d == 2){
          $dias = "Lun,  Mie,  Jue,  Vie,  Sab,  Dom";
        }else if($d == 3){
          $dias = "Lun,  Mar,  Jue,  Vie,  Sab,  Dom";
        }else if($d == 4){
          $dias = "Lun,  Mar,  Mie,  Vie,  Sab,  Dom";
        }else if($d == 5){
          $dias = "Lun,  Mar,  Mie,  Jue,  Sab,  Dom";
        }else if($d == 6){
          $dias = "Lun,  Mar,  Mie,  Jue,  Vie,  Dom";
        }elseif ($dia_uno == 1 && $dia_ultimo == 4){
          $dias = "Lunes a jueves";
        }else if($dia_uno == 1 && $dia_ultimo == 6){
          $dias = "Lunes a sábado";
        }else if($dia_uno == 2 && $dia_ultimo == 6){
          $dias = "Martes a sábado";
        }else if($dia_uno == 2 && $dia_ultimo == 5){
          $dias = "Martes a viernes";
        }else if($dia_uno == 2 && $dia_ultimo == 7){
          $dias = "Martes a domingo";
        }else if($dia_uno == 3 && $dia_ultimo == 7){
          $dias = "Miercoles a domingo";
        }else{
          $dias = "No asignado";
        }
      }
    }else{
        $dias = "Lunes a domingo";
    }

    $horas_trabajo = ($row['Dias_de_trabajo'])*8; 
    $table.='{"id":"<label class=\"textTable\">'.$row['PKTurno'].'",
      "Turno":"<label class=\"textTable\">'.$row['Turno'].'",
      "Entrada":"<label class=\"textTable\">'.$row['Entrada'].'",
      "Salida":"<label class=\"textTable\">'.$row['Salida'].'",
      "Dias":"<label class=\"textTable\">'.$dias.'",
      "Horas/Semana":"<label class=\"textTableNumber\">'.$horas_trabajo.'",
      "TiempoComida":"<label class=\"textTableNumber\">'.$row['TComida'].'</label><i><img class=\"btnEdit\" data-toggle=\"modal\" data-target=\"#editar_Turno\" onclick=\"obtenerIdTurnoEditar('.$row['PKTurno'].');\" src=\"../../img/timdesk/edit.svg\"></i>"},';
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>

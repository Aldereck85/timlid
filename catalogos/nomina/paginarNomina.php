<?php
    require_once('../../include/db-conn.php');

    $empleado  = $_POST['empleado'];
    $semana     = $_POST['semana'];
    $turno = $_POST['turno'];;
    $x = 1;

    $tabla = "";

    $stmt = $conn->prepare('SELECT * FROM empleados INNER JOIN datos_laborales_empleado ON PKEmpleado = FKEmpleado WHERE FKTurno = :fkTurno');
    //$stmt->bindValue(':fkEmpleado',$empleado);
    $stmt->bindValue(':fkTurno',$turno);
    $stmt->execute();
    //$row = $stmt->fetch();

    //
    $cantidadRegistros = $stmt->rowCount();

    if($cantidadRegistros <= 10){
        while (($row = $stmt->fetch()) !== false) {
            $idEmpleado = $row['PKEmpleado'];

            if($empleado == $idEmpleado){
                $tabla = $tabla.'<li class="page-item active"><a class="page-link" href="asistencia.php?id='.$idEmpleado.'&semana='.$semana.'&turno='.$turno.'">'.$x.'</a></li>';
            }else{
                $tabla = $tabla.'<li class="page-item"><a class="page-link" href="asistencia.php?id='.$idEmpleado.'&semana='.$semana.'&turno='.$turno.'">'.$x.'</a></li>';
            }

            $x++;
        }
    }
    else{
        $row = $stmt->fetchAll();
        foreach($row as $r) {
            $idEmpleado = $r['PKEmpleado'];
            if($empleado == $idEmpleado)
                $index = $x;
            $x++;
        }

        $x = 1;
        $banderaMenor = 0;
        $banderaMayor = 0;
        foreach($row as $r) {
            $idEmpleado = $r['PKEmpleado'];
            
            if($x < $index - 2 && $x != 1){
                if($banderaMenor == 0){
                    $tabla = $tabla.'<li class="page-item disabled"><a href="#" data-dt-idx="6" tabindex="0" class="page-link">…</a></li>';
                    $banderaMenor = 1;
                }
            }
            elseif($x > $index + 3 && $x != $cantidadRegistros){
                if($banderaMayor == 0){
                    $tabla = $tabla.'<li class="page-item disabled"><a href="#" data-dt-idx="6" tabindex="0" class="page-link">…</a></li>';
                    $banderaMayor = 1;
                }
            }
            elseif($empleado == $idEmpleado){
                $tabla = $tabla.'<li class="page-item active"><a class="page-link" href="asistencia.php?id='.$idEmpleado.'&semana='.$semana.'&turno='.$turno.'">'.$x.'</a></li>';
            }else{
                $tabla = $tabla.'<li class="page-item"><a class="page-link" href="asistencia.php?id='.$idEmpleado.'&semana='.$semana.'&turno='.$turno.'">'.$x.'</a></li>';
            }

            $x++;
        }

    }

    $lista = '<label>'.$empleado.'</label>';
    $array = array(0 => $tabla,
             1 => $lista);

    echo json_encode($array);

?>
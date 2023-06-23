<?php
        require_once('../../include/db-conn.php');
        if(isset($_GET['id'])){
          $id =  $_GET['id'];
          $semana = $_GET['semana'];
          $stmt = $conn->prepare('SELECT empleados.Primer_Nombre, empleados.Segundo_Nombre, empleados.Apellido_Paterno, empleados.Apellido_Materno, empleados.NSS, empleados.Infonavit, empleados.Deuda_Interna, empleados.Deuda_Restante, empleados.RFC, turnos.Turno, turnos.Entrada, turnos.Salida, turnos.Horas_de_trabajo, turnos.Dias_de_trabajo, puestos.Puesto, puestos.Sueldo_semanal, nomina.BonoProductividad, nomina.DescuentoImproductividad, nomina.DescuentoInfonavit, nomina.DescuentoDeuda, nomina.Salario FROM empleados INNER JOIN datos_empleo ON empleados.PKEmpleado = datos_empleo.FKEmpleado INNER JOIN turnos ON datos_empleo.FKTurno = turnos.PKTurno INNER JOIN puestos ON datos_empleo.FKPuesto = puestos.PKPuesto INNER JOIN nomina ON nomina.FKEmpleado = empleados.PKEmpleado WHERE empleados.PKEmpleado = :id AND nomina.FKSemana = :semana');
          $stmt->bindValue(':id',$id);
          $stmt->bindValue(':semana',$semana);
          $stmt->execute();
          $row = $stmt->fetch();
          $nombreEmpleado = $row['Primer_Nombre']." ".$row['Segundo_Nombre']." ".$row['Apellido_Paterno']." ".$row['Apellido_Materno'];
          $diasTrabajo = $row['Dias_de_trabajo'];
          $contEstatus = 0;

          ///////////////// Calculo de nomina////////////////////////////////////////////
          $rfc = $row['RFC'];
          $nss = $row['NSS'];
          $turno = $row['Turno'];
          $puesto = $row['Puesto'];
          $sueldoSemanal = $row['Sueldo_semanal'];
          $bono = $row['BonoProductividad'];
          $sueldoDescuento = $row['DescuentoImproductividad'];
          $infonavit = $row['DescuentoInfonavit'];
          $parcialidades = $row['DescuentoDeuda'];
          $salarioTotal = $row['Salario'];
        }
?>

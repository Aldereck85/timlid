<?php
        require_once('../../include/db-conn.php');
        if(isset($_GET['id'])){
          $id =  $_GET['id'];
          $stmt = $conn->prepare('SELECT empleados.Primer_Nombre, empleados.Segundo_Nombre, empleados.Apellido_Paterno, empleados.Apellido_Materno, empleados.Fecha_Ingreso, empleados.NSS, empleados.Infonavit, empleados.Deuda_Interna, empleados.Deuda_Restante, empleados.RFC, turnos.Turno, puestos.Puesto, puestos.BonoAsignado FROM empleados INNER JOIN datos_empleo ON empleados.PKEmpleado = datos_empleo.FKEmpleado INNER JOIN turnos ON datos_empleo.FKTurno = turnos.PKTurno INNER JOIN puestos ON datos_empleo.FKPuesto = puestos.PKPuesto WHERE empleados.PKEmpleado = :id');
          $stmt->bindValue(':id',$id);
          $stmt->execute();
          $row = $stmt->fetch();
          $nombreEmpleado = $row['Primer_Nombre']." ".$row['Segundo_Nombre']." ".$row['Apellido_Paterno']." ".$row['Apellido_Materno'];
          $contEstatus = 0;
          $rfc = $row['RFC'];
          $nss = $row['NSS'];
          $turno = $row['Turno'];
          $puesto = $row['Puesto'];
          $bonoAsignado = $row['BonoAsignado'];
          $fechaIngreso = $row['Fecha_Ingreso'];
        }
?>

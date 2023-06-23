<?php
    if(isset($_GET['id'])){
        require_once('../../../include/db-conn.php');
        $bandera = false;
        $stack = array();
        $id = $_GET['id'];
        $stmt = $conn->prepare("SELECT integrantes_equipo.FKUsuario, empleados.Nombres, empleados.PrimerApellido, empleados.SegundoApellido FROM integrantes_equipo INNER JOIN usuarios ON integrantes_equipo.FKUsuario = usuarios.PKUsuario INNER JOIN empleados ON empleados.PKEmpleado = usuarios.FKEmpleado WHERE integrantes_equipo.FKEquipo = :id");
        $stmt->execute(array(':id'=>$id));

        while (($row = $stmt->fetch()) !== false) {
            array_push($stack, $row['FKUsuario']);
        }

        
        $stmt2 = $conn->prepare("SELECT u.PKUsuario, CONCAT( e.Nombres, ' ', e.PrimerApellido, ' ', e.SegundoApellido ) AS nombre_empleado FROM usuarios AS u INNER JOIN empleados AS e ON u.FKEmpleado = e.PKEmpleado");
        $stmt2->execute();

        while (($row2 = $stmt2->fetch()) !== false) {
            foreach ($stack as &$value) {
                if($value == $row2['PKUsuario']){
                    $bandera  = true;
                    break;
                }else{
                    $bandera  = false;
                }
            }

            if($bandera == true){
                echo "<option value='".$row2['PKUsuario']."' selected>".$row2['nombre_empleado']."</option>";
            }else{
                echo "<option value='".$row2['PKUsuario']."'>".$row2['nombre_empleado']."</option>";
            }
            
        } 
    }
?>
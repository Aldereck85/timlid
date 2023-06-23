<?php
    if(isset($_POST['id'])){
        require_once('../../../include/db-conn.php');
        $id =  $_POST['id'];
        $stmt = $conn->prepare("SELECT u.PKUsuario, CONCAT(e.Primer_Nombre,' ',e.Segundo_Nombre,' ',e.Apellido_Paterno,' ',e.Apellido_Materno) as nombre_empleado
            FROM usuarios as u INNER JOIN empleados as e ON u.FKEmpleado = e.PKEmpleado");
        $stmt->execute();

        $stmt1 = $conn->prepare("SELECT p.FKResponsable FROM proyectos as p WHERE PKProyecto= :id");
        $stmt1->execute(array(':id'=>$id));
        $row1 = $stmt1->fetch();
        $idUsuario = $row1['FKResponsable'];

        $row = $stmt->fetchAll();

        $html .='<option value="">Elegir opción</option>';
        if(count($row) > 0){ 
            foreach($row as $r){
                
                
                $html .= '<option value="'.$r['PKUsuario'].'"';
                if($r['PKUsuario'] == $idUsuario){
                    $html .= 'selected>'.$r['nombre_empleado'].'</option>';
                }else{
                    $html .= '>'.$r['nombre_empleado'].'</option>';
                }
            }
        }
        else{
            $html .= '<option value="" disabled>No hay usuarios para mostrar.</option>';
        }
        echo $html;  
    }
?>
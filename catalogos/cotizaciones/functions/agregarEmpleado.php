<?php
session_start();
include "../../../include/db-conn.php";
      
      $PKEmpresa = $_SESSION["IDEmpresa"];
      $idEmpleado=0;
      $roles = $_REQUEST['roles'];
      $cp='';
      if(isset($_REQUEST['cp'])){
        $cp = $_REQUEST['cp'];
      }

    try {
      $selectIdEmpleado = "SELECT MAX(id_empleado) + 1 AS id_empleado FROM empleados WHERE empresa_id=:empresa_id";
      $stmSelectIdEmpleado = $conn->prepare($selectIdEmpleado);
      $stmSelectIdEmpleado->execute(array(':empresa_id' => $PKEmpresa));
      $id_empleado = $stmSelectIdEmpleado->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return "Error en Consulta: " . $e->getMessage();
    }

    try {
        $query = "INSERT INTO empleados(id_empleado, Nombres, PrimerApellido, Genero, FKEstado, empresa_id, estatus, CP)
                  VALUES (:id_empleado, :nombre, :apellido, :genero, :estado_id, :empresa_id, 1, :cp)";
        $stmt = $conn->prepare($query);
        $stmt->execute(array(':id_empleado' => $id_empleado['id_empleado'],':nombre' => $_REQUEST['nombre'],':apellido' => $_REQUEST['apellido'],':genero' => $_REQUEST['genero'],':estado_id' => $_REQUEST['estado'], ':empresa_id' => $PKEmpresa, ':cp' => $cp));
        $idEmpleado = $conn->lastInsertId();

    } catch (PDOException $e) {
        return "Error en Consulta: " . $e->getMessage();
    }
    
    try {
      foreach($roles as $rol){
        $query = "INSERT INTO relacion_tipo_empleado (empleado_id, tipo_empleado_id)
                  VALUES (:empleado_id, :rol_id)";
        $stmt = $conn->prepare($query);
        $stmt->execute(array(':empleado_id' => $idEmpleado,':rol_id' => $rol));
      }
    } catch (PDOException $e) {
      return "Error en Consulta: " . $e->getMessage();
    }
      return 'exito';
?>
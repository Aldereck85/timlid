<?php
  session_start();

  if(isset($_SESSION["Usuario"])) {

    $id = $_POST['idempleado'];

    require_once('../../../include/db-conn.php');

    try{
      $stmt = $conn->prepare("SELECT p.puesto FROM empleados as e
                                                       INNER JOIN datos_laborales_empleado as de ON de.FKEmpleado = e.PKEmpleado
                                                       INNER JOIN puestos as p ON p.id = de.FKPuesto
                                              WHERE e.PKEmpleado = ?");
      $stmt->execute(array($id));
      $stmt->execute();
      $row = $stmt->fetch();
      
      if(empty($row)){
        echo $row;
      }else{
        echo $row['puesto'];
      }
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }

  }
  
  
?>

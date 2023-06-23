<?php
    require_once('../../../../include/db-conn.php');
    session_start();
    $verEmpleado        =  $_POST['verEmpleado'];
    $agregarEmpleado    =  $_POST['agregarEmpleado'];
    $editarEmpleado     =  $_POST['editarEmpleado'];
    $eliminarEmpleado   =  $_POST['eliminarEmpleado'];
    $exportarEmpleado   =  $_POST['exportarEmpleado'];
    $vacacionesEmpleado =  $_POST['vacacionesEmpleado'];
    $aguinaldoEmpleado  =  $_POST['aguinaldoEmpleado'];
    $finiquitoEmpleado  =  $_POST['finiquitoEmpleado'];
    $bajaEmpleado       =  $_POST['bajaEmpleado'];
    $funcionVerEmpleado = 1;
    $funcionVerEmpleado = 2;
    $funcionVerEmpleado = 3;
    $funcionVerEmpleado = 4;
    $funcionVerEmpleado = 5;
    $funcionVerEmpleado = 6;
    $funcionVerEmpleado = 7;
    $funcionVerEmpleado = 8;
    $funcionVerEmpleado = 9;
    $usuarioId = 2;


      for(x = 1;x<9;x++){
        try{
          $stmt = $conn->prepare('INSERT INTO permisos_funciones (FKFuncion,FKUsuario) VALUES(:fkFuncion,:fkUsuario)');
          $stmt->bindValue(':fkFuncion',$x);
          $stmt->bindValue(':fkUsuario',$usuarioId);
          $stmt->execute();
        }
        catch(PDOException $ex){
          echo $ex->getMessage();
        }
      }

  //$clave = $_POST['clave'];
  //echo json_encode($cadena);
 ?>

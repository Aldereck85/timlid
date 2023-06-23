<?php
    require_once('../../../../include/db-conn.php');
    session_start();
    $pantallaEmpleado =  $_POST['pantallaEmpleado'];
    $pantallaUsuarios =  $_POST['pantallaUsuarios'];
    $pantallaNomina =  $_POST['pantallaNomina'];
    $pantallaTurnos =  $_POST['pantallaTurnos'];
    $pantallaPuestos =  $_POST['pantallaPuestos'];
    $pantallaSucursales =  $_POST['pantallaSucursales'];

    $funcionEmpleado1 =  $_POST['funcionEmpleado1'];
    $funcionEmpleado2 =  $_POST['funcionEmpleado2'];
    $funcionEmpleado3 =  $_POST['funcionEmpleado3'];
    $funcionEmpleado4 =  $_POST['funcionEmpleado4'];
    $funcionEmpleado5 =  $_POST['funcionEmpleado5'];
    $funcionEmpleado6 =  $_POST['funcionEmpleado6'];
    $funcionEmpleado7 =  $_POST['funcionEmpleado7'];
    $funcionEmpleado8 =  $_POST['funcionEmpleado8'];
    $funcionEmpleado9 =  $_POST['funcionEmpleado9'];

    $funcionUsuarios1 =  $_POST['funcionUsuarios1'];
    $funcionUsuarios2 =  $_POST['funcionUsuarios2'];
    $funcionUsuarios3 =  $_POST['funcionUsuarios3'];
    $funcionUsuarios4 =  $_POST['funcionUsuarios4'];

    $funcionNomina1 =  $_POST['funcionNomina1'];
    $funcionNomina2 =  $_POST['funcionNomina2'];
    $funcionNomina3 =  $_POST['funcionNomina3'];
    $funcionNomina4 =  $_POST['funcionNomina4'];

    $funcionTurnos1 =  $_POST['funcionTurnos1'];
    $funcionTurnos2 =  $_POST['funcionTurnos2'];
    $funcionTurnos3 =  $_POST['funcionTurnos3'];
    $funcionTurnos4 =  $_POST['funcionTurnos4'];

    $funcionPuestos1 =  $_POST['funcionPuestos1'];
    $funcionPuestos2 =  $_POST['funcionPuestos2'];
    $funcionPuestos3 =  $_POST['funcionPuestos3'];
    $funcionPuestos4 =  $_POST['funcionPuestos4'];
    /*$empleados = 1;
    $usuarios = 2;
    $nominas = 3;
    $turnos = 4;
    $puestos = 5;
    $sucursales = 6;*/
    $usuarioId = 2;
    $permiso = true;

      for($x = 1;$x<6;$x++){
        try{
          $stmt = $conn->prepare('INSERT INTO permisos_pantallas (FKPantalla,FKUsuario,Permiso) VALUES(:fkPantalla,:fkUsuario,:permiso)');
          $stmt->bindValue(':fkPantalla',$x);
          $stmt->bindValue(':fkUsuario',$usuarioId);
          $stmt->bindValue(':permiso',$permiso);
          $stmt->execute();
        }
        catch(PDOException $ex){
          echo $ex->getMessage();
        }
      }

      /*for($x = 1;$x<9;$x++){
        try{
          $stmt = $conn->prepare('INSERT INTO permisos_funciones (FKFuncion,FKUsuario,Permiso) VALUES(:fkFuncion,:fkUsuario,:permiso)');
          $stmt->bindValue(':fkFuncion',$x);
          $stmt->bindValue(':fkUsuario',$usuarioId);
          $stmt->bindValue(':permiso',$permiso);
          $stmt->execute();
        }
        catch(PDOException $ex){
          echo $ex->getMessage();
        }
      }*/

      /*for($x = 1;$x<4;$x++){
        try{
          $stmt = $conn->prepare('INSERT INTO permisos_funciones (FKFuncion,FKUsuario,Permiso) VALUES(:fkFuncion,:fkUsuario,:permiso)');
          $stmt->bindValue(':fkFuncion',$x);
          $stmt->bindValue(':fkUsuario',$usuarioId);
          $stmt->bindValue(':permiso',$permiso);
          $stmt->execute();
        }
        catch(PDOException $ex){
          echo $ex->getMessage();
        }
      }*/

  //$clave = $_POST['clave'];
  //echo json_encode($cadena);
 ?>

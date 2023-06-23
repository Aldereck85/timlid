<?php
    require_once('../../../../include/db-conn.php');
    session_start();
    $numPantallas =  $_GET['numPantallas'];
    $numFunciones =  $_GET['numFunciones'];
    $usuarioId = $_GET['idUsuario'];
    //$permiso = true;
    //echo $numPantallas."<br>";
    for($x = 1;$x<$numPantallas+1;$x++){
      $permis = $_GET['Permiso'.$x.''];
      $pantalla = $_GET['Pantalla'.$x.''];
      $permiso = 1;
      if($permis == "true"){
        $permiso = 1;
      }else{
        $permiso = 0;
      }
      try{
        $stmt = $conn->prepare('INSERT INTO permisos_pantallas (FKPantalla,FKUsuario,Permiso) VALUES(:fkPantalla,:fkUsuario,:permiso)');
        $stmt->bindValue(':fkPantalla',$pantalla);
        $stmt->bindValue(':fkUsuario',$usuarioId);
        $stmt->bindValue(':permiso',$permiso);
        $stmt->execute();
      }
      catch(PDOException $ex){
        echo $ex->getMessage();
      }
    }

    for($x = 1;$x<$numFunciones+1;$x++){
      $permis = $_GET['PermisoFuncion'.$x.''];
      $funcion = $_GET['Funcion'.$x.''];
      $permiso = 1;
      if($permis == "true"){
        $permiso = 1;
      }else{
        $permiso = 0;
      }
      try{
        $stmt = $conn->prepare('INSERT INTO permisos_funciones (FKFuncion,FKUsuario,Permiso) VALUES(:fkFuncion,:fkUsuario,:permiso)');
        $stmt->bindValue(':fkFuncion',$funcion);
        $stmt->bindValue(':fkUsuario',$usuarioId);
        $stmt->bindValue(':permiso',$permiso);
        $stmt->execute();
      }
      catch(PDOException $ex){
        echo $ex->getMessage();
      }
    }

 ?>

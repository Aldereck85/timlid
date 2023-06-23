<?php
  require_once('../../../include/db-conn.php');
  if(isset($_POST['id'])){
    $id = $_POST['id'];
    $descripcion = $_POST['descripcion'];
    $fecha = $_POST['fecha'];
    $usuario = $_POST['usuario'];
    $estatus = $_POST['estatus'];

    //para contar cuantas descripciones de la tarea hay y mostrar la primera
    $stmt = $conn->prepare('SELECT * FROM texto_tarea WHERE FKTarea = :id ORDER BY FKTarea ASC LIMIT 1');
    $stmt->execute(array(':id'=>$id));
    $textCount = $stmt->rowCount();

    //Si hay descripciones actualiza el texto
    if($textCount > 0){
      $stmt = $conn->prepare('UPDATE texto_tarea SET Texto = :texto WHERE FKTarea = :id');
      $stmt->execute(array(':id'=>$id,":texto"=>$descripcion));
      echo "Se actualizó el texto con éxito.";
    //Si no hay descripciones inserta el texto
    }else{
      $stmt = $conn->prepare('INSERT INTO texto_tarea (Texto,FKTarea) VALUES (:texto,:id)');
      $stmt->execute(array(':id'=>$id,":texto"=>$descripcion));
      echo "Se insertó el texto con éxito.";
    }

    //contar cuantas fechas hay y mostrar la primera
    $stmt = $conn->prepare('SELECT * FROM fecha_tarea WHERE FKTarea = :id ORDER BY FKTarea ASC LIMIT 1');
    $stmt->execute(array(':id'=>$id));
    $dateCount = $stmt->rowCount();

    //Si hay fecha la actualiza
    if($dateCount > 0){
      $stmt = $conn->prepare('UPDATE fecha_tarea SET Fecha = :fecha WHERE FKTarea = :id');
      $stmt->execute(array(':id'=>$id,":fecha"=>$fecha));
      echo "Se actualizó la fecha con éxito.";
    //Si no hay fecha la inserta
    }else{
      $stmt = $conn->prepare('INSERT INTO fecha_tarea (Fecha,FKTarea) VALUES (:fecha,:id)');
      $stmt->execute(array(':id'=>$id,":fecha"=>$fecha));
      echo "Se insertó la fecha con éxito.";
    }

    //contar cuantas usuarios hay y mostrar el primero
    $stmt = $conn->prepare('SELECT * FROM responsables_tarea WHERE FKTarea = :id ORDER BY FKTarea ASC LIMIT 1');
    $stmt->execute(array(':id'=>$id));
    $userCount = $stmt->rowCount();

    //Si hay responsable lo actualiza
    if($userCount > 0){
      $stmt = $conn->prepare('UPDATE responsables_tarea SET FKUsuario = :usuario WHERE FKTarea = :id');
      $stmt->execute(array(':id'=>$id,":usuario"=>$usuario));
      echo "Se actualizó la fecha con éxito.";
    //Si no hay responsable lo inserta
    }else{
      $stmt = $conn->prepare('INSERT INTO responsables_tarea (FKUsuario,FKTarea) VALUES (:fecha,:id)');
      $stmt->execute(array(':id'=>$id,":usuario"=>$usuario));
      echo "Se insertó la fecha con éxito.";
    }

    //contar cuantos estatus hay y mostrar el primero
    $stmt = $conn->prepare('SELECT * FROM estado_tarea WHERE FKTarea = :id ORDER BY FKTarea ASC LIMIT 1');
    $stmt->execute(array(':id'=>$id));
    $statusCount = $stmt->rowCount();

    //Si hay estatus lo actualiza
    if($statusCount > 0){
      $stmt = $conn->prepare('UPDATE estado_tarea SET FKColorColumna = :estatus WHERE FKTarea = :id');
      $stmt->execute(array(':id'=>$id,":estatus"=>$estatus));
      echo "Se actualizó la fecha con éxito.";
    //Si no hay responsable lo inserta
    }else{
      $stmt = $conn->prepare('INSERT INTO estado_tarea (FKColorColumna,FKTarea) VALUES (:estatus,:id)');
      $stmt->execute(array(':id'=>$id,":estatus"=>$estatus));
      echo "Se insertó la fecha con éxito.";
    }
    //echo $id."\n".$descripcion."\n".$fecha."\n".$usuario."\n".$estatus;
  }


?>

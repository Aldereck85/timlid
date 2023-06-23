<?php
  require_once('../../../include/db-conn.php');

  if(isset($_POST['project'])){
    $project = $_POST['project'];
    $tipo = $_POST['tipo'];

    $stmt = $conn->prepare('SELECT Orden FROM columnas_proyecto WHERE FKProyecto = :id ORDER BY Orden DESC LIMIT 1');
    $stmt->execute(array(':id'=>$project));
    $row = $stmt->fetch();
    $orden = $row['Orden'] + 1;

    $stmt = $conn->prepare('SELECT * FROM tareas WHERE FKProyecto = :proyecto');
    $stmt->execute(array(':proyecto'=>$project));
    $arrayTareas = $stmt->fetchAll();

    switch ($tipo) {
      case 1:
        $stmt = $conn->prepare('INSERT INTO columnas_proyecto (nombre,Tipo,Orden,FKProyecto) VALUES (:nombre,:tipo,:orden,:proyecto)');
        $stmt->execute(array(':nombre'=>"Responsable",':orden'=>$orden,':proyecto'=>$project,':tipo'=>$tipo));
        $id = $conn->lastInsertId();

        foreach ($arrayTareas as $r) {
          $stmt = $conn->prepare('INSERT INTO responsables_tarea (FKTarea,FKColumnaProyecto,FKProyecto) VALUES (:tarea,:columna,:FKProyecto)');
          $stmt->execute(array(':tarea'=>$r['PKTarea'],':columna'=>$id,':FKProyecto'=>$project));
        }
      break;
      case 2:
        $stmt = $conn->prepare('INSERT INTO columnas_proyecto (nombre,Tipo,Orden,FKProyecto) VALUES (:nombre,:tipo,:orden,:proyecto)');
        $stmt->execute(array(':nombre'=>"Estado",':orden'=>$orden,':proyecto'=>$project,':tipo'=>$tipo));
        $id = $conn->lastInsertId();

        $arrayNombreEstado = ['Hecho','Pendiente','Atrasado',''];
        $arrayColorEstado = ['#28c67a','#ede966','#e53341','#9a9a9a'];


        for ($i=0; $i < count($arrayNombreEstado); $i++) {
          $stmt = $conn->prepare('INSERT INTO colores_columna (nombre,color,FKColumnaProyecto,FKProyecto,Orden) VALUES (:nombre,:color,:columna,:proyecto,orden)');
          $stmt->execute(':nombre'=$arrayNombreEstado[$i],':color'=>$arrayColorEstado[$i],$id,$orden);
        }
        $column = $conn->lastInsertId();

        foreach ($arrayTareas as $r) {
          $stmt = $conn->prepare('INSERT INTO estado_tarea (FKColorColumna,FKTarea) VALUES (:color,:tarea)');
          $stmt->execute(':color'=>$column,':tarea'=>$r['PKTarea']);
        }
        
      break;
      case 3:
        $stmt = $conn->prepare('INSERT INTO columnas_proyecto (nombre,Tipo,Orden,FKProyecto) VALUES (:nombre,:tipo,:orden,:proyecto)');
        $stmt->execute(array(':nombre'=>"Fecha",':orden'=>$orden,':proyecto'=>$project,':tipo'=>$tipo));
        $id = $conn->lastInsertId();

        foreach ($arrayTareas as $r) {
          $stmt = $conn->prepare('INSERT INTO fecha_tarea (Fecha,FKTarea,FKColumnaProyecto,FKProyecto) VALUES (:fecha,:tarea,:columna,:FKProyecto)');
          $stmt->execute(array(':fecha'=>"0000-00-00",':tarea'=>$r['PKTarea'],':columna'=>$id,':FKProyecto'=>$project));
        }
      break;

    }

  }



?>

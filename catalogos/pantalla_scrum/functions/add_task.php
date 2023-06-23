<?php
  session_start();
  require_once('../../../include/db-conn.php');
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  date_default_timezone_set('America/Mexico_City');
  if(isset($_POST['id'])){
    $id = $_POST['id'];
    $json = [];
    //$fecha = date('Y-m-d');
    $fecha = '0000-00-00';
    $html = "";
    //$startStage = "No hay datos";

    if(isset($_POST['stageId'])){
      $startStage = $_POST['stageId'];
    }else{
      $stmt = $conn->prepare('SELECT PKEtapa FROM etapas WHERE FKProyecto =:id ORDER BY PKEtapa ASC LIMIT 1');
      $stmt->execute(array(':id'=>$id));
      $row = $stmt->fetch();
      $startStage = $row['PKEtapa'];
    }

    $stmt = $conn->prepare('SELECT Orden FROM tareas WHERE FKProyecto=:id AND FKEtapa=:etapa ORDER BY Orden DESC LIMIT 1');
    $stmt->execute(array(':id'=>$id,':etapa'=>$startStage));
    $noTareas = $stmt->rowCount();
    $row = $stmt->fetch();
    if($noTareas > 0){
      $order = $row['Orden'] + 1;
    }else{
      $order = 1;
    }



    $stmt = $conn->prepare('SELECT PKTarea,Orden FROM tareas WHERE FKProyecto=:id AND Orden >= :orden');
    $stmt->execute(array(':id'=>$id,':orden'=>$order));
    $row = $stmt->fetchAll();
    foreach ($row as $r) {
      $orden = $r['Orden'] + 1;
      $stmt = $conn->prepare('UPDATE tareas SET Orden=:orden WHERE PKTarea = :id');
      $stmt->execute(array(':id'=>$r['PKTarea'],':orden'=>$orden));
    }

    //agregar tarea nueva
    $stmt = $conn->prepare('INSERT INTO tareas (Tarea,Orden,FKProyecto,FKEtapa) VALUES (:tarea,:orden,:id,:etapa)');
    $stmt->execute(array(':tarea'=>'Tarea nueva',':orden'=>$order,':id'=>$id,':etapa'=>$startStage));
    $idLast = $conn->lastInsertId();

    //Actualizando registro de actividades de la tarea
    $fechaActividad = date('Y-m-d H:i:s');
    $insertar_actividad = sprintf("INSERT INTO chat_actividad (Tipo, Fecha, FKTarea, FKUsuario) VALUES (?,?,?,?)");
    $stmt = $conn->prepare($insertar_actividad);
    $stmt->execute(array(0,$fechaActividad,$idLast,$_SESSION['PKUsuario']));

    //Insertar fecha de acuerdo a las columnas del proyecto de tipo fecha
    $stmt = $conn->prepare('SELECT PKColumnaProyecto FROM columnas_proyecto WHERE FKProyecto = :id AND Tipo = 3');
    $stmt->execute(array(':id'=>$id));
    $nFechasColumnas = $stmt->rowCount();
    $row = $stmt->fetchAll();
    if($nFechasColumnas > 0){
      $aux = 0;
      foreach ($row as $r) {
        $stmt = $conn->prepare('INSERT INTO fecha_tarea (Fecha,FKTarea,FKColumnaProyecto,FKProyecto) VALUES (:fecha,:tarea,:columna,:id)');
        $stmt->execute(array(':fecha'=>$fecha,':tarea'=>$idLast,':columna'=>$r['PKColumnaProyecto'],':id'=>$id));
        $aux++;
      }
    }
    /*
    else{
      $stmt = $conn->prepare('SELECT Orden FROM columnas_proyecto WHERE FKProyecto = :id ORDER BY Orden DESC LIMIT 1');
      $stmt->execute(array(':id'=>$id));
      $row = $stmt->fetch();
      $nFechasColumnas = $stmt->rowCount();
      if($nFechasColumnas > 0){
        $ordenColumnas = $row['Orden'] + 1;
      }else{
        $ordenColumnas = 1;
      }

      $stmt = $conn->prepare('INSERT INTO columnas_proyecto (nombre,Tipo,Orden,FKProyecto) VALUES (:nombre,:tipo,:orden,:id)');
      $stmt->execute(array(':nombre'=>'Fecha nueva',':tipo'=>3,':orden'=>$ordenColumnas,':id'=>$id));
      $ultimaColumna = $conn->lastInsertId();

      $stmt = $conn->prepare('INSERT INTO fecha_tarea (Fecha,FKTarea,FKColumnaProyecto,FKProyecto) VALUES (:fecha,:tarea,:columna,:id)');
      $stmt->execute(array(':fecha'=>$fecha,':tarea'=>$idLast,':columna'=>$ultimaColumna,':id'=>$id));
    }
    */

    //Insertar Responsable de acuerdo a las columnas del proyecto de tipo responsable
    $stmt = $conn->prepare('SELECT PKColumnaProyecto FROM columnas_proyecto WHERE FKProyecto = :id AND Tipo = 1');
    $stmt->execute(array(':id'=>$id));
    $nResponsableColumnas = $stmt->rowCount();
    $row = $stmt->fetchAll();
    if($nResponsableColumnas > 0){
      foreach ($row as $r) {
        $stmt = $conn->prepare('INSERT INTO responsables_tarea (FKTarea,FKColumnaProyecto,FKProyecto) VALUES (:tarea,:columna,:id)');
        $stmt->execute(array(':tarea'=>$idLast,':columna'=>$r['PKColumnaProyecto'],':id'=>$id));
      }
    }

    /*
    else{
      $stmt = $conn->prepare('SELECT Orden FROM columnas_proyecto WHERE FKProyecto = :id ORDER BY Orden DESC LIMIT 1');
      $stmt->execute(array(':id'=>$id));
      $row = $stmt->fetch();
      $nResponsableColumnas = $stmt->rowCount();
      if($nResponsableColumnas > 0){
        $ordenColumnas = $row['Orden'] + 1;
      }else{
        $ordenColumnas = 1;
      }

      $stmt = $conn->prepare('INSERT INTO responsables_tarea (FKTarea,FKColumnaProyecto,FKProyecto) VALUES (:tarea,:columna,:id)');
      $stmt->execute(array(':tarea'=>$idLast,':columna'=>$r['PKColumnaProyecto'],':id'=>$id));
    }
    */
    //Insertar estado de acuerdo a las columnas del proyecto de tipo estado
    $stmt = $conn->prepare('SELECT PKColumnaProyecto FROM columnas_proyecto WHERE FKProyecto = :id AND Tipo = 2');
    $stmt->execute(array(':id'=>$id));
    $nFechasColumnas = $stmt->rowCount();
    $row = $stmt->fetchAll();

    if($nFechasColumnas > 0){
      foreach ($row as $r) {
        $stmt = $conn->prepare('SELECT PKColorColumna FROM colores_columna WHERE FKProyecto = :id AND FKColumnaProyecto = :idColumna ORDER BY PKColorColumna DESC LIMIT 1');
        $stmt->execute(array(':id'=>$id,':idColumna'=>$r['PKColumnaProyecto']));
        $row1 = $stmt->fetch();

        $stmt = $conn->prepare('INSERT INTO estado_tarea (FKColorColumna,FKTarea) VALUES (:color,:tarea)');
        $stmt->execute(array(':color'=>$row1['PKColorColumna'],':tarea'=>$idLast));
      }
    }

    //Insertar hipervinculo de acuerdo a las columnas del proyecto de tipo hipervinculo
    $stmt = $conn->prepare('SELECT PKColumnaProyecto FROM columnas_proyecto WHERE FKProyecto = :id AND Tipo = 4');
    $stmt->execute(array(':id'=>$id));
    $nHipervinculoColumnas = $stmt->rowCount();
    $row = $stmt->fetchAll();

    if($nHipervinculoColumnas > 0){
      foreach ($row as $r) {
        $stmt = $conn->prepare('INSERT INTO hipervinculo_tarea (FKTarea,FKColumnaProyecto,FKProyecto) VALUES (:tarea,:columna,:id)');
        $stmt->execute(array(':tarea'=>$idLast,':columna'=>$r['PKColumnaProyecto'],':id'=>$id));
      }
    }
    /*
    else{
      $stmt = $conn->prepare('SELECT Orden FROM columnas_proyecto WHERE FKProyecto = :id ORDER BY Orden DESC LIMIT 1');
      $stmt->execute(array(':id'=>$id));
      $row = $stmt->fetch();
      $ordenColumnas = $row['Orden'] + 1;

      $stmt = $conn->prepare('INSERT INTO columnas_proyecto (nombre,Tipo,Orden,FKProyecto) VALUES (:nombre,:tipo,:orden,:id)');
      $stmt->execute(array(':nombre'=>'Hipervinculo nuevo',':tipo'=>4,':orden'=>$ordenColumnas,':id'=>$id));
      $ultimaColumna = $conn->lastInsertId();

      $stmt = $conn->prepare('INSERT INTO hipervinculo_tarea (FKTarea,FKColumnaProyecto,FKProyecto) VALUES (:tarea,:columna,:id)');
      $stmt->execute(array(':tarea'=>$idLast,':columna'=>$ultimaColumna,':id'=>$id));
    }
    */

    //Insertar telefono de acuerdo a las columnas del proyecto de tipo telefono
    $stmt = $conn->prepare('SELECT PKColumnaProyecto FROM columnas_proyecto WHERE FKProyecto = :id AND Tipo = 7');
    $stmt->execute(array(':id'=>$id));
    $nTelefonoColumnas = $stmt->rowCount();
    $row = $stmt->fetchAll();

    if($nTelefonoColumnas > 0){
      foreach ($row as $r) {
        $stmt = $conn->prepare('INSERT INTO telefono_tarea (FKTarea,FKColumnaProyecto) VALUES (:tarea,:columna)');
        $stmt->execute(array(':tarea'=>$idLast,':columna'=>$r['PKColumnaProyecto']));
      }
    }

    //Insertar numeros de acuerdo a las columnas del proyecto de tipo numero
    $stmt = $conn->prepare('SELECT PKColumnaProyecto FROM columnas_proyecto WHERE FKProyecto = :id AND Tipo = 8');
    $stmt->execute(array(':id'=>$id));
    $nNumeroColumnas = $stmt->rowCount();
    $row = $stmt->fetchAll();

    if($nNumeroColumnas > 0){
      foreach ($row as $r) {
        $stmt = $conn->prepare('INSERT INTO numeros_tabla (FKTarea,FKColumnaProyecto,Simbolo) VALUES (:tarea,:columna,:simbolo)');
        $stmt->execute(array(':tarea'=>$idLast,':columna'=>$r['PKColumnaProyecto'],':simbolo'=>'$'));
      }
    }

    //Insertar menu desplegable de acuerdo a las columnas del proyecto de tipo menu desplegable
    $stmt = $conn->prepare('SELECT PKColumnaProyecto FROM columnas_proyecto WHERE FKProyecto = :id AND Tipo = 6');
    $stmt->execute(array(':id'=>$id));
    $nMenusColumnas = $stmt->rowCount();
    $row = $stmt->fetchAll();

    if($nMenusColumnas > 0){
      foreach ($row as $r) {
        $stmt = $conn->prepare('INSERT INTO menu_columna (FKTarea,FKColumnaProyecto,FKProyecto) VALUES (:tarea,:columna,:id)');
        $stmt->execute(array(':tarea'=>$idLast,':columna'=>$r['PKColumnaProyecto'],':id'=>$id));
      }
    }

    //Insertar verificar de acuerdo a las columnas del proyecto de tipo verificar
    $stmt = $conn->prepare('SELECT PKColumnaProyecto FROM columnas_proyecto WHERE FKProyecto = :id AND Tipo = 9');
    $stmt->execute(array(':id'=>$id));
    $nVerificarColumnas = $stmt->rowCount();
    $row = $stmt->fetchAll();

    if($nVerificarColumnas > 0){
      foreach ($row as $r) {
        $stmt = $conn->prepare('INSERT INTO verificacion_tarea (FKTarea,FKColumnaProyecto) VALUES (:tarea,:columna)');
        $stmt->execute(array(':tarea'=>$idLast,':columna'=>$r['PKColumnaProyecto']));
      }
    }

    //Insertar progreso de acuerdo a las columnas del proyecto de tipo progreso
    $stmt = $conn->prepare('SELECT PKColumnaProyecto FROM columnas_proyecto WHERE FKProyecto = :id AND Tipo = 10');
    $stmt->execute(array(':id'=>$id));
    $nProgresoColumnas = $stmt->rowCount();
    $row = $stmt->fetchAll();

    if($nProgresoColumnas > 0){
      foreach ($row as $r) {
        $stmt = $conn->prepare('INSERT INTO progreso_tarea (FKTarea,FKColumnaProyecto) VALUES (:tarea,:columna)');
        $stmt->execute(array(':tarea'=>$idLast,':columna'=>$r['PKColumnaProyecto']));
      }
    }

    //Insertar cronograma de acuerdo a las columnas del proyecto de tipo cronograma
    $stmt = $conn->prepare('SELECT PKColumnaProyecto FROM columnas_proyecto WHERE FKProyecto = :id AND Tipo = 11');
    $stmt->execute(array(':id'=>$id));
    $nRangoColumnas = $stmt->rowCount();
    $row = $stmt->fetchAll();

    if($nRangoColumnas > 0){
      foreach ($row as $r) {
        $stmt = $conn->prepare('INSERT INTO rango_fecha (FKTarea,FKColumnaProyecto) VALUES (:tarea,:columna)');
        $stmt->execute(array(':tarea'=>$idLast,':columna'=>$r['PKColumnaProyecto']));
      }
    }
    /*
    $stmt = $conn->prepare('INSERT INTO responsables_tarea (FKTarea,FKColumnaProyecto,FKProyecto) VALUES (:tarea,:columna,:id)');
    $stmt->execute(array(':tarea'=>$idLast,':columna'=>3,':id'=>$id));

    $stmt = $conn->prepare('INSERT INTO responsables_tarea (FKTarea,FKColumnaProyecto,FKProyecto) VALUES (:tarea,:columna,:id)');
    $stmt->execute(array(':tarea'=>$idLast,':columna'=>5,':id'=>$id));

    $stmt = $conn->prepare('INSERT INTO estado_tarea (FKColorColumna,FKTarea) VALUES (:color,:tarea)');
    $stmt->execute(array(':color'=>13,':tarea'=>$idLast));
    */
    $stmt = $conn->prepare('SELECT task.PKTarea,task.Tarea,dtask.Fecha FROM tareas AS task
                            LEFT JOIN fecha_tarea AS dtask ON task.PKTarea = dtask.FKTarea
                            WHERE task.PKTarea = :id');
    $stmt->execute(array(':id'=>$idLast));
    $row = $stmt->fetchAll();
    $i = 0;

    foreach ($row as $r) {
      $json[$i]= $r;
      $i++;
      /*
      if(!is_null($r['Fecha']) || $r['Fecha'] != ""){
        if($r['Fecha'] != "0000-00-00"){
          $fecha = date('d-m-Y',strtotime($r['Fecha']));
        }else{
          $fecha = '0000-00-00';
        }
      }

        $html = '<div class="task-content" data-id1="'.$r['PKTarea'].'">
                  <div class="title-task-new" style="background-color:#5cb85c" data-id2="'.$r['PKTarea'].'" id="task'.$r['PKTarea'].'">'.$r['Tarea'].'</div>
                  <div class="float-left priority-icon">'.$fecha.'</div>
                  <div class="float-right chat-icon">
                    <a href="#" class="chat-icon-text"><i class="far fa-comment fa-flip-horizontal"></i></a>
                  </div>
                  <hr class="line-hr">
                  </div>
                </div>';
*/
    }

    echo json_encode($json);

    //echo $html;
  }

?>

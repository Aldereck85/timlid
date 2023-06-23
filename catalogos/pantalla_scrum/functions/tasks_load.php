<?php
  require_once('../../../include/db-conn.php');
  $id = $_POST['id'];
  $html = "";
  $etapas = "";
  $fecha = "";
  $tipo = "";

  $stmt = $conn->prepare('SELECT PKEtapa FROM etapas WHERE FKProyecto =:id ORDER BY PKEtapa ASC LIMIT 1');
  $stmt->execute(array(':id'=>$id));
  $row = $stmt->fetch();
  if($row){
    $startStage = $row['PKEtapa'];

    $stmt = $conn->prepare('SELECT PKEtapa,Etapa FROM etapas WHERE FKProyecto = :id AND PKEtapa <> :stage ORDER BY Orden ASC');
  $stmt->execute(array(':id'=>$id,':stage'=>$startStage));
  $row = $stmt->fetchAll();
  foreach($row as $r){
    $tipo = "'etapa'";
    //$html .= '<div class="task-content"><div class="title-task-new" id="task'.$row['PKTarea'].'" data-id="&'.$row['FKEtapa'].'&">'.$row['Tarea'].'</div></div>';
      //boton agregar tarea para las etapas flotante arriba a la derecha <a class="icon-add-plus add-taskStage" data-id5="'.$row['PKEtapa'].'" href="#"><img src="../../img/scrum/agregar_tarea_gris.svg"></a>
      $html .= '<div class="container-task" id="container-task'.$r['PKEtapa'].'">
                  <span id="stage-tip-'.$r['PKEtapa'].'" class="pos-abs group-tip-content d-no"></span>
                  <div class="title-stage-new">
                    <input type="text" id="title-stage-'.$r['PKEtapa'].'" class="title-stage" data-id1="'.$r['PKEtapa'].'" contenteditable="true" onmouseenter="show_task_tip('.$r['PKEtapa'].','.$tipo.');" onmouseleave="group_tip_hidden('.$r['PKEtapa'].','.$tipo.');" value="'.$r['Etapa'].'">
                  </div>
                  <div class="content-stage-task" id="content-stage-task'.$r['PKEtapa'].'" data-id="'.$r['PKEtapa'].'">';
      $stmt1 = $conn->prepare('SELECT task.Tarea,task.PKTarea,task.FKEtapa,ptask.Prioridad FROM tareas AS task
                               LEFT JOIN prioridad_tareas AS ptask ON task.PKTarea = ptask.FKTarea
                               WHERE task.FKProyecto = :id AND task.FKEtapa <> :etapa
                               ORDER BY task.Orden ASC');
      $stmt1->execute(array(':id'=>$id,':etapa'=>$startStage));
      $row1 = $stmt1->fetchAll();
      foreach($row1 as $r1){

        switch($r1['Prioridad']){
          case 1:
            $prioridad = 'Alta';
            $color = '#e53341';
            break;
          case 2:
            $prioridad = 'Media';
            $color = '#ede966';
            break;
          case 3:
            $prioridad = 'Normal';
            $color = '#28c67a';
          break;
          default:
            $prioridad = 'Normal';
            $color = '#28c67a';
            break;
        }
        $stmt = $conn->prepare('SELECT Fecha FROM fecha_tarea WHERE FKTarea = :id ORDER BY FKTarea ASC LIMIT 1');
        $stmt->execute(array(':id'=>$r1['PKTarea']));
        $row2 = $stmt->fetch();
        $contarFecha = $stmt->rowCount();
        $tipo = "tarea";
        if($contarFecha > 0){
          if(!is_null($row2['Fecha']) || $row2['Fecha'] != ""){
            if($row2['Fecha'] != "0000-00-00"){
              $fecha = date('d-m-Y',strtotime($row2['Fecha']));
            }else{
              $fecha = "dd-mm-aaaa";
            }
          }
        }else{
          $fecha = "dd-mm-aaaa";
        }
        /*
        $stmt = $conn->prepare('SELECT FechaTermino FROM cronograma_tarea WHERE FKTarea = :id ORDER BY FKTarea ASC LIMIT 1');
        $stmt->execute(array(':id'=>$r['PKTarea']));
        $row2 = $stmt->fetch();
        $contarFecha = $stmt->rowCount();

        if($contarFecha > 0){
          if(!is_null($row1['FechaTermino']) || $row2['FechaTermino'] != ""){
            if($row2['Fecha'] != "dd-mm-aaaa"){
              $fecha = date('d-m-Y',strtotime($row2['FechaTermino']));
            }else{
              $fecha = "dd-mm-aaaa";
            }
          }
        }else{
          $fecha = "dd-mm-aaaa";
        }
        */
        if($r['PKEtapa'] == $r1['FKEtapa']){
          $tipo = "'tarea'";
          $html .= '
            <div class="task-content" data-id1="'.$r1['PKTarea'].'">
              <span id="task-tip-'.$r1['PKTarea'].'" class="pos-abs group-tip-content d-no"></span>
              <div class="title-task-new" style="background-color:'.$color.'" data-id2="'.$r1['PKTarea'].'" id="task'.$r1['PKTarea'].'" onmouseenter="show_task_tip('.$r1['PKTarea'].','.$tipo.')" onmouseleave="group_tip_hidden('.$r1['PKTarea'].','.$tipo.')">'.$r1['Tarea'].'
              <input type="hidden" id="task-title-'.$r1['PKTarea'].'" value="'.$r1['Tarea'].'">
              </div>
              <div class="float-left priority-icon">'.$fecha.'</div>
              <div class="float-right chat-icon">
                <a href="#" class="chat-icon-text"><i class="far fa-comment fa-flip-horizontal"></i></a>
              </div>
              <hr class="line-hr">
            </div>';
        }
      }
      $html .= '</div><div class="button-add-newtask">
        <a href="#" class="add-task add-taskStage" data-id5="'.$r['PKEtapa'].'"><img src="../../img/scrum/agregar_tarea_gris.svg" alt=""></a>
      </div></div>';
  }
  /*$html .= '<div class="add-stage-new">
              <div class="button-add-stage">
                <a href="#" class="add-task" id="add-task"><i class="fas fa-plus plus icon-add-plus"></i> </a>
              </div>
            </div>';*/
  echo $html;
  }


  

?>

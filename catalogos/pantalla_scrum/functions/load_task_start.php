<?php
  require_once('../../../include/db-conn.php');
  $id = $_POST['id'];
  $html = "";
  $fecha = "";

  $stmt = $conn->prepare('SELECT PKEtapa FROM etapas WHERE FKProyecto =:id ORDER BY PKEtapa ASC LIMIT 1');
  $stmt->execute(array(':id'=>$id));
  $row = $stmt->fetch();
  $startStage = $row['PKEtapa'];

  $stmt = $conn->prepare('SELECT task.Tarea, task.PKTarea, task.FKEtapa, ptask.Prioridad FROM tareas AS task
                           LEFT JOIN prioridad_tareas AS ptask ON task.PKTarea = ptask.FKTarea
                           WHERE task.FKProyecto = :id AND task.FKEtapa = :etapa
                           ORDER BY Orden ASC');
  $stmt->execute(array(':id'=>$id,':etapa'=>$startStage));
  $row = $stmt->fetchAll();

  foreach ($row as $r) {
    switch($r['Prioridad']){
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
    $stmt->execute(array(':id'=>$r['PKTarea']));
    $row1 = $stmt->fetch();
    $contarFecha = $stmt->rowCount();

    if($contarFecha > 0){
      if(!is_null($row1['Fecha']) || $row1['Fecha'] != ""){
        if($row1['Fecha'] != "0000-00-00"){
          $fecha = date('d-m-Y',strtotime($row1['Fecha']));
        }else{
          $fecha = "dd-mm-aaaa";
        }
      }
    }else{
      $fecha = "dd-mm-aaaa";
    }
      /*
      if(!is_null($row['FechaTermino']) || $row['FechaTermino'] != ""){
        if($row['FechaTermino'] != ""){
          $fecha = date('d-m-Y',strtotime($row['FechaTermino']));
        }else{
          $fecha = "dd-mm-aaaa";
        }
      }*/



    $html .= '<div class="task-content" data-id1="'.$r['PKTarea'].'">
                <div class="title-task-new" style="background-color:'.$color.'" data-id2="'.$r['PKTarea'].'" id="task'.$r['PKTarea'].'" >'.$r['Tarea'].'</div>
                <div class="float-left priority-icon">'.$fecha.'</div>
                <div class="float-right chat-icon">
                  <a href="#" class="chat-icon-text"><i class="far fa-comment fa-flip-horizontal"></i></a>
                </div>
                <hr class="line-hr">
              </div>';
  }
  echo $html;
?>

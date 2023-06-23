<?php
  require_once('../../../include/db-conn.php');
  $id = $_POST['id'];
  $html = "";
  $stmt = $conn->prepare('SELECT PKEtapa,Etapa FROM etapas WHERE FKProyecto = :id');
  $stmt->execute(array(':id'=>$id));

  while($row = $stmt->fetch()){
    $html .= '<div class="container-task" id="container-task">
                <span id="stage-tip-'.$row['PKEtapa'].'" class="pos-abs group-tip-content d-no"></span>
                <div class="title-stage-new">
                  <input type="text" id="title-stage-'.$row['PKEtapa'].'" class="title-stage" data-id1="'.$row['PKEtapa'].'" contenteditable="true" onmouseenter="show_task_tip('.$row['PKEtapa'].')" onmouseleave="group_tip_hidden('.$row['PKEtapa'].')" value="'.$row['Etapa'].'">
                </div>
                <div class="content-stage-task" id="content-stage-task'.$row['PKEtapa'].'"></div>
              </div>';
  }

  echo $html;

?>

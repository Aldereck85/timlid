<?php

$rutes = $_GET['ruta'];

//$edit = $_GET['ruteEdit'];

require_once('../include/db-conn.php');

  if(isset($_SESSION['PKUsuario'])){
    $usuario = $_SESSION['PKUsuario'];
  }else{
    $usuario = $_GET['user'];
  }


?>
<link rel="stylesheet" href="../<?=$rutes; ?>css/notificaciones.css">
<!-- Nav Item - Alerts -->
<li id="notificationContainer" class="nav-item dropdown no-arrow mx-1">
  <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <img src="../<?=$rutes;?>img/notificaciones/ICONO ALERTAS_Mesa de trabajo 1.svg" width="25px">
    <!-- Counter - Alerts -->
    <?php
      $stmt = $conn->prepare('SELECT chatNoti.PKChat_Notificaciones,chatNoti.FechaCreacion,task.Tarea,task.PKTarea,project.PKProyecto, chatNoti.FKTipoNotificacion
                              FROM chat_notificaciones AS chatNoti
                              LEFT JOIN chat AS ch ON chatNoti.FKChat = ch.PKChat
                              LEFT JOIN tareas AS task ON ch.FKTarea = task.PKTarea
                              LEFT JOIN proyectos AS project ON task.FKProyecto = project.PKProyecto
                              WHERE chatNoti.FKUsuario = :id AND chatNoti.Visto = 0');
      $stmt->execute(array(':id'=>$usuario));
      $nChatsNuevos = $stmt->rowCount();
      $arrayChatsNuevos = $stmt->fetchAll();

      $stmt = $conn->prepare('SELECT taskNoti.PKTareaNotificacion,taskNoti.FechaCreacion,task.PKTarea,task.Tarea,project.PKProyecto, taskNoti.FKTipoNotificacion
                              FROM tarea_notificaciones AS taskNoti
                              LEFT JOIN tareas AS task ON taskNoti.FKTarea = task.PKTarea
                              LEFT JOIN proyectos AS project ON task.FKProyecto = project.PKProyecto
                              WHERE taskNoti.FKResponsableTarea = :id AND taskNoti.Visto = 0');
      $stmt->execute(array(':id'=>$usuario));
      $nTareasNuevos = $stmt->rowCount();
      $arrayTareasNuevas = $stmt->fetchAll();

      $stmt = $conn->prepare('SELECT subTaskNoti.PKSubTareaNotificacion,subTaskNoti.FechaCreacion,subtask.SubTarea,task.Tarea,task.PKTarea,project.PKProyecto, subTaskNoti.FKTipoNotificacion
                              FROM subtarea_notificaciones AS subTaskNoti
                              LEFT JOIN subtareas AS subtask ON subTaskNoti.FKSubTarea = subtask.PKSubTarea
                              LEFT JOIN tareas AS task ON subtask.FKTarea = task.PKTarea
                              LEFT JOIN proyectos AS project ON task.FKProyecto = project.PKProyecto
                              WHERE subTaskNoti.FKResponsableSubTarea = :id AND subTaskNoti.Visto = 0');
      $stmt->execute(array(':id'=>$usuario));
      $nSubTareasNuevos = $stmt->rowCount();
      $arraySubTareasNuevas = $stmt->fetchAll();

      $stmt = $conn->prepare('SELECT checkNoti.PKVerificacionNotificacion,checkNoti.FechaCreacion,task.Tarea,task.PKTarea,checkNoti.FKTarea,project.PKProyecto, checkNoti.FKTipoNotificacion
                              FROM verificacion_notificaciones AS checkNoti
                              LEFT JOIN tareas AS task ON checkNoti.FKTarea = task.PKTarea
                              LEFT JOIN proyectos AS project ON task.FKProyecto = project.PKProyecto
                              WHERE checkNoti.FKResponsableTarea = :id AND checkNoti.Visto = 0');
      $stmt->execute(array(':id'=>$usuario));
      $nVerificacionesNuevas = $stmt->rowCount();
      $arrayVerificacionesNuevas = $stmt->fetchAll();

      $totalNotificaciones = $nTareasNuevos + $nChatsNuevos + $nSubTareasNuevos + $nVerificacionesNuevas;

      $arrayGeneral = array_merge($arrayTareasNuevas, $arrayChatsNuevos,$arraySubTareasNuevas,$arrayVerificacionesNuevas);

      function cmp($a, $b)
      {
        return strcmp($b["FechaCreacion"], $a["FechaCreacion"]);
      }

      usort($arrayGeneral, "cmp");

      if($totalNotificaciones >= 0){
        //$falso = 100;
        $dig = strlen($totalNotificaciones);
        //$dig = strlen($falso);
        switch($dig){
          case 1:
            $left = 0;
          break;
          case 2:
            $left = -4;
          break;
          case 3:
            $left = -8;
          break;
        }

    ?>
      <span id="contadorTareas" class="badge badge-pill badge-counter badge-circle"><p style="/*border:2px solid white;*/margin: 0;padding:0;position:relative;left:<?=$left;?>px;"><?=$totalNotificaciones;?></p></span>
    <?php } ?>
  </a>

<!-- Dropdown - Alerts -->
<div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
  <?php
    $mensaje = "";
    $html = "<h3 class='dropdown-header' style='background:#006dd9;color:white;text-transform: none !important;letter-spacing: 2px;font-size:14px;'>Notificaciones</h3>";

    /*
      Inicio Nuevo código
    */

    if($totalNotificaciones > 0){
      $contador = 0;
      foreach ($arrayGeneral as $r) {
        if($contador < 5){
          switch($r['FKTipoNotificacion']){
            case 1:
              $fecha = date('d/m/Y',strtotime($r['FechaCreacion']));
              $idNoti = $r['PKTareaNotificacion'];
              $tarea = $r['Tarea'];
              $proyecto = $r['PKProyecto'];
              $mensaje = "Se te asignó la tarea ".$tarea;
              $html .= '
                <a class="dropdown-item d-flex align-items-center notification tasks-noti" href="#" data-id="'.$idNoti.'" data-project="'.$proyecto.'">
                  <div class="mr-3">
                    <div class="icon-circle">
                      <img src="../'.$rutes.'img/notificaciones/ICONO TAREAS_Mesa de trabajo 1.svg" width="25px">
                    </div>
                  </div>
                  <div id="notification-latest">
                        <div id="fechaTarea" class="date-notification">'.$fecha.'</div>
                        <span id="tarea" class="font-weight-bold">'.$mensaje.'</span>
                  </div>
                </a>
              ';
            break;
            case 2:
              $fecha = date('d/m/Y',strtotime($r['FechaCreacion']));
              $idNoti = $r['PKChat_Notificaciones'];
              $tarea = $r['Tarea'];
              $idTarea =$r['PKTarea'];
              $proyecto = $r['PKProyecto'];
              $mensaje = "Tienes una nueva conversación en la tarea ".$tarea;
              $html .= '
                <a class="dropdown-item d-flex align-items-center notification chats-noti" href="#" data-id1="'.$idNoti.'" data-project="'.$proyecto.'" data-task="'.$idTarea.'">
                  <div class="mr-3">
                    <div class="icon-circle">
                      <img src="../'.$rutes.'img/notificaciones/ICONO CHAT_Mesa de trabajo 1.svg" width="25px">
                    </div>
                  </div>
                  <div id="notification-latest">
                        <div id="fechaTarea" class="date-notification">'.$fecha.'</div>
                        <span id="tarea" class="font-weight-bold">'.$mensaje.'</span>
                  </div>
                </a>
              ';
            break;
            case 3:
              $fecha = date('d/m/Y',strtotime($r['FechaCreacion']));
              $subTarea = $r['SubTarea'];
              $tarea = $r['Tarea'];
              $idTarea = $r['PKTarea'];
              $idNoti = $r['PKSubTareaNotificacion'];
              $proyecto = $r['PKProyecto'];
              $mensaje = "Se te asignó la subtarea ".$subTarea." de la tarea ".$tarea;
              $html .= '
                <a class="dropdown-item d-flex align-items-center notification tasks-noti" href="#" data-id="'.$idNoti.'" data-project="'.$proyecto.'">
                  <div class="mr-3">
                    <div class="icon-circle">
                      <img src="../'.$rutes.'img/notificaciones/ICONO SUBTAREAS_azul-01.svg" width="25px">
                    </div>
                  </div>
                  <div id="notification-latest">
                        <div id="fechaTarea" class="date-notification">'.$fecha.'</div>
                        <span id="tarea" class="font-weight-bold">'.$mensaje.'</span>
                  </div>
                </a>
              ';
            break;
            case 4:
              $fecha = date('d/m/Y',strtotime($r['FechaCreacion']));
              $verificacion = $r['PKVerificacionNotificacion'];
              $tarea = $r['Tarea'];
              $idTarea = $r['PKTarea'];
              $idNoti = $r['PKVerificacionNotificacion'];
              $proyecto = $r['PKProyecto'];
              $mensaje = "Se verificó la tarea ".$tarea;
              $html .= '
                <a class="dropdown-item d-flex align-items-center notification checks" href="#" data-id="'.$idNoti.'" data-project="'.$proyecto.'">
                  <div class="mr-3">
                    <div class="icon-circle">
                      <img src="../'.$rutes.'img/notificaciones/ICONO CHECK_AZUL-01.svg" width="25px">
                    </div>
                  </div>
                  <div id="notification-latest">
                        <div id="fechaTarea" class="date-notification">'.$fecha.'</div>
                        <span id="tarea" class="font-weight-bold">'.$mensaje.'</span>
                  </div>
                </a>
              ';
            break;
          }


        }

        $contador++;
      }

      /*
        Fin nuevo código
      */
      /*
      if($nTareasNuevos > 0){
        $contadorTareas = 0;
        foreach($arrayTareasNuevas as $r){
          if($contadorTareas < 2){
            $fecha = date('d/m/Y',strtotime($r['FechaCreacion']));
            $idNoti = $r['PKTareaNotificacion'];
            $tarea = $r['Tarea'];
            $proyecto = $r['PKProyecto'];
            $mensaje = "Se te asignó la tarea ".$tarea;
            $html .= '

              <a class="dropdown-item d-flex align-items-center notification tasks" href="#" data-id="'.$idNoti.'" data-project="'.$proyecto.'">
                <div class="mr-3">
                  <div class="icon-circle" >
                    <img src="../'.$rutes.'img/notificaciones/ICONO TAREAS_Mesa de trabajo 1.svg" width="25px">
                  </div>
                </div>
                <div id="notification-latest">
                      <div id="fechaTarea" class="date-notification">'.$fecha.'</div>
                      <span id="tarea" class="font-weight-bold">'.$mensaje.'</span>
                </div>
              </a>
            ';
          }
          $contadorTareas++;
        }
      }*/
      /*
    if($arrayChatsNuevos > 0){
      $contadorChats = 0;
      foreach($arrayChatsNuevos as $r){
        if($contadorChats < 2){
          $fecha = date('d/m/Y',strtotime($r['FechaCreacion']));
          $idNoti = $r['PKChat_Notificaciones'];
          $tarea = $r['Tarea'];
          $idTarea =$r['PKTarea'];
          $proyecto = $r['PKProyecto'];
          $mensaje = "Tienes una nueva conversación en la tarea ".$tarea;
          $html .= '
            <a class="dropdown-item d-flex align-items-center notification chats" href="#" data-id1="'.$idNoti.'" data-project="'.$proyecto.'" data-task="'.$idTarea.'">
              <div class="mr-3">
                <div class="icon-circle">
                  <img src="../'.$rutes.'img/notificaciones/ICONO CHAT_Mesa de trabajo 1.svg" width="25px">
                </div>
              </div>
              <div id="notification-latest">
                    <div id="fechaTarea" class="date-notification">'.$fecha.'</div>
                    <span id="tarea" class="font-weight-bold">'.$mensaje.'</span>
              </div>
            </a>
          ';
        }
        $contadorChats++;
      }
    }*/
    /*
    if($nSubTareasNuevos > 0){
      $contadorSubt = 0;
      foreach ($arraySubTareasNuevas as $r) {
        if($contadorSubt < 2){
          $fecha = date('d/m/Y',strtotime($r['FechaCreacion']));
          $subTarea = $r['SubTarea'];
          $tarea = $r['Tarea'];
          $idTarea = $r['PKTarea'];
          $idNoti = $r['PKSubTareaNotificacion'];
          $proyecto = $r['PKProyecto'];
          $mensaje = "Se te asignó la subtarea ".$subTarea." de la tarea ".$tarea;
          $html .= '
            <a class="dropdown-item d-flex align-items-center notification subtasks" href="#" data-id="'.$idNoti.'" data-project="'.$proyecto.'">
              <div class="mr-3">
                <div class="icon-circle">
                  <img src="../'.$rutes.'img/notificaciones/ICONO SUBTAREAS_azul-01.svg" width="25px">
                </div>
              </div>
              <div id="notification-latest">
                    <div id="fechaTarea" class="date-notification">'.$fecha.'</div>
                    <span id="tarea" class="font-weight-bold">'.$mensaje.'</span>
              </div>
            </a>
          ';
        }
        $contadorSubt++;
      }
    }*/
    /*
    if($nVerificacionesNuevas > 0){
      $contadorVeri = 0;
      foreach($arrayVerificacionesNuevas AS $r){
        if($contadorVeri < 1){
          $fecha = date('d/m/Y',strtotime($r['FechaCreacion']));
          $verificacion = $r['PKVerificacion'];
          $tarea = $r['Tarea'];
          $idTarea = $r['PKTarea'];
          $idNoti = $r['PKVerificacionNotificacion'];
          $proyecto = $r['PKProyecto'];
          $mensaje = "Se verificó la tarea ".$tarea;
          $html .= '
            <a class="dropdown-item d-flex align-items-center notification checks" href="#" data-id="'.$idNoti.'" data-project="'.$proyecto.'">
              <div class="mr-3">
                <div class="icon-circle">
                  <img src="../'.$rutes.'img/notificaciones/ICONO CHECK_AZUL-01.svg" width="25px">
                </div>
              </div>
              <div id="notification-latest">
                    <div id="fechaTarea" class="date-notification">'.$fecha.'</div>
                    <span id="tarea" class="font-weight-bold">'.$mensaje.'</span>
              </div>
            </a>
          ';
        }
        $contadorVeri++;
      }
    }
    */
  }else{
    $html .= '
      <a class="dropdown-item d-flex align-items-center notification" href="#">
        <div class="mr-3">
          <div class="icon-circle bg-white">
            <img src="../'.$rutes.'img/notificaciones/ICONO ALERTA_Mesa de trabajo 1.svg" width="25px">
          </div>
        </div>
        <div id="notification-latest">
          <span id="tarea" class="font-weight-bold">No hay notificaciones nuevas </span>
        </div>
      </a>
    ';
  }
  echo $html;
  ?>
  <div class="show-center-noti">
    <a href="<?=$rutes; ?>central_notificaciones/">Ir al centro de notificaciones</a>
  </div>
</div>
</li>
<script>
    $(document).on('click','.chats-noti',function(){
      var idData = $(this).data("id1");
      var data = "table=chats&id="+idData;
      var idProject = $(this).data("project");
      var idTask = $(this).data("task");
      //console.log("idTarea: "+idTask+"\nidProyecto: "+idProject+"\nData: "+data+"\nidData: "+idData);
      $.ajax({
        method: "POST",
        data: data,
        url: '<?=$edit;?>functions/edit_notification.php',
        success:function(){
          window.location.href = "<?=$rutes;?>tareas/timDesk/index.php?id="+idProject+"&idTarea="+idTask+"";
          console.log("tareas/timDesk/index.php?id="+idProject+"&idTarea="+idTask+"");
        }
      });
    });

    $(document).on('click','.tasks-noti',function(){
      var idData = $(this).data("id");
      var data = "table=tasks&id="+idData;
      var idProject = $(this).data("project");
      //console.log("idProyecto: "+idProject+"\nData: "+data+"\nidData: "+idData);
      $.ajax({
        method: "POST",
        data: data,
        url: '<?=$edit;?>functions/edit_notification.php',
        success:function(){
          window.location.href = "<?=$rutes;?>tareas/timDesk/index.php?id="+idProject+"";
          console.log("tareas/timDesk/index.php?id="+idProject+"");
        }
      });
    });

    $(document).on('click','.tasks-noti',function(){
      var idData = $(this).data("id");
      var data = "table=subtasks&id="+idData;
      var idProject = $(this).data("project");
      console.log("idProyecto: "+idProject+"\nData: "+data+"\nidData: "+idData);
      $.ajax({
        method: "POST",
        data: data,
        url: '<?=$edit;?>functions/edit_notification.php',
        success:function(data){
          window.location.href = "<?=$rutes;?>tareas/timDesk/index.php?id="+idProject+"";
          console.log("tareas/timDesk/index.php?id="+idProject+"");
          console.log(data);
        }
      });
    });

    $(document).on('click','.checks',function(){
      var idData = $(this).data("id");
      var data = "table=subtasks&id="+idData;
      var idProject = $(this).data("project");
      console.log('Usuario conectado: <?=$usuario; ?>');
      //console.log("idProyecto: "+idProject+"\nData: "+data+"\nidData: "+idData);
      $.ajax({
        method: "POST",
        data: data,
        url: '<?=$edit;?>functions/edit_notification.php',
        success:function(data){
          window.location.href = "<?=$rutes;?>tareas/timDesk/index.php?id="+idProject+"";
          console.log("tareas/timDesk/index.php?id="+idProject+"");
          console.log(data);

        }
      });
    });

</script>

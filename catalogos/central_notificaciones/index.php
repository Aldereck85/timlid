<?php
  session_start();

  if(isset($_SESSION["Usuario"])){
    $user = $_SESSION["PKUsuario"];
    date_default_timezone_set('America/Mexico_City');
    require_once('../../include/db-conn.php');
    $auxHoy = getdate();
    $hoy = $auxHoy['year']."-".$auxHoy['mon']."-".$auxHoy['mday'];
    $hora = $auxHoy['hours'].":".$auxHoy['minutes'].":".($auxHoy['seconds']);
    $horaLimite = "23:59:59";
    $fechaHora = $hoy." ".$hora;
    $fechaHoraLimite = $hoy." ".$horaLimite;
    $nChatsNuevos = 0;
    $nTareasNuevas = 0;
    $nSubtareasNuevas = 0;
    $nChatsAntiguos = 0;
    $nTareasAntiguas = 0;
    $nSubtareasAntiguas = 0;
    $nVerificacionesNuevas = 0;
    $nVerificacionesAntiguas = 0;
    echo '<script>
      console.log("usuario conectado: '.$user.'");
    </script>';
    $stmt = $conn->prepare('SELECT chatNoti.Visto, task.Tarea, chatNoti.FKTipoNotificacion, chatNoti.FechaCreacion, project.Proyecto, typever.Software,project.PKProyecto,task.PKTarea,
                            CONCAT(employer.Primer_Nombre," ", employer.Apellido_Paterno) as nombre_usuario
                            FROM chat_notificaciones AS chatNoti
                            LEFT JOIN tipo_notificacion AS typever ON chatNoti.FKTipoNotificacion = typever.PKTipoNotificacion
                            LEFT JOIN usuarios AS user ON chatNoti.FKUsuarioMencion = user.PKUsuario
                            LEFT JOIN empleados AS employer ON user.FKEmpleado = employer.PKEmpleado
                            LEFT JOIN chat AS ch ON chatNoti.FKChat = ch.PKChat
                            LEFT JOIN tareas AS task ON ch.FKTarea = task.PKTarea
                            LEFT JOIN proyectos AS project ON task.FKProyecto = project.PKProyecto
                            WHERE chatNoti.FKUsuario = :id');
    $stmt->execute(array(':id'=>$user));
    $arrayChatsNuevos = $stmt->fetchAll();

    foreach($arrayChatsNuevos as $r){
      $horaGeneral = new DateTime($r['FechaCreacion']);
      $auxDay = new DateTime($fechaHora);
      $diferencia = $horaGeneral->diff($auxDay);
      $diasDif = $diferencia->format('%a');
      //$diasDif = intval(date("d",strtotime("0000-00-00 00:00:00")+strtotime($fechaHora)-strtotime($horaGeneral)));
      if($diasDif == 0){
        $nChatsNuevos++;
      }else{
        $nChatsAntiguos++;
      }
      echo '
      <script>
        console.log("días transcurridos chats: '.$diasDif.'");
      </script>
      ';
    }

    $stmt = $conn->prepare('SELECT taskNoti.Visto, task.Tarea, taskNoti.FKTipoNotificacion, taskNoti.FechaCreacion, project.Proyecto, typever.Software,project.PKProyecto,task.PKTarea,
                            CONCAT(employer.Primer_Nombre," ", employer.Apellido_Paterno) as nombre_usuario
                            FROM tarea_notificaciones AS taskNoti
                            LEFT JOIN tipo_notificacion AS typever ON taskNoti.FKTipoNotificacion = typever.PKTipoNotificacion
                            LEFT JOIN usuarios AS user ON taskNoti.FKResponsableTarea = user.PKUsuario
                            LEFT JOIN empleados AS employer ON user.FKEmpleado = employer.PKEmpleado
                            LEFT JOIN tareas AS task ON taskNoti.FKTarea = task.PKTarea
                            LEFT JOIN proyectos AS project ON task.FKProyecto = project.PKProyecto
                            WHERE taskNoti.FKResponsableTarea = :id');
    $stmt->execute(array(':id'=>$user));
    $arrayTareasNuevas = $stmt->fetchAll();

    foreach($arrayTareasNuevas as $r){
      $horaGeneral = new DateTime($r['FechaCreacion']);
      $auxDay = new DateTime($fechaHora);
      $diferencia = $horaGeneral->diff($auxDay);
      $diasDif = $diferencia->format('%a');
      //$diasDif = intval(date("Y-m-d H:i:s",strtotime("0000-00-00 00:00:00")+strtotime($fechaHora)-strtotime($horaGeneral)));
      if($diasDif == 0){
        $nTareasNuevas++;
      }else{
        $nTareasAntiguas++;
      }
      echo '
      <script>
        console.log("días transcurridos tareas: '.$diasDif.'");
      </script>
      ';
    }

    $stmt = $conn->prepare('SELECT subTaskNoti.Visto, subtask.Subtarea, subTaskNoti.FKTipoNotificacion, subTaskNoti.FechaCreacion, project.Proyecto, task.Tarea, typever.Software,project.PKProyecto,task.PKTarea,
                            CONCAT(employer.Primer_Nombre," ", employer.Apellido_Paterno) as nombre_usuario
                            FROM subtarea_notificaciones AS subTaskNoti
                            LEFT JOIN tipo_notificacion AS typever ON subTaskNoti.FKTipoNotificacion = typever.PKTipoNotificacion
                            LEFT JOIN usuarios AS user ON subTaskNoti.FKResponsableSubTarea = user.PKUsuario
                            LEFT JOIN empleados AS employer ON user.FKEmpleado = employer.PKEmpleado
                            LEFT JOIN subtareas AS subtask ON subTaskNoti.FKSubTarea = subtask.PKSubTarea
                            LEFT JOIN tareas AS task ON subtask.FKTarea = task.PKTarea
                            LEFT JOIN proyectos AS project ON task.FKProyecto = project.PKProyecto
                            WHERE subTaskNoti.FKResponsableSubTarea = :id');
    $stmt->execute(array(':id'=>$user));
    $arraySubTareasNuevas = $stmt->fetchAll();

    foreach($arraySubTareasNuevas as $r){
      $horaGeneral = $r['FechaCreacion'];
      $horaGeneral = new DateTime($r['FechaCreacion']);
      $auxDay = new DateTime($fechaHora);
      $diferencia = $horaGeneral->diff($auxDay);
      $diasDif = $diferencia->format('%a');
      //$diasDif = intval(date("d",strtotime("0000-00-00 00:00:00")+strtotime($fechaHora)-strtotime($horaGeneral)));
      if($diasDif == 0){
        $nSubtareasNuevas++;
      }else{
        $nSubtareasAntiguas++;
      }
      echo '
      <script>
        console.log("días transcurridos subtareas: '.$diasDif.'");
      </script>
      ';
    }

    $stmt = $conn->prepare('SELECT checkNoti.Visto, task.Tarea, checkNoti.FKTipoNotificacion, checkNoti.FechaCreacion,project.Proyecto, typever.Software,project.PKProyecto,task.PKTarea,
                            CONCAT(employer.Primer_Nombre," ", employer.Apellido_Paterno) as nombre_usuario
                            FROM verificacion_notificaciones AS checkNoti
                            LEFT JOIN tipo_notificacion AS typever ON checkNoti.FKTipoNotificacion = typever.PKTipoNotificacion
                            LEFT JOIN usuarios AS user ON checkNoti.FKResponsableTarea = user.PKUsuario
                            LEFT JOIN empleados AS employer ON user.FKEmpleado = employer.PKEmpleado
                            LEFT JOIN tareas AS task ON checkNoti.FKTarea = task.PKTarea
                            LEFT JOIN proyectos AS project ON task.FKProyecto = project.PKProyecto
                            WHERE checkNoti.FKResponsableTarea = :id');
    $stmt->execute(array(':id'=>$user));
    $arrayVerificacionesNuevas = $stmt->fetchAll();

    foreach($arrayVerificacionesNuevas as $r){
      $horaGeneral = $r['FechaCreacion'];
      $horaGeneral = new DateTime($r['FechaCreacion']);
      $auxDay = new DateTime($fechaHora);
      $diferencia = $horaGeneral->diff($auxDay);
      $diasDif = $diferencia->format('%a');
      //$diasDif = intval(date("d",strtotime("0000-00-00 00:00:00")+strtotime($fechaHora)-strtotime($horaGeneral)));
      if($diasDif == 0){
        $nVerificacionesNuevas++;
      }else{
        $nVerificacionesAntiguas++;
      }

      echo '
      <script>
        console.log("días transcurridos subtareas: '.$diasDif.'");
      </script>
      ';
    }

    $totalNotificacionesNuevas = $nChatsNuevos + $nTareasNuevas + $nSubtareasNuevas + $nVerificacionesNuevas;
    $totalNotificacionesAntiguas = $nChatsAntiguos + $nTareasAntiguas + $nSubtareasAntiguas + $nVerificacionesAntiguas;
    $arrayGeneral = array_merge($arrayTareasNuevas, $arrayChatsNuevos,$arraySubTareasNuevas,$arrayVerificacionesNuevas);

    function cmp($a, $b)
    {
      return strcmp($b["FechaCreacion"], $a["FechaCreacion"]);
    }

    usort($arrayGeneral, "cmp");

    $contador = 0;

  }else{
    header("location:../dashboard.php");
  }
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Timlid | Central de notificaciones</title>

    <!-- Bootstrap core JavaScript-->
    <script src="../../vendor/jquery/jquery.min.js"></script>
    <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha256-c4gVE6fn+JRKMRvqjoDp+tlG4laudNYrXI1GncbfAYY=" crossorigin="anonymous"></script>

    <!-- Page level plugins -->
    <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../../vendor/datatables/dataTables.responsive.js"></script>
    <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Custom fonts for this template -->
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" integrity="sha256-EH/CzgoJbNED+gZgymswsIOrM9XhIbdSJ6Hwro09WE4=" crossorigin="anonymous" />
    <link href="../../css/chosen.css" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template -->
    <link href="../../css/sb-admin-2.css" rel="stylesheet">

    <link rel="stylesheet" href="../../css/notifications_center.css">
    <link rel="stylesheet" href="../../css/notificaciones.css">

    <!-- Custom styles for this page -->
    <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
    <link href="../tareas/timDesk/style/css.css" rel="stylesheet" type="text/css">
    <link href="../../css/slimselect.min.css" rel="stylesheet">
    <script src="../../js/slimselect.min.js"></script>
  </head>

  <body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">

      <!-- Sidebar -->
        <?php
          $ruta = "../";
          $ruteEdit = "";
          require_once('../menu3.php');
          $rutes = "../";
        ?>
      <!-- End of Sidebar -->

      <!-- Content Wrapper -->
      <div id="content-wrapper" class="d-flex flex-column">
        <!-- Main Content -->
        <div id="content">
          <?php
            $rutatb = "../";
            $titulo = '<div class="header-screen">
                          <div class="header-title-screen">
                            <h1 class="h3 mb-2"><img src="../../img/notificaciones/ICONO ALERTAS_Mesa de trabajo 1.svg" alt=""> Centro de notificaciones</h1>
                          </div>
                        </div>
            ';
            /*$titulo = '<div class="header-screen">
              <div class="header-title-screen">
                <h1 class="h3 mb-2"><img src="../../img/scrum/scrum_mesa1.svg" alt=""> Scrum</h1>
              </div>
              <div class="vl"></div>
              <div class="logo-views">
                <div class="logo-views-title">
                  <h1 class="h3 mb-2"><img src="../../img/scrum/vistas1.svg" alt="">  Vistas</h1>
                </div>
                <div style="margin-left:-4px;">
                  <div class="logo-views-menus">
                    <a href="#" id="goTimDesk">Timdesk</a>
                    <a href="#" id="goCalendar">Calendario</a>
                  </div>
                </div>
              </div>
            </div>';*/
            require_once('../topbar.php');
          ?>
          <!-- Begin Page Content -->
          <div class="container-fluid">
            <div id="recent-notifications">
              <div class="counter-recent-notification">
                <h1 class="h6 mb-2"><img src="../../img/notificaciones/ICONO ALERTAS_Mesa de trabajo 1.svg" alt=""> <?=$totalNotificacionesNuevas; ?> recientes</h1>
              </div>
              <div class="select-notification">
                <select class="form-control" name="cmbShowNotification" id="cmbShowNotification">
                  <option value="1" selected>Todo</option>
                  <option value="2">TimDesk</option>
                  <option value="3">ERP</option>
                </select>
              </div>
              <div id="carouselExampleControls" class="carousel slide carousel-multi-item" data-ride="carousel">
                <div class="carousel-inner" role="listbox">
                  <div class="carousel-item active">
                    <!-- Cards row -->
                    <div class="row">
                      <?php
                        $counter = 0;
                        if($totalNotificacionesNuevas > 0){
                          for ($i=0;$i<count($arrayGeneral);$i++) {

                            switch ($arrayGeneral[$i]['FKTipoNotificacion']){
                              case 1:
                                $icon = '<img src="../../img/notificaciones/ICONO TAREAS_Mesa de trabajo 1.svg" alt="">';
                                $message = '<a href="'.$rutes.'tareas/timDesk/index.php?id='.$arrayGeneral[$i]['PKProyecto'].'"><p>Agregó la  tarea '.$arrayGeneral[$i]['Tarea'].' en el proyecto '.$r['Proyecto'].'</p></a>';
                              break;
                              case 2:
                                $icon = '<img src="../../img/notificaciones/ICONO CHAT_Mesa de trabajo 1.svg" alt="">';
                                $message = '<a href="'.$rutes.'tareas/timDesk/index.php?id='.$arrayGeneral[$i]['PKProyecto'].'"><p>Te envió un mensaje desde la tarea '.$arrayGeneral[$i]['Tarea'].'</p></a>';
                              break;
                              case 3:
                                $icon = '<img src="../../img/notificaciones/ICONO SUBTAREAS_azul-01.svg" alt="">';
                                $message = '<a href="'.$rutes.'tareas/timDesk/index.php?id='.$arrayGeneral[$i]['PKProyecto'].'"><p>Agregó la subtarea '.$arrayGeneral[$i]['Subtarea'].' en la tarea Centro de notificaciones en el proyecto Timlid</p></a>';
                              break;
                              case 4:
                                $icon = '<img src="../../img/notificaciones/ICONO CHECK_AZUL-01.svg" alt="">';
                                $message = '<a href="'.$rutes.'tareas/timDesk/index.php?id='.$arrayGeneral[$i]['PKProyecto'].'"><p>Verificó la tarea '.$arrayGeneral[$i]['Tarea'].' asignada a tí</p></a>';
                              break;
                            }
                            switch($arrayGeneral[$i]['Software']){
                              case '1':
                                $iconSoft ='<img src="../../img/notificaciones/ICONO TIMDESK_Mesa de trabajo 1.svg" alt="">';
                              break;
                              case '2':
                                $iconSoft ='<img src="../../img/notificaciones/T TIMLID.svg" alt="">';
                              break;
                            }

                            if(count($arrayGeneral) > 0){
                              $horaGeneral = $arrayGeneral[$i]['FechaCreacion'];

                              $horaGeneral1 = new DateTime($arrayGeneral[$i]['FechaCreacion']);
                              $auxDay = new DateTime($fechaHora);
                              $diferencia = $horaGeneral1->diff($auxDay);
                              $diasDif = $diferencia->format('%a');
                              //$diasDif = intval(date("d",strtotime("0000-00-00 00:00:00")+strtotime($fechaHora)-strtotime($horaGeneral)));
                              $horasDif = intval(date("H",strtotime("0000-00-00 00:00:00")+strtotime($fechaHora)-strtotime($horaGeneral)));
                              $minutosDif = intval(date("i",strtotime("0000-00-00 00:00:00")+strtotime($fechaHora)-strtotime($horaGeneral)));
                              $segundosDif = intval(date("s",strtotime("0000-00-00 00:00:00")+strtotime($fechaHora)-strtotime($horaGeneral)));

                              echo '
                              <script>
                                console.log("tareas nuevas días: '.$diasDif.'");
                              </script>
                              ';
                              //$diasDif = intval($diasDif);
                              //$horasDif = intval($horasDif);
                              //$minutosDif = intval($minutosDif);
                              //$segundosDif = intval($segundosDif);

                              if($diasDif > 0){
                                if($diasDif == 1){
                                  $tiempoTrans = "hace ".$diasDif." día";
                                }else{
                                  $tiempoTrans = "hace ".$diasDif." días";
                                }
                              }else if($horasDif > 0){
                                if($horasDif == 1){
                                  $tiempoTrans = "hace ".$horasDif." hora";
                                }else{
                                  $tiempoTrans = "hace ".$horasDif." horas";
                                }
                              }else if($minutosDif > 0){
                                if($minutosDif == 1){
                                  $tiempoTrans = "hace ".$minutosDif." minuto";
                                }else{
                                  $tiempoTrans = "hace ".$minutosDif." minutos";
                                }
                              }else if($segundosDif > 0){
                                if($segundosDif == 1){
                                  $tiempoTrans = "hace ".$segundosDif." segundo";
                                }else{
                                  $tiempoTrans = "hace ".$segundosDif." segundos";
                                }
                              }
                            }
                            if($diasDif == 0){
                              $color = "#51CBD1";
                            }
                            if($diasDif == 0 && $i < 3){
                              echo '
                              <script>
                                console.log("Diff dias: '.$diasDif.'");
                                console.log("array: '.count($arrayGeneral).'");
                                console.log("valor de $i: '.$i.'");
                              </script>
                              ';
                            ?>
                            <!-- Cards col1 -->
                            <div class="col-lg-4 col-md-4 card-total" style="margin-left: 8px;margin-right: -15px;" id="card-<?=$arrayGeneral[$i]['Software']; ?>">
                              <div class="card-notification card mb-2" >

                                <div class="card-body">
                                  <div class="color-notification" style="background-color:<?=$color; ?>"></div>
                                  <div class="row">

                                    <div class="user-notification">
                                      <img src="../../img/notificaciones/ICONO PROYECTO SIN LIDER_Mesa de trabajo 1.svg" alt="">
                                      <div class="name-user-notification">
                                        <p><?=$arrayGeneral[$i]['nombre_usuario']; ?></p>
                                      </div>
                                      <hr class="divisor-user-notification">
                                    </div>
                                    <div class="notification-message-recent">
                                      <?=$message; ?>
                                    </div>
                                  </div>
                                  <div class="icon-software-footer">
                                    <?=$iconSoft; ?>
                                  </div>
                                  <div class="icon-card-footer">
                                    <?=$icon; ?>
                                  </div>
                                </div>
                              </div>
                              <div class="time-elapsed">
                                <p><?=$tiempoTrans; ?></p>
                              </div>
                            </div>

                      <?php


                          }
                          echo '<script>console.log("IdProyecto: '.$arrayGeneral[$i]['PKProyecto'].'")</script>';
                          $counter++;
                        }//termina el for
                      }else{
                        echo '<div class="no-new-notification">
                                <h4>No hay notificaciones recientes</h4>
                              </div>';
                      }
                    ?>

                    </div>
                  </div>
                  <?php
                    $n = 0;
                    for ($i=0; $i < count($arrayGeneral); $i++) {
                      $horaGeneral = $arrayGeneral[$i]['FechaCreacion'];

                      $horaGeneral1 = new DateTime($arrayGeneral[$i]['FechaCreacion']);
                      $auxDay = new DateTime($fechaHora);
                      $diferencia = $horaGeneral1->diff($auxDay);
                      $diasDif = $diferencia->format('%a');
                      echo '
                      <script>
                        console.log("Valor de $diasDif: '.$diasDif.'");
                      </script>
                      ';
                      if($diasDif == 0){
                        $n++;
                      }
                    }
                    echo '
                    <script>
                      console.log("Valor de $n: '.$n.'");
                    </script>
                    ';
                    if($n > 3){
                  ?>
                      <?php
                        $contador = 0;
                        $icon ="";
                        $x = 0;
                        $itemHeader = '
                                <div class="carousel-item">
                                  <div class="row">
                                ';
                        $itemFooter = '
                                          </div>
                                        </div>
                                      ';
                        for ($i=3; $i < count($arrayGeneral); $i++) {
                          switch ($arrayGeneral[$i]['FKTipoNotificacion']){
                            case 1:
                              $icon = '<img src="../../img/notificaciones/ICONO TAREAS_Mesa de trabajo 1.svg" alt="">';
                              $message = '<a href="'.$rutes.'tareas/timDesk/index.php?id='.$arrayGeneral[$i]['PKProyecto'].'"><p>Agregó la  tarea '.$arrayGeneral[$i]['Tarea'].' en el proyecto '.$arrayGeneral[$i]['Proyecto'].'</p></a>';
                            break;
                            case 2:
                              $icon = '<img src="../../img/notificaciones/ICONO CHAT_Mesa de trabajo 1.svg" alt="">';
                              $message = '<a href="'.$rutes.'tareas/timDesk/index.php?id='.$arrayGeneral[$i]['PKProyecto'].'"><p>Te envió un mensaje desde la tarea '.$arrayGeneral[$i]['Tarea'].'</p></a>';
                            break;
                            case 3:
                              $icon = '<img src="../../img/notificaciones/ICONO SUBTAREAS_azul-01.svg" alt="">';
                              $message = '<a href="'.$rutes.'tareas/timDesk/index.php?id='.$arrayGeneral[$i]['PKProyecto'].'"><p>Agregó la subtarea '.$arrayGeneral[$i]['Subtarea'].' en la tarea Centro de notificaciones en el proyecto Timlid</p></a>';
                            break;
                            case 4:
                              $icon = '<img src="../../img/notificaciones/ICONO CHECK_AZUL-01.svg" alt="">';
                              $message = '<a href="'.$rutes.'tareas/timDesk/index.php?id='.$arrayGeneral[$i]['PKProyecto'].'"><p>Verificó la tarea '.$arrayGeneral[$i]['Tarea'].' del proyecto '.$arrayGeneral[$i]['Proyecto'].'</p></a>';
                            break;

                          }
                          switch($arrayGeneral[$i]['Software']){
                            case '1':
                              $iconSoft ='<img src="../../img/notificaciones/ICONO TIMDESK_Mesa de trabajo 1.svg" alt="">';
                            break;
                            case '2':
                              $iconSoft ='<img src="../../img/notificaciones/T TIMLID.svg" alt="">';
                            break;
                          }

                          if(count($r) > 0){
                            $horaGeneral = $arrayGeneral[$i]['FechaCreacion'];

                            $horaGeneral1 = new DateTime($arrayGeneral[$i]['FechaCreacion']);
                            $auxDay = new DateTime($fechaHora);
                            $diferencia = $horaGeneral1->diff($auxDay);
                            $diasDif = $diferencia->format('%a');
                            //$diasDif = intval(date("d",strtotime("0000-00-00 00:00:00")+strtotime($fechaHora)-strtotime($horaGeneral)));
                            $horasDif = intval(date("H",strtotime("0000-00-00 00:00:00")+strtotime($fechaHora)-strtotime($horaGeneral)));
                            $minutosDif = date("i",strtotime("0000-00-00 00:00:00")+strtotime($fechaHora)-strtotime($horaGeneral));
                            $segundosDif = date("s",strtotime("0000-00-00 00:00:00")+strtotime($fechaHora)-strtotime($horaGeneral));

                            echo '
                            <script>
                              console.log("tareas nuevas extras días: '.$diasDif.'");
                            </script>
                            ';
                            //$diasDif = intval($diasDif);
                            //$horasDif = intval($horasDif);
                            //$minutosDif = intval($minutosDif);
                            //$segundosDif = intval($segundosDif);

                            if($diasDif > 0){
                              if($diasDif == 1){
                                $tiempoTrans = "hace ".$diasDif." día";
                              }else{
                                $tiempoTrans = "hace ".$diasDif." días";
                              }
                            }else if($horasDif > 0){
                              if($horasDif == 1){
                                $tiempoTrans = "hace ".$horasDif." hora";
                              }else{
                                $tiempoTrans = "hace ".$horasDif." horas";
                              }
                            }else if($minutosDif > 0){
                              if($minutosDif == 1){
                                $tiempoTrans = "hace ".$minutosDif." minuto";
                              }else{
                                $tiempoTrans = "hace ".$minutosDif." minutos";
                              }
                            }else if($segundosDif > 0){
                              if($segundosDif == 1){
                                $tiempoTrans = "hace ".$segundosDif." segundo";
                              }else{
                                $tiempoTrans = "hace ".$segundosDif." segundos";
                              }
                            }
                          }

                          if($diasDif == 0){
                            if($contador == 0){
                              echo $itemHeader;
                            }
                            if($contador < 3){

                      ?>
                      <div class="col-lg-4 col-md-4 card-total" style="margin-left: 8px;margin-right: -15px;" id="card-<?=$arrayGeneral[$i]['Software']; ?>">
                        <div class="card-notification card mb-2">

                          <div class="card-body">
                            <div class="color-notification" style="background-color:<?=$color; ?>"></div>
                            <div class="row">

                              <div class="user-notification">
                                <img src="../../img/notificaciones/ICONO PROYECTO SIN LIDER_Mesa de trabajo 1.svg" alt="">
                                <div class="name-user-notification">
                                  <p><?=$arrayGeneral[$i]['nombre_usuario']; ?></p>
                                </div>
                                <hr class="divisor-user-notification">
                              </div>
                              <div class="notification-message-recent">
                                <?=$message; ?>
                              </div>
                            </div>
                            <div class="icon-software-footer">
                              <?=$iconSoft; ?>
                            </div>
                            <div class="icon-card-footer">
                              <?=$icon; ?>
                            </div>
                          </div>
                        </div>
                        <div class="time-elapsed">
                          <p><?=$tiempoTrans; ?></p>
                        </div>
                      </div>
                      <?php

                      $contador++;
                      /*if($contador == 1){
                        echo $itemFooter;
                      }*/

                    }else{
                      $contador = 0;
                    }


                  }
                  $x++;
                  echo '
                  <script>
                    console.log("Valor de x: '.$x.'");
                  </script>
                  ';

                }//fin del for
                echo $itemFooter;
                      ?>
                <?php } ?>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                  <span class="prev" aria-hidden="true"><i class="fas fa-chevron-left fa-2x"></i></span>
                  <span class="sr-only">Anterior</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                  <span class="next" aria-hidden="true"><i class="fas fa-chevron-right fa-2x"></i></span>
                  <span class="sr-only">Siguiente</span>
                </a>
              </div>
            </div>

            <div id="old-notifications">
              <div class="row old-notifications-headers">
                <div class="counter-recent-notification">
                  <h1 class="h6 mb-2"><img src="../../img/notificaciones/ICONO ALERTAS_Mesa de trabajo 1.svg" alt=""> <?=$totalNotificacionesAntiguas; ?> anteriores</h1>
                </div>
                <!--
                <div class="select-notification">
                  <select class="form-control" name="cmbShowNotification" id="cmbShowNotification">
                    <option value="1" selected>Todo</option>
                    <option value="2">TimDesk</option>
                    <option value="3">ERP</option>
                  </select>
                </div>
                -->
              </div>
            <!--  <div class="container-timlib"> -->
                <!--
                <div class="header-name-notification">
                  <h1><img src="../../img/notificaciones/ICONO TIMDESK_Mesa de trabajo 1.svg" alt=""> Notificaciones TimDesk</h1>
                </div>
                -->
                <!--<div class="row timlib">-->
                  <div class="row">
                  <?php
                    $icon ="";
                    foreach ($arrayGeneral as $r) {
                      switch ($r['FKTipoNotificacion']){
                        case 1:
                          $icon = '<img style="/*margin: 15% 0 0 15%;*/" src="../../img/notificaciones/ICONO TAREAS_Mesa de trabajo 1.svg" alt="">';
                          $message = '<a href="'.$rutes.'tareas/timDesk/index.php?id='.$r['PKProyecto'].'"><p>Agregó la  tarea '.$r['Tarea'].' en el proyecto '.$r['Proyecto'].'</p></a>';
                        break;
                        case 2:
                          $icon = '<img style="/*margin: 14% 0 0 15%;*/" src="../../img/notificaciones/ICONO CHAT_Mesa de trabajo 1.svg" alt="">';
                          $message = '<a href="'.$rutes.'tareas/timDesk/index.php?id='.$r['PKProyecto'].'&idTarea='.$r['PKTarea'].'"><p>Te envió un mensaje desde la tarea '.$r['Tarea'].'</p></a>';
                        break;
                        case 3:
                          $icon = '<img style="/*margin: 13% 0 0 15%;*/" src="../../img/notificaciones/ICONO SUBTAREAS_azul-01.svg" alt="">';
                          $message = '<a href="'.$rutes.'tareas/timDesk/index.php?id='.$r['PKProyecto'].'"><p>Agregó la subtarea '.$r['Subtarea'].' en la tarea '.$r['Tarea'].' del proyecto '.$r['Proyecto'].'</p></a>';
                        break;
                        case 4:
                          $icon = '<img style="/*margin: 13% 0 0 15%;*/" src="../../img/notificaciones/ICONO CHECK_AZUL-01.svg" alt="">';
                          $message = '<a href="'.$rutes.'tareas/timDesk/index.php?id='.$r['PKProyecto'].'"><p>Verificó la tarea '.$r['Tarea'].' del proyecto '.$r['Proyecto'].'</p></a>';
                        break;
                      }
                      switch($r['Software']){
                        case '1':
                          $iconSoft ='<img src="../../img/notificaciones/ICONO TIMDESK_Mesa de trabajo 1.svg" alt="">';
                        break;
                        case '2':
                          $iconSoft ='<img src="../../img/notificaciones/T TIMLID.svg" alt="">';
                        break;
                      }
                      $horaGeneral = $r['FechaCreacion'];
                      $horaGeneral1 = new DateTime($r['FechaCreacion']);
                      $auxDay = new DateTime($fechaHora);
                      $diferencia = $horaGeneral1->diff($auxDay);
                      $diasDif = $diferencia->format('%a');

                      $horasDif = date("H",strtotime("00:00:00")+strtotime($hora)-strtotime($horaGeneral));
                      $minutosDif = date("i",strtotime("00:00:00")+strtotime($hora)-strtotime($horaGeneral));
                      $segundosDif = date("s",strtotime("00:00:00")+strtotime($hora)-strtotime($horaGeneral));

                      $horasDif = intval($horasDif);
                      $minutosDif = intval($minutosDif);
                      $segundosDif = intval($segundosDif);

                      if($diasDif > 0){
                        if($diasDif == 1){
                          $tiempoTrans = "hace ".$diasDif." día";
                        }else{
                          $tiempoTrans = "hace ".$diasDif." días";
                        }
                      }else if($horasDif > 0){
                        if($horasDif == 1){
                          $tiempoTrans = "hace ".$horasDif." hora";
                        }else{
                          $tiempoTrans = "hace ".$horasDif." horas";
                        }
                      }else if($minutosDif > 0){
                        if($minutosDif == 1){
                          $tiempoTrans = "hace ".$minutosDif." minuto";
                        }else{
                          $tiempoTrans = "hace ".$minutosDif." minutos";
                        }
                      }else if($segundosDif > 0){
                        if($segundosDif == 1){
                          $tiempoTrans = "hace ".$segundosDif." segundo";
                        }else{
                          $tiempoTrans = "hace ".$segundosDif." segundos";
                        }
                      }
                      if($diasDif > 0){
                        if($diasDif <= 5){
                          $color = "#1C87A0";
                        }
                        if($diasDif > 5){
                          $color = "#15589B";
                        }
                      ?>
                      <!-- Cards col1 -->
                      <div class="col-lg-4 col-md-4 card-total" id="card-<?=$r['Software']; ?>">
                        <input type="hidden" name="idSotfware" id="idSotfware" value="<?=$r['Software']; ?>">
                        <div class="card-notification card mb-2">

                          <div class="card-body">
                            <div class="color-notification" style="background-color:<?=$color; ?>"></div>
                            <div class="row">

                              <div class="user-notification">
                                <img src="../../img/notificaciones/ICONO PROYECTO SIN LIDER_Mesa de trabajo 1.svg" alt="">
                                <div class="name-user-notification">
                                  <p><?=$r['nombre_usuario']; ?></p>

                                </div>
                                <hr class="divisor-user-notification">
                              </div>
                              <div class="notification-message">
                                <p><?=$message; ?></p>
                              </div>
                            </div>
                            <div class="icon-software-footer">
                              <?=$iconSoft; ?>
                            </div>
                            <div class="icon-card-footer">
                              <?=$icon; ?>
                            </div>
                          </div>
                        </div>
                        <div class="time-elapsed">
                          <p><?=$tiempoTrans; ?></p>
                        </div>
                      </div>
                      <?php
                    }
                    //echo "<h5>".$r['Tarea']."</h5>";
                  }
                  ?>
                </div>
              <!--</div>-->

                <div class="container-erp">
                  <div class="header-name-notification-erp">
                    <h1>Notificaciones ERP</h1>
                  </div>
                  <div class="row erp">

                    <div class="example">
                      <h1>No hay notificaciones en ERP</h1>
                    </div>
                    <!--
                    <div class="col-lg-4 col-md-4 card-total">
                      <div class="card-notification card mb-2">
                        <div class="card-body">

                        </div>
                      </div>

                    </div>
                    -->
                  </div>
                </div>

              <!--
              <div class="show-more">
                <a class="button-show-more" id="button-show-more" href="#">Mostrar más</a>
              </div>
              -->
            </div>
          </div>
          <!-- End Page Content -->


        </div>
        <!-- End Main Content -->
        <!-- Footer -->
        <footer class="sticky-footer">
  					<img style="float:right;margin-right:20px" src="../../img/header/timlidAzul.png" width="120px">
        </footer>
        <!-- End of Footer -->
      </div>
      <!-- End Content Wrapper -->



    </div>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>
    <!-- End Page Wrapper -->

  </body>
  <script>


    $(document).ready(function(){
      $("#alertaTareas").load('../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>&ruteEdit=<?=$ruteEdit;?>');
        setInterval(refrescar, 50000);
      });
    function refrescar(){
      $("#alertaTareas").load('../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>&ruteEdit=<?=$ruteEdit;?>');
    }
    /*
    $(document).on('click','#button-show-more',function(){
      var element = $('#old-notifications');
      var newHeight = element.height()*2;
      $(element).height(newHeight);
      var element1 = $('.show-more');
      var position = element1.offset().top - (element1.offset().top*0.38);
      element1.css({top:position+"px"});
    });
    */

    $(document).ready(function(){
      $('.container-timlib').show();
      //$('.container-erp').show();
      $('.card-total').show();
      //var x = 0;
      //console.log("data software: " + $('.card-total').data('Software'));
        //console.log("input idSotfware: "+ $('#idSotfware').val());
    });

    $(document).on('change','#cmbShowNotification',function(){
      var select = $('#cmbShowNotification').val();
      var x = 0;
      switch (select) {
        case '1':
          //$('.container-timlib').show();
          //$('.container-erp').show();
          $('.card-total#card-1').each(function(){
            console.log("#card-1: "+x);
            x++;
            $('.card-total#card-1').show();
            $('.card-total#card-2').show()
          });
        break;
        case '2':
          //$('.container-timlib').show();
          //$('.container-erp').hide();
          $('.card-total#card-1').each(function(){
            console.log("#card-1: "+x);
            x++;
            $('.card-total#card-1').show();
            $('.card-total#card-2').hide()
          });
        break;
        case '3':
          //$('.container-timlib').hide();
          //$('.container-erp').show();
          $('.card-total#card-1').each(function(){
            console.log("#card-1: "+x);
            x++;
            $('.card-total#card-1').hide();
            $('.card-total#card-2').show()
          });
        break
      }
    });


  </script>
</html>

<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (isset($_SESSION["Usuario"])) {
    require_once '../../include/db-conn.php';
    $user = $_SESSION["Usuario"];

    $color = "";
    $alerta = "";
    $mesInicial = 0;
    $mesFinal = 0;

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        /*$stmt = $conn->prepare('SELECT task.PKTarea,task.Tarea,dateTask.Fecha,crono.Rango,priorityTask.Prioridad,repTask.Repetible FROM tareas AS task
        LEFT JOIN rango_fecha AS crono ON task.PKTarea = crono.FKTarea
        LEFT JOIN prioridad_tareas AS priorityTask ON task.PKTarea = priorityTask.FKTarea
        LEFT JOIN fecha_tarea AS dateTask ON task.PKTarea = dateTask.FKTarea
        LEFT JOIN tareas_repetibles AS repTask ON task.PKTarea = repTask.FKTarea
        WHERE task.FKProyecto = :id');*/
        /*$stmt = $conn->prepare('SELECT task.PKTarea,task.Tarea,priorityTask.Prioridad,repTask.Repetible FROM tareas AS task
        LEFT JOIN prioridad_tareas AS priorityTask ON task.PKTarea = priorityTask.FKTarea
        LEFT JOIN tareas_repetibles AS repTask ON task.PKTarea = repTask.FKTarea
        WHERE task.FKProyecto = :id');*/

        $stmt = $conn->prepare('SELECT task.PKTarea,task.Tarea,crono.Rango,crono.PKRangoFecha,priorityTask.Prioridad,repTask.Repetible FROM tareas AS task
                              LEFT JOIN rango_fecha AS crono ON task.PKTarea = crono.FKTarea
                              LEFT JOIN prioridad_tareas AS priorityTask ON task.PKTarea = priorityTask.FKTarea
                              LEFT JOIN tareas_repetibles AS repTask ON task.PKTarea = repTask.FKTarea
                              WHERE task.FKProyecto = :id AND crono.Rango <> "" ');
        $stmt->execute(array(':id' => $id));
        $row = $stmt->fetchAll();

        $stmt = $conn->prepare('SELECT task.PKTarea,task.Tarea,datetask.Fecha,datetask.PKFecha,priorityTask.Prioridad,repTask.Repetible FROM tareas AS task
                              LEFT JOIN fecha_tarea AS datetask ON task.PKTarea = datetask.FKTarea
                              LEFT JOIN prioridad_tareas AS priorityTask ON task.PKTarea = priorityTask.FKTarea
                              LEFT JOIN tareas_repetibles AS repTask ON task.PKTarea = repTask.FKTarea
                              WHERE task.FKProyecto = :id AND datetask.Fecha <> "" ');
        $stmt->execute(array(':id' => $id));
        $arrayFecha = $stmt->fetchAll();

    }

} else {
    header("location:../dashboard.php");
}

?>
<!DOCTYPE html>
<html lang="es" dir="ltr">

<head>
  <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Timlid | Calendario de tareas</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"
    integrity="sha256-c4gVE6fn+JRKMRvqjoDp+tlG4laudNYrXI1GncbfAYY=" crossorigin="anonymous"></script>

  <!-- Page level plugins -->
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.responsive.js"></script>
  <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts
    <script src="js/demo/datatables-demo.js"></script>
    -->

  <!-- Custom fonts for this template -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css"
    integrity="sha256-EH/CzgoJbNED+gZgymswsIOrM9XhIbdSJ6Hwro09WE4=" crossorigin="anonymous" />
  <link href="../../css/chosen.css" rel="stylesheet" type="text/css">

  <!-- Custom styles for this template -->
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">

  <!-- jquery ui -->
  <script src="../../js/jquery-ui.js"></script>
  <link rel="stylesheet" href="../../css/jquery-ui.css">

  <!-- mdb -->
  <!--
    <script src="../../js/mdb.min.js"></script>
    <link rel="stylesheet" href="../../css/mdb.min.css">

    <script src="../../js/mdb.min.js.map"></script>
    <link rel="stylesheet" href="../../css/mdb.min.css.map">
    -->
  <!-- full calendar -->
  <link href='../../vendor/fullcalendar/core/main.css' rel='stylesheet' />
  <link href='../../vendor/fullcalendar/daygrid/main.css' rel='stylesheet' />
  <link href='../../vendor/fullcalendar/bootstrap/main.css' rel='stylesheet' />
  <link href='../../vendor/fullcalendar/timegrid/main.css' rel='stylesheet' />
  <link href='../../vendor/fullcalendar/list/main.css' rel='stylesheet' />

  <script src='../../vendor/fullcalendar/core/main.js'></script>
  <script src='../../vendor/fullcalendar/daygrid/main.js'></script>
  <script src='../../vendor/fullcalendar/core/locales/es.js'></script>
  <script src='../../vendor/fullcalendar/moment/main.js'></script>
  <script src='../../vendor/fullcalendar/interaction/main.js'></script>
  <script src='../../vendor/fullcalendar/bootstrap/main.js'></script>
  <script src='../../vendor/fullcalendar/timegrid/main.js'></script>
  <script src='../../vendor/fullcalendar/list/main.js'></script>
  <script src='../../vendor/fullcalendar/rrule/main.js'></script>

  <style>

  #calendario {
    /*margin-left: 0;*/
    /*margin-right: 0;*/
    max-width: 100%;
    margin: 40px auto;
    min-width: 1%;
  }

  .fc-button-primary {
    font-weight: lighter;
  }

  .fc-center {
    font-size: 24px;
  }

  .fc-widget-header {
    font-weight: lighter;
  }

  .fc-day-top {
    font-weight: lighter;
  }

  .fc-week-number {
    font-weight: lighter;
  }

  .fc-content {
    font-weight: lighter;
    font-size: 14px;
  }

  .fc-week-number {
    background-color: #808080;
    color: white;

  }

  .fc-day-header {
    background-color: #E5E5E5;
    font-weight: bolder;
  }

  .fixed {
    position: fixed;
    top: 0;
    background-color: #E5E5E5;
    word-wrap: break-word;
    z-index: 100;
    width: 90%;
  }

  .fixed-days {
    position: fixed;
    top: 7%;
    background-color: #E5E5E5;
    word-wrap: break-word;
    z-index: 100;
    width: 90%;
  }

  .fc-view-container {
    z-index: 0;
  }

/*   .header-screen {
    display: block;
    width: 400px;
    height: 35px;
    align-items: center;
    vertical-align: middle;
  } */

  .header-title-screen {
    display: inline-block;
    color: #15589B;
    float: left;
    top: 0;
  }

  .header-title-screen img {
    display: inline-block;
    height: 40px;
  }

  .vl {
    color: #E5E5E5;
    height: 35px;
    display: inline-block;
    background: #E5E5E5;
    border: 1px solid;
    margin-left: 25px;
    margin-right: 25px;
    float: left;
    top: 1px;
  }

  .logo-views {
    display: inline-block;
    height: 35px;
    float: left;
    top: 0;
    color: #15589B;
  }

  .logo-views-menus {
    border: 1px solid;
    background-color: rgba(2, 24, 46, .9);
    border-radius: 0.35rem;
    z-index: 1;
  }

  .logo-views-menus a {
    display: block;
    padding: 0.5rem 1rem;
    ;
    text-decoration: none;
    color: #4e73df;
    background-color: transparent;
    font-size: 0.85rem;
  }

  .logo-views-menus a:visited {
    color: #b2b2b2 !important;
  }

  .logo-views-menus a:link {
    color: #b2b2b2 !important;
  }

  .logo-views-menus a:hover {
    color: #fff !important;
  }

  .logo-views-title img {
    display: inline-block;
    height: 30px;
  }

  .logo-views-title h1 {
    font-size: 12px
  }

  .logo-views .logo-views-menus {
    display: none;
    position: absolute;
    min-width: 140px;
  }

  .title-card {
    display: inline-block;
    color: #15589B;
    display: inline-block;
  }

  .title-card img {
    display: inline-block;
    height: 40px;
  }
  </style>
  <link rel="stylesheet" href="../../css/scrum_screen.css">
  <link href="../tareas/timDesk/style/css.css" rel="stylesheet" type="text/css">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <script src="../../js/slimselect.min.js"></script>
  <script>
  $(function() {
    //$('[data-toggle="tooltip"]').tooltip("hide");
    $('[data-toggle="tooltip"]').on('click', function() {
      $(this).tooltip('hide');
    });
    //$('[rel=tooltip]').tooltip({ trigger: "hover" });

  })
  </script>
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">
    <!-- Sidebar -->
    <?php
$ruta = "../";
$ruteEdit = $ruta . "central_notificaciones/";
require_once '../menu3.php';
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
                <h1 class="h3 mb-2"><img src="../../img/scrum/vista_calendario1.svg" alt=""> Calendario</h1>
              </div>
              <div class="vl"></div>
              <div class="logo-views">
                <div class="logo-views-title">
                  <h1 class="h3 mb-2"><img src="../../img/scrum/vistas1.svg" alt="">  Vistas</h1>
                </div>
                <div style="margin-left:-4px;">
                  <div class="logo-views-menus">
                    <a href="#" id="goTimDesk">Timdesk</a>
                    <a href="#" id="goScrum">Scrum</a>
                  </div>
                </div>
              </div>

            </div>';
require_once '../topbar.php';
?>
        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- Page Heading -->

          <div id="alertas"></div>
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <div class="row">
                <div class="col-lg-8" style="margin-left:-40px;">
                  <div style="display:inline-block; margin-left:25px;">
                    <div id="select-project" class="mr-10" style="padding: 10px;">
                      <i class="projects-icon imgHover imgActive" onclick="seeProjects()"></i>
                      <div id="aaa" class="form-group-projects pos-abs d-no" aria-describedby='aaa'>
                        <select id="projects-List" class="w-300px">
                          <option value="0">Selecciona un proyecto</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <h1 class="h3 mb-2 title-card">Calendario de tareas</h1>
                </div>
                <div class="col-lg-4">
                  <!--
                    <div class="severalOptions">
                      <div id="select-project" class="mr-10 pos-rel" style="padding: 6px;">
                        <i class="projects-icon imgHover imgActive" onclick="seeProjects()"></i>
                        <div id="aaa" class="form-group-projects pos-abs d-no" aria-describedby='aaa' style="right:10%;">
                          <select id="projects-List" class="w-300px">
                            <option value="0">Selecciona un proyecto</option>
                          </select>
                        </div>
                      </div>
                    -->
                </div>
              </div>

            </div>
            <div class="card-body">
              <input type="hidden" value="<?=$id;?>" id="txtIdProject">
              <div id="calendario"></div>
            </div>
          </div>
        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <?php
$rutaf = "../";
require_once '../footer.php';
?>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->
  </div>
  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendario');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      plugins: ['dayGrid', 'bootstrap', 'interaction', 'timeGrid', 'list'],
      locale: 'es',
      themeSystem: 'themeSystem',
      editable: true,

      header: {
        left: 'prevYear,prev,next,nextYear today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
      },
      titleFormat: {
        month: 'long',
        year: 'numeric'
      },

      weekNumbers: true,
      navLinks: true, // can click day/week names to navigate views
      editable: true,
      eventLimit: true,

      events: [
        <?php
/*Para fecha con rango*/

foreach ($row as $r) {
    $texto = "";
    $startEvent = "";
    $dateFinal = "";
    $contador = 0;

    if ($r['Prioridad'] == 1) {
        $color = '#e53341';
    } else if ($r['Prioridad'] == 2) {
        $color = '#ede966';
    } else {
        $color = '#28c67a';
    }

    $texto = $r['Rango'];

    if ($texto != null || $texto != "") {
        $divisor = explode(" a ", $texto);
        $auxFechaInicio = explode("/", $divisor[0]);
        $auxFechaTermino = explode("/", $divisor[1]);
        //$auxFechaInicio = strtolower($auxFechaInicio);
        $auxmesinicial = preg_replace('([^A-Za-z0-9])', '', $auxFechaInicio[1]);
        $auxmesfinal = preg_replace('([^A-Za-z0-9])', '', $auxFechaTermino[1]);

        switch ($auxmesinicial) {
            case "Ene":
                $mesInicial = 1;
                break;
            case "ne":
                $mesInicial = 1;
                break;
            case "Feb":
                $mesInicial = 2;
                break;
            case "eb":
                $mesInicial = 2;
                break;
            case "Mar":
                $mesInicial = 3;
                break;
            case "ar":
                $mesInicial = 3;
                break;
            case "Abr":
                $mesInicial = 4;
                break;
            case "br":
                $mesInicial = 4;
                break;
            case "May":
                $mesInicial = 5;
                break;
            case "ay":
                $mesInicial = 5;
                break;
            case "Jun":
                $mesInicial = 6;
                break;
            case "un":
                $mesInicial = 6;
                break;
            case "Jul":
                $mesInicial = 7;
                break;
            case "ul":
                $mesInicial = 7;
                break;
            case "Ago":
                $mesInicial = 8;
                break;
            case "go":
                $mesInicial = 8;
                break;
            case "Sep":
                $mesInicial = 9;
                break;
            case "ep":
                $mesInicial = 9;
                break;
            case "Oct":
                $mesInicial = 10;
                break;
            case "ct":
                $mesInicial = 10;
                break;
            case "Nov":
                $mesInicial = 11;
                break;
            case "ov":
                $mesInicial = 11;
                break;
            case "Dic":
                $mesInicial = 12;
                break;
            case "ic":
                $mesInicial = 12;
                break;
        }

        switch ($auxmesfinal) {
            case "Ene":
                $mesFinal = 1;
                break;
            case "ne":
                $mesFinal = 1;
                break;
            case "Feb":
                $mesFinal = 2;
                break;
            case "eb":
                $mesFinal = 2;
                break;
            case "Mar":
                $mesFinal = 3;
                break;
            case "ar":
                $mesFinal = 3;
                break;
            case "Abr":
                $mesFinal = 4;
                break;
            case "br":
                $mesFinal = 4;
                break;
            case "May":
                $mesFinal = 5;
                break;
            case "ay":
                $mesFinal = 5;
                break;
            case "Jun":
                $mesFinal = 6;
                break;
            case "un":
                $mesFinal = 6;
                break;
            case "Jul":
                $mesFinal = 7;
                break;
            case "ul":
                $mesFinal = 7;
                break;
            case "Ago":
                $mesFinal = 8;
                break;
            case "go":
                $mesFinal = 8;
                break;
            case "Sep":
                $mesFinal = 9;
                break;
            case "ep":
                $mesFinal = 9;
                break;
            case "Oct":
                $mesFinal = 10;
                break;
            case "ct":
                $mesFinal = 10;
                break;
            case "Nov":
                $mesFinal = 11;
                break;
            case "ov":
                $mesFinal = 11;
                break;
            case "Dic":
                $mesFinal = 12;
                break;
            case "ic":
                $mesFinal = 12;
                break;
        }
    }

    $fechaInicio = $mesInicial . "/" . $auxFechaInicio[0] . "/" . $auxFechaInicio[2];
    $fechaTermino = $mesFinal . "/" . $auxFechaTermino[0] . "/" . $auxFechaTermino[2];
    $startEvent = date('Y-m-d', strtotime($fechaInicio));
    $dateFinal = date('Y-m-d', strtotime($fechaTermino . " + 1 days"));
    $alerta .= "El texto es " . $texto .
        " El texto1 es: " . $divisor[0] .
        " El texto2 es: " . $divisor[1] .
        " El auxFechaInicio es: " . $auxFechaInicio[1] .
        " El auxFechaTermino es: " . $auxFechaTermino[1] .
        " El auxMesInicial es: " . $auxmesinicial .
        " El auxMesTermino es: " . $auxmesfinal .
        " El mesInicio es: " . $mesInicial .
        " El mesFinal es: " . $mesFinal .
        " El FechaInicio es: " . $fechaInicio .
        " El FechaTermino es: " . $fechaTermino .
        " La fecha inicio es: " . $startEvent .
        " La fecha termino es: " . $dateFinal;

    switch ($r['Repetible']) {
        case 1:
            $repetir = " + 0 days";
            $limite = 0;
            break;
        case 2:
            $repetir = " + 1 days";
            $limite = 365;
            break;
        case 3:
            $repetir = " + 1 weeks";
            $limite = 52;
            break;
        case 4:
            $repetir = " + 2 weeks";
            $limite = 26;
            break;
        case 5:
            $repetir = " + 1 months";
            $limite = 12;
            break;
        case 6:
            $repetir = " + 3 months";
            $limite = 4;
            break;
        case 7:
            $repetir = " + 6 months";
            $limite = 2;
            break;
        case 8:
            $repetir = " + 1 years";
            $limite = 1;
            break;
        default:
            $repetir = " + 0 days";
            break;
    }
    $startEventRecur = date('Y-m-d', strtotime($startEvent . $repetir));
    $dateFinalRecur = date('Y-m-d', strtotime($dateFinal . $repetir));

    ?> {
          id: "<?=$r['PKRangoFecha'];?>",
          title: "<?=$r['Tarea'];?>",
          start: "<?=$startEvent;?>",
          end: "<?=$dateFinal;?>",
          color: "<?=$color;?>",
          textColor: 'white',
        },

        <?php
/*
    if($r['Repetible'] > 1 || $r['Repetible'] != "" || $r['Repetible'] != null){
    while($contador < $limite){
     */
    ?>
        /*
        {
          id: "<?//=$r['PKTarea']; ?>",
          title: "<?//=$r['Tarea']; ?>",
          start: "<?//=$startEventRecur; ?>",
          end: "<?//=$dateFinalRecur; ?>",
          color: "<?//=$color; ?>",
          textColor: 'white',
          description: "<?//=$tabla; ?>",
        },
        */
        <?php
/*
    $startEventRecur = date('Y-m-d',strtotime($startEventRecur.$repetir));
    $dateFinalRecur = date('Y-m-d',strtotime($dateFinalRecur.$repetir));
    $contador++;
    }
    }
     */
    ?>
        <?php
}
foreach ($arrayFecha as $r) {
    switch ($r['Prioridad']) {
        case 1:
            $color = '#e53341';
            break;
        case 2:
            $color = '#ede966';
            break;
        default:
            $color = '#28c67a';
            break;
    }
    $startEvent = date('Y-m-d', strtotime($r['Fecha']));
    $dateFinal = date('Y-m-d', strtotime($r['Fecha'] . " + 1 days"));

    ?> {
          id: "<?=$r['PKFecha'];?>",
          title: "<?=$r['Tarea'];?>",
          start: "<?=$startEvent;?>",
          end: "<?=$dateFinal;?>",
          color: "<?=$color;?>",
          textColor: 'white',
        },
        <?php }?>
      ],
      eventDrop: function(info) {
        var endDate = "";
        var startDate = info.event.start.toISOString().split("T")[0];
        if (info.event.end != null) {
          endDate = info.event.end.toISOString().split("T")[0];
        }

        var cadena = "id=" + info.event.id + "&date=" + startDate + "&date2=" + endDate + "&tabla=" + info
          .event.description;
        console.log(cadena);
        $.ajax({
          type: 'POST',
          data: cadena,
          url: 'functions/edit_event_drop.php',
          success: function(data) {
            location.reload();
          }
        });
      },
      eventResize: function(info) {
        var startDate = info.event.start.toISOString().split("T")[0];
        var endDate = info.event.end.toISOString().split("T")[0];

        //endDate = endDate.getDate() - 1;
        //var cadena = "id="+info.event.id+"&date="+startDate+"&date2="+endDate+"&tabla="+info.event.extendedProps.description;
        var cadena = "id=" + info.event.id + "&date=" + startDate + "&date2=" + endDate + "&proyecto=" +
          <?=$id;?>;
        console.log(cadena);
        $.ajax({
          type: 'POST',
          data: cadena,
          url: 'functions/edit_event_resize.php',
          success: function(data) {
            //location.reload();
            console.log(data);
          }
        });
      },
    });




    $(window).scroll(function() {
      posicionarMenu();
    });

    function posicionarMenu() {
      var altura_del_header = $('.card-header').outerHeight(true);
      var altura_del_menu = $('.fc-header-toolbar').outerHeight(true);
      var altura_header_dias = $('.fc-head-container').outerHeight(true);
      //alert(altura_header_dias+altura_del_menu);
      if ($(window).scrollTop() >= altura_del_header) {
        $('.fc-header-toolbar').addClass('fixed');
        $('.fc-head-container').addClass('fixed-days');
        $('.fc-view-container').css('margin-top', (altura_del_menu) + 'px');
        $('.fc-day-header').css('margin-top', (altura_header_dias) + 'px');
      } else {
        $('.fc-header-toolbar').removeClass('fixed');
        $('.fc-head-container').removeClass('fixed-days');
        $('.fc-view-container').css('margin-top', '0');
        $('.fc-day-header').css('margin-top', '0');
      }
    }
    calendar.render();
  });
  console.log("<?=$alerta;?>");
  $(document).on('click', '.logo-views-title', function() {
    if ($('.logo-views-menus').toggle() == false) {
      $('.logo-views-menus').css('display', 'block');
    }
  });

  $(document).on('click', '#goTimDesk', function() {
    var id = $('#txtIdProject').val();
    window.location.href = "../tareas/timDesk/index.php?id=" + id;
  });

  $(document).on('click', '#goScrum', function() {
    var id = $('#txtIdProject').val();
    window.location.href = "../pantalla_scrum/index.php?id=" + id;
  });

  $(document).on('click', '#goCalendar', function() {
    var id = $('#txtIdProject').val();
    window.location.href = "../calendario_tareas/index.php?id=" + id;
  });
  </script>
  <script type="text/javascript">
  //esconder menu de proyectos
  $(document).mouseup(function(e) {
    var container = $(".form-group-projects");
    if (!container.is(e.target) && container.has(e.target).length === 0) {
      container.hide();
    }
  });

  function seeProjects() { //Lista de los proyectos del usuario
    varContainer = '.form-group-projects'
    $('.project-opt').remove()
    $('#aaa').removeClass('d-no');
    $('#aaa').show();
    $.ajax({
      url: "../tareas/timDesk/php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "getAllProjects"
      },
      dataType: "json",
      success: function(resp) {
        //console.log(resp);
        $.each(resp, function() {
          $('#projects-List').append('<option class="project-opt" value=' + this.PKProyecto + '>' + this
            .Proyecto + '</option>')
        })

      },
      error: function(error) {
        console.log(error);
      }
    });

  }

  var seleccionProyecto = new SlimSelect({
    select: '#projects-List',
    placeholder: 'Seleccione un proyecto',
    searchPlaceholder: 'Buscar proyecto',
    beforeOpen: function() {
      //console.log('afterOpen')
    },
    onChange: (info) => {
      idProyecto = info.value;
      idProyectoUrl = info.value;
      window.location.href = "index.php?id=" + idProyecto;
    }
  });
  </script>
  <script>
  $(document).ready(function() {
    $("#alertaTareas").load('../alerta_Tareas_Nuevas.php?user=' + <?=$_SESSION['PKUsuario'];?> + '&ruta=' +
      '<?=$ruta;?>&ruteEdit=<?=$ruteEdit;?>');
    setInterval(refrescar, 50000);
  });

  function refrescar() {
    $("#alertaTareas").load('../alerta_Tareas_Nuevas.php?user=' + <?=$_SESSION['PKUsuario'];?> + '&ruta=' +
      '<?=$ruta;?>&ruteEdit=<?=$ruteEdit;?>');
  }
  </script>
  <script>
  $(document).ready(function() {
    var element = $('.fc-center h2');
    var div = element.text().split(' de ');
    var text = div[0] + " " + div[1];
    //alert(element.text());
    //alert(text);
    element.text(text);
    /*$('.fc-prev-button').click(function(){
      var element = $('.fc-center h2');
      var div = element.text().split(' de ');
      var text = div[0]+" "+div[1];
      //alert(element.text());
      //alert(text);
      element.text(text);
    });*/
  });
  $(document).on('click', '.fc-prev-button', function() {
    var element = $('.fc-center h2');
    var div = element.text().split(' de ');
    var text = div[0] + " " + div[1];
    //alert(element.text());
    //alert(text);
    element.text(text);
  });
  $(document).on('click', '.fc-next-button', function() {
    var element = $('.fc-center h2');
    var div = element.text().split(' de ');
    var text = div[0] + " " + div[1];
    //alert(element.text());
    //alert(text);
    element.text(text);
  });
  $(document).on('click', '.fc-prevYear-button', function() {
    var element = $('.fc-center h2');
    var div = element.text().split(' de ');
    var text = div[0] + " " + div[1];
    //alert(element.text());
    //alert(text);
    element.text(text);
  });
  $(document).on('click', '.fc-nextYear-button', function() {
    var element = $('.fc-center h2');
    var div = element.text().split(' de ');
    var text = div[0] + " " + div[1];
    //alert(element.text());
    //alert(text);
    element.text(text);
  });
  </script>
  <script>
  var idData = $(this).data("id1");
  var data = "table=chats&id=" + idData;
  var idProject = $(this).data("project");
  var idTask = $(this).data("task");
  console.log("idTarea: " + idTask + "\nidProyecto: " + idProject + "\nData: " + data + "\nidData: " + idData);
  </script>
</body>

</html>
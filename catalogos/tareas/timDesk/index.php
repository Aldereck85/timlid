<?php
session_start();
//cuenta monday.com disenosocialjal@gmail.com perfil2020
$id_usuario = $_SESSION["PKUsuario"];
if (isset($_SESSION["Usuario"])) {
  require_once '../../../include/db-conn.php';

  /*Se agregar y definen los id para acceder al chat cuando se copian los enlaces.
    Se define el nombre de usuario.*/
  $user = $_SESSION["Usuario"];
  $post_max_size_limite = ini_get('post_max_size');
  $post_max_size = intval($post_max_size_limite) * 1000000;

  if (isset($_GET['id'])) {
    $id = $_GET['id'];
  } else {
    $id = 1;
  }

  // Validar encargado del proyecto
  $stmt = $conn->prepare("SELECT * FROM responsables_proyecto WHERE proyectos_PKProyecto = :idProyecto AND usuarios_id = :idUsuario");
  $stmt->execute([':idProyecto' => $id, ':idUsuario' => $_SESSION['PKUsuario']]);
  $encargadoValido = $stmt->fetch(PDO::FETCH_ASSOC);

  //VALIDAR QUE EL USUARIO TENGA ACCESO AL PROYECTO
  $stmt = $conn->prepare("SELECT u.id FROM proyectos as p LEFT JOIN usuarios as u ON u.id = p.FKResponsable WHERE p.PKProyecto = :idProyecto");
  $stmt->bindValue(":idProyecto", $id);
  $stmt->execute();
  $datosProyecto = $stmt->fetch();

  $stmt = $conn->prepare("SELECT FKUsuario FROM integrantes_proyecto WHERE FKProyecto = :idProyecto");
  $stmt->bindValue(":idProyecto", $id);
  $stmt->execute();
  $usuariosProyecto = $stmt->fetchAll();

  $stmt = $conn->prepare("SELECT ie.FKEmpleado as FKUsuario, ep.FKEquipo FROM equipos_por_proyecto as ep INNER JOIN integrantes_equipo as ie ON ie.FKEquipo = ep.FKEquipo WHERE ep.FKProyecto = :idProyecto");
  $stmt->bindValue(":idProyecto", $id);
  $stmt->execute();
  $usuariosEquipo = $stmt->fetchAll();
  //var_dump($usuariosEquipo);

  $usuarios = array_merge($usuariosProyecto, $usuariosEquipo);
  //print_r($usuarios);

  $cont = 0;
  foreach ($usuarios as $us) {
    if ($us['FKUsuario'] == $_SESSION['PKUsuario']) {
      $cont++;
    }
  }

  if (!($_SESSION['PKUsuario'] == $datosProyecto['id'] || $cont > 0)) {
    header("location:../../proyectos/");
  }

  if ($_SESSION['PKUsuario'] == $datosProyecto['id']) {
    $permisoGeneral = 1;
  } else {
    $permisoGeneral = 0;
  }

  $idIndividual = 0;
  if (isset($_GET['idIndividual'])) {
    $idIndividual = $_GET['idIndividual'];
  }
  $idTareaIndividual = 0;
  if (isset($_GET['idTarea'])) {
    $idTareaIndividual = $_GET['idTarea'];
  }

  $stmt = $conn->prepare('SELECT CONCAT(e.Nombres," ", e.PrimerApellido) as nombre_empleado FROM usuarios as u INNER JOIN empleados as e ON e.PKEmpleado = u.id WHERE u.id = :idusuario');
  $stmt->bindValue(':idusuario', $_SESSION["PKUsuario"]);
  $stmt->execute();
  $row = $stmt->fetchAll();

  if (trim($row[0]['nombre_empleado']) != "") {
    $nombreusuario = $row[0]['nombre_empleado'];
  } else {
    $nombreusuario = "Error BD";
  }
} else {
  header("location:../../dashboard.php");
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>TimLid</title>
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
  <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/4.3.1/flatly/bootstrap.min.css">-->
  <link rel="icon" type="image/png" href="../../../img/header/bluTimlid.png">
  <!--<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">-->
  <link href="style/css.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
  <link href="../../../js/flatpickr/dist/flatpickr.css">
  <link href="../../../js/picker/dist/bcp.css" rel="stylesheet">
  <link href="../../../css/slimselect.min.css" rel="stylesheet">
  <link href="style/verificar.css" rel="stylesheet">
  <link href="style/menuDespegable.css" rel="stylesheet">
  <link href="style/simpleText.css" rel="stylesheet">
  <link href="style/chat.css" rel="stylesheet">
  <link href="style/subtask.css" rel="stylesheet">
  <link href="style/mytask.css" rel="stylesheet">
  <link rel="stylesheet" href="../../../css/chat/file-input.css">
  <link href="style/css/alertify.css" rel="stylesheet">
  <link href="../../../css/lobibox.min.css" rel="stylesheet">
  <link href="style/css/themes/default.css" rel="stylesheet">
  <link rel="stylesheet" href="../../../js/build/css/intlTelInput.css">
  <link rel="stylesheet" href="../../../js/build/css/demo.css">
  <link rel="stylesheet" type="text/css" href="../../../js/flatpickr/dist/themes/light.css">
  <link href="../../../css/slimselect.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../../css/chat/file-input.css">
  <link href="../../../css/chat/multiple-emails.css" rel="stylesheet">
  <link href="../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../css/styles.css" rel="stylesheet">
  <link rel="stylesheet" href="../../../css/chat/sumoselect.css">
  <link rel="stylesheet" href="../../../css/scrum_screen.css">
  <link href="../../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <script src="../../../vendor/jquery/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
  </script>
  <script src="../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../js/slimselect.min.js"></script>

  <link rel="stylesheet" href="../../../css/notificaciones.css">

  <!-- <script src="../../../js/notificaciones_timlid.js" charset="utf-8"></script> -->

  <style type="text/css">
    /* .header-screen {
    display: block;
    width: 400px;
    height: 35px;
    align-items: center;
    vertical-align: middle;
  } */

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

    .logo-views img {
      display: inline-block;
      height: 30px;
    }

    .logo-views h1 {
      font-size: 12px;
    }

    .logo-views {
      display: inline-block;
      height: 35px;
      float: left;
      top: 0;
      color: #15589B;
    }

    .swal-icon {
      width: 60px;
      height: 60px;
      margin: 5px auto;
    }

    .swal-icon--warning__body {
      height: 30px;
    }

    .swal-button {
      height: auto;
    }

    .lobibox-notify.lobibox-notify-warning {
      border-color: #F26608;
      background-color: #F26608;
    }

    /* columna teléfono */
    .iti__country-list {
      right: -144px;
    }

    #list-all-columns.modal-body {
      padding: 1rem;
      color: #616161;
    }
  </style>
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
<!--<div class="se-pre-con"></div>-->

<body id="page-top">
  <div id="wrapper">
    <?php
    $ruta = "../../";
    $ruteEdit = $ruta . "central_notificaciones/";
    $icono = 'ICONO-TIMPROYECTOS-AZUL.svg';
    $titulo = 'Tim Proyectos';


    require_once '../../menu3.php';

    ?>
    <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
    <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
    <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">
    <input type="hidden" name="" id="txtIdProject" value="<?= $id; ?>">
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <?php
        $rutatb = "../../";
        require_once '../../topbar.php';
        ?>
        <div class="d-flex">
          <div id="page-content-wrapper">
            <div class="board-header">
              <div class="header-top">
                <!-- titulo -->
                <div class="search" align="right">
                  <input id="search-input" type="text" name="search_tareas" class="search-tareas" placeholder="Buscar..." />
                </div>
                <div class="proyecto d-flex align-items-center pos-rel" style="margin-bottom: 0 !important;">
                  <div id="select-project">
                    <div class="form-group-projects pos-abs d-no">
                      <select id="projects-List" class="w-300px">
                        <option value="0">Selecciona un proyecto</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>

              <?php if ($encargadoValido) { ?>
                <div class="btn-custom d-flex align-items-center pos-rel" style="width:265px;">
                  <img src="../timDesk/img/icons/BOTON EDITAR  PROYECTO AZUL NVO-01.svg" class="imgHover imgActive" style="widght:100%" data-toggle="modal" data-target="#modalEditar" data-original-title="Cambia de proyecto" onclick="obtenerIdProyectoEditar(<?= $id; ?>)">
                </div>
              <?php } ?>
              <div class="severalOptions">

                <div class="d-flex">
                  <div id="cerrarEtapas" class="pos-rel mr-1">
                    <span id="show-hide-tasks-tip" class="show-hide-tip-content d-no pos-abs"></span>
                    <i class="close_rows_icon" id="tipVO" data-toggle="tooltip" data-placement="top" data-original-title="Ocultar tareas" onclick="hideAllTask()"></i>
                  </div>
                </div>
                <div class="d-flex">
                  <div id="get-myTasks" class="mr-1 pos-rel" style="padding: 6px;">
                    <div id="MisTareas">
                      <i class="mis-tareas imgHover imgActive" onclick="getMyTasks(<?php echo $id_usuario ?>)" data-toggle="mytaskTip" data-placement="top" title="" data-original-title="Ver mis tareas"></i>
                    </div>
                  </div>
                </div>
                <button class="btn-custom btn-custom--white-dark mr-1" type="button" data-toggle="modal" data-target="#modalColumnsElement">
                  Agregar columna
                </button>
                <div>
                  <a id="agregarEtapa" class="btn-custom btn-custom--white-dark">Agregar etapa</a>
                </div>
              </div>
              <div class='listaColumnas'>
              </div>
            </div>
            <div class="date"></div>
            <div id="boardContent" class="board-content">
            </div>
          </div>
          <!-- /#page-content-wrapper -->
        </div>
      </div>
      <!-- Footer -->
      <?php
      $rutaf = "../../";
      require_once '../../footer.php';
      ?>
      <!-- End of Footer -->
    </div>

    <!-- MODALES DEL CHAT -->

    <!-- Central Modal Medium -->
    <div class="modal fade right" id="fluidModalRightSuccess" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-full-height modal-right modal-notify modal-success" role="document">
        <!--Content-->
        <div class="modal-content">
          <!--Header-->
          <div class="modal-header">
            <p class="heading lead" id="tituloChat">Tarea</p>

            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -10px; margin-right: 1rem;">
              <span aria-hidden="true" class="white-text">x</span>
            </button>

          </div>

          <!--Body-->
          <div class="modal-body">
            <div id="chatCompleto">
              <div class="row menu-superior-chat">
                <div class="col-md-12">
                  <nav class="menu">
                    <ol style="margin-bottom:0px;">
                      <li class="menu-item active">
                        <a href="#0" class="enlaceSub" onclick="verActualizaciones()">Actualizaciones</a>
                      </li>
                      <li class="menu-item">
                        <a href="#0" class="enlaceSub" onclick="verActividad()">Actividad</a>
                      </li>
                      <li class="menu-item">
                        <a href="#" onclick="mostrarEquipos()">
                          <span data-toggle="tooltip" class="tooltip-chat" title="<?= $nombreusuario ?>" style="position: relative; bottom: 4px;"><img src="../../../img/chat/user-add.svg" class="user-img img-responsive" width="35px"></span>
                        </a>
                      </li>
                      <li class="menu-item">
                        <span><button type="button" id="botonDesplegable" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-h"></i></button>
                          <ul class="dropdown-menu dropdown-menu-right ellipsis py-2 collapse-inner rounded sin-flecha" role="menu" aria-labelledby="dropdownMenu">
                            <li>
                              <a class="dropdown-item menu-chat__item" href="#" onclick="mostrarEquipos()">
                                <svg viewBox="0 0 30 30" width="30" height="30">
                                  <g>
                                    <path class="st0" d="M17.6,28c-6.2,0-11.2-5-11.2-11.2c0-0.9,0.1-1.9,0.3-2.7c0-0.2,0.2-0.3,0.4-0.2c0.2,0,0.2,0.2,0.2,0.4
		C7.1,15.1,7,16,7,16.9c0,5.8,4.7,10.6,10.6,10.6c5.8,0,10.6-4.7,10.6-10.6S23.4,6.3,17.6,6.3c-1.2,0-2.3,0.2-3.5,0.6
		c-0.2,0.1-0.3,0-0.4-0.2c-0.1-0.2,0-0.3,0.2-0.4c1.2-0.4,2.4-0.6,3.6-0.6c6.2,0,11.2,5,11.2,11.2S23.7,28,17.6,28z" />
                                    <g>
                                      <path class="st0" d="M17.6,16.8c-1.3,0-2.5-0.7-3.2-1.8c-0.4-0.6-0.6-1.3-0.6-2c0-2.1,1.7-3.8,3.8-3.8c2.1,0,3.8,1.7,3.8,3.8
			c0,0.7-0.2,1.4-0.6,2C20.1,16.2,18.9,16.8,17.6,16.8z M17.6,9.8c-1.8,0-3.2,1.4-3.2,3.2c0,0.6,0.2,1.2,0.5,1.7
			c0.6,1,1.6,1.5,2.7,1.5c1.1,0,2.2-0.6,2.7-1.5c0.3-0.5,0.5-1.1,0.5-1.7C20.8,11.3,19.3,9.8,17.6,9.8z" />
                                      <path class="st0" d="M23.2,24.5H11.9c-0.1,0-0.2,0-0.2-0.1c-0.1-0.1-0.1-0.1-0.1-0.2c0.5-3.8,3-6.5,5.9-6.5c3,0,5.5,2.7,5.9,6.5
			c0,0.1,0,0.2-0.1,0.2C23.4,24.4,23.3,24.5,23.2,24.5z M12.2,23.9h10.6c-0.5-3.3-2.7-5.7-5.3-5.7C15,18.2,12.8,20.6,12.2,23.9z" />
                                    </g>
                                  </g>
                                  <path class="st0" d="M7,13.2c-3.2,0-5.9-2.6-5.9-5.9c0-3.2,2.6-5.9,5.9-5.9c3.2,0,5.9,2.6,5.9,5.9C12.9,10.6,10.3,13.2,7,13.2z M7,2
	C4.1,2,1.7,4.4,1.7,7.3s2.4,5.3,5.3,5.3s5.3-2.4,5.3-5.3S10,2,7,2z" />
                                  <path class="st0" d="M7.3,7.9h2.8c0.2,0,0.4-0.2,0.4-0.4c0-0.2-0.2-0.4-0.4-0.4H7.3V4.4C7.3,4.2,7.2,4,6.9,4S6.5,4.2,6.5,4.4v2.8
	H3.8c-0.2,0-0.4,0.2-0.4,0.4c0,0.2,0.2,0.4,0.4,0.4h2.8v2.8c0,0.2,0.2,0.4,0.4,0.4s0.4-0.2,0.4-0.4V7.9z" />
                                </svg>
                                Administrar integrantes
                              </a>
                            </li>
                            <li>
                              <a class="dropdown-item menu-chat__item" href="#" onclick="verFavoritos()">
                                <svg viewBox="0 0 30 30" width="30" height="30">
                                  <path class="st0" d="M15.1,27.1c-0.1,0-0.2,0-0.3-0.1c-0.6-0.4-15.4-9.4-13-18.9c1-4,6-5,6.6-5c3,0,5.3,1.8,6.6,3.1
	c1.3-1.3,3.6-3.1,6.6-3.1c0.7,0,5.6,1,6.6,5l0,0c2.4,9.5-12.4,18.5-13,18.9C15.3,27.1,15.2,27.1,15.1,27.1z M8.5,4.2
	c0,0-4.7,0.7-5.6,4.2c-2,8.1,10.2,16.3,12.2,17.6c2-1.3,14.2-9.5,12.2-17.6l0,0c-0.9-3.5-5.6-4.2-5.6-4.2c-2.9,0-5.1,2-6.2,3.1
	c-0.2,0.2-0.6,0.2-0.8,0C13.6,6.1,11.4,4.2,8.5,4.2z" />
                                </svg>
                                Favoritos
                              </a>
                            </li>
                            <li>
                              <a class="dropdown-item menu-chat__item" href="#" onclick="copiarLinkChat()">
                                <svg viewBox="0 0 30 30" width="30" height="30">
                                  <path class="st0" d="M16.5,18.1h-2.4c-0.2,0-0.4-0.2-0.4-0.4c0-0.2,0.2-0.4,0.4-0.4h2.4c0.5,0,0.9-0.5,0.9-1.1V8.1
	c0-0.6-0.4-1.1-0.9-1.1H3.7C3.2,7,2.8,7.5,2.8,8.1v8.1c0,0.6,0.4,1.1,0.9,1.1h7.2c0.2,0,0.4,0.2,0.4,0.4c0,0.2-0.2,0.4-0.4,0.4H3.7
	c-0.9,0-1.7-0.9-1.7-1.9V8.1C2,7,2.7,6.2,3.7,6.2h12.8c0.9,0,1.7,0.9,1.7,1.9v8.1C18.1,17.3,17.4,18.1,16.5,18.1z" />
                                  <path class="st0" d="M26.6,24.2H13.8c-0.9,0-1.7-0.9-1.7-1.9v-8.1c0-1.1,0.8-1.9,1.7-1.9H16c0.2,0,0.4,0.2,0.4,0.4s-0.2,0.4-0.4,0.4
	h-2.2c-0.5,0-0.9,0.5-0.9,1.1v8.1c0,0.6,0.4,1.1,0.9,1.1h12.8c0.5,0,0.9-0.5,0.9-1.1v-8.1c0-0.6-0.4-1.1-0.9-1.1h-7.1
	c-0.2,0-0.4-0.2-0.4-0.4s0.2-0.4,0.4-0.4h7.1c0.9,0,1.7,0.9,1.7,1.9v8.1C28.3,23.4,27.5,24.2,26.6,24.2z" />
                                </svg>
                                Enlace para copiar elemento
                              </a>
                            </li>
                            <li>
                              <a class="dropdown-item menu-chat__item" href="#" onclick="eliminarChat()">
                                <svg viewBox="0 0 30 30" width="30" height="30">
                                  <g>
                                    <path class="st0" d="M24.2,2.4H20V2c0-0.8-0.7-1.5-1.5-1.5h-5.9c-0.8,0-1.5,0.7-1.5,1.5v0.4H6.9c-1.3,0-2.4,1-2.4,2.2v2.1
		C4.4,6.9,4.6,7,4.8,7h0.7v20c0,1.2,1,2.2,2.2,2.2h15.7c1.2,0,2.2-1,2.2-2.2V7h0.7c0.2,0,0.4-0.2,0.4-0.4V4.5
		C26.6,3.3,25.5,2.4,24.2,2.4z M11.9,2c0-0.4,0.3-0.7,0.7-0.7h5.9c0.4,0,0.7,0.3,0.7,0.7v0.3h-7.3V2z M24.8,27
		c0,0.8-0.6,1.4-1.4,1.4H7.7c-0.8,0-1.4-0.6-1.4-1.4V7h18.5L24.8,27L24.8,27z M25.9,6.3H5.2V4.5c0-0.8,0.7-1.4,1.7-1.4h17.3
		c0.9,0,1.6,0.6,1.6,1.4V6.3z" />
                                    <path class="st0" d="M10.9,24.4c0.2,0,0.4-0.2,0.4-0.4V11.1c0-0.2-0.2-0.4-0.4-0.4s-0.4,0.2-0.4,0.4V24
		C10.6,24.2,10.7,24.4,10.9,24.4z" />
                                    <path class="st0" d="M15.7,24.4c0.2,0,0.4-0.2,0.4-0.4V11.1c0-0.2-0.2-0.4-0.4-0.4s-0.4,0.2-0.4,0.4V24
		C15.3,24.2,15.4,24.4,15.7,24.4z" />
                                    <path class="st0" d="M20.4,24.4c0.2,0,0.4-0.2,0.4-0.4V11.1c0-0.2-0.2-0.4-0.4-0.4S20,10.9,20,11.1V24C20,24.2,20.2,24.4,20.4,24.4
		z" />
                                  </g>
                                </svg>
                                Eliminar
                              </a>
                            </li>
                          </ul>
                        </span>
                      </li>
                    </ol>
                  </nav>
                </div>
              </div>
              <br>

              <div id="VentanaActualizaciones" class="ventanas">
                <div class="row">
                  <div class="col-md-12">
                    <span id="nuevosArchivos"></span>
                    <input type="text" name="txtActualizacion" id="txtActualizacion" class="form-control" placeholder="Escribir una actualización..." value="">
                  </div>
                </div>
                <div class="row mt-2" id="botonesNuevaActualizacion" style="display: none;">
                  <div class="col-md-12 d-flex justify-content-around">
                    <button type="button" class="btn-custom btn-custom--border-blue btnCancelarActualizacion" name="btnCancelarActualizacion" id="btnCancelarActualizacion">Cancelar</button>
                    <input type="file" name="file-2" id="file-2" class="inputfile inputfile-2" data-multiple-caption="{count} archivos seleccionados" multiple />
                    <label for="file-2" class="no-margin" id="file-chat">
                      <span class="iborrainputfile ajuste">Archivos</span>
                    </label>
                    <button type="button" class="btn-custom btn-custom--blue" name="btnAgregarActualizacion" id="btnAgregarActualizacion">Agregar</button>
                  </div>
                </div>

                <br>

                <div id="NuevasActualizaciones">


                </div>
              </div>

              <div id="VentanaActividad" class="ventanas">

              </div>
              <div id="VentanaFavoritos" class="ventanas" style="display: none;">
              </div>


              <!-- AQUI FINALIZA EL CAMBIO DE MENU--->

            </div>
            <div id="chatIndividual" class="ventanas">

            </div>
          </div>
          <!--/.Content-->
        </div>
      </div>
    </div>
    <!-- Central Modal Medium Success-->
    <!-- ********************************************************************************************************************* -->
    <!-- ADDING MEMBERS -->
    <div class="modal fade agregarMiembros" id="centralModalSuccess" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-notify modal-success gestionarIntegrantes modal-lg" role="document">
        <!--Content-->
        <div class="modal-content">
          <!--Header-->
          <div class="modal-header">
            <p class="heading lead">Actualizar integrantes del proyecto</p>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -10px; margin-right: 1rem;">
              <span aria-hidden="true" class="white-text">x</span>
            </button>
          </div>

          <!--Body-->
          <div class="modal-body">
            <div class="row">
              <div id="mostrarEquipos" class="col-md-6 col-xs-12">
              </div>
              <div id="mostrarIntegrantes" class="col-md-6 col-xs-12">
              </div>
            </div>
          </div>

        </div>
        <!--/.Content-->
      </div>
    </div>
    <!--  ADDING MEMBERS FINAL  -->
    <!-- ********************************************************************************************************************* -->
    <!-- SHARE UPDATES -->
    <div class="modal fade agregarMiembros" id="centralCompartirActualizacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-notify modal-success" role="document">
        <!--Content-->
        <div class="modal-content">
          <!--Header-->
          <div class="modal-header">
            <p class="heading lead">Compartir actualización</p>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -10px; margin-right: 1rem;">
              <span aria-hidden="true" class="white-text">x</span>
            </button>
          </div>

          <!--Body-->
          <div class="modal-body">
            <form class="form-inline active-cyan-3 active-cyan-4">
              <div class="col-md-12 text-center">
                <label for="Email"><i class="far fa-envelope" style="margin-right: 5px;"></i> Correos
                  electrónicos:</label>
                <input type='text' id='enviarMails' name='enviarMails' class='form-control' value=''>
              </div>
              <br><br>
              <div class="col-md-12 text-center" style="margin-top: 15px;">
                <img src="../../../img/chat/loading.gif" id="loading" width="30px" style="position: absolute; display: none;" />
                <input type="hidden" id="idEnviar" value="">
                <button type="button" id="btnEnviarActualizacion" class="btn btn-primary btn-sm rounded" style="float: right;">Enviar</button>
              </div>
            </form>
          </div>

        </div>
        <!--/.Content-->
      </div>
    </div>
    <!--  SHARE UPDATES  -->
    <!-- FIN MODALES DEL CHAT -->
    <!-- Central Modal Medium -->
    <div class="modal fade right" id="fluidModalRightSuccess" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-full-height modal-right modal-notify modal-success" role="document">
        <!--Content-->
        <div class="modal-content">
          <!--Header-->
          <div class="modal-header">
            <p class="heading lead" id="tituloChat">Tarea</p>

            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -10px; margin-right: 1rem;">
              <span aria-hidden="true" class="white-text">x</span>
            </button>

          </div>

          <!--Body-->
          <div class="modal-body">
            <div id="chatCompleto">
              <div class="row menu-superior-chat">
                <div class="col-md-12">
                  <nav class="menu">
                    <ol style="margin-bottom:0px;">
                      <li class="menu-item active">
                        <a href="#0" class="enlaceSub" onclick="verActualizaciones()">Actualizaciones</a>
                      </li>
                      <li class="menu-item">
                        <a href="#0" class="enlaceSub" onclick="verActividad()">Actividad</a>
                      </li>
                      <li class="menu-item">
                        <a href="#" data-toggle="modal" data-target="#centralModalSuccess" onclick="mostrarEquipos()">
                          <span data-toggle="tooltip" class="tooltip-chat" title="<?= $nombreusuario ?>" style="position: relative; bottom: 4px;"><img src="../../../img/chat/user-add.svg" class="user-img img-responsive" width="35px"></span>
                        </a>
                      </li>
                      <li class="menu-item">
                        <span><button type="button" id="botonDesplegable" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-h"></i></button>
                          <ul class="dropdown-menu dropdown-menu-right ellipsis py-2 collapse-inner rounded sin-flecha" role="menu" aria-labelledby="dropdownMenu">
                            <li><a class="dropdown-item" href="#" data-toggle="modal" data-target="#centralModalSuccess" onclick="mostrarEquipos()"><img src="../../../img/chat/administrar_usuarios.svg" width="18px" class="img-responsive" style="position: relative; right: 5px;bottom: 3px;" /> Administrar integrantes</a>
                            </li>
                            <li><a class="dropdown-item" href="#" onclick="verFavoritos()"><img src="../../../img/chat/favorito.svg" width="18px" class="img-responsive" style="position: relative; right: 4px;bottom: 3px;" /> Favoritos</a></li>
                            <!--<li class="dropdown-submenu pull-left">
                        <a class="dropdown-item" href="#"><img src="../../img/chat/preferencias_correo.svg" width="18px" class="img-responsive" style="position: relative; right: 4px; bottom: 1px;"/> Preferencias de correo electrónico <img src="../../img/chat/flecha_derecha.svg" width="18px" class="img-responsive" style="position: relative; right: 4px;"/></a>
                          <ul class="dropdown-menu dropdown-menu-instantaneo">
                            <li><div class="espacio-icon"><a class="dropdown-item" href="#"><img src="../../img/chat/preferencia_correo_electronico.svg" width="18px" class="img-responsive" style="position: relative; right: 4px; bottom: 1px;"/> Correo electrónico instantáneo</a></div></li>
                            <li><div class="espacio-icon"><a class="dropdown-item" href="#">Correo electrónico diario</a></div></li>
                            <li><div class="espacio-icon"><a class="dropdown-item" href="#">Sin correo electrónico</a></div></li>
                          </ul>
                      </li>-->
                            <li><a class="dropdown-item" href="#" onclick="copiarLinkChat()"><img src="../../../img/chat/copiar_enlace.svg" width="18px" class="img-responsive" style="position: relative; right: 4px; bottom: 2px;" /> Enlace para copiar
                                elemento</a></li>
                            <!--<li><a class="dropdown-item" href="#"><img src="../../img/chat/archivar.svg" width="18px" class="img-responsive" style="position: relative; right: 4px; bottom: 3px;"/> Archivar</a></li>-->
                            <li><a class="dropdown-item" href="#" onclick="eliminarChat()"><img src="../../../img/chat/eliminar.svg" width="18px" class="img-responsive" style="position: relative; right: 4px; bottom: 1px;" /> Eliminar</a></li>
                          </ul>
                        </span>
                      </li>
                    </ol>
                  </nav>
                </div>
              </div>
              <br>

              <div id="VentanaActualizaciones" class="ventanas">
                <div class="row">
                  <div class="col-md-12">
                    <span id="nuevosArchivos"></span>
                    <input type="text" name="txtActualizacion" id="txtActualizacion" class="form-control" placeholder="Escribir una actualización..." value="">
                  </div>
                </div>
                <div class="row mt-2" id="botonesNuevaActualizacion" style="display: none;">
                  <span class="col-md-4">
                    <button type="button" class="btn-custom btn-custom--border-blue btn-chat btnCancelarActualizacion" name="btnCancelarActualizacion" id="btnCancelarActualizacion">Cancelar</button>
                    <span class="col-md-4">
                      <input type="file" name="file-2" id="file-2" class="inputfile inputfile-2" data-multiple-caption="{count} archivos seleccionados" multiple />
                      <label for="file-2" class="no-margin" id="file-chat">
                        <svg xmlns="http://www.w3.org/2000/svg" class="iborrainputfile" width="20" height="17" viewBox="0 0 20 17">
                          <path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z">
                          </path>
                        </svg>
                        <span class="iborrainputfile">Agregar archivos</span>
                      </label>
                    </span>
                    <span class="col-md-4">
                      <button type="button" class="btn-custom btn-custom--blue btn-chat" name="btnAgregarActualizacion" id="btnAgregarActualizacion">Agregar</button>
                    </span>
                </div>

                <br>

                <div id="NuevasActualizaciones">


                </div>
              </div>

              <div id="VentanaActividad" class="ventanas">

              </div>
              <div id="VentanaFavoritos" class="ventanas" style="display: none;">
              </div>


              <!-- AQUI FINALIZA EL CAMBIO DE MENU--->

            </div>
            <div id="chatIndividual" class="ventanas">

            </div>
          </div>
          <!--/.Content-->
        </div>
      </div>
    </div>
    <!-- Central Modal Medium Success-->
    <!-- ********************************************************************************************************************* -->
    <!-- ADDING MEMBERS -->
    <div class="modal fade agregarMiembros" id="centralModalSuccess" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-notify modal-success" role="document">
        <!--Content-->
        <div class="modal-content">
          <!--Header-->
          <div class="modal-header">
            <p class="heading lead">Actualizar integrantes del proyecto</p>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -10px; margin-right: 1rem;">
              <span aria-hidden="true" class="white-text">x</span>
            </button>
          </div>

          <!--Body-->
          <div class="modal-body">

            <div id="mostrarEquipos">
            </div>

          </div>

        </div>
        <!--/.Content-->
      </div>
    </div>
    <!--  ADDING MEMBERS FINAL  -->
    <!-- ********************************************************************************************************************* -->
    <!-- SHARE UPDATES -->
    <div class="modal fade agregarMiembros" id="centralCompartirActualizacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-notify modal-success" role="document">
        <!--Content-->
        <div class="modal-content">
          <!--Header-->
          <div class="modal-header">
            <p class="heading lead">Compartir actualización</p>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -10px; margin-right: 1rem;">
              <span aria-hidden="true" class="white-text">x</span>
            </button>
          </div>

          <!--Body-->
          <div class="modal-body">
            <form class="form-inline active-cyan-3 active-cyan-4">
              <div class="col-md-12 text-center">
                <label for="Email"><i class="far fa-envelope" style="margin-right: 5px;"></i> Correos
                  electrónicos:</label>
                <input type='text' id='enviarMails' name='enviarMails' class='form-control' value=''>
              </div>
              <br><br>
              <div class="col-md-12 text-center" style="margin-top: 15px;">
                <img src="../../../img/chat/loading.gif" id="loading" width="30px" style="position: absolute; display: none;" />
                <input type="hidden" id="idEnviar" value="">
                <button type="button" id="btnEnviarActualizacion" class="btn btn-primary btn-sm rounded" style="float: right;">Enviar</button>
              </div>
            </form>
          </div>

        </div>
        <!--/.Content-->
      </div>
    </div>
    <!--  SHARE UPDATES  -->

    <!-- VER USUARIOS DE VISTO  -->
    <div class="modal fade verUsuariosVisto" id="verUsuariosVisto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-notify modal-success" role="document">
        <!--Content-->
        <div class="modal-content">
          <!--Header-->
          <div class="modal-header">
            <p class="heading lead">Visto por</p>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -10px; margin-right: 1rem;">
              <span aria-hidden="true" class="white-text">x</span>
            </button>
          </div>

          <!--Body-->
          <div class="modal-body">
            <div id="verUsuarios">
            </div>
          </div>

        </div>
        <!--/.Content-->
      </div>
    </div>

    <input type="hidden" id="idEncargado" name="idEncargado" value="-1">
    <input type="hidden" id="claveEncargado" name="claveEncargado" value="-1">

    <!-- FIN MODALES DEL CHAT -->
  </div>

  <div class="modal fade" id="modalTextElement" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="padding: 1rem 1rem; font-family: unset;">
          <h5 class="modal-title tittle-text-task" id="exampleModalLabel"></h5>
          <button type="button" class="close btn-close-text-element" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <div class="modal-body">
          <textarea id="textAreaElement" cols="30" rows="10" maxlength="150" style="width: 100%;" placeholder="Agrega notas a la tarea (150 caracteres máximo.)">

          </textarea>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal" style="height: 41px;">Cerrar</button>
          <button id="edit-text-task-element" type="button" class="btn btn-primary" style="height: 41px;background-color: #15589b;">Guardar</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalColumnsElement" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 1150px;">
      <div class="modal-content">
        <div class="modal-header" style="padding: 1rem 2rem 1rem 1rem;font-family: unset;">
          <h4 class="modal-title white-text" style="color: #1c4587;">Centro de columnas</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="white-text">x</span>
          </button>
        </div>
        <div id="list-all-columns" class="modal-body">
        </div>
        <!-- <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal" style="height: 41px;">Cerrar</button>
          <button id="edit-text-task-element" type="button" class="btn btn-primary" style="height: 41px;background-color: #15589b;">Guardar</button>
        </div> -->
      </div>
    </div>
  </div>


  <div class="modal fade right" id="uploadBar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-notify modal-success" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <center>Subiendo archivo</center>
          <div id="progress-wrp">
            <div class="progress-bar"></div>
            <div class="status">0%</div>
          </div>

        </div>
      </div>
    </div>
  </div>

  <?php if ($encargadoValido) { ?>
    <!--UPDATE MODAL DENTRO DE PROYECTOS 04/09/2020-->
    <div class="modal fade right" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
        <div class="modal-content">
          <form action="../../proyectos/functions/editar_Proyecto.php" method="POST">
            <input type="hidden" name="idProyectoU" id="idProyectoU" value="<?= $id ?>">
            <div class="modal-header">
              <h4 class="modal-title w-100" id="myModalLabel">Editar proyecto</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">x</span>
              </button>
            </div>
            <div class="modal-body">

              <div class="form-group">
                <label for="usr">Nombre del proyecto:</label>
                <input type="text" class="form-control alpha-only" value="" maxlength="40" name="txtProyectoU" id="txtProyectoU">
                <div class="invalid-feedback" id="invalid-nombreProyU">El proyecto debe tener un nombre.</div>
              </div>

              <div class="form-group">
                <label for="usr">Descripción del proyecto:</label>
                <textarea id="txtDescripcionU" class="form-control alpha-only" maxlength="140" name="txtDescripcionU" rows="4"></textarea>
              </div>

              <div class="form-group">
                <label for="usr">Encargado del proyecto:</label>
                <select name="cmbIdUsuarioU[]" id="cmbIdUsuarioU" class="form-control" required multiple>
                  <!-- <option value="" disabled selected hidden>Seleccione un encargado</option> -->
                </select>
                <div class="invalid-feedback" id="invalid-encargadoProy">El proyecto debe tener un encargado.</div>
              </div>
              <div class="form-group">
                <label for="usr">Equipos participantes en el proyecto:</label>
                <select name="cmbIdEquipoU[]" id="multipleU" multiple>
                </select>
              </div>
              <!-- <div class="form-group">
              <label for="usr">Integrantes participantes en el proyecto:</label>
              <select name="cmbIntegrantesU[]" id="multipleU2" multiple>
              </select>
            </div> -->
              <div class="form-group">
                <label for="usr">Usuarios participantes en el proyecto:</label>
                <select name="cmbUsuarios[]" id="multipleU2" multiple>
                </select>
              </div>
              <div class="form-group">
                <label for="usr">Empleados participantes en el proyecto:</label>
                <select name="cmbEmpleados[]" id="multipleU4" multiple>
                </select>
              </div>
              <label style="color:#006dd9;font-size: 13px;"> Nota: Los integrantes que pertenezcan a un equipo no
                apareceran como integrantes individuales</label>
            </div>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btn-custom btn-custom--blue btn-custom--small" name="btnEliminarProyecto" id="btnEliminarProyecto">
                Eliminar
              </button>
              <button type="button" class="btn-custom btn-custom--border-blue btn-custom--small" data-dismiss="modal" id="btnCancelarActualizacion">
                Cancelar
              </button>
              <button type="submit" class="btn-custom btn-custom--blue btn-custom--small" name="btnEditar" id="btnEditar">
                Guardar
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--END UPDATE MODAL DENTRO DE PROYECTOS-->
  <?php } ?>

  <script src="../../../js/sb-admin-2.min.js"></script>
  <script src="https://cdn.tiny.cloud/1/71cqp1c8th0ba6wnq8061gvupmzmb8dowc7hv19wqb00mnw3/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
  <!--<link href="../../../css/slimselect.min.css" rel="stylesheet">
  <script src="../../../js/slimselect_administrarusuarios.js"></script>-->
  <script src="../../../js/chat/multiple-emails.js"></script>
  <script src="../../../js/chat/jquery.sumoselect.js"></script>
  <span id="copyp1" style="display: block;"></span>

  <script>
    new SlimSelect({
      select: '#multipleU',
      //placeholder: 'Seleccionar equipos'
      deselectLabel: '<span class="">✖</span>'
    });

    new SlimSelect({
      select: '#multipleU2',
      //placeholder: 'Seleccionar equipos'
      deselectLabel: '<span class="">✖</span>'
    });
    new SlimSelect({
      select: '#multipleU4',
      //placeholder: 'Seleccionar equipos'
      deselectLabel: '<span class="">✖</span>'
    });
    var selectIdUsuarioU = new SlimSelect({
      select: '#cmbIdUsuarioU',
      deselectLabel: '<span class="">✖</span>'
    });

    function obtenerIdProyectoEditar(id) {
      //document.getElementById('idProyectoU').value = id;
      //document.getElementById('idProyectoD').value = id;
      var id = "id=" + id;

      $.ajax({
        type: 'POST',
        url: '../../proyectos/functions/getProyecto.php',
        data: id,
        success: function(r) {
          var datos = JSON.parse(r);

          $("#txtProyectoU").val(datos.html);
          $("#txtDescripcionU").val(datos.htmlDescripcion);
          $("#cmbIdUsuarioU").html(datos.html2);
          $("#multipleU").html(datos.html3);
          $("#multipleU2").html(datos.html4);
          $("#multipleU4").html(datos.html5);
          $("#btntimdeskProyecto").attr("href", "../tareas/timDesk/index.php?" + id);
          //$("#idProyectoU").attr("value", id );
        }
      });
    }
  </script>

  <script>
    //Variables globales
    var urlOrigin = window.location.origin;
    var urlPath = window.location.pathname.split("/")[1];
    var urlGlobal = urlOrigin + "/" + urlPath + "/js/chat/";
    var urlDireccionamiento = urlOrigin + "/" + urlPath + "/catalogos/tareas/timDesk/";
    var tinymceurl = urlOrigin + "/" + urlPath + "/lib/tinymce/";
    var IDUsuario = <?php echo $_SESSION["PKUsuario"]; ?>;
    var nombreusuario = "<?php echo $nombreusuario; ?>";
    var IDTareaChat;
    var idChatIndividual = <?php echo $idIndividual; ?>;
    var idChatTarea = <?php echo $idTareaIndividual; ?>;
    var idProyectoUrl = <?php echo $id; ?>;
    var pagina = "tim";
    var ruta = "../";
    var rutare = "../../../";
    var post_max_size_limite = "<?php echo $post_max_size_limite; ?>";
    var post_max_size = <?php echo $post_max_size; ?>;
    var permisoGeneral = <?php echo $permisoGeneral; ?>;
  </script>
  <script src="../../../js/chat/chat.js"></script>
  <script src="../../../js/revisarestado.js"></script>
  <script src="../../../js/build/js/utils.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
  <script src="../../../js/sweet/sweetalert2.js"></script>
  <!-- <script src="https://unpkg.com/@popperjs/core@2"></script> -->
  <script src="../../../js/picker/dist/bcp.js"></script>
  <script src="../../../js/picker/dist/bcp.en.js"></script>
  <script src="../../../js/slimselect.min.js"></script>
  <script src="../../../js/flatpickr/dist/flatpickr.js"></script>
  <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.26.0/slimselect.min.js"></script>-->
  <script src="js/alertify.min.js"></script>
  <script src="../../../js/lobibox.min.js"></script>
  <script src="../../../js/notifications.js"></script>
  <script src="../../../js/messageboxes.js"></script>
  <script src="../../../js/build/js/intlTelInput.js"></script>

  <script src="js/menuDespegable.js"></script>
  <script src="js/script.js"></script>
  <script src="js/numeros.js"></script>
  <script src="js/estados.js"></script>
  <script src="js/verificar.js"></script>

  <script src="js/subtask.js"></script>
  <script src="js/mytask.js"></script>
  <script src="js/sub-verificar-progreso.js"></script>
  <script>
    lite = "php/funciones.php";
    lite_lock = 0;
    $('[data-toggle="proyectosTip"]').tooltip();
    // Animate loader off screen
    //$(".se-pre-con").fadeOut(3000);
    $('#boardContent').fadeIn("fast");
    /* $(".goaway").fadeOut(3000, function() {
    }); */


    $('.listaColumnas').hide();
    $("#menu-toggle").click(function(e) {
      e.preventDefault();
      if ($('#menu-toggle i').hasClass('fa-arrow-left')) {
        $('#menu-toggle i').removeClass('fa-arrow-left');
        $('#menu-toggle i').addClass('fa-arrow-right');
      } else {
        $('#menu-toggle i').removeClass('fa-arrow-right');
        $('#menu-toggle i').addClass('fa-arrow-left');
      }
      $("#wrapper").toggleClass("toggled");
    });

    function call_project(id) {
      $.ajax({
        url: "php/funciones.php",
        data: {
          clase: "admin_data",
          funcion: "getProject",
          id: id
        },
        dataType: "json",
        success: function(resp) {
          idProyecto = id;
          let enter = "enter";
          let doit = "doit";
          var activo = "";

          //console.log(resp)
          if (resp[0].permiso == 1) {
            activo = "";
          } else {
            activo = "disabled";
          }

          //console.log(resp)
          $('.proyecto').append('<div class="pos-rel" style="width:100%;">' +
            '<span class="pos-abs project-tip-content d-no"></span>' +
            '<input id="project-number-' + id +
            '" type="text" class="h2 project-name-dots" style="width:50%;background:transparent;color: #1c4587;height:45px; margin-bottom: 0 !important;" onkeydown="edit_project(' +
            resp[0].PKProyecto + ',' + caracter2 + enter + caracter2 + ')" onfocusout="edit_project(' + resp[0]
            .PKProyecto + ',' + caracter2 + doit + caracter2 + ')" onmouseenter="show_tip_project(' + id +
            ')" onmouseleave="hide_tip_project(' + id + ')" placeholder="Hasta 25 caracteres" value="' + resp[0]
            .Proyecto + '" ' + activo + '></div>')
          wageProject = document.getElementById("project-number-" + idProyecto);
          wageProject.addEventListener("keydown", function(e) {
            if (e.keyCode === 13) {
              let name = $('#project-number-' + idProyecto).val();
              new_project_name(id, name)
            }
          })
          getLevels(resp[0].PKProyecto); //Obtener etapas
        },
        error: function(error) {
          console.log(error);
        }

      });
    }

    function get_columns_type(id_project) {
      $.ajax({
        url: "php/funciones.php",
        data: {
          clase: "get_data",
          funcion: "get_columns_type"
        },
        dataType: "json",
        success: function(resp) {
          console.log("TIPOS DE COLUMNAS: ", resp);
          for (i = 0; i < 5; i++) {

            $('.listaColumnas').append('<div class="pd-20 columna-item" onclick="getColumn(' + resp[i]
              .PKTipoColumna +
              ',' + caracter2 + resp[i].Tabla + caracter2 +
              ')"><div class="text-left mr-30"><img class="f-bright" src="../../../img/timdesk/' + resp[i]
              .Logotipo + '" width="28px"></div><div><span class="fs-15 colorG">' + resp[i].Nombre +
              '</span></div></div>');

          }

          resp2 = resp.reverse(); //Ordenar en reversa el array

          $.each(resp2, function() {

            $('#list-all-columns').append('<div id="column-type-' + this.PKTipoColumna +
              '" class="info-column"><div class="d-flex" style="align-items:center;padding:15px;"><div class="img-column" style="background:' +
              this.Background + '"><img class="logo-column" src="../../../img/timdesk/' + this.Logotipo +
              '"></div></div><div class="description-column"><div><h5>' + this.Nombre + '</h5></div><div>' +
              this.Descripcion + '</div><div id="add-type-' + this.PKTipoColumna +
              '" class="cursorPointer add-to-project " onclick="getColumnM(' + this.PKTipoColumna + ',' +
              caracter2 + this.Tabla + caracter2 + ')">Agregar al proyecto</div></div></div>')

          });

          $('.listaColumnas').append(
            '<div class="columns_modal cursorPointer" style="color:white" data-toggle="modal" data-target="#modalColumnsElement" onclick="check_columns(' +
            id_project + ')">Más columnas</div>')
        },
        error: function(error) {
          console.log(error);
        }
      })
    }


    //   $('.agregarColumna').click(function() {
    //     varContainer = ".listaColumnas";
    //     var tieneClase = $('.agregarColumna i').hasClass('plus-icon');
    //     if (true) {
    //       $('.agregarColumna i').removeClass('plus-icon');
    //       $('.agregarColumna i').addClass('close-icon');
    //       $('.listaColumnas').show();
    //     } else {
    //       $('.agregarColumna i').removeClass('close-icon');
    //       $('.agregarColumna i').addClass('plus-icon');
    //       $('.listaColumnas').hide();
    //     }

    //     container = $(".agregarColumna");
    //     hide = $(".listaColumnas");
    //     oCaracter = tieneClase;

    //   });

    $('#abrirEtapas').mouseenter(function() {
      $('[data-toggle="taskTip"]').tooltip();
    });

    //   $('.agregarColumna').mouseenter(function() {
    //     $('[data-toggle="columnTip"]').tooltip()
    //   })

    $('#MisTareas i').mouseenter(function() {
      $('[data-toggle="mytaskTip"]').tooltip();
      $('.all-tareas').attr("data-original-title", $('.mis-tareas').attr("data-original-title") ==
        'Ver mis tareas' ?
        "Ver mis tareas" : "Ver todas las tareas");
    });

    var seleccionProyecto = new SlimSelect({
      select: '#projects-List',
      placeholder: 'Seleccione un proyecto',
      searchPlaceholder: 'Buscar proyecto',
      beforeOpen: function() {
        //console.log('afterOpen')
      },
      onChange: (info) => {
        $('.proyecto input').val();
        //$('.goaway').remove();
        $('#boardContent').remove();
        idProyecto = info.value;
        idProyectoUrl = info.value;
        $('#txtIdProject').val(info.value);
        $('.proyecto input').val(info.text);
        $('.proyecto h2').append(info.text);

        $('#page-content-wrapper').append('<div class="goaway"><div class="se-pre-pro"></div></div>');

        //goAway();
        setTimeout(function() {
          arrColumnas = [];
          indexArray = [];
          columnasArray = [];
          numTareas = [];
          tablas = [];
          numTask = 0; //Número que se le asignará cuando se agreguen nuevas tareas.
          //picker=0;
          getLevels(info.value); //Obtener etapas
          getDrag(); //Función que hará las etapas sorteables.
        }, 2050);
        $('#div-projects-list').hide();
        $('#boardContent').fadeIn("fast");
      }
    });


    $(document).on('click', '.logo-views-title', function() {
      if ($('.logo-views-menus').toggle() == false) {
        $('.logo-views-menus').css('display', 'block');
      }
    });
    $(document).ready(function() {
      $('body').click(function() {
        if ($('.logo-views-menus').is(':visible')) {
          $('.logo-views-menus').css('display', 'none');
        }
      });
    });

    $(document).on('click', '#goScrum', function() {
      var id = $('#txtIdProject').val();
      window.location.href = "../../pantalla_scrum/index.php?id=" + id;
    });

    $(document).on('click', '#goCalendar', function() {
      var id = $('#txtIdProject').val();
      window.location.href = "../../calendario_tareas/index.php?id=" + id;
    });


    call_project(<?= $id; ?>);
    get_columns_type(<?= $id; ?>);
    //loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
    //setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
    /*
      $(document).ready(function() {
        $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user=' + <? //=$_SESSION['PKUsuario'];
                                                                          ?> + '&ruta=' +
          '<? //=$ruta;
            ?>&ruteEdit=<? //=$ruteEdit;
                        ?>');
        setInterval(refrescar, 50000);

      });

      function refrescar() {
        $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user=' + <? //=$_SESSION['PKUsuario'];
                                                                          ?> + '&ruta=' +
          '<? //=$ruta;
            ?>&ruteEdit=<? //=$ruteEdit;
                        ?>');
      }
    */
    function alertNotification() { //
      console.log("alertanotificaciones");
      $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user=' + <?= $_SESSION['PKUsuario']; ?> + '&ruta=' +
        '<?= $ruta; ?>&ruteEdit=<?= $ruteEdit; ?>');
    }

    function show_tip_project(id) {
      let comprobar = $('#project-number-' + id).val();
      let contar = comprobar.length;
      if (contar >= 23) { //Elementos tienen asignado el estado
        $('.project-tip-content').html(comprobar);
        $('.project-tip-content').removeClass('d-no');
        $('.project-tip-content').addClass('d-in-block');
      }
    }

    function hide_tip_project(id) {
      $('.project-tip-content').removeClass('d-in-block');
      $('.project-tip-content').addClass('d-no');
    }

    function upload_image() {
      var bar = $('#bar');
      var percent = $('#percent');
      $('#myForm').ajaxForm({
        beforeSubmit: function() {
          document.getElementById("progress_div").style.display = "block";
          var percentVal = '0%';
          bar.width(percentVal)
          percent.html(percentVal);
        },

        uploadProgress: function(event, position, total, percentComplete) {
          var percentVal = percentComplete + '%';
          bar.width(percentVal)
          percent.html(percentVal);
        },

        success: function() {
          var percentVal = '100%';
          bar.width(percentVal)
          percent.html(percentVal);
        },

        complete: function(xhr) {
          if (xhr.responseText) {
            document.getElementById("output_image").innerHTML = xhr.responseText;
          }
        }
      });
    }
  </script>

  <script src="../../../js/scripts.js"></script>
</body>

</html>
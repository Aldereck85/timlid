<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_SESSION["Usuario"])) {
    $user = $_SESSION["Usuario"];
    require_once '../../include/db-conn.php';

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $stmt = $conn->prepare('SELECT * FROM proyectos WHERE PKProyecto = :id');
        $stmt->execute(array(':id' => $id));
        $row = $stmt->fetch();
        $proyecto = $row['Proyecto'];

        $stmt = $conn->prepare('SELECT * FROM etapas WHERE FKProyecto = :id ORDER by PKEtapa ASC LIMIT 1');
        $stmt->execute(array(':id' => $id));
        $row = $stmt->fetch();
        if($row){
          $etapa = $row['Etapa'];
          $idEtapa = $row['PKEtapa'];
        }else{
          $etapa = "Etapa";
        }

    }

    $post_max_size_limite = ini_get('post_max_size');
    $post_max_size = intval($post_max_size_limite) * 1000000;

    /*Se agregar y definen los id para acceder al chat cuando se copian los enlaces.
        Se define el nombre de usuario.*/
    $stmt = $conn->prepare('SELECT u.Nombre as nombreusuario FROM usuarios as u WHERE u.id = :idusuario');
    $stmt->bindValue(':idusuario', $_SESSION["PKUsuario"]);
    $stmt->execute();
    $row = $stmt->fetchAll();
    $nombreusuario = $row[0]['nombreusuario'];

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
  <title>Timlid | Pantalla Scrum</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>



  <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"
    integrity="sha256-c4gVE6fn+JRKMRvqjoDp+tlG4laudNYrXI1GncbfAYY=" crossorigin="anonymous"></script>

  <!-- Page level plugins -->
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.responsive.js"></script>
  <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <script src="../../js/scrum_screen.js"></script>

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

  <!--
    <script src="../../js/mdb.min.js"></script>
    <link rel="stylesheet" href="../../css/mdb.min.css">
    -->
  <link rel="stylesheet" href="../../css/scrum_screen.css">
  <link href="../tareas/timDesk/style/css.css" rel="stylesheet" type="text/css">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <script src="../../js/slimselect.min.js"></script>
  <!-- CSS Y JS del chat -->
  <link href="../tareas/timDesk/style/chat.css" rel="stylesheet">
  <link href="../tareas/timDesk/style/css/alertify.min.css" rel="stylesheet">
  <link href="../tareas/timDesk/style/css/themes/default.css" rel="stylesheet">
  <script src="https://cdn.tiny.cloud/1/71cqp1c8th0ba6wnq8061gvupmzmb8dowc7hv19wqb00mnw3/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
  <script src="../tareas/timDesk/js/alertify.min.js"></script>
  <link rel="stylesheet" href="../../css/chat/file-input.css">
  <link href="../../css/chat/multiple-emails.css" rel="stylesheet">
  <!--<script src="../../js/slimselect_administrarusuarios.js"></script>-->
  <script src="../../js/chat/multiple-emails.js"></script>
  <script src="../../js/chat/jquery.sumoselect.js"></script>
  <script src="../../js/sweet/sweetalert2.js"></script>
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <script src="../../js/lobibox.min.js"></script>
  <link rel="stylesheet" href="../../css/chat/sumoselect.css">
  <!-- FIN CSS Y JS del chat -->
  <script src="../../js/Sortable.js"></script>
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
            </div>';
require_once '../topbar.php';
?>
        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!--
              <div id="alertas"></div>
            -->
          <!--<div class="card shadow mb-4">-->
          <!--<div class="card-header py-3">-->

          <div class="header-proyect">
            <div class="row">
              <div class="col-md-12">
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

                <div class="project-name">
                  <?=$proyecto;?>
                </div>

                <!--
                      <div class="button-add">
                        <a href="#" class="add-task" id="plus_task"><img src="../../img/scrum/agregar-tarea.svg" alt=""></a>
                      </div>
                      -->
                <div class="project-description">
                  &nbsp;
                </div>
              </div>
            </div>


          </div>

          <!--</div>-->
          <!--contenedor cuerpo scrum-->
          <div class="col-12 ctn-scrum">
            <div class="body-container-scrum" id="body-container-scrum">

              <input type="hidden" name="" id="txtIdProject" value="<?=$id;?>">
              <!--contenedor eventos-->

              <!--<div class="container-event">-->
              <div class="container-task" id="container-task">
                <span id="stage-tip-<?=$idEtapa;?>" class="pos-abs group-tip-content d-no"></span>
                <div class="title-stage-new">
                  <input type="text" id="title-stage-<?=$idEtapa;?>" class="title-stage" data-id1="<?=$idEtapa;?>"
                    contenteditable="true" onmouseenter="show_task_tip('<?=$idEtapa;?>')"
                    onmouseleave="group_tip_hidden('<?=$idEtapa;?>')" value="<?=$etapa;?>">
                </div>
                <div class="new_task" id="content-stage-task<?=$idEtapa;?>" data-id="<?=$idEtapa;?>"></div>
                <!--
                      <div class="content-stage-task" id="content-stage-task'+noEtapa+'" data-id="'+noEtapa+'">
                      -->
                <div class="button-add-newtask">
                  <a href="#" class="add-task" id="plus_task"><img src="../../img/scrum/agregar_tarea_gris.svg"
                      alt=""></a>
                </div>
              </div>
              <!-- </div>-->

              <div class="add-stage-new">
                <div class="button-add-stage">
                  <a href="#" id="add-task"><img src="../../img/scrum/agregar_tarea_gris.svg" alt=""></a>

                </div>
              </div>

            </div>
          </div>



        </div>
        <!--</div>-->
        <!--</div>-->
      </div>


      <!-- Footer -->
      <?php
$rutaf = "../";
require_once '../footer.php';
?>
      <!-- End of Footer -->


    </div>
  </div>

</body>


<!-- Central Modal Medium Info -->
<div class="modal fade right" id="viewTaskInfo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-full-height modal-notify modal-primary modal-right" role="document">
    <!--Content-->
    <div class="modal-content">
      <!--Header-->
      <div class="modal-header">
        <span class="col-md-7">
          <!-- <p class="heading-lead" id="headerModalTask" contenteditable="true"></p> -->
        </span>
        <!--
             <a href="#" class="chat-icon-modal" style=""><i class="far fa-comment"style="font-size:18px;margin:5px 5px 5px 5px;"></i></a>
            -->
        <span class="col-md-5">
          <a href="#" type="button" id="abrirChat" class="btn circle-white" style="margin-left: 50px;"><i
              class="far fa-comment" style="font-size:15px;margin:2px;"></i></a>
          <button type="button" class="close float-right" data-dismiss="modal" aria-label="Close"
            style="margin-top: -10px; margin-right: 1rem;">
            <span aria-hidden="true" class="white-text">&times;</span>
          </button>
        </span>

      </div>
      <?php
$stmt = $conn->prepare('SELECT Tipo FROM columnas_proyecto WHERE FKProyecto = :id');
$stmt->execute(array(':id' => $id));
$arrayColum = $stmt->fetchAll();
$contFecha = 0;
$contResponsable = 0;
$contEstado = 0;
foreach ($arrayColum as $r) {
    switch ($r['Tipo']) {
        case 1:
            $contResponsable++;
            break;
        case 2:
            $contEstado++;
            break;
        case 3:
            $contFecha++;
            break;
    }
}

?>
      <!--Body-->
      <div class="modal-body">
        <div class="text-center">
          <div class="row">
            <div class="col-lg-4">
              <img src="../../img/scrum/tareas_repetibles.svg" class="img-responsive" width="70px"
                style="position: relative; bottom: 15px;" />
            </div>
            <div class="col-lg-8" style="text-align: left !important;">
              <p class="heading-lead" id="headerModalTask" contenteditable="true"></p>
            </div>
          </div>
          <div class="dropdown agregarColumna" style="padding:0;">
            <button class="btn btn-secondary dropdown-toggle" type="button"
              style="height: auto;background-color: #15589b;padding: 4px 8px;" id="dropdownMenuButton"
              data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Agregar columna
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
              <a class="dropdown-item" href="#" id="addDate">Fecha</a>
              <a class="dropdown-item" href="#" id="addUser">Responsable</a>
              <a class="dropdown-item" href="#" id="addState">Estado</a>
            </div>
          </div>
          <br>
          <?php
/*
if($contFecha == 0 || $contResponsable == 0 || $contEstado == 0){
if($contFecha == 0){
 */
?>
          <!--
            <div class="row" style="margin-bottom: 15px;">
              <div class="col-lg-12">
                <a href="#" type="button" class="btn swal2-confirm" id="addDate"><span style="position: relative; top: -1px; margin-bottom: 10px;">Agregar fecha</span></a>
              </div>
            </div>
          -->
          <?php //}else if($contResponsable == 0){ ?>
          <!--
            <div class="row" style="margin-bottom: 15px;">
              <div class="col-lg-12">
                <a href="#" type="button" class="btn swal2-confirm" id="addUser"><span style="position: relative; top: -1px; margin-bottom: 10px;">Agregar responsable</span></a>
              </div>
            </div>
          -->
          <?php //}else{ ?>
          <!--
            <div class="row" style="margin-bottom: 15px;">
              <div class="col-lg-12">
                <a href="#" type="button" class="btn swal2-confirm" id="addState"><span style="position: relative; top: -1px; margin-bottom: 10px;">Agregar estado</span></a>
              </div>
            </div>
          -->
          <?php
//}
//}
?>
          <!--
            <div class="row">
              <div class="col-lg-4 label-fiels description-field">
                 <center><img src="../../img/scrum/tareas_repetibles.svg" class="img-responsive" width="70px" style="position: relative; bottom: 15px;" /></center>
              </div>
              <div class="col-lg-8">
              </div>
            </div>

           <div class="row" >
             <div class="col-lg-4">
               <label for="dropbtn-stage" style="margin-top:5px;">Etapa</label>
             </div>
             <div class="col-lg-8">
               <div class="dropdown-stage">
                 <button onclick="myFunctionStage()" class="dropbtn-stage"></button>
                  <div id="myDropdown-stage" class="dropdown-content-stage">
                    <?php
//$stmt = $conn->prepare('SELECT * FROM etapas WHERE FKProyecto = :id');
//$stmt->execute(array(':id'=>$id));
//while($row = $stmt->fetch()){
?>
                    <a href="#"><?//=$row['Etapa']; ?></a>
                  <?php //} ?>
                  </div>
                </div>
             </div>
           </div>
           <br>
          -->
          <div class="row modal-fields">
            <div class="col-lg-4 label-fiels description-field">
              <h6>Descripción</h6>
            </div>
            <div class="col-lg-8">
              <textarea class="form-control" rows="3" cols="25" placeholder="Agregue más detalles a esta tarea..."
                id="txaDescription" re></textarea>
            </div>
          </div>

          <?php

if ($contFecha > 0) {
    ?>
          <div class="row modal-fields">
            <div class="col-lg-4 label-fiels date-field">
              <h6 for="">Fecha</h6>
            </div>
            <div class="col-lg-8" id="date">
              <input class="form-control" type="date" name="txtFecha" id="txtDate">
            </div>
            <?php }?>
            <!--
            <div class="col-lg-8" id="repetible">
              <select class="form-control" name="cmbRepetible" id="cmbRepetible">
                <option value="">...</option>
                <option value="1">No se repite</option>
                <option value="2">Cada día</option>
                <option value="3">Cada semana</option>
                <option value="4">Cada 15 días</option>
                <option value="5">Cada mes</option>
                <option value="6">Cada 3 meses</option>
                <option value="7">Cada 6 meses</option>
                <option value="8">Cada año</option>
              </select>
            </div>
          -->
          </div>
          <?php
if ($contResponsable > 0) {
    ?>
          <div class="row modal-fields">
            <div class="col-lg-4 label-fiels user-field">
              <h6>Responsable</h6>
            </div>
            <div class="col-lg-8">
              <select class="form-control" name="cmbUser" id="cmbUser">
                <option value="">Seleccione un responsable...</option>
                <?php

$stmt = $conn->prepare('SELECT user.PKUser, CONCAT(emp.Nombres," ",emp.PrimerApellido," ",emp.SegundoApellido) AS employe FROM usuarios AS user
                                          INNER JOIN empleados AS emp ON user.FKEmpleado = emp.PKEmpleado');
    $stmt->execute();
    $row = $stmt->fetchAll();
    foreach ($row as $r) {
        ?>
                <option value="<?=$r['PKUser'];?>"><?=$r['employe'];?></option>
                =======
                $stmt = $conn->prepare('SELECT user.PKUsuario, CONCAT(emp.Nombres," ",emp.PrimerApellido,"
                ",emp.SegundoApellido) AS employe FROM usuarios AS user
                INNER JOIN empleados AS emp ON user.FKEmpleado = emp.PKEmpleado');
                $stmt->execute();
                $row = $stmt->fetchAll();
                foreach ($row as $r) {
                ?>
                <option value="<?=$r['PKUsuario']; ?>"><?=$r['employe']; ?></option>
                >>>>>>> d6e41e7bdcfb5d451f06fd1fc6b05b7c0f7eb745
                <?php
}
    ?>
              </select>
            </div>
          </div>
          <?php }?>
          <div class="row modal-fields">
            <div class="col-lg-4 label-fiels status-field">
              <h6>Estado</h6>
            </div>
            <div class="col-lg-8">
              <select class="form-control" name="cmbStatus" id="cmbStatus">
                <option value="">Seleccione un estatus...</option>
                <?php
$stmt = $conn->prepare('SELECT * FROM colores_columna WHERE FKProyecto = :id');
$stmt->execute(array(':id' => $id));
$row = $stmt->fetchAll();
foreach ($row as $r) {
    ?>
                <option value="<?=$r['PKColorColumna'];?>"><?=$r['nombre']?></option>
                <?php }?>
              </select>


            </div>

          </div>


        </div>
        <div class="modal-footer justify-content-center">
          <a href="#" type="button" class="btn swal2-cancel" data-dismiss="modal"><span
              style="position: relative; top: -1px;">Cancelar</span></a>
          <a href="#" type="button" class="btn swal2-confirm" id="save_dataTask"><span
              style="position: relative; top: -1px;">Guardar</span></a>
        </div>
      </div>



    </div>

    <!--/.Content-->
  </div>

</div>
<!-- Central Modal Medium Info-->
</div>

<!-- MODALES DEL CHAT -->

<!-- Central Modal Medium -->
<div class="modal fade right" id="fluidModalRightSuccess" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-full-height modal-right modal-notify modal-success" role="document">
    <!--Content-->
    <div class="modal-content">
      <!--Header-->
      <div class="modal-header">
        <p class="heading lead" id="tituloChat">Tarea</p>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
          style="margin-top: -10px; margin-right: 1rem;">
          <span aria-hidden="true" class="white-text">&times;</span>
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
                      <span data-toggle="tooltip" class="tooltip-chat" title="<?=$nombreusuario?>"
                        style="position: relative; bottom: 4px;"><img src="../../img/chat/user-add.svg"
                          class="user-img img-responsive" width="35px"></span>
                    </a>
                  </li>
                  <li class="menu-item">
                    <span><button type="button" id="botonDesplegable" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false"><i class="fas fa-ellipsis-h"></i></button>
                      <ul class="dropdown-menu dropdown-menu-right ellipsis py-2 collapse-inner rounded sin-flecha"
                        role="menu" aria-labelledby="dropdownMenu">
                        <li><a class="dropdown-item" href="#" onclick="mostrarEquipos()"><img
                              src="../../img/chat/administrar_usuarios.svg" width="18px" class="img-responsive"
                              style="position: relative; right: 5px;bottom: 3px;" /> Administrar integrantes</a></li>
                        <li><a class="dropdown-item" href="#" onclick="verFavoritos()"><img
                              src="../../img/chat/favorito.svg" width="18px" class="img-responsive"
                              style="position: relative; right: 4px;bottom: 3px;" /> Favoritos</a></li>
                        <!--<li class="dropdown-submenu pull-left">
                                      <a class="dropdown-item" href="#"><img src="../../img/chat/preferencias_correo.svg" width="18px" class="img-responsive" style="position: relative; right: 4px; bottom: 1px;"/> Preferencias de correo electrónico <img src="../../img/chat/flecha_derecha.svg" width="18px" class="img-responsive" style="position: relative; right: 4px;"/></a>
                                        <ul class="dropdown-menu dropdown-menu-instantaneo">
                                          <li><div class="espacio-icon"><a class="dropdown-item" href="#"><img src="../../img/chat/preferencia_correo_electronico.svg" width="18px" class="img-responsive" style="position: relative; right: 4px; bottom: 1px;"/> Correo electrónico instantáneo</a></div></li>
                                          <li><div class="espacio-icon"><a class="dropdown-item" href="#">Correo electrónico diario</a></div></li>
                                          <li><div class="espacio-icon"><a class="dropdown-item" href="#">Sin correo electrónico</a></div></li>
                                        </ul>
                                    </li>-->
                        <li><a class="dropdown-item" href="#" onclick="copiarLinkChat()"><img
                              src="../../img/chat/copiar_enlace.svg" width="18px" class="img-responsive"
                              style="position: relative; right: 4px; bottom: 2px;" /> Enlace para copiar elemento</a>
                        </li>
                        <!--<li><a class="dropdown-item" href="#"><img src="../../img/chat/archivar.svg" width="18px" class="img-responsive" style="position: relative; right: 4px; bottom: 3px;"/> Archivar</a></li>-->
                        <li><a class="dropdown-item" href="#" onclick="eliminarChat()"><img
                              src="../../img/chat/eliminar.svg" width="18px" class="img-responsive"
                              style="position: relative; right: 4px; bottom: 1px;" /> Eliminar</a></li>
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
                <input type="text" name="txtActualizacion" id="txtActualizacion" class="form-control"
                  placeholder="Escribir una actualización..." value="">
              </div>
            </div>
            <div class="row" id="botonesNuevaActualizacion" style="display: none;">
              <div class="col-md-12">
                <center>
                  <button type="button" class="btnesp first espCancelar btnCancelarActualizacion"
                    name="btnCancelarActualizacion" id="btnCancelarActualizacion"><span
                      class="displayEsp">Cancelar</span></button>
                  <input type="file" name="file-2" id="file-2" class="inputfile inputfile-2"
                    data-multiple-caption="{count} archivos seleccionados" multiple />
                  <label for="file-2" class="no-margin" id="file-chat">
                    <span class="iborrainputfile ajuste">Archivos</span>
                  </label>
                  <button type="button" class="btnesp first espAgregar" name="btnAgregarActualizacion"
                    id="btnAgregarActualizacion"><span class="displayEsp">Agregar</span></button>
                </center>
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
<div class="modal fade agregarMiembros" id="centralModalSuccess" tabindex="-1" role="dialog"
  aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-notify modal-success gestionarIntegrantes" role="document">
    <!--Content-->
    <div class="modal-content">
      <!--Header-->
      <div class="modal-header">
        <p class="heading lead">Actualizar integrantes del proyecto</p>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
          style="margin-top: -10px; margin-right: 1rem;">
          <span aria-hidden="true" class="white-text">&times;</span>
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
<div class="modal fade agregarMiembros" id="centralCompartirActualizacion" tabindex="-1" role="dialog"
  aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-notify modal-success" role="document">
    <!--Content-->
    <div class="modal-content">
      <!--Header-->
      <div class="modal-header">
        <p class="heading lead">Compartir actualización</p>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
          style="margin-top: -10px; margin-right: 1rem;">
          <span aria-hidden="true" class="white-text">&times;</span>
        </button>
      </div>

      <!--Body-->
      <div class="modal-body">
        <form class="form-inline active-cyan-3 active-cyan-4">
          <div class="col-md-12 text-center">
            <label for="Email"><i class="far fa-envelope" style="margin-right: 5px;"></i> Correos electrónicos:</label>
            <input type='text' id='enviarMails' name='enviarMails' class='form-control' value=''>
          </div>
          <br><br>
          <div class="col-md-12 text-center" style="margin-top: 15px;">
            <img src="../../img/chat/loading.gif" id="loading" width="30px"
              style="position: absolute; display: none;" />
            <input type="hidden" id="idEnviar" value="">
            <button type="button" id="btnEnviarActualizacion" class="btn btn-primary btn-sm rounded"
              style="float: right;">Enviar</button>
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
<div class="modal fade right" id="fluidModalRightSuccess" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-full-height modal-right modal-notify modal-success" role="document">
    <!--Content-->
    <div class="modal-content">
      <!--Header-->
      <div class="modal-header">
        <p class="heading lead" id="tituloChat">Tarea</p>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
          style="margin-top: -10px; margin-right: 1rem;">
          <span aria-hidden="true" class="white-text">&times;</span>
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
                      <span data-toggle="tooltip" class="tooltip-chat" title="<?=$nombreusuario?>"
                        style="position: relative; bottom: 4px;"><img src="../../img/chat/user-add.svg"
                          class="user-img img-responsive" width="35px"></span>
                    </a>
                  </li>
                  <li class="menu-item">
                    <span><button type="button" id="botonDesplegable" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false"><i class="fas fa-ellipsis-h"></i></button>
                      <ul class="dropdown-menu dropdown-menu-right ellipsis py-2 collapse-inner rounded sin-flecha"
                        role="menu" aria-labelledby="dropdownMenu">
                        <li><a class="dropdown-item" href="#" data-toggle="modal" data-target="#centralModalSuccess"
                            onclick="mostrarEquipos()"><img src="../../img/chat/administrar_usuarios.svg" width="18px"
                              class="img-responsive" style="position: relative; right: 5px;bottom: 3px;" /> Administrar
                            integrantes</a></li>
                        <li><a class="dropdown-item" href="#" onclick="verFavoritos()"><img
                              src="../../img/chat/favorito.svg" width="18px" class="img-responsive"
                              style="position: relative; right: 4px;bottom: 3px;" /> Favoritos</a></li>
                        <!--<li class="dropdown-submenu pull-left">
                        <a class="dropdown-item" href="#"><img src="../../img/chat/preferencias_correo.svg" width="18px" class="img-responsive" style="position: relative; right: 4px; bottom: 1px;"/> Preferencias de correo electrónico <img src="../../img/chat/flecha_derecha.svg" width="18px" class="img-responsive" style="position: relative; right: 4px;"/></a>
                          <ul class="dropdown-menu dropdown-menu-instantaneo">
                            <li><div class="espacio-icon"><a class="dropdown-item" href="#"><img src="../../img/chat/preferencia_correo_electronico.svg" width="18px" class="img-responsive" style="position: relative; right: 4px; bottom: 1px;"/> Correo electrónico instantáneo</a></div></li>
                            <li><div class="espacio-icon"><a class="dropdown-item" href="#">Correo electrónico diario</a></div></li>
                            <li><div class="espacio-icon"><a class="dropdown-item" href="#">Sin correo electrónico</a></div></li>
                          </ul>
                      </li>-->
                        <li><a class="dropdown-item" href="#" onclick="copiarLinkChat()"><img
                              src="../../img/chat/copiar_enlace.svg" width="18px" class="img-responsive"
                              style="position: relative; right: 4px; bottom: 2px;" /> Enlace para copiar elemento</a>
                        </li>
                        <!--<li><a class="dropdown-item" href="#"><img src="../../img/chat/archivar.svg" width="18px" class="img-responsive" style="position: relative; right: 4px; bottom: 3px;"/> Archivar</a></li>-->
                        <li><a class="dropdown-item" href="#" onclick="eliminarChat()"><img
                              src="../../img/chat/eliminar.svg" width="18px" class="img-responsive"
                              style="position: relative; right: 4px; bottom: 1px;" /> Eliminar</a></li>
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
                <input type="text" name="txtActualizacion" id="txtActualizacion" class="form-control"
                  placeholder="Escribir una actualización..." value="">
              </div>
            </div>
            <div class="row" id="botonesNuevaActualizacion" style="display: none;">
              <span class="col-md-4">
                <button type="button" class="btn btn-mdb btn-chat btn-sm btn-sm-mdb btnCancelarActualizacion float-left"
                  name="btnCancelarActualizacion" id="btnCancelarActualizacion" style="display: flex;">Cancelar</button>
              </span>
              <span class="col-md-4">
                <input type="file" name="file-2" id="file-2" class="inputfile inputfile-2"
                  data-multiple-caption="{count} archivos seleccionados" multiple />
                <label for="file-2" class="no-margin" id="file-chat">
                  <svg xmlns="http://www.w3.org/2000/svg" class="iborrainputfile" width="20" height="17"
                    viewBox="0 0 20 17">
                    <path
                      d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z">
                    </path>
                  </svg>
                  <span class="iborrainputfile">Agregar archivos</span>
                </label>
              </span>
              <span class="col-md-4">
                <button type="button" class="btn btn-mdb btn-chat btn-sm btn-sm-mdb float-right"
                  name="btnAgregarActualizacion" id="btnAgregarActualizacion">Agregar</button>
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
<div class="modal fade agregarMiembros" id="centralModalSuccess" tabindex="-1" role="dialog"
  aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-notify modal-success" role="document">
    <!--Content-->
    <div class="modal-content">
      <!--Header-->
      <div class="modal-header">
        <p class="heading lead">Actualizar integrantes del proyecto</p>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
          style="margin-top: -10px; margin-right: 1rem;">
          <span aria-hidden="true" class="white-text">&times;</span>
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
<div class="modal fade agregarMiembros" id="centralCompartirActualizacion" tabindex="-1" role="dialog"
  aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-notify modal-success" role="document">
    <!--Content-->
    <div class="modal-content">
      <!--Header-->
      <div class="modal-header">
        <p class="heading lead">Compartir actualización</p>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
          style="margin-top: -10px; margin-right: 1rem;">
          <span aria-hidden="true" class="white-text">&times;</span>
        </button>
      </div>

      <!--Body-->
      <div class="modal-body">
        <form class="form-inline active-cyan-3 active-cyan-4">
          <div class="col-md-12 text-center">
            <label for="Email"><i class="far fa-envelope" style="margin-right: 5px;"></i> Correos electrónicos:</label>
            <input type='text' id='enviarMails' name='enviarMails' class='form-control' value=''>
          </div>
          <br><br>
          <div class="col-md-12 text-center" style="margin-top: 15px;">
            <img src="../../img/chat/loading.gif" id="loading" width="30px"
              style="position: absolute; display: none;" />
            <input type="hidden" id="idEnviar" value="">
            <button type="button" id="btnEnviarActualizacion" class="btn btn-primary btn-sm rounded"
              style="float: right;">Enviar</button>
          </div>
        </form>
      </div>

    </div>
    <!--/.Content-->
  </div>
</div>
<!--  SHARE UPDATES  -->

<!-- VER USUARIOS DE VISTO  -->
<div class="modal fade verUsuariosVisto" id="verUsuariosVisto" tabindex="-1" role="dialog"
  aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-notify modal-success" role="document">
    <!--Content-->
    <div class="modal-content">
      <!--Header-->
      <div class="modal-header">
        <p class="heading lead">Visto por</p>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
          style="margin-top: -10px; margin-right: 1rem;">
          <span aria-hidden="true" class="white-text">&times;</span>
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

<div class="modal fade right" id="uploadBar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
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



<span id="copyp1" style="display: block;"></span>
<script>
//Variables globales
var urlDireccionamiento = "http://localhost/tasksManager/catalogos/tareas/timDesk/";
var urlGlobal = "http://localhost/tasksManager/js/chat/";
var tinymceurl = "http://localhost/tasksManager/lib/tinymce/";
var IDUsuario = <?php echo $_SESSION["PKUsuario"]; ?>;
var nombreusuario = "<?php echo $nombreusuario; ?>";
var IDTareaChat;
var idChatIndividual = 0;
var idChatTarea = 0;
var idProyectoUrl = <?php echo $id; ?>;
var pagina = "scrum";
var ruta = "";
var rutare = "../../";
var post_max_size_limite = "<?php echo $post_max_size_limite; ?>";
var post_max_size = <?php echo $post_max_size; ?>;
</script>
<script src="../../js/chat/chat.js"></script>
<script src="../../js/revisarestado.js"></script>
<script type="text/javascript">
//esconder menu de proyectos
$(document).mouseup(function(e) {
  var container = $(".form-group-projects");
  if (!container.is(e.target) && container.has(e.target).length === 0) {
    container.hide();
  }
});

function seeProjects() { //Lista de los proyectos del usuario
  varContainer = '.form-group-projects';
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
/*
if (estado != 0) {
  $('#addState').css('display', 'none');

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

  /*$(document).ready(function() {
    $("#alertaTareas").load('../alerta_Tareas_Nuevas.php?user=' + <?//=$_SESSION['PKUsuario'];?> + '&ruta=' +
      '<?//=$ruta;?>&ruteEdit=<?//=$ruteEdit;?>');
    setInterval(refrescar, 50000);
  });

  function refrescar() {
    $("#alertaTareas").load('../alerta_Tareas_Nuevas.php?user=' + <?//=$_SESSION['PKUsuario'];?> + '&ruta=' +
      '<?//=$ruta;?>&ruteEdit=<?//=$ruteEdit;?>');
  }*/
  /*
  if (fecha != 0 && responsable != 0 && estado != 0) {
    $('#addDate').css('display', 'none');
    $('#addUser').css('display', 'none');
    $('#addState').css('display', 'none');
    $('.dropdown-menu').html(
      '<a class="dropdown-item" href="#">Todas las columnas seleccionables están agregadas.</a>')
  }
}
*/
</script>

</html>
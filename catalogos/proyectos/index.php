<?php
session_start();

if (isset($_SESSION["Usuario"])) {
  require_once '../../include/db-conn.php';
  $user = $_SESSION["Usuario"];
} else {
  header("location:../dashboard.php");
}

if (isset($_POST['id'])) {
  $id = $_POST['id'];
  $stmt = $conn->prepare("SELECT p.Proyecto, p.FKResponsable
  FROM proyectos as p LEFT JOIN usuarios as u ON u.PKUsuario = p.FKResponsable
  LEFT JOIN empleados as e ON e.PKEmpleado = u.FKEmpleado
  WHERE PKProyecto= :id");
  $stmt->execute(array(':id' => $id));
  $row = $stmt->fetch();
  $proyecto = $row['Proyecto'];
  $idUsuario = $row['FKResponsable'];
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | Proyectos</title>

  <!-- ESTILOS -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link rel="stylesheet" href="../../css/notificaciones.css">
  <!-- JS -->
  <script src="../../js/lobibox.min.js"></script>
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/mdb.min.js"></script>
  <script src="../../js/slimselect.min.js"></script>
  <script src="../../js/sweet/sweetalert2.js"></script>
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>

  <script>
    $(document).ready(function() {
      var idioma_espanol = {
        "sProcessing": "Procesando...",
        "sZeroRecords": "No se encontraron resultados",
        "sEmptyTable": "Ningún dato disponible en esta tabla",
        "sSearch": "<img src='../../img/timdesk/buscar.svg' width='20px' />",
        "sLoadingRecords": "Cargando...",
        searchPlaceholder: "Buscar...",
        "oPaginate": {
          "sFirst": "Primero",
          "sLast": "Último",
          "sNext": "<i class='fas fa-chevron-right'></i>",
          "sPrevious": "<i class='fas fa-chevron-left'></i>",
        },
      }

      $("#tblProyectos").dataTable({
          "language": idioma_espanol,
          info: false,
          scrollX: true,
          bSort: false,
          pageLength: 50,
          responsive: true,
          lengthChange: false,
          dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
          <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
          buttons: {
            dom: {
              button: {
                tag: "button",
                className: "btn-custom mr-2",
              },
              buttonLiner: {
                tag: null,
              },
            },
            buttons: [{
                text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> Añadir registro</span>',
                className: "btn-custom--white-dark",
                action: function() {
                  $("#txtProyectoU").prop('disabled', false);
                  $("#cmbIdUsuarioU").prop('disabled', false);
                  $(".ss-main").css("pointer-events", "auto");
                  $("#btnEditar").prop('disabled', false);
                  $("#idProyectoDelete").prop('disabled', false);
                  $("#txtDescripcionU").prop('disabled', false);
                  $('#agregar_Proyecto').modal('show');
                },
              },
              {
                extend: "excelHtml5",
                text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar excel</span>',
                className: "btn-custom--white-dark",
                titleAttr: "Excel",
              }
            ],
          },
          "ajax": "functions/function_Proyectos.php",
          "columns": [{
              "data": "Proyecto"
            },
            {
              "data": "Descripcion"
            },
            {
              "data": "Usuario"
            },
            {
              "data": "Acciones",
              width: "5%"
            }
          ]
        }

      )
    });
  </script>
</head>

<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
    $ruta = "../";
    $ruteEdit = $ruta . "central_notificaciones/";
    require_once '../menu3.php';
    ?>
    <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
    <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
    <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">
        <?php
        $icono = 'ICONO-MIS-PROYECTOS-AZUL.svg';
        $titulo = 'Tim Proyectos';
        $rutatb = "../";
        require_once '../topbar.php';
        ?>
        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- DataTales Example -->
          <div class="card mb-4">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table" id="tblProyectos" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Proyecto</th>
                      <th>Descripción</th>
                      <th>Creado por</th>
                      <th></th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->
      <?php
      $rutaf = "../";
      require_once '../footer.php';
      ?>
    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->
  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>
  <!-- Add Fluid Modal mis proyectos -->
  <div class="modal fade right" id="agregar_Proyecto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">


      <div class="modal-content">
        <form action="#" method="POST" id="agregarProyecto">
          <!--<input type="hidden" name="idProyectoA" value="">-->
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Agregar proyecto</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">

            <div class="form-group">
              <label for="usr">Nombre del proyecto:</label>
              <input type="text" id="txtarea" class="form-control alpha-only" maxlength="40" name="txtProyecto" required onkeyup="validEmptyInput(this)">
              <div class="invalid-feedback" id="invalid-nombreProy">El proyecto debe tener un nombre.</div>
            </div>

            <div class="form-group">
              <label for="usr">Descripción del proyecto:</label>
              <textarea id="txtDescripcion" class="form-control alpha-only" maxlength="140" name="txtDescripcion" rows="4"></textarea>
            </div>

            <div class="form-group">
              <label for="usr">Encargado del proyecto:</label>
              <select name="cmbIdUsuario[]" id="cmbIdUsuario" class="form-control" required onchange="validEmptyInput(this)" multiple>
                <!--<select name="cmbIdUsuario[]" id="multiple"  multiple>-->
                <!-- <option disabled selected hidden>Seleccionar encargado</option> -->
                <?php
                $stmt = $conn->prepare("SELECT u.id, CONCAT(e.Nombres,' ',e.PrimerApellido,' ',e.SegundoApellido) as nombre_empleado FROM usuarios as u INNER JOIN empleados as e ON e.PKEmpleado = u.id WHERE u.empresa_id = " . $_SESSION['IDEmpresa']);
                $stmt->execute();
                $row = $stmt->fetchAll();
                $cuentaProy = $stmt->rowCount();

                if ($cuentaProy > 0) {
                  foreach ($row as $r) //Mostrar usuarios
                  {
                    if (trim($r['nombre_empleado']) != "") {
                      $nombre_empleado = $r['nombre_empleado'];
                    } else {
                      $nombre_empleado = "Error BD";
                    }
                    echo '<option value="' . $r['id'] . '">' . $nombre_empleado . '</option>';
                  }
                } else {
                  echo '<option value="" disabled>No hay usuarios para mostrar.</option>';
                }
                ?>
              </select>
              <div class="invalid-feedback" id="invalid-encargadoProy">El proyecto debe tener un encargado.</div>
            </div>

            <div class="form-group">
              <label for="usr">Equipos participantes en el proyecto:</label>
              <select name="cmbIdEquipo[]" id="multiple2" multiple>
                <?php
                $stmt2 = $conn->prepare("SELECT * FROM equipos WHERE empresa_id = :idempresa");
                $stmt2->bindValue(':idempresa', $_SESSION['IDEmpresa']);
                $stmt2->execute();
                $row2 = $stmt2->fetchAll();

                if (count($row2) > 0) {

                  foreach ($row2 as $r2) //mostrar equipos
                  {
                    echo '<option value="' . $r2['PKEquipo'] . '">' . $r2['Nombre_Equipo'] . '</option>';
                  }
                  //'<option value="'.$r['PKEquipo'].'">'.$r['Nombre_Equipo'].'</option>';
                } else {
                  echo '<option value="" disabled>No hay equipos para mostrar.</option>';
                }
                ?>
              </select>
            </div>
            <div class="form-group">
              <label for="usr">Usuarios participantes en el proyecto:</label>
              <select name="cmbUsuarios[]" id="multiple3" multiple>
                <?php
                $stmt = $conn->prepare("SELECT u.id, CONCAT(e.Nombres,' ',e.PrimerApellido) AS nombre_usuario 
                FROM usuarios AS u 
                INNER JOIN empleados AS e ON e.PKEmpleado = u.id 
                WHERE u.empresa_id = :idempresa AND u.estatus = 1");
                $stmt->bindValue(':idempresa', $_SESSION['IDEmpresa']);
                $stmt->execute();
                $row = $stmt->fetchAll();

                if (count($row) > 0) {
                  foreach ($row as $r) {
                    echo '<option value="' . $r['id'] . '">' . $r['nombre_usuario'] . '</option>';
                  }
                } else {
                  echo '<option value="" disabled>No hay usuarios para mostrar.</option>';
                }
                ?>
              </select>
            </div>
            <div class="form-group">
              <label for="usr">Empleados participantes en el proyecto:</label>
              <select name="cmbEmpleados[]" id="multiple4" multiple>
                <?php
                $stmt = $conn->prepare("SELECT e.PKEmpleado, CONCAT(e.Nombres,' ',e.PrimerApellido) as nombre_empleado FROM empleados AS e 
                WHERE e.empresa_id = :idempresa AND e.estatus = 1 AND NOT EXISTS (SELECT 1 FROM usuarios AS u WHERE u.id = e.PKEmpleado)");
                $stmt->bindValue(':idempresa', $_SESSION['IDEmpresa']);
                $stmt->execute();
                $row = $stmt->fetchAll();

                if (count($row) > 0) {
                  foreach ($row as $r) {
                    echo '<option value="' . $r['PKEmpleado'] . '">' . $r['nombre_empleado'] . '</option>';
                  }
                } else {
                  echo '<option value="" disabled>No hay usuarios para mostrar.</option>';
                }
                ?>
              </select>
            </div>
            <label style="color:#006dd9;font-size: 13px;"> Nota: Los integrantes que pertenezcan a un equipo no
              apareceran como integrantes individuales</label>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal" id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>

              <button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnAgregar"><span class="ajusteProyecto">Agregar</span></button>
            </div>
        </form>
      </div>
    </div>
  </div>
  </div>
  <!-- End Add Fluid Modal mis proyectos -->

  <!--UPDATE MODAL DENTRO DE PROYECTOS 04/09/2020-->
  <div class="modal fade right" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
      <div class="modal-content">
        <form action="functions/editar_Proyecto.php" method="POST">
          <input type="hidden" name="idProyectoU" id="idProyectoU">
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

            <a class="btn-custom btn-custom--border-blue btn-custom--small" href="../tareas/timDesk/index.php?id=" name="btntimdeskProyecto" id="btntimdeskProyecto">Ver
              Proyecto
            </a>
            <button type="button" class="btn-custom btn-custom--border-blue btn-custom--small" onclick="eliminarProyecto(this.value);" name="btnEliminarProyecto" id="idProyectoDelete" data-toggle="modal" data-target="">
              Eliminar proyecto
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

  <!--CONFIRMACION DE ELIMINAR PROYECTO-->
  <div id="eliminar_Proyecto_Conf" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="functions/eliminar_Proyecto.php" method="POST">
          <!--?idProyectoD=-->
          <input type="hidden" name="idProyectoD" id="idProyectoD" value="">
          <div class="modal-header">
            <h4 class="modal-title">Eliminar proyecto</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <p>¿Está seguro de realizar esta acción?</p>
            <p class="text-warning"><small>Esta acción es irreversible.</small></p>
          </div>
          <div class="modal-footer justify-content-center">
            <input type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal" value="Cancelar">
            <input type="submit" class="btnesp first espEliminar" value="Eliminar">
          </div>
        </form>
      </div>
    </div>
  </div>
  <!--END CONFIRMACION DE ELIMINAR PROYECTO-->

  <!-- <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.23/r-2.2.6/sc-2.0.3/datatables.min.js"></script> -->
  <script>
    function obtenerIdProyectoEditar(id) {
      document.getElementById('idProyectoU').value = id;
      document.getElementById('idProyectoD').value = id;
      var id = "id=" + id;

      $.ajax({
        type: 'POST',
        url: 'functions/getProyecto.php',
        data: id,
        success: function(r) {
          console.log(r);
          var datos = JSON.parse(r);

          $("#txtProyectoU").prop('disabled', false);
          $("#cmbIdUsuarioU").prop('disabled', false);
          $(".ss-main").css("pointer-events", "auto");
          $("#btnEditar").prop('disabled', false);
          $("#idProyectoDelete").prop('disabled', false);
          $("#txtDescripcionU").prop('disabled', false);

          $("#txtProyectoU").val(datos.html);
          $("#txtDescripcionU").val(datos.htmlDescripcion);
          $("#cmbIdUsuarioU").html(datos.html2);
          $("#multipleU").html(datos.html3);
          $("#multipleU2").html(datos.html4);
          $("#multipleU4").html(datos.html5);
          $("#btntimdeskProyecto").attr("href", "../tareas/timDesk/index.php?" + id);

          if (datos.permiso == 0) {
            $("#txtProyectoU").prop('disabled', true);
            $("#cmbIdUsuarioU").prop('disabled', true);
            $(".ss-main").css("pointer-events", "none");
            $("#btnEditar").prop('disabled', true);
            $("#idProyectoDelete").prop('disabled', true);
            $("#txtDescripcionU").prop('disabled', true);
          }
          //$("#idProyectoU").attr("value", id );
        }
      });
    }

    $("#btnAgregar").click(function() {
      data = $('#agregarProyecto').serialize();

      var nombre = $("#txtarea").val();
      var idusuario = $("#cmbIdUsuario").val();

      if (!nombre) {
        $("#invalid-nombreProy").css("display", "block");
        $("#txtarea").addClass("is-invalid");
      }

      if (!idusuario || idusuario < 1) {
        $("#invalid-encargadoProy").css("display", "block");
        $("#cmbIdUsuario").addClass("is-invalid");
      }

      var badNombreProy =
        $("#invalid-nombreProy").css("display") === "block" ? false : true;
      var badencargadoProy =
        $("#invalid-encargadoProy").css("display") === "block" ? false : true;

      if (badNombreProy && badencargadoProy) {
        $.ajax({
          url: "functions/agregar_Proyecto.php",
          type: "POST",
          data: data,
          success: function(resp, status, xhr) {
            if (resp > 0) {
              $('#agregarProyecto')[0].reset();
              window.location.href = '../../catalogos/tareas/timDesk/index.php?id=' + resp;
            }

          }
        });
      }
    });

    loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
    setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());

    $('#editarventana').click(function() {
      $('#editar_Proyecto').modal('hide');
    });

    new SlimSelect({
      select: '#multiple3',
      placeholder: 'Seleccionar integrantes',
      deselectLabel: '<span class="">✖</span>'
    });
    new SlimSelect({
      select: '#multiple4',
      placeholder: 'Seleccionar integrantes',
      deselectLabel: '<span class="">✖</span>'
    });
    new SlimSelect({
      select: '#multiple2',
      placeholder: 'Seleccionar equipos',
      deselectLabel: '<span class="">✖</span>',
      onChange: (info) => {
        console.log(info);
        var id = 0;

      }
    });
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

    var selectIdUsuario = new SlimSelect({
      select: '#cmbIdUsuario',
      deselectLabel: '<span class="">✖</span>'
    });

    var selectIdUsuarioU = new SlimSelect({
      select: '#cmbIdUsuarioU',
      deselectLabel: '<span class="">✖</span>'
    });

    /*Funcion para  mostrar una alerta al eliminar un proyecto*/
    function eliminarProyecto(id) {
      id = $('#idProyectoU').val();
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: 'btn',
          cancelButton: 'btn'
        },
        buttonsStyling: false
      })

      swalWithBootstrapButtons.fire({
        title: '¿Desea eliminar el registro de este proyecto?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<span class="verticalCenter2">Eliminar proyecto</span>',
        cancelButtonText: '<span class="verticalCenter2">Cancelar</span>',
        reverseButtons: false,
        customClass: {
          confirmButton: "btn-custom btn-custom--blue",
          cancelButton: "btn-custom btn-custom--border-blue",
          actions: "d-flex justify-content-around"
        }
      }).then((result) => {
        if (result.isConfirmed) {

          $.ajax({
            url: "functions/eliminar_Proyecto.php",
            type: "POST",
            data: {
              "idProyectoD": id
            },
            success: function(data, status, xhr) {
              if (data == "exito") {
                $('#modalEditar').modal('toggle');
                $('#tblProyectos').DataTable().ajax.reload();
                Lobibox.notify('success', {
                  size: 'mini',
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: 'center top', //or 'center bottom'
                  icon: false,
                  img: '../../img/chat/checkmark.svg',
                  msg: '¡Proyecto eliminado!'
                });
              } else {
                Lobibox.notify('warning', {
                  size: 'mini',
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: 'center top',
                  icon: false,
                  img: '../../img/chat/notificacion_error.svg',
                  msg: 'Ocurrió un error al eliminar'
                });
              }
            }
          });

        } else if (
          /* Read more about handling dismissals below */
          result.dismiss === Swal.DismissReason.cancel
        ) {

        }
      })

      swal("¿Desea eliminar el registro de este proyecto?", {
          buttons: {
            cancel: {
              text: "Cancelar",
              value: null,
              visible: true,
              className: "",
              closeModal: true,
            },
            confirm: {
              text: "Eliminar proyecto",
              value: true,
              visible: true,
              className: "",
              closeModal: true,
            },
          },
          icon: "warning"
        })
        .then((value) => {
          if (value) {
            $.ajax({
              url: "functions/eliminar_Proyecto.php",
              type: "POST",
              data: {
                "idProyectoD": id
              },
              success: function(data, status, xhr) {
                if (data == "exito") {
                  $('#modalEditar').modal('toggle');
                  $('#tblProyectos').DataTable().ajax.reload();
                  Lobibox.notify('success', {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/chat/checkmark.svg',
                    msg: '¡Proyecto eliminado!'
                  });
                } else {
                  Lobibox.notify('warning', {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top',
                    icon: false,
                    img: '../../img/chat/notificacion_error.svg',
                    msg: 'Ocurrió un error al eliminar'
                  });
                }
              }
            });
          } else {
            //cuando se presiona el boton de cancelar
          }
        });


    }
    /*End Funcion para  mostrar una alerta al eliminar un proyecto*/

    $("#txtarea").on('input', function() {
      if ($(this).val().length >= 40) {
        Lobibox.notify('warning', {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top',
          icon: true,
          img: '../../img/timdesk/warning_circle.svg',
          msg: 'Maximo 40 caractéres!'
        });
      }
    });

    $("#txtProyectoU").on('input', function() {
      if ($(this).val().length >= 40) {
        Lobibox.notify('warning', {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top',
          icon: true,
          img: '../../img/timdesk/warning_circle.svg',
          msg: 'Maximo 40 caractéres!'
        });
      }
    });

    function validEmptyInput(item, invalid = null) {
      const val = item.value;
      const parent = item.parentNode;
      let invalidDiv;
      if (invalid) {
        invalidDiv = document.getElementById(invalid);
      } else {
        for (let i = 0; i < parent.children.length; i++) {
          if (parent.children[i].classList.contains("invalid-feedback")) {
            invalidDiv = parent.children[i];
            break;
          }
        }
      }
      if (!val) {
        item.classList.add("is-invalid");
        invalidDiv.style.display = "block";
      } else {
        item.classList.remove("is-invalid");
        invalidDiv.style.display = "none";
      }
    }
  </script>
  <script>
    var ruta = "../";
  </script>
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>
</body>

</html>
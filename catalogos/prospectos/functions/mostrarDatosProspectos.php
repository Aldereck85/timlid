<?php
session_start();

if (isset($_SESSION["Usuario"])) {
    require_once '../../../include/db-conn.php';

    if (isset($_POST['idProspecto'])) {
        $idProspecto = $_POST['idProspecto'];
    } else {
        header("location:../");
    }

} else {
    header("location:../../dashboard.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="icon" type="image/png" href="../../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Timlid | Prospecto</title>

  <!-- Custom fonts for this template -->
  <link href="../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="../../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../../vendor/datatables/buttons.bootstrap4.min.css" rel="stylesheet">
  <link href="../../../css/styles.css" rel="stylesheet">
  <link href="../../../css/stylesTable.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" rel="stylesheet">
  <link href="../../../css/timeline.css" rel="stylesheet">
  <link href="../../../css/lobibox.min.css" rel="stylesheet">

  <!-- Bootstrap core JavaScript-->
  <script src="../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../../js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="../../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="../../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../../vendor/datatables/buttons.bootstrap4.min.js"></script>
  <script src="../../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../../vendor/jszip/jszip.min.js"></script>
  <script src="../../../js/validaciones.js"></script>
  <script src="../../../js/sweet/sweetalert2.js"></script>
  <script src="../../../js/bootstrap-clockpicker.min.js"></script>
  <script src="../../../js/lobibox.min.js"></script>

  <style>
  #etiquetas h6 {
    text-align: center;
    font-weight: bold;
    color: white;
  }

  #CargarDatosPersonales {
    background-color: #5bc0de;
  }

  #CargarDatosLaborales {
    background-color: #5cb85c;
  }

  #CargarDatosPersonales:hover {
    background-color: #00acc1;
  }

  #CargarDatosLaborales:hover {
    background-color: #2e7d32;
  }

  #CargarBitacoraNotas {
    background-color: #757575;
  }

  #CargarBitacoraNotas:hover {
    background-color: #585858;
  }
  </style>
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
$ruta = "../../";
require_once '../../menu3.php';
?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <?php
$rutatb = "../../";
$titulo = "Editar prospecto";
require_once '../../topbar.php';
?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-address-book"></i> Prospecto</h1>
          </div>

          <div class="row">
            <div class="col-12">
              <ul class="nav nav-tabs">
                <li class="nav-item">
                  <a class="nav-link active" href="#" id="datos-prospecto" data-contenedor="contenedor-prospecto">Datos
                    del prospecto</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#" id="datos-contacto" data-contenedor="contenedor-contactos">Datos de
                    contacto</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#" id="bitacora-notas" data-contenedor="contenedor-notas">Bitácora de
                    notas</a>
                </li>
              </ul>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <div class="card shadow mb-4">
                <div id="divAgregar" class="card-header">
                </div>
                <div id="cardProspecto" class="card-body">
                  <?php require_once 'mostrar_Editar_Prospecto.php';?>
                  <?php require_once 'mostrar_Datos_Contacto.php';?>
                  <?php require_once 'mostrar_Bitacora_Notas.php';?>
                </div>
              </div>

            </div>
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <?php
$rutaf = "../../";
require_once '../../footer.php';
?>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!--MODAL AÑADIR CONTACTO-->
  <div class="modal fade right" id="modalAddContacto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-full-height modal-right modal-md" role="document">
      <div class="modal-content">
        <form>
          <input type="hidden" id="id-add-contacto" value="<?=$idProspecto?>">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Añadir Contacto</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="usr">Nombre(s) del contacto:</label>
              <input type="text" class="form-control alpha-only" id="nombre-add-contacto">
            </div>
            <div class="form-group">
              <label for="usr">Apellido(s) del contacto:</label>
              <input type="text" class="form-control alpha-only" id="apellido-add-contacto">
            </div>
            <div class="form-group">
              <label for="usr">Puesto:</label>
              <input type="text" class="form-control alpha-only" id="puesto-add-contacto">
            </div>
            <div class="form-group">
              <label for="usr">Teléfono:</label>
              <input type="text" class="form-control numeric-only" id="telefono-add-contacto">
            </div>
            <div class="form-group">
              <label for="usr">Celular:</label>
              <input type="text" class="form-control numeric-only" id="celular-add-contacto">
            </div>
            <div class="form-group">
              <label for="usr">Email:</label>
              <input type="text" class="form-control alphaNumeric-only" id="email-add-contacto">
            </div>
          </div>
          <div class="modal-footer justify-content-around">
            <button type="button" class="btn-custom btn-custom--border-blue btnCancelarActualizacion"
              data-dismiss="modal" id="btnCancelarActualizacion">
              <span class="ajusteProyecto">Cancelar</span>
            </button>
            <button type="button" class="btn-custom btn-custom--blue" id="addContacto">
              <span class="ajusteProyecto">Añadir</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!--MODAL EDITAR CONTACTO-->
  <div class="modal fade right" id="modalContacto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-full-height modal-right modal-md" role="document">
      <div class="modal-content">
        <form>
          <input type="hidden" id="id-contacto">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Editar Contacto</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="usr">Nombre(s) del contacto:</label>
              <input type="text" class="form-control alpha-only" id="nombre-contacto">
            </div>
            <div class="form-group">
              <label for="usr">Apellido(s) del contacto:</label>
              <input type="text" class="form-control alpha-only" id="apellido-contacto">
            </div>
            <div class="form-group">
              <label for="usr">Puesto:</label>
              <input type="text" class="form-control alpha-only" id="puesto-contacto">
            </div>
            <div class="form-group">
              <label for="usr">Teléfono:</label>
              <input type="text" class="form-control numeric-only" id="telefono-contacto">
            </div>
            <div class="form-group">
              <label for="usr">Celular:</label>
              <input type="text" class="form-control numeric-only" id="celular-contacto">
            </div>
            <div class="form-group">
              <label for="usr">Email:</label>
              <input type="text" class="form-control alphaNumeric-only" id="email-contacto">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn-custom btn-custom--border-blue btnCancelarActualizacion"
              data-dismiss="modal" id="btnCancelarActualizacion">
              <span class="ajusteProyecto">Cancelar</span>
            </button>
            <button type="button" class="btn-custom btn-custom--border-blue" id="eliminarContacto">
              <span class="ajusteProyecto">Eliminar proyecto</span>
            </button>
            <button type="button" class="btn-custom btn-custom--blue" id="guardarContacto">
              <span class="ajusteProyecto">Guardar</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- MODAL AÑADIR NOTA-->
  <div id="agregar_Proyecto" class="modal fade" style="z-index: 100000000">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="" method="POST" id="frProyecto">
          <div class="modal-header">
            <h4 class="modal-title">NOTA</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <br>
          <div class="row">
            <div class="col-lg-8" style="text-align: center;position:relative;left: 16%;">
              <label for="usr">Ingresar nota:</label>
              <textarea id="txtNota" rows="4" cols="50" class="form-control" maxlength="200" required></textarea>
            </div>
          </div>
          <br>
          <div id="mostrarErrorProyecto" style="display:none;color:#d9534f;text-align:center;">Ocurrio un error, no
            se agregó la nota. Lo puede volver a intentar.</div>
          <div id="mostrarProyecto" style="display:none;color:#5cb85c;text-align:center;">Nota enviada.</div>
          <div class="modal-footer">
            <input type="button" class="btn-custom btn-custom--border-blue" data-dismiss="modal" value="Cancelar">
            <input type="button" class="btn-custom btn-custom--blue" id="btnGuardar" value="Agregar">
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- MODAL EDITAR NOTA -->
  <div class="modal fade right" id="modalNotaEditar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-full-height modal-right modal-md" role="document">
      <div class="modal-content">
        <form>
          <input type="hidden" id="id-nota">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Editar Nota</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="usr">Nota:</label>
              <textarea class="form-control" rows="5" id="nota-desc-edit"></textarea>
            </div>
          </div>
          <div class="modal-footer justify-content-around">
            <button type="button" class="btn-custom btn-custom--border-blue btnCancelarActualizacion"
              data-dismiss="modal" id="btnCancelarActualizacion">
              <span class="ajusteProyecto">Cancelar</span>
            </button>
            <button type="button" class="btn-custom btn-custom--blue" id="guardarNota">
              <span class="ajusteProyecto">Guardar</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- MODAL BORRAR NOTA -->
  <div id="eliminarNota" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <input type="hidden" name="txtIdD" id="txtIdD">
        <div class="modal-header">
          <h4 class="modal-title">Eliminar nota</h4>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="modal-body">
          <p>¿Está seguro de realizar esta acción?</p>
          <p class="text-warning"><small>Esta acción es irreversible.</small></p>
        </div>
        <div class="modal-footer">
          <input type="button" class="btn-custom btn-custom--blue" data-dismiss="modal" value="Cancelar">
          <input type="button" class="btn-custom btn-custom--border-blue" value="Eliminar" id="eliminarNotas">
        </div>
        </form>
      </div>
    </div>
  </div>

  <script src="../js/prospectos.js"></script>
  <script type="text/javascript">
  $(document).ready(function() {
    /* TABLA CONTACTOS */
    var idioma_espanol = {
      sProcessing: "Procesando...",
      sZeroRecords: "No se encontraron resultados",
      sEmptyTable: "Ningún dato disponible en esta tabla",
      sSearch: "<img src='../../../img/timdesk/buscar.svg' width='20px' />",
      sLoadingRecords: "Cargando...",
      searchPlaceholder: "Buscar...",
      oPaginate: {
        sFirst: "Primero",
        sLast: "Último",
        sNext: "<img src='../../../img/icons/pagination.svg' width='20px'/>",
        sPrevious: "<img src='../../../img/icons/pagination.svg' width='20px' style='transform: scaleX(-1)'/>",
      },
    };
    $("#tblDatosContacto").dataTable({
      language: idioma_espanol,
      dom: "Bfrtip",
      buttons: [{
        extend: "excelHtml5",
        text: '<img class="readEditPermissions" type="submit" width="50px" src="../../../img/excel-azul.svg" />',
        className: "excelDataTableButton",
        titleAttr: "Excel",
      }, ],
      scrollX: true,
      lengthChange: false,
      info: false,
      ajax: "function_Datos_Contacto.php?id=" + <?=$idProspecto?>,
      columns: [{
          data: "Nombre",
        },
        {
          data: "Apellido",
        },
        {
          data: "Puesto",
        },
        {
          data: "Telefono",
        },
        {
          data: "Celular",
        },
        {
          data: "Email",
        },
      ],
    });

    /* ALERTAS */
    /* $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user=' + <?=$_SESSION['PKUsuario'];?> +
      '&ruta=' +
      '<?=$ruta;?>');

    function refrescar() {
      $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user=' + <?=$_SESSION['PKUsuario'];?> +
        '&ruta=' +
        '<?=$ruta;?>');
    }
    setInterval(refrescar, 5000); */
  });
  </script>
  <script>
  $("#btnGuardar").click(function() {

    var nota = $('#txtNota').val().trim();
    var Cliente = <?=$idProspecto?>;
    var contador = parseInt($('#contador').val());
    var numeronotas = parseInt($('#numeronota').val());
    var ladoNota = $('#ladoNota').val();

    if (nota === '') {
      $("#txtNota")[0].reportValidity();
      $("#txtNota")[0].setCustomValidity('Completa este campo.');
      return;
    }

    <?php date_default_timezone_set('America/Mexico_City');?>
    var fecha = "<?php echo date('d/m/Y H:i:s', time()); ?>";

    var myData = {
      "Nota": nota,
      "Cliente": Cliente,
      "Fecha": fecha
    };

    $.ajax({
      url: "agregarNota.php",
      type: "POST",
      data: myData,
      success: function(data, status, xhr) {
        if (parseInt(data) > 0) {

          numeronotas = numeronotas + 1;
          contador = contador + 1;

          if (ladoNota == 'primero') {
            clase = 'class="timeline-inverted"';
            color = 'warning';
            $('#ladoNota').val('segundo');
          } else {
            clase = '';
            color = 'info';
            $('#ladoNota').val('primero');
          }
          var agregarLista = '<li ' + clase + ' id="nota_' + data + '">' +
            '<div class="timeline-badge ' + color +
            '"><i class="glyphicon glyphicon-credit-card"></i></div>' +
            '<div class="timeline-panel">' +
            '<div class="timeline-heading">' +
            '<h4 class="timeline-title">Nota ' + contador + '</h4>' +
            '</div>' +
            '<div class="timeline-"body" id="editarNota_' + data + '">' +
            '<p>' + nota + '</p>' +
            '</div>' +
            '<hr>' +
            '<div class="row">' +
            '<div class="col-md-9" align="left">' +
            '<button type="button" class="btn btn-primary btn-circle" data-toggle="modal" data-target="#editarNota" id="botonEditar_' +
            data + '" onclick="asignarEditar( ' + data + ', \'' + nota +
            '\')"><i class="fas fa-edit"></i></button>' +
            '<button type="button" class="btn btn-danger btn-circle" data-toggle="modal" data-target="#eliminarNota" onclick="asignarNota( ' +
            data + ')"><i class="fas fa-trash-alt"></i></button>' +
            '</div>' +
            '<div class="col-md-3" align="right">' +
            '<small>' + fecha + '</small>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</li>';

          $('#contador').val(contador);
          $('#numeronota').val(numeronotas);

          $("#add-timeline").addClass("timeline");
          $("#nuevo_mensaje").remove();
          $("#add-timeline").prepend(agregarLista);

          $("#mostrarProyecto").css("display", "block");
          setTimeout(
            function() {
              $("#mostrarProyecto").css("display", "none");
              $('#agregar_Proyecto').modal('toggle');
              $('#txtNota').val("");

            }, 2000);
        } else {

          $("#mostrarErrorProyecto").css("display", "block");
          setTimeout(
            function() {
              $("#mostrarErrorProyecto").css("display", "none");
            }, 2000);
        }

      }

    });


  });
  </script>
  <script>
  var ruta = "../../";
  </script>
  <script src="../../../js/sb-admin-2.min.js"></script>
  <script src="../../../js/scripts.js"></script>


</body>

</html>
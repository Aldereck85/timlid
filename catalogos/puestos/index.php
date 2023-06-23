<?php
session_start();

if (isset($_SESSION["Usuario"])) {
    require_once '../../include/db-conn.php';
    $user = $_SESSION["Usuario"];
} elseif (isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 6)) {
    require_once '../../include/db-conn.php';
    $user = $_SESSION["Usuario"];
    $stmt = $conn->prepare('SELECT * FROM permisos_pantallas WHERE FKPantalla = 5 AND FKUsuario = :id');
    $stmt->execute(array(':id' => $_SESSION["PKUsuario"]));
    $row = $stmt->fetch();
    $permiso = $row['Permiso'];
    if ($permiso == 0) {
        header("location:../dashboard.php");
    }
} else {
    header("location:../dashboard.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Timlid | Puestos</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>

  <!-- Page level plugins -->
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.bootstrap4.min.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>
  <script src="../../js/validaciones.js"></script>
  <script src="../../js/sweet/sweetalert2.js"></script>
  <script src="../../js/slimselect.min.js"></script>
  <script src="../../js/lobibox.min.js"></script>

  <!-- Custom fonts for this template -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="../../css/stylesTable.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet">

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
        "sNext": "<img src='../../img/icons/pagination.svg' width='20px'/>",
        "sPrevious": "<img src='../../img/icons/pagination.svg' width='20px' style='transform: scaleX(-1)'/>"
      },
    }

    $("#tblPuestos").dataTable({
      "language": idioma_espanol,
      "dom": "Bfrtip",
      "buttons": [{
        extend: 'excelHtml5',
        text: '<img class="readEditPermissions" type="submit" width="50px" src="../../img/excel-azul.svg" />',
        className: "excelDataTableButton",
        titleAttr: 'Excel',
      }],
      "scrollX": true,
      "lengthChange": false,
      "info": false,
      "ajax": "functions/functions_puestos.php",
      "columns": [{
          "data": "id"
        },
        {
          "data": "Puesto"
        },
        {
          "data": "Tipo de pago"
        }
      ]
    })
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
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">
        <?php
$rutatb = '../';
$titulo = 'Puestos';
$icono = '../../img/icons/puestos.svg';
require_once '../topbar.php';
?>

        <!-- Topbar -->

        <!-- End of Topbar -->


        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <!--<div class="divPageTitle">
            <img src="../../img/icons/puestos.svg" width="45px" style="position:relative;top:-10px;">
            <label class="lblPageTitle">&nbsp;Puestos</label>
          </div>-->

          <!-- DataTales Example -->
          <div class="card mb-4">
            <div class="card-header py-2">
              <div class="float-right">
                <div class="button-container2" id="">
                  <div class="button-icon-container">
                    <a href="" class="btn btn-circle float-right waves-effect waves-light shadow btn-table"
                      id="btn-puestos" data-toggle="modal" data-target="#agregar_Puesto"><i class="fas fa-plus"></i></a>
                  </div>
                  <div class="button-text-container">
                    <span>Agregar almacén</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table" id="tblPuestos" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>id</th>
                      <th>Puesto</th>
                      <th>Tipo de pago</th>
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

      <!-- Footer -->
      <?php
$rutaf = "../";
require_once '../footer.php';
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

  <!-- Logout Modal-->

  <!--ADD MODAL SLIDE PUESTOS-->
  <div class="modal fade right" id="agregar_Puesto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
      <div class="modal-content">
        <form action="" id="agregarPuesto" method="POST">
          <!--<input type="hidden" name="idProyectoA" value="">-->
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Agregar puesto</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="usr">Puesto:</label>
              <input type="text" class="form-control alpha-only" maxlength="30" id="txtPuesto" name="txtPuesto">
            </div>
            <!--<div class="form-group">
                <label for="usr">Sueldo:</label>
                <input type="number" class="form-control numeric-only" maxlength="30" id="txtSueldo" name="txtSueldo">
              </div>-->
            <div class="form-group">
              <label for="usr">Tipo de pago:</label>
              <select name="cmbTipoPago" class="form-control" id="cmbTipoPago">
                <option value="" style="" disabled selected hidden>Seleccionar</option>
                <?php
                      $stmt = $conn->prepare("SELECT * FROM tipo_pago_nomina");
                      $stmt->execute();
                      $row = $stmt->fetchAll();
                      if (count($row) > 0) {
                  foreach ($row as $r) //Mostrar usuarios
                  {
                      echo '<option value="' . $r['PKPagoNomina'] . '">' . $r['TipoPago'] . '</option>';
                  }

                  } else {
                      echo '<option value="" disabled>No hay registros para mostrar.</option>';
                  }
                  ?>
              </select>
            </div>
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
              id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnAgregarPuesto"><span
                class="ajusteProyecto">Agregar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
  </div>
  <!--END ADD MODAL SLIDE PUESTOS-->
  <!--EDIT MODAL SLIDE PUESTOS-->
  <div class="modal fade right" id="editar_Puesto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
      <div class="modal-content">
        <form action="" id="editarPuesto" method="POST">
          <input type="hidden" name="idPuestoU" id="idPuestoU">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Editar puesto</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

            <div class="form-group">
              <label for="usr">Puesto:</label>
              <input type="text" class="form-control alpha-only" maxlength="30" id="txtPuestoU" name="txtPuestoU">
            </div>
            <!--<div class="form-group">
                <label for="usr">Sueldo:</label>
                <input type="number" class="form-control numeric-only" maxlength="30" id="txtSueldoU" name="txtSueldoU">
              </div>-->
            <div class="form-group">
              <label for="usr">Tipo de pago:</label>
              <select name="cmbTipoPagoU" class="form-control" value="seleccionar" id="cmbTipoPagoU">
                <option value="" style="" disabled selected hidden>Seleccionar</option>
                <?php
              $stmt = $conn->prepare("SELECT * FROM tipo_pago_nomina");
              $stmt->execute();
              $row = $stmt->fetchAll();
              if (count($row) > 0) {
                  foreach ($row as $r) //Mostrar usuarios
                  {
                      echo '<option value="' . $r['PKPagoNomina'] . '">' . $r['TipoPago'] . '</option>';
                  }

              } else {
                  echo '<option value="" disabled>No hay registros para mostrar.</option>';
              }
              ?>
              </select>
            </div>
          </div>
          <div class="modal-footer justify-content-center">
            <a class="btnesp first espEliminar" href="#" onclick="eliminarPuesto(this.value);" name="idPuestoD"
              id="idPuestoD" data-toggle="modal" data-target=""><span class="ajusteProyecto">Eliminar puesto</span></a>
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
              id="btnCancelarActualizacionU"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnEditarPuesto"><span
                class="ajusteProyecto">Agregar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
  </div>
  <!--END EDIT MODAL SLIDE PUESTOS-->



  <script>
  //script agregado para agregar Puestos
  $("#btnAgregarPuesto").click(function() {
    console.log("holaaaa clickeado");
    var puesto = $("#txtPuesto").val().trim();
    var sueldo = $("#txtSueldo").val();
    var tipoPago = $("#cmbTipoPago").val();
    var contTipoP = 0;

    if (puesto.length < 1) {
      $("#txtPuesto")[0].reportValidity();
      $("#txtPuesto")[0].setCustomValidity('Completa este campo.');
      return;
    }
    if (tipoPago == null) {
      if (contTipoP == 0) {
        $("#cmbTipoPago")[0].reportValidity();
        $("#cmbTipoPago")[0].setCustomValidity('Selecciona un tipo de pago.');
      }
      contTipoP = 1;

      $("#cmbTipoPago")[0].reportValidity();
      $("#cmbTipoPago")[0].setCustomValidity('Selecciona un tipo de pago.');
      return;
    }

    /*if(sueldo.length < 1){
      $("#txtSueldo")[0].reportValidity();
      $("#txtSueldo")[0].setCustomValidity('Completa este campo.');
      return;
    }*/

    /*if(sueldo.length < 1){
      $("#cmbTipoPago")[0].reportValidity();
      $("#cmbTipoPago")[0].setCustomValidity('Completa este campo.');
      return;
    }*/

    $.ajax({
      url: "functions/agregar_Puesto.php",
      type: "POST",
      data: {
        "txtPuesto": puesto,
        "txtTipoPago": tipoPago
      },
      success: function(data, status, xhr) {
        if (data.trim() == "exito") {
          $('#agregar_Puesto').modal('toggle');
          $('#agregarPuesto').trigger("reset");
          $('#tblPuestos').DataTable().ajax.reload();
          Lobibox.notify('success', {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/checkmark.svg',
            msg: '¡Registro agregado!'
          });
        } else {
          Lobibox.notify('warning', {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top',
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: 'Ocurrió un error al agregar'
          });
        }
      }
    });
  });
  //end script
  //EDitar Puesto
  $("#btnEditarPuesto").click(function() {
    var id = $('#idPuestoU').val();
    var puesto = $("#txtPuestoU").val().trim();
    var sueldo = $("#txtSueldoU").val();
    var tipoPago = $("#cmbTipoPagoU").val();
    var contTipoP = 0;

    if (puesto.length < 1) {
      $("#txtPuestU")[0].reportValidity();
      $("#txtPuestoU")[0].setCustomValidity('Completa este campo.');
      return;
    }

    /*if(sueldo.length < 1){
      $("#txtSueldoU")[0].reportValidity();
      $("#txtSueldoU")[0].setCustomValidity('Completa este campo.');
      return;
    }*/

    if (tipoPago == "Seleccionar") {
      if (contTipoP == 0) {
        $("#cmbTipoPagoU")[0].reportValidity();
        $("#cmbTipoPagoU")[0].setCustomValidity('Selecciona un tipo de pago.');
      }
      contTipoP = 1;

      $("#cmbTipoPagoU")[0].reportValidity();
      $("#cmbTipoPagoU")[0].setCustomValidity('Selecciona un tipo de pago.');
      return;
    }

    $.ajax({
      url: "functions/editar_Puesto.php",
      type: "POST",
      data: {
        "idPuestoU": id,
        "txtPuestoU": puesto,
        "txtTipoPagoU": tipoPago
      },
      success: function(data, status, xhr) {
        console.log(data);
        if (data.trim() == "exito") {
          $('#editar_Puesto').modal('toggle');
          $('#editarPuesto').trigger("reset");
          $('#tblPuestos').DataTable().ajax.reload();
          Lobibox.notify('success', {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/checkmark.svg',
            msg: '¡Registro modificado!'
          });
        } else {
          Lobibox.notify('warning', {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top',
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: 'Ocurrió un error al modificar'
          });
        }
      }
    });
  });
  //end editar

  function obtenerIdPuestoEditar(id) {
    document.getElementById('idPuestoD').value = id;
    document.getElementById('idPuestoU').value = id;
    var id = "id=" + id;
    $.ajax({
      type: 'POST',
      url: 'functions/traer_datos.php',
      data: id,
      success: function(r) {
        var datos = JSON.parse(r);
        console.log(datos.html21);
        $("#txtPuestoU").val(datos.html);
        $("#txtSueldoU").val(datos.html11);
        $("#cmbTipoPagoU").val(datos.html21);
      }
    });
  }

  function obtenerIdPuestoEliminar(ide) {
    window.location = "functions/editar_puesto.php?ver=" + ide + "";
  }
  $(document).ready(function() {
    /*$("#alertaTareas").load('../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
      setInterval(refrescar, 5000);*/
    $("#alertaTareas").load('../alerta_Tareas_Nuevas.php?user=' + <?=$_SESSION['PKUsuario'];?> + '&ruta=' +
      '<?=$ruta;?>&ruteEdit=<?=$ruteEdit;?>');
  });

  function refrescar() {
    /*$("#alertaTareas").load('../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');*/
    $("#alertaTareas").load('../alerta_Tareas_Nuevas.php?user=' + <?=$_SESSION['PKUsuario'];?> + '&ruta=' +
      '<?=$ruta;?>&ruteEdit=<?=$ruteEdit;?>');
  }

  function eliminarPuesto(id) {

    const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        confirmButton: 'btn',
        cancelButton: 'btn'
      },
      buttonsStyling: false
    })

    swalWithBootstrapButtons.fire({
      title: '¿Desea eliminar el registro de este puesto?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: '<span class="verticalCenter2">Eliminar puesto</span>',
      cancelButtonText: '<span class="verticalCenter2">Cancelar</span>',
      reverseButtons: true
    }).then((result) => {
      if (result.isConfirmed) {

        $.ajax({
          url: "functions/eliminar_Puesto.php",
          type: "POST",
          data: {
            "idPuestoD": id
          },
          success: function(data, status, xhr) {
            if (data == "exito") {
              $('#editar_Puesto').modal('toggle');
              $('#tblPuestos').DataTable().ajax.reload();
              Lobibox.notify('error', {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: true,
                img: '../../img/chat/notificacion_error.svg',
                msg: '¡Registro eliminado!'
              });
            } else {
              Lobibox.notify('warning', {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top',
                icon: true,
                img: '../../img/timdesk/warning_circle.svg',
                msg: 'Ocurrió un error al eliminar'
              });
            }
          }
        });

      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {}
    });

    swal("¿Desea eliminar el registro de este puesto?", {
        buttons: {
          cancel: {
            text: "Cancelar",
            value: null,
            visible: true,
            className: "",
            closeModal: true,
          },
          confirm: {
            text: "Eliminar puesto",
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
            url: "functions/eliminar_Puesto.php",
            type: "POST",
            data: {
              "idPuestoD": id
            },
            success: function(data, status, xhr) {
              if (data == "exito") {
                $('#editar_Puesto').modal('toggle');
                $('#tblPuestos').DataTable().ajax.reload();
                Lobibox.notify('error', {
                  size: 'mini',
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: 'center top', //or 'center bottom'
                  icon: false,
                  img: '../../img/chat/notificacion_error.svg',
                  msg: '¡Registro eliminado!'
                });
              } else {
                Lobibox.notify('warning', {
                  size: 'mini',
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: 'center top',
                  icon: false,
                  img: '../../img/timdesk/warning_circle.svg',
                  msg: 'Ocurrió un error al eliminar'
                });
              }
            }
          });
        }
      });


  }
  </script>


</body>

</html>
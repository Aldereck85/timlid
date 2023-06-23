<?php
session_start();
require_once '../../../include/db-conn.php';
$user = $_SESSION["Usuario"];
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

  <title>Timlid | Puestos</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../../js/sb-admin-2.min.js"></script>
  <script src="../../../js/scripts.js"></script>

  <!-- Page level plugins -->
  <script src="../../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <script src="../../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../../vendor/datatables/buttons.bootstrap4.min.js"></script>
  <script src="../../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../../vendor/jszip/jszip.min.js"></script>
  <script src="../../../js/validaciones.js"></script>
  <script src="../../../js/sweet/sweetalert2.js"></script>
  <script src="../../../js/slimselect.min.js"></script>
  <script src="../../../js/lobibox.min.js"></script>

  <!-- Custom fonts for this template -->
  <link href="../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="../../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../css/styles.css" rel="stylesheet">
  <link href="../../../css/stylesTable.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
  <link href="../../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../../css/slimselect.min.css" rel="stylesheet">

  <script>
  $(document).ready(function() {
    var idioma_espanol = {
      "sProcessing": "Procesando...",
      "sZeroRecords": "No se encontraron resultados",
      "sEmptyTable": "Ningún dato disponible en esta tabla",
      "sSearch": "<img src='../../../img/timdesk/buscar.svg' width='20px' />",
      "sLoadingRecords": "Cargando...",
      searchPlaceholder: "Buscar...",
      "oPaginate": {
        "sFirst": "Primero",
        "sLast": "Último",
        "sNext": "<img src='../../../img/icons/pagination.svg' width='20px'/>",
        "sPrevious": "<img src='../../../img/icons/pagination.svg' width='20px' style='transform: scaleX(-1)'/>"
      },
    }
    $("#tblPuestos").dataTable({
      "language": idioma_espanol,
      "dom": "Bfrtip",
      "buttons": [{
        extend: 'excelHtml5',
        text: '<img class="readEditPermissions" type="submit" width="50px" src="../../../img/excel-azul.svg" />',
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
        }
      ]
    })
  });
  </script>
  <style>
  .nav-link {
    padding: .5rem;
  }
  </style>
</head>

<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
$ruta = "../../";
$ruteEdit = $ruta . "central_notificaciones/";
require_once '../../menu3.php';
?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <?php
$rutatb = "../../";
$titulo = 'Configuraciones';
$icono = '../../../img/inventarios/ICONO INVENTARIOS Y PROD_Mesa de trabajo 1.svg';
require_once '../../topbar.php';
?>
        <!-- Topbar -->

        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="row">
            <div class="col-lg-12">
              <ul class="nav nav-tabs">
                <li class="nav-item">
                  <a id="CargarUsuarios" class="nav-link" href="../">
                    Usuarios
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarPuestos" class="nav-link active" href="../puestos">
                    Puestos
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarTurnos" class="nav-link" href="../turnos">
                    Turnos
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarSucursales" class="nav-link" href="../sucursales">
                    Sucursales
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarCategoriaProductos" class="nav-link" href="../categoria_productos">
                    Categoría de productos
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarMarcaDeProductos" class="nav-link" href="../marca_productos">
                    Marca de productos
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarCategoriaGastos" class="nav-link" href="../categoria_gastos">
                    Categoría gastos
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarSubategoriaGastos" class="nav-link" href="../subcategorias_gastos">
                    Subcategoría de gastos
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarResponsableGastos" class="nav-link" href="../responsables_gastos">
                    Responsable de gastos
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarTipoOrdenInventario" class="nav-link" href="../tipo_orden_inventario">
                    Tipo de orden de inventario
                  </a>
                </li>
              </ul>
            </div>
          </div>
          <!-- DataTales Example -->
          <div class="card mb-4">
            <div class="card-header py-3">
              <div class="float-right">
                <div class="button-container2" id="">
                  <div class="button-icon-container">
                    <a href="" class="btn btn-circle float-right waves-effect waves-light shadow btn-table"
                      id="btn-puestos" data-toggle="modal" data-target="#agregar_Puesto"><i class="fas fa-plus"></i></a>
                  </div>
                  <div class="button-text-container">
                    <span>Agregar puesto</span>
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
$rutaf = "../../";
require_once '../../footer.php';
?>
      <!-- End of Footer -->

    </div> <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->

  <!--ADD MODAL SLIDE PUESTOS-->
  <div class="modal fade right" id="agregar_Puestos_45" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
      <div class="modal-content">
        <form action="" id="agregarPuesto" method="POST">
          <!--<input type="hidden" name="idProyectoA" value="">-->
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Agregar puesto</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <input class="form-control" id="notaPuesto" name="notaPuesto" type="hidden"
              style="color: darkred; background-color: transparent!important; border: none;"
              value="Nota: Nombre de la sucursal ya está registrada." readonly>
            <div class="form-group">
              <label for="usr">Puesto:*</label>
              <input type="text" class="form-control alpha-only" maxlength="30" id="txtPuesto" name="txtPuesto"
                onkeyup="validarUnicoPuesto(this)" required>
              <div class="invalid-feedback" id="invalid-puesto">El nombre del puesto es requerido.</div>
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
  <div class="modal fade right" id="editar_Puestos_45" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
      <div class="modal-content">
        <form action="" id="editarPuesto" method="POST">
          <input type="hidden" name="idPuestoU" id="idPuestoU">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Editar puesto</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">

            <div class="form-group">
              <input class="form-control" id="notaExisteRelacion" name="notaExisteRelacion" type="hidden"
                style="color: darkred; background-color: transparent!important; border: none;"
                value="Nota: El puesto está siendo utilizado." readonly>

              <label for="usr">Puesto:</label>
              <input type="text" class="form-control alpha-only" maxlength="30" id="txtPuestoU" name="txtPuestoU"
                onkeyup="validarUnicoPuesto(this)" required>
              <div class="invalid-feedback" id="invalid-puestoEdit">El nombre del puesto es requerido.</div>
            </div>
          </div>
          <div class="modal-footer justify-content-center">
            <a class="btnesp first espEliminar" href="#" onclick="eliminarPuesto(this.value);" name="idPuestoD"
              id="idPuestoD" data-toggle="modal" data-target=""><span class="ajusteProyecto">Eliminar puesto</span></a>
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
              id="btnCancelarActualizacionU"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnEditarPuesto"><span
                class="ajusteProyecto">Modificar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
  </div>
  <!--END EDIT MODAL SLIDE PUESTOS-->



  <script>
  /* Reiniciar el modal al cerrarlo */
  $("#agregar_Puesto").on("hidden.bs.modal", function(e) {
    $("#invalid-puesto").css("display", "none");
    $("#txtPuesto").removeClass("is-invalid");
    $("#txtPuesto").val("");
  });


  $("#btnAgregarPuesto").click(function() {
    if ($("#agregarPuesto")[0].checkValidity()) {
      var puesto = $("#txtPuesto").val().trim();
      $.ajax({
        url: "functions/agregar_Puesto.php",
        type: "POST",
        data: {
          "txtPuesto": puesto
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
              img: '../../../img/timdesk/checkmark.svg',
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
              img: '../../../img/timdesk/warning_circle.svg',
              msg: 'Ocurrió un error al agregar'
            });
          }
        }
      });
    } else {
      if (!$("#txtPuesto").val()) {
        $("#invalid-puesto").css("display", "block");
        $("#txtPuesto").addClass("is-invalid");
      }
    }
  });

  /* Reiniciar el modal al cerrarlo */
  $("#editar_Puesto").on("hidden.bs.modal", function(e) {
    console.log("entre")
    $("#invalid-puestoEdit").css("display", "none");
    $("#txtPuestoU").removeClass("is-invalid");
    $("#txtPuestoU").val("");
  });


  $("#btnEditarPuesto").click(function() {
    if ($("#editarPuesto")[0].checkValidity()) {
      var badNombrePuesto =
        $("#invalid-puestoEdit").css("display") === "block" ? false : true;
      if (badNombrePuesto) {
        var id = $('#idPuestoU').val();
        var puesto = $("#txtPuestoU").val().trim();

        $.ajax({
          url: "functions/editar_Puesto.php",
          type: "POST",
          data: {
            "idPuestoU": id,
            "txtPuestoU": puesto
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
                img: '../../../img/timdesk/checkmark.svg',
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
                img: '../../../img/timdesk/warning_circle.svg',
                msg: 'Ocurrió un error al modificar'
              });
            }
          }
        });
      }
    } else {
      if (!$("#txtPuestoU").val()) {
        $("#invalid-puestoEdit").css("display", "block");
        $("#txtPuestoU").addClass("is-invalid");
      }
    }
  });

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

        $("#txtPuestoU").val(datos.html);

        var idSucursal = $("#idPuestoU").val();
        if (idSucursal != "") {
          validarRelacionPuesto(idSucursal);
        }
      }
    });
  }

  function obtenerIdPuestoEliminar(ide) {
    window.location = "functions/editar_puesto.php?ver=" + ide + "";
  }

  function eliminarPuesto(id) {
    const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        actions: "d-flex justify-content-around",
        confirmButton: "btn-custom btn-custom--border-blue",
        cancelButton: "btn-custom btn-custom--blue"
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
              Lobibox.notify('success', {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: true,
                img: '../../../img/chat/checkmark.svg',
                msg: '¡Registro eliminado!'
              });
            } else {
              Lobibox.notify('error', {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top',
                icon: true,
                img: '../../../img/timdesk/warning_circle.svg',
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
                  img: '../../../img/chat/notificacion_error.svg',
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
                  img: '../../../img/timdesk/warning_circle.svg',
                  msg: 'Ocurrió un error al eliminar'
                });
              }
            }
          });
        }
      });
  }
  </script>
  <script src="js/puestos.js"></script>

</body>

</html>
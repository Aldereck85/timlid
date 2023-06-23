<?php
session_start();

//if (isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 1)) {
if (isset($_SESSION["Usuario"])) {
    require_once '../../../../include/db-conn.php';
    $user = $_SESSION["Usuario"];
} else {

    header("location:../../../dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="icon" type="image/png" href="../../../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Timlid | Proveedores</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../../js/validaciones.js"></script>
  <link href="../../../../css/lobibox.min.css" rel="stylesheet">
  <script src="../../../../js/lobibox.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../../../js/sb-admin-2.min.js"></script>
  <script src="../../../../js/scripts.js"></script>
  <script src="../../../../js/sweet/sweetalert2.js"></script>
  <script src="../../../../js/slimselect.min.js"></script>

  <!-- Page level plugins -->
  <script src="../../../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="../../../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../../../vendor/datatables/buttons.bootstrap4.min.js"></script>
  <script src="../../../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../../../vendor/jszip/jszip.min.js"></script>

  <!-- Page level custom scripts
  <script src="js/demo/datatables-demo.js"></script>
  -->

  <!-- Custom fonts for this template -->
  <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="../../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../../css/styles.css" rel="stylesheet">
  <link href="../../../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../../../css/stylesTable.css" rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="../../../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link rel="stylesheet" href="../../../../vendor/datatables/buttons.dataTables.css">
  <link href="../../../../vendor/datatables/buttons.bootstrap4.min.css" rel="stylesheet">

  <script>
  $(document).ready(function() {
    var idioma_espanol = {
      "sProcessing": "Procesando...",
      "sZeroRecords": "No se encontraron resultados",
      "sEmptyTable": "Ningún dato disponible en esta tabla",
      "sSearch": "<img src='../../../../img/timdesk/buscar.svg' width='20px' />",
      "sLoadingRecords": "Cargando...",
      searchPlaceholder: "Buscar...",
      "oPaginate": {
        "sFirst": "Primero",
        "sLast": "Último",
        "sNext": "<img src='../../../../img/icons/pagination.svg' width='20px'/>",
        "sPrevious": "<img src='../../../../img/icons/pagination.svg' width='20px' style='transform: scaleX(-1)'/>"
      },
    }
    $("#tblProveedores").dataTable({
      "language": idioma_espanol,
      "dom": "Bfrtip",
      "buttons": [{
        extend: 'excelHtml5',
        text: '<img class="readEditPermissions" type="submit" width="50px" src="../../../../img/excel-azul.svg" />',
        className: "excelDataTableButton",
        titleAttr: 'Excel',
      }],
      "scrollX": true,
      "lengthChange": false,
      "info": false,
      "order": [
        [0, "desc"]
      ],
      "ajax": "functions/function_Proveedores.php",
      "columns": [{
          "data": "Id"
        },
        {
          "data": "Razon Social"
        },
        {
          "data": "Nombre comercial"
        },
        {
          "data": "RFC"
        },
        {
          "data": "Direccion"
        },
        {
          "data": "Colonia"
        },
        {
          "data": "Municipio"
        },
        {
          "data": "Estado"
        },
        {
          "data": "Pais"
        },
        {
          "data": "Codigo Postal"
        },
        {
          "data": "Dias de credito"
        },
        {
          "data": "Limite de credito"
        }
      ]
    })
  });
  </script>
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
$ruta = "../../../";
$ruteEdit = $ruta . "../central_notificaciones/";
require_once '../../../menu3.php';
?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <?php
$rutatb = '../';
$titulo = 'Proveedores';
$icono = '../../../../img/icons/proveedores.svg';
require_once '../../../topbar.php';
?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <!--<h1 class="h3 mb-2 text-gray-800">Proveedores</h1>-->

          <!-- DataTales Example -->
          <div class="">
            <div class="card-header py-3">
              <div class="float-right">
                <div class="button-container2" id="">
                  <div class="button-icon-container">
                    <a href="#" class="btn btn-circle float-right waves-effect waves-light shadow btn-table"
                      id="btn-proyectos" data-toggle="modal" data-target="#agregar_Proveedor">
                      <i class="fas fa-plus"></i>
                    </a>
                  </div>
                  <div class="button-text-container">
                    <span>Agregar proveedor</span>
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table" id="tblProveedores" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Razon Social</th>
                      <th>Nombre comercial</th>
                      <th>RFC</th>
                      <th>Dirección</th>
                      <th>Colonia</th>
                      <th>Municipio</th>
                      <th>Estado</th>
                      <th>Pais</th>
                      <th>CP.</th>
                      <th>Dias de credito</th>
                      <th>Limite de credito</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
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
$rutaf = "../../../";
require_once '../../../footer.php';
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

  <!--INICIO MODAL SLIM AGREGAR PROVEEDOR-->
  <div class="modal fade right" id="agregar_Proveedor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">

    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-md" role="document">


      <div class="modal-content">
        <form action="" id="agregarProveedor" method="POST">
          <!--<input type="hidden" name="idProyectoA" value="">-->
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Agregar Proveedor</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

            <div class="form-group">
              <label for="usr">Razón social:*</label>
              <input type="text" class="form-control alphaNumeric-only" maxlength="30" name="txtRazon" id="txtRazon"
                required>
            </div>
            <div class="form-group">
              <label for="usr">Nombre comercial:*</label>
              <div class="input-group mb-3">
                <input type="text" class="form-control alphaNumeric-only" maxlength="30" name="txtNombre" id="txtNombre"
                  required>
              </div>
            </div>
            <div class="form-group">
              <label for="usr">RFC:*</label>
              <input type="text" class="form-control alphaNumeric-only" maxlength="30" name="txtRFC" id="txtRFC"
                required>
            </div>
            <div class="form-group">
              <label for="usr">Calle:*</label>
              <input type="text" class="form-control alphaNumeric-only" maxlength="30" name="txtCalle" id="txtCalle"
                required>
            </div>
            <div class="form-group">
              <label for="usr">Numero exterior:*</label>
              <div class="input-group mb-3">
                <input type="text" class="form-control numeric-only" maxlength="30" name="txtNumeroEx" id="txtNumeroEx"
                  required>
              </div>
            </div>
            <div class="form-group">
              <label for="usr">Numero interior:</label>
              <input type="text" class="form-control alphaNumeric-only" maxlength="30" name="txtNumeroInt"
                id="txtNumeroInt">
            </div>
            <div class="form-group">
              <label for="usr">Colonia:*</label>
              <input type="text" class="form-control alpha-only" maxlength="30" name="txtColonia" id="txtColonia"
                required>
            </div>
            <div class="form-group">
              <label for="usr">Municipio:*</label>
              <div class="input-group mb-3">
                <input type="text" class="form-control alpha-only" maxlength="30" name="txtMunicipio" id="txtMunicipio"
                  required>
              </div>
            </div>
            <div class="form-group">
              <label for="usr">País:</label>
              <select name="txtPais" class="cmbSelect" id="txtPais" required>
                <!--<option value="" style="" disabled selected hidden>Seleccionar pais</option>-->
                <?php
$stmt = $conn->prepare("SELECT * FROM paises");
$stmt->execute();
$row = $stmt->fetchAll();
if (count($row) > 0) {
    foreach ($row as $r) { //Mostrar usuarios
        if ($r['Disponible'] == 1) {
            echo '<option value="' . $r['PKPais'] . '" selected>' . $r['Pais'] . '</option>';

        } else {
            //echo '<option value="'.$r['PKPais'].'">'.$r['Pais'].'</option>';
        }
    }
} else {
    echo '<option value="" disabled>No hay registros para mostrar.</option>';
}
?>

              </select>

              <!--<input type="text" id="txtarea6" class="form-control alpha-only" size="40" name="txtEstado" required>-->
            </div>
            <div class="form-group">
              <label for="usr">Estado:</label>
              <select name="cmbEstados" class="cmbSelect" placeholder="hola" id="cmbEstados" required>
                <option value="" style="" disabled selected hidden>Seleccionar estado</option>
                <?php
$stmt = $conn->prepare("SELECT * FROM estados_federativos");
$stmt->execute();
$row = $stmt->fetchAll();
if (count($row) > 0) {
    foreach ($row as $r) { //Mostrar usuarios
        echo '<option value="' . $r['PKEstado'] . '">' . $r['Estado'] . '</option>';
    }
} else {
    echo '<option value="" disabled>No hay registros para mostrar.</option>';
}
?>
              </select>
            </div>
            <div class="form-group">
              <label for="usr">Código Postal:*</label>
              <input type="text" class="form-control numeric-only" maxlength="30" name="txtCP" id="txtCP" required>
            </div>
            <div class="form-group">
              <label for="usr">Credito:*</label><br>
              <div class="form-check-inline">
                <label class="form-check-label"><input type="radio" class="form-check-input" value="Si"
                    name="txtCredito" id="txtCredito" required> Si </label>
              </div>
              <div class="form-check-inline">
                <label class="form-check-label"><input type="radio" class="form-check-input" value="No"
                    name="txtCredito" id="txtCredito" required checked> No </label>
              </div>
            </div>
            <div class="form-group" id="datosCredito">
              <label for="txtDiasCredito">Dias de credito</label>
              <input class="form-control numeric-only" type="text" name="txtDiasCredito" id="txtDiasCredito">
              <label for="txtLimiteCredito">Limite de credito</label>
              <input class="form-control numericDecimal-only" type="text" name="txtLimiteCredito" id="txtLimiteCredito">
            </div>
            <h4>Datos del vendedor oficial</h4>
            <div class="form-group">
              <label for="usr">Nombre(s):*</label>
              <input class="form-control" type="text" name="txtContacto" id="txtContacto" value="" required>
            </div>
            <div class="form-group">
              <label for="usr">Apellido:*</label>
              <input class="form-control" type="text" name="txtApellido" id="txtApellido" value="" required>
            </div>
            <div class="form-group">
              <label for="usr">Telefono:*</label>
              <input class="form-control numeric-only" type="text" maxlength="10" name="txtTelefono" id="txtTelefono"
                value="" required>
            </div>
            <div class="form-group">
              <label for="usr">Celular:</label>
              <input class="form-control numeric-only" type="text" maxlength="10" name="txtCelular" id="txtCelular"
                value="">
            </div>
            <div class="form-group">
              <label for="usr">Email:*</label>
              <input class="form-control" type="text" name="txtEmail" id="txtEmail" value="" required>
            </div>
          </div>
          <div class="modal-footer justify-content-center">

            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
              id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnAgregarProveedor"><span
                class="ajusteProyecto">Agregar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
  </div>
  <!--FIN MODAL SLIM AGREGAR PROVEEDOR-->
  <!--INICIO MODAL SLIM UPDATE PROVEEDOR-->
  <div class="modal fade right" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">

    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-md" role="document">


      <div class="modal-content">
        <form action="" id="editarProveedor" method="POST">
          <input type="hidden" name="idProveedorU" id="idProveedorU">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Editar Proveedor</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

            <div class="form-group">
              <label for="usr">Razón social:*</label>
              <input type="text" class="form-control alphaNumeric-only" maxlength="30" name="txtRazonU" id="txtRazonU"
                required>
            </div>
            <div class="form-group">
              <label for="usr">Nombre comercial:*</label>
              <div class="input-group mb-3">
                <input type="text" class="form-control alphaNumeric-only" maxlength="30" name="txtNombreU"
                  id="txtNombreU" required>
              </div>
            </div>
            <div class="form-group">
              <label for="usr">RFC:*</label>
              <input type="text" class="form-control alphaNumeric-only" maxlength="30" name="txtRFCU" id="txtRFCU"
                required>
            </div>
            <div class="form-group">
              <label for="usr">Calle:*</label>
              <input type="text" class="form-control alphaNumeric-only" maxlength="30" name="txtCalleU" id="txtCalleU"
                required>
            </div>
            <div class="form-group">
              <label for="usr">Numero exterior:*</label>
              <div class="input-group mb-3">
                <input type="text" class="form-control numeric-only" maxlength="30" name="txtNumeroExU"
                  id="txtNumeroExU" required>
              </div>
            </div>
            <div class="form-group">
              <label for="usr">Numero interior:</label>
              <input type="text" class="form-control alphaNumeric-only" maxlength="30" name="txtNumeroIntU"
                id="txtNumeroIntU">
            </div>
            <div class="form-group">
              <label for="usr">Colonia:*</label>
              <input type="text" class="form-control alpha-only" maxlength="30" name="txtColoniaU" id="txtColoniaU"
                required>
            </div>
            <div class="form-group">
              <label for="usr">Municipio:*</label>
              <div class="input-group mb-3">
                <input type="text" class="form-control alpha-only" maxlength="30" name="txtMunicipioU"
                  id="txtMunicipioU" required>
              </div>
            </div>
            <div class="form-group">
              <label for="usr">País:</label>
              <select name="cmbPaisU" class="" id="cmbPaisU" required>
                <!--<option value="" style="" disabled selected hidden>Seleccionar pais</option>-->
                <?php
$stmt = $conn->prepare("SELECT * FROM paises");
$stmt->execute();
$row = $stmt->fetchAll();
if (count($row) > 0) {
    foreach ($row as $r) { //Mostrar usuarios
        if ($r['Disponible'] == 1) {
            echo '<option value="' . $r['PKPais'] . '" selected>' . $r['Pais'] . '</option>';
            //$pais = $r['PKPais'];
        } else {
            //echo '<option value="'.$r['PKPais'].'">'.$r['Pais'].'</option>';

        }
    }
} else {
    echo '<option value="" disabled>No hay registros para mostrar.</option>';
}
?>
              </select>

              <!--<input type="text" id="txtarea6" class="form-control alpha-only" size="40" name="txtEstado" required>-->
            </div>
            <div class="form-group">
              <label for="usr">Estado:</label>
              <select name="cmbEstadosU" class="" id="cmbEstadosU" required>
                <!--<option value="" style="" disabled selected hidden>Seleccionar estado</option>-->
                <?php
$stmt = $conn->prepare("SELECT * FROM estados_federativos");
$stmt->execute();
$row = $stmt->fetchAll();
if (count($row) > 0) {
    foreach ($row as $r) { //Mostrar usuarios
        echo '<option value="' . $r['PKEstado'] . '" selected>' . $r['Estado'] . '</option>';
    }
} else {
    echo '<option value="" disabled>No hay registros para mostrar.</option>';
}
?>
              </select>
            </div>


            <div class="form-group">
              <label for="usr">Código Postal:*</label>
              <input type="text" class="form-control numeric-only" maxlength="30" name="txtCPU" id="txtCPU" required>
            </div>
            <div class="form-group">
              <label for="usr">Credito:*</label><br>
              <div class="form-check-inline">
                <label class="form-check-label"><input type="radio" class="form-check-input" value="Si"
                    name="txtCreditoU" id="txtCreditoU" required> Si </label>
              </div>
              <div class="form-check-inline">
                <label class="form-check-label"><input type="radio" class="form-check-input" value="No"
                    name="txtCreditoU2" id="txtCreditoU2" required checked> No </label>
              </div>
            </div>
            <div class="form-group" id="datosCreditoU">
              <label for="txtDiasCreditoU">Dias de credito</label>
              <input class="form-control numeric-only" type="text" name="txtDiasCreditoU" id="txtDiasCreditoU">
              <label for="txtLimiteCreditoU">Limite de credito</label>
              <input class="form-control numericDecimal-only" type="text" name="txtLimiteCreditoU"
                id="txtLimiteCreditoU">
            </div>
            <h4>Datos del vendedor oficial</h4>
            <div class="form-group">
              <label for="usr">Nombre(s):*</label>
              <input class="form-control" type="text" name="txtContactoU" id="txtContactoU" value="" required>
            </div>
            <div class="form-group">
              <label for="usr">Apellido:*</label>
              <input class="form-control" type="text" name="txtApellidoU" id="txtApellidoU" value="" required>
            </div>
            <div class="form-group">
              <label for="usr">Telefono:*</label>
              <input class="form-control numeric-only" type="text" maxlength="10" name="txtTelefonoU" id="txtTelefonoU"
                value="" required>
            </div>
            <div class="form-group">
              <label for="usr">Celular:</label>
              <input class="form-control numeric-only" type="text" maxlength="10" name="txtCelularU" id="txtCelularU"
                value="">
            </div>
            <div class="form-group">
              <label for="usr">Email:*</label>
              <input class="form-control" type="text" name="txtEmailU" id="txtEmailU" value="" required>
            </div>
          </div>
          <div class="modal-footer justify-content-center">
            <a class="btnesp first espEliminar" href="#" onclick="eliminarProveedor(this.value);" name="idProveedorD"
              id="idProveedorD" data-toggle="modal" data-target=""><span class="ajusteProyecto">Eliminar
                locacion</span></a>
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
              id="btnCancelarActualizacionU"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnEditarProveedor"><span
                class="ajusteProyecto">Agregar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
  </div>
  <!--FIN MODAL SLIM UPDATE PROVEEDOR-->


  <script>
  $(document).ready(function() {
    $('#cmbPaisU').on('change', function() {
      var id = $('#cmbPaisU').val();
      $.ajax({
        url: "functions/getEstados.php",
        type: "POST",
        data: {
          id: id
        },
        success: function(resp) {
          $("#cmbEstadosU").html(resp);
        }
      })
    });
  });

  function obtenerIdProveedorEliminar(id) {
    document.getElementById('idProveedorD').value = id;
  }

  function obtenerIdProveedorEditar(id) {
    document.getElementById('idProveedorU').value = id;
    document.getElementById('idProveedorD').value = id;
    var id = "id=" + id;
    console.log(id);

    $.ajax({
      type: 'POST',
      url: 'functions/ver_Proveedor.php',
      data: id,
      success: function(r) {
        var datos = JSON.parse(r);
        $("#txtRazonU").val(datos.razon);
        $("#txtNombreU").val(datos.nombre);
        $("#txtRFCU").val(datos.rfc);
        $("#txtCalleU").val(datos.calle);
        $("#txtNumeroExU").val(datos.numEx);
        $("#txtNumeroIntU").val(datos.numInt);
        $("#txtColoniaU").val(datos.colonia);
        $("#cmbEstadosU").val(datos.estado);
        select.set(datos.estado);
        $("#txtMunicipioU").val(datos.municipio);
        $("#cmbPaisU").val(datos.pais);
        $("#txtCPU").val(datos.cp);
        if (datos.dias > 0) {
          $('#datosCreditoU').show();
          $("#txtCreditoU").prop('checked', true);
          $("#txtCreditoU2").prop('checked', false);
          $("#txtDiasCreditoU").val(datos.dias);
          $("#txtLimiteCreditoU").val(datos.limite);
        } else { //if(datos.dias == 'null')
          $('#datosCreditoU').hide();
          $("#txtCreditoU").prop('checked', false);
          $("#txtCreditoU2").prop('checked', true);
        }

        $("#txtContactoU").val(datos.contacto);
        $("#txtApellidoU").val(datos.apellido);
        $("#txtTelefonoU").val(datos.telefono);
        $("#txtCelularU").val(datos.celular);
        $("#txtEmailU").val(datos.email);
      }
    });
  }
  $("#btnAgregarProveedor").click(function() {
    var razon = $("#txtRazon").val();
    var nombre = $("#txtNombre").val();
    var rfc = $("#txtRFC").val();
    var calle = $("#txtCalle").val();
    var numeroext = $("#txtNumeroEx").val();
    var numeroint = $("#txtNumeroInt").val();
    var colonia = $("#txtColonia").val();
    var municipio = $("#txtMunicipio").val();
    var pais = $("#txtPais").val();
    var estado = $("#cmbEstados").val();
    var cp = $("#txtCP").val();
    var diascredito = $("#txtDiasCredito").val();
    var limitepractico = $("#txtLimiteCredito").val();

    var contacto = $("#txtContacto").val();
    var apellido = $("#txtApellido").val();
    var telefono = $("#txtTelefono").val();
    var celular = $("#txtCelular").val();
    var email = $("#txtEmail").val();

    $.ajax({
      url: "functions/agregar_Proveedor.php",
      type: "POST",
      data: {
        "txtRazon": razon,
        "txtNombre": nombre,
        "txtRFC": rfc,
        "txtCalle": calle,
        "txtNumeroEx": numeroext,
        "txtNumeroInt": numeroint,
        "txtColonia": colonia,
        "txtMunicipio": municipio,
        "txtPais": pais,
        "cmbEstados": estado,
        "txtCP": cp,
        "txtDiasCredito": diascredito,
        "txtLimiteCredito": limitepractico,
        "txtContacto": contacto,
        "txtApellido": apellido,
        "txtTelefono": telefono,
        "txtCelular": celular,
        "txtEmail": email
      },
      success: function(data, status, xhr) {
        if (data.trim() == "exito") {
          $('#agregar_Proveedor').modal('toggle');
          $('#agregarProveedor').trigger("reset");
          $('#tblProveedores').DataTable().ajax.reload();
          Lobibox.notify('success', {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: true,
            //img: '<i class="fas fa-check-circle"></i>',
            img: '../../../../img/timdesk/checkmark.svg',
            msg: '¡Registro agregado!'
          });
        } else {
          Lobibox.notify('warning', {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top',
            icon: true,
            img: '../../../../img/timdesk/warning_circle.svg',
            img: null,
            msg: 'Ocurrió un error al agregar'
          });
        }
      }
    });
  });

  $("#btnEditarProveedor").click(function() {
    var id = $('#idProveedorU').val();
    var razon = $("#txtRazonU").val();
    var nombre = $("#txtNombreU").val();
    var rfc = $("#txtRFCU").val();
    var calle = $("#txtCalleU").val();
    var numeroext = $("#txtNumeroExU").val();
    var numeroint = $("#txtNumeroIntU").val();
    var colonia = $("#txtColoniaU").val();
    var municipio = $("#txtMunicipioU").val();
    var pais = $("#cmbPaisU").val();
    var estado = $("#cmbEstadosU").val();
    var cp = $("#txtCPU").val();
    var diascredito = $("#txtDiasCreditoU").val();
    var limitepractico = $("#txtLimiteCreditoU").val();

    var contacto = $("#txtContactoU").val();
    var apellido = $("#txtApellidoU").val();
    var telefono = $("#txtTelefonoU").val();
    var celular = $("#txtCelularU").val();
    var email = $("#txtEmailU").val();

    $.ajax({
      url: "functions/editar_Proveedor.php",
      type: "POST",
      data: {
        "txtId": id,
        "txtRazon": razon,
        "txtNombre": nombre,
        "txtRFC": rfc,
        "txtCalle": calle,
        "txtNumeroEx": numeroext,
        "txtNumeroInt": numeroint,
        "txtColonia": colonia,
        "txtMunicipio": municipio,
        "txtPais": pais,
        "cmbEstados": estado,
        "txtCP": cp,
        "txtDiasCredito": diascredito,
        "txtLimiteCredito": limitepractico,
        "txtContacto": contacto,
        "txtApellido": apellido,
        "txtTelefono": telefono,
        "txtCelular": celular,
        "txtEmail": email
      },
      success: function(data, status, xhr) {
        if (data.trim() == "exito") {
          $('#modalEditar').modal('toggle');
          $('#editarProveedor').trigger("reset");
          $('#tblProveedores').DataTable().ajax.reload();
          Lobibox.notify('success', {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: true,
            //img: '<i class="fas fa-check-circle"></i>',
            img: '../../../../img/timdesk/checkmark.svg',
            msg: '¡Registro modificado!'
          });
        } else {
          Lobibox.notify('warning', {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top',
            icon: true,
            img: '../../../../img/timdesk/warning_circle.svg',
            img: null,
            msg: 'Ocurrió un error al agregar'
          });
        }
      }
    });
  });

  function eliminarProveedor(id) {
    console.log(id);

    const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        confirmButton: 'btn',
        cancelButton: 'btn'
      },
      buttonsStyling: false
    })

    swalWithBootstrapButtons.fire({
      title: '¿Desea eliminar el registro de este proveedor?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: '<span class="verticalCenter2">Eliminar locación</span>',
      cancelButtonText: '<span class="verticalCenter2">Cancelar</span>',
      reverseButtons: true
    }).then((result) => {
      if (result.isConfirmed) {

        $.ajax({
          url: "functions/eliminar_Proveedor.php",
          type: "POST",
          data: {
            "idProveedorD": id
          },
          success: function(data, status, xhr) {
            if (data == "exito") {
              $('#modalEditar').modal('toggle');
              $('#tblProveedores').DataTable().ajax.reload();
              Lobibox.notify('error', {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: true,
                img: '../../../../img/chat/notificacion_error.svg',
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
                img: '../../../../img/timdesk/warning_circle.svg',
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

    swal("¿Desea eliminar el registro de este proveedor?", {
        buttons: {
          cancel: {
            text: "Cancelar",
            value: null,
            visible: true,
            className: "",
            closeModal: true,
          },
          confirm: {
            text: "Eliminar proveedor",
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
            url: "functions/eliminar_Proveedor.php",
            type: "POST",
            data: {
              "idProveedorD": id
            },
            success: function(data, status, xhr) {
              if (data == "exito") {
                $('#modalEditar').modal('toggle');
                $('#tblProveedores').DataTable().ajax.reload();
                Lobibox.notify('error', {
                  size: 'mini',
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: 'center top', //or 'center bottom'
                  icon: false,
                  img: '../../../../img/timdesk/notificacion_error.svg',
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
                  img: '../../../../img/timdesk/warning_circle.svg',
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






  $(document).ready(function() {
    /*$("#alertaTareas").load('../alerta_Tareas_Nuevas.php?user='+<?//=$_SESSION['PKUsuario'];?>+'&ruta='+'<?//=$ruta;?>');
      setInterval(refrescar, 5000);*/
    $("#alertaTareas").load('../alerta_Tareas_Nuevas.php?user=' + <?=$_SESSION['PKUsuario'];?> + '&ruta=' +
      '<?=$ruta;?>&ruteEdit=<?=$ruteEdit;?>');

    $('#datosCredito').hide();
    $("input[type=radio]").click(function(evento) {
      var valor = $(event.target).val();

      if (valor == 'Si') {
        $('#datosCredito').show();
      } else if (valor == 'No') {
        $('#nada').show();
        $('#datosCredito').hide();
      }
    });
    $('#datosCreditoU').hide();
    $("input[type=radio]").click(function(evento) {
      var valor = $(event.target).val();

      if (valor == 'Si') {
        $('#datosCreditoU').show();
        //$("#txtCreditoU").prop('checked', false);
        $("#txtCreditoU2").prop('checked', false);
      } else if (valor == 'No') {
        $('#nada').show();
        $('#datosCreditoU').hide();
        $("#txtCreditoU").prop('checked', false);
      }
    });
    new SlimSelect({
      select: '#txtPais'
    });
    new SlimSelect({
      select: '#cmbEstados'
    });
    new SlimSelect({
      select: '#cmbPaisU'
    });
    /*$("#cmbEstadosU").click(function(){
      $("#cmbEstadosU").removeClass("form-control");
      var slim = new SlimSelect({
        select: '#cmbEstadosU',
        afterClose: function () {
          slim.destroy()
        }
      });
    });*/
    select = new SlimSelect({
      select: '#cmbEstadosU'
    });
    $('#txtPais').on('change', function() {
      var id = $('#txtPais').val();
      $.ajax({
        url: "functions/getEstados.php",
        type: "POST",
        data: {
          id: id
        },
        success: function(resp) {
          $("#cmbEstados").html(resp);
        }
      })

    });

  });

  function refrescar() {
    /*$("#alertaTareas").load('../alerta_Tareas_Nuevas.php?user='+<?//=$_SESSION['PKUsuario'];?>+'&ruta='+'<?//=$ruta;?>');*/
    $("#alertaTareas").load('../alerta_Tareas_Nuevas.php?user=' + <?=$_SESSION['PKUsuario'];?> + '&ruta=' +
      '<?=$ruta;?>&ruteEdit=<?=$ruteEdit;?>');
  }
  </script>
  <script>
  var ruta = "../";
  </script>
  <script src="../../../../js/sb-admin-2.min.js"></script>
  <script src="../../../../js/scripts.js"></script>


</body>

</html>
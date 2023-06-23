<?php
session_start();

if (isset($_SESSION["Usuario"])) {
    require_once '../../include/db-conn.php';
    $user = $_SESSION["Usuario"];
    $pkusuario = $_SESSION["PKUsuario"];
    $ruta = "../";
    $screen = 25;
    require_once $ruta . 'validarPermisoPantalla.php';
    if($permiso === 0){
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

  <title>Timlid | Sucursales</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../js/validaciones.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/sweet/sweetalert2.js"></script>

  <!-- Page level plugins -->
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.responsive.js"></script>
  <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.bootstrap4.min.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>
  <script src="../../js/slimselect.min.js"></script>

  <!-- Page level custom scripts
  <script src="js/demo/datatables-demo.js"></script>
  -->

  <!-- Custom fonts for this template -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="../../css/stylesTable.css" rel="stylesheet">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <script src="../../js/lobibox.min.js"></script>

  <!-- Custom styles for this page -->
  <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link rel="stylesheet" href="../../vendor/datatables/buttons.dataTables.css">
  <link href="../../vendor/datatables/buttons.bootstrap4.min.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet">

  <script>
  /*$(document).ready(function() {
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
    $("#tblLocaciones").dataTable({
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
        "order": [
          [0, "desc"]
        ],
        "ajax": "functions/function_Locacion.php",
        "columns": [{
            "data": "id"
          },
          {
            "data": "Locacion"
          },
          {
            "data": "Domicilio"
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
            "data": "Telefono"
          }
        ],
        columnDefs: [{
          orderable: false,
          targets: 0,
          visible: false
        }],
        responsive: true,

      }

    )
  });*/
  </script>
</head>

<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
      $icono = '../../img/icons/locaciones.svg';
      $titulo = '<div class="header-screen d-flex align-items-center">
                        <div class="header-title-screen">
                          <h1 class="h3">Sucursales </h1>
                        </div>
                      </div>';
      
      $ruteEdit = $ruta . "central_notificaciones/";
      require_once $ruta . 'menu3.php';
      
      
    ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <input type="hidden" id="txtUsuario" value="<?=$_SESSION['PKUsuario'];?>">
      <input type="hidden" id="txtRuta" value="<?=$ruta;?>">
      <input type="hidden" id="txtEdit" value="<?=$ruteEdit;?>">
      <input type="hidden" id="txtPantalla" value="<?=$screen;?>">
      <!-- Main Content -->
      <div id="content">

      <?php
        $rutatb = "../";
        require_once '../topbar.php';
      ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">


          <!-- DataTales Example -->
          <div class="card mb-4">
            <div class="card-header py-3">
              <div class="float-right">
                <div class="button-container2" id="">
                  <div class="button-icon-container">
                    <a href="#" class="btn btn-circle float-right waves-effect waves-light shadow btn-table"
                      id="btn-proyectos" data-toggle="modal" data-target="#agregar_Locacion">
                      <i class="fas fa-plus"></i>
                    </a>
                  </div>
                  <div class="button-text-container">
                    <span>Agregar sucursal</span>
                  </div>
                </div>
              </div>
            </div>
            <br>
            <!--<div class="card-header py-3">
              <a href="functions/agregar_Locacion.php" class="btn btn-success float-right" ><i class="fas fa-plus"></i> Agregar locacion </a>
            </div>-->
            <div class="card-body" style="">
              <div class="table-responsive">
                <table class="table" id="tblSucursales" " width=" 100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>id</th>
                      <th>Sucursal</th>
                      <th>Domicilio</th>
                      <th>Colonia</th>
                      <th>Municipio</th>
                      <th>Estado</th>
                      <th>País</th>
                      <th>Teléfono</th>
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
  <div class="modal fade right" id="agregar_Locacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">

    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-md" role="document">


      <div class="modal-content">
        <form action="" id="agregarLocacion" method="POST">
          <!--<input type="hidden" name="idProyectoA" value="">-->
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Agregar sucursal</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

            <div class="form-group">
              <label for="usr">Nombre de sucursal:</label>
              <input type="text" id="txtarea" class="form-control alpha-only" maxlength="40" name="txtLocacion"
                required>
            </div>
            <div class="form-group">
              <label for="usr">Calle:</label>
              <input type="text" id="txtarea2" class="form-control alpha-only" name="txtCalle" maxlength="50" required>
            </div>
            <div class="form-group">
              <label for="usr">Número exterior:</label>
              <input type="text" id="txtarea3" class="form-control alphaNumeric-only" name="txtNe">
            </div>
            <div class="form-group">
              <label for="usr">Número interior:</label>
              <div class="input-group">
                <select name="cmbPrefijo" class="form-control" placeholder="hola" id="txtarea9">
                  <option value="" disabled selected hidden>Seleccionar</option>
                  <option value="Interior">Interior</option>
                  <option value="Bodega">Bodega</option>
                  <option value="Piso">Piso</option>
                  <option value="Departamento">Departamento</option>
                </select>
                <input type="text" id="txtarea4" style="width:40%;" class="form-control alphaNumeric-only"
                  style="text-transform: uppercase" name="txtNi">
              </div>
            </div>
            <div class="form-group">
              <label for="usr">Colonia:</label>
              <input type="text" id="txtarea5" class="form-control alpha-only" size="40" name="txtColonia" required>
            </div>
            <div class="form-group">
              <label for="usr">Municipio:</label>
              <input type="text" id="txtarea7" class="form-control alphaNumeric-only" size="40" name="txtMunicipio"
                required>
            </div>
            <div class="form-group">
              <label for="usr">País:</label>
              <select name="cmbPais" class="" id="txtarea8" required>
                <!--<option value="" style="" disabled selected hidden>Seleccionar pais</option>-->
                <?php
$stmt = $conn->prepare("SELECT * FROM paises");
$stmt->execute();
$row = $stmt->fetchAll();

if (count($row) > 0) {
    foreach ($row as $r) { //Mostrar usuarios
        if ($r['Disponible'] == 1) {
            echo '<option value="' . $r['PKPais'] . '" selected>' . $r['Pais'] . '</option>';
            $pais = $r['PKPais'];
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
              <select name="cmbEstados" class="" placeholder="hola" id="txtarea6" required>
                <option value="" style="" disabled selected hidden>Seleccionar estado</option>
                <?php
$stmt = $conn->prepare("SELECT * FROM estados_federativos");
$stmt->execute();
$row = $stmt->fetchAll();
if (count($row) > 0) {
    foreach ($row as $r) { //Mostrar usuarios
        echo '<option value="' . $r['PKEstado'] . '" >' . $r['Estado'] . '</option>';
    }
} else {
    echo '<option value="" disabled>No hay registros para mostrar.</option>';
}
?>
              </select>
              <!--<input type="text" id="txtarea6" class="form-control alpha-only" size="40" name="txtEstado" required>-->
            </div>
            <div class="form-group">
              <label for="usr">Teléfono:</label>
              <input type="text" id="txtarea10" maxlength="10" class="form-control numeric-only" name="txtTelefono"
                required>
            </div>
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
              id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnAgregarLocacion"><span
                class="ajusteProyecto">Agregar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
  </div>
  <!-- End Add Fluid Modal mis proyectos -->
  <!--UPDATE MODAL DENTRO DE PROYECTOS 04/09/2020-->
  <div class="modal fade right" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-full-height modal-right modal-md" role="document">
      <div class="modal-content">
        <form action="" id="editarLocacionU" method="POST">
          <input type="hidden" name="idLocacionU" id="idLocacionU">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Editar sucursal</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="usr">Nombre de sucursal:</label>
              <input type="text" id="txtareau" class="form-control alpha-only" maxlength="40" name="txtLocacionU"
                value="" required>
            </div>
            <div class="form-group">
              <label for="usr">Calle:</label>
              <input type="text" id="txtarea2u" class="form-control " maxlength="40" name="txtCalleU" required>
            </div>
            <div class="form-group">
              <label for="usr">Numero exterior:</label>
              <input type="text" id="txtarea3u" class="form-control alphaNumeric-only" name="txtNeU" required>
            </div>
            <div class="form-group">
              <label for="usr">Numero interior:</label>

              <div class="input-group">
                <select name="cmbPrefijoU" class="form-control" placeholder="hola" id="txtarea9u">
                  <!--<option value="">Seleccionar</option>-->
                  <option value="Interior">Interior</option>
                  <option value="Bodega">Bodega</option>
                  <option value="Piso">Piso</option>
                  <option value="Departamento">Departamento</option>
                </select>
                <input type="text" id="txtarea4u" style="width:40%;" class="form-control alphaNumeric-only"
                  name="txtNiU">
              </div>
            </div>
            <div class="form-group">
              <label for="usr">Colonia:</label>
              <input type="text" id="txtarea5u" class="form-control alpha-only" size="40" name="txtColoniaU" required>
            </div>
            <div class="form-group">
              <label for="usr">Municipio:</label>
              <input type="text" id="txtarea7u" class="form-control alphaNumeric-only" size="40" name="txtMunicipioU"
                required>
            </div>
            <div class="form-group">
              <label for="usr">País:</label>
              <select name="cmbPaisU" class="" id="txtarea8u" required>
                <!--<option value="Elegir" id="AF">Elegir opción</option>-->
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
            </div>
            <div class="form-group">
              <label for="usr">Estado:</label>
              <select name="cmbEstadosU" class="" id="txtarea6u" required>
                <?php
$stmt = $conn->prepare("SELECT * FROM estados_federativos");
$stmt->execute();
$row = $stmt->fetchAll();
if (count($row) > 0) {
    foreach ($row as $r) { //Mostrar usuarios
        echo '<option value="' . $r['PKEstado'] . '" >' . $r['Estado'] . '</option>';
    }
} else {
    echo '<option value="" disabled>No hay registros para mostrar.</option>';
}
?>
              </select>

              <!--<input type="text" id="txtarea6" class="form-control alpha-only" size="40" name="txtEstado" required>-->
            </div>

            <div class="form-group">
              <label for="usr">Teléfono:</label>
              <input type="text" maxlength="10" id="txtarea10u" class="form-control alphaNumeric-only" size="40"
                name="txtTelefono" required>
            </div>

            <div class="modal-footer justify-content-center">
              <a class="btnesp first espEliminar" href="#" onclick="eliminarLocacion(this.value);" name="idLocacionD"
                id="idLocacionD" data-toggle="modal" data-target=""><span class="ajusteProyecto">Eliminar
                  locacion</span></a>
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
                0id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
              <!--<input type="submit" class="btn btn-primary float-right" name="btnEditar" id="btnEditar" value="Guardar">-->
              <button type="button" class="btnesp espAgregar float-right" name="btnEditar" id="btnEditarLocacion"
                value=""><span class="ajusteProyecto">Guardar</span></button>
            </div>
        </form>
      </div>
    </div>
  </div>
  <!--END UPDATE MODAL DENTRO DE PROYECTOS-->


  <script>
  $(document).ready(function() {
    $('#txtarea8u').on('change', function() {
      var id = $('#txtarea8u').val();
      $.ajax({
        url: "functions/getEstados.php",
        type: "POST",
        data: {
          id: id
        },
        success: function(resp) {
          $("#txtarea6u").html(resp);
        }
      })
    });
  });
  $("#btnAgregarLocacion").click(function() {
    var nombreSucursal = $("#txtarea").val().trim();
    var calle = $("#txtarea2").val();
    var numExterior = $("#txtarea3").val();
    var prefijo = $("#txtarea9").val();
    var numInterior = $("#txtarea4").val();
    var colonia = $("#txtarea5").val();
    var municipio = $("#txtarea7").val();
    var estado = $("#txtarea6").val();
    var pais = $("#txtarea8").val();
    var telefono = $("#txtarea10").val();
    var contPais = 0;
    var contEstado = 0;
    console.log(telefono);

    if (nombreSucursal.length < 1) {
      $("#txtarea")[0].reportValidity();
      $("#txtarea")[0].setCustomValidity('Completa este campo.');
      return;
    }

    if (calle.length < 1) {
      $("#txtarea2")[0].reportValidity();
      $("#txtarea2")[0].setCustomValidity('Completa este campo.');
      return;
    }

    if (numExterior.length < 1) {
      $("#txtarea3")[0].reportValidity();
      $("#txtarea3")[0].setCustomValidity('Completa este campo.');
      return;
    }

    if (colonia.length < 1) {
      $("#txtarea5")[0].reportValidity();
      $("#txtarea5")[0].setCustomValidity('Completa este campo.');
      return;
    }

    if (municipio.length < 1) {
      $("#txtarea7")[0].reportValidity();
      $("#txtarea7")[0].setCustomValidity('Completa este campo.');
      return;
    }

    if (estado == "Estado") {
      if (contPais == 0) {
        $("#txtarea6")[0].reportValidity();
        $("#txtarea6")[0].setCustomValidity('Selecciona un estado.');
      }
      contPais = 1;
      $("#txtarea6")[0].reportValidity();
      $("#txtarea6")[0].setCustomValidity('Selecciona un estado.');
      return;
    }

    if (pais == "Elegir") {
      if (contEstado == 0) {
        $("#txtarea8")[0].reportValidity();
        $("#txtarea8")[0].setCustomValidity('Selecciona un país.');
      }
      contEstado = 1;

      $("#txtarea8")[0].reportValidity();
      $("#txtarea8")[0].setCustomValidity('Selecciona un país.');
      return;
    }

    $.ajax({
      url: "functions/agregar_Locacion.php",
      type: "POST",
      data: {
        "txtLocacion": nombreSucursal,
        "txtCalle": calle,
        "txtNe": numExterior,
        "prefijo": prefijo,
        "txtNi": numInterior,
        "txtColonia": colonia,
        "txtMunicipio": municipio,
        "cmbEstados": estado,
        "cmbPais": pais,
        "telefono": telefono
      },
      success: function(data, status, xhr) {
        if (data.trim() == "exito") {
          $('#agregar_Locacion').modal('toggle');
          $('#agregarLocacion').trigger("reset");
          $('#tblLocaciones').DataTable().ajax.reload();
          Lobibox.notify('success', {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: true,
            //img: '<i class="fas fa-check-circle"></i>',
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
            icon: true,
            img: '../../img/timdesk/warning_circle.svg',
            img: null,
            msg: 'Ocurrió un error al agregar'
          });
        }
      }
    });
  });
  $("#btnEditarLocacion").click(function() {
    var id = $('#idLocacionU').val();
    var nombreSucursal = $("#txtareau").val().trim();
    var calle = $("#txtarea2u").val();
    var numExterior = $("#txtarea3u").val();
    var prefijo = $("#txtarea9u").val();
    var numInterior = $("#txtarea4u").val();
    var colonia = $("#txtarea5u").val();
    var municipio = $("#txtarea7u").val();
    var estado = $("#txtarea6u").val();
    var pais = $("#txtarea8u").val();
    var telefono = $("#txtarea10u").val();
    var contPais = 0;
    var contEstado = 0;

    if (nombreSucursal.length < 1) {
      $("#txtareau")[0].reportValidity();
      $("#txtareau")[0].setCustomValidity('Completa este campo.');
      return;
    }

    if (calle.length < 1) {
      $("#txtarea2u")[0].reportValidity();
      $("#txtarea2u")[0].setCustomValidity('Completa este campo.');
      return;
    }

    if (numExterior.length < 1) {
      $("#txtarea3u")[0].reportValidity();
      $("#txtarea3u")[0].setCustomValidity('Completa este campo.');
      return;
    }

    if (colonia.length < 1) {
      $("#txtarea5u")[0].reportValidity();
      $("#txtarea5u")[0].setCustomValidity('Completa este campo.');
      return;
    }

    if (municipio.length < 1) {
      $("#txtarea7u")[0].reportValidity();
      $("#txtarea7u")[0].setCustomValidity('Completa este campo.');
      return;
    }

    if (estado == "Estado") {
      if (contPais == 0) {
        $("#txtarea6u")[0].reportValidity();
        $("#txtarea6u")[0].setCustomValidity('Selecciona un estado.');
      }
      contPais = 1;
      $("#txtarea6u")[0].reportValidity();
      $("#txtarea6u")[0].setCustomValidity('Selecciona un estado.');
      return;
    }

    if (pais == "Elegir") {
      if (contEstado == 0) {
        $("#txtarea8u")[0].reportValidity();
        $("#txtarea8u")[0].setCustomValidity('Selecciona un país.');
      }
      contEstado = 1;

      $("#txtarea8u")[0].reportValidity();
      $("#txtarea8u")[0].setCustomValidity('Selecciona un país.');
      return;
    }
    if (telefono.length < 1) {
      $("#txtarea10u")[0].reportValidity();
      $("#txtarea10u")[0].setCustomValidity('Completa este campo.');
      return;
    }

    $.ajax({
      url: "functions/editar_Locacion.php",
      type: "POST",
      data: {
        "idLocacionU": id,
        "txtLocacionU": nombreSucursal,
        "txtCalleU": calle,
        "txtNeU": numExterior,
        "prefijo": prefijo,
        "txtNiU": numInterior,
        "txtColoniaU": colonia,
        "txtMunicipioU": municipio,
        "cmbEstadosU": estado,
        "cmbPaisU": pais,
        "telefono": telefono
      },
      success: function(data, status, xhr) {
        console.log(data);
        if (data.trim() == "exito") {
          $('#modalEditar').modal('toggle');
          $('#editarLocacionU').trigger("reset");
          $('#tblLocaciones').DataTable().ajax.reload();
          Lobibox.notify('success', {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: true,
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
            icon: true,
            img: '../../img/timdesk/warning_circle.svg',
            msg: 'Ocurrió un error al editar'
          });
        }
      }
    });

  });

  function obtenerIdLocacionEliminar(id) {
    document.getElementById('idLocacionD').value = id;
  }

  function obtenerIdLocacionEditar(id) {
    document.getElementById('idLocacionU').value = id;
    document.getElementById('idLocacionD').value = id;
    var id = "id=" + id;
    console.log(id);
    $.ajax({
      type: 'POST',
      url: 'functions/getLocacion.php',
      data: id,
      success: function(r) {
        var datos = JSON.parse(r);
        $("#txtareau").val(datos.html);
        $("#txtarea2u").val(datos.html11);
        $("#txtarea3u").val(datos.html21);
        $("#txtarea4u").val(datos.html31);
        $("#txtarea5u").val(datos.html41);
        $("#txtarea6u").val(datos.html51);
        select.set(datos.html51);
        $("#txtarea7u").val(datos.html61);
        $("#txtarea8u").val(datos.html71);
        $("#txtarea9u").val(datos.html81);
        $("#txtarea10u").val(datos.html91);
      }
    });
  }

  function eliminarLocacion(id) {

    const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        confirmButton: 'btn',
        cancelButton: 'btn'
      },
      buttonsStyling: false
    })

    swalWithBootstrapButtons.fire({
      title: '¿Desea eliminar el registro de esta locacion?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: '<span class="verticalCenter2">Eliminar locación</span>',
      cancelButtonText: '<span class="verticalCenter2">Cancelar</span>',
      reverseButtons: true
    }).then((result) => {
      if (result.isConfirmed) {

        $.ajax({
          url: "functions/eliminar_Locacion.php",
          type: "POST",
          data: {
            "idLocacionD": id
          },
          success: function(data, status, xhr) {
            if (data == "exito") {
              $('#modalEditar').modal('toggle');
              $('#tblLocaciones').DataTable().ajax.reload();
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
      ) {

      }
    })

    swal("¿Desea eliminar el registro de esta locación?", {
        buttons: {
          cancel: {
            text: "Cancelar",
            value: null,
            visible: true,
            className: "",
            closeModal: true,
          },
          confirm: {
            text: "Eliminar locación",
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
            url: "functions/eliminar_Locacion.php",
            type: "POST",
            data: {
              "idLocacionD": id
            },
            success: function(data, status, xhr) {
              if (data == "exito") {
                $('#modalEditar').modal('toggle');
                $('#tblLocaciones').DataTable().ajax.reload();
                Lobibox.notify('error', {
                  size: 'mini',
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: 'center top', //or 'center bottom'
                  icon: false,
                  img: '../../img/timdesk/notificacion_error.svg',
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
        } else {
          //cuando se presiona el boton de cancelar
        }
      });


  }

  $(document).ready(function() {
    
    new SlimSelect({
      select: '#txtarea8'
    });
    new SlimSelect({
      select: '#txtarea6'
    });
    new SlimSelect({
      select: '#txtarea8u'
    });
    select = new SlimSelect({
      select: '#txtarea6u'
    });
    $('#txtarea8').on('change', function() {
      var id = $('#txtarea8').val();
      $.ajax({
        url: "functions/getEstados.php",
        type: "POST",
        data: {
          id: id
        },
        success: function(resp) {
          $("#txtarea6").html(resp);
        }
      })

    });
  });


  
  </script>
  <script>
  var ruta = "../";
  </script>
  <script src="js/sucursales.js"></script>
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>



</body>

</html>
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

  <title>Timlid | Sucursales</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../js/validaciones.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../../js/sb-admin-2.min.js"></script>
  <script src="../../../js/sweet/sweetalert2.js"></script>

  <!-- Page level plugins -->
  <script src="../../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../vendor/datatables/dataTables.responsive.js"></script>
  <script src="../../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="../../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../../vendor/datatables/buttons.bootstrap4.min.js"></script>
  <script src="../../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../../vendor/jszip/jszip.min.js"></script>
  <script src="../../../js/slimselect.min.js"></script>

  <!-- Page level custom scripts
  <script src="js/demo/datatables-demo.js"></script>
  -->

  <!-- Custom fonts for this template -->
  <link href="../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../css/styles.css" rel="stylesheet">
  <link href="../../../css/stylesTable.css" rel="stylesheet">
  <link href="../../../css/lobibox.min.css" rel="stylesheet">
  <script src="../../../js/lobibox.min.js"></script>

  <!-- Custom styles for this page -->
  <link href="../../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="../../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link rel="stylesheet" href="../../../vendor/datatables/buttons.dataTables.css">
  <link href="../../../vendor/datatables/buttons.bootstrap4.min.css" rel="stylesheet">
  <link href="../../../css/slimselect.min.css" rel="stylesheet">

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
$icono = '../../../img/inventarios/ICONO INVENTARIOS Y PROD_Mesa de trabajo 1.svg';
$titulo = '<div class="header-screen d-flex align-items-center">
                        <div class="header-title-screen">
                          <h1 class="h3">Configuraciones </h1>
                        </div>
                      </div>';
$ruta = "../../";
$ruteEdit = $ruta . "central_notificaciones/";
require_once '../../menu3.php';

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
$rutatb = "../../";
require_once '../../topbar.php';
?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <div class="row">
            <div class="col-lg-12">
              <ul class="nav nav-tabs">
                <li class="nav-item">
                  <a id="CargarUsuarios" class="nav-link" href="../">
                    Usuarios
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarPuestos" class="nav-link" href="../puestos">
                    Puestos
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarTurnos" class="nav-link" href="../turnos">
                    Turnos
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarSucursales" class="nav-link active" href="">
                    Sucursales
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarCategoriasProductos" class="nav-link" href="../categoria_productos">
                    Categoría de productos
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarMarcas" class="nav-link" href="../marca_productos">
                    Marcas de productos
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
            <!--<div class="card-header py-3">
              <a href="functions/agregar_Locacion.php" class="btn btn-success float-right" ><i class="fas fa-plus"></i> Agregar locacion </a>
            </div>-->
            <div class="card-body">
              <div class="table-responsive">
                <table class="table" id="tblSucursales" width=" 100%" cellspacing="0">
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
                      <th>Con Inventario</th>
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
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <input class="form-control" id="notaSucursal" name="notaSucursal" type="hidden"
              style="color: darkred; background-color: transparent!important; border: none;"
              value="Nota: Nombre de la sucursal ya está registrada." readonly>

            <div class="form-group">
              <label for="usr">Nombre de sucursal:*</label>
              <input type="text" id="txtarea" class="form-control alpha-only" maxlength="40" name="txtLocacion" required
                onkeyup="validarUnicaSucursal(this)">
              <div class="invalid-feedback" id="invalid-nombreSuc">La sucursal debe tener un nombre.</div>
            </div>
            <div class="form-group">
              <label for="usr">Calle:*</label>
              <input type="text" id="txtarea2" class="form-control alpha-only" name="txtCalle" maxlength="50"
                onkeyup="validEmptyInput(this)" required>
              <div class="invalid-feedback" id="invalid-calleSuc">La sucursal debe tener una calle.</div>
            </div>
            <div class="form-group">
              <label for="usr">Número exterior:*</label>
              <input type="text" id="txtarea3" class="form-control numeric-only" name="txtNe"
                onkeyup="validEmptyInput(this)" required>
              <div class="invalid-feedback" id="invalid-noExtSuc">La sucursal debe tener un número exterior.</div>
            </div>
            <div class="form-group">
              <label for="usr">Número interior: </label>
              <div class="input-group">
                <select name="cmbPrefijo" class="form-control" placeholder="" id="txtarea9">
                  <option disabled selected hidden>Seleccionar</option>
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
              <label for="usr">Colonia:*</label>
              <input type="text" id="txtarea5" class="form-control alpha-only" size="40" name="txtColonia"
                onkeyup="validEmptyInput(this)" required>
              <div class="invalid-feedback" id="invalid-coloniaSuc">La sucursal debe tener una colonia.</div>
            </div>
            <div class="form-group">
              <label for="usr">Municipio:*</label>
              <input type="text" id="txtarea7" class="form-control alphaNumeric-only" size="40" name="txtMunicipio"
                onkeyup="validEmptyInput(this)" required>
              <div class="invalid-feedback" id="invalid-municipioSuc">La sucursal debe tener un municipio.</div>
            </div>
            <div class="form-group">
              <label for="usr">País:*</label>
              <select name="cmbPais" class="" id="txtarea8" onchange="validEmptyInput(this)" required>
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
              <div class="invalid-feedback" id="invalid-paisSuc">La sucursal debe tener un país.</div>
              <!--<input type="text" id="txtarea6" class="form-control alpha-only" size="40" name="txtEstado" required>-->
            </div>
            <div class="form-group">
              <label for="usr">Estado:*</label>
              <select name="cmbEstados" class="" id="txtarea6" onchange="validEmptyInput(this)" required>
                <option disabled selected>Seleccionar estado</option>
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
              <div class="invalid-feedback" id="invalid-estadoSuc">La sucursal debe tener un estado.</div>
              <!--<input type="text" id="txtarea6" class="form-control alpha-only" size="40" name="txtEstado" required>-->
            </div>
            <div class="form-group">
              <label for="usr">Teléfono:*</label>
              <input type="text" id="txtarea10" maxlength="10" class="form-control numeric-only" name="txtTelefono"
              onkeyup=" return validaNumTelefono(event,this)" required >
                <input type="hidden" id="result1" readonly>
              <div class="invalid-feedback" id="invalid-telSuc">La sucursal debe número de teléfono valido.</div>
              
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-lg-12">
                  <label for="usr">¿Se administran inventarios en esta sucursal?</label>
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="cbxActivarInventario"
                      name="cbxActivarInventario">
                    <label class="form-check-label" for="flexSwitchCheckDefault">Activar inventario</label>
                  </div>
                </div>
              </div>
            </div>
            <br><br>
            <div>
              <label for="usr">Campos requeridos *</label>
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
  <!--UPDATE MODAL SUCURSALES-->
  <div class="modal fade right" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-full-height modal-right modal-md" role="document">
      <div class="modal-content">
        <form action="" id="editarLocacionU" method="POST">
          <input type="hidden" name="idLocacionU" id="idLocacionU">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Editar sucursal</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <input class="form-control" id="notaExisteRelacion" name="notaExisteRelacion" type="hidden"
              style="color: darkred; background-color: transparent!important; border: none;"
              value="Nota: La sucursal está siendo utilizada." readonly>
            <div class="form-group">
              <label for="usr">Nombre de sucursal:*</label>
              <input type="text" id="txtareau" class="form-control alpha-only" maxlength="40" name="txtLocacionU"
                onkeyup="validarUnicaSucursalU(this)" required>
              <div class="invalid-feedback" id="invalid-nombreSucEdit">La sucursal debe tener un nombre.</div>
            </div>
            <div class="form-group">
              <label for="usr">Calle:*</label>
              <input type="numeric" id="txtarea2u" class="form-control " maxlength="40" name="txtCalleU"
                onkeyup="validEmptyInput(this)" required>
              <div class="invalid-feedback" id="invalid-calleSucEdit">La sucursal debe tener una calle.</div>
            </div>
            <div class="form-group">
              <label for="usr">Número exterior:*</label>
              <input type="text" id="txtarea3u" class="form-control numeric-only" name="txtNeU"
                onkeyup="validEmptyInput(this)" required>
              <div class="invalid-feedback" id="invalid-noExtSucEdit">La sucursal debe tener un número exterior.</div>
            </div>
            <div class="form-group">
              <label for="usr">Número interior:</label>

              <div class="input-group">
                <select name="cmbPrefijoU" class="form-control" id="txtarea9u">
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
              <label for="usr">Colonia:*</label>
              <input type="text" id="txtarea5u" class="form-control alpha-only" size="40" name="txtColoniaU"
                onkeyup="validEmptyInput(this)" required>
              <div class="invalid-feedback" id="invalid-coloniaSucEdit">La sucursal debe tener una colonia.</div>
            </div>
            <div class="form-group">
              <label for="usr">Municipio:*</label>
              <input type="text" id="txtarea7u" class="form-control alphaNumeric-only" size="40" name="txtMunicipioU"
                onkeyup="validEmptyInput(this)" required>
              <div class="invalid-feedback" id="invalid-municipioSucEdit">La sucursal debe tener un municipio.</div>
            </div>
            <div class="form-group">
              <label for="usr">País:*</label>
              <select name="cmbPaisU" class="" id="txtarea8u" onchange="validEmptyInput(this)" required>
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
              <div class="invalid-feedback" id="invalid-paisSucEdit">La sucursal debe tener un país.</div>
            </div>
            <div class="form-group">
              <label for="usr">Estado:*</label>
              <select name="cmbEstadosU" class="" id="txtarea6u" onchange="validEmptyInput(this)" required>
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
              <div class="invalid-feedback" id="invalid-estadoSucEdit">La sucursal debe tener un número estado.</div>
            </div>

            <div class="form-group">
              <label for="usr">Teléfono:*</label>
              <input type="numeric" maxlength="10" id="txtarea10u" class="form-control numeric-only" size="40"
                name="txtTelefono" onkeyup=" return validaNumTelefonoU(event,this)" required>
                <input type="hidden" id="result2" readonly>
              <div class="invalid-feedback" id="invalid-telSucEdit">La sucursal debe número de teléfono valido.</div>
            </div>

            <div class="form-group">
              <div class="row">
                <div class="col-lg-12">
                  <label for="usr">¿Se administran inventarios en esta sucursal?</label>
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="cbxActivarInventarioU"
                      name="cbxActivarInventarioU" onclick="activarCredito()">
                    <label class="form-check-label" for="flexSwitchCheckDefault">Activar inventario</label>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal-footer justify-content-center">
              <a class="btnesp first espEliminar" href="#" onclick="eliminarLocacion(this.value);" name="idLocacionD"
                id="idLocacionD" data-toggle="modal" data-target=""><span class="ajusteProyecto">Eliminar
                  sucursal</span></a>
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
                0id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
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
    var actInventario = 0;

    if ($("#editarLocacionU")[0].checkValidity()) {
      var badNombreSucEdit =
        $("#invalid-nombreSucEdit").css("display") === "block" ? false : true;
      var badCalleSucEdit =
        $("#invalid-calleSucEdit").css("display") === "block" ? false : true;
      var badNoExtSucEdit =
        $("#invalid-noExtSucEdit").css("display") === "block" ? false : true;
      var badColoniaSucEdit =
        $("#invalid-coloniaSucEdit").css("display") === "block" ? false : true;
      var badMunicipioSucEdit =
        $("#invalid-municipioSucEdit").css("display") === "block" ? false : true;
      var badPaisSucEdit =
        $("#invalid-paisSucEdit").css("display") === "block" ? false : true;
      var badEstadoSucEdit =
        $("#invalid-estadoSucEdit").css("display") === "block" ? false : true;
      var badTelSucEdit =
        $("#invalid-telSucEdit").css("display") === "block" ? false : true;
      if (
        badNombreSucEdit &&
        badCalleSucEdit &&
        badNoExtSucEdit &&
        badColoniaSucEdit &&
        badMunicipioSucEdit &&
        badPaisSucEdit &&
        badEstadoSucEdit &&
        badTelSucEdit
      ) {
        if ($("#cbxActivarInventarioU").is(":checked")) {
          actInventario = 1;
        } else {
          actInventario = 0;
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
            "telefono": telefono,
            "actInventario": actInventario
          },
          success: function(data, status, xhr) {
            console.log(data);
            if (data.trim() == "exito") {
              $('#modalEditar').modal('toggle');
              $('#editarLocacionU').trigger("reset");
              $('#tblSucursales').DataTable().ajax.reload();
              Lobibox.notify('success', {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: true,
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
                icon: true,
                img: '../../../img/timdesk/warning_circle.svg',
                msg: 'Ocurrió un error al editar'
              });
            }
          }
        });
      }
    } else {
      if (!nombreSucursal) {
        $("#txtareau").addClass("is-invalid")
        $("#invalid-nombreSucEdit").css("display", "block");
      }

      if (!calle) {
        $("#txtarea2u").addClass("is-invalid")
        $("#invalid-calleSucEdit").css("display", "block");
      }

      if (!colonia) {
        $("#txtarea5u").addClass("is-invalid")
        $("#invalid-coloniaSucEdit").css("display", "block");
      }

      if (!municipio) {
        $("#txtarea7u").addClass("is-invalid")
        $("#invalid-municipioSucEdit").css("display", "block");
      }

      if (!estado) {
        $("#txtarea6u").addClass("is-invalid")
        $("#invalid-estadoSucEdit").css("display", "block");
      }

      if (!pais) {
        $("#txtarea8u").addClass("is-invalid")
        $("#invalid-paisSucEdit").css("display", "block");
      }
      if (!telefono) {
        $("#txtarea10u").addClass("is-invalid")
        $("#invalid-telSucEdit").css("display", "block");
      }
    }
  });

  /*function obtenerIdLocacionEliminar(id) {
    document.getElementById('idLocacionD').value = id;
  }*/


  function eliminarLocacion(id) {

    const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        actions: "d-flex justify-content-around",
        confirmButton: "btn-custom btn-custom--border-blue",
        cancelButton: "btn-custom btn-custom--blue"
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
            if (data == "1") {
              $('#modalEditar').modal('toggle');
              $('#tblSucursales').DataTable().ajax.reload();
              Lobibox.notify('error', {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: true,
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
              if (data == "1") {
                $('#modalEditar').modal('toggle');
                $('#tblLocaciones').DataTable().ajax.reload();
                Lobibox.notify('error', {
                  size: 'mini',
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: 'center top', //or 'center bottom'
                  icon: false,
                  img: '../../../img/timdesk/notificacion_error.svg',
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

  /* Reiniciar el modal al cerrarlo */
  $("#modalEditar").on("hidden.bs.modal", function(e) {
    $("#invalid-nombreSucEdit").css("display", "none");
    $("#txtareau").removeClass("is-invalid");
    $("#txtareau").val("");

    $("#invalid-calleSucEdit").css("display", "none");
    $("#txtarea2u").removeClass("is-invalid");
    $("#txtarea2u").val("");

    $("#invalid-noExtSucEdit").css("display", "none");
    $("#txtarea3u").removeClass("is-invalid");
    $("#txtarea3u").val("");

    $("#invalid-coloniaSucEdit").css("display", "none");
    $("#txtarea5u").removeClass("is-invalid");
    $("#txtarea5u").val("");

    $("#invalid-municipioSucEdit").css("display", "none");
    $("#txtarea7u").removeClass("is-invalid");
    $("#txtarea7u").val("");

    $("#invalid-paisSucEdit").css("display", "none");
    $("#txtarea8u").removeClass("is-invalid");
    $("#txtarea8u").val("");

    $("#invalid-estadoSucEdit").css("display", "none");
    $("#txtarea6u").removeClass("is-invalid");
    $("#txtarea6u").val("");

    $("#invalid-telSucEdit").css("display", "none");
    $("#txtarea10u").removeClass("is-invalid");
    $("#txtarea10u").val("");
  });
  </script>
  <script>
  var ruta = "../../";
  </script>
  <script src="js/sucursales.js"></script>
  <script src="../../../js/sb-admin-2.min.js"></script>
  <script src="../../../js/scripts.js"></script>

</body>

</html>
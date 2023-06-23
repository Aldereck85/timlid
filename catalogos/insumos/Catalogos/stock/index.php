<?php
session_start();

if (isset($_SESSION["Usuario"])) {
    require_once '../../../../include/db-conn.php';
    $user = $_SESSION["Usuario"];
} else {
    header("location:../dashboard.php");
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <link rel="icon" type="image/png" href="../../../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Timlid | Insumos</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->


  <!-- Page level plugins -->
  <script src="../../../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="../../../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../../../vendor/datatables/buttons.bootstrap4.min.js"></script>
  <script src="../../../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../../../vendor/jszip/jszip.min.js"></script>

  <!-- Custom fonts for this template -->
  <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="../../../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="../../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../../css/styles.css" rel="stylesheet">
  <link href="../../../../css/stylesTable.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
  <!-- <link href="../../css/css_Insumos.css" rel="stylesheet"> -->

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

    $("#tblInsumosStock").dataTable({
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
      "ajax": "functions/functions_insumos.php",
      "columns": [{
          "data": "id"
        },
        {
          "data": "Identificador"
        },
        {
          "data": "Nombre"
        },
        {
          "data": "Tipo_de_insumo"
        },
        {
          "data": "Unidad_de_medida"
        },
        {
          "data": "Cantidad_en_Existencia"
        },
        {
          "data": "Cantidad_Minima"
        },
        {
          "data": "Descripcion"
        },
        {
          "data": "Fecha_de_actualizacion"
        },
        {
          "data": "Estatus"
        },
        {
          "data": "Usuario"
        }
      ],
      columnDefs: [{
          orderable: false,
          targets: 0,
          visible: false
        },
        {
          orderable: false,
          targets: 6,
          visible: false
        },
        {
          orderable: false,
          targets: 9,
          visible: false
        },
      ],
      rowCallback: function(row, data) {
        console.log('C Existencia: ' + data.Cantidad_en_Existencia.slice(25, -8));
        console.log('C Minima: ' + data.Cantidad_Minima.slice(25, -8));
        console.log('C Estatus: ' + data.Estatus.slice(25, -8));
        if ((data.Cantidad_en_Existencia.slice(25, -8) == 0) && (data.Estatus.slice(25, -8) != 'Inactivo')) {
          $($(row).find("td")[0]).css("background-color", "#e53341");
          $($(row).find("td label.textTable")[0]).attr("style", "color: #FFFFFF!important");
          $($(row).find("td label.textTable")[0]).attr("title", "Insumo agotado");
        } else if ((data.Cantidad_Minima.slice(25, -8) >= data.Cantidad_en_Existencia.slice(25, -8)) && (data
            .Estatus.slice(25, -8) != 'Inactivo')) {
          $($(row).find("td")[0]).css("background-color", "#efefa8");
          $($(row).find("td label.textTable")[0]).attr("style", "color: #15589B!important");
          $($(row).find("td label.textTable")[0]).attr("title", "Insumo agotándose");
        } else if (data.Estatus.slice(25, -8) == 'Inactivo') {
          $($(row).find("td")[0]).css("background-color", "#cac8c6");
          $($(row).find("td label.textTable")[0]).attr("style", "color: #FFFFFF!important");
          $($(row).find("td label.textTable")[0]).attr("title", "Insumo inactivo");
        } else {
          $($(row).find("td")[0]).css("background-color", "#28c67a");
          $($(row).find("td label.textTable")[0]).attr("style", "color: #FFFFFF!important");
          $($(row).find("td label.textTable")[0]).attr("title", "Insumo en existencia");
        }
      }
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
require_once '../../../menu3.php';
?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">
        <!-- Topbar -->
        <?php
$rutatb = '../../../';
$icono = '../../../img/menu/dashboardTopbar.svg';
$titulo = '<div class="header-screen d-flex align-items-center">
                      <div class="header-title-screen">
                        <h1 class="h3">Cambiar</h1>
                      </div>
                    </div>';
require_once '../../../topbar.php';
?>
        <!-- Topbar -->

        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <?php
if (isset($_SESSION['message'])) {
    ?>
          <div class="alert alert-<?=$_SESSION['message_type'];?> alert-dismissible fade show" role="alert">
            <?=$_SESSION['message'];?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hideen="true"> &times; </span>
            </button>
          </div>
          <?php
$_SESSION['message_type'] = null;
    $_SESSION['message'] = null;
}
?>

          <!-- Page Heading -->
          <div class="divPageTitle">
            <img src="../../../../img/icons/insumos.svg" width="45px" style="position:relative;top:-10px;">
            <label class="lblPageTitle">&nbsp;Stock de Insumos</label>
          </div>

          <!-- DataTales Example -->
          <div class="card mb-4">
            <div class="card-header py-3">
              <div class="float-right">
                <div class="button-container2" id="">
                  <div class="button-icon-container">
                    <a href="" class="btn btn-circle float-right waves-effect waves-light shadow btn-table"
                      id="btn-insumos" data-toggle="modal" data-target="#agregar_Insumo"><i class="fas fa-plus"></i></a>
                  </div>
                  <div class="button-text-container">
                    <span>Agregar insumo</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table" id="tblInsumosStock" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>id</th>
                      <th>Identificador</th>
                      <th>Nombre</th>
                      <th>Tipo de insumo</th>
                      <th>Unidad de medida</th>
                      <th>Cantidad en Existencia</th>
                      <th>Cantidad Minima</th>
                      <th>Descripcion</th>
                      <th>Fecha de actualizacion</th>
                      <th>Estatus</th>
                      <th>Usuario</th>
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

      <div class="row" style="margin-left: 10%;">
        <div class="cuadrado1 col-md-1">

        </div>
        <div class="col-md-2">
          Insumo en existencia
        </div>
        <div class="cuadrado2 col-md-1">

        </div>
        <div class="col-md-2">
          Insumo agotándose
        </div>
        <div class="cuadrado3 col-md-1">

        </div>
        <div class="col-md-2">
          Insumo agotado
        </div>
        <div class="cuadrado4 col-md-1">

        </div>
        <div class="col-md-2">
          Insumo inactivo
        </div>
      </div>

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



  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">¿En serio quieres salir?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <div class="modal-body">Selecciona "Salir" Para cerrar tu sesión</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
          <a class="btn btn-primary" href="../../../logout.php">Salir</a>
        </div>
      </div>
    </div>
  </div>

  <!--ADD MODAL SLIDE INSUMOS-->
  <div class="modal fade right" id="agregar_Insumo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
      <div class="modal-content">
        <form action="functions/agregar_Insumo.php" method="POST">
          <!--<input type="hidden" name="idProyectoA" value="">-->
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Agregar insumo</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="form-group col-md-4">
                <label for="usr">Identificador:</label>
                <input type="hidden" class="form-control" id="txtUsuario" name="txtUsuario"
                  value="<?php echo $_SESSION['Usuario']; ?>" required>
                <input type="text" class="form-control" maxlength="20" id="txtIdentidicador" name="txtIdentidicador"
                  placeholder="AA-001" required>
              </div>
              <div class="form-group col-md-8">
                <label for="usr">Nombre:</label>
                <input type="text" class="form-control" maxlength="50" id="txtNombre" name="txtNombre"
                  placeholder="Insumo AA" required>
              </div>
            </div>
            <input class="form-control" id="notaIdentificador" type="hidden"
              style="color: darkred; background-color: transparent!important; border: none;"
              value="Nota: El identificador ya existe, favor de anexar otro" readonly>

            <div class="form-group">
              <label for="usr">Tipo de insumo:</label>
              <select class="form-control" name="cmbTipoInsumo" id="cmbTipoInsumo" required>
                <option value="">Seleccione un tipo de insumo...</option>
                <?php
$stmt = $conn->prepare('select ti.PKTipoInsumo, ti.Tipo from tipo_insumo ti where ti.FK_EstatusInsumo = "1" order by ti.Tipo asc');
$stmt->execute();
$rowTipoInsumos = $stmt->fetchAll();
foreach ($rowTipoInsumos as $rti) {
    ?>
                <option value="<?=$rti['PKTipoInsumo'];?>"><?=$rti['Tipo'];?></option>
                <?php }?>
              </select>
            </div>
            <div class="form-group">
              <label for="usr">Unidad de medida:</label>
              <select class="form-control" name="cmbUnidadMedida" id="cmbUnidadMedida" required>
                <option value="">Seleccione una unidad de medida...</option>
                <?php
$stmt = $conn->prepare('select um.PKUnidadMedida, um.UnidadMedida from unidad_medida um  where um.FK_EstatusInsumo = "1" order by um.UnidadMedida asc;');
$stmt->execute();
$rowUnidadMedida = $stmt->fetchAll();
foreach ($rowUnidadMedida as $rum) {
    ?>
                <option value="<?=$rum['PKUnidadMedida'];?>"><?=$rum['UnidadMedida'];?></option>
                <?php }?>
              </select>
            </div>
            <div class="row">
              <div class="form-group col-md-6">
                <label for="usr">Cantidad mínima:</label>
                <input type="number" min="0" class="form-control" max="2147483647" id="txtCantidadMin"
                  name="txtCantidadMin" placeholder="0" required>
              </div>
              <div class="form-group col-md-6">
                <label for="usr">Cantidad en existencia:</label>
                <input type="number" min="0" class="form-control" max="2147483647" id="txtCantidadExi"
                  name="txtCantidadExi" placeholder="0" required>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-6">
                <label for="usr">Estatus del insumo:</label>
                <select class="form-control" name="cmbEstatusInsumo" id="cmbEstatusInsumo" required>
                  <option value="">Seleccione un estatus...</option>
                  <?php
$stmt = $conn->prepare('select ei.PKEstatusInsumo, ei.EstatusInsumo from estatus_insumo ei where ei.PKEstatusInsumo = "1" or ei.PKEstatusInsumo = "2";');
$stmt->execute();
$rowUnidadMedida = $stmt->fetchAll();
foreach ($rowUnidadMedida as $rum) {
    ?>
                  <option value="<?=$rum['PKEstatusInsumo'];?>"><?=$rum['EstatusInsumo'];?></option>
                  <?php }?>
                </select>
              </div>
              <div class="form-group col-md-6">
                <label for="usr">Costo ( $ MXN ):</label>
                <input type="number" min="0" class="form-control" max="2147483647" id="txtCosto" name="txtCosto"
                  placeholder="0" required>
              </div>
            </div>
            <div class="form-group">
              <label for="usr">Descripci&oacute;n breve:</label>
              <input type="text" class="form-control" maxlength="50" id="txtDescripcionBreve" name="txtDescripcionBreve"
                placeholder="Escriba aquí la descripción" required>
            </div>
            <div class="form-group">
              <label for="usr">Descripci&oacute;n larga:</label>
              <textarea type="text" class="form-control" maxlength="100" id="txtDescripcionLarga"
                name="txtDescripcionLarga" cols="30" rows="5" placeholder="Escriba aquí la descripción"
                style="resize: none!important;" required></textarea>
            </div>
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
              id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="submit" class="btnesp espAgregar float-right" name="btnAgregar" id="btnAgregar"><span
                class="ajusteProyecto">Agregar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
  </div>
  <!--END ADD MODAL SLIDE INSUMOS-->


  <!--EDIT MODAL SLIDE INSUMOS-->
  <div class="modal fade right" id="editar_Insumo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
      <div class="modal-content">
        <form action="functions/editar_Insumo.php" method="POST">
          <input type="hidden" name="idInsumoU" id="idInsumoU">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Editar insumo</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="form-group col-md-4">
                <label for="usr">Identificador:</label>
                <input type="hidden" class="form-control" id="txtUsuarioU" name="txtUsuarioU"
                  value="<?php echo $_SESSION['Usuario']; ?>" required>
                <input type="hidden" class="form-control" maxlength="20" id="txtIdentidicadorHistoricoU"
                  name="txtIdentidicadorHistoricoU" readonly>
                <input type="text" class="form-control" maxlength="20" id="txtIdentidicadorU" name="txtIdentidicadorU"
                  placeholder="AA-001" required>
              </div>
              <div class="form-group col-md-8">
                <label for="usr">Nombre:</label>
                <input type="text" class="form-control" maxlength="50" id="txtNombreU" name="txtNombreU"
                  placeholder="Insumo AA" required>
              </div>
            </div>
            <input class="form-control" id="notaIdentificadorU" type="hidden"
              style="color: darkred; background-color: transparent!important; border: none;"
              value="Nota: El identificador ya existe, favor de anexar otro" readonly>
            <div class="form-group">
              <label for="usr">Tipo de insumo:</label>
              <select class="form-control" name="cmbTipoInsumoU" id="cmbTipoInsumoU" required>
                <option value="">Seleccione un tipo de insumo...</option>
                <?php
$stmt = $conn->prepare('select ti.PKTipoInsumo, ti.Tipo from tipo_insumo ti where ti.FK_EstatusInsumo = "1" order by ti.Tipo asc');
$stmt->execute();
$rowTipoInsumos = $stmt->fetchAll();
foreach ($rowTipoInsumos as $rti) {
    ?>
                <option value="<?=$rti['PKTipoInsumo'];?>"><?=$rti['Tipo'];?></option>
                <?php }?>
              </select>
            </div>
            <div class="form-group">
              <label for="usr">Unidad de medida:</label>
              <select class="form-control" name="cmbUnidadMedidaU" id="cmbUnidadMedidaU" required>
                <option value="">Seleccione una unidad de medida...</option>
                <?php
$stmt = $conn->prepare('select um.PKUnidadMedida, um.UnidadMedida from unidad_medida um  where um.FK_EstatusInsumo = "1" order by um.UnidadMedida asc;');
$stmt->execute();
$rowUnidadMedida = $stmt->fetchAll();
foreach ($rowUnidadMedida as $rum) {
    ?>
                <option value="<?=$rum['PKUnidadMedida'];?>"><?=$rum['UnidadMedida'];?></option>
                <?php }?>
              </select>
            </div>
            <div class="row">
              <div class="form-group col-md-6">
                <label for="usr">Cantidad mínima:</label>
                <input type="number" min="0" class="form-control" max="2147483647" id="txtCantidadMinU"
                  name="txtCantidadMinU" placeholder="0" required>
              </div>
              <div class="form-group col-md-6">
                <label for="usr">Cantidad en existencia:</label>
                <input type="number" min="0" class="form-control" max="2147483647" id="txtCantidadExiU"
                  name="txtCantidadExiU" placeholder="0" required>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-6">
                <label for="usr">Estatus del insumo:</label>
                <select class="form-control" name="cmbEstatusInsumoU" id="cmbEstatusInsumoU" required>
                  <option value="">Seleccione un estatus...</option>
                  <?php
$stmt = $conn->prepare('select ei.PKEstatusInsumo, ei.EstatusInsumo from estatus_insumo ei where ei.PKEstatusInsumo = "1" or ei.PKEstatusInsumo = "2";');
$stmt->execute();
$rowUnidadMedida = $stmt->fetchAll();
foreach ($rowUnidadMedida as $rum) {
    ?>
                  <option value="<?=$rum['PKEstatusInsumo'];?>"><?=$rum['EstatusInsumo'];?></option>
                  <?php }?>
                </select>
              </div>
              <div class="form-group col-md-6">
                <label for="usr">Costo ( $ MXN ):</label>
                <input type="number" min="0" class="form-control" max="2147483647" id="txtCostoU" name="txtCostoU"
                  placeholder="0" required>
              </div>
            </div>
            <div class="form-group">
              <label for="usr">Descripci&oacute;n breve:</label>
              <input type="text" class="form-control" maxlength="50" id="txtDescripcionBreveU"
                name="txtDescripcionBreveU" placeholder="Escriba aquí la descripción" required>
            </div>
            <div class="form-group">
              <label for="usr">Descripci&oacute;n larga:</label>
              <textarea type="text" class="form-control" maxlength="100" id="txtDescripcionLargaU"
                name="txtDescripcionLargaU" cols="30" rows="5" placeholder="Escriba aquí la descripción"
                style="resize: none!important;" required></textarea>
            </div>
          </div>
          <div class="modal-footer justify-content-center">
            <a aria-label="Close" class="btnesp first espEliminar btnCancelarActualizacion"
              onclick="eliminarInsumo(this.value);" data-toggle="modal" data-target="#eliminar_Insumo"><span
                class="ajusteProyecto">Eliminar</span></a>
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
              id="btnCancelarActualizacionU"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="submit" class="btnesp espAgregar float-right" name="btnEditar" id="btnEditar"><span
                class="ajusteProyecto">Guardar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
  </div>
  <!--END EDIT MODAL SLIDE INSUMOS-->


  <!--DELETE MODAL SLIDE INSUMOS-->
  <div class="modal fade" id="eliminar_Insumo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="functions/eliminar_Insumo.php" method="POST">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">¿Desea eliminar el registro de este?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div>
            <input type="hidden" name="idInsumoD" id="idInsumoD">
            <br>
            <label for="usr" style="margin-left: 80px!important;">Se eliminará el insumo con los siguientes
              datos:</label>
          </div>
          <div class="row card-body" style="margin-top: -80px!important;">
            <div class="form-group col-md-3">
              <label for="usr">Identificador:</label>
              <input type="hidden" class="form-control" id="txtUsuarioD" name="txtUsuarioD"
                value="<?php echo $_SESSION['Usuario']; ?>" required>
            </div>
            <div class="form-group col-md-9">
              <input type="text" style="border:none!important; background-color: transparent!important;"
                class="form-control" maxlength="20" id="txtIdentidicadorD" name="txtIdentidicadorD" placeholder="AA-001"
                required readonly>
            </div>
            <div class="form-group col-md-3">
              <label for="usr">Nombre:</label>
            </div>
            <div class="form-group col-md-9">
              <input type="text" style="border:none!important; background-color: transparent!important;"
                class="form-control" maxlength="50" id="txtNombreD" name="txtNombreD" placeholder="Insumo AA" required
                readonly>
            </div>
          </div>
          <div class="modal-body" style="font-size: 10 px!important; color: red;">Esta acción no podrá deshacerse</div>
          <div class="modal-footer">
            <button class="btnesp first espCancelar btnCancelarActualizacion" type="button" data-dismiss="modal"><span
                class="ajusteProyecto">Cancelar</span></button>
            <button type="submit" class="btnesp espAgregar float-right"><span
                class="ajusteProyecto">Eliminar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!--END DELETE MODAL SLIDE INSUMOS-->



  <script>
  /*$("#btnEditarInsumo").click(function(){
      console.log("Editar Insumo Clickeado");
      var identificador = $("#txtIdentidicadorU").val().trim();
      var nombre = $("#txtNombreU").val();
      var tipoInsumo = $("#cmbTipoInsumoU").val();
      var unidadMedida = $("#cmbUnidadMedidaU").val();
      var cantidadMin = $("#txtCantidadMinU").val();
      var cantidadExi = $("#txtCantidadExiU").val();
      var descripcionBreve = $("#txtDescripcionBreveU").val();
      var descripcionLarga = $("#txtDescripcionLargaU").val();
      var usuario = $("#txtUsuarioU").val();;
      var estatusInsumo = $("#cmbEstatusInsumoU").val();
      var costo = $("#txtCostoU").val();

      if(identificador.length < 1){
        $("#txtIdentidicadorU")[0].reportValidity();
        $("#txtIdentidicadorU")[0].setCustomValidity('Completa este campo.');
        return;
      }

      if(nombre.length < 1){
        $("#txtNombreU")[0].reportValidity();
        $("#txtNombreU")[0].setCustomValidity('Completa este campo.');
        return;
      }

      if(tipoInsumo.length < 1){
        $("#cmbTipoInsumoU")[0].reportValidity();
        $("#cmbTipoInsumoU")[0].setCustomValidity('Selecciona una opción.');
        return;
      }

      if(unidadMedida.length < 1){
        $("#cmbUnidadMedidaU")[0].reportValidity();
        $("#cmbUnidadMedidaU")[0].setCustomValidity('Selecciona una opción.');
        return;
      }

      if(cantidadMin.length < 1){
        $("#txtCantidadMinu")[0].reportValidity();
        $("#txtCantidadMinu")[0].setCustomValidity('Completa este campo.');
        return;
      }

      if(cantidadExi.length < 1){
        $("#txtCantidadExiU")[0].reportValidity();
        $("#txtCantidadExiU")[0].setCustomValidity('Completa este campo.');
        return;
      }

      if(descripcionBreve.length < 1){
        $("#txtDescripcionBreveU")[0].reportValidity();
        $("#txtDescripcionBreveU")[0].setCustomValidity('Completa este campo.');
        return;
      }

      if(descripcionLarga.length < 1){
        $("#txtDescripcionLargaU")[0].reportValidity();
        $("#txtDescripcionLargaU")[0].setCustomValidity('Completa este campo.');
        return;
      }

      if(estatusInsumo.length < 1){
        $("#cmbEstatusInsumoU")[0].reportValidity();
        $("#cmbEstatusInsumoU")[0].setCustomValidity('Selecciona una opción.');
        return;
      }

      if(costo.length < 1){
        $("#txtCostoU")[0].reportValidity();
        $("#txtCostoU")[0].setCustomValidity('Completa este campo.');
        return;
      }

      $.ajax({
          url : "functions/editar_Insumo.php",
          type: "POST",
          data : { "txtIdentidicadorU" : identificador,
                  "txtNombreU" : nombre,
                  "cmbTipoInsumoU" : tipoInsumo,
                  "cmbUnidadMedidaU" : unidadMedida,
                  "txtCantidadMinU" : cantidadMin,
                  "txtCantidadExiU" : cantidadExi,
                  "txtDescripcionBreveU" : descripcionBreve,
                  "txtDescripcionLargaU" : descripcionBreve,
                  "usuarioU": usuario,
                  "cmbEstatusInsumoU" : estatusInsumo,
                  "txtCostoU" : costo},
          success: function(data,status,xhr)
          {
            if(data.trim() == "exito"){
              $('#editar_Insumo').modal('toggle');
              $('#editarInsumo').trigger("reset");
              $('#tblInsumosStock').DataTable().ajax.reload();
              Lobibox.notify('success', {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: false,
                img: '../../../../img/timdesk/checkmark.svg',
                msg: '¡Registro modificado!'
              });
            }
            else{
              Lobibox.notify('warning', {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top',
                icon: false,
                img: '../../../../img/timdesk/warning_circle.svg',
                msg: 'Ocurrió un error al modificar'
              });
            }
          }
      });
    });
    //end script*/

  function obtenerIdInsumoEditar(id) {
    document.getElementById('idInsumoD').value = id;
    document.getElementById('idInsumoU').value = id;
    var id = "id=" + id;
    $.ajax({
      type: 'POST',
      url: 'functions/traer_datos.php',
      data: id,
      success: function(r) {
        var datos = JSON.parse(r);
        /* DATOS EN MODAL EDITAR*/
        $("#txtIdentidicadorU").val(datos.identificador);
        $("#txtNombreU").val(datos.nombre);
        $("#cmbTipoInsumoU").val(datos.tipoInsumo);
        $("#cmbUnidadMedidaU").val(datos.unidadMedida);
        $("#txtCantidadMinU").val(datos.cantidadMin);
        $("#txtCantidadExiU").val(datos.cantidadExi);
        $("#txtDescripcionBreveU").val(datos.descripcionBreve);
        $("#txtDescripcionLargaU").val(datos.descripcionLarga);
        $("#cmbEstatusInsumoU").val(datos.estatusInsumo);
        $("#txtCostoU").val(datos.costo);
        $("#txtIdentidicadorHistoricoU").val(datos.identificador);

        /* DATOS EN MODAL ELIMINAR */
        $("#txtIdentidicadorD").val(datos.identificador);
        $("#txtNombreD").val(datos.nombre);
      }
    });
  }

  /* VALIAR QUE NO SE REPIDA EL IDENTIFICADOR AGREGADO POR EL USUARIO EN AGREGAR */
  document.getElementById('txtIdentidicador').onkeyup = function() {
    var valor = this.value;
    var identificador = "identificador=" + valor;
    $.ajax({
      type: 'POST',
      url: 'functions/validar_Identificador.php',
      data: identificador,
      success: function(r) {
        var datos = JSON.parse(r);
        /* Validar si ya existe el identificador con ese nombre*/

        if (parseInt(datos.existe) == 1) {
          var agregar = document.getElementById("btnAgregar");
          agregar.disabled = true;

          var nota = document.getElementById("notaIdentificador");
          nota.setAttribute('type', 'text');

          console.log('¡Ya existe!');
        } else {
          var agregar = document.getElementById("btnAgregar");
          agregar.disabled = false;

          var nota = document.getElementById("notaIdentificador");
          nota.setAttribute('type', 'hidden');

          console.log('¡No existe!');
        }

      }
    });
  }

  /* VALIAR QUE NO SE REPIDA EL IDENTIFICADOR AGREGADO POR EL USUARIO EN EDITAR */
  document.getElementById('txtIdentidicadorU').onkeyup = function() {
    var valor = this.value;
    var identificador = "identificador=" + valor;
    $.ajax({
      type: 'POST',
      url: 'functions/validar_Identificador.php',
      data: identificador,
      success: function(r) {
        var datos = JSON.parse(r);

        var identiHis = document.getElementById('txtIdentidicadorHistoricoU').value;
        console.log('Historico:' + identiHis);
        console.log('Nuevo:' + datos.identi);

        /* Validar si ya existe el identificador con ese nombre*/
        if (datos.identi != identiHis) {
          if (parseInt(datos.existe) == 1) {
            var agregar = document.getElementById("btnEditar");
            agregar.disabled = true;

            var nota = document.getElementById("notaIdentificadorU");
            nota.setAttribute('type', 'text');

            console.log('¡Ya existe!');
          } else {
            var agregar = document.getElementById("btnEditar");
            agregar.disabled = false;

            var nota = document.getElementById("notaIdentificadorU");
            nota.setAttribute('type', 'hidden');

            console.log('¡No existe!');
          }
        }
      }
    });
  }
  </script>


</body>

</html>
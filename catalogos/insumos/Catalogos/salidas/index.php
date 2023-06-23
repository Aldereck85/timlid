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

  <title>Timlid | Salida de insumos</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../../../js/sb-admin-2.min.js"></script>
  <script src="../../../../js/mdb.min.js"></script>
  <script src="../../../../js/lobibox.min.js"></script>
  <script src="../../../../js/sweet/sweetalert2.js"></script>
  <script src="../../../../js/validaciones.js"></script>

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
          "data": "Cantidad saliente"
        },
        {
          "data": "Descripcion"
        },
        {
          "data": "Fecha de salida"
        },
        {
          "data": "Usuario"
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
            <label class="lblPageTitle">&nbsp;Salida de Insumos</label>
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
                    <span>Agregar salida</span>
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
                      <th>Cantidad saliente</th>
                      <th>Descripcion</th>
                      <th>Fecha de salida</th>
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
            <h4 class="modal-title w-100" id="myModalLabel">Agregar salida</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="usr">Insumo:</label>
              <input type="hidden" class="form-control" id="txtUsuario" name="txtUsuario"
                value="<?php echo $_SESSION['Usuario']; ?>" required>
              <select class="form-control" name="cmbInsumo" id="cmbInsumo" required>
                <option value="">Seleccione un insumo...</option>
                <?php
$stmt = $conn->prepare('select ist.PKInsumosStock, (concat(ist.Identificador," / ", ist.Nombre)) as Insumo, ist.CantidadExistencia, ist.CantidadMinima from insumos_stock ist where ist.FKEstatusInsumo = "1" or ist.FKEstatusInsumo = "2";');
$stmt->execute();
$rowTipoInsumos = $stmt->fetchAll();
foreach ($rowTipoInsumos as $rti) {
    ?>
                <option value="<?=$rti['PKInsumosStock'];?>" data-cantidad="<?=$rti['CantidadExistencia'];?>"
                  data-minima="<?=$rti['CantidadMinima'];?>"><?=$rti['Insumo'];?></option>
                <?php }?>
              </select>
            </div>
            <div class="row">
              <div class="form-group col-md-6">
                <label for="usr">Cantidad en existencia:</label>
                <input type="number" min="0" class="form-control" max="2147483647" id="txtCantidadExi"
                  name="txtCantidadExi" placeholder="0" required readonly
                  style="border:none!important; background-color: transparent!important;">
              </div>
              <div class="form-group col-md-6">
                <label for="usr">Cantidad mínima:</label>
                <input type="number" min="0" class="form-control" max="2147483647" id="txtCantidadMin"
                  name="txtCantidadMin" placeholder="0" required readonly
                  style="border:none!important; background-color: transparent!important;">
              </div>
            </div>
            <br>
            <hr><br>
            <div class="row">
              <div class="form-group col-md-12">
                <input id="notaSalida" class="form-control" type="hidden"
                  style="color: darkred; background-color: transparent!important; border:none;"
                  value="Nota: La salida no puede ser mayor a la cantidad en existencia" readonly>
              </div>
              <div class="form-group col-md-12">
                <label for="usr">Cantidad saliente:</label>
                <input type="number" min="0" class="form-control" max="2147483647" id="txtCantidadSali"
                  name="txtCantidadSali" placeholder="0" required onkeyup="comprobar()">
              </div>
            </div>
            <div class="form-group">
              <label for="usr">Descripci&oacute;n de la salida:</label>
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
  lida
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
            <h4 class="modal-title w-100" id="myModalLabel">Editar salida</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="usr">Insumo:</label>
              <input type="hidden" id="cmbInsumoUHistorico" name="cmbInsumoUHistorico">
              <input type="hidden" class="form-control" id="txtUsuarioU" name="txtUsuarioU"
                value="<?php echo $_SESSION['Usuario']; ?>" required>
              <select class="form-control" name="cmbInsumoU" id="cmbInsumoU" required>
                <option value="">Seleccione un insumo...</option>
                <?php
$stmt = $conn->prepare('select ist.PKInsumosStock, (concat(ist.Identificador," / ", ist.Nombre)) as Insumo, ist.CantidadExistencia, ist.CantidadMinima from insumos_stock ist where ist.FKEstatusInsumo = "1" or ist.FKEstatusInsumo = "2";');
$stmt->execute();
$rowTipoInsumos = $stmt->fetchAll();
foreach ($rowTipoInsumos as $rti) {
    ?>
                <option value="<?=$rti['PKInsumosStock'];?>" data-cantidad="<?=$rti['CantidadExistencia'];?>"
                  data-minima="<?=$rti['CantidadMinima'];?>"><?=$rti['Insumo'];?></option>
                <?php }?>
              </select>
            </div>
            <div class="row">
              <div class="form-group col-md-6">
                <label for="usr">Cantidad en existencia:</label>
                <input type="number" min="0" class="form-control" max="2147483647" id="txtCantidadExiU"
                  name="txtCantidadExiU" placeholder="0" required readonly
                  style="border:none!important; background-color: transparent!important;">
              </div>
              <div class="form-group col-md-6">
                <label for="usr">Cantidad mínima:</label>
                <input type="number" min="0" class="form-control" max="2147483647" id="txtCantidadMinU"
                  name="txtCantidadMinU" placeholder="0" required readonly
                  style="border:none!important; background-color: transparent!important;">
              </div>
            </div>
            <br>
            <hr><br>
            <div class="row">
              <div class="form-group col-md-12">
                <input class="form-control" id="notaSalidaU" type="hidden"
                  style="color: darkred; background-color: transparent!important; border: none;"
                  value="Nota: La salida no puede ser mayor a la cantidad en existencia" readonly>
              </div>
              <div class="form-group col-md-12">
                <label for="usr">Cantidad saliente:</label>
                <input type="hidden" id="txtCantidadSaliHistoricaU" name="txtCantidadSaliHistoricaU">
                <input type="number" min="0" class="form-control" max="2147483647" id="txtCantidadSaliU"
                  name="txtCantidadSaliU" placeholder="0" required>
              </div>
            </div>
            <div class="form-group">
              <label for="usr">Descripci&oacute;n de la salida:</label>
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
            <label for="usr" style="margin-left: 80px!important;">Se eliminará la entrada con los siguientes
              datos:</label>
          </div>
          <div class="row card-body" style="margin-top: -80px!important;">
            <div class="form-group col-md-3">
              <label for="usr">Insumo:</label>
              <input type="hidden" class="form-control" id="txtUsuarioD" name="txtUsuarioD"
                value="<?php echo $_SESSION['Usuario']; ?>" required>
            </div>
            <div class="form-group col-md-9">
              <select class="form-control" name="cmbInsumoD" id="cmbInsumoD" required readonly>
                <option value="">Seleccione un insumo...</option>
                <?php
$stmt = $conn->prepare('select ist.PKInsumosStock, (concat(ist.Identificador," / ", ist.Nombre)) as Insumo, ist.CantidadExistencia, ist.CantidadMinima from insumos_stock ist where ist.FKEstatusInsumo = "1" or ist.FKEstatusInsumo = "2";');
$stmt->execute();
$rowTipoInsumos = $stmt->fetchAll();
foreach ($rowTipoInsumos as $rti) {
    ?>
                <option value="<?=$rti['PKInsumosStock'];?>" data-cantidad="<?=$rti['CantidadExistencia'];?>"
                  data-minima="<?=$rti['CantidadMinima'];?>"><?=$rti['Insumo'];?></option>
                <?php }?>
              </select>
            </div>
            <div class="form-group col-md-3">
              <label for="usr">Descripción:</label>
            </div>
            <div class="form-group col-md-9">
              <textarea type="text" class="form-control" maxlength="100" id="txtDescripcionLargaD"
                name="txtDescripcionLargaD" cols="30" rows="5" placeholder="Escriba aquí la descripción"
                style="resize: none!important;" readonly></textarea>
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
        $("#cmbInsumoU").val(datos.fkInsumoStock);
        $("#txtCantidadSaliU").val(datos.cantidadMovimiento);
        $("#txtDescripcionLargaU").val(datos.descripcion);
        $("#txtCantidadExiU").val(datos.cantidadExistencia);
        $("#txtCantidadMinU").val(datos.cantidadMinima);
        $("#txtCantidadSaliHistoricaU").val(datos.cantidadMovimiento);
        $("#cmbInsumoUHistorico").val(datos.fkInsumoStock);

        /* DATOS EN MODAL ELIMINAR */
        $("#cmbInsumoD").val(datos.fkInsumoStock);
        $("#txtDescripcionLargaD").val(datos.descripcion);
      }
    });
  }

  /* ------------------------------------------------------------------------------------ */
  /* ----------------------------------- AGREGAR ---------------------------------------- */
  /* ------------------------------------------------------------------------------------ */

  /* Mostrar la cantidad de la existencia actual del insumo en base a su selección */
  document.getElementById('cmbInsumo').onchange = function() {
    /* Referencia al option seleccionado */
    var mOption = this.options[this.selectedIndex];
    /* Referencia a los atributos data de la opción seleccionada */
    var mData = mOption.dataset;

    /* Referencia a los input */
    var cantidadExistente = document.getElementById('txtCantidadExi');
    var cantidadMinima = document.getElementById('txtCantidadMin');

    /* Asignamos cada dato a su input*/
    cantidadExistente.value = mData.cantidad;
    cantidadMinima.value = mData.minima;

    /* Deshabilitar boton porque la salida es mayor a la existencia */
    var cantidadSalida = document.getElementById('txtCantidadSali');
    if (parseInt(cantidadSalida.value) > parseInt(cantidadExistente.value)) {
      var salida = document.getElementById("btnAgregar");
      salida.disabled = true;

      var nota = document.getElementById("notaSalida");
      nota.setAttribute('type', 'text');

    } else {
      var salida = document.getElementById("btnAgregar");
      salida.disabled = false;
      var nota = document.getElementById("notaSalida");
      nota.setAttribute('type', 'hidden');
    }
  };



  /* Deshabilitar boton porque la salida es mayor a la existencia */
  document.getElementById('txtCantidadSali').onchange = function comprobar() {
    var cantidadExistente = document.getElementById("txtCantidadExi").value;

    if (parseInt(this.value) > parseInt(cantidadExistente)) {
      var salida = document.getElementById("btnAgregar");
      salida.disabled = true;

      var nota = document.getElementById("notaSalida");
      nota.setAttribute('type', 'text');

    } else {
      var salida = document.getElementById("btnAgregar");
      salida.disabled = false;
      var nota = document.getElementById("notaSalida");
      nota.setAttribute('type', 'hidden');
    }
  }

  /* Deshabilitar boton porque la salida es mayor a la existencia */
  /* Botón */
  document.getElementById('txtCantidadSali').onchange = function comprobar() {
    var cantidadExistente = document.getElementById("txtCantidadExi").value;

    if (parseInt(this.value) > parseInt(cantidadExistente)) {
      var salida = document.getElementById("btnAgregar");
      salida.disabled = true;

      var nota = document.getElementById("notaSalida");
      nota.setAttribute('type', 'text');

    } else {
      var salida = document.getElementById("btnAgregar");
      salida.disabled = false;
      var nota = document.getElementById("notaSalida");
      nota.setAttribute('type', 'hidden');
    }
  }

  /* ------------------------------------------------------------------------------------ */
  /* ----------------------------------- EDITAR ----------------------------------------- */
  /* ------------------------------------------------------------------------------------ */

  /* Mostrar la cantidad de la existencia actual del insumo en base a su selección */
  var select = document.getElementById('cmbInsumoU');
  select.onchange = getOption;

  function getOption() {
    /* Referencia al option seleccionado */
    var mOption = this.options[this.selectedIndex];
    /* Referencia a los atributos data de la opción seleccionada */
    var mData = mOption.dataset;

    /* Referencia a los input */
    var cantidadExistente = document.getElementById('txtCantidadExiU');
    var cantidadMinima = document.getElementById('txtCantidadMinU');

    /* Asignamos cada dato a su input*/
    cantidadExistente.value = mData.cantidad;
    cantidadMinima.value = mData.minima;

    /* Deshabilitar boton porque la salida es mayor a la existencia */
    var cantidadSalida = document.getElementById('txtCantidadSaliU').value;
    var cantidadSalidaHistorica = document.getElementById('txtCantidadSaliHistoricaU').value;
    var insumoUHistoricoID = document.getElementById('cmbInsumoUHistorico').value;
    var insumoSeleccionadoID = this.value;

    var suma = parseInt(cantidadExistente.value) + parseInt(cantidadSalidaHistorica);

    console.log('Lo que quiere salir: ' + cantidadSalida);
    console.log('Lo que ya habia salido: ' + cantidadSalidaHistorica);
    console.log('El Id del insumo que ya habia salido: ' + insumoUHistoricoID);
    console.log('El Id del insumo que quiere salir: ' + insumoSeleccionadoID);
    console.log('Cantidad existente: ' + cantidadExistente.value);
    console.log('La suma es: ' + suma);

    /* Si es el mismo Insumo seleccionado */
    if (insumoSeleccionadoID == insumoUHistoricoID) {
      console.log('¡Si son iguales!');

      /* Si la cantidad es mayor a la suma de lo que se tiene y lo que se sacó */
      if (parseInt(cantidadSalida) > suma) {
        var salida = document.getElementById("btnEditar");
        salida.disabled = true;

        var nota = document.getElementById("notaSalidaU");
        nota.setAttribute('type', 'text');

        console.log('¡Es menor la cantidad existente!');
      } else {
        var salida = document.getElementById("btnEditar");
        salida.disabled = false;
        var nota = document.getElementById("notaSalidaU");
        nota.setAttribute('type', 'hidden');

        console.log('¡Si se puede sacar esto!');
      }
    } else {
      console.log('¡No son iguales!');
      if (parseInt(cantidadSalida) > parseInt(cantidadExistente.value)) {
        var salida = document.getElementById("btnEditar");
        salida.disabled = true;

        var nota = document.getElementById("notaSalidaU");
        nota.setAttribute('type', 'text');

        console.log('¡Es menor la cantidad existente!');

      } else {
        var salida = document.getElementById("btnEditar");
        salida.disabled = false;
        var nota = document.getElementById("notaSalidaU");
        nota.setAttribute('type', 'hidden');

        console.log('¡Si se puede sacar esto!');
      }
    }
  };

  /* Deshabilitar boton porque la salida es mayor a la existencia */
  document.getElementById('txtCantidadSaliU').onchange = function comprobarU() {
    var cantidadExistente = document.getElementById("txtCantidadExiU").value;
    var cantidadSalidaHistorica = document.getElementById('txtCantidadSaliHistoricaU').value;
    var insumoUHistoricoID = document.getElementById('cmbInsumoUHistorico').value;
    var cantidadSalida = document.getElementById('txtCantidadSaliU').value;
    var insumoSeleccionadoID = document.getElementById('cmbInsumoU').value;

    var suma = parseInt(cantidadExistente) + parseInt(cantidadSalidaHistorica);

    console.log('Lo que quiere salir: ' + cantidadSalida);
    console.log('Lo que ya habia salido: ' + cantidadSalidaHistorica);
    console.log('El Id del insumo que ya habia salido: ' + insumoUHistoricoID);
    console.log('El Id del insumo que quiere salir: ' + insumoSeleccionadoID);
    console.log('Cantidad existente: ' + cantidadExistente);
    console.log('La suma es: ' + suma);

    /* Si es el mismo Insumo seleccionado */
    if (insumoSeleccionadoID == insumoUHistoricoID) {
      console.log('¡Si son iguales!');

      /* Si la cantidad es mayor a la suma de lo que se tiene y lo que se sacó */
      if (parseInt(this.value) > suma) {
        var salida = document.getElementById("btnEditar");
        salida.disabled = true;

        var nota = document.getElementById("notaSalidaU");
        nota.setAttribute('type', 'text');

        console.log('¡Es menor la cantidad existente!');
      } else {
        var salida = document.getElementById("btnEditar");
        salida.disabled = false;
        var nota = document.getElementById("notaSalidaU");
        nota.setAttribute('type', 'hidden');

        console.log('¡Si se puede sacar esto!');
      }
    } else {
      console.log('¡No son iguales!');
      if (parseInt(this.value) > parseInt(cantidadExistente)) {
        var salida = document.getElementById("btnEditar");
        salida.disabled = true;

        var nota = document.getElementById("notaSalidaU");
        nota.setAttribute('type', 'text');

        console.log('¡Es menor la cantidad existente!');

      } else {
        var salida = document.getElementById("btnEditar");
        salida.disabled = false;
        var nota = document.getElementById("notaSalidaU");
        nota.setAttribute('type', 'hidden');

        console.log('¡Si se puede sacar esto!');
      }
    }
  }

  /* Cuando se presiona el botón de guardar cambios desde editar */
  document.getElementById('btnEditar').onclick = function comprobar() {
    var cantidadExistente = document.getElementById("txtCantidadExiU").value;
    var cantidadSalidaHistorica = document.getElementById('txtCantidadSaliHistoricaU').value;
    var insumoUHistoricoID = document.getElementById('cmbInsumoUHistorico').value;
    var cantidadSalida = document.getElementById('txtCantidadSaliU').value;
    var insumoSeleccionadoID = document.getElementById('cmbInsumoU').value;

    var suma = parseInt(cantidadExistente) + parseInt(cantidadSalidaHistorica);

    console.log('Lo que quiere salir: ' + cantidadSalida);
    console.log('Lo que ya habia salido: ' + cantidadSalidaHistorica);
    console.log('El Id del insumo que ya habia salido: ' + insumoUHistoricoID);
    console.log('El Id del insumo que quiere salir: ' + insumoSeleccionadoID);
    console.log('Cantidad existente: ' + cantidadExistente);
    console.log('La suma es: ' + suma);

    /* Si es el mismo Insumo seleccionado */
    if (insumoSeleccionadoID == insumoUHistoricoID) {
      console.log('¡Si son iguales!');

      /* Si la cantidad es mayor a la suma de lo que se tiene y lo que se sacó */
      if (parseInt(this.value) > suma) {
        var salida = document.getElementById("btnEditar");
        salida.disabled = true;

        var nota = document.getElementById("notaSalidaU");
        nota.setAttribute('type', 'text');

        console.log('¡Es menor la cantidad existente!');
      } else {
        var salida = document.getElementById("btnEditar");
        salida.disabled = false;
        var nota = document.getElementById("notaSalidaU");
        nota.setAttribute('type', 'hidden');

        console.log('¡Si se puede sacar esto!');
      }
    } else {
      console.log('¡No son iguales!');
      if (parseInt(this.value) > parseInt(cantidadExistente)) {
        var salida = document.getElementById("btnEditar");
        salida.disabled = true;

        var nota = document.getElementById("notaSalidaU");
        nota.setAttribute('type', 'text');

        console.log('¡Es menor la cantidad existente!');

      } else {
        var salida = document.getElementById("btnEditar");
        salida.disabled = false;
        var nota = document.getElementById("notaSalidaU");
        nota.setAttribute('type', 'hidden');

        console.log('¡Si se puede sacar esto!');
      }
    }
  }
  </script>

</body>

</html>
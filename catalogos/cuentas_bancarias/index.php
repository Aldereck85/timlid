<?php
session_start();
require_once '../../include/db-conn.php';
$user = $_SESSION["Usuario"];
$pkususario = $_SESSION["PKUsuario"];
$idempresa = $_SESSION["IDEmpresa"];

$ruta = "../";
$screen = 2;
$stmt = $conn->prepare("SELECT id FROM tipo_empleado WHERE tipo = ?");
$stmt->execute(["Responsable gastos"]);
$idTipoResponsable = $stmt->fetch(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | Cuentas Bancarias</title>

  <!-- ESTILOS -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="../../css/notificaciones.css" rel="stylesheet">
  <link href="../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../css/sweetalert2.css" rel="stylesheet">
  <!-- JS -->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../js/lobibox.min.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>
  <script src="../../js/sweet/sweetalert2.js"></script>
  <script src="../../js/jquery.redirect.min.js"></script>
  <script>
    $(document).ready(function() {
      var idioma_espanol = {
        "sProcessing": "Procesando...",
        "sZeroRecords": "No se encontraron resultados",
        "sEmptyTable": "Ningún dato disponible en esta tabla",
        "sSearch": "<img src='../../img/timdesk/buscar.svg' width='20px' />",
        "sLoadingRecords": "<img src='../../img/timdesk/Preloader.gif' width='100px' style='transition-duration:300ms;' />",
        searchPlaceholder: "Buscar...",
        "oPaginate": {
          "sFirst": "Primero",
          "sLast": "Último",
          "sNext": "<i class='fas fa-chevron-right'></i>",
          "sPrevious": "<i class='fas fa-chevron-left'></i>"
        },
      }
      $("#tblCuentasBancarias").dataTable({
        "language": idioma_espanol,
        info: false,
        scrollX: true,
        bSort: false,
        pageLength: 50,
        responsive: true,
        lengthChange: false,
        columnDefs: [{
          orderable: false,
          targets: 0,
          visible: false
        }],
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
                $('#agregar_Cuenta').modal('show');
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
        "ajax": "functions/function_CuentasBancarias.php",
        "columns": [{
            "data": "Estado"
          },
          {
            "data": "Nombre"
          },
          {
            "data": "Tipo"
          },
          {
            "data": "Saldo"
          },
          {
            "data": "Acciones",
            width: "5%",
          }
        ],
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
      <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
      <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
      <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">
      <input type="hidden" id="txtPantalla" value="<?= $screen; ?>">

      <!-- Main Content -->
      <div id="content">
        <?php
        $rutatb = "../";
        $icono = 'ICONO-CUENTAS-INTERNAS-AZUL.svg';
        $titulo = 'Cuentas Bancarias';
        require_once '../topbar.php';
        ?>
        <!-- Begin Page Content -->
        <div class="container-fluid">
          <div class="card">
            <!-- Page Heading -->
            <input type="hidden" name="" id="emp_id" value="<?php echo $idempresa ?>">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table" id="tblCuentasBancarias" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Estado</th>
                      <th>Nombre</th>
                      <th>Tipo</th>
                      <th>Saldo</th>
                      <th></th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!-- /.container-fluid -->
      </div> <!-- End of Main Content -->

      <!-- Footer -->
      <?php
      $rutaf = "../";
      require_once '../footer.php';
      ?>
      <!-- End of Footer -->
    </div>
    <!-- End of Main Content -->
  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!--ADD MODAL SLIDE Cuentas-->
  <div class="modal fade right" id="agregar_Cuenta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="overflow-y: scroll;">
    <!-- Add class .modal-full-height and then add class .modal-right or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right  modal-md" role="document">
      <div class="modal-content">
        <form action="" id="agregarCuenta" method="POST">
          <!--<input type="hidden" name="idProyectoA" value="">-->
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Agregar Cuenta</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>

          <div class="modal-body">

            <div class="form-group">
              <label for="usr">Tipo de cuenta:*</label>
              <select class="form-control" name="cmbTipoCuenta" id="cmbTipoCuenta" required onchange="validEmptyInput(this)">
                <option value="0">Seleccione una opcion...</option>
                <option value="1">Cuentas de Cheques(Bancaria)</option>
                <option value="2">Crédito</option>
                <option value="3">Otro (No bancarias o control interno)</option>
                <option value="4">Caja chica</option>
              </select>
              <div class="invalid-feedback" id="invalid-tipoCnt">La cuenta debe tener un tipo.</div>
            </div>

            <div class="form-group">
              <label for="usr">Nombre de la cuenta:*</label>
              <input class="form-control alphaNumeric-only" type="text" name="txtNombreCuenta" id="txtNombreCuenta" onKeyUp="validEmptyInput(this)">
              <div class="invalid-feedback" id="invalid-nombreCnt">La cuenta debe tener un nombre.</div>
            </div>

            <input class="form-control" id="notaBotones" name="notaBotones" type="text" style="color: darkred; background-color: transparent!important; border: none;" value="Nota: Seleccione el tipo de cuenta para habilitar los botones." readonly>

            <div id="cajaChicaA">
              <div class="form-group">
                <label for="usr">Responsable:*</label>
                <select class="form-control" name="cmbResponsable" id="cmbResponsable" required onchange="validEmptyInput(this)">
                  <option selected disabled>Seleccione una opcion...</option>
                  <?php
                  $stmt = $conn->prepare('SELECT emp.PKEmpleado, emp.Nombres, emp.PrimerApellido, emp.SegundoApellido
                    from empleados emp
                    INNER JOIN relacion_tipo_empleado rte
                    on emp.PKEmpleado = rte.empleado_id
                    WHERE emp.empresa_id = :empresa AND rte.tipo_empleado_id = :tipoEmpleado');
                  $stmt->bindValue(':empresa', $idempresa);
                  $stmt->bindValue(':tipoEmpleado', $idTipoResponsable);
                  $stmt->execute();
                  $responsables = $stmt->fetchAll(PDO::FETCH_ASSOC);
                  foreach ($responsables as $responsable) {
                  ?>
                    <option value="<?= $responsable['PKEmpleado']; ?>"> <?= $responsable['Nombres'] ?>
                      <?= $responsable['PrimerApellido'] ?> <?= $responsable['SegundoApellido'] ?></option>
                  <?php
                  } ?>
                </select>
                <div class="invalid-feedback" id="invalid-ResponsableCajaCh">La cuenta debe tener un responsable.
                </div>
              </div>
              <div class="form-group">
                <label for="usr">Descripción: </label>
                <input type="text" id="areaDescripcion" name="areaDescripcion" class="form-control">
              </div>
              <div class="form-group">
                <label for="usr">Sucursal:*</label>
                <select class="form-control" name="cmbLocacion" id="cmbLocacion" onchange="validEmptyInput(this)">
                  <option selected disabled>Seleccione una opcion...</option>
                  <?php
                  $stmt = $conn->prepare('SELECT * from sucursales WHERE empresa_id = :empresa');
                  $stmt->bindValue(':empresa', $idempresa);
                  $stmt->execute();
                  while ($row = $stmt->fetch()) {
                  ?>
                    <option value="<?= $row['id']; ?>"><?= $row['sucursal']; ?></option>
                  <?php
                  } ?>
                </select>
                <div class="invalid-feedback" id="invalid-SucursalCajaCh">La cuenta debe tener una sucursal.</div>
              </div>
              <div class="form-group">
                <label for="usr">Saldo inicial:*</label>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">$</span>
                  </div>
                  <input type="text" maxlength="11" class="form-control numericDecimal-only" name="txtSaldoInicialCaja" id="txtSaldoInicialCaja" onKeyUp="validEmptyInput(this)">
                  <div class="invalid-feedback" id="invalid-SaldoInCajaCh">La cuenta debe tener un saldo inicial.</div>
                </div>
              </div>
              <label for="">* Campos requeridos</label>
              <div class="modal-footer justify-content-end">
                <button type="button" class="btnesp espAgregar float-right" name="btnAgregarCajaC" id="btnAgregarCajaC"><span class="ajusteProyecto" onclick="agregaCajaChica()">Agregar</span></button>
              </div>
            </div> <!-- fin de cajaChica-->

            <div id="cheques">

              <div class="form-group">
                <label for="usr">Banco o institución:*</label>
                <select class="form-control" name="cmbBanco" id="cmbBanco" onchange="validEmptyInput(this)">
                  <option selected disabled>Seleccione un banco</option>
                  <!-- Si la cuenta es tipo Cheques se muestra la lista de bancos-->
                  <?php
                  $variable = $conn->prepare("SELECT *FROM bancos");
                  $variable->execute();
                  $arreglo = $variable->fetchAll();
                  foreach ($arreglo as $a) {
                  ?>
                    <option value="<?php echo $a['PKBanco']; ?>"><?php echo $a['Banco']; ?></option>
                  <?php } ?>
                </select>
                <div class="invalid-feedback" id="invalid-BancoChe">La cuenta debe tener un banco.</div>
              </div>
              <div class="form-group">
                <label for="usr">Número de cuenta:*</label>
                <div class="input-group mb-3">
                  <input type="text" maxlength="12" class="form-control numeric-only" name="txtNoCuenta" id="txtNoCuenta" placeholder="Ej. 000 000 000 00" onkeyup="validaUnicaNoCuentaCheques(event,this)">
                  <input type="hidden" id="result1" readonly>
                  <div class="invalid-feedback" id="invalid-noCuenta">La cuenta debe tener un número.</div>

                </div>
              </div>

              <div class="form-group">
                <label for="usr">CLABE:*</label>
                <div class="row">
                  <div class="col-lg-12 input-group">
                    <input class="form-control numeric-only" type="numeric" name="txtCLABE" id="txtCLABE" maxlength="18" autofocus="" placeholder="Ej. 000 000 0000000000 0" onkeyup="validaUnicaClabeCheques(event,this)">

                    <input type="hidden" id="result2" readonly>
                    <div class="invalid-feedback" id="invalid-claveCuenta">La cuenta debe tener una clabe.</div>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="usr">Saldo:*</label>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">$</span>
                  </div>
                  <input type="text" maxlength="11" class="form-control numericDecimal-only" name="txtSaldo" id="txtSaldo" onKeyUp="validEmptyInput(this)">
                  <div class="invalid-feedback" id="invalid-saldoChe">La cuenta debe tener un saldo.</div>
                </div>
              </div>
              <label for="">* Campos requeridos</label>
              <div class="modal-footer justify-content-end">
                <button type="button" class="btnesp espAgregar float-right" name="btnAgregarChequesf" id="btnAgregarChequesf" onclick="agregarCheque()"><span class="ajusteProyecto">Agregar</span></button>
              </div>
            </div>
            <!--End div Cheques-->
            <div id="credito">
              <div class="form-group">
                <label for="usr">Banco o institución:*</label>
                <select class="form-control" name="cmbBancoCredito" id="cmbBancoCredito" onchange="validEmptyInput(this)">
                  <option selected disabled>Seleccione un banco</option>
                  <!-- si la cuenta es de CREDITO muestra la lista de los bancos -->
                  <?php
                  $variable = $conn->prepare("SELECT *FROM bancos");
                  $variable->execute();
                  $arreglo = $variable->fetchAll();
                  foreach ($arreglo as $a) {
                  ?>
                    <option value="<?php echo $a['PKBanco']; ?>"><?php echo $a['Banco']; ?></option>
                  <?php } ?>
                </select>
                <div class="invalid-feedback" id="invalid-bancoCred">La cuenta debe tener un banco.</div>
              </div>
              <div class="form-group">
                <label for="usr">Número de crédito:*</label>
                <div class="input-group mb-3">
                  <input type="text" maxlength="11" class="form-control numeric-only" name="txtNoCredito" id="txtNoCredito" onkeyup="validacionUnicoNoCredito()">
                  <div class="invalid-feedback" id="invalid-noCredito">Número de crédito incorrecto.</div>
                </div>
              </div>
              <div class="form-group">
                <label for="usr">Referencia:*</label>
                <input type="text" class="form-control alphaNumeric-only" name="txtReferencia" maxlength="15" id="txtReferencia" onKeyUp="validEmptyInput(this)">
                <div class="invalid-feedback" id="invalid-refCre">La cuenta debe tener una referencia.</div>
              </div>
              <div class="form-group">
                <label for="usr">Limite de credito:*</label>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">$</span>
                  </div>
                  <input type="text" maxlength="11" class="form-control numericDecimal-only" name="txtLimiteCredito" id="txtLimiteCredito" onKeyUp="validEmptyInput(this)">
                  <div class="invalid-feedback" id="invalid-limCred">La cuenta debe tener un limite de credito.</div>
                </div>
              </div>
              <div class="form-group">
                <label for="">* Campos requeridos</label>
              </div>
              <div class="modal-footer justify-content-end">
                <button type="button" class="btnesp espAgregar float-right" name="btnAgregarCreditodd" id="btnAgregarCreditodd" onclick="agregarCredito()"><span class="ajusteProyecto">Agregar</span></button>
              </div>
            </div>
            <!-- CREATE OTROS -->
            <div id="otros">

              <div class="form-group">
                <label for="">Identificador de la cuenta:* </label>
                <input class="form-control numeric-only" type="text" name="txtIdCuenta" id="txtIdCuenta" maxlength="11" onkeyup="validacionUnicoIdentificador(event,this)">
                <input type="hidden" id="result5" readonly>
                <div class="invalid-feedback" id="invalid-identOtros">La cuenta debe tener un identificador.</div>
              </div>
              <div class="form-group">
                <label for="">Descripción: </label>
                <input class="form-control" type="text" name="txtDescripcion" id="txtDescripcion">
              </div>
              <div class="form-group">
                <label for="usr">Saldo inicial:*</label>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">$</span>
                  </div>
                  <input type="text" maxlength="11" class="form-control numericDecimal-only" name="txtSaldoInicial" id="txtSaldoInicial" onKeyUp="validEmptyInput(this)">
                  <div class="invalid-feedback" id="invalid-saldoInOtros">La cuenta debe tener un saldo inicial.</div>
                </div>
              </div>
              <div class="form-group">
                <label for="">* Campos requeridos</label>
              </div>

              <div class="modal-footer justify-content-end">
                <button type="button" class="btnesp espAgregar float-right" name="" id="btnAgregarOtra" onclick="agregaOtras()"><span class="ajusteProyecto">Agregar</span></button>
              </div>
            </div>
          </div> <!-- end div Class MODAL BODY-->
        </form>
      </div>
    </div>
  </div>
  <!-- UPDATE WITH MODAL -->
  <div class="modal fade right" id="editar_Cuenta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-md" role="document">
      <div class="modal-content">
        <form action="" id="editarCuenta" method="POST">
          <input type="hidden" name="idCuentaU" id="idCuentaU">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Editar cuenta</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="usr">Nombre de la cuenta:*</label>
              <input type="text" class="form-control alphaNumeric-only" maxlength="30" name="txtNombreCuentaU" id="txtNombreCuentaU" required onKeyUp="validEmptyInput(this)">
              <div class="invalid-feedback" id="invalid-nombreCntU">La cuenta debe tener nombre.</div>
            </div>
            <div class="form-group">
              <label for="usr">Tipo de cuenta:*</label>
              <input class="form-control" type="text" name="txtTipoCuentaU" id="txtTipoCuentaU" value="" readonly required>
            </div>
            <!--UPDATE CUENTAS CHEQUES -->
            <div id="chequesU">
              <div class="form-group">
                <label for="usr">Banco o institución:*</label>
                <select class="form-control" name="cmbBancoU" id="cmbBancoU" readonly disabled>
                </select>
              </div>
              <div class="form-group">
                <label for="usr">Número de cuenta:*</label>
                <div class="input-group mb-3">
                  <input type="text" maxlength="12" class="form-control numeric-only" name="txtNoCuentaU" id="txtNoCuentaU" onkeyup="validaUnicaNoCuentaChequesU(event,this)">
                </div><input type="hidden" id="result4" readonly>
                <div class="invalid-feedback" id="invalid-noCuentaU">La cuenta debe tener una clabe.</div>
              </div>
              <div class="form-group">
                <label for="usr">Saldo:*</label>
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1">$</span>
                  <input type="text" maxlength="11" class="form-control numericDecimal-only" name="txtSaldoInicialU" id="txtSaldoInicialU" readonly disabled>
                </div>
              </div>
              <div class="form-group">
                <label for="usr">CLABE:*</label>
                <div class="input-group mb-3">
                  <input type="text" maxlength="18" class="form-control numeric-only" name="txtClabeUp" id="txtClabeUp" onkeyup="validaUnicaClabeChequesU(event,this)">
                  <input type="hidden" id="result3" readonly>
                  <div class="invalid-feedback" id="invalid-clabeCuentaU">La cuenta debe tener una clabe.</div>
                </div>
              </div>
            </div>
            <!--UPDATE CUENTAS CREDITO -->
            <div id="creditoU">

              <div class="form-group">
                <label for="usr">Banco o institución:*</label>
                <select class="form-control" name="cmbBancoUCredito" id="cmbBancoUCredito" readonly disabled>
                </select>
              </div>
              <div class="form-group">
                <label for="usr">Número de credito:*</label>
                <div class="input-group mb-3">
                  <input type="text" maxlength="11" class="form-control numeric-only" name="txtNoCreditoU" id="txtNoCreditoU" onkeyup="validacionUnicoNoCreditoU()">
                  <div class="invalid-feedback" id="invalid-noCreditoU">La cuenta debe tener número de crédito.</div>
                </div>
              </div>
              <div class="form-group">
                <label for="usr">Referencia:*</label>
                <div class="input-group mb-3">
                  <input type="text" class="form-control numeric-only" name="txtReferenciaU" id="txtReferenciaU" onKeyUp="validEmptyInput(this)">
                  <div class="invalid-feedback" id="invalid-referenciaU">La cuenta debe tener referencia.</div>
                </div>
              </div>
              <div class="form-group">
                <label for="usr">Limite de crédito:*</label>
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1">$</span>
                  <input type="text" maxlength="11" class="form-control numericDecimal-only" name="txtLimiteCreditoU" id="txtLimiteCreditoU" readonly>
                </div>
              </div>
              <div class="form-group">
                <label for="usr">Crédito Utilizado:*</label>
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1">$</span>
                  <input type="text" maxlength="11" class="form-control numericDecimal-only" name="txtCreditoUtilizadoU" id="txtCreditoUtilizadoU" readonly>
                </div>
              </div>
            </div>
            <!-- UPDATE DE CUENTAS OTRAS-->
            <div id="otrasU">
              <div class="form-group">
                <label for="usr">Identificador De la Cuenta:*</label>
                <div class="input-group mb-3">
                  <input type="text" maxlength="20" class="form-control numeric-only" name="txtIdentificadorU" id="txtIdentificadorU" onKeyUp="validacionUnicoIdentificadorU()">
                  <div class="invalid-feedback" id="invalid-identfCntU">La cuenta debe tener un identificador.</div>
                </div>
              </div>
              <div class="form-group">
                <label for="usr">Descripción:</label>
                <input type="text" class="form-control" name="txtDescripcionU" id="txtDescripcionU">
                <div class="invalid-feedback" id="invalid-descrOtU">La cuenta debe tener una descripción.</div>
              </div>
              <div class="form-group">
                <label for="usr">Saldo:*</label>
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1">$</span>
                  <input type="text" maxlength="11" class="form-control numericDecimal-only" name="txtSaldoInicialUOtras" id="txtSaldoInicialUOtras" readonly>
                </div>
              </div>
            </div>
            <!-- UPDATE cuenta caja chica -->
            <div id="cajaU">
              <div class="form-group">
                <label for="usr">Responsable:*</label>
                <select class="form-control" name="cmbResponsableU" id="cmbResponsableU" readonly disabled>
                </select>
              </div>
              <div class="form-group">
                <label for="usr">Descripción:</label>
                <input type="text" class="form-control" name="txtDescripcionUCaja" id="txtDescripcionUCaja">
                <div class="invalid-feedback" id="invalid-descrCajaU">La cuenta debe tener una descripción.</div>
              </div>
              <div class="form-group">
                <label for="usr">Saldo:*</label>
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1">$</span>
                  <input type="text" maxlength="11" class="form-control numericDecimal-only" name="txtSaldoInicialUCaja" id="txtSaldoInicialUCaja" readonly disabled>
                </div>
              </div>
            </div>
            <div class="modal-footer justify-content-end">
              <a class="btnesp first espEliminar" href="#" onclick="eliminarCuenta();" name="idCuentaU" id="idCuentaU" data-toggle="modal" data-target=""><span class="ajusteProyecto">Eliminar cuenta</span></a>
              <button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnEditarCuenta" onclick="editaCuenta()"><span class="ajusteProyecto">Modificar</span></button>
              <button type="button" class="btnesp first espCancelar" data-dismiss="modal" name="" id="" onclick="detalleCuenta()"><span class="ajusteProyecto">Detalles</span></button>
            </div>
        </form>
      </div>
    </div>
  </div>

  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/slimselect.min.js"></script>
  <script src="../../js/scripts.js"></script>

  <script>
    var cmtipoSelect = new SlimSelect({
      select: "#cmbTipoCuenta",
      deselectLabel: '<span class="">✖</span>',
    });
    new SlimSelect({
      select: "#cmbBanco",
      deselectLabel: '<span class="">✖</span>',
    });
    new SlimSelect({
      select: "#cmbResponsable",
      deselectLabel: '<span class="">✖</span>',
    });
    new SlimSelect({
      select: "#cmbLocacion",
      deselectLabel: '<span class="">✖</span>',
    });
    new SlimSelect({
      select: "#cmbBancoCredito",
      deselectLabel: '<span class="">✖</span>',
    });
  </script>

  <script src="../../js/validaciones.js"></script>
  <script src="js/validacion_datos.js" charset="utf-8"></script>
  <script src="js/validacion_clabes.js" charset="utf-8"></script>
  <script src="js/detalles_Cuentas.js"></script>
  <script src="js/agregarCuentas.js" charset="utf-8"></script>
  <script src="js/editar_Cuentas.js" charset="utf-8"></script>

  <script>
    /* $(document).on("mouseover", "#tblCuentasBancarias tbody tr", function () {
      $(this).css("cursor", "pointer");
    }); */
    
     function irDetalleCuenta(id, tipo) {
      /* var tableCuentas = $("#tblCuentasBancarias").DataTable();
      var rowData = tableCuentas.row(this).data();
      var acciones = rowData.Acciones;
      var idpos1 = acciones.indexOf('-');
      var idpos2 = acciones.indexOf('\">');
      var id = acciones.slice(idpos1 + 1, idpos2); */

      if (tipo == 4) {
        $().redirect("detalles_Cuenta.php", {
          idDetalle: id,
          tipoIdCuentaU: tipo,
        });
      } else if (tipo == 2) {
        $().redirect("detalles_CuentaCredito.php", {
          idDetalle: id,
          tipoIdCuentaU: tipo,
        });
      } else if (tipo == 3) {
        $().redirect("detalles_CuentaOtras.php", {
          idDetalle: id,
          tipoIdCuentaU: tipo,
        });
      } else {
        $().redirect("detalles_CuentaCheques.php", {
          idDetalle: id,
          tipoIdCuentaU: tipo,
        });
      }

      $.ajax({
        type: "POST",
        url: "functions/get_Ids.php",
        data: { idDetalle: getId },
        success: function (r) {
          var datos = JSON.parse(r);
          $("#pkCuenta").val(datos.pkcuenta);
          $("#idCuentaActuala").val(datos.pkcuentaActual);
        },
      });
    }

    $("#btnTipoCuenta").click(function() {
      alert();
      var idTipo = $('#tipoIdCuentaU').val();
      //alert("Tipo cuenta: "+idTipo);
      $.ajax({
        type: 'POST',
        url: 'functions/movimientos_Cuentas.php',
        data: {
          'tipoIdCuentaU': idTipo
        },
        success: function(r) {
          var datos = JSON.parse(r);
          console.log(datos.html);
          //$("#idCuentaU").val(datos.pkcuenta);
          //
        }
      });

    });

    function eliminarCuenta() {
      var id = $("#idCuentaU").val();
      //alert("oo: "+id);
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          actions: "d-flex justify-content-around",
          confirmButton: "btn-custom btn-custom--border-blue",
          cancelButton: "btn-custom btn-custom--blue",
        },
        buttonsStyling: false,
      });
      swalWithBootstrapButtons.fire({
        title: '¿Desea eliminar el registro de esta cuenta?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<span class="verticalCenter2">Eliminar Cuenta</span>',
        cancelButtonText: '<span class="verticalCenter2">Cancelar</span>',
        reverseButtons: true
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "functions/eliminar_Cuenta.php",
            type: "POST",
            data: {
              "idCuentaU": id
            },
            success: function(data, status, xhr) {
              if (data == "exito") {
                $('#editar_Cuenta').modal('toggle');
                $('#tblCuentasBancarias').DataTable().ajax.reload();
                Lobibox.notify('success', {
                  size: 'mini',
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: 'center top', //or 'center bottom'
                  icon: true,
                  img: '../../img/chat/checkmark.svg',
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
                  img: '../../img/timdesk/notificacion_error.svg',
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

      swal("¿Desea eliminar el registro de esta cuenta?", {
          buttons: {
            cancel: {
              text: "Cancelar",
              value: null,
              visible: true,
              className: "",
              closeModal: true,
            },
            confirm: {
              text: "Eliminar turno",
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
              url: "functions/eliminar_Cuenta.php",
              type: "POST",
              data: {
                "idTurnoD": id
              },
              success: function(data, status, xhr) {
                if (data == "exito") {
                  $('#editar_Cuenta').modal('toggle');
                  $('#tblCuentasBancarias').DataTable().ajax.reload();
                  Lobibox.notify('error', {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/chat/notificacion_error.svg',
                    msg: 'Cuenta eliminada!'
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

  <script>
    var ruta = "../";
  </script>

  <script>
    $(document).on('change', '#cmbTipoCuenta', function(event) {
      if ($("#cmbTipoCuenta option:selected").val() == 1) {
        $('#cheques').show();
        $('#cmbBanco').prop('required', true);
        $('#cmbEmpresaCheques').prop('required', true);
        $('#txtNoCuenta').prop('required', true);
        $('#txtCLABE').prop('required', true);
        $('#txtSaldo').prop('required', true);
        $('#cmbBancoCredito').prop('required', false);
        //$('#txtNoCredito').prop('required', false);
        $('#txtLimiteCredito').prop('required', false);
        $('#txtCreditoUtilizado').prop('required', false);
        $('#txtIdCuenta').prop('required', false);
        $('#txtSaldoInicial').prop('required', false);
        $('#credito').hide();
        $('#otros').hide();
        $('#cajaChicaA').hide();
        var nota = document.getElementById("notaBotones");
        nota.setAttribute("type", "hidden");
      } else if ($("#cmbTipoCuenta option:selected").val() == 2) {
        $('#credito').show();
        $('#cmbBanco').prop('required', false);
        $('#txtNoCuenta').prop('required', false);
        $('#txtSaldo').prop('required', false);
        $('#cmbBancoCredito').prop('required', true);
        //$('#txtNoCredito').prop('required', true);
        $('#txtLimiteCredito').prop('required', true);
        $('#txtCreditoUtilizado').prop('required', true);
        $('#txtIdCuenta').prop('required', false);
        $('#txtSaldoInicial').prop('required', false);
        $('#cheques').hide();
        $('#otros').hide();
        $('#cajaChicaA').hide();
        var nota = document.getElementById("notaBotones");
        nota.setAttribute("type", "hidden");
      } else if ($("#cmbTipoCuenta option:selected").val() == 3) {
        $('#otros').show();
        $('#cmbBanco').prop('required', false);
        $('#txtNoCuenta').prop('required', false);
        $('#txtSaldo').prop('required', false);
        $('#cmbBancoCredito').prop('required', false);
        $('#txtNoCredito').prop('required', false);
        $('#txtLimiteCredito').prop('required', false);
        $('#cmbEmpresaOtros').prop('required', true);
        $('#txtCreditoUtilizado').prop('required', false);
        $('#txtIdCuenta').prop('required', true);
        $('#txtSaldoInicial').prop('required', true);
        $('#cheques').hide();
        $('#credito').hide();
        $('#cajaChicaA').hide();
        var nota = document.getElementById("notaBotones");
        nota.setAttribute("type", "hidden");

      } else if ($("#cmbTipoCuenta option:selected").val() == 4) {
        $('#cajaChica').show();
        $('#otros').hide();
        $('#credito').hide();
        $('#cheques').hide();
        $('#txtSaldoInicialCaja').prop('required', true);
        var nota = document.getElementById("notaBotones");
        nota.setAttribute("type", "hidden");

      } else {
        var nota = document.getElementById("notaBotones");
        nota.setAttribute("type", "text");
      }

    });
  </script>
  <script>
    $('#cajaChicaA').hide();
    $('#otros').hide();
    $('#credito').hide();
    $('#cheques').hide();

    $(document).on('change', '#cmbTipoCuenta', function(event) {
      if ($("#cmbTipoCuenta option:selected").val() == 1) {
        $('#cheques').show();
        $('#cmbBanco').prop('required', true);
        $('#cmbEmpresaCheques').prop('required', true);
        $('#txtNoCuenta').prop('required', true);
        $('#txtSaldo').prop('required', true);
        $('#credito').hide();
        $('#otros').hide();
        $('#cajaChicaA').hide();
      } else if ($("#cmbTipoCuenta option:selected").val() == 2) {
        $('#credito').show();
        $('#cmbBancoCredito').prop('required', true);
        $('#txtNoCredito').prop('required', true);
        $('#txtLimiteCredito').prop('required', true);
        $('#txtCreditoUtilizado').prop('required', true);
        $('#cheques').hide();
        $('#otros').hide();
        $('#cajaChicaA').hide();
      } else if ($("#cmbTipoCuenta option:selected").val() == 3) {
        $('#otros').show();
        $('#txtIdCuenta').prop('required', true);
        $('#txtSaldoInicial').prop('required', true);
        $('#cheques').hide();
        $('#credito').hide();
        $('#cajaChicaA').hide();
      } else if ($("#cmbTipoCuenta option:selected").val() == 4) {
        $('#cajaChicaA').show();
        $('#otros').hide();
        $('#credito').hide();
        $('#cheques').hide();
      } else {
        $('#cajaChicaA').hide();
        $('#otros').hide();
        $('#credito').hide();
        $('#cheques').hide();
      }
    });
  </script>

  <script>
    /* Reiniciar el modal al cerrarlo */
    $("#editar_Cuenta").on("hidden.bs.modal", function(e) {
      $("#invalid-nombreCntU").css("display", "none");
      $("#txtNombreCuentaU").removeClass("is-invalid");
      $("#txtNombreCuentaU").val("");

      $("#invalid-noCuentaU").css("display", "none");
      $("#txtNoCuentaU").removeClass("is-invalid");
      $("#txtNoCuentaU").val("");

      $("#invalid-clabeCuentaU").css("display", "none");
      $("#txtClabeUp").removeClass("is-invalid");
      $("#txtClabeUp").val("");

      $("#invalid-noCreditoU").css("display", "none");
      $("#txtNoCreditoU").removeClass("is-invalid");
      $("#txtNoCreditoU").val("");

      $("#invalid-referenciaU").css("display", "none");
      $("#txtReferenciaU").removeClass("is-invalid");
      $("#txtReferenciaU").val("");

      $("#invalid-identfCntU").css("display", "none");
      $("#txtIdentificadorU").removeClass("is-invalid");
      $("#txtIdentificadorU").val("");

      $("#invalid-descrOtU").css("display", "none");
      $("#txtDescripcionU").removeClass("is-invalid");
      $("#txtDescripcionU").val("");

      $("#txtTipoCuentaU").val("");
      $("#txtSaldoInicialU").val("");
      $("#txtLimiteCreditoU").val("");
      $("#txtCreditoUtilizadoU").val("");
      $("#txtSaldoInicialUOtras").val("");
    });
  </script>

</body>

</html>
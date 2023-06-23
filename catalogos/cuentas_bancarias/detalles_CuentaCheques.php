<?php
session_start();
//var_dump($_POST);
require_once '../../include/db-conn.php';
$user = $_SESSION["Usuario"];
$fecha = date('Y-m-d');
if (!isset($_POST['idDetalles']) && !isset($_POST['tipoIdCuentaU'])) {
  header("location:index.php");
} else {
  $idDet = $_POST['idDetalle'];
  $tipo = ($_POST['tipoIdCuentaU']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | Detalle cuentas</title>

  <!-- ESTILOS -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
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
  <script src="../../js/slimselect.min.js"></script>

  <script>
    var getId = "<?php echo $idDet; ?>";
    $.ajax({
      type: 'POST',
      url: 'functions/get_Ids.php',
      data: {
        'idDetalle': getId
      },
      success: function(r) {
        var datos = JSON.parse(r);
        //console.log(datos.pkcuenta);
        $("#pkCuenta").val(datos.pkcuenta);
      }
    });
  </script>
  <script>
    var idTipo = "<?php echo $tipo; ?>";
    var idCuenta = "<?php echo $idDet; ?>";
    switch(idTipo){
      case 'Cheques(Bancaria)':
        idTipo = 1;
      break;
      case 'Crédito':
        idTipo = 2;
      break;
      case 'Otros':
        idTipo = 3;
      break;
      default:
        idTipo = 4;
      break;      
    }
    
    $(document).ready(function() {
      tablaInicialCheques(idCuenta);
    });
  </script>

  <style>
    td {
      text-align: left;
    }

    td span {
      color: #000000;
    }
  </style>

</head>

<body id="page-top" class="sidebar-toggled">
  <!-- Page Wrapper -->
  <div id="wrapper">
    <!-- Sidebar -->
    <?php
    //$icono '../../img/toolbar/usuarios.svg';
    $titulo = 'Detalle de cuenta';
    $ruta = "../";
    $ruteEdit = $ruta . "central_notificaciones/";
    require_once '../menu3.php';
    ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">
        <!--
        <input value="<?php echo "hola" . $_POST["tipo"]; ?>">
        -->
        <?php
        $rutatb = "../";
        $icono = '../../img/icons/ICONO_CUENTA_BANCARIA.svg';
        $backIcon = true;
        require_once '../topbar.php';
        ?>
        <!-- Begin Page Content -->
        <div class="container-fluid">
          <div class="card">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table" id="tblDatosGenerales" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th tcolspan="2">Datos generales</th>
                    </tr>
                  </thead>
                  <tbody>

                    <tr>
                      <td>
                        <div>Saldo: <span id="saldoG"></span></div>
                      </td>
                    </tr>
                    <td>
                      <div>Nombre de la cuenta: <span id="nomCuentaG"></span></div>
                    </td>
                    </tr>
                    <tr>
                      <td>
                        <div>Tipo de cuenta: <span id="tipoCuentaG"></span></div>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <div>Número de cuenta: <span id="noCuentaG"></span></div>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <div>CLABE: <span id="clabeG"></span></div>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <div>BANCO: <span id="bancoG"></span></div>
                      </td>
                    </tr>

                  </tbody>
                </table>
              </div>
              <input type="hidden" id="pkCuenta" name="pkCuenta" value="<?php echo $idDet; ?>">
              <div class="row">
                <div class="col-9">
                  <div>
                    <span class="btn-table-custom btn-table-custom--green" data-toggle="modal" data-target="#" onclick="disponerCh(<?php echo $idDet; ?>)"><i class="fas fa-minus-circle"></i> Disponer</span>
                    <span class="btn-table-custom btn-table-custom--red" data-toggle="modal" data-target="#credito_Pagar" onclick="pagar(<?php echo $idDet; ?>)"><i class="fas fa-plus-circle"></i> Depositar</span>
                    <span class="btn-table-custom btn-table-custom--blue-light" data-toggle="modal" data-target="#transferencia_Modal" onclick="disposicionTransfer(<?php echo $idDet; ?>)"><i class="fas fa-exchange-alt"></i> Transferir</span>
                  </div>
                </div>
                <div class="col-3">
                  <button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnEditarCuenta" data-toggle="modal" data-target="#editar_Cuenta" onclick="obtenerIdCuentaEditar(idCuenta, idTipo)"><span class="ajusteProyecto">Modificar</span></button>
                  <a class="btnesp first espEliminar float-right mr-3" href="#" onclick="eliminarCuenta();" name="idCuentaU" id="idCuentaU" data-toggle="modal" data-target=""><span class="ajusteProyecto">Eliminar cuenta</span></a>
                </div>
              </div>
            </div>
          </div>
          <div class="card mt-2">
            <div class="card-body">
              <div class="row">
                <div class="col md-10">
                  <div class="table-responsive">
                    <table class="table" id="tblMovimientosCheques" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th colspan="10" style="color: var(--color-primario); background-color: var(--color-claro);">Últimos Movimientos</th>
                        </tr>
                        <tr>
                          <th>Id</th>
                          <th>Fecha</th>
                          <th>Descripción</th>
                          <th>Retiro/cargo</th>
                          <th>Deposito/Abono</th>
                          <th></th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div> <!-- End col md 8-->
              </div> <!-- end row -->
            </div> <!-- end card body -->
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

  <!-- MODAL DISPONER -->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>
  <div class="modal fade right" id="cheques_Disponer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="overflow-y: scroll;">
    <div class="modal-dialog modal-full-height modal-right  modal-md" role="document">
      <div class="modal-content">
        <form name="formular" action="" id="chequesDisponer" method="POST">
          <input type="hidden" name="idCuentaActualCheque" id="idCuentaActualCheque" style="width: 100px;">
          <input type="hidden" name="saldoDisponibleCheque" id="saldoDisponibleCheque" style="width: 100px;">
          <input type="hidden" name="moActualCheque" id="moActualCheque" style="width: 100px;">
          <input type="hidden" name="nomCuentaCheque" id="nomCuentaCheque" style="width: 100px;">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Disposición</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="idAcuenta" id="idAcuenta" style="width: 100px;">
            <input type="hidden" name="saldoCuentaDest" id="saldoCuentaDest" style="width: 100px;">
            <input type="hidden" name="moACuenta" id="moACuenta" style="width: 100px;">
            <input type="hidden" name="tipoC" id="tipoC" style="width: 100px;">

            <div class="form-group">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="checkDisponer" name="checkDisponer" enabled>
                <label class="form-check-label" for="checkDisponer">
                  A cuenta
                </label>
              </div>
            </div>
            <div id="aCuenta">
              <div class="form-group">
                <select name="cmbACuenta" class="form-control" id="cmbACuenta" onchange="get_ACuenta(<?= $idDet; ?>)">
                </select>
                <div class="invalid-feedback" id="invalid-cuentaDisp">La disposición debe tener una cuenta destino.
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="usr">Cantidad: *</label>
            </div>

            <div class="form-group">
              <div class="row">
                <div class="col">
                  <input class="form-control numericDecimal-only" placeholder="Ej: 00.00" type="numeric" id="cantidadDisponer" onkeyup="validEmptyInput(this)">
                  <div class="invalid-feedback" id="invalid-cantidadDisp">La disposición debe tener una cantidad.</div>
                </div>
                <div class="col">
                  <input class="form-control" type="text" id="cmbMonedaAcD" name="cmbMonedaAcD" value="" disabled>
                </div>
              </div>
            </div>

            <div id="moneda_diferente">
              <div class="form-group">
                <label for="usr">Tipo de cambio:</label>
              </div>
              <div class="row">
                <div class="col md">
                  <input class="form-control numericDecimal-only" type="numeric" placeholder="" name="tipoDeCambioDis" id="tipoDeCambioDis" onkeyup="validEmptyInput(this)">
                  <div class="invalid-feedback" id="invalid-monedaCambio">La moneda de cambio debe tener un valor.</div>
                </div>
                <div class="col">
                  <input class="form-control" type="text" id="monedaACuenta" name="monedaACuenta" value="" disabled>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="usr">Observaciones: </label>
              <textarea class="form-control" placeholder="" type="text" id="areaObservacionD"></textarea>
            </div>
            <br>
            <label for="">* Campos requeridos</label>

            <div class="modal-footer justify-content-center">
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacionDisponer" data-dismiss="modal" id="btnCancelarActualizacionDisponer"><span class="ajusteProyecto">Cancelar</span></button>
              <button type="button" class="btnesp first espAgregar" id="btnAgregarDisposicionCheq" onclick="agregar_DisposicionCh()"><span class="ajusteProyecto" onclick="" disabled>Guardar</span></button>
            </div>
          </div><!-- end div Class MODAL BODY-->
        </form>
      </div>
    </div>
  </div>
  <!-- ADD MODAL TRANSFERIR -->
  <div class="modal fade right" id="transferencia_Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="overflow-y: scroll;">
    <div class="modal-dialog modal-full-height modal-right  modal-md" role="document">
      <div class="modal-content">
        <form name="formular" action="" id="transferencia" method="POST">
          <input type="hidden" name="idCuentaActualTransfer" id="idCuentaActualTransfer" style="width: 100px;">
          <input type="hidden" name="saldoCuentaActual" id="saldoCuentaActual" style="width: 100px;">
          <input type="hidden" id="moActualT" name="moActualT" value="" style="width: 100px;">
          <input type="hidden" name="nomCuentaT" id="nomCuentaT" style="width: 100px;">

          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Transferencia</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <input type="hidden" name="idDestino" id="idDestino" style="width: 100px;">
          <input type="hidden" name="saldoACuenta" id="saldoACuenta" style="width: 100px;">
          <input type="hidden" name="moDestino" id="moDestino" style="width: 100px;">
          <input type="hidden" name="tipoCuenta" id="tipoCuenta" style="width: 100px;">


          <div class="modal-body">
            <div class="form-group">
              <label for="usr">Cuenta Destino:*</label>
              <select class="form-control" name="cmbCuentaDestino" id="cmbCuentaDestino" onchange="get_monedasTransfer(<?= $idDet; ?>)"></select>
              <div class="invalid-feedback" id="invalid-cuentaTrans">La transferencis debe teber una cuenta destino.
              </div>
            </div>
            <div class="form-group">
              <label for="usr">Cantidad a enviar:*</label>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col">
                  <input class="form-control numericDecimal-only" placeholder="Ej: 00.00" type="numeric" name="txtCantidadT" id="txtCantidadT" onkeyup="validEmptyInput(this)">
                  <div class="invalid-feedback" id="invalid-cantidadTrans">La transferencis debe teber una cantidad.
                  </div>
                </div>
                <div class="col">
                  <input class="form-control" type="text" id="moDescripcionActual" name="moDescripcionActual" value="" disabled>
                </div>
              </div>
            </div>
            <div id="moneda_diferenteT">
              <div class="form-group">
                <label for="usr">Tipo de cambio:*</label>
              </div>
              <div class="row">
                <div class="col md">
                  <input class="form-control numericDecimal-only" type="numeric" placeholder="" name="tipoDeCambioT" id="tipoDeCambioT" onkeyup="validEmptyInput(this)">
                  <div class="invalid-feedback" id="invalid-monedaCambioTrans">La moneda de cambio debe tener un valor.
                  </div>
                </div>
                <div class="col">
                  <input class="form-control" type="text" id="monedaDescrip" name="monedaDescrip" value="" disabled>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="usr">Fecha de Tranferencia:*</label>
              <input class="form-control" style="border:none;" value="<?= $fecha; ?>" type="date" min="2021-01-01" max="2030-01-01" required id="txtFechaTransferencia" onchange="validEmptyInput(this)">
              <div class="invalid-feedback" id="invalid-fechaTrans">La tranferencia debe tener una fecha.
              </div>
            </div>
            <div class="form-group">
              <label for="usr">Observaciones: </label>
              <textarea class="form-control" placeholder="" type="text" required id="areaObservaciones"></textarea>
            </div>
            <label for="">* Campos requeridos</label>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal" id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
              <button type="button" class="btnesp first espAgregar " id="btnGuardarTransferencia" onclick="guardarTransferenciaCh()"><span class="ajusteProyecto">Guardar</span></button>
            </div>
          </div> <!-- end div Class MODAL BODY-->
        </form>
      </div>
    </div>
  </div>

  <!-- MODAL PAGO -->
  <div class="modal fade right" id="credito_Pagar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="overflow-y: scroll;">
    <div class="modal-dialog modal-full-height modal-right  modal-md" role="document">
      <div class="modal-content">
        <form name="formular" action="" id="creditoPagar" method="POST">
          <input type="hidden" name="idCuentaAcP" id="idCuentaAcP" style="width: 100px;">
          <input type="hidden" name="saldoDisponibleP" id="saldoDisponibleP" style="width: 100px;">
          <input type="hidden" name="moActualP" id="moActualP" style="width: 100px;">
          <input type="hidden" name="nomCuentaP" id="nomCuentaP" style="width: 100px;">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Deposito</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="idAcuentaP" id="idAcuentaP" style="width: 100px;">
            <input type="hidden" name="saldoCuentaDestP" id="saldoCuentaDestP" style="width: 100px;">
            <input type="hidden" name="moACuentaP" id="moACuentaP" style="width: 100px;">

            <input type="hidden" name="tipoCP" id="tipoCP" style="width: 100px;">

            <div class="form-group">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="checkDisponerP" name="checkDisponerP" enabled>
                <label class="form-check-label" for="checkDisponerP">
                  Desde cuenta
                </label>
              </div>
            </div>
            <div id="aCuentaP">
              <div class="form-group">
                <select name="cmbACuentaP" class="form-control" id="cmbACuentaP" onchange="get_ACuentaP(<?= $idDet; ?>)">
                </select>
                <div class="invalid-feedback" id="invalid-cuentaDeposito">El deposito debe teber una cuenta de origen.
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="usr">Cantidad: *</label>
            </div>

            <div class="form-group">
              <div class="row">
                <div class="col">
                  <input class="form-control numericDecimal-only" placeholder="Ej: 00.00" type="numeric" id="cantidadDisponerP" onkeyup="validEmptyInput(this)">
                  <div class="invalid-feedback" id="invalid-cantidadDeposito">El deposito debe tener una cantidad.</div>
                </div>
                <div class="col">
                  <input class="form-control" type="text" id="cmbMonedaAcP" name="cmbMonedaAcP" value="" disabled>
                </div>
              </div>
            </div>

            <div id="moneda_diferenteP">
              <div class="form-group">
                <label for="usr">Tipo de cambio:</label>
              </div>
              <div class="row">
                <div class="col md">
                  <input class="form-control numericDecimal-only" type="numeric" placeholder="" name="tipoDeCambioP" id="tipoDeCambioP" onkeyup="validEmptyInput(this)">
                  <div class="invalid-feedback" id="invalid-monedaCambioDep">La moneda de cambio debe tener un valor.
                  </div>
                </div>
                <div class="col">
                  <input class="form-control" type="text" id="monedaACuentaP" name="monedaACuentaP" value="" disabled>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="usr">Observaciones: </label>
              <textarea class="form-control" placeholder="" type="text" id="areaObservacionP"></textarea>
            </div>
            <br>
            <label for="">* Campos requeridos</label>

            <div class="modal-footer justify-content-center">
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacionInyeccion" data-dismiss="modal" id="btnCancelarActualizacionInyeccion"><span class="ajusteProyecto">Cancelar</span></button>
              <button type="button" class="btnesp first espAgregar " id="btnAgregarPago" onclick="agregar_Pago()"><span class="ajusteProyecto" onclick="" disabled>Guardar</span></button>
            </div>
          </div><!-- end div Class MODAL BODY-->
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
              <button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnEditarCuenta" onclick="editaCuenta()"><span class="ajusteProyecto">Modificar</span></button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>
  <script src="../../js/validaciones.js"></script>
  <script src="js/validacion_datos.js" charset="utf-8"></script>
  <script src="js/agregar_TransferenciaCapital.js" charset="utf-8"></script>
  <script src="js/funciones_Cheques.js" charset="utf-8"></script>
  <script src="js/validacion_clabes.js" charset="utf-8"></script>
  <script src="js/editar_Cuentas.js" charset="utf-8"></script>
  <script>
    new SlimSelect({
      select: "#cmbACuenta",
      deselectLabel: '<span class="">✖</span>',
    });
    new SlimSelect({
      select: "#cmbACuentaP",
      deselectLabel: '<span class="">✖</span>',
    });
    new SlimSelect({
      select: "#cmbCuentaDestino",
      deselectLabel: '<span class="">✖</span>',
    });

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

    console.log(parseInt(getId));
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
        title: '¿Desea eliminar el registro de esta cuenta?2',
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
              "idCuentaU": parseInt(getId)
            },
            success: function(data, status, xhr) {
              if (data == "exito") {
                window.location.href = '../cuentas_bancarias/';
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
<?php
session_start();

if (isset($_SESSION["Usuario"])) {
  require_once '../../include/db-conn.php';
} else {
  header("location:../dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | EDitar Pago</title>

  <!-- ESTILOS -->
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../css/stylesModal-lg.css" rel="stylesheet">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../css/notificaciones.css">
  <link rel="stylesheet" href="css/disabled.css">
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script type="text/javascript" src="js/editar.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../js/sweet/sweetalert2.js"></script>
  <script src="../../js/validaciones.js"></script>
  <script src="../../js/lobibox.min.js"></script>
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>
  <script src="../../js/jquery.redirect.min.js"></script>
  <script src="js/deletePago.js"></script>
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>
</head>

<body id="page-top" class="sidebar-toggled">

  <!-- Content Wrapper -->

  <div id="wrapper">
    <!-- Sidebar -->
    <?php
    $titulo = "Pagos-completos";
    $ruta = "../";
    $ruteEdit = $ruta . "central_notificaciones/";
    require_once '../menu3.php';
    ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <?php
        $rutatb = "../";

        $icono = 'ICONO-PAGOS-ANTICIPOS-AZUL.svg';
        $backIcon = true;
        require_once "../topbar.php";
        ?>
        <!-- End of Topbar -->
        <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
        <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
        <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">
        <input type="hidden" id="idpago" value="<?php echo (int)($_GET['id']); ?>">
        <!-- Comprobar permisos para estar en la pagina -->
        <?php
        ///Primera parte comprueba si puede ver
        $pkuser = $_SESSION["PKUsuario"];
        $stmt = $conn->prepare("Select funcion_ver, funcion_agregar, funcion_editar, funcion_eliminar, funcion_exportar, 
          pantalla_id, fp.perfil_id from funciones_permisos as fp inner join usuarios as us 
          on fp.perfil_id = us.perfil_id where us.id = $pkuser and pantalla_id = 60");
        $stmt->execute();
        $row = $stmt->fetch();
        //Ponemos en el DOM el permiso ver
        echo ('<input id="ver" type="hidden" value="' . $row['funcion_ver'] . '">');
        //Ponemos en el DOM el permiso editar.
        echo ('<input id="edit" type="hidden" value="' . $row['funcion_editar'] . '">');
        ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <div class="card mb-4">
            <div class="card-header">
              Editar un Pago
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-lg-12">
                  <form id="formeditCpagar" action="" onsubmit="enviarDatosEmpleado(); return false">
                    <div class="form-group">
                      <!-- Example single danger button -->
                      <div class="form-group">
                        <label for="prov_id"></label>

                        <div class="row">
                          <div class="col-sm-4">
                            <input type="hidden" id="proveedorid" />
                            <!-- <div class="row"> -->
                            <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                              <label for="cmbProveedor">Proveedor:*</label>
                              <select name="cmbProveedor" class="form-select disabled" id="cmbProveedor" aria-label="Default select example" onchange="validateSelects('cmbProveedor', 'invalid-nombreProv')">
                              </select>
                              <div class="invalid-feedback" id="invalid-nombreProv">El producto debe tener un fecha de Factura.</div>
                            </div>
                            <!-- </div> -->
                          </div>

                          <div class="col-sm-4">
                            <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                              <label for="usr">Fecha de pago:*</label>
                              <div class="col-sm input-group pegar">
                                <input class="form-control" type="date" name="txtfecha" value="2019-07-22" id="txtfecha" max="<?php echo (date('Y-m-d')); ?>">
                                <div class="invalid-feedback" id="invalid-nombreProd">El producto debe tener un fecha de Factura.</div>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-4">
                            <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                              <label for="cmbTipoPag">Tipo:*</label>
                              <input type="hidden" id="tipopagoid" value="" />
                              <select name="cmbTipoPag" class="form-select" id="cmbTipoPag" aria-label="Default select example" onchange="validateSelects('cmbTipoPag', 'invalid-tipo')">
                                <!--                                       <option value="f" disabled selected>Selecciona un tipo</option>
                                        <option value="0">Trasferencia </option>
                                        <option value="1">Cheque</option>
                                        <option value="2">Efectivo</option>
                                        <option value="3">Tarjeta de credito/debito</option> -->
                              </select>
                              <div class="invalid-feedback" id="invalid-tipo">Campo requerido</div>
                            </div>
                          </div>
                        </div>
                        <br>

                        <div class="row">
                          <div class="col-sm-4">
                            <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                              <label for="cmbCuenta">Cuenta:*</label>
                              <input type="hidden" id="cuentaid" value="" />
                              <select class="form-select" name="cmbCuenta" id="cmbCuenta" aria-label="Default select example" onchange="validateSelects('cmbCuenta', 'invalid-cuenta')"></select>
                              <div class="invalid-feedback" id="invalid-cuenta">El producto debe tener un fecha de Factura.</div>
                            </div>
                          </div>
                          <div class="col-sm-4">
                            <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                              <label for="usr">Referencia:</label>
                              <div class="col-sm input-group pegar">
                                <input type="text" maxlength="50" class="form-control alphaNumeric-only" name="txtreferencia" id="txtreferencia" value="" required maxlength="50" placeholder="Ej. AA - 0001">
                                <div class="invalid-feedback" id="invalid-reference">El producto debe tener una clave interna.</div>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-4">
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-sm-4">
                            <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                              <label for="txtTotal"> <b>Total:*</b></label>
                              <div class="col-sm input-group pegar">
                                <label for="">$
                                  <label for="">
                                    <input class="form-control numericDecimal-only readEditPermissions disabled" type="text" maxlength="50" class="form-control" name="txtTotal" id="txtTotal" value="0" required maxlength="50" placeholder="Ej. AA - 0001" onkeyup="validEmptyInput('txtTotal', 'invalid-txtTotal', 'Campo requerido')">
                                  </label>
                                  <div class="invalid-feedback" id="invalid-txtTotal">El producto debe tener una clave interna.</div>
                                </label>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-4">

                          </div>
                          <div class="col-sm-4">
                            <label class="float-right">* Campos requeridos</label>
                          </div>
                        </div>
                        <br>
                        <div class="row justify-content-center">
                          <div class="col-sm-4">
                            <label class="float-center">
                              <h4>Cuentas afectadas</h2>
                            </label>
                          </div>
                        </div>
                        <div class="card-body">
                          <div class="table-responsive">
                            <table class="table" id="tblmovimientos" width="100%" cellspacing="0">
                              <thead>
                                <tr>
                                  <th>Proveedor</th>
                                  <th>Folio de Factura</th>
                                  <th>Serie de Factura</th>
                                  <th>Fecha de Vencimiento</th>
                                  <th>Importe</th>
                                  <th>Saldo insoluto</th>
                                  <th>Estatus</th>
                                  <th>Id</th>
                                  <th>Seleccionar</th>
                                </tr>
                              </thead>
                            </table>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col">
                            <div class="form-group">
                              <label for="textareaCoemtarios">Comentarios</label>
                              <textarea class="form-control alphaNumeric-only" id="textareaCoemtarios" rows="3" maxlength="140"></textarea>
                              <div class="invalid-feedback" id="invalid-txtTotal">Maximo 140 caracteres en el comenario.</div>
                            </div>                            
                          </div>
                        </div>

                        <!-- <span id="spanbutton" class="float-right d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="left" title="No hay cambios que guardar"> -->
                        <div class="row">
                          <div class="col-sm-9 col-xl-9 col-lg-9">
                          </div>
                          <div class="col-sm-3 col-xl-3 col-lg-3">
                            <span id="spanbutton" class="float-right d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Guardar pago">
                              <button class="btn-custom btn-custom--blue float-right" type="button" id="btnguardar">Guardar</button>
                            </span>
                            <span id="spanbuttond" class="float-right d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Eliminar pago">
                              <button type="button" class="btn-custom btn-custom--border-blue float-right btnEliminar" onclick="modalShow(<?php echo (int)($_GET['id']); ?>,0)">Eliminar</button>
                          </div>
                          <!-- <button type="button" class="btn-custom btn-custom--border-blue btnCancelarActualizacion" onclick = "history.back ()">Regresar</button> -->
                        </div>
                        <!-- </span> -->
                        <?php
                        require_once 'modalEdit.php';
                        ?>
                        <?php
                        $accion = "eliminar el registro?";
                        require_once 'modal_alert_confirm.php';
                        ?>
                        <?php
                        require_once 'modal_alert.php';
                        ?>
                      </div>
                      <!-- End of Content Wrapper -->
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <!-- Footer -->
          <?php
          $rutaf = "../";
          require_once '../footer.php';
          ?>
          <!-- End of Footer -->
        </div>
      </div>
    </div>
    <!-- End of Page Wrapper -->
    <script>
      //VAlidar que no este vacio
      function validEmptyInput(inputID, invalidDivID, textInvalidDiv) {
        if (!$("#" + inputID).val()) {
          $("#" + inputID).addClass("is-invalid");
          $("#" + invalidDivID).css("display", "block");
          $("#" + invalidDivID).text(textInvalidDiv);
        } else {
          $("#" + inputID).removeClass("is-invalid");
          $("#" + invalidDivID).css("display", "none");
          $("#" + invalidDivID).text(textInvalidDiv);
        }
      }

      //Validar los selects
      function validateSelects(selectID, invalidDivId) {
        textInvalidDiv = "Campo requerido";
        if (($('select[name=' + selectID + '] option').filter(':selected').val()) == "f") {
          $("#" + selectID).addClass("is-invalid");
          document.getElementById(invalidDivID).style.display = 'block';
          $("#" + invalidDivID).text(textInvalidDiv);
        } else {
          $("#" + selectID).removeClass("is-invalid");
          document.getElementById(invalidDivID).style.display = 'none';
          $("#" + invalidDivID).text("");
        }

      }
      //Separar los numero en grupos de 3
      function numberWithSpaces(inputID) {
        var parts = $("#" + inputID).val().toString().split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, " ");
        var union = parts.join(".");
        $("#" + inputID).val(union);
      }
      var ar = [];
      $("input:checkbox").change(function() {
        ar.length = 0;
        $("input:checkbox").each(function() {
          if ($(this).is(':checked')) {
            ar.push($(this).val());
          }
        });
        console.log(JSON.stringify(ar));
        alert(JSON.stringify(ar));
      });
    </script>
    <script>
      loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
      setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit')
        .val());
    </script>
    <script src="../../js/slimselect.min.js"></script>
</body>

</html>
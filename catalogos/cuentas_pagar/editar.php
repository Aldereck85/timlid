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
  <title>Timlid | Cuentas Por Pagar</title>

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
  <link href="css/disabled.css" rel="stylesheet">
  

  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="js/update.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../js/validaciones.js"></script>
  <script src="../../js/lobibox.min.js"></script>
  <script src="../../js/slimselect.min.js"></script>
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>
</head>

<body id="page-top" class="sidebar-toggled">

  <!-- Content Wrapper -->

  <div id="wrapper">
    <!-- Sidebar -->
    <?php
    $titulo = "Cuentas por pagar";
    $ruta = "../";
    $ruteEdit = $ruta . "central_notificaciones/";
    require_once '../menu3.php';
    ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Topbar -->
      <?php
      $rutatb = "../";
      $backIcon = true;
      $icono = 'ICONO-CUENTAS-POR-PAGAR-AZUL.svg';
      require_once "../topbar.php"
      ?>
      <!-- End of Topbar -->
      <!-- Main Content -->
      <div id="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col">
              <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
              <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
              <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">
              <input type="hidden" id="txtEstatusFactura">
              <!-- Comprobar permisos para estar en la pagina -->
              <?php
              ///Primera parte comprueba si puede ver
              $pkuser = $_SESSION["PKUsuario"];
              $stmt = $conn->prepare("SELECT funcion_ver, funcion_agregar, funcion_editar, funcion_eliminar, funcion_exportar, pantalla_id, fp.perfil_id
              FROM funciones_permisos AS fp
              INNER JOIN usuarios AS us on fp.perfil_id = us.perfil_id
              WHERE us.id = $pkuser AND pantalla_id = 27");
              $stmt->execute();
              $row = $stmt->fetch();
              //Ponemos en el DOM el permiso ver
              echo ('<input id="ver" type="hidden" value="' . $row['funcion_ver'] . '">');
              //Ponemos en el DOM el permiso editar.
              echo ('<input id="edit" type="hidden" value="' . $row['funcion_editar'] . '">');
              ?>

              <!-- Begin Page Content -->
              <div class="card shadow mb-4">
                <div class="card-body">
                  <form id="formeditCpagar" action="" onsubmit="enviarDatosEmpleado(); return false" class=mb-4>
                    <!-- Example single danger button -->
                    <div class="row">
                      <div class="col-6">
                        <a style="cursor:pointer; padding-right:1.5rem" id="btnPagos" class="btn-table-custom btn-table-custom--blue float-left"><img style="width:1.5rem; vertical-align: top;" src="../../img/facturacion/aplicar_pago.svg"> Registrar pago</a>
                        <a style="cursor:pointer; padding-right:1.5rem" id="btnEliminar" class="btn-table-custom btn-table-custom--blue float-left" ><i class="fa fa-times-circle"></i> Eliminar</a>
                      </div>
                      <div class="d-flex justify-content-end col-6">
                        <button type="button" class="btn dropdown-toggle btn-custom--white-dark btn-custom" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="../../img/icons/ICONO-LISTA DE MATERIALES AZUL NVO-01.svg" width="20" class="mr-1">
                          Documentos Relacionados
                        </button>
                        <div class="dropdown-menu">
                          <a class="dropdown-item" href="#">Notas de Credito</a>
                          <a class="dropdown-item" href="#">Ordenes de Compra</a>
                          <div class="dropdown-divider"></div>
                          <a class="dropdown-item" href="#">Pagos</a>
                        </div>
                      </div>
                    </div>
                    <br>
                    <div class="form-group form-row">
                      <input type="hidden" id="cuenta_id" value="<?php echo (int)($_GET['id']); ?>" />
                      <div class="col-lg-2">
                        <div class="col-xl-12 col-lg-6 col-md col-sm-12 col-xs">
                          <p class="font-large">
                            <b class="color-primary-darker">Folio de Factura:</b>
                            <span id="txtfolio"></span>
                          </p>
                          <p></p>
                          <p class="font-large">
                            <b class="color-primary-darker">Serie de facturas:</b>
                            <span id="txtserie"></span>
                          </p>
                          
                        </div>
                      </div>
                      <div class="col-lg-4">
                        <div class="col-xl-12 col-lg-6 col-md col-sm-12 col-xs">
                          <p class="font-large">
                            <b class="color-primary-darker">Proveedor: </b>
                            <span id="nombre"></span>
                          </p>
                          <p></p>
                          <p class="font-large">
                            <b class="color-primary-darker">Categoria de gastos:</b>
                            <span id="txtCategoriaGasto"></span>
                            <a class="float-right" href="#" data-toggle="tooltip" data-placement="top" title="Editar categoria"><img src="../../img/timdesk/edit.svg" width="16" data-toggle="modal" data-target="#modal_edit_category"></a>
                            <input type="hidden" name="txtIdCategoriaGasto" id="txtIdCategoriaGasto">
                            
                          </p>
                          <p></p>
                          <p class="font-large">
                            <b class="color-primary-darker">Subcategoria de gastos:</b>
                            <span id="txtSubcategoriaGasto"></span>
                            <a class="float-right" href="#" data-toggle="tooltip" data-placement="top" title="Editar subcategoria"><img src="../../img/timdesk/edit.svg" width="16" data-toggle="modal" data-target="#modal_edit_subcategory"></a>
                            <input type="hidden" name="txtIdSubcategoriaGasto" id="txtIdSubcategoriaGasto">
                          </p>
                        </div>
                      </div>
                      <div class="col-lg-3">
                        <div class="col-xl-12 col-lg-6 col-md col-sm-12 col-xs">
                          <p class="font-large">
                            <b class="color-primary-darker">Fecha factura:</b>
                            <span id="txtfechaF"></span>
                          </p>
                          <p></p>
                          <p class="font-large">
                            <b class="color-primary-darker">Fecha que vence:</b>
                            <span id="txtfechaV"></span>
                          </p>
                          
                        </div>
                      </div>
                      <div class="col-lg-3">
                        <div class="col-xl-12 col-lg-6 col-md col-sm-12 col-xs">
                        <h2>
                          <b class="textBlue">Importe: </b>
                          <div>
                            <b><span id="txtimporte_Cabecera">$ 486.50</span></b>
                          </div>
                        </h2>
                        </div>
                      </div>
                    </div>                   
                    <div class="form-row">
                      <label>* Campos requeridos</label>
                    </div>
                  </form>
                  <div class="table-responsive">
                    <table class="table" id="tbldetalle" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>Proveedor</th>
                          <th>Producto</th>
                          <th>Clave</th>
                          <th>Cantidad</th>
                          <th>Precio</th>
                          <th>Descuento</th>
                          <th>IVA</th>
                          <th>IEPS Fijo</th>
                          <th>IEPS</th>
                          <th>Editar</th>
                          <th>Key</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                  <div class="form-group form-row">
                      <div class="col-lg-3">
                        <div class="col-xl-12 col-lg-6 col-md col-sm-12 col-xs">
                          <div class="form-group">
                            <label for="usr" class="color-primary-darker font-large"><b>Subtotal:*</b></label>
                            <!-- Para Agregar el signo de pesos descomentar el input y el JS el el codigo JqueryDependence-->
                            <div class="d-flex align-items-center">
                              <label class="mr-1">$</label><input class="form-control numericDecimal-only readEditPermissions edit" type="text" name="subtotal" id="subtotal" required="" min="0" maxlength="13" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 30.00" onkeyup="validEmptyInput('subtotal', 'invalid-subtotal', 'Campo requerido'), numberWithSpaces('subtotal')">
                            </div>
                            <!-- <input  class="form-control alpha-only" type="number" step="any" name="txtsubtotal" value="" id="txtsubtotal" required maxlength="100" placeholder="Ej. Bata quirúgica desechable" onkeyup="escribirNombre()"> -->
                            <div class="invalid-feedback" id="invalid-subtotal">El producto debe tener un Subtotal.</div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-3">
                        <div class="col-xl-12 col-lg-6 col-md col-sm-12 col-xs">
                          <div class="form-group">
                            <label for="usr" class="color-primary-darker font-large"><b>Importe:*</b></label>
                            <div class="d-flex align-items-center">
                              <label class="mr-1">$</label><input class="form-control numericDecimal-only readEditPermissions edit" type="text" name="txtimporte" id="txtimporte" required="" min="0" maxlength="13" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 30.00" onkeyup="validEmptyInput('txtimporte', 'invalid-importe', 'Campo requerido'), numberWithSpaces('txtimporte')">
                            </div>
                            <div class="invalid-feedback" id="invalid-importe">El Importe no puede estar vacio</div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-3">
                        <div class="col-xl-12 col-lg-6 col-md col-sm-12 col-xs">
                          <div class="form-group">
                          <label for="usr" class="color-primary-darker font-large"><b>IVA:*</b></label>
                            <div class="d-flex align-items-center">
                              <label class="mr-1">$</label><input class="form-control numericDecimal-only readEditPermissions edit" type="text" name="_txtiva" id="_txtiva" required="" min="0" maxlength="13" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 30.00" onkeyup="validEmptyInput('_txtiva', 'invalid-_txtiva', 'Campo requerido para excluir escriba 0.'), numberWithSpaces('_txtiva')">
                            </div>
                            <div class="invalid-feedback" id="invalid-_txtiva">Campo requerido para excluir escriba "0"</div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-3">
                        <div class="col-xl-12 col-lg-6 col-md col-sm-12 col-xs">
                          <div class="form-group">
                            <label  abel for="usr" class="color-primary-darker font-large"><b>IEPS:*</b></label>
                            <div class="d-flex align-items-center">
                              <label class="mr-1">$</label><input class="form-control numericDecimal-only readEditPermissions edit" type="text" name="_txtieps" id="_txtieps" required="" min="0" maxlength="13" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 30.00" onkeyup="validEmptyInput('_txtieps', 'invalid-_txtieps', 'Campo requerido para excluir escriba 0.'), numberWithSpaces('_txtieps')">
                            </div>
                            <div class="invalid-feedback" id="invalid-_txtieps">Campo requerido para excluir escriba "0"</div>
                          </div>
                        </div>
                      </div>
                  </div>
                  <div class="form-row">
                    <div class="form-group col">
                      <label for="usr">Comentarios:</label>
                      <div class="d-flex align-items-center">
                        <textarea class="form-control alphaNumericDot-only edit" name="txtComentarios" id="txtComentarios" placeholder="Comentarios u observaciones de la cuenta"></textarea>
                      </div>
                    </div>
                  </div>
                  <div class="d-flex justify-content-end mt-3">
                    <span id="spanbutton" class="" tabindex="0" data-toggle="tooltip" data-placement="left" title="No hay cambios que guardar">
                      <button disabled class="btn-custom btn-custom--blue" style="pointer-events: none;" type="button" data-toggle="modal" data-target="#mdlsavealert" id="btnguardarDetalle">Guardar</button>
                    </span>
                  </div>
                </div>
              </div>
              <?php
              require_once 'modal_alert_confirm.php';
              require_once 'modalnoempresa.php';
              require_once 'modal_alert.php';
              ?>

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

  <!-- End of Page Wrapper -->
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>
  <script src="../../js/jquery.redirect.min.js"></script>
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
    //Separar los numero en grupos de 3
    function numberWithSpaces(inputID) {
      $(".edit").blur(function() {
        var parts = $("#" + inputID).val().toString().split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, " ");
        var union = parts.join(".");
        $("#" + inputID).val(union);
      });

    }
  </script>
  <script>
    loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
    setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
  </script>

  <div class="modal fade" id="modal_edit_category" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Editar categoria de gasto:</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="cmbEditCategory">Categoria:*</label>
            <select class="form-select" name="cmbEditCategory" id="cmbEditCategory"></select>
          </div>
          <div class="form-group">
            <label for="cmbEditSubcategory">Subcategoria:*</label>
            <select name="cmbEditSubcategory" id="cmbEditSubcategory"></select>
          </div>
        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal">Cancelar</button>
          <button type="button" class="btn-custom btn-custom--blue espAgregar float-right" id="btn_save_editCategory">Guardar</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="modal_edit_subcategory" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Editar subcategoria de gasto:</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p class="font-large">
            <b class="color-primary-darker">Categoria de gastos:</b>
            <span id="txtEditCategoryText"></span>
          </p>
          <div class="form-group">
            <label for="cmbEditSubcategory1">Subcategoria:*</label>
            <select name="cmbEditSubcategory1" id="cmbEditSubcategory1"></select>
          </div>
        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal">Cancelar</button>
          <button type="button" class="btn-custom btn-custom--blue espAgregar float-right" id="btn_save_editSubcategory">Guardar</button>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
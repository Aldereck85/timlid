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
  <title>Timlid | Agregar Pago</title>

  <!-- ESTILOS -->
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="../../css/stylesTable.css" rel="stylesheet">
  <link href="../../css/stylesModal-lg.css" rel="stylesheet">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../css/notificaciones.css" rel="stylesheet">
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="js/agregar.js" type="text/javascript"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../js/sweet/sweetalert2.js"></script>
  <script src="../../js/validaciones.js"></script>
  <script src="../../js/lobibox.min.js"></script>
  
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script>

  </script>
</head>

<body id="page-top" class="sidebar-toggled">

  <!-- Content Wrapper -->

  <div id="wrapper">
    <!-- Sidebar -->
    <?php
    $titulo = "Cuentas por Pagar";
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
        $backIcon = true;
        $icono = 'ICONO-CUENTAS-POR-PAGAR-AZUL.svg';
        require_once "../topbar.php"
        ?>
        <!-- End of Topbar -->
        <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
        <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
        <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">
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
        <div class="container-fluid">
          <div class="card mb-4">
            <div class="card-body">
              <form id="formeditCpagar" action="" onsubmit="enviarDatosEmpleado(); return false">
                <div class="form-row">
                  <div class="form-group col-12 col-md-3">
                    <label for="usr">Tipo de documento:</label><br>
                    <div class="form-check-inline">
                      <input class="form-check-input" type="radio" name="radioDoc" id="radioDoc1" value="1" checked>
                      <label class="form-check-label" for="radioDoc1">
                        Factura
                      </label>
                    </div>
                    <div class="form-check-inline">
                      <input class="form-check-input" type="radio" name="radioDoc" id="radioDoc2" value="2">
                      <label class="form-check-label" for="radioDoc2">
                        Remision
                      </label>
                    </div>
                    <div class="form-check-inline">
                      <input class="form-check-input" type="radio" name="radioDoc" id="radioDoc3" value="4">
                      <label class="form-check-label" for="radioDoc3">
                        Anticipo
                      </label>
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-12 col-md-3">
                    <input type="hidden" id="user_id" value="<?php echo (int)($_SESSION["PKUsuario"]); ?>" />
                    <label for="cmbProveedor" class="d-flex justify-content-between">Proveedor:</label>
                    <select name="cmbProveedor" class="form-select" id="cmbProveedor" aria-label="Default select example" onchange="validateSelects('cmbProveedor', 'invalid-nombreProv')">
                    </select>
                    <div class="invalid-feedback" id="invalid-nombreProv">Campo obligatorio.</div>
                  </div>
                  <div class="form-group col-12 col-md-3">
                    <label for="usr">Fecha de factura:*</label>
                    <input class="form-control" type="date" name="txtfecha" value="<?php echo (date('Y-m-d')); ?>" id="txtfecha" max="<?php echo (date('Y-m-d')); ?>">
                    <div class="invalid-feedback" id="invalid-nombreProd">Campo obligatorio.</div>
                  </div>
                  <div class="form-group col-12 col-md-3">
                    <label for="usr">F.vencimiento:*</label>
                    <input class="form-control" type="date" name="txtfechavenci" value="<?php echo (date('Y-m-d')); ?>" id="txtfechavenci">
                    <div class="invalid-feedback" id="invalid-nombreProd">Campo obligatorio.</div>
                  </div>
                  <div class="form-group col-12 col-md-3">
                    <label for="cmbSucursal">Sucursal:*</label>
                    <select name="cmbSucursal" class="form-select" id="cmbSucursal" aria-label="Default select example" onClick="click(this)" onchange="validateSelects('cmbTipoPag', 'invalid-tipo')">
                    </select>
                    <div class="invalid-feedback" id="invalid-tipo">Campo requerido</div>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-12 col-md-3">
                    <label for="usr">No. de documento*:</label>
                    <input required class="form-control numeric-only" type="text" name="txtNoDocumento" maxlength="7" id="txtNoDocumento" placeholder="Folio" style="float:left;" onchange="validEmptyInput('txtNoDocumento', 'invalid-noDocumento', 'La entrada debe de tener un número de folio.')">
                    <div class="invalid-feedback" id="invalid-noDocumento">La entrada debe de tener número de serie.</div>
                  </div>
                  <div class="form-group col-12 col-md-3">
                    <label for="usr">Serie de factura:</label>
                    <input required class="form-control alphaNumeric-only" type="text" name="txtSerie" id="txtSerie" maxlength="12" placeholder="Serie" style="float:left;">
                    <div class="invalid-feedback" id="invalid-serie">La entrada debe de tener número de serie.</div>
                  </div>
                  <div class="form-group col-12 col-md-3">
                    <label for="cmbCategoriaCuenta">Categoria:*</label>
                    <select class="form-select" name="cmbCategoriaCuenta" id="cmbCategoriaCuenta" required></select>
                    <div class="invalid-feedback" id="invalid-categoriaCuenta">El campo es obligatorio.</div>
                  </div>
                  <div class="form-group col-12 col-md-3">
                    <label for="cmbSubcategoriaCuenta">Subcategoria:</label>
                    <select class="form-select" name="cmbSubcategoriaCuenta" id="cmbSubcategoriaCuenta">
                    </select>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-12 col-md-2">
                    <label for="usr">Subtotal*:</label>
                    <div class="d-flex align-items-center">
                      <label class="mr-1">$</label><input required class="form-control numericDecimal-only" type="number" name="txtSubtotal" id="txtSubtotal" placeholder="Ej. 1000.00" style="float:left;" onchange="validEmptyInput('txtSubtotal', 'invalid-subtotal', 'La entrada debe de tener subtotal.')">
                      <div class="invalid-feedback" id="invalid-subtotal">La entrada debe de tener subtotal.</div>
                    </div>
                  </div>  
                  <div class="form-group col-12 col-md-2">
                    <label for="usr">IVA (Monto):</label>
                    <div class="d-flex align-items-center">
                      <label class="mr-1">$</label><input class="form-control numericDecimal-only" type="number" name="txtIva" id="txtIva" placeholder="Ej. 1000.00" style="float:left;" value="0" onchange="validEmptyInput('txtIva', 'invalid-iva', 'La entrada debe de tener IVA.')">
                      <div class="invalid-feedback" id="invalid-iva">La entrada debe de tener IVA.</div>
                    </div>
                  </div>
                  <div class="form-group col-12 col-md-2">
                    <label for="usr">IEPS (Monto):</label>
                    <div class="d-flex align-items-center">  
                      <label class="mr-1">$</label><input class="form-control numericDecimal-only" type="number" name="txtIEPS" id="txtIEPS" placeholder="Ej. 1000.00" style="float:left;" value="0" onchange="validEmptyInput('txtIEPS', 'invalid-ieps', 'La entrada debe de tener IEPS.')">
                      <div class="invalid-feedback" id="invalid-ieps">La entrada debe de tener IEPS.</div>
                    </div>
                  </div>
                  <div class="form-group col-12 col-md-3">
                    <label for="usr">Importe factura*:</label>
                    <div class="d-flex align-items-center">
                      <label class="mr-1">$</label><input required class="form-control numericDecimal-only" type="number" name="txtImporte" id="txtImporte" placeholder="Ej. 1000.00" style="float:left;" onchange="validEmptyInput('txtImporte', 'invalid-importe', 'La entrada debe de tener importe.')" oninput="dosDecimales(this)">
                      <div class="invalid-feedback" id="invalid-importe">La entrada debe de tener importe.</div>
                    </div>
                  </div>
                  <div class="form-group col-12 col-md-3">
                    <label for="usr">Descuento (Monto):</label>
                    <div class="d-flex align-items-center">
                      <label class="mr-1">$</label><input class="form-control numericDecimal-only" type="number" name="txtDescuento" id="txtDescuento" placeholder="Ej. 1000.00" style="float:left;" onchange="validEmptyInput('txtImporte', 'invalid-importe', 'La entrada debe de tener importe.')">
                    </div>
                  </div>
                  
                </div>
                <div class="form-row">
                  <div class="form-group col">
                    <label for="usr">Comentarios:</label>
                    <div class="d-flex align-items-center">
                      <textarea class="form-control alphaNumericDot-only" name="txtComentarios" id="txtComentarios" placeholder="Comentarios u observaciones de la cuenta"></textarea>
                    </div>
                  </div>
                </div>
                <div class="d-flex justify-content-end">
                  <span id="spanbutton" tabindex="0" data-toggle="tooltip" data-placement="left" title="" data-original-title="Guardar cuenta por pagar">
                    <button class="btn-custom btn-custom--blue" type="button" id="btnguardarDetalle">Guardar</button>
                  </span>
                </div>
                <!-- End of Content Wrapper -->
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

<!-- ADD MODAL CATEGORIA -->
<div class="modal fade right" id="nueva_categoria" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="overflow-y: scroll;">
  <div class="modal-dialog modal-full-height modal-right  modal-md" role="document">
    <div class="modal-content">
      <form name="formular" action="" id="agregar_categoria" method="POST">

        <div class="modal-header">
          <h4 class="modal-title w-100" id="myModalLabel">Nueva categoria</h4>
          <button type="button" class="close" onclick="$('#nueva_categoria').modal('hide');" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="tipoCmbCat">
          <div class="form-group">
            <label for="usr">Nombre categoria:*</label>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col">
                <input class="form-control" type="text" placeholder="Categoria" name="txtCategoria" id="txtCategoria" onkeyup="validEmptyInput(this.id, 'invalid-categoria')">
                <div class="invalid-feedback" id="invalid-categoria">La categoria debe tener un nombre.
                </div>
              </div>
            </div>
          </div>
          <label for="">* Campos requeridos</label>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" onclick="$('#nueva_categoria').modal('hide');" id="cancelarCategoria"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp first espAgregar " id="btnGuardarCategoria" onclick="guardarCategoria($('#tipoCmbCat').val())"><span class="ajusteProyecto">Guardar</span></button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div> 

  <!-- ADD MODAL PROVEEDOR -->
  <div class="modal fade right" id="nuevo_Proveedor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="overflow-y: scroll;">
    <div class="modal-dialog modal-full-height modal-right  modal-md" role="document">
      <div class="modal-content">
        <form action="" method="POST">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Nuevo proveedor</h4>
            <button type="button" class="close" onclick="$('#nuevo_Proveedor').modal('hide');" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="usr">Nombre comercial:*</label>
              <div class="input-group mb-3">
                <input type="text" class="form-control alphaNumeric-only" maxlength="30" name="nombreProv" id="nombreProv" required onkeyup="escribirNombre()">
                <div class="invalid-feedback" id="invalid-nombreProvModal">El proveedor debe tener un nombre.
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="usr">Email:*</label>
              <input type="text" class="form-control" maxlength="30" name="emailProv" id="emailProv" required onkeyup="validarCorreo(this)">
              <div class="invalid-feedback" id="invalid-emailProv">El proveedor debe tener un email.
              </div>
            </div>
            <div class="form-group">
              <label for="cmbTipoPersona">Tipo de persona:*</label>
              <select class="form-control" name="cmbTipoPersona" id="cmbTipoPersona" onchange="validTipoPersona()">
                <option data-placeholder="true"></option>
                <option value="Moral">Moral</option>
                <option value="Física">Física</option>
              </select>
              <div class="invalid-feedback" id="invalid-tipoPersonaProv">El proveedor debe tener un tipo de persona.
              </div>
            </div>
            <div class="form-group">
              <input type="checkbox" id="creditoProv" onchange="activarDesactivarCred(this)">
              <label for="creditoProv">Activar credito:</label>
            </div>
            <div class="form-group">
              <label for="txtDiasCredito">Días de crédito</label>
              <input class="form-control numeric-only" type="text" name="txtDiasCredito" id="txtDiasCredito" disabled onkeyup="validEmptyInput(this.id, 'invalid-diasProv')">
              <div class="invalid-feedback" id="invalid-diasProv">El credito debe tener los dias del credito.
              </div>
              <label for="txtLimiteCredito">Límite de crédito</label>
              <input class="form-control numeric-only" type="text" name="txtLimiteCredito" id="txtLimiteCredito" disabled onkeyup="validEmptyInput(this.id, 'invalid-credProv')">
              <div class="invalid-feedback" id="invalid-credProv">El credito debe tener un limite de credito.
              </div>
            </div>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btnesp first espCancelar btnCancelarNuevoProv" onclick="$('#nuevo_Proveedor').modal('hide');" id="btnCancelarNuevoProv"><span class="ajusteProyecto">Cancelar</span></button>
              <button type="button" class="btnesp espAgregar float-right" name="btnAgregarProv" id="btnAgregarProveedor"><span class="ajusteProyecto">Agregar</span></button>
            </div>
        </form>
      </div>
    </div>
  </div>

  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>
  <script src="../../js/slimselect.min.js"></script>
  <script src="../facturacion/js/slimselect_add.js"></script>
  <script>
    //VAlidar que no este vacio
    function validEmptyInput(inputID, invalidDivID) {
      if (!$("#" + inputID).val()) {
        $("#" + inputID).addClass("is-invalid");
        $("#" + invalidDivID).css("display", "block");
      } else {
        $("#" + inputID).removeClass("is-invalid");
        $("#" + invalidDivID).css("display", "none");
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
    // function numberWithSpaces(inputID) {
    //   var parts = $("#" + inputID).val().toString().split(".");
    //   parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    //   var union = parts.join(".");
    //   $("#" + inputID).val(union);
    // }
    var ar = [];
    $("input:checkbox").change(function() {
      ar.length = 0;
      $("input:checkbox").each(function() {
        if ($(this).is(':checked')) {
          ar.push($(this).val());
        }
      });
    });
  </script>
  <script>
    loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
    setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit')
      .val());
  </script>
</body>

</html>
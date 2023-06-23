<?php

use PhpOffice\PhpSpreadsheet\Calculation\Logical\Boolean;

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
  <title>Timlid | Editar Pago</title>

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
  <link href="../../css/notificaciones.css" rel="stylesheet">
  <link href="css/disabled.css" rel="stylesheet">

  <!-- JS -->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script type="text/javascript" src="js/ver.js"></script>
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
      $titulo = "Pagos";
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
          $icono = 'ICONO-PAGOS-ANTICIPOS-AZUL.svg';
          require_once "../topbar.php"
        ?>
        <!-- End of Topbar -->
      <div class="container-fluid">
            <div class="row">
              <div class="col">
        <input type="hidden" id="txtUsuario" value="<?=$_SESSION['PKUsuario'];?>">
    <input type="hidden" id="txtRuta" value="<?=$ruta;?>">
    <input type="hidden" id="txtEdit" value="<?=$ruteEdit;?>">
    <input type="hidden" id="idpago" value="<?php echo (Int)($_GET['id']); ?>">
    <input type="hidden" id="pagoLibre" value="<?php echo ($_GET['pagoLibre']); ?>">
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
              echo ('<input id="ver" type="hidden" value="'.$row['funcion_ver'].'">');
              //Ponemos en el DOM el permiso editar.
              echo ('<input id="edit" type="hidden" value="'.$row['funcion_editar'].'">');
        ?>

        <!-- Begin Page Content -->
        <div class="card mb-4">
          <div class="card-header">
            Ver pago
          </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                          <!-- Example single danger button -->
                          <span>
                            <div class="d-flex">
                              <div id="btnEditar">
                              </div>
                              <div id="btnEliminar">
                              </div>
                            </div>
                          </span>
                              <div class="form-group">
                              <label for="prov_id"></label>
                                <div class="row">
                                  <div class="col-sm-4">
                                  <input type="hidden" id="proveedorid" value="" />
                                    <!-- <div class="row"> -->
                                    <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                                    <label for="cmbProveedor">Proveedor:</label>
                                            <select  name="cmbProveedor" class="form-select disabled" id="cmbProveedor" aria-label="Default select example" onchange="validateSelects('cmbProveedor', 'invalid-nombreProv')">
                                            </select>
                                            <div class="invalid-feedback" id="invalid-nombreProv">El producto debe tener un fecha de Factura.</div>
                                    </div>
                                    <!-- </div> -->
                                  </div>
                                  
                                  <div class="col-sm-4">
                                  <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                                    <label for="usr">Fecha de pago:</label>
                                      <div class="col-sm input-group pegar">
                                        <input  class="form-control alpha-only disabled" type="text" name="txtfecha" value="" id="txtfecha" required maxlength="100" placeholder="Ej. Bata quirúgica desechable" onkeyup="escribirNombre()">
                                        <div class="invalid-feedback" id="invalid-nombreProd">El producto debe tener un fecha de Factura.</div>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-sm-4">
                                  <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                                    <label for="cmbTipoPag">Tipo:</label>
                                  <input type="hidden" id="tipopagoid" value="" />
                                      <select  name="cmbTipoPag" class="form-select disabled" id="cmbTipoPag" aria-label="Default select example" onchange="validateSelects('cmbTipoPag', 'invalid-tipo')">
                                      </select>
                                      <div class="invalid-feedback" id="invalid-tipo">Campo requerido</div>
                                  </div>
                                  </div>
                                </div>
                                <br>

                                <div class="row">
                                  <div class="col-sm-4">
                                    <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                                      <label for="cmbCuenta">Cuenta:</label>
                                    <input type="hidden" id="cuentaid" value="" />
                                        <select  class="form-select disabled" name="cmbCuenta"  id="cmbCuenta" aria-label="Default select example" onchange="validateSelects('cmbCuenta', 'invalid-cuenta')"></select>
                                        <div class="invalid-feedback" id="invalid-cuenta">El producto debe tener un fecha de Factura.</div>
                                    </div>
                                  </div>
                                  <div class="col-sm-4">
                                    <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                                      <label for="usr">Referencia:</label>
                                          <div class="col-sm input-group pegar">
                                              <input  type="text" maxlength="50" class="form-control alphaNumeric-only disabled" name="txtreferencia" id="txtreferencia" value=""  required maxlength="50" placeholder="Ej. AA - 0001" onkeyup="validEmptyInput('txtreferencia', 'invalid-reference', 'Campo requerido')">
                                              <div class="invalid-feedback" id="invalid-reference">El producto debe tener una clave interna.</div>
                                          </div>
                                    </div>
                                  </div>
                                </div>
                                <br>
                                <div class="row d-none" id="cat_cuentas">
                                  <div class="col-sm-4">
                                      <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                                          <label for="cmbCategoriaCuenta">Categoria:*</label>
                                          <input class="form-control alphaNumeric-only disabled" type="text" name="txtCategoriaCuenta" id="txtCategoriaCuenta">
                                          <div class="invalid-feedback" id="invalid-categoriaCuenta">El producto debe tener una clave interna.</div>
                                      </div>
                                  </div>
                                  <div class="col-sm-4">
                                      <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                                          <label for="cmbCategoriaCuenta">Subcategoria:*</label>
                                          <input class="form-control alphaNumeric-only disabled" type="text" name="txtSubcategoriaCuenta" id="txtSubcategoriaCuenta">
                                          <div class="invalid-feedback" id="invalid-subcategoriaCuenta">El producto debe tener una clave interna.</div>
                                      </div>
                                  </div>
                                <div class="col-sm-4">
                                </div>
                          </div>
                              </div>
                              
                              <div class="row">
                                <div class="col-sm-4">
                                    <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                                      <label for="usr">Total:</label>
                                          <div class="col-sm input-group pegar">
                                          <label for="">$
                                              <label for="">
                                              <input  class="form-control numericDecimal-only readEditPermissions disabled" type="text" maxlength="50" class="form-control" name="txtTotal" id="txtTotal" value="0"  required maxlength="50" placeholder="Ej. AA - 0001" onkeyup="validEmptyInput('txtTotal', 'invalid-txtTotal', 'Campo requerido')">
                                              </label>
                                              <div class="invalid-feedback" id="invalid-txtTotal">El producto debe tener una clave interna.</div>
                                            </label>
                                          </div>
                                    </div>
                                  </div>
                                  <div class="col-sm-4">
                                                              
                                  </div>                             
                                <div class="col-sm-4">
                                                                 
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
                                        <th>Estatus</th>
                                        <th>Id</th>
                                      </tr>
                                    </thead>
                                  </table>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col">
                                  <div class="form-group">
                                    <label for="textareaCoemtarios">Comentarios:</label>
                                    <textarea  class="form-control alphaNumeric-only disabled" id="textareaCoemtarios" rows="3"></textarea>
                                  </div>                                  
                                </div>
                              </div>
                              </div>
                              </div>
                              </div>
                              </div>
                              </div>
                              </div>
                              </div>
                              </div>
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
      <br><br><br>
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
  function validateSelects(selectID,invalidDivId){
    textInvalidDiv = "Campo requerido";
    if (($('select[name='+selectID+'] option').filter(':selected').val())=="f") {
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
    var union =parts.join(".");
    $("#" + inputID).val(union);
}
var ar=[];
	$("input:checkbox").change(function() {
  	ar.length=0;
    $("input:checkbox").each ( function() {
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
  <script src="js/deletePago.js"></script>
</body>

</html>
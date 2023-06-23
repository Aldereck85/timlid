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
  <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Timlid | Cuentas Por pagar</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="js\update.js"></script>
  <!-- Core plugin JavaScript-->
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../js/sweet/sweetalert2.js"></script>
  <script src="../../js/validaciones.js"></script>
  <script src="../../js/lobibox.min.js"></script>
  <script src="../../js/slimselect.min.js"></script>
  
  

  <!-- Page level plugins -->
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.bootstrap4.min.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>

  <!-- Custom fonts for this template -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.bootstrap4.min.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="../../css/stylesTable.css" rel="stylesheet">
  <link href="../../css/stylesModal-lg.css" rel="stylesheet">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
  

  <!-- Personales -->
  <link href="css\menus.css" rel="stylesheet">
  <link rel="stylesheet" href="../../css/notificaciones.css">
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <style>

  </style>
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

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <?php
          $rutatb = "../";
          $icono = '../../img/icons/CUENTAS POR PAGAR.svg';
          require_once "../topbar.php"
        ?>
        <!-- End of Topbar -->
        <input type="hidden" id="txtUsuario" value="<?=$_SESSION['PKUsuario'];?>">
    <input type="hidden" id="txtRuta" value="<?=$ruta;?>">
    <input type="hidden" id="txtEdit" value="<?=$ruteEdit;?>">
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
            Tarjeta de Detalles de la Cuenta por Pagar
          </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <form id="formeditCpagar" action="" onsubmit="enviarDatosEmpleado(); return false">
                        <div class="form-group">
                          <!-- Example single danger button -->
                        <div class="btn-group">
                          <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Documentos Relacionados
                          </button>
                          <div class="dropdown-menu">
                            <a class="dropdown-item" href="#">Notas de Credito</a>
                            <a class="dropdown-item" href="#">Ordenes de Compra</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Pagos</a>
                          </div>
                        </div>
                              <div class="form-group">
                              <label for="prov_id"></label>
                                <div class="row">
                                  <div class="col-sm-4">
                                  <input type="hidden" id="user_id" value="<?php echo (Int)($_GET['id']); ?>" />
                                    <!-- <div class="row"> -->
                                    <label for="usr">Proveedor:</label>
                                      <div class="col-sm input-group pegar">
                                        <input disabled class="form-control alpha-only" type="text" id="nombre" name="txtNombre" value=""  required maxlength="100" placeholder="Ej. Bata quirúgica desechable" onkeyup="escribirNombre()">
                                        <div class="invalid-feedback" id="invalid-nombreProd">El producto debe tener un Nombre.</div>
                                      </div>
                                    <!-- </div> -->
                                  </div>
                                  <div class="col-sm-4">
                                    <label for="usr">Folio de Factura:</label>
                                    <!-- <div class="row"> -->
                                        <!-- OJO Poner el Style en un css -->
                                      <div class="col-sm input-group pegar">
                                        <input disabled class="form-control alpha-only" type="text" name="txtfolio" value="" id="txtfolio" required maxlength="100" placeholder="Ej. Bata quirúgica desechable" onkeyup="escribirNombre()">
                                        <div class="invalid-feedback" id="invalid-nombreProd">El producto debe tener un Folio de Factura.</div>
                                      </div>
                                    <!-- </div> -->
                                  </div>
                                  <div class="col-sm-4">
                                    <label for="usr">Serie de facturas:</label>
                                    <!-- <div class="row"> -->
                                      <div class="col-sm input-group pegar">
                                        <input disabled class="form-control alpha-only" type="text" name="txtserie" value="" id="txtserie" required maxlength="100" placeholder="Ej. Bata quirúgica desechable" onkeyup="escribirNombre()">
                                        <div class="invalid-feedback" id="invalid-nombreProd">El producto debe tener una serie de facturas.</div>
                                      </div>
                                    <!-- </div> -->
                                  </div>
                                </div>
                                <br>
                                <div class="row">
                                <div class="col-sm-2">
                                        <label for="usr">Fecha factura:</label>
                                        <!-- <div class="row"> -->
                                        <div class="col-sm input-group pegar">
                                            <input disabled type="text" maxlength="50" class="form-control" name="txtfechaF" id="txtfechaF" value=""  required maxlength="50" placeholder="Ej. AA - 0001" onkeyup="escribirClave()">
                                            <div class="invalid-feedback" id="invalid-claveProd">El producto debe tener una clave interna.</div>
                                            <!-- <a href="#" class="btn-custom btn-custom--blue float-right" id="btnGenerarClave">Generar</a> -->
                                        </div>
                                        <!-- </div> -->
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="usr">Fecha que vence:</label>
                                        <!-- <div class="row"> -->
                                        <div class="col-sm input-group pegar">
                                            <input disabled type="text" maxlength="50" class="form-control" name="txtfechaV" id="txtfechaV" value=""  required maxlength="50" placeholder="Ej. AA - 0001" onkeyup="escribirClave()">
                                            <div class="invalid-feedback" id="invalid-claveProd">El producto debe tener una clave interna.</div>
                                            <!-- <a href="#" class="btn-custom btn-custom--blue float-right" id="btnGenerarClave">Generar</a> -->
                                        </div>
                                        <!-- </div> -->
                                    </div>
                                <div class="col-sm-2">
                                    <label for="usr">Subtotal:*</label>
                                    <!-- <div class="row"> -->
                                      <div class="col-sm input-group pegar">
                                        <!-- Para Agregar el signo de pesos descomentar el input y el JS el el codigo JqueryDependence-->
                                        <label>$ <label><input class="form-control numericDecimal-only readEditPermissions edit" type="text" name="subtotal" id="subtotal"autofocus="" required="" min="0" maxlength="13" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 30.00" onkeyup="validEmptyInput('subtotal', 'invalid-subtotal', 'Campo requerido'), numberWithSpaces('subtotal')">                                      
                                         <!-- <input  class="form-control alpha-only" type="number" step="any" name="txtsubtotal" value="" id="txtsubtotal" required maxlength="100" placeholder="Ej. Bata quirúgica desechable" onkeyup="escribirNombre()"> -->
                                         <div class="invalid-feedback" id="invalid-subtotal">El producto debe tener un Subtotal.</div>
                                      </div>
                                    <!-- </div> -->
                                  </div>
                                   <div class="col-sm-2">
                                    <label for="usr">Importe:*</label>
                                    <!-- <div class="row"> -->
                                      <div class="col-sm input-group pegar">
                                      <label> $ <label><input class="form-control numericDecimal-only readEditPermissions edit" type="text" name="txtimporte" id="txtimporte" autofocus="" required="" min="0" maxlength="13" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 30.00" onkeyup="validEmptyInput('txtimporte', 'invalid-importe', 'Campo requerido'), numberWithSpaces('txtimporte')">                                      
                                        <div class="invalid-feedback" id="invalid-importe">El Importe no puede estar vacio</div>
                                      </div>
                                    <!-- </div> -->
                                  </div>
                                  <div class="col-sm-2">
                                    <label for="usr">IVA:*</label>
                                    <!-- <div class="row"> -->
                                    <div class="col-sm input-group pegar ">
                                    <label>$ <label><input class="form-control numericDecimal-only readEditPermissions edit" type="text" name="_txtiva" id="_txtiva" autofocus="" required="" min="0" maxlength="13" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 30.00" onkeyup="validEmptyInput('_txtiva', 'invalid-_txtiva', 'Campo requerido para excluir escriba 0.'), numberWithSpaces('_txtiva')">                                                                              
                                      <div class="invalid-feedback" id="invalid-_txtiva">Campo requerido para excluir escriba "0"</div>
                                      </div>
                                    <!-- </div> -->
                                  </div>

                                  <div class="col-sm-2">
                                    <label for="usr">IEPS:*</label>
                                    <!-- <div class="row"> -->
                                      <div class="col-sm input-group pegar">
                                        <label>$ <label><input class="form-control numericDecimal-only readEditPermissions edit" type="text" name="_txtieps" id="_txtieps" autofocus="" required="" min="0" maxlength="13" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 30.00" onkeyup="validEmptyInput('_txtieps', 'invalid-_txtieps', 'Campo requerido para excluir escriba 0.'), numberWithSpaces('_txtieps')">                                                                              
                                        <div class="invalid-feedback" id="invalid-_txtieps">Campo requerido para excluir escriba "0"</div>
                                      </div>
                                    <!-- </div> -->
                                  </div>
                                 

                                  <div class="col-sm-3">
                                    <br>
                                  <label>* Campos requeridos</label>                                    
                                  </div>
                              </div>
                              <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table" id="tblcuentas" style="width: 100%;" cellspacing="0">
                                        <thead style="width: 100%;">
                                            <tr style="width: 100%;">
                                                <th>Proveedor</th>
                                                <th>Folio de Factura</th>
                                                <th>Fecha de Factura</th>
                                                <th>Fecha de Vencimiento</th>
                                                <th>Vencimiento</th>
                                                <th>Importe</th>
                                                <th>Estatus</th>
                                                <th>Id</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                              </div>
      <button type="button" class="btn-custom btn-custom--border-blue btnCancelarActualizacion" onclick = "history.back ()">Regresar</button>
      <span id="spanbutton" class="float-right d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="left" title="No hay cambios que guardar">
        <button  disabled class="btn-custom btn-custom--blue float-right" style="pointer-events: none;" type="button" data-toggle="modal" data-target="#mdlsavealert" id="btnguardarDetalle">Guardar</button>
      </span>
      <?php
        require_once 'modalEdit.php';
      ?>
      <?php
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
  //Separar los numero en grupos de 3
  function numberWithSpaces(inputID) {
    var parts = $("#" + inputID).val().toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    var union =parts.join(".");
    $("#" + inputID).val(union);
}
</script>
<script>
  loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
  setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit')
    .val());
  </script>
</body>

</html>
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

  <title>Timlid | Agregar Pago</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script type="text/javascript" src="js\vercpdirect.js"></script>
  <!-- Core plugin JavaScript-->
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../js/sweet/sweetalert2.js"></script>
  <script src="../../js/validaciones.js"></script>
  <script src="../../js/lobibox.min.js"></script>
  <script src="../../js/slimselect.min.js"></script>

  
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  
  

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

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
  

  <!-- Personales -->
  <link href="css\menus.css" rel="stylesheet">
  <link rel="stylesheet" href="../../css/notificaciones.css">
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>
<script>

</script>
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
          $backIcon = true;
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
            Agregar una cuenta por pagar
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
                                  <input type="hidden" id="cuenta" value="<?php echo (Int)($_GET['id']); ?>" />
                                    <!-- <div class="row"> -->
                                    <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                                    <label for="cmbProveedor">Proveedor:*</label>
                                        <input type="hidden" id="id_proveedor">
                                            <input name="txtProveedor" class="form-control disabled" id="txtProveedor" aria-label="Default select example" onchange="validateSelects('cmbProveedor', 'invalid-nombreProv')">
                                            <div class="invalid-feedback" id="invalid-nombreProv">El producto debe tener un fecha de Factura.</div>
                                    </div>
                                    <!-- </div> -->
                                  </div>
                                  <div class="col-sm-4">
                                  <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                                    <label for="usr">Fecha de factura:*</label>
                                      <div class="col-sm input-group pegar">
                                        <input  class="form-control disabled" type="date" name="txtfecha" value="<?php echo (date('Y-m-d')); ?>" id="txtfecha"  max="<?php echo (date('Y-m-d')); ?>">
                                        <div class="invalid-feedback" id="invalid-nombreProd">El producto debe tener un fecha de Factura.</div>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-sm-4">
                                  <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                                    <label for="cmbSucursal">Sucursal:*</label>
                                    <input type="hidden" id="id_sucursal">
                                      <select  name="cmbSucursal" class="form-select disabled" id="cmbSucursal" aria-label="Default select example" onClick="click(this)" onchange="validateSelects('cmbTipoPag', 'invalid-tipo')">
                                      </select>
                                      <div class="invalid-feedback" id="invalid-tipo">Campo requerido</div>
                                  </div>
                                  </div>
                                </div>
                                <br>

                                <div class="row">
                                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                                        <div class="providers-disabled">
                                        <label for="usr">No. de documento*:</label>
                                        <div class="input-group">
                                            <input required class="form-control alphaNumeric-only disabled " type="text" name="txtNoDocumento" id="txtNoDocumento" placeholder="Folio" style="float:left;" onchange="validEmptyInput('txtNoDocumento', 'invalid-noDocumento', 'La entrada debe de tener un número de folio.')">
                                            <div class="invalid-feedback" id="invalid-noDocumento">La entrada debe de tener número de serie.</div>
                                        </div>
                                        </div>
                                    </div><div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                                        <div class="providers-disabled">
                                        <label for="usr">Serie de factura*:</label>
                                        <div class="input-group">
                                            <input required class="form-control alphaNumeric-only disabled" type="text" name="txtSerie" id="txtSerie" placeholder="Serie" style="float:left;" onchange="validEmptyInput('txtSerie', 'invalid-serie', 'La entrada debe de tener número de serie.')">
                                            <div class="invalid-feedback" id="invalid-serie">La entrada debe de tener número de serie.</div>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6">
                                        <div class="providers-disabled">
                                        <label for="usr">Subtotal*:</label>
                                        <div class="input-group">
                                            <input required class="form-control numericDecimal-only disabled" type="number" name="txtSubtotal" id="txtSubtotal" placeholder="Ej. 1000.00" style="float:left;" onchange="validEmptyInput('txtSubtotal', 'invalid-subtotal', 'La entrada debe de tener subtotal.')">
                                            <div class="invalid-feedback" id="invalid-subtotal">La entrada debe de tener subtotal.</div>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6">
                                        <div class="providers-disabled">
                                        <label for="usr">IVA (Monto):</label>
                                        <div class="input-group">
                                            <input class="form-control numericDecimal-only disabled" type="number" name="txtIva" id="txtIva" placeholder="Ej. 1000.00" style="float:left;" value="0" onchange="validEmptyInput('txtIva', 'invalid-iva', 'La entrada debe de tener IVA.')">
                                            <div class="invalid-feedback" id="invalid-iva">La entrada debe de tener IVA.</div>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6">
                                        <div class="providers-disabled">
                                        <label for="usr">IEPS (Monto):</label>
                                        <div class="input-group">
                                            <input class="form-control numericDecimal-only disabled" type="number" name="txtIEPS" id="txtIEPS" placeholder="Ej. 1000.00" style="float:left;" value="0" onchange="validEmptyInput('txtIEPS', 'invalid-ieps', 'La entrada debe de tener IEPS.')">
                                            <div class="invalid-feedback" id="invalid-ieps">La entrada debe de tener IEPS.</div>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                                            
                                <div class="row">
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-6">
                                        <div class="providers-disabled">
                                        <label for="usr">Importe factura*:</label>
                                        <div class="input-group">
                                            <input required class="form-control numericDecimal-only disabled" type="number" name="txtImporte" id="txtImporte" placeholder="Ej. 1000.00" style="float:left;" onchange="validEmptyInput('txtImporte', 'invalid-importe', 'La entrada debe de tener importe.')">
                                            <div class="invalid-feedback" id="invalid-importe">La entrada debe de tener importe.</div>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-6">
                                        <div class="providers-disabled">
                                        <label for="usr">Descuento (Monto):</label>
                                        <div class="input-group">
                                            <input class="form-control numericDecimal-only disabled" type="number" name="txtDescuento" id="txtDescuento" placeholder="Ej. 1000.00" style="float:left;">
                                        </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-6">
                                        <div class="providers-disabled">
                                            <label for="usr">Tipo de documento:</label><br>
                                            <div class="form-check-inline">
                                                <input class="form-check-input disabled" type="radio" name="radioDoc" id="factura" value="1" checked>
                                                    <label class="form-check-label" for="factura">
                                                    Factura
                                                </label>
                                            </div>
                                            <div class="form-check-inline">
                                                <input class="form-check-input disabled" type="radio" name="radioDoc" id="remision" value="2">
                                                <label class="form-check-label" for="remision">
                                                    Remision
                                                </label>
                                            </div>
                                            <div class="form-check-inline">
                                                <input class="form-check-input disabled" type="radio" name="radioDoc" id="anticipo" value="4">
                                                <label class="form-check-label" for="anticipo">
                                                    Anticipo
                                                </label>
                                            </div>
                                            </div>
                                    </div>
                                </div>
                              <div class="card-body">
                                <!-- <div class="table-responsive">
                                  <table class="table" id="tblcuentas" width="100%" cellspacing="0">
                                    <thead>
                                      <tr>
                                        <th>Proveedor</th>
                                        <th>Folio de Factura</th>
                                        <th>Fecha de Vencimiento</th>
                                        <th>Importe</th>
                                        <th>Estatus</th>
                                        <th>Id</th>
                                        <th>Acciones</th>
                                      </tr>
                                    </thead>
                                  </table>
                                </div> -->
                              </div>
        
      <!-- </span> -->
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
  
</body>

</html>
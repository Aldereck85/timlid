<?php
session_start();
require_once '../../../../include/db-conn.php';
$user = $_SESSION["Usuario"];
$PKEmpresa = $_SESSION["IDEmpresa"];

$rutaServer = $_ENV['RUTA_ARCHIVOS_READ'].$PKEmpresa.'/archivos'.'/';

$idNotaCredito = $_GET['nc'];

$stmt = $conn->prepare("SELECT p.empresa_id AS empresa,
                               if(count(dncpp.id) = 0, 'M','C') as tipo
                        FROM notas_cuentas_por_pagar ncpp
                          INNER JOIN proveedores p ON ncpp.proveedor_id = p.PKProveedor
                          LEFT JOIN detalle_notas_cuentas_por_pagar dncpp on ncpp.id = dncpp.nota_cuenta_id
                        WHERE ncpp.id = :id
                        ;");
$stmt->bindValue(':id', $idNotaCredito, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch();

$GLOBALS["PKEmpresaNC"] = $row['empresa'];
$GLOBALS["Tipo"] = $row['tipo'];

if (isset($_SESSION["Usuario"])) {
    require_once '../../../../include/db-conn.php';
    $user = $_SESSION["Usuario"];
} else {
    header("location:../../../dashboard.php");
}


if (isset($_SESSION["Usuario"])) {
  require_once '../../../../include/db-conn.php';
  $user = $_SESSION["Usuario"];

  if($GLOBALS["PKEmpresaNC"] != $PKEmpresa){
    header("location:../notas_credito/");
  }
} else {
  header("location:../../../dashboard.php");
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

  <title>Timlid | Editar nota de crédito</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Page level plugins -->
  <script src="../../../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="../../../../vendor/datatables/dataTables.responsive.js"></script>
  <script src="../../../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../../../vendor/datatables/buttons.bootstrap4.min.js"></script>
  <script src="../../../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../../../vendor/jszip/jszip.min.js"></script>
  <script src="../../../../js/lobibox.min.js"></script>
  <script src="../../../../js/numeral.min.js" charset="utf-8"></script>
  <script src="../../../../js/croppie.js"></script>
  <script src="../../../../js/sweet/sweetalert2.js"></script>
  <script src="../../js/editar_notasCredito.js" charset="utf-8"></script>

  <!-- Custom fonts for this template -->
  <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Custom styles for this template -->
  <link href="../../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../../css/slimselect.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../style/agregar_entrada.css">

  <!-- Custom styles for this page -->
  <link href="../../../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link rel="stylesheet" href="../../../../vendor/datatables/buttons.dataTables.css">
  <link href="../../../../vendor/datatables/buttons.bootstrap4.min.css" rel="stylesheet">
  <link href="../../../../css/stylesTable.css" rel="stylesheet">
  <link href="../../../../css/styles.css" rel="stylesheet">
  <link rel="stylesheet" href="../../../../css/croppie.css" />
  <link href="../../../../css/lobibox.min.css" rel="stylesheet">

  <link rel="stylesheet" href="../../../../css/sweetalert2.css">
  <link rel="stylesheet" href="../../../../css/notificaciones.css">
  <link href="../../style/editar_notasCredito.css" rel="stylesheet">
</head>

<body id="page-top">

  <div id="wrapper">
  <!-- Sidebar -->
  <?php
    $ruta = "../../../";
    $ruteEdit = $ruta . "central_notificaciones/";
    require_once $ruta . 'menu3.php';
    ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">
        <!-- Topbar -->
        <?php
          $rutatb = "../../../";
          $icono = 'ICONO-APLICAR-NOTAS-DE-CREDITO-CARGO-AZUL.svg';
          $titulo = 'Editar nota de crédito';
          $backIcon = true;
          require_once $rutatb . 'topbar.php';
        ?>
        <!-- End of Topbar -->
        <input type="hidden" id="txtUsuario" value="<?=$_SESSION['PKUsuario'];?>">
        <input type="hidden" id="txtRuta" value="<?=$ruta;?>">
        <input type="hidden" id="txtEdit" value="<?=$ruteEdit;?>">
        <!-- Begin Page Content -->
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-12">
              <!-- Basic Card Example -->
              <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="datos" role="tabpanel" aria-labelledby="nav-main-tab">
                  <div class="card shadow mb-4">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-lg-12">
                          <form id="formDatosNotaCredito" class="needs-validation" novalidate>
                            <div class="form-group">
                              <div class="row">
                              <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4 col-xs-4">
                                  <label for="usr">Tipo de nota de crédito*:</label>
                                  <div class="row">
                                    <div class="col-lg-12 input-group">
                                      <select name="cmbTipoGral" id="cmbTipoGral" required disabled>
                                          
                                      </select>
                                      <div class="invalid-feedback" id="invalid-tipoGral">La nota de crédito debe de tener un tipo.</div>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4 col-xs-4">

                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-4 col-xs-4">
                                  <span id="inputsFiles">

                                  </span>
                                </div>
                              </div>
                            </div>
                            <br>
                            <span id="diseno">

                            </span>
                            <label for="">* Campos requeridos</label>                  
                          </form>
                          <span id="addBoton">

                          </span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- End Begin Page Content -->
        </div>
        <!-- End Main Content -->
        <!-- Footer -->
        <?php
          $rutaf = "../../../";
          require_once $rutaf . 'footer.php';
        ?>
      </div>
      <!-- End Content Wrapper -->
    </div>
    <!-- End Page Wrapper -->
    
    <!--ADD MODAL SLIDE NOTA DE CREDITO PRODUCTO-->
    <div class="modal fade" id="agregar_Producto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="" method="POST" id="formDatosPrestamosEdit">
            <div class="modal-header">
              <h4 class="modal-title w-100" id="myModalLabel">Agregar producto</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">x</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="card">
                <div class="card-body">
                <div class="table-responsive">
                <table class="table" id="tblListadoProductosDevolucion" width="100%">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Clave</th>
                      <th>Producto</th>
                      <th>Cantidad</th>
                    </tr>
                  </thead>
                </table>
              </div> 
                </div>
              </div>
            </div>
            <div class="modal-footer justify-content-center">
              
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--END ADD MODAL SLIDE NOTA DE CREDITO PRODUCTO-->

    <!--DELETE MODAL SLIDE NOTA DE CREDITO-->
    <div class="modal fade" id="eliminar_NotaCreditoProductoTemp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">¿Desea eliminar el registro de este?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" class="text-light">x</span>
                        </button>
                    </div>
                    <div class="modal-body" style="font-size: 10 px!important; color: red;"></div>
                    <div class="modal-footer">
                        <button class="btn-custom btn-custom--border-blue btnCancelarActualizacion" type="button" data-dismiss="modal"><span class="ajusteProyecto">Cancelar</span></button>
                        <button type="button" onclick="eliminarNotaCreditoProductoTemp()" data-dismiss="modal" class="btn-custom btn-custom--blue">
                            <span class="ajusteProyecto">Eliminar</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--END DELETE MODAL SLIDE NOTA DE CREDITO-->

    <!--DELETE MODAL SLIDE NOTA DE CREDITO-->
    <div class="modal fade" id="eliminar_NotaCredito" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">¿Desea eliminar el registro de este?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" class="text-light">x</span>
                        </button>
                    </div>
                    <div class="modal-body" style="font-size: 10 px!important; color: red;"></div>
                    <div class="modal-footer">
                        <button class="btn-custom btn-custom--border-blue btnCancelarActualizacion" type="button" data-dismiss="modal"><span class="ajusteProyecto">Cancelar</span></button>
                        <button type="button" onclick="obtenerEliminarNotaCredito()" data-dismiss="modal" class="btn-custom btn-custom--blue">
                            <span class="ajusteProyecto">Eliminar</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--END DELETE MODAL SLIDE NOTA DE CREDITO-->

    
    <script src="../../../../js/sb-admin-2.min.js"></script>
    <script src="../../../../js/scripts.js"></script>
    <script src="../../../../js/notificaciones_timlid.js" charset="utf-8"></script>
    <script src="../../../../js/slimselect.min.js"></script>
    <script src="../../../../js/validaciones.js"></script>
    <script>
      loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
      setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
    </script>
    <script>
      _global.PKNotaCredito = '<?php echo $idNotaCredito;?>';
      _global.TipoNC = '<?php echo $GLOBALS["Tipo"];?>';
      _global.rutaServer = '<?php echo $rutaServer;?>'
    </script>
</body>
</html>
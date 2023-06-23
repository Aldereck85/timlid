<?php
session_start();
require_once '../../../../include/db-conn.php';

$PKProducto = $_GET["lstm"];

if (isset($_SESSION["Usuario"])) {
  $user = $_SESSION["Usuario"];
  $PKEmpresa = $_SESSION["IDEmpresa"];
  
  $rutaServer = $_ENV['RUTA_ARCHIVOS_READ'].$PKEmpresa.'/img'.'/';

  $stmt = $conn->prepare("SELECT empresa_id FROM productos WHERE PKProducto = :id");
  $stmt->bindValue(':id', $PKProducto, PDO::PARAM_INT);
  $stmt->execute();
  $row = $stmt->fetch();

  $GLOBALS["PKProducto"] = $PKProducto;
  $GLOBALS["RutaServerRead"] = $rutaServer;
  $GLOBALS["PKEmpresa"] = $row['empresa_id'];

  if ($GLOBALS["PKEmpresa"] != $PKEmpresa) {
    header("location:../../../produccion/catalogos/lista_materiales/");
  }
} else {
  header("location:../../../dashboard");
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | Editar materiales</title>

  <!-- ESTILOS -->
  <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../../css/slimselect.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../style/agregar_entrada.css">
  <link rel="stylesheet" href="../../style/materiales.css">
  <link href="../../../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link rel="stylesheet" href="../../../../vendor/datatables/buttons.dataTables.css">
  <link href="../../../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../../../css/styles.css" rel="stylesheet">
  <link rel="stylesheet" href="../../../../css/croppie.css" />
  <link href="../../../../css/lobibox.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../../../css/sweetalert2.css">
  <link rel="stylesheet" href="../../../../css/notificaciones.css">
  <!-- JS -->
  <script src="../../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../../vendor/datatables/dataTables.responsive.js"></script>
  <script src="../../../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../../../vendor/jszip/jszip.min.js"></script>
  <script src="../../../../js/lobibox.min.js"></script>
  <script src="../../../../js/numeral.min.js" charset="utf-8"></script>
  <script src="../../../../js/croppie.js"></script>
  <script src="../../../../js/sweet/sweetalert2.js"></script>
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
        $icono = '../../../../img/icons/insumos.svg';
        $titulo = 'Editar materiales';
        $backIcon = true;
        require_once $rutatb . 'topbar.php';
        ?>
        <!-- End of Topbar -->
        <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
        <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
        <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <div class="card shadow mb-4">
            <div class="card-body">
              <div class="row">
                <div class="col-lg-12">
                  <form id="formDatosProducto">
                    <div class="form-group">
                      <div class="row">
                        
                        <div class="col-lg-8">
                          <div class="form-group " id="btnDeletePermissions">
                            <div class="row">
                              <div class="col-xl-9 col-lg-9 col-md-6 col-sm-3 col-xs-0">
                                
                              </div>
                              <div class="col-xl-1 col-lg-1 col-md-2 col-sm-4 col-xs-6">
                                <label for="usr">Estatus:*</label>
                              </div>
                              <div class="col-xl-2 col-lg-2 col-md-4 col-sm-5 col-xs-6">
                                <input type="checkbox" id="activeProducto" name="activeProducto" class="check-custom">
                                <label class="shadow-sm check-custom-label" for="activeProducto">
                                  <div class="circle"></div>
                                  <div class="check-inactivo">Inactivo</div>
                                  <div class="check-activo">Activo</div>
                                </label>
                              </div>
                            </div>
                          </div>
                          
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-12">
                                <label for="usr">Nombre:*</label>
                                <div class="row">
                                  <div class="col-lg-12 input-group">
                                    <input class="form-control " type="text" name="txtNombre" id="txtNombre" disabled>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-6">
                                <label for="usr">Clave interna:*</label>
                                <div class="row">
                                  <div class="col-lg-12 input-group">
                                    <input class="form-control " type="text" name="txtClaveInterna" id="txtClaveInterna" style="text-transform:uppercase" disabled>
                                  </div>
                                </div>
                              </div>
                              <div class="col-lg-6">
                                <label for="usr">Código de barras:</label>
                                <div class="row">
                                  <div class="col-lg-12 input-group">
                                    <input class="form-control " type="text" name="txtCodigoBarras" id="txtCodigoBarras" disabled>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-6">
                                <label for="usr">Categoría:</label>
                                <div class="row">
                                  <div class="col-lg-12 input-group">
                                    <input class="form-control " type="text" name="cmbCategoriaProducto" id="cmbCategoriaProducto" disabled>
                                  </div>
                                </div>
                              </div>
                              <div class="col-lg-6">
                                <label for="usr">Marca:</label>
                                <div class="row">
                                  <div class="col-lg-12 input-group">
                                    <input class="form-control " type="text" name="cmbMarcaProducto" id="cmbMarcaProducto" disabled>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-12">
                                <label for="usr">Descripción:</label>
                                <div class="row">
                                  <div class="col-lg-12 input-group">
                                    <textarea class="form-control " type="text" id="txtDescripcionLarga" name="txtDescripcionLarga"></textarea>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                        </div>
                        <div class="col-lg-4">
                          <div class="file-field">
                            <span id="espacioImagen">
                            <div class="mb-4 img-thumbnail" style="position: relative; width:350px; height:350px; display:block; margin:auto; opacity: .6;">
                              <img class="z-depth-1-half" src="../../../../img/timdesk/ICONO AGREGAR2_Mesa de trabajo 1.svg"
                                alt="example placeholder" id="imgProd" name="imgProd" width="100px" height="100px" style=" position: absolute; top: 35%; right: 0; left: 0; margin: 0 auto;">
                            </div>
                            </span>
                            <div class="d-flex justify-content-center">
                              <span id="espacioFile">
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div> 
                    <br>

                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-12">
                          <span id="areaCompuesto">
                          </span>
                          <span id="areaCompuestoEmp">
                          </span>
                        </div>
                      </div>
                    </div>


                    <input type="hidden" id="txtHistorialNombre" value="">
                    <input type="hidden" id="txtHistorialClave" value="">
                    <input type="hidden" id="txtHistorialCodigoBarras" value="">
                    <input type="hidden" id="txtPKProductoEdit" value="${id}">

                    <label for="">* Campos requeridos</label>
                  </form>

                  <div id="btnAgregarProducto2">
                    
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

    <!--ADD MODAL SLIDE PRODUCTOS-->
    <div class="modal fade right" id="agregar_Producto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
      <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
        <div class="modal-content">
          <form action="" method="POST">
            <input type="hidden" name="idInput" value="">
            <div class="modal-header">
              <h4 class="modal-title w-100" id="myModalLabel">Agregar Producto</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">x</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <!-- DataTales Example -->
                <div class="card mb-4">
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table" id="tblListadoProductos" width="100%" cellspacing="0">
                        <thead>
                          <tr>
                            <th>Id</th>
                            <th>Clave</th>
                            <th>Producto</th>
                            <th>Estatus</th>
                          </tr>
                        </thead>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacionProductos" data-dismiss="modal" id="btnCancelarActualizacionProductos"><span class="ajusteProyecto">Cancelar</span></button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--END ADD MODAL SLIDE PRODUCTOS-->

    <!--ADD MODAL SLIDE EMPAQUES-->
    <div class="modal fade right" id="agregar_Empaque" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
      <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
        <div class="modal-content">
          <form action="" method="POST">
            <input type="hidden" name="idInput" value="">
            <div class="modal-header">
              <h4 class="modal-title w-100" id="myModalLabel">Agregar Empaque</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">x</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <!-- DataTales Example -->
                <div class="card mb-4">
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table" id="tblListadoEmpaques" width="100%" cellspacing="0">
                        <thead>
                          <tr>
                            <th>Id</th>
                            <th>Clave</th>
                            <th>Producto</th>
                            <th>Estatus</th>
                          </tr>
                        </thead>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacionProductos" data-dismiss="modal" id="btnCancelarActualizacionProductos"><span class="ajusteProyecto">Cancelar</span></button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--END ADD MODAL SLIDE EMPAQUES-->

    <!--ADD MODAL SLIDE UNIDADES SAT-->
    <div class="modal fade right" id="agregar_UnidadSAT" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <span id="cargarUnidadSAT">
      </span>
      <input id="contadorUnidadSAT" value="0" type="hidden">
      <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
      <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
        <div class="modal-content">
          <form action="" method="POST">
            <!--<input type="hidden" name="idProyectoA" value="">-->
            <div class="modal-header">
              <h4 class="modal-title w-100" id="myModalLabel">Agregar Unidad SAT</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">x</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <!-- DataTales Example -->
                <div class="card mb-4">
                  <div class="card-body">
                    <div class="form-group">
                      <div class="row">
                        <input for="txtBuscarUnidad" class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6" value="Buscar:" style="text-align:right; border:none; background:transparent;" readonly>
                        <input type="text" class="form-control col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6" id="txtBuscarUnidad" name="txtBuscarUnidad" maxlength="255" onkeyup="buscandoUnidad();">
                      </div>
                    </div>
                    <div class="table-responsive">
                      <table class="table" id="tblListadoUnidadesSAT" width="100%" cellspacing="0">
                        <thead>
                          <tr>
                            <th>Id</th>
                            <th>Clave</th>
                            <th>Descripción</th>
                          </tr>
                        </thead>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacionUnidad" data-dismiss="modal"
                id="btnCancelarActualizacionUnidad"><span class="ajusteProyecto">Cancelar</span></button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--END ADD MODAL SLIDE UNIDADES SAT-->


    <script src="../../../../js/sb-admin-2.min.js"></script>
    <script src="../../../../js/scripts.js"></script>
    <script src="../../../../js/notificaciones_timlid.js" charset="utf-8"></script>
    <script src="../../js/editar_material.js" charset="utf-8"></script>
    <script src="../../../../js/slimselect.min.js"></script>
    <script src="../../../../js/validaciones.js"></script>
    <script src="../../js/lista_combo_productos.js" charset="utf-8"></script>
    <script>
      loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
      setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
    </script>
    <script type="text/javascript">
      _global.pkProducto = '<?php echo $GLOBALS["PKProducto"];?>';
      _global.rutaServer = '<?php echo $GLOBALS["RutaServerRead"] ?>'; 
    </script>
</body>

</html>
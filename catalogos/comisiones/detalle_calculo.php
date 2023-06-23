<?php
session_start();
$jwt_ruta = "../../";
require_once '../jwt.php';

date_default_timezone_set('America/Mexico_City');

$token = $_SESSION['token_ld10d'];

if (isset($_SESSION["Usuario"])) {
    require_once '../../include/db-conn.php';
    $user = $_SESSION["Usuario"];
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
  <title>Timlid | Ver cálculo</title>

  <!-- ESTILOS -->
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../css/stylesModal-lg.css" rel="stylesheet">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="../../css/stylesNewTable.css" rel="stylesheet">
  <link href="css/detalle_calculo.css" rel="stylesheet">
  <link href="css/disabled.css" rel="stylesheet">
  
  <!-- JS -->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../js/sweet/sweetalert2.js"></script>
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
    <!-- Page Wrapper -->
    <div id="wrapper">
        
        <!-- Sidebar -->
        <?php
            $titulo = "Ver cálculo de comisión";
            $ruta = "../";
            $ruteEdit = $ruta . "central_notificaciones/";
            require_once '../menu3.php';
        ?>
        <!-- End of Sidebar -->

        <input type="hidden" id="txtUsuario" value="<?=$_SESSION['PKUsuario'];?>">
        <input type="hidden" id="txtRuta" value="<?=$ruta;?>">
        <input type="hidden" id="txtEdit" value="<?=$ruteEdit;?>">
        <input type="hidden" id="idComision" value="<?php echo ($_GET['idComision']); ?>" /> <!-- Recuperacion del ID de la comisión a mostrar -->
        <input type="hidden" id="idVendedor" value="<?php echo ($_GET['idVendedor']); ?>" /> <!-- Recuperacion del ID del vendedor-->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id=loader></div>
            
            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php
                    $rutatb = "../";
                    $backIcon=true;
                    $icono = '../../img/icons/CUENTAS POR PAGAR.svg';
                    require_once "../topbar.php"
                ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    
                    <!-- Page Heading-->
                    <div class="card mb-4">
                        <!-- Opciones para editar o eliminar cálculo -->
                        <div class="card-body">
                            
                            <div class="row">
                                <div id="divEditar">
                                    <a class="btn-table-custom btn-table-custom--blue" id="btnEditar">
                                        <i class="fas fa-edit"></i>Editar
                                    </a>
                                </div>
                                <div id="divEliminar">
                                    <button class="btn-table-custom btn-table-custom--blue" id="btnEliminarCalculo" onclick="eliminarCalculo('')">
                                        <img src="../../img/timdesk/delete.svg" width="15px" height="15px"> Eliminar cálculo
                                    </button>
                                </div>
                            </div>
                            
                            <br>

                            <div class="form-group">
                                
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                                            <label for="usr">Vendedor:</label>
                                            <input class="form-control disabled" type="text" id="txtVendedor">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                                            <label for="usr">Desde:</label>
                                            <input type="date" class="form-control disabled" id="txtFechaDesde">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                                            <label for="usr">Porcentaje de comisión:</label>
                                            <input type="number" class="form-control disabled" id="txtPorcentaje">
                                        </div>
                                    </div>
                                </div>

                                <br>

                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                                            <label for="usr">Fecha de registro:</label>
                                            <input class="form-control disabled" type="text" id="txtFechaRegistro">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                                            <label for="usr">Hasta:</label>
                                            <input type="date" class="form-control disabled" id="txtFechaHasta">
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="usr">Saldo insoluto:</label>
                                        <div class="input-group pegar">
                                            <div class="signoPesos" for="usr">$</div>
                                            <input type="text" id="txtSaldoInsoluto" class="form-control disabled" value="0">          
                                        </div>
                                    </div>
                                    <div class="col-sm-2">                      
                                        <label for="usr">Estatus:</label>
                                        <input type="text" id="txtEstatus" class="form-control disabled">
                                    </div>
                                </div>

                                <br><br><br><br>

                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">                      
                                            <label for="usr">Monto calculado:</label>
                                            <div class="input-group pegar">
                                                <div class="signoPesos" for="usr">$</div>
                                                <input type="text" id="txtMontoCalculado"  class="form-control disabled" value="0">          
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="col-sm-4"> 
                                        <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                                            <label for="usr">Monto ingresado:</label>
                                            <div class="input-group pegar">
                                                <div class="signoPesos" for="usr">$</div>
                                                <input type="text" id="txtMontoComisionado"  class="form-control disabled" value="0">          
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <button type="button" class="btnesp espAgregar float-left" name="btnAbonos" id="btnAbonos">
                                            <span class="ajusteProyecto">Parcialidades</span>
                                        </button>
                                    </div>
                                    <div class="col-sm-2" id="divPagarCanPagoCom">                      
                                        
                                    </div>
                                </div>

                                <br><br>

                                <div class="table-responsive">
                                    <table class="table" id="tblFacturas" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Folio</th>
                                                <th>Fecha de factura</th>
                                                <th>Razón social del cliente</th>
                                                <th>Monto facturado (sin impuestos)</th>
                                                <th>Monto comisionado por factura</th>
                                                <th>Seleccionar</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <!--End of container fluid-->

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

    <?php 
    require_once 'modal_alert_eliminar_calculo.php';
    require_once 'modal_abonos.php';
    require_once 'modal_alert_cancelar_pago_comision.php'
    ?>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Custom scripts for all pages-->
    <script src="../../js/sb-admin-2.min.js"></script>
    <script src="../../js/scripts.js"></script>
    <script src="../../js/jquery.redirect.min.js"></script>
    <script src="js/data_detalle_calculo.js"></script>
</body>
</html>
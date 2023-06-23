<?php 
    $ruta = "../";
    $screen = 14;
    $jwt_ruta = "../../";
    require_once '../validarPermisoPantalla.php';
    require_once '../jwt.php';
    
    date_default_timezone_set('America/Mexico_City');
    $token = $_SESSION['token_ld10d'];
    
    $min = new DateTime();
    $min->sub(new DateInterval('P3D'));
    $years = array_combine(range(date("Y"), 2022), range(date("Y"), 2022));
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
    <title>Timlid | Facturación</title>

    <!-- Bootstrap core JavaScript-->
    <script src="../../vendor/jquery/jquery.min.js"></script>
    <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>

    <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.js"></script>

    <!-- Custom scripts for all pages-->

    <!-- Page level plugins -->
    <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="../../vendor/datatables/dataTables.buttons.js"></script>
    <script src="../../vendor/datatables/buttons.bootstrap4.min.js"></script>
    <script src="../../vendor/datatables/buttons.html5.min.js"></script>
    <script src="../../vendor/jszip/jszip.min.js"></script>

    <!-- Custom fonts for this template -->
    <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template -->
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
    <link href="../../vendor/datatables/buttons.bootstrap4.min.css" rel="stylesheet">
    <link href="../../css/styles.css" rel="stylesheet">
    <!-- <link href="../../css/stylesTable.css" rel="stylesheet"> -->
    <link href="../../css/stylesNewTable.css" rel="stylesheet">
    <link href="../../css/slimselect.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../../css/sweetalert2.css">
    <link href="../../css/lobibox.min.css" rel="stylesheet">

    <!-- <link rel="stylesheet" href="../../css/notificaciones.css"> -->
    <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>

    <link rel="stylesheet" href="css/agregar_factura_concepto.css">

    <script src="../../js/lobibox.min.js"></script>
    <script src="../../js/jquery.redirect.js"></script>
</head>
<body id="page-top">
    <div id=loader></div>
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <?php
        $icono = 'ICONO-FACTURACION-AZUL.svg';
        $titulo = 'Crear factura global';
        $backIcon = true;
        $ruteEdit = $ruta . "central_notificaciones/";
        require_once $ruta . 'menu3.php';
        ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <?php
                $rutatb = "../";
                require_once '../topbar.php';
                ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <form id="form-date-filters">
                                <div id="form-group" class="row">
                                    <div class="col-3">
                                        <label for="txtInitialDate">Fecha inicial</label>
                                        <input class="form-control" type="date" name="txtInitialDate" id="txtInitialDate">
                                    </div>
                                    <div class="col-3">
                                        <label for="txtFinalDate">Fecha final</label>
                                        <input class="form-control" type="date" name="txtFinalDate" id="txtFinalDate">
                                    </div>
                                    <div class="col-3 col-3 d-flex align-items-end">
                                        <button class="btn-custom btn-custom--blue espAgregar" type="button" id="btnFilter" onclick="getDataFilters()">Filtrar</button>
                                    </div>
                                </div>
                                <br>
                                <input type="hidden" id="taxes_json">
                                <div class="table-responsive">
                                    <table class="table" id="tblSales" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Folio</th>
                                                <th>Fecha</th>
                                                <th>Cliente</th>
                                                <th>Subtotal</th>
                                                <th></th>
                                                <th><input type="checkbox" name="sales-checked-all" id="sales-checked-all" disabled></th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <br>
                                
                                <div class="row">
                                    <div class="col-4"></div>
                                    <div class="col-4"></div>
                                    <div class="col-4">
                                        <div id="summary_subtotal"></div>
                                        <div id="summary_taxes"></div>
                                        <div id="summary_total"></div>
                                    </div>
                                </div>
                                <br>
                                <div class="d-flex align-items-end justify-content-end">
                                    <div class="ml-auto p-2">
                                        <button class="btn-custom btn-custom--blue espAgregar" type="button" onclick="showModalGlobalInvoice()">Facturar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- End Page Content -->
            </div>
            <!-- End Main Content -->
        </div>
        <!-- End Content Wrapper -->
    </div>

    <div class="modal fade" id="modal_fiscal_data_general" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title w-100" id="myModalLabel">Datos fiscales facturación</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" tabindex="-2">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-fiscal-data-general">
          <input type="hidden" name="txtInitialDate_hide" id="txtInitialDate_hide">
          <input type="hidden" name="txtFinalDate_hide" id="txtFinalDate_hide">
          <div class="form-group">
            <label for="cmbCFDIUse">Uso CFDI:</label>
            <select name="cmbCFDIUse" id="cmbCFDIUseGeneral" required></select>
            <div class="invalid-feedback" id="invalid-globalInvoiceCFDIUse">La factura require de un uso CFDI.</div>
          </div>
          <div class="form-group">
            <label for="cmbPaidType">Forma de pago:</label>
            <select name="" id="cmbPaidTypeGeneral" required></select>
            <div class="invalid-feedback" id="invalid-globalInvoicePaidType">La factura require de una forma de pago.</div>
          </div>
          <div class="form-group">
            <label for="cmbPaidMethod">Método de pago:</label>
            <select name="cmbPaidMethod" id="cmbPaidMethodGeneral" required></select>
            <div class="invalid-feedback" id="invalid-globalInvoicePaidMethod">La factura require de un método de pago.</div>
          </div>
          <div class="form-group">
            <label for="cmbCurrency">Moneda:</label>
            <select name="cmbCurrency" id="cmbCurrencyGeneral" required></select>
            <div class="invalid-feedback" id="invalid-globalInvoiceCurrency">La factura require de una moneda.</div>
          </div>
          <div class="form-group">
            <label for="cmbPeriodicity">Periodicidad:</label>
            <select name="cmbPeriodicity" id="cmbPeriodicity" required>
              <option value="">Seleccione un periodo...</option>
              <option value="day">Diario</option>
              <option value="week">Semanal</option>
              <option value="fortnight">Quincenal</option>
              <option value="month">Mensual</option>
              <option value="two_months">Bimestral</option>
            </select>
            <div class="invalid-feedback" id="invalid-globalInvoicePeriodicity">La factura require de una Periodicidad.</div>
          </div>
          <div class="form-group">
            <div class="row">
                <div class="col">
                    <label for="cmbMonth">Meses:</label>
                    <select name="cmbMonth" id="cmbMonth" required>
                        <option value="">Seleccione un mes o bimestre...</option>
                        <option value="01">Enero</option>
                        <option value="02">Febrero</option>
                        <option value="03">Marzo</option>
                        <option value="04">Abril</option>
                        <option value="05">Mayo</option>
                        <option value="06">Junio</option>
                        <option value="07">Julio</option>
                        <option value="08">Agosto</option>
                        <option value="09">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>
                        <option value="13">Enero-Febrero</option>
                        <option value="14">Marzo-Abril</option>
                        <option value="15">Mayo-Junio</option>
                        <option value="16">Julio-Agosto</option>
                        <option value="17">Septiembre-Octubre</option>
                        <option value="18">Noviembre-Diciembre</option>              
                    </select>
                    <div class="invalid-feedback" id="invalid-globalInvoiceMonth">La factura require de un mes o bimestre.</div>
                </div>
                <div class="col">
                    <label for="cmbYears">Año</label>
                    <select name="cmbYears" id="cmbYears" required>
                        <?php foreach($years as $r => $val){ $selected = $val == date("Y") ? "selected" : "";?>
                        <option value="<?=$val?>" <?=$selected;?>><?=$val?></option>
                        <?php } ?>
                    </select>
                    <div class="invalid-feedback" id="invalid-globalInvoiceYear">La factura require de un año.</div>
                </div>
            </div>
            
          </div>
        </form>
      </div>

      <div class="modal-footer justify-content-center">
        <button type="button" class="btnesp first espCancelar btnCancelarActualizacion mx-2" data-dismiss="modal"
          id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
        <button type="button" class="btn-custom btn-custom--blue espAgregar mx-2" name="btnAgregar" id="btnSaveDataFiscalGeneral" onclick="saveGlobalInvoice()"><span
          class="ajusteProyecto">Aceptar</span></button>
      </div>
    </div>
  </div>
</div>
    
    <!-- End Page Wrapper -->
    <script src="../../js/sb-admin-2.min.js"></script>
    <script src="../../js/scripts.js"></script>
    <script src="js/agregar_factura_global.js"></script>
    <script src="../../js/numeral.min.js"></script>
    <script src="../../js/slimselect.min.js"></script>
</body>
</html>
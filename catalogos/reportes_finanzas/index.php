<?php
    $screen = 85;
    $ruta = "../";
    require_once $ruta . 'validarPermisoPantalla.php';
    if(isset($_SESSION["Usuario"]) && $permiso === 1){
        require_once '../../include/db-conn.php';
    } else {
        header("location:../dashboard.php");
    }
    $jwt_ruta = "../../";
    require_once '../jwt.php';

    date_default_timezone_set('America/Mexico_City');

    $token = $_SESSION['token_ld10d'];
    require_once "php/class.php";
    $ingresosEgresos = get_data::getDataIncomeExpenses();
    $capitalTrabajo = get_data::getDataWorkingCapital();
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Last-Modified" content="0">
    <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <title>Timlid | Reportes finanzas</title>

    <!-- ESTILOS -->
    <link href="../../css/slimselect.min.css" rel="stylesheet">
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
    <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
    <link href="../../vendor/KeyTable/css/keyTable.dataTables.css" rel="stylesheet">
    <link href="../../css/styles.css" rel="stylesheet">
    <link href="../../css/stylesNewTable.css" rel="stylesheet">
    <link href="../../css/sweetalert2.css" rel="stylesheet">
    <link href="../../css/lobibox.min.css" rel="stylesheet">

    <!-- JS -->
    <script src="../../vendor/jquery/jquery.min.js"></script>
    <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../../js/sb-admin-2.min.js"></script>
    <script src="../../vendor/moment/moment.min.js"></script>
    <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../../vendor/KeyTable/js/dataTables.keyTable.min.js"></script>
    <script src="../../vendor/datatables/dataTables.buttons.js"></script>
    <script src="../../vendor/datatables/datetime-moment.js"></script>
    <script src="../../vendor/datatables/datetime.js"></script>
    <script src="../../vendor/datatables/buttons.html5.min.js"></script>
    <script src="../../vendor/jszip/jszip.min.js"></script>
    <script src="../../js/jquery.redirect.min.js"></script>
    <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>
    <script src="../../js/mdtimepicker.min.js"></script>
    <script src="../../js/permisos_usuario.js"></script>
    <script src="../../js/jquery.redirect.js"></script>
    <script src="../../js/numeral.min.js"></script>
    <script src="../../js/lobibox.min.js"></script>
    <script src="../../js/sweet/sweetalert2.js"></script>
    <script src="../../js/slimselect.min.js"></script>

</head>
<body id="page-top" data-screen="85">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <?php
            $icono = '../../img/icons/CUENTAS POR PAGAR.svg';
            $titulo = 'Reportes finanzas';
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
                        <ul class="nav nav-tabs" id="nav-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="graficaVentasGeneral-tab" data-toggle="tab" href="#graficaVentas" role="tab" aria-controls="detalleGastos" aria-selected="false">Gráficas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="resumenGeneral-tab" data-toggle="tab" href="#resumenGeneral" role="tab" aria-controls="gastos" aria-selected="true">Resumen general</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" id="reporteGastos-tab" data-toggle="tab" href="#reporteGastos" role="tab" aria-controls="gastos" aria-selected="true">Reporte de gastos</a>
                            </li>
                            
                            <!-- <li class="nav-item">
                                <a id="resumenGeneral-tab" class="nav-link active" href="#resumenGeneral" data-toggle="tab" role="tab" aria-controls="nav-datos" aria-selected="true">
                                    Resumen general
                                </a>
                            </li>
                            <li class="nav-item">
                                <a id="graficaVentasGeneral-tab" class="nav-link" href="#graficaVentas" data-toggle="tab" role="tab" aria-controls="nav-datos" aria-selected="true">
                                    Gráficas ventas y gastos general
                                </a>
                            </li> -->

                            <li class="nav-item">
                                <a class="nav-link" id="resumenUtilidades-tab" data-toggle="tab" href="#resumenUtilidades" role="tab" aria-controls="gastos" aria-selected="true">Resumen utilidades</a>
                            </li>
                        </ul>
                        <div class="card">
                            <div class="tab-content"> 
                                <div class="tab-pane" id="resumenGeneral" role="tabpanel" aria-labelledby="resumenGeneral-tab">
                                    <div class="card-body">
                                        <div class="row form-group">
                                            <div class="col-3">
                                                <label for="txtInitialDate">Fecha inicial:</label>
                                                <input class="form-control" type="date" name="txtGeneralInitialDate" id="txtGeneralInitialDate">
                                            </div>
                                            <div class="col-3">
                                                <label for="txtFinalDate">Fecha final:</label>
                                                <input class="form-control" type="date" name="txtGeneralFinalDate" id="txtGeneralFinalDate">
                                            </div>
                                            <div class="col-3 d-flex align-items-end">
                                                <button class="btn-custom btn-custom--blue espAgregar" type="button" onclick="generalFilterData()">Filtrar</button>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-4 textData">
                                                    <p><h2 class="textBlue">Ingresos/Egresos</h2></p>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <p class="textBlue">
                                                                <b>Ventas cobradas:</b>
                                                            </p>
                                                            <p class="textBlue">
                                                                <b>Gastos pagados:</b>
                                                            
                                                            </p>
                                                            <p class=" textBlue">
                                                                <b>Total:</b>
                                                            </p>
                                                        </div>
                                                        <div class="col-6 text-right">
                                                            <p>
                                                                <b class="totalText" id="ventasTotalNet"><?=$ingresosEgresos['total_ventas'];?></b>
                                                            </p>
                                                            <p>
                                                                <b class="totalText" id="gastosTotalNet"><?=$ingresosEgresos['total_gastos'];?></b>
                                                            </p>
                                                            <p style="border-top: 1px solid #15589b">
                                                                <b class="totalText" id="totalNet"><?=$ingresosEgresos['total_general'];?></b>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-4 textData">
                                                    <p><h2 class="textBlue">Capital de trabajo</h2></p>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <p class="textBlue">
                                                                <b>Valor de Inventario:</b>
                                                            </p>
                                                            <p class="textBlue">
                                                                <b>Cuentas por cobrar:</b>
                                                            </p>
                                                            <p class="textBlue">
                                                                <b>Saldo cuentas bancarias:</b>
                                                            </p>
                                                            <p class="textBlue">
                                                                <b>Cuentas por pagar:</b>
                                                            </p>
                                                            <p class="textBlue">
                                                                <b>Saldo cuenta crédito:</b>
                                                            </p>
                                                        
                                                            <p class="textBlue">
                                                                <b>Total:</b>
                                                            </p>
                                                        </div>
                                                        <div class="col-6 text-right">
                                                            <p>
                                                                <b class="totalText" id="valorInventarioTotalNet"><?=$capitalTrabajo['inventario']?></b>
                                                            </p>
                                                            <p>
                                                                <b class="totalText" id="cuentasCobrarTotalNet"><?=$capitalTrabajo['cuentas_cobrar']?></b>
                                                            </p>
                                                            <p>
                                                                <b class="totalText" id="cuentasBancariasTotalNet"><?=$capitalTrabajo['cuentasBancarias']?></b>
                                                            </p>
                                                            <p>
                                                                <b class="totalText" id="cuentasPagarTotalNet"><?=$capitalTrabajo['cuentas_pagar']?></b>
                                                            </p>
                                                            <p>
                                                                <b class="totalText" id="cuentasCreditoTotalNet"><?=$capitalTrabajo['cuentasCredito']?></b>
                                                            </p>
                                                            
                                                            <p style="border-top: 1px solid #15589b">
                                                                <b class="totalText" id="TotalCapitalTrabajoNet" ><?=$capitalTrabajo['total_general']?></b>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane active" id="graficaVentas" role="tabpanel" aria-labelledby="graficaVentasGeneral-tab">
                                    <div class="card-body">
                                        <div class="row form-group">
                                            <div class="col-3">
                                                <label for="txtInitialDate">Fecha inicial:</label>
                                                <input class="form-control" type="date" name="txtInitialDate" id="txtInitialDate">
                                            </div>
                                            <div class="col-3">
                                                <label for="txtFinalDate">Fecha final:</label>
                                                <input class="form-control" type="date" name="txtFinalDate" id="txtFinalDate">
                                            </div>
                                            <div class="col-3 d-flex align-items-end">
                                                <button class="btn-custom btn-custom--blue espAgregar" type="button" onclick="createChartFilter()">Filtrar</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="container-canvas"></div>
                                </div>
                                <div class="tab-pane" id="reporteGastos" role="tabpanel" aria-labelledby="reporteGastos-tab">
                                    <div class="card-body">
                                        <div class="row form-group">
                                            <div class="col-3">
                                                <label for="txtExpensiveReport_year">Año:</label>
                                                <input class="form-control" type="number" min="1900" max="2099" placeholder="Ingrese un año" name="txtExpensiveReport_year" id="txtExpensiveReport_year" onChange="validateYear(this)"/>
                                                <div class="invalid-feedback" id="invalid-txtYear"></div>
                                            </div>
                                            <div class="col-3">
                                                <label for="cmbMes">Mes:</label>
                                                <select name="cmbMes" id="cmbMes" class="form-select" multiple></select>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <div class="col-3">
                                                <label for="txtExpensiveReport_InitialDate">Fecha inicial:</label>
                                                <input class="form-control dateRange" type="date" name="txtExpensiveReport_InitialDate" id="txtExpensiveReport_InitialDate">
                                            </div>
                                            <div class="col-3">
                                                <label for="txtExpensiveReport_FinalDate">Fecha final:</label>
                                                <input class="form-control dateRange" type="date" name="txtExpensiveReport_FinalDate" id="txtExpensiveReport_FinalDate">
                                            </div>
                                            <div class="col-3 d-flex align-items-end">
                                                <button class="btn-custom btn-custom--blue espAgregar" type="button" onclick="generateExpensiveReport()">Descargar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="resumenUtilidades" role="tabpanel" aria-labelledby="resumenUtilidades-tab">
                                    <div class="card-body">
                                        <div class="row form-group">
                                            <div class="col-3">
                                            <label for="txtInitialDate">Fecha inicial:</label>
                                                <input class="form-control" type="date" name="txtUtilitiesInitialDate" id="txtUtilitiesInitialDate">
                                            </div>
                                            <div class="col-3">
                                                <label for="txtFinalDate">Fecha final:</label>
                                                <input class="form-control" type="date" name="txtUtilitiesFinalDate" id="txtUtilitiesFinalDate">
                                            </div>
                                            <div class="col-3 d-flex align-items-end">
                                                <button class="btn-custom btn-custom--blue espAgregar" type="button" onclick="downloadUtilities()">Descargar</button>
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
    <script src="../../js/scripts.js"></script>
    <script src="../../vendor/chart.js/Chart.min.js"></script>
    <script src="js/index.js"></script>
    <script>
        slimMes = new SlimSelect({
        select: "#cmbMes",
        deselectLabel: '<span class="">✖</span>',
        data: [
            { value: "0", text: "Todos"},
            { text: "Enero", value: "1" },
            { text: "Febrero", value: "2" },
            { text: "Marzo", value: "3" },
            { text: "Abril", value: "4" },
            { text: "Mayo", value: "5" },
            { text: "Junio", value: "6" },
            { text: "Julio", value: "7" },
            { text: "Agosto", value: "8" },
            { text: "Septiembre", value: "9" },
            { text: "Octubre", value: "10" },
            { text: "Noviembre", value: "11" },
            { text: "Diciembre", value: "12" },
            ],
        });
    </script>
    
</body>
</html>
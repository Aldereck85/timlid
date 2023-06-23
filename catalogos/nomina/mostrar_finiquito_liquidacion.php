<?php
session_start();

$jwt_ruta = "../../";
require_once '../jwt.php';

date_default_timezone_set('America/Mexico_City');

$token = $_SESSION['token_ld10d'];

if (isset($_SESSION["Usuario"])) {
    require_once '../../include/db-conn.php';

} else {
    header("location:../dashboard.php");
}

if(!isset($_POST['idFiniquito'])){
  header("location:finiquito_liquidacion.php");
}
$idFiniquito = $_POST['idFiniquito'];
$stmt = $conn->prepare("SELECT f.*, l.*,l.id as liquidacion_id, e.Nombres, e.PrimerApellido, e.SegundoApellido,dle.FechaIngreso, e.RFC, dme.NSS, p.puesto,t.Turno, dle.Sueldo,pp.DiasPago, ms.descripcion as desc_motivo_separacion, mp.descripcion as metodo_pago FROM finiquito as f INNER JOIN empleados as e ON e.PKEmpleado = f.empleado_id INNER JOIN datos_laborales_empleado as dle ON dle.FKEmpleado = e.PKEmpleado INNER JOIN periodo_pago as pp ON pp.PKPeriodo_pago = dle.FKPeriodo LEFT JOIN datos_medicos_empleado as dme ON dme.FKEmpleado = e.PKEmpleado LEFT JOIN puestos as p ON p.id = dle.FKPuesto LEFT JOIN turnos as t ON t.PKTurno = dle.FKTurno LEFT JOIN liquidacion as l ON l.finiquito_id = f.id LEFT JOIN motivo_separacion as ms ON ms.id = f.motivo_separacion_id LEFT JOIN formas_pago_sat as mp ON mp.id = f.metodo_pago_id WHERE f.id = :idFiniquito ");
$stmt->bindValue(':idFiniquito',$idFiniquito);
$stmt->execute();
$datosFiniquito = $stmt->fetch();
$nombreEmpleado = $datosFiniquito['Nombres'].' '.$datosFiniquito['PrimerApellido'].' '.$datosFiniquito['SegundoApellido'];
$idEmpleado = $datosFiniquito['empleado_id'];
//print_r($datosFiniquito);

$GLOBALS['rutaFuncion'] = "./";
require_once("../../functions/funcionNomina.php");
$datosSalario = getSalario_Dias($idEmpleado);
$sueldo = $datosSalario[0];
$sueldoDiario = $datosSalario[2];
$sueldoMensual = $datosSalario[5];
$datosSBC = getSBCNomina($idEmpleado, $datosFiniquito['fecha_salida']);
$SDI = $datosSBC[3];

if($datosFiniquito['finiquito_id'] == NULL){
  $tipoMovimiento = 1;
}
else{
  $tipoMovimiento = 2;
}

$aguinaldo = $datosFiniquito['aguinaldo'];
$aguinaldo_exento = $datosFiniquito['aguinaldo_exento'];
$aguinaldo_gravado = $datosFiniquito['aguinaldo_gravado'];

$vacaciones_gravadas = $datosFiniquito['vacaciones'];
$vacaciones_exentas = 0.00;
$vacaciones_gravadas = $datosFiniquito['vacaciones'];

$primaVacacional = $datosFiniquito['prima_vacacional'];
$primaVacacional_exento = $datosFiniquito['prima_vacacional_exenta'];
$primaVacacional_gravado = $datosFiniquito['prima_vacacional_gravada'];

$salariosDevengados = $datosFiniquito['salarios_devengados'];
$otros = $datosFiniquito['otros'];
$gratificacion = $datosFiniquito['gratificacion'];
$bonoAsistencia = $datosFiniquito['bonos_asistencia'];
$bonoPuntualidad = $datosFiniquito['bonos_puntualidad'];

$total = $datosFiniquito['aguinaldo'] + $datosFiniquito['vacaciones'] + $datosFiniquito['prima_vacacional'] + $datosFiniquito['salarios_devengados'] + $datosFiniquito['otros'] + $datosFiniquito['gratificacion'] + $datosFiniquito['bonos_asistencia'] + $datosFiniquito['bonos_puntualidad'];
$total_exento = $datosFiniquito['aguinaldo_exento'] + $datosFiniquito['prima_vacacional_exenta'];
$total_gravado = $datosFiniquito['aguinaldo_gravado'] + $datosFiniquito['vacaciones'] + $datosFiniquito['prima_vacacional_gravada'] + $datosFiniquito['salarios_devengados'] + $datosFiniquito['otros'] + $datosFiniquito['gratificacion'] + $datosFiniquito['bonos_asistencia'] + $datosFiniquito['bonos_puntualidad'];

if($datosFiniquito['isr_vacaciones_salarios'] > 0){
  $titulo1 = "ISR (Vacaciones,salarios, otros, gratificación)";
  $impuestoAplicableVS = $datosFiniquito['isr_vacaciones_salarios'];
}
else{
  $titulo1 = "SAE a pagar (Vacaciones,salarios, otros, gratificación)";
  $impuestoAplicableVS = $datosFiniquito['sae_vacaciones_salarios'];
}

if($datosFiniquito['isr_aguinaldo'] > 0){
  $titulo2 = "ISR Aguinaldo";
  $impuestoAguinaldo = $datosFiniquito['isr_aguinaldo'];
}
else{
  $titulo2 = "SAE Aguinaldo";
  $impuestoAguinaldo = $datosFiniquito['sae_aguinaldo'];
}

if($datosFiniquito['isr_aguinaldo'] > 0){
  $titulo3 = "ISR Prima Vacacional";
  $impuestoPrimaVacacional = $datosFiniquito['isr_prima_vacacional'];
}
else{
  $titulo3 = "SAE Prima Vacacional";
  $impuestoPrimaVacacional = $datosFiniquito['sae_prima_vacacional'];
}

if($tipoMovimiento == 2){
 $indemnizacion = $datosFiniquito['indemnizacion'];
 $indemnizacion_exento = $datosFiniquito['indemnizacion_exento'];
 $indemnizacion_gravado = $datosFiniquito['indemnizacion_gravado'];

 $anios_servicio = $datosFiniquito['anios_servicio'];
 $prima_antiguedad = $datosFiniquito['prima_antiguedad'];

 $totalLiquidacion = bcdiv($indemnizacion + $anios_servicio + $prima_antiguedad,1,2);
 $totalLiquidacionExento = bcdiv($indemnizacion_exento,1,2);
 $totalLiquidacionGravada = bcdiv($indemnizacion_gravado + $anios_servicio + $prima_antiguedad,1,2);

 if($datosFiniquito['isr_liquidacion'] > 0){
    $titulo_indemnizacion = "ISR Indemnización";
    $impuestoIndemnizacion = $datosFiniquito['isr_liquidacion'];
  }
  else{
    $titulo_indemnizacion = "SAE Indemnización";
    $impuestoIndemnizacion = $datosFiniquito['sae_liquidacion'];
  }
 $totalLiquidacionFInal = $datosFiniquito['total_liquidacion'];
}

$pensionAlimenticiaCheck = $datosFiniquito['tipo_pension_alimenticia'];
$pension_alimenticia = $datosFiniquito['pension_alimenticia_cantidad'];
$pension_alimenticia_porc = $datosFiniquito['pension_alimenticia'];
$infonavit = $datosFiniquito['infonavit'];
$fonacot = $datosFiniquito['fonacot'];
$imss_salarios = $datosFiniquito['imss_salarios'];

if($pensionAlimenticiaCheck == 2){
  $titulo_pension = "Pensión alimenticia (Salario)";
}

if($pensionAlimenticiaCheck == 3){
  $titulo_pension = "Pensión alimenticia (Total percepciones)";
}

if($pensionAlimenticiaCheck == 4){
  $titulo_pension = "Pensión alimenticia (Total percepciones menos deducciones)";
}

$suma_deducciones = $datosFiniquito['isr_vacaciones_salarios'] + $datosFiniquito['isr_aguinaldo'] + $datosFiniquito['isr_prima_vacacional'] - $datosFiniquito['sae_vacaciones_salarios'] - $datosFiniquito['sae_aguinaldo'] - $datosFiniquito['sae_prima_vacacional'] + $pension_alimenticia + $infonavit + $fonacot + $imss_salarios;

$total_pagar = $datosFiniquito['total_pagar'];
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

  <title>Timlid | Finiquito</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Page level plugins -->
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.bootstrap4.min.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>

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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <script src="../../js/slimselect.min.js"></script>
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <script src="../../js/lobibox.min.js"></script>
  <script src="../../js/sweet/sweetalert2.js"></script>
  <link rel="stylesheet" href="../../css/notificaciones.css">
  <script src="../../js/Cleave.js"></script>
  <script src="../../js/numeral.min.js"></script>
  <script src="../../js/jquery.redirect.min.js"></script>
  <style type="text/css">
    .dataTables_length{
      display: none;
    }
    .align-right{
      display: table-cell !important;
      text-align: right;
    }
    .align-center{
      display: table-cell !important;
      text-align: center;
    }
    .circuloImpuestos{
      background: none;
      color: inherit;
      border: 2px solid #15589b;
      padding: 0;
      font: inherit;
      cursor: pointer;
      outline: inherit;
      width: 27px;
      height: 27px;
      border-radius: 50%;
    }
  </style>
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
$icono = '../../img/icons/puestos.svg';
$titulo = "Finiquito";
$ruta = "../";
$ruteEdit = $ruta . "central_notificaciones/";
require_once '../menu3.php';
?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <input type="hidden" id="txtUsuario" value="<?=$_SESSION['PKUsuario'];?>">
      <input type="hidden" id="txtRuta" value="<?=$ruta;?>">
      <input type="hidden" id="txtEdit" value="<?=$ruteEdit;?>">
      <input type="hidden" id="txtPantalla" value="13">

      <!-- Main Content -->
      <div id="content">

        <?php
$rutatb = "../";
require_once "../topbar.php";
?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="card mb-4 data-table">
            <div class="card-header py-3">

                <div class="row">
                  <div class="col-12 col-lg-6">
                    <label>Empleado:</label>
                    <input type="text" name="nombreEmpleado" id="NombreEmpleado" value="<?=$nombreEmpleado?>" class="form-control" readonly>
                  </div>
                  <div class="row col-12 col-lg-6" id="timbradoNomina" style="display: block;">
                      <label>Tipo:</label>
                      <div class="row" style="position: relative; left: 5%;">
                        <div class="row col-12 col-lg-6" style="position: relative; top: 7px;">
                          <input type="radio" id="tipo" name="tipo" value="1" class="tipoFinal form-control" style="width:20px;height: 20px;" <?php if($tipoMovimiento == 1) echo "checked"; ?> disabled>
                          <label for="Finiquito" style="position:relative;left: 1%;">  Finiquito</label>
                        </div>
                        <div class="row col-12 col-lg-6" style="position: relative; top: 7px;">
                          <input type="radio" id="tipo" name="tipo" value="2" class="tipoFinal form-control" style="width:20px;height: 20px;" <?php if($tipoMovimiento == 2) echo "checked"; ?> disabled>
                          <label for="Liquidacion" style="position:relative;left: 1%;">  Liquidación</label>
                        </div>
                      </div>
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-12 col-lg-12">
                    <label>Causa de la baja:</label>
                    <input type="text" name="causaBaja" id="causaBaja" value="<?=$datosFiniquito['desc_motivo_separacion']?>" class="form-control" readonly>
                  </div>
                </div>
                <br>
            </div>
          </div>



          <div class="card shadow mb-4" id="divCalculo">
            <div class="card-header py-3">
              <div class="row">
                <div class="col-lg-10">
                  <b>Calculo de finiquito</b>
                </div>
                <div class="col-lg-2">

                </div>
              </div>

            </div>
            <div class="card-body">
                <form action="" method="post" id="frmNomina">
                <div class="row">
                  <div class="col-lg-4">
                    <label><b>Nombre:</b> <span id="NombreEmpleado"><?=$nombreEmpleado?></span></label><br>
                    <label><b>NSS:</b> <span id="NSS"><?=$datosFiniquito['NSS']?></span></label><br>
                    <label><b>RFC:</b> <span id="RFC"><?=$datosFiniquito['RFC']?></span></label><br>
                    <label><b>Salario diario:</b> <span id="SalarioDiarioS"><?=$sueldoDiario?></span></label><br>
                  </div>
                  <div class="col-lg-4">

                  </div>
                  <div class="col-lg-4">

                    <label><b>Turno:</b> <span id="Turno"><?=$datosFiniquito['Turno']?></span></label><br>
                    <label><b>Puesto:</b> <span id="Puesto"><?=$datosFiniquito['puesto']?></span></label><br>
                    <label><b>Salario: </b><span id="SalarioSpan"><?=$sueldo?></span></label><br>
                    <label><b>Salario diario integrado:</b> <span id="SalarioDiarioIntegradoS"><?=$SDI?></span></label><br>
                  </div>
                </div>
                <br>
                <hr>

                <br>
                <form id="formFiniquito" method="POST" action="#">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-12 col-lg-6">
                        <label for="txtFechaIngreso">Fecha de ingreso:</label>                
                        <input type="date" class="form-control" name="txtFechaIngreso" id="txtFechaIngreso" value="<?=$datosFiniquito['fecha_ingreso']?>" readonly>
                      </div>
                      <div class="col-12 col-lg-6">
                        <label for="txtFechaSalida">Fecha de salida:</label>                
                        <input type="date" class="form-control" name="txtFechaSalida" id="txtFechaSalida" value="<?=$datosFiniquito['fecha_salida']?>" readonly>
                      </div>
                    </div>
                  </div>
                  <br>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-12 col-lg-4">
                        <label for="txtDiasAguinaldo">Días de aguinaldo:</label>                
                        <input type="number" class="form-control" name="txtDiasAguinaldo" id="txtDiasAguinaldo" value="<?=$datosFiniquito['dias_aguinaldo']?>" readonly>
                      </div>
                      <div class="col-12 col-lg-4">
                        <label for="txtProporcionalesAguinaldo">Proporcionales:</label>                
                        <input type="text" class="form-control" name="txtProporcionalesAguinaldo" id="txtProporcionalesAguinaldo" value="<?=$datosFiniquito['dias_aguinaldo_proporcionales']?>" readonly>
                      </div>
                      <div class="col-12 col-lg-4">
                        <label for="txtAntiguedad">Antigüedad:</label>                
                        <input type="number" class="form-control" name="txtAntiguedad" id="txtAntiguedad" value="<?=$datosFiniquito['antiguedad']?>" readonly>
                      </div>
                    </div>
                  </div>
                  <br>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-12 col-lg-3">
                        <label for="txtDiasVacaciones">Días de vacaciones:</label>                
                        <input type="number" class="form-control" name="txtDiasVacaciones" id="txtDiasVacaciones" value="<?=$datosFiniquito['dias_vacaciones']?>" readonly>
                      </div>
                      <div class="col-12 col-lg-3">
                        <label for="txtProporcionalesVacaciones">Proporcionales:</label>                
                        <input type="text" class="form-control" name="txtProporcionalesVacaciones" id="txtProporcionalesVacaciones" value="<?=$datosFiniquito['dias_vacaciones_proporcionales']?>" readonly>
                      </div>
                      <div class="col-12 col-lg-3">
                        <label for="txtDiasRestantes">Restantes:</label>                
                        <input type="number" class="form-control" name="txtDiasRestantes" id="txtDiasRestantes" value="<?=$datosFiniquito['dias_restantes']?>" readonly>
                      </div>
                      <div class="col-12 col-lg-3">
                        <label for="txtDiasPagar">A pagar:</label>                
                        <input type="number" class="form-control" name="txtDiasPagar" id="txtDiasPagar" value="<?=$datosFiniquito['dias_pagar']?>" readonly>
                      </div>
                    </div>
                  </div>
                  <br>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-12 col-lg-4">
                        <label for="txtSalariosDevengados">Salarios devengados:</label>                
                        <input type="text" class="form-control" name="txtSalariosDevengados" id="txtSalariosDevengados" value="<?=$datosFiniquito['salarios_devengados']?>" readonly>
                      </div>
                      <div class="col-12 col-lg-4">
                        <label for="txtFechaIniP">Periodo - Fecha inicial:</label>                
                        <input type="date" class="form-control" name="txtFechaIniP" id="txtFechaIniP" value="<?=$datosFiniquito['fecha_inicial_salarios_devengados']?>" readonly>
                      </div>
                      <div class="col-12 col-lg-4">
                        <label for="txtFechaFinP">Periodo - Fecha final:</label>                
                        <input type="date" class="form-control" name="txtFechaFinP" id="txtFechaFinP" value="<?=$datosFiniquito['fecha_final_salarios_devengados']?>" readonly>
                      </div>
                    </div>
                  </div>
                  <br>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-12 col-lg-6">
                        <label for="txtGratificacion">Gratificación:</label>                
                        <input type="text" class="form-control" name="txtGratificacion" id="txtGratificacion" value="<?=$datosFiniquito['gratificacion']?>" readonly>
                      </div>
                      <div class="col-12 col-lg-6">
                        <label for="txtOtros">Otros:</label>                
                        <input type="text" class="form-control" name="txtOtros" id="txtOtros" value="<?=$datosFiniquito['otros']?>" readonly>
                      </div>
                    </div>
                  </div>
                  <br>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-12 col-lg-6">
                        <label for="txtBonoAsistencia">Bonos de asistencia:</label>                
                        <input type="text" class="form-control" name="txtBonoAsistencia" id="txtBonoAsistencia" value="<?=$bonoAsistencia?>" readonly>
                      </div>
                      <div class="col-12 col-lg-6">
                        <label for="txtBonoPuntualidad">Bonos de puntualidad:</label>                
                        <input type="text" class="form-control" name="txtBonoPuntualidad" id="txtBonoPuntualidad" value="<?=$bonoPuntualidad?>"readonly>
                      </div>
                  </div>
                  <br>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-12 col-lg-6">
                        <label for="txtInfonavit">Infonavit:</label>                
                        <input type="text" class="form-control" name="txtInfonavit" id="txtInfonavit" value="<?=$infonavit?>" readonly>
                      </div>
                      <div class="col-12 col-lg-6">
                        <label for="txtFonacot">FONACOT:</label>                
                        <input type="text" class="form-control" name="txtFonacot" id="txtFonacot" value="<?=$fonacot?>" readonly>
                      </div>
                  </div>
                  <br>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-12 col-lg-6">
                        <label for="checkTipoPensionAlimenticia">Pensión alimenticia:</label>                
                        <div class="row" style="position: relative; left: 5%;">
                        <div class="row col-12 col-lg-6" style="position: relative; top: 7px;">
                          <input type="radio" id="pensionAlimenticiaOpc" name="pensionAlimenticiaOpc" value="1" class="pensionAlimenticiaClass form-control" style="width:20px;height: 20px;" <?php if($pensionAlimenticiaCheck == 1) echo 'checked'; ?> disabled>
                          <label for="checkTipoPensionAlimenticia" style="position:relative;left: 1%;">  No</label>
                        </div>
                        <div class="row col-12 col-lg-6" style="position: relative; top: 7px;">
                          <input type="radio" id="tippensionAlimenticiaOpco" name="pensionAlimenticiaOpc" value="2" class="pensionAlimenticiaClass form-control" style="width:20px;height: 20px;" <?php if($pensionAlimenticiaCheck == 2) echo 'checked'; ?> disabled>
                          <label for="checkTipoPensionAlimenticia" style="position:relative;left: 1%;">  Por salario</label>
                        </div>
                        <div class="row col-12 col-lg-6" style="position: relative; top: 7px;">
                          <input type="radio" id="pensionAlimenticiaOpc" name="pensionAlimenticiaOpc" value="3" class="pensionAlimenticiaClass form-control" style="width:20px;height: 20px;" <?php if($pensionAlimenticiaCheck == 3) echo 'checked'; ?> disabled>
                          <label for="checkTipoPensionAlimenticia" style="position:relative;left: 1%;">  Por total de percepciones</label>
                        </div>
                        <div class="row col-12 col-lg-6" style="position: relative; top: 7px;">
                          <input type="radio" id="pensionAlimenticiaOpc" name="pensionAlimenticiaOpc" value="4" class="pensionAlimenticiaClass form-control" style="width:20px;height: 20px;" <?php if($pensionAlimenticiaCheck == 4) echo 'checked'; ?> disabled>
                          <label for="checkTipoPensionAlimenticia" style="position:relative;left: 1%;">  Por total de percepciones menos deducciones</label>
                        </div>
                      </div>
                      </div>
                      <div class="col-12 col-lg-6">
                        <label for="txtPorcPensioAlimenticia"> Pensión alimenticia (%):</label>                
                        <input type="number" class="form-control" name="txtPorcPensioAlimenticia" id="txtPorcPensioAlimenticia" value="<?=$pension_alimenticia_porc?>" readonly>
                      </div>
                  </div>
                  <br>
                </form>

                <br>
                <div class="form-group">
                    <div class="row">
                      <div id="cargarFiniquito" class="col-lg-12">

<?php
                        if($tipoMovimiento == 1){

                            $tituloMovimiento = "finiquito";
                            $leyenda = "Finiquito cancelado";
                            echo
                            '<table class="table">
                              <thead>
                                <th>Concepto</th>
                                <th>Importe</th>
                                <th>Exentos</th>
                                <th>Gravado</th>
                              </thead>
                              <tbody>
                                <tr>
                                  <td colspan="4"><b>PERCEPCIONES</b></td>
                                </tr>
                                <tr>
                                  <td>Aguinaldo</td>
                                  <td id="lblAguinaldoPercepcion">'.number_format($aguinaldo,2,'.',',').'</td>
                                  <td id="lblAguinaldoExento">'.number_format($aguinaldo_exento,2,'.',',').'</td>
                                  <td id="lblAguinaldoGravado">'.number_format($aguinaldo_gravado,2,'.',',').'</td>
                                </tr>
                                <tr>
                                  <td>Vacaciones</td>
                                  <td id="lblVacacionesPercepcion">'.number_format($vacaciones_gravadas,2,'.',',').'</td>
                                  <td id="lblVacacionesExento">'.number_format($vacaciones_exentas,2,'.',',').'</td>
                                  <td id="lblVacacionesGravado">'.number_format($vacaciones_gravadas,2,'.',',').'</td>
                                </tr>
                                <tr>
                                  <td>Prima vacacional</td>
                                  <td id="lblPrimaVacacionalPercepcion">'.number_format($primaVacacional,2,'.',',').'</td>
                                  <td id="lblPrimaVacacionalExento">'.number_format($primaVacacional_exento,2,'.',',').'</td>
                                  <td id="lblPrimaVacacionalGravado">'.number_format($primaVacacional_gravado,2,'.',',').'</td>
                                </tr>
                                <tr>
                                  <td>Salario devengado</td>
                                  <td id="lblSalarioDevengadoPercepcion">'.number_format($salariosDevengados,2,'.',',').'</td>
                                  <td id="lblSalarioDevengadoExento">0.00</td>
                                  <td id="lblSalarioDevengadoGravado">'.number_format($salariosDevengados,2,'.',',').'</td>
                                </tr>
                                <tr>
                                  <td>Otros</td>
                                  <td id="lblOtrosPercepcion">'.number_format($otros,2,'.',',').'</td>
                                  <td id="lblOtrosExento">0.00</td>
                                  <td id="lblOtrosGravado">'.number_format($otros,2,'.',',').'</td>
                                </tr>
                                <tr>
                                  <td>Gratificación</td>
                                  <td id="lblGratificacionPercepcion">'.number_format($gratificacion,2,'.',',').'</td>
                                  <td id="lblGratificacionExento">0.00</td>
                                  <td id="lblGratificacionGravado">'.number_format($gratificacion,2,'.',',').'</td>
                                </tr>
                                <tr>
                                  <td>Bono por asistencia</td>
                                  <td id="lblBonoAsistenciaPercepcion">'.number_format($bonoAsistencia,2,'.',',').'</td>
                                  <td id="lblBonoAsistenciaExento">0.00</td>
                                  <td id="lblBonoAsistenciaGravado">'.number_format($bonoAsistencia,2,'.',',').'</td>
                                </tr>
                                <tr>
                                  <td>Bono por puntualidad</td>
                                  <td id="lblBonoPuntualidadPercepcion">'.number_format($bonoPuntualidad,2,'.',',').'</td>
                                  <td id="lblBonoPuntualidadExento">0.00</td>
                                  <td id="lblBonoPuntualidadGravado">'.number_format($bonoPuntualidad,2,'.',',').'</td>
                                </tr>
                                 <tr>
                                  <td><b>SUMA PERCEPCIONES</b></td>
                                  <td id="lblSubtotalFiniquitoPercepcion">'.number_format($total,2,'.',',').'</td>
                                  <td id="lblSubtotalFiniquitoExento">'.number_format($total_exento,2,'.',',').'</td>
                                  <td id="lblSubtotalFiniquitoGravado">'.number_format($total_gravado,2,'.',',').'</td>
                                </tr>
                                <tr>
                                  <td colspan="4"><b>DEDUCCIONES</b></td>
                                </tr>
                                <td>'.$titulo1.' <b><button type="button" id="mostrarCalculoImpuestos" class="circuloImpuestos" title="Mostrar el calculo del ISR/SAE de Salarios/Vacaciones" value="1">?</button></b></td>
                                  <td>&nbsp</td>
                                  <td>&nbsp</td>
                                  <td id="lblISRVacacacionesSalarios">'.number_format($impuestoAplicableVS,2,'.',',').'</td>
                                </tr>
                                <td>'.$titulo2.' <b><button type="button" id="mostrarCalculoImpuestos" class="circuloImpuestos" title="Mostrar el calculo del ISR/SAE de Aguinaldo" value="2">?</button></b></td>
                                  <td>&nbsp</td>
                                  <td>&nbsp</td>
                                  <td id="lblISRAguinaldo">'.number_format($impuestoAguinaldo,2,'.',',').'</td>
                                </tr>
                                <td>'.$titulo3.' <b><button type="button" id="mostrarCalculoImpuestos" class="circuloImpuestos" title="Mostrar el calculo del ISR/SAE de Prima Vacacional" value="3">?</button></b></td>
                                  <td>&nbsp</td>
                                  <td>&nbsp</td>
                                  <td id="lblISRPrimaVacacional">'.number_format($impuestoPrimaVacacional,2,'.',',').'</td>
                                </tr>';

                                if($infonavit > 0){

                                    echo '
                                          <tr>
                                            <td>INFONAVIT</td>
                                            <td>&nbsp</td>
                                            <td>&nbsp</td>
                                            <td id="lblInfonavit">'.number_format($infonavit,2,'.',',').'</td>
                                          </tr>';
                                  }

                                  if($fonacot > 0){

                                    echo '
                                          <tr>
                                            <td>FONACOT</td>
                                            <td>&nbsp</td>
                                            <td>&nbsp</td>
                                            <td id="lblFonacot">'.number_format($fonacot,2,'.',',').'</td>
                                          </tr>';
                                  }
                                  //tipo de pension alimenticia, aparecera en caso de que exista
                                  if($pensionAlimenticiaCheck != 1){

                                    echo '
                                      <tr>
                                        <td>'.$titulo_pension.'</td>
                                        <td>&nbsp</td>
                                        <td>&nbsp</td>
                                        <td id="lblPension">'.number_format($pension_alimenticia,2,'.',',').'</td>
                                      </tr>
                                    ';

                                  }

                                  if($imss_salarios > 0){

                                    echo '
                                          <tr>
                                            <td>IMSS (Salarios devengados) <b><button type="button" id="mostrarCalculoImpuestos" class="circuloImpuestos" title="Mostrar el calculo del IMSS de salarios devengados" value="4">?</button></b></td>
                                            <td>&nbsp</td>
                                            <td>&nbsp</td>
                                            <td id="lblFonacot">'.number_format($imss_salarios,2,'.',',').'</td>
                                          </tr>';
                                  }
        
                                  echo '
                                  <tr>
                                    <td><b>SUMA DEDUCCIONES</b></td>
                                    <td>&nbsp</td>
                                    <td>&nbsp</td>
                                    <td id="lblSumaDeducciones" style="font-weight:800">'.number_format($suma_deducciones,2,'.',',').'</td>
                                  </tr>
                                ';

                                echo '
                                <tr>
                                  <td><b>TOTAL A PAGAR</b></td>
                                  <td>&nbsp</td>
                                  <td>&nbsp</td>
                                  <td id="lblTotalPagar" style="font-weight:800">'.number_format($total_pagar,2,'.',',').'</td>
                                </tr>
                              </tbody>
                            </table>';

                            if($datosFiniquito['estadoTimbrado'] == 1 || $datosFiniquito['estadoTimbrado'] == 2){

                              echo '
                                <br>
                                <div class="col-12 text-center">
                                    <div class="row" id="actualizarMetodoPago">
                                        <div class="col-lg-4">
                                          <button type="button" class="btn btn-custom btn-custom--border-blue" id="exportarExcel">Exportar Excel</button>
                                        </div>
                                        <div class="col-lg-4">
                                          <label for="">Método de pago:</label><br>
                                          <span class="btn btn-custom btn-custom--blue">'.$datosFiniquito['metodo_pago'].'</span>
                                        </div>
                                        <div class="col-lg-4">
                                          <button type="button" class="btn btn-custom btn-custom--border-blue" id="exportarPDF">Exportar PDF</button>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row" id="mostrarTimbrado">
                                          <div class="col-lg-12">
                                            <label for="timbrarLiquidacionL"><b>Opciones de timbrado</b></label>
                                          </div>
                                          <br><br>
                                          <div class="col-lg-3">
                                            <a href="functions/download_pdf_finiquito.php?value='.$datosFiniquito['idFactura'].'" class="btn btn-custom btn-custom--blue" target="_blank">Descargar pdf</a>
                                          </div>
                                          <div class="col-lg-3">
                                            <a href="functions/download_xml_finiquito.php?value='.$datosFiniquito['idFactura'].'" class="btn btn-custom btn-custom--blue" target="_blank">Descargar xml</a>
                                          </div>
                                          <div class="col-lg-3">
                                            <a href="functions/download_zip_finiquito.php?value='.$datosFiniquito['idFactura'].'" class="btn btn-custom btn-custom--blue" target="_blank">Descargar zip</a>
                                           </div>
                                           <div class="col-lg-3">
                                            <a href="#" class="btn btn-custom btn-custom--blue" id="send_email_finiquito" data-toggle="modal" data-target="#enviarFactura">Enviar por email</a>
                                          </div>
                                    </div>
                                </div>';

                                if($datosFiniquito['estadoTimbrado'] == 1){

                                  echo '
                                    <br><br>
                                    <div class="col-12 text-center">
                                        <div class="row" id="cancelarTimbradoMostrar">
                                          <div class="col-lg-6">
                                            <button type="button" class="btn btn-custom btn-custom--border-blue" id="regresarFiniquito">Regresar</button>
                                          </div>
                                          <div class="col-12 col-lg-6">
                                               <button type="button" class="btn btn-custom btn-custom--red" id="eliminarFiniquito2">Eliminar finiquito</button>
                                          </div>
                                        </div>
                                    </div>
                                    ';
                                }
                                if($datosFiniquito['estadoTimbrado'] == 2){
                                  echo '
                                    <br>
                                    <div class="col-12 text-center">
                                        <div class="row" id="cancelarTimbradoMostrar">
                                          <div class="col-lg-6">
                                            <button type="button" class="btn btn-custom btn-custom--border-blue" id="regresarFiniquito">Regresar</button>
                                          </div>
                                          <div class="col-12 col-lg-6">
                                               <button type="button" class="btn btn-custom btn-custom--red">'.$leyenda.'</button>
                                          </div>
                                        </div>
                                    </div>
                                    ';
                                }
                            }
                            if($datosFiniquito['estadoTimbrado'] == 0){

                                echo '<br>
                                  <div class="col-12 text-center">
                                      <div class="row" id="actualizarMetodoPago">
                                        <div class="col-lg-6">
                                          <button type="button" class="btn btn-custom btn-custom--border-blue" id="exportarExcel">Exportar Excel</button>
                                        </div>
                                        <div class="col-lg-6">
                                          <button type="button" class="btn btn-custom btn-custom--border-blue" id="exportarPDF">Exportar PDF</button>
                                        </div>
                                      </div>
                                      <br>
                                      <div class="row" id="mostrarTimbrado">
                                        <br><br>
                                        <div class="col-lg-12">
                                          <label for="timbrarLiquidacionL"><b>Opciones de timbrado</b></label>
                                        </div>
                                        <br><br>
                                        <div class="col-lg-6" style="position:relative; bottom: 20px;">
                                            <label>Método de pago:</label>
                                              <select class="form-control" id="metodo_pago_id">
                                                <option disabled selected>Selecciona un método de pago</option>';

                                                  $stmt = $conn->prepare('SELECT id, clave as codigo, descripcion FROM formas_pago_sat');
                                                  $stmt->execute();
                                                  $metodos_de_pago = $stmt->fetchAll();
                                                  foreach ($metodos_de_pago as $mp) {
                                                
                                                   echo '<option value="'.$mp['id'].'">'.$mp["codigo"]." - ".$mp["descripcion"].'</option>';

                                                  }
                                                
                                            echo '
                                              </select>
                                        </div>
                                        <div class="col-lg-6" style="position:relative; bottom: 20px;">
                                          <div class="row">
                                              <div class="col-lg-8">
                                                <label>Cfdi relacionados:</label>
                                                  <select class="form-control" id="facturas_relacionadas_id">
                                                    <option value="0" disabled selected>Selecciona un cfdi</option>';

                                                      $stmt = $conn->prepare('SELECT bcef.id, bcef.idFactura, bcef.uuid, IF(bcef.tipo = 1,"Finiquito","Liquidación") as tipo_factura, bcef.tipo FROM bitacora_cfdi_eliminado_finiquito_liquidacion as bcef INNER JOIN finiquito as f ON f.id = bcef.finiquito_id AND f.empleado_id = :empleado_id WHERE bcef.tipo = 1 ORDER BY tipo_factura');
                                                      $stmt->bindValue(":empleado_id",$idEmpleado);
                                                      $stmt->execute();
                                                      $bitacora = $stmt->fetchAll();
                                                      foreach ($bitacora as $b) {
                                                    
                                                       echo '<option value="'.$b['uuid'].'&'.$b["tipo"].'&'.$b["id"].'&'.$b["idFactura"].'">'.$b["uuid"]." - ".$b["tipo_factura"].'</option>';

                                                      }
                                                    
                                                echo '
                                                  </select>
                                              </div>
                                              <div class="col-lg-4">
                                                <br>
                                                <button type="button" class="btn btn-custom btn-custom--border-blue" id="agregarRelacion">+</button>
                                                <a href="#" class="btn btn-custom btn-custom--border-blue" id="descargarPDF" target="_self" > <i class="fas fa-file-invoice"></i> PDF </a>
                                                <a href="#" class="btn btn-custom btn-custom--border-blue" id="descargarXML" target="_self"> <i class="far fa-file-alt"></i> XML </a>
                                              </div>
                                          </div>
                                        </div>
                                        <div class="col-lg-12">
                                          <label><b>CFDI agregados para relacionar:</b></label><br>
                                          <span id="cfdiRelacionado">No hay ningún CFDI relacionado.</span>
                                        </div>   
                                        <br><br><br>
                                        <div class="col-lg-12">
                                          <button type="button" class="btn btn-custom btn-custom--border-blue" id="timbrarFiniquito">Timbrar finiquito</button>
                                        </div>                                        
                                      </div>
                                      <br>
                                      <div class="row" id="cancelarTimbradoMostrar">
                                        <div class="col-lg-6">
                                          <button type="button" class="btn btn-custom btn-custom--border-blue" id="regresarFiniquito">Regresar</button>
                                        </div>
                                        <div class="col-lg-6">
                                          <button type="button" class="btn btn-custom btn-custom--red" id="eliminarFiniquito">Eliminar finiquito</button>
                                        </div>
                                      </div>
                                  </div>';

                            }

                        }

                       if($tipoMovimiento == 2){
                        $tituloMovimiento = "liquidación";
                        $leyenda = "Liquidación cancelada";

                            echo
                            '<center><h3><b>FINIQUITO</b></h3></center><br>
                            <table class="table">
                              <thead>
                                <th>Concepto</th>
                                <th>Importe</th>
                                <th>Exentos</th>
                                <th>Gravado</th>
                              </thead>
                              <tbody>
                                <tr>
                                  <td colspan="4"><b>PERCEPCIONES</b></td>
                                </tr>
                                <tr>
                                  <td>Aguinaldo</td>
                                  <td id="lblAguinaldoPercepcion">'.number_format($aguinaldo,2,'.',',').'</td>
                                  <td id="lblAguinaldoExento">'.number_format($aguinaldo_exento,2,'.',',').'</td>
                                  <td id="lblAguinaldoGravado">'.number_format($aguinaldo_gravado,2,'.',',').'</td>
                                </tr>
                                <tr>
                                  <td>Vacaciones</td>
                                  <td id="lblVacacionesPercepcion">'.number_format($vacaciones_gravadas,2,'.',',').'</td>
                                  <td id="lblVacacionesExento">'.number_format($vacaciones_exentas,2,'.',',').'</td>
                                  <td id="lblVacacionesGravado">'.number_format($vacaciones_gravadas,2,'.',',').'</td>
                                </tr>
                                <tr>
                                  <td>Prima vacacional</td>
                                  <td id="lblPrimaVacacionalPercepcion">'.number_format($primaVacacional,2,'.',',').'</td>
                                  <td id="lblPrimaVacacionalExento">'.number_format($primaVacacional_exento,2,'.',',').'</td>
                                  <td id="lblPrimaVacacionalGravado">'.number_format($primaVacacional_gravado,2,'.',',').'</td>
                                </tr>
                                <tr>
                                  <td>Salario devengado</td>
                                  <td id="lblSalarioDevengadoPercepcion">'.number_format($salariosDevengados,2,'.',',').'</td>
                                  <td id="lblSalarioDevengadoExento">0.00</td>
                                  <td id="lblSalarioDevengadoGravado">'.number_format($salariosDevengados,2,'.',',').'</td>
                                </tr>
                                <tr>
                                  <td>Otros</td>
                                  <td id="lblOtrosPercepcion">'.number_format($otros,2,'.',',').'</td>
                                  <td id="lblOtrosExento">0.00</td>
                                  <td id="lblOtrosGravado">'.number_format($otros,2,'.',',').'</td>
                                </tr>
                                <tr>
                                  <td>Gratificación</td>
                                  <td id="lblGratificacionPercepcion">'.number_format($gratificacion,2,'.',',').'</td>
                                  <td id="lblGratificacionExento">0.00</td>
                                  <td id="lblGratificacionGravado">'.number_format($gratificacion,2,'.',',').'</td>
                                </tr>
                                <tr>
                                  <td>Bono por asistencia</td>
                                  <td id="lblBonoAsistenciaPercepcion">'.number_format($bonoAsistencia,2,'.',',').'</td>
                                  <td id="lblBonoAsistenciaExento">0.00</td>
                                  <td id="lblBonoAsistenciaGravado">'.number_format($bonoAsistencia,2,'.',',').'</td>
                                </tr>
                                <tr>
                                  <td>Bono por puntualidad</td>
                                  <td id="lblBonoPuntualidadPercepcion">'.number_format($bonoPuntualidad,2,'.',',').'</td>
                                  <td id="lblBonoPuntualidadExento">0.00</td>
                                  <td id="lblBonoPuntualidadGravado">'.number_format($bonoPuntualidad,2,'.',',').'</td>
                                </tr>
                                 <tr>
                                  <td><b>SUMA PERCEPCIONES</b></td>
                                  <td id="lblSubtotalFiniquitoPercepcion">'.number_format($total,2,'.',',').'</td>
                                  <td id="lblSubtotalFiniquitoExento">'.number_format($total_exento,2,'.',',').'</td>
                                  <td id="lblSubtotalFiniquitoGravado">'.number_format($total_gravado,2,'.',',').'</td>
                                </tr>
                                <tr>
                                  <td colspan="4"><b>DEDUCCIONES</b></td>
                                </tr>
                                <td>'.$titulo1.' <b><button type="button" id="mostrarCalculoImpuestos" class="circuloImpuestos" title="Mostrar el calculo del ISR/SAE de Salarios/Vacaciones" value="1">?</button></b></td>
                                  <td>&nbsp</td>
                                  <td>&nbsp</td>
                                  <td id="lblISRVacacacionesSalarios">'.number_format($impuestoAplicableVS,2,'.',',').'</td>
                                </tr>
                                <td>'.$titulo2.' <b><button type="button" id="mostrarCalculoImpuestos" class="circuloImpuestos" title="Mostrar el calculo del ISR/SAE de Aguinaldo" value="2">?</button></b></td>
                                  <td>&nbsp</td>
                                  <td>&nbsp</td>
                                  <td id="lblISRAguinaldo">'.number_format($impuestoAguinaldo,2,'.',',').'</td>
                                </tr>
                                <td>'.$titulo3.' <b><button type="button" id="mostrarCalculoImpuestos" class="circuloImpuestos" title="Mostrar el calculo del ISR/SAE de Prima Vacacional" value="3">?</button></b></td>
                                  <td>&nbsp</td>
                                  <td>&nbsp</td>
                                  <td id="lblISRPrimaVacacional">'.number_format($impuestoPrimaVacacional,2,'.',',').'</td>
                                </tr>';

                                if($infonavit > 0){

                                    echo '
                                          <tr>
                                            <td>INFONAVIT</td>
                                            <td>&nbsp</td>
                                            <td>&nbsp</td>
                                            <td id="lblInfonavit">'.number_format($infonavit,2,'.',',').'</td>
                                          </tr>';
                                  }

                                  if($fonacot > 0){

                                    echo '
                                          <tr>
                                            <td>FONACOT</td>
                                            <td>&nbsp</td>
                                            <td>&nbsp</td>
                                            <td id="lblFonacot">'.number_format($fonacot,2,'.',',').'</td>
                                          </tr>';
                                  }
                                  //tipo de pension alimenticia, aparecera en caso de que exista
                                  if($pensionAlimenticiaCheck != 1){

                                    echo '
                                      <tr>
                                        <td>'.$titulo_pension.'</td>
                                        <td>&nbsp</td>
                                        <td>&nbsp</td>
                                        <td id="lblPension">'.number_format($pension_alimenticia,2,'.',',').'</td>
                                      </tr>
                                    ';

                                  }

                                  if($imss_salarios > 0){

                                    echo '
                                          <tr>
                                            <td>IMSS (Salarios devengados) <b><button type="button" id="mostrarCalculoImpuestos" class="circuloImpuestos" title="Mostrar el calculo del IMSS de salarios devengados" value="4">?</button></b></td>
                                            <td>&nbsp</td>
                                            <td>&nbsp</td>
                                            <td id="lblFonacot">'.number_format($imss_salarios,2,'.',',').'</td>
                                          </tr>';
                                  }
        
                                  echo '
                                  <tr>
                                    <td><b>SUMA DEDUCCIONES</b></td>
                                    <td>&nbsp</td>
                                    <td>&nbsp</td>
                                    <td id="lblSumaDeducciones" style="font-weight:800">'.number_format($suma_deducciones,2,'.',',').'</td>
                                  </tr>
                                ';

                                echo '
                                <tr>
                                  <td><b>TOTAL FINIQUITO</b></td>
                                  <td>&nbsp</td>
                                  <td>&nbsp</td>
                                  <td id="lblTotalPagar" style="font-weight:800">'.number_format($total_pagar,2,'.',',').'</td>
                                </tr>
                              </tbody>
                            </table>';

                            $total_final = $totalLiquidacionFInal + $total_pagar;
                            echo
                            '<center><h3><b>LIQUIDACION</b></h3></center><br>
                             <table class="table">
                              <thead>
                                <th>Concepto</th>
                                <th>Importe</th>
                                <th>Exentos</th>
                                <th>Gravado</th>
                              </thead>
                              <tbody>
                                <tr>
                                  <td>Indemnización (90 días)</td>
                                  <td id="lblIndeminizacionPercepcion">'.number_format($indemnizacion,2,'.',',').'</td>
                                  <td id="lblIndeminizacionExento">'.number_format($indemnizacion_exento,2,'.',',').'</td>
                                  <td id="lblIndeminizacionGravado">'.number_format($indemnizacion_gravado,2,'.',',').'</td>
                                </tr>
                                <tr>
                                  <td>20 días por año de servicio</td>
                                  <td id="lblSalarioAnioPercepcion">'.number_format($anios_servicio,2,'.',',').'</td>
                                  <td id="lblSalarioAnioExento">0.00</td>
                                  <td id="lblSalarioAnioGravado">'.number_format($anios_servicio,2,'.',',').'</td>
                                </tr>
                                <tr>
                                  <td>Prima antiguedad</td>
                                  <td id="lblPrimaAntiguedadPercepcion">'.number_format($prima_antiguedad,2,'.',',').'</td>
                                  <td id="lblPrimaAntiguedadExento">0.00</td>
                                  <td id="lblPrimaAntiguedadGravado">'.number_format($prima_antiguedad,2,'.',',').'</td>
                                </tr>
                                <tr>
                                  <td><b>SUMA LIQUIDACION</b></td>
                                  <td id="lblSubtotalLiquidacionPercepcion">'.number_format($totalLiquidacion,2,'.',',').'</td>
                                  <td id="lblSubtotalLiquidacionExento">'.number_format($totalLiquidacionExento,2,'.',',').'</td>
                                  <td id="lblSubtotalLiquidacionGravado">'.number_format($totalLiquidacionGravada,2,'.',',').'</td>
                                </tr>
                                <tr>
                                <td>'.$titulo_indemnizacion.' <b><button type="button" id="mostrarCalculoImpuestos" class="circuloImpuestos" title="Mostrar el calculo del ISR/SAE de Indemnización" value="5">?</button></b></td>
                                  <td>&nbsp</td>
                                  <td>&nbsp</td>
                                  <td id="lblISRIndemnizacion">'.number_format($impuestoIndemnizacion,2,'.',',').'</td>
                                </tr>
                                <tr>
                                  <td><b>SUMA DEDUCCIONES</b></td>
                                  <td>&nbsp</td>
                                  <td>&nbsp</td>
                                  <td id="lblSumaDeducciones" style="font-weight:800">'.number_format($impuestoIndemnizacion,2,'.',',').'</td>
                                </tr>
                                <tr>
                                  <td><b>TOTAL LIQUIDACION</b></td>
                                  <td>&nbsp</td>
                                  <td>&nbsp</td>
                                  <td id="lblTotalPagar" style="font-weight:800">'.number_format($totalLiquidacionFInal,2,'.',',').'</td>
                                </tr>
                              </tbody>
                            </table>';

                            echo '<br><h2><span style="float: right;position:relative; right: 5%;">TOTAL A PAGAR: '.number_format($total_final,2,'.',',').'</span></h2><br>';

                            if(trim($datosFiniquito['metodo_pago']) == ""){
                              $tamanio = '6';
                              $display = 'none';
                            }
                            else{
                              $tamanio = '4';
                              $display = 'block';
                            }
                            echo '<br><div class="col-12 text-center">
                                      <div class="row" id="actualizarMetodoPago">
                                        <div class="col-lg-'.$tamanio.'">
                                          <button type="button" class="btn btn-custom btn-custom--border-blue" id="exportarExcel">Exportar Excel</button>
                                        </div>
                                        <div class="col-lg-'.$tamanio.'" style="display:'.$display.';">
                                          <label for="">Método de pago:</label><br>
                                          <span class="btn btn-custom btn-custom--blue">'.$datosFiniquito['metodo_pago'].'</span>
                                        </div>
                                        <div class="col-lg-'.$tamanio.'">
                                          <button type="button" class="btn btn-custom btn-custom--border-blue" id="exportarPDF">Exportar PDF</button>
                                        </div>
                                      </div>
                                  </div>';


                            if($datosFiniquito['estadoTimbrado'] == 0 && $tipoMovimiento == 1){

                                echo '<br>
                                  <div class="col-12 text-center">
                                      <div class="row" id="mostrarTimbrado">
                                        <br><br><br>
                                        <div class="col-lg-12">
                                          <label for="timbrarLiquidacionL">Opciones de timbrado</label>
                                        </div>
                                        <br>
                                            <div class="col-lg-6">
                                              <label>Método de pago:</label>
                                              <select class="form-control" id="metodo_pago_id">
                                                <option disabled selected>Selecciona un método de pago</option>';

                                                  $stmt = $conn->prepare('SELECT id, clave as codigo, descripcion FROM formas_pago_sat');
                                                  $stmt->execute();
                                                  $metodos_de_pago = $stmt->fetchAll();
                                                  foreach ($metodos_de_pago as $mp) {
                                                
                                                   echo '<option value="'.$mp['id'].'">'.$mp["codigo"]." - ".$mp["descripcion"].'</option>';

                                                  }
                                                
                                            echo '
                                              </select>
                                            </div>
                                            <div class="col-lg-6">
                                              <button type="button" class="btn btn-custom btn-custom--border-blue" id="timbrarLiquidacion">Timbrar liquidación</button>
                                            </div>
                                        <div class="col-lg-6">
                                          <button type="button" class="btn btn-custom btn-custom--border-blue" id="regresarFiniquito">Regresar</button>
                                        </div>
                                        <div class="col-lg-6">
                                          <button type="button" class="btn btn-custom btn-custom--red" id="eliminarFiniquito">Eliminar liquidación</button>
                                        </div>
                                      </div>
                                  </div>';

                            }

                            //cuando ya esta facturado el finiquito
                            if($datosFiniquito['estadoTimbrado'] != 0){

                                echo '
                                  <br><br>
                                  <div class="col-12 text-center">
                                      <div class="row" id="mostrarTimbrado">
                                        <div class="col-12 col-lg-12"><b>Opciones de factura de finiquito</b></div>
                                        <br><br>
                                            <div class="col-lg-3">
                                              <a href="functions/download_pdf_finiquito.php?value='.$datosFiniquito['idFactura'].'" class="btn btn-custom btn-custom--blue" target="_blank">Descargar pdf</a>
                                            </div>
                                            <div class="col-lg-3">
                                              <a href="functions/download_xml_finiquito.php?value='.$datosFiniquito['idFactura'].'" class="btn btn-custom btn-custom--blue" target="_blank">Descargar xml</a>
                                            </div>
                                            <div class="col-lg-3">
                                              <a href="functions/download_zip_finiquito.php?value='.$datosFiniquito['idFactura'].'" class="btn btn-custom btn-custom--blue" target="_blank">Descargar zip</a>
                                             </div>
                                             <div class="col-lg-3">
                                              <a href="#" class="btn btn-custom btn-custom--blue" id="send_email_finiquito" data-toggle="modal" data-target="#enviarFactura">Enviar por email</a>
                                            </div>
                                      </div>
                                  </div>';

                                  if($datosFiniquito['estadoTimbrado'] == 1){
                                            
                                    echo '
                                      <br>
                                      <div class="col-12 text-center">
                                          <div class="row" id="cancelarTimbradoMostrarFiniquito">';

                                          if($datosFiniquito['liquidacion_id'] == ""){
                                            
                                            echo '
                                            <div class="col-12 col-lg-6">
                                             <button type="button" class="btn btn-custom btn-custom--border-blue" id="regresarFiniquito">Regresar</button>
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                 <button type="button" class="btn btn-custom btn-custom--red" id="eliminarFiniquito2">Eliminar finiquito</button>
                                            </div>';

                                          }

                                      echo '      
                                          </div>
                                      </div>
                                      ';
                                }
     
                            }

                            //cuando ya esta facturado la liquidacion
                            if($datosFiniquito['estadoTimbradoLiquidacion'] != 0 && $tipoMovimiento == 2){

                                echo '
                                  <br><br>
                                  <div class="col-12 text-center">
                                      <div class="row" id="mostrarTimbrado">
                                        <div class="col-12 col-lg-12"><b>Opciones de factura de liquidación</b></div>
                                        <br><br>
                                            <div class="col-lg-3">
                                              <a href="functions/download_pdf_finiquito.php?value='.$datosFiniquito['idFacturaLiquidacion'].'" class="btn btn-custom btn-custom--blue" target="_blank">Descargar pdf</a>
                                            </div>
                                            <div class="col-lg-3">
                                              <a href="functions/download_xml_finiquito.php?value='.$datosFiniquito['idFacturaLiquidacion'].'" class="btn btn-custom btn-custom--blue" target="_blank">Descargar xml</a>
                                            </div>
                                            <div class="col-lg-3">
                                              <a href="functions/download_zip_finiquito.php?value='.$datosFiniquito['idFacturaLiquidacion'].'" class="btn btn-custom btn-custom--blue" target="_blank">Descargar zip</a>
                                             </div>
                                             <div class="col-lg-3">
                                              <a href="#" class="btn btn-custom btn-custom--blue" id="send_email_liquidacion" data-toggle="modal" data-target="#enviarFactura">Enviar por email</a>
                                            </div>
                                            <div class="col-lg-1"></div>
                                      </div>
                                  </div>';

                                  if($datosFiniquito['estadoTimbradoLiquidacion'] == 1){

                                  echo '
                                    <br>
                                    <div class="col-12 text-center">
                                        <div class="row" id="cancelarTimbradoMostrar">
                                          <div class="col-12 col-lg-6">
                                            <button type="button" class="btn btn-custom btn-custom--border-blue" id="regresarFiniquito">Regresar</button>
                                          </div>
                                          <div class="col-12 col-lg-6">
                                               <button type="button" class="btn btn-custom btn-custom--red" id="eliminarLiquidacion">Eliminar liquidación</button>
                                          </div>
                                        </div>
                                    </div>
                                    ';
                                }
                                if($datosFiniquito['estadoTimbradoLiquidacion'] == 2){
                                  echo '
                                    <br>
                                    <div class="col-12 text-center">
                                        <div class="row" id="cancelarTimbradoMostrar">
                                          <div class="col-6 col-lg-6">
                                            <button type="button" class="btn btn-custom btn-custom--border-blue" id="regresarFiniquito">Regresar</button>
                                          </div>
                                          <div class="col-6 col-lg-6">
                                               <button type="button" class="btn btn-custom btn-custom--red">'.$leyenda.'</button>
                                          </div>
                                        </div>
                                    </div>
                                    ';
                                }
     
                            }
                            if($datosFiniquito['estadoTimbradoLiquidacion'] == 0){

                                echo '<br>
                                  <div class="col-12 text-center">
                                      <div class="row" id="mostrarTimbrado">
                                        <br><br><br>
                                        <div class="col-lg-12">
                                          <label for="timbrarLiquidacionL"><b>Opciones de timbrado</b></label>
                                        </div>
                                        <div class="col-lg-6" style="position:relative; bottom: 20px;">
                                            <label>Método de pago:</label>
                                              <select class="form-control" id="metodo_pago_id">
                                                <option disabled selected>Selecciona un método de pago</option>';

                                                  $stmt = $conn->prepare('SELECT id, clave as codigo, descripcion FROM formas_pago_sat');
                                                  $stmt->execute();
                                                  $metodos_de_pago = $stmt->fetchAll();
                                                  foreach ($metodos_de_pago as $mp) {
                                                
                                                   echo '<option value="'.$mp['id'].'">'.$mp["codigo"]." - ".$mp["descripcion"].'</option>';

                                                  }
                                                
                                            echo '
                                              </select>
                                        </div>
                                        <div class="col-lg-6" style="position:relative; bottom: 20px;">
                                          <div class="row">
                                              <div class="col-lg-8">
                                                <label>Cfdi relacionados:</label>
                                                  <select class="form-control" id="facturas_relacionadas_id">
                                                    <option value="0" disabled selected>Selecciona un cfdi</option>';

                                                      $stmt = $conn->prepare('SELECT bcef.id, bcef.idFactura, bcef.uuid, IF(bcef.tipo = 1,"Finiquito","Liquidación") as tipo_factura, bcef.tipo FROM bitacora_cfdi_eliminado_finiquito_liquidacion as bcef INNER JOIN finiquito as f ON f.id = bcef.finiquito_id AND f.empleado_id = :empleado_id ORDER BY tipo_factura');
                                                      $stmt->bindValue(":empleado_id",$idEmpleado);
                                                      $stmt->execute();
                                                      $bitacora = $stmt->fetchAll();
                                                      foreach ($bitacora as $b) {
                                                    
                                                       echo '<option value="'.$b['uuid'].'&'.$b["tipo"].'&'.$b["id"].'&'.$b["idFactura"].'">'.$b["uuid"]." - ".$b["tipo_factura"].'</option>';

                                                      }
                                                    
                                                echo '
                                                  </select>
                                              </div>
                                              <div class="col-lg-4">
                                                <br>
                                                <button type="button" class="btn btn-custom btn-custom--border-blue" id="agregarRelacion">+</button>
                                                <a href="#" class="btn btn-custom btn-custom--border-blue" id="descargarPDF" target="_self" > <i class="fas fa-file-invoice"></i> PDF </a>
                                                <a href="#" class="btn btn-custom btn-custom--border-blue" id="descargarXML" target="_self"> <i class="far fa-file-alt"></i> XML </a>
                                              </div>
                                          </div>
                                        </div>
                                        <div class="col-lg-12">
                                          <label><b>CFDI agregados para relacionar:</b></label><br>
                                          <span id="cfdiRelacionado">No hay ningún CFDI relacionado.</span>
                                        </div>   
                                        <br><br><br>
                                        <div class="col-lg-12">
                                              <button type="button" class="btn btn-custom btn-custom--border-blue" id="timbrarLiquidacion">Timbrar liquidación</button>
                                        </div>
                                      </div>
                                      <br><br>
                                      <div class="row" id="cancelarTimbradoMostrar">
                                        <div class="col-lg-6">
                                          <button type="button" class="btn btn-custom btn-custom--border-blue" id="regresarFiniquito">Regresar</button>
                                        </div>
                                        ';

                                        if($datosFiniquito['estadoTimbrado'] == 1 && $datosFiniquito['estadoTimbradoLiquidacion'] == 0){
                                          $idEliminarLiquidacion = "eliminarLiquidacion_solofiniquito";
                                        }
                                        elseif($datosFiniquito['estadoTimbrado'] == 0 && $datosFiniquito['estadoTimbradoLiquidacion'] == 0){
                                          $idEliminarLiquidacion = "eliminarLiquidacion_unico";
                                        }
                                        elseif($datosFiniquito['estadoTimbrado'] == 1 && $datosFiniquito['estadoTimbradoLiquidacion'] == 1){
                                          $idEliminarLiquidacion = "eliminarLiquidacion";
                                        }
                                        
                                        echo '
                                        <div class="col-lg-6">
                                          <button type="button" class="btn btn-custom btn-custom--red" id="'.$idEliminarLiquidacion.'">Eliminar liquidación</button>
                                        </div>
                                      </div>
                                  </div>';

                            }
                        }


?>



                      </div>
                    </div>
                  </div>

                <hr>  
    
          </div>
        </div>


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
  </div>

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>


  <!-- Modal s  end email client -->
    <div class="modal fade" id="enviarFactura" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Enviar factura por email</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" id="enviarDestinatarios">
            <form id="formEnviarEmail">
              <div class="form-group">
                <label for="txtDestino">Destinatario:</label>                
                <input type="text" class="form-control" name="txtDestino" id="txtDestino" required="">
                <div class="invalid-feedback" id="invalid-destino">Debe ingresar un email destinatario.</div>
              </div>
            </form>
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
              id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnEnviarFactura"><span
                class="ajusteProyecto">Enviar</span></button>
          </div>
        </div>
      </div>
    </div>
    <!-- End modal send email client -->



    <!--ADD MODAL CLAVE nuevas claves-->
    <div class="modal fade right" id="agregar_clave" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
          <div class="modal-content">
            <form action="#" method="POST" id="agregarNomina">
              <!--<input type="hidden" name="idProyectoA" value="">-->
              <div class="modal-header">
                <h4 class="modal-title w-100" id="myModalLabel">Agregar clave</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">x</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label for="usr">Tipo*:</label>
                  <div class="row">
                      <div class="col-lg-6">
                        <input type="radio" id="tipoConceptoAgregar" name="tipoConceptoAgregar" value="1" class="tipoConceptoCAgregar" checked>
                        <label for="percepcion">Percepción</label>
                      </div>
                      <div class="col-lg-6">
                        <input type="radio" id="tipoConceptoAgregar" name="tipoConceptoAgregar" value="2" class="tipoConceptoCAgregar">
                        <label for="deduccion">Deducción</label>
                      </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="usr">Concepto*:</label>
                  <select class="form-control" id="cmbConceptoClave" name="cmbConceptoClave">
                    <?php
                      $stmt = $conn->prepare('SELECT id,codigo, concepto FROM tipo_percepcion');
                      $stmt->execute();
                      $claves = $stmt->fetchAll();
                      foreach ($claves as $c) {
                    ?>
                        <option value="<?=$c['id']?>"><?=$c["codigo"]." - ".$c["concepto"]?></option>
                    <?php
                      }
                    ?>
                  </select>
                </div>
                <div class="form-group">
                  <label for="usr">Clave*:</label>
                  <input type="text" class="form-control" name="txtClaveAgregar" id="txtClaveAgregar" value="" maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" required>
                </div>
                <label style="color:#006dd9;font-size: 13px;"> (*) Campos requeridos</label>
                <div class="modal-footer justify-content-center">
                  <button type="button" class="btn-custom btn-custom--border-blue btnCancelarActualizacion"
                  data-dismiss="modal" id="btnCancelarClave"><span class="ajusteProyecto">Cancelar</span></button>
                  <button type="button" class="btn-custom btn-custom--blue" name="btnAgregar" id="btnAgregarClave"><span
                    class="ajusteProyecto">Agregar</span></button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  <!--END ADD MODAL-->

  <?php
    $stmt = $conn->prepare('SELECT cantidad FROM parametros_nomina WHERE descripcion = "Dias_Aguinaldo" = 1 AND empresa_id = '.$_SESSION['IDEmpresa']);
    $stmt->execute();
    $row_par = $stmt->fetch();

    $diasAguinaldo = $row_par['cantidad'];
  ?>

  <!-- Opciones aguinaldo -->
  <div id="aguinaldo_modal" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="#" method="POST">
          <div class="modal-header">
            <h4 class="modal-title">Aguinaldo</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-12 col-lg-12">
                <label>Días aguinaldo:</label>
                <input type="number" class="form-control" id="txtDiasAguinaldo" min="1" max="30" value="<?=$diasAguinaldo?>" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
              </div>
            </div>
            <br>
            <div class="form-group">
                <br>
                  <label for="usr">Procedimiento:</label>
                  <div class="row">
                      <div class="col-lg-12">
                        <input type="radio" id="procedimientoISR" name="procedimientoISR" value="1" class="procedimientoISR" checked>
                        <label for="isr">Ley del impuesto sobre la renta</label>
                      </div>
                      <div class="col-lg-12">
                        <input type="radio" id="procedimientoISR" name="procedimientoISR" value="2" class="procedimientoISR">
                        <label for="risr">Reglamento del impuesto sobre la renta</label>
                      </div>
                  </div>
            </div>
            <br>
            <div class="form-group" id="claveMostrarAguinaldo" style="display: none;">
                  <label for="usr">Clave:</label>
                  <input type="text" class="form-control" name="txtClaveAguinaldo" id="txtClaveAguinaldo" value="" maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" required>
            </div>
            <br>
            <div class="row" style="display: block;">
              <center>
                <button type="button" class="btn btn-custom btn-custom--blue" id="agregarAguinaldo">Guardar</button>
              </center>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- modal Calculo -->
  <div id="calculo_unico_modal" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="tituloCalculo"></h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body" id="mostrarCalculoUnico">
          </div>
      </div>
    </div>
  </div>

  <input type="hidden" name="csr_token_UT5JP" id="csr_token_UT5JP" value="<?=$token?>">
  <input type="hidden" name="idFiniquito" id="idFiniquito" value="<?=$idFiniquito?>">
  
  <?php
  // se evaluan las claves que son necesarias ingresar
    $stmt = $conn->prepare('SELECT id FROM relacion_tipo_percepcion WHERE tipo_percepcion_id = 1 AND empresa_id = '.$_SESSION['IDEmpresa']);
    $stmt->execute();
    $clave_salario = $stmt->rowCount();

    $cantidad_clave = 0;
    if($clave_salario < 1){
      $cantidad_clave = 1;
    }

    $stmt = $conn->prepare('SELECT id FROM relacion_tipo_deduccion WHERE tipo_deduccion_id = 1 AND empresa_id = '.$_SESSION['IDEmpresa']." ORDER BY id ASC");
    $stmt->execute();
    $clave_imss = $stmt->rowCount();

    $cantidad_clave_imss = 0;
    if($clave_imss < 1){
      $cantidad_clave_imss = 1;
    }

    $stmt = $conn->prepare('SELECT id FROM relacion_tipo_deduccion WHERE tipo_deduccion_id = 2 AND empresa_id = '.$_SESSION['IDEmpresa']." ORDER BY id ASC");
    $stmt->execute();
    $clave_isr = $stmt->rowCount();

    $cantidad_clave_isr = 0;
    if($clave_isr < 1){
      $cantidad_clave_isr = 1;
    }

    $stmt = $conn->prepare('SELECT id FROM relacion_tipo_percepcion WHERE tipo_percepcion_id = 14 AND empresa_id = '.$_SESSION['IDEmpresa']);
    $stmt->execute();
    $clave_salario_horasextra = $stmt->rowCount();

    $cantidad_clave_horasextra = 0;
    if($clave_salario_horasextra < 1){
      $cantidad_clave_horasextra = 1;
    }

    $stmt = $conn->prepare('SELECT id FROM relacion_tipo_percepcion WHERE tipo_percepcion_id = 15 AND empresa_id = '.$_SESSION['IDEmpresa']);
    $stmt->execute();
    $clave_salario_primadominical = $stmt->rowCount();

    $cantidad_clave_primadominical = 0;
    if($clave_salario_primadominical < 1){
      $cantidad_clave_primadominical = 1;
    }

    $stmt = $conn->prepare('SELECT id FROM relacion_tipo_percepcion WHERE tipo_percepcion_id = 16 AND empresa_id = '.$_SESSION['IDEmpresa']);
    $stmt->execute();
    $clave_salario_primavacacional = $stmt->rowCount();

    $cantidad_clave_primavacacional = 0;
    if($clave_salario_primavacacional < 1){
      $cantidad_clave_primavacacional = 1;
    }

    $stmt = $conn->prepare('SELECT id FROM relacion_tipo_percepcion WHERE tipo_percepcion_id = 33 AND empresa_id = '.$_SESSION['IDEmpresa']);
    $stmt->execute();
    $clave_salario_otrosingresos = $stmt->rowCount();

    $cantidad_clave_otrosingresos = 0;
    if($clave_salario_otrosingresos < 1){
      $cantidad_clave_otrosingresos = 1;
    }

    $stmt = $conn->prepare('SELECT id FROM relacion_tipo_deduccion WHERE tipo_deduccion_id = 6 AND empresa_id = '.$_SESSION['IDEmpresa']." ORDER BY id ASC");
    $stmt->execute();
    $clave_descuento = $stmt->rowCount();

    $cantidad_clave_descuentoincapacidad = 0;
    if($clave_descuento < 1){
      $cantidad_clave_descuentoincapacidad = 1;
    }

    $stmt = $conn->prepare('SELECT id FROM relacion_tipo_deduccion WHERE tipo_deduccion_id = 20 AND empresa_id = '.$_SESSION['IDEmpresa']." ORDER BY id ASC");
    $stmt->execute();
    $clave_ausencia = $stmt->rowCount();

    $cantidad_clave_ausencia = 0;
    if($clave_ausencia < 1){
      $cantidad_clave_ausencia = 1;
    }

     
    //SE CARGAN LAS OPCIONES PARA CANCELAR LA FACTURA
    $stmt = $conn->prepare('SELECT id, clave, descripcion FROM motivo_cancelacion_factura');
    $stmt->execute();
    $motivos = $stmt->fetchAll();
    $options = "";

    foreach ($motivos as $m) {
      $options = $options . "'".$m['id']."': '".$m['clave']." - ".$m['descripcion']."',";
    }

    $opciones =          "input: 'select',
                          inputOptions: {
                            ".$options."
                          },
                          inputPlaceholder: 'Motivo de cancelación.',
                            inputValidator: function (value) {
                            return new Promise(function (resolve, reject) {
                              if (value !== '') {
                                resolve();
                              } else {
                                resolve('Selecciona el motivo de cancelación');
                              }
                            });
                          },";
    $mensajeOpciones = ", se cancelará la factura";
  ?>

  <!-- Custom scripts for all pages-->
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>

  <script>
    let idEmpleado = 0;//se usara en todas las funciones
    let diasAguinaldo = 0;
    let diasVacaciones = 0;
    let tipoMovimiento = <?=$tipoMovimiento?>;//1 finiquito   2 liquidacion
    let estadoTimbrado = <?=$datosFiniquito['estadoTimbrado']?>;
    let idFacturaFiniquitoGeneral = "<?=$datosFiniquito['idFactura']?>";
    let idFacturaLiquidacionGeneral = "<?=$datosFiniquito['idFacturaLiquidacion']?>";

    /*let fechaTimbrado = "";*/
    $("#empleado").change(function(){

      if($("#empleado").val() < 1){
        Lobibox.notify("error", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/warning_circle.svg',
          msg: "Selecciona un empleado para modificar su finiquito."
        });
        return;
      }

      idEmpleado = $("#empleado").val();
    });

    $("#seleccionarEmpleado").click(function(){

      if($("#empleado").val() < 1){
        Lobibox.notify("error", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/warning_circle.svg',
          msg: "Selecciona un empleado."
        });
        return;
      }

      if(!$('.tipoFinal').is(':checked')){ 
        Lobibox.notify("error", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/warning_circle.svg',
          msg: "Selecciona el tipo de movimiento."
        });
        return;
      }

      let token = $("#csr_token_UT5JP").val();

      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          actions: "d-flex justify-content-around",
          confirmButton: "btn-custom btn-custom--border-blue",
          cancelButton: "btn-custom btn-custom--blue",
        },
        buttonsStyling: false,
      });

      swalWithBootstrapButtons
        .fire({
          title: "¿Desea continuar?",
          text: "Ya no podras cambiar de empleado o tipo a menos de que recargues la página.",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: '<span class="verticalCenter">Confirmar</span>',
          cancelButtonText: '<span class="verticalCenter">Cerrar</span>',
          reverseButtons: true,
          allowOutsideClick: false,
        })
        .then((result) => {
          if (result.isConfirmed) {

            $.ajax({
              type: 'POST',
              url: 'functions/cargarFechaIngreso.php',
              data: { 
                      idEmpleado: idEmpleado,
                      csr_token_UT5JP: token
                    },
              success: function(r) {

                var datos = JSON.parse(r);

                if(datos.estatus == "exito"){
                    
                    $("#txtFechaIngreso").val(datos.fechaIngreso);
                    selectEmpleado.disable();
                    
                    $("#txtFechaSalida").prop("readonly", false);
                    $("#seleccionarEmpleado").prop("disabled", true);
                    $("#txtSalariosDevengados").prop("readonly", false);
                    $("#txtFechaIniP").prop("readonly", false);
                    $("#txtFechaFinP").prop("readonly", false);
                    $("#txtGratificacion").prop("readonly", false);
                    $("#txtOtros").prop("readonly", false);
                    $("#calcularFiniquito").prop("disabled", false);

                    $("#NombreEmpleado").html(datos.nombreEmpleado);
                    $("#NSS").html(datos.nss);
                    $("#RFC").html(datos.rfc);
                    $("#Turno").html(datos.Turno);
                    $("#Puesto").html(datos.puesto);
                    $("#SalarioSpan").html(datos.sueldoPeriodo);
                    $("#SalarioDiarioS").html(datos.sueldoDiario);   
                    $("#SalarioDiarioIntegradoS").html(datos.SDI);

                    tipoMovimiento = $('.tipoFinal:checked').val();
                    $(".tipoFinal ").prop("disabled", true);

                }

                if(datos.estatus == "fallo"){
                  Lobibox.notify("error", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/notificacion_error.svg",
                    msg: "¡Ocurrio un error, intentalo nuevamente!",
                  });
                }

              },
              error: function(){
                Lobibox.notify("error", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top",
                  icon: true,
                  img: "../../img/timdesk/notificacion_error.svg",
                  msg: "¡Ocurrio un error, intentalo nuevamente!",
                });

              }
            });


          } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
          ) {


          }
        });
    });

    $("#txtFechaSalida").change(function(){

        let token = $("#csr_token_UT5JP").val();
        let fechaIngreso = $("#txtFechaIngreso").val().trim();
        let fechaSalida = $("#txtFechaSalida").val().trim();

        if(fechaSalida == "" || fechaSalida == null){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Ingresa la fecha de salida."
          });
          return;
        }

        if (new Date(fechaIngreso).getTime() > new Date(fechaSalida).getTime()) {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "La fecha de ingreso no puede ser posterior a la fecha de salida.",
          });
          return;
        }

        $("#guardarFiniquito").prop("disabled", true);

        $.ajax({
          type: 'POST',
          url: 'functions/cargarDatosFiniquito.php',
          data: { 
                  idEmpleado: idEmpleado,
                  fechaIngreso: fechaIngreso,
                  fechaSalida: fechaSalida,
                  csr_token_UT5JP: token
                },
          success: function(r) {

            var datos = JSON.parse(r);
              
              $("#txtDiasAguinaldo").val(datos.dias_aguinaldo);
              $("#txtProporcionalesAguinaldo").val(datos.dias_aguinaldo_proporcionales);
              $("#txtAntiguedad").val(datos.num_anios);
              $("#txtDiasVacaciones").val(datos.dias_vacaciones);
              $("#txtProporcionalesVacaciones").val(datos.dias_vacaciones_calculo);
              $("#txtDiasRestantes").val(datos.dias_vacaciones_restantes);
              $("#txtDiasPagar").val(datos.dias_vacaciones_a_pagar);
              $("#ultimo_sueldo_mensual_ord").val(datos.ultimo_sueldo_mensual_ord);

              diasAguinaldo = datos.dias_aguinaldo_proporcionales;
              diasVacaciones = datos.dias_vacaciones_a_pagar;

          },
          error: function(){
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/notificacion_error.svg",
              msg: "¡Ocurrio un error, intentalo nuevamente!",
            });

          }
        });

    });

    $("#calcularFiniquito").click(function(){

      let token = $("#csr_token_UT5JP").val();
      let fechaIngreso = $("#txtFechaIngreso").val().trim();
      let fechaSalida = $("#txtFechaSalida").val().trim();
      let fechaPeriodoIni = $("#txtFechaIniP").val().trim();
      let fechaPeriodoFin = $("#txtFechaFinP").val().trim();
      let salariosDevengadosObj =  numeral($("#txtSalariosDevengados").val().trim());
      let salariosDevengados = salariosDevengadosObj.value();
      let gratificacionObj =  numeral($("#txtGratificacion").val().trim());
      let gratificacion = gratificacionObj.value();
      let otrosObj =  numeral($("#txtOtros").val().trim());
      let otros = otrosObj.value();
      let antiguedad = $("#txtAntiguedad").val().trim();

      if(fechaSalida == "" || fechaSalida == null){
        Lobibox.notify("error", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/warning_circle.svg',
          msg: "Ingresa la fecha de salida."
        });
        return;
      }

      if (new Date(fechaIngreso).getTime() > new Date(fechaSalida).getTime()) {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "La fecha de ingreso no puede ser posterior a la fecha de salida.",
        });
        return;
      }

      if($("#txtSalariosDevengados").val().trim() != ""){

          if(fechaPeriodoIni == "" || fechaPeriodoIni == null){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "La fecha inicial del periodo no puede estar vacía si ingresaste salarios atrasados."
            });
            return;
          }

          if(fechaPeriodoFin == "" || fechaPeriodoFin == null){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "La fecha final del periodo no puede estar vacía si ingresaste salarios atrasados."
            });
            return;
          }

          if (new Date(fechaPeriodoIni).getTime() > new Date(fechaPeriodoFin).getTime()) {
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/notificacion_error.svg",
              msg: "La fecha inicial del periodo no puede ser posterior a la fecha final del periodo.",
            });
            return;
          }
      }


          $.ajax({
            type: 'POST',
            url: 'functions/calcularFiniquito.php',
            data: { 
                    idEmpleado: idEmpleado,
                    diasAguinaldo: diasAguinaldo,
                    diasVacaciones: diasVacaciones,
                    csr_token_UT5JP: token,
                    salariosDevengados: salariosDevengados,
                    fechaPeriodoIni: fechaPeriodoIni,
                    fechaPeriodoFin: fechaPeriodoFin,
                    tipoMovimiento: tipoMovimiento,
                    fechaSalida: fechaSalida,
                    antiguedad: antiguedad,
                    gratificacion: gratificacion,
                    otros: otros
                  },
            success: function(r) {

                $("#cargarFiniquito").html(r);
                $("#guardarFiniquito").prop("disabled", false);

            },
            error: function(){
              Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "¡Ocurrio un error, intentalo nuevamente!",
              });

            }
          });


    });

    $("#txtSalariosDevengados,#txtFechaIniP,#txtFechaFinP,#txtGratificacion,#txtOtros").change(function(){
      $("#guardarFiniquito").prop("disabled", true);
    });

    $(document).on('click','#guardarFiniquito', function(e) {


      let token = $("#csr_token_UT5JP").val();
      let fechaIngreso = $("#txtFechaIngreso").val().trim();
      let fechaSalida = $("#txtFechaSalida").val().trim();
      let fechaPeriodoIni = $("#txtFechaIniP").val().trim();
      let fechaPeriodoFin = $("#txtFechaFinP").val().trim();
      let salariosDevengadosObj =  numeral($("#txtSalariosDevengados").val().trim());
      let salariosDevengados = salariosDevengadosObj.value();
      let diasAguinaldoTotales = $("#txtDiasAguinaldo").val().trim();

      if(fechaSalida == "" || fechaSalida == null){
        Lobibox.notify("error", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/warning_circle.svg',
          msg: "Ingresa la fecha de salida."
        });
        return;
      }

      if (new Date(fechaIngreso).getTime() > new Date(fechaSalida).getTime()) {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "La fecha de ingreso no puede ser posterior a la fecha de salida.",
        });
        return;
      }

      if($("#txtSalariosDevengados").val().trim() != ""){

          if(fechaPeriodoIni == "" || fechaPeriodoIni == null){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "La fecha inicial del periodo no puede estar vacía si ingresaste salarios atrasados."
            });
            return;
          }

          if(fechaPeriodoFin == "" || fechaPeriodoFin == null){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "La fecha final del periodo no puede estar vacía si ingresaste salarios atrasados."
            });
            return;
          }

          if (new Date(fechaPeriodoIni).getTime() > new Date(fechaPeriodoFin).getTime()) {
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/notificacion_error.svg",
              msg: "La fecha inicial del periodo no puede ser posterior a la fecha final del periodo.",
            });
            return;
          }
      }

      //Se asignan todas las variables
      let diasAguinaldoProporcionales = $("#txtProporcionalesAguinaldo").val().trim();
      let antiguedad = $("#txtAntiguedad").val().trim();
      let ultimo_sueldo_mensual_ord = $("#ultimo_sueldo_mensual_ord").val().trim();

      let aguinaldoPercepcion = $("#lblAguinaldoPercepcion").html();
      let aguinaldoExento = $("#lblAguinaldoExento").html();
      let aguinaldoGravado = $("#lblAguinaldoGravado").html();

      let vacacionesPercepcion = $("#lblVacacionesPercepcion").html();
      let diasVacacionesProporcionales = $("#txtProporcionalesVacaciones").val().trim();
      let diasVacacionesRestantes = $("#txtDiasRestantes").val().trim();
      let diasVacacionesPagar = $("#txtDiasPagar").val().trim();

      let primaVacacionalPercepcion = $("#lblPrimaVacacionalPercepcion").html();
      let primaVacacionalExento = $("#lblPrimaVacacionalExento").html();
      let primaVacacionalGravado = $("#lblPrimaVacacionalGravado").html();

      let salarioDevengadoPercepcion = $("#lblSalarioDevengadoPercepcion").html();
      let salarioDevengadoExento = $("#lblSalarioDevengadoExento").html();
      let salarioDevengadoGravado = $("#lblSalarioDevengadoGravado").html();

      let otros = $("#lblOtrosPercepcion").html();
      let gratificacion = $("#lblGratificacionPercepcion").html();

      let subtotalPercepcion = $("#lblSubtotalFiniquitoPercepcion").html();
      let subtotalExento = $("#lblSubtotalFiniquitoExento").html();
      let subtotalGravado = $("#lblSubtotalFiniquitoGravado").html();

      let ISRVacacionesSalarios = $("#lblISRVacacacionesSalarios").html();
      let ISRAguinaldo = $("#lblISRAguinaldo").html();
      let ISRPrimaVacacional = $("#lblISRPrimaVacacional").html();

      let tipoISRVacacionesSalarios = $("#tipoISRVacacionesSalarios").val().trim();
      let tipoISRAguinaldo = $("#tipoISRAguinaldo").val().trim();
      let tipoISRPrimaVacacional = $("#tipoISRPrimaVacacional").val().trim();

      let TotalPagar = $("#lblTotalPagar").html();


      let IndeminizacionPercepcion = '', IndeminizacionExento = '', IndeminizacionGravado = '';
      
      let SalarioAnioPercepcion = '', PrimaAntiguedadPercepcion = '', ISRIndemnizacion = '', tipoISRIndemnizacion = '';   

      //variables de las liquidaciones
      if(tipoMovimiento == 2){
        IndeminizacionPercepcion = $("#lblIndeminizacionPercepcion").html();
        IndeminizacionExento = $("#lblIndeminizacionExento").html();
        IndeminizacionGravado = $("#lblIndeminizacionGravado").html();
        
        SalarioAnioPercepcion = $("#lblSalarioAnioPercepcion").html();
        PrimaAntiguedadPercepcion = $("#lblPrimaAntiguedadPercepcion").html();   
        ISRIndemnizacion = $("#lblISRIndemnizacion").html();   
        tipoISRIndemnizacion = $("#tipoISRIndemnizacion").val();   
      }
      else{
        IndeminizacionPercepcion = 0.00;
        IndeminizacionExento = 0.00;
        IndeminizacionGravado = 0.00;
        
        SalarioAnioPercepcion = 0.00;
        PrimaAntiguedadPercepcion = 0.00;  
        ISRIndemnizacion = 0.00;
        tipoISRIndemnizacion = 0;
      }

      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          actions: "d-flex justify-content-around",
          confirmButton: "btn-custom btn-custom--border-blue",
          cancelButton: "btn-custom btn-custom--blue",
        },
        buttonsStyling: false,
      });

      swalWithBootstrapButtons
        .fire({
          title: "¿Deseas guardar el finiquito?",
          text: "",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: '<span class="verticalCenter">Confirmar</span>',
          cancelButtonText: '<span class="verticalCenter">Cerrar</span>',
          reverseButtons: true,
          allowOutsideClick: false,
        })
        .then((result) => {
          if (result.isConfirmed) {

            $("#guardarFiniquito").prop("disabled", true);

            $.ajax({
              type: 'POST',
              url: 'functions/guardarFiniquito.php',
              data: { 
                      idEmpleado: idEmpleado,
                      fechaIngreso: fechaIngreso,
                      fechaSalida: fechaSalida,
                      diasAguinaldo: diasAguinaldo,
                      diasAguinaldoProporcionales: diasAguinaldoProporcionales,
                      antiguedad: antiguedad,
                      diasVacaciones: diasVacaciones,
                      csr_token_UT5JP: token,
                      salariosDevengados: salariosDevengados,
                      fechaPeriodoIni: fechaPeriodoIni,
                      fechaPeriodoFin: fechaPeriodoFin,
                      aguinaldoPercepcion: aguinaldoPercepcion,
                      aguinaldoExento: aguinaldoExento,
                      aguinaldoGravado: aguinaldoGravado,
                      vacacionesPercepcion: vacacionesPercepcion,
                      diasVacacionesProporcionales: diasVacacionesProporcionales,
                      diasVacacionesRestantes: diasVacacionesRestantes,
                      diasVacacionesPagar : diasVacacionesPagar,
                      primaVacacionalPercepcion: primaVacacionalPercepcion,
                      primaVacacionalExento: primaVacacionalExento,
                      primaVacacionalGravado: primaVacacionalGravado,
                      salarioDevengadoPercepcion: salarioDevengadoPercepcion,
                      salarioDevengadoExento: salarioDevengadoExento,
                      salarioDevengadoGravado: salarioDevengadoGravado,
                      otros: otros,
                      gratificacion: gratificacion,
                      subtotalPercepcion: subtotalPercepcion,
                      subtotalExento: subtotalExento,
                      subtotalGravado: subtotalGravado,
                      ISRVacacionesSalarios: ISRVacacionesSalarios,
                      ISRAguinaldo: ISRAguinaldo,
                      ISRPrimaVacacional: ISRPrimaVacacional,
                      TotalPagar: TotalPagar,
                      tipoISRVacacionesSalarios: tipoISRVacacionesSalarios,
                      tipoISRAguinaldo: tipoISRAguinaldo,
                      tipoISRPrimaVacacional: tipoISRPrimaVacacional,
                      tipoMovimiento: tipoMovimiento,
                      IndeminizacionPercepcion: IndeminizacionPercepcion,
                      IndeminizacionExento: IndeminizacionExento,
                      IndeminizacionGravado: IndeminizacionGravado,
                      SalarioAnioPercepcion: SalarioAnioPercepcion,
                      PrimaAntiguedadPercepcion: PrimaAntiguedadPercepcion,
                      ultimo_sueldo_mensual_ord: ultimo_sueldo_mensual_ord,
                      ISRIndemnizacion: ISRIndemnizacion,
                      tipoISRIndemnizacion: tipoISRIndemnizacion
                    },
              success: function(r) {
                
                var datos = JSON.parse(r);

                if(datos.estatus == "exito"){
                  
                  $("#txtFechaSalida").prop("readonly", true);
                  $("#txtSalariosDevengados").prop("readonly", true);
                  $("#txtFechaIniP").prop("readonly", true);
                  $("#txtFechaFinP").prop("readonly", true);
                  $("#txtGratificacion").prop("readonly", true);
                  $("#txtOtros").prop("readonly", true);
                  $("#calcularFiniquito").prop("disabled", true);
                  $("#guardarFiniquito").css("display", "none");
                  $("#mostrarTimbrado").css("display", "flex");
                  $("#idFiniquito").val(datos.idfiniquito);

                  Lobibox.notify("success", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/checkmark.svg",
                    msg: "Se guardo el finiquito, ahora lo puedes timbrar.",
                  });
                  
                }

                if(datos.estatus == "fallo"){
                  Lobibox.notify("error", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/notificacion_error.svg",
                    msg: "¡Ocurrio un error, intentalo nuevamente!",
                  });
                  $("#guardarFiniquito").prop("disabled", false);
                }

              },
              error: function(){
                Lobibox.notify("error", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top",
                  icon: true,
                  img: "../../img/timdesk/notificacion_error.svg",
                  msg: "¡Ocurrio un error, intentalo nuevamente!",
                });
                $("#guardarFiniquito").prop("disabled", false);
              }
            });


          } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
          ) {


          }
        });

    });
    
    let idFactura = "";
    let fechaTimbrado = "";
    $(document).on('click','#timbrarFiniquito', function(e) {
      let idFiniquito = $("#idFiniquito").val();
      let token = $("#csr_token_UT5JP").val();
      let metodo_pago_id = $("#metodo_pago_id").val();
      let metodo_pago = $("#metodo_pago_id option:selected").text();

      let IDs = [];
      $("#cfdiRelacionado").find(".mostrarCFDIRelacionado").each(function(){ IDs.push(this.id); });

      if(metodo_pago_id < 1 || metodo_pago_id == null){
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "Selecciona un método de pago.",
        });
        return;
      }

      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          actions: "d-flex justify-content-around",
          confirmButton: "btn-custom btn-custom--border-blue",
          cancelButton: "btn-custom btn-custom--blue",
        },
        buttonsStyling: false,
      });

      swalWithBootstrapButtons
        .fire({
          title: "¿Desea continuar?",
          text: "Se timbrará el finiquito de este empleado.",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: '<span class="verticalCenter">Confirmar</span>',
          cancelButtonText: '<span class="verticalCenter">Cerrar</span>',
          reverseButtons: true,
          allowOutsideClick: false,
        })
        .then((result) => {
          if (result.isConfirmed) {

            $("#timbrarFiniquito").prop("disabled", true);

            $.ajax({
              type: 'POST',
              url: 'functions/facturar_finiquito_liquidacion.php',
              data: {
                idFiniquito: idFiniquito,
                metodo_pago_id: metodo_pago_id,
                tipoMovimiento: tipoMovimiento,
                csr_token_UT5JP : token,
                idRelacionados : IDs
              },
              success: function(data) {

                var datos = JSON.parse(data);

                if (datos.estatus == "fallo-general") {

                  Lobibox.notify("error", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/warning_circle.svg',
                    msg: datos.mensaje
                  });
                  $("#timbrarFiniquito").prop("disabled", false);

                }

                if (datos.estatus == "fallo-estadotimbrado") {

                  Lobibox.notify("error", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/warning_circle.svg',
                    msg: "Ya timbraste el finiquito del empleado."
                  });
                  idFacturaFiniquitoGeneral = datos.idFactura;
                  fechaTimbrado = datos.fechaTimbrado;
                  mostrarOpcionesFactura(idFacturaFiniquitoGeneral, '');  
                  $("#timbrarFiniquito").prop("disabled", false);

                  let nuevoTexto =     '<div class="col-lg-4">' +
                                          '<button type="button" class="btn btn-custom btn-custom--border-blue" id="exportarExcel">Exportar Excel</button>' +
                                        '</div>' +
                                        '<div class="col-lg-4">' +
                                          '<label for="">Método de pago:</label><br>' +
                                          '<span class="btn btn-custom btn-custom--blue">' + datos.metodo_pago_act + '</span>' +
                                        '</div>' +
                                        '<div class="col-lg-4">' +
                                          '<button type="button" class="btn btn-custom btn-custom--border-blue" id="exportarPDF">Exportar PDF</button>' +
                                        '</div> ';
                  $("#actualizarMetodoPago").html(nuevoTexto);

                }

                if (datos.estatus == "fallo-isr") {

                  Lobibox.notify("error", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/warning_circle.svg',
                    msg: "No puedes timbrar ninguna nómina, te falta la clave del ISR."
                  });
                  $("#timbrarFiniquito").prop("disabled", false);

                }

                if (datos.estatus == "exito") {

                  Lobibox.notify("success", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/checkmark.svg',
                    msg: "Se timbro el finiquito del empleado."
                  });
                  idFacturaFiniquitoGeneral = datos.idFactura;
                  fechaTimbrado = datos.fechaTimbrado;
                  mostrarOpcionesFactura(idFacturaFiniquitoGeneral, '');  
                  $('#eliminarFiniquito').attr("id","eliminarFiniquito2")
                  $("#timbrarNominaIndividual").prop("disabled", false);

                  let nuevoTexto =     '<div class="col-lg-4">' +
                                          '<button type="button" class="btn btn-custom btn-custom--border-blue" id="exportarExcel">Exportar Excel</button>' +
                                        '</div>' +
                                        '<div class="col-lg-4">' +
                                          '<label for="">Método de pago:</label><br>' +
                                          '<span class="btn btn-custom btn-custom--blue">' + metodo_pago + '</span>' +
                                        '</div>' +
                                        '<div class="col-lg-4">' +
                                          '<button type="button" class="btn btn-custom btn-custom--border-blue" id="exportarPDF">Exportar PDF</button>' +
                                        '</div> ';
                  $("#actualizarMetodoPago").html(nuevoTexto);

                  if (datos.estatus_email == "exito") {

                    Lobibox.notify("success", {
                      size: 'mini',
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: 'center top', //or 'center bottom'
                      icon: false,
                      img: '../../img/timdesk/checkmark.svg',
                      msg: "Se envio el recibo del finiquito al correo registrado del empleado."
                    });

                  }

                  if (datos.estatus_email == "fallo") {
                    Lobibox.notify("error", {
                      size: 'mini',
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: 'center top', //or 'center bottom'
                      icon: false,
                      img: '../../img/timdesk/warning_circle.svg',
                      msg: "No se pudo enviar el email del finiquito."
                    });
                  }

                }

                if (datos.estatus == "fallo" || datos.estatusLiquidacion == "fallo") {

                  Lobibox.notify("error", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/warning_circle.svg',
                    msg: "Ocurrio un error, vuelva intentarlo."
                  });
                  $("#timbrarFiniquito").prop("disabled", false);

                }
              }
            });



          } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
          ) {


          }
        });

    });


    $(document).on('click','#timbrarLiquidacion', function(e) {
      let idFiniquito = $("#idFiniquito").val();
      let token = $("#csr_token_UT5JP").val();
      let metodo_pago_id = $("#metodo_pago_id").val();
      let metodo_pago = $("#metodo_pago_id option:selected").text();

      let IDs = [];
      $("#cfdiRelacionado").find(".mostrarCFDIRelacionado").each(function(){ IDs.push(this.id); });

      if(metodo_pago_id < 1 || metodo_pago_id == null){
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "Selecciona un método de pago.",
        });
        return;
      }

      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          actions: "d-flex justify-content-around",
          confirmButton: "btn-custom btn-custom--border-blue",
          cancelButton: "btn-custom btn-custom--blue",
        },
        buttonsStyling: false,
      });

      swalWithBootstrapButtons
        .fire({
          title: "¿Desea continuar?",
          text: "Se timbrará la liquidación y el finiquito de este empleado.",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: '<span class="verticalCenter">Confirmar</span>',
          cancelButtonText: '<span class="verticalCenter">Cerrar</span>',
          reverseButtons: true,
          allowOutsideClick: false,
        })
        .then((result) => {
          if (result.isConfirmed) {

            $("#timbrarLiquidacion").prop("disabled", true);

            $.ajax({
              type: 'POST',
              url: 'functions/facturar_finiquito_liquidacion.php',
              data: {
                idFiniquito: idFiniquito,
                metodo_pago_id: metodo_pago_id,
                tipoMovimiento: tipoMovimiento,
                csr_token_UT5JP : token,
                idRelacionados : IDs
              },
              success: function(data) {

                var datos = JSON.parse(data);

                if (datos.estatus == "fallo-general") {

                  Lobibox.notify("error", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/warning_circle.svg',
                    msg: datos.mensaje
                  });
                  $("#timbrarLiquidacion").prop("disabled", false);

                }

                if (datos.estatus == "fallo-estadotimbrado") {

                  Lobibox.notify("error", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/warning_circle.svg',
                    msg: "Ya timbraste el finiquito del empleado."
                  });

                  fechaTimbrado = datos.fechaTimbrado;
                  idFacturaFiniquitoGeneral = datos.idFactura;
                  idFacturaLiquidacionGeneral = datos.idFacturaLiquidacion;
                  mostrarOpcionesFactura(datos.idFactura, datos.idFacturaLiquidacion);  
                  $("#timbrarLiquidacion").prop("disabled", false);

                  let nuevoTexto =     '<div class="col-lg-4">' +
                                          '<button type="button" class="btn btn-custom btn-custom--border-blue" id="exportarExcel">Exportar Excel</button>' +
                                        '</div>' +
                                        '<div class="col-lg-4">' +
                                          '<label for="">Método de pago:</label><br>' +
                                          '<span class="btn btn-custom btn-custom--blue">' + datos.metodo_pago_act + '</span>' +
                                        '</div>' +
                                        '<div class="col-lg-4">' +
                                          '<button type="button" class="btn btn-custom btn-custom--border-blue" id="exportarPDF">Exportar PDF</button>' +
                                        '</div> ';
                  $("#actualizarMetodoPago").html(nuevoTexto);

                }

                if (datos.estatusLiquidacion == "fallo-estadotimbrado") {

                  Lobibox.notify("error", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/warning_circle.svg',
                    msg: "Ya timbraste la liquidación del empleado."
                  });
                  idFacturaFiniquitoGeneral = datos.idFactura;
                  idFacturaLiquidacionGeneral = datos.idFacturaLiquidacion;
                  fechaTimbrado = datos.fechaTimbrado;
                  mostrarOpcionesFactura(datos.idFactura, datos.idFacturaLiquidacion);  
                  $("#timbrarLiquidacion").prop("disabled", false);

                }

                if (datos.estatus == "fallo-isr") {

                  Lobibox.notify("error", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/warning_circle.svg',
                    msg: "No puedes timbrar ninguna nómina, te falta la clave del ISR."
                  });
                  $("#timbrarLiquidacion").prop("disabled", false);

                }

                if (datos.estatus == "exito") {

                  Lobibox.notify("success", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/checkmark.svg',
                    msg: "Se timbro el finiquito del empleado."
                  });
                  idFacturaFiniquitoGeneral = datos.idFactura;
                  idFacturaLiquidacionGeneral = datos.idFacturaLiquidacion;
                  fechaTimbrado = datos.fechaTimbrado;
                  mostrarOpcionesFactura(datos.idFactura, datos.idFacturaLiquidacion);  
                  if(tipoMovimiento == 1){
                    $('#eliminarFiniquito').attr("id","eliminarFiniquito2");
                  }
                  $("#timbrarNominaIndividual").prop("disabled", false);

                  let nuevoTexto =     '<div class="col-lg-4">' +
                                          '<button type="button" class="btn btn-custom btn-custom--border-blue" id="exportarExcel">Exportar Excel</button>' +
                                        '</div>' +
                                        '<div class="col-lg-4">' +
                                          '<label for="">Método de pago:</label><br>' +
                                          '<span class="btn btn-custom btn-custom--blue">' + metodo_pago + '</span>' +
                                        '</div>' +
                                        '<div class="col-lg-4">' +
                                          '<button type="button" class="btn btn-custom btn-custom--border-blue" id="exportarPDF">Exportar PDF</button>' +
                                        '</div> ';
                  $("#actualizarMetodoPago").html(nuevoTexto);

                  if (datos.estatus_email == "exito") {

                    Lobibox.notify("success", {
                      size: 'mini',
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: 'center top', //or 'center bottom'
                      icon: false,
                      img: '../../img/timdesk/checkmark.svg',
                      msg: "Se envio el recibo del finiquito al correo registrado del empleado."
                    });

                  }

                  if (datos.estatus_email == "fallo") {
                    Lobibox.notify("error", {
                      size: 'mini',
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: 'center top', //or 'center bottom'
                      icon: false,
                      img: '../../img/timdesk/warning_circle.svg',
                      msg: "No se pudo enviar el email del finiquito."
                    });
                  }

                }

                if (datos.estatusLiquidacion == "exito") {

                  Lobibox.notify("success", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/checkmark.svg',
                    msg: "Se timbro la liquidación del empleado."
                  });
                  idFacturaFiniquitoGeneral = datos.idFactura;
                  idFacturaLiquidacionGeneral = datos.idFacturaLiquidacion;
                  fechaTimbrado = datos.fechaTimbrado;
                  mostrarOpcionesFactura(datos.idFactura, datos.idFacturaLiquidacion); 
                  if(datos.idFacturaFiniquito != '' && datos.idFacturaLiquidacion == ''){
                    $('#eliminarLiquidacion_unico').attr("id","eliminarLiquidacion_solofiniquito");
                  }
                  if(datos.idFacturaFiniquito != '' && datos.idFacturaLiquidacion != ''){
                    $('#eliminarLiquidacion_unico').attr("id","eliminarLiquidacion");
                  }
                  
                  $("#timbrarNominaIndividual").prop("disabled", false);
                  let nuevoTexto =     '<div class="col-lg-4">' +
                                          '<button type="button" class="btn btn-custom btn-custom--border-blue" id="exportarExcel">Exportar Excel</button>' +
                                        '</div>' +
                                        '<div class="col-lg-4">' +
                                          '<label for="">Método de pago:</label><br>' +
                                          '<span class="btn btn-custom btn-custom--blue">' + metodo_pago + '</span>' +
                                        '</div>' +
                                        '<div class="col-lg-4">' +
                                          '<button type="button" class="btn btn-custom btn-custom--border-blue" id="exportarPDF">Exportar PDF</button>' +
                                        '</div> ';
                  $("#actualizarMetodoPago").html(nuevoTexto);

                  if (datos.estatus_email_liquidacion == "exito") {

                    Lobibox.notify("success", {
                      size: 'mini',
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: 'center top', //or 'center bottom'
                      icon: false,
                      img: '../../img/timdesk/checkmark.svg',
                      msg: "Se envio el recibo de la liquidación al correo registrado del empleado."
                    });

                  }

                  if (datos.estatus_email_liquidacion == "fallo") {
                    Lobibox.notify("error", {
                      size: 'mini',
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: 'center top', //or 'center bottom'
                      icon: false,
                      img: '../../img/timdesk/warning_circle.svg',
                      msg: "No se pudo enviar el email de la liquidación."
                    });
                  }

                } 

                if (datos.estatus == "fallo" || datos.estatusLiquidacion == "fallo") {

                  Lobibox.notify("error", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/warning_circle.svg',
                    msg: "Ocurrio un error, vuelva intentarlo."
                  });
                  $("#timbrarLiquidacion").prop("disabled", false);

                }
              }
            });



          } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
          ) {


          }
        });

    });

    function mostrarOpcionesFactura(idFacturaFiniquito, idFacturaLiquidacion){
      
      idFacturaFiniquito = idFacturaFiniquito || '';
      idFacturaLiquidacion = idFacturaLiquidacion || '';

      let tituloMovimiento = "finiquito", botonEmail = "send_email_finiquito";

      let funcionesFactura =    '<div class="col-12 col-lg-12"><b>Opciones de factura de ' + tituloMovimiento + '</b></div>' +
                                  '<br><br>' +
                                '<div class="col-lg-3">' +
                                  '<a href="functions/download_pdf_finiquito.php?value=' + idFacturaFiniquito + '" class="btn btn-custom btn-custom--blue" target="_blank">Descargar pdf</a>' +
                                '</div>' +
                                '<div class="col-lg-3">' +
                                  '<a href="functions/download_xml_finiquito.php?value=' + idFacturaFiniquito + '" class="btn btn-custom btn-custom--blue" target="_blank">Descargar xml</a>' +
                                '</div>' +
                                '<div class="col-lg-3">' +
                                  '<a href="functions/download_zip_finiquito.php?value=' + idFacturaFiniquito + '" class="btn btn-custom btn-custom--blue" target="_blank">Descargar zip</a>' +
                                 '</div>' +
                                 '<div class="col-lg-3">' + 
                                  '<a href="#" class="btn btn-custom btn-custom--blue" id="' + botonEmail + '" data-toggle="modal" data-target="#enviarFactura">Enviar por email</a>' +
                                '</div>';

      if(tipoMovimiento == 2 && idFacturaLiquidacion != ""){
        tituloMovimiento = "liquidación";
        botonEmail = "send_email_liquidacion";

        funcionesFactura = funcionesFactura + '<br><br>' +
                                '<div class="col-12 col-lg-12"><b>Opciones de factura de ' + tituloMovimiento + '</b></div>' +
                                  '<br><br>' +
                                '<div class="col-lg-3">' +
                                  '<a href="functions/download_pdf_finiquito.php?value=' + idFacturaLiquidacion + '" class="btn btn-custom btn-custom--blue" target="_blank">Descargar pdf</a>' +
                                '</div>' +
                                '<div class="col-lg-3">' +
                                  '<a href="functions/download_xml_finiquito.php?value=' + idFacturaLiquidacion + '" class="btn btn-custom btn-custom--blue" target="_blank">Descargar xml</a>' +
                                '</div>' +
                                '<div class="col-lg-3">' +
                                  '<a href="functions/download_zip_finiquito.php?value=' + idFacturaLiquidacion + '" class="btn btn-custom btn-custom--blue" target="_blank">Descargar zip</a>' +
                                 '</div>' +
                                 '<div class="col-lg-3">' + 
                                  '<a href="#" class="btn btn-custom btn-custom--blue" id="' + botonEmail + '" data-toggle="modal" data-target="#enviarFactura">Enviar por email</a>' +
                                '</div>';

      }

      $("#mostrarTimbrado").html(funcionesFactura);

    }

    $(document).on('click','#regresarFiniquito', function(e) {
      $().redirect("finiquito_liquidacion.php", {
      });

    });

    let tipoEnvioEmail = 2;
    $(document).on('click','#send_email_finiquito', function(e) {
      tipoEnvioEmail = 2;
    });

    $(document).on('click','#send_email_liquidacion', function(e) {
      tipoEnvioEmail = 3;
    });

    $(document).on("click","#btnEnviarFactura",function(){
  
        let token = $("#csr_token_UT5JP").val();
        let idEmpleado = $("#empleado").val();
        let idFactura = "";

        if(tipoEnvioEmail == 2){
          idFactura = idFacturaFiniquitoGeneral;
        }
        else{
          idFactura = idFacturaLiquidacionGeneral;
        }
        
        if(idEmpleado < 1){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Selecciona un empleado para enviar la nómina por email."
          });
          return;
        }

        if($("#txtDestino").val().trim() == ""){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Ingresa un email."
          });
          return;
        }
        let email = $("#txtDestino").val().trim();

        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

        if( !emailReg.test(email) ){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Ingresa un email válido."
          });
          return;
        }

        $("#btnEnviarFactura").prop("disabled", true);

            //enviar datos del trabajor y nomina
            $.ajax({
              type: 'POST',
              url: 'functions/enviarFactura.php',
              data: { 
                      idFactura: idFactura,
                      csr_token_UT5JP : token,
                      destino:email,
                      tipo: tipoEnvioEmail
                    },
              success: function(response){
                if(response == "exito"){
                  Lobibox.notify("success", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/checkmark.svg",
                    msg: "Se ha envíado el archivo pdf y el xml",
                  });
                  $("#btnEnviarFactura").prop("disabled", false);
                  $("#enviarFactura").modal("toggle");
                  
                }

                if(response == "fallo"){
                  Lobibox.notify("error", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/warning_circle.svg',
                    msg: "Ocurrio un error, vuelva intentarlo."
                  });
                  $("#btnEnviarFactura").prop("disabled", false);

                }
                
              },
              error:function(error){
                $("#btnEnviarFactura").prop("disabled", false);
              }
            });

      });

//para eliminar un finiquito sin timbrar
    $(document).on("click","#eliminarFiniquito",function(){

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
              actions: "d-flex justify-content-around",
              confirmButton: "btn-custom btn-custom--border-blue",
              cancelButton: "btn-custom btn-custom--blue",
            },
            buttonsStyling: false,
          });

          swalWithBootstrapButtons
            .fire({
              title: "¿Deseas eliminar el finiquito?",
              text: "Se eliminará el finiquito.",
              icon: "warning",
              showCancelButton: true,
              confirmButtonText: '<span class="verticalCenter">Confirmar</span>',
              cancelButtonText: '<span class="verticalCenter">Cerrar</span>',
              reverseButtons: true,
              allowOutsideClick: false,
            })
            .then((result) => {
              if (result.isConfirmed) {

                $("#eliminarFiniquito").prop("disabled", true);
                let idFiniquito = $("#idFiniquito").val();
                let mensaje = "";
                let token = $("#csr_token_UT5JP").val();
                let motivoCancelacion = 0;

                $.ajax({
                  type: 'POST',
                  url: 'functions/cancelarFacturaFiniquitoLiquidacion.php',
                  data: { 
                          idFiniquito: idFiniquito,
                          csr_token_UT5JP : token,
                          motivoCancelacion: motivoCancelacion,
                          tipoEliminacion: 5, 
                        },
                  success: function(r) {
                    
                    var datos = JSON.parse(r);
                    let mensaje = "";

                    if (datos.estatus == "exito") {

                      Lobibox.notify("success", {
                        size: 'mini',
                        rounded: true,
                        delay: 3000,
                        delayIndicator: false,
                        position: 'center top', //or 'center bottom'
                        icon: false,
                        img: '../../img/timdesk/checkmark.svg',
                        msg: "Se elimino el finiquito."
                      });

                      $("#eliminarFiniquito").css("display","none");
                      $("#mostrarTimbrado").css("display","none");
                      setTimeout(
                      function() 
                      {
                        $().redirect("finiquito_liquidacion.php", {
                        });
                      }, 3000);
                    } 

                    if (datos.estatus == "fallo") {

                      Lobibox.notify("error", {
                        size: 'mini',
                        rounded: true,
                        delay: 3000,
                        delayIndicator: false,
                        position: 'center top', //or 'center bottom'
                        icon: false,
                        img: '../../img/timdesk/warning_circle.svg',
                        msg: "Ocurrio un error, vuelva intentarlo."
                      });
                      $("#eliminarFiniquito").prop("disabled", false);

                    }

                  },
                  error: function(){
                    Lobibox.notify("error", {
                      size: "mini",
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: "center top",
                      icon: true,
                      img: "../../img/timdesk/notificacion_error.svg",
                      msg: "¡Ocurrio un error, intentalo nuevamente!",
                    });
                    $("#eliminarFiniquito").prop("disabled", false);
                  }
                });


              } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
              ) {


              }
            });

    });

    $(document).on("click","#eliminarLiquidacion_solofiniquito",function(){

          const swalWithBootstrapButtons = Swal.mixin({
              customClass: {
                actions: "d-flex justify-content-around",
                confirmButton: "btn-custom btn-custom--border-blue",
                cancelButton: "btn-custom btn-custom--blue",
              },
              buttonsStyling: false,
            });

            swalWithBootstrapButtons
              .fire({
                title: "¿Deseas eliminar la liquidación?",
                text: "Se inactivará la liquidación, se cancelará la factura del finiquito y se volverá a activar el empleado.",
                icon: "warning",
                <?php echo $opciones;?>
                showCancelButton: true,
                confirmButtonText: '<span class="verticalCenter">Confirmar</span>',
                cancelButtonText: '<span class="verticalCenter">Cerrar</span>',
                reverseButtons: true,
                allowOutsideClick: false,
              })
              .then((result) => {
                if (result.isConfirmed) {

                  $("#eliminarLiquidacion_solofiniquito").prop("disabled", true);
                  let idFiniquito = $("#idFiniquito").val();
                  let mensaje = "";
                  let token = $("#csr_token_UT5JP").val();
                  let motivoCancelacion = result.value;

                  $.ajax({
                    type: 'POST',
                    url: 'functions/cancelarFacturaFiniquitoLiquidacion.php',
                    data: { 
                            idFiniquito: idFiniquito,
                            tipoEliminacion : 7,
                            csr_token_UT5JP : token,
                            motivoCancelacion: motivoCancelacion
                          },
                    success: function(r) {
                      
                      var datos = JSON.parse(r);

                      if (datos.estatus == "exito") {

                        Lobibox.notify("success", {
                          size: 'mini',
                          rounded: true,
                          delay: 3000,
                          delayIndicator: false,
                          position: 'center top', //or 'center bottom'
                          icon: false,
                          img: '../../img/timdesk/checkmark.svg',
                          msg: datos.mensaje
                        });

                        let etiquetado = '<div class="col-lg-6">' +
                                           '<button type="button" class="btn btn-custom btn-custom--border-blue" id="regresarFiniquito">Regresar</button>' +
                                          '</div>' +
                                          '<div class="col-lg-6">' +
                                            '<span class="btn btn-custom btn-custom--red">Liquidación cancelada</span>' +
                                          '</div>';
                        $("#mostrarTimbrado").html(etiquetado);

                      } 

                      if (datos.estatus == "fallo-cancelado") {

                        Lobibox.notify("error", {
                          size: 'mini',
                          rounded: true,
                          delay: 3000,
                          delayIndicator: false,
                          position: 'center top', //or 'center bottom'
                          icon: false,
                          img: '../../img/timdesk/warning_circle.svg',
                          msg: "La factura del finiquito ya fue cancelada anteriormente."
                        });
                        $("#eliminarLiquidacion_solofiniquito").prop("disabled", false);

                      }

                      if (datos.estatus == "fallo") {

                        Lobibox.notify("error", {
                          size: 'mini',
                          rounded: true,
                          delay: 3000,
                          delayIndicator: false,
                          position: 'center top', //or 'center bottom'
                          icon: false,
                          img: '../../img/timdesk/warning_circle.svg',
                          msg: "Ocurrio un error, vuelva intentarlo."
                        });
                        $("#eliminarLiquidacion_solofiniquito").prop("disabled", false);

                      }

                  },
                  error: function(){
                    Lobibox.notify("error", {
                      size: "mini",
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: "center top",
                      icon: true,
                      img: "../../img/timdesk/notificacion_error.svg",
                      msg: "¡Ocurrio un error, intentalo nuevamente!",
                    });
                    $("#eliminarLiquidacion_solofiniquito").prop("disabled", false);
                  }
                });


              } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
              ) {


              }
            });

    });

    $(document).on("click","#eliminarLiquidacion_unico",function(){

          const swalWithBootstrapButtons = Swal.mixin({
              customClass: {
                actions: "d-flex justify-content-around",
                confirmButton: "btn-custom btn-custom--border-blue",
                cancelButton: "btn-custom btn-custom--blue",
              },
              buttonsStyling: false,
            });

            swalWithBootstrapButtons
              .fire({
                title: "¿Deseas eliminar la liquidación?",
                text: "Se eliminará la liquidación y se volverá a activar el empleado.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: '<span class="verticalCenter">Confirmar</span>',
                cancelButtonText: '<span class="verticalCenter">Cerrar</span>',
                reverseButtons: true,
                allowOutsideClick: false,
              })
              .then((result) => {
                if (result.isConfirmed) {

                  $("#eliminarLiquidacion_unico").prop("disabled", true);
                  let idFiniquito = $("#idFiniquito").val();
                  let mensaje = "";
                  let token = $("#csr_token_UT5JP").val();
                  let motivoCancelacion = 0;

                  $.ajax({
                    type: 'POST',
                    url: 'functions/cancelarFacturaFiniquitoLiquidacion.php',
                    data: { 
                            idFiniquito: idFiniquito,
                            tipoEliminacion : 8,
                            csr_token_UT5JP : token,
                            motivoCancelacion: motivoCancelacion
                          },
                    success: function(r) {
                      
                      var datos = JSON.parse(r);

                      if (datos.estatus == "exito") {

                        Lobibox.notify("success", {
                          size: 'mini',
                          rounded: true,
                          delay: 3000,
                          delayIndicator: false,
                          position: 'center top', //or 'center bottom'
                          icon: false,
                          img: '../../img/timdesk/checkmark.svg',
                          msg: datos.mensaje
                        });

                        let etiquetado = '<div class="col-lg-6">' +
                                           '<button type="button" class="btn btn-custom btn-custom--border-blue" id="regresarFiniquito">Regresar</button>' +
                                          '</div>' +
                                          '<div class="col-lg-6">' +
                                            '<span class="btn btn-custom btn-custom--red">Liquidación cancelada</span>' +
                                          '</div>';
                                          
                        $("#cancelarTimbradoMostrar").html(etiquetado);
                        $("#mostrarTimbrado").css("display","none");
                        setTimeout(
                          function() 
                          {
                            $().redirect("finiquito_liquidacion.php", {
                            });
                        }, 3000);

                      } 

                      if (datos.estatus == "fallo") {

                        Lobibox.notify("error", {
                          size: 'mini',
                          rounded: true,
                          delay: 3000,
                          delayIndicator: false,
                          position: 'center top', //or 'center bottom'
                          icon: false,
                          img: '../../img/timdesk/warning_circle.svg',
                          msg: "Ocurrio un error, vuelva intentarlo."
                        });
                        $("#eliminarLiquidacion_unico").prop("disabled", false);

                      }

                  },
                  error: function(){
                    Lobibox.notify("error", {
                      size: "mini",
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: "center top",
                      icon: true,
                      img: "../../img/timdesk/notificacion_error.svg",
                      msg: "¡Ocurrio un error, intentalo nuevamente!",
                    });
                    $("#eliminarLiquidacion_unico").prop("disabled", false);
                  }
                });


              } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
              ) {


              }
            });

    });

$(document).on("click","#eliminarLiquidacion",function(){

          const swalWithBootstrapButtons = Swal.mixin({
              customClass: {
                actions: "d-flex justify-content-around",
                confirmButton: "btn-custom btn-custom--border-blue",
                cancelButton: "btn-custom btn-custom--blue",
              },
              buttonsStyling: false,
            });

            swalWithBootstrapButtons
              .fire({
                title: "¿Deseas eliminar la liquidación?",
                text: "Se inactivará la liquidación, se cancelará la factura del finiquito y la liquidación, y se volverá a activar el empleado.",
                icon: "warning",
                <?php echo $opciones;?>
                showCancelButton: true,
                confirmButtonText: '<span class="verticalCenter">Confirmar</span>',
                cancelButtonText: '<span class="verticalCenter">Cerrar</span>',
                reverseButtons: true,
                allowOutsideClick: false,
              })
              .then((result) => {
                if (result.isConfirmed) {

                  $("#eliminarLiquidacion").prop("disabled", true);
                  let idFiniquito = $("#idFiniquito").val();
                  let mensaje = "";
                  let token = $("#csr_token_UT5JP").val();
                  let motivoCancelacion = result.value;

                  $.ajax({
                    type: 'POST',
                    url: 'functions/cancelarFacturaFiniquitoLiquidacion.php',
                    data: { 
                            idFiniquito: idFiniquito,
                            tipoEliminacion : 9,
                            csr_token_UT5JP : token,
                            motivoCancelacion: motivoCancelacion
                          },
                    success: function(r) {
                      
                      var datos = JSON.parse(r);
                      if (datos.estatus == "exito" ) {

                        Lobibox.notify("success", {
                          size: 'mini',
                          rounded: true,
                          delay: 3000,
                          delayIndicator: false,
                          position: 'center top', //or 'center bottom'
                          icon: false,
                          img: '../../img/timdesk/checkmark.svg',
                          msg: datos.mensaje
                        });
                     }

                     if (datos.estatus_liquidacion == "exito") {

                        Lobibox.notify("success", {
                          size: 'mini',
                          rounded: true,
                          delay: 3000,
                          delayIndicator: false,
                          position: 'center top', //or 'center bottom'
                          icon: false,
                          img: '../../img/timdesk/checkmark.svg',
                          msg: datos.mensaje_liquidacion
                        });

                        let etiquetado = '<div class="col-lg-6">' +
                                           '<button type="button" class="btn btn-custom btn-custom--border-blue" id="regresarFiniquito">Regresar</button>' +
                                          '</div>' +
                                          '<div class="col-lg-6">' +
                                            '<span class="btn btn-custom btn-custom--red">Liquidación cancelada</span>' +
                                          '</div>';
                        $("#cancelarTimbradoMostrar").html(etiquetado);

                      } 

                      if (datos.estatus == "fallo-cancelado-finiquito") {

                        Lobibox.notify("error", {
                          size: 'mini',
                          rounded: true,
                          delay: 3000,
                          delayIndicator: false,
                          position: 'center top', //or 'center bottom'
                          icon: false,
                          img: '../../img/timdesk/warning_circle.svg',
                          msg: "No se canceló la factura del finiquito, para cancelar la factura de liquidación es necesario que se cancele primero la factura del finiquito."
                        });
                        $("#eliminarLiquidacion_solofiniquito").prop("disabled", false);

                      }

                      if (datos.estatus == "fallo-cancelado") {

                        Lobibox.notify("error", {
                          size: 'mini',
                          rounded: true,
                          delay: 3000,
                          delayIndicator: false,
                          position: 'center top', //or 'center bottom'
                          icon: false,
                          img: '../../img/timdesk/warning_circle.svg',
                          msg: "La factura del finiquito ya fue cancelada anteriormente."
                        });
                        $("#eliminarLiquidacion_solofiniquito").prop("disabled", false);

                      }

                      if (datos.estatus == "fallo") {

                        Lobibox.notify("error", {
                          size: 'mini',
                          rounded: true,
                          delay: 3000,
                          delayIndicator: false,
                          position: 'center top', //or 'center bottom'
                          icon: false,
                          img: '../../img/timdesk/warning_circle.svg',
                          msg: "Ocurrio un error, vuelva intentarlo."
                        });
                        $("#eliminarLiquidacion_solofiniquito").prop("disabled", false);

                      }

                  },
                  error: function(){
                    Lobibox.notify("error", {
                      size: "mini",
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: "center top",
                      icon: true,
                      img: "../../img/timdesk/notificacion_error.svg",
                      msg: "¡Ocurrio un error, intentalo nuevamente!",
                    });
                    $("#eliminarLiquidacion_solofiniquito").prop("disabled", false);
                  }
                });


              } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
              ) {


              }
            });

    });

    
    $(document).on("click","#eliminarFiniquito2",function(){

          let tituloMovimiento = "finiquito";

          const swalWithBootstrapButtons = Swal.mixin({
              customClass: {
                actions: "d-flex justify-content-around",
                confirmButton: "btn-custom btn-custom--border-blue",
                cancelButton: "btn-custom btn-custom--blue",
              },
              buttonsStyling: false,
            });

            swalWithBootstrapButtons
              .fire({
                title: "¿Deseas eliminar el " +  tituloMovimiento + "?",
                text: "Se eliminará el " +  tituloMovimiento + ", se cancelará la factura del finiquito y se volverá a activar el empleado.",
                icon: "warning",
                <?php echo $opciones;?>
                showCancelButton: true,
                confirmButtonText: '<span class="verticalCenter">Confirmar</span>',
                cancelButtonText: '<span class="verticalCenter">Cerrar</span>',
                reverseButtons: true,
                allowOutsideClick: false,
              })
              .then((result) => {
                if (result.isConfirmed) {

                  $("#eliminarFiniquito2").prop("disabled", true);
                  let idFiniquito = $("#idFiniquito").val();
                  let mensaje = "";
                  let token = $("#csr_token_UT5JP").val();
                  let motivoCancelacion = result.value;

                  $.ajax({
                    type: 'POST',
                    url: 'functions/cancelarFacturaFiniquitoLiquidacion.php',
                    data: { 
                            idFiniquito: idFiniquito,
                            csr_token_UT5JP : token,
                            motivoCancelacion: motivoCancelacion,
                            tipoEliminacion: 6
                          },
                    success: function(r) {
                      
                      var datos = JSON.parse(r);
                      let mensaje = "";

                      if (datos.estatus == "exito") {

                        Lobibox.notify("success", {
                          size: 'mini',
                          rounded: true,
                          delay: 3000,
                          delayIndicator: false,
                          position: 'center top', //or 'center bottom'
                          icon: false,
                          img: '../../img/timdesk/checkmark.svg',
                          msg: datos.mensaje
                        });

                        let etiquetado = '<div class="col-12 col-lg-6">' +
                                            '<button type="button" class="btn btn-custom btn-custom--border-blue" id="regresarFiniquito">Regresar</button>' +
                                          '</div>' +
                                          '<div class="col-12 col-lg-6">' +
                                            '<button type="button" class="btn btn-custom btn-custom--red">Finiquito cancelado</button>' +
                                         '</div>';
                        $("#cancelarTimbradoMostrar").html(etiquetado);

                      } 
                      if (datos.estatus == "fallo") {

                        Lobibox.notify("error", {
                          size: 'mini',
                          rounded: true,
                          delay: 3000,
                          delayIndicator: false,
                          position: 'center top', //or 'center bottom'
                          icon: false,
                          img: '../../img/timdesk/warning_circle.svg',
                          msg: "Ocurrio un error, vuelva intentarlo."
                        });
                        $("#eliminarFiniquito2").prop("disabled", false);

                      }

                      if (datos.estatus == "fallo-cancelado") {

                        Lobibox.notify("error", {
                          size: 'mini',
                          rounded: true,
                          delay: 3000,
                          delayIndicator: false,
                          position: 'center top', //or 'center bottom'
                          icon: false,
                          img: '../../img/timdesk/warning_circle.svg',
                          msg: "La factura ya esta cancelada."
                        });

                        let etiquetado = '<div class="col-12 col-lg-6">' +
                                            '<button type="button" class="btn btn-custom btn-custom--border-blue" id="regresarFiniquito">Regresar</button>' +
                                          '</div>' +
                                          '<div class="col-12 col-lg-6">' +
                                            '<button type="button" class="btn btn-custom btn-custom--red">Finiquito cancelado</button>' +
                                         '</div>';
                        $("#cancelarTimbradoMostrar").html(etiquetado);
                        $("#eliminarFiniquito2").prop("disabled", false);
                      }

                  },
                  error: function(){
                    Lobibox.notify("error", {
                      size: "mini",
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: "center top",
                      icon: true,
                      img: "../../img/timdesk/notificacion_error.svg",
                      msg: "¡Ocurrio un error, intentalo nuevamente!",
                    });
                    $("#eliminarFiniquito2").prop("disabled", false);
                  }
                });


              } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
              ) {


              }
            });

    });

    $(document).on("click","#exportarExcel",function(){
        let idFiniquito = $("#idFiniquito").val();
        let token = $("#csr_token_UT5JP").val();
        $().redirect("functions/exportar_finiquito_liquidacion_excel.php", {
          idFiniquito: idFiniquito,
          csr_token_UT5JP: token
        });

        Lobibox.notify("success", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/checkmark.svg',
          msg: "Excel generado."
        });
        
    });

    $(document).on("click","#exportarPDF",function(){
        let idFiniquito = $("#idFiniquito").val();
        let token = $("#csr_token_UT5JP").val();
        $().redirect("functions/exportar_finiquito_liquidacion_pdf.php", {
          idFiniquito: idFiniquito,
          csr_token_UT5JP: token
        });

        Lobibox.notify("success", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/checkmark.svg',
          msg: "PDF generado."
        });
        
    });

    $(document).on("change","#facturas_relacionadas_id",function(){
        let valor = $("#facturas_relacionadas_id option:selected").val();
        let valores=valor.split('&');
        let url = 'functions/download_pdf_relacionado.php?value=' + valores[3] + '&tipo=' + valores[1];
        let url2 = 'functions/download_xml_relacionado.php?value=' + valores[3] + '&tipo=' + valores[1];

        $("#descargarPDF").attr("href", url);
        $("#descargarPDF").attr("target", "_blank");
        $("#descargarXML").attr("href", url2);
        $("#descargarXML").attr("target", "_blank");

    });

    let nuevo = 0;
    $(document).on("click","#agregarRelacion",function(){
        let valor = $("#facturas_relacionadas_id option:selected").val();
        let valores=valor.split('&');

        if(valores[0] == "0"){
          return;
        }

        if($('#' + valores[1] +  '_' + valores[2]).length){
          
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/notificacion_error.svg",
              msg: "El cfdi ya fue agregado.",
            });
            return;

        }

        if(nuevo == 0){
          $("#cfdiRelacionado").html("");
          nuevo = 1;
        }

        let tipo = "";
        if(valores[1] == 1){
          tipo = "Finiquito";
        }
        else{
          tipo = "Liquidación";
        }

        let nuevoCFDI = "<div class='row mostrarCFDIRelacionado' id='" + valores[1]  + "_" + valores[2] + "' style='margin: 0 0 5px 0;'><div class='row col-12 col-lg-12'>" +
                            "<div class='col-lg-6'>" + valores[0] + " - " + tipo + "</div>" + 
                            "<div class='col-lg-6'><a href='functions/download_pdf_relacionado.php?value=" + valores[3] + "&tipo=" + valores[1] + "' class='btn btn-custom btn-custom--border-blue' target='_blank'> <i class='fas fa-file-invoice'></i> PDF </a> <a href='functions/download_xml_relacionado.php?value=" + valores[3] + "&tipo=" + valores[1] + "' class='btn btn-custom btn-custom--border-blue' target='_blank'> <i class='far fa-file-alt'></i> XML </a>" +  
                            "<button type='button' class='btn btn-custom btn-custom--red' id='deleteCFDIRelacionado' style='position:relative; left:4px;'> <i class='fas fa-trash-alt'></i> </button></div>" +
                         "</div></div>";
        $("#cfdiRelacionado").append(nuevoCFDI);

    });

    $(document).on("click","#deleteCFDIRelacionado",function(){
        let id = $(this).closest('.mostrarCFDIRelacionado').attr('id');

        $("#" + id).fadeOut(500, function(){ $("#" + id).remove(); });
    });


    $(document).on("click","#mostrarCalculoImpuestos", function(){
        
        let token = $("#csr_token_UT5JP").val(); 
        let value = $(this).attr("value");

        //valores para calcular isr de salarios/vacaciones
        let totalVacacionesGravado = $("#lblVacacionesGravado").html();
        let totalSalario = $("#lblSalarioDevengadoGravado").html();

        //isr aguinaldo
        let aguinaldoGravado = $("#lblAguinaldoGravado").html();

        //isr prima vacacional 
        let primaVacacionalGravado = $("#lblPrimaVacacionalGravado").html();
        
        let sueldoMensual = '<?php echo $sueldoMensual; ?>';

        //IMSS
        let fechaPeriodoIni = $("#txtFechaIniP").val();
        let fechaPeriodoFin = $("#txtFechaFinP").val();
        let idEmpleado = '<?php echo $idEmpleado; ?>';

        //Indemnizacion
        let indeminizacionGravado = $("#lblIndeminizacionGravado").html();
        $.ajax({
          type: 'POST',
          url: 'functions/calculoUnicoImpuestos.php',
          data: { 
                  totalVacacionesGravado: totalVacacionesGravado,
                  totalSalario: totalSalario,
                  aguinaldoGravado: aguinaldoGravado,
                  csr_token_UT5JP: token,
                  tipoMovimiento: value,
                  sueldoMensual: sueldoMensual,
                  primaVacacionalGravado: primaVacacionalGravado,
                  fechaPeriodoIni: fechaPeriodoIni,
                  fechaPeriodoFin: fechaPeriodoFin,
                  idEmpleado: idEmpleado,
                  indeminizacionGravado: indeminizacionGravado
                },
          success: function(r) {

            var datos = JSON.parse(r);

            if(datos.estatus == "exito"){
              
              if(value == 1){
                $("#tituloCalculo").html('ISR Salario/Vacaciones');
              }
              if(value == 2){
                $("#tituloCalculo").html('ISR Aguinaldo');
              }
              if(value == 3){
                $("#tituloCalculo").html('ISR Prima Vacacional');
              }

              $("#mostrarCalculoUnico").html(datos.resultado);
              $("#calculo_unico_modal").modal('toggle');

            }

            if(datos.estatus == "fallo"){
              Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "¡Ocurrio un error, intentalo nuevamente!",
              });
            }

          },
          error: function(){
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/notificacion_error.svg",
              msg: "¡Ocurrio un error, intentalo nuevamente!",
            });

          }
        });
        
    });  
    

    var selectMetodoPago = new SlimSelect({
      select: '#metodo_pago_id',
      deselectLabel: '<span class="">✖</span>'
    });

    var facturasRelacionadas = new SlimSelect({
      select: '#facturas_relacionadas_id',
      deselectLabel: '<span class="">✖</span>'
    });

     new Cleave('#txtSalariosDevengados', {
        numeral: true,
        numeralDecimalMark: '.',
        delimiter: ','
      });

     new Cleave('#txtOtros', {
        numeral: true,
        numeralDecimalMark: '.',
        delimiter: ','
      });

     new Cleave('#txtGratificacion', {
        numeral: true,
        numeralDecimalMark: '.',
        delimiter: ','
      });
  </script>
  <script>
  var ruta = "../";
  </script>
  <script>
    loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
    setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
  </script>
</body>

</html>
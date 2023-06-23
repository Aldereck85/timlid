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
                    <select class="form-control" id="empleado">
                      <option disabled selected>Selecciona empleado</option>
                      <?php
                        $stmt = $conn->prepare('SELECT e.PKEmpleado,e.Nombres, e.PrimerApellido, e.SegundoApellido FROM empleados as e INNER JOIN datos_laborales_empleado as dle ON dle.FKEmpleado = e.PKEmpleado WHERE e.is_generic = 0 AND e.empresa_id = '.$_SESSION['IDEmpresa']);
                        $stmt->bindValue(":idsucursal", $row_datos_nomina['sucursal_id']);
                        $stmt->bindValue(":idperiodo", $row_datos_nomina['periodo_id']);
                        $stmt->execute();
                        $empleados = $stmt->fetchAll();
                        foreach ($empleados as $emp) {
                      ?>
                          <option value="<?=$emp['PKEmpleado']?>"><?php echo $emp["Nombres"]." ".$emp["PrimerApellido"]." ".$emp["SegundoApellido"]; ?></option>
                      <?php
                        }
                      ?>
                    </select>
                  </div>
                  <div class="row col-12 col-lg-6" id="timbradoNomina" style="display: block;">
                      <label>Tipo:</label>
                      <div class="row" style="position: relative; left: 5%;">
                        <div class="row col-12 col-lg-6" style="position: relative; top: 7px;">
                          <input type="radio" id="tipo" name="tipo" value="1" class="tipoFinal form-control" style="width:20px;height: 20px;" checked>
                          <label for="Finiquito" style="position:relative;left: 1%;">  Finiquito</label>
                        </div>
                        <div class="row col-12 col-lg-6" style="position: relative; top: 7px;">
                          <input type="radio" id="tipo" name="tipo" value="2" class="tipoFinal form-control" style="width:20px;height: 20px;">
                          <label for="Liquidacion" style="position:relative;left: 1%;">  Liquidación</label>
                        </div>
                      </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-lg-12">
                      <center>
                            <button id="seleccionarEmpleado" class="btn btn-custom btn-custom--border-blue" style="position: relative; top:23px;">Seleccionar empleado</button>
                      </center>
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
                    <label><b>Nombre:</b> <span id="NombreEmpleado"></span></label><br>
                    <label><b>NSS:</b> <span id="NSS"></span></label><br>
                    <label><b>RFC:</b> <span id="RFC"></span></label><br>
                    <label><b>Salario diario:</b> <span id="SalarioDiarioS"></span></label><br>
                  </div>
                  <div class="col-lg-4">

                  </div>
                  <div class="col-lg-4">

                    <label><b>Turno:</b> <span id="Turno"></span></label><br>
                    <label><b>Puesto:</b> <span id="Puesto"></span></label><br>
                    <label><b>Salario: </b><span id="SalarioSpan"></span></label><br>
                    <label><b>Salario diario integrado:</b> <span id="SalarioDiarioIntegradoS"></span></label><br>
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
                        <input type="date" class="form-control" name="txtFechaIngreso" id="txtFechaIngreso" readonly>
                      </div>
                      <div class="col-12 col-lg-6">
                        <label for="txtFechaSalida">Fecha de salida:</label>                
                        <input type="date" class="form-control" name="txtFechaSalida" id="txtFechaSalida" required readonly>
                      </div>
                    </div>
                  </div>
                  <br>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-12 col-lg-4">
                        <label for="txtDiasAguinaldo">Días de aguinaldo:</label>                
                        <input type="number" class="form-control" name="txtDiasAguinaldo" id="txtDiasAguinaldo" readonly>
                      </div>
                      <div class="col-12 col-lg-4">
                        <label for="txtProporcionalesAguinaldo">Proporcionales:</label>                
                        <input type="text" class="form-control" name="txtProporcionalesAguinaldo" id="txtProporcionalesAguinaldo" readonly>
                      </div>
                      <div class="col-12 col-lg-4">
                        <label for="txtAntiguedad">Antigüedad:</label>                
                        <input type="number" class="form-control" name="txtAntiguedad" id="txtAntiguedad" readonly>
                      </div>
                    </div>
                  </div>
                  <br>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-12 col-lg-3">
                        <label for="txtDiasVacaciones">Días de vacaciones:</label>                
                        <input type="number" class="form-control" name="txtDiasVacaciones" id="txtDiasVacaciones" readonly>
                      </div>
                      <div class="col-12 col-lg-3">
                        <label for="txtProporcionalesVacaciones">Proporcionales:</label>                
                        <input type="text" class="form-control" name="txtProporcionalesVacaciones" id="txtProporcionalesVacaciones" readonly>
                      </div>
                      <div class="col-12 col-lg-3">
                        <label for="txtDiasRestantes">Restantes:</label>                
                        <input type="number" class="form-control" name="txtDiasRestantes" id="txtDiasRestantes" readonly>
                      </div>
                      <div class="col-12 col-lg-3">
                        <label for="txtDiasPagar">A pagar:</label>                
                        <input type="number" class="form-control" name="txtDiasPagar" id="txtDiasPagar" readonly>
                      </div>
                    </div>
                  </div>
                  <br>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-12 col-lg-4">
                        <label for="txtSalariosDevengados">Salarios devengados:</label>                
                        <input type="number" class="form-control" name="txtSalariosDevengados" id="txtSalariosDevengados" readonly>
                      </div>
                      <div class="col-12 col-lg-4">
                        <label for="txtFechaIniP">Periodo - Fecha inicial:</label>                
                        <input type="date" class="form-control" name="txtFechaIniP" id="txtFechaIniP" readonly>
                      </div>
                      <div class="col-12 col-lg-4">
                        <label for="txtFechaFinP">Periodo - Fecha final:</label>                
                        <input type="date" class="form-control" name="txtFechaFinP" id="txtFechaFinP" readonly>
                      </div>
                    </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-12 text-center">
                      <button type="button" class="btn btn-custom btn-custom--border-blue" id="calcularFiniquito" disabled>Calcular</button>
                    </div>
                  </div>
                </form>

                <br><br>
                <div class="form-group">
                    <div class="row">
                      <div id="cargarFiniquito" class="col-lg-12">
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

  <!-- modal ISR -->
  <div id="calculo_isr_modal" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="#" method="POST">
          <div class="modal-header">
            <h4 class="modal-title">Cálculo ISR</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body" id="mostrarUnicoISR">
          </div>
        </form>
      </div>
    </div>
  </div>

  <input type="hidden" name="csr_token_UT5JP" id="csr_token_UT5JP" value="<?=$token?>">
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
  ?>

  <!-- Custom scripts for all pages-->
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>

  <script>
    let idEmpleado = 0;//se usara en todas las funciones
    let diasAguinaldo = 0;
    let diasVacaciones = 0;

    /*let idFactura = "";
    let fechaTimbrado = "";*/
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
          text: "Ya no podra cambiar de empleado a menos de que recargue la página.",
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

                if(datos.estatus == "exito" && datos.estatus_finiquito != "3"){
                    
                    $("#txtFechaIngreso").val(datos.fechaIngreso);
                    selectEmpleado.disable();

                  
                  if(datos.estatus_finiquito == "0"){
                    
                    $("#txtFechaSalida").prop("readonly", false);
                    $("#seleccionarEmpleado").prop("disabled", true);
                    $("#txtSalariosDevengados").prop("readonly", false);
                    $("#txtFechaIniP").prop("readonly", false);
                    $("#txtFechaFinP").prop("readonly", false);
                    $("#calcularFiniquito").prop("disabled", false);

                    $("#NombreEmpleado").html(datos.nombreEmpleado);
                    $("#NSS").html(datos.nss);
                    $("#RFC").html(datos.rfc);
                    $("#Turno").html(datos.Turno);
                    $("#Puesto").html(datos.puesto);
                    $("#SalarioSpan").html(datos.sueldoPeriodo);
                    $("#SalarioDiarioS").html(datos.sueldoDiario);   
                    $("#SalarioDiarioIntegradoS").html(datos.SDI);
                  }
                  

                  if(datos.estatus_finiquito == "1"){
                    $("#txtFechaSalida").html(datos.fecha_salida);
                    $("#txtDiasAguinaldo").val(datos.dias_aguinaldo);
                    $("#txtProporcionalesAguinaldo").val(datos.dias_aguinaldo_proporcionales);
                    $("#txtAntiguedad").val(datos.antiguedad);
                    $("#txtDiasVacaciones").val(datos.dias_vacaciones);
                    $("#txtProporcionalesVacaciones").val(datos.dias_vacaciones_calculo);
                    $("#txtDiasRestantes").val(datos.dias_vacaciones_restantes);
                    $("#txtDiasPagar").val(datos.dias_vacaciones_a_pagar);
                    $("#txtSalariosDevengados").val(datos.salarios_devengados);
                    $("#txtFechaIniP").val(datos.fecha_inicial_salarios_devengados);
                    $("#txtFechaFinP").val(datos.fecha_final_salarios_devengados);

                  }

                }

                if(datos.estatus == "fallo" || datos.estatus_finiquito == "3"){
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
                    fechaPeriodoFin: fechaPeriodoFin
                  },
            success: function(r) {

                $("#cargarFiniquito").html(r);

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
                      subtotalPercepcion: subtotalPercepcion,
                      subtotalExento: subtotalExento,
                      subtotalGravado: subtotalGravado,
                      ISRVacacionesSalarios: ISRVacacionesSalarios,
                      ISRAguinaldo: ISRAguinaldo,
                      ISRPrimaVacacional: ISRPrimaVacacional,
                      TotalPagar: TotalPagar,
                      tipoISRVacacionesSalarios: tipoISRVacacionesSalarios,
                      tipoISRAguinaldo: tipoISRAguinaldo,
                      tipoISRPrimaVacacional: tipoISRPrimaVacacional
                    },
              success: function(r) {

                var datos = JSON.parse(r);

                if(datos.estatus == "exito"){
                  
                  $("#txtFechaIngreso").val(datos.fechaIngreso);
                  $("#txtFechaSalida").prop("readonly", false);
                  selectEmpleado.disable();
                  $("#seleccionarEmpleado").prop("disabled", true);
                  $("#txtSalariosDevengados").prop("readonly", false);
                  $("#txtFechaIniP").prop("readonly", false);
                  $("#txtFechaFinP").prop("readonly", false);
                  $("#calcularFiniquito").prop("disabled", false);

                  $("#NombreEmpleado").html(datos.nombreEmpleado);
                  $("#NSS").html(datos.nss);
                  $("#RFC").html(datos.rfc);
                  $("#Turno").html(datos.Turno);
                  $("#Puesto").html(datos.puesto);
                  $("#SalarioSpan").html(datos.sueldoPeriodo);
                  $("#SalarioDiarioS").html(datos.sueldoDiario);   
                  $("#SalarioDiarioIntegradoS").html(datos.SDI);

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


        alert("aguinaldoPercepcion: " + aguinaldoPercepcion + " aguinaldoExento: " + aguinaldoExento + " aguinaldoGravado: " + aguinaldoGravado + " vacacionesPercepcion: " + vacacionesPercepcion +  " vacacionesExento: " + vacacionesExento + " vacacionesGravado: " + vacacionesGravado);
            //alert("fechaIngreso: " + fechaIngreso + " fechaSalida: " + fechaSalida + " salariosDevengados: " + salariosDevengados + " fechaPeriodoIni: " + fechaPeriodoIni +  " fechaPeriodoFin: " + fechaPeriodoFin );

    });

     var selectEmpleado = new SlimSelect({
      select: '#empleado',
      deselectLabel: '<span class="">✖</span>'
    });

     new Cleave('#txtSalariosDevengados', {
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
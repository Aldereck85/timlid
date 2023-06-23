<?php
session_start();

$jwt_ruta = "../../";
require_once '../jwt.php';

date_default_timezone_set('America/Mexico_City');

if(isset($_POST['idNomina'])){
  $idNomina = $_POST['idNomina'];
}
else{
  header("location: ./");
}
$token = $_SESSION['token_ld10d'];

$tipo_nomina = 1;
require_once("functions/calcularNominaInicial.php");//Ingresa la nomina de cada empleado

if (isset($_SESSION["Usuario"])) {
    require_once '../../include/db-conn.php';

} else {
    header("location:../dashboard.php");
}


///VALIDAR SI LA NOMINA ANTERIOR ESTA AUTORIZADA
$stmt = $conn->prepare('SELECT id, id_nomina_anterior FROM nomina WHERE id = :idNomina');
$stmt->bindValue(':idNomina', $idNomina);
$stmt->execute();

$rowNominaUn = $stmt->fetch();

if($rowNominaUn['id_nomina_anterior'] != 0){
    $idNominaAnterior = $rowNominaUn['id_nomina_anterior'];

    $stmt = $conn->prepare('SELECT autorizada FROM nomina WHERE id = :id_nomina_anterior');
    $stmt->bindValue(':id_nomina_anterior', $idNominaAnterior);
    $stmt->execute();

    $rowNAnt = $stmt->fetch();
    $autorizado = $rowNAnt['autorizada'];

    if($autorizado == 0){
        header("location:../dashboard.php");
    }
}

///comprobar si ya estan todos timbrados
$stmt = $conn->prepare('SELECT COUNT(PKNomina) as total_empleados FROM nomina_empleado WHERE FKNomina = :idNomina AND Exento = 0');
$stmt->bindValue(':idNomina', $idNomina);
$stmt->execute();
$nomina_completa = $stmt->fetch();

$stmt = $conn->prepare('SELECT COUNT(PKNomina)  as total_timbrado FROM nomina_empleado WHERE FKNomina = :idNomina AND Exento = 0 AND estadoTimbrado = 1');
$stmt->bindValue(':idNomina', $idNomina);
$stmt->execute();
$timbrados = $stmt->fetch();

if($timbrados['total_timbrado'] == "" || $timbrados['total_timbrado'] == NULL){
  $total_timbrado = 0;
}else{
  $total_timbrado = $timbrados['total_timbrado'];
}

if($total_timbrado == $nomina_completa['total_empleados'] && $nomina_completa['total_empleados'] > 0){

  $stmt = $conn->prepare(' SELECT estatus FROM nomina WHERE id = :idNomina AND empresa_id = :idEmpresa');
  $stmt->bindValue(':idNomina', $idNomina);
  $stmt->bindValue(':idEmpresa', $_SESSION['IDEmpresa']);
  $stmt->execute();
  $estatus_nomina = $stmt->fetch();

  if($estatus_nomina['estatus'] == 1){
    $stmt = $conn->prepare(' UPDATE nomina SET estatus = 2 WHERE id = :idNomina AND empresa_id = :idEmpresa');
    $stmt->bindValue(':idNomina', $idNomina);
    $stmt->bindValue(':idEmpresa', $_SESSION['IDEmpresa']);
    $stmt->execute();
    $row_datos_nomina['estatus'] = 2;
  }
}
else{
  $stmt = $conn->prepare('SELECT estatus FROM nomina WHERE id = :idNomina AND empresa_id = :idEmpresa');
  $stmt->bindValue(':idNomina', $idNomina);
  $stmt->bindValue(':idEmpresa', $_SESSION['IDEmpresa']);
  $stmt->execute();
  $estatus_nomina = $stmt->fetch();

  if($estatus_nomina['estatus'] == 2){
    $stmt = $conn->prepare(' UPDATE nomina SET estatus = 1 WHERE id = :idNomina AND empresa_id = :idEmpresa');
    $stmt->bindValue(':idNomina', $idNomina);
    $stmt->bindValue(':idEmpresa', $_SESSION['IDEmpresa']);
    $stmt->execute();
    $row_datos_nomina['estatus'] = 1;
  }
}

//SE CARGAN LAS OPCIONES PARA CANCELAR LA FACTURA
$stmt = $conn->prepare('SELECT id, clave, descripcion FROM motivo_cancelacion_factura WHERE clave <> "04" ');
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

$nominaAutorizada = $row_datos_nomina['autorizada'];

$stmt = $conn->prepare('SELECT estadoTimbrado FROM nomina_empleado WHERE FKNomina = :idNomina');
$stmt->bindValue(':idNomina', $idNomina);
$stmt->execute();
$rowNTimbrado = $stmt->fetchAll();

$sumaNTimbrada = 0;
foreach($rowNTimbrado as $t){
    if($t['estadoTimbrado'] == 1){
        $sumaNTimbrada++;
    }
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

  <title>Timlid | Nómina</title>

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
  <link href="../../css/stylesNewTable.css" rel="stylesheet">
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
  <script src="../../js/validaciones.js"></script>
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
    #btnMostrarFonacot, #btnMostrarInfonavit, #btnMostrarPensionAlimenticia {
      display:  block;
    }
    select:invalid {
      color: #b1b1b1 !important;
    }

    .ss-main .ss-single-selected {
      background-color: #fff;
      color: #666;
      outline: 0;
      box-sizing: border-box;
      transition: background-color 0.2s;
    }

    .ss-main .ss-single-selected .ss-arrow span {
      border: solid #006dd9;
      border-width: 0 2px 2px 0;
    }

    .dropdown-menu:after,
    .arrow-up:before {
      border-bottom: 10px solid transparent;
    }
    .size-btn{
      width: 210px;
    }
    .loader {
      position: fixed;
      left: 0px;
      top: 0px;
      width: 100%;
      height: 100%;
      z-index: 9999;
      opacity: 0.6;
      background: url("../../img/timdesk/Preloader.gif") 50% 50% no-repeat
        rgb(249, 249, 249);
    }
  </style>
</head>

<body id="page-top">
  <div id=loader></div>
  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
$icono = '../../img/icons/puestos.svg';
$titulo = "Detalle nómina";
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
          $backIcon = true;
          $backRoute = "./periodos.php";
          $rutatb = "../";
          $_SESSION['IDNomina'] = $_SESSION['idNominaG'];
          require_once "../topbar.php";
        ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <div class="card mb-4 data-table">
            <div class="card-header py-3">
              <div class="row">
                  <div class="col-12 col-lg-4">
                    <b><label>No. Nómina:</label></b>
                    <?=$row_datos_nomina['no_nomina']?>
                  </div>
                  <div class="col-12 col-lg-4">
                    <b><label>Sucursal:</label></b>
                    <?=$row_datos_nomina['sucursal']?>
                  </div>
                  <div class="col-12 col-lg-4">
                    <b><label>Periodicidad:</label></b>
                    <?=$row_datos_nomina['Periodo']?>
                  </div>
              </div>

              <div class="row">
                  <div class="col-12 col-lg-4">
                    <b><label>Fecha inicio:</label></b>
                    <?=$row_datos_nomina['fecha_inicio']?>
                  </div>
                  <div class="col-12 col-lg-4">
                    <b><label>Fecha fin:</label></b>
                    <?=$row_datos_nomina['fecha_fin']?>
                  </div>
                  <div class="col-12 col-lg-4">
                    <b><label>Fecha pago:</label></b>
                    <?=$row_datos_nomina['fecha_pago']?>
                  </div>
              </div>

              <div class="row">
                  <div class="col-12 col-lg-4">
                    <b><label>Tipo nómina:</label></b>
                    <?=$row_datos_nomina['tipo']?>
                  </div>
                  <div class="col-12 col-lg-4">
                    <b><label>No. empleados:</label></b>
                    <?=$row_datos_nomina['no_empleados']?>
                  </div>
                  <div class="col-12 col-lg-4">
                    <b><label>Total:</label></b>
                    <?=$row_datos_nomina['total']?>
                  </div>
              </div>

              <div class="row">
                  <div class="col-12 col-lg-12">
                    <b><label>Ultima nómina del mes:</label></b>
                    <?php echo ($row_datos_nomina['ultima_nomina'] == 1) ? "Si" : "No"; ?>
                  </div>
              </div>

              <br>
              <div class="row">
                  <div class="col-12 col-lg-6" id="nominaAutorizar">
                    <?php
                      if($row_datos_nomina['autorizada'] == 1){
                        echo '<center>
                                <span class="btn-custom btn-custom--blue size-btn">Nómina autorizada</button>
                              </center>';
                      }
                      else{
                        echo '<center>
                                <button type="button" class="btn-custom btn-custom--border-blue size-btn" id="autorizarNomina">Autorizar nómina</button>
                             </center>';
                      }
                    ?>
                  </div>
                  <div class="col-12 col-lg-6" id="timbradoCompletoNomina">
                    <?php
                      if($row_datos_nomina['estatus'] == 2){
                        echo '<center>
                                <span class="btn-custom btn-custom--blue size-btn">Nómina timbrada</button>
                              </center>';
                      }
                      else{
                        echo '<center>
                                <button type="button" class="btn-custom btn-custom--border-blue size-btn" id="timbrarNominaCompleta">Timbrar nómina</button>
                             </center>';
                      }
                    ?>
                  </div>
              </div>
                  <?php
                    if($row_datos_nomina['autorizada'] == 1){
                      $displayDesautorizar = "block";
                    }
                    else{
                      $displayDesautorizar = "none";
                    }

                    if($sumaNTimbrada > 0){
                      $displayTimbrado = "block";
                    }
                    else{
                      $displayTimbrado = "none";
                    }

                      echo '<br>
                            <div class="row">
                              <div class="col-12 col-lg-6" id="nominaDesautorizada" style="display: '.$displayDesautorizar.'">
                                <center>
                                  <button type="button" class="btn-custom btn-custom--border-blue" id="desautorizarNomina">Desautorizar nómina</button>
                                </center>
                              </div>
                              <div class="col-12 col-lg-6" id="nominaTimbradaMos" style="display: '.$displayTimbrado.'">
                                <center>
                                  <button type="button" class="btn-custom btn-custom--border-blue" id="cancelarNominaGeneral">Cancelar timbrado nómina</button>
                                </center>
                              </div>
                            </div>';

                  ?>
              

            </div>
          </div>

          <!-- Page Heading -->
          <div class="card mb-4 data-table">
            <div class="card-header py-3">
<?php
//echo $row_datos_nomina['sucursal_id']." -- ".$row_datos_nomina['periodo_id']." -- ".$row_datos_nomina['fecha_fin_or'];
?>
                <div class="row">
                  <div class="col-12 col-lg-12">
                    <label>Empleado:</label>
                    <select class="form-control" id="empleado" disabled>
                      <option disabled selected>Selecciona empleado</option>
                      <?php
                        $f_ini = $row_datos_nomina['fecha_inicio_or'];
                        $f_fin = $row_datos_nomina['fecha_fin_or'];

                        $stmt = $conn->prepare('SELECT e.PKEmpleado,e.Nombres, e.PrimerApellido, e.SegundoApellido FROM empleados as e INNER JOIN datos_laborales_empleado as dle ON dle.FKEmpleado = e.PKEmpleado WHERE e.estatus = 1 AND e.is_generic = 0 AND dle.FKSucursal = :idsucursal AND dle.FKPeriodo = :idperiodo AND e.empresa_id = '.$_SESSION['IDEmpresa'].' AND dle.FechaIngreso <= :fecha_fin AND dle.Confidencial = :confidencial');
                        $stmt->bindValue(":idsucursal", $row_datos_nomina['sucursal_id']);
                        $stmt->bindValue(":idperiodo", $row_datos_nomina['periodo_id']);
                        $stmt->bindValue(":fecha_fin", $f_fin);
                        $stmt->bindValue(":confidencial", $row_datos_nomina['confidencial']);
                        $stmt->execute();
                        $empleados = $stmt->fetchAll();
                        foreach ($empleados as $emp) {
                      ?>
                          <option value="<?=$emp['PKEmpleado']?>"><?php echo $emp["Nombres"]." ".$emp["PrimerApellido"]." ".$emp["SegundoApellido"]; ?></option>
                      <?php
                        }
                      ?>
                    </select>
                    <br>
                    <span style="position: relative; top: 2px;"><label><input type="checkbox" id="cbboxExcluir" value="1"> Excluir empleado</label></span>
                    <input type="hidden" name="csr_token_UT5JP" id="csr_token_UT5JP" value="<?=$token?>">
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-lg-12" id="timbradoNomina">
                </div>
                </div>
                <div class="col-12 col-lg-12" id="mostrarFuncionesFactura" style="display: none;">
                </div>
                <br id="espacioMostrarresultado" style="display:none;">
                <div id="mostrarResultadosPadre" class="row alert alert-dismissible fade show" role="alert" style="display:none;"> 
                    <div class="col-12 col-lg-12" id="mostrarResultados">
                    </div>
                    <button id="close-mostrarResultado" type="button" class="close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
          </div>


          <div class="card shadow mb-4" id="divCalculo">
            <div class="card-header py-3">
              <div class="row">
                <div class="col-lg-10">
                  <b>Calculo de nomina</b>
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
                  </div>
                  <div class="col-lg-4">

                  </div>
                  <div class="col-lg-4">

                    <label><b>Turno:</b> <span id="Turno"></span></label><br>
                    <label><b>Puesto:</b> <span id="Puesto"></span></label><br>
                    <label><b>Fecha de ingreso: </b><span id="FechaIngreso"></span></label>
                  </div>
                </div>
                <br>
                <hr>
                <span id="funcionesNomina">
                    <div class="row">
                      <div class="col-12 col-lg-1"></div>
                      <div class="col-12 col-lg-2">
                        <center>
                          <button type="button" class="btn-custom btn-custom--border-blue size-btn" id="cargarPercepcionDeduccion">Percepción</button>
                        </center>
                      </div>
                      <div class="col-12 col-lg-2">
                        <center>
                          <button type="button" class="btn-custom btn-custom--border-blue size-btn" id="cargarDeduccion">Deducción</button>
                        </center>
                      </div>
                      <div class="col-12 col-lg-2">
                        <center>
                          <button type="button" class="btn-custom btn-custom--border-blue size-btn" id="cargarOtrosPagos">Otros pagos</button>
                        </center>
                      </div>
                      <div class="col-12 col-lg-2">
                        <center>
                          <button type="button" class="btn-custom btn-custom--border-blue size-btn" id="horasExtra">Horas extras</button>
                        </center>
                      </div>
                      <div class="col-12 col-lg-2">
                        <center>
                          <button type="button" class="btn-custom btn-custom--border-blue size-btn" id="turnosExtra">Turnos extra</button>
                        </center>
                      </div>
                      <div class="col-12 col-lg-1"></div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-12 col-lg-1"></div>

                      <div class="col-12 col-lg-2">
                        <center>
                          <button type="button" class="btn-custom btn-custom--border-blue size-btn" id="faltaDias">Faltas</button>
                        </center>
                      </div>
                      <div class="col-12 col-lg-2">
                        <center>
                          <button type="button" class="btn-custom btn-custom--border-blue size-btn" id="incapacidadBtn">Incapacidades</button>
                          <!--<button type="button" class="btn-custom btn-custom--border-blue" id="reenvio">Reenvio</button>-->
                        </center>
                      </div>
                      <div class="col-12 col-lg-2">
                        <center>
                          <button type="button" class="btn-custom btn-custom--border-blue size-btn" id="primaDominical">Prima dominical</button>
                        </center>
                      </div>
                      <div class="col-12 col-lg-2">
                        <center>
                          <button type="button" class="btn-custom btn-custom--border-blue size-btn" id="primaVacacional">Prima vacacional</button>
                          <!--<button type="button" class="btn-custom btn-custom--border-blue" id="reenvio">Reenvio</button>-->
                        </center>
                      </div>
                      <div class="col-12 col-lg-2">
                        <center>
                          <button type="button" class="btn-custom btn-custom--border-blue size-btn size-btn" id="pensionAlimenticiaBtn">Pensión alimenticia</button>
                        </center>
                      </div>
                      <div class="col-12 col-lg-1"></div>
                    </div>
                     <br>
                    <div class="row">
                      <div class="col-12 col-lg-1"></div>
                      <div class="col-12 col-lg-2">
                        <center>
                          <button type="button" class="btn-custom btn-custom--border-blue size-btn" id="fonacotBtn">FONACOT</button>
                        </center>
                      </div>
                      <div class="col-12 col-lg-2">
                        <center>
                          <button type="button" class="btn-custom btn-custom--border-blue size-btn" id="infonavitBtn">INFONAVIT</button>
                        </center>
                      </div>
                      <div class="col-12 col-lg-2">
                        <center>
                          <button type="button" class="btn-custom btn-custom--border-blue size-btn" id="agregarConcepto" data-toggle="modal" data-target="#agregar_clave">Actualizar claves</button>
                        </center>
                      </div>
                      <div class="col-12 col-lg-2">
                        <center>
                          <button type="button" class="btn-custom btn-custom--border-blue size-btn" id="mostrarSBC">Actualizar SBC</button>
                        </center>
                      </div>
                      <div class="col-12 col-lg-1"></div>
                    </div>
                    <br>
                </span>
                <div class="table-responsive">
                  <table class="table dataTable" id="tblNominaEmpleado" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>Concepto</th>
                        <th style="text-align: left !important;">Imp. Gravado</th>
                        <th style="text-align: left !important;">Imp. Exento</th>
                        <th style="text-align: center !important;">Especie</th>
                        <th style="text-align: left !important;">Total</th>
                      </tr>
                    </thead>
                  </table>
                </div>
                <hr> 
                <div id="opcionesExportacion" class="col-12 text-center" style="display:none">
                    <div class="row">
                        <div class="col-lg-3">
                          <button type="button" class="btn btn-custom btn-custom--border-blue" id="exportarExcel">Excel empleado</button>
                        </div>
                        <div class="col-lg-3">
                          <button type="button" class="btn btn-custom btn-custom--border-blue" id="exportarExcelNomina">Excel nómina</button>
                        </div>
                        <div class="col-lg-3">
                          <button type="button" class="btn btn-custom btn-custom--border-blue" id="exportarPDF">PDF Empleado</button>
                        </div>
                        <div class="col-lg-3">
                          <button type="button" class="btn btn-custom btn-custom--border-blue" id="exportarPDFNomina">PDF nómina</button>
                        </div>
                    </div>
                </div>
    
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

  <!--AGREGA Percepcion/Deduccion modal -->
    <div class="modal fade right" id="agregar_percepcion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
          <div class="modal-content">
            <form action="#" method="POST" id="agregarPercepcionF">
              <!--<input type="hidden" name="idProyectoA" value="">-->
              <div class="modal-header">
                <h4 class="modal-title w-100" id="percepcionDeducionTitulo">Agregar</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">x</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label for="usr">Concepto*:</label>
                      <select class="form-control" id="cmbConcepto">
                      </select>
                  <div class="col-12" style="position: relative; left: 2%;top:5px;"> 
                    <input class="form-check-input" type="checkbox" value="1" id="checkNuevoConcepto" name="checkNuevoConcepto">
                    <label class="form-check-label" for="checkNuevoConcepto">Agregar concepto
                  </div>
                </div>
                <span id="mostrarNuevoConcepto" style="display: none;">
                    <div class="form-group">
                      <label for="usr">Nuevo concepto*:</label>
                      <input type="text" class="form-control" name="txtConcepto" id="txtConcepto" value="" maxlength="100" required>
                    </div>
                    <div class="form-group">
                      <label for="usr">Clave SAT*:</label>
                          <select class="form-control" id="cmbClaveSAT">
                          </select>
                    </div>
                    <div class="form-group" id="claveMostrar" style="display: none;">
                      <label for="usr">Clave*:</label>
                      <input type="text" class="form-control" name="txtClave" id="txtClave" value="" maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" required>
                    </div>
                </span>

                    <div class="form-group">
                      <label for="usr">Importe:</label>
                      <input type="text" value="" name="txtImporte" id="txtImporte" class="form-control txtImporte numericDecimal-only" maxlength="14">
                      <input type="hidden" name="tipoMovimiento" id="tipoMovimiento" value="1">
                    </div>
                    <div class="form-group" id="mostrarExento">
                      <label><input type="checkbox" id="cbboxExento" value="1"> Especie</label>
                    </div>
            
                <div class="modal-footer justify-content-center">
                  <button type="button" class="btn-custom btn-custom--border-blue btnCancelarActualizacion"
                  data-dismiss="modal" id="CancelaragregarPercepcionDeduccion"><span class="ajusteProyecto">Cerrar</span></button>
                  <button type="button" class="btn-custom btn-custom--blue" name="agregarPercepcionDeduccion" id="agregarPercepcionDeduccion"><span
                    class="ajusteProyecto">Agregar</span></button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  <!--END EDIT MODAL-->

   <!--EDIT Percepcion/Deduccion modal -->
    <div class="modal fade right" id="editar_percepcion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
          <div class="modal-content">
            <form action="#" method="POST" id="editarConcepto">
              <!--<input type="hidden" name="idProyectoA" value="">-->
              <div class="modal-header">
                <h4 class="modal-title w-100" id="percepcionDeducionTituloEdit">Editar</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">x</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label for="usr">Concepto:</label>
                  <input type='text' value='' name="txtConceptoEdit" id="txtConceptoEdit" class='form-control' disabled>
                  <input type="hidden" id="tipoConceptoEdit" name="tipoConceptoEdit" value="1">
                </div>
                <div class="form-group">
                  <label for="usr">Importe:</label>
                  <input type='text' value='' name="txtImporteEdit" id="txtImporteEdit" class='form-control txtImporteEdit numericDecimal-only' maxlength="14">
                </div>

                <div class="form-group" id="editExento">
                  <label><input type="checkbox" id="cbboxExentoEdit" value="1"> Especie</label>
                </div>
            
                <div class="modal-footer justify-content-center">
                  <input type="hidden" name="idDetalleNomina" id="idDetalleNomina" value="">
                  <input type="hidden" name="txtTipoEdit" id="txtTipoEdit" value="">
                  <input type="hidden" name="editTipoConcepto" id="editTipoConcepto" value="">
                  <button type="button" class="btn-custom btn-custom--border-blue btnCancelarActualizacion"
                  data-dismiss="modal" id="CancelarEditarPercepcionDeduccion"><span class="ajusteProyecto">Cerrar</span></button>
                  <button type="button" class="btn-custom btn-custom--blue" name="editarPercepcionDeduccion" id="editarPercepcionDeduccion"><span
                    class="ajusteProyecto">Modificar</span></button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  <!--END EDIT MODAL-->


  <!--AGREGA Otros Pagos modal -->
    <div class="modal fade right" id="agregar_otros_pagos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
          <div class="modal-content">
            <form action="#" method="POST" id="agregarOtrosPagosF">
              <!--<input type="hidden" name="idProyectoA" value="">-->
              <div class="modal-header">
                <h4 class="modal-title w-100" id="percepcionOtrosPagosTitulo">Agregar otros pagos</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">x</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label for="usr">Concepto:</label>
                      <select class="form-control" id="cmbConceptoOP">
                      </select>
                </div>
                <div class="form-group" id="claveMostrar" style="display: none;">
                  <label for="usr">Clave*:</label>
                  <input type="text" class="form-control" name="txtClaveOP" id="txtClaveOP" value="" maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" required>
                </div>
                <div class="form-group">
                  <label for="usr">Importe:</label>
                  <input type="text" value="" name="txtImporte" id="txtImporte" class="form-control txtImporte numericDecimal-only" maxlength="14">
                  <input type="hidden" name="tipoMovimiento" id="tipoMovimiento" value="1">
                </div>

                <div class="form-group" id="mostrarExento">
                  <label><input type="checkbox" id="cbboxExento" value="1"> Especie</label>
                </div>
            
                <div class="modal-footer justify-content-center">
                  <button type="button" class="btn-custom btn-custom--border-blue btnCancelarActualizacion"
                  data-dismiss="modal" id="CancelaragregarPercepcionDeduccion"><span class="ajusteProyecto">Cerrar</span></button>
                  <button type="button" class="btn-custom btn-custom--blue" name="agregarPercepcionDeduccion" id="agregarPercepcionDeduccion"><span
                    class="ajusteProyecto">Agregar</span></button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  <!--END EDIT MODAL-->


  <!--AGREGA claves necesarias modal -->
    <div class="modal fade right" id="agregar_clave_salario" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
          <div class="modal-content">
            <form action="#" method="POST" id="agregarPercepcionF">
              <!--<input type="hidden" name="idProyectoA" value="">-->
              <div class="modal-header">
                <h4 class="modal-title w-100">Claves</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">x</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <center>
                    No se han agregado las siguientes claves y son requeridas, favor de ingresarlas.
                  </center>
                </div>
                <div class="form-group" id="claveMostrarSalario" style="display: none;">
                  <label for="usr">Sueldos, Salarios Rayas y Jornales:</label>
                  <input type="text" class="form-control" name="txtClaveSalario" id="txtClaveSalario" value="" maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" required>
                </div>
                <div class="form-group" id="claveMostrarIMSS"  style="display: none;">
                  <label for="usr">Seguridad Social(IMSS):</label>
                  <input type="text" class="form-control" name="txtClaveIMSS" id="txtClaveIMSS" value="" maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" required>
                </div>
                <div class="form-group" id="claveMostrarISR"  style="display: none;">
                  <label for="usr">ISR:</label>
                  <input type="text" class="form-control" name="txtClaveISR" id="txtClaveISR" value="" maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" required>
                </div>

                <div class="form-group" id="claveMostrarHorasExtra" style="display: none;">
                  <label for="usr">Horas extra:</label>
                  <input type="text" class="form-control" name="txtClaveHorasExtra" id="txtClaveHorasExtra" value="" maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" required>
                </div>
                <div class="form-group" id="claveMostrarPrimaDominical"  style="display: none;">
                  <label for="usr">Prima dominical:</label>
                  <input type="text" class="form-control" name="txtClavePrimaDominical" id="txtClavePrimaDominical" value="" maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" required>
                </div>
                <div class="form-group" id="claveMostrarPrimaVacacional"  style="display: none;">
                  <label for="usr">Prima vacacional:</label>
                  <input type="text" class="form-control" name="txtClavePrimaVacacional" id="txtClavePrimaVacacional" value="" maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" required>
                </div>

                <div class="form-group" id="claveMostrarOtrosIngresos" style="display: none;">
                  <label for="usr">Otros ingresos por salarios:</label>
                  <input type="text" class="form-control" name="txtClaveOtrosIngresos" id="txtClaveOtrosIngresos" value="" maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" required>
                </div>
                <div class="form-group" id="claveMostrarDescuentoIncapacidad"  style="display: none;">
                  <label for="usr">Descuento por incapacidad:</label>
                  <input type="text" class="form-control" name="txtClaveDescuentoIncapacidad" id="txtClaveDescuentoIncapacidad" value="" maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" required>
                </div>
                <div class="form-group" id="claveMostrarAusencia"  style="display: none;">
                  <label for="usr">Ausencia(ausentismo):</label>
                  <input type="text" class="form-control" name="txtClaveAusencia" id="txtClaveAusencia" value="" maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" required>
                </div>

                <div class="form-group" id="claveMostrar071"  style="display: none;">
                  <label for="usr">Ajuste en Subsidio para el empleo:</label>
                  <input type="text" class="form-control" name="txtClave071" id="txtClave071" value="" maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" required>
                </div>
                <div class="form-group" id="claveMostrar107"  style="display: none;">
                  <label for="usr">Ajuste al Subsidio Causado:</label>
                  <input type="text" class="form-control" name="txtClave107" id="txtClave107" value="" maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" required>
                </div>

                <div class="modal-footer justify-content-center">
                  <button type="button" class="btn-custom btn-custom--border-blue btnCancelarActualizacion"
                  data-dismiss="modal" id="CancelClaveSalario"><span class="ajusteProyecto">Cerrar</span></button>
                  <button type="button" class="btn-custom btn-custom--blue" name="agregarClaveSalario" id="agregarClaveSalario"><span
                    class="ajusteProyecto">Agregar</span></button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  <!--END EDIT MODAL-->


  <!-- Modal turnos extras -->
  <div id="turnosExtraModal" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="agregarPedido.php" method="POST">
          <div class="modal-header">
            <h4 class="modal-title">Turnos extra</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <label>Turnos:</label>
            <br>
            <div class="row">
              <div class="col-12 col-lg-12">
                <input type="number" class="form-control" id="txtTurnosExtra" min="1" max="12" value="" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
              </div>
            </div>
            <br>
            <label>Importe:</label>
            <div class="row">
              <div class="col-12 col-lg-12">
                <input type="text" value="" name="txtImporteTurno" id="txtImporteTurno" class="form-control txtImporteTurno numericDecimal-only" maxlength="14">
              </div>
            </div>
            <br>
            <div class="form-group" id="claveMostrarTurnoExtra" style="display: none;">
                  <label for="usr">Clave:</label>
                  <input type="text" class="form-control" name="txtClaveTurnoExtra" id="txtClaveTurnoExtra" value="" maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" required>
            </div>
            <br>
            <div class="form-group" id="claveMostrarTurnoExtraConcepto" style="display: none;">
              <label for="usr">Concepto:</label>
              <input type="text" class="form-control" name="txtConceptoTurnoExtra" id="txtConceptoTurnoExtra" value="" maxlength="100" required>
            </div>
            <div class="row" style="display: block;">
              <center>
                <button type="button" class="btn btn-custom btn-custom--blue" id="agregarTurnosExtra">Agregar</button>
              </center>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>


  <!-- Update Modal turnos extras -->
  <div id="turnosExtraModalEdit" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="#" method="POST">
          <div class="modal-header">
            <h4 class="modal-title">Turnos extra</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <label>Turnos:</label>
            <br>
            <div class="row">
              <div class="col-12 col-lg-12">
                <input type="number" class="form-control" id="txtTurnosExtraEdit" min="1" max="12" value="" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
              </div>
            </div>
            <br>
            <label>Importe:</label>
            <div class="row">
              <div class="col-12 col-lg-12">
                <input type="text" value="" name="txtImporteTurnoEdit" id="txtImporteTurnoEdit" class="form-control txtImporteTurnoEdit numericDecimal-only" maxlength="14">
              </div>
            </div>
            <br>
            <div class="row" style="display: block;">
              <center>
                <input type="hidden" value="" name="txtIdDetalleNominaTurnos" id="txtIdDetalleNominaTurnos">
                <button type="button" class="btn btn-custom btn-custom--blue" id="modificarTurnosExtra">Modificar</button>
              </center>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  

  <!-- Modal faltas -->
  <div id="diasFaltaModal" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="agregarPedido.php" method="POST">
          <div class="modal-header">
            <h4 class="modal-title">Faltas</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <label>Días:</label>
            <br>
            <div class="row">
              <div class="col-12 col-lg-12">
                <input type="number" class="form-control" id="txtDiasFalta" min="1" max="31" value="" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-12 col-lg-6">
                <label>Fecha inicio:</label>
                <input type="date" class="form-control" id="fechaIniFaltas" name="fechaIniFaltas" value="">
              </div>
              <div class="col-12 col-lg-6">
                <label>Fecha fin:</label>
                <input type="date" class="form-control" id="fechaFinFaltas" name="fechaFinFaltas" value="">
              </div>
            </div>
            <br>
            <label>Importe:</label>
            <div class="row">
              <div class="col-12 col-lg-12">
                <input type="text" value="" name="txtImporteDiasFalta" id="txtImporteDiasFalta" class="form-control txtImporteDiasFalta numericDecimal-only" maxlength="14">
              </div>
            </div>
            <br>
            <div class="form-group">
                  <label for="usr">Motivo:</label>
                      <select class="form-control" id="cmbMotivo">
                        <?php
                           $stmt = $conn->prepare('SELECT id, codigo, concepto FROM tipo_deduccion WHERE id IN (6,20)');
                            if($stmt->execute()){

                                $claves = $stmt->fetchAll();
                                  echo "<option value='0'>Selecciona el motivo de la falta</option>";
                                  foreach ($claves as $c) {
                                    echo "<option value='".$c['id']."'>".$c["codigo"]." - ".$c["concepto"]."</option>";
                                  }
                            }
                        ?>
                      </select>
            </div>
            <div class="form-group" id="mostrarIncapacidad" style="display: none;">
                  <label for="usr">Incapacidad:</label>
                      <select class="form-control" id="cmbTipoIncapacidad">
                        <?php
                           $stmt = $conn->prepare('SELECT id, codigo, concepto FROM tipo_incapacidad');
                            if($stmt->execute()){

                                $claves = $stmt->fetchAll();
                                  echo "<option value='0'>Selecciona el motivo de la incapacidad</option>";
                                  foreach ($claves as $c) {
                                    echo "<option value='".$c['id']."'>".$c["codigo"]." - ".$c["concepto"]."</option>";
                                  }
                            }
                        ?>
                      </select>
            </div>
            <div class="form-group" id="claveMostrarFaltas" style="display: none;">
                  <label for="usr">Clave:</label>
                  <input type="text" class="form-control" name="txtClaveFaltas" id="txtClaveFaltas" value="" maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" required>
            </div>
            <div class="form-group" id="claveMostrarFaltasConcepto" style="display: none;">
              <label for="usr">Concepto:</label>
              <input type="text" class="form-control" name="txtConceptoFaltas" id="txtConceptoFaltas" value="" maxlength="100" required>
            </div>
            <br>
            <div class="row" style="display: block;">
              <center>
                <button type="button" class="btn btn-custom btn-custom--blue" id="agregarDiasFalta">Agregar</button>
              </center>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>


  <!-- Update Modal faltas -->
  <div id="diasFaltaModalEdit" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="#" method="POST">
          <div class="modal-header">
            <h4 class="modal-title">Faltas</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <label>Días:</label>
            <br>
            <div class="row">
              <div class="col-12 col-lg-12">
                <input type="number" class="form-control" id="txtDiasFaltaEdit" min="1" max="31" value="" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-12 col-lg-6">
                <label>Fecha inicio:</label>
                <input type="date" class="form-control" id="fechaIniFaltasEdit" name="fechaIniFaltasEdit" value="">
              </div>
              <div class="col-12 col-lg-6">
                <label>Fecha fin:</label>
                <input type="date" class="form-control" id="fechaFinFaltasEdit" name="fechaFinFaltasEdit" value="">
              </div>
            </div>
            <br>
            <label>Importe:</label>
            <div class="row">
              <div class="col-12 col-lg-12">
                <input type="text" value="" name="txtImporteDiasFaltaEdit" id="txtImporteDiasFaltaEdit" class="form-control txtImporteDiasFaltaEdit numericDecimal-only" maxlength="14">
              </div>
            </div>
            <br>
            <div class="form-group">
                  <label for="usr">Motivo:</label>
                  <input type='text' value='' name="txtMotivoEdit" id="txtMotivoEdit" class='form-control' disabled>
            </div>
            <div class="form-group" id="mostrarEditIncapacidad">
                  <br>
                  <label for="usr">Incapacidad:</label>
                  <input type='text' value='' name="txtmostrarIncapacidad" id="txtmostrarIncapacidad" class='form-control' disabled>
            </div>
            <br>
            <div class="row" style="display: block;">
              <center>
                <input type="hidden" value="" name="txtIdDetalleNominaFaltas" id="txtIdDetalleNominaFaltas">
                <button type="button" class="btn btn-custom btn-custom--blue" id="modificarDiasFalta">Modificar</button>
              </center>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal incapacidades -->
  <div id="incapacidadesModal" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="incapacipidad.php" id="formIncapacidades" method="POST">
          <div class="modal-header">
            <h4 class="modal-title">Incapacidades</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-12 col-lg-6">
                <label>Folio*:</label>
                <input type="text" class="form-control" id="txtFolioIncapacidad" value="" >
              </div>
              <div class="col-12 col-lg-6">
                <label>Días autorizados*:</label>
                <input type="number" class="form-control" id="txtDiasIncapacidad" min="1" max="31" value="" onkeypress="return event.charCode >= 48 && event.charCode <= 57" oninput="limitar(this, 2);">
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-12 col-lg-12">
                <label>Fecha inicio*:</label>
                <input type="date" class="form-control" id="fechaIniIncapacidad" name="fechaIniIncapacidad" value="">
              </div>
            </div>
            <br>
            <div class="form-group">
                  <label for="usr">Motivo:</label>
                      <select class="form-control" id="cmbMotivoIncapacidad">
                        <?php
                           $stmt = $conn->prepare('SELECT id, codigo, concepto FROM tipo_deduccion WHERE id IN (6)');
                            if($stmt->execute()){

                                $claves = $stmt->fetchAll();
                                  foreach ($claves as $c) {
                                    echo "<option value='".$c['id']."'>".$c["codigo"]." - ".$c["concepto"]."</option>";
                                  }
                            }
                        ?>
                      </select>
            </div>
            <div class="form-group">
                  <label for="usr">Incapacidad*:</label>
                      <select class="form-control" id="cmbTipoIncapacidadPer">
                        <?php
                           $stmt = $conn->prepare('SELECT id, codigo, concepto FROM tipo_incapacidad');
                            if($stmt->execute()){

                                $claves = $stmt->fetchAll();
                                  echo "<option value='0'>Selecciona el motivo de la incapacidad</option>";
                                  foreach ($claves as $c) {
                                    echo "<option value='".$c['id']."'>".$c["codigo"]." - ".$c["concepto"]."</option>";
                                  }
                            }
                        ?>
                      </select>
            </div>
            <div class="row">
              <div class="col-12 col-lg-6">
                <label>% incapacidad:</label>
                <input type="text" value="" name="txtPorcentajeIncapacidad" id="txtPorcentajeIncapacidad" class="form-control txtPorcentajeIncapacidad numericDecimal-only" maxlength="14" oninput="limitar(this, 5);" value="0" disabled>
              </div>
              <div class="col-12 col-lg-6">
                <label>Riesgo:</label>
                <select class="form-control" id="cmbRiesgo" disabled>
                  <option value='1'>Accidente</option>
                  <option value='2'>Enfermedad</option>
                </select>
              </div>
            </div>
            <br>
            <label>Observaciones:</label>
            <div class="row">
              <div class="col-12 col-lg-12">
                <textarea name="txtObservacionesIncapacidad" id="txtObservacionesIncapacidad" cols="40" rows="3" class="form-control" maxlength="100"></textarea>
              </div>
            </div>
            <br>
            <div class="form-group" id="claveMostrarIncapacidad" style="display: none;">
              <label for="usr">Clave:</label>
              <input type="text" class="form-control" name="txtClaveIncapacidad" id="txtClaveIncapacidad" value="" maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" required>
            </div>
            <div class="form-group" id="claveMostrarIncapacidadConcepto" style="display: none;">
              <label for="usr">Concepto:</label>
              <input type="text" class="form-control" name="txtConceptoIncapacidad" id="txtConceptoIncapacidad" value="" maxlength="100" required>
            </div>
            <br>
            <div class="row" style="display: block;">
              <center>
                <button type="button" class="btn btn-custom btn-custom--blue" id="agregarDiasIncapacidad">Agregar</button>
              </center>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>


  <!-- Agregar Modal prima dominical -->
  <div id="primaDominicalModal" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="#" method="POST">
          <div class="modal-header">
            <h4 class="modal-title">Prima dominical</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <label>Importe:</label>
            <br>
            <div class="row">
              <div class="col-12 col-lg-12">
                <input type="text" value="" name="txtPrimaDominical" id="txtPrimaDominical" class="form-control txtPrimaDominical numericDecimal-only" maxlength="14" readonly>
              </div>
            </div>
            <br>
            <div class="form-group" id="claveMostrarPrimaDominicalUnica" style="display: none;">
                  <label for="usr">Clave:</label>
                  <input type="text" class="form-control" name="txtClavePrimaDominicalUnica" id="txtClavePrimaDominicalUnica" value="" maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" required>
            </div>
            <br>
            <div class="row small" style="color: red;position: relative; left: 10px;">
              *Al agregar se ingresará la cantidad seleccionada, y se contabilizará como un domingo. 
            </div>
            <br>
            <div class="row" style="display: block;">
              <center>
                <button type="button" class="btn btn-custom btn-custom--blue" id="agregarPrimaDominical">Agregar</button>
              </center>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>


  <!-- Modal agregar horas -->
  <div id="horasExtraModal" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="#" method="POST">
          <div class="modal-header">
            <h4 class="modal-title">Horas extra</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-12 col-lg-8">
                <label>Hora:</label>
                <input type="number" class="form-control" id="txtHoras" min="1" max="20" value="" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
              </div>
              <div class="col-12 col-lg-4">
                <label>Tipo:</label>
                <select class="form-control" id="tipoHoras">
                  <option value="1">Sencillas</option>
                  <option value="2">Doble</option>
                  <option value="3">Triple</option>
                </select>
              </div>
            </div>
            <br>
            <label>Importe:</label>
            <div class="row">
              <div class="col-12 col-lg-12">
                <input type="text" value="" name="txtImporteHoras" id="txtImporteHoras" class="form-control txtImporteHoras numericDecimal-only" maxlength="14">
              </div>
            </div>
            <br>
            <div class="form-group" id="claveMostrarHorasExtraUnica" style="display: none;">
                  <label for="usr">Clave:</label>
                  <input type="text" class="form-control" name="txtClaveHorasExtraUnica" id="txtClaveHorasExtraUnica" value="" maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" required>
            </div>
            <br>
            <div class="form-group" id="claveMostrarHorasExtraConcepto" style="display: none;">
              <label for="usr">Concepto:</label>
              <input type="text" class="form-control" name="txtConceptoHorasExtra" id="txtConceptoHorasExtra" value="" maxlength="100" required>
            </div>
            <div class="row" style="display: block;">
              <center>
                <button type="button" class="btn btn-custom btn-custom--blue" id="agregarHoras">Agregar</button>
              </center>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>


  <!-- Update Modal horas extras -->
  <div id="editar_horas_extras" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="#" method="POST">
          <div class="modal-header">
            <h4 class="modal-title">Editar horas extra</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-12 col-lg-8">
                <label>Hora:</label>
                <input type="number" class="form-control" id="txtHorasEdit" min="1" max="12" value="" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
              </div>
              <div class="col-12 col-lg-4">
                <label>Tipo:</label>
                <input type="text" value="" name="tipoHorasEdit" id="tipoHorasEdit" class="form-control" disabled>
                <input type="hidden" name="txttipoHorasEdit" id="txttipoHorasEdit" value="">
              </div>
            </div>
            <br>
            <label>Importe:</label>
            <div class="row">
              <div class="col-12 col-lg-12">
                <input type="text" value="" name="txtImporteHorasEdit" id="txtImporteHorasEdit" class="form-control txtImporteHorasEdit numericDecimal-only" maxlength="14">
              </div>
            </div>
            <br>
            <div class="row" style="display: block;">
              <center>
                <input type="hidden" value="" name="idDetalleNominaHorasExtras" id="idDetalleNominaHorasExtras">
                <button type="button" class="btn btn-custom btn-custom--blue" id="modificarHoras">Modificar</button>
              </center>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>


  <!-- Update SBC -->
  <div id="actualizar_sbc" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="#" method="POST">
          <div class="modal-header">
            <h4 class="modal-title">Salario base de cotización</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-12 col-lg-12">
                <label>Salario base de cotización (Parte fija)</label>
                <input type="text" class="form-control numericDecimal-only" name="SBCFijo" id="SBCFijo" value="" maxlength="12">
              </div>
            </div>
            <br>
            <label>Salario base de cotización (Parte variable)</label>
            <div class="row">
              <div class="col-12 col-lg-12">
                <input type="text" class="form-control numericDecimal-only" name="SBCVariables" id="SBCVariables" value="" maxlength="12">
              </div>
            </div>
            <br>
            <span style="font-size: 10px; color: red;">*Guardar el SBC sin límite, al calcular el IMSS se topará automaticamente a 25 UMAS.</span>
            <br>
            <div class="row">
              <div class="col-12 col-lg-6">
                <center>
                  <button type="button" class="btn btn-custom btn-custom--blue" id="calcularSBC">Calcular</button>
                </center>
              </div>
              <div class="col-12 col-lg-6">
                <center>  
                  <button type="button" class="btn btn-custom btn-custom--blue" id="actualizarSBC">Actualizar</button>
                </center>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>


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
              id="btnCancelarActualizacion"><span class="ajusteProyecto">Cerrar</span></button>
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
                  data-dismiss="modal" id="btnCancelarClave"><span class="ajusteProyecto">Cerrar</span></button>
                  <button type="button" class="btn-custom btn-custom--blue" name="btnAgregar" id="btnAgregarClave"><span
                    class="ajusteProyecto">Agregar</span></button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  <!--END ADD MODAL-->



  <!--AGREGAR NUEVA NOMINA EN BASE A LA QUE YA ESTA-->
    <div class="modal fade right" id="agregar_nomina" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
          <div class="modal-content">
            <form action="#" method="POST" id="agregarNomina">
              <!--<input type="hidden" name="idProyectoA" value="">-->
              <div class="modal-header">
                <h4 class="modal-title w-100" id="myModalLabel">Generar nueva nómina</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">x</span>
                </button>
              </div>
              <div class="modal-body">

                <div class="form-group">
                  <label for="usr">Sucursal:</label>
                    <input type="text" name="txtSucursalAN" class="form-control" id="txtSucursalAN" readonly>
                    <input type="hidden" name="idSucursalAN" id="idSucursalAN">
                </div>

                <div class="form-group">
                  <label for="usr">Periodo:</label>
                    <input type="text" name="txtPeriodoAN" class="form-control" id="txtPeriodoAN" readonly>
                    <input type="hidden" name="idPeriodoAN" id="idPeriodoAN">
                </div>

                <div class="form-group">
                  <label for="usr">Tipo:</label>
                    <input type="text" name="txtTipoAN" class="form-control" id="txtTipoAN" readonly>
                    <input type="hidden" name="idTipoAN" id="idTipoAN">
                </div>

                <div class="form-group">
                  <label for="usr">Fecha de pago*:</label>
                  <input type="date" class="form-control" name="txtFechaPagoAN" id="txtFechaPagoAN" value="" required>
                </div>
                <div class="form-group">
                  <label for="usr">Fecha de inicio*:</label>
                  <input type="date" class="form-control" name="txtFechaInicioAN" id="txtFechaInicioAN" value="" required>
                </div>
                <div class="form-group">
                  <label for="usr">Fecha final*:</label>
                  <input type="date" class="form-control" name="txtFechaFinAN" id="txtFechaFinAN" value="" required>
                </div>
                <div class="form-group">
                  <label><input type="checkbox" id="cbUltimaNomina" value="1"> Última nómina del mes</label>
                </div>
                <label style="color:#006dd9;font-size: 13px;"> (*) Campos requeridos</label>
                <div class="modal-footer justify-content-center">
                  <button type="button" class="btn-custom btn-custom--border-blue btnCancelarActualizacion"
                  data-dismiss="modal" id="btnCancelarNomina"><span class="ajusteProyecto">Cerrar</span></button>
                  <button type="button" class="btn-custom btn-custom--blue" name="btnAgregar" id="btnAgregarNomina"><span
                    class="ajusteProyecto">Agregar</span></button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  <!--END ADD MODAL-->

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


  <!-- modal IMSS -->
  <div id="calculo_imss_modal" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="#" method="POST">
          <div class="modal-header">
            <h4 class="modal-title">Cálculo IMSS</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body" id="mostrarUnicoIMSS">
          </div>
        </form>
      </div>
    </div>
  </div>


  <!-- Modal prima Vacacional -->
  <div id="primaVacacionalModal" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="#" method="POST" id="formPrimaVacacional">
          <div class="modal-header">
            <h4 class="modal-title">Prima vacacional</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-12 col-lg-6">
                <label>Días:</label>
                <input type="number" value="" name="txtDiasPrimaVac" id="txtDiasPrimaVac" class="form-control numericDecimal-only" maxlength="2" min="1" max="35" onkeypress="return event.charCode >= 48 && event.charCode <= 57" >
                <input type="hidden" name="totalVacaciones" id="totalVacaciones" value="">
              </div>
              <div class="col-12 col-lg-6">
                <label>Días restantes:</label>
                <input type="number" value="" name="txtDiasRestantePrimaVac" id="txtDiasRestantePrimaVac" class="form-control numericDecimal-only" maxlength="2" min="1" max="35" readonly>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-12 col-lg-6">
                <label>Fecha inicio:</label>
                <input type="date" class="form-control" id="fechaIniPV" name="fechaIniPV" value="" readonly>
              </div>
              <div class="col-12 col-lg-6">
                <label>Fecha termino:</label>
                <input type="date" class="form-control" id="fechaFinPV" name="fechaFinPV" value="" readonly>
              </div>
            </div>
            <br>
            <label>Importe:</label>
            <div class="row">
              <div class="col-12 col-lg-12">
                <input type="text" value="" name="txtImportePrimaVac" id="txtImportePrimaVac" class="form-control txtImportePrimaVac numericDecimal-only" maxlength="14" readonly>
              </div>
            </div>
            <br>
            <div class="form-group" id="claveMostrarPrimaVacacionalUnica" style="display: none;">
                  <label for="usr">Clave:</label>
                  <input type="text" class="form-control" name="txtClavePrimaVacacionalUnica" id="txtClavePrimaVacacionalUnica" value="" maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" required>
            </div>
            <br>
            <div class="row" style="display: block;">
              <center>
                <button type="button" class="btn btn-custom btn-custom--blue" id="agregarPrimaVacacional">Agregar</button>
              </center>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>


  <!-- Modal editar prima Vacacional -->
  <div id="primaVacacionalEditModal" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="#" method="POST" id="formPrimaVacacional">
          <div class="modal-header">
            <h4 class="modal-title">Prima vacacional</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-12 col-lg-12">
                <label>Días:</label>
                <input type="number" value="" name="txtDiasPrimaVacEdit" id="txtDiasPrimaVacEdit" class="form-control numericDecimal-only" readonly>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-12 col-lg-6">
                <label>Fecha inicio:</label>
                <input type="date" class="form-control" id="fechaIniPVEdit" name="fechaIniPVEdit" value="" readonly>
              </div>
              <div class="col-12 col-lg-6">
                <label>Fecha termino:</label>
                <input type="date" class="form-control" id="fechaFinPVEdit" name="fechaFinPVEdit" value="" readonly>
              </div>
            </div>
            <br>
            <label>Importe:</label>
            <div class="row">
              <div class="col-12 col-lg-12">
                <input type="text" value="" name="txtImportePrimaVacEdit" id="txtImportePrimaVacEdit" class="form-control txtImportePrimaVac numericDecimal-only" readonly>
              </div>
            </div>
            <br>
            <div class="row" style="display: block;">
              <center>
                <button type="button" class="btn btn-custom btn-custom--blue" id="cerrarPrimaVacacional" data-dismiss="modal">Cerrar</button>
              </center>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>


  <!-- Modal FONACOT -->
  <div id="fonacotModal" class="modal fade">
    <div class="modal-dialog" style="max-width: 850px !important;">
      <div class="modal-content">
        <form action="#" method="POST" id="formFonacot">
          <div class="modal-header">
            <h4 class="modal-title">FONACOT</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-12 col-lg-4" id="mostrarClaveFonacot">
              </div>
              <div class="col-12 col-lg-4">
                <label>Centro de trabajo FONACOT:</label>
                <input type="number" value="" name="txtCentroFonacot" id="txtDiasPrimaVac" class="form-control numericDecimal-only" readonly>
              </div>
              <div class="col-12 col-lg-4">
                <label>Número FONACOT:</label>
                <input type="number" value="" name="txtNumFonacot" id="txtNumFonacot" class="form-control numericDecimal-only"readonly>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-12 col-lg-4" id="mostrarConceptoFonacot">
              </div>
              <div class="col-12 col-lg-4">
                <label>Núm. de crédito:</label>
                <input type="text" class="form-control" id="txtNumCredito" name="txtNumCredito numericDecimal-only" value="" maxlength="20" required onkeypress="return isNumber(event)">
              </div>
              <div class="col-12 col-lg-4">
                  <label>Fecha de aplicación:</label>
                  <input type="date" name="fechaAplicacion" id="fechaAplicacion" class="form-control" value="<?=$row_datos_nomina['fecha_inicio_or'];?>">
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-12 col-lg-4">
                  <label>Tipo de cálculo:</label>
                  <select id="cmbTipoCalculo" name="cmbTipoCalculo" class="form-control">
                    <option value="1">Importe fijo</option>
                    <!--<option value="2">Proporción a días trabajados</option>-->
                  </select>
              </div>
              <div class="col-12 col-lg-4">
                <label>Importe:</label>
                <input type="text" class="form-control" id="txtImporteFonacot" name="txtImporteFonacot" value="">
              </div>
              <div class="col-12 col-lg-4">
                <label>Importe fijo en el período:</label>
                <input type="text" class="form-control" id="txtImporteFijoFonacot" name="txtImporteFijoFonacot" value="">
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-12 col-lg-4">
                <label>Pago de otros patrones:</label>
                <input type="text" class="form-control" id="txtPagosAnterioresFonacot" name="txtPagosAnterioresFonacot" value="">
              </div>
              <div class="col-12 col-lg-4">
                <label>Importe acumulado retenido:</label>
                <input type="text" class="form-control" id="txtImporteAcumuladoFonacot" name="txtImporteAcumuladoFonacot" value="" readonly>
              </div>
              <div class="col-12 col-lg-4">
                <label>Saldo:</label>
                <input type="text" class="form-control" id="txtSaldoFonacot" name="txtSaldoFonacot" value="" readonly>
              </div>
            </div>
            <br>
            <div class="row" id="btnMostrarFonacot">
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal INFONAVIT -->
  <div id="infonavitModal" class="modal fade">
    <div class="modal-dialog" style="max-width: 850px !important;">
      <div class="modal-content">
        <form action="#" method="POST" id="formInfonavit">
          <div class="modal-header">
            <h4 class="modal-title">INFONAVIT</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-12 col-lg-4" id="mostrarClaveInfonavit">
              </div>
              <div class="col-12 col-lg-8" id="mostrarConceptoInfonavit">
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-12 col-lg-4">
                <label>Núm. de crédito:</label>
                <input type="text" class="form-control" id="txtNumCreditoInfonavit" name="txtNumCreditoInfonavit numericDecimal-only" value="" maxlength="20" required onkeypress="return isNumber(event)">
              </div>
              <div class="col-12 col-lg-4">
                  <label>Tipo de cálculo:</label>
                  <select id="cmbTipoCalculoInfonavit" name="cmbTipoCalculoInfonavit" class="form-control">
                    <option value="1">Cuota fija</option>
                    <!--<option value="2">Proporción a días trabajados</option>-->
                  </select>
              </div>
              <div class="col-12 col-lg-4">
                <label>Cuota fija:</label>
                <input type="text" class="form-control" id="txtCuotaFija" name="txtCuotaFija" value="">
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-12 col-lg-4">
                  <label>Fecha de aplicación:</label>
                  <input type="date" name="fechaAplicacionInfonavit" id="fechaAplicacionInfonavit" class="form-control" value="">
              </div>      
              <div class="col-12 col-lg-4">
                  <label>Fecha de suspensión:</label>
                  <input type="date" name="fechaSuspensionInfonavit" id="fechaSuspensionInfonavit" class="form-control" value="">
              </div>       
              <div class="col-12 col-lg-4">
                  <label>Fecha de registro:</label>
                  <input type="date" name="fechaRegistroInfonavit" id="fechaRegistroInfonavit" class="form-control" value="" readonly>
              </div> 
            </div>
            <br>
            <div class="row">
              <div class="col-12 col-lg-4">
                <label>Importe acumulado:</label>
                <input type="text" class="form-control" id="txtMontoAcumuladoInfonavit" name="txtMontoAcumuladoInfonavit" value="">
              </div>
              <div class="col-12 col-lg-4">
                <label>Veces aplicadas:</label>
                <input type="number" class="form-control" id="txtVecesAplicadasInfonavit" name="txtVecesAplicadasInfonavit" value="" onkeypress="return isNumber(event)">
              </div>
              <div class="col-12 col-lg-4">
                <label><input type="checkbox" id="cbxSeguroVivienda"> Incluir seguro de vivienda (D-14)</label>
              </div>
            </div>
            <br>
            <div class="row" id="btnMostrarInfonavit">
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>


  <!--EDIT Credito Infonavit o fonacot-->
    <div class="modal fade right" id="creditoFonacotInfonavitModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
          <div class="modal-content">
            <form action="#" method="POST" id="editarCreditoInfoFonaFormulario">
              <div class="modal-header">
                <h4 class="modal-title w-100" id="tituloCreditoInfoFona"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">x</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label for="usr">Concepto:</label>
                  <input type='text' value='' name="txtCreditoConceptoEdit" id="txtCreditoConceptoEdit" class='form-control' disabled>
                  <input type="hidden" id="tipoCreditoEdit" name="tipoCreditoEdit" value="">
                </div>
                <div class="form-group">
                  <label for="usr">Importe:</label>
                  <input type='text' value='' name="txtCantidadAplicadaCredito" id="txtCantidadAplicadaCredito" class='form-control txtCantidadAplicadaCredito numericDecimal-only' maxlength="14">
                </div>            
                <div class="modal-footer justify-content-center">
                  <input type="hidden" name="idCreditoUnico" id="idCreditoUnico" value="">
                  <input type="hidden" name="txtTipoEditCredito" id="txtTipoEditCredito" value="">
                  <button type="button" class="btn-custom btn-custom--border-blue btnCancelarCredito" data-dismiss="modal" id="btnCancelarCredito">
                    <span class="ajusteProyecto">Cerrar</span>
                  </button>
                  <button type="button" class="btn-custom btn-custom--blue" name="editarCreditoInfoFona" id="editarCreditoInfoFona">
                    <span class="ajusteProyecto">Modificar</span>
                  </button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  <!--END EDIT MODAL-->


  <!-- Modal Pension Alimenticia -->
  <div id="pensionalimenticiaModal" class="modal fade">
    <div class="modal-dialog" style="max-width: 850px !important;">
      <div class="modal-content">
        <form action="#" method="POST" id="formPensionAlimenticia">
          <div class="modal-header">
            <h4 class="modal-title">Pensión alimenticia</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-12 col-lg-4" id="mostrarClavePensionAlimenticia">
              </div>
              <div class="col-12 col-lg-8" id="mostrarConceptoPensionAlimenticia">
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-12 col-lg-12">
                <label>Tipo:</label>
                <div class="row">
                    <div class="row col-12 col-lg-4" style="position: relative; top: 7px; left: 10px;">
                      <input type="radio" id="pensionAlimenticiaOpc1" name="pensionAlimenticiaOpc" value="2" class="pensionAlimenticiaClass form-control" style="width:20px;height: 20px;">
                      <label for="checkTipoPensionAlimenticia" style="position:relative;left: 1%;">  Por salario</label>
                    </div>
                    <div class="row col-12 col-lg-4" style="position: relative; top: 7px; left: 10px;">
                      <input type="radio" id="pensionAlimenticiaOpc2" name="pensionAlimenticiaOpc" value="3" class="pensionAlimenticiaClass form-control" style="width:20px;height: 20px;" >
                      <label for="checkTipoPensionAlimenticia" style="position:relative;left: 1%;">  Por total de percepciones</label>
                    </div>
                    <div class="row col-12 col-lg-4" style="position: relative; top: 7px; left: 10px;">
                      <input type="radio" id="pensionAlimenticiaOpc3" name="pensionAlimenticiaOpc" value="4" class="pensionAlimenticiaClass form-control" style="width:20px;height: 20px;">
                      <label for="checkTipoPensionAlimenticia" style="position:relative;left: 1%;">  Por total de percepciones<br> menos deducciones</label>
                    </div>
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-12 col-lg-4">
                <label>Porcentaje a aplicar:</label>
                <input type="number" class="form-control" id="txtPorcentajeAplicar" name="txtPorcentajeAplicar" value="" onkeypress="return isNumber(event)">
              </div>
              <div class="col-12 col-lg-4">
                  <label>Fecha de aplicación:</label>
                  <input type="date" name="fechaAplicacionPensionAlimenticia" id="fechaAplicacionPensionAlimenticia" class="form-control" value="">
              </div>     
              <div class="col-12 col-lg-4">
                  <label>Fecha de suspensión:</label>
                  <input type="date" name="fechaSuspensionPensionAlimenticia" id="fechaSuspensionPensionAlimenticia" class="form-control" value="" readonly>
              </div>     
            </div>
            <br>
            <div class="row" id="btnMostrarPensionAlimenticia">
            </div>
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

    $stmt = $conn->prepare('SELECT id FROM relacion_tipo_deduccion WHERE tipo_deduccion_id = 71 AND empresa_id = '.$_SESSION['IDEmpresa']." ORDER BY id ASC");
    $stmt->execute();
    $clave_071 = $stmt->rowCount();

    $cantidad_clave_071 = 0;
    if($clave_071 < 1){
      $cantidad_clave_071 = 1;
    }

    $stmt = $conn->prepare('SELECT id FROM relacion_tipo_deduccion WHERE tipo_deduccion_id = 107 AND empresa_id = '.$_SESSION['IDEmpresa']." ORDER BY id ASC");
    $stmt->execute();
    $clave_107 = $stmt->rowCount();

    $cantidad_clave_107 = 0;
    if($clave_107 < 1){
      $cantidad_clave_107 = 1;
    }
  ?>

  <!-- Custom scripts for all pages-->
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>

  <script>
    let idNomina = <?=$idNomina?>;
    let tipoNomina = <?=$row_datos_nomina['tipo_id']?>;
    let idNominaEmpleado = "";
    let cantidad_clave = <?=$cantidad_clave?>;
    let cantidad_clave_imss = <?=$cantidad_clave_imss?>;
    let cantidad_clave_isr = <?=$cantidad_clave_isr?>;
    let cantidad_clave_horasextra = <?=$cantidad_clave_horasextra?>;
    let cantidad_clave_primadominical = <?=$cantidad_clave_primadominical?>;
    let cantidad_clave_primavacacional = <?=$cantidad_clave_primavacacional?>;
    let cantidad_clave_otrosingresos = <?=$cantidad_clave_otrosingresos?>;
    let cantidad_clave_descuentoincapacidad = <?=$cantidad_clave_descuentoincapacidad?>;
    let cantidad_clave_ausencia = <?=$cantidad_clave_ausencia?>;
    let cantidad_clave_071 = <?=$cantidad_clave_071?>;
    let cantidad_clave_107 = <?=$cantidad_clave_107?>;
    let nominaAutorizadaG = <?=$nominaAutorizada?>; 

    if(cantidad_clave == 1 || cantidad_clave_imss == 1 || cantidad_clave_isr == 1 || cantidad_clave_horasextra == 1 || cantidad_clave_primadominical == 1 || cantidad_clave_primavacacional == 1 || cantidad_clave_otrosingresos == 1 || cantidad_clave_otrosingresos == 1 || cantidad_clave_ausencia == 1 || cantidad_clave_071 == 1 || cantidad_clave_107){
      
      if(cantidad_clave == 1){
        $("#claveMostrarSalario").css("display","block");
      }
      if(cantidad_clave_imss == 1){
        $("#claveMostrarIMSS").css("display","block");
      }
      if(cantidad_clave_isr == 1){
        $("#claveMostrarISR").css("display","block");
      }

      if(cantidad_clave_horasextra == 1){
        $("#claveMostrarHorasExtra").css("display","block");
      }
      if(cantidad_clave_primadominical == 1){
        $("#claveMostrarPrimaDominical").css("display","block");
      }
      if(cantidad_clave_primavacacional == 1){
        $("#claveMostrarPrimaVacacional").css("display","block");
      }

      if(cantidad_clave_otrosingresos == 1){
        $("#claveMostrarOtrosIngresos").css("display","block");
      }
      if(cantidad_clave_descuentoincapacidad == 1){
        $("#claveMostrarDescuentoIncapacidad").css("display","block");
      }
      if(cantidad_clave_ausencia == 1){
        $("#claveMostrarAusencia").css("display","block");
      }

      if(cantidad_clave_071 == 1){
        $("#claveMostrar071").css("display","block");
      }

      if(cantidad_clave_107 == 1){
        $("#claveMostrar107").css("display","block");
      }

      $('#agregar_clave_salario').modal({backdrop: 'static', keyboard: false})  
    }

    $("#agregarClaveSalario").click(function(){
      
      let clave = $("#txtClaveSalario").val().trim();
      let claveIMSS = $("#txtClaveIMSS").val().trim();
      let claveISR = $("#txtClaveISR").val().trim();
      let claveHorasExtra = $("#txtClaveHorasExtra").val().trim();
      let clavePrimaDominical = $("#txtClavePrimaDominical").val().trim();
      let clavePrimaVacacional = $("#txtClavePrimaVacacional").val().trim();
      let claveOtrosIngresos = $("#txtClaveOtrosIngresos").val().trim();
      let claveDescuentoIncapacidad = $("#txtClaveDescuentoIncapacidad").val().trim();
      let claveAusencia = $("#txtClaveAusencia").val().trim();
      let clave071 = $("#txtClave071").val().trim();
      let clave107 = $("#txtClave107").val().trim();
      let msg;
      let token = $("#csr_token_UT5JP").val();

      $("#CancelClaveSalario").prop("disabled", true);
      $("#agregarClaveSalario").prop("disabled", true);

      if(cantidad_clave == 1){
        if (clave == "") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Es necesario ingresar una clave para el salario.",
          });
          $("#CancelClaveSalario").prop("disabled", false);
          $("#agregarClaveSalario").prop("disabled", false);
          return;
        }

        if(clave.length > 15){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "No puedes agregar una clave de más de 15 caracteres."
            });
            $("#CancelClaveSalario").prop("disabled", false);
            $("#agregarClaveSalario").prop("disabled", false);
            return;
        }
      }

      if(cantidad_clave_imss == 1){
        if (claveIMSS == "") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Es necesario ingresar la clave del IMSS.",
          });
          $("#CancelClaveSalario").prop("disabled", false);
          $("#agregarClaveSalario").prop("disabled", false);
          return;
        }

        if(claveIMSS.length > 15){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "No puedes agregar una clave de más de 15 caracteres."
            });
            $("#CancelClaveSalario").prop("disabled", false);
            $("#agregarClaveSalario").prop("disabled", false);
            return;
        }
      }

      if(cantidad_clave_isr == 1){
        if (claveISR == "") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Es necesario ingresar la clave del ISR.",
          });
          $("#CancelClaveSalario").prop("disabled", false);
          $("#agregarClaveSalario").prop("disabled", false);
          return;
        }

        if(claveISR.length > 15){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "No puedes agregar una clave de más de 15 caracteres."
            });
            $("#CancelClaveSalario").prop("disabled", false);
            $("#agregarClaveSalario").prop("disabled", false);
            return;
        }
      }

      if(cantidad_clave_horasextra == 1){
        if (claveHorasExtra == "") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Es necesario ingresar una clave para las horas extras.",
          });
          $("#CancelClaveSalario").prop("disabled", false);
          $("#agregarClaveSalario").prop("disabled", false);
          return;
        }

        if(claveHorasExtra.length > 15){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "No puedes agregar una clave de más de 15 caracteres."
            });
            $("#CancelClaveSalario").prop("disabled", false);
            $("#agregarClaveSalario").prop("disabled", false);
            return;
        }
      }

      if(cantidad_clave_primadominical == 1){
        if (clavePrimaDominical == "") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Es necesario ingresar la clave de la prima dominical.",
          });
          $("#CancelClaveSalario").prop("disabled", false);
          $("#agregarClaveSalario").prop("disabled", false);
          return;
        }

        if(clavePrimaDominical.length > 15){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "No puedes agregar una clave de más de 15 caracteres."
            });
            $("#CancelClaveSalario").prop("disabled", false);
            $("#agregarClaveSalario").prop("disabled", false);
            return;
        }
      }

      if(cantidad_clave_primavacacional == 1){
        if (clavePrimaVacacional == "") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Es necesario ingresar la clave de la prima vacacional.",
          });
          $("#CancelClaveSalario").prop("disabled", false);
          $("#agregarClaveSalario").prop("disabled", false);
          return;
        }

        if(clavePrimaVacacional.length > 15){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "No puedes agregar una clave de más de 15 caracteres."
            });
            $("#CancelClaveSalario").prop("disabled", false);
            $("#agregarClaveSalario").prop("disabled", false);
            return;
        }
      }

      if(cantidad_clave_otrosingresos == 1){
        if (claveOtrosIngresos == "") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Es necesario ingresar una clave para otros ingresos por salarios.",
          });
          $("#CancelClaveSalario").prop("disabled", false);
          $("#agregarClaveSalario").prop("disabled", false);
          return;
        }

        if(claveOtrosIngresos.length > 15){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "No puedes agregar una clave de más de 15 caracteres."
            });
            $("#CancelClaveSalario").prop("disabled", false);
            $("#agregarClaveSalario").prop("disabled", false);
            return;
        }
      }

      if(cantidad_clave_descuentoincapacidad == 1){
        if (claveDescuentoIncapacidad == "") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Es necesario ingresar la clave del descuento por incapacidad.",
          });
          $("#CancelClaveSalario").prop("disabled", false);
          $("#agregarClaveSalario").prop("disabled", false);
          return;
        }

        if(claveDescuentoIncapacidad.length > 15){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "No puedes agregar una clave de más de 15 caracteres."
            });
            $("#CancelClaveSalario").prop("disabled", false);
            $("#agregarClaveSalario").prop("disabled", false);
            return;
        }
      }

      if(cantidad_clave_ausencia == 1){
        if (claveAusencia == "") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Es necesario ingresar la clave de ausencia.",
          });
          $("#CancelClaveSalario").prop("disabled", false);
          $("#agregarClaveSalario").prop("disabled", false);
          return;
        }

        if(claveAusencia.length > 15){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "No puedes agregar una clave de más de 15 caracteres."
            });
            $("#CancelClaveSalario").prop("disabled", false);
            $("#agregarClaveSalario").prop("disabled", false);
            return;
        }
      }

      if(cantidad_clave_071 == 1){
        if (clave071 == "") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Es necesario ingresar una clave para el ajuste en subsidio para el empleo.",
          });
          $("#CancelClaveSalario").prop("disabled", false);
          $("#agregarClaveSalario").prop("disabled", false);
          return;
        }

        if(clave071.length > 15){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "No puedes agregar una clave de más de 15 caracteres."
            });
            $("#CancelClaveSalario").prop("disabled", false);
            $("#agregarClaveSalario").prop("disabled", false);
            return;
        }
      }

      if(cantidad_clave_107 == 1){
        if (clave107 == "") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Es necesario ingresar una clave para el ajuste al subsidio causado.",
          });
          $("#CancelClaveSalario").prop("disabled", false);
          $("#agregarClaveSalario").prop("disabled", false);
          return;
        }

        if(clave107.length > 15){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "No puedes agregar una clave de más de 15 caracteres."
            });
            $("#CancelClaveSalario").prop("disabled", false);
            $("#agregarClaveSalario").prop("disabled", false);
            return;
        }
      }


        $.ajax({
          type: 'POST',
          url: 'functions/agregarClavesNecesarias.php',
          data: {
            claveSalario: clave,
            existeclaveSalario: cantidad_clave,
            claveIMSS: claveIMSS,
            existeclaveIMSS: cantidad_clave_imss,
            claveISR: claveISR,
            existeclaveISR: cantidad_clave_isr,
            claveHorasExtra: claveHorasExtra,
            existeclaveHorasExtra: cantidad_clave_horasextra,
            clavePrimaDominical: clavePrimaDominical,
            existeclavePrimaDominical: cantidad_clave_primadominical,
            clavePrimaVacacional: clavePrimaVacacional,
            existeclavePrimaVacacional: cantidad_clave_primavacacional,
            claveOtrosIngresos: claveOtrosIngresos,
            existeclaveIngresos: cantidad_clave_otrosingresos,
            claveDescuentoIncapacidad: claveDescuentoIncapacidad,
            existeclaveDescuentoIncapacidad: cantidad_clave_descuentoincapacidad,
            claveAusencia: claveAusencia,
            existeclaveAusencia: cantidad_clave_ausencia,
            clave_071: clave071,
            existeClave071: cantidad_clave_071,
            clave_107: clave107,
            existeClave107: cantidad_clave_107,
            csr_token_UT5JP: token
          },
          success: function(data) {

            if (data == "exito") {

               Lobibox.notify("success", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/checkmark.svg',
                    msg: "Se han agregado las claves"
               });

               $("#agregar_clave_salario").modal("hide");

            } 

            if (data == "existe-clave-salario" || data == "existe-clave-imss" || data == "existe-clave-isr" ||
                data == "existe-clave-HorasExtra" || data == "existe-clave-PrimaDominical" || data == "existe-clave-PrimaVacacional" ||
                data == "existe-clave-OtrosIngresos" || data == "existe-clave-DescuentoIncapacidad" || data == "existe-clave-Ausencia" || data == "existe-clave-071" || data == "existe-clave-107") {

              if (data == "existe-clave-salario"){
                msg = "La clave del salario ya esta agregada, ingresa una diferente.";
              }
              if (data == "existe-clave-imss"){
                msg = "La clave del imss ya esta agregada, ingresa una diferente.";
              }
              if (data == "existe-clave-isr"){
                msg = "La clave del isr ya esta agregada, ingresa una diferente.";
              }

              if (data == "existe-clave-HorasExtra"){
                msg = "La clave de las horas extra ya esta agregada, ingresa una diferente.";
              }
              if (data == "existe-clave-PrimaDominical"){
                msg = "La clave de la prima dominical ya esta agregada, ingresa una diferente.";
              }
              if (data == "existe-clave-PrimaVacacional"){
                msg = "La clave de la prima vacacional ya esta agregada, ingresa una diferente.";
              }

              if (data == "existe-clave-OtrosIngresos"){
                msg = "La clave de otros ingresos ya esta agregada, ingresa una diferente.";
              }
              if (data == "existe-clave-DescuentoIncapacidad"){
                msg = "La clave del descuento por incapacidad ya esta agregada, ingresa una diferente.";
              }
              if (data == "existe-clave-Ausencia"){
                msg = "La clave de ausencia ya esta agregada, ingresa una diferente.";
              }

              if (data == "existe-clave-071"){
                msg = "La clave de ajuste en subsidio al empleo ya esta agregada, ingresa una diferente.";
              }

              if (data == "existe-clave-107"){
                msg = "La clave de ajuste al subsidio causado ya esta agregada, ingresa una diferente.";
              }

              Lobibox.notify("error", {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: false,
                img: '../../img/timdesk/warning_circle.svg',
                msg: msg
              });

              $("#CancelClaveSalario").prop("disabled", false);
              $("#agregarClaveSalario").prop("disabled", false);

            }

            if (data == "existe-concepto-salario" || data == "existe-concepto-imss" || data == "existe-concepto-isr" ||
                data == "existe-concepto-HorasExtra" || data == "existe-concepto-PrimaDominical" || data == "existe-concepto-PrimaVacacional" ||
                data == "existe-concepto-OtrosIngresos" || data == "existe-concepto-DescuentoIncapacidad" || data == "existe-concepto-Ausencia" || data == "existe-concepto-071" || data == "existe-concepto-107") {

              if (data == "existe-concepto-salario"){
                msg = "El concepto del salario ya esta agregada.";
                cantidad_clave = 0;
                $("#claveMostrarSalario").css("display","none");
              }
              if (data == "existe-concepto-imss"){
                msg = "El concepto del imss ya esta agregada.";
                cantidad_clave_imss = 0;
                $("#claveMostrarIMSS").css("display","none");
              }
              if (data == "existe-concepto-isr"){
                msg = "El concepto del isr ya esta agregada.";
                cantidad_clave_isr = 0;
                $("#claveMostrarISR").css("display","none");
              }

              if (data == "existe-concepto-HorasExtra"){
                msg = "El concepto de las horas extras ya esta agregado.";
                cantidad_clave_horasextra = 0;
                $("#claveMostrarHorasExtra").css("display","none");
              }
              if (data == "existe-concepto-PrimaDominical"){
                msg = "El concepto de la prima dominical ya esta agregado.";
                cantidad_clave_primadominical = 0;
                $("#claveMostrarPrimaDominical").css("display","none");
              }
              if (data == "existe-concepto-PrimaVacacional"){
                msg = "El concepto de la prima vacacional ya esta agregado.";
                cantidad_clave_primavacacional = 0;
                $("#claveMostrarPrimaVacacional").css("display","none");
              }

              if (data == "existe-concepto-OtrosIngresos"){
                msg = "El concepto de otros ingresos por salario ya esta agregado.";
                cantidad_clave_otrosingresos = 0;
                $("#claveMostrarOtrosIngresos").css("display","none");
              }
              if (data == "existe-concepto-DescuentoIncapacidad"){
                msg = "El concepto de de descuento por incapacidad ya esta agregado.";
                cantidad_clave_descuentoincapacidad = 0;
                $("#claveMostrarDescuentoIncapacidad").css("display","none");
              }
              if (data == "existe-concepto-Ausencia"){
                msg = "El concepto de ausencia ya esta agregado.";
                cantidad_clave_ausencia = 0;
                $("#claveMostrarAusencia").css("display","none");
              }

              if (data == "existe-concepto-071"){
                msg = "El concepto de ajuste en subsidio para el empleo ya esta agregado.";
                cantidad_clave_071 = 0;
                $("#claveMostrar071").css("display","none");
              }
              if (data == "existe-concepto-107"){
                msg = "El concepto de ajuste al subsidio causado ya esta agregado.";
                cantidad_clave_107 = 0;
                $("#claveMostrar107").css("display","none");
              }
              Lobibox.notify("error", {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: false,
                img: '../../img/timdesk/warning_circle.svg',
                msg: msg
              });

            }


          }
        });


    });
  
  var facturasRelacionadas;
  function mostrarTimbrado(){
    let txt = '<center>' +
                '<div class="row" style="position: relative; top:23px;">' +
                  '<div class="col-lg-9">' +
                     '<div class="row">' +
                        '<div class="col-lg-8">' +
                            '<label>Cfdi relacionados:</label>' +
                                '<select class="form-control" id="facturas_relacionadas_id">' +
                                '</select>' +
                        '</div>' +
                        '<div class="col-lg-4">' +
                          '<br>' +
                          '<button type="button" class="btn btn-custom btn-custom--border-blue" id="agregarRelacion">+</button>' +
                          '<a href="#" class="btn btn-custom btn-custom--border-blue" id="descargarPDF" target="_self" > <i class="fas fa-file-invoice"></i> PDF </a>' +
                          '<a href="#" class="btn btn-custom btn-custom--border-blue" id="descargarXML" target="_self"> <i class="far fa-file-alt"></i> XML </a>' +
                        '</div>' +
                    '</div>' +
                  '</div>' +
                  '<div class="col-lg-3" style="top: 24px;">' +
                    '<button id="timbrarNominaIndividual" class="btn btn-custom btn-custom--border-blue">Timbrar empleado</button>' +
                  '</div>' +
                '</div>' +
                '<br><br>' +
                '<div class="row">' +
                  '<div class="col-lg-12">' +
                    '<label><b>CFDI agregados para relacionar:</b></label><br>' +
                    '<span id="cfdiRelacionado">No hay ningún CFDI relacionado.</span>' +
                  '</div> ' + 
                '</div>' +
              '</center>';

    $("#timbradoNomina").html(txt);

    let idEmpleado = $("#empleado").val();  
    let token = $("#csr_token_UT5JP").val();
    $.ajax({
      type: 'POST',
      url: 'functions/cargarFacturasRelacionadas.php',
      data: {
        csr_token_UT5JP: token,
        idEmpleado: idEmpleado,
        tipo: 1
      },
      success: function(data) {

         $("#facturas_relacionadas_id").html(data);

         facturasRelacionadas = new SlimSelect({
          select: '#facturas_relacionadas_id',
          deselectLabel: '<span class="">✖</span>'
        });
      } 
    });

    

    $("#funcionesNomina").css("display","block");     

    if(autorizadaGlobal == 1){
      $("#funcionesNomina").css("display","none");    
    }

    $("#mostrarFuncionesFactura").css("display","none");
    $("#mostrarFuncionesFactura").html("");  

    
    loadDataTables(idNomina, idEmpleado);
  }

  function mostrarOpcionesFactura(){

    let txt = '<center>' +
                '<div class="row" style="position: relative; top:23px;">' +
                  '<div class="col-lg-6">' +
                    '<button id="cancelarNominaIndividual" class="btn btn-custom btn-custom--red">Cancelar</button>' +
                  '</div>' +
                  '<div class="col-lg-6">' +
                    '<b>Fecha de timbrado:</b><br>' + fechaTimbrado +
                  '</div>' +
                '</div>' +
              '</center>';

    $("#timbradoNomina").html(txt);

      
    let funcionesFactura = '<center>' +
                            '<div class="row">' +
                              '<div class="col-lg-3">' +
                                '<a href="functions/download_pdf.php?value=' + idFactura + '" class="btn btn-custom btn-custom--blue" target="_blank">Descargar pdf</a>' +
                              '</div>' +
                              '<div class="col-lg-3">' +
                                '<a href="functions/download_xml.php?value=' + idFactura + '" class="btn btn-custom btn-custom--blue" target="_blank">Descargar xml</a>' +
                              '</div>' +
                              '<div class="col-lg-3">' +
                                '<a href="functions/download_zip.php?value=' + idFactura + '" class="btn btn-custom btn-custom--blue" target="_blank">Descargar zip</a>' +
                               '</div>' +
                               '<div class="col-lg-3">' + 
                                '<a href="#" class="btn btn-custom btn-custom--blue" id="send_email" data-toggle="modal" data-target="#enviarFactura">Enviar por email</a>' +
                              '</div>' +
                            '</div>' +
                          '</center>';

    $("#funcionesNomina").css("display","none");     

    if(autorizadaGlobal == 1){
      $("#funcionesNomina").css("display","none");    
    }
    $("#mostrarFuncionesFactura").css("display","block");
    $("#mostrarFuncionesFactura").html(funcionesFactura);

    let idEmpleado = $("#empleado").val();
    loadDataTables(idNomina, idEmpleado);
  }

    let idFactura = "";
    let fechaTimbrado = "";
    let autorizadaGlobal = "";
    //selectempleado 
    $("#empleado").change(function(){

      let token = $("#csr_token_UT5JP").val();
      let idEmpleado = $("#empleado").val();

      if(idEmpleado < 1){
        Lobibox.notify("error", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/warning_circle.svg',
          msg: "Selecciona un empleado para modificar su estado de nómina."
        });
        return;
      }

        $.ajax({
          type: 'POST',
          url: 'functions/cargarNominaEmpleado.php',
          data: {
            idNomina: idNomina,
            idEmpleado: idEmpleado,
            csr_token_UT5JP: token
          },
          success: function(data) {

            var datos = JSON.parse(data);
            if (datos.estatus == "exito") {

               $("#NombreEmpleado").html(datos.nombreEmpleado);
               $("#RFC").html(datos.rfc);
               $("#NSS").html(datos.nss);
               $("#Turno").html(datos.Turno);
               $("#Puesto").html(datos.puesto);
               $("#FechaIngreso").html(datos.FechaIngreso);
               $("#txtDestino").val(datos.email);
               idNominaEmpleado = datos.idNominaEmpleado;
               autorizadaGlobal = datos.autorizada;

               if(datos.Exento == 1){
                  $('#cbboxExcluir').prop('checked', true);
               }
               else{
                  $('#cbboxExcluir').prop('checked', false);
               }

               if(datos.estadoTimbrado == 0 || datos.estadoTimbrado == 2){

                  mostrarTimbrado();                                     
                    
               }
               if(datos.estadoTimbrado == 1){
                  idFactura = datos.idFactura;
                  fechaTimbrado = datos.fechaTimbrado;
                  mostrarOpcionesFactura();
                    
               }

               loadDataTables(idNomina, idEmpleado);

               if(idNominaEmpleado != null){
                 
                 $("#opcionesExportacion").css("display","block");
                 nuevo = 0;
              }
              else{
                $("#opcionesExportacion").css("display","none");
                $("#funcionesNomina").css("display","none");
              }

            } 
            else {

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

            }
          }
        });

    });


    $("#cbboxExcluir").click(function(){

      let token = $("#csr_token_UT5JP").val(); 
      let idEmpleado = $("#empleado").val();
      let textoSweet, activo, msgEstado;

      if(idEmpleado < 1){
        Lobibox.notify("error", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/warning_circle.svg',
          msg: "Selecciona un empleado para modificar su estado de nómina."
        });

        if ($('#cbboxExcluir').is(":checked")){
          $('#cbboxExcluir').prop('checked', false);
        }
        else{
          $('#cbboxExcluir').prop('checked', true);
        }
        return;
      }

      if ($('#cbboxExcluir').is(":checked"))
      {
        textoSweet = "Se excluirá al empleado de la nómina.";
        activo = 1;
        msgEstado = "Se ha excluido al empleado de la nómina.";

      }
      else{
        textoSweet = "Se volverá a incluir al empleado en la nómina.";
        activo = 0;
        msgEstado = "Se ha incluido al empleado en la nómina.";
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
          text: textoSweet,
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
              url: 'functions/estadoNominaEmpleado.php',
              data: {
                idNomina: idNomina,
                idEmpleado: idEmpleado,
                activo: activo,
                csr_token_UT5JP: token
              },
              success: function(data) {

                if (data == "exito") {

                  Lobibox.notify("success", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/checkmark.svg',
                    msg: msgEstado
                  });

                } 
                else {

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

                }
              }
            });



          } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
          ) {

            if ($('#cbboxExcluir').is(":checked")){
              $('#cbboxExcluir').prop('checked', false);
            }
            else{
              $('#cbboxExcluir').prop('checked', true);
            }

          }
        });
      
    });

    $("#cargarPercepcionDeduccion,#cargarDeduccion,#cargarOtrosPagos").click(function(){

      let idEmpleado = $("#empleado").val();
      let token = $("#csr_token_UT5JP").val(); 

      if(idEmpleado == "" || idEmpleado < 1){
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

      $("#claveMostrar").css("display","none");
      $("#mostrarNuevoConcepto").css("display","none");
      $('#checkNuevoConcepto').prop('checked', false);
      $("#txtConcepto").val("");
      disponibleClave = 0;

      $("#agregar_percepcion").modal('show');

      let element = this.id;
      let tipo;
      if(element == "cargarPercepcionDeduccion"){
        tipo = 1;
        $("#tipoMovimiento").val(1);
        $("#percepcionDeducionTitulo").html("Agregar percepción");
        $("#mostrarExento").css("display","block");
      }
      if(element == "cargarDeduccion"){
        tipo = 2;
        $("#tipoMovimiento").val(2);
        $("#percepcionDeducionTitulo").html("Agregar deducción");
        $("#mostrarExento").css("display","none");
      }
      if(element == "cargarOtrosPagos"){
        tipo = 3;
        $("#tipoMovimiento").val(3);
        $("#percepcionDeducionTitulo").html("Agregar otro pago");
        $("#mostrarExento").css("display","none");
      }

      $.ajax({
          type: 'POST',
          url: 'functions/cargarConceptosNomina.php',
          data: {
            csr_token_UT5JP: token,
            tipo: tipo
          },
          success: function(data) {

             $("#cmbConcepto").html(data);

          } 
      });

        selectConcepto.destroy();
        selectClaveSAT.destroy();

        $.ajax({
          type: 'POST',
          url: 'functions/cargarClavesSATNomina.php',
          data: {
            csr_token_UT5JP: token,
            tipo: tipo
          },
          success: function(data) {

             $("#cmbClaveSAT").html(data);

          } 
        });

        selectConcepto = new SlimSelect({
          select: '#cmbConcepto',
          deselectLabel: '<span class="">✖</span>'
        });

        selectClaveSAT = new SlimSelect({
          select: '#cmbClaveSAT',
          deselectLabel: '<span class="">✖</span>'
        });
    });


    $("#checkNuevoConcepto").change(function(){

        if ($('input#checkNuevoConcepto').is(':checked')) {
          $("#mostrarNuevoConcepto").css("display", "block");
        }
        else{
          $("#mostrarNuevoConcepto").css("display", "none");
        }       

    });

    $("#agregarPercepcionDeduccion").click(function(){

      let nuevoConceptoCheck;
      if($('input#checkNuevoConcepto').is(':checked')) {

        if($("#txtConcepto").val().trim() == null || $("#txtConcepto").val().trim() == ""){
          Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "Ingresa el nuevo concepto"
            });
          return;
        }

        if($("#cmbClaveSAT").val() == null){
          Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "Selecciona la clave SAT."
            });
          return;
        }

        nuevoConceptoCheck = 1;
      }
      else{

        if($("#cmbConcepto").val() == null){
          Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "Selecciona el concepto"
            });
          return;
        }
        nuevoConceptoCheck = 0;
      }

      let concepto = $("#cmbConcepto").val();
      let claveSAT = $("#cmbClaveSAT").val();
      let nuevoConcepto = $("#txtConcepto").val().trim();
      let idEmpleado = $("#empleado").val();
      let importeObj = numeral($("#txtImporte").val().trim());
      let importe = importeObj.value();
      let tipo = $('#tipoMovimiento').val();
      let token = $("#csr_token_UT5JP").val(); 
      let exento, clave = "";

      if ($('#cbboxExento').is(":checked")){
        exento = 1;
      }
      else{
        exento = 0;
      }

      if(tipo == 2){
        exento = 0;
      }

      if(idEmpleado == "" || idEmpleado < 1){
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

      if(nuevoConcepto.length >= 100){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "No puedes agregar un concepto de más de 100 caracteres."
          });
          return;
      }

      if(importe == "" || importe == null){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Ingresa el importe"
          });
          return;
      }

      //es necesario la clave
      if(disponibleClave == 1){
        clave = $("#txtClave").val().trim();

        if (clave == "") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Es necesario ingresar una clave.",
          });
          return;
        }

        if(clave.length > 15){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "No puedes agregar una clave de más de 15 caracteres."
            });
            return;
        }
      }

      let fechaPago = '<?php echo $row_datos_nomina['fecha_pago_or'];?>';

      $("#CancelaragregarPercepcionDeduccion").prop("disabled", true);
      $("#agregarPercepcionDeduccion").prop("disabled", true);

      $.ajax({
          type: 'POST',
          url: 'functions/agregarNominaEmpleado.php',
          data: {
            idNominaEmpleado: idNominaEmpleado,
            idEmpleado: idEmpleado,
            csr_token_UT5JP: token,
            concepto: concepto,
            importe: importe,
            tipo: tipo,
            exento: exento,
            idNomina: idNomina,
            disponibleClave: disponibleClave,
            clave: clave,
            fechaPago: fechaPago,
            nuevoConcepto: nuevoConcepto,
            nuevoConceptoCheck: nuevoConceptoCheck,
            claveSAT: claveSAT
          },
          success: function(data) {

            if (data == "exito") {

              Lobibox.notify("success", {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: false,
                img: '../../img/timdesk/checkmark.svg',
                msg: "Se agregó el concepto a la nómina."
              });

              $("#txtImporte").val("");
              $("#txtClave").val("");
              $("#claveMostrar").css("display","none");
              disponibleClave = 0;

              selectConcepto.set([]);

              $('#cbboxExento').prop('checked', false);
              $(".percepcion").prop("checked", true);
              
              $("#CancelaragregarPercepcionDeduccion").prop("disabled", false);
              $("#agregarPercepcionDeduccion").prop("disabled", false);

              $("#agregar_percepcion").modal('hide');
              $('#tblNominaEmpleado').DataTable().ajax.reload();

            } 
            if (data == "fallo") {

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
              $("#CancelaragregarPercepcionDeduccion").prop("disabled", false);
              $("#agregarPercepcionDeduccion").prop("disabled", false);

            }

            if (data == "fallo-agregar") {
                Lobibox.notify("error", {
                  size: 'mini',
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: 'center top', //or 'center bottom'
                  icon: false,
                  img: '../../img/timdesk/warning_circle.svg',
                  msg: "No puedes agregar un concepto a una nómina timbrada."
                });
                $("#CancelaragregarPercepcionDeduccion").prop("disabled", false);
                $("#agregarPercepcionDeduccion").prop("disabled", false);

            }

            if (data == "existe-concepto") {
              Lobibox.notify("warning", {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: false,
                img: '../../img/timdesk/warning_circle.svg',
                msg: "El concepto ya esta agregado en la nómina."
              });
              $("#CancelaragregarPercepcionDeduccion").prop("disabled", false);
              $("#agregarPercepcionDeduccion").prop("disabled", false);
            }

            if(data == "existe-clave"){
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "Ya existe esa clave.",
              });
              $("#CancelaragregarPercepcionDeduccion").prop("disabled", false);
              $("#agregarPercepcionDeduccion").prop("disabled", false);
            }

            if(data == "existe-concepto-clave"){
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "Ese concepto ya tiene asignada una clave.",
              });
              $("#CancelaragregarPercepcionDeduccion").prop("disabled", false);
              $("#agregarPercepcionDeduccion").prop("disabled", false);
            }
          }
        });

    });

    
    let disponibleClave = 0;
    $("#cmbClaveSAT").change(function(){

      let tipo = $("#tipoMovimiento").val();

      if(tipo != 3){

          let val = $( "#cmbClaveSAT option:selected" ).text();
          let array = val.split("-");

          if(array.length == 3){
            disponibleClave = 0;
            $("#claveMostrar").css("display","none");
          }

          if(array.length == 2){
            disponibleClave = 1;
            $("#claveMostrar").css("display","block");
          }
      
      }
      
    });

    $("#editarPercepcionDeduccion").click(function(){

      let idEmpleado = $("#empleado").val();
      let idDetalleNomina = $("#idDetalleNomina").val().trim();
      let importeObj = numeral($("#txtImporteEdit").val().trim());
      let importe = importeObj.value();
      let tipo = $('#txtTipoEdit').val().trim();
      let token = $("#csr_token_UT5JP").val(); 
      let tipo_concepto = $("#editTipoConcepto").val();
      let exento;

      let fechaPago = '<?php echo $row_datos_nomina['fecha_pago_or'];?>';

      if ($('#cbboxExentoEdit').is(":checked")){
        exento = 1;
      }
      else{
        exento = 0;
      }

      if(idEmpleado == "" || idEmpleado < 1){
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

      if(importe == "" || importe == null){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Ingresa el importe"
          });
          return;
      }

      $("#CancelarEditarPercepcionDeduccion").prop("disabled", true);
      $("#editarPercepcionDeduccion").prop("disabled", true);

      $.ajax({
          type: 'POST',
          url: 'functions/editarNominaEmpleado.php',
          data: {
            idDetalleNomina: idDetalleNomina,
            csr_token_UT5JP: token,
            importe: importe,
            tipo: tipo,
            exento: exento,
            idNominaEmpleado: idNominaEmpleado,
            idNomina: idNomina,
            idEmpleado: idEmpleado,
            tipo_concepto: tipo_concepto,
            fechaPago: fechaPago
          },
          success: function(data) {

            if (data == "exito") {

              Lobibox.notify("success", {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: false,
                img: '../../img/timdesk/checkmark.svg',
                msg: "Se modificó el concepto de la nómina."
              });
              $('#editar_percepcion').modal('hide');
              $('#tblNominaEmpleado').DataTable().ajax.reload();

            } 
            if (data == "fallo-edicion") {
                Lobibox.notify("error", {
                  size: 'mini',
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: 'center top', //or 'center bottom'
                  icon: false,
                  img: '../../img/timdesk/warning_circle.svg',
                  msg: "No puedes editar un concepto de una nómina timbrada."
                });
            }

            if (data == "fallo") {

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

            }

            $("#CancelarEditarPercepcionDeduccion").prop("disabled", false);
            $("#editarPercepcionDeduccion").prop("disabled", false);
          }
        });

    });

  
    $("#editarCreditoInfoFona").click(function(){
      let idEmpleado = $("#empleado").val();
      let idDetalleNomina = $("#idCreditoUnico").val().trim();
      let importeObj = numeral($("#txtCantidadAplicadaCredito").val().trim());
      let importe = importeObj.value();
      let tipoCredito = $("#txtTipoEditCredito").val().trim();
      let token = $("#csr_token_UT5JP").val(); 

      let fechaPago = '<?php echo $row_datos_nomina['fecha_pago_or'];?>';

      if(idEmpleado == "" || idEmpleado < 1){
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

      if(importe == "" || importe == null){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Ingresa el importe"
          });
          return;
      }

      $("#btnCancelarCredito").prop("disabled", true);
      $("#editarCreditoInfoFona").prop("disabled", true);

      $.ajax({
          type: 'POST',
          url: 'functions/editarNominaEmpleadoCreditos.php',
          data: {
            idDetalleNomina: idDetalleNomina,
            csr_token_UT5JP: token,
            importe: importe,
            idNominaEmpleado: idNominaEmpleado,
            idNomina: idNomina,
            idEmpleado: idEmpleado,
            tipoCredito: tipoCredito,
            fechaPago: fechaPago
          },
          success: function(data) {

            if (data == "pasa_credito") {
                Lobibox.notify("error", {
                  size: 'mini',
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: 'center top', //or 'center bottom'
                  icon: false,
                  img: '../../img/timdesk/warning_circle.svg',
                  msg: "No puedes ingresar un importe mayor del crédito restante."
                });
            }

            if (data == "exito") {

              Lobibox.notify("success", {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: false,
                img: '../../img/timdesk/checkmark.svg',
                msg: "Se modificó el crédito de la nómina."
              });
              $('#creditoFonacotInfonavitModal').modal('hide');
              $('#tblNominaEmpleado').DataTable().ajax.reload();

            } 

            if (data == "fallo") {

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

            }

            $("#btnCancelarCredito").prop("disabled", false);
            $("#editarCreditoInfoFona").prop("disabled", false);
          }
        });

    });


    function cargarDetalleNominaEmpleado(idDetalleNomina,tipo){

      let token = $("#csr_token_UT5JP").val();

        $.ajax({
          type: 'POST',
          url: 'functions/cargarDetalleNominaEmpleado.php',
          data: {
            idDetalleNomina: idDetalleNomina,
            tipo: tipo,
            csr_token_UT5JP: token
          },
          success: function(data) {

            var datos = JSON.parse(data);
            let importeEdit = 0.00;

            if (datos.estatus == "exito") {

                importeEdit = datos.importe_total;

                $("#idDetalleNomina").val(datos.idDetalleNomina);
                $("#txtConceptoEdit").val(datos.concepto);
                $("#txtImporteEdit").val(importeEdit);
                $("#editTipoConcepto").val(datos.tipo_concepto);
                $("#editExento").css("display","block");
                $('#cbboxExentoEdit').prop('checked', false);

                //salarios , prima dominical
               if(datos.tipo_concepto == 1 || datos.tipo_concepto == 6 || datos.tipo_concepto == 11){
                $("#editExento").css("display","none");  

               }

               if(datos.tipo_concepto == 1 || datos.tipo_concepto == 11){

                  $("#percepcionDeducionTituloEdit").html("Editar salario");
                  $("#editar_percepcion").modal('toggle');
               }

               //percepciones/deducciones
               if(datos.tipo_concepto == 2){
                    
                   $("#editPercDed").css("display","block");
                   if(tipo == 2){
                      $("#editExento").css("display","none");
                   }
                   if(tipo == 1){
                      $("#editExento").css("display","block");
                   }

                   if(datos.exento == 1){
                    $('#cbboxExentoEdit').prop('checked', true);
                   }
                   else{
                    $('#cbboxExentoEdit').prop('checked', false);
                   }

                    $("#editar_percepcion").modal('toggle');

                    if(tipo == 1){
                      $("#percepcionDeducionTituloEdit").html("Editar percepción");
                    }
                    else{
                      $("#percepcionDeducionTituloEdit").html("Editar deducción");
                    }

                    $("#txtTipoEdit").val(tipo);

               }

               //Otros pagos
               if(datos.tipo_concepto == 100){

                  $("#percepcionDeducionTituloEdit").html("Editar otro pago");
                  $("#editar_percepcion").modal('toggle');
                  $("#txtTipoEdit").val(tipo);

                  if(tipo == 3){
                      $("#editExento").css("display","none");
                   }
               }


               if(datos.tipo_concepto == 3 || datos.tipo_concepto == 7 || datos.tipo_concepto == 8){

                   let importeHoras = parseFloat(datos.importe) + parseFloat(datos.importe_exento);
                   $("#txtHorasEdit").val(datos.horas);
                   $("#txtImporteHorasEdit").val(importeHoras.toFixed(2));
                   $("#idDetalleNominaHorasExtras").val(datos.iddetallenomina);

                   if(datos.tipo_concepto == 3){
                      $("#tipoHorasEdit").val("Sencillas");
                      $("#txttipoHorasEdit").val("1");
                      $("#txtHorasEdit").attr("max",30);
                   }
                   if(datos.tipo_concepto == 7){
                      $("#tipoHorasEdit").val("Doble");
                      $("#txttipoHorasEdit").val("2");
                      $("#txtHorasEdit").attr("max",9);
                   }
                   if(datos.tipo_concepto == 8){
                      $("#tipoHorasEdit").val("Triple");
                      $("#txttipoHorasEdit").val("3");
                      $("#txtHorasEdit").attr("max",30);
                   }

                   $("#editar_horas_extras").modal('toggle');

               }
               

               if(datos.tipo_concepto == 4){
                  
                   $("#txtIdDetalleNominaTurnos").val(datos.iddetallenomina);
                   $("#txtTurnosExtraEdit").val(datos.dias);
                   $("#txtImporteTurnoEdit").val(datos.importe);

                   $("#turnosExtraModalEdit").modal('toggle');

               }

               if(datos.tipo_concepto == 5){
                  
                   $("#txtIdDetalleNominaFaltas").val(datos.iddetallenomina);
                   $("#txtDiasFaltaEdit").val(datos.dias);
                   $("#txtImporteDiasFaltaEdit").val(datos.importe);
                   $("#txtMotivoEdit").val(datos.concepto);

                   if(datos.relacion_tipo_deduccion_id == 6){
                    $("#mostrarEditIncapacidad").css("display", "block");
                    $("#txtmostrarIncapacidad").val(datos.incapacidad);
                   }
                   else{
                    $("#mostrarEditIncapacidad").css("display", "none");
                    $("#txtmostrarIncapacidad").val("");
                   }

                   $("#fechaIniFaltasEdit").val(datos.fecha_inicio_faltas);
                   $("#fechaFinFaltasEdit").val(datos.fecha_fin_faltas);


                   $("#diasFaltaModalEdit").modal('toggle');

               }

               //prima vacacional
               if(datos.tipo_concepto == 10){
                  
                   $("#txtDiasPrimaVacEdit").val(datos.dias);
                   $("#fechaIniPVEdit").val(datos.fechaIniPV);
                   $("#fechaFinPVEdit").val(datos.fechaFinPV);
                   $("#txtImportePrimaVacEdit").val(datos.importePrimaVac);

                   $("#primaVacacionalEditModal").modal('toggle');

               }

                //prima vacacional
               if(datos.tipo_concepto == 10){
                  
                   $("#txtDiasPrimaVacEdit").val(datos.dias);
                   $("#fechaIniPVEdit").val(datos.fechaIniPV);
                   $("#fechaFinPVEdit").val(datos.fechaFinPV);
                   $("#txtImportePrimaVacEdit").val(datos.importePrimaVac);

                   $("#primaVacacionalEditModal").modal('toggle');

               }

               //credito fonacot(12) o infonavit(13)
               if(datos.tipo_concepto == 12 || datos.tipo_concepto == 13 || datos.tipo_concepto == 14){
                  
                   if(datos.tipo_concepto == 12){
                      $("#tituloCreditoInfoFona").html("Crédito FONACOT");
                   }
                   if(datos.tipo_concepto == 13){
                      $("#tituloCreditoInfoFona").html("Crédito INFONAVIT");
                   }
                   if(datos.tipo_concepto == 14){
                      $("#tituloCreditoInfoFona").html("Pensión alimenticia");
                   }

                   $("#txtTipoEditCredito").val(datos.tipo_concepto);
                   $("#idCreditoUnico").val(idDetalleNomina);
                   $("#txtCreditoConceptoEdit").val(datos.concepto);
                   $("#txtCantidadAplicadaCredito").val(datos.importe);

                   

                   $("#creditoFonacotInfonavitModal").modal('toggle');

               }
               

            } 
            else {

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

            }
          }
        });

    }


    function loadDataTables(idNomina, idEmpleado){
        var table = $('#tblNominaEmpleado').DataTable();
        table.destroy();

        var idioma_espanol = {
          sProcessing: "Procesando...",
          sZeroRecords: "No se encontraron resultados",
          sEmptyTable: "Ningún dato disponible en esta tabla",
          sSearch: "<img src='../../img/timdesk/buscar.svg' width='20px' />",
          sLoadingRecords: "Cargando...",
          searchPlaceholder: "Buscar...",
          oPaginate: {
            sFirst: "Primero",
            sLast: "Último",
            sNext: "<img src='../../img/icons/pagination.svg' width='20px'/>",
            sPrevious:
              "<img src='../../img/icons/pagination.svg' width='20px' style='transform: scaleX(-1)'/>",
            },
        };

        $("#tblNominaEmpleado").dataTable({
          language: idioma_espanol,
          dom: "lfrtip",
          scrollX: true,
          lengthChange: true,
          info: false,
          ordering: false,
          pageLength: 50,
          paging: false,
          ajax : {
                  url : 'functions/function_nomina_empleado.php',
                  data: {
                    'idEmpleado' : idEmpleado,
                    'idNomina': idNomina
                  },
                  type : 'POST'
          },
          columns: [
            { data: "concepto" },
            { data: "importe gravado" },
            { data: "importe exento" },
            { data: "exento" },
            { data: "total" },
          ],
          columnDefs: [
            {
                targets: 1,
                className: 'align-right'
            },
            {
                targets: 2,
                className: 'align-right'
            },
            {
                targets: 3,
                className: 'align-center'
            },
            {
                targets: 4,
                className: 'align-center'
            }
          ],
        });

    }

    function eliminarDetalleNomina(idDetalleNomina, tipo){
    let token = $("#csr_token_UT5JP").val(); 
    let idEmpleado = $("#empleado").val();
    let fechaPago = '<?php echo $row_datos_nomina['fecha_pago_or'];?>';

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
        text: "Se eliminará este concepto de la nómina.",
        icon: "warning",
        showCancelButton: true,
        cancelButtonText: '<span class="verticalCenter">Cerrar</span>',
        confirmButtonText: '<span class="verticalCenter">Eliminar</span>',
        reverseButtons: true,
      })
      .then((result) => {
        if (result.isConfirmed) {

          $.ajax({
            type: 'POST',
            url: 'functions/eliminar_DetalleNomina.php',
            data: {
              idDetalleNomina: idDetalleNomina,
              idNominaEmpleado: idNominaEmpleado,
              idNomina: idNomina,
              idEmpleado : idEmpleado,
              tipo: tipo,
              csr_token_UT5JP: token,
              fechaPago: fechaPago
            },
            success: function(data) {

              if (data == "exito") {

                Lobibox.notify("success", {
                  size: 'mini',
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: 'center top', //or 'center bottom'
                  icon: false,
                  img: '../../img/timdesk/checkmark.svg',
                  msg: "Se ha eliminado el concepto de la nómina"
                });

                $('#tblNominaEmpleado').DataTable().ajax.reload();

              } 
              else if (data == "fallo-cancelacion") {
                Lobibox.notify("error", {
                  size: 'mini',
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: 'center top', //or 'center bottom'
                  icon: false,
                  img: '../../img/timdesk/warning_circle.svg',
                  msg: "No puedes eliminar un concepto de una nómina timbrada."
                });

              }
              else if (data == "fallo-salario") {
                Lobibox.notify("error", {
                  size: 'mini',
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: 'center top', //or 'center bottom'
                  icon: false,
                  img: '../../img/timdesk/warning_circle.svg',
                  msg: "No puedes eliminar el concepto por salario."
                });

              }
              else {

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

              }
            }
          });



        } else if (
          /* Read more about handling dismissals below */
          result.dismiss === Swal.DismissReason.cancel
        ) {}
      });
  }

  var selectConcepto, selectClaveSAT;
  $("#btnAgregarConcepto").click(function(){
        let concepto = $("#txtConcepto").val().trim();
        let token = $("#csr_token_UT5JP").val(); 
        let clave = $("#cmbClave").val().trim();
        let tipo = $('input[name="tipoConcepto"]:checked').val();

        if (concepto == "") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Es necesario ingresar un concepto.",
          });
          return;
        }

        if(concepto.length >= 50){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "No puedes agregar un concepto de más de 50 caracteres."
            });
            return;
        }

        if (concepto.toLowerCase() == "salario") {
          Lobibox.notify("warning", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/notificacion_error.svg",
              msg: "No puedes agregar el concepto salario.",
            });
          return;
        }

        $("#btnCancelarConcepto").prop("disabled", true);
        $("#btnAgregarConcepto").prop("disabled", true);

        $.ajax({
          type: 'POST',
          url: 'functions/agregar_Concepto.php',
          data: { 
                  concepto : concepto,
                  clave: clave,
                  tipo: tipo,
                  csr_token_UT5JP : token
                },
          success: function(r) {

                if(r == "exito"){
                  Lobibox.notify("success", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/checkmark.svg",
                    msg: "Concepto agregado!",
                  });
                  $("#txtConcepto").val("");

              $('#agregar_concepto').modal('hide');
            }
            
            if(r == "existe"){
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "Ya existe ese concepto.",
              });
            }

            if(r == "salario"){
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "No puedes agregar el concepto salario.",
              });
            }

            if(r == "fallo"){
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
            $("#btnCancelarConcepto").prop("disabled", false);
            $("#btnAgregarConcepto").prop("disabled", false);
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
            $("#btnCancelarConcepto").prop("disabled", false);
            $("#btnAgregarConcepto").prop("disabled", false);
          }
        });

     });
  
  let agregarClaveTurnoExtra = 0;
  let existeConceptoTurnoExtra = 0;
  $("#turnosExtra").click(function(){

    let idEmpleado = $("#empleado").val();
    let token = $("#csr_token_UT5JP").val();
    
    if(idEmpleado == "" || idEmpleado < 1){
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

    let idclave = 33;
    $.ajax({
          type: 'POST',
          url: 'functions/mostrarClaves.php',
          data: {
            idclave: idclave,
            tipo: 1,
            csr_token_UT5JP: token
          },
          success: function(data) {

            if(data < 1){
              $("#claveMostrarTurnoExtra").css("display","block");
              agregarClaveTurnoExtra = 1;
            }
            else{
              $("#claveMostrarTurnoExtra").css("display","none");
              agregarClaveTurnoExtra = 0;
            }
          }
        });

    //saber si existe el concepto
    $.ajax({
      type: 'POST',
      url: 'functions/existeConcepto.php',
      data: {
        idclave: idclave,
        tipo: 1,
        csr_token_UT5JP: token
      },
      success: function(data) {

        if(data < 1){
          $("#claveMostrarTurnoExtraConcepto").css("display","block");
          existeConceptoTurnoExtra = 1;
        }
        else{
          existeConceptoTurnoExtra = 0;
          $("#claveMostrarTurnoExtraConcepto").css("display","none");
        }
      }
    });

    $("#turnosExtraModal").modal('toggle');

  });


  $("#txtTurnosExtra,#txtTurnosExtraEdit").change(function(){

      let element = this.id;
      let turnos;

      if(element == "txtTurnosExtra"){
        $("#agregarTurnosExtra").prop("disabled", true);
        turnos = $("#txtTurnosExtra").val().trim();
      }

      if(element == "txtTurnosExtraEdit"){
        $("#modificarTurnosExtra").prop("disabled", true);
        turnos = $("#txtTurnosExtraEdit").val().trim();
      }
      
      let token = $("#csr_token_UT5JP").val();
      let idEmpleado = $("#empleado").val();


      if(turnos == "" || turnos == null || turnos < 1 ){
        Lobibox.notify("error", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/warning_circle.svg',
          msg: "Ingresa un número de turnos."
        });
        return;
      }
        
      if(isNaN(turnos)){
        Lobibox.notify("error", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/warning_circle.svg',
          msg: "Ingresa un número de turnos."
        });
        return;
      }


      if(idEmpleado == "" || idEmpleado < 1){
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

    $.ajax({
        type: 'POST',
        url: 'functions/calcularSalarioDiario.php',
        data: { 
                csr_token_UT5JP : token,
                idEmpleado: idEmpleado,
                diasfalta: turnos
              },
        success: function(r) {

              if(r == "fallo"){
                Lobibox.notify("warning", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top",
                  icon: true,
                  img: "../../img/timdesk/notificacion_error.svg",
                  msg: "No se pudo cargar el calculo del salario diario pero puedes ingresar el importe y los turnos extra.",
                });
                $("#agregarTurnosExtra").prop("disabled", false);
                $("#modificarTurnosExtra").prop("disabled", false);

              }
              else{
                
                if(element == "txtTurnosExtra"){
                  $("#txtImporteTurno").val(r);
                  $("#agregarTurnosExtra").prop("disabled", false);
                }

                if(element == "txtTurnosExtraEdit"){
                  $("#txtImporteTurnoEdit").val(r);
                  $("#modificarTurnosExtra").prop("disabled", false);
                }

              }
        
        },
        error: function(){
          Lobibox.notify("warning", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "No se pudo cargar el calculo del salario diario pero puedes ingresar el importe y los turnos extra.",
          });
          $("#agregarTurnosExtra").prop("disabled", false);
          $("#modificarTurnosExtra").prop("disabled", false);
        }
      });

  });


  $("#agregarTurnosExtra").click(function(){

    let idEmpleado = $("#empleado").val();
    let diasExtra = $("#txtTurnosExtra").val().trim();
    let ImporteTurnosObj = numeral($("#txtImporteTurno").val().trim());
    let ImporteTurnos = ImporteTurnosObj.value();
    let token = $("#csr_token_UT5JP").val();
    let claveHorasExtra = $("#txtClaveTurnoExtra").val().trim();

    if(diasExtra == "" || diasExtra == null || diasExtra < 1 ){
      Lobibox.notify("error", {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: 'center top', //or 'center bottom'
        icon: false,
        img: '../../img/timdesk/warning_circle.svg',
        msg: "Ingresa un número de turnos."
      });
      return;
    }
      
    if(isNaN(diasExtra)){
      Lobibox.notify("error", {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: 'center top', //or 'center bottom'
        icon: false,
        img: '../../img/timdesk/warning_circle.svg',
        msg: "Ingresa un número de turnos."
      });
      return;
    }

    if(idEmpleado == "" || idEmpleado < 1){
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

    if(ImporteTurnos == "" || ImporteTurnos == null){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Ingresa el importe"
          });
          return;
    }

    if(agregarClaveTurnoExtra == 1){
        if (claveHorasExtra == "") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Es necesario ingresar una clave para los turnos extras.",
          });
          return;
        }

        if(claveHorasExtra.length > 15){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "No puedes agregar una clave de más de 15 caracteres."
            });
            return;
        }
      }

    let fechaPago = '<?php echo $row_datos_nomina['fecha_pago_or'];?>';
    
    let nuevoConcepto = $("#txtConceptoTurnoExtra").val().trim();

    if(existeConceptoTurnoExtra == 1){
      if(nuevoConcepto == "" || nuevoConcepto == null){
        Lobibox.notify("error", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/warning_circle.svg',
          msg: "Necesitas ingresar un concepto."
        });
        return;
      }

      if(nuevoConcepto.length >= 100){
        Lobibox.notify("error", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/warning_circle.svg',
          msg: "No puedes agregar un concepto de más de 100 caracteres."
        });
        return;
      }
    }

    $("#agregarTurnosExtra").prop("disabled", true);

    $.ajax({
          type: 'POST',
          url: 'functions/agregar_TurnosExtra.php',
          data: { 
                  diasExtra : diasExtra,
                  ImporteTurnos : ImporteTurnos,
                  csr_token_UT5JP : token,
                  idNominaEmpleado: idNominaEmpleado,
                  idEmpleado: idEmpleado,
                  idNomina: idNomina,
                  claveHorasExtra: claveHorasExtra,
                  agregarClaveTurnoExtra: agregarClaveTurnoExtra,
                  fechaPago: fechaPago,
                  existeconcepto : existeConceptoTurnoExtra,
                  nuevoconcepto : nuevoConcepto
                },
          success: function(r) {

                if(r == "exito"){
                  Lobibox.notify("success", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/checkmark.svg",
                    msg: "Turnos extra agregados",
                  });
                  $("#txtTurnosExtra").val("");
                  $("#txtImporteTurno").val("");
                  $("#agregarTurnosExtra").prop("disabled", false);
                  $("#turnosExtraModal").modal('hide');
                  $('#tblNominaEmpleado').DataTable().ajax.reload();

                }

                if(r == "existe-clave-OtrosIngresos"){

                    Lobibox.notify("error", {
                      size: 'mini',
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: 'center top', //or 'center bottom'
                      icon: false,
                      img: '../../img/timdesk/warning_circle.svg',
                      msg: "La clave de turnos extras(otros ingresos por salarios) ya esta agregada, ingresa una diferente."
                    });
                    $("#agregarTurnosExtra").prop("disabled", false);
                }

                if(r == "existe-concepto-OtrosIngresos"){

                    agregarClaveTurnoExtra = 0;
                    $("#claveMostrarTurnoExtra").css("display","none");

                    Lobibox.notify("error", {
                      size: 'mini',
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: 'center top', //or 'center bottom'
                      icon: false,
                      img: '../../img/timdesk/warning_circle.svg',
                      msg: "El concepto de turnos extra(otros ingresos por salario) ya esta agregado."
                    });
                    $("#agregarTurnosExtra").prop("disabled", false);
                }

                if(r == "diaspaso"){
                  Lobibox.notify("error", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/notificacion_error.svg",
                    msg: "No puede agregar más de 3 turnos extra por semana",
                  });
                  $("#agregarTurnosExtra").prop("disabled", false);
                }

                if(r == "fallo"){
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
                  $("#agregarTurnosExtra").prop("disabled", false);
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
            $("#agregarTurnosExtra").prop("disabled", false);
          }
        });


  });


  $("#modificarTurnosExtra").click(function(){

    $("#modificarTurnosExtra").prop("disabled", true);

    let idEmpleado = $("#empleado").val();
    let diasExtra = $("#txtTurnosExtraEdit").val().trim();
    let idDetalleNomina = $("#txtIdDetalleNominaTurnos").val().trim();
    let ImporteTurnosObj = numeral($("#txtImporteTurnoEdit").val().trim());
    let ImporteTurnos = ImporteTurnosObj.value();
    let token = $("#csr_token_UT5JP").val();

    if(diasExtra == "" || diasExtra == null || diasExtra < 1 ){
      Lobibox.notify("error", {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: 'center top', //or 'center bottom'
        icon: false,
        img: '../../img/timdesk/warning_circle.svg',
        msg: "Ingresa un número de turnos."
      });
      $("#modificarTurnosExtra").prop("disabled", false);
      return;
    }
      
    if(isNaN(diasExtra)){
      Lobibox.notify("error", {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: 'center top', //or 'center bottom'
        icon: false,
        img: '../../img/timdesk/warning_circle.svg',
        msg: "Ingresa un número de turnos."
      });
      $("#modificarTurnosExtra").prop("disabled", false);
      return;
    }

    if(idEmpleado == "" || idEmpleado < 1){
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
          $("#modificarTurnosExtra").prop("disabled", false);
          return;
    }

    if(ImporteTurnos == "" || ImporteTurnos == null){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Ingresa el importe"
          });
          $("#modificarTurnosExtra").prop("disabled", false);
          return;
    }

    let fechaPago = '<?php echo $row_datos_nomina['fecha_pago_or'];?>';

    $.ajax({
          type: 'POST',
          url: 'functions/editar_TurnosExtra.php',
          data: { 
                  diasExtra : diasExtra,
                  ImporteTurnos : ImporteTurnos,
                  csr_token_UT5JP : token,
                  idNominaEmpleado: idNominaEmpleado,
                  idEmpleado: idEmpleado,
                  idDetalleNomina: idDetalleNomina,
                  idNomina: idNomina,
                  fechaPago: fechaPago
                },
          success: function(r) {

                if(r == "exito"){
                  Lobibox.notify("success", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/checkmark.svg",
                    msg: "Turnos extra modificados",
                  });
                  $("#txtTurnosExtra").val("");
                  $("#txtImporteTurno").val("");
                  $("#modificarTurnosExtra").prop("disabled", false);
                  $("#turnosExtraModalEdit").modal('hide');
                  $('#tblNominaEmpleado').DataTable().ajax.reload();

                }

                if(r == "diaspaso"){
                  Lobibox.notify("error", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/notificacion_error.svg",
                    msg: "No puede agregar más de 3 turnos extra por semana",
                  });
                  $("#modificarTurnosExtra").prop("disabled", false);
                }

                if(r == "fallo"){
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
                  $("#modificarTurnosExtra").prop("disabled", false);
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
            $("#modificarTurnosExtra").prop("disabled", false);
          }
        });


  });

  let agregarClavePrimaDominical = 0;
  $("#primaDominical").click(function(){

    let idEmpleado = $("#empleado").val();
    let token = $("#csr_token_UT5JP").val(); 
    
    if(idEmpleado == "" || idEmpleado < 1){
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

    let idclave = 15;
    $.ajax({
      type: 'POST',
      url: 'functions/mostrarClaves.php',
      data: {
        idclave: idclave,
        tipo: 1,
        csr_token_UT5JP: token
      },
      success: function(data) {

        if(data < 1){
          $("#claveMostrarPrimaDominicalUnica").css("display","block");
          agregarClavePrimaDominical = 1;
        }
        else{
          $("#claveMostrarPrimaDominicalUnica").css("display","none");
          agregarClavePrimaDominical = 0;
        }
      }
    });

    $.ajax({
          type: 'POST',
          url: 'functions/calcularDiaDomingo.php',
          data: { 
                  csr_token_UT5JP : token,
                  idEmpleado: idEmpleado,
                },
          success: function(r) {

                if(r == "fallo"){
                  Lobibox.notify("warning", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/notificacion_error.svg",
                    msg: "No se pudo cargar el calculo de la prima dominical pero puedes ingresar el importe",
                  });
                  $("#turnosExtraModal").prop("disabled", false);
                }
                else{

                  $("#txtPrimaDominical").val(r);

                }

          
          },
          error: function(){
            Lobibox.notify("warning", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/notificacion_error.svg",
              msg: "No se pudo cargar el calculo de la prima dominical pero puedes ingresar el importe",
            });
            $("#turnosExtraModal").prop("disabled", false);
          }
        });

    $("#primaDominicalModal").modal('toggle');

  });


  $("#agregarPrimaDominical").click(function(){

    let idEmpleado = $("#empleado").val();
    let primaDominicalObj = numeral($("#txtPrimaDominical").val().trim());
    let primaDominical = primaDominicalObj.value();
    let token = $("#csr_token_UT5JP").val(); 
    let clavePrimaDominicalUnica = $("#txtClavePrimaDominicalUnica").val();
    let fechaPago = '<?php echo $row_datos_nomina['fecha_pago_or'];?>';

    if(primaDominical == "" || primaDominical == null || primaDominical < 1 ){
      Lobibox.notify("error", {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: 'center top', //or 'center bottom'
        icon: false,
        img: '../../img/timdesk/warning_circle.svg',
        msg: "Ingresa el importe de la prima dominical."
      });
      return;
    }
      
    if(isNaN(primaDominical)){
      Lobibox.notify("error", {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: 'center top', //or 'center bottom'
        icon: false,
        img: '../../img/timdesk/warning_circle.svg',
        msg: "Ingresa un número."
      });
      return;
    }

    if(idEmpleado == "" || idEmpleado < 1){
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

    if(agregarClavePrimaDominical == 1){
        if (clavePrimaDominicalUnica == "") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Es necesario ingresar una clave para la prima dominical.",
          });
          return;
        }

        if(clavePrimaDominicalUnica.length > 15){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "No puedes agregar una clave de más de 15 caracteres."
            });
            return;
        }
      }

      $("#agregarPrimaDominical").prop("disabled", true);

    $.ajax({
          type: 'POST',
          url: 'functions/agregar_PrimaDominical.php',
          data: { 
                  primaDominical : primaDominical,
                  csr_token_UT5JP : token,
                  idNominaEmpleado: idNominaEmpleado,
                  idEmpleado: idEmpleado,
                  idNomina: idNomina,
                  agregarClavePrimaDominical : agregarClavePrimaDominical,
                  clavePrimaDominicalUnica: clavePrimaDominicalUnica,
                  fechaPago: fechaPago
                },
          success: function(r) {

                if(r == "exito"){
                  Lobibox.notify("success", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/checkmark.svg",
                    msg: "Prima dominical agregada",
                  });
                  $("#txtPrimaDominical").val("");
                  $("#agregarPrimaDominical").prop("disabled", false);
                  $("#primaDominicalModal").modal('hide');
                  $('#tblNominaEmpleado').DataTable().ajax.reload();

                }

                if(r == "existe-clave-PrimaDominicalUnica"){

                    Lobibox.notify("error", {
                      size: 'mini',
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: 'center top', //or 'center bottom'
                      icon: false,
                      img: '../../img/timdesk/warning_circle.svg',
                      msg: "La clave de prima dominical ya esta agregada, ingresa una diferente."
                    });
                    $("#agregarPrimaDominical").prop("disabled", false);
                }

                if(r == "existe-concepto-PrimaDominicalUnica"){

                    agregarClavePrimaDominical = 0;
                    $("#clavePrimaDominicalUnica").css("display","none");

                    Lobibox.notify("error", {
                      size: 'mini',
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: 'center top', //or 'center bottom'
                      icon: false,
                      img: '../../img/timdesk/warning_circle.svg',
                      msg: "El concepto de prima dominical ya esta agregado."
                    });
                    $("#agregarPrimaDominical").prop("disabled", false);
                }

                if(r == "domingopaso"){
                  Lobibox.notify("error", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/notificacion_error.svg",
                    msg: "Sólo puedes ingresar un domingo de acuerdo a la cantidad de semanas de la nómina.",
                  });
                  $("#agregarPrimaDominical").prop("disabled", false);
                }

                if(r == "fallo"){
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
                  $("#agregarPrimaDominical").prop("disabled", false);
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
            $("#agregarPrimaDominical").prop("disabled", false);
          }
        });


  });


  let agregarClavePrimaVacacional = 0;
  $("#primaVacacional").click(function(){
    let idEmpleado = $("#empleado").val();
    let token = $("#csr_token_UT5JP").val(); 
    
    if(idEmpleado == "" || idEmpleado < 1){
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

    $("#txtDiasPrimaVac").val("");
    $("#fechaIniPV").val("");
    $("#fechaFinPV").val("");
    $("#txtDiasRestantePrimaVac").val("");
    $("#fechaIniPV").prop("readonly", true);
    $("#fechaFinPV").prop("readonly", true);

    $.ajax({
      type: 'POST',
      url: 'functions/calculovacacionesPrimaVacacional.php',
      data: {
        idEmpleado: idEmpleado,
        csr_token_UT5JP: token
      },
      success: function(data) {

        if(data != "exito"){

            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "No se pudieron calcular las vacaciones del año en curso, es necesario volvera intentarlo."
            });
        }
        else{
          $("#primaVacacionalModal").modal('toggle');
        }

      }
    });

    let idclave = 16;
    $.ajax({
      type: 'POST',
      url: 'functions/mostrarClaves.php',
      data: {
        idclave: idclave,
        tipo: 1,
        csr_token_UT5JP: token
      },
      success: function(data) {

        if(data < 1){
          $("#claveMostrarPrimaVacacionalUnica").css("display","block");
          agregarClavePrimaVacacional = 1;
        }
        else{
          $("#claveMostrarPrimaVacacionalUnica").css("display","none");
          agregarClavePrimaVacacional = 0;
        }
      }
    });


  });

  $("#txtDiasPrimaVac").change(function(){
    let token = $("#csr_token_UT5JP").val(); 
    let dias = $("#txtDiasPrimaVac").val().trim();
    let idEmpleado = $("#empleado").val();

    if(dias != ""){
      $("#fechaIniPV").prop('readonly', false);
      $("#fechaFinPV").prop('readonly', false);
    }
    else{
      return;
    }

    if(dias > 0){


      $.ajax({
          type: 'POST',
          url: 'functions/calcularPrimaVacacional.php',
          data: {
            dias: dias,
            idEmpleado: idEmpleado,
            csr_token_UT5JP: token
          },
          success: function(data) {
            var datos = JSON.parse(data);
            
            $("#txtImportePrimaVac").val(datos.primaVacacional);
            $("#totalVacaciones").val(datos.totalVacaciones);
            $("#txtDiasRestantePrimaVac").val(datos.diasVacaciones);
          }
        });

    }
    else{
      return;
    }

  });

  $("#agregarPrimaVacacional").click(function(){

    let idEmpleado = $("#empleado").val();  
    let dias = $("#txtDiasPrimaVac").val().trim();  
    let diasRestantes = $("#txtDiasRestantePrimaVac").val().trim();
    let primaVacacional = $("#txtImportePrimaVac").val().trim();  
    let totalVacaciones = $("#totalVacaciones").val().trim();  
    let token = $("#csr_token_UT5JP").val(); 
    let clavePrimaVacacionalUnica = $("#txtClavePrimaVacacionalUnica").val();
    let fechaIni = $("#fechaIniPV").val().trim(); 
    let fechaFin = $("#fechaFinPV").val().trim(); 
    let fechaPago = '<?php echo $row_datos_nomina['fecha_pago_or'];?>';

    if(idEmpleado == "" || idEmpleado < 1){
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

    if(isNaN(dias)){
      Lobibox.notify("error", {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: 'center top', //or 'center bottom'
        icon: false,
        img: '../../img/timdesk/warning_circle.svg',
        msg: "Ingresa un número."
      });
      return;
    }

    if(dias == "" || dias == null || parseInt(dias) < 1 ){
      Lobibox.notify("error", {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: 'center top', //or 'center bottom'
        icon: false,
        img: '../../img/timdesk/warning_circle.svg',
        msg: "Ingresa los días."
      });
      return;
    }   

    if(diasRestantes == "" || diasRestantes == null){
      Lobibox.notify("error", {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: 'center top', //or 'center bottom'
        icon: false,
        img: '../../img/timdesk/warning_circle.svg',
        msg: "Ingresa los días restantes."
      });
      return;
    }   

    if(parseInt(diasRestantes) < 1){
      Lobibox.notify("error", {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: 'center top', //or 'center bottom'
        icon: false,
        img: '../../img/timdesk/warning_circle.svg',
        msg: "Ingresa los días restantes."
      });
      return;
    }   

    if(fechaIni == "" || fechaIni == null){
      Lobibox.notify("error", {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: 'center top', //or 'center bottom'
        icon: false,
        img: '../../img/timdesk/warning_circle.svg',
        msg: "Ingresa la fecha de inicio."
      });
      return;
    }

    if(fechaFin == "" || fechaFin == null){
      Lobibox.notify("error", {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: 'center top', //or 'center bottom'
        icon: false,
        img: '../../img/timdesk/warning_circle.svg',
        msg: "Ingresa la fecha de termino."
      });
      return;
    }

    if (new Date(fechaIni).getTime() > new Date(fechaFin).getTime()) {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/notificacion_error.svg",
        msg: "La fecha de inicio no puede ser posterior a la fecha de termino.",
      });
      return;
    }

    if(agregarClavePrimaVacacional == 1){
        if (clavePrimaVacacionalUnica == "") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Es necesario ingresar una clave para la prima vacacional.",
          });
          return;
        }

        if(clavePrimaVacacionalUnica.length > 15){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "No puedes agregar una clave de más de 15 caracteres."
            });
            return;
        }
    }

    $("#agregarPrimaVacacional").prop("disabled", true);

    $.ajax({
          type: 'POST',
          url: 'functions/agregar_PrimaVacacional.php',
          data: { 
                  primaVacacional : primaVacacional,
                  csr_token_UT5JP : token,
                  idNominaEmpleado: idNominaEmpleado,
                  idEmpleado: idEmpleado,
                  idNomina: idNomina,
                  agregarClavePrimaVacacional : agregarClavePrimaVacacional,
                  clavePrimaVacacionalUnica: clavePrimaVacacionalUnica,
                  dias: dias,
                  totalVacaciones: totalVacaciones,
                  fechaIni: fechaIni,
                  fechaFin: fechaFin,
                  fechaPago: fechaPago

                },
          success: function(r) {

                if(r == "exito"){
                  Lobibox.notify("success", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/checkmark.svg",
                    msg: "Prima vacacional agregada",
                  });
                  $('#formPrimaVacacional')[0].reset();
                  $("#agregarPrimaVacacional").prop("disabled", false);
                  $("#primaVacacionalModal").modal('hide');
                  $('#tblNominaEmpleado').DataTable().ajax.reload();

                }

                if(r == "existe-clave-PrimaVacacionalUnica"){

                    Lobibox.notify("error", {
                      size: 'mini',
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: 'center top', //or 'center bottom'
                      icon: false,
                      img: '../../img/timdesk/warning_circle.svg',
                      msg: "La clave de prima vacacional ya esta agregada, ingresa una diferente."
                    });
                    $("#agregarPrimaVacacional").prop("disabled", false);
                }

                if(r == "existe-concepto-PrimaVacacionalUnica"){

                    agregarClavePrimaVacacional = 0;
                    $("#clavePrimaVacacionalUnica").css("display","none");

                    Lobibox.notify("error", {
                      size: 'mini',
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: 'center top', //or 'center bottom'
                      icon: false,
                      img: '../../img/timdesk/warning_circle.svg',
                      msg: "El concepto de prima vacacional ya esta agregado."
                    });
                    $("#agregarPrimaVacacional").prop("disabled", false);
                }

                if(r == "paso-dias"){
                  Lobibox.notify("error", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/notificacion_error.svg",
                    msg: "No puedes ingresar más dias de los disponibles de vacaciones.",
                  });
                  $("#agregarPrimaVacacional").prop("disabled", false);
                }

                if(r == "fallo"){
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
                  $("#agregarPrimaVacacional").prop("disabled", false);
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
            $("#agregarPrimaVacacional").prop("disabled", false);
          }
        });


  });
  
  let agregarClaveIncapacidad, existeConceptoIncapacidad;
  $("#incapacidadBtn").click(function(){

    let idEmpleado = $("#empleado").val();
    
    if(idEmpleado == "" || idEmpleado < 1){
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

    let idclave = 6;
    let token = $("#csr_token_UT5JP").val(); 

    $.ajax({
      type: 'POST',
      url: 'functions/mostrarClaves.php',
      data: {
        idclave: idclave,
        tipo: 2,
        csr_token_UT5JP: token
      },
      success: function(data) {

        if(data < 1){
          $("#claveMostrarIncapacidad").css("display","block");
          agregarClaveIncapacidad = 1;
        }
        else{
          $("#agregarClaveFaltas").css("display","none");
          agregarClaveIncapacidad = 0;
        }
      }
    });

    //saber si existe el concepto
    $.ajax({
      type: 'POST',
      url: 'functions/existeConcepto.php',
      data: {
        idclave: idclave,
        tipo: 2,
        csr_token_UT5JP: token
      },
      success: function(data) {

        if(data < 1){
          $("#claveMostrarIncapacidadConcepto").css("display","block");
          existeConceptoIncapacidad = 1;
        }
        else{
          existeConceptoIncapacidad = 0;
          $("#claveMostrarIncapacidadConcepto").css("display","none");
        }
      }
    });

    $("#fechaIniIncapacidad").val("<?php echo $row_datos_nomina['fecha_inicio_or'];?>");

    $("#incapacidadesModal").modal('toggle');
  });

  $("#cmbTipoIncapacidadPer").change(function(){

    let idIncapacidad = $("#cmbTipoIncapacidadPer").val().trim();

    if(idIncapacidad == 1 || idIncapacidad == 5){
      $("#txtPorcentajeIncapacidad").prop("disabled", false);
      selectRiesgoIncapacidad.enable();

    }
    else{
      $("#txtPorcentajeIncapacidad").prop("disabled", true);
      selectRiesgoIncapacidad.disable();
    }
    
  });

  let fechaIniIncapacidadValidacion = "<?php echo $row_datos_nomina['fecha_inicio_or'];?>";
  $("#agregarDiasIncapacidad").click(function(){

      let idEmpleado = $("#empleado").val();
      let diasIncapacidad = $("#txtDiasIncapacidad").val().trim();
      let folioIncapacidad = $("#txtFolioIncapacidad").val().trim();
      let token = $("#csr_token_UT5JP").val();
      let fechaIni = $("#fechaIniIncapacidad").val().trim();
      let PorcentajeFaltasIncapacidadObj = numeral($("#txtPorcentajeIncapacidad").val().trim());
      let PorcentajeFaltasIncapacidad = PorcentajeFaltasIncapacidadObj.value();
      let cmbMotivoID = $("#cmbMotivoIncapacidad").val().trim();
      let incapacidadID = $("#cmbTipoIncapacidadPer").val().trim();
      let riesgoID = $("#cmbRiesgo").val().trim();
      let claveIncapacidad = "";
      let conceptoIncapacidad = "";
      let fechaPago = '<?php echo $row_datos_nomina['fecha_pago_or'];?>';
      let observaciones = $("#txtObservacionesIncapacidad").val().trim();

      if(folioIncapacidad == "" || folioIncapacidad == null || folioIncapacidad < 1 ){
        Lobibox.notify("error", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/warning_circle.svg',
          msg: "Ingresa un folio de la incapacidad."
        });
        return;
      }
        
      if(isNaN(folioIncapacidad)){
        Lobibox.notify("error", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/warning_circle.svg',
          msg: "Ingresa un número de folio de la incapacidad."
        });
        return;
      }

      if(diasIncapacidad == "" || diasIncapacidad == null || diasIncapacidad < 1 ){
        Lobibox.notify("error", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/warning_circle.svg',
          msg: "Ingresa un número de días."
        });
        return;
      }
        
      if(isNaN(diasIncapacidad)){
        Lobibox.notify("error", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/warning_circle.svg',
          msg: "Ingresa un número de días."
        });
        return;
      }

      
      if(fechaIni == "" || fechaIni == null){
        Lobibox.notify("error", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/warning_circle.svg',
          msg: "Ingresa la fecha de inicio."
        });
        return;
      }

      
      if (new Date(fechaIniIncapacidadValidacion).getTime() > new Date(fechaIni).getTime()) {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "La fecha de inicio de la incapacidad no puede ser anterior a la fecha de inicio de la nómina.",
        });
        return;
      }


      if(cmbMotivoID != 6){
        Lobibox.notify("error", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/warning_circle.svg',
          msg: "Selecciona el motivo de la falta."
        });
        return;
      }

      if(idEmpleado == "" || idEmpleado < 1){
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

      if(incapacidadID < 1){
        Lobibox.notify("error", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/warning_circle.svg',
          msg: "Selecciona el motivo de la incapacidad."
        });
        return;
      }

      if(incapacidadID == 1 || incapacidadID == 5){
        if((PorcentajeFaltasIncapacidad == "" || PorcentajeFaltasIncapacidad == null) && PorcentajeFaltasIncapacidad != 0){
              Lobibox.notify("error", {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: false,
                img: '../../img/timdesk/warning_circle.svg',
                msg: "Ingresa el porcentaje de incapacidad."
              });
              return;
        }

        if(PorcentajeFaltasIncapacidad > 100){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "El porcentaje de incapacidad no puede ser mayor de 100."
            });
            return;
        }
      }


      if(agregarClaveIncapacidad == 1){

          claveIncapacidad = $("#txtClaveIncapacidad").val().trim();
      
          if (claveIncapacidad == "") {
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/notificacion_error.svg",
              msg: "Es necesario ingresar una clave para el motivo de la incapacidad.",
            });
            return;
          }

          if(claveIncapacidad.length > 15){
              Lobibox.notify("error", {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: false,
                img: '../../img/timdesk/warning_circle.svg',
                msg: "No puedes agregar una clave de más de 15 caracteres."
              });
              return;
          }
      }

      if(existeConceptoIncapacidad == 1){

        conceptoIncapacidad = $("#txtConceptoIncapacidad").val().trim();
        
        if(conceptoIncapacidad == "" || conceptoIncapacidad == null){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Necesitas ingresar un concepto."
          });
          return;
        }

        if(conceptoIncapacidad.length >= 100){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "No puedes agregar un concepto de más de 100 caracteres."
          });
          return;
        }
      }

      if(observaciones.length > 100){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "No puedes agregar observaciones de más de 100 caracteres."
          });
          return;
      }

/*
      console.log("txtFolioIncapacidad " +$("#txtFolioIncapacidad").val());
      console.log("txtDiasIncapacidad " +$("#txtDiasIncapacidad").val());
      console.log("fechaIniIncapacidad " +$("#fechaIniIncapacidad").val());
      console.log("PorcentajeFaltasIncapacidad " + PorcentajeFaltasIncapacidad);

      console.log("cmbMotivoID " +cmbMotivoID);
      console.log("incapacidadID " +incapacidadID);
      console.log("claveIncapacidad " +claveIncapacidad);
      console.log("conceptoIncapacidad " + conceptoIncapacidad);


      return;
*/

      $("#agregarDiasIncapacidad").prop("disabled", true);

      $.ajax({
            type: 'POST',
            url: 'functions/agregarIncapacidadProgramada.php',
            data: { 
                    folioIncapacidad : folioIncapacidad,
                    diasIncapacidad : diasIncapacidad,
                    fechaIni: fechaIni,
                    incapacidadID: incapacidadID,
                    PorcentajeFaltasIncapacidad: PorcentajeFaltasIncapacidad,
                    riesgoID: riesgoID,
                    observaciones: observaciones,
                    agregarClaveIncapacidad : agregarClaveIncapacidad,
                    claveIncapacidad: claveIncapacidad,
                    existeConceptoIncapacidad: existeConceptoIncapacidad,
                    conceptoIncapacidad: conceptoIncapacidad,
                    idEmpleado: idEmpleado,
                    idNomina: idNomina,
                    idNominaEmpleado: idNominaEmpleado,
                    csr_token_UT5JP : token,
                    fechaPago: fechaPago
                  },
            success: function(r) {

                  if(r == "fallo-fechaini"){

                      Lobibox.notify("error", {
                        size: 'mini',
                        rounded: true,
                        delay: 3000,
                        delayIndicator: false,
                        position: 'center top', //or 'center bottom'
                        icon: false,
                        img: '../../img/timdesk/warning_circle.svg',
                        msg: "La fecha de inicio esta fuera de las fechas del periodo."
                      });
                  }

                  if(r == "fallo-fechafin"){

                      Lobibox.notify("error", {
                        size: 'mini',
                        rounded: true,
                        delay: 3000,
                        delayIndicator: false,
                        position: 'center top', //or 'center bottom'
                        icon: false,
                        img: '../../img/timdesk/warning_circle.svg',
                        msg: "La fecha final esta fuera de las fechas del periodo."
                      });
                  }

                  if(r == "fallo-fechacuenta"){

                      Lobibox.notify("error", {
                        size: 'mini',
                        rounded: true,
                        delay: 3000,
                        delayIndicator: false,
                        position: 'center top', //or 'center bottom'
                        icon: false,
                        img: '../../img/timdesk/warning_circle.svg',
                        msg: "Los días del periodo seleccionado no coinciden con los dias seleccionados."
                      });
                  }

                  if(r == "exito"){
                    Lobibox.notify("success", {
                      size: "mini",
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: "center top",
                      icon: true,
                      img: "../../img/timdesk/checkmark.svg",
                      msg: "Se agregaron las incapacidades.",
                    });
                    $("#txtFolioIncapacidad").val("");
                    $("#txtDiasIncapacidad").val("");
                    $("#agregarDiasIncapacidad").prop("disabled", false);
                    $("#incapacidadesModal").modal('hide');
                    agregarClaveIncapacidad = 0;
                    $("#claveMostrarIncapacidad").css("display","none");
                    $('#tblNominaEmpleado').DataTable().ajax.reload();

                    existeConceptoIncapacidad = 0;
                    $("#claveMostrarIncapacidadConcepto").css("display","none");

                  }

                  if(r == "existe-clave-incapacidad"){

                      Lobibox.notify("error", {
                        size: 'mini',
                        rounded: true,
                        delay: 3000,
                        delayIndicator: false,
                        position: 'center top', //or 'center bottom'
                        icon: false,
                        img: '../../img/timdesk/warning_circle.svg',
                        msg: "La clave del motivo de falta ya esta agregada, ingresa una diferente."
                      });
                  }

                  if(r == "existe-concepto-incapacidad"){

                      agregarClaveFaltas = 0;
                      $("#claveMostrarFaltas").css("display","none");

                      Lobibox.notify("error", {
                        size: 'mini',
                        rounded: true,
                        delay: 3000,
                        delayIndicator: false,
                        position: 'center top', //or 'center bottom'
                        icon: false,
                        img: '../../img/timdesk/warning_circle.svg',
                        msg: "El concepto de faltas ya esta agregado."
                      });
                  }

                  if(r == "fallo"){
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
                  $("#agregarDiasIncapacidad").prop("disabled", false);
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
              $("#agregarDiasIncapacidad").prop("disabled", false);
            }
          });


  });


  function limitar(element, limite){
    let valor = $("#" + element.id).val();
    if (valor.length > limite) {
        $("#" + element.id).val(valor.slice(0,limite)); 
    }
  }


  $("#faltaDias").click(function(){

    let idEmpleado = $("#empleado").val();
    
    if(idEmpleado == "" || idEmpleado < 1){
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

    $("#diasFaltaModal").modal('toggle');

  });

  let agregarClaveFaltas = 0;
  let agregarMotivoIncapacidad = 0;
  let existeConceptoFaltas = 0;
  $("#cmbMotivo").change(function(){
    let idclave = $("#cmbMotivo").val();
    let token = $("#csr_token_UT5JP").val(); 

    if(idclave == 0){
      $("#claveMostrarFaltas").css("display","none");
      agregarClaveFaltas = 0;
    }
    else{
      
      if(idclave == 20){
        $("#mostrarIncapacidad").css("display","none");
        agregarMotivoIncapacidad = 0;
      }

      $.ajax({
        type: 'POST',
        url: 'functions/mostrarClaves.php',
        data: {
          idclave: idclave,
          tipo: 2,
          csr_token_UT5JP: token
        },
        success: function(data) {

          if(data < 1){
            $("#claveMostrarFaltas").css("display","block");
            agregarClaveFaltas = 1;
          }
          else{
            $("#claveMostrarFaltas").css("display","none");
            agregarClaveFaltas = 0;
          }
        }
      });

    //saber si existe el concepto
    $.ajax({
      type: 'POST',
      url: 'functions/existeConcepto.php',
      data: {
        idclave: idclave,
        tipo: 2,
        csr_token_UT5JP: token
      },
      success: function(data) {

        if(data < 1){
          $("#claveMostrarFaltasConcepto").css("display","block");
          existeConceptoFaltas = 1;
        }
        else{
          existeConceptoFaltas = 0;
          $("#claveMostrarFaltasConcepto").css("display","none");
        }
      }
    });

      if(idclave == 6){
        agregarMotivoIncapacidad = 1;
        $("#mostrarIncapacidad").css("display","block");
      }
    }


  });


  $("#agregarDiasFalta").click(function(){

    let idEmpleado = $("#empleado").val();
    let diasFalta = $("#txtDiasFalta").val().trim();
    let ImporteDiasFaltaObj = numeral($("#txtImporteDiasFalta").val().trim());
    let ImporteDiasFalta = ImporteDiasFaltaObj.value();
    let token = $("#csr_token_UT5JP").val();
    let cmbMotivoID = $("#cmbMotivo").val().trim();
    let claveFaltas = $("#txtClaveFaltas").val().trim();
    let claveIncapacidad = $("#cmbTipoIncapacidad").val().trim();
    let fechaPago = '<?php echo $row_datos_nomina['fecha_pago_or'];?>';
    let fechaIni = $("#fechaIniFaltas").val().trim();
    let fechaFin = $("#fechaFinFaltas").val().trim();

    if(diasFalta == "" || diasFalta == null || diasFalta < 1 ){
      Lobibox.notify("error", {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: 'center top', //or 'center bottom'
        icon: false,
        img: '../../img/timdesk/warning_circle.svg',
        msg: "Ingresa un número de faltas."
      });
      return;
    }
      
    if(isNaN(diasFalta)){
      Lobibox.notify("error", {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: 'center top', //or 'center bottom'
        icon: false,
        img: '../../img/timdesk/warning_circle.svg',
        msg: "Ingresa un número de faltas."
      });
      return;
    }

    if(fechaIni == "" || fechaIni == null){
      Lobibox.notify("error", {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: 'center top', //or 'center bottom'
        icon: false,
        img: '../../img/timdesk/warning_circle.svg',
        msg: "Ingresa la fecha de inicio."
      });
      return;
    }

    if(fechaFin == "" || fechaFin == null){
      Lobibox.notify("error", {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: 'center top', //or 'center bottom'
        icon: false,
        img: '../../img/timdesk/warning_circle.svg',
        msg: "Ingresa la fecha de termino."
      });
      return;
    }

    if (new Date(fechaIni).getTime() > new Date(fechaFin).getTime()) {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/notificacion_error.svg",
        msg: "La fecha de inicio no puede ser posterior a la fecha de termino.",
      });
      return;
    }

    if(cmbMotivoID < 1){
      Lobibox.notify("error", {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: 'center top', //or 'center bottom'
        icon: false,
        img: '../../img/timdesk/warning_circle.svg',
        msg: "Selecciona el motivo de la falta."
      });
      return;
    }

    if(idEmpleado == "" || idEmpleado < 1){
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

    if(ImporteDiasFalta == "" || ImporteDiasFalta == null){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Ingresa el importe"
          });
          return;
    }

    if(agregarClaveFaltas == 1){
        if (claveFaltas == "") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Es necesario ingresar una clave para el motivo de la falta.",
          });
          return;
        }

        if(claveFaltas.length > 15){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "No puedes agregar una clave de más de 15 caracteres."
            });
            return;
        }
      }

    if(agregarMotivoIncapacidad == 1){
      if (claveIncapacidad < 1) {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Es necesario ingresar el motivo de la incapacidad.",
          });
          return;
        }

    }

    let nuevoConcepto = $("#txtConceptoFaltas").val().trim();
    if(existeConceptoFaltas == 1){
      if(nuevoConcepto == "" || nuevoConcepto == null){
        Lobibox.notify("error", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/warning_circle.svg',
          msg: "Necesitas ingresar un concepto."
        });
        return;
      }

      if(nuevoConcepto.length >= 100){
        Lobibox.notify("error", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/warning_circle.svg',
          msg: "No puedes agregar un concepto de más de 100 caracteres."
        });
        return;
      }
    }

    $("#agregarDiasFalta").prop("disabled", true);

    $.ajax({
          type: 'POST',
          url: 'functions/faltas_Dias.php',
          data: { 
                  diasFalta : diasFalta,
                  ImporteDiasFalta : ImporteDiasFalta,
                  csr_token_UT5JP : token,
                  idNominaEmpleado: idNominaEmpleado,
                  idEmpleado: idEmpleado,
                  idNomina: idNomina,
                  cmbMotivoID: cmbMotivoID,
                  agregarClaveFaltas : agregarClaveFaltas,
                  claveFaltas: claveFaltas,
                  agregarMotivoIncapacidad: agregarMotivoIncapacidad,
                  claveIncapacidad: claveIncapacidad,
                  fechaPago: fechaPago,
                  existeconcepto : existeConceptoFaltas,
                  nuevoconcepto : nuevoConcepto,
                  fechaIni: fechaIni,
                  fechaFin: fechaFin
                },
          success: function(r) {

                if(r == "fallo-fechaini"){

                    Lobibox.notify("error", {
                      size: 'mini',
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: 'center top', //or 'center bottom'
                      icon: false,
                      img: '../../img/timdesk/warning_circle.svg',
                      msg: "La fecha de inicio esta fuera de las fechas del periodo."
                    });
                    $("#agregarDiasFalta").prop("disabled", false);
                }

                if(r == "fallo-fechafin"){

                    Lobibox.notify("error", {
                      size: 'mini',
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: 'center top', //or 'center bottom'
                      icon: false,
                      img: '../../img/timdesk/warning_circle.svg',
                      msg: "La fecha final esta fuera de las fechas del periodo."
                    });
                    $("#agregarDiasFalta").prop("disabled", false);
                }

                if(r == "fallo-fechacuenta"){

                    Lobibox.notify("error", {
                      size: 'mini',
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: 'center top', //or 'center bottom'
                      icon: false,
                      img: '../../img/timdesk/warning_circle.svg',
                      msg: "Los días del periodo seleccionado no coinciden con los dias seleccionados."
                    });
                    $("#agregarDiasFalta").prop("disabled", false);
                }

                if(r == "exito"){
                  Lobibox.notify("success", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/checkmark.svg",
                    msg: "Se agrego la falta.",
                  });
                  $("#txtDiasFalta").val("");
                  $("#txtImporteDiasFalta").val("");
                  $("#agregarDiasFalta").prop("disabled", false);
                  $("#diasFaltaModal").modal('hide');
                  agregarClaveFaltas = 0;
                  $("#claveMostrarFaltas").css("display","none");
                  $('#tblNominaEmpleado').DataTable().ajax.reload();

                  existeConceptoFaltas = 0;
                  $("#claveMostrarFaltasConcepto").css("display","none");
                  existeConceptoFaltas = 0;
                  $("#claveMostrarFaltasConcepto").css("display","none");

                }

                if(r == "existe-clave-faltas"){

                    Lobibox.notify("error", {
                      size: 'mini',
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: 'center top', //or 'center bottom'
                      icon: false,
                      img: '../../img/timdesk/warning_circle.svg',
                      msg: "La clave del motivo de falta ya esta agregada, ingresa una diferente."
                    });
                    $("#agregarDiasFalta").prop("disabled", false);
                }

                if(r == "existe-concepto-faltas"){

                    agregarClaveFaltas = 0;
                    $("#claveMostrarFaltas").css("display","none");

                    Lobibox.notify("error", {
                      size: 'mini',
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: 'center top', //or 'center bottom'
                      icon: false,
                      img: '../../img/timdesk/warning_circle.svg',
                      msg: "El concepto de faltas ya esta agregado."
                    });
                    $("#agregarDiasFalta").prop("disabled", false);
                }

                if(r == "diaspaso"){
                  Lobibox.notify("error", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/notificacion_error.svg",
                    msg: "No puede agregar más faltas.",
                  });
                  $("#agregarDiasFalta").prop("disabled", false);
                }

                if(r == "fallo"){
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
                  $("#agregarDiasFalta").prop("disabled", false);
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
            $("#agregarDiasFalta").prop("disabled", false);
          }
        });


  });

  $("#modificarDiasFalta").click(function(){

    $("#modificarDiasFalta").prop("disabled", true);

    let idEmpleado = $("#empleado").val();
    let diasFalta = $("#txtDiasFaltaEdit").val().trim();
    let ImporteDiasFaltaObj = numeral($("#txtImporteDiasFaltaEdit").val().trim());
    let ImporteDiasFalta = ImporteDiasFaltaObj.value();
    let token = $("#csr_token_UT5JP").val();
    let idDetalleNominafaltas = $("#txtIdDetalleNominaFaltas").val().trim();
    let fechaPago = '<?php echo $row_datos_nomina['fecha_pago_or'];?>';
    let fechaIni = $("#fechaIniFaltasEdit").val().trim();
    let fechaFin = $("#fechaFinFaltasEdit").val().trim();

    if(diasFalta == "" || diasFalta == null || diasFalta < 1 ){
      Lobibox.notify("error", {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: 'center top', //or 'center bottom'
        icon: false,
        img: '../../img/timdesk/warning_circle.svg',
        msg: "Ingresa un número de faltas."
      });
      $("#modificarDiasFalta").prop("disabled", false);
      return;
    }
      
    if(isNaN(diasFalta)){
      Lobibox.notify("error", {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: 'center top', //or 'center bottom'
        icon: false,
        img: '../../img/timdesk/warning_circle.svg',
        msg: "Ingresa un número de faltas."
      });
      $("#modificarDiasFalta").prop("disabled", false);
      return;
    }

    if(fechaIni == "" || fechaIni == null){
      Lobibox.notify("error", {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: 'center top', //or 'center bottom'
        icon: false,
        img: '../../img/timdesk/warning_circle.svg',
        msg: "Ingresa la fecha de inicio."
      });
      $("#modificarDiasFalta").prop("disabled", false);
      return;
    }

    if(fechaFin == "" || fechaFin == null){
      Lobibox.notify("error", {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: 'center top', //or 'center bottom'
        icon: false,
        img: '../../img/timdesk/warning_circle.svg',
        msg: "Ingresa la fecha de termino."
      });
      $("#modificarDiasFalta").prop("disabled", false);
      return;
    }

    if (new Date(fechaIni).getTime() > new Date(fechaFin).getTime()) {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/notificacion_error.svg",
        msg: "La fecha de inicio no puede ser posterior a la fecha de termino.",
      });
      $("#modificarDiasFalta").prop("disabled", false);
      return;
    }

    if(idEmpleado == "" || idEmpleado < 1){
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
          $("#modificarDiasFalta").prop("disabled", false);
          return;
    }

    if(ImporteDiasFalta == "" || ImporteDiasFalta == null){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Ingresa el importe"
          });
          $("#modificarDiasFalta").prop("disabled", false);
          return;
    }

    $.ajax({
          type: 'POST',
          url: 'functions/editar_faltas_Dias.php',
          data: { 
                  diasFalta : diasFalta,
                  ImporteDiasFalta : ImporteDiasFalta,
                  csr_token_UT5JP : token,
                  idNominaEmpleado: idNominaEmpleado,
                  idDetalleNominafaltas: idDetalleNominafaltas,
                  idEmpleado: idEmpleado,
                  idNomina: idNomina,
                  fechaPago: fechaPago,
                  fechaIni: fechaIni,
                  fechaFin: fechaFin
                },
          success: function(r) {

                if(r == "fallo-fechaini"){

                    Lobibox.notify("error", {
                      size: 'mini',
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: 'center top', //or 'center bottom'
                      icon: false,
                      img: '../../img/timdesk/warning_circle.svg',
                      msg: "La fecha de inicio esta fuera de las fechas del periodo."
                    });
                    $("#modificarDiasFalta").prop("disabled", false);
                }

                if(r == "fallo-fechafin"){

                    Lobibox.notify("error", {
                      size: 'mini',
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: 'center top', //or 'center bottom'
                      icon: false,
                      img: '../../img/timdesk/warning_circle.svg',
                      msg: "La fecha final esta fuera de las fechas del periodo."
                    });
                    $("#modificarDiasFalta").prop("disabled", false);
                }

                if(r == "fallo-fechacuenta"){

                    Lobibox.notify("error", {
                      size: 'mini',
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: 'center top', //or 'center bottom'
                      icon: false,
                      img: '../../img/timdesk/warning_circle.svg',
                      msg: "Los días del periodo seleccionado no coinciden con los dias seleccionados."
                    });
                    $("#modificarDiasFalta").prop("disabled", false);
                }

                if(r == "exito"){
                  Lobibox.notify("success", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/checkmark.svg",
                    msg: "Se modficaron las faltas",
                  });
                  $("#modificarDiasFalta").prop("disabled", false);
                  $("#diasFaltaModalEdit").modal('hide');
                  $('#tblNominaEmpleado').DataTable().ajax.reload();

                }

                if(r == "diaspaso"){
                  Lobibox.notify("error", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/notificacion_error.svg",
                    msg: "No puede agregar más faltas.",
                  });
                  $("#modificarDiasFalta").prop("disabled", false);
                }

                if(r == "fallo"){
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
                  $("#modificarDiasFalta").prop("disabled", false);
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
            $("#modificarDiasFalta").prop("disabled", false);
          }
        });


  });

  $("#txtDiasFalta,#txtDiasFaltaEdit").change(function(){

      let element = this.id;
      let diasfalta;

      if(element == "txtDiasFalta"){
        $("#agregarDiasFalta").prop("disabled", true);
        diasfalta = $("#txtDiasFalta").val().trim();
      }

      if(element == "txtDiasFaltaEdit"){
        $("#modificarDiasFalta").prop("disabled", true);
        diasfalta = $("#txtDiasFaltaEdit").val().trim();
      }

      let token = $("#csr_token_UT5JP").val();
      let idEmpleado = $("#empleado").val();

      if(diasfalta == "" || diasfalta == null || diasfalta < 1 ){
        Lobibox.notify("error", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/warning_circle.svg',
          msg: "Ingresa un número de faltas."
        });
        return;
      }
        
      if(isNaN(diasfalta)){
        Lobibox.notify("error", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/warning_circle.svg',
          msg: "Ingresa un número de faltas."
        });
        return;
      }


      if(idEmpleado == "" || idEmpleado < 1){
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


    $.ajax({
        type: 'POST',
        url: 'functions/calcularSalarioFaltas.php',
        data: { 
                csr_token_UT5JP : token,
                idEmpleado: idEmpleado,
                diasfalta: diasfalta
              },
        success: function(r) {

              if(r == "fallo"){
                Lobibox.notify("warning", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top",
                  icon: true,
                  img: "../../img/timdesk/notificacion_error.svg",
                  msg: "No se pudo cargar el calculo del salario diario pero puedes ingresar el importe y los días.",
                });
                $("#agregarDiasFalta").prop("disabled", false);
                $("#modificarDiasFalta").prop("disabled", false);

              }
              else{

                if(element == "txtDiasFalta"){
                  $("#agregarDiasFalta").prop("disabled", false);
                  $("#txtImporteDiasFalta").val(r);
                }

                if(element == "txtDiasFaltaEdit"){
                  $("#modificarDiasFalta").prop("disabled", false);
                  $("#txtImporteDiasFaltaEdit").val(r);
                }

              }

        
        },
        error: function(){
          Lobibox.notify("warning", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "No se pudo cargar el calculo del salario diario pero puedes ingresar el importe y los días.",
          });
          $("#agregarDiasFalta").prop("disabled", false);
          $("#modificarDiasFalta").prop("disabled", false);
        }
      });

  });


  $("#txtHoras,#tipoHoras,#txtHorasEdit").change(function(){

      let element = this.id;
      let hora, tipoHora;

      if(element == "txtHoras" || element == "tipoHoras"){
        hora = $("#txtHoras").val().trim();
        tipoHora = $("#tipoHoras").val().trim();
        $("#agregarHoras").prop("disabled", true);
      }

      if(element == "txtHorasEdit"){
        hora = $("#txtHorasEdit").val().trim();
        tipoHora = $("#txttipoHorasEdit").val().trim();
        $("#modificarHoras").prop("disabled", true);
      }
      
      let token = $("#csr_token_UT5JP").val();
      let idEmpleado = $("#empleado").val();


      if(hora == "" || hora == null || hora < 1 ){
        Lobibox.notify("error", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/warning_circle.svg',
          msg: "Ingresa un número de horas."
        });
        return;
      }
        
      if(isNaN(hora)){
        Lobibox.notify("error", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/warning_circle.svg',
          msg: "Ingresa un número de horas."
        });
        return;
      }


      if(idEmpleado == "" || idEmpleado < 1){
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

    $.ajax({
        type: 'POST',
        url: 'functions/calcularSalarioHora.php',
        data: { 
                csr_token_UT5JP : token,
                idEmpleado: idEmpleado,
                hora: hora,
                tipoHora: tipoHora
              },
        success: function(r) {

              if(r == "fallo"){
                Lobibox.notify("warning", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top",
                  icon: true,
                  img: "../../img/timdesk/notificacion_error.svg",
                  msg: "No se pudo cargar el calculo del salario por hora pero puedes ingresar el importe y las horas.",
                });
                $("#agregarHoras").prop("disabled", false);
                $("#modificarHoras").prop("disabled", false);

              }
              else{

                
                if(element == "txtHoras" || element == "tipoHoras"){
                  $("#txtImporteHoras").val(r);
                  $("#agregarHoras").prop("disabled", false);
                }

                if(element == "txtHorasEdit"){
                  $("#txtImporteHorasEdit").val(r);
                  $("#modificarHoras").prop("disabled", false);
                }

              }

        
        },
        error: function(){
          Lobibox.notify("warning", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "No se pudo cargar el calculo del salario diario pero puedes ingresar el importe y las horas.",
          });
          $("#agregarHoras").prop("disabled", false);
          $("#modificarHoras").prop("disabled", false);
        }
      });

  });


  let agregarClaveHorasExtra = 0;
  let existeConcepto = 0;
  $("#horasExtra").click(function(){

    let idEmpleado = $("#empleado").val();
    let token = $("#csr_token_UT5JP").val();
    
    if(idEmpleado == "" || idEmpleado < 1){
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

    let idclave = 14;
    $.ajax({
      type: 'POST',
      url: 'functions/mostrarClaves.php',
      data: {
        idclave: idclave,
        tipo: 1,
        csr_token_UT5JP: token
      },
      success: function(data) {

        if(data < 1){
          $("#claveMostrarHorasExtraUnica").css("display","block");
          agregarClaveHorasExtra = 1;
        }
        else{
          agregarClaveHorasExtra = 0;
          $("#claveMostrarHorasExtraUnica").css("display","none");
        }
      }
    });

    //saber si existe el concepto
    $.ajax({
      type: 'POST',
      url: 'functions/existeConcepto.php',
      data: {
        idclave: idclave,
        tipo: 1,
        csr_token_UT5JP: token
      },
      success: function(data) {

        if(data < 1){
          $("#claveMostrarHorasExtraConcepto").css("display","block");
          existeConcepto = 1;
        }
        else{
          existeConcepto = 0;
          $("#claveMostrarHorasExtraConcepto").css("display","none");
        }
      }
    });

    $("#horasExtraModal").modal('toggle');

  });

  
  $("#agregarHoras").click(function(){

    let idEmpleado = $("#empleado").val();
    let horas = $("#txtHoras").val().trim();
    let tipoHora = $("#tipoHoras").val().trim();
    let ImporteHorasObj = numeral($("#txtImporteHoras").val().trim());
    let ImporteHoras = ImporteHorasObj.value();
    let token = $("#csr_token_UT5JP").val();
    let claveHorasExtra = $("#txtClaveHorasExtraUnica").val().trim();
    let fechaPago = '<?php echo $row_datos_nomina['fecha_pago_or'];?>';

    if(horas == "" || horas == null || horas < 1 ){
      Lobibox.notify("error", {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: 'center top', //or 'center bottom'
        icon: false,
        img: '../../img/timdesk/warning_circle.svg',
        msg: "Ingresa un número de horas."
      });
      return;
    }
      
    if(isNaN(horas)){
      Lobibox.notify("error", {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: 'center top', //or 'center bottom'
        icon: false,
        img: '../../img/timdesk/warning_circle.svg',
        msg: "Ingresa un número de horas."
      });
      return;
    }

    if(ImporteHoras == "" || ImporteHoras == null){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Ingresa el importe"
          });
          return;
    }

    if(idEmpleado == "" || idEmpleado < 1){
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

    if(agregarClaveHorasExtra == 1){
      if (claveHorasExtra == "") {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "Es necesario ingresar una clave para las horas extras.",
        });
        return;
      }

      if(claveHorasExtra.length > 15){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "No puedes agregar una clave de más de 15 caracteres."
          });
          return;
      }
    }

    let nuevoConcepto = $("#txtConceptoHorasExtra").val().trim();
    if(existeConcepto == 1){
      if(nuevoConcepto == "" || nuevoConcepto == null){
        Lobibox.notify("error", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/warning_circle.svg',
          msg: "Necesitas ingresar un concepto."
        });
        return;
      }

      if(nuevoConcepto.length >= 100){
        Lobibox.notify("error", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/warning_circle.svg',
          msg: "No puedes agregar un concepto de más de 100 caracteres."
        });
        return;
      }
    }

    $("#agregarHoras").prop("disabled", true);

    $.ajax({
          type: 'POST',
          url: 'functions/agregar_Horas.php',
          data: { 
                  horas : horas,
                  tipoHora : tipoHora,
                  ImporteHoras : ImporteHoras,
                  csr_token_UT5JP : token,
                  idNominaEmpleado: idNominaEmpleado,
                  idEmpleado: idEmpleado,
                  idNomina: idNomina,
                  agregarClaveHorasExtra: agregarClaveHorasExtra,
                  claveHorasExtra: claveHorasExtra,
                  fechaPago: fechaPago,
                  existeconcepto : existeConcepto,
                  nuevoconcepto : nuevoConcepto
                },
          success: function(r) {

                if(r == "exito"){
                  Lobibox.notify("success", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/checkmark.svg",
                    msg: "Se agregaron las horas.",
                  });
                  $("#txtHoras").val("");
                  $("#txtImporteHoras").val("");
                  $("#agregarHoras").prop("disabled", false);
                  $("#horasExtraModal").modal('hide');
                  $('#tblNominaEmpleado').DataTable().ajax.reload();

                }

                if(r == "mas-conceptos-doble"){

                    Lobibox.notify("error", {
                      size: 'mini',
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: 'center top', //or 'center bottom'
                      icon: false,
                      img: '../../img/timdesk/warning_circle.svg',
                      msg: "Sólo puedes agregar un concepto de horas dobles por semana."
                    });
                    $("#agregarHoras").prop("disabled", false);
                }

                if(r == "horas-pasadas-doble"){

                    Lobibox.notify("error", {
                      size: 'mini',
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: 'center top', //or 'center bottom'
                      icon: false,
                      img: '../../img/timdesk/warning_circle.svg',
                      msg: "Sólo puedes agregar un máximo de 9 horas dobles."
                    });
                    $("#agregarHoras").prop("disabled", false);
                }

                if(r == "existe-clave-HorasExtra"){

                    Lobibox.notify("error", {
                      size: 'mini',
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: 'center top', //or 'center bottom'
                      icon: false,
                      img: '../../img/timdesk/warning_circle.svg',
                      msg: "La clave de horas extra ya esta agregada, ingresa una diferente."
                    });
                    $("#agregarHoras").prop("disabled", false);
                }

                if(r == "existe-concepto-HorasExtra"){

                    agregarClaveHorasExtra = 0;
                    $("#claveMostrarHorasExtraUnica").css("display","none");

                    Lobibox.notify("error", {
                      size: 'mini',
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: 'center top', //or 'center bottom'
                      icon: false,
                      img: '../../img/timdesk/warning_circle.svg',
                      msg: "El concepto de horas extra ya esta agregado."
                    });
                    $("#agregarHoras").prop("disabled", false);
                }

                if(r == "fallo"){
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
                  $("#agregarHoras").prop("disabled", false);
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
            $("#agregarHoras").prop("disabled", false);
          }
        });


  });

$("#modificarHoras").click(function(){

    $("#modificarHoras").prop("disabled", true);

    let idEmpleado = $("#empleado").val();
    let horas = $("#txtHorasEdit").val().trim();
    let tipoHora = $("#txttipoHorasEdit").val().trim();
    let ImporteHorasObj = numeral($("#txtImporteHorasEdit").val().trim());
    let ImporteHoras = ImporteHorasObj.value();
    let token = $("#csr_token_UT5JP").val();
    let idDetalleNominaHorasExtras = $("#idDetalleNominaHorasExtras").val().trim();
    let fechaPago = '<?php echo $row_datos_nomina['fecha_pago_or'];?>';

    if(horas == "" || horas == null || horas < 1 ){
      Lobibox.notify("error", {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: 'center top', //or 'center bottom'
        icon: false,
        img: '../../img/timdesk/warning_circle.svg',
        msg: "Ingresa un número de horas."
      });
      $("#modificarHoras").prop("disabled", false);
      return;
    }
      
    if(isNaN(horas)){
      Lobibox.notify("error", {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: 'center top', //or 'center bottom'
        icon: false,
        img: '../../img/timdesk/warning_circle.svg',
        msg: "Ingresa un número de horas."
      });
      $("#modificarHoras").prop("disabled", false);
      return;
    }

    if(ImporteHoras == "" || ImporteHoras == null){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Ingresa el importe"
          });
          return;
    }

    if(idEmpleado == "" || idEmpleado < 1){
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
          $("#modificarHoras").prop("disabled", false);
          return;
    }

    $.ajax({
          type: 'POST',
          url: 'functions/editar_Horas.php',
          data: { 
                  horas : horas,
                  tipoHora : tipoHora,
                  ImporteHoras : ImporteHoras,
                  csr_token_UT5JP : token,
                  idNominaEmpleado: idNominaEmpleado,
                  idEmpleado: idEmpleado,
                  idNomina: idNomina,
                  idDetalleNominaHorasExtras: idDetalleNominaHorasExtras,
                  fechaPago: fechaPago
                },
          success: function(r) {

                if(r == "exito"){
                  Lobibox.notify("success", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/checkmark.svg",
                    msg: "Se modificaron las horas.",
                  });

                  $("#modificarHoras").prop("disabled", false);
                  $("#editar_horas_extras").modal('hide');
                  $('#tblNominaEmpleado').DataTable().ajax.reload();

                }

                if(r == "fallo"){
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
                  $("#modificarHoras").prop("disabled", false);
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
            $("#modificarHoras").prop("disabled", false);
          }
        });


  });

  $(document).on('click','.cbxCambio', function(e) {
      let id = this.value;
      let token = $("#csr_token_UT5JP").val(); 
      let idEmpleado = $("#empleado").val();
      let textoSweet, activo, msgEstado;
      let fechaPago = '<?php echo $row_datos_nomina['fecha_pago_or'];?>';

      if(idEmpleado < 1){
        Lobibox.notify("error", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/warning_circle.svg',
          msg: "Selecciona un empleado para modificar su estado de nómina."
        });

        if ($('#cbxCambio_' + id).is(":checked")){
          $('#cbxCambio_' + id).prop('checked', false);
        }
        else{
          $('#cbxCambio_' + id).prop('checked', true);
        }
        return;
      }

      if ($('#cbxCambio_' + id).is(":checked")){
          textoSweet = "Se otorgará en especie este concepto de la nómina de este empleado.";
          activo = 1;
          msgEstado = "Concepto en especie en nómina.";
      }
        else{
          textoSweet = "Se calcularán los impuestos de este concepto.";
          activo = 0;
          msgEstado = "Concepto con impuestos.";
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
          text: textoSweet,
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
              url: 'functions/exentarImpuesto.php',
              data: {
                idDetalleNomina: id,
                idNominaEmpleado: idNominaEmpleado,
                idEmpleado: idEmpleado,
                idNomina: idNomina,
                activo: activo,
                csr_token_UT5JP: token,
                fechaPago: fechaPago
              },
              success: function(data) {

                if (data == "exito") {

                  Lobibox.notify("success", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/checkmark.svg',
                    msg: msgEstado
                  });
                  $('#tblNominaEmpleado').DataTable().ajax.reload();

                } 
                if (data == "fallo") {

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

                }

                if (data == "fallo_viatico") {

                  Lobibox.notify("warning", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/warning_circle.svg',
                    msg: "Los viaticos no se pueden incluir para el cálculo de impuestos."
                  });

                }

              }
            });



          } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
          ) {

            if ($('#cbxCambio_' + id).is(":checked")){
              $('#cbxCambio_' + id).prop('checked', false);
            }
            else{
              $('#cbxCambio_' + id).prop('checked', true);
            }

          }
        });
  });


  $(".tipoConceptoC").change(function(){
    let tipo = this.value;
    let token = $("#csr_token_UT5JP").val(); 
    
    $.ajax({
          type: 'POST',
          url: 'functions/cargar_claves.php',
          data: { 
                  tipo : tipo,
                  csr_token_UT5JP : token
                },
          success: function(r) {
                
              if(r == "fallo"){
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
              else{
                  selectClave.destroy();

                  $("#cmbClave").html(r);

                  selectClave = new SlimSelect({
                    select: '#cmbClave',
                    deselectLabel: '<span class="">✖</span>'
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


$(document).on("click","#btnEnviarFactura",function(){
  
  let token = $("#csr_token_UT5JP").val();
  let idEmpleado = $("#empleado").val();

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
                tipo: 1
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


$(document).on("click","#cancelarNominaIndividual",function(){

  let token = $("#csr_token_UT5JP").val();
  let idEmpleado = $("#empleado").val();

  if(idEmpleado < 1){
    Lobibox.notify("error", {
      size: 'mini',
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: 'center top', //or 'center bottom'
      icon: false,
      img: '../../img/timdesk/warning_circle.svg',
      msg: "Selecciona un empleado para cancelar la nómina."
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
          text: "Se cancelará la factura de esta nómina.",
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

            $("#cancelarNominaIndividual").prop("disabled", true);
            let motivoCancelacion = result.value;
            $("#loader").addClass("loader");
            $("#loader").css("display","block");

            $.ajax({
              type: 'POST',
              url: 'functions/cancelarNominaEmpleado.php',
              data: {
                idNominaEmpleado: idNominaEmpleado,
                idEmpleado: idEmpleado,
                idNomina: idNomina,
                csr_token_UT5JP : token,
                motivoCancelacion: motivoCancelacion
              },
              success: function(data) {

                var datos = JSON.parse(data);

                if (datos.estatus == "exito") {

                  Lobibox.notify("success", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/checkmark.svg',
                    msg: "Se cancelo la factura."
                  });
                  mostrarTimbrado();  
                  $("#cancelarNominaIndividual").prop("disabled", false);

                  if(datos.estatus_nomina_completa_ant == 1 && datos.nomina_completa == 0){

                    let actualizacionTimbradoCompleto = '<center>' +
                                '<button type="button" class="btn-custom btn-custom--border-blue size-btn" id="timbrarNominaCompleta">Timbrar nómina</button>' +
                                '</center>';
                    $("#timbradoCompletoNomina").html(actualizacionTimbradoCompleto);
                  }

                }

                if (datos.estatus == "exito-existe" || datos.estatus == "fallo-existe") {

                  Lobibox.notify("success", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/checkmark.svg',
                    msg: "La factura ya estaba cancelada."
                  });
                  mostrarTimbrado();  
                  $("#cancelarNominaIndividual").prop("disabled", false);

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
                  $("#cancelarNominaIndividual").prop("disabled", false);

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
                    msg: "La factura ya se cancelo, se habilitarán los controles para facturar."
                  });
                  $("#cancelarNominaIndividual").prop("disabled", false);
                  mostrarTimbrado();
                }

                $(".loader").fadeOut("slow");
                $("#loader").removeClass("loader");
              }
            });



          } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
          ) {


          }
        });

});


$(document).on("click","#cancelarNominaGeneral",function(){

  let token = $("#csr_token_UT5JP").val();
  let idEmpleado = $("#empleado").val();

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
          text: "Se cancelarán todas las facturas de esta nómina. El motivo de cancelación será el mismo para todas las facturas.",
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

            $("#cancelarNominaGeneral").prop("disabled", true);
            let motivoCancelacion = result.value;

            $("#loader").addClass("loader");
            $("#loader").css("display","block");

            $.ajax({
              type: 'POST',
              url: 'functions/cancelarNominaEmpleadoGeneral.php',
              data: {
                idEmpleado: idEmpleado,
                idNomina: idNomina,
                csr_token_UT5JP : token,
                motivoCancelacion: motivoCancelacion
              },
              success: function(data) {

                var datos = JSON.parse(data);

                //console.log(datos);

                if (datos.resultado[0].estatus == "exito") {

                  Lobibox.notify("success", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/checkmark.svg',
                    msg: "Se cancelo la nómina completa."
                  });
                  
                  let texto = "<h3 style='font-size:16px;'>Estatus del timbrado:</h3><br>";

                  $.each( datos.resultado, function( key, value ) {

                    texto = texto + "<b>Nombre:</b> " + value.nombre;

                    if(value.estatus_ind.trim() == "exito"){
                      texto = texto + " <b>Estatus:</b> Cancelado <br>";
                    }
                    if(value.estatus_ind.trim() == "fallo"){
                      texto = texto + " <b>Estatus:</b> Fallo <br>";
                    }
                    if(value.estatus_ind.trim() == "fallo-cancelado"){
                      if(value.estatus_act.trim() == "exito-existe"){
                        texto = texto + " <b>Estatus:</b> Actualizado a cancelado.<br>";
                      }
                      if(value.estatus_act.trim() == "fallo-existe"){
                        texto = texto + " <b>Estatus:</b> Fallo actualizado.<br>";
                      }
                    }

                    if(value.estatus_ind.trim() == "exito"){

                      if(idEmpleado == value.idempleado){
                          mostrarTimbrado();  
                      }
                    }


                  });


                  $("#mostrarResultadosPadre").removeClass("alert-danger"); 
                  $("#mostrarResultadosPadre").removeClass("alert-info");
                  $("#mostrarResultadosPadre").addClass("alert-info");
                  $("#espacioMostrarresultado").css("display","block");
                  $("#mostrarResultadosPadre").css("display","block");
                  $("#mostrarResultados").html(texto);

                  let actualizacionTimbradoCompleto = '<center>' +
                                '<button type="button" class="btn-custom btn-custom--border-blue size-btn" id="timbrarNominaCompleta">Timbrar nómina</button>' +
                                '</center>';
                  $("#timbradoCompletoNomina").html(actualizacionTimbradoCompleto);

                  $("#cancelarNominaGeneral").prop("disabled", false);

                  $("#nominaTimbradaMos").css("display","none");

                } 

                if (datos.resultado[0].estatus == "fallo") {

                  let texto = "<h3 style='font-size:16px;'>Estatus del timbrado:</h3><br>";
                  $.each( datos.resultado, function( key, value ) {
                    texto = texto + "<b>Nombre:</b> " + value.nombre;


                    if(value.estatus_ind.trim() == "exito"){
                      texto = texto + " <b>Estatus:</b> Cancelado <br>";

                      if(idEmpleado == value.idempleado){
                          idFactura = value.idfactura;
                          mostrarOpcionesFactura();  
                      }
                    }
                    if(value.estatus_ind.trim() == "fallo"){
                      texto = texto + " <b>Estatus:</b> Fallo <br>";
                    }
                    if(value.estatus_ind.trim() == "fallo-cancelado"){
                      if(value.estatus_act.trim() == "exito-existe"){
                        texto = texto + " <b>Estatus:</b> Actualizado a cancelado.<br>";
                      }
                      if(value.estatus_act.trim() == "fallo-existe"){
                        texto = texto + " <b>Estatus:</b> Fallo actualizado.<br>";
                      }
                    }



                  });

                  $("#mostrarResultadosPadre").removeClass("alert-danger"); 
                  $("#mostrarResultadosPadre").removeClass("alert-info");
                  $("#mostrarResultadosPadre").addClass("alert-danger");
                  $("#espacioMostrarresultado").css("display","block");
                  $("#mostrarResultadosPadre").css("display","block");
                  $("#mostrarResultados").html(texto);

                  $("#cancelarNominaGeneral").prop("disabled", false);

                }


                if (datos.resultado[0].estatus_token== "fallo") {

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
                  $("#timbrarNominaCompleta").prop("disabled", false);

                }
                
                $(".loader").fadeOut("slow");
                $("#loader").removeClass("loader");

              }
            });



          } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
          ) {


          }
        });

});

let timbradoNominaCompleta = 0;

$(document).on("click","#timbrarNominaIndividual",function(){

  if(nominaAutorizadaG == 0){
      Lobibox.notify("error", {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: 'center top', //or 'center bottom'
        icon: false,
        img: '../../img/timdesk/warning_circle.svg',
        msg: "No puedes timbrar una nómina que no está autorizada."
      });
      return;
  }

  let token = $("#csr_token_UT5JP").val();
  let idEmpleado = $("#empleado").val();

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

  let IDs = [];
  $("#cfdiRelacionado").find(".mostrarCFDIRelacionado").each(function(){ IDs.push(this.id); });

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
          text: "Se timbrará la factura de esta empleado.",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: '<span class="verticalCenter">Confirmar</span>',
          cancelButtonText: '<span class="verticalCenter">Cerrar</span>',
          reverseButtons: true,
          allowOutsideClick: false,
        })
        .then((result) => {
          if (result.isConfirmed) {

            $("#timbrarNominaIndividual").prop("disabled", true);
            $("#loader").addClass("loader");
            $("#loader").css("display","block");

            $.ajax({
              type: 'POST',
              url: 'functions/timbrarNominaIndividual.php',
              data: {
                idNominaEmpleado: idNominaEmpleado,
                idEmpleado: idEmpleado,
                idNomina: idNomina,
                csr_token_UT5JP : token,
                idRelacionados : IDs
              },
              success: function(data) {

                var datos = JSON.parse(data);

                if (datos.estatus == "fallo-exento") {

                  Lobibox.notify("error", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/warning_circle.svg',
                    msg: "No puedes timbrar un empleado exento."
                  });
                  $("#timbrarNominaIndividual").prop("disabled", false);

                }

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
                  $("#timbrarNominaIndividual").prop("disabled", false);

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
                    msg: "No puedes volver a timbrar un empleado."
                  });
                  idFactura = datos.idFactura;
                  fechaTimbrado = datos.fechaTimbrado;
                  mostrarOpcionesFactura();  
                  $("#timbrarNominaIndividual").prop("disabled", false);

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
                  $("#timbrarNominaIndividual").prop("disabled", false);

                }

                if (datos.estatus == "fallo-imss") {

                  Lobibox.notify("error", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/warning_circle.svg',
                    msg: "No puedes timbrar ninguna nómina, te falta la clave del IMSS."
                  });
                  $("#timbrarNominaIndividual").prop("disabled", false);

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
                    msg: "Se timbro la factura del empleado."
                  });
                  idFactura = datos.idFactura;
                  fechaTimbrado = datos.fechaTimbrado;
                  mostrarOpcionesFactura();  
                  $("#timbrarNominaIndividual").prop("disabled", false);

                  timbradoNominaCompleta = datos.nomina_completa;

                  $("#nominaTimbradaMos").css("display","block");

                  if(timbradoNominaCompleta == 1){

                    let mostrarTimbradoCompleto = '<center>' +
                                '<span class="btn-custom btn-custom--blue size-btn">Nómina timbrada</button>' +
                              '</center>';
                    $("#timbradoCompletoNomina").html(mostrarTimbradoCompleto);
                    timbradoNominaCompleta = 0;
                  }

                  if (datos.estatus_email == "exito") {

                    Lobibox.notify("success", {
                      size: 'mini',
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: 'center top', //or 'center bottom'
                      icon: false,
                      img: '../../img/timdesk/checkmark.svg',
                      msg: "Se envio el recibo de nómina correo registrado del empleado."
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
                      msg: "No se pudo enviar el email."
                    });
                  }

                  $(".loader").fadeOut("slow");
                  $("#loader").removeClass("loader");

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
                  $("#timbrarNominaIndividual").prop("disabled", false);
                  $(".loader").fadeOut("slow");
                  $("#loader").removeClass("loader");
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

$(document).on("click","#timbrarNominaCompleta",function(){

  if(nominaAutorizadaG == 0){
      Lobibox.notify("error", {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: 'center top', //or 'center bottom'
        icon: false,
        img: '../../img/timdesk/warning_circle.svg',
        msg: "No puedes timbrar una nómina que no está autorizada."
      });
      return;
  }

  let token = $("#csr_token_UT5JP").val();
  let idEmpleado = $("#empleado").val();

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
          text: "Se timbrará la factura de todos los empleados de esta nómina.",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: '<span class="verticalCenter">Confirmar</span>',
          cancelButtonText: '<span class="verticalCenter">Cerrar</span>',
          reverseButtons: true,
          allowOutsideClick: false,
        })
        .then((result) => {
          if (result.isConfirmed) {

            $("#timbrarNominaCompleta").prop("disabled", true);
            $("#loader").addClass("loader");
            $("#loader").css("display","block");

            $.ajax({
              type: 'POST',
              url: 'functions/timbrarNominaCompleta.php',
              data: {
                idNomina: idNomina,
                csr_token_UT5JP : token,
              },
              success: function(data) {

                var datos = JSON.parse(data);

                //console.log(datos);

                if (datos.resultado[0].estatus == "exito") {

                  Lobibox.notify("success", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/checkmark.svg',
                    msg: "Se timbro la nómina completa."
                  });
                  
                  let texto = "<h3 style='font-size:16px;'>Estatus al enviar email:</h3><br>";

                  $.each( datos.resultado, function( key, value ) {

                    texto = texto + "<b>Nombre:</b> " + value.nombre;

                    if(value.estatus_email.trim() == "exito"){
                      texto = texto + " <b>Estatus:</b> Enviado <br>";
                    }
                    if(value.estatus_email.trim() == "fallo"){
                      texto = texto + " <b>Estatus:</b> Fallo <br>";
                    }

                    if(value.estatus_ind.trim() == "exito"){

                      if(idEmpleado == value.idempleado){
                          idFactura = value.idfactura;
                          mostrarOpcionesFactura();  
                      }
                    }


                  });

                  $("#mostrarResultadosPadre").removeClass("alert-danger"); 
                  $("#mostrarResultadosPadre").removeClass("alert-info");
                  $("#mostrarResultadosPadre").addClass("alert-info");
                  $("#espacioMostrarresultado").css("display","block");
                  $("#mostrarResultadosPadre").css("display","block");
                  $("#mostrarResultados").html(texto);

                  let actualizacionTimbradoCompleto = '<center>' +
                                '<span class="btn-custom btn-custom--blue size-btn">Nómina timbrada</button>' +
                                '</center>';
                  $("#timbradoCompletoNomina").html(actualizacionTimbradoCompleto);
                  $("#nominaTimbradaMos").css("display","block");
                  
                  $("#timbrarNominaCompleta").prop("disabled", false);

                } 

                if (datos.resultado[0].estatus == "fallo") {

                  $("#timbrarNominaCompleta").prop("disabled", false);

                  let texto = "<h3 style='font-size:16px;'>Errores en el timbrado:</h3><br>";
                  $.each( datos.resultado, function( key, value ) {
                    texto = texto + "<b>Nombre:</b> " + value.nombre;

                    if(value.estatus_curp.trim() !== ""){
                      texto = texto + " <b>Error:</b> " + value.mensaje_curp;
                    }

                    if(value.estatus_rfc.trim() !== ""){
                      texto = texto + " <b>Error:</b> " + value.mensaje_rfc;
                    }

                    if(value.estatus_imss.trim() !== ""){
                      texto = texto + " <b>Error:</b> " + value.mensaje_imss;
                    }

                    if(value.estatus_timbrado.trim() !== ""){
                      texto = texto + " <b>Error:</b> " + value.mensaje_timbrado;
                    }

                    if(value.estatus_curpt.trim() !== ""){
                      texto = texto + " <b>Error:</b> " + value.mensaje_curpt;
                    }

                    if(value.estatus_ind.trim() == "exito"){
                      texto = texto + " <b>Estatus:</b> Timbrado exitosamente.<br>";

                      if(idEmpleado == value.idempleado){
                          idFactura = value.idfactura;
                          mostrarOpcionesFactura();  
                      }
                    }
                    if(value.estatus_ind.trim() == "fallo"){
                      texto = texto + " <b>Estatus:</b> Fallo.<br>";
                    }

                  });


                  $("#mostrarResultadosPadre").removeClass("alert-danger"); 
                  $("#mostrarResultadosPadre").removeClass("alert-info");
                  $("#mostrarResultadosPadre").addClass("alert-danger");
                  $("#espacioMostrarresultado").css("display","block");
                  $("#mostrarResultadosPadre").css("display","block");
                  $("#mostrarResultados").html(texto);

                }

                if (datos.resultado[0].estatus_token== "fallo-isr") {

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
                  $("#timbrarNominaCompleta").prop("disabled", false);

                }

                if (datos.resultado[0].estatus_token== "fallo") {

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
                  $("#timbrarNominaCompleta").prop("disabled", false);

                }

                $(".loader").fadeOut("slow");
                $("#loader").removeClass("loader");
              }
            });



          } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
          ) {


          }
        });

});

$(document).on("click","#close-mostrarResultado", function(){
  $("#mostrarResultadosPadre").css("display","none");
  $("#espacioMostrarresultado").css("display","none");
});


$(".tipoConceptoCAgregar").change(function(){
    let tipo = this.value;
    let token = $("#csr_token_UT5JP").val(); 
    
    $.ajax({
          type: 'POST',
          url: 'functions/cargar_claves.php',
          data: { 
                  tipo : tipo,
                  csr_token_UT5JP : token
                },
          success: function(r) {
                
              if(r == "fallo"){
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
              else{
                  selectClaveAgregar.destroy();

                  $("#cmbConceptoClave").html(r);

                  selectClaveAgregar = new SlimSelect({
                    select: '#cmbConceptoClave',
                    deselectLabel: '<span class="">✖</span>'
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


  $("#btnAgregarClave").click(function(){
        let clave = $("#txtClaveAgregar").val().trim();
        let concepto = $("#cmbConceptoClave").val().trim();
        let tipo = $('input[name="tipoConceptoAgregar"]:checked').val();
        let token = $("#csr_token_UT5JP").val(); 

        if (clave == "") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Es necesario ingresar una clave.",
          });
          return;
        }

        if(clave.length < 3 || clave.length > 15){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "No puedes agregar una clave de menos de 3 caracteres ni más de 15."
            });
            return;
        }

        $("#btnCancelarClave").prop("disabled", true);
        $("#btnAgregarClave").prop("disabled", true);

        $.ajax({
          type: 'POST',
          url: 'functions/agregar_Clave.php',
          data: { 
                  concepto : concepto,
                  clave: clave,
                  tipo: tipo,
                  csr_token_UT5JP : token
                },
          success: function(r) {

            if(r == "exito"){
              Lobibox.notify("success", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/checkmark.svg",
                msg: "Clave agregada!",
              });
              $("#txtClaveAgregar").val("");
              $("#btnCancelarClave").prop("disabled", false);
              $("#btnAgregarClave").prop("disabled", false);
              $('#agregar_clave').modal('hide');
            }
            
            if(r == "existe-clave"){
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "Ya existe esa clave.",
              });
            }

            if(r == "existe-concepto"){
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "Ese concepto ya tiene asignada una clave.",
              });
            }

            if(r == "fallo"){
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
            $("#btnCancelarClave").prop("disabled", false);
            $("#btnAgregarClave").prop("disabled", false);
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
            $("#btnCancelarClave").prop("disabled", false);
            $("#btnAgregarClave").prop("disabled", false);
          }
        });

     });

    function agregarDias(fechaOr, diasAgregarOr){

      let fecha = new Date(fechaOr);
      let diasAgregar = parseInt(diasAgregarOr);
      fecha.setDate(fecha.getDate() + diasAgregar);

      var day = ("0" + fecha.getDate()).slice(-2);
      var month = ("0" + (fecha.getMonth() + 1)).slice(-2);
      var today = fecha.getFullYear()+"-"+(month)+"-"+(day) ;

      return today;
    }

    $("#btnAgregarNomina").click(function(){
        let idSucursal = $("#idSucursalAN").val();
        let idPeriodo = $("#idPeriodoAN").val();
        let idTipo = $("#idTipoAN").val();
        let fechaPago = $("#txtFechaPagoAN").val();
        let fechaIni = $("#txtFechaInicioAN").val();
        let fechaFin = $("#txtFechaFinAN").val(); 
        let token = $("#csr_token_UT5JP").val();
        let ultimaNomina;
        if($("#cbUltimaNomina").is(':checked')){
          ultimaNomina = 1;
        }
        else{
          ultimaNomina = 0;
        }

        if (fechaPago.trim() == "") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "¡Es necesario seleccionar la fecha de pago!",
          });
          return;
        }

        if (fechaIni.trim() == "") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "¡Es necesario seleccionar la fecha de inicio!",
          });
          return;
        }

        if (fechaFin.trim() == "") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "¡Es necesario seleccionar la fecha final!",
          });
          return;
        }

        if (new Date(fechaIni).getTime() > new Date(fechaFin).getTime()) {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "¡La fecha final no puede ser menor a la fecha de inicio!",
          });
          return;
        }

        $("#btnCancelarNomina").prop("disabled", true);
        $("#btnAgregarNomina").prop("disabled", true);

        $.ajax({
          type: 'POST',
          url: 'functions/agregar_Nomina.php',
          data: { 
                  idSucursal : idSucursal,
                  idPeriodo : idPeriodo,
                  idTipo : idTipo,
                  fechaPago : fechaPago,
                  fechaIni : fechaIni,
                  fechaFin : fechaFin,
                  csr_token_UT5JP : token,
                  ultimaNomina: ultimaNomina
                },
          success: function(r) {

            if(r == "exito"){
              Lobibox.notify("success", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/checkmark.svg",
                msg: "¡Nómina agregada!",
              });
              $('#tblNominas').DataTable().ajax.reload();
              $('#agregar_nomina').modal('hide');
            }
            else{
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
            $("#btnCancelarNomina").prop("disabled", false);
            $("#btnAgregarNomina").prop("disabled", false);
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
            $("#btnCancelarNomina").prop("disabled", false);
            $("#btnAgregarNomina").prop("disabled", false);
          }
        });

     });

    $(document).on("click","#mostrarISR", function(){

        let idEmpleado = $("#empleado").val();
        let token = $("#csr_token_UT5JP").val(); 
        let fechaPago = '<?=$row_datos_nomina['fecha_pago_or']?>';
         
        if(idEmpleado == "" || idEmpleado < 1){
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

        $.ajax({
          type: 'POST',
          url: 'functions/calculoISR.php',
          data: { 
                  idNominaEmpleado: idNominaEmpleado,
                  idEmpleado: idEmpleado,
                  csr_token_UT5JP: token,
                  idNomina: idNomina,
                  fechaPago: fechaPago
                },
          success: function(r) {

            var datos = JSON.parse(r);

            if(datos.estatus == "exito"){
              
              $("#mostrarUnicoISR").html(datos.resultado);
              $("#calculo_isr_modal").modal('toggle');

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

    $(document).on("click","#mostrarIMSS", function(){

        let idEmpleado = $("#empleado").val();
        let token = $("#csr_token_UT5JP").val(); 

        if(idEmpleado == "" || idEmpleado < 1){
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

        let fechaPago = '<?php echo $row_datos_nomina['fecha_pago_or'];?>';

        $.ajax({
          type: 'POST',
          url: 'functions/calculoIMSS.php',
          data: { 
                  idNominaEmpleado: idNominaEmpleado,
                  idEmpleado: idEmpleado,
                  csr_token_UT5JP: token,
                  idNomina: idNomina,
                  fechaPago: fechaPago
                },
          success: function(r) {

            var datos = JSON.parse(r);

            if(datos.estatus == "exito"){
              
              $("#mostrarUnicoIMSS").html(datos.resultado);
              $("#calculo_imss_modal").modal('toggle');

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


    $(document).on("click","#exportarExcel",function(){
        let token = $("#csr_token_UT5JP").val();
        let idEmpleado = $("#empleado").val();

        if(idEmpleado == "" || idEmpleado < 1){
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

        $().redirect("functions/exportar_nomina_excel.php", {
          idNominaEmpleado: idNominaEmpleado,
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

    $(document).on("click","#exportarExcelNomina",function(){
        let token = $("#csr_token_UT5JP").val();
        let idEmpleado = $("#empleado").val();

        if(idEmpleado == "" || idEmpleado < 1){
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

        $().redirect("functions/exportar_nomina_excel_todos.php", {
          idNomina: idNomina,
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
        let token = $("#csr_token_UT5JP").val();
        $().redirect("functions/exportar_nomina_pdf.php", {
          idNominaEmpleado: idNominaEmpleado,
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


    $(document).on("click","#exportarPDFNomina",function(){
        let token = $("#csr_token_UT5JP").val();
        $().redirect("functions/exportar_nomina_pdf_todos.php", {
          idNomina: idNomina,
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
        let url = 'functions/download_pdf_nomina_eliminado.php?value=' + valores[2];
        let url2 = 'functions/download_xml_nomina_eliminado.php?value=' + valores[2];

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

        let nuevoCFDI = "<div class='row mostrarCFDIRelacionado' id='" + valores[1]  + "_" + valores[2] + "' style='margin: 0 0 5px 0;'><div class='row col-12 col-lg-12'>" +
                            "<div class='col-lg-6'>" + valores[0] + "</div>" + 
                            "<div class='col-lg-6'><a href='functions/download_pdf_nomina_eliminado.php?value=" + valores[2] + "' class='btn btn-custom btn-custom--border-blue' target='_blank'> <i class='fas fa-file-invoice'></i> PDF </a> <a href='functions/download_xml_nomina_eliminado.php?value=" + valores[2] + "' class='btn btn-custom btn-custom--border-blue' target='_blank'> <i class='far fa-file-alt'></i> XML </a>" +  
                            "<button type='button' class='btn btn-custom btn-custom--red' id='deleteCFDIRelacionado' style='position:relative; left:4px;'> <i class='fas fa-trash-alt'></i> </button></div>" +
                         "</div></div>";
        $("#cfdiRelacionado").append(nuevoCFDI);

    });

    $(document).on("click","#deleteCFDIRelacionado",function(){
        let id = $(this).closest('.mostrarCFDIRelacionado').attr('id');

        $("#" + id).fadeOut(500, function(){ $("#" + id).remove(); });
    });

    $(document).on("click","#reenvio",function(){
        
        let fechaPago = '<?=$row_datos_nomina['fecha_pago_or']?>';
        let idEmpleado = $("#empleado").val();

        let token = $("#csr_token_UT5JP").val();
        $.ajax({
          type: 'POST',
          url: 'functions/calculoImpuestos.php',
          data: { 
                  csr_token_UT5JP: token,
                  idEmpleado: idEmpleado,
                  idNominaEmpleado: idNominaEmpleado,
                  fechaPago: fechaPago,
                  idNomina: idNomina
                },
          success: function(r) {

           // var datos = JSON.parse(r);
            $('#tblNominaEmpleado').DataTable().ajax.reload();


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


    $(document).on("click","#mostrarSBC",function(){

        let token = $("#csr_token_UT5JP").val();
        let idEmpleado = $("#empleado").val();
        let fechaPago = '<?=$row_datos_nomina['fecha_pago_or']?>';

        if(idEmpleado == "" || idEmpleado < 1){
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

        $.ajax({
          type: 'POST',
          url: 'functions/mostrarSBC.php',
          data: { 
                  csr_token_UT5JP: token,
                  idEmpleado: idEmpleado,
                  fechaPago: fechaPago
                },
          success: function(r) {

            var datos = JSON.parse(r);

            if(datos.estatus == "exito"){
              
              $("#SBCFijo").val(datos.SBCFijo);
              $("#SBCVariables").val(datos.SBCVariable);
              $("#actualizar_sbc").modal('toggle');

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

    $(document).on("click","#actualizarSBC",function(){

        let token = $("#csr_token_UT5JP").val();
        let idEmpleado = $("#empleado").val();
        let fechaPago = '<?=$row_datos_nomina['fecha_pago_or']?>';
        let SBCFijo = $("#SBCFijo").val().trim();
        let SBCVariables = $("#SBCVariables").val().trim();
        
        if(idEmpleado == "" || idEmpleado < 1){
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

        if(SBCFijo == "" || SBCFijo == null){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "Ingresa el salario base de cotización fijo."
            });
            return;
        }

        if(SBCVariables == "" || SBCVariables == null){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "Ingresa el salario base de cotización variable."
            });
            return;
        }

        
        $.ajax({
          type: 'POST',
          url: 'functions/actualizarSBC.php',
          data: { 
                  csr_token_UT5JP: token,
                  idEmpleado: idEmpleado,
                  fechaPago: fechaPago,
                  SBCFijo: SBCFijo,
                  SBCVariables: SBCVariables,
                  idNomina: idNomina,
                  idNominaEmpleado: idNominaEmpleado
                },
          success: function(r) {

            var datos = JSON.parse(r);

            if(datos.estatus == "exito"){
              
              $("#actualizar_sbc").modal('toggle');
              $('#tblNominaEmpleado').DataTable().ajax.reload();

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

            if(datos.estatus == "no-modificar"){
              Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "Ya hay una nómina timbrada con el SBC guardado, si deseas modificarlo es necesario cancelar las nóminas que ya estén timbradas con ese salario.",
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

    
    $(document).on("click","#calcularSBC",function(){

        let token = $("#csr_token_UT5JP").val();
        let idEmpleado = $("#empleado").val();
        let fechaPago = '<?=$row_datos_nomina['fecha_pago_or']?>';
        let SBCFijo = $("#SBCFijo").val().trim();
        let SBCVariables = $("#SBCVariables").val().trim();
        
        if(idEmpleado == "" || idEmpleado < 1){
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

       
        $.ajax({
          type: 'POST',
          url: 'functions/calcularSBC.php',
          data: { 
                  csr_token_UT5JP: token,
                  idEmpleado: idEmpleado,
                  fechaPago: fechaPago,
                  SBCFijo: SBCFijo,
                  SBCVariables: SBCVariables,
                  idNomina: idNomina,
                  idNominaEmpleado: idNominaEmpleado
                },
          success: function(r) {

            var datos = JSON.parse(r);

            if(datos.estatus == "exito"){
              
              $("#SBCFijo").val(datos.SBCFijo);
              $("#SBCVariables").val(datos.SBCVariable);             

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


    let existeClaveFonacot = 0 , existeConceptoFonacot = 0;
    let selectConceptoFonacot;
    $("#fonacotBtn").click(function(){

      let idEmpleado = $("#empleado").val();
      let token = $("#csr_token_UT5JP").val(); 

      if(idEmpleado == "" || idEmpleado < 1){
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

      $("#txtNumCredito").prop("readonly", false);
      $("#fechaAplicacion").prop("readonly", false);
      $("#txtImporteFonacot").prop("readonly", false);
      $("#txtImporteFijoFonacot").prop("readonly", false);
      $("#txtPagosAnterioresFonacot").prop("readonly", false);
      selectTipoCalculo.enable();

      $.ajax({
          type: 'POST',
          url: 'functions/mostrarFonacot.php',
          data: { 
                  csr_token_UT5JP: token,
                  idEmpleado : idEmpleado
                },
          success: function(r) {

            var datos = JSON.parse(r);

            if(datos.existeCredito == 0){
                $("#txtNumCredito").val("");
                $("#txtImporteFonacot").val("");
                $("#txtImporteFijoFonacot").val("");
                $("#txtPagosAnterioresFonacot").val("");
                $("#txtImporteAcumuladoFonacot").val("0.00");
                $("#txtSaldoFonacot").val("");
                $("#fechaAplicacion").val("<?php echo $row_datos_nomina['fecha_inicio_or'];?>");

                $("#mostrarClaveFonacot").html(datos.clave);
                $("#mostrarConceptoFonacot").html(datos.concepto);
                existeClaveFonacot = datos.claveValor;
                existeConceptoFonacot = datos.conceptoValor;

                if(existeConceptoFonacot == 0){

                  selectConceptoFonacot = new SlimSelect({
                    select: '#cmbConceptoFonacot',
                    deselectLabel: '<span class="">✖</span>'
                  });

                }
            }
            else{

                $("#txtNumCredito").val(datos.num_credito);
                $("#txtImporteFonacot").val(datos.importe_tot);
                $("#txtImporteFijoFonacot").val(datos.importe_per);
                $("#txtPagosAnterioresFonacot").val(datos.pagos_otros_pat);
                $("#txtImporteAcumuladoFonacot").val(datos.monto_acumulado_ret);
                $("#txtSaldoFonacot").val(datos.saldo);
                $("#fechaAplicacion").val(datos.fecha_apli);
                $("#mostrarClaveFonacot").html(datos.clave);
                $("#mostrarConceptoFonacot").html(datos.concepto);

                $("#txtNumCredito").prop("readonly", true);
                $("#fechaAplicacion").prop("readonly", true);
                $("#txtImporteFonacot").prop("readonly", true);
                $("#txtImporteFijoFonacot").prop("readonly", true);
                $("#txtPagosAnterioresFonacot").prop("readonly", true);
                selectTipoCalculo.disable();
            }

            $("#btnMostrarFonacot").html(datos.botonFonacot);
            $("#fonacotModal").modal('show');

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

    $("#txtImporteFonacot, #txtPagosAnterioresFonacot").change(function(){

      
      let ImporteFonacotObj = numeral($("#txtImporteFonacot").val().trim());
      let ImporteFonacot = ImporteFonacotObj.value();
      let PagoOtrosPatronesObj = numeral($("#txtPagosAnterioresFonacot").val().trim());
      //console.log(PagoOtrosPatronesObj);
      let PagoOtrosPatrones = PagoOtrosPatronesObj.value();

      if(PagoOtrosPatrones == '' || PagoOtrosPatrones == null || PagoOtrosPatrones == 0){
        $("#txtImporteAcumuladoFonacot").val("0.00");
      }
      else{
        $("#txtImporteAcumuladoFonacot").val(PagoOtrosPatronesObj._input);
      }

      
      let saldo = ImporteFonacot - PagoOtrosPatrones;
      $("#txtSaldoFonacot").val(saldo);

      if(PagoOtrosPatrones > ImporteFonacot){
        Lobibox.notify("error", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/warning_circle.svg',
          msg: "El pago de otros patrones no puede ser mayor que el importe total del crédito."
        });
      }
    });

    $(document).on("click","#agregarFonacot",function(){

        let token = $("#csr_token_UT5JP").val();
        let idEmpleado = $("#empleado").val();
        
        if(idEmpleado == "" || idEmpleado < 1){
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

        let clave = "";
        if(existeClaveFonacot == 1){
          clave = $("#txtClaveFonacotUnica").val().trim();

          if(clave == '' || clave.length == 0){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "Ingresa la clave del FONACOT."
            });
            return;
          }

          if(clave.length > 15){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "La clave del FONACOT no puede ser mayor de 15 caracteres."
            });
            return;
          }

        }

        let concepto = "";
        if(existeConceptoFonacot == 1){
          concepto = $("#txtClaveSATFonacot").val().trim();

          if(concepto == '' || concepto.length == 0){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "Ingresa el concepto del FONACOT."
            });
            return;
          }

          if(concepto.length > 100){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "El concepto del FONACOT no puede ser mayor de 100 caracteres."
            });
            return;
          }
        }

        let fechaAplicacion = $("#fechaAplicacion").val();
        let numCredito = $("#txtNumCredito").val().trim();
        let ImporteFonacotObj = numeral($("#txtImporteFonacot").val().trim());
        let ImporteFonacot = ImporteFonacotObj.value();
        let ImporteFijoFonacotObj = numeral($("#txtImporteFijoFonacot").val().trim());
        let ImporteFijoFonacot = ImporteFijoFonacotObj.value();
        let PagoOtrosPatronesObj = numeral($("#txtPagosAnterioresFonacot").val().trim());
        let PagoOtrosPatrones = PagoOtrosPatronesObj.value();
        let tipoCalculo = $("#cmbTipoCalculo").val();
        let fechaPago = '<?=$row_datos_nomina['fecha_pago_or']?>';

        if(numCredito == "" || numCredito == null){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "Ingresa el número de crédito."
            });
            return;
        }

        if(fechaAplicacion == "" || fechaAplicacion == null){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "Ingresa la fecha de aplicación."
            });
            return;
        }

        if(ImporteFonacot == "" || ImporteFonacot == null){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "Ingresa el importe."
            });
            return;
        }

        if(ImporteFijoFonacot == "" || ImporteFijoFonacot == null){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "Ingresa el importe fijo."
            });
            return;
        }

        if(PagoOtrosPatrones > ImporteFonacot){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "El pago de otros patrones no puede ser mayor que el importe total del crédito."
          });
          return;
        }

        $("#agregarFonacot").prop("disabled", true);

        $.ajax({
          type: 'POST',
          url: 'functions/agregarCreditoFonacot.php',
          data: { 
                  csr_token_UT5JP: token,
                  idEmpleado: idEmpleado,
                  clave: clave,
                  concepto: concepto,
                  fechaAplicacion: fechaAplicacion,
                  numCredito: numCredito,
                  ImporteFonacot: ImporteFonacot,
                  ImporteFijoFonacot: ImporteFijoFonacot,
                  PagoOtrosPatrones: PagoOtrosPatrones,
                  idNomina: idNomina,
                  idNominaEmpleado: idNominaEmpleado,
                  existeConceptoFonacot : existeConceptoFonacot,
                  existeClaveFonacot: existeClaveFonacot,
                  tipoCalculo: tipoCalculo,
                  fechaPago: fechaPago
                },
          success: function(r) {

            if(r == "existe-clave"){
              
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "La clave ingresada ya esta registrada.",
              });
              $("#agregarFonacot").prop("disabled", false);

            }

            if(r == "existe-concepto"){
              
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "Ya se ha ingresado la clave para el crédito FONACOT.",
              });
              $("#agregarFonacot").prop("disabled", false);

            }

            if(r == "exito"){
              
              Lobibox.notify("success", {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: false,
                img: '../../img/timdesk/checkmark.svg',
                msg: "Crédito guardado."
              });

              $("#fonacotModal").modal('toggle');
              $('#tblNominaEmpleado').DataTable().ajax.reload();

            }

            if(r == "fallo"){
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
    
    $(document).on("click","#eliminarFonacot",function(){

        let token = $("#csr_token_UT5JP").val(); 
        let idEmpleado = $("#empleado").val();
        let fechaPago = '<?php echo $row_datos_nomina['fecha_pago_or'];?>';

        let idCreditoFonacot = $("#idCreditoFonacot").val();

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
            text: "Se cancelará el crédito FONACOT.",
            icon: "warning",
            showCancelButton: true,
            cancelButtonText: '<span class="verticalCenter">Cerrar</span>',
            confirmButtonText: '<span class="verticalCenter">Cancelar</span>',
            reverseButtons: true,
          })
          .then((result) => {
            if (result.isConfirmed) {

              $.ajax({
                type: 'POST',
                url: 'functions/cancelarCreditoFonacot.php',
                data: {
                  csr_token_UT5JP: token,
                  idCreditoFonacot: idCreditoFonacot
                },
                success: function(data) {

                  if (data == "exito") {

                    Lobibox.notify("success", {
                      size: 'mini',
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: 'center top', //or 'center bottom'
                      icon: false,
                      img: '../../img/timdesk/checkmark.svg',
                      msg: "Se ha cancelado el crédito de la nómina, pero las deducciones del crédito siguen vigentes en la nómina."
                    });

                    $("#fonacotModal").modal('toggle');

                  } 
                  else {

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

                  }
                }
              });


            } else if (
              /* Read more about handling dismissals below */
              result.dismiss === Swal.DismissReason.cancel
            ) {}
          });
    });


    let existeClaveInfonavit = 0 , existeConceptoInfonavit = 0;
    let selectConceptoInfonavit;
    $("#infonavitBtn").click(function(){

      let idEmpleado = $("#empleado").val();
      let token = $("#csr_token_UT5JP").val(); 

      if(idEmpleado == "" || idEmpleado < 1){
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

      $("#txtNumCreditoInfonavit").prop("readonly", false);
      $("#txtCuotaFija").prop("readonly", false);
      $("#fechaAplicacionInfonavit").prop("readonly", false);
      $("#fechaSuspensionInfonavit").prop("readonly", false);
      $("#txtMontoAcumuladoInfonavit").prop("readonly", false);
      $("#txtVecesAplicadasInfonavit").prop("readonly", false);
      selectTipoCalculoInfonavit.enable();

      $.ajax({
          type: 'POST',
          url: 'functions/mostrarInfonavit.php',
          data: { 
                  csr_token_UT5JP: token,
                  idEmpleado : idEmpleado
                },
          success: function(r) {

            var datos = JSON.parse(r);

            if(datos.existeCredito == 0){
                $("#txtNumCreditoInfonavit").val("");
                $("#txtCuotaFija").val("");
                $("#txtMontoAcumuladoInfonavit").val("");
                $("#txtVecesAplicadasInfonavit").val("");
                $("#fechaAplicacionInfonavit").val("<?php echo date("Y-m-d");?>");
                $("#fechaSuspensionInfonavit").val("");
                $("#fechaRegistroInfonavit").val("<?php echo date("Y-m-d");?>");


                $("#mostrarClaveInfonavit").html(datos.clave);
                $("#mostrarConceptoInfonavit").html(datos.concepto);
                existeClaveInfonavit = datos.claveValor;
                existeConceptoInfonavit = datos.conceptoValor;

                if(existeConceptoInfonavit == 0){

                  selectConceptoInfonavit = new SlimSelect({
                    select: '#cmbConceptoInfonavit',
                    deselectLabel: '<span class="">✖</span>'
                  });

                }
            }
            else{

                $("#txtNumCreditoInfonavit").val(datos.num_credito);
                $("#txtCuotaFija").val(datos.importe_fijo);
                $("#fechaAplicacionInfonavit").val(datos.fecha_apli); 
                $("#fechaSuspensionInfonavit").val(datos.fecha_sus);
                $("#fechaRegistroInfonavit").val(datos.fecha_reg);
                $("#txtMontoAcumuladoInfonavit").val(datos.importe_acum);
                $("#txtVecesAplicadasInfonavit").val(datos.veces_apli);
                $("#mostrarClaveInfonavit").html(datos.clave);
                $("#mostrarConceptoInfonavit").html(datos.concepto);

                $("#txtNumCreditoInfonavit").prop("readonly", true);
                $("#txtCuotaFija").prop("readonly", true);
                $("#fechaAplicacionInfonavit").prop("readonly", true);
                $("#fechaRegistroInfonavit").prop("readonly", true);
                $("#txtMontoAcumuladoInfonavit").prop("readonly", true);
                $("#txtVecesAplicadasInfonavit").prop("readonly", true);
                selectTipoCalculoInfonavit.disable();
            }

            $("#btnMostrarInfonavit").html(datos.botonInfonavit);
            $("#infonavitModal").modal('show');

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


    $(document).on("click","#agregarInfonavit",function(){

        let token = $("#csr_token_UT5JP").val();
        let idEmpleado = $("#empleado").val();
        
        if(idEmpleado == "" || idEmpleado < 1){
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

        let clave = "";
        if(existeClaveInfonavit == 1){
          clave = $("#txtClaveInfonavitUnica").val().trim();

          if(clave == '' || clave.length == 0){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "Ingresa la clave del INFONAVIT."
            });
            return;
          }

          if(clave.length > 15){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "La clave del INFONAVIT no puede ser mayor de 15 caracteres."
            });
            return;
          }

        }

        let concepto = "";
        if(existeConceptoInfonavit == 1){
          concepto = $("#txtClaveSATInfonavit").val().trim();

          if(concepto == '' || concepto.length == 0){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "Ingresa el concepto del INFONAVIT."
            });
            return;
          }

          if(concepto.length > 100){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "El concepto del INFONAVIT no puede ser mayor de 100 caracteres."
            });
            return;
          }
        }

        let numCredito = $("#txtNumCreditoInfonavit").val().trim();
        let tipoCalculo = $("#cmbTipoCalculoInfonavit").val();
        let CuotaFijaObj = numeral($("#txtCuotaFija").val().trim());
        let CuotaFija = CuotaFijaObj.value();
        let fechaAplicacion = $("#fechaAplicacionInfonavit").val();
        let fechaSuspension = $("#fechaSuspensionInfonavit").val();
        let fechaRegistro = $("#fechaRegistroInfonavit").val();
        let MontoAcumuladoObj = numeral($("#txtMontoAcumuladoInfonavit").val().trim());
        let MontoAcumulado = MontoAcumuladoObj.value();
        let vecesAplicadas = $("#txtVecesAplicadasInfonavit").val().trim();

        let fechaPago = '<?=$row_datos_nomina['fecha_pago_or']?>';

        if(numCredito == "" || numCredito == null){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "Ingresa el número de crédito."
            });
            return;
        }

        if(CuotaFija == "" || CuotaFija == null){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "Ingresa la cuota fija."
            });
            return;
        }

        if(fechaAplicacion == "" || fechaAplicacion == null){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Ingresa la fecha de aplicación."
          });
          return;
        }

        if(fechaSuspension != "" || fechaSuspension != null){
            if (new Date(fechaAplicacion).getTime() >= new Date(fechaSuspension).getTime()) {
              Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "La fecha de suspensión no puede ser anterior o igual a la fecha de aplicación.",
              });
              return;
            }
        }
        
        //$("#agregarInfonavit").prop("disabled", true);

        $.ajax({
          type: 'POST',
          url: 'functions/agregarCreditoInfonavit.php',
          data: { 
                  csr_token_UT5JP: token,
                  idEmpleado: idEmpleado,
                  clave: clave,
                  concepto: concepto,
                  numCredito: numCredito,
                  tipoCalculo: tipoCalculo,
                  CuotaFija: CuotaFija,
                  fechaAplicacion: fechaAplicacion,
                  fechaSuspension: fechaSuspension,
                  fechaRegistro: fechaRegistro,
                  MontoAcumulado: MontoAcumulado,
                  vecesAplicadas: vecesAplicadas,
                  idNomina: idNomina,
                  idNominaEmpleado: idNominaEmpleado,
                  existeConceptoInfonavit : existeConceptoInfonavit,
                  existeClaveInfonavit: existeClaveInfonavit,
                  fechaPago: fechaPago
                },
          success: function(r) {

            if(r == "existe-clave"){
              
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "La clave ingresada ya esta registrada.",
              });
              $("#agregarInfonavit").prop("disabled", false);

            }

            if(r == "existe-concepto"){
              
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "Ya se ha ingresado la clave para el crédito INFONAVIT.",
              });
              $("#agregarInfonavit").prop("disabled", false);

            }

            if(r == "exito"){
              
              Lobibox.notify("success", {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: false,
                img: '../../img/timdesk/checkmark.svg',
                msg: "Crédito guardado."
              });

              $("#infonavitModal").modal('toggle');
              $('#tblNominaEmpleado').DataTable().ajax.reload();

            }

            if(r == "fallo"){
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

    $(document).on("click","#modificarInfonavit",function(){

        let token = $("#csr_token_UT5JP").val(); 
        let idEmpleado = $("#empleado").val();
        let fechaPago = '<?php echo $row_datos_nomina['fecha_pago_or'];?>';

        let idCreditoInfonavit = $("#idCreditoInfonavit").val();
        let fechaAplicacion = $("#fechaAplicacionInfonavit").val();
        let fechaSuspension = $("#fechaSuspensionInfonavit").val();

        if(fechaSuspension != "" || fechaSuspension != null){
            if (new Date(fechaAplicacion).getTime() >= new Date(fechaSuspension).getTime()) {
              Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "La fecha de suspensión no puede ser anterior o igual a la fecha de aplicación.",
              });
              return;
            }
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
            text: "Se modificará la fecha de suspensión del crédito INFONAVIT.",
            icon: "warning",
            showCancelButton: true,
            cancelButtonText: '<span class="verticalCenter">Cerrar</span>',
            confirmButtonText: '<span class="verticalCenter">Modificar</span>',
            reverseButtons: true,
          })
          .then((result) => {
            if (result.isConfirmed) {

              $.ajax({
                type: 'POST',
                url: 'functions/modificarCreditoInfonavit.php',
                data: {
                  csr_token_UT5JP: token,
                  idCreditoInfonavit: idCreditoInfonavit,
                  fechaSuspension : fechaSuspension
                },
                success: function(data) {

                  if (data == "exito") {

                    Lobibox.notify("success", {
                      size: 'mini',
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: 'center top', //or 'center bottom'
                      icon: false,
                      img: '../../img/timdesk/checkmark.svg',
                      msg: "Se ha modficado la fecha de suspensión del crédito."
                    });

                    $("#infonavitModal").modal('toggle');

                  } 
                  else {

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

                  }
                }
              });


            } else if (
              /* Read more about handling dismissals below */
              result.dismiss === Swal.DismissReason.cancel
            ) {}
          });
    });
    
    $(document).on("click","#eliminarInfonavit",function(){

        let token = $("#csr_token_UT5JP").val(); 
        let idEmpleado = $("#empleado").val();
        let fechaPago = '<?php echo $row_datos_nomina['fecha_pago_or'];?>';

        let idCreditoInfonavit = $("#idCreditoInfonavit").val();

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
            text: "Se cancelará el crédito INFONAVIT.",
            icon: "warning",
            showCancelButton: true,
            cancelButtonText: '<span class="verticalCenter">Cerrar</span>',
            confirmButtonText: '<span class="verticalCenter">Cancelar</span>',
            reverseButtons: true,
          })
          .then((result) => {
            if (result.isConfirmed) {

              $.ajax({
                type: 'POST',
                url: 'functions/cancelarCreditoInfonavit.php',
                data: {
                  csr_token_UT5JP: token,
                  idCreditoInfonavit: idCreditoInfonavit
                },
                success: function(data) {

                  if (data == "exito") {

                    Lobibox.notify("success", {
                      size: 'mini',
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: 'center top', //or 'center bottom'
                      icon: false,
                      img: '../../img/timdesk/checkmark.svg',
                      msg: "Se ha cancelado el crédito de la nómina, pero las deducciones del crédito siguen vigentes en la nómina."
                    });

                    $("#infonavitModal").modal('toggle');

                  } 
                  else {

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

                  }
                }
              });


            } else if (
              /* Read more about handling dismissals below */
              result.dismiss === Swal.DismissReason.cancel
            ) {}
          });
    });



    let existeClavePensionAlimenticia = 0 , existeConceptoPensionAlimenticia = 0;
    let selectConceptoPensionAlimenticia;
    $("#pensionAlimenticiaBtn").click(function(){

      let idEmpleado = $("#empleado").val();
      let token = $("#csr_token_UT5JP").val(); 

      if(idEmpleado == "" || idEmpleado < 1){
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

      $("#txtPorcentajeAplicar").prop("readonly", false);
      $("#fechaAplicacionPensionAlimenticia").prop("readonly", false);

      //selectTipoCalculo.enable();

      $.ajax({
          type: 'POST',
          url: 'functions/mostrarPensionAlimenticia.php',
          data: { 
                  csr_token_UT5JP: token,
                  idEmpleado : idEmpleado
                },
          success: function(r) {

            var datos = JSON.parse(r);

            if(datos.existePension == 0){
                $("#txtPorcentajeAplicar").val("");
                $("#fechaAplicacion").val("<?php echo date("Y-m-d");?>");

                $("#mostrarClavePensionAlimenticia").html(datos.clave);
                $("#mostrarConceptoPensionAlimenticia").html(datos.concepto);
                existeClavePensionAlimenticia = datos.claveValor;
                existeConceptoPensionAlimenticia = datos.conceptoValor;

                if(existeConceptoPensionAlimenticia == 0){

                  selectConceptoPensionAlimenticia = new SlimSelect({
                    select: '#cmbConceptoPension',
                    deselectLabel: '<span class="">✖</span>'
                  });

                }
            }
            else{

                if(datos.tipoPension == 2){
                  $("#pensionAlimenticiaOpc1").prop("checked", true);
                }
                if(datos.tipoPension == 3){
                  $("#pensionAlimenticiaOpc2").prop("checked", true);
                }
                if(datos.tipoPension == 4){
                  $("#pensionAlimenticiaOpc3").prop("checked", true);
                }

                $("#txtPorcentajeAplicar").val(datos.tasa_pension);
                $("#fechaAplicacionPensionAlimenticia").val(datos.fecha_apli);
                $("#mostrarClavePensionAlimenticia").html(datos.clave);
                $("#mostrarConceptoPensionAlimenticia").html(datos.concepto);

                //selectTipoCalculo.disable();
            }

            $("#btnMostrarPensionAlimenticia").html(datos.botonPension);
            $("#pensionalimenticiaModal").modal('show');

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


    $(document).on("click","#agregarPension",function(){

        let token = $("#csr_token_UT5JP").val();
        let idEmpleado = $("#empleado").val();
        
        if(idEmpleado == "" || idEmpleado < 1){
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

        let clave = "";
        if(existeClavePensionAlimenticia == 1){
          clave = $("#txtClavePensionUnica").val().trim();

          if(clave == '' || clave.length == 0){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "Ingresa la clave de pensión alimenticia."
            });
            return;
          }

          if(clave.length > 15){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "La clave de la pesnión alimenticia no puede ser mayor de 15 caracteres."
            });
            return;
          }

        }

        let concepto = "";
        if(existeConceptoPensionAlimenticia == 1){
          concepto = $("#txtClaveSATPension").val().trim();

          if(concepto == '' || concepto.length == 0){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "Ingresa el concepto de la pensión alimenticia."
            });
            return;
          }

          if(concepto.length > 100){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "El concepto de la pensión alimenticia no puede ser mayor de 100 caracteres."
            });
            return;
          }
        }

        let fechaAplicacion = $("#fechaAplicacionPensionAlimenticia").val();
        let PorcentajeAplicar = $("#txtPorcentajeAplicar").val().trim();
        let pensionAlimenticiaTipo = $('.pensionAlimenticiaClass:checked').val();
        let fechaPago = '<?=$row_datos_nomina['fecha_pago_or']?>';

        if(pensionAlimenticiaTipo == "" || pensionAlimenticiaTipo == null){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "Selleciona el tipo de porcentaje a aplicar."
            });
            return;
        }

        if(PorcentajeAplicar == "" || PorcentajeAplicar == null){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "Ingresa el porcentaje a aplicar."
            });
            return;
        }

        if(fechaAplicacion == "" || fechaAplicacion == null){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "Ingresa la fecha de aplicación."
            });
            return;
        }

        $("#agregarPension").prop("disabled", true);

        $.ajax({
          type: 'POST',
          url: 'functions/agregarPensionAlimenticia.php',
          data: { 
                  csr_token_UT5JP: token,
                  idEmpleado: idEmpleado,
                  clave: clave,
                  concepto: concepto,
                  fechaAplicacion: fechaAplicacion,
                  PorcentajeAplicar: PorcentajeAplicar,
                  pensionAlimenticiaTipo: pensionAlimenticiaTipo,
                  idNomina: idNomina,
                  idNominaEmpleado: idNominaEmpleado,
                  existeConceptoPensionAlimenticia : existeConceptoPensionAlimenticia,
                  existeClavePensionAlimenticia : existeClavePensionAlimenticia,
                  fechaPago: fechaPago
                },
          success: function(r) {

            if(r == "existe-clave"){
              
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "La clave ingresada ya esta registrada.",
              });
              $("#agregarPension").prop("disabled", false);

            }

            if(r == "existe-concepto"){
              
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "Ya se ha ingresado la clave para el crédito FONACOT.",
              });
              $("#agregarPension").prop("disabled", false);

            }

            if(r == "exito"){
              
              Lobibox.notify("success", {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: false,
                img: '../../img/timdesk/checkmark.svg',
                msg: "Crédito guardado."
              });

              $("#pensionalimenticiaModal").modal('toggle');
              $('#tblNominaEmpleado').DataTable().ajax.reload();

            }

            if(r == "fallo"){
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
              $("#agregarPension").prop("disabled", false);
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
            $("#agregarPension").prop("disabled", false);
          }
        });
        
    });  
    
    $(document).on("click","#eliminarPension",function(){

        let token = $("#csr_token_UT5JP").val(); 
        let idEmpleado = $("#empleado").val();
        let fechaPago = '<?php echo $row_datos_nomina['fecha_pago_or'];?>';

        let idPensionAlimenticia = $("#idPensionAlimenticia").val();

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
            text: "Se cancelará la pensión alimenticia.",
            icon: "warning",
            showCancelButton: true,
            cancelButtonText: '<span class="verticalCenter">Cerrar</span>',
            confirmButtonText: '<span class="verticalCenter">Cancelar</span>',
            reverseButtons: true,
          })
          .then((result) => {
            if (result.isConfirmed) {

              $.ajax({
                type: 'POST',
                url: 'functions/cancelarPensionAlimenticia.php',
                data: {
                  csr_token_UT5JP: token,
                  idPensionAlimenticia: idPensionAlimenticia,
                  estado: 2
                },
                success: function(data) {

                  if (data == "exito") {

                    Lobibox.notify("success", {
                      size: 'mini',
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: 'center top', //or 'center bottom'
                      icon: false,
                      img: '../../img/timdesk/checkmark.svg',
                      msg: "Se ha cancelado la pensión de la nómina, pero las deducciones de la pensión siguen vigentes en la nómina."
                    });

                    $("#pensionalimenticiaModal").modal('toggle');

                  } 
                  else {

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

                  }
                }
              });


            } else if (
              /* Read more about handling dismissals below */
              result.dismiss === Swal.DismissReason.cancel
            ) {}
          });
    });

    $(document).on("click","#completarPension",function(){

        let token = $("#csr_token_UT5JP").val(); 
        let idEmpleado = $("#empleado").val();
        let fechaPago = '<?php echo $row_datos_nomina['fecha_pago_or'];?>';

        let idPensionAlimenticia = $("#idPensionAlimenticia").val();

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
            text: "Se finalizará la pensión alimenticia.",
            icon: "warning",
            showCancelButton: true,
            cancelButtonText: '<span class="verticalCenter">Cerrar</span>',
            confirmButtonText: '<span class="verticalCenter">Finalizar</span>',
            reverseButtons: true,
          })
          .then((result) => {
            if (result.isConfirmed) {

              $.ajax({
                type: 'POST',
                url: 'functions/cancelarPensionAlimenticia.php',
                data: {
                  csr_token_UT5JP: token,
                  idPensionAlimenticia: idPensionAlimenticia,
                  estado: 3
                },
                success: function(data) {

                  if (data == "exito") {

                    Lobibox.notify("success", {
                      size: 'mini',
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: 'center top', //or 'center bottom'
                      icon: false,
                      img: '../../img/timdesk/checkmark.svg',
                      msg: "Se ha finalizado la pensión de la nómina, ya no se aplicará en las próximas nóminas."
                    });

                    $("#pensionalimenticiaModal").modal('toggle');

                  } 
                  else {

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

                  }
                }
              });


            } else if (
              /* Read more about handling dismissals below */
              result.dismiss === Swal.DismissReason.cancel
            ) {}
          });
    });


    $(document).on("click","#modificarPension",function(){

        let token = $("#csr_token_UT5JP").val();
        let idEmpleado = $("#empleado").val();
        
        if(idEmpleado == "" || idEmpleado < 1){
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

        let fechaAplicacion = $("#fechaAplicacionPensionAlimenticia").val();
        let PorcentajeAplicar = $("#txtPorcentajeAplicar").val().trim();
        let pensionAlimenticiaTipo = $('.pensionAlimenticiaClass:checked').val();
        let fechaPago = '<?=$row_datos_nomina['fecha_pago_or']?>';
        let idPensionAlimenticia = $("#idPensionAlimenticia").val();

        if(pensionAlimenticiaTipo == "" || pensionAlimenticiaTipo == null){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "Selleciona el tipo de porcentaje a aplicar."
            });
            return;
        }

        if(PorcentajeAplicar == "" || PorcentajeAplicar == null){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "Ingresa el porcentaje a aplicar."
            });
            return;
        }

        if(fechaAplicacion == "" || fechaAplicacion == null){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "Ingresa la fecha de aplicación."
            });
            return;
        }

        $("#modificarPension").prop("disabled", true);

        $.ajax({
          type: 'POST',
          url: 'functions/modificarPensionAlimenticia.php',
          data: { 
                  csr_token_UT5JP: token,
                  idEmpleado: idEmpleado,
                  idPensionAlimenticia : idPensionAlimenticia,
                  fechaAplicacion: fechaAplicacion,
                  PorcentajeAplicar: PorcentajeAplicar,
                  pensionAlimenticiaTipo: pensionAlimenticiaTipo,
                  idNomina: idNomina,
                  idNominaEmpleado: idNominaEmpleado,
                  fechaPago: fechaPago
                },
          success: function(r) {


            if(r == "exito"){
              
              Swal.fire(
                'Pensión modificada',
                'Se ha modificado la pensión pero los importes ya aplicados no se han modificado. el cambio se aplicará en las próximas nóminas.',
                'success'
              )

              $("#pensionalimenticiaModal").modal('toggle');
              $('#tblNominaEmpleado').DataTable().ajax.reload();

            }

            if(r == "fallo"){
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
              $("#agregarPension").prop("disabled", false);
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
            $("#agregarPension").prop("disabled", false);
          }
        });
        
    });
    
    $(document).on("click","#autorizarNomina",function(){

      let idEmpleado = ($("#empleado").val());
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
          text: "Se autorizará la nómina. Una vez que se autorice sólo podrás desautorizar la última nómina autorizada y sin timbrar.",
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
              url: 'functions/autorizarNomina.php',
              data: {
                csr_token_UT5JP: token,
                idNomina: idNomina
              },
              success: function(r) {

                if (r == "exito") {
                  Lobibox.notify("success", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/checkmark.svg",
                    msg: "Nómina autorizada, ya la puedes timbrar.",
                  });

                  let nominaActualizacion = '<center>' +
                      '<span class="btn-custom btn-custom--blue size-btn">Nómina autorizada</button>' +
                      '</center>';
                  $("#nominaAutorizar").html(nominaActualizacion);
                  $("#funcionesNomina").css("display", "none");
                  $("#nominaDesautorizada").css("display", "block");

                  if(idEmpleado != null){
                    $('#tblNominaEmpleado').DataTable().ajax.reload();
                  }

                  nominaAutorizadaG = 1;
                }
                if (r == "nomina-ya-autorizada") {
                  Lobibox.notify("success", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/checkmark.svg",
                    msg: "La nómina ya esta autorizada.",
                  });

                  let nominaActualizacion = '<center>' +
                      '<span class="btn-custom btn-custom--blue size-btn">Nómina autorizada</button>' +
                      '</center>';
                  $("#nominaAutorizar").html(nominaActualizacion);
                  $("#funcionesNomina").css("display", "none");
                  $("#nominaDesautorizada").css("display", "block");

                  if(idEmpleado != null){
                    $('#tblNominaEmpleado').DataTable().ajax.reload();
                  }

                  nominaAutorizadaG = 1;
                } 
                if (r == "no-autorizado"){
                  Lobibox.notify("error", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/notificacion_error.svg",
                    msg: "La nómina anterior no está autorizada, no puedes proceder a modificar está nómina hasta que autorices la anterior.",
                  });
                }
                if (r == "no-existen-nominas"){
                  Lobibox.notify("error", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/notificacion_error.svg",
                    msg: "No se ha generado ningúna nómina por empleado, sólo se podrá autorizar la nómina después de generar la nómina de los empleados.",
                  });
                }
                if (r == "fallo"){
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
              error: function() {
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
    
    $(document).on("click","#desautorizarNomina",function(){

      let idEmpleado = ($("#empleado").val());
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
          text: "Se desautorizará la nómina y podrás volver a hacer modificaciones.",
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
              url: 'functions/desautorizarNomina.php',
              data: {
                csr_token_UT5JP: token,
                idNomina: idNomina
              },
              success: function(r) {

                if (r == "exito") {
                  Lobibox.notify("success", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/checkmark.svg",
                    msg: "La nómina se ha desautorizado.",
                  });

                  let nominaActualizacion = '<center>' +
                                              '<button type="button" class="btn-custom btn-custom--border-blue size-btn" id="autorizarNomina">Autorizar nómina</button>' +
                                            '</center>';
                  $("#nominaAutorizar").html(nominaActualizacion);
                  $("#funcionesNomina").css("display", "block");
                  $("#nominaDesautorizada").css("display", "none");

                  if(idEmpleado != null){
                    $('#tblNominaEmpleado').DataTable().ajax.reload();
                  }

                  nominaAutorizadaG = 0;
                }
                if (r == "nomina-ya-desautorizada") {
                  Lobibox.notify("success", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/checkmark.svg",
                    msg: "La nómina se ha desautorizado.",
                  });

                  let nominaActualizacion = '<center>' +
                                              '<button type="button" class="btn-custom btn-custom--border-blue size-btn" id="autorizarNomina">Autorizar nómina</button>' +
                                            '</center>';
                  $("#nominaAutorizar").html(nominaActualizacion);
                  $("#funcionesNomina").css("display", "block");
                  $("#nominaDesautorizada").css("display", "none");

                  if(idEmpleado != null){
                    $('#tblNominaEmpleado').DataTable().ajax.reload();
                  }

                  nominaAutorizadaG = 0;
                }

                if (r == "nomina-ya-timbrada"){
                  Lobibox.notify("error", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/notificacion_error.svg",
                    msg: "La nómina ya está timbrada, si quieres desautorizarla, primero tienes que cancelar el timbrado de las facturas de esta nómina.",
                  });
                }

                if (r == "nomina-ya-timbradas"){
                  Lobibox.notify("error", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/notificacion_error.svg",
                    msg: "Uno de los empleados ya tiene timbrada su nómina, si quieres desautorizarla, primero tienes que cancelar el timbrado de cada empleado en esta nómina.",
                  });
                }
                
                if (r == "nomina-siguiente-autorizada"){
                  Lobibox.notify("error", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/notificacion_error.svg",
                    msg: "La nómina siguiente ya está autorizada, sólo puedes desautorizar la última nómina autorizada.",
                  });
                }
                if (r == "fallo"){
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
              error: function() {
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

    var selectEmpleado = new SlimSelect({
      select: '#empleado',
      deselectLabel: '<span class="">✖</span>'
    });

    selectEmpleado.enable()

    selectConcepto = new SlimSelect({
      select: '#cmbConcepto',
      deselectLabel: '<span class="">✖</span>'
    });

    selectClaveSAT = new SlimSelect({
      select: '#cmbClaveSAT',
      deselectLabel: '<span class="">✖</span>'
    });

    selectMotivo = new SlimSelect({
      select: '#cmbMotivo',
      deselectLabel: '<span class="">✖</span>'
    });
    
    selectTipoHoras = new SlimSelect({
      select: '#tipoHoras',
      deselectLabel: '<span class="">✖</span>'
    });

    selectTipoIncapacidad = new SlimSelect({
      select: '#cmbTipoIncapacidad',
      deselectLabel: '<span class="">✖</span>'
    });
    
    var selectClaveAgregar = new SlimSelect({
      select: '#cmbConceptoClave',
      deselectLabel: '<span class="">✖</span>'
    });

    var selectTipoCalculo = new SlimSelect({
      select: '#cmbTipoCalculo',
      deselectLabel: '<span class="">✖</span>'
    });

    var selectTipoCalculoInfonavit = new SlimSelect({
      select: '#cmbTipoCalculoInfonavit',
      deselectLabel: '<span class="">✖</span>'
    });

    var selectMotivoIncapacidad = new SlimSelect({
      select: '#cmbMotivoIncapacidad',
      deselectLabel: '<span class="">✖</span>'
    });

    var selectTipoIncapacidadPer = new SlimSelect({
      select: '#cmbTipoIncapacidadPer',
      deselectLabel: '<span class="">✖</span>'
    });

    var selectRiesgoIncapacidad = new SlimSelect({
      select: '#cmbRiesgo',
      deselectLabel: '<span class="">✖</span>'
    });

    new Cleave('.txtImporte', {
      numeral: true,
      numeralDecimalMark: '.',
      delimiter: ','
    });

    new Cleave('.txtImporteEdit', {
      numeral: true,
      numeralDecimalMark: '.',
      delimiter: ','
    });

    new Cleave('.txtPrimaDominical', {
      numeral: true,
      numeralDecimalMark: '.',
      delimiter: ','
    });

    new Cleave('.txtImporteDiasFalta', {
      numeral: true,
      numeralDecimalMark: '.',
      delimiter: ','
    });

    new Cleave('.txtImporteDiasFaltaEdit', {
      numeral: true,
      numeralDecimalMark: '.',
      delimiter: ','
    });

    new Cleave('.txtPorcentajeIncapacidad', {
      numeral: true,
      numeralDecimalMark: '.',
      delimiter: ','
    });

    new Cleave('.txtImporteHoras', {
      numeral: true,
      numeralDecimalMark: '.',
      delimiter: ','
    });

    new Cleave('.txtImporteHorasEdit', {
      numeral: true,
      numeralDecimalMark: '.',
      delimiter: ','
    });
    
    new Cleave('.txtImporteTurno', {
      numeral: true,
      numeralDecimalMark: '.',
      delimiter: ','
    });

    new Cleave('.txtImporteTurnoEdit', {
      numeral: true,
      numeralDecimalMark: '.',
      delimiter: ','
    });

    new Cleave('#txtImporteFonacot', {
      numeral: true,
      numeralDecimalMark: '.',
      delimiter: ','
    });

    new Cleave('#txtImporteFijoFonacot', {
      numeral: true,
      numeralDecimalMark: '.',
      delimiter: ','
    });

    new Cleave('#txtPagosAnterioresFonacot', {
      numeral: true,
      numeralDecimalMark: '.',
      delimiter: ','
    });

    new Cleave('#txtCuotaFija', {
      numeral: true,
      numeralDecimalMark: '.',
      delimiter: ','
    });

    new Cleave('#txtMontoAcumuladoInfonavit', {
      numeral: true,
      numeralDecimalMark: '.',
      delimiter: ','
    });

    new Cleave('#txtCantidadAplicadaCredito', {
      numeral: true,
      numeralDecimalMark: '.',
      delimiter: ','
    });

    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }


$('#txtFolioIncapacidad').click(function() {
 /*     if (this.value.match(/[^a-zA-Z0-9]/g)) {
          this.value = this.value.replace(/[^a-zA-Z0-9]/g, '');
      }*/
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
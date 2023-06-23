<?php

session_start();

  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 1 || $_SESSION["FKRol"] == 4)){

    require_once('../../../include/db-conn.php');

    $fkEmpleado = $_GET['id'];

    $stmt = $conn->prepare("SELECT de.PKLaboralesEmpleado, e.*, p.Sueldo_semanal, p.Sueldo_semanal_bruto,
                            IFNULL(de.Dias_de_Vacaciones,0) as Dias_de_Vacaciones,
                            IFNULL(SUM(v.Dias_de_Vacaciones_Tomados),0) as Dias_de_Vacaciones_Tomados, t.Dias_de_trabajo, t.Turno, p.Puesto, dm.NSS,
                            va.DiasAgregados
                            FROM empleados as e
                            LEFT JOIN datos_laborales_empleado as de ON de.FKEmpleado = e.PKEmpleado
                            LEFT JOIN datos_medicos_empleado as dm ON dm.FKEmpleado = e.PKEmpleado
                            LEFT JOIN puestos as p ON p.PKPuesto = de.FKPuesto
                            LEFT JOIN vacaciones as v ON v.FKEmpleado = e.PKEmpleado AND YEAR(v.FechaIni) = YEAR(CURDATE())
                            LEFT JOIN turnos as t ON de.FKTurno = t.PKTurno
                            LEFT JOIN vacaciones_agregadas as va ON va.FKEmpleado = e.PKEmpleado AND va.Anio = YEAR(CURDATE())
                            WHERE e.PKEmpleado = :id ");
    $stmt->bindValue(':id',$fkEmpleado);
    $stmt->execute();
    $row = $stmt->fetch();
    $PKDatosEmpleo = $row['PKLaboralesEmpleado'];
    $fkEmpleado = $row['PKEmpleado'];
    $dias_trabajo = $row['Dias_de_trabajo'];
    $dias_vacaciones_actuales = $row['DiasAgregados'];

    $segundo_nombre = '';
    if(trim($row['Segundo_Nombre']) != ""){
      $segundo_nombre = ' '.$row['Segundo_Nombre'];
    }
    $nombreEmpleado = $row['Primer_Nombre'].$segundo_nombre.' '.$row['Apellido_Paterno'].' '.$row['Apellido_Materno'];

    $nss = $row['NSS'];
    $rfc = $row['RFC'];
    $turno = $row['Turno'];
    $puesto = $row['Puesto'];
    $fecha = "Sin datos";

    $sueldoSemanal = $row['Sueldo_semanal_bruto'];
    $sueldoMensual = number_format(($sueldoSemanal / 7) * 30, 2, '.', '');
    $sueldoDiario = number_format(($sueldoSemanal / 7), 2, '.', '');

    $sueldoVacaciones = number_format($sueldoDiario * $dias_vacaciones_actuales, 2, '.', '');
    $primaVacacional = number_format(($sueldoDiario * $dias_vacaciones_actuales) * 0.25, 2, '.', '');
    $dias_vacaciones_restantes =  $row['Dias_de_Vacaciones'];

    $stmt = $conn->prepare("SELECT cantidad FROM parametros
                            WHERE descripcion = 'UMA' OR descripcion = 'Dias_excentos_PV' ");
    $stmt->execute();
    $row_parametro = $stmt->fetchAll();

    $primaVacacionalExcenta = number_format($row_parametro[0]['cantidad'] * $row_parametro[1]['cantidad'], 2, '.', '');
    $primaVacacionalGravar = number_format($primaVacacional - $primaVacacionalExcenta, 2, '.', '');

    /*Calculo de impuestos vacaciones*/
    $base = number_format($sueldoMensual + $primaVacacionalGravar, 2, '.', '');

    $stmt = $conn->prepare("SELECT * FROM pagos_provisionales_mensuales WHERE Limite_inferior <= :impuestogravablemin AND Limite_superior >= :impuestogravablesup ");
    $stmt->bindValue(':impuestogravablemin',$base);
    $stmt->bindValue(':impuestogravablesup',$base);
    $stmt->execute();
    $row_limite = $stmt->fetch();

    $Limite_inferior = $row_limite['Limite_inferior'];
    $excedente_limite_inferior = number_format($base - $Limite_inferior, 2, '.', '');
    $porcentaje_tabla = $row_limite['Porcentaje_sobre_limite_inferior'];
    $impuesto_marginal = number_format($excedente_limite_inferior * ($porcentaje_tabla/100), 2, '.', '');
    $cuota_fija = $row_limite['Cuota_fija'];
    $ISRDeterminado = $impuesto_marginal + $cuota_fija;

    $stmt = $conn->prepare("SELECT * FROM subsidio_empleo WHERE IngresoMinimo <= :ingresominimo AND IngresoMaximo >= :ingresomaximo ");
    $stmt->bindValue(':ingresominimo',$sueldoMensual);
    $stmt->bindValue(':ingresomaximo',$sueldoMensual);
    $stmt->execute();
    $row_subsidio = $stmt->fetch();

    $subsidioMensual = $row_subsidio['SubsidioMensual'];
    $ISRRetenido = number_format($ISRDeterminado - $subsidioMensual, 2, '.', '');
    $tasaEfectiva = number_format( ($ISRRetenido / $base) * 100, 2, '.', '');

    //ISR 1
    $excedente_limite_inferior_isr1 = number_format($sueldoMensual - $Limite_inferior, 2, '.', '');
    $impuesto_marginal_isr1 = number_format($excedente_limite_inferior_isr1 * ($porcentaje_tabla / 100), 2, '.', '');
    $ISRDeterminado_isr1 =  number_format($impuesto_marginal_isr1 + $cuota_fija, 2, '.', '');
    $ISRRetenido_isr1 = number_format($ISRDeterminado_isr1 - $subsidioMensual, 2, '.', '');//sin vacaciones
    //ISR 1

    //ISR base para calcular el ISR 2
    $primaVacacionalGravarMensual = number_format(($primaVacacionalGravar / 365) * 30.4, 2, '.', '');
    $base_primavacacional = number_format($sueldoMensual + $primaVacacionalGravarMensual, 2, '.', '');
    //ISR final

    //ISR 2
     $stmt = $conn->prepare("SELECT * FROM pagos_provisionales_mensuales WHERE Limite_inferior <= :impuestogravablemin AND Limite_superior >= :impuestogravablesup ");
    $stmt->bindValue(':impuestogravablemin',$base_primavacacional);
    $stmt->bindValue(':impuestogravablesup',$base_primavacacional);
    $stmt->execute();
    $row_limite_isr2 = $stmt->fetch();

    $Limite_inferior_isr2 = $row_limite_isr2['Limite_inferior'];
    $excedente_limite_inferior_isr2 = number_format($base_primavacacional - $Limite_inferior_isr2, 2, '.', '');
    $impuesto_marginal_isr2= number_format($excedente_limite_inferior_isr2 * ($porcentaje_tabla / 100), 2, '.', '');
    $ISRDeterminado_isr2 =  number_format($impuesto_marginal_isr2 + $cuota_fija, 2, '.', '');
    $ISRRetenido_isr2 = number_format($ISRDeterminado_isr2 - $subsidioMensual, 2, '.', '');//con vacaciones
    //ISR 2

    $diferencia_isr = number_format($ISRRetenido_isr2 - $ISRRetenido_isr1, 2, '.', '');
    $tasaImpuesto = number_format(($diferencia_isr / $primaVacacionalGravarMensual) * 100, 2, '.', '');
    $ISRprimaVacacional = number_format($primaVacacionalGravar * ($tasaImpuesto / 100), 2, '.', '');

    $ISRRetenido_VacacionesPrima = number_format($ISRprimaVacacional + $ISRRetenido_isr2, 2, '.', '');

    /*FIN calculo de impuestos vacaciones*/
    //if($dias_vacaciones_restantes == 0){
      //header('Location:../vacaciones.php?id='.$fkEmpleado);
    //}

  }else {
    header("location:../dashboard.php");
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="icon" type="image/png" href="../../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Timlid | Agregar vacaciones</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../js/validaciones.js"></script>
  <!-- Core plugin JavaScript-->
  <script src="../../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../../js/sb-admin-2.min.js"></script>

  <!-- Custom fonts for this template-->
  <link href="../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../../../css/sb-admin-2.css" rel="stylesheet">
  <link href="../../../css/dashboard.css" rel="stylesheet">
  <style type="text/css">
    #info{
      display: none;
    }
    .alinear_derecha{
      text-align: right;
    }
  </style>

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
      $ruta = "../../";
      $ruteEdit = $ruta."central_notificaciones/";
      require_once('../../menu3.php');
    ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>


          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">
            <div id="alertaTareas"></div>
            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION["Usuario"] ?></span>
                <i class="fas fa-user-circle fa-3x"></i>
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Perfil
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Salir
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Agregar vacaciones</h1>
          </div>

          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4" id="agregarVacaciones">
                <div class="card-header">
                  Tarjeta del empleado
                </div>
                <div class="card-body">
                    <div class="row">
                      <div class="col-lg-12">
                        <form action="" method="post">
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-6">
                                <label><b>Empleado:</b> <?=$nombreEmpleado;?></label><br/>
                                <label><b>Días de vacaciones del año actual:</b> <?=$dias_vacaciones_actuales;?></label><br/>
                                <label><b>Días de vacaciones restantes:</b> <?=$dias_vacaciones_restantes;?></label>
                              </div>
                              <div class="col-lg-6">
                                <label><b>Sueldo diario:</b> <?=$sueldoDiario;?></label><br/>
                                <label><b>Sueldo semanal:</b> <?=$sueldoSemanal;?></label><br/>
                                <label><b>Sueldo mensual:</b> <?=$sueldoMensual;?></label><br/>
                              </div>
                            </div>
                            <br>
                            <div class="row">
                              <div class="col-lg-6">
                                <table class="table table-sm">
                                    <thead class="thead-light">
                                        <tr>
                                            <th colspan="2">Datos iniciales</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><b>Base(salario mensual)</b></td>
                                            <td class="alinear_derecha"><?=$base;?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Tasa</b></td>
                                            <td class="alinear_derecha"><?=$porcentaje_tabla;?> %</td>
                                        </tr>
                                        <tr>
                                            <td><b>Vacaciones</b></td>
                                            <td class="alinear_derecha"><?=$sueldoVacaciones;?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Prima Vacacional</b></td>
                                            <td class="alinear_derecha"><?=$primaVacacional;?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Prima Vacacional Excenta</b></td>
                                            <td class="alinear_derecha"><?=$primaVacacionalExcenta;?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Prima Vacacional a Gravar</b></td>
                                            <td class="alinear_derecha"><?=$primaVacacionalGravar;?></td>
                                        </tr>
                                    </tbody>
                                  </table>
                              </div>
                              <div class="col-lg-6">
                                <table class="table table-sm">
                                    <thead class="thead-light">
                                        <tr>
                                            <th colspan="2">ISR Retenido sin decreto</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><b>Base</b></td>
                                            <td class="alinear_derecha"><?=$base;?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Límite inferior</b></td>
                                            <td class="alinear_derecha"><?=$Limite_inferior;?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Excedente sobre límite inferior</b></td>
                                            <td class="alinear_derecha"><?=$excedente_limite_inferior;?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Impuesto marginal</b></td>
                                            <td class="alinear_derecha"><?=$impuesto_marginal;?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Cuota fija</b></td>
                                            <td class="alinear_derecha"><?=$cuota_fija;?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Impuesto sobre la renta determinado</b></td>
                                            <td class="alinear_derecha"><?=$ISRDeterminado;?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Subsidio para el empleo</b></td>
                                            <td class="alinear_derecha"><?=$subsidioMensual;?></td>
                                        </tr>
                                        <tr>
                                            <td><b>ISR Retenido</b></td>
                                            <td class="alinear_derecha"><?=$ISRRetenido;?></td>
                                            <!-- El que se paga sin decreto -->
                                        </tr>
                                        <tr>
                                            <td><b>Tasa efectiva</b></td>
                                            <td class="alinear_derecha"><?=$tasaEfectiva;?></td>
                                            <!-- Tasa efectiva del ISR sin decreto -->
                                        </tr>
                                    </tbody>
                                  </table>
                              </div>
                            </div>

                            <br>
                            <div class="row">
                              <div class="col-lg-6">
                                <table class="table table-sm">
                                      <thead class="thead-light">
                                          <tr>
                                              <th colspan="2">ISR Retenido 1</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          <tr>
                                              <td><b>Base</b></td>
                                              <td class="alinear_derecha"><?=$base;?></td>
                                          </tr>
                                          <tr>
                                              <td><b>Límite inferior</b></td>
                                              <td class="alinear_derecha"><?=$Limite_inferior;?></td>
                                          </tr>
                                          <tr>
                                              <td><b>Excedente sobre límite inferior</b></td>
                                              <td class="alinear_derecha"><?=$excedente_limite_inferior_isr1;?></td>
                                          </tr>
                                          <tr>
                                              <td><b>Porcentaje en tabla</b></td>
                                              <td class="alinear_derecha"><?=$porcentaje_tabla;?></td>
                                          </tr>
                                          <tr>
                                              <td><b>Impuesto marginal</b></td>
                                              <td class="alinear_derecha"><?=$impuesto_marginal_isr1;?></td>
                                          </tr>
                                          <tr>
                                              <td><b>Cuota fija</b></td>
                                              <td class="alinear_derecha"><?=$cuota_fija;?></td>
                                          </tr>
                                          <tr>
                                              <td><b>Impuesto sobre la renta determinado</b></td>
                                              <td class="alinear_derecha"><?=$ISRDeterminado_isr1;?></td>
                                          </tr>
                                          <tr>
                                              <td><b>Subsidio para el empleo</b></td>
                                              <td class="alinear_derecha"><?=$subsidioMensual;?></td>
                                          </tr>
                                          <tr>
                                              <td><b>ISR 1 Retenido</b></td>
                                              <td class="alinear_derecha"><?=$ISRRetenido_isr1;?></td>
                                          </tr>
                                      </tbody>
                                    </table>
                              </div>
                              <div class="col-lg-6">
                                <table class="table table-sm">
                                      <thead class="thead-light">
                                          <tr>
                                              <th colspan="2">ISR Retenido 1</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          <tr>
                                              <td><b>Sueldo Mensual</b></td>
                                              <td class="alinear_derecha"><?=$sueldoMensual;?></td>
                                          </tr>
                                          <tr>
                                              <td><b>ISR</b></td>
                                              <td class="alinear_derecha"><?=$ISRRetenido_isr1;?></td>
                                          </tr>
                                          <tr>
                                              <td><b>Prima Vacacional</b></td>
                                              <td class="alinear_derecha"><?=$primaVacacional;?></td>
                                          </tr>
                                          <tr>
                                              <td><b>Excento</b></td>
                                              <td class="alinear_derecha"><?=$primaVacacionalExcenta;?></td>
                                          </tr>
                                          <tr>
                                              <td><b>Prima Vacacional Gravada Anual</b></td>
                                              <td class="alinear_derecha"><?=$primaVacacionalGravar;?></td>
                                          </tr>
                                          <tr>
                                              <td><b>Prima Vacacional Gravada Mensual</b></td>
                                              <td class="alinear_derecha"><?=$primaVacacionalGravarMensual;?></td>
                                          </tr>
                                          <tr>
                                              <td><b>Base</b></td>
                                              <td class="alinear_derecha"><?=$base_primavacacional;?></td>
                                          </tr>
                                      </tbody>
                                    </table>
                              </div>
                            </div>
                            <br>
                            <div class="row">
                              <div class="col-lg-6">
                                <table class="table table-sm">
                                      <thead class="thead-light">
                                          <tr>
                                              <th colspan="2">ISR Retenido 2</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          <tr>
                                              <td><b>Base</b></td>
                                              <td class="alinear_derecha"><?=$sueldoMensual;?></td>
                                          </tr>
                                          <tr>
                                              <td><b>Límite inferior</b></td>
                                              <td class="alinear_derecha"><?=$Limite_inferior_isr2;?></td>
                                          </tr>
                                          <tr>
                                              <td><b>Excedente sobre límite inferior</b></td>
                                              <td class="alinear_derecha"><?=$excedente_limite_inferior_isr2;?></td>
                                          </tr>
                                          <tr>
                                              <td><b>Porcentaje en tabla</b></td>
                                              <td class="alinear_derecha"><?=$porcentaje_tabla;?></td>
                                          </tr>
                                          <tr>
                                              <td><b>Impuesto marginal</b></td>
                                              <td class="alinear_derecha"><?=$impuesto_marginal_isr2;?></td>
                                          </tr>
                                          <tr>
                                              <td><b>Cuota fija</b></td>
                                              <td class="alinear_derecha"><?=$cuota_fija;?></td>
                                          </tr>
                                          <tr>
                                              <td><b>Impuesto sobre la renta determinado</b></td>
                                              <td class="alinear_derecha"><?=$ISRDeterminado_isr2;?></td>
                                          </tr>
                                          <tr>
                                              <td><b>Subsidio para el empleo</b></td>
                                              <td class="alinear_derecha"><?=$subsidioMensual;?></td>
                                          </tr>
                                          <tr>
                                              <td><b>ISR 2 Retenido</b></td>
                                              <td class="alinear_derecha"><?=$ISRRetenido_isr2;?></td>
                                          </tr>
                                      </tbody>
                                    </table>
                              </div>
                              <div class="col-lg-6">
                                <table class="table table-sm">
                                      <thead class="thead-light">
                                          <tr>
                                              <th colspan="2">ISR Final</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          <tr>
                                              <td><b>ISR 2(con vacaciones)</b></td>
                                              <td class="alinear_derecha"><?=$ISRRetenido_isr2;?></td>
                                          </tr>
                                          <tr>
                                              <td><b>ISR 1(sin vacaciones)</b></td>
                                              <td class="alinear_derecha"><?=$ISRRetenido_isr1;?></td>
                                          </tr>
                                          <tr>
                                              <td><b>Diferencia</b></td>
                                              <td class="alinear_derecha"><?=$diferencia_isr;?></td>
                                          </tr>
                                          <tr>
                                              <td><b>Tasa Impuesto</b></td>
                                              <td class="alinear_derecha"><?=$tasaImpuesto;?></td>
                                          </tr>
                                          <tr>
                                              <td><b>ISR de la prima vacacional</b></td>
                                              <td class="alinear_derecha"><?=$ISRprimaVacacional;?></td>
                                          </tr>
                                          <tr>
                                              <td><b>ISR Retenido(ISR Vacaciones + ISR Prima Vacacional)</b></td>
                                              <td class="alinear_derecha"><?=$ISRRetenido_VacacionesPrima;?></td>
                                              <!-- ISR a pagar con decreto-->
                                          </tr>
                                      </tbody>
                                    </table>
                              </div>
                            </div>
                          </div>
                          <br>
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-6">
                                <label for="usr">Fecha de inicio:</label>
                                <input type="date" name="txtFechaInicio" id="txtFechaInicio" class="form-control" step="1" required >
                              </div>
                              <div class="col-lg-6">
                                <label for="usr">Fecha de termino:</label>
                                <input type="date" name="txtFechaTermino" id="txtFechaTermino" class="form-control" step="1" required>
                              </div>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-12">
                                <center><div id="info" class="alert alert-danger" role="alert"></div></center>
                              </div>
                            </div>
                          </div>
                          <input type="hidden" name="prima_vacacional" id="prima_vacacional" value="<?=$prima_vacacional;?>">
                          <input type="hidden" name="total_vacaciones" id="total_vacaciones" value="<?=$total_vacaciones;?>">
                          <button type="button" class="btn btn-success float-right" name="btnAgregar" id="btnAgregar">Agregar</button>
                        </form>
                      </div>
                    </div>

                </div>
              </div>

            </div>
          </div>

          <?php

            $sueldoSemanalVacaciones = number_format(0.00,2);
            $primaVacacional = number_format(0.00,2);
            $sueldoTotal = 0.00;

          ?>

          <div class="card shadow mb-4" id="divRecibo" style="display:none">
            <div class="card-header py-3">
              <div class="row">
                <div class="col-lg-10">
                  <b>Recibo</b>
                </div>
                <div class="col-lg-2">

                </div>
              </div>

            </div>
            <div class="card-body">
            <form action="" method="post" id="frmNomina">
                <br>
                <div class="row">
                  <div class="col-lg-12">
                    <center><h4>Recibo de Vacaciones</h4></center><br>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-4">
                    <label><b>Nombre:</b> <?=$nombreEmpleado;?></label><br>
                    <label><b>NSS:</b> <?=$nss;?></label><br>
                    <label><b>RFC:</b> <?=$rfc;?></label><br>
                  </div>
                  <div class="col-lg-4">

                  </div>
                  <div class="col-lg-4">

                    <label><b>Turno:</b> <?=$turno;?></label><br>
                    <label><b>Puesto:</b> <?=$puesto;?></label><br>
                    <label><b>Días de vacaciones: </b><span id="lbldias_vacaciones"><?=$fecha;?></span></label>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-12">
                    <center><label><b>Periodo de vacaciones: </b><span id="periodo_vacaciones"><?=$fecha;?></label></span></center>
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-lg-3">
                    <div class="row">
                      <div class="col-lg-4">
                        <b>Acciones</b>
                      </div>
                      <div class="col-lg-8">
                        <b>Concepto</b>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <b><label class="float-right">Percepción</label></b>
                  </div>
                  <div class="col-lg-3">
                    <b><label class="float-right">Deducción</label></b>
                  </div>
                  <div class="col-lg-3">
                    <b><label class="float-right">Total</label></b>
                  </div>
                </div>
                <hr>

                <div class="row">
                  <div class="col-lg-3">
                    <div class="row">
                      <div class="col-lg-4">

                      </div>
                      <div class="col-lg-8">
                        Sueldo vacaciones
                      </div>
                    </div>

                  </div>
                  <div class="col-lg-3">
                    <label class="float-right" id="lblSueldoVacaciones"><?=$sueldoSemanalVacaciones;?></label>
                  </div>
                  <div class="col-lg-3">
                  </div>
                  <div class="col-lg-3">
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-3">
                    <div class="row">
                      <div class="col-lg-4">
                      </div>
                      <div class="col-lg-8">
                        Prima vacacional
                      </div>
                    </div>

                    <br>
                  </div>
                  <div class="col-lg-3">
                    <label id="lblPrimaVacacional" class="float-right"><?=$primaVacacional;?></label>
                  </div>
                  <div class="col-lg-3">
                  </div>
                  <div class="col-lg-3">
                  </div>
                </div>

                <hr>
                <div class="row">
                  <div class="col-lg-3">
                  </div>
                  <div class="col-lg-3">
                  </div>
                  <div class="col-lg-3">
                  </div>
                  <div class="col-lg-3">
                    <label name="lblTotal" id="lblTotal" class="float-right"><?=number_format($sueldoTotal, 2, '.', '');?></label>
                  </div>
                </div>
                <a href="recibovacacionespdf.php?id=8" class="btn btn-success float-right" target="_blank" id="linkcambio">Imprimir recibo</a>
              </form>
              <br>
            </div>
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy;  Timlid 2020</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>


    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">¿En serio quieres salir?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">Selecciona "Salir" Para cerrar tu sesión</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
            <a class="btn btn-primary" href="../../logout.php">Salir</a>
          </div>
        </div>
      </div>
    </div>

<script type="text/javascript">

 function ajaxFunction(){
   var ajaxRequest;

     try{
       // Opera 8.0+, Firefox, Safari
       ajaxRequest = new XMLHttpRequest();
       }catch (e){
         // Internet Explorer Browsers
         try{
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
         }catch (e) {
            try{
               ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            }catch (e){
               //alert("Your browser broke!");
               return false;
            }
         }
       }
  }


$("#btnAgregar").click(function(){
  var fecha_ini = $('#txtFechaInicio').val();
  var fecha_fin = $('#txtFechaTermino').val();


  if(fecha_ini === ''){
    $("#txtFechaInicio")[0].reportValidity();
    $("#txtFechaInicio")[0].setCustomValidity('Completa este campo.');
    return;
  }

  if(fecha_fin === ''){
    $("#txtFechaTermino")[0].reportValidity();
    $("#txtFechaTermino")[0].setCustomValidity('Completa este campo.');
    return;
  }

  var dias_vacaciones = $('#dias_vacaciones').val();
  var prima_vacacional = $('#prima_vacacional').val();
  var total_vacaciones = $('#total_vacaciones').val();
  var fkEmpleado = <?php echo $fkEmpleado;?>;
  var dias_trabajo = <?php echo $dias_trabajo;?>;

  var myData={"fkEmpleado":fkEmpleado,"dias_vacaciones":dias_vacaciones,"prima_vacacional":prima_vacacional,"total_vacaciones":total_vacaciones,"txtFechaInicio":fecha_ini,"txtFechaTermino":fecha_fin,"dias_trabajo":dias_trabajo};

  $.ajax({
      url : "function_agregar_Vacaciones.php",
      type: "POST",
      data : myData,
      success: function(data,status,xhr)
       {
          var datos = JSON.parse(data);

          $("#info").html("");

          if(datos.error == 2){
            $("#info").css("display","block");
            $("#info").html("La fecha de termino no puede ser mayor que la de inicio.");
            setTimeout(function(){
                $("#info").css("display","none");
              }, 3000);
            return;
          }

          if(datos.error == 1){
            $("#info").css("display","block");
            $("#info").html("El número de días seleccionados es diferente que el rango de fechas.");
            setTimeout(function(){
                $("#info").css("display","none");
              }, 3000);
            return;
          }

          if(datos.error == 0){
              var divoculto = document.getElementById('agregarVacaciones');
              divoculto.style.display = "none";

              var div = document.getElementById('divRecibo');
              div.style.display = "block";

              var new_position = jQuery('#divRecibo').offset();
              window.scrollTo(new_position.left,new_position.top);

              $("#linkcambio").attr("href","recibovacacionespdf.php?id=" + datos.last_id);
              $('#lbldias_vacaciones').html(dias_vacaciones);
              $('#periodo_vacaciones').html(datos.fecha);
              $('#lblSueldoVacaciones').html(datos.sueldo_vacaciones);
              $('#lblPrimaVacacional').html(prima_vacacional);
              $('#lblTotal').html(total_vacaciones);
          }
       }

  });


});


   $("#dias_vacaciones").change(function() {
        var fkEmpleado = <?php echo $fkEmpleado;?>;
        var sueldoSemanal = <?php echo $sueldoSemanal;?>;
        var dias_vacaciones = $("#dias_vacaciones").val();

        $.ajax({
          url: 'function_vacacionescalculo.php?fkEmpleado='+fkEmpleado+'&dias='+dias_vacaciones+'&sueldoSemanal='+sueldoSemanal,
          success: function(data){
             var datos = JSON.parse(data);
             $('#prima_vacacional').val(datos.prima_vacacional);
             $('#total_vacaciones').val(datos.total_vacaciones);
             var info = "<span id='sueldo_vacaciones'><b>Sueldo vacaciones: </b></span>" + datos.salario_vacaciones + "<br>" +
                        "<span id='prima_vacacionaltxt'><b>Prima vacacional: </b></span>" + datos.prima_vacacional + "<br>" +
                        "<span id='sueldo_total'><b>Sueldo total: </b></span>" + datos.total_vacaciones;

             $('#mostrarVacaciones').html(info);
      }});
    });

    $(document).ready(function(){
      $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
        setInterval(refrescar, 5000);
      });
    function refrescar(){
      $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
    }

</script>


</body>

</html>

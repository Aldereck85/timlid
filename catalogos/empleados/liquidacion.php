<?php
session_start();

  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 1 || $_SESSION["FKRol"] == 4)){
    require_once('../../include/db-conn.php');

    $fkEmpleado = $_GET['id'];

  if(isset ($_POST['btnGuardar'])){
      $fechaSalida = $_POST['txtFechaSalida'];

      if($_POST['txtSalariosDevengados'] != "")
        $salarioDevengado = $_POST['txtSalariosDevengados'];
      else
        $salarioDevengado = 0.00;

      $fechaInicialSD = $_POST['txtFechaInicialSD'];
      $fechaFinalSD = $_POST['txtFechaFinalSD'];

      if($_POST['txtHorasExtras'] != "")
        $horasExtras = $_POST['txtHorasExtras'];
      else
        $horasExtras = 0;

      $Prestamo = $_POST['txtPrestamo'];
      $diasAguinaldo = $_POST['hiddenDiasAguinaldo'];
      $diasVacaciones = $_POST['hiddenDiasVacacionesAPagar'];

      $diasPeriodo = $_POST['dias_periodo'];
      $ISRProporcional = 0.00;
      $Subsidio = 0.00;
      $ISRRetenido = 0.00;

      $Salario3Meses = $_POST['Salario3Meses'];
      $DiasAnioServicio = $_POST['20DiasAnioServicio'];
      $PrimaAntiguedad = $_POST['PrimaAntiguedad'];
      $Exencion = $_POST['Exencion'];
      $ISR113LISR = $_POST['ISR113LISR'];
      $ISRIndemnizacion = $_POST['ISRIndemnizacion'];
      $ISR142RLISR = $_POST['ISR142RLISR'];

      $FKFiniquito = 0;

      try{
        $conn->beginTransaction();

        $stmt = $conn->prepare("SELECT PKFiniquito FROM finiquito WHERE FKEmpleado = :id ");
        $stmt->bindValue(':id',$fkEmpleado);
        $stmt->execute();
        $validacion = $stmt->rowCount();

        if($validacion == 0){
            $stmt = $conn->prepare('INSERT INTO Finiquito (FechaSalida,SalariosDevengados,FechaInicialSalariosDevengados,FechaFinalSalariosDevengados,HorasExtras,Prestamos,FKEmpleado,
            Dias_Periodo, ISRProporcional, Subsidio, ISRRetenido, Tipo)
            VALUES(:fecha_salida,:salarios_devengados,:fecha_inicial_salarios_devengados,:fecha_final_salarios_devengados,:horas_extras,:prestamos,:fk_empleado, :dias_periodo,
            :isrproporcional, :subsidio, :isrretenido, 2)');
            $stmt->bindValue(':fecha_salida',$fechaSalida);
            $stmt->bindValue(':salarios_devengados',$salarioDevengado);
            $stmt->bindValue(':fecha_inicial_salarios_devengados',$fechaInicialSD);
            $stmt->bindValue(':fecha_final_salarios_devengados',$fechaFinalSD);
            $stmt->bindValue(':horas_extras',$horasExtras);
            $stmt->bindValue(':prestamos',$Prestamo);
            $stmt->bindValue(':fk_empleado',$fkEmpleado);
            $stmt->bindValue(':dias_periodo',$diasPeriodo);
            $stmt->bindValue(':isrproporcional',$ISRProporcional);
            $stmt->bindValue(':subsidio',$Subsidio);
            $stmt->bindValue(':isrretenido',$ISRRetenido);
            $stmt->execute();

            $FKFiniquito = $conn->lastInsertId();

          $stmt = $conn->prepare("INSERT INTO liquidacion (Salario3Meses, 20DiasAnioServicio, PrimaAntiguedad, Exencion, ISR113LISR, ISRIndemnizacion, ISR142RLISR, FKFiniquito) VALUES (:Salario3Meses, :20DiasAnioServicio, :PrimaAntiguedad, :Exencion, :ISR113LISR, :ISRIndemnizacion, :ISR142RLISR, :FKFiniquito)");
          $stmt->bindValue(':Salario3Meses',$Salario3Meses);
          $stmt->bindValue(':20DiasAnioServicio',$DiasAnioServicio);
          $stmt->bindValue(':PrimaAntiguedad',$PrimaAntiguedad);
          $stmt->bindValue(':Exencion',$Exencion);
          $stmt->bindValue(':ISR113LISR',$ISR113LISR);
          $stmt->bindValue(':ISRIndemnizacion',$ISRIndemnizacion);
          $stmt->bindValue(':ISR142RLISR',$ISR142RLISR);
          $stmt->bindValue(':FKFiniquito',$FKFiniquito);
          $stmt->execute();

            $stmt = $conn->prepare("UPDATE datos_laborales_empleado SET FKEstatus = '1', Deuda_Restante = '0.00', fecha_baja = :fecha_baja WHERE FKEmpleado = :fk_empleado");
            $stmt->bindValue(':fecha_baja',$fechaSalida);
            $stmt->bindValue(':fk_empleado',$fkEmpleado);
            $stmt->execute();

            $conn->commit();
        }
        else{
          $conn->rollBack();
        }
      }catch(PDOException $ex){
        echo $ex->getMessage();
      }
    }


    $stmt = $conn->prepare("SELECT e.PKEmpleado, e.Primer_Nombre, e.Segundo_Nombre, e.Apellido_Paterno, e.Apellido_Materno, de.Fecha_Ingreso,
                                   p.Sueldo_semanal_bruto, de.Deuda_Restante, de.PKLaboralesEmpleado,t.Horas_de_trabajo, f.*, l.*
                            FROM empleados AS e
                            LEFT JOIN datos_laborales_empleado as de ON de.FKEmpleado = e.PKEmpleado
                            LEFT JOIN puestos as p ON p.PKPuesto = de.FKPuesto
                            LEFT JOIN finiquito as f  ON f.FKEmpleado = e.PKEmpleado
                            LEFT JOIN turnos as t ON de.FKTurno = t.PKTurno
                            LEFT JOIN liquidacion as l ON l.FKFiniquito = f.PKFiniquito
                            WHERE e.PKEmpleado = :id ");
    $stmt->bindValue(':id',$fkEmpleado);
    $stmt->execute();
    $row = $stmt->fetch();
    $fkEmpleado = $row['PKEmpleado'];

    $ValidarDatosEmpleo = $row['PKLaboralesEmpleado'];

    $segundo_nombre = '';
    if(trim($row['Segundo_Nombre']) != ""){
      $segundo_nombre = ' '.$row['Segundo_Nombre'];
    }

    $nombreEmpleado = $row['Primer_Nombre'].$segundo_nombre.' '.$row['Apellido_Paterno'].' '.$row['Apellido_Materno'];
    $fechaIngreso = $row['Fecha_Ingreso'];
    $Sueldo_semanal = number_format($row['Sueldo_semanal_bruto'],2,'.','');
    $Sueldo_diario = $row['Sueldo_semanal_bruto'] / 7;
    $Sueldo_diario_formato = number_format($Sueldo_diario,2,'.','');
    $prestamo = $row['Deuda_Restante'];
    $horasTrabajoArray = explode(':',$row['Horas_de_trabajo']);
    $horasTrabajo = $horasTrabajoArray[0] + ($horasTrabajoArray[1]/60);
    $sueldo_Hora = $row['Sueldo_semanal_bruto'] / $horasTrabajo;

    //echo "sueldo semanal ".$row['Sueldo_semanal']." /// ".$row['Horas_de_trabajo'];
    /*Calculo desde la DB de los datos del finiquito*/
    function esBisiesto($year=NULL) {
      $year = ($year==NULL)? date('Y'):$year;
      return ( ($year%4 == 0 && $year%100 != 0) || $year%400 == 0 );
    }
    $fechaSalidaBD = $row['FechaSalida'];
    $salarioDevengadoBD = $row['SalariosDevengados'];
    $fechaIniciaSD_BD = $row['FechaInicialSalariosDevengados'];
    $fechaFinalSD_BD = $row['FechaFinalSalariosDevengados'];
    $horasExtrasBD = $row['HorasExtras'];
    $horasExtras_calculo = $horasExtrasBD * $sueldo_Hora;
    $prestamosBD = $row['Prestamos'];
    $ISRProporcionalBD = $row['ISRProporcional'];
    $ISRetenidoBD = $row['ISRRetenido'];
    $SubsidioBD = $row['Subsidio'];

    //liquidacion
    $Salario3MesesBD = $row['Salario3Meses'];
    $DiasAnioServicioBD = $row['20DiasAnioServicio'];
    $PrimaAntiguedadBD = $row['PrimaAntiguedad'];
    $ExencionBD = $row['Exencion'];
    $ISR113LISRBD = $row['ISR113LISR'];
    $ISRIndemnizacionBD = $row['ISRIndemnizacion'];
    $ISR142RLISRBD = $row['ISR142RLISR'];

    $dias_periodo = 7;

    $datetime1 = new DateTime($fechaIngreso); // Fecha inicial
    $datetime2 = new DateTime($fechaSalidaBD); // Fecha actual
    $interval = $datetime1->diff($datetime2);
    $num_dias_antiguedad = $interval->format('%a');

    $fechaIngresoComoEntero = strtotime($fechaIngreso);
    $fechaFinalComoEntero = strtotime($fechaSalidaBD);
    $num_anios = 0;
    $num_dias = 0;

    if($row['PKFiniquito'] != ""){

        /*CALCULO DIAS AGUINALDO*/
        if(date("Y",$fechaIngresoComoEntero) == date("Y") ){
          $fechainicial = $fechaIngreso;
        }
        else{
          $fechainicial = date("Y",$fechaFinalComoEntero).'-01-01';
        }

        $fecha1= new DateTime($fechainicial);
        $diff = $fecha1->diff($datetime2);
        $factor_dias = $diff->days + 1;

        if(esBisiesto(date("Y",$fechaFinalComoEntero))){
          $dias_aguinaldo_calculo = number_format((15 / 366) * $factor_dias,2,'.','');
        }
        else{
          $dias_aguinaldo_calculo = number_format((15 / 365) * $factor_dias,2,'.','');
        }

        $calculo_aguinaldo = number_format($dias_aguinaldo_calculo * $Sueldo_diario_formato,2,'.','');

        /*FIN CALCULO DIAS AGUINALDO*/

        if(date("m",$fechaIngresoComoEntero) == date("m",$fechaFinalComoEntero) && date("d",$fechaIngresoComoEntero) == date("d",$fechaFinalComoEntero)){

            for($x = date("Y",$fechaIngresoComoEntero);$x < date("Y",$fechaFinalComoEntero);$x++){
              $num_anios++;
            }

            $num_anios_json = number_format($num_anios,3,'.','');
        }
        else{
            for($x = date("Y",$fechaIngresoComoEntero);$x <= date("Y",$fechaFinalComoEntero);$x++){
              if(esBisiesto($x)){
                $num_dias = $num_dias + 366;
              }
              else{
                $num_dias = $num_dias + 365;
              }
              $num_anios++;
            }
            $factor_anios = $num_dias/$num_anios;
            $num_dias_totales = number_format($num_dias_antiguedad / $factor_anios,3,'.','');
            $num_anios = $num_dias_totales;
        }

        /*CALCULO DIAS DE VACACIONES*/
        $stmt = $conn->prepare("SELECT IFNULL(de.Dias_de_Vacaciones,0) as Dias_de_Vacaciones,IFNULL(SUM(v.Dias_de_Vacaciones_Tomados),0)
                                as Dias_de_Vacaciones_Tomados
                                FROM datos_laborales_empleado as de
                                LEFT JOIN vacaciones as v ON v.FKEmpleado = de.FKEmpleado AND YEAR(FechaIni) = :fecha_anio
                                WHERE de.FKEmpleado = :id
                                GROUP BY v.FKEmpleado");
        $stmt->bindValue(':id',$fkEmpleado);
        $stmt->bindValue(':fecha_anio',date("Y",$fechaFinalComoEntero));
        $stmt->execute();
        $row_dv = $stmt->fetch();

        $dias_vacaciones = $row_dv['Dias_de_Vacaciones'];
        $dias_vacaciones_tomados = $row_dv['Dias_de_Vacaciones_Tomados'];
        $dias_vacaciones_restantes = $dias_vacaciones - $dias_vacaciones_tomados;

        switch ($num_dias_totales)
        {
          case ($num_dias_totales > 0 && $num_dias_totales < 2):
            $dias_vacaciones = 6;
            break;
          case ($num_dias_totales > 2 && $num_dias_totales < 3):
            $dias_vacaciones = 8;
            break;
          case ($num_dias_totales > 3 && $num_dias_totales < 4):
            $dias_vacaciones = 10;
            break;
          case ($num_dias_totales > 4 && $num_dias_totales < 5):
            $dias_vacaciones = 12;
            break;
          case ($num_dias_totales > 5 && $num_dias_totales < 10):
            $dias_vacaciones = 14;
            break;
          case ($num_dias_totales > 10 && $num_dias_totales < 15):
            $dias_vacaciones = 16;
            break;
          case ($num_dias_totales > 15 && $num_dias_totales < 20):
            $dias_vacaciones = 18;
            break;
          case ($num_dias_totales > 20 && $num_dias_totales < 25):
            $dias_vacaciones = 20;
            break;
          case ($num_dias_totales > 25 && $num_dias_totales < 30):
            $dias_vacaciones = 22;
            break;
          case ($num_dias_totales > 30 && $num_dias_totales < 35):
            $dias_vacaciones = 24;
            break;
          case ($num_dias_totales > 35 && $num_dias_totales < 40):
            $dias_vacaciones = 26;
            break;
          case ($num_dias_totales > 40 && $num_dias_totales < 45):
            $dias_vacaciones = 28;
            break;
          case ($num_dias_totales > 45 && $num_dias_totales < 50):
            $dias_vacaciones = 30;
            break;
        }

        if(esBisiesto(date("Y",$fechaFinalComoEntero))){
          $dias_vacaciones_calculo = ($dias_vacaciones / 366) * $factor_dias;
        }
        else{
          $dias_vacaciones_calculo = ($dias_vacaciones / 365) * $factor_dias;
        }
        $dias_vacaciones_json = $dias_vacaciones;
        $dias_vacaciones_tomados_json = $dias_vacaciones_tomados;

        if($dias_vacaciones_calculo > $dias_vacaciones_tomados){
          $dias_vacaciones_a_pagar = $dias_vacaciones_calculo - $dias_vacaciones_tomados;
        }
        else{
          $dias_vacaciones_a_pagar = 0.00;
        }
        $dias_vacaciones_calculo_json = number_format($dias_vacaciones_calculo,2,'.','');
        $dias_vacaciones_a_pagar_json = number_format($dias_vacaciones_a_pagar,2,'.','');
        $calculo_vacaciones_json = number_format($dias_vacaciones_a_pagar * $Sueldo_diario_formato,2,'.','');
        $prima_vacacional_json = number_format(($dias_vacaciones_a_pagar * $Sueldo_diario_formato) *.25,2,'.','');

        $subtotal_finiquito_json = number_format($calculo_aguinaldo + $calculo_vacaciones_json + $prima_vacacional_json + $salarioDevengadoBD + $horasExtras_calculo ,2,'.','');
        if($subtotal_finiquito_json > $prestamosBD){
          $cantidad_neta_recibida = number_format($subtotal_finiquito_json - $prestamosBD ,2,'.','');
        }
        else{
          $cantidad_neta_recibida = 0.00;
        }
        /*FIN CALCULO DIAS DE VACACIONES*/

        /* ISR del finiquito*/
        $stmt = $conn->prepare("SELECT cantidad FROM parametros WHERE descripcion = 'Prima_Vacacional' OR descripcion = 'UMA' OR descripcion = 'Factor_mes' ORDER BY PKParametros Asc");
        $stmt->execute();
        $row_parametros = $stmt->fetchAll();
        $UMA = $row_parametros[0]['cantidad'];
        $dias_mes = $row_parametros[2]['cantidad'];
        $prima_vacacional_tasa = $row_parametros[1]['cantidad']/100;
        $dias_periodo = $row['Dias_Periodo'];//verificar cantidad

        $limite_gravar_prima_vacacional = $UMA * 15;
        $limite_gravar_aguinaldo = $UMA * 30;
        $SubsidioProporcional = number_format($SubsidioBD/$dias_mes * $dias_periodo,2,'.','');

        //base gravable aguinaldo
        if($calculo_aguinaldo > $limite_gravar_aguinaldo){
          $aguinaldo_gravar =  number_format($calculo_aguinaldo - $limite_gravar_aguinaldo,2,'.','');
          $aguinaldo_excento = $limite_gravar_aguinaldo - $aguinaldo_gravar;
        }
        else{
          $aguinaldo_gravar = 0.00;
          $aguinaldo_excento = $calculo_aguinaldo;
        }

        $aguinaldo_excento =  number_format($aguinaldo_excento,2,'.','');
        $aguinaldo_gravar = number_format($aguinaldo_gravar,2,'.','');

        //base gravable prima vacacional
        if($prima_vacacional_json > $limite_gravar_prima_vacacional){
          $prima_vacacional_gravar =  number_format($prima_vacacional_json - $limite_gravar_prima_vacacional,2);
          $prima_vacacional_excento = $limite_gravar_prima_vacacional - $prima_vacacional_gravar;
        }
        else{
          $prima_vacacional_gravar = 0.00;
          $prima_vacacional_excento = $prima_vacacional_json;
        }
        $prima_vacacional_excento =  number_format($prima_vacacional_excento,2,'.','');
        $prima_vacacional_gravar = number_format($prima_vacacional_gravar,2,'.','');

        //base gravable horas extras
        $salarioTiempoExtra = $horasExtras_calculo;
        $salarioTiempoExtraLimite = ($horasExtras_calculo) / 2;
        $limite_excento_salarioExtra = $UMA * 5;

        if($salarioTiempoExtraLimite > $limite_excento_salarioExtra){
          $salarioTiempoExtraBase =  $salarioTiempoExtra - $limite_excento_salarioExtra;
          $tiempoextra_excento = $salarioTiempoExtra - $salarioTiempoExtraBase;
        }
        else{
          $salarioTiempoExtraBase = $salarioTiempoExtraLimite;
          $tiempoextra_excento = $salarioTiempoExtraLimite;
        }
        $horasextra_importe =  number_format($horasExtras_calculo,2,'.','');
        $horasextra_excento = number_format($tiempoextra_excento,2,'.','');
        $horasextra_gravado = number_format($salarioTiempoExtraBase,2,'.','');

        $base_finiquito_pre = $calculo_vacaciones_json + $prima_vacacional_gravar + $aguinaldo_gravar + $salarioDevengadoBD  + $salarioTiempoExtraBase - $prestamosBD;
        $subtotal_finiquito_exento = number_format($aguinaldo_excento + $prima_vacacional_excento + $tiempoextra_excento,2,'.','');
        $subtotal_finiquito_gravado = number_format($base_finiquito_pre,2,'.','');
        /*ISR Finiquito*/
        $tasa_isr_pre = $ISRetenidoBD / $subtotal_finiquito_gravado;
        $tasa_isr = number_format($tasa_isr_pre * 100,2, '.', '');
        $tasa_isr = $tasa_isr." %";
        $total_pagar = number_format($cantidad_neta_recibida - $ISRetenidoBD,2,'.','');


        /////LIQUIDACION calculos desde BD
      $TotalIndemnizacionBD = number_format($Salario3MesesBD + $DiasAnioServicioBD + $PrimaAntiguedadBD,2, '.', '');
      $base_gravable_liquidacionBD = number_format( $TotalIndemnizacionBD - $ExencionBD,2, '.', '');
      $TotalDeduccionesBD = number_format($ISR113LISRBD + $ISRIndemnizacionBD + $ISR142RLISRBD,2, '.', '');

      $TotalIngresosIndemnizacionBD = number_format($TotalIndemnizacionBD + $cantidad_neta_recibida,2, '.', '');

      $TotalLiquidacionBD = number_format($TotalIngresosIndemnizacionBD - $TotalDeduccionesBD,2, '.', '');
    }

    /*FIN Calculo desde la DB de los datos del finiquito*/



  }else {
    header("location:../../dashboard.php");
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

  <title>Timlid | Liquidación</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../js/validaciones.js"></script>
  <!-- Core plugin JavaScript-->
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>



  <!-- Custom fonts for this template-->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../../css/sb-admin-2.css" rel="stylesheet">
  <link href="../../css/dashboard.css" rel="stylesheet">

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
      $ruta = "../";
      $ruteEdit = $ruta."central_notificaciones/";
      require_once('../menu3.php');
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
            <?php
              $rutes = "../";
              //require_once('../alerta_Tareas_Nuevas.php');
            ?>
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
            <?php
              if($ValidarDatosEmpleo != "")
                  echo '<h1 class="h3 mb-0 text-gray-800">Liquidación</h1>';
                else
                  echo '<div class="alert alert-danger" role="alert">Aun no tienes datos de empleo de este trabajor, no se puede calcular la liquidación.</div>';
            ?>
          </div>


          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4">
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
                                <label for="nombreEmpleado"><b>Nombre del empleado:</b></label>
                                <?=$nombreEmpleado?>
                              </div>
                              <div class="col-lg-6">
                                <label for="sueldoSemanal"><b>Sueldo semanal:</b></label>
                                <?="$ ".$Sueldo_semanal?>
                              </div>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-4">
                                <label for="txtFechaIngreso">Fecha ingreso:</label>
                                <input type="date" class="form-control" id="txtFechaIngreso" name="txtFechaIngreso" required value="<?=$fechaIngreso?>" readonly>
                              </div>
                              <div class="col-lg-4">
                                <label for="txtFechaSalida">Fecha salida:</label>
                                <input type="date" class="form-control" id="txtFechaSalida" name="txtFechaSalida" required <?php if($row['PKFiniquito'] != "") { echo "value='$fechaSalidaBD'"; echo "readonly";} ?> >
                              </div>
                              <div class="col-lg-4">
                                <label for="dias_periodo">Días que abarca el período:</label>
                                <input type="number" class="form-control" id="dias_periodo" name="dias_periodo" min="1" max="31" value="<?php echo $dias_periodo;?>" required <?php if($row['PKFiniquito'] != "") { echo "readonly";} ?>>
                              </div>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-6">
                                <label for="sueldo_diario">Salario diario:</label>
                                <input type="number" maxlength="11" class="form-control numeric-only upperCaseletter" name="sueldo_diario" id="sueldo_diario" value="<?=number_format($Sueldo_diario,2,'.','')?>" readonly >
                              </div>
                              <div class="col-lg-6">
                                <label for="sueldo_diario_integrado">Salario diario integrado:</label>
                                <input type="number" maxlength="11" class="form-control numeric-only upperCaseletter" name="sueldo_diario_integrado" id="sueldo_diario_integrado" value="" readonly>
                              </div>
                              <!--
                              <div class="col-lg-4">
                                <label for="salario_minimo">Salario mínimo/UMA:</label>
                                <input type="text" name="txtSalarioMinimo" class="form-control" step="1"  value="88.36" required readonly>
                              </div>
                            -->
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-6">
                                <label for="txtDiasAguinaldo">Días de aguinaldo:</label>
                                <input type="text" maxlength="4" class="form-control numeric-only"  name="txtDiasAguinaldo" id="txtDiasAguinaldo" value="15" readonly>
                              </div>
                              <div class="col-lg-3">
                                <label for="lblDA" id="lblDA">Proporcionales:</label>
                                <div id="lblDiasAguinaldo"><?php if($row['PKFiniquito'] != "") echo $dias_aguinaldo_calculo;?></div>
                                <input type="hidden" name="hiddenDiasAguinaldo" id="hiddenDiasAguinaldo" value="">
                              </div>
                              <div class="col-lg-3">
                                <label for="txtAntiguedad">Antigüedad:</label>
                                <input type="text" class="form-control" id="txtAntiguedad" name="txtAntiguedad" value="<?php if($row['PKFiniquito'] != "") echo $num_dias_totales;?>" required readonly >
                              </div>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-3">
                                <label for="txtDiasVacaciones">Días de vacaciones:</label>
                                <input type="text" maxlength="4" class="form-control numeric-only"  name="txtDiasVacaciones" id="txtDiasVacaciones" value="<?php if($row['PKFiniquito'] != "") echo $dias_vacaciones_json; else echo "6"; ?>" readonly>
                              </div>
                              <div class="col-lg-3">
                                <label for="lblDV" id="lblDV">Proporcionales:</label>
                                <div id="lblDiasVacaciones"><?php if($row['PKFiniquito'] != "") echo $dias_vacaciones_calculo_json;?></div>
                                <input type="hidden" name="hiddenDiasVacaciones" id="hiddenDiasVacaciones" value="">
                              </div>
                              <div class="col-lg-3">
                                <label for="lblDVT" id="lblDVT">Tomados:</label>
                                <div id="lblDiasVacacionesTomados"><?php if($row['PKFiniquito'] != "") echo $dias_vacaciones_tomados_json;?></div>
                                <input type="hidden" name="hiddenDiasVacacionesTomados" id="hiddenDiasVacacionesTomados" value="">
                              </div>
                              <div class="col-lg-3">
                                <label for="lblDVP" id="lblDVP">A pagar:</label>
                                <div id="lblDiasVacacionesAPagar"><?php if($row['PKFiniquito'] != "") echo $dias_vacaciones_a_pagar_json;?></div>
                                <input type="hidden" name="hiddenDiasVacacionesAPagar" id="hiddenDiasVacacionesAPagar" value="">
                              </div>
                            </div>
                          </div>
                           <div class="form-group">
                            <div class="row">
                              <div class="col-lg-4">
                                <label for="txtSalariosDevengados">Salarios devengados:</label>
                                <input type="number" class="form-control" id="txtSalariosDevengados" name="txtSalariosDevengados" value="<?php if($row['PKFiniquito'] != "") echo $salarioDevengadoBD;?>"  disabled>
                              </div>
                              <div class="col-lg-4">
                                <label for="txtFechaInicialSD">Periodo - Fecha inicial:</label>
                                <input type="date" class="form-control" id="txtFechaInicialSD" name="txtFechaInicialSD" value="<?php if($row['PKFiniquito'] != "") echo $fechaIniciaSD_BD;?>" disabled>
                              </div>
                              <div class="col-lg-4">
                                <label for="txtFechaFinalSD">Periodo - Fecha final:</label>
                                <input type="date" class="form-control" id="txtFechaFinalSD" name="txtFechaFinalSD" value="<?php if($row['PKFiniquito'] != "") echo $fechaFinalSD_BD;?>" disabled>
                              </div>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-4">
                                <label for="txtHorasExtras">Horas:</label>
                                <input type="number" class="form-control" id="txtHorasExtras" name="txtHorasExtras" min="0" value="<?php if($row['PKFiniquito'] != "") echo $horasExtrasBD;?>" disabled>
                              </div>
                              <div class="col-lg-4">
                                <label for="txtPrestamo">Prestamos:</label>
                                <input type="number" class="form-control" id="txtPrestamo" name="txtPrestamo" value="<?php if($row['PKFiniquito'] != "") echo $prestamosBD; else echo number_format($prestamo,2,'.','');?>" readonly>
                              </div>
                              <div class="col-lg-4">&nbsp</div>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-12">
                                <table class="table">
                                  <thead>
                                    <th>Concepto</th>
                                    <th>Importe</th>
                                    <th>Exentos</th>
                                    <th>Gravado</th>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td>Aguinaldo</td>
                                      <td id="lblAguinaldoPercepcion"><?php if($row['PKFiniquito'] != "") echo $calculo_aguinaldo; else echo "0.00";?></td>
                                      <td id="lblAguinaldoExento"><?php if($row['PKFiniquito'] != "") echo $aguinaldo_excento; else echo "0.00";?></td>
                                      <td id="lblAguinaldoGravado"><?php if($row['PKFiniquito'] != "") echo $aguinaldo_gravar; else echo "0.00";?></td>
                                    </tr>
                                    <tr>
                                      <td>Vacaciones</td>
                                      <td id="lblVacacionesPercepcion"><?php if($row['PKFiniquito'] != "") echo $calculo_vacaciones_json;else echo "0.00";?></td>
                                      <td id="lblVacacionesExento">0.00</td>
                                      <td id="lblVacacionesGravado"><?php if($row['PKFiniquito'] != "") echo $calculo_vacaciones_json;else echo "0.00";?></td>
                                    </tr>
                                    <tr>
                                      <td>Prima vacacional</td>
                                      <td id="lblPrimaVacacionalPercepcion"><?php if($row['PKFiniquito'] != "") echo $prima_vacacional_json;else echo "0.00";?></td>
                                      <td id="lblPrimaVacacionalExento"><?php if($row['PKFiniquito'] != "") echo $prima_vacacional_excento;else echo "0.00";?></td>
                                      <td id="lblPrimaVacacionalGravado"><?php if($row['PKFiniquito'] != "") echo $prima_vacacional_gravar;else echo "0.00";?></td>
                                    </tr>
                                    <tr>
                                      <td>Salario devengado</td>
                                      <td id="lblSalarioDevengadoPercepcion"><?php if($row['PKFiniquito'] != "") echo number_format($salarioDevengadoBD,2,'.','');else echo "0.00";?></td>
                                      <td id="lblSalarioDevengadoExento">0.00</td>
                                      <td id="lblSalarioDevengadoGravado"><?php if($row['PKFiniquito'] != "") echo number_format($salarioDevengadoBD,2,'.','');else echo "0.00";?></td>
                                    </tr>
                                    <tr>
                                      <td>Horas extras</td>
                                      <td id="lblHorasExtrasPercepcion"><?php if($row['PKFiniquito'] != "") echo number_format($horasExtras_calculo,2,'.','');else echo "0.00";?></td>
                                      <td id="lblHorasExtrasExento"><?php if($row['PKFiniquito'] != "") echo number_format($horasExtras_calculo,2,'.','');else echo "0.00";?></td>
                                      <td id="lblHorasExtrasGravado"><?php if($row['PKFiniquito'] != "") echo number_format($horasExtras_calculo,2,'.','');else echo "0.00";?></td>
                                    </tr>
                                    <tr>
                                      <td>Prestamos(-)</td>
                                      <td id="lblPrestamosPercepcion"><?php if($row['PKFiniquito'] != "") echo number_format($prestamosBD,2,'.',''); else echo number_format($prestamo,2,'.','');?></td>
                                      <td id="lblPrestamosExtrasExento">0.00</td>
                                      <td id="lblPrestamosGravado"><?php if($row['PKFiniquito'] != "") echo number_format($prestamosBD,2,'.',''); else echo number_format($prestamo,2,'.','');?></td>
                                    </tr>
                                     <tr>
                                      <td>Subtotal finiquito</td>
                                      <td id="lblSubtotalFiniquitoPercepcion"><?php if($row['PKFiniquito'] != "") echo number_format($cantidad_neta_recibida,2,'.',''); else echo "0.00";?></td>
                                      <td id="lblSubtotalFiniquitoExento"><?php if($row['PKFiniquito'] != "") echo number_format($subtotal_finiquito_exento,2,'.',''); else echo "0.00";?></td>
                                      <td id="lblSubtotalFiniquitoGravado"><?php if($row['PKFiniquito'] != "") echo number_format($subtotal_finiquito_gravado,2,'.',''); else echo "0.00";?></td>
                                    </tr>
                                    <tr>
                                      <td>Total finiquito</td>
                                      <td>&nbsp</td>
                                      <td>&nbsp</td>
                                      <td id="lblSubtotalFiniquito" style="font-weight:800"><?php if($row['PKFiniquito'] != "") echo number_format($cantidad_neta_recibida,2,'.',''); else echo "0.00";?></td>
                                    </tr>
                                    <tr>
                                      <td colspan="3"><center><b>Liquidación</b></center></td>
                                    </tr>
                                    <tr>
                                      <td>3 meses de salario</td>
                                      <td id="lblMesesSalario"><?php if($row['PKFiniquito'] != "") echo number_format($Salario3MesesBD,2,'.',''); else echo "0.00";?></td>
                                      <td>&nbsp</td>
                                      <td>&nbsp</td>
                                    </tr>
                                    <tr>
                                      <td>20 días por cada año de servicio</td>
                                      <td id="lblSalarioAnioServicio"><?php if($row['PKFiniquito'] != "") echo number_format($DiasAnioServicioBD,2,'.',''); else echo "0.00";?></td>
                                      <td>&nbsp</td>
                                      <td>&nbsp</td>
                                    </tr>
                                    <tr>
                                      <td>Prima antiguedad</td>
                                      <td id="lblPrimaAntiguedad"><?php if($row['PKFiniquito'] != "") echo number_format($PrimaAntiguedadBD,2,'.',''); else echo "0.00";?></td>
                                      <td>&nbsp</td>
                                      <td>&nbsp</td>
                                    </tr>
                                    <tr>
                                      <td>Total de ingresos por indemnización</td>
                                      <td>&nbsp</td>
                                      <td id="lblTotalIngresosIndemnizacion"><?php if($row['PKFiniquito'] != "") echo $TotalIndemnizacionBD; else echo "0.00";?></td>
                                      <td id="lblTotalIngresos"><?php if($row['PKFiniquito'] != "") echo $TotalIngresosIndemnizacionBD; else echo "0.00";?></td>
                                    </tr>
                                    <tr>
                                      <td>Exención</td>
                                      <td>&nbsp</td>
                                      <td id="lblExencion"><?php if($row['PKFiniquito'] != "") echo $ExencionBD; else echo "0.00";?></td>
                                      <td>&nbsp</td>
                                    </tr>
                                    <tr>
                                      <td>Base gravable indemnización</td>
                                      <td>&nbsp</td>
                                      <td id="lblBaseGravableIndemnizacion"><?php if($row['PKFiniquito'] != "") echo $base_gravable_liquidacionBD; else echo "0.00";?></td>
                                      <td>&nbsp</td>
                                    </tr>
                                    <tr>
                                      <td>Determinación del ISR Art. 113 LISR</td>
                                      <td id="lblISRRetenerUSMO"><?php if($row['PKFiniquito'] != "") echo $ISR113LISRBD; else echo "0.00";?></td>
                                      <td>&nbsp</td>
                                      <td>&nbsp</td>
                                    </tr>
                                    <tr>
                                      <td>ISR de la indemnización</td>
                                      <td id="lblISRIndemnizacion"><?php if($row['PKFiniquito'] != "") echo $ISRIndemnizacionBD; else echo "0.00";?></td>
                                      <td>&nbsp</td>
                                      <td>&nbsp</td>
                                    </tr>
                                    <tr>
                                      <td>ISR ART. 142 RLISR</td>
                                      <td id="lblISR_ART142"><?php if($row['PKFiniquito'] != "") echo number_format($ISR142RLISRBD,2,'.',''); else echo "0.00";?></td>
                                      <td>&nbsp</td>
                                      <td>&nbsp</td>
                                    </tr>
                                    <tr>
                                      <td>Total deducciones</td>
                                      <td>&nbsp</td>
                                      <td id="lblTotalDeducciones"><?php if($row['PKFiniquito'] != "") echo $TotalDeduccionesBD; else echo "0.00";?></td>
                                      <td>&nbsp</td>
                                    </tr>
                                    <tr>
                                      <td>Total a pagar</td>
                                      <td>&nbsp</td>
                                      <td>&nbsp</td>
                                      <td id="lblTotalPagarFinal" style="font-weight:800"><?php if($row['PKFiniquito'] != "") echo number_format($TotalLiquidacionBD,2,'.',''); else echo "0.00";?></td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>

                          <input type="hidden" name="Salario3Meses" id="Salario3Meses" value="" />
                          <input type="hidden" name="20DiasAnioServicio" id="20DiasAnioServicio" value="" />
                          <input type="hidden" name="PrimaAntiguedad" id="PrimaAntiguedad" value="" />
                          <input type="hidden" name="Exencion" id="Exencion" value="" />
                          <input type="hidden" name="ISR113LISR" id="ISR113LISR" value="" />
                          <input type="hidden" name="ISRIndemnizacion" id="ISRIndemnizacion" value="" />
                          <input type="hidden" name="ISR142RLISR" id="ISR142RLISR" value="" />

                          <?php
                           if($row['PKFiniquito'] != NULL || $row['PKFiniquito'] != ""){
                              echo "<a class='btn btn-danger' href='functions/eliminar_liquidacion.php?id=".$row['PKFiniquito']."'><i class='fas fa-trash-alt'></i> Eliminar</a>";
                          ?>
                              <a href="functions/reciboliquidacionpdf.php?id=<?=$row['PKFiniquito']?>" class="btn btn-primary float-right" target="_blank">Imprimir comprobante</a>
                          <?php
                          }
                           else{
                              if($ValidarDatosEmpleo != "")
                                echo "<button type='submit' class='btn btn-success float-right' name='btnGuardar'>Guardar finiquito</button>";
                          }
                          ?>
                        </form>
                      </div>
                    </div>

                </div>
              </div>

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
            <a class="btn btn-primary" href="../logout.php">Salir</a>
          </div>
        </div>
      </div>
    </div>

<script type="text/javascript">
  var subtotal_finiquito_global = 0.00;
  var aguinaldo_global = 0.00;
  var vacaciones_global = 0.00;
  var prima_vacacional_global = 0.00;
  var salarios_devengados_global = 0.00;
  var horas_extras_global = 0.00;
  var prestamos = <?=number_format($prestamo,2,'.','')?>;

  function actualizarFiniquito(){

    var fechaIngreso = $("#txtFechaIngreso").val();
    var fechaSalida = $("#txtFechaSalida").val();
    var dias_aguinaldo = $("#txtDiasAguinaldo").val();
    var salario_diario = $("#sueldo_diario").val();
    var FKEmpleado = <?php echo $fkEmpleado;?>;
    var SalariosDevengados;
    var dias_periodo = $("#dias_periodo").val();

    if($('#txtSalariosDevengados').val().trim() == ''){
      SalariosDevengados = 0.00;
    }
    else{
      SalariosDevengados = $('#txtSalariosDevengados').val();
    }

    var sueldoHora = <?=$sueldo_Hora?>;

    if($("#txtHorasExtras").val().trim() == ""){
        var HorasExtras = 0.00;
    }
    else{
        var HorasExtras = parseFloat($("#txtHorasExtras").val()) * sueldoHora ;
    }

    $.post( "functions/function_liquidacion.php", { FKEmpleado: FKEmpleado, fecha_ingreso : fechaIngreso, fecha_salida : fechaSalida, dias_aguinaldo : dias_aguinaldo, salario_diario: salario_diario, SalariosDevengados : SalariosDevengados, HorasExtras : HorasExtras, prestamos : prestamos, dias_periodo : dias_periodo} ,function( data ) {
        var datos = JSON.parse(data);
        $( "#txtAntiguedad" ).val( datos.num_anios );
        $( "#hiddenDiasAguinaldo" ).val( datos.dias_aguinaldo );
        $( "#lblDiasAguinaldo" ).html( datos.dias_aguinaldo );

        $( "#txtDiasVacaciones" ).val( datos.dias_vacaciones );
        $( "#hiddenDiasVacaciones" ).val( datos.dias_vacaciones_calculo );
        $( "#lblDiasVacaciones" ).html( datos.dias_vacaciones_calculo );
        $( "#hiddenDiasVacacionesTomados" ).val( datos.dias_vacaciones_tomados );
        $( "#hiddenDiasVacacionesAPagar" ).val( datos.dias_vacaciones_a_pagar );
        $( "#lblDiasVacacionesTomados" ).html( datos.dias_vacaciones_tomados );
        $( "#lblDiasVacacionesAPagar" ).html( datos.dias_vacaciones_a_pagar );

        $( "#lblAguinaldoPercepcion" ).html( datos.calculo_aguinaldo );
        $( "#lblAguinaldoExento" ).html( datos.aguinaldo_excento );
        $( "#lblAguinaldoGravado" ).html( datos.aguinaldo_gravar );

        $( "#lblVacacionesPercepcion" ).html( datos.calculo_vacaciones );
        $( "#lblVacacionesGravado" ).html( datos.calculo_vacaciones );

        $( "#lblPrimaVacacionalPercepcion" ).html( datos.prima_vacacional );
        $( "#lblPrimaVacacionalExento" ).html( datos.prima_vacacional_excento );
        $( "#lblPrimaVacacionalGravado" ).html( datos.prima_vacacional_gravar );

        $( "#lblSalarioDevengadoPercepcion" ).html( datos.salarios_devengados );
        $( "#lblSalarioDevengadoGravado" ).html( datos.salarios_devengados );

        $( "#lblHorasExtrasPercepcion" ).html( datos.horasextra_importe);
        $( "#lblHorasExtrasExento" ).html( datos.horasextra_excento);
        $( "#lblHorasExtrasGravado" ).html( datos.horasextra_gravado);

        $( "#lblSubtotalFiniquitoPercepcion" ).html(datos.subtotal_finiquito);
        $( "#lblSubtotalFiniquitoExento" ).html(datos.subtotal_finiquito_exento);
        $( "#lblSubtotalFiniquitoGravado" ).html(datos.subtotal_finiquito_gravado);
        $( "#lblSubtotalFiniquito" ).html(datos.subtotal_finiquito);

        $( "#lblISRProporcional" ).html(datos.ISR_Proporcional);

        $( "#lblSubsidioProporcional" ).html(datos.subsidio_proporcional);
        $( "#lblSubsidioMensual" ).html(datos.subsidio_mensual);

        $( "#lblTasaISR" ).html(datos.tasa_isr);
        $( "#lblISRRetenido" ).html(datos.ISR_Retener);

        $( "#lblTotalPagar" ).html(datos.TotalPagar);

        //liquidacion
        $( "#sueldo_diario_integrado" ).val( datos.sdi);

        $( "#lblMesesSalario" ).html( datos.salario_meses);
        $( "#Salario3Meses" ).val( datos.salario_meses);

        $( "#lblSalarioAnioServicio" ).html( datos.salario_anio_servicio);
        $( "#20DiasAnioServicio" ).val( datos.salario_anio_servicio);

        $( "#lblPrimaAntiguedad" ).html( datos.prima_antiguedad);
        $( "#PrimaAntiguedad" ).val( datos.prima_antiguedad);

        $( "#lblTotalIngresosIndemnizacion" ).html( datos.liquidacion);
        $( "#lblTotalIngresos" ).html( datos.totalIngresos);

        $( "#lblExencion" ).html(datos.exencion_liquidacion);
        $( "#Exencion" ).val( datos.exencion_liquidacion);

        $( "#lblBaseGravableIndemnizacion" ).html(datos.base_gravable_liquidacion);

        $( "#lblISRRetenerUSMO" ).html(datos.ISR_ART_1_1_3_LISR);
        $( "#ISR113LISR" ).val(datos.ISR_ART_1_1_3_LISR);

        $( "#lblISRIndemnizacion" ).html(datos.ISR_INDEMNIZACION_RESUMEN);
        $( "#ISRIndemnizacion" ).val(datos.ISR_INDEMNIZACION_RESUMEN);

        $( "#lblISR_ART142" ).html(datos.ISR_ART_142_RLISR);
        $( "#ISR142RLISR" ).val(datos.ISR_ART_142_RLISR);

        $( "#lblTotalDeducciones" ).html(datos.total_deducciones);
        $( "#lblTotalPagarFinal" ).html(datos.total_pagar);
        //liquidacion

        $('#txtSalariosDevengados').prop("disabled", false);
        $('#txtFechaInicialSD').prop("disabled", false);
        $('#txtFechaFinalSD').prop("disabled", false);
        $('#txtHorasExtras').prop("disabled", false);
    });

  }

  $("#txtFechaSalida").change(function() {

    actualizarFiniquito();
  });

  $("#txtSalariosDevengados").change(function(){
    actualizarFiniquito();
  });

  $("#txtHorasExtras").change(function(){
    actualizarFiniquito();
  });

  $("#dias_periodo").change(function(){
    actualizarFiniquito();
  });

  $(document).ready(function(){
    $("#alertaTareas").load('alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
      setInterval(refrescar, 5000);
    });
  function refrescar(){
    $("#alertaTareas").load('alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
  }


</script>
<script> var ruta = "../";</script>
<script src="../../js/sb-admin-2.min.js"></script>

</body>

</html>

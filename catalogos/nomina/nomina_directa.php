<?php
session_start();
//error_reporting(0);//comentar en pruebas
  if(isset($_SESSION["Usuario"])){

    require_once('../../include/db-conn.php');

    $fkEmpleado = $_GET['id'];
    $semana = $_GET['semana'];
    $idTurno = $_GET['turno'];

    if(isset ($_POST['btnAgregar'])){
      $fkEmpleado = $_POST['txtId'];
      $fkSemana = $_POST['txtSem'];
      $fkBono = floatval($_POST['txtBonoAgregado']);
      $deudaParcialidades = $_POST['txtParcialidades'];
      $salario = floatval($_POST['txtSalario']);
      $horasextras = floatval($_POST['txtHorasExtras']);
      $dobleturno = floatval($_POST['txtDobleTurno']);

      $bono = $_POST['txtBono'];
      $descuento = $_POST['txtDescuento'];
      $infonavit = $_POST['txtInfonavit'];
      $deuda = $_POST['txtDeudaInterna'];

      $ISR = $_POST['txtISR'];
      $cuotaIMSS = $_POST['txtIMSS'];

      try{
        $stmt = $conn->prepare('SELECT PKNomina FROM nomina_empleado WHERE FKEmpleado = :fkEmpleado AND FKSemana = :fkSemana');
        $stmt->bindValue(':fkEmpleado',$fkEmpleado);
        $stmt->bindValue(':fkSemana',$fkSemana);
        $stmt->execute();


        if($stmt->rowCount() == 0){
            $stmt = $conn->prepare('INSERT INTO nomina_empleado (FKEmpleado,FKSemana,BonoSemanal,BonoProductividad,DescuentoImproductividad,DescuentoInfonavit,DescuentoDeuda,DobleTurno, HorasExtras, ISR, cuotaIMSS, Salario)VALUES(:fkEmpleado,:fkSemana,:bonoSemanal,:bonoProductividad,:descuento,:descuentoInfonavit,:descuentoDeuda,:dobleturno, :horasextras, :isr, :imss, :salario)');
            $stmt->bindValue(':fkEmpleado',$fkEmpleado);
            $stmt->bindValue(':fkSemana',$fkSemana);
            $stmt->bindValue(':bonoSemanal',$fkBono);
            $stmt->bindValue(':bonoProductividad',$bono);
            $stmt->bindValue(':descuento',$descuento);
            $stmt->bindValue(':descuentoInfonavit',$infonavit);
            $stmt->bindValue(':descuentoDeuda',$deuda);
            $stmt->bindValue(':dobleturno',$dobleturno);
            $stmt->bindValue(':horasextras',$horasextras);
            $stmt->bindValue(':isr',$ISR);
            $stmt->bindValue(':imss',$cuotaIMSS);
            $stmt->bindValue(':salario',$salario);
            $stmt->execute();
            if($salario < 0){
              $stmt = $conn->prepare('SELECT Deuda_Restante FROM datos_laborales_empleado WHERE FKEmpleado= :id');
              $stmt->execute(array(':id'=>$fkEmpleado));
              $row = $stmt->fetch();
              $deuda = $row['Deuda_Restante'];
              $deuda = $deuda - abs($deudaParcialidades);

              if($deuda > 0){
                $stmt = $conn->prepare('UPDATE datos_laborales_empleado set Deuda_Restante= :deuda WHERE FKEmpleado = :id');
                $stmt->bindValue(':deuda',abs($deuda));
                $stmt->bindValue(':id',$fkEmpleado);
                $stmt->execute();
              }else if ($deuda == 0){
                $stmt = $conn->prepare('UPDATE datos_laborales_empleado set Deuda_Interna= :deudaInterna,Deuda_Restante= :deuda WHERE FKEmpleado = :id');
                $stmt->bindValue(':deudaInterna',abs($deuda));
                $stmt->bindValue(':deuda',abs($deuda));
                $stmt->bindValue(':id',$fkEmpleado);
                $stmt->execute();
              }
            }
        }


      }catch(PDOException $ex){
        echo $ex->getMessage();
      }
    }

    if(isset ($_POST['btnEliminar'])){
      $fkEmpleado = $_POST['txtId'];
      $fkSemana = $_POST['txtSem'];

      try{
        $stmt = $conn->prepare('SELECT de.Deuda_Interna,de.Deuda_Restante,n.Salario,n.DescuentoDeuda FROM datos_laborales_empleado as de INNER JOIN nomina_empleado as n ON n.FKEmpleado = de.FKEmpleado WHERE de.FKEmpleado = :fkEmpleado AND n.FKSemana = :fkSemana ');
        $stmt->bindValue(':fkEmpleado',$fkEmpleado);
        $stmt->bindValue(':fkSemana',$fkSemana);
        $stmt->execute();
        $row = $stmt->fetch();

        $deudaInterna = $row['Deuda_Interna'];
        $deudaRestante = $row['Deuda_Restante'];
        $deudaUltimaNomina = $row['DescuentoDeuda'];
        $deudaInternaActualizada = $deudaInterna + abs($deudaUltimaNomina);
        $totalDeuda = $deudaRestante + abs($deudaUltimaNomina);

        //echo $deudaRestante." ".$deudaUltimaNomina." ".$totalDeuda;

        if($deudaUltimaNomina != 0.00){
          if($deudaInterna != 0.00){
            $stmt = $conn->prepare('UPDATE datos_laborales_empleado set Deuda_Restante= :deuda WHERE FKEmpleado = :id');
            $stmt->bindValue(':deuda',$totalDeuda);
            $stmt->bindValue(':id',$fkEmpleado);
            $stmt->execute();
          }else{
            $stmt = $conn->prepare('UPDATE datos_laborales_empleado set Deuda_Interna= :deudaInterna,Deuda_Restante= :deuda WHERE FKEmpleado = :id');
            $stmt->bindValue(':deudaInterna',$deudaInternaActualizada);
            $stmt->bindValue(':deuda',$totalDeuda);
            $stmt->bindValue(':id',$fkEmpleado);
            $stmt->execute();
          }
        }

        $stmt = $conn->prepare('DELETE FROM nomina_empleado WHERE FKEmpleado = :fkEmpleado AND FKSemana = :fkSemana');
        $stmt->bindValue(':fkEmpleado',$fkEmpleado);
        $stmt->bindValue(':fkSemana',$fkSemana);
        $stmt->execute();

        /*
          if($deudaUltimaNomina < 0){
            $stmt = $conn->prepare('UPDATE empleados set Deuda_Interna= :deuda WHERE PKEmpleado = :id');
            $stmt->bindValue(':deuda',$totalDeuda);
            $stmt->bindValue(':id',$fkEmpleado);
            $stmt->execute();
          }

          */
      }catch(PDOException $ex){
        echo $ex->getMessage();
      }
    }

    require_once("calculo_nomina_directa.php");
    $stmt = $conn->prepare('SELECT * FROM nomina_empleado WHERE FKEmpleado= :id AND FKSemana = :semana');
    $stmt->bindValue(':id',$fkEmpleado);
    $stmt->bindValue(':semana',$semana);
    $stmt->execute();
    $sellado = $stmt->rowCount();
    $row_nomina = $stmt->fetch();

  }else {
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

  <title>Timlid | Nomina semanal</title>

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

  <script src="../../js/cambiar_Estatus_Asistencia.js"></script>
  <script src="../../js/nomina.js"></script>
  <script src="../../js/nomina_dobleturno.js"></script>
  <script src="../../js/nomina_horasextras.js"></script>
  <script src="../../js/paginacionNomina.js"></script>

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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
$icono = '../../img/icons/puestos.svg';
$titulo = "Nomina";
$ruta = "../";
$ruteEdit = $ruta . "central_notificaciones/";
require_once '../menu3.php';
?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <?php
          $rutatb = "../";
          require_once('../topbar.php');
        ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">Nomina semanal</h1>

          <?php
            if($noSueldo == 1)
              echo '  <div class="alert alert-warning" role="alert">
                  No se puede calcular la nómina sin ingresar el salario del trabajador.
                </div>';
          ?>
          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <div class="row">
                <div class="col-lg-10">
                  <b>Asistencias de :</b> <label><?=$nombreEmpleado;?></label>
                </div>
                <div class="col-lg-2">
                </div>
              </div>
            </div>
            <div class="card-body" id="divAsistencias">
              <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tblEmpleados" width="100%" cellspacing="0">
                          <thead>
                            <tr>
                              <th>Dia</th>
                              <th>Fecha</th>
                              <th>Fecha Sin</th>
                              <th>Entrada</th>
                              <th>Salida a comer</th>
                              <th>Regreso de Comer</th>
                              <th>Salida</th>
                              <th>Tiempo a deber</th>
                              <th>Estatus</th>
                              <th>Acciones</th>
                            </tr>
                          </thead>
                          <tfoot>
                            <tr>
                              <th>Dia</th>
                              <th>Fecha</th>
                              <th>Fecha Sin</th>
                              <th>Entrada</th>
                              <th>Salida a comer</th>
                              <th>Regreso de Comer</th>
                              <th>Salida</th>
                              <th>Tiempo a deber</th>
                              <th>Estatus</th>
                              <th>Acciones</th>
                            </tr>
                          </tfoot>
                          <tbody>
                          </tbody>
                        </table>
                      </div>
                </div>
              </div>
              <br>
            </div>
          </div>

          <?php
            if($cuentaDobleTurno > 0){
          ?>
          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <div class="row">
                <div class="col-lg-10">
                  <b>Doble turno de :</b> <label><?=$nombreEmpleado;?></label>
                </div>
                <div class="col-lg-2">
                </div>
              </div>
            </div>
            <div class="card-body" id="divAsistencias">
              <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tblDobleTurno" width="100%" cellspacing="0">
                          <thead>
                            <tr>
                              <th>Dia</th>
                              <th>Fecha</th>
                              <th>Entrada</th>
                              <th>Salida a comer</th>
                              <th>Regreso de Comer</th>
                              <th>Salida</th>
                              <th>Tiempo a deber</th>
                              <th>Estatus</th>
                              <th>Acciones</th>
                            </tr>
                          </thead>
                          <tfoot>
                            <tr>
                              <th>Dia</th>
                              <th>Fecha</th>
                              <th>Entrada</th>
                              <th>Salida a comer</th>
                              <th>Regreso de Comer</th>
                              <th>Salida</th>
                              <th>Tiempo a deber</th>
                              <th>Estatus</th>
                              <th>Acciones</th>
                            </tr>
                          </tfoot>
                          <tbody>
                          </tbody>
                        </table>
                      </div>
                </div>
              </div>
              <br>
            </div>
          </div>
        <?php } ?>

          <?php if($cuentaHorasExtras > 0){ ?>
          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <div class="row">
                <div class="col-lg-10">
                  <b>Horas extras de :</b> <label><?=$nombreEmpleado;?></label>
                </div>
                <div class="col-lg-2">
                </div>
              </div>
            </div>
            <div class="card-body" id="divAsistencias">
              <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tblHorasExtras" width="100%" cellspacing="0">
                          <thead>
                            <tr>
                              <th>Dia</th>
                              <th>Fecha</th>
                              <th>Entrada</th>
                              <th>Salida</th>
                              <th>Horas extras</th>
                              <th>Horas autorizadas</th>
                              <th>Responsable</th>
                              <th>Acciones</th>
                            </tr>
                          </thead>
                          <tfoot>
                            <tr>
                              <th>Dia</th>
                              <th>Fecha</th>
                              <th>Entrada</th>
                              <th>Salida</th>
                              <th>Horas extras</th>
                              <th>Horas autorizadas</th>
                              <th>Responsable</th>
                              <th>Acciones</th>
                            </tr>
                          </tfoot>
                          <tbody>
                          </tbody>
                        </table>
                      </div>
                </div>
              </div>
              <br>
            </div>
          </div>
          <?php } ?>

          <input type="hidden" name="txtSemana" id="txtSemana" value="<?=$semana;?>">


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
                <br>
                <div class="row">
                  <div class="col-lg-12">
                    <center><h4>Recibo de Pago</h4></center><br>
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
                    <label><b>Periodo de pago: </b><?=$fechanomina;?></label>
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
                        Sueldo semanal
                      </div>
                    </div>

                  </div>
                  <div class="col-lg-3">
                    <label class="float-right"><?=$sueldoSemanal;?></label>
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
                          <button id="btnAgregarBono" type="button" class="btn btn-outline-success" onclick="agregarBono();"><i class="fas fa-plus"></i></i></button>
                          <button id="btnEliminarBono" type="button" class="btn btn-outline-danger" onclick="eliminarBono();"><i class="fas fa-times"></i></button>
                      </div>
                      <div class="col-lg-8">
                        Bono de productividad
                      </div>
                    </div>

                    <br>
                  </div>
                  <div class="col-lg-3">
                    <label id="lblBono" class="float-right"><?=$bono;?></label>
                    <input type="hidden" name="txtBono" id="txtBono" value="<?=number_format($bono, 2, '.', '');?>">
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
                        Doble turno
                      </div>
                    </div>

                  </div>
                  <div class="col-lg-3">
                    <label id="lblDobleTurno" class="float-right"><?=number_format($sueldoTotalDT, 2, '.', '');?></label>
                    <input type="hidden" name="txtDobleTurno" id="txtDobleTurno" value="<?=number_format($sueldoTotalDT, 2, '.', '');?>">
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
                        Horas Extras
                      </div>
                    </div>

                  </div>
                  <div class="col-lg-3">
                    <label id="lblHorasExtras" class="float-right"><?=number_format($horasExtras, 2, '.', '');?></label>
                    <input type="hidden" name="txtHorasExtras" id="txtHorasExtras" value="<?=number_format($horasExtras, 2, '.', '');?>">
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
                        ISR
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-3">

                  </div>
                  <div class="col-lg-3">
                    <label id="lblISR" class="float-right"><?php if($sellado > 0) echo $row_nomina['ISR']; else echo $ISRSemana;?></label>
                    <input type="hidden" name="txtISR" id="txtISR" value="<?=$ISRSemana;?>">
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
                        Cuota IMSS
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-3">

                  </div>
                  <div class="col-lg-3">
                    <label id="lblIMSS" class="float-right"><?php if($sellado > 0) echo $row_nomina['cuotaIMSS']; else echo number_format($cuota_obrero,2,".","");?></label>
                    <input type="hidden" name="txtIMSS" id="txtIMSS" value="<?=number_format($cuota_obrero,2,".","");?>">
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
                        Descuento de improductividad
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-3">

                  </div>
                  <div class="col-lg-3">
                    <label id="lblDescuento" class="float-right"><?=number_format($sueldoDescuento, 2, '.', '');?></label>
                    <input type="hidden" name="txtDescuento" id="txtDescuento" value="<?=number_format($sueldoDescuento, 2, '.', '');?>">
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
                        Descuento del INFONAVIT
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-3">

                  </div>
                  <div class="col-lg-3">
                    <label id="lblInfonavit" class="float-right"><?=number_format($infonavit, 2, '.', '');?></label>
                    <input type="hidden" name="txtInfonavit" id="txtInfonavit" value="<?=number_format($infonavit, 2, '.', '');?>">
                  </div>
                  <div class="col-lg-3">
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-3">
                    <div class="row">
                      <div class="col-lg-4">
                          <button id="btnAgregarPago" type="button" class="btn btn-outline-success" onclick="agregarPago();"><i class="fas fa-plus"></i></i></button>
                          <button id="btnEliminarPago" type="button" class="btn btn-outline-danger" onclick="eliminarPagoDeuda();"><i class="fas fa-times"></i></button>
                      </div>
                      <div class="col-lg-8">
                        Descuento deuda interna
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-3">

                  </div>
                  <div class="col-lg-3">

                    <label id="lblDeudaInterna" class="float-right"><?=number_format($parcialidades, 2, '.', '');?></label>
                    <input type="hidden" name="txtDeudaInterna" id="txtDeudaInterna" value="<?=number_format($parcialidades, 2, '.', '');?>">

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
                    <label name="lblTotal" id="lblTotal" class="float-right"><?php if($sellado > 0) echo $row_nomina['Salario']; else echo number_format($neto_a_pagar, 2, '.', '');?></label>
                  </div>
                </div>
                <input type="hidden" name="txtId" id="txtId" value="<?=$id;?>">
                <input type="hidden" name="txtContEstatus" id="txtContEstatus" value="<?=$contEstatus;?>">
                <input type="hidden" name="txtContExcelente" id="txtContExcelente" value="<?=$contExcelente;?>">
                <input type="hidden" name="txtBonoAgregado" id="txtBonoAgregado" value="0">
                <input type="hidden" name="txtSem" id="txtSem" value="<?=$semana;?>">
                <input type="hidden" name="txtTurno" id="txtTurno" value="<?=$idTurno;?>">
                <input type="hidden" name="txtSellado" id="txtSellado" value="<?=$sellado;?>">
                <input type="hidden" name="txtDobleTurno" id="txtDobleTurno" value="<?=$sueldoTotalDT;?>">
                <input type="hidden" name="txtHorasExtras" id="txtHorasExtras" value="<?=$horasExtras;?>">
                <input type="hidden" name="txtSalario" id="txtSalario" value="<?=$neto_a_pagar;?>">
                <input type="hidden" name="txtSalarioSem" id="txtSalarioSem" value="<?=$sueldoSemanal;?>">
                <input type="hidden" name="txtDeuda" id="txtDeuda" value="<?=$deuda;?>">
                <input type="hidden" name="txtParcialidades" id="txtParcialidades" value="<?=$parcialidades;?>">
                <input type="hidden" name="txtBonoPreAprobado" id="txtBonoPreAprobado" value="<?=$bonoCorrespondiente;?>">
                <input type="hidden" name="txtBonoExiste" id="txtBonoExiste" value="<?=$bonoExiste;?>">
                <input type="hidden" name="txtDiasTrabajados" id="txtDiasTrabajados" value="<?=$diasTrabajadosImpuestos;?>">
                <?php if($noSueldo == 0){ ?>
                <button type="submit" class="btn btn-success float-right" name="btnAgregar" id="btnAgregar">Sellar nomina</button>
                <?php } ?>
                <button type="submit" class="btn btn-danger float-right" name="btnEliminar" id="btnEliminar">Eliminar nomina</button>
              </form>
              <br>
            </div>

            <!-- Inicio paginación -->
            <div class="row">
            <div class="col-lg-8">

            </div>
              <div class="col-lg-4">
                <nav aria-label="..." class="float-right" style="padding:20px">
                    <ul class="pagination" id="pagination">

                    </ul>
                </nav>
              </div>
            </div>


            <!-- Termino paginación -->
          </div>
        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <?php
        $rutaf = "../";
        require_once('../footer.php');
      ?>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

</body>
<script> var ruta = "../";</script>
<script src="../../js/sb-admin-2.min.js"></script>

</html>

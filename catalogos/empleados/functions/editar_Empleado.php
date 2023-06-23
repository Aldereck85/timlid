<?php
session_start();

if (isset($_SESSION["Usuario"])) {
    require_once '../../../include/db-conn.php';
    if (isset($_POST['btnEditarLaborales'])) {
        $ide = $_POST['txtId'];
        $fechaIngreso = $_POST['txtfechaIngreso'];
        $puesto = $_POST['cmbPuesto'];
        $turno = $_POST['cmbTurno'];
        $locacion = $_POST['cmbLocacion'];
        $infonavit = $_POST['txtInfonavit'];
        $deuda = $_POST['txtDeuda'];
        $deudaRestante = $_POST['txtDeudaRestante'];
        $estatus = $_POST['cmbEstatus'];
        $empresa = $_POST['cmbEmpresa'];
        try {
            $stmt = $conn->prepare('UPDATE datos_laborales_empleado SET Fecha_Ingreso = :fecha_ingreso,Infonavit = :infonavit, Deuda_Interna = :deuda, Deuda_Restante = :deuda_restante, FKPuesto = :puesto, FKLocacion = :locacion, FKTurno = :turno, FKEstatus = :estatus, FKEmpresa = :empresa WHERE FKEmpleado = :id');
            $stmt->bindValue(':fecha_ingreso', $fechaIngreso);
            $stmt->bindValue(':infonavit', $infonavit);
            $stmt->bindValue(':deuda', $deuda);
            $stmt->bindValue(':deuda_restante', $deudaRestante);
            $stmt->bindValue(':puesto', $puesto);
            $stmt->bindValue(':locacion', $locacion);
            $stmt->bindValue(':turno', $turno);
            $stmt->bindValue(':estatus', $estatus);
            $stmt->bindValue(':empresa', $empresa);
            $stmt->bindValue(':id', $ide, PDO::PARAM_INT);
            $stmt->execute();
            header('Location:../index.php');
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }
    if (isset($_POST['btnAgregarLaborales'])) {
        $id = $_POST['txtIdEmpleado'];
        $fechaIngreso = $_POST['txtfechaIngreso'];
        $infonavit = $_POST['txtInfonavit'];
        $deudaInterna = $_POST['txtDeuda'];
        $deudaRestante = $_POST['txtDeudaRestante'];
        $estatus = $_POST['cmbEstatus'];
        $turno = $_POST['cmbTurno'];
        $puesto = $_POST['cmbPuesto'];
        $locacion = $_POST['cmbLocacion'];
        $empresa = $_POST['cmbEmpresa'];
        try {
            $stmt = $conn->prepare('INSERT INTO datos_laborales_empleado (Fecha_Ingreso,Infonavit,Deuda_Interna,Deuda_Restante,FKEstatus,FKEmpleado,FKPuesto,FKTurno,FKLocacion,FKEmpresa) VALUES (:fecha_ingreso,:infonavit,:deuda_interna,:deuda_restante,:estatus,:empleado,:puesto,:turno,:locacion,:empresa)');
            $stmt->bindValue(':fecha_ingreso', $fechaIngreso);
            $stmt->bindValue(':turno', $turno);
            $stmt->bindValue(':puesto', $puesto);
            $stmt->bindValue(':locacion', $locacion);
            $stmt->bindValue(':infonavit', $infonavit);
            $stmt->bindValue(':deuda_interna', $deudaInterna);
            $stmt->bindValue(':deuda_restante', $deudaRestante);
            $stmt->bindValue(':estatus', $estatus);
            $stmt->bindValue(':empresa', $empresa);
            $stmt->bindValue(':empleado', $id);
            $stmt->execute();
            header('Location:../index.php');
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }
    if (isset($_POST['btnAgregarMedicos'])) {
        $id = $_POST['txtIdEmpleado'];
        $nss = $_POST['txtNSS'];
        $tipoSangre = $_POST['cmbTipoSangre'];
        $contactoEmergencia = $_POST['txtContactoEmergencia'];
        $numeroEmergencia = $_POST['txtNumeroEmergencia'];
        $notas = $_POST['txaNotas'];

        try {
            $stmt = $conn->prepare('INSERT IGNORE INTO datos_medicos_empleado (NSS,Tipo_de_Sangre,Contacto_Emergencia,Numero_Emergencia,Notas,FKEmpleado) VALUES (:nns,:tipo_sangre,:contacto_emergencia,:numero_emergencia,:notas,:id)');
            $stmt->bindValue(':nns', $nss);
            $stmt->bindValue(':tipo_sangre', $tipoSangre);
            $stmt->bindValue(':contacto_emergencia', $contactoEmergencia);
            $stmt->bindValue(':numero_emergencia', $numeroEmergencia);
            $stmt->bindValue(':notas', $notas);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            header('Location:../index.php');
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }
    if (isset($_POST['btnEditarMedicos'])) {
        $ide = $_POST['txtId'];
        $nss = $_POST['txtNSS'];
        $tipo_sangre = $_POST['cmbTipoSangre'];
        $contacto_emeregencia = $_POST['txtContactoEmergencia'];
        $numero_emergencia = $_POST['txtNumeroEmergencia'];
        $notas = $_POST['txaNotas'];
        $tipo_sangre = strval($tipo_sangre);
        try {
            $stmt = $conn->prepare('UPDATE datos_medicos_empleado SET NSS = :nss, Contacto_Emergencia = :contacto_emergencia, Numero_Emergencia = :numero_emergencia, Notas = :notas WHERE FKEmpleado = :id');
            $stmt->bindValue(':nss', $nss);
            //$stmt->bindValue(':tipo_sangre',$tipo_sangre);
            $stmt->bindValue(':contacto_emergencia', $contacto_emeregencia);
            $stmt->bindValue(':numero_emergencia', $numero_emergencia);
            $stmt->bindValue(':notas', $notas);
            $stmt->bindValue(':id', $ide, PDO::PARAM_INT);
            $stmt->execute();
            header('Location:../index.php');
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }

    }
    if (isset($_POST['btnAgregarBancarios'])) {
        $id = $_POST['txtIdEmpleado'];
        $banco = $_POST['cmbBanco'];
        $cuenta = $_POST['txtCuentaBancaria'];
        $clabe = $_POST['txtCLABE'];
        $tarjeta = $_POST['txtNumeroTarjeta'];
        try {
            $stmt = $conn->prepare('INSERT INTO datos_bancarios_empleado (FKBanco,Cuenta_Bancaria,CLABE,Numero_Tarjeta,FKEmpleado) VALUES (:banco,:cuenta,:clabe,:tarjeta,:id)');
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':banco', $banco);
            $stmt->bindValue(':cuenta', $cuenta);
            $stmt->bindValue(':clabe', $clabe);
            $stmt->bindValue(':tarjeta', $tarjeta);
            $stmt->execute();
            header('Location:../index.php');
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }
    if (isset($_POST['btnEditarBancarios'])) {
        $ide = $_POST['txtId'];
        $banco = $_POST['cmbBanco'];
        $cuenta = $_POST['txtCuentaBancaria'];
        $clabe = $_POST['txtCLABE'];
        $tarjeta = $_POST['txtNumeroTarjeta'];
        try {
            $stmt = $conn->prepare('UPDATE datos_bancarios_empleado SET FKBanco = :banco, Cuenta_Bancaria = :cuenta, CLABE = :clabe, Numero_Tarjeta = :tarjeta WHERE PKBancariosEmpleado = :id');
            $stmt->bindValue(':banco', $banco);
            $stmt->bindValue(':cuenta', $cuenta);
            $stmt->bindValue(':clabe', $clabe);
            $stmt->bindValue('tarjeta', $tarjeta);
            $stmt->bindValue(':id', $ide, PDO::PARAM_INT);
            $stmt->execute();
            header('Location:../index.php');
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }
    /*Llenado de formularios con datos de la base de datos*/
    if (isset($_POST['idEmpleadoU'])) {
        $id = $_POST['idEmpleadoU'];
        $stmt = $conn->prepare('SELECT * FROM empleados LEFT JOIN datos_laborales_empleado ON empleados.PKEmpleado = datos_laborales_empleado.FKEmpleado WHERE PKEmpleado= :id');
        $stmt->execute(array(':id' => $id));
        $row = $stmt->fetch();
        $primerNombre = $row['Primer_Nombre'];
        $segundoNombre = $row['Segundo_Nombre'];
        $apellidoPaterno = $row['Apellido_Paterno'];
        $apellidoMaterno = $row['Apellido_Materno'];
        $calle = $row['Direccion'];
        $estado = $row['Estado'];
        $ciudad = $row['Ciudad'];
        $colonia = $row['Colonia'];
        $cp = $row['CP'];
        $curp = $row['CURP'];
        $rfc = $row['RFC'];
        $fecha = $row['Fecha_de_Nacimiento'];
        $telefono = $row['Telefono'];
        $estadoCivil = $row['Estado_Civil'];
        $sexo = $row['Sexo'];
        $no_exterior = $row['Numero_Exterior'];
        $no_interior = $row['Numero_Interior'];
        $estatus = $row['FKEstatus'];
        $puesto = $row['FKPuesto'];
        $turno = $row['FKTurno'];
        $locacion = $row['FKLocacion'];
        $fechaIngreso = $row['Fecha_Ingreso'];
        $infonavit = $row['Infonavit'];
        $deuda = $row['Deuda_Interna'];
        $deuda_restante = $row['Deuda_Restante'];
        $infonavit = number_format($infonavit, 2, '.', '');
        $deuda = number_format($deuda, 2, '.', '');
        $deuda_restante = number_format($deuda_restante, 2, '.', '');
        $empresa = $row['FKEmpresa'];

        $stmt = $conn->prepare('SELECT * FROM empleados LEFT JOIN datos_medicos_empleado ON empleados.PKEmpleado = datos_medicos_empleado.FKEmpleado WHERE PKEmpleado= :id');
        $stmt->execute(array(':id' => $id));
        $row = $stmt->fetch();
        $nss = $row['NSS'];
        $tipo_sangre = $row['Tipo_de_Sangre'];
        $contacto_emeregencia = $row['Contacto_Emergencia'];
        $numero_emergencia = $row['Numero_Emergencia'];
        $notas = $row['Notas'];

        $stmt = $conn->prepare('SELECT * FROM empleados LEFT JOIN datos_bancarios_empleado ON empleados.PKEmpleado = datos_bancarios_empleado.FKEmpleado WHERE PKEmpleado= :id');
        $stmt->execute(array(':id' => $id));
        $row = $stmt->fetch();
        $banco = $row['FKBanco'];
        $cuenta = $row['Cuenta_Bancaria'];
        $clabe = $row['CLABE'];
        $tarjeta = $row['Numero_Tarjeta'];
    }
} else {
    header("location:../../dashboard.php");
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | Editar empleado</title>

  <!-- ESTILOS -->
  <link href="../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../css/dashboard.css" rel="stylesheet">
  <link href="../../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../../css/notificaciones.css" rel="stylesheet">
  <link href="../../../css/styles.css" rel="stylesheet">
  <link href="../../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link href="../../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../../css/stylesNewTable.css" rel="stylesheet">
  <!-- JS -->
  <script src="../../../vendor/jquery/jquery.min.js"></script>
  <script src="../js/validaciones.js"></script>
  <script src="../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../../js/lobibox.min.js"></script>
  <script src="../../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../vendor/datatables/dataTables.responsive.js"></script>
  <script src="../../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../../vendor/jszip/jszip.min.js"></script>
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
$ruta = "../../";
$ruteEdit = $ruta . "central_notificaciones/";
require_once '../../menu3.php';
?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <?php
$rutatb = "../../";
$titulo = "Empleados";
$icono = '../../img/Empleados/ICONO LISTA DE EMPLEADOS_Mesa de trabajo 1.svg';
$backIcon = true;
$backRoute = "../";
require_once $rutatb . 'topbar.php';
?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <div id="alertas"></div>

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h2 class="mb-0 color-primary">Editar empleado</h2>
          </div>


          <div class="row">

            <div class="col-lg-12">

              <ul class="nav nav-tabs">
                <li class="nav-item">
                  <a id="CargarEdicionDatosPersonales" class="nav-link" href="#" data-id="<?=$_GET['idEmpleadoU'];?>">
                    Datos personales
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarEdicionDatosLaborales" class="nav-link" href="#" data-id1="<?=$_GET['idEmpleadoU'];?>">
                    Datos Laborales
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarEdicionDatosMedicos" class="nav-link" href="#" data-id1="<?=$_GET['idEmpleadoU'];?>">
                    Datos médicos
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarEdicionDatosBancarios" class="nav-link" href="#" data-id1="<?=$_GET['idEmpleadoU'];?>">
                    Datos bancarios
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarEdicionRoles" class="nav-link" href="#" data-id1="<?=$_GET['idEmpleadoU'];?>">
                    Roles
                  </a>
                </li>
              </ul>

              <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="datos" role="tabpanel" aria-labelledby="nav-main-tab">
                </div>
              </div>
            </div>
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <?php
$rutaf = "../../";
require_once '../../footer.php';
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
  <script src="../js/pestanas_empleados.js"></script>
  <script>
  var ruta = "../../";
  </script>
  <script src="../../../js/sb-admin-2.min.js"></script>
  <script src="../../../js/scripts.js"></script>
  <script src="../../../js/slimselect.min.js"></script>

  <script type="text/javascript">
  var id = $('#CargarEdicionDatosLaborales').data('id1');
  CargarDatosPersonalesEdicion(id);

  </script>

</body>

</html>
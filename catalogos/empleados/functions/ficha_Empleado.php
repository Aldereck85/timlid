<?php
session_start();

  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 1 || $_SESSION["FKRol"] == 4)){
    require_once('../../../include/db-conn.php');
      if(isset($_GET['id'])){
        $id =  $_GET['id'];
        $stmt = $conn->prepare('SELECT COUNT(*) FROM empleados LEFT JOIN datos_laborales_empleado ON empleados.PKEmpleado = datos_laborales_empleado.FKEmpleado LEFT JOIN datos_medicos_empleado ON empleados.PKEmpleado = datos_medicos_empleado.FKEmpleado LEFT JOIN datos_bancarios_empleado ON empleados.PKEmpleado = datos_bancarios_empleado.FKEmpleado WHERE empleados.PKEmpleado= :id');
        $stmt->execute(array(':id'=>$id));
        $number_of_rows1 = $stmt->fetchColumn();
        //$stmt = $conn->prepare('SELECT COUNT(*) FROM empleados LEFT JOIN datos_laborales_empleado ON empleados.PKEmpleado = datos_laborales_empleado.FKEmpleado WHERE empleados.PKEmpleado= :id');
        //$stmt->execute(array(':id'=>$id));
        //$number_of_rows = $stmt->fetchColumn();
        if($number_of_rows1 > 0)
        {
          $id =  $_GET['id'];
          $stmt = $conn->prepare('SELECT * FROM empleados LEFT JOIN datos_laborales_empleado ON empleados.PKEmpleado = datos_laborales_empleado.FKEmpleado LEFT JOIN datos_medicos_empleado ON empleados.PKEmpleado = datos_medicos_empleado.FKEmpleado LEFT JOIN datos_bancarios_empleado ON empleados.PKEmpleado = datos_bancarios_empleado.FKEmpleado LEFT JOIN locacion on locacion.PKLocacion = datos_laborales_empleado.FKLocacion LEFT JOIN puestos ON puestos.PKPuesto = datos_laborales_empleado.FKPuesto LEFT JOIN turnos on datos_laborales_empleado.FKTurno = turnos.PKTurno WHERE PKEmpleado= :id');
          $stmt->execute(array(':id'=>$id));
          $row = $stmt->fetch();
          $primerNombre = $row['Primer_Nombre'];
          $segundoNombre = $row['Segundo_Nombre'];
          $apellidoPaterno = $row['Apellido_Paterno'];
          $apellidoMaterno = $row['Apellido_Materno'];
          $nss = $row['NSS'];
          $calle = $row['Direccion'];
          $no_exterior = $row['Numero_Exterior'];
          $no_interior = $row['Numero_Interior'];
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
          $locacion = $row['FKLocacion'];
          $puesto = $row['FKPuesto'];
          $turno = $row['FKTurno'];
          $sueldo = $row['Sueldo_semanal'];
          $estatus = $row['FKEstatus'];
          $tipo_sangre = $row['Tipo_de_Sangre'];
          $contacto_emeregencia = $row['Contacto_Emergencia'];
          $numero_emergencia = $row['Numero_Emergencia'];
          $notas = $row['Notas'];
          $banco = $row['FKBanco'];
          $cuenta = $row['Cuenta_Bancaria'];
          $clabe = $row['CLABE'];
          $tarjeta = $row['Numero_Tarjeta'];
          $fechaIngreso = $row['Fecha_Ingreso'];
          $infonavit = $row['Infonavit'];
          $deuda= $row['Deuda_Interna'];
          $deuda_restante = $row['Deuda_Restante'];
          $infonavit = number_format($infonavit, 2, '.', '');
          $deuda = number_format($deuda, 2, '.', '');
        }else if($number_of_rows1 > 0){
          $stmt = $conn->prepare('SELECT * FROM empleados LEFT JOIN datos_laborales_empleado ON empleados.PKEmpleado = datos_laborales_empleado.FKEmpleado LEFT JOIN datos_medicos_empleado ON empleados.PKEmpleado = datos_medicos_empleado.FKEmpleado LEFT JOIN locacion on locacion.PKLocacion = datos_laborales_empleado.FKLocacion LEFT JOIN puestos ON puestos.PKPuesto = datos_laborales_empleado.FKPuesto LEFT JOIN turnos on datos_laborales_empleado.FKTurno = turnos.PKTurno WHERE PKEmpleado= :id');
          $stmt->execute(array(':id'=>$id));
          $row = $stmt->fetch();
          $primerNombre = $row['Primer_Nombre'];
          $segundoNombre = $row['Segundo_Nombre'];
          $apellidoPaterno = $row['Apellido_Paterno'];
          $apellidoMaterno = $row['Apellido_Materno'];
          $nss = $row['NSS'];
          $calle = $row['Direccion'];
          $no_exterior = $row['Numero_Exterior'];
          $no_interior = $row['Numero_Interior'];
          $estado = $row['Estado'];
          $ciudad = $row['Ciudad'];
          $colonia = $row['Colonia'];
          $cp = $row['CP'];
          $curp = $row['CURP'];
          $rfc = $row['RFC'];
          $fecha = $row['Fecha_de_Nacimiento'];
          $estadoCivil = $row['Estado_Civil'];
          $sexo = $row['Sexo'];
          $telefono = $row['Telefono'];
          $estatus = $row['FKEstatus'];
          $locacion = "No asignado";
          $puesto = "No asignado";
          $turno = "No asignado";
          $sueldo = "No asignado";
          $fechaIngreso = $row['Fecha_Ingreso'];
          $infonavit = $row['Infonavit'];
          $deuda= $row['Deuda_Interna'];
          $idBanco = "No asignado";
          $cuentaBanco = "No asignado";
          $infonavit = number_format($infonavit, 2, '.', '');
          $deuda = number_format($deuda, 2, '.', '');
        }
      }
  }else {
    header("location:../../dashboard.php");
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

  <title>Timlid | Editar empleado</title>

  <!-- Custom fonts for this template-->
  <link href="../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../../../css/sb-admin-2.css" rel="stylesheet">

  <style type="text/css">
  #etiquetas h6{
    text-align: center;
    font-weight: bold;
    color: white;
  }
  /*background*/
  #CargarDatosPersonales{
    background-color: #5bc0de;
  }
  #CargarDatosLaborales{
    background-color: #5cb85c;
  }
  #CargarDatosMedicos{
    background-color: #757575;
  }
  #CargarDatosBancarios{
    background-color: #59698d;
  }
  /*hover*/
  #CargarDatosPersonales:hover{
    background-color: #00acc1;
  }
  #CargarDatosLaborales:hover{
    background-color: #2e7d32;
  }
  #CargarDatosMedicos:hover{
    background-color: #424242;
  }
  #CargarDatosBancarios:hover{
    background-color: #2e3951;
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

          <?php
            $rutatb = "../../";
            require_once('../../topbar.php');
          ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Empleado(a): <?php echo $primerNombre." ".$segundoNombre." ".$apellidoPaterno." ".$apellidoMaterno;?></h1>
          </div>


          <div class="row">

            <div class="col-lg-12">

              <ul class="nav nav-tabs" id="etiquetas">
                <li class="nav-item col-lg-3">
                  <a id="CargarDatosPersonales" class="nav-link" href="#"><h6 class=" mb-0">Datos personales</h6></a>
                </li>
                <li class="nav-item col-lg-3">
                  <a id="CargarDatosLaborales" class="nav-link" href="#"><h6 class=" mb-0">Datos Laborales</h6></a>
                </li>
                <li class="nav-item col-lg-3">
                  <a id="CargarDatosMedicos" class="nav-link" href="#"><h6 class=" mb-0">Datos médicos</h6></a>
                </li>
                <li class="nav-item col-lg-3">
                  <a id="CargarDatosBancarios" class="nav-link" href="#"><h6 class=" mb-0">Datos bancarios</h6></a>
                </li>
              </ul>

                <div id="datos"><h1 class="h3 mb-0 text-gray-800">Seleccione el tipo de datos que va a ingresar...</h1></div>


        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->
      </div>
      </div>

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

  <!-- Bootstrap core JavaScript-->
  <script src="../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <script>
  $(document).ready(function(){
    $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
      setInterval(refrescar, 5000);
      $('#datos').load('datos_personales_ficha.php?primer_nombre=<?=$primerNombre;?>&segundo_nombre=<?=$segundoNombre;?>&apellido_paterno=<?=$apellidoPaterno;?>&apellido_materno=<?=$apellidoMaterno;?>&telefono=<?=$telefono;?>&curp=<?=$curp;?>&rfc=<?=$rfc;?>&$fecha_nacimiento=<?=$fecha;?>&estado_civil=<?=$estadoCivil;?>&sexo=<?=$sexo;?>&calle=<?=urlencode($calle);?>&n_exterior=<?=$no_exterior;?>&n_interior=<?=$no_interior;?>&colonia=<?=urlencode($colonia);?>&cp=<?=$cp;?>&ciudad=<?=$ciudad;?>&estado=<?=urlencode($estado);?>&id=<?=$id;?>');
    });
  function refrescar(){
    $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
  }
  $('#CargarDatosPersonales').click(function(){
    document.getElementById('CargarDatosPersonales').style.background = 'linear-gradient(#5bc0de,#00acc1,white)';
    document.getElementById('CargarDatosLaborales').style.background = '#5cb85c';
    document.getElementById('CargarDatosMedicos').style.background = '#757575';
    document.getElementById('CargarDatosBancarios').style.background = '#59698d';
    $('#datos').load('datos_personales_ficha.php?primer_nombre=<?=urlencode($primerNombre);?>&segundo_nombre=<?=urlencode($segundoNombre);?>&apellido_paterno=<?=urlencode($apellidoPaterno);?>&apellido_materno=<?=urlencode($apellidoMaterno);?>&telefono=<?=$telefono;?>&curp=<?=$curp;?>&rfc=<?=$rfc;?>&$fecha_nacimiento=<?=$fecha;?>&estado_civil=<?=$estadoCivil;?>&sexo=<?=$sexo;?>&calle=<?=urlencode($calle);?>&n_exterior=<?=$no_exterior;?>&n_interior=<?=$no_interior;?>&colonia=<?=urlencode($colonia);?>&cp=<?=$cp;?>&ciudad=<?=$ciudad;?>&estado=<?=urlencode($estado);?>&id=<?=$id;?>');
  });
  $('#CargarDatosLaborales').click(function(){
    document.getElementById('CargarDatosPersonales').style.background = '#5bc0de';
    document.getElementById('CargarDatosLaborales').style.background = 'linear-gradient(#5cb85c,#2e7d32,white)';
    document.getElementById('CargarDatosMedicos').style.background = '#757575';
    document.getElementById('CargarDatosBancarios').style.background = '#59698d';
    $('#datos').load('datos_laborales_ficha.php?id=<?=$id;?>&puesto=<?=$puesto;?>&estatus=<?=$estatus;?>&turno=<?=$turno;?>&locacion=<?=$locacion;?>&fecha_ingreso=<?=$fechaIngreso;?>&infonavit=<?=$infonavit;?>&deuda=<?=$deuda;?>&deuda_restante=<?=$deuda_restante;?>');
  });
  $('#CargarDatosMedicos').click(function(){
    document.getElementById('CargarDatosPersonales').style.background = '#5bc0de';
    document.getElementById('CargarDatosLaborales').style.background = '#5cb85c';
    document.getElementById('CargarDatosMedicos').style.background = 'linear-gradient(#757575,#424242,white)';
    document.getElementById('CargarDatosBancarios').style.background = '#59698d';
    $('#datos').load('datos_medicos_ficha.php?id=<?=$id;?>&nss=<?=$nss;?>&tipo_sangre=<?=urlencode($tipo_sangre);?>&contacto_emergencia=<?=urlencode($contacto_emeregencia);?>&numero_emergencia=<?=$numero_emergencia;?>&notas=<?=urlencode($notas);?>');
  });
  $('#CargarDatosBancarios').click(function(){
    document.getElementById('CargarDatosPersonales').style.background = '#5bc0de';
    document.getElementById('CargarDatosLaborales').style.background = '#5cb85c';
    document.getElementById('CargarDatosMedicos').style.background = '#757575';
    document.getElementById('CargarDatosBancarios').style.background = 'linear-gradient(#59698d,#2e3951,white)';
    $('#datos').load('datos_bancarios_ficha.php?id=<?=$id;?>&banco=<?=$banco;?>&cuenta=<?=$cuenta;?>&tarjeta=<?=$tarjeta;?>&clabe=<?=$clabe;?>');
  });
  </script>

  <script> var ruta = "../../";</script>
  <script src="../../../js/sb-admin-2.min.js"></script>

</body>

</html>

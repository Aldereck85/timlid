<?php
session_start();

function generateRandomString($length = 12) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
$redirect = 0;
  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 1)){
    require_once('../../../../include/db-conn.php');

    $stmt = $conn->prepare('SELECT * FROM roles INNER JOIN usuarios ON roles.PKRol = usuarios.FKRol WHERE usuarios.FKRol = :id');
    $stmt->execute(array(":id"=>$_SESSION['PKUsuario']));
    $array = $stmt->fetchAll(PDO::FETCH_OBJ);

    if(isset ($_POST['btnAgregar'])){
      $idUsuario = $_POST['cmbIdEmpleado'];

      $stmt = $conn->prepare('SELECT count(*) FROM empleados WHERE PKEmpleado= :id');
      $stmt->execute(array(':id'=>$idUsuario));
      $number_of_rows = $stmt->fetchColumn();
      if($number_of_rows > 0)
      {
        $usuario = $_POST['txtUsuario'];
        $password = $_POST['txtContrasena'];
        $rol = (int) $_POST['cmbRol'];
        $codigo = generateRandomString();

        try{
          $stmt = $conn->prepare('INSERT INTO usuarios (Usuario,Contrasena,FKEmpleado,FKRol,Codigo)VALUES(:usuario,:contrasena,:idEmpleado,:rol, :codigo)');
          $stmt->bindValue(':usuario',$usuario);
          $stmt->bindValue(':contrasena',$password);
          $stmt->bindValue(':idEmpleado',$idUsuario);
          $stmt->bindValue(':codigo',$codigo);
          $stmt->bindValue(':rol', (int) $rol, PDO::PARAM_INT);
          if($stmt->execute()){
            $idUsuario = $conn->lastInsertId();

            $redirect = 1;

            $stmt = $conn->prepare("SELECT u.PKUser, u.Usuario, e.Primer_Nombre, e.Segundo_Nombre, e.Apellido_Paterno, e.Apellido_Materno, u.Codigo FROM usuarios as u LEFT JOIN empleados as e ON u.FKEmpleado = e.PKEmpleado WHERE u.PKUser = :idusuario");
            $stmt->bindValue(':idusuario',$idUsuario);
            $stmt->execute();
            $row_usuario = $stmt->fetch();

            if($row_usuario['Segundo_Nombre'] == ""){
              $segundoNombre = "";
            }
            else{
              $segundoNombre = $row_usuario['Segundo_Nombre']." ";
            }

            $email = $row_usuario['Usuario'];
            $nombreUsuario = $row_usuario['Primer_Nombre']." ".$segundoNombre.$row_usuario['Apellido_Paterno']." ".$row_usuario['Apellido_Paterno'];
            $codigoBD = $row_usuario['Codigo'];

          }
        }catch(PDOException $ex){
          echo $ex->getMessage();
        }
      }else{
        echo "El empleado no existe";
      }
    }
  }else {
    header("location:../../dashboard.php");
  }


 ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="icon" type="image/png" href="../../../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Timlid | Roles del usuario</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../../js/validaciones.js"></script>
  <!-- Core plugin JavaScript-->
  <script src="../../../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../../../js/sb-admin-2.min.js"></script>

  <script src="../../../../js/bootstrap-clockpicker.min.js"></script>
  <script src="../../../../js/roles.js"></script>

  <!-- Custom fonts for this template-->
  <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../../css/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../../../../css/sb-admin-2.css" rel="stylesheet">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha256-c4gVE6fn+JRKMRvqjoDp+tlG4laudNYrXI1GncbfAYY=" crossorigin="anonymous"></script>
  <link href="../../../../css/chosen.css" rel="stylesheet" type="text/css">
  <link href="../../../../css/roles.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" integrity="sha256-EH/CzgoJbNED+gZgymswsIOrM9XhIbdSJ6Hwro09WE4=" crossorigin="anonymous" />

  <style>
    .title_tab{
      background-color: #15589B;
      border: 1px solid #15589B;
      color: white;
    }
  </style>

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
      <?php
      $ruta = "../../../";
      require_once('../../../menu3.php');
      ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <?php
          $rutatb = "../../../";
          $titulo = "";
          require_once('../../../topbar.php');
        ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

		    <?php
          if($redirect == 1){
            echo '<div class="container" id="mensaje_exito">
                  <br>
                  <div class="alert alert-success" role="alert">
                    Se ha enviado un correo electrónico al email de este usuario para activar su cuenta.
                  </div>
                </div>';

            $to = $email;
            $subject = "Activar cuenta | Timlid";

            $message = "
            <h2 align='center'>Activar cuenta</h2>
            <hr>
            <p align='left'>Saludos, ".$nombreUsuario."</p>
            <p align='justify'>Bienvenido al sistema ERP de Timlid, para completar tu registro solo necesitas acceder al siguiente enlace:</p>
            <p align='center'><a href='http://erpghmedic.com.mx/index.php?id=".$idUsuario."&codigo=".$codigoBD."' >Timlid - Activar cuenta</a></p>
            <hr>
            <center><img src='http://erpghmedic.com.mx/img/Logo-transparente.png' width='15%' /></center>
            ";

            // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            $headers .= 'From: <erpghmed@erpghmedic.com.mx>' . "\r\n";

            mail($to,$subject,$message,$headers);
          }
        ?>
          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Administración de roles</h1>
          </div>
          <div class="row">

            <div class="col-lg-12">
              <ul class="nav nav-tabs" id="myTab" role="tablist">

              <?php
                //echo "tamaño array: ".count($array);
                //echo ($array[0]->Rol);
                
                for ($i=0; $i < count($array); $i++) { 
                  //echo ($array[$i]->Rol);
                  if($array[$i]->PKRol == 1){
                    echo '<li class="nav-item">
                            <a class="nav-link active" id="'.$array[$i]->Rol.'-tab" data-toggle="tab" href="#" role="tab" aria-controls="home" aria-selected="true">'.$array[$i]->Rol.'</a>
                          </li>';
                  }else{
                    echo '<li class="nav-item">
                            <a class="nav-link" id="'.$array[$i]->Rol.'-tab" data-toggle="tab" href="#" role="tab" aria-controls="profile" aria-selected="false">'.$array[$i]->Rol.'</a>
                          </li>';
                  }
                  
                }

              ?>
              <!--
                <li class="nav-item">
                  <a class="nav-link active" id="administrador-tab" data-toggle="tab" href="#" role="tab" aria-controls="home" aria-selected="true">Administrador</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="rh-tab" data-toggle="tab" href="#" role="tab" aria-controls="profile" aria-selected="false">Recursos humanos</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="personalizado-tab" onclick="mostrarPersonalizado()" data-toggle="tab" href="#" role="tab" aria-controls="contact" aria-selected="false">Personalizado</a>
                </li>
                -->
              </ul>
              <!-- Basic Card Example -->
              <div class="card mb-4" style="position:relative;top:-10px;">
                <div class="card-body">
                    <div class="row">
                      <div class="col-lg-12">
                        <div class="tab-content" id="myTabContent">
                          <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <br>
                            <div class="row">
                              <div class="col-lg-12">
                                <div class="accordion" id="accordionExample">
                                  <div class="card">
                                    <div class="card-header title_tab" id="headingOne">
                                      <h5 class="mb-0">
                                          <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            <i id="opt-group-173" class="opt-menu-icon"></i>
                                          </button>
                                          Recursos humanos
                                      </h5>
                                    </div>

                                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                      <div class="card-body">
                                        <div class="row">
                                          <div class="col-lg-6">
                                            <input type="radio" id="rdoControlTotalRh" class="rdbHeader" name="gender" disabled checked><label for="male">&nbsp;Control total</label><br>
                                            <input type="radio" id="rdoNoEliminarRh" class="rdbHeader" name="gender" disabled><label for="noEliminar">&nbsp;Controlar todo excepto eliminar</label><br>
                                            <input type="radio" id="rdoVerRh" class="rdbHeader" name="gender" disabled><label for="female">&nbsp;Solo lectura</label><br>
                                          </div>
                                          <div class="col-lg-6">
                                            <input type="radio" id="rdoSinPermisosRh" class="rdbHeader" name="gender" disabled><label for="female">&nbsp;Sin permisos</label><br>
                                            <input type="radio" id="rdoPersonalizadoRh" class="rdbHeader" name="gender" disabled><label for="female">&nbsp;Personalizado</label>
                                          </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                          <div class="col-lg-2">
                                            <input type="checkbox" name="chkTodoEmpleados" class="chkRh chkEncabezado" id="chkTodoEmpleados" disabled checked>
                                            <label for="chkTodoEmpleados"> <b>Empleados</b></label>
                                            <form action="" method="post">
                                              &nbsp;<input type="checkbox" id="chkVerEmpleado" class="chkEmpleados chkRh chkRhVer" name="chkVerEmpleado chkRhVer" disabled checked>
                                              <label for="chkVerEmpleado"> Ver datos</label><br>
                                              &nbsp;<input type="checkbox" id="chkAgregarEmpleado" class="chkEmpleados chkRh" name="chkAgregarEmpleado" disabled checked>
                                              <label for="chkAgregarEmpleado"> Agregar</label><br>
                                              &nbsp;<input type="checkbox" id="chkEditarEmpleado" class="chkEmpleados chkRh" name="chkEditarEmpleado" disabled checked>
                                              <label for="chkEditarEmpleado"> Editar</label><br>
                                              &nbsp;<input type="checkbox" id="chkEliminarEmpleado" class="chkEmpleados chkRh chkRhEliminar" name="chkEliminarEmpleado" disabled checked>
                                              <label for="chkEliminarEmpleado"> Eliminar</label><br>
                                              &nbsp;<input type="checkbox" id="chkExportarEmpleado" class="chkEmpleados chkRh" name="chkExportarEmpleado" disabled checked>
                                              <label for="chkExportarEmpleado"> Exportar</label><br>
                                              &nbsp;<input type="checkbox" id="chkVacacionesEmpleado" class="chkEmpleados chkRh" name="chkVacacionesEmpleado" disabled checked>
                                              <label for="chkVacacionesEmpleado"> Vacaciones</label><br>
                                              &nbsp;<input type="checkbox" id="chkAguinaldoEmpleado" class="chkEmpleados chkRh" name="chkAguinaldoEmpleado" disabled checked>
                                              <label for="chkAguinaldoEmpleado"> Aguinaldo</label><br>
                                              &nbsp;<input type="checkbox" id="chkFiniquitoEmpleado" class="chkEmpleados chkRh" name="chkFiniquitoEmpleado" disabled checked>
                                              <label for="chkFiniquitoEmpleado"> Finiquito</label><br>
                                              &nbsp;<input type="checkbox" id="chkBajaEmpleado" class="chkEmpleados chkRh" name="chkBajaEmpleado" disabled checked>
                                              <label for="chkBajaEmpleado"> Baja</label><br>
                                            </form>
                                          </div>
                                          <div class="col-lg-2">
                                            <input type="checkbox" name="chkTodoUsuarios" class="chkRh chkEncabezado" id="chkTodoUsuarios" disabled checked>
                                            <label for="chkTodoUsuarios"> <b>Usuarios</b></label>
                                            <form action="/action_page.php">
                                              &nbsp;<input type="checkbox" class="chkUsuarios chkRh chkRhVer" name="chkVerUsuarios" disabled checked>
                                              <label for="chkVerUsuarios"> Ver datos</label><br>
                                              &nbsp;<input type="checkbox" class="chkUsuarios chkRh" name="chkAgregarUsuarios" disabled checked>
                                              <label for="chkAgregarUsuarios"> Agregar</label><br>
                                              &nbsp;<input type="checkbox" class="chkUsuarios chkRh" name="chkEditarUsuarios" disabled checked>
                                              <label for="chkEditarUsuarios"> Editar</label><br>
                                              &nbsp;<input type="checkbox" class="chkUsuarios chkRh chkRhEliminar" name="chkEliminarUsuarios" disabled checked>
                                              <label for="chkEliminarUsuarios"> Eliminar</label><br>
                                            </form>
                                          </div>
                                          <div class="col-lg-2">
                                            <input type="checkbox" name="chkTodoNomina" class="chkRh chkEncabezado" id="chkTodoNomina" disabled checked>
                                            <label for="chkTodoNomina"> <b>Nomina</b></label>
                                            <form action="/action_page.php">
                                              &nbsp;<input type="checkbox" class="chkNomina chkRh chkRhVer" name="chkVerNomina" disabled checked>
                                              <label for="chkVerNomina"> Ver datos</label><br>
                                              &nbsp;<input type="checkbox" class="chkNomina chkRh" name="chkAgregarNomina" disabled checked>
                                              <label for="chkAgregarNomina"> Agregar</label><br>
                                              &nbsp;<input type="checkbox" class="chkNomina chkRh" name="chkEditarNomina" disabled checked>
                                              <label for="chkEditarNomina"> Editar</label><br>
                                              &nbsp;<input type="checkbox" class="chkNomina chkRh chkRhEliminar" name="chkEliminarNomina" disabled checked>
                                              <label for="chkEliminarNomina"> Eliminar</label><br>
                                            </form>
                                          </div>
                                          <div class="col-lg-2">
                                            <input type="checkbox" name="chkTodoTurnos" class="chkRh chkEncabezado" id="chkTodoTurnos" disabled checked>
                                            <label for="chkTodoTurnos"> <b>Turnos</b></label>
                                            <form action="/action_page.php">
                                              &nbsp;<input type="checkbox" class="chkTurnos chkRh chkRhVer" name="chkVerTurnos" disabled checked>
                                              <label for="chkVerTurnos"> Ver datos</label><br>
                                              &nbsp;<input type="checkbox" class="chkTurnos chkRh" name="chkAgregarTurnos" disabled checked>
                                              <label for="chkAgregarTurnos"> Agregar</label><br>
                                              &nbsp;<input type="checkbox" class="chkTurnos chkRh" name="chkEditarTurnos" disabled checked>
                                              <label for="chkEditarTurnos"> Editar</label><br>
                                              &nbsp;<input type="checkbox" class="chkTurnos chkRh chkRhEliminar" name="chkEliminarTurnos" disabled checked>
                                              <label for="chkEliminarTurnos"> Eliminar</label><br>
                                            </form>
                                          </div>
                                          <div class="col-lg-2">
                                            <input type="checkbox" name="chkTodoPuestos" class="chkRh chkEncabezado" id="chkTodoPuestos" disabled checked>
                                            <label for="chkTodoPuestos"> <b>Puestos</b></label>
                                            <form action="/action_page.php">
                                              &nbsp;<input type="checkbox" class="chkPuestos chkRh chkRhVer" name="chkVerPuestos" disabled checked>
                                              <label for="chkVerPuestos"> Ver datos</label><br>
                                              &nbsp;<input type="checkbox" class="chkPuestos chkRh" name="chkAgregarPuestos" disabled checked>
                                              <label for="vehicle1"> Agregar</label><br>
                                              &nbsp;<input type="checkbox" class="chkPuestos chkRh" name="chkEditarPuestos" disabled checked>
                                              <label for="vehicle2"> Editar</label><br>
                                              &nbsp;<input type="checkbox" class="chkPuestos chkRh chkRhEliminar" name="chkEliminarPuestos" disabled checked>
                                              <label for="vehicle3"> Eliminar</label><br>
                                            </form>
                                          </div>
                                          <div class="col-lg-2">
                                            <input type="checkbox" name="chkTodoSucursales" class="chkRh chkEncabezado" id="chkTodoSucursales" disabled checked>
                                            <label for="chkTodoSucursales"> <b>Sucursales</b></label>
                                            <form action="/action_page.php">
                                              &nbsp;<input type="checkbox" class="chkSucursales chkRh chkRhVer" name="chkVerSucursales" disabled checked>
                                              <label for="chkVerSucursales"> Ver datos</label><br>
                                              &nbsp;<input type="checkbox" class="chkSucursales chkRh" name="chkAgregarPuestos" disabled checked>
                                              <label for="vehicle1"> Agregar</label><br>
                                              &nbsp;<input type="checkbox" class="chkSucursales chkRh" name="chkEditarSucursales" disabled checked>
                                              <label for="vehicle2"> Editar</label><br>
                                              &nbsp;<input type="checkbox" class="chkSucursales chkRh chkRhEliminar" name="chkEliminarSucursales" disabled checked>
                                              <label for="vehicle3"> Eliminar</label><br>
                                            </form>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <!--
                                  <div class="card">
                                    <div class="card-header title_tab" id="headingTwo">
                                      <h5 class="mb-0">
                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                          <i id="opt-group-173" class="opt-menu-icon"></i>
                                        </button>
                                        Configuración
                                      </h5>
                                    </div>
                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                      <div class="card-body">
                                        <div class="row">
                                          hola como estas ??
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  -->
                                  <div class="card">
                                    <div class="card-header title_tab" id="headingTwo">
                                      <h5 class="mb-0">
                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                          <i id="opt-group-173" class="opt-menu-icon"></i>
                                        </button>
                                        Ventas
                                      </h5>
                                    </div>
                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                      <div class="card-body">
                                        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                      </div>
                                    </div>
                                  </div>
                                  <div class="card">
                                    <div class="card-header title_tab" id="headingThree">
                                      <h5 class="mb-0">
                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                          <i id="opt-group-173" class="opt-menu-icon"></i>
                                        </button>
                                        Inventarios y productos
                                      </h5>
                                    </div>
                                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                                      <div class="card-body">
                                        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                      </div>
                                    </div>
                                  </div>
                                  <div class="card">
                                    <div class="card-header title_tab" id="headingFour">
                                      <h5 class="mb-0">
                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                          <i id="opt-group-173" class="opt-menu-icon"></i>
                                        </button>
                                        Control vehicular
                                      </h5>
                                    </div>
                                    <div id="collapseFour" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                                      <div class="card-body">
                                        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                      </div>
                                    </div>
                                  </div>
                                  <div class="card">
                                    <div class="card-header title_tab" id="headingFive">
                                      <h5 class="mb-0">
                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseThree">
                                          <i id="opt-group-173" class="opt-menu-icon"></i>
                                        </button>
                                        Textiles
                                      </h5>
                                    </div>
                                    <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordionExample">
                                      <div class="card-body">
                                        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <input type="submit" class="btnesp espAgregar float-right" name="btnGuardar" id="btnGuardar" value="Guardar">
                          </div>
                          <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            Como estas
                          </div>
                          <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                            bien gracias a Dios y tu ?
                          </div>
                        </div>
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
      <?php
        $rutaf = "../../../";
        require_once('../../../footer.php');
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

  <script>
    function refrescar(){
      //$("#alertaTareas").load('../../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUser'];?>+'&ruta='+'<?=$ruta;?>');
    }
    $(document).ready(function() {
      $("#cmbIdEmpleado").chosen();
      $('#btnGuardar').click(function(){
        var verEmpleado = $("#chkVerEmpleado").is( ':checked' );
        var agregarEmpleado = $("#chkAgregarEmpleado").is( ':checked' );// A partir de aqui
        var editarEmpleado = $("#chkEditarEmpleado").is( ':checked' );
        var eliminarEmpleado = $("#chkEliminarEmpleado").is( ':checked' );
        var exportarEmpleado = $("#chkExportarEmpleado").is( ':checked' );
        var vacacionesEmpleado = $("#chkVacacionesEmpleado").is( ':checked' );
        var aguinaldoEmpleado = $("#chkAguinaldoEmpleado").is( ':checked' );
        var finiquitoEmpleado = $("#chkFiniquitoEmpleado").is( ':checked' );
        var bajaEmpleado = $("#chkBajaEmpleado").is( ':checked' );
        var cadena = "verEmpleado="+verEmpleado+"&agregarEmpleado="+agregarEmpleado+"&agregarEmpleado="+editarEmpleado+"&eliminarEmpleado="+eliminarEmpleado+"&exportarEmpleado="+exportarEmpleado+"&vacacionesEmpleado="+vacacionesEmpleado+"&aguinaldoEmpleado="+aguinaldoEmpleado+"&finiquitoEmpleado="+finiquitoEmpleado+"&bajaEmpleado="+bajaEmpleado;
        //alert(cadena);
        $.ajax({
          url: 'cambiar_Rol.php?'+cadena,
          data: cadena,
          type: 'POST',
          success:function(){
            //window.location.href = "index.php";
            alert("exito");
          }
        })
      });
    });
  </script>
  <script> var ruta = "../../../";</script>
  <script src="../../../../js/sb-admin-2.min.js"></script>

</body>

</html>
<?php
if($redirect == 1){
    sleep(3);
    ?>
    <script>
    window.location.replace("../../index.php");
    </script>

   <?php
}
?>

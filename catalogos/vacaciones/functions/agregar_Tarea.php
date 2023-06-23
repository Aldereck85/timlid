<?php
session_start();
  if(isset($_SESSION["Usuario"])){
    require_once('../../../include/db-conn.php');

      if(isset ($_POST['btnAgregar'])){
        $proyecto = $_POST['cmbProyecto'];
        $tarea = $_POST['txtTarea'];
        $prioridad = $_POST['cmbPrioridad'];
        $estatus = $_POST['cmbEstatus'];
        $responsable = $_POST['cmbResponsables'];
        $fechaInicio = $_POST['txtFechaInicio'];
        $fechaTermino = $_POST['txtFechaTermino'];
        $asignante = $_SESSION["PKUsuario"];
        $nota = $_POST['txtNota'];
        try{
          $conn->beginTransaction();
          $stmt = $conn->prepare('INSERT INTO tareas_pendientes (Tarea,Notas, FKPrioridad,Estatus,Fecha_Inicio,Fecha_Termino,FKResponsable,FKUsuarioAsig, FKProyectos) VALUES (:tarea,:nota, :prioridad,:estatus,:fecha_Inicio,:fecha_Termino,:responsable,:asignatario, :proyecto)');
          $stmt->bindValue(':tarea',$tarea);
          $stmt->bindValue(':nota',$nota);
          $stmt->bindValue(':prioridad',$prioridad);
          $stmt->bindValue(':estatus',$estatus);
          $stmt->bindValue(':fecha_Inicio',$fechaInicio);
          $stmt->bindValue(':fecha_Termino',$fechaTermino);
          $stmt->bindValue(':responsable',$responsable);
          $stmt->bindValue(':asignatario',$asignante);
          $stmt->bindValue(':proyecto',$proyecto);
          $stmt->execute();

          $fktarea = $conn->lastInsertId();

          $x = 0;

          if(count($_POST['subtarea']) > 0){
            foreach($_POST['subtarea'] as $subtarea){
              $stmt = $conn->prepare('INSERT INTO subtareas (IDSubtarea, Subtarea, FKUsuario, FKTarea) VALUES (:idsubtarea, :subtarea, :fkusuario , :fktarea)');
              $stmt->bindValue(':idsubtarea',$x+1);
              $stmt->bindValue(':subtarea',$subtarea);
              $stmt->bindValue(':fkusuario',$_POST['usuarios'][$x]);
              $stmt->bindValue(':fktarea',$fktarea);
              $stmt->execute();
              $x++;
            }
          }

          $conn->commit();

          header('Location:../index.php');
        }catch(PDOException $ex){
          echo $ex->getMessage();
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

  <title>Timlid | Agregar tarea</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../../js/validaciones.js"></script>
  <script src="../../../js/tareas.js"></script>


  <!-- Custom scripts for all pages-->
  <script src="../../../js/sb-admin-2.min.js"></script>

  <script src="../../../js/bootstrap-clockpicker.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha256-c4gVE6fn+JRKMRvqjoDp+tlG4laudNYrXI1GncbfAYY=" crossorigin="anonymous"></script>

  <!-- Custom fonts for this template-->
  <link href="../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css"/>
  <link href="../../../css/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css"/>
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../../../css/sb-admin-2.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" integrity="sha256-EH/CzgoJbNED+gZgymswsIOrM9XhIbdSJ6Hwro09WE4=" crossorigin="anonymous" />
  <link href="../../../css/chosen.css" rel="stylesheet" type="text/css">

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
            <?php
              $rutes = "../";
              //require_once('../../alerta_Tareas_Nuevas.php');
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
                <a class="dropdown-item" href="../../perfil">
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
            <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-tasks"></i>  Agregar tarea</h1>
          </div>


          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header">
                  Tarjeta de tareas
                </div>
                <div class="card-body">
                    <div class="row">
                      <div class="col-lg-12">
                        <form action="" method="post">
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-12">
                                <label for="usr">Proyecto:</label>
                                <select name="cmbProyecto" id="cmbProyecto" class="form-control" required>
                                    <option value="">Elegir opción</option>
                                        <?php
                                            if($_SESSION["FKRol"] == 1 || $_SESSION["FKRol"] == 4){
                                              $stmt = $conn->prepare('SELECT PKProyectos, Nombre_proyecto FROM proyectos');
                                              $stmt->execute();
                                            }
                                            else{
                                              $stmt = $conn->prepare('SELECT PKProyectos, Nombre_proyecto FROM proyectos WHERE FKUsuario = ?');
                                              $stmt->execute(array($_SESSION['PKUsuario']));
                                            }

                                        ?>
                                        <?php foreach($stmt as $option) : ?>
                                             <option value="<?php echo $option['PKProyectos']; ?>"><?php echo $option['Nombre_proyecto']; ?></option>
                                        <?php endforeach; ?>
                                </select>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-6">
                                <label for="usr">Tarea:</label>
                                <input type="text" class="form-control alpha-only" maxlength="25" name="txtTarea" required>
                              </div>
                              <div class="col-lg-6">
                                <label for="usr">Prioridad:</label>
                                <select name="cmbPrioridad" id="cmbPrioridad" class="form-control" required>
                                    <option value="">Elegir opción</option>
                                        <?php
                                            $stmt = $conn->prepare('SELECT * FROM prioridades');
                                            $stmt->execute();
                                        ?>
                                        <?php foreach($stmt as $option) : ?>
                                             <option value="<?php echo $option['PKPrioridad']; ?>"><?php echo $option['Prioridad']; ?></option>
                                        <?php endforeach; ?>
                                </select>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-6">
                                <label for="usr">Estatus:</label>
                                <select name="cmbEstatus" id="cmbEstatus" class="form-control" required>
                                    <option value="">Elegir opción</option>
                                    <option value="1">Por comenzar</option>
                                    <option value="2">En proceso</option>
                                    <option value="3">Terminado</option>
                                    <option value="4">No realizado</option>
                                </select>
                              </div>
                              <div class="col-lg-6">
                                <label for="usr">Responsable:</label>
                                <select name="cmbResponsables" id="cmbResponsables" class="form-control" required>
                                    <option value="">Elegir opción</option>
                                        <?php
                                            $stmt = $conn->prepare('SELECT * FROM usuarios INNER JOIN empleados ON FKEmpleado = PKEmpleado');
                                            $stmt->execute();
                                        ?>
                                        <?php foreach($stmt as $option) : ?>
                                             <option value="<?php echo $option['PKUsuario']; ?>"><?php echo $option['Primer_Nombre']." ".$option['Segundo_Nombre']." ".$option['Apellido_Paterno']." ".$option['Apellido_Materno']; ?></option>
                                        <?php endforeach; ?>
                                </select>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-6">
                                <label for="usr">Fecha de inicio:</label>
                                <input type="date" name="txtFechaInicio" class="form-control" step="1"  required>
                              </div>
                              <div class="col-lg-6">
                                <label for="usr">Fecha de termino:</label>
                                <input type="date" name="txtFechaTermino" class="form-control" step="1"  required>
                              </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                  <label for="usr">Nota:</label>
                                  <input type="text" class="form-control alpha-only" maxlength="70" name="txtNota">
                                </div>
                              </div>
                              <br>
                              <div class="row">
                                <div class="col-lg-12">
                                  <label for="usr">Subtarea:</label>
                                  <input type="text" class="form-control" maxlength="100" name="txtSubtarea" id="txtSubtarea">
                                  <div id="mostrarSubtarea" style="color:#d9534f;display:none;">Ingresa la subtarea.</div>
                                </div>
                              </div>
                              <div class="row">
                                  <div class="col-lg-9">
                                    <label for="usr">Responsable subtarea:</label>
                                    <select name="cmbResponsablesSub" id="cmbResponsablesSub" class="form-control">
                                        <option value="">Elegir opción</option>
                                            <?php
                                                $stmt = $conn->prepare('SELECT * FROM usuarios INNER JOIN empleados ON FKEmpleado = PKEmpleado');
                                                $stmt->execute();
                                            ?>
                                            <?php foreach($stmt as $option) : ?>
                                                 <option value="<?php echo $option['PKUsuario']; ?>"><?php echo $option['Primer_Nombre']." ".$option['Segundo_Nombre']." ".$option['Apellido_Paterno']." ".$option['Apellido_Materno']; ?></option>
                                            <?php endforeach; ?>
                                    </select>
                                    <div id="mostrarResponsableSubtarea" style="color:#d9534f;display:none;">Ingresa el responsable de la subtarea.</div>
                                  </div>
                                  <div class="col-lg-3">
                                    <button type="button" class="btn btn-info justify-content-center" id="agregarSubtarea" name="btnAgregar" style="position: relative;top:45%;width: 100%">Agregar subtarea</button>
                                  </div>
                              </div>


                              <br>
                              <div class="card-header">
                                Subtareas
                              </div>
                              <br>
                              <div class="row">
                                <div class="col-lg-7">
                                  <label class="d-flex justify-content-center">Descripción</label>
                                </div>
                                <div class="col-lg-4">
                                  <label class="d-flex justify-content-center">Responsable</label>
                                </div>
                                <div class="col-lg-1">
                                  <label class="d-flex justify-content-center">Acciones</label>
                                </div>
                              </div>
                              <div id="lstProductos">
                              </div>
                          </div>

                          <br>
                            <button type="submit" class="btn btn-success float-right" onclick="refrescar();" id="notification-icon" name="btnAgregar">Guardar</button>
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
          <a class="btn btn-primary" href="../../logout.php">Salir</a>
        </div>
      </div>
    </div>
  </div>
  <script>
      $( document ).ready(function() {
        $("#cmbProyecto").chosen();
      });
  </script>
  <script>
    var id_elemento = 1;
    $(document).ready(function(){
      $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
        setInterval(refrescar, 5000);
      });
    function refrescar(){
      $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
    }

   $("#agregarSubtarea").click(function(){
   		var subtarea = $("#txtSubtarea").val();
      var responsable_sub = $("#cmbResponsablesSub").children("option:selected").text();
      var responsable_sub_id = $("#cmbResponsablesSub").children("option:selected").val();

    	if(subtarea === ''){
    		$("#mostrarSubtarea").css("display","flex");
    		setTimeout(
	          function()
	          {
	            $("#mostrarSubtarea").css("display", "none");
	          }, 2000);
    		return;
    	}

      if(responsable_sub_id === ''){
        $("#mostrarResponsableSubtarea").css("display","flex");
        setTimeout(
            function()
            {
              $("#mostrarResponsableSubtarea").css("display", "none");
            }, 2000);
        return;
      }

      var nuevo_elemento = "<div class='row' style='padding-bottom:10px;'  id='div_" + id_elemento + "'><div class='col-lg-7'>" + subtarea + "</div><div class='col-lg-4'>" + responsable_sub + "</div><div class='col-lg-1'><input type='hidden' value='" + subtarea + "' name='subtarea[]' ><input type='hidden' value='" + responsable_sub_id + "' name='usuarios[]' ><a href='#' class='btn btn-danger d-flex justify-content-center eliminar' id='" + id_elemento + "' >Eliminar</a></div></div>"
    	$('#lstProductos').append(nuevo_elemento);
      id_elemento++;
      $("#txtSubtarea").val('');
      $("#cmbResponsablesSub").val('');

   });

   $(document).on('click','.eliminar',function () {
      var delete_id = $(this).prop('id');
      $( "#div_" + delete_id ).remove();
   });
  </script>
  <script> var ruta = "../../";</script>
  <script src="../../../js/sb-admin-2.min.js"></script>

</body>

</html>

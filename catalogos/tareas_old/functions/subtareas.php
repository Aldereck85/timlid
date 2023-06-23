<?php
session_start();

  if(isset($_SESSION["Usuario"])){
    require_once('../../../include/db-conn.php');

    if(isset($_GET['id'])){
        $id =  $_GET['id'];
        $idusuario = $_SESSION['PKUsuario'];
        $stmt = $conn->prepare('SELECT * FROM tareas_pendientes WHERE PKTarea= :id');
        $stmt->execute(array(':id'=>$id));
        $row = $stmt->fetch();
        $proyecto = $row['FKProyectos'];
        $tarea = $row['Tarea'];
        $prioridad = $row['FKPrioridad'];
        $estatus = $row['Estatus'];
        $responsable = $row['FKResponsable'];
        $fechaInicio = $row['Fecha_Inicio'];
        $fechaTermino = $row['Fecha_Termino'];
        $nota = $row['Notas'];
        $porcentaje = $row['Porcentaje_Avance'];
    }
    else{
      header('Location:../../dashboard.php');
    }


    $stmt = $conn->prepare('SELECT s.IDSubtarea, s.Subtarea, s.Estatus, s.FKUsuario, s.FKTarea, e.Primer_Nombre, e.Segundo_Nombre, e.Apellido_Paterno, e.Apellido_Materno FROM subtareas AS s LEFT JOIN usuarios as u ON u.PKUsuario = s.FKUsuario LEFT JOIN empleados as e ON e.PKEmpleado = u.FKEmpleado WHERE s.FKTarea= :id AND s.FKUsuario = :idusuario');
    $stmt->execute(array(':id'=>$id,':idusuario'=>$idusuario));
    $row_subtareas = $stmt->fetchAll();


      if(isset ($_POST['btnEditar'])){

        try{
          $conn->beginTransaction();

          $x = 0;

          foreach ($row_subtareas as $rs) {

              if(in_array($rs['IDSubtarea'], $_POST['tareacumplida'])){

                $stmt = $conn->prepare('UPDATE subtareas set Estatus = 1 WHERE IDSubtarea = :id AND FKTarea = :idtarea');
                $stmt->bindValue(':id',$rs['IDSubtarea']);
                $stmt->bindValue(':idtarea',$rs['FKTarea']);
                $stmt->execute();
              }
              $x++;
          }

          $stmt = $conn->prepare('SELECT count(subtarea) AS cuenta FROM subtareas WHERE FKTarea = :id ');
          $stmt->execute(array(':id'=>$id));
          $row_cuenta = $stmt->fetch();
          $numero_subtareas = $row_cuenta['cuenta'];

          $stmt = $conn->prepare('SELECT count(subtarea) AS cuenta FROM subtareas WHERE FKTarea = :id AND Estatus = 1');
          $stmt->execute(array(':id'=>$id));
          $row_cuenta_subtareas = $stmt->fetch();
          $numero_subtareas_cumplidas = $row_cuenta_subtareas['cuenta'];

          $porcentaje = ($numero_subtareas_cumplidas / $numero_subtareas) * 100;
          $stmt = $conn->prepare('UPDATE tareas_pendientes set Porcentaje_Avance = :porcentaje WHERE PKTarea = :idtarea');
          $stmt->bindValue(':porcentaje',$porcentaje);
          $stmt->bindValue(':idtarea',$row_subtareas[0]['FKTarea']);
          $stmt->execute();

          $conn->commit();
          header('Location:../tareas_pendientes.php');
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

  <title>Timlid | Tareas a realizar</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../js/validaciones.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../../js/sb-admin-2.min.js"></script>
  <script src="../../../js/bootstrap-clockpicker.min.js"></script>

  <!-- Custom fonts for this template-->
  <link href="../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../css/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../../../css/sb-admin-2.css" rel="stylesheet">


  <style type="text/css">
    input[type="checkbox"][readonly] {
      pointer-events: none;
    }
  </style>

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
      <?php
      $ruta = "../../";
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
            <h1 class="h3 mb-0 text-gray-800">Subtareas a realizar</h1>
          </div>


          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header">
                  Tarjeta de subtareas
                </div>
                <div class="card-body">
                    <div class="row">
                      <div class="col-lg-12">
                        <h3><?=$tarea?></h3>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-12">
                        <form action="" method="post">
                          <div class="form-group">
                              <div class="card-header">
                                Subtareas
                              </div>
                              <br>
                              <div class="row">
                                <div class="col-lg-10">
                                  <label class="d-flex justify-content-center">Descripción</label>
                                </div>
                                <div class="col-lg-2">
                                  <label class="d-flex justify-content-center">Seleccionar</label>
                                </div>
                              </div>
                              <div id="lstProductos">
                                  <?php
                                      $id_elemento = 1;
                                      foreach ($row_subtareas as $rs) {
                                        echo "<div class='row' style='padding-bottom:10px;'  id='div_".$id_elemento."'>
                                                  <div class='col-lg-10'>".$rs['Subtarea']."</div>
                                                  <div class='col-lg-2 text-center'>
                                                      <input type='checkbox' name='tareacumplida[]' value='".$rs['IDSubtarea']."'";

                                            if($rs['Estatus'] == 1){
                                              echo "checked readonly";
                                            }

                                        echo ">
                                                  </div>
                                              </div>";
                                        $id_elemento++;
                                      }
                                  ?>

                              </div>
                          </div>
                          <input type="hidden" name="txtId" value="<?=$id;?>">
                          <button type="submit" class="btn btn-primary float-right" name="btnEditar">Editar</button>
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
    var id_elemento = <?=$id_elemento?>;
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

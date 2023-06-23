<?php
session_start();

  if(isset($_SESSION["Usuario"])){
    require_once('../../../include/db-conn.php');
      if(isset ($_POST['btnEditar'])){
        $id = (int) $_POST['txtId'];
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
        $stmt = $conn->prepare('UPDATE tareas_pendientes set Tarea= :tarea, Notas = :nota, FKPrioridad = :prioridad,Estatus = :estatus,Fecha_Inicio = :fecha_Inicio,Fecha_Termino = :fecha_Termino, FKResponsable = :responsable, FKUsuarioAsig = :asignatario, FKProyectos = :proyecto WHERE PKTarea = :id');
        $stmt->bindValue(':tarea',$tarea);
        $stmt->bindValue(':nota',$nota);
        $stmt->bindValue(':prioridad',$prioridad);
        $stmt->bindValue(':estatus',$estatus);
        $stmt->bindValue(':fecha_Inicio',$fechaInicio);
        $stmt->bindValue(':fecha_Termino',$fechaTermino);
        $stmt->bindValue(':responsable',$responsable);
        $stmt->bindValue(':asignatario',$asignante);
        $stmt->bindValue(':proyecto',$proyecto);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $stmt = $conn->prepare('DELETE FROM subtareas WHERE FKTarea = '.$id);
        $stmt->execute();

        $x = 0;

          if(count($_POST['subtarea']) > 0){
            foreach($_POST['subtarea'] as $subtarea){
              $stmt = $conn->prepare('INSERT INTO subtareas (IDSubtarea, Subtarea, FKUsuario, Estatus, FKTarea) VALUES (:idsubtarea, :subtarea, :fkusuario , :estatus, :fktarea)');
              $stmt->bindValue(':idsubtarea',$x+1);
              $stmt->bindValue(':subtarea',$subtarea);
              $stmt->bindValue(':fkusuario',$_POST['usuarios'][$x]);
              $stmt->bindValue(':estatus',$_POST['estatus'][$x]);
              $stmt->bindValue(':fktarea',$id);
              $stmt->execute();
              $x++;
            }
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
          $stmt->bindValue(':idtarea',$id);
          $stmt->execute();

          $conn->commit();
          header('Location:../index.php');
        }catch(PDOException $ex){
          echo $ex->getMessage();
        }
      }

      if(isset($_POST['idTareaU'])){
        $id =  $_POST['idTareaU'];
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
        //$sueldo = $row['Sueldo_semanal'];
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

  <title>Timlid | Editar tarea</title>

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
            <h1 class="h3 mb-0 text-gray-800">Editar tarea</h1>
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
                                               <option value="<?php echo $option['PKProyectos']; ?>"
                                                <?php if($option['PKProyectos'] == $proyecto) echo "selected"; ?>
                                                ><?php echo $option['Nombre_proyecto']; ?></option>
                                          <?php endforeach; ?>
                                  </select>
                                </div>
                              </div>
                            <div class="row">
                              <div class="col-lg-6">
                                <label for="usr">Tarea:</label>
                                <input type="text" class="form-control alpha-only" maxlength="25" name="txtTarea" value="<?=$tarea;?>" required>
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
                                             <option value="<?php echo $option['PKPrioridad']; ?>" <?php if ($prioridad == $option['PKPrioridad'] ) echo 'selected'; ?>><?php echo $option['Prioridad']; ?></option>
                                        <?php endforeach; ?>
                                </select>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-6">
                                <label for="usr">Estatus:</label>
                                <select name="cmbEstatus" id="cmbEstatus" class="form-control" required>
                                    <option value="">Elegir opción</option>
                                    <option value="1" <?php if ($estatus == 1 ) echo 'selected'; ?>>Por comenzar</option>
                                    <option value="2" <?php if ($estatus == 2 ) echo 'selected'; ?>>En proceso</option>
                                    <option value="3" <?php if ($estatus == 3 ) echo 'selected'; ?>>Terminado</option>
                                    <option value="4" <?php if ($estatus == 4 ) echo 'selected'; ?>>No realizado</option>
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
                                             <option value="<?php echo $option['PKUsuario']; ?>" <?php if ($responsable == $option['PKUsuario'] ) echo 'selected'; ?>><?php echo $option['Primer_Nombre']." ".$option['Segundo_Nombre']." ".$option['Apellido_Paterno']." ".$option['Apellido_Materno']; ?></option>
                                        <?php endforeach; ?>
                                </select>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-6">
                                <label for="usr">Fecha de inicio:</label>
                                <input type="date" name="txtFechaInicio" class="form-control" step="1"  value="<?=$fechaInicio;?>" required>
                              </div>
                              <div class="col-lg-6">
                                <label for="usr">Fecha de termino:</label>
                                <input type="date" name="txtFechaTermino" class="form-control" step="1"  value="<?=$fechaTermino;?>" required>
                              </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                  <label for="usr">Nota:</label>
                                  <input type="text" class="form-control alpha-only" maxlength="70" name="txtNota" value="<?=$nota?>">
                                </div>
                                <div class="col-lg-6">
                                  <label for="usr">Porcentaje:</label>
                                  <input type="text" class="form-control numeric-only" maxlength="3" name="txtPorcentaje" value="<?=$porcentaje?>" readonly>
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
                                  <?php
                                      $stmt = $conn->prepare('SELECT s.Subtarea, s.Estatus, s.FKUsuario, e.Primer_Nombre, e.Segundo_Nombre, e.Apellido_Paterno, e.Apellido_Materno FROM subtareas AS s LEFT JOIN usuarios as u ON u.PKUsuario = s.FKUsuario LEFT JOIN empleados as e ON e.PKEmpleado = u.FKEmpleado WHERE FKTarea= :id');
                                      $stmt->execute(array(':id'=>$id));
                                      $row_subtareas = $stmt->fetchAll();

                                      $id_elemento = 1;
                                      foreach ($row_subtareas as $rs) {
                                        echo "<div class='row' style='padding-bottom:10px;'  id='div_".$id_elemento."'>
                                                  <div class='col-lg-7'>".$rs['Subtarea']."</div>
                                                  <div class='col-lg-4'>".$rs['Primer_Nombre']." ".$rs['Segundo_Nombre']." ".$rs['Apellido_Paterno']." ".$rs['Apellido_Materno']."</div>
                                                  <div class='col-lg-1'>
                                                      <input type='hidden' value='".$rs['Subtarea']."' name='subtarea[]' >
                                                      <input type='hidden' value='".$rs['FKUsuario']."' name='usuarios[]' >
                                                      <input type='hidden' value='".$rs['Estatus']."' name='estatus[]' >
                                                      <a href='#' class='btn btn-danger d-flex justify-content-center eliminar' id='".$id_elemento."' >Eliminar</a>
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

      var nuevo_elemento = "<div class='row' style='padding-bottom:10px;'  id='div_" + id_elemento + "'><div class='col-lg-7'>" + subtarea + "</div><div class='col-lg-4'>" + responsable_sub + "</div><div class='col-lg-1'><input type='hidden' value='" + subtarea + "' name='subtarea[]' ><input type='hidden' value='" + responsable_sub_id + "' name='usuarios[]' ><input type='hidden' value='0' name='estatus[]' ><a href='#' class='btn btn-danger d-flex justify-content-center eliminar' id='" + id_elemento + "' >Eliminar</a></div></div>"
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

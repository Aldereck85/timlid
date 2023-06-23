<?php
session_start();

  if(isset($_SESSION["Usuario"])){
    $user = $_SESSION["Usuario"];
    require_once('../../include/db-conn.php');
  }else {
    header("location:../dashboard.php");
  }

  if(isset($_GET["id"])){
    $id = $_GET["id"];
    $stmt = $conn->prepare("SELECT p.Proyecto, p.FKResponsable
                              FROM proyectos as p
                                LEFT JOIN usuarios as u ON u.PKUsuario = p.FKResponsable
                                LEFT JOIN empleados as e ON e.PKEmpleado = u.FKEmpleado
                                  WHERE PKProyecto= :id");
    $stmt->execute(array(':id'=>$id));
    $row = $stmt->fetch();
    $proyecto = $row['Proyecto'];
    $idUsuario = $row['FKResponsable'];
  }
  else{
    $id = 0;
  }
  if(isset($_POST['id'])){
    $id =  $_POST['id'];
    $stmt = $conn->prepare("SELECT p.PKProyecto, p.Proyecto, p.FKResponsable
                              FROM proyectos as p
                                LEFT JOIN usuarios as u ON u.PKUsuario = p.FKResponsable
                                LEFT JOIN empleados as e ON e.PKEmpleado = u.FKEmpleado
                                  WHERE PKProyecto= :id");
    $stmt->execute(array(':id'=>$id));
    $row = $stmt->fetch();
    $proyecto = $row['Proyecto'];
    $idUsuario = $row['FKResponsable'];
    
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

  <title>Timlid | Tareas</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/mdb.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha256-c4gVE6fn+JRKMRvqjoDp+tlG4laudNYrXI1GncbfAYY=" crossorigin="anonymous"></script>
  
  

  <!-- Page level plugins -->
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.responsive.js"></script>
  <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="../../js/slimselect.min.js"></script>

  <!-- Page level custom scripts
  <script src="js/demo/datatables-demo.js"></script>
  -->

  <!-- Custom fonts for this template -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" integrity="sha256-EH/CzgoJbNED+gZgymswsIOrM9XhIbdSJ6Hwro09WE4=" crossorigin="anonymous" />
  <link href="../../css/chosen.css" rel="stylesheet" type="text/css">
  

  <!-- Custom styles for this template -->
  <link href="../../css/mdb.min.css" rel="stylesheet">
  <link href="../../css/sb-admin-2.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet"></link>

  <!-- Custom styles for this page -->
  <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <script>
    var id = <?=$id?>;
    var rol = <?=$_SESSION['FKRol']?>;
    $(document).ready(function(){
      var idioma_espanol = {
          "sProcessing":     "Procesando...",
          "sLengthMenu":     "Mostrar _MENU_ registros",
          "sZeroRecords":    "No se encontraron resultados",
          "sEmptyTable":     "Ningún dato disponible en esta tabla",
          "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
          "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
          "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
          "sInfoPostFix":    "",
          "sSearch":         "Buscar:",
          "sUrl":            "",
          "sInfoThousands":  ",",
          "sLoadingRecords": "Cargando...",
          "oPaginate": {
              "sFirst":    "Primero",
              "sLast":     "Último",
              "sNext":     "Siguiente",
              "sPrevious": "Anterior"
          },
          "oAria": {
              "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
              "sSortDescending": ": Activar para ordenar la columna de manera descendente"
          }
      }

      if(rol == 1 || rol == 4){
      $("#tblPuestos").dataTable(
      {
        "ajax":"functions/function_Tareas.php?id="+id,
          "columns":[
            {"data":"Cambiar estatus"},
            {"data":"Tarea"},
            {"data":"Prioridad"},
            {"data":"Estatus"},
            {"data":"Fecha de inicio"},
            {"data":"Fecha de termino"},
            {"data":"Responsable"},
            {"data":"Asignante"},
            {"data":"Nota"},
            {"data":"Porcentaje"},
            {"data":"Acciones"}
          ],
          "language": idioma_espanol,
            columnDefs: [
              { orderable: false, targets: 10 },
              { "width": "4%", "targets": 0 },
              { "width": "14%", "targets": 10 }
            ],
            responsive: true
      }
      )
    }else{
      $("#tblPuestos").dataTable(
      {

        "ajax":"functions/function_Tareas.php?id="+id,
          "columns":[
            {"data":"Cambiar estatus"},
            {"data":"Tarea"},
            {"data":"Prioridad"},
            {"data":"Estatus"},
            {"data":"Fecha de inicio"},
            {"data":"Fecha de termino"},
            {"data":"Asignante"},
            {"data":"Nota"},
            {"data":"Porcentaje"},
            {"data":"Acciones"}
          ],
          "language": idioma_espanol,
            columnDefs: [
              { orderable: false, targets: 7 }
            ],
            responsive: true
      }
      )
    }
    });
  </script>
</style>
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
      <?php
        $ruta = "../";
        require_once('../menu3.php');
        $rutes = "../";
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
          <h1 class="h3 mb-2 text-gray-800"><i class="fas fa-tasks"></i> Tareas</h1>
          <p class="mb-4">Trabajos o actividades pendientes</p>

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <?php
                if($id > 0){
                    $stmt = $conn->prepare('SELECT Proyecto FROM proyectos WHERE PKProyecto= :id');
                    $stmt->execute(array(':id'=>$id));
                    $row = $stmt->fetch();
                    $nombre_proyecto = $row['Proyecto'];
                    echo "<h4>".$nombre_proyecto."</h4>";
                }
              ?>
              <a href="functions/agregar_Tarea.php" class="btn btn-success float-right" ><i class="fas fa-plus"></i> Agregar tarea </a>
              <a href="functions/agregar_Tarea.php" class="btn btn-primary float-right" style="position:relative;right:10px;" data-toggle="modal" data-target="#agregar_Proyecto" onclick="cambiarTamanio();"><i class="fas fa-project-diagram"></i> Agregar proyecto </a>
              <a href="tareas_pendientes.php" class="btn btn-info float-right" style="position:relative;right:20px;"><i class="fas fa-clipboard-list"></i> Tareas pendientes</a>
              <a href="" class="btn btn-primary float-right" style="position:relative;right:30px;"  data-toggle="modal" data-target="#modalEditar" ><i class="fas fa-edit"></i> Editar</a>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table stripe" id="tblPuestos" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Cambiar estatus</th>
                      <th>Tarea</th>
                      <th>Prioridad</th>
                      <th>Estatus</th>
                      <th>Fecha de inicio</th>
                      <th>Fecha de termino</th>
                      <?php if($_SESSION['FKRol'] == 1 || $_SESSION['FKRol'] == 4){
                        echo '<th>Responsable</th>';
                      }
                      ?>
                      <th>Asignante</th>
                      <th>Nota</th>
                      <th>Avance</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Cambiar estatus</th>
                      <th>Tarea</th>
                      <th>Prioridad</th>
                      <th>Estatus</th>
                      <th>Fecha de inicio</th>
                      <th>Fecha de termino</th>
                      <?php if($_SESSION['FKRol'] == 1 || $_SESSION['FKRol'] == 4){
                        echo '<th>Responsable</th>';
                      }
                      ?>
                      <th>Asignante</th>
                      <th>Nota</th>
                      <th>Avance</th>
                      <th>Acciones</th>
                    </tr>
                  </tfoot>
                  <tbody>
                  </tbody>
                </table>
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

  <div class="modal fade" id="eliminarModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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

  <!-- Delete Modal mis tareas -->
  <div id="eliminar_Tarea" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="functions/eliminar_Tarea.php" method="POST">
          <input type="hidden" name="idTareaD" id="idTareaD">
          <div class="modal-header">
            <h4 class="modal-title">Eliminar tarea</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <p>¿Está seguro de realizar esta acción?</p>
            <p class="text-warning"><small>Esta acción es irreversible.</small></p>
          </div>
          <div class="modal-footer">
            <input type="button" class="btn btn-secondary" data-dismiss="modal" value="Cancelar">
            <input type="submit" class="btn btn-danger" value="Eliminar">
          </div>
        </form>
      </div>
    </div>
  </div>
  
  
  <!-- Update Modal mis tareas -->
  <div id="editar_Tarea" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="functions/editar_Tarea.php" method="POST">
          <input type="hidden" name="idTareaU" id="idTareaU">
          <div class="modal-header">
            <h4 class="modal-title">Editar tarea</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <p>¿Está seguro de realizar esta acción?</p>
            <p class="text-warning"><small>Esta acción cambiará los datos del registro.</small></p>
          </div>
          <div class="modal-footer">
            <input type="button" class="btn btn-secondary" data-dismiss="modal" value="Cancelar">
            <input type="submit" class="btn btn-primary" value="Editar">
          </div>
        </form>
      </div>
    </div>
  </div>

<!-- Button trigger modal -->
<!-- Full Height Modal Right -->
<div class="modal fade right" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">

  
  <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">


    <div class="modal-content">
      <form action="../proyectos/functions/editar_Proyecto.php" method="POST">
        <input type="hidden" name="idProyectoU" value="<?=$id;?>">
        <div class="modal-header">
          <h4 class="modal-title w-100" id="myModalLabel">Editar Proyecto</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          
              <div class="form-group">
                  <label for="usr">Nombre del proyecto:</label>
                  <input type="text" class="form-control alpha-only" value="<?=$proyecto;?>" maxlength="50" name="txtProyectoU">
              </div>

              <div class="form-group">
                  <label for="usr">Encargado del proyecto:</label>
                  <select name="cmbIdUsuarioU" id="cmbIdUsuarioU"  class="form-control" required>
                    <option value="" disabled selected hidden>Seleccione un encargado</option>
                    <?php
                      $stmt = $conn->prepare("SELECT u.PKUsuario, CONCAT(e.Primer_Nombre,' ',e.Segundo_Nombre,' ',e.Apellido_Paterno,' ',e.Apellido_Materno) as nombre_empleado
                      FROM usuarios as u INNER JOIN empleados as e ON u.FKEmpleado = e.PKEmpleado");
                      $stmt->execute();
                      while($row = $stmt->fetch()){
                      ?>
                        <option value="<?=$row['PKUsuario'];?>" <?php if($row['PKUsuario'] == $idUsuario) echo 'selected'; ?>><?=$row['nombre_empleado'];?></option>
                      <?php  }?>          
                  </select>
              </div>
              <div class="form-group">
                  <label for="usr">Equipos participantes en el proyecto:</label>
                  <select name="cmbIdEquipoU[]" id="multipleU"  multiple>
                    <!--<option value="">Elegir usuarios o equipos</option>-->
                    <!--Consultar en DB Nombre de proyecto, Nombre de responsable, Equipo asignados-->
                      <?php
                        $bandera = false;
                        $stack = array();
                        $id = $_GET['id'];
                        $stmt2 = $conn->prepare("SELECT FKEquipo FROM equipos_por_proyecto  WHERE FKProyecto = :id");
                        $stmt2->execute(array(':id'=>$id));
                        
                        while (($row = $stmt2->fetch()) !== false) {
                            array_push($stack, $row['FKEquipo']);
                        }

                        $stmt = $conn->prepare("SELECT PKEquipo, Nombre_Equipo FROM equipos");
                        $stmt->execute();
                
                        while (($row2 = $stmt->fetch()) !== false) {//
                            foreach ($stack as $value) {
                                if($value == $row2['PKEquipo']){
                                    $bandera = true;
                                    break;
                                }else{
                                    $bandera  = false;
                                }
                            }
                            if($bandera == true){
                                echo "<option value='".$row2['PKEquipo']."' selected>".$row2['Nombre_Equipo']."</option>";
                            }else{
                                echo "<option value='".$row2['PKEquipo']."'>".$row2['Nombre_Equipo']."</option>";
                                
                            }
                            
                        }
                    
                      ?>
                  </select>
                </div>

              

          
        </div>
        <div class="modal-footer justify-content-center">   
          
          <!--<a class="btn btn-danger" href="../proyectos/functions/eliminar_Proyecto.php?idProyectoD=<?=$id;?>"  name="idProyectoD" ><i class="fas fa-trash-alt"></i> Eliminar Proyecto</a>-->
          
          <a class="btn btn-danger" href="#"  name="btnEliminarProyecto" id="btnEliminarProyecto" data-toggle="modal" data-target="#eliminar_Proyecto_Conf" ><i class="fas fa-trash-alt"></i> Eliminar Proyecto</a>
          <input type="button" class="btn btn-secondary" data-dismiss="modal" value="Cancelar">
          <input type="submit" class="btn btn-primary float-right" name="btnEditar" id="btnEditar" value="Guardar">
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Full Height Modal Right -->
<!-- Delete Modal Proyectos -->
<div id="eliminar_Proyecto_Conf" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="../proyectos/functions/eliminar_Proyecto.php?idProyectoD=<?=$id;?>" method="POST">
          <input type="hidden" name="idProyectoD" id="idProyectoD">
          <div class="modal-header">
            <h4 class="modal-title">Eliminar proyecto</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <p>¿Está seguro de realizar esta acción?</p>
            <p class="text-warning"><small>Esta acción es irreversible.</small></p>
          </div>
          <div class="modal-footer">
            <input type="button" class="btn btn-secondary" data-dismiss="modal" value="Cancelar">
            <input type="submit" class="btn btn-danger"  value="Eliminar"> 
          </div>
        </form>
      </div>
    </div>
  </div>

<!--End Editar Proyecto with Fluid Modal-->

  <!-- Agregar proyecto-->
  <div id="agregar_Proyecto" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="" method="POST" id="frProyecto">
          <div class="modal-header">
            <h4 class="modal-title">Editar tarea</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>

              <div class="row">
                <div class="col-lg-8" style="text-align: center;position:relative;left: 16%;">
                  <label for="usr">Nombre del proyecto:</label>
                  <input type="text" class="form-control alpha-only" maxlength="50" name="txtProyecto" id="txtProyecto" required>
                </div>
              </div>
               <br>
              <div class="row">
                <div class="col-lg-8" style="text-align: center;position:relative;left: 16%;">
                  <label for="usr">Encargado del proyecto:</label>
                  <br>
                  <select name="cmbIdUsuario" id="cmbIdUsuario" class="form-control" required>
                    <option value="">Elegir opción</option>
                      <?php

                        $stmt = $conn->prepare("SELECT u.PKUsuario, CONCAT(e.Primer_Nombre,' ',e.Segundo_Nombre,' ',e.Apellido_Paterno,' ',e.Apellido_Materno) as nombre_empleado
                        FROM usuarios as u INNER JOIN empleados as e ON u.FKEmpleado = e.PKEmpleado");
                        $stmt->execute();
                        $row = $stmt->fetchAll();

                        if(count($row) > 0){
                            foreach($row as $r)
                              echo '<option value="'.$r['PKUsuario'].'">'.$r['nombre_empleado'].'</option>';
                        }
                        else{
                            echo '<option value="" disabled>No hay usuarios para mostrar.</option>';
                        }

                      ?>
                  </select>

                  <div id="mostrarUsuario" style="display:none;color:#d9534f;">Ingresa el usuario.</div>
                </div>
              </div>
              <br>
              <div id="mostrarErrorProyecto" style="display:none;color:#d9534f;text-align:center;">Ocurrio un error, no se dio de alta el proyecto.</div>
              <div id="mostrarProyecto" style="display:none;color:#5cb85c;text-align:center;">Se dio de alta el proyecto.</div>
          <div class="modal-footer">
            <input type="button" class="btn btn-secondary" data-dismiss="modal" value="Cancelar">
            <input type="button" class="btn btn-primary" id="btnGuardar" value="Agregar">
          </div>
        </form>
      </div>
    </div>
  </div>
  
  

  <script>
    
    
    function obtenerIdProyectoEditar(id){
      document.getElementById('idProyectoU').value = id;
      var data = "id="+id;
      /*$.ajax({
        type: 'POST',
        url: 'functions/getEquipoProyecto.php',
        data: data,
        success:function(r){
          $("#multipleU").html(r);
        }
      });*/

      /*
      $.ajax({
        type: 'POST',
        url: 'functions/getProyecto.php',
        data: data,
        success:function(r){
          $("#txtProyectoU").val(r);
        }
      });*/
      $("#multipleU").load("functions/getEquipoProyecto.php?id="+id);
      //$("#multipleU").load("functions/getEquipoProyecto.php?id="+id);
    }
    
    function obtenerIdTareaEliminar(id){
      document.getElementById('idTareaD').value = id;
    }
    function obtenerIdTareaEditar(id){
      document.getElementById('idTareaU').value = id;
    }

    $( document ).ready(function() {
          $("#cmbIdUsuario").chosen();
    });

    $(document).ready(function(){
      $("#alertaTareas").load('../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
        setInterval(refrescar, 5000);
      });
    function refrescar(){
      $("#alertaTareas").load('../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
    }

    function cambiarTamanio(){
      $("#cmbIdUsuario_chosen").css("width", "100%");
    }

    $("#btnGuardar").click(function(){
      var proyecto = $('#txtProyecto').val().trim();
      var idUsuario = $('#cmbIdUsuario').val().trim();

      if(proyecto === ''){
        $("#txtProyecto")[0].reportValidity();
        $("#txtProyecto")[0].setCustomValidity('Completa este campo.');
        return;
      }

      if(idUsuario === ''){
        $("#mostrarUsuario").css("display", "block");
        setTimeout(
          function()
          {
            $("#mostrarUsuario").css("display", "none");
          }, 2000);
        return;
      }

      var myData={"Proyecto":proyecto,"Usuario":idUsuario};

      $.ajax({
          url : "functions/function_agregar_Proyecto.php",
          type: "POST",
          data : myData,
          success: function(data,status,xhr)
          {
            if(data == 'exito'){
                $("#mostrarProyecto").css("display", "block");
                setTimeout(
                function()
                {
                  $("#mostrarProyecto").css("display", "none");
                  $('#agregar_Proyecto').modal('toggle');
                  $('#txtProyecto').val("");
                  $('#cmbIdUsuario').val("");

                }, 2000);
            }
            else{
              $("#mostrarErrorProyecto").css("display", "block");
                setTimeout(
                function()
                {
                  $("#mostrarErrorProyecto").css("display", "none");
                }, 2000);
            }

          }

      });

    });
    new SlimSelect({
        select: '#multipleU',
        //placeholder: 'Seleccionar equipos'
    });

  </script>
  <script> var ruta = "../";</script>
  <script src="../../js/sb-admin-2.min.js"></script>


</body>

</html>

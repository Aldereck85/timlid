<?php
session_start();
  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 1 || $_SESSION["FKRol"] == 2 || $_SESSION["FKRol"] == 4)){
    require_once('../../../include/db-conn.php');
    if(isset($_GET['id'])){
      $id = $_GET['id'];
      $stmt = $conn->prepare('SELECT c.FKUsuario, e.Primer_Nombre, e.Segundo_Nombre, e.Apellido_Paterno, e.Apellido_Materno FROM cotizacion as c INNER JOIN usuarios as u ON u.PKUsuario = c.FKUsuario INNER JOIN empleados as e ON e.PKEmpleado = u.FKEmpleado WHERE c.PKCotizacion = :cotizacion ');
      $stmt->bindValue(':cotizacion',$id);
      $stmt->execute();
      $row = $stmt->fetch();
      $idVendedor = $row['FKUsuario'];

      $segundo_nombre = "";
      if(trim($row['Segundo_Nombre']) != ""){
        $segundo_nombre = trim($row['Segundo_Nombre'])." ";
      }

      $nombreVendedor = $row['Primer_Nombre']." ".$segundo_nombre.$row['Apellido_Paterno']." ".$row['Apellido_Materno'];

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

  <title>Timlid | Chat</title>

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
  <link href="../../../css/timeline.css" rel="stylesheet">
  <script src="../../../js/jquery.redirect.min.js"></script>

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
            <h1 class="h3 mb-0 text-gray-800">Chat</h1>
          </div>


          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header">
                      <a href="#" class="btn btn-success btn-round" style="position: relative; right: 2%;float: right;margin: 5px 0;" data-toggle="modal" data-target="#agregar_Proyecto" onclick="cambiarTamanio();"><i class="far fa-comment-dots"></i> Agregar mensaje </a>
                </div>
                <div class="card-body">


                    <?php
                        $stmt = $conn->prepare('SELECT mc.TipoUsuario, mc.Mensaje, DATE_FORMAT(mc.FechaAgregado, "%d/%m/%Y %H:%i:%s") as Fecha ,cl.Nombre_comercial,CONCAT(e.Primer_Nombre," ",e.Apellido_Paterno) as Nombre_Empleado  FROM mensajes_cotizacion as mc INNER JOIN cotizacion as c ON c.PKCotizacion = mc.FKCotizacion INNER JOIN clientes as cl ON cl.PKCliente = c.FKCliente INNER JOIN usuarios as u ON u.PKUsuario = c.FKUsuario INNER JOIN empleados as e ON e.PKEmpleado = u.FKEmpleado WHERE mc.FKCotizacion = :cotizacion ORDER BY  mc.FechaAgregado DESC');
                        $stmt->bindValue(':cotizacion',$id);
                        $stmt->execute();
                        $row = $stmt->fetchAll();

                    ?>
                    <?php
                        if(count($row) > 0){

                          echo '<ul class="timeline" id="add-timeline">';

                            foreach($row as $r){

                                if($r['TipoUsuario'] == 1){
                                  $nombreMensaje = $r['Nombre_comercial'];
                                  $clase = 'class="timeline-inverted"';
                                  $color = 'warning';
                                }
                                else{
                                  $nombreMensaje = $r['Nombre_Empleado'];
                                  $clase = '';
                                  $color = 'info';
                                }
                                echo '<li '.$clase.'>
                                        <div class="timeline-badge '.$color.'"><i class="glyphicon glyphicon-credit-card"></i></div>
                                        <div class="timeline-panel">
                                          <div class="timeline-heading">
                                            <h4 class="timeline-title">'.$nombreMensaje.'</h4>
                                          </div>
                                          <div class="timeline-body">
                                            <p>'.$r['Mensaje'].'</p>
                                          </div>
                                          <hr>
                                          <div class="row">
                                            <div class="col-md-12" align="right">
                                              <small>'.$r['Fecha'].'</small>
                                            </div>
                                          </div>
                                        </div>
                                      </li>';
                            }
                            echo "</ul>";

                          }
                          else{
                            echo '<ul id="add-timeline">';
                            echo "<h3 id='nuevo_mensaje'><center>AUN NO HAY MENSAJES EN ESTA COTIZACIÓN</center></h3>";
                            echo '</ul>';
                          }
                    ?>

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

   <!-- Agregar mensaje-->
   <div id="agregar_Proyecto" class="modal fade" style="z-index: 100000000">
        <div class="modal-dialog">
          <div class="modal-content">
            <form action="" method="POST" id="frProyecto">
              <div class="modal-header">
                <h4 class="modal-title">Portal de clientes</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              </div>
                <br>
                  <div class="row">
                    <div class="col-lg-8" style="text-align: center;position:relative;left: 16%;">
                      <label for="usr">Mensaje:</label>
                      <textarea id="txtMensaje" rows="4" cols="50" class="form-control" maxlength="150" required></textarea>
                    </div>
                  </div>
                  <br>
                  <div id="mostrarErrorProyecto" style="display:none;color:#d9534f;text-align:center;">Ocurrio un error, no se envio el mensaje. Lo puede volver a intentar.</div>
                  <div id="mostrarProyecto" style="display:none;color:#5cb85c;text-align:center;">Mensaje enviado.</div>
              <div class="modal-footer">
                <input type="button" class="btn btn-outline btn-warning" data-dismiss="modal" value="Cancelar">
                <input type="button" class="btn btn-outline btn-success" id="btnGuardar" value="Agregar">
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

  <script>
  $(document).ready(function(){
    $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
      setInterval(refrescar, 5000);
    });
  function refrescar(){
    $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
  }

  </script>
  <script> var ruta = "../../";</script>
  <script src="../../../js/sb-admin-2.min.js"></script>
  <script>
      $("#btnGuardar").click(function(){

        var mensaje = $('#txtMensaje').val().trim();
        var cotizacion = <?=$id?>;

        if(mensaje === ''){
          $("#txtMensaje")[0].reportValidity();
          $("#txtMensaje")[0].setCustomValidity('Completa este campo.');
          return;
        }

      <?php date_default_timezone_set('America/Mexico_City'); ?>
      var fecha = "<?php echo date('d/m/Y H:i:s', time()); ?>";
      var nombreVendedor = '<?php echo $nombreVendedor;?>';
      var idVendedor = <?php echo $idVendedor;?>;

      var myData={"Mensaje":mensaje, "Cotizacion":cotizacion, "Fecha" : fecha};

      $.ajax({
          url : "agregarMensaje.php",
          type: "POST",
          data : myData,
          success: function(data,status,xhr)
          {
            if(data == 'exito'){

                var agregarLista =  '<li>' +
                                    '<div class="timeline-badge info"><i class="glyphicon glyphicon-credit-card"></i></div>' +
                                     '<div class="timeline-panel">' +
                                      '<div class="timeline-heading">' +
                                        '<h4 class="timeline-title">' + nombreVendedor + '</h4>' +
                                      '</div>' +
                                      '<div class="timeline-"body">' +
                                        '<p>' + mensaje + '</p>' +
                                      '</div>' +
                                      '<hr>' +
                                      '<div class="row">' +
                                        '<div class="col-md-12" align="right">' +
                                          '<small>' + fecha + '</small>' +
                                        '</div>' +
                                      '</div>' +
                                    '</div>' +
                                  '</li>';

                $( "#add-timeline" ).addClass( "timeline" );
                $( "#nuevo_mensaje" ).remove();
                $( "#add-timeline").prepend(agregarLista);

                $("#mostrarProyecto").css("display", "block");
                setTimeout(
                function()
                {
                  $("#mostrarProyecto").css("display", "none");
                  $('#agregar_Proyecto').modal('toggle');
                  $('#txtMensaje').val("");

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
</script>
</body>

</html>

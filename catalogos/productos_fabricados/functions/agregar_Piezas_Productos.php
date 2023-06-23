<?php
session_start();
  if(isset($_GET['id'])){
    $id =  $_GET['id'];
    if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 1 || $_SESSION["FKRol"] == 3 || $_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 6)){
      require_once('../../../include/db-conn.php');

      $stmt = $conn->prepare('SELECT * FROM productos_fabricados WHERE PKProductoFabricado = :id');
      $stmt->execute(array(':id'=>$id));
      $row = $stmt->fetch();

      $productoFabricado = $row['ProductoFabricado'];


    }else if(isset($_SESSION["Usuario"])){
      header("location:agregar_Productos_Envios_Sencillos.php?id=".$id);
    }
    else if(isset($_SESSION["Usuario"])){
      header("location:../../dashboard.php");
    }
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

  <title>Timlid | Agregar piezas al producto fabricado</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../js/validaciones.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha256-c4gVE6fn+JRKMRvqjoDp+tlG4laudNYrXI1GncbfAYY=" crossorigin="anonymous"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../../js/sb-admin-2.min.js"></script>
  <script src="../../../js/subir_Piezas_Producto.js"></script>

  <!-- Custom fonts for this template-->
  <link href="../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../css/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" integrity="sha256-EH/CzgoJbNED+gZgymswsIOrM9XhIbdSJ6Hwro09WE4=" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
  <link href="../../../css/chosen.css" rel="stylesheet" type="text/css">

  <!-- Custom styles for this template-->
  <link href="../../../css/sb-admin-2.css" rel="stylesheet">

  <script>
      $( document ).ready(function() {
        $("#chosen").chosen();
      });
  </script>

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

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
            <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-th"></i> Agregar piezas por producto fabricado</h1>
          </div>

          <div id="divAlert">
            <div class="alert alert-success" role="alert">
              Acción ejecutada con exito
            </div>
          </div>
          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header">
                  Tarjeta de paqueterias
                </div>
                <div class="card-body">
                    <div class="row">
                      <div class="col-lg-12">
                        <form action="" method="POST" id="frmProductos">
                          <div class="row">
                            <div class="col-lg-4">
                              <div>
                                <div class="row" id="divCantidad">
                                  <div class="col-lg-12">
                                    <label for="usr">Producto fabricado:</label>
                                    <input type='text' value="<?=$productoFabricado;?>" class='form-control' disabled>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Pieza:</label>
                              <select name="cmbPieza" id="chosen" class="form-control" required>
                                  <option value="">Elegir opción</option>
                                      <?php
                                          $stmt = $conn->prepare('SELECT PKPiezaFabricada,NombrePiezas FROM piezas_fabricadas');
                                          $stmt->execute();
                                      ?>
                                      <?php foreach($stmt as $option) : ?>
                                           <option value="<?php echo $option['PKPiezaFabricada']; ?>"><?php echo $option['NombrePiezas']; ?></option>
                                      <?php endforeach; ?>
                              </select>
                            </div>
                            <div class="col-lg-4">
                              <div>
                                <div class="row" id="divCantidad">
                                  <div class="col-lg-12">
                                    <label for="usr">Cantidad de piezas:</label>
                                    <input type='number' name="txtCantidad" id="txtCantidad" value='0' class='form-control'>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <br>
                          <input type="hidden" name="txtId" id="txtId" value="<?=$id;?>">
                          <button type="submit" class="btn btn-success float-right" id="btnAgregar" name="btnAgregar" onclick="agregarProducto()">Agregar</button>
                        </form>
                        <br><br><br>
                        <div class="row">
                          <div class="col-lg-6">
                            Pieza
                          </div>
                          <div class="col-lg-3">
                            Cantidad
                          </div>
                          <div class="col-lg-3">
                            Acciones
                          </div>
                        </div>
                        <hr>
                        <div id="lstProductos">

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
        $rutaf = "../../";
        require_once('../../footer.php');
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

  <!-- Delete Modal mis Rollos -->
  <div id="eliminarModal" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="" id="frmPiezasEliminar" method="POST">
          <input type="hidden" name="idPiezaElimina" id="idPiezaElimina">
          <div class="modal-header">
            <h4 class="modal-title">Eliminar pieza del producto</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <p>¿Está seguro de realizar esta acción?</p>
            <p class="text-warning">Esta acción es irreversible.</p>
          </div>
          <div class="modal-footer">
            <input type="button" class="btn btn-secondary" data-dismiss="modal" value="Cancelar">
            <input type="submit" class="btn btn-danger" value="Eliminar" onclick="eliminarPieza()">
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
  function obtenerIdPiezaEliminar(id){
    document.getElementById('idPiezaElimina').value = id;
  }

    $("#btnCambiarEstatus").click(function(){
      var Estatus = $('#cmbEstatus').val();
      var ID = <?=$id?>;
      var Numero_Guias = $('#numero_guias').val();
      var Tipo_Guia = $('#cmbTipoGuia option:selected').val();

      var guia = 0;
      if ($('#guias').is(":checked"))
      {
        guia = 1;
      }

      var productos = 0;
      if ($('#productos_c').is(":checked"))
      {
        productos = 1;
      }

      $.ajax({
          type:"POST",
          url:"cambiarEstatus.php",
          data:{IDEnvio : ID, EstatusEnvio: Estatus, NumeroGuias: Numero_Guias, Guia: guia, Productos : productos, Tipo_Guia : Tipo_Guia },
          success:function(data){
            $("#alertaEstatus").html(data);
            var res = data.substring(0, 11);
            setTimeout(function(){  $("#alertaEstatus").html(""); }, 3000);

            if(res != 'El producto')
             location.reload();
          }
        });

    });

    $("#cmbEstatus").change(function(){
      var Estatus = $('#cmbEstatus').val();

      if(Estatus != 'Cancelado'){
        $("#opciones_devolucion").css('display','none');
      }

      if(Estatus == 'Enviado'){
        $("#alertaEstatus").html("Si cambias el estatus a Enviado ya no podras modificar los productos del envío y se contabilizarán las guías.");
        setTimeout(function(){  $("#alertaEstatus").html(""); }, 3000);
      }

      if(Estatus == 'Entregado'){
        $("#alertaEstatus").html("Si cambias el estatus a Entregado ya no podras modificar los productos ni el estatus del envío.");
        setTimeout(function(){  $("#alertaEstatus").html(""); }, 2000);
      }

      if(Estatus == 'Cancelado'){
        $("#opciones_devolucion").css('display','block');
      }

    });

    $(document).ready(function(){
      $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
        setInterval(refrescar, 5000);
      });
    function refrescar(){
      $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
    }
  </script>
  <script src="../../../js/subir_Productos_Enviados.js"></script>

</body>

</html>

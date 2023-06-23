<?php
session_start();
  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 1)){
    require_once('../../../include/db-conn.php');
    if(isset($_GET['id'])){
      $id = $_GET['id'];
      $stmt = $conn->prepare('SELECT oc.Referencia AS ref,oc.Fecha_Deseada_Entrega as fechaDE,oc.Fecha_de_Emision AS fecha,pr.PKProveedor,pr.Razon_Social,p.Clave,p.Descripcion,oc.Importe,pc.Cantidad,oc.Estatus FROM orden_compra AS oc
        LEFT JOIN compras_productos AS cp ON oc.PKOrdenCompra = cp.FKOrdenCompra
        LEFT JOIN productos_oc AS pc ON oc.PKOrdenCompra = pc.FKOrdenCompra
        LEFT JOIN productos AS p ON pc.FKProducto = p.PKProducto
        LEFT JOIN proveedores AS pr ON oc.FKProveedor = pr.PKProveedor
        WHERE PKOrdenCompra = :id');
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch();
      $compra = $row['ref'];
      $fecha = $row['fecha'];
      $fecha = date("d/m/Y",strtotime($fecha));
      $idProveedor = $row['PKProveedor'];
      $proveedor = $row['Razon_Social'];
      $producto = $row['Clave']." ".$row['Descripcion'];
      $estatus = $row['Estatus'];
      $precio = "$ ".number_format($row['Importe'],2);
      $importe = $row['Importe'];
      $cantidad = $row['Cantidad'];
      $fechaDE = $row['fechaDE'];
      $fechaDE = date("d/m/Y",strtotime($fechaDE));
    }
  }else {
    header("location:../../dashboard.php");
  }
  if(isset($_POST['btnPagar'])){
    $aux = explode(" ",$_POST['txtImporte']);
    $monto = floatval($aux[1]);

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

  <title>Timlid | Ver orden de compra</title>

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

  <script src="../../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <link href="../../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

  <script>
    $(document).ready(function(){
      var id = <?=$id; ?>;
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
      $("#tblProductos").dataTable(
      {
        "ajax":"function_VerCompra.php?id="+id,
          "columns":[
            {"data":"No"},
            {"data":"Clave"},
            {"data":"Producto"},
            {"data":"Precio unitario"},
            {"data":"Cantidad"},
            {"data":"Importe"}
          ],
          "language": idioma_espanol,
            columnDefs: [
              { orderable: false, targets: 2 }
            ],
            responsive: true
      }

      )
    });
  </script>

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
            <h1 class="h3 mb-0 text-gray-800">Ver orden de compras</h1>
          </div>

          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header">
                  <div class="row">
                    <div class="col-lg-12 float-right">
                      Tarjeta de orden de compras
                      <?php if($estatus != 3){ ?>
                        <button type="button" class="btn btn-success float-right" style="position: relative;right: 1%;" name="btnAgregarProducto" id="btnAgregarProducto">Enviar</button>
                        <button type="button" class="btn btn-secondary float-right" style="position: relative;right: 2%;" name="btnDescargar" id="btnDescargar">Descargar</button>
                      <?php
                        if($estatus == 0){
                      ?>
                        <button type="button" class="btn btn-info float-right" style="position: relative;right: 3%;" name="btnAceptar" id="btnAceptar">Aceptar</button>

                      <?php }else{ ?>
                          <a class="btn btn-primary float-right" style="position: relative;right: 3%;" href="ver_Compras.php?id=<?=$id; ?>" name="btnVerCompras" id="btnVerCompras">Compras</a>
                      <?php } if($_SESSION['FKRol'] == 1 || $_SESSION['FKRol'] == 4){?>
                        <button type="button" class="btn btn-dark float-right" style="position: relative;right: 4%;" name="btnProrroga" id="btnProrroga">Prorroga</button>
                        <button type="button" class="btn btn-warning float-right" style="position: relative;right: 5%;color:#808080" name="btnEntrega" id="btnEntrega">Entrega</button>
                      <?php if($estatus == 0){ ?>
                        <a type="button" class="btn btn-primary float-right " style="position: relative;right: 6%;" href="#" data-toggle="modal" data-target="#editar_OrdenCompra" onclick="obtenerIdOrdenCompraEditar(<?=$id ?>);"><i class="fas fa-edit"></i> Editar OC</a>
                      <?php }}} ?>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <div class="row my-3">
                    <div class="col-lg-4 ">
                      <h4>Referencia: <?=$compra; ?></h4>
                    </div>
                    <div class="col-lg-4">
                      <h4>Fecha de emision: <?=$fecha; ?></h4>
                    </div>
                    <div class="col-lg-4">
                      <h4>Proveedor: <?=$proveedor; ?></h4>
                    </div>
                  </div>
                  <div class="row my-3">
                      <div class="col-lg-4">
                        <h4>Fecha de entrega: <?=$fechaDE; ?></h4>
                      </div>
                    <div class="col-lg-4">
                      <h4>Importe Total: <?=$precio; ?></h4>
                    </div>
                  </div>

                  <hr class="my-3"style="width: 100%">
                  <br>
                  <br>

                  <!-- Datatables aqui -->
                  <div class="table-responsive">
                    <table class="table table-bordered" id="tblProductos" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Clave</th>
                          <th>Producto</th>
                          <th>Precio unitario</th>
                          <th>Cantidad</th>
                          <th>Importe</th>
                        </tr>
                      </thead>
                      <tfoot>
                        <tr>
                          <th>No</th>
                          <th>Clave</th>
                          <th>Producto</th>
                          <th>Precio unitario</th>
                          <th>Cantidad</th>
                          <th>Importe</th>
                        </tr>
                      </tfoot>
                      <tbody>
                      </tbody>
                    </table>
                  </div>

              </div>

            </div>
          </div>
          </div>
          <?php
            $stmt = $conn->prepare('SELECT e.Primer_Nombre,e.Apellido_Paterno,b.Fecha_Movimiento,m.Mensaje FROM bitacora_compras AS b
                    LEFT JOIN usuarios AS u ON b.FKUsuario = u.PKUsuario
                    LEFT JOIN empleados AS e ON u.FKEmpleado = e.PKEmpleado
                    LEFT JOIN mensajes_acciones AS m ON b.FKMensaje = m.PKMensajesAcciones
                    WHERE FKOrdenCompra = :id');
            $stmt->bindValue(':id',$id);
            $stmt->execute();
            while($row = $stmt->fetch()){
              $fecha = new DateTime($row['Fecha_Movimiento']);
              $usuario = $row['Primer_Nombre']." ".$row['Apellido_Paterno'];
              $alerta = $fecha->format('d/m/Y').": ".$row['Mensaje']." por ".$usuario;
          ?>
          <!-- bitacora de movimientos en compras-->
          <div class="row">
            <div class="alert alert-secondary col-lg-6 text-center text-primary" style="font-weight: bold;margin-left:25%" role="alert">
              <?=$alerta;?>
            </div>
          </div>
        <?php } ?>
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
      <div id="modal_envio"></div>
    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <div id="prorroga" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="" method="POST">
          <input type="hidden" name="txtId" id="txtId" value="<?=$id; ?>">
          <div class="modal-header">
            <h4 class="modal-title">Registro de prorroga</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <div class="row">
                <div class="col-lg-12">
                  <label for="txtFechaProrroga">Fecha prorroga:*</label>
                  <?php
                    $stmt = $conn->prepare('SELECT Fecha_Deseada_Entrega FROM orden_compra WHERE PKOrdenCompra = :id');
                    $stmt->bindValue(':id',$id);
                    $stmt->execute();
                    $row = $stmt->fetch();

                  ?>
                  <input class="form-control" type="date" name="txtFechaProrroga" id="txtFechaProrroga" value="<?=$row['Fecha_Deseada_Entrega']; ?>">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" name="button"><i class="fas fa-times"></i> Cancelar</button>
            <button class="btn btn-success" type="button" name="btnRegistroPago" id="btnRegistroProrroga" ><i class="fas fa-calendar-alt"></i> Registrar prorroga</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div id="entrega" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="" method="POST">
          <input type="hidden" name="txtIdLugar" id="txtIdLugar" value="<?=$id; ?>">
          <div class="modal-header">
            <h4 class="modal-title">Editar lugar de entrega</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <div class="row">
                <div class="col-lg-12">
                  <label for="txtFechaProrroga">Lugar de entrega:*</label>
                  <select class="form-control" name="cmbDireccion" id="cmbDireccion">
                    <option value="">Seleccione un opcion...</option>
                    <?php
                      $stmt = $conn->prepare('SELECT PKDomicilio,Calle, Numero_exterior, Numero_Interior,Colonia,CP,Municipio,Estado FROM domicilio_de_envio_proveedores WHERE FKProveedor = :id');
                      $stmt->bindValue(':id',1);
                      $stmt->execute();
                      while($row = $stmt->fetch()){
                        ?>
                        <option value="<?=$row['PKDomicilio']; ?>"><?=$row['Calle']." No. ".$row['Numero_exterior']." ".$row['Numero_Interior']." Colonia ".$row['Colonia']." C.P. ".$row['CP']." ".$row['Municipio']." ".$row['Estado']; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" name="button"><i class="fas fa-times"></i> Cancelar</button>
            <button class="btn btn-success" type="button" name="btnCambiarLugar" id="btnCambiarLugar" ><i class="fas fa-calendar-alt"></i> Registrar prorroga</button>
          </div>
        </form>
      </div>
    </div>
  </div>

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

  <div id="editar_OrdenCompra" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="editar_OrdenCompra.php" method="POST">
          <input type="hidden" name="txtIdU" id="txtIdU">
          <div class="modal-header">
            <h4 class="modal-title">Editar orden de compra</h4>
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



  <script>
  function obtenerIdOrdenCompraEditar(id){
    document.getElementById('txtIdU').value = id;
  }
  $(document).ready(function(){
    var n = new Date();

    var y = n.getFullYear();
    var m = n.getMonth()+1;
    var d = n.getDate();

    if(m < 10 && d < 10){
      $('#txtFechaPago').val(y+"-0"+m+"-0"+d);
    }else if(m < 10){
      $('#txtFechaPago').val(y+"-0"+m+"-"+d);
    }else if(d < 10){
      $('#txtFechaPago').val(y+"-"+m+"-0"+d);
    }else{
      $('#txtFechaPago').val(y+"-"+m+"-0"+d);
    }

  });
  $(document).ready(function(){
    $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
      setInterval(refrescar, 5000);
    });
  function refrescar(){
    $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
  }
  $(document).ready(function(){
    $('#btnAgregarProducto').click(function(){

      $('#modal_envio').load('modal_envio.php?id='+<?=$idProveedor; ?>+'&txtId='+<?=$id; ?>, function(){
        $('#datos_envio').modal('show');
      });
    });
  });
  $(document).ready(function(){
    $('#btnPagar').click(function(){
      $('#pagar').modal('show');
    });
  });

    $(document).ready(function(){
      $('#btnDescargar').click(function(){
        window.open('ordenCompraPdf.php?txtId='+<?=$idProveedor;?>+'&txtCompra='+<?=$id;?>);
      });
    });

    $(document).ready(function(){
      $('#btnAceptar').click(function(){
        var cadena = "id="+<?=$id;?>;
        $.ajax({
          type: "POST",
          url: "cambiar_Estatus.php",
          data: cadena,
          success:function(){
            window.location.href = "../index.php";
          }
        });
      });
    });

    $(document).ready(function(){
      $('#btnProrroga').click(function(){
        $('#prorroga').modal('show');
      });
    });

    $(document).ready(function(){
      $('#btnEntrega').click(function(){
        $('#entrega').modal('show');
      });
    });

    $(document).ready(function(){
      $('#btnRegistroProrroga').click(function(){
        var cadena = "id="+$('#txtId').val()+"&fecha="+$('#txtFechaProrroga').val();
        $.ajax({
          type: 'POST',
          url: 'editar_Prorroga.php',
          data: cadena,
          success:function(){
            window.location.href = "../index.php";
          }
        });
      });
    });

    $(document).ready(function(){
      $('#btnCambiarLugar').click(function(){
        var cadena = "id="+$('#txtId').val()+"&lugar="+$('#cmbDireccion').val();
        $.ajax({
          type: 'POST',
          url: 'editar_Direccion.php',
          data: cadena,
          success:function(r){
            window.location.href = "../index.php";
          }
        });
      });
    });

  </script>
  <script> var ruta = "../../";</script>
  <script src="../../../js/sb-admin-2.min.js"></script>

</body>

</html>

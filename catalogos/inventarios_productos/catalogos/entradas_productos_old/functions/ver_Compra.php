<?php
session_start();
  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 1)){
    require_once('../../../include/db-conn.php');
    if(isset($_GET['id'])){
      $id = $_GET['id'];
      $compra = $_GET['compra'];
      $stmt = $conn->prepare('SELECT *,cp.Referencia AS ref,cp.Importe AS imp, cp.Fecha_de_Emision AS fecha FROM compras_productos AS cp
        LEFT JOIN productos_cc AS pc ON cp.PKCompra = pc.FKCompra
        LEFT JOIN orden_compra AS oc ON cp.FKOrdenCompra = oc.PKOrdenCompra
        LEFT JOIN productos AS pr ON pc.FKProducto = pr.PKProducto
        LEFT JOIN proveedores AS p ON oc.FKProveedor = p.PKProveedor
        WHERE cp.PKCompra = :id');
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch();
      $compra = $row['ref'];
      $fecha = $row['fecha'];
      $totales = $row['imp'];
      $fecha = date("d/m/Y",strtotime($fecha));
      $idProveedor = $row['PKProveedor'];
      $proveedor = $row['Razon_Social'];
      $producto = $row['Clave']." ".$row['Descripcion'];
      $precio = "$ ".number_format($row['imp'] * 1.16,2);
      $cantidad = $row['Cantidad_Recibida'];
      $ordenCompra = $row['Referencia'];
      $importe = $row['Importe'];
      $fechaOrden = $row['Fecha_de_Emision'];
      $fechaOrden = date("d/m/Y",strtotime($fechaOrden));
    }
  }else {
    header("location:../../dashboard.php");
  }
  if(isset($_POST['btnRegistroPago'])){


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

  <title>Timlid | Ver compra</title>

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
            <h1 class="h3 mb-0 text-gray-800">Ver compras</h1>
          </div>


          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header">
                  Tarjeta de compras
                  <?php
                    $totalNeto = 0;
                    $stmt = $conn->prepare('SELECT SUM(Importe) AS suma FROM pagos_productos WHERE FKCompra = :id');
                    $stmt->bindValue(':id',$id);
                    $stmt->execute();
                    $row = $stmt->fetch();
                    if($row['suma'] > 0){
                      $totalNeto = $row['suma'];
                    }
                    if($totalNeto < $importe){
                  ?>
                  <button type="button" style="position: relative;right: 1%;" class="btn btn-info float-right" name="btnPagar" id="btnPagar">Registrar pago</button>
                  <?php } if($totalNeto > 0){?>
                  <a href="ver_PagosCompras.php?id=<?=$id;?>" style="position: relative;right: 2%;" class="btn btn-success float-right" name="btnVerPagos" id="btnVerPagos">Ver pagos</a>
                  <?php } if($_SESSION['FKRol'] == 1 || $_SESSION['FKRol'] == 4){?>
                  <a type="button" class="btn btn-primary float-right " style="position: relative;right: 3%;" href="#" data-toggle="modal" data-target="#editar_Compra" onclick="obtenerIdOrdenCompraEditar(<?=$id ?>);"><i class="fas fa-edit"></i> Editar CC</a>
                  <?php } ?>
                </div>
                <div class="card-body">

                  <div class="row my-3">
                    <div class="col-lg-3 ">
                      <h4>Referencia: <?=$compra; ?></h4>
                    </div>
                    <div class="col-lg-3">
                      <h4>Fecha de emision: <?=$fecha; ?></h4>
                    </div>
                    <div class="col-lg-3">
                      <h4>Proveedor: <?=$proveedor; ?></h4>
                    </div>
                    <div class="col-lg-3 redondear">
                      <h4>Importe Total: <?=$precio; ?></h4>
                      <small style="margin-left: 80px">Importe incluye IVA</small>
                    </div>
                    <hr class="my-3"style="width: 100%">
                    <br>
                    <br>
                  </div>
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
            $stmt = $conn->prepare('SELECT e.Primer_Nombre,e.Apellido_Paterno,b.Fecha_Movimiento,m.Mensaje FROM bitacora_compras_productos AS b
                    LEFT JOIN usuarios AS u ON b.FKUsuario = u.PKUsuario
                    LEFT JOIN empleados AS e ON u.FKEmpleado = e.PKEmpleado
                    LEFT JOIN mensajes_acciones AS m ON b.FKMensaje = m.PKMensajesAcciones
                    WHERE b.FKCompraProducto = :id');
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

      <!-- Modal Datos pago -->
      <div id="pagar" class="modal fade">
        <div class="modal-dialog">
          <div class="modal-content">
            <form action="" method="POST">
              <input type="hidden" name="txtId" value="<?=$id; ?>">
              <div class="modal-header">
                <h4 class="modal-title">Registro de pago</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              </div>
              <div class="modal-body">
                <div id="alertas"></div>
                <div class="form-group">
                  <div class="row">
                    <div class="col-lg-6">
                      <label for="txtImporte">Importe:*</label>
                      <?php
                        $costo = 0;
                        $stmt = $conn->prepare('SELECT Importe FROM pagos_productos WHERE FKCompra = :id');
                        $stmt->execute(array(':id'=>$id));
                        $rowCount = $stmt->rowCount();
                        while($row = $stmt->fetch()){
                          if($rowCount > 0){
                            $costo += $row['Importe'];
                          }
                        }
                        $total1 = (double)$totales - (double)$costo;

                      ?>
                      <input class="form-control numericDecimal-only" style="text-align:right" type="text" pattern="^\$ \d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" name="txtImporte" id="txtImporte" value="<?=$total1; ?>" required autofocus>

                    </div>
                    <div class="col-lg-6">
                      <label for="txtFechaPago">Fecha de pago:*</label>
                      <input class="form-control" type="date" name="txtFechaPago" id="txtFechaPago" value="" required>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="row">
                    <div class="col-lg-6">
                      <label for="cmbCuenta">Cuenta:*</label>
                      <select class="form-control" name="cmbCuenta" id="cmbCuenta" required>
                        <option value="">Seleccione una cuenta...</option>
                        <?php
                          $stmt = $conn->prepare('SELECT cb.PKCuentaProveedor,b.Nombre FROM cuentas_bancarias_proveedores AS cb
                            LEFT JOIN bancos AS b ON cb.FKBanco = b.PKBanco
                            WHERE FKProveedor = :id');
                          $stmt->bindValue(':id',1);
                          $stmt->execute();
                          while($row = $stmt->fetch()){
                            ?>
                            <option value="<?=$row['PKCuentaProveedor']; ?>"><?=$row['Nombre']; ?></option>
                          <?php } ?>
                      </select>
                    </div>
                    <div class="col-lg-6">
                      <label for="cmbTipoPago">Tipo de pago:*</label>
                      <select class="form-control" name="cmbTipoPago" id="cmbTipoPago" required>
                        <option value="">Seleccione un tipo de pago...</option>
                        <option value="1">Transferencia electrónica</option>
                        <option value="2">Efectivo</option>
                        <option value="3">Tarjeta de crédito</option>
                        <option value="4">Tarjeta de débito</option>
                        <option value="5">Por definir</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-lg-12">
                    <label for="">Notas de pago:</label>
                    <textarea class="form-control alphaNumeric-only" name="txtNotasPago" maxlength="100" rows="3" cols="80"></textarea>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" name="button"><i class="fas fa-times"></i> Cancelar</button>
                <button class="btn btn-success" type="button" name="btnRegistroPago" id="btnRegistroPago" ><i class="fas fa-coins"></i> Registrar pago</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div id="editar_Compra" class="modal fade">
        <div class="modal-dialog">
          <div class="modal-content">
            <form action="editar_Compra.php" method="POST">
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

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <script>
  function obtenerIdOrdenCompraEditar(id){
    document.getElementById('txtIdU').value = id;
  }
  $(document).ready(function(){
    $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
      setInterval(refrescar, 5000);
    });
  function refrescar(){
    $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
  }

  $(document).ready(function(){
    $('#btnPagar').click(function(){
      var n = new Date();

      var y = n.getFullYear();
      var m = n.getMonth()+1;
      var d = n.getDate();
      if(m < 10 && d < 10){
        document.getElementById('txtFechaPago').value = y+"-0"+m+"-0"+d;
      }else if(m < 10){
        document.getElementById('txtFechaPago').value = y+"-0"+m+"-"+d;
      }else if(d < 10){
        document.getElementById('txtFechaPago').value = y+"-"+m+"-0"+d;
      }else{
        document.getElementById('txtFechaPago').value = y+"-"+m+"-0"+d;
      }
      $('#pagar').modal('show');
    });
  });
  $('#pagar').on('shown.bs.modal', function() {
    $('#txtImporte').focus();
  });
  $("input[data-type='currency']").on({
    keyup: function() {
      formatCurrency($(this));
    },
    blur: function() {
      formatCurrency($(this), "blur");
    }
  });
  function formatNumber(n) {
    // format number 1000000 to 1,234,567
    return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
  }
  function formatCurrency(input, blur) {
      // appends $ to value, validates decimal side
      // and puts cursor back in right position.

      // get input value
      var input_val = input.val();

      // don't validate empty input
      if (input_val === "") { return; }

      // original length
      var original_len = input_val.length;

      // initial caret position
      var caret_pos = input.prop("selectionStart");

      // check for decimal
      if (input_val.indexOf(".") >= 0) {

        // get position of first decimal
        // this prevents multiple decimals from
        // being entered
        var decimal_pos = input_val.indexOf(".");

        // split number by decimal point
        var left_side = input_val.substring(0, decimal_pos);
        var right_side = input_val.substring(decimal_pos);

        // add commas to left side of number
        left_side = formatNumber(left_side);

        // validate right side
        right_side = formatNumber(right_side);

        // On blur make sure 2 numbers after decimal
        if (blur === "blur") {
          right_side += "00";
        }

        // Limit decimal to only 2 digits
        right_side = right_side.substring(0, 2);

        // join number by .
        input_val = "$ " + left_side + "." + right_side;

      } else {
        // no decimal entered
        // add commas to number
        // remove all non-digits
        input_val = formatNumber(input_val);
        input_val = "$ " + input_val;

        // final formatting
        if (blur === "blur") {
          input_val += ".00";
        }
      }

      // send updated string to input
      input.val(input_val);

      // put caret back in the right position
      var updated_len = input_val.length;
      caret_pos = updated_len - original_len + caret_pos;
      input[0].setSelectionRange(caret_pos, caret_pos);
    }
    $(document).ready(function(){
      var totales = <?=$costo; ?>;
      var costos = <?=$total1; ?>;

      var alertas ="";
      $('#btnRegistroPago').click(function(){
        var aux = $('#txtImporte').val().split(' ');
        var aux2 = "";
        var aux1 = aux[1].split(',');

        var importe = parseFloat(aux1);

        if(importe > costos){
          alertas = '<div class="alert alert-danger" role="alert">'+
                    'El costo debe ser menor o igual al importe.'+
                    '</div>';
        }else if(!$('#txtFechaPago').val()){
          alertas = '<div class="alert alert-warning" role="alert">'+
                    'Debe seleccionar una fecha.'+
                    '</div>';
        }else if(!$('#cmbCuenta').val()){
          alertas = '<div class="alert alert-warning" role="alert">'+
                    'Debe seleccionar una cuenta bancaria.'+
                    '</div>';
        }else if(!$('#cmbTipoPago').val()){
          alertas = '<div class="alert alert-warning" role="alert">'+
                    'Debe seleccionar un tipo de pago.'+
                    '</div>';
        }else{

          var cadena = 'fecha='+$('#txtFechaPago').val()+
                        '&cuenta='+$('#cmbCuenta').val()+
                        '&tipo_pago='+$('#cmbTipoPago').val()+
                        '&importe='+importe+
                        '&compra='+<?=$id;?>;
          $.ajax({
            type: 'POST',
            url: 'darAltaPago.php',
            data: cadena,
            success:function(r){
              window.location.href = "../index.php";
              //alert(r);
            }
          });
        }
        $('#alertas').html(alertas);
      });
    });
    $(document).ready(function(){
      $('#btnVerPagos').click(function(){
        window.location.href = "ver_PagosCompras.php?id="+<?=$id;?>
      });
    });
  </script>

</body>

</html>

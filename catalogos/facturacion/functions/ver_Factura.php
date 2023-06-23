<?php
session_start();
  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 1)){
    require_once('../../../include/db-conn.php');
    if(isset($_GET['id'])){
      $id = $_GET['id'];
      $stmt = $conn->prepare('SELECT f.Folio,df.Razon_Social,f.Total,f.Fecha_Timbrado,f.Estatus,f.UUID FROM facturacion AS f
                            INNER JOIN domicilio_fiscal AS df ON f.FKDomicilioFiscal = df.PKDomicilioFiscal
                            WHERE PKFacturacion = :id');
      $stmt->bindValue(':id',$id);
      $stmt->execute();
      $row = $stmt->fetch();
      $folio = $row['Folio'];
      $razonSocial = $row['Razon_Social'];
      $total = $ImporteTotal = $row['Total'];
      $fecha_timbrado = date('d-m-Y',strtotime($row['Fecha_Timbrado']));
      $estatus = $row['Estatus'];
      $Subtotal = 0;
      $uuid = $row['UUID'];
      //$ImporteTotal = 0;
      switch ($estatus) {
          case 'Pendiente':
              $color = '#f0ad4e';
              break;
          case 'Cancelada':
              $color = '#d9534f';
              break;
          case 'Cobrada':
              $color = '#5cb85c';
              break;
      }
    }else{
      header("location:../../dashboard.php");
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

  <title>Timlid | Ver factura</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../js/validaciones.js"></script>
  <script src="../../../js/numeral.min.js"></script>
  <script src="../../../js/jquery.number.min.js"></script>

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

  <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha256-c4gVE6fn+JRKMRvqjoDp+tlG4laudNYrXI1GncbfAYY=" crossorigin="anonymous"></script>
  <link href="../../../css/chosen.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" integrity="sha256-EH/CzgoJbNED+gZgymswsIOrM9XhIbdSJ6Hwro09WE4=" crossorigin="anonymous" />

  <style type="text/css">
    .header-color {
    font-size: 18px;
    color: #fff;
    line-height: 1.4;
    background-color: #BD7849;

  }
  .redondear{
    border-radius: 10px;
  }

  table .btn{
    padding: 0 0;
    color:#d9534f;
  }
  .table-input{
    height: 20px;
    width: 80%;
    border: none;
    border-bottom: 1px solid #f2f2f2;
    text-align: center;background-color: #f2f2f2;
  }
  .table-input:focus{
    text-align: center;background-color: #f2f2f2;
  }
  .total{
    color:white;
    background-color: #97603A;
    font-size: 24px;
  }
  .redondearAbajoIzq{
    border-radius: 0px 0px 0px 10px;
  }
  .redondearAbajoDer{
    border-radius: 0px 0px 10px 0px;
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

          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header">
                  <div class="row">
                    <div class="col-lg-2" style="margin-top:5px">
                      <h1 class="h3 text-gray-800" style="font-weight:bold">Ver factura</h1>
                    </div>
                    <div class="col-md-2 col-sm-6">
                      <a title="PDF" class="btn btn-info btn-circle" href="getPDF.php?filename=<?=$folio;?>&uid=<?=$uuid;?>"><i class="fas fa-file-pdf fa-1x"></i></a>
                    </div>
                    <div class="col-md-2 col-sm-6">
                      <a title="XML" class="btn btn-info btn-circle" href="getXML.php?filename=<?=$folio;?>&uid=<?=$uuid;?>"><i class="fas fa-file-code fa-1x"></i></a>
                    </div>
                    <?php if($estatus != 'Cancelada'){ ?>
                      <div class="col-md-2 col-sm-6">
                        <a title="Cancelar" class="btn btn-danger btn-circle" data-toggle="modal" data-target="#eliminar_factura" onclick="obtenerIdEmpresaEditar(<?=$id;?>,<?=$uuid;?>);"href="CancelarCFDI.php?id=<?=$id;?>&uid=<?=$uuid;?>"><i class="fas fa-exclamation-triangle fa-1x"></i></a>
                      </div>
                    <?php } ?>
                  </div>

                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-4 text-center">
                      <h5><b>Razon social:</b> <?=$razonSocial; ?></h5>
                    </div>
                    <div class="col-lg-4 text-center">
                      <h5><b>Folio:</b> <?=$folio; ?></h5>
                    </div>
                    <div class="col-lg-4 text-center">
                      <h5><b>Fecha de timbrado:</b> <?=$fecha_timbrado; ?></h5>
                    </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-lg-6 text-center">
                      <h5><b>Total:</b> <?="$".number_format($total,2); ?></h5>
                    </div>
                    <div class="col-lg-2" style="background:<?=$color; ?>;position:relative;float:right;text-align:center;padding:5px;color:white;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;">
                      <h5><b>Estatus:</b> <?=$estatus; ?></h5>
                    </div>
                  </div>
                  <br><br>
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="table-responsive redondear">
                        <table class="table table-sm" id="cotizacion">
                            <thead class="text-center header-color">
                              <tr>
                                <th>Clave/Producto</th>
                                <th>Cantidad</th>
                                <th>Unidad de medida</th>
                                <th>Precio unitario</th>
                                <th>Impuestos</th>
                                <th>Importe</th>
                                <th></th>
                              </tr>
                            </thead>
                            <tbody id="lstProductos">
                              <?php
                                  $stmt = $conn->prepare('SELECT * FROM facturas_productos AS fp INNER JOIN productos as p ON fp.FKProducto = p.PKProducto WHERE fp.FKFacturacion= :id');
                                  $stmt->execute(array(':id'=>$id));
                                  $numero_productos = $stmt->rowCount();
                                  $rowp = $stmt->fetchAll();
                                  $impuestos = array();
                                  $x = 0;

                                  foreach ($rowp as $rp) {

                                    $stmt = $conn->prepare('SELECT i.PKImpuesto, i.Nombre, i.TipoImpuesto, i.TipoImporte, i.Operacion, ip.FKProducto, ip.Tasa FROM impuestos_productos as ip
                                                            INNER JOIN impuesto as i ON i.PKImpuesto = ip.FKImpuesto
                                                            INNER JOIN facturas_productos AS fp ON ip.FKProducto = fp.FKProducto
                                                            WHERE fp.FKFacturacion= :id AND fp.FKProducto = :idProducto');
                                    $stmt->execute(array(':id'=>$id, ':idProducto'=>$rp['FKProducto']));
                                    $rowi = $stmt->fetchAll();

                                    //print_r($rowi);

                                    $totalProducto = $rp['Cantidad'] * $rp['Precio_Unitario'];
                                    $Subtotal += $totalProducto;
                                    echo  "<tr id='idProducto_".$rp['FKProducto']."' class='text-center'>".
                                            "<th id='nombreproducto_".$rp['FKProducto']."'>".$rp['Clave']." ".$rp['Descripcion']."</th>".
                                            "<th id='piezas_".$rp['FKProducto']."'>".$rp['Cantidad']."</th>".
                                            "<input type='hidden' id='piezaAnt_".$rp['FKProducto']."' value='".$rp['Cantidad']."' />".
                                            "<input type='hidden' name='inp_productos[]' value='".$rp['FKProducto']."' />".
                                            "<th>Pieza</th>".
                                            "<th id='precio_".$rp['FKProducto']."'>".$rp['Precio_Unitario']."</th>".
                                            "<input type='hidden' name='inp_precio[]' value='".$rp['Precio_Unitario']."' />".
                                            "<th>".
                                            "<span id='impuestos_".$rp['FKProducto']."'>";

                                          $contImpuestos = 1;
                                          $numImpuestos = count($rowi);
                                          foreach ($rowi as $ri) {
                                            $IniImpuesto = explode(" ", $ri['Nombre']);
                                            $Identificador = $IniImpuesto[0]."_".$ri['TipoImpuesto']."_".$ri['TipoImporte']."_".$ri['PKImpuesto']."_".$ri['FKProducto'];

                                            if($ri['TipoImporte'] == 1){
                                              $tas = "%";
                                            }
                                            if($ri['TipoImporte'] == 2 || $ri['TipoImporte'] == 3){
                                              $tas = "";
                                            }

                                            //print_r($impuestos);

                                            //echo "id impuesto :".$ri['PKImpuesto']."//";
                                            $found_key = array_search($ri['PKImpuesto'], array_column($impuestos, 0));
                                            /*print_r($found_key);
                                            echo "fk ".$found_key[0];*/
                                            if($found_key > -1){
                                                $impuestos[$found_key][0] = $ri['PKImpuesto'];

                                                if($ri['TipoImporte'] == 1)
                                                  $impuestos[$found_key][1] = $impuestos[$found_key][1] + (($rp['Cantidad'] * $rp['Precio_Unitario']) * ($ri['Tasa'] / 100));
                                                else
                                                  $impuestos[$found_key][1] = $impuestos[$found_key][1] + $ri['Tasa'];

                                                $impuestos[$found_key][2] = $ri['Nombre'];
                                                $impuestos[$found_key][3] = $ri['TipoImpuesto'];
                                                $impuestos[$found_key][4] = $ri['Operacion'];
                                            }
                                            else{
                                                $impuestos[$x][0] = $ri['PKImpuesto'];
                                                if($ri['TipoImporte'] == 1)
                                                  $impuestos[$x][1] = ($rp['Cantidad'] * $rp['Precio_Unitario']) * ($ri['Tasa'] / 100);
                                                else
                                                  $impuestos[$x][1] = $ri['Tasa'];

                                                $impuestos[$x][2] = $ri['Nombre'];
                                                $impuestos[$x][3] = $ri['TipoImpuesto'];
                                                $impuestos[$x][4] = $ri['Operacion'];
                                                $x++;
                                            }

                                             echo "<span id='".$Identificador."' >".$ri['Nombre']." ".$ri['Tasa'].$tas." <input name='valImp_".$ri['Tasa']."' type='hidden' id='impAgregado_".$ri['FKProducto']."_".$ri['PKImpuesto']."' value='".$ri['Tasa']."' /><input type='hidden' id='OperacionUnica_".$ri['FKProducto']."_".$ri['PKImpuesto']."' value='".$ri['Operacion']."' /></span>";
                                             if($contImpuestos != $numImpuestos)
                                              echo "<br>";
                                             $contImpuestos++;
                                          }


                                    echo    "</span>".
                                             "</th>".
                                            "<th id='totalproducto_".$rp['FKProducto']."'>".number_format($totalProducto,2)."</th>".
                                            "<input type='hidden' name='inp_total_producto[]' value='".number_format($totalProducto,2)."' />".
                                            "<th><button type='button' class='btn eliminarProductos' id='".$rp['FKProducto']."'>X</button></th>".
                                          "</tr>";

                                    }

                              ?>
                            </tbody>
                              <tr>
                                <th colspan="3"></th>
                                <th>Subtotal:</th>
                                <th colspan="2" style="text-align: right;">$ <span id="Subtotal"><?= number_format($Subtotal,2); ?></span></th>
                                <th>&nbsp</th>
                              </tr>
                              <tr>
                                <th colspan="3"></th>
                                <th>Impuestos:</th>
                                <th colspan="2"></th>
                                <th>&nbsp</th>
                              </tr>
                              <tbody id="lstimpuestos">
                                <?php
                                  foreach ($impuestos as $imp) {
                                      $IniImpuesto = explode(" ", $imp[2]);
                                      echo "<tr id='".$IniImpuesto[0]."_".$imp[0]."'>".
                                              "<th colspan='3'></th>".
                                              "<th style='text-align: right;'>".$imp[2]."</th>".
                                              "<th colspan='2' style='text-align: right;'>$ <span id='Impuesto_".$imp[0]."' name='".$imp[3]."_".$imp[4]."' class='ImpuestoTot'>".number_format($imp[1],2)."</span></th>".
                                              "<th>&nbsp</th>".
                                           "</tr>";
                                  }
                                ?>
                              </tbody>
                                <tr class="total">
                                  <th colspan="3" class="redondearAbajoIzq"></th>
                                  <th>Total:</th>
                                  <th colspan="2" style="text-align: right;">$ <span id="Total"><?=number_format($ImporteTotal,2); ?></span></th>
                                  <th class="redondearAbajoDer">&nbsp</th>
                                </tr>
                          </table>
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
  <!-- Delete Modal mis paqueterias -->
  <div id="eliminar_factura" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="CancelarCFDI.php" method="POST">
          <input type="hidden" name="txtId" id="txtId">
          <input type="hidden" name="txtUuid" id="txtUuid">
          <div class="modal-header">
            <h4 class="modal-title">Eliminar paqueteria</h4>
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
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <script>
  $(document).ready(function(){
    $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
      setInterval(refrescar, 5000);

    });
    function refrescar(){
      $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
    }
    function obtenerIdEliminar(id,uuid){
      document.getElementById('txtId').value = id;
      document.getElementById('txtUuid').value = uuid;
    }

  </script>
  <script> var ruta = "../../";</script>
  <script src="../../../js/sb-admin-2.min.js"></script>

</body>

</html>

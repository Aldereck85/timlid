<?php
session_start();
  if(isset($_GET['id'])){
    $id =  $_GET['id'];
    if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 1 || $_SESSION["FKRol"] == 3 || $_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 6)){
      require_once('../../../include/db-conn.php');

      $stmt = $conn->prepare('SELECT *, e.Estatus as EstatusEnvio FROM envios as e INNER JOIN paqueterias as p on e.FKPaqueteria = p.PKPaqueteria LEFT JOIN guias_envio as ge ON e.FKPaqueteria = ge.FKPaqueteria LEFT JOIN facturacion as f ON f.PKFacturacion = e.FKFactura WHERE e.PKEnvio= :id');
      $stmt->execute(array(':id'=>$id));
      $row = $stmt->fetch();

      $estatus = $row['EstatusEnvio'];
      $factura = $row['PKFacturacion'];
      $folio = $row['Folio'];
      $paqueteria = $row['Nombre_Comercial'];
      $idpaqueteria = $row['FKPaqueteria'];
      $idnumeroguia = $row['FKGuiaEnvio'];

      $modo = 0;
      $_SESSION['modoenvio'] = 0;

      if($estatus == 'Enviado' || $estatus == 'Cancelado'){
        $modo = 1;
        $_SESSION['modoenvio'] = 1;
      }

      if($estatus == 'Entregado'){
        $modo = 2;
        $_SESSION['modoenvio'] = 2;
      }

      if($row['PKGuiaEnvio'] == "")
        $guia_existe = 0;
      else
        $guia_existe = 1;



      //*****CALCULO DE GUIAS USADAS HASTA EL MOMENTO*****/////
    $stmt = $conn->prepare('SELECT Cajas_por_enviar, Piezas_por_enviar, Piezas_por_Caja  FROM productos_en_envio as pe
                                    LEFT JOIN productos as p ON p.PKProducto = pe.FKProducto
                                    LEFT JOIN unidad_medida as um ON um.PKUnidadMedida = p.FKUnidadMedida
                                    LEFT JOIN inventario as i ON i.FKProducto = pe.FKProducto
                                        WHERE pe.FKEnvio = :id GROUP BY pe.FKProducto ORDER BY p.FKCategoria');
    $stmt->execute(array(':id' => $id));
    $row = $stmt->fetchAll();

    $cajas = 0;
    $cajas_piezas_sueltas = 0;
    for($x = 0; $x < count($row);$x++) {
      $cajas = $cajas + $row[$x]['Cajas_por_enviar'];

      if($row[$x]['Piezas_por_enviar'] > 0)
        $cajas_piezas_sueltas = $cajas_piezas_sueltas + (($row[$x]['Piezas_por_enviar'] / $row[$x]['Piezas_por_Caja']) + 0.10 );
    }

    $cajastotales = $cajas + ceil($cajas_piezas_sueltas);
    //*****FIN CALCULO DE GUIAS USADAS HASTA EL MOMENTO*****/////

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

  <title>Timlid | Agregar envío</title>

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
      $ruteEdit = "../central_notificaciones/";
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
            <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-truck-loading"></i> Agregar envios</h1>
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
                          <div class="form-group">
                              <div class="row">
                                <div class="col-lg-6">
                                  <label for="usr">Folio de factura:</label>
                                  <input type="text" class="form-control"  value="<?=$folio;?>" disabled>
                                </div>
                                <div class="col-lg-6">
                                  <label for="usr">Paqueteria:</label>
                                  <input type="text" class="form-control"  value="<?=$paqueteria;?>" disabled>
                                </div>
                              </div>
                              <br>
                              <?php if($guia_existe == 0) {
                                  echo '<div class="alert alert-warning" role="alert">
                                          No está dada de alta ninguna guía de está paquetería, favor de darla de alta para poder modificar el estatus del pedido.
                                        </div>';
                                }
                              ?>
                              <div class="row">
                                  <div class="col-lg-3">
                                    <label for="usr">Número de guías:</label>
                                    <input type="number" id="numero_guias" name="numero_guias" class="form-control numeric-only"  min="<?=$cajastotales-3;?>" max="<?=$cajastotales+3;?>" value="<?=$cajastotales;?>" <?php if($modo == 1 || $modo == 2) echo 'disabled';?>>
                                  </div>
                                  <div class="col-lg-3">
                                    <label for="usr">Tipo de guía:</label>
                                    <?php
                                      $stmt = $conn->prepare('SELECT * FROM guias_envio WHERE FKPaqueteria= :id');
                                      $stmt->execute(array(':id'=>$idpaqueteria));
                                      $row_guias = $stmt->fetchAll();

                                      echo "<select name='cmbTipoGuia' id='cmbTipoGuia' class='form-control' required ";

                                      if($modo == 1 || $modo == 2) echo 'disabled';

                                      echo ">";

                                      foreach ($row_guias as $rg) {
                                        echo "<option value='".$rg['PKGuiaEnvio']."' ";

                                        if($idnumeroguia == $rg['PKGuiaEnvio'])
                                          echo "selected";

                                        echo ">".$rg['Descripcion']."</option>";
                                      }
                                      echo "</select>";
                                    ?>
                                  </div>
                                  <div class="col-lg-4">
                                    <label for="usr">Estatus del envio:</label>
                                    <select name="cmbEstatus" id="cmbEstatus" class="form-control" <?php if($guia_existe == 0) echo 'disabled'; if($modo == 2) echo 'disabled';?>>
                                      <option value="Cancelado" <?php if($estatus == "Cancelado") echo "selected"; ?> >Cancelado</option>
                                      <option value="En proceso" <?php if($estatus == "En proceso") echo "selected"; ?> >En proceso</option>
                                      <option value="Enviado" <?php if($estatus == "Enviado") echo "selected"; ?> >Enviado</option>
                                      <option value="Entregado" <?php if($estatus == "Entregado") echo "selected"; ?> >Entregado</option>
                                    </select>
                                    <span id="alertaEstatus" style="color:#d9534f;"></span>
                                    <span id="opciones_devolucion" style="display: none;">
                                      <input type="checkbox" name="guias" id="guias" value="guias"> Guías &nbsp &nbsp &nbsp &nbsp
                                      <input type="checkbox" name="productos_c" id="productos_c" value="productos"> Productos
                                    </span>
                                  </div>
                                  <div class="col-lg-2">
                                    <button type="button" class="btn btn-primary" id="btnCambiarEstatus" style="position: relative;top: 30px; width: 100%;" <?php if($guia_existe == 0) echo 'disabled'; if($modo == 2) echo 'disabled';?>>Cambiar estatus</button>
                                  </div>
                              </div>
                          </div>
                          <br>
                          <div class="row">
                            <div class="col-lg-8">
                              <label for="usr">Producto:</label>
                              <select name="cmbProducto" id="chosen" class="form-control" required <?php if($modo == 1 || $modo == 2) echo 'disabled';?>>
                                  <option value="">Elegir opción</option>
                                      <?php
                                          $stmt = $conn->prepare('SELECT p.PKProducto,p.Descripcion,p.Clave FROM facturacion as f INNER JOIN cotizacion as c ON c.PKCotizacion = f.FKCotizacion INNER JOIN detallecotizacion as dc ON dc.FKCotizacion = c.PKCotizacion INNER JOIN productos as p ON p.PKProducto = dc.FKProducto WHERE f.PKFacturacion = :id');
                                          $stmt->execute(array(':id'=>$factura));
                                      ?>
                                      <?php foreach($stmt as $option) : ?>
                                           <option value="<?php echo $option['PKProducto']; ?>"><?php echo $option['Clave']." ".$option['Descripcion']; ?></option>
                                      <?php endforeach; ?>
                              </select>
                            </div>
                            <div class="col-lg-4">
                              <div>
                                <div class="row" id="divCantidad">
                                  <div class="col-lg-6">
                                    <label for="usr">Cantidad de cajas a enviar:</label>
                                    <input type='text' value='0' class='form-control' disabled>
                                  </div>
                                  <div class="col-lg-6">
                                    <label for="usr">Cantidad de piezas:</label>
                                    <input type='text' value='0' class='form-control' disabled>
                                  </div>
                                </div>

                              </div>
                            </div>
                          </div>
                          <br>
                          <input type="hidden" name="txtId" id="txtId" value="<?=$id;?>">
                          <input type="hidden" name="txtFolio" id="txtFolio" value="<?=$factura;?>">
                          <input type="hidden" name="txtPedido" id="txtPedido" value="1">
                          <button type="submit" class="btn btn-success float-right" id="btnAgregar" name="btnAgregar" <?php if($modo == 1 || $modo == 2) echo 'disabled';?>>Agregar</button>
                        </form>
                        <br><br><br>
                        <div class="row">
                          <div class="col-lg-6">
                            Producto
                          </div>
                          <div class="col-lg-2">
                            Cajas
                          </div>
                          <div class="col-lg-2">
                            Piezas
                          </div>
                          <div class="col-lg-2">
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

  <script>
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
  <script> var ruta = "../../";</script>
  <script src="../../../js/sb-admin-2.min.js"></script>

</body>

</html>

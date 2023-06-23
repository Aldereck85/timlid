<?php
session_start();
  if(isset($_SESSION["Usuario"])){
    require_once('../../../include/db-conn.php');
    require_once('../../../include/functions_Api.php');
    $api = new API();
    if(isset ($_POST['btnAgregar'])){

      $idTipoCFDI = $_POST['cmbTipoCFDI'];
      $cliente = $_POST['cmbRazonSocial'];
      $idUsoCFDI = $_POST['cmbUsoCFDI'];
      $serie = $_POST['txtSerie'];
      $folio = $_POST['txtFolio'];
      $idMetodoPago = $_POST['cmbMetodoPago'];
      $idTipoPago = $_POST['cmbTipoPago'];
      $idMoneda = $_POST['cmbMoneda'];
      $cotizacion = $_POST['cmbCotizacion'];
      $subtotal = 0;
      $idSerie = "";
      switch($serie){
        case 'F':
          $idSerie = '5594';
        break;
        case 'R':
          $idSerie = '5595';
        break;
        case 'C':
          $idSerie = '5596';
        break;
        case 'N':
          $idSerie = '5597';
        break;
        case 'FH':
          $idSerie = '5599';
        break;
        case 'NC':
          $idSerie = '5600';
        break;
        case 'DO':
          $idSerie = '5601';
        break;
        case 'RA':
          $idSerie = '5602';
        break;
        case 'ND':
          $idSerie = '5603';
        break;

      }

      $stmt = $conn->prepare('SELECT Clave FROM monedas WHERE PKMoneda = :id');
      $stmt->bindValue(':id',$idMoneda);
      $stmt->execute();
      $moneda = $stmt->fetch()['Clave'];

      $stmt = $conn->prepare('SELECT Clave FROM tipo_cfdi WHERE PKTipoCFDI = :id');
      $stmt->bindValue(':id',$idTipoCFDI);
      $stmt->execute();
      $tipoCFDI = $stmt->fetch()['Clave'];

      $stmt = $conn->prepare('SELECT Clave FROM uso_cfdi WHERE PKUsoCFDI = :id');
      $stmt->bindValue(':id',$idUsoCFDI);
      $stmt->execute();
      $usoCFDI = $stmt->fetch()['Clave'];

      $stmt = $conn->prepare('SELECT Clave FROM metodo_pagos WHERE PKMetodoPago = :id');
      $stmt->bindValue(':id',$idMetodoPago);
      $stmt->execute();
      $metodoPago = $stmt->fetch()['Clave'];

      $stmt = $conn->prepare('SELECT Clave FROM tipo_pago WHERE PKTipoPago = :id');
      $stmt->bindValue(':id',$idTipoPago);
      $stmt->execute();
      $tipoPago = $stmt->fetch()['Clave'];

      try{
        $conceptos = [];

        $stmt = $conn->prepare('SELECT UID FROM domicilio_fiscal WHERE PKDomicilioFiscal = :id');
        $stmt->bindValue(':id',$cliente);
        $stmt->execute();
        $uid = $stmt->fetch()['UID'];
        $traslados = [];
        $retenidos = [];
        $locales = [];
        $tasaTraslados = 0;
        $tasaRetenidos = 0;
        $tasaLocales = 0;

        //conceptos
        $stmt = $conn->prepare('SELECT dc.*,cs.Clave AS claveSAT,pr.*,um.FKClaveSAT AS unidadSAT,csu.Clave AS claveUnidadSAT, csu.Descripcion AS descripcionUnidadSAT FROM detallecotizacion AS dc
                                INNER JOIN productos AS pr ON dc.FKProducto = pr.PKProducto
                                INNER JOIN claves_sat AS cs ON pr.FKClaveSAT = cs.PKClaveSAT
                                INNER JOIN unidad_medida AS um ON pr.FKUnidadMedida = um.PKUnidadMedida
                                INNER JOIN claves_sat_unidades AS csu ON um.FKClaveSAT = csu.PKClaveSATUnidad
                                WHERE dc.FKCotizacion = :id');
        $stmt->bindValue(':id',$cotizacion);
        $stmt->execute();

        //impuestos

        $stmt1 = $conn->prepare('SELECT i.ClaveSAT AS claveS,i.Nombre,i.TipoImpuesto,di.Tasa,di.FKProducto,pr.Descripcion FROM detalleimpuesto AS di
                                LEFT JOIN impuesto AS i ON di.FKImpuesto = i.PKImpuesto
                                LEFT JOIN cotizacion AS c ON di.FKCotizacion = c.PKCotizacion
                                LEFT JOIN productos AS pr ON di.FKProducto = pr.PKProducto
                                WHERE di.FKCotizacion = :id');
        $stmt1->bindValue(':id',$cotizacion);
        $stmt1->execute();
        $productos = $stmt->fetchAll();

        $impuestos = $stmt1->fetchAll();
        $x = 0;
        $tempT = [];
        $tempR = [];
        $tempL = [];
        $tempP = [];
        $prueba = [];
        for ($i=0; $i < count($productos); $i++) {
          $subtotal = $productos[$i]['Precio'] * $productos[$i]['Cantidad'];
          $tempP [] = [
            'SAT' => $productos[$i]['claveSAT'],
            'Cantidad' => $productos[$i]['Cantidad'],
            'ClaveUnidad' => $productos[$i]['claveUnidadSAT'],
            'Unidad' => $productos[$i]['descripcionUnidadSAT'],
            'Precio' =>$productos[$i]['Precio'],
            'Importe' => $subtotal,
            'Descripcion' => $productos[$i]['Descripcion'],
          ];
          for ($j=0; $j < count($impuestos); $j++) {
            if($productos[$i]['FKProducto'] == $impuestos[$j]['FKProducto']){
              if($impuestos[$j]['TipoImpuesto'] == 1){
                $tempT[] = [
                  'Descripcion' => $productos[$i]['Descripcion'],
                  'ImpuestoT' => $impuestos[$j]['Nombre'],
                  'TasaT' => $impuestos[$j]['Tasa'],
                  'ClaveSATT' => $impuestos[$j]['claveS']
                ];
              }else if($impuestos[$j]['TipoImpuesto'] == 2){
                $tempR[] = [
                  'Descripcion' => $productos[$i]['Descripcion'],
                  'ImpuestoR' => $impuestos[$j]['Nombre'],
                  'TasaR' => $impuestos[$j]['Tasa'],
                  'ClaveSATR' => $impuestos[$j]['claveS']
                ];
              }else{
                $tempL[] = [
                'Descripcion' => $productos[$i]['Descripcion'],
                'ImpuestoL' => $impuestos[$j]['Nombre'],
                'TasaL' => $impuestos[$j]['Tasa'],
                'ClaveSATL' => $impuestos[$j]['claveS']];
              }

            }
          }
        }

        $x = 0;
        foreach ($tempP as $key => $value) {
          $conceptos[$x] = [
            'ClaveProdServ' => $value['SAT'],
            'Cantidad' => $value['Cantidad'],
            'ClaveUnidad' => $value['ClaveUnidad'],
            'Unidad' => $value['Unidad'],
            'ValorUnitario' => $value['Precio'],
            'Importe' => number_format($value['Importe'],6,'.',''),
            'Descripcion' => $value['Descripcion'],
            'Descuento' => '0',
            'Impuestos' => [
              'Traslados' => [],
              'Retenidos' => [],
              'Locales' => []
            ],
          ];

          foreach ($tempT as $key1 => $value1) {
            if($value['Descripcion'] == $value1['Descripcion']){
              $conceptos[$x]['Impuestos']['Traslados'][] = [
                'Base' => number_format($value['Importe'],6,'.',''),
                'Impuesto' => $value1['ClaveSATT'],
                'TipoFactor' => 'Tasa',
                'TasaOCuota' => number_format(($value1['TasaT']/100),2,'.',''),
                'Importe' => number_format((($value['Importe']) * ($value1['TasaT']/100)),6,'.','')
              ];
            }
          }

          foreach ($tempR as $key1 => $value1) {
            if($value['Descripcion'] == $value1['Descripcion']){
              $conceptos[$x]['Impuestos']['Retenidos'][] = [
                'Base' => number_format($value['Importe'],6,'.',''),
                'Impuesto' => $value1['ClaveSATR'],
                'TipoFactor' => 'Tasa',
                'TasaOCuota' => number_format($value1['TasaR']/100,2,'.',''),
                'Importe' => number_format((($value['Importe']) * ($value1['TasaR']/100)),6,'.','')
              ];
            }
          }

          foreach ($tempL as $key1 => $value1) {
            if($value['Descripcion'] == $value1['Descripcion']){
              $conceptos[$x]['Impuestos']['Locales'][] = [
                'Impuesto' => $value1['ImpuestoL'],
                'TasaOCuota' => number_format($value1['TasaL']/100,2,'.','')
              ];
            }
          }

          $x++;
        }

        $stmt = $conn->prepare('INSERT INTO facturacion (FKDomicilioFiscal,Serie,Referencia,Total,FKCotizacion) VALUES (:cliente,:serie,:referencia,:total,:cotizacion)');
        $stmt->bindValue(':cliente',$cliente);
        $stmt->bindValue(':serie',$serie);
        $stmt->bindValue(':referencia',$folio);
        $stmt->bindValue(':total',$subtotal);
        $stmt->bindValue(':cotizacion',$cotizacion);
        $stmt->execute();
        $idLast = $conn->lastInsertId();

        $fields = [
          'Receptor' => ['UID' => $uid],
          'TipoDocumento' => $tipoCFDI,
          'UsoCFDI' => $usoCFDI,
          'Redondeo' => 2,
          'Conceptos' => $conceptos,
          'FormaPago' => $tipoPago,
          'MetodoPago' => $metodoPago,
          'Moneda' => $moneda,
          'CondicionesDePago' => '',
          'Serie' => $idSerie,
          'NumOrder' => $folio,
          'EnviarCorreo' => 'true',
          'InvoiceComments' => ''
        ];

        $jsonfield = json_encode($fields);

        $api->AgregarCFDI($jsonfield);

        $facturas = json_decode($api->ListarCFDI());

        for ($i=0; $i < count($facturas->data); $i++) {

          if($facturas->data[$i]->NumOrder == $folio){
            $UUID = $facturas->data[$i]->UUID;
            $serieFac = $facturas->data[$i]->Folio;
            $version = $facturas->data[$i]->Version;
            $fechaFac = $facturas->data[$i]->FechaTimbrado;
          }
        }

        $stmt = $conn->prepare('UPDATE facturacion SET UUID= :uuid,Folio= :folio, Fecha_Timbrado= :fecha, Version= :version WHERE PKFacturacion = :id');
        $stmt->bindValue(':uuid',$UUID);
        $stmt->bindValue(':folio',$serieFac);
        $stmt->bindValue(':fecha',$fechaFac);
        $stmt->bindValue(':version',$version);
        $stmt->bindValue(':id',$idLast);
        $stmt->execute();

        //cotizacion
        $stmt = $conn->prepare('UPDATE cotizacion SET Facturado= :estado WHERE PKCotizacion = :id');
        $stmt->bindValue(':estado', 1);
        $stmt->bindValue(':id',$cotizacion);
        $stmt->execute();

        //bitacora
        $stmt = $conn->prepare('INSERT INTO bitacora_cotizaciones (FKUsuario,Fecha_Movimiento,FKMensaje,FKCotizacion) VALUES (:user,:fecha,:mensaje,:cotizacion)');
        $stmt->bindValue(':user', $_SESSION['PKUsuario']);
        $stmt->bindValue(':fecha', date('Y-m-d'));
        $stmt->bindValue(':mensaje', 9);
        $stmt->bindValue(':cotizacion', $cotizacion);

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

  <title>Timlid | Agregar CFDI</title>

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

  <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha256-c4gVE6fn+JRKMRvqjoDp+tlG4laudNYrXI1GncbfAYY=" crossorigin="anonymous"></script>
  <link href="../../../css/chosen.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" integrity="sha256-EH/CzgoJbNED+gZgymswsIOrM9XhIbdSJ6Hwro09WE4=" crossorigin="anonymous" />

  <style type="text/css">
    .header-color {
    font-size: 18px;
    color: #fff;
    line-height: 1.4;
    background-color: #6c7ae0;

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
    background-color: #2433a8;
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
      $titulo = "";
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
            <h1 class="h3 mb-0 text-gray-800">Crear CFDI</h1>
          </div>


          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header">
                  Tarjeta de unidad de medida
                </div>
                <div class="card-body">
                    <div class="row">
                      <div class="col-lg-12">
                        <form action="" method="post">
                          <input type="hidden" name="txtCantidadProductos" id="txtCantidadProductos" value="">
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-3">
                                <label for="usr">Tipo de CFDI:*</label>
                                <select class="form-control" name="cmbTipoCFDI" id="cmbTipoCFDI" required>
                                  <option value="">Selecciona el tipo de CFDI...</option>
                                  <?php
                                    /*$stmt = $conn->prepare('SELECT * FROM tipo_cfdi');
                                    $stmt->execute();
                                    while($row = $stmt->fetch()){*/
                                  ?>
                                  <option value="<?//=$row['PKTipoCFDI']; ?>"><?//=$row['Descripcion']; ?></option>
                                <?php //} ?>
                                </select>
                              </div>
                              <div class="col-lg-3">
                                <label for="usr">Nombre comercial:*</label>
                                <select class="form-control" name="cmbCliente" id="cmbCliente" required>
                                  <option value="">Seleccione un nombre comercial...</option>
                                  <?php
                                    /*$stmt = $conn->prepare('SELECT * FROM clientes WHERE FKEstatus= :estatus');
                                    $stmt->bindValue(':estatus',4);
                                    $stmt->execute();
                                    while($row = $stmt->fetch()){*/
                                  ?>
                                  <option value="<?//=$row['PKCliente']; ?>"><?//=$row['Nombre_comercial']; ?></option>
                                <?php //} ?>
                                </select>
                              </div>
                              <div class="col-lg-3">
                                <label for="usr">Razon social:*</label>
                                <select class="form-control" name="cmbRazonSocial" id="cmbRazonSocial" required>
                                  <option value="">Seleccione una razon social...</option>

                                </select>
                              </div>
                              <div class="col-lg-3">
                                <label for="usr">Uso CFDI:*</label>
                                <select class="form-control" name="cmbUsoCFDI" id="cmbUsoCFDI" required>
                                  <option value="">Selecciona un uso CFDI...</option>
                                  <?php
                                    /*$stmt = $conn->prepare('SELECT * FROM uso_cfdi');
                                    $stmt->execute();
                                    while($row = $stmt->fetch()){*/
                                  ?>
                                  <option value="<?//=$row['PKUsoCFDI']; ?>"><?//=$row['Uso_CFDI']; ?></option>
                                <?php //} ?>
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-4">
                                <label for="">Serie:*</label>
                                <input class="form-control" type="text" name="txtSerie" id="txtSerie" readonly required>
                              </div>
                              <div class="col-lg-4">
                                <label for="">Folio:*</label>
                                <input class="form-control" type="text" name="txtFolio" id="txtFolio" readonly required>
                              </div>
                              <div class="col-lg-4">
                                <label for="usr">Método de pago:*</label>
                                <select class="form-control" name="cmbMetodoPago" id="cmbMetodoPago" required>
                                  <option value="">Selecciona un método de pago...</option>
                                  <?php
                                    /*$stmt = $conn->prepare('SELECT * FROM metodo_pagos');
                                    $stmt->execute();
                                    while($row = $stmt->fetch()){*/
                                  ?>
                                  <option value="<?//=$row['PKMetodoPago']; ?>"><?//=$row['Descripcion']; ?></option>
                                <?php //} ?>
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-4">
                                <label for="">Forma de pago:*</label>
                                <select class="form-control" name="cmbTipoPago" id="cmbTipoPago" required>
                                  <option value="">Selecciona una forma de pago...</option>
                                  <?php
                                    /*$stmt = $conn->prepare('SELECT * FROM formas_pago');
                                    $stmt->execute();
                                    while($row = $stmt->fetch()){*/
                                  ?>
                                  <option value="<?//=$row['PKFormaPago']; ?>"><?//=$row['Descripcion']; ?></option>
                                <?php //} ?>
                                </select>
                              </div>
                              <div class="col-lg-4">
                                <label for="">Moneda:*</label>
                                <select class="form-control" name="cmbMoneda" id="cmbMoneda" required>
                                  <option value="">Selecciona una moneda...</option>
                                  <?php
                                    /*$stmt = $conn->prepare('SELECT * FROM monedas WHERE Estatus = :estatus');
                                    $stmt->bindValue(':estatus',1);
                                    $stmt->execute();
                                    while($row = $stmt->fetch()){*/
                                  ?>
                                  <option value="<?//=$row['PKMoneda']; ?>"><?//=$row['Clave']; ?></option>
                                <?php //} ?>
                                </select>
                              </div>
                              <div class="col-lg-4">
                                <label for="">Cotización:*</label>
                                <select class="form-control" name="cmbCotizacion" id="cmbCotizacion" required>
                                  <option value="">Seleccione una cotizacion...</option>

                                <?php //} ?>
                                </select>
                              </div>
                            </div>
                          </div>
                          <button type="submit" class="btn btn-success float-right" name="btnAgregar">Crear</button>
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
  $(document).ready(function(){
    $('#cmbTipoCFDI').chosen();
  });
  $(document).ready(function(){
    $('#cmbCliente').chosen();
  });
  $(document).ready(function(){
    $('#cmbUsoCFDI').chosen();
  });
  $(document).ready(function(){
    $('#cmbMetodoPago').chosen();
  });
  $(document).ready(function(){
    $('#cmbTipoPago').chosen();
  });
  $(document).ready(function(){
    $('#cmbMoneda').chosen();
  });
  $(document).ready(function(){
    $('#cmbCotizacion').chosen();
  });
  $(document).ready(function(){
    $('#cmbRazonSocial').chosen();
  });
  $(document).ready(function(){
    $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
      setInterval(refrescar, 5000);
    });
    function refrescar(){
      $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
    }
  $(document).ready(function(){
    $('#cmbTipoCFDI').change(function(){
      switch($('#cmbTipoCFDI').val()){
        case '1':
          $('#txtSerie').val('F');
          $('#txtSerie1').val('F');
        break;
        case '2':
          $('#txtSerie').val('FH');
          $('#txtSerie1').val('FH');
        break;
        case '3':
          $('#txtSerie').val('R');
          $('#txtSerie1').val('R');
        break;
        case '4':
          $('#txtSerie').val('NC');
          $('#txtSerie1').val('NC');
        break;
        case '5':
          $('#txtSerie').val('DO');
          $('#txtSerie1').val('DO');
        break;
        case '6':
          $('#txtSerie').val('RA');
          $('#txtSerie1').val('RA');
        break;
        case '7':
          $('#txtSerie').val('N');
          $('#txtSerie1').val('N');
        break;
        case '8':
          $('#txtSerie').val('ND');
          $('#txtSerie1').val('ND');
        break;
        case '9':
          $('#txtSerie').val('C');
          $('#txtSerie1').val('C');
        break;
      }
      var cadena = "serie=" + $('#txtSerie').val();
      $.ajax({
        type: 'POST',
        url: 'getFolio.php',
        data: cadena,
        success:function(r){
          $('#txtFolio').val(r)
        }
      });
    });
  });
//cmbCotizacion
  $(document).ready(function(){
    $('#cmbCliente').change(function(){
      var idCliente = $('#cmbCliente').val();
      var cadena = "cliente="+idCliente;
      $.ajax({
        type: 'POST',
        url: 'getRazonSocial.php',
        data: cadena,
        success:function(r){
          $("#cmbRazonSocial").html(r).trigger("chosen:updated");
        }
      });
    });
  });

  $(document).ready(function(){
    $('#cmbRazonSocial').change(function(){
      var idRazonSocial = $('#cmbRazonSocial').val();
      var cadena = "razonSocial="+idRazonSocial;
      $.ajax({
        type: 'POST',
        url: 'getCotizacion.php',
        data: cadena,
        success:function(r){
          $("#cmbCotizacion").html(r).trigger("chosen:updated");
        }
      });
    });
  });


  </script>
  <script> var ruta = "../../";</script>
  <script src="../../../js/sb-admin-2.min.js"></script>

</body>

</html>

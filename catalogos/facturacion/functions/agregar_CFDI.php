<?php
session_start();
  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 1)){
    require_once('../../../include/db-conn.php');
    require_once('../../../include/functions_Api.php');
    $api = new API();
    if(isset ($_POST['idCotizacionF'])){
      $cotizacion = $_POST['idCotizacionF'];
      $stmt = $conn->prepare('SELECT * FROM cotizacion WHERE PKCotizacion= :id');
      $stmt->execute(array(':id'=>$cotizacion));
      $row = $stmt->fetch();
      $Subtotal = $row['Subtotal'];
      $ImporteTotal = $row['ImporteTotal'];
    }else{
      $cotizacion = -1;
    }
    if(isset ($_POST['btnAgregar'])){
      $idTipoCFDI = $_POST['cmbTipoCFDI'];
      $cliente = $_POST['cmbRazonSocial'];
      $idUsoCFDI = $_POST['cmbUsoCFDI'];
      $serie = $_POST['txtSerie'];
      $folio = $_POST['txtFolio'];
      $idMetodoPago = $_POST['cmbMetodoPago'];
      $idTipoPago = $_POST['cmbTipoPago'];
      $idMoneda = $_POST['cmbMoneda'];
      //$cotizacion = $_POST['cmbCotizacion'];
      $cuenta = $_POST['cmbCuenta'];
      $subtotal = 0;
      $idSerie = "";

      $idProductos = $_POST['inp_productos'];
      $cantidades = $_POST['inp_piezas'];
      $precios = $_POST['inp_precio'];

      for ($i=0; $i < count($idProductos); $i++) {
        $stmt = $conn->prepare('SELECT Tasa FROM impuestos_productos WHERE FKProducto = :id');
        $stmt->bindValue(':id',$idProductos[$i]);
        $stmt->execute();
        $row = $stmt->fetch();
      }

      $stmt = $conn->prepare('INSERT INTO facturacion (FKDomicilioFiscal,Serie,Referencia,FKCotizacion,Estatus) VALUES (:cliente,:serie,:referencia,:cotizacion,:estatus)');
      $stmt->bindValue(':cliente',$cliente);
      $stmt->bindValue(':serie',$serie);
      $stmt->bindValue(':referencia',$folio);
      $stmt->bindValue(':cotizacion',$cotizacion);
      $stmt->bindValue(':estatus','Pendiente');
      $stmt->execute();
      $idLast = $conn->lastInsertId();


      for ($i=0; $i < count($idProductos); $i++) {
        $stmt = $conn->prepare('INSERT INTO facturas_productos (FKFacturacion,FKProducto,Cantidad,Precio_Unitario) VALUES (:factura,:producto,:cantidad,:precio)');
        $stmt->bindValue(':factura',$idLast);
        $stmt->bindValue(':producto',$idProductos[$i]);
        $stmt->bindValue(':cantidad',$cantidades[$i]);
        $stmt->bindValue(':precio',$precios[$i]);
        $stmt->execute();

      }


      print_r($productos);
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
        $stmt = $conn->prepare('SELECT fp.*,cs.Clave AS claveSAT,pr.*,um.FKClaveSAT AS unidadSAT,csu.Clave AS claveUnidadSAT, csu.Descripcion AS descripcionUnidadSAT FROM facturas_productos AS fp
                                INNER JOIN productos AS pr ON fp.FKProducto = pr.PKProducto
                                INNER JOIN claves_sat AS cs ON pr.FKClaveSAT = cs.PKClaveSAT
                                INNER JOIN unidad_medida AS um ON pr.FKUnidadMedida = um.PKUnidadMedida
                                INNER JOIN claves_sat_unidades AS csu ON um.FKClaveSAT = csu.PKClaveSATUnidad
                                WHERE fp.FKFacturacion = :id');
        $stmt->bindValue(':id',$idLast);
        $stmt->execute();

        $productos = $stmt->fetchAll();
/*


        $stmt = $conn->prepare('SELECT dc.*,cs.Clave AS claveSAT,pr.*,um.FKClaveSAT AS unidadSAT,csu.Clave AS claveUnidadSAT, csu.Descripcion AS descripcionUnidadSAT FROM detallecotizacion AS dc
                                INNER JOIN productos AS pr ON dc.FKProducto = pr.PKProducto
                                INNER JOIN claves_sat AS cs ON pr.FKClaveSAT = cs.PKClaveSAT
                                INNER JOIN unidad_medida AS um ON pr.FKUnidadMedida = um.PKUnidadMedida
                                INNER JOIN claves_sat_unidades AS csu ON um.FKClaveSAT = csu.PKClaveSATUnidad
                                WHERE dc.FKCotizacion = :id');
        $stmt->bindValue(':id',$cotizacion);
        $stmt->execute();
        */

        //impuestos
        for ($i=0; $i < count($idProductos); $i++) {
          $stmt1 = $conn->prepare('SELECT i.ClaveSAT AS claveS,i.Nombre,i.TipoImpuesto,ip.Tasa,ip.FKProducto,pr.Descripcion,pr.PKProducto FROM impuestos_productos AS ip
                                  LEFT JOIN impuesto AS i ON ip.FKImpuesto = i.PKImpuesto
                                  LEFT JOIN productos AS pr ON ip.FKProducto = pr.PKProducto
                                  WHERE pr.PKProducto = :id');
          $stmt1->bindValue(':id',$idProductos[$i],PDO::PARAM_INT);
          $stmt1->execute();

          $impuestos = $stmt1->fetchAll();
        }

        /*
        $stmt1 = $conn->prepare('SELECT i.ClaveSAT AS claveS,i.Nombre,i.TipoImpuesto,di.Tasa,di.FKProducto,pr.Descripcion FROM detalleimpuesto AS di
                                LEFT JOIN impuesto AS i ON di.FKImpuesto = i.PKImpuesto
                                LEFT JOIN cotizacion AS c ON di.FKCotizacion = c.PKCotizacion
                                LEFT JOIN productos AS pr ON di.FKProducto = pr.PKProducto
                                WHERE di.FKCotizacion = :id');
        $stmt1->bindValue(':id',$cotizacion);
        $stmt1->execute();
        */
        //$productos = $stmt->fetchAll();

        //$impuestos = $stmt1->fetchAll();

        $x = 0;
        $tempT = [];
        $tempR = [];
        $tempL = [];
        $tempP = [];
        $prueba = [];
        for ($i=0; $i < count($productos); $i++) {
          $subtotal = $precios[$i] * $cantidades[$i];
          $tempP [] = [
            'SAT' => $productos[$i]['claveSAT'],
            'Cantidad' => $cantidades[$i],
            'ClaveUnidad' => $productos[$i]['claveUnidadSAT'],
            'Unidad' => $productos[$i]['descripcionUnidadSAT'],
            'Precio' => $precios[$i],
            'Importe' => $subtotal,
            'Descripcion' => $productos[$i]['Descripcion'],
          ];
          for ($j=0; $j < count($impuestos); $j++) {
            if($productos[$i]['PKProducto'] == $impuestos[$j]['PKProducto']){
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
            $total = $facturas->data[$i]->Total;
          }
        }


        $stmt = $conn->prepare('UPDATE facturacion SET UUID= :uuid,Folio= :folio, Fecha_Timbrado= :fecha, Version= :version, Total= :total WHERE PKFacturacion = :id');
        $stmt->bindValue(':uuid',$UUID);
        $stmt->bindValue(':folio',$serieFac);
        $stmt->bindValue(':fecha',$fechaFac);
        $stmt->bindValue(':version',$version);
        $stmt->bindValue(':total',$total);
        $stmt->bindValue(':id',$idLast);
        $stmt->execute();


        //cuentas_facturas
        $stmt = $conn->prepare('INSERT INTO cuentas_facturas (FKCuenta,FKFactura) VALUES (:cuenta,:factura)');
        $stmt->bindValue(':cuenta',$cuenta);
        $stmt->bindValue(':factura',$idLast);
        $stmt->execute();

        /*
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
        */

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
                  <div class="col-lg-4" style="margin-top:5px">
                    <h1 class="h3 text-gray-800" style="font-weight:bold">Crear factura</h1>
                  </div>
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
                                <input class="form-control" type="text" name="" value="Factura" readonly>
                                <input type="hidden" name="cmbTipoCFDI" value="1">
                                <!--
                                <select class="form-control" name="cmbTipoCFDI" id="cmbTipoCFDI" required>
                                  <option value="">Selecciona el tipo de CFDI...</option>
                                  <?php/*
                                    $stmt = $conn->prepare('SELECT * FROM tipo_cfdi');
                                    $stmt->execute();
                                    while($row = $stmt->fetch()){*/
                                  ?>
                                  <option value="<?//=$row['PKTipoCFDI']; ?>"><?//=$row['Descripcion']; ?></option>
                                <?php //} ?>
                                </select>
                              -->
                              </div>
                              <div class="col-lg-3">
                                <label for="usr">Nombre comercial:*</label>
                                <?php
                                  if($cotizacion < 0){
                                ?>
                                <select class="form-control" name="cmbCliente" id="cmbCliente" required>
                                  <option value="">Seleccione un nombre comercial...</option>
                                  <?php
                                    $stmt = $conn->prepare('SELECT PKCliente,Nombre_comercial FROM clientes WHERE FKEstatus= :estatus');
                                    $stmt->bindValue(':estatus',4);
                                    $stmt->execute();
                                    while($row = $stmt->fetch()){
                                  ?>
                                  <option value="<?=$row['PKCliente']; ?>"><?=$row['Nombre_comercial']; ?></option>
                                <?php } ?>
                                </select>
                              <?php }else{
                                $stmt = $conn->prepare('SELECT cl.PKCliente, cl.Nombre_comercial FROM clientes AS cl
                                                        INNER JOIN cotizacion ON cl.PKCliente = FKCliente
                                                        WHERE PKCotizacion = :id');
                                $stmt->bindValue(':id',$cotizacion);
                                $stmt->execute();

                                $row = $stmt->fetch();
                                $idCliente = $row['PKCliente'];
                              ?>
                                <input class="form-control" type="text" name="" value="<?=$row['Nombre_comercial']; ?>" readonly>
                                <input type="hidden" name="cmbCliente" value="<?=$row['PKCliente']; ?>">
                              <?php } ?>
                              </div>
                              <div class="col-lg-3">
                                <label for="usr">Razon social:*</label>
                                <?php if($cotizacion < 0){ ?>
                                <select class="form-control" name="cmbRazonSocial" id="cmbRazonSocial" required>
                                  <option value="">Seleccione una razon social...</option>

                                </select>
                              <?php }else{
                                $stmt = $conn->prepare('SELECT PKDomicilioFiscal,Razon_Social FROM domicilio_fiscal AS df
                                                        LEFT JOIN clientes AS c ON df.FKCliente = c.PKCliente
                                                        WHERE df.FKCliente = :cliente AND df.UID IS NOT NULL');
                                $stmt->bindValue(':cliente',$idCliente);
                                $stmt->execute();

                                $row = $stmt->fetch();
                                $idDomicilio = $row['PKDomicilioFiscal'];
                                $razonSocial = $row['Razon_Social'];
                                ?>
                                <input class="form-control" type="text" name="" id="txtRazonSocial" value="<?=$razonSocial; ?>" readonly>
                                <input type="hidden" name="cmbRazonSocial" value="<?=$idDomicilio; ?>">
                              <?php } ?>
                              </div>
                              <div class="col-lg-3">
                                <label for="usr">Uso CFDI:*</label>
                                <select class="form-control" name="cmbUsoCFDI" id="cmbUsoCFDI" required>
                                  <option value="">Selecciona un uso CFDI...</option>
                                  <?php
                                    $stmt = $conn->prepare('SELECT * FROM uso_cfdi');
                                    $stmt->execute();
                                    while($row = $stmt->fetch()){
                                  ?>
                                  <option value="<?=$row['PKUsoCFDI']; ?>"><?=$row['Uso_CFDI']; ?></option>
                                <?php } ?>
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
                                    $stmt = $conn->prepare('SELECT * FROM metodo_pagos');
                                    $stmt->execute();
                                    while($row = $stmt->fetch()){
                                  ?>
                                  <option value="<?=$row['PKMetodoPago']; ?>"><?=$row['Descripcion']; ?></option>
                                <?php } ?>
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-4">
                                <label for="cmbCuenta">Cuenta:</label>
                                <select class="form-control" name="cmbCuenta" id="cmbCuenta" required>
                                  <option value="">Seleccione una cuenta:</option>
                                  <?php
                                    $stmt = $conn->prepare('SELECT PKCuenta, Nombre FROM cuentas_bancarias_empresa');
                                    $stmt->execute();
                                    while($row = $stmt->fetch()){
                                  ?>
                                  <option value="<?=$row['PKCuenta']; ?>"><?=$row['Nombre']; ?></option>
                                <?php } ?>
                                </select>
                              </div>
                              <div class="col-lg-4">
                                <label for="">Forma de pago:*</label>
                                <select class="form-control" name="cmbTipoPago" id="cmbTipoPago" required>
                                  <option value="">Selecciona una forma de pago...</option>
                                  <?php
                                    $stmt = $conn->prepare('SELECT * FROM formas_pago');
                                    $stmt->execute();
                                    while($row = $stmt->fetch()){
                                  ?>
                                  <option value="<?=$row['PKFormaPago']; ?>"><?=$row['Descripcion']; ?></option>
                                <?php } ?>
                                </select>
                              </div>
                              <div class="col-lg-4">
                                <label for="">Moneda:*</label>
                                <select class="form-control" name="cmbMoneda" id="cmbMoneda" required>
                                  <option value="">Selecciona una moneda...</option>
                                  <?php
                                    $stmt = $conn->prepare('SELECT * FROM monedas WHERE Estatus = :estatus');
                                    $stmt->bindValue(':estatus',1);
                                    $stmt->execute();
                                    while($row = $stmt->fetch()){
                                  ?>
                                  <option value="<?=$row['PKMoneda']; ?>"><?=$row['Clave']; ?></option>
                                <?php } ?>
                                </select>
                              </div>
                              <!--
                              <div class="col-lg-3">
                                <label for="">Cotización:*</label>
                                <select class="form-control" name="cmbCotizacion" id="cmbCotizacion" required>
                                  <option value="">Seleccione una cotizacion...</option>

                                <?php //} ?>
                                </select>
                              </div>
                              -->
                            </div>
                          </div>
                          <div class="form-group" id="productosdiv">
                            <div class="row">
                            <div class="col-lg-6">
                              <label for="usr">Producto:</label>
                              <select name="cmbProducto" id="cmbProducto" class="form-control">
                                  <option value="">Elegir opción</option>
                                      <?php
                                          $stmt = $conn->prepare('SELECT p.PKProducto,p.Descripcion,p.Clave FROM productos as p INNER JOIN producto_tipo as pt ON p.PKProducto = pt.FKProducto WHERE pt.FKTipoProducto = 1');
                                          $stmt->execute();
                                      ?>
                                      <?php foreach($stmt as $option) : ?>
                                           <option value="<?php echo $option['PKProducto']; ?>"><?php echo $option['Clave']." ".$option['Descripcion']; ?></option>
                                      <?php endforeach; ?>
                              </select>
                              <span style="color: #d9534f;display: none;position: absolute;" id="alertaProducto">Ingresa un producto</span>
                            </div>

                            <div class="col-lg-4">
                              <div>
                                <div class="row" id="divCantidad">
                                  <div class="col-lg-6">
                                    <label for="usr">Piezas:</label>
                                    <input type='number' value='' name="txtPiezas" id="txtPiezas" class='form-control numeric-only' disabled>
                                    <span style="color: #d9534f;display: none;position: absolute;" id="alertaPiezas" onkeydown="insertProduct(event)">Ingresa la cantidad de piezas</span>
                                  </div>
                                  <div class="col-lg-6">
                                    <label for="usr">Precio:</label>
                                    <input type='number' value='' name="txtPrecio" id="txtPrecio" class='form-control' readonly>
                                  </div>
                                </div>

                              </div>
                            </div>
                             <div class="col-lg-2">
                              <input type="hidden" name="txtImpuestos" id="txtImpuestos" value="" />
                              <button type="button" class="btn btn-info" style="position: relative; top: 30px;width: 100%;" id="agregarProducto">Agregar</button>
                            </div>

                          </div>
                          </div>
                          <br><br>
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
                                    if($cotizacion > 0){
                                      $stmt = $conn->prepare('SELECT dc.FKProducto, dc.Cantidad, dc.Precio, p.Clave, p.Descripcion, p.PrecioUnitario FROM detallecotizacion  as dc INNER JOIN productos as p ON p.PKProducto = dc.FKProducto WHERE dc.FKCotizacion= :id');
                                      $stmt->execute(array(':id'=>$cotizacion));
                                      $numero_productos = $stmt->rowCount();
                                      $rowp = $stmt->fetchAll();
                                      $impuestos = array();
                                      $x = 0;

                                      foreach ($rowp as $rp) {

                                        $stmt = $conn->prepare('SELECT i.PKImpuesto, i.Nombre, i.TipoImpuesto, i.TipoImporte, i.Operacion, di.FKProducto, di.Tasa FROM detalleimpuesto  as di INNER JOIN impuesto as i ON i.PKImpuesto = di.FKImpuesto WHERE di.FKCotizacion= :id AND FKProducto = :idProducto');
                                        $stmt->execute(array(':id'=>$cotizacion, ':idProducto'=>$rp['FKProducto']));
                                        $rowi = $stmt->fetchAll();
                                        //print_r($rowi);

                                        $totalProducto = $rp['Cantidad'] * $rp['Precio'];
                                        echo  "<tr id='idProducto_".$rp['FKProducto']."' class='text-center'>".
                                                "<th id='nombreproducto_".$rp['FKProducto']."'>".$rp['Clave']." ".$rp['Descripcion']."</th>".
                                                "<th id='piezas_".$rp['FKProducto']."'>".$rp['Cantidad']."</th>".
                                                "<input type='hidden' id='piezaAnt_".$rp['FKProducto']."' value='".$rp['Cantidad']."' />".
                                                "<input type='hidden' name='inp_productos[]' value='".$rp['FKProducto']."' />".
                                                "<th>Pieza</th>".
                                                "<th id='precio_".$rp['FKProducto']."'>".$rp['Precio']."</th>".
                                                "<input type='hidden' name='inp_precio[]' value='".$rp['Precio']."' />".
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
                                                      $impuestos[$found_key][1] = $impuestos[$found_key][1] + (($rp['Cantidad'] * $rp['Precio']) * ($ri['Tasa'] / 100));
                                                    else
                                                      $impuestos[$found_key][1] = $impuestos[$found_key][1] + $ri['Tasa'];

                                                    $impuestos[$found_key][2] = $ri['Nombre'];
                                                    $impuestos[$found_key][3] = $ri['TipoImpuesto'];
                                                    $impuestos[$found_key][4] = $ri['Operacion'];
                                                }
                                                else{
                                                    $impuestos[$x][0] = $ri['PKImpuesto'];
                                                    if($ri['TipoImporte'] == 1)
                                                      $impuestos[$x][1] = ($rp['Cantidad'] * $rp['Precio']) * ($ri['Tasa'] / 100);
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
                                      }
                                  ?>
                                </tbody>
                                  <tr>
                                    <th colspan="3"></th>
                                    <th>Subtotal:</th>
                                    <th colspan="2" style="text-align: right;">$ <span id="Subtotal"><?php if($cotizacion > 0){ echo number_format($Subtotal,2);}else{ echo "0.00";} ?></span></th>
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
                                      if($cotizacion > 0){
                                        foreach ($impuestos as $imp) {
                                            $IniImpuesto = explode(" ", $imp[2]);
                                            echo "<tr id='".$IniImpuesto[0]."_".$imp[0]."'>".
                                                    "<th colspan='3'></th>".
                                                    "<th style='text-align: right;'>".$imp[2]."</th>".
                                                    "<th colspan='2' style='text-align: right;'>$ <span id='Impuesto_".$imp[0]."' name='".$imp[3]."_".$imp[4]."' class='ImpuestoTot'>".number_format($imp[1],2)."</span></th>".
                                                    "<th>&nbsp</th>".
                                                 "</tr>";
                                        }
                                      }
                                    ?>
                                  </tbody>
                                    <tr class="total">
                                      <th colspan="3" class="redondearAbajoIzq"></th>
                                      <th>Total:</th>
                                      <th colspan="2" style="text-align: right;">$ <span id="Total"><?php if($cotizacion > 0){ echo number_format($ImporteTotal,2);}else{ echo "0.00";} ?></span></th>
                                      <input type="hidden" name="Total" value="<?= $ImporteTotal; ?>">
                                      <th class="redondearAbajoDer">&nbsp</th>
                                    </tr>
                              </table>
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
  var cuenta = 0;
  var ctzn = <?=$cotizacion; ?>;
  $(document).ready(function(){
    $('#cmbTipoCFDI').chosen();
    $('#cmbCliente').chosen();
    $('#cmbUsoCFDI').chosen();
    $('#cmbMetodoPago').chosen();
    $('#cmbTipoPago').chosen();
    $('#cmbMoneda').chosen();
    $('#cmbCotizacion').chosen();
    $('#cmbRazonSocial').chosen();
    $('#cmbCuenta').chosen();
    $('#cmbProducto').chosen();
    $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
      setInterval(refrescar, 5000);

    });
    function refrescar(){
      $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
    }
    $("#cmbProducto").prop('disabled', true).trigger("chosen:updated");
    if(ctzn > 0){
      $('#productosdiv').hide();
    }


  $(document).ready(function(){
    var cadena = "";
    /*if(ctzn > 0){
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
      cadena = "serie=" + $('#txtSerie').val();
      $.ajax({
        type: 'POST',
        url: 'getFolio.php',
        data: cadena,
        success:function(r){
          $('#txtFolio').val(r)
        }
      });
    });
  }else{*/
    $('#txtSerie').val('F');
    $('#txtSerie1').val('F');
    cadena = "serie=" + $('#txtSerie').val();
    $.ajax({
      type: 'POST',
      url: 'getFolio.php',
      data: cadena,
      success:function(r){
        $('#txtFolio').val(r)
      }
    });
  //}
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

  $("#cmbCliente").change(function(){
      var cliente = parseInt($("#cmbCliente").val());

      if(cliente > 0){
        $("#txtPiezas").prop("disabled",false);
        $("#agregarProducto").prop("disabled",false);
        $("#cmbProducto").prop('disabled', false).trigger("chosen:updated");
        $('#cmbRazon').prop('disabled', false);

        $.ajax({
          type: 'POST',
          url: 'getRazonSocial.php',
          data: { idCliente : cliente},
          success: function(data){
              $("#cmbRazon").html(data);
        }});

      }
      else{
        $("#txtPiezas").prop("disabled",true);
        $("#agregarProducto").prop("disabled",true);
        $("#cmbProducto").prop('disabled', true).trigger("chosen:updated");
        $('#cmbRazon').prop('disabled', true);

      }

      $("#txtPrecio").val("");
  });

  $("#cmbProducto").change(function(){
      var idProducto = $("#cmbProducto").val();
      var idCliente = $("#cmbCliente").val();

      $.ajax({
          type: 'POST',
          url: '../../cotizaciones/functions/valoresCotizacion.php',
          data: { idProducto : idProducto, idCliente : idCliente},
          success: function(data){
              var datos = JSON.parse(data);
              $("#txtPrecio").val(datos.Precio);
              $("#txtImpuestos").val(datos.Impuestos);
      }});
  });

  $("#agregarProducto").click(function(){

      var idProducto = parseInt($("#cmbProducto").val());
      var Producto = $("#cmbProducto").children("option:selected").text();
      var Piezas = parseInt($("#txtPiezas").val());
      var Precio = parseFloat($("#txtPrecio").val());
      var TotalProducto, nuevo_elemento, Piezas_old, TotalProducto_old = 0, nuevo_impuesto;
      var SubtotalNum = numeral($("#Subtotal").html());
      var Subtotal, Operacion;
      var PrecioF, TotalProductoF, TotalProducto_format;
      var IVAProducto, IVAProductoF, IVATotalProductoF, IVAant, IVATotalProducto;
      var ImpuestosCompleto = $("#txtImpuestos").val();

      $("#cmbCiente").prop('disabled', true).trigger("chosen:updated");

      if(isNaN(idProducto)){
        $("#alertaProducto").css("display","block");
        setTimeout(function(){ $("#alertaProducto").css("display", "none");}, 2000);
        return;
      }

      if(Piezas < 1 || isNaN(Piezas)){
        $("#alertaPiezas").css("display","block");
        setTimeout(function(){ $("#alertaPiezas").css("display", "none");}, 2000);
        return;
      }

      if ($('#idProducto_' + idProducto).length) {
        //cuando ya se agregó el producto
        Piezas_old = parseInt($("#piezasUnic_" + idProducto).val());
        TotalProducto_format = numeral($("#totalproducto_" + idProducto).html());
        TotalProducto_old = TotalProducto_format.value();

        var PiezasImp = Piezas;
        Piezas = Piezas + Piezas_old;
        TotalProducto = (Piezas * Precio).toFixed(2);

        PrecioF = $.number( Precio, 2, '.',',');
        TotalProductoF = $.number( TotalProducto, 2,'.',',');

        var impuestosOld = $("#impuestos_" + idProducto).html();

        var impuestoXProducto, impuestoCantidad, arrayImp, TipoTasa;
        var idImpuestoOld, totImpIndividual, impuestoTotalant, impuestoTotNuevo, impuestoTotNuevoF;

        $('#impuestos_' + idProducto + ' > span').each(function(index, span) {
            impuestoXProducto = $(this).attr("id");
            arrayImp = impuestoXProducto.split("_");
            idImpuestoOld = arrayImp[3];
            TipoTasa = arrayImp[2];
            impuestoCantidad = parseFloat($("#impAgregado_" + idProducto + "_" + idImpuestoOld).val());

            if(TipoTasa == 1)
              totImpIndividual = parseFloat((PiezasImp * Precio) * (impuestoCantidad/100));
            if(TipoTasa == 2 || TipoTasa == 3)
              totImpIndividual = 0.00;

            impuestoTotalant = numeral($("#Impuesto_" + idImpuestoOld).html());
            impuestoTotNuevo = totImpIndividual + impuestoTotalant.value();
            impuestoTotNuevoF = $.number( impuestoTotNuevo, 2, '.',',');
            $("#Impuesto_" + idImpuestoOld).html(impuestoTotNuevoF);

        });

        $('#idProducto_' + idProducto).empty();
        nuevo_elemento =    "<th id='nombreproducto_" + idProducto + "'>" + Producto + "</th>" +
                            "<th id='piezas_" + idProducto + "'><input type='number' id='piezasUnic_" + idProducto + "' name='inp_piezas[]' value='" + Piezas + "' class='modificarnumero numeros-solo' min='1' >" + "</th>" +
                            "<input type='hidden' id='piezaAnt_" + idProducto + "' value='" + Piezas + "' />" +
                            "<input type='hidden' name='inp_productos[]' value='" + idProducto + "' />" +
                            "<th>Pieza</th>" +
                            "<th id='precio_" + idProducto + "'>" + PrecioF + "</th>" +
                            "<input type='hidden' name='inp_precio[]' value='" + Precio + "' />" +
                            "<th>" +
                            "<span id='impuestos_" + idProducto + "'> " +
                            impuestosOld +
                            "</span>" +
                            "</th>" +
                            "<th id='totalproducto_" + idProducto + "'>" + TotalProductoF + "</th>" +
                            "<input type='hidden' name='inp_total_producto[]' value='" + TotalProducto + "' />" +
                            "<th><button type='button' class='btn eliminarProductos' id='" + idProducto + "'>X</button></th>";
        $('#idProducto_' + idProducto).append(nuevo_elemento);

      } else {
        //cuando se ingresa un nuevo producto
        TotalProducto = (Piezas * Precio).toFixed(2);
        descuento = "";

        PrecioF = $.number( Precio, 2, '.',',');
        TotalProductoF = $.number( TotalProducto, 2,'.',',');

        nuevo_elemento = "<tr id='idProducto_" + idProducto + "' class='text-center'>" +
                            "<th id='nombreproducto_" + idProducto + "'>" + Producto + "</th>" +
                            "<th id='piezas_" + idProducto + "'><input type='number' name='inp_piezas[]' id='piezasUnic_" + idProducto + "' value='" + Piezas + "' class='modificarnumero numeros-solo' min='1' >" + "</th>" +
                            "<input type='hidden' id='piezaAnt_" + idProducto + "' value='" + Piezas + "' />" +
                            "<input type='hidden' name='inp_productos[]' value='" + idProducto + "' />" +
                            "<th>Pieza</th>" +
                            "<th id='precio_" + idProducto + "'>" + PrecioF + "</th>" +
                            "<input type='hidden' name='inp_precio[]' value='" + Precio + "' />" +
                            "<th>" +
                            ImpuestosCompleto +
                             "</th>";

                            nuevo_elemento += "<th id='totalproducto_" + idProducto + "'>" + TotalProductoF + "</th>" +
                            "<input type='hidden' name='inp_total_producto[]' value='" + TotalProducto + "' />" +
                            "<th><button type='button' class='btn eliminarProductos' id='" + idProducto + "'>X</button></th>" +
                          "</tr>";

                          $('#lstProductos').append(nuevo_elemento);

                          $('#impuestos_' + idProducto + ' > span').each(function(index, span) {

                              impuestoXProducto = $(this).attr("id");
                              arrayImp = impuestoXProducto.split("_");
                              idImpuestoOld = arrayImp[3];
                              TipoTasa = arrayImp[2];
                              impuestoCantidad = parseFloat($("#impAgregado_" + idProducto + "_" + idImpuestoOld).val());

                              if(TipoTasa == 1)
                                totImpIndividual = parseFloat((PiezasImp * Precio) * (impuestoCantidad/100));
                              if(TipoTasa == 2 || TipoTasa == 3)
                                totImpIndividual = 0.00;

                              if(TipoTasa == 1){
                                var TotalImpuesto = TotalProducto * ( impuestoCantidad / 100);
                              }
                              if(TipoTasa == 2){
                                var TotalImpuesto = impuestoCantidad ;
                              }
                              if(TipoTasa == 3){
                                var TotalImpuesto = 0.00 ;
                              }

                              var TotalImpuestoF;
                              var TotalImpuestoGen;
                              Operacion = $("#OperacionUnica_" + idProducto + "_" + idImpuestoOld).val();
                              if($('#lstimpuestos').find('#' + arrayImp[0] + "_" + idImpuestoOld).length == 1) {

                                var sumaImpuesto = numeral($('#Impuesto_' + idImpuestoOld).html());

                                TotalImpuestoGen = TotalImpuesto + parseFloat(sumaImpuesto.value());
                                TotalImpuestoF = $.number( TotalImpuestoGen, 2, '.',',');
                                  nuevo_impuesto =  "<th colspan='3'></th>" +
                                                      "<th style='text-align: right;'>" + arrayImp[0] + "</th>" +
                                                      "<th colspan='2' style='text-align: right;'>$ <span id='Impuesto_" + idImpuestoOld + "' name='" + arrayImp[1] + "_" + Operacion + "' class='ImpuestoTot'>" + TotalImpuestoF + "</span></th>" +
                                                      "<th>&nbsp</th>";
                                  $('#' + arrayImp[0] + '_' + idImpuestoOld).empty();
                                  $('#' + arrayImp[0] + '_' + idImpuestoOld).append(nuevo_impuesto);
                              }else{
                                //nuevo impuesto
                                TotalImpuestoF = $.number( TotalImpuesto, 2, '.',',');
                                nuevo_impuesto =   "<tr id='" + arrayImp[0] + "_" + idImpuestoOld + "'>" +
                                                      "<th colspan='3'></th>" +
                                                      "<th style='text-align: right;'>" + arrayImp[0] + "</th>" +
                                                      "<th colspan='2' style='text-align: right;'>$ <span id='Impuesto_" + idImpuestoOld + "' name='" + arrayImp[1] + "_" + Operacion + "' class='ImpuestoTot'>" + TotalImpuestoF + "</span></th>" +
                                                      "<th>&nbsp</th>" +
                                                  "</tr>";
                                $('#lstimpuestos').append(nuevo_impuesto);

                              }

                          });

      }

      Subtotal = SubtotalNum.value() + parseFloat(TotalProducto) - TotalProducto_old;
      var SubtotalF = $.number( Subtotal, 2, '.',',');
      $('#Subtotal').empty();
      $('#Subtotal').append(SubtotalF);

      //calculo de impuestos
      var suma = 0.00, cantidadImp, tipoimp, arrayTipoImp;
      $('#lstimpuestos > tr').each(function(index, tr) {
          cantidadImp = numeral($(this).find(".ImpuestoTot").html());
          tipoimp = $(this).find(".ImpuestoTot").attr("name");
          arrayTipoImp = tipoimp.split("_");

          if(arrayTipoImp[0] == 1){
              suma = suma + cantidadImp.value();
          }
          if(arrayTipoImp[0] == 2){
              suma = suma - cantidadImp.value();
          }
          if(arrayTipoImp[0] == 3){

              if(arrayTipoImp[1] == 1)
                suma = suma + cantidadImp.value();

              if(arrayTipoImp[1] == 2)
                suma = suma - cantidadImp.value();
          }

      });

      var Total = Subtotal + suma;
      var TotalF = $.number( Total, 2, '.',',');
      $('#Total').empty();
      $('#Total').append(TotalF);

      $('#cmbProducto').val("").trigger('chosen:updated');
      $('#txtPiezas').val("");
      $('#txtPrecio').val("");
      cuenta++;

   });

   //Eliminar productos
   $(document).on("click",".eliminarProductos",function(){
       var idProducto = this.id;
       var TotalProductoFormat, SubtotalFormat, IVAFormat;
       TotalProductoFormat = numeral($("#totalproducto_" + idProducto).html());
       var TotalProducto = TotalProductoFormat.value();
       SubtotalFormat = numeral($("#Subtotal").html());
       var Subtotal_old = SubtotalFormat.value();

       var Subtotal = Subtotal_old - parseFloat(TotalProducto);
       var SubtotalF = $.number( Subtotal, 2, '.',',');
       $('#Subtotal').empty();
       $('#Subtotal').append(SubtotalF);

       var impuestoXProducto, impuestoCantidad, arrayImp, TipoTasa, Impuesto;
       var idImpuestoOld, totImpIndividual, impuestoTotalant, impuestoTotNuevo, impuestoTotNuevoF;

       $('#impuestos_' + idProducto + ' > span').each(function(index, span) {
             impuestoXProducto = $(this).attr("id");
             arrayImp = impuestoXProducto.split("_");
             Impuesto = arrayImp[0];
             TipoTasa = arrayImp[2];
             idImpuestoOld = arrayImp[3];
             impuestoCantidad = parseFloat($("#impAgregado_" + idProducto + "_" + idImpuestoOld).val());

             if(TipoTasa == 1)
               totImpIndividual = parseFloat(TotalProducto * (impuestoCantidad/100));
             if(TipoTasa == 2)
               totImpIndividual = impuestoCantidad;
             if(TipoTasa == 3)
               totImpIndividual = 0.00;
             impuestoTotalant = numeral($("#Impuesto_" + idImpuestoOld).html());
             impuestoTotNuevo = impuestoTotalant.value() - totImpIndividual;
             impuestoTotNuevoF = $.number( impuestoTotNuevo, 2, '.',',');
             $("#Impuesto_" + idImpuestoOld).html(impuestoTotNuevoF);

             if(impuestoTotNuevo <= 0 && TipoTasa != 3){
                 $("#" + Impuesto + "_" + idImpuestoOld).remove();
              }
              else{
                 $("#Impuesto_" + idImpuestoOld).html(impuestoTotNuevoF);
              }

              if(TipoTasa == 3){
               cuentaIVAexento--;
                 if(cuentaIVAexento == 0)
                   $("#" + Impuesto + "_" + idImpuestoOld).remove();
              }

       });

       //calculo de impuestos
       var suma = 0.00, cantidadImp, tipoimp, arrayTipoImp;
       $('#lstimpuestos > tr').each(function(index, tr) {
           cantidadImp = numeral($(this).find(".ImpuestoTot").html());
           tipoimp = $(this).find(".ImpuestoTot").attr("name");
           arrayTipoImp = tipoimp.split("_");

           if(arrayTipoImp[0] == 1){
               suma = suma + cantidadImp.value();
           }
           if(arrayTipoImp[0] == 2){
               suma = suma - cantidadImp.value();
           }
           if(arrayTipoImp[0] == 3){

               if(arrayTipoImp[1] == 1)
                 suma = suma + cantidadImp.value();

               if(arrayTipoImp[1] == 2)
                 suma = suma - cantidadImp.value();
           }

       });

       var Total = Subtotal + suma;
       var TotalF = $.number( Total, 2, '.',',');
       $('#Total').empty();
       $('#Total').append(TotalF);

       $('#idProducto_' + idProducto).remove();
       cuenta--;

       if(cuenta == 0)
         $("#cmbCliente").prop('disabled', false).trigger("chosen:updated");

       $("#catalogoImpuestos").css("display","none");
   });

   $("#txtPiezas").on('keydown', function (e) {
         if(e.keyCode==13){

         var idProducto = parseInt($("#cmbProducto").val());
         var Producto = $("#cmbProducto").children("option:selected").text();
         var Piezas = parseInt($("#txtPiezas").val());
         var Precio = parseFloat($("#txtPrecio").val());
         var TotalProducto, nuevo_elemento, Piezas_old, TotalProducto_old = 0, nuevo_impuesto;
         var SubtotalNum = numeral($("#Subtotal").html());
         var Subtotal, Operacion;
         var PrecioF, TotalProductoF, TotalProducto_format;
         var IVAProducto, IVAProductoF, IVATotalProductoF, IVAant, IVATotalProducto;
         var ImpuestosCompleto = $("#txtImpuestos").val();

         $("#cmbCiente").prop('disabled', true).trigger("chosen:updated");

         if(isNaN(idProducto)){
           $("#alertaProducto").css("display","block");
           setTimeout(function(){ $("#alertaProducto").css("display", "none");}, 2000);
           return;
         }

         if(Piezas < 1 || isNaN(Piezas)){
           $("#alertaPiezas").css("display","block");
           setTimeout(function(){ $("#alertaPiezas").css("display", "none");}, 2000);
           return;
         }

         if ($('#idProducto_' + idProducto).length) {
           //cuando ya se agregó el producto
           Piezas_old = parseInt($("#piezasUnic_" + idProducto).val());
           TotalProducto_format = numeral($("#totalproducto_" + idProducto).html());
           TotalProducto_old = TotalProducto_format.value();

           var PiezasImp = Piezas;
           Piezas = Piezas + Piezas_old;
           TotalProducto = (Piezas * Precio).toFixed(2);

           PrecioF = $.number( Precio, 2, '.',',');
           TotalProductoF = $.number( TotalProducto, 2,'.',',');

           var impuestosOld = $("#impuestos_" + idProducto).html();

           var impuestoXProducto, impuestoCantidad, arrayImp, TipoTasa;
           var idImpuestoOld, totImpIndividual, impuestoTotalant, impuestoTotNuevo, impuestoTotNuevoF;

           $('#impuestos_' + idProducto + ' > span').each(function(index, span) {
               impuestoXProducto = $(this).attr("id");
               arrayImp = impuestoXProducto.split("_");
               idImpuestoOld = arrayImp[3];
               TipoTasa = arrayImp[2];
               impuestoCantidad = parseFloat($("#impAgregado_" + idProducto + "_" + idImpuestoOld).val());

               if(TipoTasa == 1)
                 totImpIndividual = parseFloat((PiezasImp * Precio) * (impuestoCantidad/100));
               if(TipoTasa == 2 || TipoTasa == 3)
                 totImpIndividual = 0.00;

               impuestoTotalant = numeral($("#Impuesto_" + idImpuestoOld).html());
               impuestoTotNuevo = totImpIndividual + impuestoTotalant.value();
               impuestoTotNuevoF = $.number( impuestoTotNuevo, 2, '.',',');
               $("#Impuesto_" + idImpuestoOld).html(impuestoTotNuevoF);

           });

           $('#idProducto_' + idProducto).empty();
           nuevo_elemento =    "<th id='nombreproducto_" + idProducto + "'>" + Producto + "</th>" +
                               "<th id='piezas_" + idProducto + "'><input type='number' id='piezasUnic_" + idProducto + "' name='inp_piezas[]' value='" + Piezas + "' class='modificarnumero numeros-solo' min='1' >" + "</th>" +
                               "<input type='hidden' id='piezaAnt_" + idProducto + "' value='" + Piezas + "' />" +
                               "<input type='hidden' name='inp_productos[]' value='" + idProducto + "' />" +
                               "<th>Pieza</th>" +
                               "<th id='precio_" + idProducto + "'>" + PrecioF + "</th>" +
                               "<input type='hidden' name='inp_precio[]' value='" + Precio + "' />" +
                               "<th>" +
                               "<span id='impuestos_" + idProducto + "'> " +
                               impuestosOld +
                               "</span>" +
                               "</th>" +
                               "<th id='totalproducto_" + idProducto + "'>" + TotalProductoF + "</th>" +
                               "<input type='hidden' name='inp_total_producto[]' value='" + TotalProducto + "' />" +
                               "<th><button type='button' class='btn eliminarProductos' id='" + idProducto + "'>X</button></th>";
           $('#idProducto_' + idProducto).append(nuevo_elemento);

         } else {
           //cuando se ingresa un nuevo producto
           TotalProducto = (Piezas * Precio).toFixed(2);
           descuento = "";

           PrecioF = $.number( Precio, 2, '.',',');
           TotalProductoF = $.number( TotalProducto, 2,'.',',');

           nuevo_elemento = "<tr id='idProducto_" + idProducto + "' class='text-center'>" +
                               "<th id='nombreproducto_" + idProducto + "'>" + Producto + "</th>" +
                               "<th id='piezas_" + idProducto + "'><input type='number' name='inp_piezas[]' id='piezasUnic_" + idProducto + "' value='" + Piezas + "' class='modificarnumero numeros-solo' min='1' >" + "</th>" +
                               "<input type='hidden' id='piezaAnt_" + idProducto + "' value='" + Piezas + "' />" +
                               "<input type='hidden' name='inp_productos[]' value='" + idProducto + "' />" +
                               "<th>Pieza</th>" +
                               "<th id='precio_" + idProducto + "'>" + PrecioF + "</th>" +
                               "<input type='hidden' name='inp_precio[]' value='" + Precio + "' />" +
                               "<th>" +
                               ImpuestosCompleto +
                                "</th>";

                               nuevo_elemento += "<th id='totalproducto_" + idProducto + "'>" + TotalProductoF + "</th>" +
                               "<input type='hidden' name='inp_total_producto[]' value='" + TotalProducto + "' />" +
                               "<th><button type='button' class='btn eliminarProductos' id='" + idProducto + "'>X</button></th>" +
                             "</tr>";

                             $('#lstProductos').append(nuevo_elemento);

                             $('#impuestos_' + idProducto + ' > span').each(function(index, span) {

                                 impuestoXProducto = $(this).attr("id");
                                 arrayImp = impuestoXProducto.split("_");
                                 idImpuestoOld = arrayImp[3];
                                 TipoTasa = arrayImp[2];
                                 impuestoCantidad = parseFloat($("#impAgregado_" + idProducto + "_" + idImpuestoOld).val());

                                 if(TipoTasa == 1)
                                   totImpIndividual = parseFloat((PiezasImp * Precio) * (impuestoCantidad/100));
                                 if(TipoTasa == 2 || TipoTasa == 3)
                                   totImpIndividual = 0.00;

                                 if(TipoTasa == 1){
                                   var TotalImpuesto = TotalProducto * ( impuestoCantidad / 100);
                                 }
                                 if(TipoTasa == 2){
                                   var TotalImpuesto = impuestoCantidad ;
                                 }
                                 if(TipoTasa == 3){
                                   var TotalImpuesto = 0.00 ;
                                 }

                                 var TotalImpuestoF;
                                 var TotalImpuestoGen;
                                 Operacion = $("#OperacionUnica_" + idProducto + "_" + idImpuestoOld).val();
                                 if($('#lstimpuestos').find('#' + arrayImp[0] + "_" + idImpuestoOld).length == 1) {

                                   var sumaImpuesto = numeral($('#Impuesto_' + idImpuestoOld).html());

                                   TotalImpuestoGen = TotalImpuesto + parseFloat(sumaImpuesto.value());
                                   TotalImpuestoF = $.number( TotalImpuestoGen, 2, '.',',');
                                     nuevo_impuesto =  "<th colspan='3'></th>" +
                                                         "<th style='text-align: right;'>" + arrayImp[0] + "</th>" +
                                                         "<th colspan='2' style='text-align: right;'>$ <span id='Impuesto_" + idImpuestoOld + "' name='" + arrayImp[1] + "_" + Operacion + "' class='ImpuestoTot'>" + TotalImpuestoF + "</span></th>" +
                                                         "<th>&nbsp</th>";
                                     $('#' + arrayImp[0] + '_' + idImpuestoOld).empty();
                                     $('#' + arrayImp[0] + '_' + idImpuestoOld).append(nuevo_impuesto);
                                 }else{
                                   //nuevo impuesto
                                   TotalImpuestoF = $.number( TotalImpuesto, 2, '.',',');
                                   nuevo_impuesto =   "<tr id='" + arrayImp[0] + "_" + idImpuestoOld + "'>" +
                                                         "<th colspan='3'></th>" +
                                                         "<th style='text-align: right;'>" + arrayImp[0] + "</th>" +
                                                         "<th colspan='2' style='text-align: right;'>$ <span id='Impuesto_" + idImpuestoOld + "' name='" + arrayImp[1] + "_" + Operacion + "' class='ImpuestoTot'>" + TotalImpuestoF + "</span></th>" +
                                                         "<th>&nbsp</th>" +
                                                     "</tr>";
                                   $('#lstimpuestos').append(nuevo_impuesto);

                                 }

                             });

         }

         Subtotal = SubtotalNum.value() + parseFloat(TotalProducto) - TotalProducto_old;
         var SubtotalF = $.number( Subtotal, 2, '.',',');
         $('#Subtotal').empty();
         $('#Subtotal').append(SubtotalF);

         //calculo de impuestos
         var suma = 0.00, cantidadImp, tipoimp, arrayTipoImp;
         $('#lstimpuestos > tr').each(function(index, tr) {
             cantidadImp = numeral($(this).find(".ImpuestoTot").html());
             tipoimp = $(this).find(".ImpuestoTot").attr("name");
             arrayTipoImp = tipoimp.split("_");

             if(arrayTipoImp[0] == 1){
                 suma = suma + cantidadImp.value();
             }
             if(arrayTipoImp[0] == 2){
                 suma = suma - cantidadImp.value();
             }
             if(arrayTipoImp[0] == 3){

                 if(arrayTipoImp[1] == 1)
                   suma = suma + cantidadImp.value();

                 if(arrayTipoImp[1] == 2)
                   suma = suma - cantidadImp.value();
             }

         });

         var Total = Subtotal + suma;
         var TotalF = $.number( Total, 2, '.',',');
         $('#Total').empty();
         $('#Total').append(TotalF);

         $('#cmbProducto').val("").trigger('chosen:updated');
         $('#txtPiezas').val("");
         $('#txtPrecio').val("");
         cuenta++;
         }
     });

     $(document).on("keyup",".modificarnumero",function(){
       this.value = this.value.replace(/[^0-9]/g,'');
     });

     //Modificar cantidad de  productos
     $(document).on("change",".modificarnumero",function(){

         this.value = this.value.replace('.','');
         var cantidadNueva;

         if(isNaN(this.value) || $.trim(this.value) == ''){
           cantidadNueva = parseInt(1);
           this.value = 1;
         }
         else{
           cantidadNueva = parseInt(this.value);
         }

         var arrayImp = this.id.split("_");
         var idProducto = arrayImp[1];
         cantidadAnterior = $("#piezaAnt_" + idProducto).val();
         $("#piezaAnt_" + idProducto).val(cantidadNueva);
         var cantidad = cantidadNueva - cantidadAnterior;

         var PrecioProducto = numeral($("#precio_" + idProducto).html());

         var TotalProducto = (cantidad * PrecioProducto.value()).toFixed(2);

         var TotalProductoAnt = numeral($("#totalproducto_" + idProducto).html());
         var TotalProductoFinFormat = parseFloat(TotalProducto) + parseFloat(TotalProductoAnt.value());
         var TotalProductoFin = $.number( TotalProductoFinFormat, 2,'.',',');

         $('#totalproducto_' + idProducto).html(TotalProductoFin);


         var SubtotalNum = numeral($("#Subtotal").html());
         Subtotal = SubtotalNum.value() + parseFloat(TotalProducto);
         var SubtotalF = $.number( Subtotal, 2, '.',',');
         $('#Subtotal').empty();
         $('#Subtotal').append(SubtotalF);

         var impuestoXProducto, impuestoCantidad, arrayImp, TipoTasa;
         var idImpuestoOld, totImpIndividual, impuestoTotalant, impuestoTotNuevo, impuestoTotNuevoF;

         $('#impuestos_' + idProducto + ' > span').each(function(index, span) {
             impuestoXProducto = $(this).attr("id");
             arrayImp = impuestoXProducto.split("_");
             idImpuestoOld = arrayImp[3];
             TipoTasa = arrayImp[2];
             impuestoCantidad = parseFloat($("#impAgregado_" + idProducto + "_" + idImpuestoOld).val());

             if(TipoTasa == 1){
               totImpIndividual = parseFloat((cantidad * PrecioProducto.value()) * (impuestoCantidad/100));
               //console.log(cantidad + "//" + PrecioProducto.value() + "//" + impuestoCantidad);
             }
             if(TipoTasa == 2 || TipoTasa == 3){
               totImpIndividual = 0.00;
             }

             impuestoTotalant = numeral($("#Impuesto_" + idImpuestoOld).html());
             //console.log(totImpIndividual + "/////" + impuestoTotalant.value());
             impuestoTotNuevo = totImpIndividual + impuestoTotalant.value();
             impuestoTotNuevoF = $.number( impuestoTotNuevo, 2, '.',',');
             $("#Impuesto_" + idImpuestoOld).html(impuestoTotNuevoF);

         });

         //calculo de impuestos
         var suma = 0.00, cantidadImp, tipoimp, arrayTipoImp;
         $('#lstimpuestos > tr').each(function(index, tr) {
             cantidadImp = numeral($(this).find(".ImpuestoTot").html());
             tipoimp = $(this).find(".ImpuestoTot").attr("name");
             arrayTipoImp = tipoimp.split("_");

             if(arrayTipoImp[0] == 1){
                 suma = suma + cantidadImp.value();
             }
             if(arrayTipoImp[0] == 2){
                 suma = suma - cantidadImp.value();
             }
             if(arrayTipoImp[0] == 3){

                 if(arrayTipoImp[1] == 1)
                   suma = suma + cantidadImp.value();

                 if(arrayTipoImp[1] == 2)
                   suma = suma - cantidadImp.value();
             }

         });

         var Total = Subtotal + suma;
         var TotalF = $.number( Total, 2, '.',',');
         $('#Total').empty();
         $('#Total').append(TotalF);


     });


  </script>
  <script> var ruta = "../../";</script>
  <script src="../../../js/sb-admin-2.min.js"></script>

</body>

</html>

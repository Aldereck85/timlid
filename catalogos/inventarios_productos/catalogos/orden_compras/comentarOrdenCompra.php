<?php
session_start();
require_once '../../../../include/db-conn.php';
$user = $_SESSION["Usuario"];
$PKEmpresa = $_SESSION["IDEmpresa"];

$id = $_GET["oc"];
$FKUsuario = $_SESSION["PKUsuario"];

$stmt = $conn->prepare("SELECT empresa_id FROM ordenes_compra WHERE id_encriptado = :id");
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch();

$GLOBALS["PKOrdenCompra"] = $row['empresa_id'];

if (isset($_SESSION["Usuario"])) {
    require_once '../../../../include/db-conn.php';
    $user = $_SESSION["Usuario"];

    if($GLOBALS["PKOrdenCompra"] != $PKEmpresa){
      header("location:../../../inventarios_productos/catalogos/orden_compras/");
    }
} else {
    header("location:../../../dashboard.php");
}

  //Logo de la empresa que emite la OC
  $stmtLogo = $conn->prepare("SELECT logo FROM empresas WHERE PKEmpresa = :empresa");
  $stmtLogo->bindValue(':empresa', $PKEmpresa, PDO::PARAM_INT);
  $stmtLogo->execute();
  $rowLogo = $stmtLogo->fetch();

  
  $ruta = $_ENV['RUTA_ARCHIVOS_READ'].$PKEmpresa.'/fiscales'.'/';

  $GLOBALS["ruta"] = $ruta;
  $GLOBALS["logotipo"] = $ruta.$rowLogo['logo'];

  $stmtProveedor = $conn->prepare("SELECT pv.PKProveedor 
                                  FROM ordenes_compra oc 
                                    inner join proveedores pv on oc.FKProveedor = pv.PKProveedor 
                                  where oc.PKOrdenCompra = (select PKOrdenCompra from ordenes_compra where id_encriptado = :id);");
  $stmtProveedor->bindValue(':id', $id, PDO::PARAM_INT);
  $stmtProveedor->execute();
  $rowProveedor = $stmtProveedor->fetch();

  $GLOBALS["PKPrveedor"] = $rowProveedor['PKProveedor'];

  //Datos generales de la orden de compra
  $stmt = $conn->prepare("SELECT oc.Referencia,
                                pv.NombreComercial,
                                oc.Importe,
                                DATE_FORMAT(oc.created_at, '%d/%m/%Y') as FechaCreacion,
                                DATE_FORMAT(oc.FechaEstimada, '%d/%m/%Y') as FechaEstimada,
                                s.Sucursal,
                                s.Calle,
                                s.numero_exterior as NumExt,
                                s.Prefijo,
                                s.numero_interior as NumInt,
                                s.Colonia,
                                s.Municipio,
                                ef.Estado,
                                ps.Pais,
                                s.Telefono,
                                em.RazonSocial as razon_social,
                                em.RFC as rfcCliente,
                                em.telefono as telefonoCliente,
                                @_subtotal := (select ifnull(sum(doc.Cantidad * doc.Precio),0) as subtotal
                                                from detalle_orden_compra doc
                                                where doc.FKOrdenCompra = (select PKOrdenCompra from ordenes_compra where id_encriptado = :id1)) as Subtotal,
                                @_impuestos := (select ifnull(sum((doc.Cantidad * doc.Precio) * (ip.Tasa / 100)),0) as totalImpuesto
                                                from detalle_orden_compra doc
                                                  inner join productos p on doc.FKProducto = p.PKProducto  
                                                  inner join info_fiscal_productos ifp on p.PKProducto = ifp.FKProducto
                                                  inner join impuestos_productos ip on ifp.PKInfoFiscalProducto = ip.FKInfoFiscalProducto
                                                  inner join impuesto i on ip.FKImpuesto = i.PKImpuesto
                                                  inner join tipos_impuestos ti on i.FKTipoImpuesto = ti.PKTipoImpuesto
                                                where doc.FKOrdenCompra = (select PKOrdenCompra from ordenes_compra where id_encriptado = :id2)) as Impuestos,
                                (@_subtotal+@_impuestos) as Total, 
                                oc.NotasProveedor as notas,
                                dfp2.RFC as rfcProveedor,
                                dfp2.Razon_Social as razonSocialPV,
                                pv.Telefono as telefonoPv,
                                pv.Email as emailPV,
                                concat(emOP.Nombres,' ',emOP.PrimerApellido,' ',emOP.SegundoApellido) as comprador, 
                                emOP.email as emailComprador,
                                emOP.Telefono as telefonoComprador                    
                            FROM ordenes_compra oc
                                  inner join proveedores pv on oc.FKProveedor = pv.PKProveedor
                                  inner join (select dfp.FKProveedor, dfp.RFC, dfp.Razon_Social from domicilio_fiscal_proveedor dfp where dfp.FKProveedor = ".$GLOBALS["PKPrveedor"]." limit 1) dfp2 on dfp2.FKProveedor = oc.FKProveedor
                                  inner join sucursales s on oc.FKSucursal = s.id
                                  inner join paises ps on s.pais_id = ps.PKPais
                                  inner join estados_federativos ef on s.estado_id = ef.PKEstado
                                  inner join empresas em on oc.empresa_id = em.PKEmpresa
                                  left join empleados emOP on oc.comprador_id = emOP.PKEmpleado
                            where oc.PKOrdenCompra = (select PKOrdenCompra from ordenes_compra where id_encriptado = :id5);");
  $stmt->bindValue(':id1', $id, PDO::PARAM_INT);
  $stmt->bindValue(':id2', $id, PDO::PARAM_INT);
  $stmt->bindValue(':id5', $id, PDO::PARAM_INT);
  $stmt->execute();
  $row = $stmt->fetch();

  $GLOBALS["referencia"] = $row['Referencia'];
  $GLOBALS["Subtotal"] = number_format($row['Subtotal'],2,'.',','); 
  $GLOBALS["ImporteTotal"] = number_format($row['Importe'],2,'.',',');
  $GLOBALS["FechaIngreso"] = $row['FechaCreacion'];
  $GLOBALS["FechaEstimada"] = $row['FechaEstimada'];
  $GLOBALS["Sucursal"] = $row['Sucursal'];
  $GLOBALS["Direccion"] = $row['Sucursal']. ' - ' .$row['Calle'].' '.$row['NumExt'].' Int.'.$row['NumInt'].'- '.$row['Prefijo'].', '.$row['Colonia'].', '.$row['Municipio'].', '.$row['Estado'].', '.$row['Pais'];
  $GLOBALS["NombreCliente"] = $row['razon_social'];
  $GLOBALS["RFCCliente"] = $row['rfcCliente'];
  $GLOBALS["TelefonoCliente"] = $row['telefonoCliente'];
  $GLOBALS["RFCProveedor"] = $row['rfcProveedor'];
  $GLOBALS["RazonSocialPV"] = $row['razonSocialPV'];
  $GLOBALS["TelefonoPv"] = $row['telefonoPv'];
  $GLOBALS["EmailPV"] = $row['emailPV'];
  $GLOBALS["NombreProveedor"] = $row['NombreComercial'];
  $GLOBALS["Telefono"] = $row['Telefono'];
  $GLOBALS["NotaComprador"] = $row['notas'];
  $GLOBALS["Comprador"] = $row['comprador'];
  $GLOBALS["EmailComprador"] = $row['emailComprador'];
  $GLOBALS["TelefonoComprador"] = $row['telefonoComprador'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="icon" type="image/png" href="../../../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Timlid | Comentar Orden de compra</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Page level plugins -->
  <script src="../../../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="../../../../vendor/datatables/dataTables.responsive.js"></script>
  <script src="../../../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../../../vendor/datatables/buttons.bootstrap4.min.js"></script>
  <script src="../../../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../../../vendor/jszip/jszip.min.js"></script>

  <!-- Page level custom scripts
    <script src="js/demo/datatables-demo.js"></script>
    -->

  <!-- Custom fonts for this template -->
  <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Custom styles for this template -->
  <link href="../../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../../css/slimselect.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../style/agregar_entrada.css">
  <link rel="stylesheet" href="../../style/pestanas_producto.css">
  <link rel="stylesheet" href="../../style/aceptar_ordenCompra.css">

  <!-- Custom scripts for all pages-->

  <script src="../../../../js/sb-admin-2.min.js"></script>
  <script src="../../../../js/scripts.js"></script>

  <!-- Custom styles for this page -->
  <link href="../../../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link rel="stylesheet" href="../../../../vendor/datatables/buttons.dataTables.css">
  <link href="../../../../vendor/datatables/buttons.bootstrap4.min.css" rel="stylesheet">
  <link href="../../../../css/stylesTable.css" rel="stylesheet">
  <link href="../../../../css/styles.css" rel="stylesheet">
  <link rel="stylesheet" href="../../../../css/croppie.css" />

  <script src="../../../../js/numeral.min.js" charset="utf-8"></script>
  <script src="../../../../js/croppie.js"></script>

  <script src="../../../../js/sweet/sweetalert2.js"></script>
  <link rel="stylesheet" href="../../../../css/sweetalert2.css">
  <link rel="stylesheet" href="../../../../css/notificaciones.css">
  <link rel="stylesheet" href="../../style/ordenesCompra.css">

  <script src="../../../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="../../js/ordenesCompra.js" charset="utf-8"></script>
  <script src="../../../../js/numeral.min.js"></script>
  <link href="../../../../css/lobibox.min.css" rel="stylesheet">
  <script src="../../js/comentar_ordenCompra.js" charset="utf-8"></script>

  <script src="//code.jquery.com/jquery-latest.min.js"></script>
  <script type="text/javascript" src="../../../../js/jquery-barcode.min.js"></script>
</head>

<body id="page-top">
  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
$icono = '../../../../img/inventarios/ICONO INVENTARIOS Y PROD_Mesa de trabajo 1.svg';
$titulo = 'Comentar orden de compra';
$ruta = "../../../";
?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">
        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 sticky-top shadow">
          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>
          <!-- Topbar Search -->
          <h3 class="d-none d-sm-inline-block mr-auto ml-md-3 my-2 my-md-0 mw-100">
            <?php if (!isset($icono)) {
    $icono = $rutatb . "../img/menu/dashboardTopbar.svg";
}
?>
            <img src="<?=$icono;?>" alt="" width="40px">
            <?=$titulo;?>
            <a href="./" data-toggle="tooltip" data-placement="bottom" title="Regresar" class="ml-3">
              <img src="../../../../img/icons/REGRESAR_2.svg" alt="Regresar" width="40px">
            </a>
          </h3>
        </nav>

        <input type="hidden" id="txtUsuario" value="<?=$_SESSION['PKUsuario'];?>">
        <input type="hidden" id="txtRuta" value="<?=$ruta;?>">
        <input type="hidden" id="txtEdit" value="<?=$ruteEdit;?>">
        <input type="hidden" id="txtPantalla" value="13">
        <input type="hidden" id="cmbProveedor" value="0">

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <div class="row">
            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4 nav-link" id="bodyUp">
                <div>
                  <span id="alertas"> </span>
                  <div class="row">
                    <div class="col-lg-12">
                      <form action="" method="post" id="frmOrdenCompra">
                        <br>
                        <input type="hidden" value="<?=$id?>" id="txtFKEncripted">
                        <div class="form-group" style="width:70%; margin:auto; ">
                          <div class="row">
                            <div class="col-lg-6" >
                            <span id="botones">
                                
                             </span> 
                            </div>
                            <div class="col-lg-6">
                              Envía comentarios:
                              <br><br>
                              <div class="col-sm-12 col-sm-offset-4 frame">
                                <span id="chat">
                                <ul></ul>
                                </span>
                              <div>
                                  <div class="msj-rta macro">                        
                                      <div class="text text-r" style="background:whitesmoke !important">
                                          <input class="mytext" name="mytext" id="mytext" placeholder="Escribe un mensaje" maxlength="255" onkeypress="validarTecla(event)"/>
                                      </div> 
                                  </div>
                                  <div style="padding:10px;">
                                      <button type="button" class="btn-custom btn-custom--blue" name="btnEnviar" id="btnEnviar"
                                    onclick="enviarMensaje()" style="float:right" title=""> Enviar</button>
                                  </div>                
                              </div>
                          </div>  
                            </div>
                          </div>
                        </div>
                        <br><br>

                        <div class="form-group" style="width:70%; margin:auto;">
                        <iframe src="functions/open_OrdenCompra.php?txtId=<?= $id?>" style="width:100%; height:800px;" frameborder="0" ></iframe>
                        </div>
                        
                        <!--<div class="form-group" style="width:70%; margin:auto; border: 1px solid">
                          <div class="row">
                            <div class="col-lg-12">
                              <br>
                              <table>
                                <tr>
                                </tr>
                                <tr>
                                  <td class="" width="70%" style="background-color: transparent;" rowspan="3"><img src="<?=$GLOBALS["logotipo"]?>" width="30%"></td>
                                  <td style="text-align: right; font-size: 24px; font-weight: bold; background-color: transparent;">Orden de compra</td>
                                </tr>
                                <tr>
                                  <td style="text-align: right; font-size: 18px; background-color: transparent;">#<?=$GLOBALS["referencia"]?></td>
                                </tr>
                                <tr>
                                  <td style="text-align: right; font-size: 16px; background-color: transparent;">Fecha: <?=$GLOBALS["FechaIngreso"]?></td>
                                </tr>
                              </table>
                              <style>
                                table {
                                  font-family: Helvetica;
                                  font-size: 12px;
                                  text-align: left;
                                  border: none;
                                  width: 95%;
                                  border: 1px solid #ffffff;
                                  border-collapse: collapse;
                                  margin: 0 auto;
                                }
                                th {
                                  font-size: 14px;
                                  font-weight: bold;
                                  padding: 8px;
                                  background-color: #b2b2b2;
                                  color: #000000;
                                  text-align: left;
                                  line-height: 20px;
                                  border: 1px solid #ffffff;
                                  border-collapse: collapse;
                                }
                                td {
                                  font-size: 9px;
                                  padding: 2px;
                                  background-color: #f0f0f0;
                                  color: #000000;
                                  vertical-align: middle;
                                  text-align: left;
                                  line-height: 10px;
                                  border: 1px solid #ffffff;
                                  border-collapse: collapse;
                                }
                                .td3 {
                                  background-color: #ffffff;
                                }
                                .td4 {
                                  font-weight: bold;
                                }
                                .td5 {
                                  font-size: 28px;
                                }
                                .td6 {
                                  display:inline-block;
                                  text-align: left;
                                  border: none;
                                  vertical-align:middle;
                                }
                                .td7 {
                                  background-color: #f0f0f0;
                                }
                              </style>
                              <table>
                                <tr>
                                  <th colspan="2" width="50%">INFORMACIÓN DEL PROVEEDOR</th>
                                  <th colspan="2" width="50%">DIRECCIÓN DE ENTREGA</th>
                                </tr>
                                <tr>
                                  <td class="td3 td4" width="25%">Nombre</td>
                                  <td class="td3" width="25%"><?=$GLOBALS["NombreProveedor"]?></td>
                                  <td class="td4" width="25%">Nombre</td>
                                  <td width="25%"><?=$GLOBALS["NombreCliente"]?></td>
                                </tr>
                                
                                <tr>
                                  <td class="td3 td4">Atención</td>
                                  <td class="td3"></td>
                                  <td class="td4">Atención</td>
                                  <td></td>
                                </tr>
                                <tr>
                                  <td class="td3 td4">Condición Pago</td>
                                  <td class="td3"></td>
                                  <td class="td4">Condición Pago</td>
                                  <td></td>
                                </tr>
                                <tr>
                                  <td class="td3 td4">RFC</td>
                                  <td class="td3"><?=$GLOBALS["RFCProveedor"]?></td>
                                  <td class="td4">RFC</td>
                                  <td><?=$GLOBALS["RFCCliente"]?></td>
                                </tr>
                                <tr>
                                  <td class="td3 td4">Teléfono</td>
                                  <td class="td3"><?=$GLOBALS["TelefonoPv"]?></td>
                                  <td class="td4">Teléfono</td>
                                  <td><?=$GLOBALS["TelefonoCliente"]?></td>
                                </tr>
                                <tr>
                                  <td class="td3 td4">Forma de Envío</td>
                                  <td class="td3"></td>
                                  <td class="td4">Forma de Envío</td>
                                  <td></td>
                                </tr>
                                <tr>
                                  <td class="td3 td4">email</td>
                                  <td class="td3"><u><?=$GLOBALS["EmailPV"]?></u></td>
                                  <td class="td4">email</td>
                                  <td><u></u></td>
                                </tr>
                                <tr>
                                  <td class="td3 td4">Razón Social</td>
                                  <td class="td3"><?=$GLOBALS["RazonSocialPV"]?></td>
                                  <td class="td4">Dirección de entrega</td>
                                  <td><?=$GLOBALS["Direccion"]?></td>
                                </tr>
                                <tr>
                                  <td class="td3 td4"rowspan="2"></td>
                                  <td class="td3"rowspan="2"></td>
                                  <td class="td4"> Fecha solicitada de entrega </td>
                                  <td><?=$GLOBALS["FechaEstimada"]?></td>
                                </tr>
                              </table>
                              <br>
                              <table>
                                <tr>
                                  <th width="100%" colspan="5" style="border:solid 1px;">INFORMACIÓN DE COMPRA</th>
                                </tr>
                                <tr>
                                  <td class="td3 td4" style="border:solid 1px #000000;" width="27.5%">Comprador</td>
                                  <td class="td3 td4" style="border:solid 1px #000000;" width="27.5%">Correo</td>
                                  <td class="td3 td4" style="border:solid 1px #000000;" width="15%">Teléfono</td>
                                  <td class="td3 td4" style="border:solid 1px #000000;" width="15%">Moneda</td>
                                  <td class="td3 td4" style="border:solid 1px #000000;" width="15%">Tipo de Cambio</td>
                                </tr>
                                <tr>
                                  <td class="td3" width="27.5%"><?=$GLOBALS['Comprador']?></td>
                                  <td class="td3" width="27.5%"><u><?=$GLOBALS["EmailComprador"]?></u></td>
                                  <td class="td3" width="15%"><?=$GLOBALS["TelefonoComprador"]?></td>
                                  <td class="td3" width="15%"></td>
                                  <td class="td3" width="15%"></td>
                                </tr>
                              </table>
                              <br>
                             
                              <table>
                                <thead>
                                  <tr>
                                    <th width="9%" style="font-size: 11px; font-weight: bold; border: 1px solid #000000; border-collapse: collapse;">Piezas</th>
                                    <th width="10%" style="font-size: 11px; font-weight: bold; border: 1px solid #000000; border-collapse: collapse;">Clave</th>
                                    <th width="39%" style="font-size: 11px; font-weight: bold; border: 1px solid #000000; border-collapse: collapse;">Nombre</th>
                                    <th width="14%" style="font-size: 11px; font-weight: bold; border: 1px solid #000000; border-collapse: collapse;">Precio</th>
                                    <th width="16%" style="font-size: 11px; font-weight: bold; border: 1px solid #000000; border-collapse: collapse;">Unidad de Medida</th>
                                    <th width="12%" style="font-size: 11px; font-weight: bold; border: 1px solid #000000; border-collapse: collapse;">Importe</th>
                                  </tr>
                                </thead>
                                <tbody>

                                <?php $stmtp = $conn->prepare("SELECT id, producto, nombre, clave, cantidad, unidadMedida, precio, importe, GROUP_CONCAT(impuestos SEPARATOR ' / ') as impuestos  from (
                                                          select oc.PKOrdenCompra as id,
                                                              doc.FKProducto as producto,
                                                              dpp.NombreProducto as nombre,
                                                              dpp.Clave as clave,
                                                              doc.Cantidad as cantidad,
                                                              dpp.UnidadMedida as unidadMedida,
                                                              doc.Precio as precio,
                                                              (doc.Cantidad * doc.Precio) as importe,
                                                              ifnull(if(i.FKTipoImporte = 2,(concat(i.Nombre,' - ',ioc.Tasa)),(concat(i.Nombre,' ',ioc.Tasa, '%' ))),'') as impuestos
                                                          from ordenes_compra oc
                                                            inner join detalle_orden_compra doc on oc.PKOrdenCompra = doc.FKOrdenCompra
                                                            inner join datos_producto_proveedores dpp on doc.FKProducto = dpp.FKProducto and (select FKProveedor from ordenes_compra where id_encriptado = :idOrdenCompra1) = dpp.FKProveedor
                                                            inner join productos p on doc.FKProducto = p.PKProducto  
                                                            left join impuestos_orden_compra ioc on oc.PKOrdenCompra = ioc.FKOrdenCompra and doc.FKProducto = ioc.FKProducto
                                                            left join impuesto i on ioc.FKImpuesto = i.PKImpuesto
                                                          where doc.FKOrdenCompra = (select PKOrdenCompra from ordenes_compra where id_encriptado =:idOrdenCompra2)
                                                          ) as tabless
                                                          GROUP BY producto 
                                                          ;");
                                                        $stmtp->execute(array(':idOrdenCompra1'=>$id, ':idOrdenCompra2'=>$id));
                                                        $rowp = $stmtp->fetchAll();

                                foreach ($rowp as $rp) { 

                                echo '<tr>
                                        <td style="background:transparent; border-bottom: 1px solid #b2b2b2; border-top: 1px solid #b2b2b2;">'.$rp['cantidad'].'</td>
                                        <td style="background:transparent; border-bottom: 1px solid #b2b2b2; border-top: 1px solid #b2b2b2;">'.$rp['clave'].'</td>
                                        <td style="background:transparent; border-bottom: 1px solid #b2b2b2; border-top: 1px solid #b2b2b2;">'.$rp['nombre'].'</td>
                                        <td style="background:transparent; border-bottom: 1px solid #b2b2b2; border-top: 1px solid #b2b2b2;">$ '.number_format($rp['precio'],2,'.',',').'</td>
                                        <td style="background:transparent; border-bottom: 1px solid #b2b2b2; border-top: 1px solid #b2b2b2;">'.$rp['unidadMedida'].'</td>
                                        <td style="background:transparent; border-bottom: 1px solid #b2b2b2; border-top: 1px solid #b2b2b2;">$ '.number_format($rp['importe'],2,'.',',').'</td>
                                      </tr>';
                                
                                } ?>

                                <tr>
                                  <td colspan="6" width="100%" style="background: transparent;"></td>
                                </tr>
                                <tr>
                                  <td colspan="4" style="background: transparent;border-bottom: 1px solid #fff; border-top: 1px solid #fff;"></td>
                                  <td width="16%" style="background: transparent; font-weight: bold;">Subtotal:</td>
                                  <td width="12%" style="text-align: right; background: transparent;">$ <?=$GLOBALS["Subtotal"]?></td>
                                </tr>
                                <tr>
                                  <td colspan="4" style="background: transparent;border-bottom: 1px solid #fff; border-top: 1px solid #fff;"></td>
                                  <td width="16%" style="background: transparent; font-weight: bold;">Impuestos:</td>
                                  <td width="12%" style="background: transparent;"></td>
                                </tr>

                                <?php $stmti = $conn->prepare("SELECT GROUP_CONCAT(id SEPARATOR ' / ') as id , GROUP_CONCAT(producto SEPARATOR ' / ') as producto, nombre, tasa, pkImpuesto, sum(totalImpuesto) as totalImpuesto from (
                                                          select oc.PKOrdenCompra as id,
                                                              dpp.NombreProducto as producto,
                                                              i.Nombre as nombre,
                                                              if(i.FKTipoImporte = 2,'',ioc.Tasa) as tasa,
                                                              i.PKImpuesto as pkImpuesto,
                                                              if(i.FKTipoImporte = 2,doc.Cantidad * ioc.Tasa,(doc.Cantidad * doc.Precio) * (ioc.Tasa / 100)) as totalImpuesto
                                                          from ordenes_compra oc
                                                            inner join detalle_orden_compra doc on oc.PKOrdenCompra = doc.FKOrdenCompra
                                                            inner join productos p on doc.FKProducto = p.PKProducto  
                                                            left join impuestos_orden_compra ioc on oc.PKOrdenCompra = ioc.FKOrdenCompra
                                                            left join impuesto i on ioc.FKImpuesto = i.PKImpuesto
                                                            left join tipos_impuestos ti on i.FKTipoImpuesto = ti.PKTipoImpuesto
                                                            inner join datos_producto_proveedores dpp on doc.FKProducto = dpp.FKProducto and (select FKProveedor from ordenes_compra where id_encriptado = :idOrdenCompra1) = dpp.FKProveedor
                                                          where doc.FKOrdenCompra = (select PKOrdenCompra from ordenes_compra where id_encriptado =:idOrdenCompra2)
                                                        ) as impu
                                                        GROUP BY tasa 
                                                        order by nombre, tasa desc
                                                        ;");
                                  $stmti->execute(array(':idOrdenCompra1'=>$id, ':idOrdenCompra2'=>$id));
                                  $rowi = $stmti->fetchAll();
                                  ?>

                                  <?php $tasa = '';
                                  foreach ($rowi as $ri) { 
                                    if($ri['tasa'] == '' || $ri['tasa'] == null){
                                      $tasa = $ri['nombre'].' '.$ri['tasa'].'';
                                    }else{
                                      $tasa = $ri['nombre'].' - '.$ri['tasa'].'%';
                                    }

                                    echo '<tr>
                                            <td colspan="4" style="background: transparent; border-bottom: 1px solid #fff; border-top: 1px solid #fff;"></td>
                                            <td width="16%" style="text-align: right; background: transparent; border-bottom: 1px solid #fff; border-top: 1px solid #fff;">'. $tasa.' </td>
                                            <td width="12%" style="text-align: right; background: transparent;" border-bottom: 1px solid #fff; border-top: 1px solid #fff;">$ '.number_format($ri['totalImpuesto'],2,'.',',').'</td>
                                          </tr>';

                                  } ?>

                                  <tr>
                                    <td colspan="4" style="background: transparent; border-bottom: 1px solid #fff; border-top: 1px solid #fff;"></td>
                                    <th width="16%" style="font-weight: bold; border: 1px solid #000000;">Total:</th>
                                    <td width="12%" style="text-align: right; background: transparent; border: 1px solid #000000;">$ <?=$GLOBALS["ImporteTotal"]?></td>
                                  </tr>
                                </tbody>
                              </table>
                              <div style="margin-left:2.5%">
                                <p style="font-size: 9px; font-weight: bold;">Notas:</p>
                                <p style="font-size: 9px; font-weight: normal;"><?=$GLOBALS["NotaComprador"]?><p>
                              </div>

                              <div id="barcode"></div>
                            </div>
                          </div>
                          </br></br>
                        </div>--->
                          <span id="modal_envio"></span>

                      </form>
                    </div>
                  </div>
                </div>
              </div>
              <!-- End Basic Card Example -->

            </div>
          </div>

        </div>
        <!-- End Page Content -->
      </div>

      <!--<embed src="../../../../ordenComp/OrdendeCompra_15.pdf" type="application/pdf" width="100%" height="600px" />-->
      <!-- End Main Content -->

      <!-- Footer -->
      <?php
$rutaf = "../../../";
require_once $rutaf . 'footer.php';
?>
      <!-- End of Footer -->

    </div>
    <!-- End Content Wrapper -->



  </div>
  <!-- End Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!--<script src="../../../../js/sb-admin-2.min.js"></script>
  <script src="../../../../js/scripts.js"></script>-->
  <script src="../../../../js/slimselect.min.js"></script>
  <script src="../../../../js/lobibox.min.js"></script>
  <script>
  loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
  setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit')
    .val());
  </script>
  <script>
    var code = '<?php echo $GLOBALS["referencia"]?>';
    $(document).ready(function(){
      $("#barcode").barcode(
        code, // Valor del codigo de barras
      "code128" // tipo (cadena)
      );
    });
  </script>
</body>

</html>

<!--CANCEL MODAL SLIDE Orden Compra-->
<div class="modal fade" id="cancelar_OrdenCompra" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">¿Desea cancelar la orden de compra?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="text-light">x</span>
          </button>
        </div>
        <input type="hidden" vlaue="" id="estatusIDCancelar" name="estatusIDCancelar">
        <div class="modal-body" style="font-size: 10 px!important; color: red;">Al cancelar la Orden de compra se le notificará al proveedor de esta acción vía chat.</div>
        <div class="modal-footer">
          <button class="btn-custom btn-custom--border-blue btnCancelarActualizacion" type="button"
            data-dismiss="modal"><span class="ajusteProyecto">Cancelar</span></button>
          <button type="submit" onclick="updateEstatusOC($('#estatusIDCancelar').val())" class="btn-custom btn-custom--blue"><span
              class="ajusteProyecto" data-dismiss="modal">Confirmar</span></button>
        </div>
      </form>
    </div>
  </div>
</div>

<!--ACEPT MODAL SLIDE Orden Compra-->
<div class="modal fade" id="aceptar_OrdenCompra" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">¿Desea aceptar la orden de compra?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="text-light">x</span>
          </button>
        </div>
        <input type="hidden" vlaue="" id="estatusIDAceptar" name="estatusIDAceptar">
        <div class="modal-body" style="font-size: 10 px!important; color: red;">Al aceptar usted la Orden de compra se le notificará al proveedor de esta acción vía chat.</div>
        <div class="modal-footer">
          <button class="btn-custom btn-custom--border-blue btnCancelarActualizacion" type="button"
            data-dismiss="modal"><span class="ajusteProyecto">Cancelar</span></button>
          <button type="submit" onclick="updateEstatusOC($('#estatusIDAceptar').val())" class="btn-custom btn-custom--blue"><span
              class="ajusteProyecto" data-dismiss="modal">Confirmar</span></button>
        </div>
      </form>
    </div>
  </div>
</div>

<!--ACTIVATE MODAL SLIDE Orden Compra-->
<div class="modal fade" id="activar_OrdenCompra" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">¿Desea reactivar la orden de compra?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="text-light">x</span>
          </button>
        </div>
        <input type="hidden" vlaue="" id="estatusIDReactivar" name="estatusIDReactivar">
        <div class="modal-body" style="font-size: 10 px!important; color: red;">Al reactivar la Orden de compra se le notificará al proveedor de esta acción vía chat.</div>
        <div class="modal-footer">
          <button class="btn-custom btn-custom--border-blue btnCancelarActualizacion" type="button"
            data-dismiss="modal"><span class="ajusteProyecto">Cancelar</span></button>
          <button type="submit" onclick="updateEstatusOC($('#estatusIDReactivar').val())" class="btn-custom btn-custom--blue"><span
              class="ajusteProyecto" data-dismiss="modal">Reactivar</span></button>
        </div>
      </form>
    </div>
  </div>
</div>

<!--CLOSE MODAL SLIDE Orden Compra-->
<div class="modal fade" id="cerrar_OrdenCompra" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">¿Desea cerrar la orden de compra?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="text-light">x</span>
          </button>
        </div>
        <input type="hidden" vlaue="" id="estatusIDCerrar" name="estatusIDCerrar">
        <div class="modal-body" style="font-size: 10 px!important; color: red;">Esta acción no podrá deshacerse, y ya no podrá darle entrada a ningún producto de la orden.</div>
        <div class="modal-footer">
          <button class="btn-custom btn-custom--border-blue btnCancelarActualizacion" type="button"
            data-dismiss="modal"><span class="ajusteProyecto">Cancelar</span></button>
          <button type="submit" onclick="updateEstatusOC($('#estatusIDCerrar').val())" class="btn-custom btn-custom--blue"><span
              class="ajusteProyecto" data-dismiss="modal">Cerrar</span></button>
        </div>
      </form>
    </div>
  </div>
</div>
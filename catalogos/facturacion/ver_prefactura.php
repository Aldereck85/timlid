<?php
session_start();
require_once('../../include/db-conn.php');

$a = $_POST['razon_social'];
$a1 = $_POST['rfc'];
$impuestos = "";
$a2 = $_POST['productos'];
$a21 = json_encode($_POST['productos']);
$value = $_POST['id_invoice'];

if (isset($_POST['impuestos'])) {
  $a3 = explode("<br>", $_POST['impuestos']);
  for ($i = 0; $i < count($a3); $i++) {
    $impuestos .= $a3[$i] . "<br>";
  }
  $impuestos = substr($impuestos, 0, strlen($impuestos) - 4);
} else {

  $impuestos = "0.00";
}

$query = sprintf("SELECT e.logo, e.RazonSocial, e.RFC, e.domicilio_fiscal, crf.descripcion
FROM empresas as e 
LEFT JOIN claves_regimen_fiscal AS crf ON e.regimen_fiscal_id = crf.id 
WHERE PKEmpresa = :id");
$stmt = $conn->prepare($query);
$stmt->execute([":id" => $_SESSION['IDEmpresa']]);
$empresa = $stmt->fetch(PDO::FETCH_ASSOC);
$logo = $_ENV['RUTA_ARCHIVOS_READ'] . $_SESSION['IDEmpresa'] . "/fiscales/" . $empresa['logo'];
$razonSoc = $empresa['RazonSocial'];
$rfcEmpresa = $empresa['RFC'];
$domicilio = $empresa['domicilio_fiscal'];
$regimen = $empresa['descripcion'];

$query = sprintf("select 
vd.NotasCliente notas_cliente, 
decl.Calle calle,
decl.Numero_exterior no_exterior,
decl.Numero_Interior no_interior,
decl.Colonia colonia,
decl.CP cp,
decl.Municipio municipio,
e.Estado estado,
decl.Contacto contacto,
decl.Telefono telefono
from facturacion f 
inner join ventas_directas vd on f.referencia = vd.PKVentaDirecta
inner join direcciones_envio_cliente decl on f.cliente_id = decl.FKCliente
inner join estados_federativos e on decl.Estado = e.PKEstado
where f.id = :id");
$stmt = $conn->prepare($query);
$stmt->bindValue(":id",$value);
$stmt->execute();

$footer_pdf = $stmt->fetchAll(PDO::FETCH_OBJ);

$interior = "";
$direccion_envio = "";
$direccion_envio_pdf = "";
$notas_cliente = "";
$contacto = "";
$telefono = "";

if(count($footer_pdf) > 0){
  $interior = $footer_pdf[0]->no_interior !== "" && $footer_pdf[0]->no_interior !== null ? "Int. " . $footer_pdf[0]->no_interior : "";
  $direccion_envio = $footer_pdf[0]->calle . " " . $footer_pdf[0]->no_exterior . " " . $interior . " " . " C.P. " . $footer_pdf[0]->cp . " " . $footer_pdf[0]->municipio . ", " . $footer_pdf[0]->estado;
  $direccion_envio_pdf = $direccion_envio !== "" && $direccion_envio !== null ? "Dirección de envío: " . $direccion_envio : "";
  $notas_cliente = $footer_pdf[0]->notas_cliente !== "" && $footer_pdf[0]->notas_cliente !== null ? "Notas de cliente: " . $footer_pdf[0]->notas_cliente : "";
  $contacto = $footer_pdf[0]->contacto !== "" && $footer_pdf[0]->contacto !== null ? "Contacto: " . $footer_pdf[0]->contacto : "";
  $telefono = $footer_pdf[0]->telefono !== "" && $footer_pdf[0]->telefono !== null ? "Teléfono: " . $footer_pdf[0]->telefono : "";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Timlid | Ver Prefactura</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.js"></script>

  <!-- Custom scripts for all pages-->

  <!-- Page level plugins -->
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.bootstrap4.min.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>

  <!-- Custom fonts for this template -->
  <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Custom styles for this template -->
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.bootstrap4.min.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <!--<link href="../../css/stylesTable.css" rel="stylesheet">-->
  <link href="../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet">

  <link rel="stylesheet" href="../../css/sweetalert2.css">
  <link href="../../css/lobibox.min.css" rel="stylesheet">

  <!-- <link rel="stylesheet" href="css/ver_prefactura.css"> -->

  <link rel="stylesheet" href="../../css/notificaciones.css">
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>

  <link rel="stylesheet" href="css/agregar_factura.css">

  <script src="../../js/lobibox.min.js"></script>

  <script src="../../js/jquery.redirect.js"></script>
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
    $icono = '../../img/icons/ICONO FACTURACION-01.svg';
    $titulo = 'Ver prefactura';
    $ruta = "../../";
    ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">
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
            <img src="<?= $icono; ?>" alt="" width="40px">
            <?= $titulo; ?>
            <!--
          <a href="index.php" data-toggle="tooltip" data-placement="bottom" title="Regresar" class="ml-3">
            <img src="../../img/icons/REGRESAR_2.svg" alt="Regresar" width="40px">
          </a>
          -->
          </h3>
        </nav>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <div class="row">
            <div class="col-lg-12">
              <!-- Basic Card Example -->
              <div class="card shadow mb-4 nav-link" id="bodyUp">
                <div>
                  <button type="button" class="btn-table-custom btn-table-custom--turquoise" name="btnDescargar" id="btnDescargarPrefactura">
                    <i class="fas fa-cloud-download-alt"></i> Descargar prefactura
                  </button>
                  <button type="button" class="btn-table-custom btn-table-custom--turquoise" name="btnEnviar" id="btnEnviarPrefactura">
                    <i class="fas fa-envelope"></i> Enviar prefactura
                  </button>
                  <br><br>
                  <div class="row justify-content-center">
                    <div class="col-lg-6">

                      <!-- HEADER -->
                      <input type="hidden" id="folioyserie" value="<?= $_POST['serie'] . " " . $_POST['folio']; ?>">

                      <table style="width: 100%; font-family: Helvetica; margin-bottom: 20px;">
                        <tr>
                          <td style="width: 30%;"><img src="<?= $logo ?>" width="50"></td>
                          <td style="width: 50%;"></td>
                          <td style="text-align: end; width:20%;">
                            <table style="color: #000000; width:100%;">
                              <tr>
                                <td style="text-align: center; border-bottom: 2px solid #7abaff;">Folio</td>
                              </tr>
                              <tr>
                                <td style="font-weight: bolder; background-color: #f5f5f5; text-align: center; height: 30px; vertical-align: bottom;"><?= $_POST['serie'] . " " . $_POST['folio']; ?></td>
                              </tr>
                            </table>
                          </td>
                          
                        </tr>
                      </table>

                      <!-- EMPRESA -->
                      <table style="width: 100%; font-family: Helvetica; margin-bottom: 20px;">
                        <tr>
                          <td style="width: 100%;">
                            <table style="color: #000000; width:100%;">
                              <tr>
                                <td><?= $razonSoc ?></td>
                              </tr>
                              <tr>
                                <td><?= $domicilio ?></td>
                              </tr>
                              <tr>
                                <td><span style="font-weight: bold;">RFC </span> <?= $rfcEmpresa ?></td>
                              </tr>
                              <tr>
                                <td><span style="font-weight: bold;">Régimen F. </span> <?= $regimen ?></td>
                              </tr>
                            </table>
                          </td>
                          
                        </tr>
                      </table>

                      <!-- CLIENTE -->
                      <input type="hidden" id="rfc" value="<?= $a1; ?>">
                      <input type="hidden" id="razon_social" value="<?= $a; ?>">
                      <input type="hidden" id="cfdi" value="<?= $_POST['cfdi']; ?>">

                      <table style="width: 100%; font-family: Helvetica; margin-bottom: 20px;">
                        <tr>
                          <td style="width: 100%;">
                            <table style="color: #000000; width:100%;">
                              <tr>
                                <td style="font-weight: bold; border-bottom: 2px solid #7abaff">Receptor</td>
                              </tr>
                              <tr>
                                <td style="border: 1px solid #dfdfdf">
                                  <table>
                                    <tr>
                                      <td style="width: 30%; font-weight: bold;">Razón Social</td>
                                      <td style="width: 70%;"><?= $a; ?></td>
                                    </tr>
                                    <tr>
                                      <td style="width: 30%; font-weight: bold;">RFC</td>
                                      <td style="width: 70%;"><?= $a1; ?></td>
                                    </tr>
                                    <tr>
                                      <td style="width: 30%; font-weight: bold;">Uso del CFDI</td>
                                      <td style="width: 70%;"><?= $_POST['cfdi']; ?></td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                            </table>
                          </td>
                          <td style="width: 50%;"></td>
                        </tr>
                      </table>

                      <!-- PRODUCTOS -->
                      <?php
                      $clavesHTML = '';
                      $productosHTML = '';
                      $medidasHTML = '';
                      $cantidadesHTML = '';
                      $preciosHTML = '';
                      $impuestosHTML = '';
                      $totalesHTML = '';
                      foreach ($a2 as $r) {
                        $clavesHTML .= '
                            <tr>
                              <td>' . $r['clave'] . '</td>
                            </tr>
                            <input type="hidden" name="clave[]" id="clave" value="' . $r['clave'] . '">
                        ';
                        $productosHTML .= '
                            <tr>
                              <td>' . $r['descripcion'] . '</td>
                            </tr>
                            <input type="hidden" name="producto[]" id="producto" value="' . $r['descripcion'] . '">
                        ';
                        $medidasHTML .= '
                            <tr>
                              <td>' . $r['unidad_medida'] . '</td>
                            </tr>
                            <input type="hidden" name="u_medida[]" id="u_medida" value="' . $r['unidad_medida'] . '">
                        ';
                        $cantidadesHTML .= '
                            <tr>
                              <td>' . $r['cantidad'] . '</td>
                            </tr>
                            <input type="hidden" name="cantidad[]" id="cantidad" value="' . $r['cantidad'] . '">
                        ';
                        $preciosHTML .= '
                            <tr>
                              <td>' . $r['precio'] . '</td>
                            </tr>
                            <input type="hidden" name="precio[]" id="precio" value="' . $r['precio'] . '">
                        ';
                        $impuestosHTML .= '
                            <tr>
                              <td>' . $r['impuestos'] . '</td>
                            </tr>
                            <input type="hidden" name="impuestos[]" id="impuestos" value="' . $r['impuestos'] . '">
                        ';
                        $totalesHTML .= '
                            <tr>
                              <td>' . $r['importe_total'] . '</td>
                            </tr>
                            <input type="hidden" name="total[]" id="total" value="' . $r['importe_total'] . '">
                        ';
                      }
                      ?>
                      <table style="width: 100%; font-family: Helvetica; margin-bottom: 20px;">
                        <tr>
                          <td>
                            <table style="color: #000000; width:100%;">
                              <tr>
                                <th style="font-weight: bold; border-bottom: 2px solid #7abaff">Clave</th>
                                <th style="font-weight: bold; border-bottom: 2px solid #7abaff">Concepto</th>
                                <th style="font-weight: bold; border-bottom: 2px solid #7abaff">U. Medida</th>
                                <th style="font-weight: bold; border-bottom: 2px solid #7abaff">Cant.</th>
                                <th style="font-weight: bold; border-bottom: 2px solid #7abaff">Precio</th>
                                <th style="font-weight: bold; border-bottom: 2px solid #7abaff">Importe</th>
                              </tr>
                              <tr>
                                <td style="border: 1px solid #dfdfdf;">
                                  <table><?= $clavesHTML ?></table>
                                </td>
                                <td style="border: 1px solid #dfdfdf;">
                                  <table><?= $productosHTML ?></table>
                                </td>
                                <td style="border: 1px solid #dfdfdf;">
                                  <table><?= $medidasHTML ?></table>
                                </td>
                                <td style="border: 1px solid #dfdfdf;">
                                  <table><?= $cantidadesHTML ?></table>
                                </td>
                                <td style="border: 1px solid #dfdfdf;">
                                  <table><?= $preciosHTML ?></table>
                                </td>
                                <td style="border: 1px solid #dfdfdf;">
                                  <table><?= $totalesHTML ?></table>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>

                      <!-- TOTALES -->
                      <input type="hidden" id="metodo_pago" value="<?= $_POST['metodo_pago'] ?>">
                      <input type="hidden" id="forma_pago" value="<?= $_POST['forma_pago']; ?>">
                      <input type="hidden" id="moneda" value="<?= $_POST['moneda']; ?>">
                      <input type="hidden" id="subtotal" value="<?= $_POST['subtotal'] ?>">
                      <input type="hidden" id="impuestos1" value="<?= $impuestos ?>">
                      <input type="hidden" id="total1" value="<?= $_POST['total'] ?>">
                      <input type="hidden" id="notas_cliente" value="<?= $notas_cliente ?>">
                      <input type="hidden" id="direccion_envio" value="<?= $direccion_envio ?>">
                      <input type="hidden" id="contacto" value="<?= $contacto ?>">
                      <input type="hidden" id="telefono" value="<?= $telefono ?>">

                      <table style="color: #000000; width: 100%; font-family: Helvetica;">
                        <tr>
                          <td style="width: 70%;">
                            <table style="width:100%;">
                              <tr>
                                <td>
                                  <table>
                                    <tr>
                                      <th style="width: 25%; font-weight: bold;">Forma de pago</th>
                                      <th style="width:50%; font-weight: bold;">Método de pago</th>
                                      <th style="width: 25%; font-weight: bold;">Moneda</th>
                                    </tr>
                                    <tr>
                                      <td style="width: 25%;"><?= $_POST['forma_pago'] ?></td>
                                      <td style="width:50%;"><?= $_POST['metodo_pago'] ?></td>
                                      <td style="width: 25%;"><?= $_POST['moneda'] ?></td>
                                    </tr>
                                    <tr><td colspan="2"><?= $notas_cliente ?></td></tr>
                                    <tr><td colspan="2"><?= $direccion_envio ?></td></tr>
                                    <tr><td colspan="2"><?= $contacto ?></td></tr>
                                    <tr><td colspan="2"><?= $telefono ?></td></tr>
                                  </table>
                                </td>
                              </tr>
                            </table>
                          </td>
                          <td style="width: 30%;">
                            <table>
                              <tr>
                                <td style="width: 50%; text-align: right;">Subtotal</td>
                                <td style="width: 50%; text-align: right;"><?= $_POST['subtotal']; ?></td>
                              </tr>
                              <tr>
                                <td style="width: 50%; text-align: right;">Impuestos</td>
                                <td style="width: 50%; text-align: right;"><?= $impuestos; ?></td>
                              </tr>
                              <tr>
                                <td style="width: 50%; text-align: right;">Total</td>
                                <td style="width: 50%; text-align: right;"><?= $_POST['total'] ?></td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
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
      <!-- End Main Content -->

    </div>
    <!-- End Content Wrapper -->
  </div>
  <!-- End Page Wrapper -->

  <div class="modal fade" id="modalEnviarCorreo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title w-100" id="myModalLabel">Enviar Prefactura</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="dataCancelacion">
            <div class="form-group">
              <label for="txtDestino">Destinatario:</label>
              <input type="email" class="form-control" name="txtDestinoCancel" id="txtDestinoCancel" required="">
              <div class="invalid-feedback" id="invalid-destinoCancel">Debe ingresar un email destinatario.</div>
            </div>
            <div class="form-group">
              <label for="txtDestino">Mensaje:</label>
              <textarea class="form-control" name="txtMotivoCancelacion" id="txtMotivoCancelacion" cols="30" rows="2" required=""></textarea>
              <div class="invalid-feedback" id="invalid-motivoCancelacion">Debe ingresar un motivo de cancelación.</div>
            </div>
          </form>
        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal" id="btnCancelarActualizacion"><span class="ajusteProyecto">Cerrar</span></button>
          <button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="enviar_prefactura"><span class="ajusteProyecto">Enviar</span></button>
        </div>
      </div>
    </div>
  </div>
  
  <div id="elementH"></div>
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>
  <script src="js/ver_prefactura.js"></script>
</body>

</html>
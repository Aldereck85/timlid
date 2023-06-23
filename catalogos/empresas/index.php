<?php

  $screen = 55;
  $ruta = "../";
  require_once $ruta . 'validarPermisoPantalla.php';
  if(isset($_SESSION["Usuario"]) && $permiso === 1){
    require_once '../../include/db-conn.php';
  } else {
    header("location:../dashboard.php");
  }
  $jwt_ruta = "../../";
  require_once '../jwt.php';

  date_default_timezone_set('America/Mexico_City');

  $token = $_SESSION['token_ld10d'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">

  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | Perfil empresa</title>

  <!-- ESTILOS -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../css/sweetalert2.css" rel="stylesheet">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/main.css">
  <!-- JS -->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>
  <script src="../../js/jquery.redirect.min.js"></script>
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="../../js/mdtimepicker.min.js"></script>
  <script src="../../js/permisos_usuario.js"></script>
  <script src="../../js/lobibox.min.js"></script>
  <script src="js/main.js"></script>
  <script src="../../js/slimselect.min.js"></script>
  <script src="../../vendor/moment/moment.min.js"></script>

</head>
<body id="page-top" data-screen="55">
  <div id=loader></div>
  <div id=loader1></div>
  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
        $icono = '../../img/icons/ICONO FACTURACION-01.svg';
        $titulo = 'Perfil empresa';
        $backIcon = true;
        $ruteEdit = $ruta . "central_notificaciones/";
        require_once $ruta . 'menu3.php';
    ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <?php
          $rutatb = "../";
          require_once '../topbar.php';
        ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->

          <!-- DataTales Example -->
          <!--
          <div class="card shadow mb-4">

            <div class="card-body">
-->       
          <div class="row">
            <div class="col-lg-12">
              <div class="alert alert-warning" role="alert" id="fiscal_data_required"></div>
              <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                  <a class="nav-link active" id="datos_fiscales-tab" data-toggle="tab" href="#datos_fiscales" role="tab" aria-controls="datos_fiscales" aria-selected="true">Datos fiscales</a>
                </li>
                <li class="nav-item" role="presentation">
                  <a class="nav-link" id="certificados-tab" data-toggle="tab" href="#certificados" role="tab" aria-controls="profile" aria-selected="false">Certificados</a>
                </li>
              </ul>    
              
              <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="datos_fiscales" role="tabpanel" aria-labelledby="datos_fiscales-tab">
                  <div class="card shadow mb-4">
                    <div class="card-body">
                      <div class="form-group">
                        <div class="row">
                          <div class="col-lg-6">
                            <img id="logo_empresa" class="img-thumbnail" src="" alt="..." width="250" >
                            <!--<div class="mb-4" style="width:150px; height:150px; border:1px solid #e3e6f0;border-radius: 0.35rem;" float-left>
                              
                            </div>-->
                          </div>
                        </div>
                        <br>
                        <form id="upload_logo">
                          <div class="row">
                            <div class="col-lg-3">
                              <div class="form-group inputFilePaddingLogo" style="border:none !important">
                                <label for="fileLogo" id="fileLogoName" data-toggle="tooltip" data-placement="bottom" title="Clic para subir logo"><i class="fas fa-cloud-upload-alt"></i> Subir logo</label>
                                <input style="border:none !important" type="file" class="form-control" name="fileLogo" id="fileLogo" accept="image/jpg,image/jpeg,image/png,image/gif">
                              </div>
                            </div>
                          </div>
                        </form>
                      </div>
                      <form id="data-enterprise">
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg">
                              <label for="txtNombreEmpresa">Nombre comercial:*</label>
                              <input class="form-control" type="text"name="txtNombreEmpresa" id="txtNombreEmpresa" required>
                              <div class="invalid-feedback" id="invalid-nombreEmpresa">La empresa debe de tener un nombre comercial.</div>
                            </div>
                            <div class="col-lg">
                              <label for="txtRazonSocial">Razón social:*</label>
                              <input class="form-control" type="text" name="txtRazonSocial" id="txtRazonSocial" required>
                              <div class="invalid-feedback" id="invalid-razonSocial">La empresa debe de tener una razon social.</div>
                              <div class="invalid-feedbackFormat" id="invalid-razonSocialFormat">La razon social no debe contener su régimen sociativo por petición del SAT.</div>
                            </div>
                            <div class="col-lg">
                              <label for="txtRfcl">RFC:</label>
                              <input class="form-control" type="text" name="txtRfc" id="txtRfc" readonly data-toggle="tooltip" data-placement="bottom" title="Se guarda al subir los certificados">
                            </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg">
                              <label for="cmbRegimenFiscal">Régimen fiscal:*</label>
                              <select name="cmbRegimenFiscal" id="cmbRegimenFiscal" required></select>
                              <div class="invalid-feedback" id="invalid-regimenFiscal">La empresa debe de tener un régimen fiscal.</div>
                            </div>
                            <div class="col-lg">
                              <label for="txtTelefono">Teléfono:</label>
                              <input class="form-control" type="text" name="txtTelefono" id="txtTelefono">
                            </div>
                          </div>
                        </div>

                        <br>
                      
                        <p>Dirección Fiscal</p>
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg">
                              <label for="txtCalle">Calle:</label>
                              <input class="form-control" type="text" name="txtCalle" id="txtCalle" required>
                              <div class="invalid-feedback" id="invalid-calle">La empresa debe de tener una calle.</div>
                            </div>
                            <div class="col-lg">
                              <label for="txtNumeroExterior">Número exterior:</label>
                              <input class="form-control" type="text" name="txtNumeroExterior" id="txtNumeroExterior" required>
                              <div class="invalid-feedback" id="invalid-noExterior">La empresa debe de tener un número exterior.</div>
                            </div>
                            <div class="col-lg">
                              <label for="txtNumeroInterior">Número interior:</label>
                              <input class="form-control" type="text" name="txtNumeroInterior" id="txtNumeroInterior">
                            </div>
                          </div>
                          <br>
                          <div class="row">
                            <div class="col-lg">
                              <label for="txtCp">Código postal:*</label>
                              <input class="form-control" type="text" name="txtCp" id="txtCp" required>
                              <div class="invalid-feedback" id="invalid-codigoPostal">La empresa debe de tener un código postal.</div>
                            </div>
                            <div class="col-lg">
                              <label for="txtColonia">Colonia:</label>
                              <input class="form-control" type="text" name="txtColonia" id="txtColonia">
                            </div>
                            <div class="col-lg">
                              <label for="txtCiudad">Ciudad:</label>
                              <input class="form-control" type="text" name="txtCiudad" id="txtCiudad">
                            </div>
                          </div>
                          <br>
                          <div class="row">
                            <div class="col-lg">
                              <label for="cmbEstado">Estado:</label>
                              <select name="cmbEstado" id="cmbEstado"></select>
                            </div>
                            <div class="col-lg">
                              <label for="txtPais">País:</label>
                              <input class="form-control" type="text" name="txtPais" id="txtPais" readonly>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg">
                              <div class="d-flex align-items-end flex-column">
                                <a class="btn-table-custom--blue mt-auto p-2" id="btn_save_data" data-toggle="tooltip" data-placement="top" title="Clic para guardar datos de la empresa"><i class="fas fa-plus-square" ></i> Guardar</a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                
                <div class="tab-pane fade show" id="certificados" role="tabpanel" aria-labelledby="certificados-tab">
                  <div class="card shadow mb-4">
                    <div class="card-body">
                    <div class="form-group">
                      <form id="data-certificate">
                        <div class="row">
                          <div class="col-lg-6">
                            <div class="alert alert-success" role="alert" id="expired_date_certificate"></div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-lg-3">
                            <label for="">Archivo .cert*:</label>
                            <div class="form-group inputFilePadding">
                              <label for="fileCer" id="fileUpdateCerName" data-toggle="tooltip" data-placement="top" title="Clic para resubir archivo .cer"><i class="fas fa-cloud-upload-alt"></i></label>
                              <input type="file" class="form-control" name="fileCer" id="fileCer" accept=".cer" required>
                              <input id="uploadFileUpdateCer" placeholder="No hay archivo" disabled="disabled" data-toggle="tooltip" data-placement="top">
                            </div>
                            <div class="invalid-feedback" id="invalid-fileCer">El archivo .cert es obligatorio.</div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-lg-3">
                            <label for="">Archivo .key*:</label>
                            <div class="form-group inputFilePadding">
                              <label for="fileKey" id="fileUpdateKeyName" data-toggle="tooltip" data-placement="top" title="Clic para resubir archivo .key"><i class="fas fa-cloud-upload-alt"></i></label>
                              <input type="file" class="form-control" name="fileKey" id="fileKey" accept=".key" required>
                              <input id="uploadFileUpdateKey" placeholder="No hay archivo" disabled="disabled" data-toggle="tooltip" data-placement="top">
                            </div>
                            <div class="invalid-feedback" id="invalid-fileKey">El archivo .key es obligatorio.</div>
                          </div>
                        </div>
                      
                      
                        <div class="row">
                          <div class="col-lg-3">
                            <div class="form-group">
                              <label for="txtPasswordCert">Contraseña certificado*:</label>
                              <input type="password" class="form-control" name="txtPasswordCert" id="txtPasswordCert" autocomplete="on" data-toggle="tooltip" data-placement="top" title="Ingrese la contrasela del certificado." required>
                              <div class="invalid-feedback" id="invalid-passwordCert">La contraseña del certificado es obligatoria.</div>
                            </div>
                          </div>
                        </div>
                      </form>
                      <br>
                      <div class="row">
                        <div class="col-lg">
                          <div class="d-flex align-items-end flex-column">
                            <a class="btn-table-custom--blue mt-auto p-2" id="btn_save_certificate"><i class="fas fa-plus-square"></i> Guardar</a>
                          </div>
                        </div>
                      </div>
                      
                    </div>
                    </div></div>
                </div>
              </div>
            </div>

            </div>
<!--
            </div>

          </div>
-->
        </div>
        <!-- End Page Content -->

      </div>
      <!-- End Main Content -->

    </div>
    <!-- End Content Wrapper -->

  </div>
  <!-- End Page Wrapper -->

</body>
</html>
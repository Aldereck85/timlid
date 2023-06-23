<?php

  $screen = 69;
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
  <meta http-equiv="Expires" content="0">
  <meta http-equiv="Last-Modified" content="0">
  <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <title>Timlid | Punto de venta</title>

  <!-- ESTILOS -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../vendor/KeyTable/css/keyTable.dataTables.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../css/sweetalert2.css" rel="stylesheet">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <link href="css/index.css" rel="stylesheet">
 
  <!-- JS -->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../vendor/moment/moment.min.js"></script>
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/KeyTable/js/dataTables.keyTable.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/datetime-moment.js"></script>
  <script src="../../vendor/datatables/datetime.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>
  <script src="../../js/jquery.redirect.min.js"></script>
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="../../js/mdtimepicker.min.js"></script>
  <script src="../../js/permisos_usuario.js"></script>
  <script src="../../js/jquery.redirect.js"></script>
  <script src="../../js/numeral.min.js"></script>
  <script src="js/slimselect_punto_venta.js"></script>
  <script src="../../js/lobibox.min.js"></script>
  <script src="../../js/sweet/sweetalert2.js"></script>
  
</head>

<body id="page-top" data-screen="16">
  <div id="loader"></div>
  <div id="loader1"></div>
  <div id="loader2"></div>
  <?php 
    include_once "modal_product_finder.php";
    include_once "modal_update_quantity.php"; 
    include_once "modal_create_product.php"; 
    include_once "modal_add_tax.php";
    include_once "modal_product_checker.php";
    include_once "modal_opening_cash_register.php";
    include_once "modal_product_service_key.php";
    include_once "modal_product_unit_key.php";
    include_once "modal_add_cash_register_question.php";
    include_once "modal_cancel_add_cash_register.php";
    include_once "modal_redirect_dashboard.php";
    include_once "modal_add_cash_register.php";
    include_once "modal_products_found_seeker.php";
    include_once "modal_update_product_ticket.php";
    include_once "modal_update_product.php";
    include_once "modal_update_tax.php";
    include_once "modal_products_found_seeker_checker.php";
    include_once "modal_pedding_sale.php";
    include_once "modal_product_sales.php";
    include_once "modal_cash_register_movements.php";
    include_once "modal_cash_closing.php";
    include_once "modal_add_professional_license.php";
    include_once "modal_create_client.php";
    include_once "modal_tickets_view.php";
    include_once "modal_config_tickets.php";
    include_once "modal_invoice_general.php";
    include_once "modal_fiscal_data.php";
    include_once "modal_fiscal_data_general.php";
    include_once "modal_details_tickets_view.php";
    include_once "modal_add_personal.php";
    include_once "modal_categories_products.php";
    include_once "modal_marks_products.php"
  ?>

  <!-- Page Wrapper -->
  <div id="wrapper">
  
    <!-- Sidebar -->
    <?php
        $icono = 'ICONO-PUNTO-DE-VENTA-AZUL.svg';
        $titulo = 'Punto de venta';
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

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-body">

              

              <nav id="btn-controls-sales-outlet" class="navbar navbar-expand-lg navbar-light bg-light">

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                  <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                      <a href="#" class="nav-link" id="list_tickets" data-toggle="modal" data-target="#modal_tickets_view">
                        <span>
                        <img src="../../img/punto_venta/listar_tickets1.svg" width="30">
                          Listar tickets
                        </span>
                      </a>
                      
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#" data-toggle="modal" data-target="#product_checker">
                        <span data-toggle="tooltip" data-placement="top" title="Atajo: ctrl + F3">
                          <img src="../../img/punto_venta/ICONO VERIFICADOR DE PRECIOS-01.svg" width="30">
                          Verificador
                        </span>
                      </a>
                    </li>
                    <li class="nav-item dropdown">
                      <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span data-toggle="tooltip" data-placement="top" title="Productos">
                          <img src="../../img/punto_venta/productos.svg" width="30">
                          Productos
                        </span>  
                      
                      </a>
                      <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#product_finder">
                          <span data-toggle="tooltip" data-placement="right" title="Atajo: ctrl + F2">
                            Buscar producto
                          </span>
                         
                        </a>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#create_product">
                          <span data-toggle="tooltip" data-placement="right" title="Atajo: ctrl + F1">
                            Agregar Producto
                          </span>
                          
                        </a>
                      </div>
                    </li>

                    <li id="li_link_informes"></li>
                    
                  </ul>
                  <ul class="navbar-nav no-visible" id="itemsBoxSuc">
                    <li class="nav-item" data-toggle="tooltip" data-placement="top" title="Atajo: ctrl + alt + 1" id="li_add_cash_register">
                     
                    </li>
                    <li class="nav-item" data-toggle="tooltip" data-placement="top" title="Atajo: ctrl + alt + 2">
                      <a class="nav-link" href="#" id="btn_change_cash_register" data-toggle="modal" data-target="#opening_cash_register" onclick="clearTicketTable()"> <img src="../../img/punto_venta/cambiar_caja.svg" width="30" > Cambiar de caja</a>
                    </li>
                    <li class="nav-item" data-toggle="tooltip" data-placement="top" title="Atajo: ctrl + alt + 3" onclick="getCurrentBalance()">
                      <a class="nav-link" href="#" id="btn_movement_cash_register" data-toggle="modal" data-target="#add_cash_register_movements" > <img src="../../img/punto_venta/movimiento_caja.svg" width="30" > Movimiento en caja</a>
                    </li>
                    <li class="nav-item" data-toggle="tooltip" data-placement="top" title="Atajo: ctrl+ alt + 4">
                      <a class="nav-link" href="#" id="btn_close_cash_register"> <img src="../../img/punto_venta/ICONO CORTE DE CAJA-01-01.svg" width="30" > Corte de caja</a>
                    </li>
                    
                   
                  </ul>

                  <ul class="navbar-nav no-visible" id="idBoxSuc">
                    <li class="nav-item nav-link">
                      
                      <label style="color:#15589b">                               
                        <b>Caja: </b><span id="labelCaja"></span> 
                        <!-- <a href="#" data-toggle="tooltip" data-placement="top" title="Configuración">
                          <img src="../../img/punto_venta/ICONO CONFIGURACION-01.svg" width="35" data-toggle="modal" data-target="#modal_config_tickets">
                        </a> -->
                      </label><br>
                      <input type="hidden" name="txtCashRegisterId" id="txtCashRegisterId">
                      <label style="color:#15589b"><b>Sucursal: </b><span id="labelSucursal"></span> </label>
                      <input type="hidden" name="txtBranchOfficeId" id="txtBranchOfficeId"><br>
                      <label style="color:#15589b"><b><span id="txtUserType"></span></b><span id="txtEmployedName"></span></label><br>
                      <input type="hidden" name="txtEmployeId" id="txtEmployeId">
                      <label style="color:#15589b"><span id="txtActiveInventory"></span></label>
                      <input type="hidden" name="txtHasPrescription" id="txtHasPrescription">
                      <input type="hidden" name="txtHideProfesionalLincese" id="txtHideProfesionalLincese">
                      <input type="hidden" name="txtHideUserType" id="txtHideUserType">
                        
                    </li>
                  </ul>

                </div>
              </nav>
              <br>
              <div class="row">
                <div class="col-4">
                  <div class="card">
                    <div class="card-body">
                      <form id="form-general-data-ticket">
                        <div class="form-group">
                          <!--<a href="#" id="btn-testing">Pruebas</a>-->
                          <div class="row">
                            <div class="col">
                              <label for="txtSearchProduct">Producto:</label>
                              <input class="form-control alphaNumericDotAlter-only" type="text" name="txtSearchProduct" id="txtSearchProduct">
                            </div>
                      
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="row">
                            <div class="col">
                              <label for="cmbDocument">Documento</label>
                              <select name="cmbDocument" id="cmbDocument">
                                <option data-placeholder='true'></option>
                                <option value="1" selected>Ticket</option>
                                <option value="2">Factura</option>
                                <option value="3">Nota de venta</option>
                                <option value="4">Remisión</option>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="form-group" id="comboClient">
                          <div class="row">
                            <div class="col">
                              <label for="cmbClient">Cliente:</label>
                              <select name="cmbClient" id="cmbClient"></select>
                              <div class="invalid-feedback" id="invalid-ticketClient">El ticket debe tener un cliente.</div>
                              <!-- <a class="btn-custom mr-2 mt-2 btn-custom--white-dark" id="btn_add_client" href="#">
                                <span class="d-flex align-items-center">
                                  <img src="../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1">
                                  Agregar cliente
                                </span>
                              </a> -->
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                  
                  
                </div>
                
              </div>
                <div class="d-flex justify-content-center" id="alert_price_zero">
                    <div class="alert alert-danger" style="width:42%;" role="alert">
                        Hay productos con el precio unitario en cero. Favor de editar el precio para continuar.
                    </div>
                </div> 
                
              
              <div class="table-responsive">
                <table class="table" id="tblTicket" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th class="disable-select"></th>
                      <th class="disable-select">Cant.</th>
                      <th></th>
                      <th class="disable-select">Descripción</th>
                      <th class="disable-select">Exis.</th>
                      <th class="disable-select">% Desc</th>
                      <th class="disable-select">Precio U.</th>
                      <th class="disable-select">Importe</th>
                      <th class="disable-select"></th>
                    </tr>
                  </thead>
                  <tbody></tbody>

                </table>
                
              </div>
              <div class="row">
                <div class="col-4"></div>
                <div class="col-4"></div>
                <div class="col-4">
                  <div class="card">
                    <div class="card-body">
                      <form class="disable-select" id="form-subtotals-ticket">
                        <div class="row" style="border-bottom: 1px solid #e3e6f07d;">
                          <div class="col-4">
                            <p>Subtotal:</p>
                            <input type="hidden" name="subtotal-ticket-hidden" id="subtotal-ticket-hidden">
                          </div>
                          <div class="col-4"></div>
                          <div class="col-4">
                            <p id="subtotal-ticket">$0.00</p>
                          </div>
                          
                        </div>
                        <div class="row">
                          <div class="col-4">
                            <p>Impuestos:</p>
                          </div>
                          
                        </div>
                        <div class="row" style="padding-bottom:15px;border-bottom: 1px solid #e3e6f07d;">
                          <div class="col-1"></div>
                          <div class="col-7" id="ticket-taxes-names"></div>
                          <div class="col-4" id="ticket-taxes-prices"></div>
                        </div>
                        <div class="row" style="padding-bottom:15px;border-bottom: 1px solid #e3e6f07d;">
                          <div class="col-4">
                            <p>Descuento <span id="discont-porcent"></span> :</p>
                          </div>
                          <div class="col-4"></div>
                          <div class="col-4">
                            <p id="ticket-discount">$0.00</p>
                          </div>
                        </div>
                        <div class="row" style="font-size:1.5em;border-bottom: 1px solid #e3e6f07d;">
                          <div class="col-4">
                            <p>Total:</p>
                          </div>
                          <div class="col-4"></div>
                          <div class="col-4">
                            <p id="ticket-total-price">$0.00</p>
                            <input type="hidden" name="ticket-total-price-hidden" id="ticket-total-price-hidden">
                          </div>
                        </div>

                      </form>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-9"></div>
                <div class="col-2">
                    <div>
                        <a class="btn-table-custom--blue float-right" href="#" onclick="clearTable()">
                            <img src="../../img/punto_venta/ICONO-LIMPIAR TABLA AZUL NVO-01.svg" width="60"> Limpiar tabla
                        </a>
                    </div>
                </div>
                <div class="col-1">

                    <div data-toggle="tooltip" data-placement="top" title="Atajo: ctrl + Enter">
                        <a class="btn-table-custom--blue float-right" href="#" onclick="get_ifProductoPrescription()">
                            <img src="../../img/punto_venta/ICONO-VENDER AZUL NVO-01.svg" width="60" > Vender
                        </a>
                    </div>
                    
                </div>
                
              </div>
            </div>
            
          </div>

        </div>
        <!-- End Page Content -->

      </div>
      <!-- End Main Content -->
      <!-- Footer -->
      <?php
        $rutaf = "../";
        require_once('../footer.php');
      ?>
      <!-- End of Footer -->
    </div>
    <!-- End Content Wrapper -->

  </div>
  <!-- End Page Wrapper -->
  <script src="js/tax_table.js"></script>
  <script src="js/tax_update_table.js"></script>
  <script src="js/product_finder.js"></script>
  <script src="js/product_finder_all.js"></script>
  <script src="js/product_service_key_table.js"></script>
  <script src="js/product_unit_key_table.js"></script>
  <script src="js/create_product.js"></script>
  <script src="js/products_found_seeker.js"></script>
  <script src="js/update_product.js"></script>
  <script src="js/products_found_seeker_checker.js"></script>
  <script src="js/pedding_sales.js"></script>
  <script src="js/index.js"></script>
  <script src="js/tickets_tables.js"></script>
  <script src="js/general_invoice.js"></script>
  <script src="js/details_tickets_tables.js"></script>

  <script src="../../js/validaciones.js"></script>
  <script src="../../js/scripts.js"></script>
</body>
</html>
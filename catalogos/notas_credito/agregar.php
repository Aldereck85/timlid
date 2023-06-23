<?php
session_start();

if (isset($_SESSION["Usuario"])) {
  require_once '../../include/db-conn.php';
} else {
  header("location:../dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | Crear nota de crédito</title>
  <link href="css/agregar_animates.css" rel="stylesheet">
  <!-- ESTILOS -->
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../css/stylesModal-lg.css" rel="stylesheet">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../css/notificaciones.css" rel="stylesheet">
  <link href="css/agregar.css" rel="stylesheet">
  <link href="css/disabled.css" rel="stylesheet">

  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="js/agregar.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../js/validaciones.js"></script>
  <script src="../../js/lobibox.min.js"></script>
  <script src="../../js/slimselect.min.js"></script>
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>



</head>

<body id="page-top" class="sidebar-toggled">

  <!-- Content Wrapper -->

  <div id="wrapper">
    <!-- Sidebar -->
    <?php
    $titulo = "Crear nota de crédito";
    $ruta = "../";
    $ruteEdit = $ruta . "central_notificaciones/";
    require_once '../menu3.php';
    ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Topbar -->
      <?php
      $rutatb = "../";
      $backIcon = true;
      //se define la ruta de regreso
      if(isset($_REQUEST['rutaFrom'])){
        switch ($_REQUEST['rutaFrom']){
          case 1:
            $_REQUEST['rutaFrom']='../cuentas_cobrar/cuentas_Cliente.php?periodo='. ($_REQUEST['periodo']) .'&id='. ($_REQUEST['idCliente']) . '&seleccion='. ($_REQUEST["seleccion"]);
            break;
          case 2:
            $_REQUEST['rutaFrom']='../cuentas_cobrar/detalle_factura.php?idFactura='.$_REQUEST['idFactura'];
            break;
        }
      }
      
      $backRoute = (isset($_REQUEST["rutaFrom"]) && ($_REQUEST["rutaFrom"] != "" || $_REQUEST["rutaFrom"] != null)) ? $_REQUEST["rutaFrom"] : "../notas_credito";

      $icono = 'ICONO-CUENTAS-POR-COBRAR-AZUL.svg';
      require_once "../topbar.php"
      ?>
      <!-- End of Topbar -->
      <!-- Main Content -->
      <div id="content">
        <!-- id del cliente provenienete de cuentas cobrar -->
        <?php
          if (isset($_REQUEST["idCliente"])) {
            echo ('<input type="hidden" id="idClienteFrom" value="' . $_REQUEST['idCliente'] . '">');
          }
          if (isset($_REQUEST["idFactura"])) {
            echo ('<input type="hidden" id="idFacturaFrom" value="' . $_REQUEST['idFactura'] . '">');
          }
        ?>
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-12">
              <ul class="nav nav-tabs" id="nav-tab" role="tablist">
                <li class="nav-item">
                  <a id="CargarFacturas" class="nav-item nav-link active" href="#datos" data-toggle="tab" role="tab" aria-controls="nav-datos" aria-selected="true">
                    Facturas
                  </a>
                </li>
                <li class="nav-item nav-item">
                  <a id="CargarConceptos" class="nav-item nav-link disabled" href="#concepto" data-toggle="tab" role="tab" aria-controls="nav-datos" aria-selected="false">
                    Conceptos
                  </a>
                </li>
              </ul>
                <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
                <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
                <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">
                <!-- Comprobar permisos para estar en la pagina -->
                <?php
                ///Primera parte comprueba si puede ver
                $pkuser = $_SESSION["PKUsuario"];
                $stmt = $conn->prepare("Select funcion_ver, funcion_agregar, funcion_editar, funcion_eliminar, funcion_exportar, 
                pantalla_id, fp.perfil_id from funciones_permisos as fp inner join usuarios as us 
                on fp.perfil_id = us.perfil_id where us.id = $pkuser and pantalla_id = 60");
                $stmt->execute();
                $row = $stmt->fetch();
                if(isset($row['funcion_ver'])){
                  //Ponemos en el DOM el permiso ver
                  echo ('<input id="ver" type="hidden" value="' . $row['funcion_ver'] . '">');
                  //Ponemos en el DOM el permiso editar.
                  echo ('<input id="edit" type="hidden" value="' . $row['funcion_editar'] . '">');
                }else{
                  /* header("location:../../dashboard.php"); */
                }
                ?>
                <div class="tab-content" id="nav-tabContent">
                  <div class="tab-pane fade show active" id="datos" role="tabpanel" aria-labelledby="nav-main-tab">
                                    <!-- Begin Page Content -->
                    <div class="card shadow mb-4">
                      <div class="card-body">
                        <form id="formAddH" action="" onsubmit="enviarDatosEmpleado(); return false" class=mb-4>
                          <div class="row">
                            <div class= "col-sm-3">
                                <div class="col-xl-10 col-lg-8 col-md col-sm col-xs">
                                    <label for="cmbCliente">Cliente:*</label>
                                    <select name="cmbCliente" class="form-select" id="cmbCliente" aria-label="Default select example">
                                    </select>
                                    <div class="invalid-feedback" id="invalid-nombreProv">El producto debe tener un Cliente.</div>
                                </div>
                            </div>
                            <div class="col-sm-3" Style="padding-top: 1em;">
                                  <a id="modalshow" class="btn-custom btn-custom--blue btn-sm float-center" data-toggle="modal" data-target="#mod_agregarFacturas" style="margin-left: 3em;">
                                    Relacionar factura
                                  </a>
                            </div>
                            <div class= "col-sm-3">
                                <div class="col-xl-10 col-lg-8 col-md col-sm col-xs">
                                    <label for="usr">Forma de pago:*</label>
                                    <select name="cmbFMPago" class="form-select" id="cmbFMPago" aria-label="Default select example">
                                    </select>
                                    <div class="invalid-feedback" id="invalid-cmbFMPago">El producto debe tener un Folio de Factura.</div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="col-xl-10 col-lg-8 col-md col-sm col-xs">
                                <label for="cmbRelacion">Uso de la factura:*</label>
                                    <select  name="cmbMPago" class="form-select" id="cmbRelacion" aria-label="Default select example" onClick="click(this)">
                                    </select>
                                    <div class="invalid-feedback" id="invalid-tipo">Campo requerido</div>
                                </div>
                            </div>
                          </div>
                        <!-- <div class="row">
                          <div class="form-group col-md-2">
                            <label for="usr">Fecha factura:</label>
                            <input type="text" maxlength="50" class="form-control disabled" name="txtfechaF" id="txtfechaF" value="" required maxlength="50" placeholder="Ej. AA - 0001" onkeyup="escribirClave()">
                            <div class="invalid-feedback" id="invalid-claveProd">El producto debe tener una clave interna.</div>
                          </div>
                          <div class="form-group col-md-2">
                            <label for="usr">Fecha que vence:</label>
                            <input type="text" maxlength="50" class="form-control disabled" name="txtfechaV" id="txtfechaV" value="" required maxlength="50" placeholder="Ej. AA - 0001" onkeyup="escribirClave()">
                            <div class="invalid-feedback" id="invalid-claveProd">El producto debe tener una clave interna.</div>
                          </div>
                          <div class="form-group col-md-2">
                            <label for="usr">Subtotal:*</label>
                            <div class="d-flex align-items-center">
                              <label class="mr-1">$</label><input class="form-control numericDecimal-only readEditPermissions edit" type="text" name="subtotal" id="subtotal" autofocus="" required="" min="0" maxlength="13" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 30.00" onkeyup="validEmptyInput('subtotal', 'invalid-subtotal', 'Campo requerido'), numberWithSpaces('subtotal')">
                            </div>
                            <div class="invalid-feedback" id="invalid-subtotal">El producto debe tener un Subtotal.</div>
                          </div>
                          <div class="form-group col-md-2">
                            <label for="usr">Importe:*</label>
                            <div class="d-flex align-items-center">
                              <label class="mr-1">$</label><input class="form-control numericDecimal-only readEditPermissions edit" type="text" name="txtimporte" id="txtimporte" autofocus="" required="" min="0" maxlength="13" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 30.00" onkeyup="validEmptyInput('txtimporte', 'invalid-importe', 'Campo requerido'), numberWithSpaces('txtimporte')">
                            </div>
                            <div class="invalid-feedback" id="invalid-importe">El Importe no puede estar vacio</div>
                          </div>
                          <div class="form-group col-md-2">
                            <label for="usr">IVA:*</label>
                            <div class="d-flex align-items-center">
                              <label class="mr-1">$</label><input class="form-control numericDecimal-only readEditPermissions edit" type="text" name="_txtiva" id="_txtiva" autofocus="" required="" min="0" maxlength="13" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 30.00" onkeyup="validEmptyInput('_txtiva', 'invalid-_txtiva', 'Campo requerido para excluir escriba 0.'), numberWithSpaces('_txtiva')">
                            </div>
                            <div class="invalid-feedback" id="invalid-_txtiva">Campo requerido para excluir escriba "0"</div>
                          </div>
                          <div class="form-group col-md-2">
                            <label for="usr">IEPS:*</label>
                            <div class="d-flex align-items-center">
                              <label class="mr-1">$</label><input class="form-control numericDecimal-only readEditPermissions edit" type="text" name="_txtieps" id="_txtieps" autofocus="" required="" min="0" maxlength="13" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 30.00" onkeyup="validEmptyInput('_txtieps', 'invalid-_txtieps', 'Campo requerido para excluir escriba 0.'), numberWithSpaces('_txtieps')">
                            </div>
                            <div class="invalid-feedback" id="invalid-_txtieps">Campo requerido para excluir escriba "0"</div>
                          </div>
                        </div> -->
                        <div class="row">
                          <div class= "col-sm-3">
                            <br>
                            <label>* Campos requeridos</label>
                          </div>
                          <div class="col-sm-3" Style="padding-top: 1em; align-items: center;">
                            <a id="modalshow" class="btn-custom btn-custom--blue btn-sm float-center" data-toggle="modal" data-target="#mod_agregarVenta" style="margin-left: 3em;">
                              Relacionar venta
                            </a>
                          </div>
                        </div>
                        </form>
                          <div class="table-responsive">
                            <table class="table" id="tblFacturasCliente" width="100%" cellspacing="0">
                              <thead>
                                <tr>
                                  <th>Folio factura</th>
                                  <th>Serie</th>
                                  <th>Fecha de timbrado</th>
                                  <th>Monto</th>
                                  <th>Eliminar</th>
                                  <th>Id</th>
                                </tr>
                              </thead>
                            </table>
                          </div>
                          <div class="row justify-content-md-center">
                          <div class="col-lg">
                            <div class="d-flex justify-content-end">
                              <a id="btnSiguiente" class="btn-custom btn-custom--blue float-right">
                                Siguiente
                              </a>
                            </div>
                          </div>
                          </div>
                      </div>
                    </div>
                    <?php
                    //require_once 'modal_alert_confirm.php';
                    //require_once 'modalnoempresa.php';
                    //require_once 'modal_alert.php';
                    ?>
                  </div>
                  <div class="tab-pane fade" id="concepto" role="tabpanel" aria-labelledby="nav-main-tab">
                    <div class="card shadow mb-4">
                    <div class="card-body">
                        <div id="conceptos_section" style="display:none;">
                          <div class="row justify-content-md-center">
                            <div disabled style="cursor: default;" class="col align-self-center disabled"><h2>Conceptos</h2></div>
                          </div>
                          <div class="row">
                          <div class= "col-sm-4">
                            <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                                  <label for="cmbFactura">Factura:*</label>
                                  <select name="cmbFactura" class="form-select" id="cmbFactura" aria-label="Default select example">
                                  </select>
                                  <div class="invalid-feedback" id="invalid-nombreProv">Seleccione la factura a la que pertenece el concepto.</div>
                              </div>
                            </div>
                            </div>
                            <br>
                            <!-- CONCEPTO -->
                            <div class="row">
                              <div class= "col-sm-4">
                                  <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                                      <label for="usr">Concepto:*</label>
                                      <select name="ConceptosAJAX" class="form-select" id="ConceptosAJAX" aria-label="Default select example">
                                      </select>
                                      <div class="invalid-feedback" id="invalid-nombreProv">Seleccione el concepto</div>
                                      <!-- <input class="form-control alphaNumeric-only" type="text" name="Concepto" value="" id="Concepto" required maxlength="100" placeholder="Ej. Vaso recolector" onkeyup="cargarListConceptos(this.value)" list="listConceptos">
                                      <div class="invalid-feedback" id="invalid-Concepto">La clave no puede estar vacia.</div>
                                      <div id="lista">
                                        <datalist id="listConceptos">
                                        </datalist>
                                      </div> -->
                                  </div>
                              </div>
                              <div class= "col-sm-4">
                                  <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                                      <label for="usr">Clave interna:*</label>
                                      <input class="form-control disabled" readonly type="text" name="ClaveI" value="" id="ClaveI" required maxlength="100" placeholder="Ej. PW200">
                                      <div class="invalid-feedback " id="invalid-ClaveI">La clave no puede estar vacia.</div>
                                      <div id="listaUnidad">
                                        <datalist id="listClavesUnidad">

                                        </datalist>
                                      </div>
                                  </div>
                              </div>
                              <div class= "col-sm-4">
                                  <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                                      <label for="usr">Clave SAT:*</label>
                                      <div id="ClaveST">
                                        <input class="form-control numeric-only disabled" readonly type="text" name="ClaveSAT" value="" id="ClaveSAT" required maxlength="100" placeholder="Ej. 41121801" >
                                        <div class="invalid-feedback" id="invalid-ClaveSAT"></div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          <br>
                            <div class="row">
                              <!-- <div class= "col-sm-4">
                                  <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                                      <label for="usr">Clave de producto o servicio:*</label>
                                      <input class="form-control alphaNumeric-only" type="text" name="CalveP" value="84111506" id="CalveP" required maxlength="100" placeholder="Ej. 841111506 Servicios de facturación" onkeyup="cargarListClaveSat(this.value)" list="listClaves">
                                      <div class="invalid-feedback" id="invalid-CalveP">La clave no puede estar vacia.</div>
                                      <div id="lista">
                                        <datalist id="listClaves">
                                        </datalist>
                                      </div>
                                  </div>
                              </div> -->
                              <div class= "col-sm-4">
                                  <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                                      <label for="usr">Clave de unidad:*</label>
                                      <div id="ClaveUnit">
                                      <input class="form-control disabled" readonly type="text" name="ClaveU"  id="ClaveU" required maxlength="6" placeholder="Ej. 30" onkeyup="cargarListClaveSat_unidad(this.value)" list="listClavesUnidad">
                                      </div>
                                      <div class="invalid-feedback " id="invalid-ClaveU"></div>
                                      <div id="listaUnidad">
                                        <datalist id="listClavesUnidad">

                                        </datalist>
                                      </div>
                                  </div>
                              </div>
                              <div class= "col-sm-4">
                                  <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                                      <label for="usr">Cantidad:*</label>
                                      <input class="form-control numeric-only" type="text" name="txtCantidads" value="1" id="txtCantidads" required maxlength="6" placeholder="Ej. ACT Actividad" >
                                      <div class="invalid-feedback" id="invalid-nombreProd">La cantidad no puede estar vacia.</div>
                                  </div>
                              </div>
                              <div class= "col-sm-4">
                                  <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                                      <label id="lblPrecio" for="usr">Precio unitario sin impuestos:*</label>
                                      <input class="form-control numericDecimal-only" type="text" name="txtImporte" value="" id="txtImporte" required maxlength="18" placeholder="Ej. 1000" onfocusout="focusout(this)" onkeyup="thisIsValid(this)">
                                      <div class="invalid-feedback" id="invalid-txtImporte">El producto debe tener un Folio de Factura.</div>
                                  </div>
                              </div>
                          </div>
                          <br>
                          <div class="row">
                  
                            <div class= "col-sm-12">
                                <div class="col-xl-8 col-lg-8 col-md col-sm col-xs">
                                    <label for="usr">Descripción:*</label>
                                    <input class="form-control alphaNumeric-only" type="text" name="txtDescripcion" value="" id="txtDescripcion" required maxlength="55" placeholder="Ej. Devolución de 5 impresoras HP Modelo X43" onkeyup="thisIsValid(this)">
                                    <div class="invalid-feedback" id="invalid-txtDescripcion">El producto debe tener un Folio de Factura.</div>
                                </div>
                            </div>
                          </div>
                          <div class="row">                       
                          <!-- Boton -->
                          <!-- 
                          <div class="row">
                            <div class="col-lg-3">
                              <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                                <label for="cmbDetallesF">Clave concepto</label>
                                <select class="cmbSlim" name="cmbDetallesF" id="cmbDetallesF" >
                                </select>
                              </div>
                            </div>
                            <div class="col-lg-2">
                              <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                                <label for="cmbDetallesF">Serie:</label>
                                <input class="form-control numeric-only" type="text" name="serietxt" id="serietxt"  maxlength="100" placeholder="Serie">
                              </div>
                            </div>
                            <div class="col-lg-2">
                              <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                                <label for="cmbDetallesF">Lote:</label>
                                <input class="form-control numeric-only" type="text" name="lotetxt" id="lotetxt"  maxlength="100" placeholder="Lote">
                              </div>
                            </div> -->
                            <div class="col-md-6">
                              <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                                <label for="usr">Impuesto:</label>
                                <select class="cmbSlim" name="cmbImpuestosp" id="cmbImpuestosp" multiple>
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="row" id="impuestos">
                            <div class= "col-sm-4">
                              <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                                <div class="custom-control custom-switch">
                                  <input type="checkbox" class="custom-control-input" id="chkImpuestosInclu" onclick="cambioImp()">
                                  <label class="custom-control-label" for="chkImpuestosInclu">¿Impuestos incluidos en el importe?</label>
                                </div>
                              </div>
                            </div>            
                          </div>
                          <!-- Tabla -->
                          <div class="row">
                          <label for="">* Campos requeridos</label>
                            <div class="col-lg">
                              <div class="d-flex justify-content-end">
                                <a id="addConcepto" class="btn-custom btn-custom--blue float-right">
                                  Agregar
                                </a>
                              </div>
                            </div>
                            
                          </div>
                          <div class="row justify-content-md-center">
                              <div class="col align-self-center"><h3>Conceptos Agregados</h3></div>
                          </div>
                          <div class="row">
                            <div class="table-responsive">
                              <table class="table" id="tblConceptos" width="100%" cellspacing="0">
                                <thead>
                                  <tr>
                                    <th>C.Producto/Servicio</th>
                                    <th>C.Unidad</th>
                                    <th>Descripción</th>
                                    <th>Precio Unitario</th>
                                    <th>Cantidad</th>
                                    <th>Impuestos</th>
                                    <th>Importe</th>
                                    <th>Acciones</th>
                                  </tr>
                                </thead>
                              </table>
                            </div>
                            <table class="table table-hover">
                              <tfoot>
                                <tr>
                                  <th style="text-align: right;">Subtotal:</th>
                                  <td style="text-align: right; width:400px!important"> <span id="Subtotal">0.00</span>
                                  </td>
                                  <th style="width:60px;"></th>
                                </tr>
                                <tr>
                                  <th style="text-align: right;">Impuestos:</th>
                                  <td id="impuestosX"></td>
                                  <th></th>
                                </tr>
                                <tr class="total redondearAbajoIzq">
                                  <th style="text-align: right;" class="redondearAbajoIzq">Total:</th>
                                  <td style="text-align: right;"> <span id="Total">0.00</span></td>
                                  <th></th>
                                </tr>
                              </tfoot>
                            </table>
                          </div>
                          <div class="d-flex justify-content-end mt-3">
                              <span id="spanbutton" class="" tabindex="0" data-toggle="tooltip" data-placement="top" title="Ir atras"><p></p><p></p>
                                <button class="btn-custom btn-custom--blue" type="button" id="btnAtras">Atras</button>
                              </span>
                              <span id="spanbutton" class="" tabindex="0" data-toggle="tooltip" data-placement="top" title="Guardar"><p></p><p></p>
                                <button class="btn-custom btn-custom--blue" type="button" data-toggle="modal" data-target="#mdlsavealert" id="btnguardarF">Guardar</button>
                              </span>
                          </div>
                        </div>
                        <div id="importeVenta_section">
                          <div class="row justify-content-md-center">
                            <div disabled style="cursor: default;" class="col align-self-center disabled"><h2>Venta</h2></div>
                          </div>
                          <div class="row">
                            <div class= "col-sm-4">
                              <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                                  <label for="cmbVenta">Folio venta:*</label>
                                  <select name="cmbVenta" class="form-select" id="cmbVenta" aria-label="Default select example">
                                  </select>
                                  <div class="invalid-feedback" id="invalid-cmbVenta">Seleccione la venta a relacionar.</div>
                              </div>
                            </div>
                          </div>
                          <br>
                          <div class="row">
                            <div class= "col-sm-6">
                              <div class="col-xl-12 col-lg-6 col-md col-sm col-xs">
                                <label for="usr">Descripción:*</label>
                                <input class="form-control alphaNumeric-only" type="text" name="txtDescripcionVenta" value="" id="txtDescripcionVenta" required maxlength="55" placeholder="Ej. Devolución de 5 impresoras HP Modelo X43" onkeyup="thisIsValid(this)">
                                <div class="invalid-feedback" id="invalid-txtDescripcionVenta">El producto debe tener una descripción.</div>
                              </div>
                            </div>
                            <div class= "col-sm-3">
                              <div class="col-xl-12 col-lg-6 col-md col-sm col-xs">
                                <label for="usr">Importe total:*</label>
                                <input class="form-control numericDecimal-only" type="text" name="txtImporteVenta" value="" id="txtImporteVenta" required maxlength="18" onkeyup="thisIsValid(this)">
                                <div class="invalid-feedback" id="invalid-txtImporteVenta">La nota de crédito debe tener un importe.</div>
                              </div>
                            </div>
                          </div>
                          <div class="d-flex justify-content-end mt-3">
                                <span id="spanbutton" class="" tabindex="0" data-toggle="tooltip" data-placement="top" title="Ir atras"><p></p><p></p>
                                  <button class="btn-custom btn-custom--blue" type="button" id="btnAtrasV">Atras</button>
                                </span>
                                <span id="spanbutton" class="" tabindex="0" data-toggle="tooltip" data-placement="top" title="Guardar"><p></p><p></p>
                                  <button class="btn-custom btn-custom--blue" type="button" data-toggle="modal" data-target="#mdlsavealert" id="btnguardarV">Guardar</button>
                                </span>
                            </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Footer -->
        <?php
        $rutaf = "../";
        require_once '../footer.php';
        ?>
        <?php
            require_once 'modal_addF.php';
        ?>
        <!-- End of Footer -->
      </div>
    </div>
  </div>

  <!-- End of Page Wrapper -->
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>
  <script>
    //VAlidar que no este vacio
    function validEmptyInput(inputID, invalidDivID, textInvalidDiv) {
      if (!$("#" + inputID).val()) {
        $("#" + inputID).addClass("is-invalid");
        $("#" + invalidDivID).css("display", "block");
        $("#" + invalidDivID).text(textInvalidDiv);
      } else {
        $("#" + inputID).removeClass("is-invalid");
        $("#" + invalidDivID).css("display", "none");
        $("#" + invalidDivID).text(textInvalidDiv);
      }
    }
    //Separar los numero en grupos de 3
    function numberWithSpaces(inputID) {
      $(".edit").blur(function() {
        var parts = $("#" + inputID).val().toString().split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, " ");
        var union = parts.join(".");
        $("#" + inputID).val(union);
      });

    }
  </script>
  <script>
    loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
    setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
  </script>
</body>

</html>
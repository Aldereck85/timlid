<?php
session_start();

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
  <title>Timlid | Percepciones</title>

  <!-- ESTILOS -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../css/stylesModal-lg.css" rel="stylesheet">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../css/notificaciones.css" rel="stylesheet">
  <!-- JS -->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="../../js/lobibox.min.js"></script>
  <script src="../../js/sweet/sweetalert2.js"></script>
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <script src="../../js/slimselect.min.js"></script>
  <script src="../../js/numeral.min.js"></script>
  <script src="../../js/jquery.number.min.js"></script>
  <script src="../../js/Cleave.js"></script>
  <style type="text/css">
    /* The container */
.container_esp {
  display: block;
  position: relative;
  bottom: 7px;
  left: 9%;
  cursor: pointer;
  font-size: 22px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* Hide the browser's default checkbox */
.container_esp input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}

/* Create a custom checkbox */
.checkmark {
  position: absolute;
  top: 0;
  left: 0;
  height: 25px;
  width: 25px;
  background-color: #eee;
}

/* On mouse-over, add a grey background color */
.container_esp:hover input ~ .checkmark {
  background-color: #ccc;
}

/* When the checkbox is checked, add a blue background */
.container_esp input:checked ~ .checkmark {
  background-color: #2196F3;
}

/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the checkmark when checked */
.container_esp input:checked ~ .checkmark:after {
  display: block;
}

/* Style the checkmark/indicator */
.container_esp .checkmark:after {
  left: 10px;
  top: 6px;
  width: 5px;
  height: 10px;
  border: solid white;
  border-width: 0 3px 3px 0;
  -webkit-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  transform: rotate(45deg);
}
  </style>
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
    $icono = '../../img/icons/puestos.svg';
    $titulo = "Percepciones";
    $ruta = "../";
    $ruteEdit = $ruta . "central_notificaciones/";
    require_once '../menu3.php';
    ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
      <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
      <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">
      <input type="hidden" id="txtPantalla" value="13">

      <!-- Main Content -->
      <div id="content">
        <?php
          $rutatb = "../";
          require_once "../topbar.php"
        ?>

        <div class="container-fluid">

          <div class="row">
            <div class="col-lg-12 config-tabs">
              <ul class="nav nav-tabs">
                <li class="nav-item">
                  <a class="nav-link active" href="#">Percepciones</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="deduccionesbase.php">Deducciones</a>
                </li>
              </ul>
            </div>
          </div>
        <!-- Begin Page Content -->
          <!-- Page Heading -->
          <!-- DataTales Example -->
          <div class="card mb-4 data-table">
            <!-- <div class="card-header py-3">
              <div class="float-right">
                <div class="button-container2" id="">
                  <div class="button-icon-container">
                    <a href="#" class="btn btn-circle float-right waves-effect waves-light shadow btn-table" id="btn-nomina" data-toggle="modal" data-target="#agregar_nomina"><i class="fas fa-plus"></i></a>
                  </div>
                  <div class="button-text-container">
                    <span>Agregar nómina</span>
                  </div>
                </div>
              </div>
            </div> -->
            <div class="card-body">
              <div class="table-responsive">
                <table class="table" id="tblPercepcionesBase" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Concepto</th>
                      <th>Clave</th>
                      <th>Global</th>
                      <th>Sucursal</th>
                      <th></th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>

          </div>
        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <?php
        $rutaf = "../";
        require_once '../footer.php';
        ?>
        <!-- End of Footer -->

      </div>
      <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->
  </div>

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!--ADD MODAL CLAVE AND Tipo Automatico-->
  <!--
    <div class="modal fade right" id="agregar_base_tipo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
          <div class="modal-content">
            <form action="#" method="POST" id="agregarClaveTipo">
              <div class="modal-header">
                <h4 class="modal-title w-100" id="myModalLabel">Percepción</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">x</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label for="usr">Concepto:</label>
                  <select class="form-control" name="cmbConcepto" id="cmbConcepto" value="">
                  </select>
                </div>

                <div class="form-group" id="baseempresa">
                  <label for="usr">Base por empresa:</label>
                  <div class="row">
                      <div class="col-lg-6">
                        <input type="radio" id="tipoBase" name="tipoBase" value="1" class="tipoBase1 baseporempresa">
                        <label for="percepcion">No aplica</label>
                      </div>
                      <div class="col-lg-6">
                        <input type="radio" id="tipoBase" name="tipoBase" value="2" class="tipoBase2 baseporempresa">
                        <label for="deduccion">Automático</label>
                      </div>
                  </div>
                </div>

                <div class="form-group" id="basecalculo">
                  <label for="usr">Cálculo:</label>
                  <div class="row">
                      <div class="col-lg-6">
                        <input type="radio" id="tipoCalculo" name="tipoCalculo" value="1" class="tipoCalculo1 baseEmpresa">
                        <label for="percepcion">Porcentaje</label>
                      </div>
                      <div class="col-lg-6">
                        <input type="radio" id="tipoCalculo" name="tipoCalculo" value="2" class="tipoCalculo2 baseEmpresa">
                        <label for="deduccion">Cantidad</label>
                      </div>
                  </div>
                </div>

                <div class="form-group" id="basecalculo2">
                    <label for="percepcion">Valor</label>
                    <input type="text" class="form-control txtValor numericDecimal-only" name="txtValor" id="txtValor" value="" maxlength="14" required>
                </div>

                <div class="form-group" id="basesucursal">
                    <label for="usr">Base por sucursal:</label>
                    <select class="form-control" id="cmbSucursales" name="cmbSucursales">
                      <?php
                       /* $stmt = $conn->prepare('SELECT id, sucursal FROM sucursales WHERE estatus = 1 AND empresa_id = '.$_SESSION['IDEmpresa']);
                        $stmt->execute();
                        $row = $stmt->fetchAll();

                        foreach ($row as $r) {
                          echo "<option value='".$r['id']."' >".$r['sucursal']."</option>";
                        }*/
                      ?>
                    </select>
                    <br><br>
                    <label for="usr">Cálculo por sucursal:</label>
                    <div class="row">
                        <div class="col-lg-6">
                          <input type="radio" id="tipoCalculoSuc" name="tipoCalculoSuc" value="1" class="tipoCalculoSuc1 baseSucursal">
                          <label for="percepcion">Porcentaje</label>
                        </div>
                        <div class="col-lg-6">
                          <input type="radio" id="tipoCalculoSuc" name="tipoCalculoSuc" value="2" class="tipoCalculoSuc2 baseSucursal">
                          <label for="deduccion">Cantidad</label>
                        </div>
                    </div>
                    <br>
                    <label for="percepcion">Valor</label>
                    <input type="text" class="form-control txtValorSuc numericDecimal-only" name="txtValorSuc" id="txtValorSuc" value="" maxlength="14" required>

                    <br>
                    <div align="center"><button type="button" class="btn-custom btn-custom--blue" name="btnAgregarSucursal" id="btnAgregarSucursal"><span
                    class="ajusteProyecto">Agregar sucursal</span></button></div>
                </div>

                <div class="form-group" id="basesucursal2">
                  <label for="usr" style="margin-bottom: 5px;"><b>Sucursales:</b></label>
                  <div id="sucursales" class="col-lg-12">
                  </div>
                </div>

                <div class="modal-footer justify-content-center">
                  <button type="button" class="btn-custom btn-custom--border-blue btnCancelarActualizacion"
                  data-dismiss="modal" id="btnCancelarClave"><span class="ajusteProyecto">Cancelar</span></button>
                  <button type="button" class="btn-custom btn-custom--blue" name="btnAgregar" id="btnGuardarBase"><span
                    class="ajusteProyecto">Guardar</span></button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div> 
  -->
  <!--END ADD MODAL-->

  <!--ADD BASE BY BRANCH-->
    <div class="modal fade right" id="agregar_base_sucursal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
          <div class="modal-content">
            <form action="#" method="POST" id="agregarBaseSucursal">
              <div class="modal-header">
                <h4 class="modal-title w-100" id="myModalLabel">Base por sucursal</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">x</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label for="usr">Concepto:</label>
                  <select class="form-control" name="cmbConceptoSucursal" id="cmbConceptoSucursal" value="">
                  </select>
                </div>

                <div class="form-group" id="basesucursal">
                    <label for="usr">Sucursal:</label>
                    <select class="form-control" id="cmbSucursales" name="cmbSucursales">
                      <?php
                        $stmt = $conn->prepare('SELECT id, sucursal FROM sucursales WHERE estatus = 1 AND empresa_id = '.$_SESSION['IDEmpresa']);
                        $stmt->execute();
                        $row = $stmt->fetchAll();

                        foreach ($row as $r) {
                          echo "<option value='".$r['id']."' >".$r['sucursal']."</option>";
                        }
                      ?>
                    </select>
                    <br><br>
                    <label for="usr">Cálculo por sucursal:</label>
                    <div class="row">
                        <div class="col-lg-6">
                          <input type="radio" id="tipoCalculoSuc" name="tipoCalculoSuc" value="1" class="tipoCalculoSuc1 baseSucursal">
                          <label for="percepcion">Porcentaje</label>
                        </div>
                        <div class="col-lg-6">
                          <input type="radio" id="tipoCalculoSuc" name="tipoCalculoSuc" value="2" class="tipoCalculoSuc2 baseSucursal">
                          <label for="deduccion">Cantidad</label>
                        </div>
                    </div>
                    <br>
                    <label for="percepcion">Valor</label>
                    <input type="text" class="form-control txtValorSuc numericDecimal-only" name="txtValorSuc" id="txtValorSuc" value="" maxlength="14" required>

                    <br>
                    <div align="center"><button type="button" class="btn-custom btn-custom--blue" name="btnAgregarSucursal" id="btnAgregarSucursal"><span
                    class="ajusteProyecto">Agregar sucursal</span></button></div>
                </div>

                <div class="form-group" id="basesucursal2">
                  <label for="usr" style="margin-bottom: 5px;"><b>Sucursales:</b></label>
                  <div id="sucursales" class="col-lg-12">
                  </div>
                </div>

                <div class="modal-footer justify-content-center">
                  <button type="button" class="btn-custom btn-custom--border-blue btnCancelarActualizacion"
                  data-dismiss="modal" id="btnCancelarSucursal"><span class="ajusteProyecto">Cancelar</span></button>
                  <button type="button" class="btn-custom btn-custom--border-blue btnLimpiarSucursal" id="btnLimpiarSucursal"><span class="ajusteProyecto">Limpiar</span></button>
                  <button type="button" class="btn-custom btn-custom--blue" name="btnGuardarBaseSucursal" id="btnGuardarBaseSucursal"><span
                    class="ajusteProyecto">Guardar</span></button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div> 
  <!--END ADD MODAL-->

  <!--EDIT BASE BY BRANCH-->
    <div class="modal fade right" id="editar_base_sucursal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
          <div class="modal-content">
            <form action="#" method="POST" id="editarBaseSucursal">
              <div class="modal-header">
                <h4 class="modal-title w-100" id="myModalLabel">Editar base por sucursal</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">x</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label for="usr">Concepto:</label>
                  <input type="text" class="form-control" name="txtConceptoSucursalEdit" id="txtConceptoSucursalEdit" value="" readonly="">
                  <input type="hidden" id="idConceptoEdit" name="idConceptoEdit" value="">
                </div>

                <div class="form-group" id="basesucursalEdit">
                    <label for="usr">Sucursal:</label>
                    <select class="form-control" id="cmbSucursalesEdit" name="cmbSucursalesEdit">
                      <?php
                        $stmt = $conn->prepare('SELECT id, sucursal FROM sucursales WHERE estatus = 1 AND empresa_id = '.$_SESSION['IDEmpresa']);
                        $stmt->execute();
                        $row = $stmt->fetchAll();

                        foreach ($row as $r) {
                          echo "<option value='".$r['id']."' >".$r['sucursal']."</option>";
                        }
                      ?>
                    </select>
                    <br><br>
                    <label for="usr">Cálculo por sucursal:</label>
                    <div class="row">
                        <div class="col-lg-6">
                          <input type="radio" id="tipoCalculoSucEdit" name="tipoCalculoSucEdit" value="1" class="tipoCalculoSucEdit1 baseSucursalEdit">
                          <label for="percepcion">Porcentaje</label>
                        </div>
                        <div class="col-lg-6">
                          <input type="radio" id="tipoCalculoSucEdit" name="tipoCalculoSucEdit" value="2" class="tipoCalculoSucEdit2 baseSucursalEdit">
                          <label for="deduccion">Cantidad</label>
                        </div>
                    </div>
                    <br>
                    <label for="percepcion">Valor</label>
                    <input type="text" class="form-control txtValorSucEdit numericDecimal-only" name="txtValorSucEdit" id="txtValorSucEdit" value="" maxlength="14" required readonly>

                    <br>
                    <div align="center"><button type="button" class="btn-custom btn-custom--blue" name="btnEditarSucursal" id="btnEditarSucursal"><span
                    class="ajusteProyecto">Agregar sucursal</span></button></div>
                </div>

                <div class="form-group" id="basesucursal2">
                  <label for="usr" style="margin-bottom: 5px;"><b>Sucursales:</b></label>
                  <div id="sucursalesEdit" class="col-lg-12">
                    
                  </div>
                </div>

                <div class="modal-footer justify-content-center">
                  <button type="button" class="btn-custom btn-custom--border-blue btnCancelar"
                  data-dismiss="modal" id="btnCancelarSucursal"><span class="ajusteProyecto">Cerrar</span></button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div> 
  <!--END ADD MODAL-->

  <!--ADD BASE BY COMPANY-->
    <div class="modal fade right" id="agregar_base_tipo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
          <div class="modal-content">
            <form action="#" method="POST" id="agregarClaveTipo">
              <!--<input type="hidden" name="idProyectoA" value="">-->
              <div class="modal-header">
                <h4 class="modal-title w-100" id="myModalLabel">Base por empresa</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">x</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label for="usr">Concepto:</label>
                  <select class="form-control" name="cmbConceptoEmpresa" id="cmbConceptoEmpresa" value="">
                  </select>
                </div>

                <div class="form-group" id="basecalculo">
                  <label for="usr">Cálculo:</label>
                  <div class="row">
                      <div class="col-lg-6">
                        <input type="radio" id="tipoCalculo" name="tipoCalculo" value="1" class="tipoCalculo1 baseEmpresa">
                        <label for="percepcion">Porcentaje</label>
                      </div>
                      <div class="col-lg-6">
                        <input type="radio" id="tipoCalculo" name="tipoCalculo" value="2" class="tipoCalculo2 baseEmpresa">
                        <label for="deduccion">Cantidad</label>
                      </div>
                  </div>
                </div>

                <div class="form-group" id="basecalculo2">
                    <label for="percepcion">Valor</label>
                    <input type="text" class="form-control txtValor numericDecimal-only" name="txtValor" id="txtValor" value="" maxlength="14" required>
                </div>

                <div class="modal-footer justify-content-center">
                  <button type="button" class="btn-custom btn-custom--border-blue btnCancelarActualizacion"
                  data-dismiss="modal" id="btnCancelarBaseSucursal"><span class="ajusteProyecto">Cancelar</span></button>
                  <button type="button" class="btn-custom btn-custom--blue" name="btnAgregar" id="btnGuardarBaseEmpresa"><span
                    class="ajusteProyecto">Guardar</span></button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  <!--END ADD MODAL-->


  <!--EDIT BASE BY COMPANY-->
    <div class="modal fade right" id="editar_base_tipo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
          <div class="modal-content">
            <form action="#" method="POST" id="editarBaseTipo">
              <!--<input type="hidden" name="idProyectoA" value="">-->
              <div class="modal-header">
                <h4 class="modal-title w-100" id="myModalLabel">Base por empresa</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">x</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label for="usr">Concepto:</label>
                  <input type="text" class="form-control" name="txtConceptoEmpresaEdit" id="txtConceptoEmpresaEdit" value="" readonly>
                  <input type="hidden" name="idConceptoEditEmpresa" id="idConceptoEditEmpresa" value="">
                </div>

                <div class="form-group" id="basecalculo">
                  <label for="usr">Cálculo:</label>
                  <div class="row">
                      <div class="col-lg-6">
                        <input type="radio" id="tipoCalculoEdit" name="tipoCalculoEdit" value="1" class="tipoCalculoEdit1 baseEmpresaEdit">
                        <label for="percepcion">Porcentaje</label>
                      </div>
                      <div class="col-lg-6">
                        <input type="radio" id="tipoCalculoEdit" name="tipoCalculoEdit" value="2" class="tipoCalculoEdit2 baseEmpresaEdit">
                        <label for="deduccion">Cantidad</label>
                      </div>
                  </div>
                </div>

                <div class="form-group" id="basecalculo2">
                    <label for="percepcion">Valor</label>
                    <input type="text" class="form-control txtValor numericDecimal-only" name="txtValorEmpresaEdit" id="txtValorEmpresaEdit" value="" maxlength="14" required>
                </div>

                <div class="modal-footer justify-content-center">
                  <button type="button" class="btn-custom btn-custom--border-blue btnCancelarBaseEmpresaEdit"
                  data-dismiss="modal" id="btnCancelarBaseEmpresaEdit"><span class="ajusteProyecto">Cancelar</span></button>
                  <button type="button" class="btn-custom btn-custom--blue" name="btnEliminarBaseEmpresa" id="btnEliminarBaseEmpresa"><span
                    class="ajusteProyecto">Eliminar</span></button>
                  <button type="button" class="btn-custom btn-custom--blue" name="btnEditarBaseEmpresa" id="btnEditarBaseEmpresa"><span
                    class="ajusteProyecto">Guardar</span></button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  <!--END ADD MODAL-->



  <!--AGREGA Percepcion/Deduccion modal -->
    <div class="modal fade right" id="agregar_percepcion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
          <div class="modal-content">
            <form action="#" method="POST" id="agregarConceptoF">
              <!--<input type="hidden" name="idProyectoA" value="">-->
              <div class="modal-header">
                <h4 class="modal-title w-100" id="conceptosTitulo">Agregar concepto</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">x</span>
                </button>
              </div>
              <div class="modal-body">
                    <div class="form-group">
                      <label for="usr">Nuevo concepto*:</label>
                      <input type="text" class="form-control" name="txtConcepto" id="txtConcepto" value="" maxlength="100" required>
                    </div>
                    <div class="form-group">
                      <label for="usr">Clave SAT*:</label>
                          <select class="form-control" id="cmbClaveSAT">
                          </select>
                    </div>
                    <div class="form-group" id="claveMostrar" style="display: none;">
                      <label for="usr">Clave*:</label>
                      <input type="text" class="form-control" name="txtClave" id="txtClave" value="" maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" required>
                    </div>
            
                <div class="modal-footer justify-content-center">
                  <button type="button" class="btn-custom btn-custom--border-blue btnCancelarActualizacion"
                  data-dismiss="modal" id="CancelaragregarPercepcionDeduccion"><span class="ajusteProyecto">Cancelar</span></button>
                  <button type="button" class="btn-custom btn-custom--blue" name="agregarPercepcionDeduccion" id="agregarPercepcionDeduccion"><span
                    class="ajusteProyecto">Agregar</span></button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  <!--END EDIT MODAL-->

    <!--EDITAR Percepcion/Deduccion modal -->
    <div class="modal fade right" id="editar_percepcion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
          <div class="modal-content">
            <form action="#" method="POST" id="aeditarConceptoF">
              <!--<input type="hidden" name="idProyectoA" value="">-->
              <div class="modal-header">
                <h4 class="modal-title w-100" id="conceptosTituloEditar">Editar concepto</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">x</span>
                </button>
              </div>
              <div class="modal-body">
                    <div class="form-group">
                      <label for="usr">Concepto:</label>
                      <input type="text" class="form-control" name="txtConceptoEdit" id="txtConceptoEdit" value="" maxlength="100" required>
                      <input type="hidden" name="txtConceptoEditCopia" id="txtConceptoEditCopia" value="">
                      <input type="hidden" name="idConceptoClaveEdit" id="idConceptoClaveEdit" value="">
                    </div>
                    <div class="form-group">
                          <label for="usr">Clave SAT:</label>
                          <input type="text" class="form-control" id="txtClaveSAT" name="txtClaveSAT" readonly>
                    </div>
                    <div class="form-group">
                      <label for="usr">Clave:</label>
                      <input type="text" class="form-control" name="txtClaveEdit" id="txtClaveEdit" value="" maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" required>
                      <input type="hidden" name="txtClaveEditOriginal" id="txtClaveEditOriginal" value="" />
                    </div>
            
                <div class="modal-footer justify-content-center">
                  <button type="button" class="btn-custom btn-custom--border-blue btnCancelarConceptoClave"
                  data-dismiss="modal" id="btnCancelarConceptoClave"><span class="ajusteProyecto">Cancelar</span></button>
                  <button type="button" class="btn-custom btn-custom--blue" name="editarPercepcionClaveConcepto" id="editarPercepcionClaveConcepto"><span
                    class="ajusteProyecto">Editar</span></button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  <!--END EDIT MODAL-->

  <input type="hidden" name="csr_token_UT5JP" id="csr_token_UT5JP" value="<?= $token ?>">

  <!-- Custom scripts for all pages-->
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>
  <script src="./js/percepcionesbase.js"></script>

  <script>
    let claveguardada = '';
    let existe_base_empresa = 0;
    let esNecesarioValidar = 0;

    function cargarPercepcion(idPercepcion){
      let token = $("#csr_token_UT5JP").val(); 
      $.ajax({
        type: 'POST',
        url: 'functions/cargarPercepcion.php',
        data: { 
                idPercepcion : idPercepcion,
                csr_token_UT5JP : token
              },
        success: function(r) {
          var datos = JSON.parse(r);

          $("#baseempresa").css("display","block");
          $("#basesucursal").css("display","block");
          $("#basesucursal2").css("display","block");
          $("#basecalculo").css("display","none");
          $("#basecalculo2").css("display","none");
          existe_base_empresa = 0;

          $("#txtValor").attr("readonly", true);

          $("#txtConcepto").val(datos.concepto);
          $("#idconcepto").val(idPercepcion);
          $("#txtClave").val(datos.clave);
          claveguardada = datos.clave;

          $("#txtValorSuc").val("");
          $("#txtValorSuc").attr("readonly", true);
          $(".tipoCalculoSuc1").prop("checked", false);
          $(".tipoCalculoSuc2").prop("checked", false);

          if(datos.existe_global > 0){
            $(".tipoBase2").prop("checked", true);
            existe_base_empresa = 1;

            if(datos.tipo_base == 1){
              $(".tipoCalculo1").prop("checked", true);
            }
            if(datos.tipo_base == 2){
              $(".tipoCalculo2").prop("checked", true);
            }
            $("#txtValor").val(datos.cantidad_base);
            
            $("#basecalculo").css("display","block");
            $("#basecalculo2").css("display","block");
            esNecesarioValidar = 1;
            $("#txtValor").attr("readonly", false);
          } 
          else{
            $(".tipoBase1").prop("checked", true);
            $("#txtValor").val("");
            $(".tipoCalculo1").prop("checked", false);
            $(".tipoCalculo2").prop("checked", false);
          }

          $("#sucursales").html(datos.sucursales);

          if(idPercepcion == 1 || idPercepcion == 2 || idPercepcion == 3 || idPercepcion == 14 || idPercepcion == 16 || idPercepcion == 17 || idPercepcion == 18 || idPercepcion == 20){

            $("#baseempresa").css("display","none");
            $("#basesucursal").css("display","none");
            $("#basesucursal2").css("display","none");
            $("#basecalculo").css("display","none");
            $("#basecalculo2").css("display","none");

          }

        },
        error: function(){
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "¡Ocurrio un error, intentalo nuevamente!",
          });
        }
      });
   }

   $("#btnGuardarBaseEmpresa").click(function(){

        let baseEmpresa = "";
        let conceptoID = $("#cmbConceptoEmpresa").val();
        
        if(conceptoID == null || conceptoID < 1){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Selecciona el concepto."
          });
          return;
        }

        if( $("#agregarClaveTipo input[name='tipoCalculo']:radio").is(':checked')) {  
            baseEmpresa = $('input[name="tipoCalculo"]:checked').val();
        }
        else{
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Necesitas seleccionar la forma para calcular el impuesto agregado."
          });
          return;
        }

        if($("#txtValor").val().trim() == "" || $("#txtValor").val().trim() == "."){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Necesitas ingresar una cantidad."
          });
          return;
        }

        let token = $("#csr_token_UT5JP").val(); 
        let valor = "", valorObj;
        valorObj = numeral($("#txtValor").val());
        valor = valorObj.value();

        if(baseEmpresa == 1){
          if(valor > 1000){
            Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "No puede ingresar un porcentaje mayor de 1000."
          });
          return;
          }
        }

        $("#btnCancelarClave").prop("disabled", true);
        $("#btnGuardarBaseEmpresa").prop("disabled", true);

        $.ajax({
          type: 'POST',
          url: 'functions/agregarBaseUnica.php',
          data: { 
                  conceptoID : conceptoID,
                  csr_token_UT5JP : token,
                  baseEmpresa: baseEmpresa,
                  valorEmpresa: valor
                },
          success: function(r) {

            var datos = JSON.parse(r);

            if(datos.estatus_base_empresa == "exito"){
              Lobibox.notify("success", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/checkmark.svg",
                msg: "Se ha actualizado la base de la percepción.",
              });
              $("#btnCancelarClave").prop("disabled", false);
              $("#btnGuardarBaseEmpresa").prop("disabled", false);
              $("#txtValor").val("");
              $(".tipoCalculo1").prop("checked", false);
              $(".tipoCalculo2").prop("checked", false);
              $('#tblPercepcionesBase').DataTable().ajax.reload();
              $('#agregar_base_tipo').modal('hide');

            }

            if(datos.estatus_base_empresa == "existe"){
                Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "Ya se ha agregado ese concepto, modificalo directamente.",
              });
              $("#btnCancelarClave").prop("disabled", false);
              $("#btnGuardarBaseEmpresa").prop("disabled", false);
            }


            if(datos.estatus_base_empresa == "fallo"){
                Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "¡Ocurrio un error, intentalo nuevamente!",
              });
              $("#btnCancelarClave").prop("disabled", false);
              $("#btnGuardarBaseEmpresa").prop("disabled", false);
            }


            $("#btnCancelarClave").prop("disabled", false);
            $("#btnGuardarBaseEmpresa").prop("disabled", false);
          },
          error: function(){
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/notificacion_error.svg",
              msg: "¡Ocurrio un error, intentalo nuevamente!",
            });
            $("#btnCancelarClave").prop("disabled", false);
            $("#btnGuardarBaseEmpresa").prop("disabled", false);
          }
        });

     });

     //Editar las bases por empresa
     $("#btnEditarBaseEmpresa").click(function(){

        let baseEmpresa = "";

        if( $("#editarBaseTipo input[name='tipoCalculoEdit']:radio").is(':checked')) {  
            baseEmpresa = $('input[name="tipoCalculoEdit"]:checked').val();
        }
        else{
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Necesitas seleccionar la forma para calcular el impuesto agregado."
          });
          return;
        }

        if($("#txtValorEmpresaEdit").val().trim() == "" || $("#txtValorEmpresaEdit").val().trim() == "."){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Necesitas ingresar una cantidad."
          });
          return;
        }

        let conceptoID = $("#idConceptoEditEmpresa").val();
        let token = $("#csr_token_UT5JP").val(); 
        let valor = "", valorObj;
        valorObj = numeral($("#txtValorEmpresaEdit").val());
        valor = valorObj.value();

        if(baseEmpresa == 1){
          if(valor > 1000){
            Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "No puede ingresar un porcentaje mayor de 1000."
          });
          return;
          }
        }

        $("#btnCancelarBaseEmpresaEdit").prop("disabled", true);
        $("#btnEditarBaseEmpresa").prop("disabled", true);
        $("#btnEliminarBaseEmpresa").prop("disabled", true);

        $.ajax({
          type: 'POST',
          url: 'functions/editarBaseUnica.php',
          data: { 
                  conceptoID : conceptoID,
                  csr_token_UT5JP : token,
                  baseEmpresa: baseEmpresa,
                  valorEmpresa: valor
                },
          success: function(r) {

            var datos = JSON.parse(r);

            if(datos.estatus_base_empresa == "exito"){
              Lobibox.notify("success", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/checkmark.svg",
                msg: "Se ha actualizado la base de la percepción.",
              });
              $("#btnCancelarBaseEmpresaEdit").prop("disabled", false);
              $("#btnEditarBaseEmpresa").prop("disabled", false);
              $("#btnEliminarBaseEmpresa").prop("disabled", false);
              $('#tblPercepcionesBase').DataTable().ajax.reload();
              $('#editar_base_tipo').modal('hide');

            }


            if(datos.estatus_base_empresa == "fallo"){
                Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "¡Ocurrio un error, intentalo nuevamente!",
              });
              $("#btnCancelarBaseEmpresaEdit").prop("disabled", false);
              $("#btnEditarBaseEmpresa").prop("disabled", false);
              $("#btnEliminarBaseEmpresa").prop("disabled", false);
            }


            $("#btnCancelarBaseEmpresaEdit").prop("disabled", false);
            $("#btnEditarBaseEmpresa").prop("disabled", false);
            $("#btnEliminarBaseEmpresa").prop("disabled", false);
          },
          error: function(){
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/notificacion_error.svg",
              msg: "¡Ocurrio un error, intentalo nuevamente!",
            });
            $("#btnCancelarBaseEmpresaEdit").prop("disabled", false);
            $("#btnEditarBaseEmpresa").prop("disabled", false);
            $("#btnEliminarBaseEmpresa").prop("disabled", false);
          }
        });

     });


    //Eliminar las bases por empresa
     $("#btnEliminarBaseEmpresa").click(function(){

        let conceptoID = $("#idConceptoEditEmpresa").val();
        let token = $("#csr_token_UT5JP").val(); 

        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            actions: "d-flex justify-content-around",
            confirmButton: "btn-custom btn-custom--border-blue",
            cancelButton: "btn-custom btn-custom--blue",
          },
          buttonsStyling: false,
        });

      swalWithBootstrapButtons
        .fire({
          title: "¿Desea continuar?",
          text: "Se eliminará esta sucursal",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: '<span class="verticalCenter">Confirmar</span>',
          cancelButtonText: '<span class="verticalCenter">Cerrar</span>',
          reverseButtons: true,
          allowOutsideClick: false,
        })
        .then((result) => {
          if (result.isConfirmed) {

            $("#btnCancelarBaseEmpresaEdit").prop("disabled", true);
            $("#btnEditarBaseEmpresa").prop("disabled", true);
            $("#btnEliminarBaseEmpresa").prop("disabled", true);

            $.ajax({
              type: 'POST',
              url: 'functions/eliminarBaseUnica.php',
              data: { 
                      conceptoID : conceptoID,
                      csr_token_UT5JP : token
                    },
              success: function(r) {

                var datos = JSON.parse(r);

                if(datos.estatus_base_empresa == "exito"){
                  Lobibox.notify("success", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/checkmark.svg",
                    msg: "Se ha eliminado la base de la percepción.",
                  });
                  $("#btnCancelarBaseEmpresaEdit").prop("disabled", false);
                  $("#btnEditarBaseEmpresa").prop("disabled", false);
                  $("#btnEliminarBaseEmpresa").prop("disabled", false);
                  $('#tblPercepcionesBase').DataTable().ajax.reload();
                  $('#editar_base_tipo').modal('hide');

                }


                if(datos.estatus_base_empresa == "fallo"){
                    Lobibox.notify("error", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/notificacion_error.svg",
                    msg: "¡Ocurrio un error, intentalo nuevamente!",
                  });
                  $("#btnCancelarBaseEmpresaEdit").prop("disabled", false);
                  $("#btnEditarBaseEmpresa").prop("disabled", false);
                  $("#btnEliminarBaseEmpresa").prop("disabled", false);
                }


                $("#btnCancelarBaseEmpresaEdit").prop("disabled", false);
                $("#btnEditarBaseEmpresa").prop("disabled", false);
                $("#btnEliminarBaseEmpresa").prop("disabled", false);
              },
              error: function(){
                Lobibox.notify("error", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top",
                  icon: true,
                  img: "../../img/timdesk/notificacion_error.svg",
                  msg: "¡Ocurrio un error, intentalo nuevamente!",
                });
                $("#btnCancelarBaseEmpresaEdit").prop("disabled", false);
                $("#btnEditarBaseEmpresa").prop("disabled", false);
                $("#btnEliminarBaseEmpresa").prop("disabled", false);
              }
            });
            

          } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
          ) {

          }
        });   


        

     });

     
     $("#btnGuardarBaseSucursal").click(function(){

        let conceptoID = $("#cmbConceptoSucursal").val();
        let token = $("#csr_token_UT5JP").val(); 

        if(conceptoID == null || conceptoID < 1){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Selecciona el concepto."
          });
          return;
        }

        if(cuentaSucursales == 0){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Necesitas ingresar al menos una sucursal."
          });
          return;
        }

        if(cuentaSucursales == 0){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Necesitas ingresar al menos una sucursal."
          });
          return;
        }

        let cadenaSucursales = [];
        let sucursalIndividual = [];
        let x = 0, y = 0;
       $("#sucursales :input").each(function(){
          var input = $(this); // This is the jquery object of the input, do what you will
          //console.log(input);
          //console.log(input[0].value);
          
          sucursalIndividual[x] = input[0].value;
          x++;

          if(x == 3){
            //console.log("individual " + sucursalIndividual);
            //console.log("valor of " + y);
            cadenaSucursales[y] = sucursalIndividual; 
            sucursalIndividual = [];
            y = y + 1;
            x = 0;
          }
       });

        $("#btnCancelarSucursal").prop("disabled", true);
        $("#btnGuardarBaseSucursal").prop("disabled", true);
        $("#btnLimpiarSucursal").prop("disabled", true);

        $.ajax({
          type: 'POST',
          url: 'functions/agregarBaseUnicaSucursal.php',
          data: { 
                  conceptoID : conceptoID,
                  csr_token_UT5JP : token,
                  cadenaSucursales: cadenaSucursales
                },
          success: function(r) {

            var datos = JSON.parse(r);

            if(datos.estatus == "existe-registro"){
              Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "Ya se han ingresado sucursales con ese concepto, si quieres agregar más modificalos en su concepto correspondiente.",
              });
              $("#btnCancelarSucursal").prop("disabled", false);
              $("#btnGuardarBaseSucursal").prop("disabled", false);
              $("#btnLimpiarSucursal").prop("disabled", false);
            }

            if(datos.estatus == "exito"){
              Lobibox.notify("success", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/checkmark.svg",
                msg: "Sucursales agregadas a la percepción.",
              });
              $("#btnCancelarSucursal").prop("disabled", false);
              $("#btnGuardarBaseSucursal").prop("disabled", false);
              $("#btnLimpiarSucursal").prop("disabled", false);
              $('#tblPercepcionesBase').DataTable().ajax.reload();
              $('#agregar_base_sucursal').modal('hide');
            }

            if(datos.estatus_base_empresa == "exito"){
              Lobibox.notify("success", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/checkmark.svg",
                msg: "Se ha actualizado la base de la percepción.",
              });
              $("#btnCancelarClave").prop("disabled", false);
              $("#btnGuardarBase").prop("disabled", false);
              $('#tblPercepcionesBase').DataTable().ajax.reload();
              $('#agregar_clave_tipo').modal('hide');
              esNecesarioValidar = 0;
            }

            if(datos.estatus_clave == "sin_cambio" && datos.estatus_base_empresa == "sin_cambio"){
              Lobibox.notify("success", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/checkmark.svg",
                msg: "No hay cambios.",
              });
              $("#btnCancelarClave").prop("disabled", false);
              $("#btnGuardarBase").prop("disabled", false);
            }

            $("#btnCancelarClave").prop("disabled", false);
            $("#btnGuardarBase").prop("disabled", false);
          },
          error: function(){
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/notificacion_error.svg",
              msg: "¡Ocurrio un error, intentalo nuevamente!",
            });
            $("#btnCancelarClave").prop("disabled", false);
            $("#btnGuardarBase").prop("disabled", false);
          }
        });

     });
  
  
    let cuentaSucursales = 0;
    $("#btnAgregarSucursal").click(function(){

        let idsucursal = $("#cmbSucursales").val();
        let sucursal = $("#cmbSucursales option:selected").text();
        let token = $("#csr_token_UT5JP").val(); 
        let baseSucursal = 0;
        let valorObj, valor, tipoBase, cantidad;
        let idConcepto = $("#cmbConcepto").val();
        let conceptoID = $("#cmbConceptoSucursal").val();

        if(conceptoID == null || conceptoID < 1){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Selecciona el concepto."
          });
          return;
        }

        if( $("#agregarBaseSucursal input[name='tipoCalculoSuc']:radio").is(':checked')) {
            baseSucursal = $('input[name="tipoCalculoSuc"]:checked').val();
        }
        else{
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Necesitas seleccionar la forma para calcular el impuesto agregado de la sucursal."
          });
          return;
        }

        if($("#txtValorSuc").val().trim() == "" || $("#txtValorSuc").val().trim() == "."){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Necesitas ingresar una cantidad."
          });
          return;
        }
            
        valorObj = numeral($("#txtValorSuc").val());
        cantidad = $("#txtValorSuc").val().trim();
        valor = valorObj.value();

        if(baseSucursal == 1){

          if(valor > 1000){
            Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "No puede ingresar un porcentake mayor de 1000."
          });
          return;
          }
        }

        $("#btnAgregarSucursal").prop("disabled", true);

        if($("#sucursales").find( "#sucursal_" + idsucursal).length){
          Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "La sucursal ya fue agregada",
          });

          $("#btnAgregarSucursal").prop("disabled", false);
          return;
        }

        cuentaSucursales++;
        selectConceptosSucursal.disable();

        if(baseSucursal == 1){
          tipoBase = "%";
          cantidad = valor;
        }
        else{
          tipoBase = "$";
        }
        let nuevaSucursal = '<div class="row" id="sucursal_' + idsucursal + '">' +
                                '<div class="col-lg-9">' + sucursal + ' - ' + tipoBase + ' - ' + cantidad + '</div>' +
                                '<div class="col-lg-3 text-right"><i class="fas fa-trash-alt pointer" onclick="eliminarSucursal(' + idsucursal + ');"></i></div>' +
                                '<input type="hidden" id="idBaseSucursal" value="' + idsucursal + '" />' +
                                '<input type="hidden" id="cantidadIngresar" value="' + cantidad + '" />' +
                                '<input type="hidden" id="idTipoBase" value="' + baseSucursal + '" />' +
                            '</div>';
        $("#sucursales").append(nuevaSucursal);
        $("#cmbConcepto").prop("readonly",true);
        $("#btnAgregarSucursal").prop("disabled", false);
        $("#txtValorSuc").val("");
/*
        $.ajax({
          type: 'POST',
          url: 'functions/agregarSucursalBase.php',
          data: { 
                  idconcepto : idconcepto,
                  idsucursal : idsucursal,
                  sucursal : sucursal,
                  csr_token_UT5JP : token,
                  baseSucursal: baseSucursal,
                  valorSucursal: valor
                },
          success: function(r) {

            var datos = JSON.parse(r);

            if(datos.estatus == "existe"){
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "Ya existe esa sucursal.",
              });
              $("#btnAgregarSucursal").prop("disabled", true);
            }

            if(datos.estatus == "exito"){
              Lobibox.notify("success", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/checkmark.svg",
                msg: "Sucursal agregada",
              });

              if(baseSucursal == 1){
                tipoBase = "%";
                cantidad = valor;
              }
              else{
                tipoBase = "$";
              }
              let nuevaSucursal = '<div class="row" id="sucursal_' + datos.id + '">' +
                                      '<div class="col-lg-9">' + datos.concepto + ' - ' + tipoBase + ' - ' + cantidad + '</div>' +
                                      '<div class="col-lg-3 text-right"><i class="fas fa-trash-alt pointer" onclick="eliminarSucursal(' + datos.id + ');"></i></div>' +
                                  '</div>';
              $("#sucursales").append(nuevaSucursal);
              $("#btnAgregarSucursal").prop("disabled", true);
              $('#tblPercepcionesBase').DataTable().ajax.reload();
            }

            if(datos.estatus == "fallo"){
              Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "¡Ocurrio un error, intentalo nuevamente!",
              });
              $("#btnAgregarSucursal").prop("disabled", true);
            }

            $("#btnAgregarSucursal").prop("disabled", false);
          },
          error: function(){
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/notificacion_error.svg",
              msg: "¡Ocurrio un error, intentalo nuevamente!",
            });
            $("#btnAgregarSucursal").prop("disabled", false);
          }
        });*/

     });

     
    let cuentaSucursalesEdit = 0;
    $("#btnEditarSucursal").click(function(){

        let idsucursal = $("#cmbSucursalesEdit").val();
        let sucursal = $("#cmbSucursalesEdit option:selected").text();
        let token = $("#csr_token_UT5JP").val(); 
        let baseSucursal = 0;
        let valorObj, valor, tipoBase, cantidad;
        let idConcepto = $("#idConceptoEdit").val();

        if( $("#editarBaseSucursal input[name='tipoCalculoSucEdit']:radio").is(':checked')) {
            baseSucursal = $('input[name="tipoCalculoSucEdit"]:checked').val();
        }
        else{
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Necesitas seleccionar la forma para calcular el impuesto agregado de la sucursal."
          });
          return;
        }

        if($("#txtValorSucEdit").val().trim() == "" || $("#txtValorSucEdit").val().trim() == "."){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Necesitas ingresar una cantidad."
          });
          return;
        }
            
        valorObj = numeral($("#txtValorSucEdit").val());
        cantidad = $("#txtValorSucEdit").val().trim();
        valor = valorObj.value();

        if(baseSucursal == 1){

          if(valor > 1000){
            Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "No puede ingresar un porcentake mayor de 1000."
          });
          return;
          }
        }

        if($("#sucursales").find( "#sucursal_" + idsucursal).length){
          Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "La sucursal ya fue agregada",
          });

          return;
        }

        $("#btnEditarSucursal").prop("disabled", true);        

        $.ajax({
          type: 'POST',
          url: 'functions/agregarSucursalBase.php',
          data: { 
                  idconcepto : idConcepto,
                  idsucursal : idsucursal,
                  sucursal : sucursal,
                  csr_token_UT5JP : token,
                  baseSucursal: baseSucursal,
                  valorSucursal: valor
                },
          success: function(r) {

            var datos = JSON.parse(r);

            if(datos.estatus == "existe"){
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "Ya existe esa sucursal.",
              });
              $("#btnEditarSucursal").prop("disabled", false);
            }

            if(datos.estatus == "exito"){
              Lobibox.notify("success", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/checkmark.svg",
                msg: "Sucursal agregada",
              });

              cuentaSucursales++;

              if(baseSucursal == 1){
                tipoBase = "%";
                cantidad = valor;
              }
              else{
                tipoBase = "$";
              }
              let nuevaSucursal = '<div class="row" id="sucursal_' + idsucursal + '">' +
                                      '<div class="col-lg-9">' + sucursal + ' - ' + tipoBase + ' - ' + cantidad + '</div>' +
                                      '<div class="col-lg-3 text-right"><i class="fas fa-trash-alt pointer" onclick="eliminarSucursalEdit(' + datos.id + ',' + idsucursal + ');"></i></div>' +
                                      '<input type="hidden" id="idBaseSucursal" value="' + idsucursal + '" />' +
                                      '<input type="hidden" id="cantidadIngresar" value="' + cantidad + '" />' +
                                      '<input type="hidden" id="idTipoBase" value="' + baseSucursal + '" />' +
                                  '</div>';
              $("#sucursalesEdit").append(nuevaSucursal);
              $("#btnEditarSucursal").prop("disabled", false);

              $('#tblPercepcionesBase').DataTable().ajax.reload();
            }

            if(datos.estatus == "fallo"){
              Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "¡Ocurrio un error, intentalo nuevamente!",
              });
              $("#btnEditarSucursal").prop("disabled", true);
            }

            $("#btnEditarSucursal").prop("disabled", false);
          },
          error: function(){
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/notificacion_error.svg",
              msg: "¡Ocurrio un error, intentalo nuevamente!",
            });
            $("#btnEditarSucursal").prop("disabled", false);
          }
        });

     });


     function eliminarSucursal(idBaseSucursal){
        
       let token = $("#csr_token_UT5JP").val(); 
       
       const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          actions: "d-flex justify-content-around",
          confirmButton: "btn-custom btn-custom--border-blue",
          cancelButton: "btn-custom btn-custom--blue",
        },
        buttonsStyling: false,
      });

      swalWithBootstrapButtons
        .fire({
          title: "¿Desea continuar?",
          text: "Se eliminará esta sucursal",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: '<span class="verticalCenter">Confirmar</span>',
          cancelButtonText: '<span class="verticalCenter">Cerrar</span>',
          reverseButtons: true,
          allowOutsideClick: false,
        })
        .then((result) => {
          if (result.isConfirmed) {

            cuentaSucursales--;
            if(cuentaSucursales == 0){
              selectConceptosSucursal.enable();
            }
            
            $("#sucursal_" + idBaseSucursal).fadeOut(500, function() { $("#sucursal_" + idBaseSucursal).remove(); });
/*
            $.ajax({
              type: 'POST',
              url: 'functions/eliminarSucursalBase.php',
              data: { 
                      idBaseSucursal : idBaseSucursal,
                      csr_token_UT5JP : token
                    },
              success: function(r) {

                var datos = JSON.parse(r);

                if(datos.estatus  == "exito"){
                  Lobibox.notify("success", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/checkmark.svg",
                    msg: "Sucursal eliminada",
                  });
                  $("#sucursal_" + idBaseSucursal).fadeOut(500, function() { $("#sucursal_" + idBaseSucursal).remove(); });
                  //$('#tblPercepcionesBase').DataTable().ajax.reload();
                }

                if(datos.estatus == "fallo"){
                  Lobibox.notify("error", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/notificacion_error.svg",
                    msg: "¡Ocurrio un error, intentalo nuevamente!",
                  });
                }

              },
              error: function(){
                Lobibox.notify("error", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top",
                  icon: true,
                  img: "../../img/timdesk/notificacion_error.svg",
                  msg: "¡Ocurrio un error, intentalo nuevamente!",
                });
              }
            });*/



          } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
          ) {

            if ($('#cbboxExcluir').is(":checked")){
              $('#cbboxExcluir').prop('checked', false);
            }
            else{
              $('#cbboxExcluir').prop('checked', true);
            }

          }
        });      
     
     }

  function eliminarSucursalEdit(idBaseSucursal, idSucursal){
        
       let token = $("#csr_token_UT5JP").val(); 
       
       const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          actions: "d-flex justify-content-around",
          confirmButton: "btn-custom btn-custom--border-blue",
          cancelButton: "btn-custom btn-custom--blue",
        },
        buttonsStyling: false,
      });

      swalWithBootstrapButtons
        .fire({
          title: "¿Desea continuar?",
          text: "Se eliminará esta sucursal",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: '<span class="verticalCenter">Confirmar</span>',
          cancelButtonText: '<span class="verticalCenter">Cerrar</span>',
          reverseButtons: true,
          allowOutsideClick: false,
        })
        .then((result) => {
          if (result.isConfirmed) {


            $.ajax({
              type: 'POST',
              url: 'functions/eliminarSucursalBase.php',
              data: { 
                      idBaseSucursal : idBaseSucursal,
                      idSucursal: idSucursal,
                      csr_token_UT5JP : token
                    },
              success: function(r) {

                var datos = JSON.parse(r);

                if(datos.estatus  == "exito"){
                  Lobibox.notify("success", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/checkmark.svg",
                    msg: "Sucursal eliminada",
                  });

                    /*cuentaSucursalesEdit--;
                    if(cuentaSucursalesEdit == 0){
                      selectConceptosSucursal.enable();
                    }*/
                    
                    $("#sucursal_" + idSucursal).fadeOut(500, function() { $("#sucursal_" + idSucursal).remove(); });

                    $('#tblPercepcionesBase').DataTable().ajax.reload();
                }

                if(datos.estatus == "fallo"){
                  Lobibox.notify("error", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/notificacion_error.svg",
                    msg: "¡Ocurrio un error, intentalo nuevamente!",
                  });
                }

              },
              error: function(){
                Lobibox.notify("error", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top",
                  icon: true,
                  img: "../../img/timdesk/notificacion_error.svg",
                  msg: "¡Ocurrio un error, intentalo nuevamente!",
                });
              }
            });



          } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
          ) {

            if ($('#cbboxExcluir').is(":checked")){
              $('#cbboxExcluir').prop('checked', false);
            }
            else{
              $('#cbboxExcluir').prop('checked', true);
            }

          }
        });      
     
     }

  function agregarBaseSucursal(){
    
    let token = $("#csr_token_UT5JP").val();

    $.ajax({
          type: 'POST',
          url: 'functions/cargarDetalleConceptos.php',
          data: {
            tipo: 1,
            modo: 2,
            csr_token_UT5JP: token
          },
          success: function(data) {

            var datos = JSON.parse(data);

            if (datos.estatus == "exito") {

                $("#cmbConceptoSucursal").html(datos.select);

                selectConceptosSucursal.destroy();
                selectConceptosSucursal = new SlimSelect({
                  select: '#cmbConceptoSucursal',
                  deselectLabel: '<span class="">✖</span>'
                });

                $("#btnLimpiarSucursal").trigger( "click");
                selectConceptosSucursal.enable();
                $("#agregar_base_sucursal").modal("show");

            } 
            else {

              Lobibox.notify("error", {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: false,
                img: '../../img/timdesk/warning_circle.svg',
                msg: "Ocurrio un error, vuelva intentarlo."
              });

            }
          }
        });
  }

  function agregarBaseEmpresa(idConcepto){
    
    let token = $("#csr_token_UT5JP").val();

    $.ajax({
          type: 'POST',
          url: 'functions/cargarDetalleConceptos.php',
          data: {
            tipo: 1,
            modo: 1,
            csr_token_UT5JP: token
          },
          success: function(data) {

            var datos = JSON.parse(data);

            if (datos.estatus == "exito") {

                $("#cmbConceptoEmpresa").html(datos.select);

                selectConceptos.destroy();

                if(idConcepto > 0){
                  $("#cmbConceptoEmpresa").val(idConcepto);
                }

                selectConceptos = new SlimSelect({
                  select: '#cmbConceptoEmpresa',
                  deselectLabel: '<span class="">✖</span>'
                });

                $("#txtValor").prop("readonly", true);
                $("#agregar_base_tipo").modal("show");

            } 
            else {

              Lobibox.notify("error", {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: false,
                img: '../../img/timdesk/warning_circle.svg',
                msg: "Ocurrio un error, vuelva intentarlo."
              });

            }
          }
        });
  }


  function cargarBaseSucursal(idConcepto){
    
    let token = $("#csr_token_UT5JP").val();

    $.ajax({
          type: 'POST',
          url: 'functions/cargarDetalleSucursalEdit.php',
          data: {
            tipo: 1,
            idConcepto: idConcepto,
            csr_token_UT5JP: token
          },
          success: function(data) {

            var datos = JSON.parse(data);

            if (datos.estatus == "exito") {

                $("#txtConceptoSucursalEdit").val(datos.concepto);
                $("#idConceptoEdit").val(datos.conceptoID);

                $(".tipoCalculoSucEdit1").prop("checked", false);
                $(".tipoCalculoSucEdit2").prop("checked", false);

                selectConceptosSucursal.enable();

                $("#sucursalesEdit").html(datos.sucursales);
                $("#editar_base_sucursal").modal("show");

            } 
            else {

              Lobibox.notify("error", {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: false,
                img: '../../img/timdesk/warning_circle.svg',
                msg: "Ocurrio un error, vuelva intentarlo."
              });

            }
          }
        });
  }

  function cargarBaseEmpresa(idConcepto){

    let token = $("#csr_token_UT5JP").val();

    $.ajax({
          type: 'POST',
          url: 'functions/cargarDetalleEmpresaEdit.php',
          data: {
            tipo: 1,
            idConcepto: idConcepto,
            csr_token_UT5JP: token
          },
          success: function(data) {

            var datos = JSON.parse(data);

            if (datos.estatus == "exito") {

                if(datos.conceptoID == null || datos.conceptoID == ""){
                  agregarBaseEmpresa(idConcepto);                  
                }
                else{
                  
                  $("#txtConceptoEmpresaEdit").val(datos.concepto);
                  $("#idConceptoEditEmpresa").val(datos.conceptoID);

                  $("#txtValorEmpresaEdit").val(datos.cantidad);

                  if(datos.tipo_base == 1){
                    $(".tipoCalculoEdit1").prop("checked", true);
                  }
                  else{
                    $(".tipoCalculoEdit2").prop("checked", true);
                  }                

                  $("#editar_base_tipo").modal("show");
                }

            } 
            else {

              Lobibox.notify("error", {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: false,
                img: '../../img/timdesk/warning_circle.svg',
                msg: "Ocurrio un error, vuelva intentarlo."
              });

            }
          }
        });

  }

  function cargarDatosConcepto(idConcepto){

    let token = $("#csr_token_UT5JP").val();

    $.ajax({
          type: 'POST',
          url: 'functions/cargarDetalleConceptoEdit.php',
          data: {
            tipo: 1,
            idConcepto: idConcepto,
            csr_token_UT5JP: token
          },
          success: function(data) {

            var datos = JSON.parse(data);

            if (datos.estatus == "exito") {
                  
                $("#txtConceptoEdit").val(datos.concepto);
                $("#txtConceptoEditCopia").val(datos.concepto);
                $("#idConceptoClaveEdit").val(datos.conceptoID);
                $("#txtClaveSAT").val(datos.conceptoSAT);
                $("#txtClaveEdit").val(datos.clave);
                $("#txtClaveEditOriginal").val(datos.clave);

                $("#editar_percepcion").modal("show");

            } 
            else {

              Lobibox.notify("error", {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: false,
                img: '../../img/timdesk/warning_circle.svg',
                msg: "Ocurrio un error, vuelva intentarlo."
              });

            }
          }
        });

  }


  $("#editarPercepcionClaveConcepto").click(function(){
          
        let concepto = $("#txtConceptoEdit").val().trim();
        let concepto_guardado = $("#txtConceptoEditCopia").val().trim();
        let idconcepto = $("#idConceptoClaveEdit").val();
        let clave = $("#txtClaveEdit").val().trim();
        let token = $("#csr_token_UT5JP").val(); 
        let claveguardada = $("#txtClaveEditOriginal").val().trim();


        if(concepto.length < 1){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "El concepto no puede estar vacio."
            });
            return;
        }

        if(clave.length < 1){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "La clave no puede estar vacía."
            });
            return;
        }

        if( (clave.length > 0 && clave.length < 3) || clave.length > 15){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "No puedes agregar una clave de menos de 3 caracteres ni más de 15."
            });
            return;
        }

        $("#btnCancelarConceptoClave").prop("disabled", true);
        $("#editarPercepcionClaveConcepto").prop("disabled", true);

        $.ajax({
          type: 'POST',
          url: 'functions/editar_ClaveConcepto.php',
          data: { 
                  idconcepto : idconcepto,
                  clave: clave,
                  concepto: concepto,
                  concepto_guardado: concepto_guardado,
                  csr_token_UT5JP : token,
                  clave_guardada: claveguardada,
                  tipo: 1
                },
          success: function(r) {

            if(r == "existe-clave"){
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "Ya existe esa clave.",
              });
              $("#btnCancelarConceptoClave").prop("disabled", false);
              $("#editarPercepcionClaveConcepto").prop("disabled", false);
            }

            if(r == "existe-concepto"){
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "Ya tienes asigando ese concepto a otra percepción.",
              });
              $("#btnCancelarConceptoClave").prop("disabled", false);
              $("#editarPercepcionClaveConcepto").prop("disabled", false);
            }

            if(r == "exito"){
              Lobibox.notify("success", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/checkmark.svg",
                msg: "Clave y concepto modificado.",
              });
              $("#btnCancelarConceptoClave").prop("disabled", false);
              $("#editarPercepcionClaveConcepto").prop("disabled", false);
              $('#tblPercepcionesBase').DataTable().ajax.reload();
              $('#editar_percepcion').modal('hide');
            }

            $("#btnCancelarClave").prop("disabled", false);
            $("#btnGuardarBase").prop("disabled", false);
          },
          error: function(){
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/notificacion_error.svg",
              msg: "¡Ocurrio un error, intentalo nuevamente!",
            });
            $("#btnCancelarConceptoClave").prop("disabled", false);
            $("#editarPercepcionClaveConcepto").prop("disabled", false);
          }
        });

     });

  let selectClaveSAT = new SlimSelect({
      select: '#cmbClaveSAT',
      deselectLabel: '<span class="">✖</span>'
  });

  function agregarConcepto(){

    let token = $("#csr_token_UT5JP").val(); 

    selectClaveSAT.destroy();

    $.ajax({
      type: 'POST',
      url: 'functions/cargarClavesSATNomina.php',
      data: {
        csr_token_UT5JP: token,
        tipo: 1
      },
      success: function(data) {

         $("#cmbClaveSAT").html(data);

      } 
    });

    selectClaveSAT = new SlimSelect({
      select: '#cmbClaveSAT',
      deselectLabel: '<span class="">✖</span>'
    });

    $("#agregar_percepcion").modal('show');
  }


  let disponibleClave = 0;
  $("#cmbClaveSAT").change(function(){

      let val = $( "#cmbClaveSAT option:selected" ).text();
      let array = val.split("-");

      if(array.length == 3){
        disponibleClave = 0;
        $("#claveMostrar").css("display","none");
      }

      if(array.length == 2){
        disponibleClave = 1;
        $("#claveMostrar").css("display","block");
      }
      
  });

  
  $("#agregarPercepcionDeduccion").click(function(){

      if($("#txtConcepto").val().trim() == ''){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Agrega un concepto."
          });
          return;
      }

      if($("#txtConcepto").val().trim().length >= 100){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "No puedes agregar un concepto de más de 100 caracteres."
          });
          return;
      }

      if($("#cmbClaveSAT").val() == null){
          Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "Selecciona la clave SAT."
            });
          return;
      }

      let claveSAT = $("#cmbClaveSAT").val();
      let nuevoConcepto = $("#txtConcepto").val().trim();
      let token = $("#csr_token_UT5JP").val(); 
      let clave = "";

      

      //es necesario la clave
      if(disponibleClave == 1){
        clave = $("#txtClave").val().trim();

        if (clave == "") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Es necesario ingresar una clave.",
          });
          return;
        }

        if(clave.length > 15){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "No puedes agregar una clave de más de 15 caracteres."
            });
            return;
        }
      }

      $("#CancelaragregarPercepcionDeduccion").prop("disabled", true);
      $("#agregarPercepcionDeduccion").prop("disabled", true);


      $.ajax({
          type: 'POST',
          url: 'functions/agregarConceptoClave.php',
          data: {
            csr_token_UT5JP: token,
            disponibleClave: disponibleClave,
            clave: clave,
            nuevoConcepto: nuevoConcepto,
            claveSAT: claveSAT,
            tipo: 1
          },
          success: function(data) {

            if (data == "exito") {

              Lobibox.notify("success", {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: false,
                img: '../../img/timdesk/checkmark.svg',
                msg: "Se agregó el concepto a la nómina."
              });

              $("#txtConcepto").val("");
              $("#txtClave").val("");
              $("#claveMostrar").css("display","none");
              disponibleClave = 0;

              selectClaveSAT.set([]);

              
              $("#CancelaragregarPercepcionDeduccion").prop("disabled", false);
              $("#agregarPercepcionDeduccion").prop("disabled", false);

              $("#agregar_percepcion").modal('hide');
              $('#tblPercepcionesBase').DataTable().ajax.reload();

            } 
            if(data == "fallo") {

              Lobibox.notify("error", {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: false,
                img: '../../img/timdesk/warning_circle.svg',
                msg: "Ocurrio un error, vuelva intentarlo."
              });
              $("#CancelaragregarPercepcionDeduccion").prop("disabled", false);
              $("#agregarPercepcionDeduccion").prop("disabled", false);

            }

            if(data == "existe-concepto") {

              Lobibox.notify("warning", {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: false,
                img: '../../img/timdesk/warning_circle.svg',
                msg: "Ya tienes asignado ese concepto a otra percepción."
              });
              $("#CancelaragregarPercepcionDeduccion").prop("disabled", false);
              $("#agregarPercepcionDeduccion").prop("disabled", false);

            }

            if (data == "fallo-agregar") {
                Lobibox.notify("error", {
                  size: 'mini',
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: 'center top', //or 'center bottom'
                  icon: false,
                  img: '../../img/timdesk/warning_circle.svg',
                  msg: "No puedes agregar un concepto a una nómina timbrada."
                });
                $("#CancelaragregarPercepcionDeduccion").prop("disabled", false);
                $("#agregarPercepcionDeduccion").prop("disabled", false);

            }

            /*if (data == "existe-concepto") {
              Lobibox.notify("warning", {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: false,
                img: '../../img/timdesk/warning_circle.svg',
                msg: "El concepto ya esta agregado en la nómina."
              });
              $("#CancelaragregarPercepcionDeduccion").prop("disabled", false);
              $("#agregarPercepcionDeduccion").prop("disabled", false);
            }*/

            if(data == "existe-clave"){
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "Ya existe esa clave.",
              });
              $("#CancelaragregarPercepcionDeduccion").prop("disabled", false);
              $("#agregarPercepcionDeduccion").prop("disabled", false);
            }

            if(data == "existe-concepto-clave"){
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "Ese concepto ya tiene asignada una clave.",
              });
              $("#CancelaragregarPercepcionDeduccion").prop("disabled", false);
              $("#agregarPercepcionDeduccion").prop("disabled", false);
            }
          }
      });
      

  });

  $("#btnLimpiarSucursal").click(function(){

      selectSucursales.destroy();
      $("#cmbSucursales").val(1);
      selectSucursales = new SlimSelect({
        select: '#cmbSucursales',
        deselectLabel: '<span class="">✖</span>'
      });

      $("#txtValorSuc").val("");
      $("#txtValorSuc").prop("readonly",true);

      $(".tipoCalculoSuc1").prop("checked", false);
      $(".tipoCalculoSuc2").prop("checked", false);

      selectConceptosSucursal.enable();

      $("#sucursales").html("");

  });

  let selectConceptos = new SlimSelect({
    select: '#cmbConceptoEmpresa',
    deselectLabel: '<span class="">✖</span>'
  });

  let selectConceptosSucursal = new SlimSelect({
    select: '#cmbConceptoSucursal',
    deselectLabel: '<span class="">✖</span>'
  });

  var selectSucursales = new SlimSelect({
    select: '#cmbSucursales',
    deselectLabel: '<span class="">✖</span>'
  });

  var selectSucursalesEdit = new SlimSelect({
    select: '#cmbSucursalesEdit',
    deselectLabel: '<span class="">✖</span>'
  });

     new Cleave('.txtValor', {
      numeral: true,
      numeralDecimalMark: '.',
      delimiter: ','
    });

    new Cleave('.txtValorSuc', {
      numeral: true,
      numeralDecimalMark: '.',
      delimiter: ','
    });

    new Cleave('.txtValorSucEdit', {
      numeral: true,
      numeralDecimalMark: '.',
      delimiter: ','
    });

    new Cleave('#txtValorEmpresaEdit', {
      numeral: true,
      numeralDecimalMark: '.',
      delimiter: ','
    });


    $(document).on( "keypress", ".allownumericwithoutdecimal", function(){
      if ((event.which < 48 || event.which > 57)) {
            event.preventDefault();
      }

    });


    $(document).on( "change", ".baseEmpresa", function(){

      let baseEmpresa = $('input[name="tipoCalculo"]:checked').val();
        
      if(baseEmpresa == 1){
        $("#txtValor").addClass("allownumericwithoutdecimal");
        $("#txtValor").attr("readonly", false); 
      }
      if(baseEmpresa == 2){
        $("#txtValor").removeClass("allownumericwithoutdecimal");
        $("#txtValor").attr("readonly", false); 
      }
      if(baseEmpresa == 3){
        $("#txtValor").removeClass("allownumericwithoutdecimal");
        $("#txtValor").attr("readonly", true); 
      }

      $("#txtValor").val("");


    });


    $(document).on( "change", ".baseEmpresaEdit", function(){

      let baseEmpresa = $('input[name="tipoCalculoEdit"]:checked').val();
        
      if(baseEmpresa == 1){
        $("#txtValorEmpresaEdit").addClass("allownumericwithoutdecimal");
        $("#txtValorEmpresaEdit").attr("readonly", false); 
      }
      if(baseEmpresa == 2){
        $("#txtValorEmpresaEdit").removeClass("allownumericwithoutdecimal");
        $("#txtValorEmpresaEdit").attr("readonly", false); 
      }
      if(baseEmpresa == 3){
        $("#txtValorEmpresaEdit").removeClass("allownumericwithoutdecimal");
        $("#txtValorEmpresaEdit").attr("readonly", true); 
      }

      $("#txtValorEmpresaEdit").val("");


    });

    $(document).on( "change", ".baseporempresa", function(){

      let baseporempresa = $('input[name="tipoBase"]:checked').val();
        
      if(baseporempresa == 1){
        $("#basecalculo").css("display","none");
        $("#basecalculo2").css("display","none");
        esNecesarioValidar = 0;
      }
      if(baseporempresa == 2){
        $("#basecalculo").css("display","block");
        $("#basecalculo2").css("display","block");
        esNecesarioValidar = 1;
      }

    });

    $(document).on( "change", ".baseSucursal", function(){

      let baseSucursal = $('input[name="tipoCalculoSuc"]:checked').val();
        
      if(baseSucursal == 1){
        $("#txtValorSuc").addClass("allownumericwithoutdecimal");
        $("#txtValorSuc").attr("readonly", false); 
      }
      if(baseSucursal == 2){
        $("#txtValorSuc").removeClass("allownumericwithoutdecimal");
        $("#txtValorSuc").attr("readonly", false); 
      }

      $("#txtValorSuc").val("");


    });


    $(document).on( "change", ".baseSucursalEdit", function(){

      let baseSucursal = $('input[name="tipoCalculoSucEdit"]:checked').val();
        
      if(baseSucursal == 1){
        $("#txtValorSucEdit").addClass("allownumericwithoutdecimal");
        $("#txtValorSucEdit").attr("readonly", false); 
      }
      if(baseSucursal == 2){
        $("#txtValorSucEdit").removeClass("allownumericwithoutdecimal");
        $("#txtValorSucEdit").attr("readonly", false); 
      }

      $("#txtValorSucEdit").val("");


    });
  
  </script>
  <script>
    var ruta = "../";
  </script>
  <script>
    loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
    setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
  </script>
</body>

</html>
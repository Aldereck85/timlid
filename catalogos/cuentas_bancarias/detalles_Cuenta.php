<?php
session_start();
//var_dump($_POST);
require_once '../../include/db-conn.php';
$user = $_SESSION["Usuario"];
$idemp = $_SESSION["IDEmpresa"];
$fecha = date('Y-m-d');
if (!isset($_POST['idDetalle']) && !isset($_POST['tipoIdCuentaU'])) {
  header("location:index.php");
} else {
  $idDet = $_POST['idDetalle'];
  $tipo = ($_POST['tipoIdCuentaU']);
  //echo $idDet;
}

$stmt = $conn->prepare("SELECT id FROM tipo_empleado WHERE tipo = ?");
$stmt->execute(["Responsable gastos"]);
$idTipoResponsable = $stmt->fetch(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | Detalle cuentas</title>

  <!-- ESTILOS -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../css/sweetalert2.css" rel="stylesheet">
  <!-- JS -->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../js/lobibox.min.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>
  <script src="../../js/sweet/sweetalert2.js"></script>
  <script src="../../js/slimselect.min.js"></script>

  <script>
    var getId = "<?= $idDet; ?>";
    $.ajax({
      type: 'POST',
      url: 'functions/get_Ids.php',
      data: {
        'idDetalle': getId
      },
      success: function(r) {
        var datos = JSON.parse(r);
        $("#pkCuenta").val(datos.pkcuenta);
      }
    });
  </script>
  <script>
    var idTipo = "<?= $tipo; ?>";
    var idCuenta = "<?= $idDet; ?>";
    switch(idTipo){
      case 'Cheques(Bancaria)':
        idTipo = 1;
      break;
      case 'Crédito':
        idTipo = 2;
      break;
      case 'Otros':
        idTipo = 3;
      break;
      default:
        idTipo = 4;
      break;      
    }

    $(document).ready(function() {
      tablaInicialCaja(idCuenta);
    });
  </script>

</head>

<body id="page-top" class="sidebar-toggled">
  <!-- Page Wrapper -->
  <div id="wrapper">
    <!-- Sidebar -->
    <?php
    //$icono '../../img/toolbar/usuarios.svg';
    $titulo = 'Detalle de cuenta';

    $ruta = "../";
    $ruteEdit = $ruta . "central_notificaciones/";
    require_once '../menu3.php';
    ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">

        <?php
        $rutatb = "../";
        $backIcon = true;
        $icono = '../../img/icons/ICONO_CUENTA_BANCARIA.svg';
        require_once '../topbar.php';
        ?>
        <!-- Begin Page Content -->

        <div class="container-fluid">

          <div class="card">
            <div class="card-body">
              <div class="table-responsive">
                <input type="hidden" name="tipoCuentaGs" id="tipoCuentaGa" value="">
                <input type="hidden" name="tipoCuentaGd" id="tipoCuentaGd" value="">
                <table class="table" id="tblDatosGenerales" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th tcolspan="2">Datos generales</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <div style="text-align: left;"> Saldo: <input type="text" name="saldoG" id="saldoG" value="" style="border: none;"> </div>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <div style="text-align: left;"> Tipo de cuenta: <input type="text" name="tipoCuentaG" id="tipoCuentaG" value="" style="border: none;"> </div>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <div style="text-align: left;"> Nombre de la cuenta: <input type="text" name="nomCuentaG" id="nomCuentaG" value="" style="border: none;"> </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <input type="hidden" id="pkCuenta" name="pkCuenta" value="<?php echo $idDet; ?>">
              <div class="row">
                <div class="col-9">
                  <span class="btn-table-custom btn-table-custom--green" data-toggle="modal" data-target="#" onclick="ir_Ventana(<?php echo $idDet; ?>)"><i class="fas fa-plus-circle"></i> Agregar Dinero</span>
                  <span class="btn-table-custom btn-table-custom--red" data-toggle="modal" data-target="#retiro_Gasto" onclick="rDinero(<?php echo $idDet; ?>)"><i class="fas fa-minus-circle"></i> Retirar Dinero</span>
                  <span class="btn-table-custom btn-table-custom--blue-light" data-toggle="modal" data-target="#transferencia_Modal" onclick="abrirModalTransfer(<?php echo $idDet; ?>)"><i class="fas fa-exchange-alt"></i> Transferir</span>
                  <span class="btn-table-custom btn-table-custom--yellow" data-toggle="modal" data-target="ajuste_Modal" onclick="modalAjuste(<?php echo $idDet; ?>)"><i class="fas fa-sliders-h"></i> Ajuste</span>
                </div>
                <div class="col-3">
                  <button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnEditarCuenta" data-toggle="modal" data-target="#editar_Cuenta" onclick="obtenerIdCuentaEditar(idCuenta, idTipo)"><span class="ajusteProyecto">Modificar</span></button>
                  <a class="btnesp first espEliminar float-right mr-3" href="#" onclick="eliminarCuenta();" name="idCuentaU" id="idCuentaU" data-toggle="modal" data-target=""><span class="ajusteProyecto">Eliminar cuenta</span></a>
                </div>
              </div>
            </div>
          </div>
          <div class="card mt-2">
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <div class="table-responsive">
                    <table class="table" id="tblDetalles" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th colspan="10" style="color: var(--color-primario); background-color: var(--color-claro);">Últimos Movimientos</th>
                        </tr>
                        <tr>
                          <th>Id</th>
                          <th>Fecha</th>
                          <th>Descripción</th>
                          <th>Retiro/cargo</th>
                          <th>Deposito/Abono</th>
                          <th></th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div> <!-- End col md 8-->
              </div> <!-- end row -->
            </div>
            <!-- End of card body -->
          </div>
        </div>
        <!-- End of container-fluid -->
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

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>
  
  <!--ADD MODAL-->
  <div class="modal fade right" id="agregar_Dinero" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <!--<div class="modal-dialog modal-full-height modal-right  modal-md" role="document"> -->
    <div class="modal-dialog  modal-right  modal-md" role="document">
      <div class="modal-content">
        <form name="formular" action="" id="agregarDinero" method="POST">
          <input type="hidden" name="idCuentaActuala" id="idCuentaActuala">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Origen del ingreso</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <select name="cmbOrigenDinero" class="form-control" id="cmbOrigenDinero" onchange="agrBtn(<?php echo $idDet; ?>)">
                <option value="0">Seleccione una opción...</option>
                <!--<option value="1">Venta </option> -->
                <option value="2">Capital</option>
              </select>
            </div>
            <div id="venta">
              <div class="form-group">
                <label for="usr">El origen corresponde a una nueva venta</label>
              </div>
            </div>
            <div id="capital">
              <div class="form-group">
                <label for="usr">Es dinero que estas invirtiendo a tu empresa</label>
              </div>
            </div>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btnesp first espCancelar btnCancelarOrigen" data-dismiss="modal" id="btnCancelarOrigen" name="btnCancelarOrigen"><span class="ajusteProyecto">Cancelar</span></button>
              <button type="button" class="btnesp first espCancelar " data-dismiss="modal" id="btnAgregarDin"><span class="ajusteProyecto" onclick="ir_Ventana(<?php echo $idDet; ?>)">Siguiente</span></button>
            </div>
          </div> <!-- end div Class MODAL BODY-->
        </form>
      </div>
    </div>
  </div>

  <!-- MODAL INYECCION DE CAPITAL-->
  <div class="modal fade right" id="inyeccion_Capital" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="overflow-y: scroll;">
    <div class="modal-dialog modal-full-height modal-right  modal-md" role="document">
      <div class="modal-content">
        <form name="formular" action="" id="inyeccionCapital" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="idCuentaIny" id="idCuentaIny">
          <input type="hidden" name="idCuentaOr" id="idCuentaOr">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Inyección de capital</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <input type="hidden" name="idMonedaO" id="idMonedaO">
          <input type="hidden" name="saldoIny" id="saldoIny">
          <div class="modal-body">
            <div class="form-group">
              <label for="usr">Monto:*</label>
              <input class="form-control numericDecimal-only" placeholder="Ej: 00.00" type="numeric" id="montoInyeccionCapital" required onkeyup="validEmptyInput(this)">
              <div class="invalid-feedback" id="invalid-montoCjChica">La transacción debe tener un monto.</div>
            </div>
            <div class="form-group">
              <label for="usr">Fecha del movimiento:*</label>
              <input class="form-control" style="border:none;" value="<?= $fecha; ?>" type="date" min="2021-01-01" id="fechaInyeccionCapital" max="2030-01-01" required onchange="validEmptyInput(this)">
              <div class="invalid-feedback" id="invalid-fechaCjChica">La transacción debe tener una fecha.</div>
            </div>

            <div class="form-group">
              <label for="usr">Observaciones:*</label>
              <textarea class="form-control" type="text" id="areaObservacion" required onkeyup="validEmptyInput(this)"></textarea>
              <div class="invalid-feedback" id="invalid-obsCjChica">La transacción debe tener una descripcción.</div>
            </div>

            <div class="form-group">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="checkCajaInyeccion" name="checkCajaInyeccion" enabled checked>
                <label class="form-check-label" for="checkCajaInyeccion">
                  Por comprobar
                </label>
                <input id="inputFileInyeccion" name="inputFileInyeccion" type="file" accept="image/*, .pdf, .xlsx, .xml " class="file" data-browse-on-zone-click="true" class="form-control" onchange="validar_documentoInyeccion()" style="display: none;">
                <div class="invalid-feedback" id="invalid-archivoCjChica">La transacción debe tener un archivo de
                  comprobación.</div>
              </div>
            </div>
            <label for="">* Campos requeridos</label>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacionInyeccion" data-dismiss="modal" id="btnCancelarActualizacionInyeccion"><span class="ajusteProyecto">Cancelar</span></button>
              <button type="button" class="btnesp first espAgregar " id="btnAgregarInyeccionCapitalOtros" onclick="agregar_TransferenciaC()"><span class="ajusteProyecto" onclick="" disabled>Guardar</span></button>
            </div>
          </div><!-- end div Class MODAL BODY-->
        </form>
      </div>
    </div>
  </div>


  <!-- MODAL GASTO DE CUENTA CHICA-->
  <div class="modal fade right" id="retiro_Gasto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="overflow-y: scroll;">
    <div class="modal-dialog modal-full-height modal-right  modal-md" role="document">
      <div class="modal-content">
        <form method="POST" name="retiroGasto" action="" id="retiroGasto">
          <input type="hidden" id="emp_id" name="emp_id" value="<?php echo $idemp; ?>">
          <input type="hidden" name="idCuentaCaja" id="idCuentaCaja">
          <input type="hidden" name="saldoCuentaCaja" id="saldoCuentaCaja">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Retiro de gasto</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="usr">Responsable:*</label>
              <select class="form-control" name="cmbResponsableGasto" id="cmbResponsableGasto" required onchange="validEmptyInput(this)">
                <option disabled selected>Selecciona un responsable</option>
                <?php
                $stmt = $conn->prepare('SELECT emp.PKEmpleado, emp.Nombres, emp.PrimerApellido, emp.SegundoApellido
                                        from empleados emp
                                        INNER JOIN relacion_tipo_empleado rte
                                        on emp.PKEmpleado = rte.empleado_id
                                        WHERE emp.empresa_id = :empresa AND rte.tipo_empleado_id = :tipoEmpleado');
                $stmt->bindValue(':empresa', $idemp);
                $stmt->bindValue(':tipoEmpleado', $idTipoResponsable);
                $stmt->execute();
                while ($row = $stmt->fetch()) {
                ?>
                  <option value="<?= $row['PKEmpleado']; ?>"><?= $row['Nombres'] ?> <?= $row['PrimerApellido']; ?>
                    <?= $row['SegundoApellido']; ?></option>
                <?php
                } ?>
              </select>
              <div class="invalid-feedback" id="invalid-responsableRet">El retiro debe tener un responsable.
              </div>
            </div>
            <div class="form-group">
              <label for="usr">Importe del gasto: *</label>
              <input class="form-control numericDecimal-only" placeholder="Ej: 00.00" type="numeric" required id="txtImporteGasto" name="txtImporteGasto" onkeyup="validEmptyInput(this)">
              <div class="invalid-feedback" id="invalid-importeRet">El retiro debe tener una cantidad.
              </div>
            </div>
            <div class="form-group">
              <label for="usr">Fecha del movimiento: *</label>
              <input class="form-control" style="border:none;" value="<?php echo $fecha; ?>" type="date" min="2021-01-01" max="2030-01-01" id="txtFechaGasto" name="txtFechaGasto" required onchange="validEmptyInput(this)">
              <div class="invalid-feedback" id="invalid-fechaRet">El retiro debe tener una fecha.
              </div>
            </div>
            <div class="form-group">
              <label for="usr" class="d-flex justify-content-between"><span>Proveedor:*</span><a href="" data-toggle="modal" data-target="#nuevo_Provedor" type="text" style="text-align: right">Nuevo
                  proveedor</a></label>
              <select name="cmbProvedoresGasto" class="form-control" id="cmbProvedoresGasto" required onchange="validEmptyInput(this)">
                <option value="" disabled selected hidden>Seleccionar un proveedor</option>
                <?php
                $stmt = $conn->prepare("SELECT * FROM proveedores WHERE empresa_id = :empresa");
                $stmt->bindValue(':empresa', $idemp);
                $stmt->execute();
                $row = $stmt->fetchAll();
                if (count($row) > 0) {
                  foreach ($row as $r) { //Mostrar usuarios
                    echo '<option value="' . $r['PKProveedor'] . '">' . $r['NombreComercial'] . '</option>';
                  }
                } else {
                  echo '<option value="" disabled>No hay registros para mostrar.</option>';
                }
                ?>
              </select>
              <div class="invalid-feedback" id="invalid-provRet">El retiro debe tener un proveedor.
              </div>
            </div>
            <div class="form-group">
              <label for="usr">Observaciones:*</label>
              <textarea class="form-control" name="areaDescripcionGasto" id="areaDescripcionGasto" required onkeyup="validEmptyInput(this)"></textarea>
              <div class="invalid-feedback" id="invalid-observRet">El retiro debe tener obvservaciones.
              </div>
            </div>
            <div class="form-group">
              <label for="usr" class="d-flex justify-content-between"><span>Categoría:</span><a href="" data-toggle="modal" data-target="#nueva_categoria" type="text" style="text-align: right">Nueva
                  categoria</a></label>
              <select name="cmbCategoria" class="form-control" id="cmbCategoria" required onchange="categoria()">
              </select>
            </div>
            <div class="form-group">
              <label for="usr" class="d-flex justify-content-between"><span>Subcategoría:</span> <a href="" data-toggle="modal" data-target="#nueva_subCategoria" type="text" style="text-align: right" onclick="cargarCMBCategoriasG('','cmCategoria')">Nueva
                  subcategoria</a></label>
              <select class="form-control" name="cmbSubcategoria" id="cmbSubcategoria">
              </select>
            </div>

            <div class="form-group">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="checkCaja" name="checkCaja" enabled checked>
                <label class="form-check-label" for="checkCaja">
                  Por comprobar
                </label>
                <input id="inputFile" name="inputFile" type="file" accept="image/*, .pdf, .xlsx, .xml " class="file" data-browse-on-zone-click="true" class="form-control" onchange="validar_documento()" style="display: none;">
                <div class="invalid-feedback" id="invalid-archivoRet">El retiro debe tener un documento de comprobación.
                </div>
              </div>
            </div>
            <label for="">* Campos requeridos</label>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacionCajaChica" data-dismiss="modal" id="btnCancelarActualizacionCajaChica"><span class="ajusteProyecto">Cancelar</span></button>
              <button type="button" class="btnesp first espAgregar" id="btnGuardarMovimiento"><span class="ajusteProyecto" disabled>Guardar</span></button>
            </div>
          </div> <!-- end div Class MODAL BODY-->
        </form>
      </div>
    </div>
  </div>

  <!-- ADD MODAL CATEGORIA -->
  <div class="modal fade right" id="nueva_categoria" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="overflow-y: scroll;">
    <div class="modal-dialog modal-full-height modal-right  modal-md" role="document">
      <div class="modal-content">
        <form name="formular" action="" id="agregar_categoria" method="POST">

          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Nueva categoria</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="usr">Nombre categoria:*</label>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col">
                  <input class="form-control" type="text" placeholder="Categoria" name="txtCategoria" id="txtCategoria" onkeyup="validEmptyInput(this)">
                  <div class="invalid-feedback" id="invalid-categoria">La categoria debe tener un nombre.
                  </div>
                </div>
              </div>
            </div>
            <label for="">* Campos requeridos</label>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal" id="cancelarCategoria"><span class="ajusteProyecto">Cancelar</span></button>
              <button type="button" class="btnesp first espAgregar " id="btnGuardarCategoria" onclick="guardarCategoria()"><span class="ajusteProyecto">Guardar</span></button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- ADD MODAL SUBCATEGORIA -->
  <div class="modal fade right" id="nueva_subCategoria" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="overflow-y: scroll;">
    <div class="modal-dialog modal-full-height modal-right  modal-md" role="document">
      <div class="modal-content">
        <form name="formular" action="" id="agregar_categoria" method="POST">

          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Nueva subcategoria</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="usr">Categoria:*</label>
              <select name="cmCategoria" class="cmbSelect form-control" id="cmCategoria" onchange="validEmptyInput(this)">
                <option disabled selected hidden>Seleccionar categoria</option>
                <?php
                $stmt = $conn->prepare("SELECT PKCategoria, Nombre FROM categoria_gastos WHERE empresa_id = :idempresa");
                $stmt->bindValue(':idempresa', $_SESSION["IDEmpresa"]);
                $stmt->execute();
                $row = $stmt->fetchAll();
                if (count($row) > 0) {
                  foreach ($row as $r) { //Mostrar usuarios
                    echo '<option value="' . $r['PKCategoria'] . '">' . $r['Nombre'] . '</option>';
                  }
                } else {
                  echo '<option value="" disabled>No hay registros para mostrar.</option>';
                }
                ?>
              </select>
              <div class="invalid-feedback" id="invalid-categoriaSub">La subcategoria debe tener una categoria.
              </div>
            </div>
            <div class="form-group">
              <label for="usr">Nombre subcategoria:*</label>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col">
                  <input class="form-control" type="text" placeholder="Categoria" name="txtSubCategoria" id="txtSubCategoria" onkeyup="validEmptyInput(this)">
                  <div class="invalid-feedback" id="invalid-subcategoria">La subcategoria debe tener un nombre.
                  </div>
                </div>
              </div>
            </div>
            <label for="">* Campos requeridos</label>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal" id="cancelarCategoria"><span class="ajusteProyecto">Cancelar</span></button>
              <button type="button" class="btnesp first espAgregar " id="btnGuardarSubCategoria" onclick="guardarSubCategoria()"><span class="ajusteProyecto">Guardar</span></button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- ADD MODAL TRANSFERIR -->
  <div class="modal fade right" id="transferencia_Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="overflow-y: scroll;">
    <div class="modal-dialog modal-full-height modal-right  modal-md" role="document">
      <div class="modal-content">
        <form name="formular" action="" id="transferencia" method="POST">
          <input type="hidden" name="idCuentaActualTransfer" id="idCuentaActualTransfer">

          <input type="hidden" id="actual" name="actual" value="">
          <input type="hidden" id="nomCuentaT" name="nomCuentaT" value="">

          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Transferencia</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <input type="hidden" name="idCuentaDestinoTransfer" id="idCuentaDestinoTransfer">
          <input type="hidden" id="saldoI" name="saldoI" value="">
          <input type="hidden" id="saldoDes" name="saldoDes" value="">
          <input type="hidden" id="destino" name="destino" value="">
          <div class="modal-body">
            <div class="form-group">
              <label for="usr">Cuenta Destino: *</label>
              <select class="form-control" name="cmbCuentaDestinoCj" id="cmbCuentaDestinoCj" onchange="getMonedaD(<?= $idDet; ?>)"></select>
              <div class="invalid-feedback" id="invalid-cuentaTrans">La transferencia debe tener una cuenta destino.
              </div>
            </div>
            <div class="form-group">
              <label for="usr">Cantidad a enviar:*</label>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col">
                  <input class="form-control numericDecimal-only" type="numeric" placeholder="Ej: 00.00" name="txtCantidad1" id="txtCantidad1" onkeyup="validEmptyInput(this)">
                  <div class="invalid-feedback" id="invalid-cantidadTrans">La tranferencia debe tener una cantidad.
                  </div>
                </div>
                <div class="col">
                  <input class="form-control" type="text" id="cmbMonedaAc" name="cmbMonedaAc" value="" disabled>
                </div>
              </div>
            </div>
            <div id="moneda_diferente">
              <div class="form-group">
                <label for="usr">Tipo de cambio:</label>
              </div>
              <div class="row">
                <div class="col md">
                  <input class="form-control numericDecimal-only" type="numeric" placeholder="" name="tipoDeCambio" id="tipoDeCambio">
                </div>
                <div class="col">
                  <input class="form-control" type="text" id="cmbMonedaG" name="cmbMonedaG" value="" disabled>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="usr">Fecha de Tranferencia: *</label>
              <input class="form-control" style="border:none;" value="<?php echo $fecha; ?>" type="date" min="2021-01-01" max="2030-01-01" id="txtFechaTransferencia" onchange="validEmptyInput(this)">
              <div class="invalid-feedback" id="invalid-fechaTrans">La tranferencia debe tener una fecha.
              </div>
            </div>
            <div class="form-group">
              <label for="usr">Observaciones: </label>
              <textarea class="form-control" placeholder="" type="text" required id="areaObservaciones"></textarea>
            </div>
            <label for="">* Campos requeridos</label>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal" id="btnCancelarActualizacionTransfer"><span class="ajusteProyecto">Cancelar</span></button>
              <button type="button" class="btnesp first espAgregar " id="btnGuardarTransferencia" onclick="guardarTransferencia()"><span class="ajusteProyecto">Guardar</span></button>
            </div>
          </div> <!-- end div Class MODAL BODY-->
        </form>
      </div>
    </div>
  </div>

  <!-- ADD MODAL AJUSTE -->
  <div class="modal fade right" id="ajuste_Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="overflow-y: scroll;">
    <div class="modal-dialog modal-full-height modal-right  modal-md" role="document">
      <div class="modal-content">
        <form name="formular" action="" id="ajuste" method="POST">
          <input type="hidden" name="idCuentaActualAjuste" id="idCuentaActualAjuste">

          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Ajuste</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>

          <div class="modal-body">

            <div class="form-group">
              <label for="usr">Cantidad:*</label>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col">
                  <input class="form-control numericDecimal-only" type="numeric" placeholder="Ej: 00.00" name="txtCantidadAjuste" id="txtCantidadAjuste" onkeyup="validEmptyInput(this)">
                  <div class="invalid-feedback" id="invalid-cantidadAjusCaj">El ajuste debe tener una cantidad.
                  </div>
                </div>
                <div class="col">
                  <input class="form-control" type="text" id="cmbMonedaAjuste" name="cmbMonedaAjuste" value="" disabled>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="d-flex">
                <div class="form-check mr-3">
                  <input class="form-check-input" type="radio" name="ajusteCaja" id="ajsutePositivo" value="positivo" onchange="validEmptyInput(this, 'invalid-tipoAjusCaj')" checked>
                  <label class="form-check-label" for="ajsutePositivo">
                    Ajuste positivo
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="ajusteCaja" id="ajusteNegativo" value="negativo" onchange="validEmptyInput(this, 'invalid-tipoAjusCaj')">
                  <label class="form-check-label" for="ajusteNegativo">
                    Ajuste negativo
                  </label>
                </div>
              </div>
              <div class="invalid-feedback" id="invalid-tipoAjusCaj">El ajuste debe tener un tipo de ajuste.
              </div>
            </div>

            <div class="form-group">
              <label for="usr">Fecha del Ajuste: *</label>
              <input class="form-control" style="border:none;" value="<?= $fecha; ?>" type="date" min="2021-01-01" max="2030-01-01" id="txtFechaAjuste" onchange="validEmptyInput(this)">
              <div class="invalid-feedback" id="invalid-fechaAjusCaj">El ajuste debe tener una fecha.
              </div>
            </div>
            <div class="form-group">
              <label for="usr">Observaciones:* </label>
              <textarea class="form-control" placeholder="" type="text" required id="areaObservacionesAjuste" onkeyup="validEmptyInput(this)"></textarea>
              <div class="invalid-feedback" id="invalid-obsAjusCaj">El ajuste debe tener una cantidad.
              </div>
            </div>
            <br>
            <label for="">* Campos requeridos</label>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal" id="btnCancelarAjuste"><span class="ajusteProyecto">Cancelar</span></button>
              <button type="button" class="btnesp first espAgregar " id="btnGuardarAjuste" onclick="guardarAjuste()"><span class="ajusteProyecto">Guardar</span></button>
            </div>
          </div> <!-- end div Class MODAL BODY-->
        </form>
      </div>
    </div>
  </div>
<!-- ADD MODAL PROVEEDOR -->
<div class="modal fade right" id="nuevo_Provedor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="overflow-y: scroll;">
  <div class="modal-dialog modal-full-height modal-right  modal-md" role="document">
    <div class="modal-content">
      <form action="" method="POST">
        <div class="modal-header">
          <h4 class="modal-title w-100" id="myModalLabel">Nuevo proveedor</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="usr">Nombre comercial:*</label>
            <div class="input-group mb-3">
              <input type="text" class="form-control alphaNumeric-only" maxlength="30" name="nombreProv" id="nombreProv" required onkeyup="escribirNombre()">
              <div class="invalid-feedback" id="invalid-nombreProv">El proveedor debe tener un nombre.
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="usr">Email:*</label>
            <input type="text" class="form-control" maxlength="30" name="emailProv" id="emailProv" required onkeyup="validarCorreo(this)">
            <div class="invalid-feedback" id="invalid-emailProv">El proveedor debe tener un email.
            </div>
          </div>
          <div class="form-group">
            <label for="cmbTipoPersona">Tipo de persona:*</label>
            <select class="form-control" name="cmbTipoPersona" id="cmbTipoPersona" onchange="validTipoPersona()">
              <option data-placeholder="true"></option>
              <option value="Moral">Moral</option>
              <option value="Física">Física</option>
            </select>
            <div class="invalid-feedback" id="invalid-tipoPersonaProv">El proveedor debe tener un tipo de persona.
            </div>
          </div>
          <div class="form-group">
            <input type="checkbox" id="creditoProv" onchange="activarDesactivarCred(this)">
            <label for="creditoProv">Activar credito:</label>
          </div>
          <div class="form-group">
            <label for="txtDiasCredito">Días de crédito</label>
            <input class="form-control numeric-only" type="text" name="txtDiasCredito" id="txtDiasCredito" disabled onkeyup="validEmptyInput(this, 'invalid-diasProv')">
            <div class="invalid-feedback" id="invalid-diasProv">El credito debe tener los dias del credito.
            </div>
            <label for="txtLimiteCredito">Límite de crédito</label>
            <input class="form-control numeric-only" type="text" name="txtLimiteCredito" id="txtLimiteCredito" disabled onkeyup="validEmptyInput(this, 'invalid-credProv')">
            <div class="invalid-feedback" id="invalid-credProv">El credito debe tener un limite de credito.
            </div>
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btnesp first espCancelar btnCancelarNuevoProv" data-dismiss="modal" id="btnCancelarNuevoProv"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp espAgregar float-right" name="btnAgregarProv" id="btnAgregarProveedor"><span class="ajusteProyecto">Agregar</span></button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
  
  <!-- UPDATE WITH MODAL -->
  <div class="modal fade right" id="editar_Cuenta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-md" role="document">
      <div class="modal-content">
        <form action="" id="editarCuenta" method="POST">
          <input type="hidden" name="idCuentaU" id="idCuentaU">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Editar cuenta</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="usr">Nombre de la cuenta:*</label>
              <input type="text" class="form-control alphaNumeric-only" maxlength="30" name="txtNombreCuentaU" id="txtNombreCuentaU" required onKeyUp="validEmptyInput(this)">
              <div class="invalid-feedback" id="invalid-nombreCntU">La cuenta debe tener nombre.</div>
            </div>
            <div class="form-group">
              <label for="usr">Tipo de cuenta:*</label>
              <input class="form-control" type="text" name="txtTipoCuentaU" id="txtTipoCuentaU" value="" readonly required>
            </div>
            <!--UPDATE CUENTAS CHEQUES -->
            <div id="chequesU">
              <div class="form-group">
                <label for="usr">Banco o institución:*</label>
                <select class="form-control" name="cmbBancoU" id="cmbBancoU" readonly disabled>
                </select>
              </div>
              <div class="form-group">
                <label for="usr">Número de cuenta:*</label>
                <div class="input-group mb-3">
                  <input type="text" maxlength="12" class="form-control numeric-only" name="txtNoCuentaU" id="txtNoCuentaU" onkeyup="validaUnicaNoCuentaChequesU(event,this)">
                </div><input type="hidden" id="result4" readonly>
                <div class="invalid-feedback" id="invalid-noCuentaU">La cuenta debe tener una clabe.</div>
              </div>
              <div class="form-group">
                <label for="usr">Saldo:*</label>
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1">$</span>
                  <input type="text" maxlength="11" class="form-control numericDecimal-only" name="txtSaldoInicialU" id="txtSaldoInicialU" readonly disabled>
                </div>
              </div>
              <div class="form-group">
                <label for="usr">CLABE:*</label>
                <div class="input-group mb-3">
                  <input type="text" maxlength="18" class="form-control numeric-only" name="txtClabeUp" id="txtClabeUp" onkeyup="validaUnicaClabeChequesU(event,this)">
                  <input type="hidden" id="result3" readonly>
                  <div class="invalid-feedback" id="invalid-clabeCuentaU">La cuenta debe tener una clabe.</div>
                </div>
              </div>
            </div>
            <!--UPDATE CUENTAS CREDITO -->
            <div id="creditoU">

              <div class="form-group">
                <label for="usr">Banco o institución:*</label>
                <select class="form-control" name="cmbBancoUCredito" id="cmbBancoUCredito" readonly disabled>
                </select>
              </div>
              <div class="form-group">
                <label for="usr">Número de credito:*</label>
                <div class="input-group mb-3">
                  <input type="text" maxlength="11" class="form-control numeric-only" name="txtNoCreditoU" id="txtNoCreditoU" onkeyup="validacionUnicoNoCreditoU()">
                  <div class="invalid-feedback" id="invalid-noCreditoU">La cuenta debe tener número de crédito.</div>
                </div>
              </div>
              <div class="form-group">
                <label for="usr">Referencia:*</label>
                <div class="input-group mb-3">
                  <input type="text" class="form-control numeric-only" name="txtReferenciaU" id="txtReferenciaU" onKeyUp="validEmptyInput(this)">
                  <div class="invalid-feedback" id="invalid-referenciaU">La cuenta debe tener referencia.</div>
                </div>
              </div>
              <div class="form-group">
                <label for="usr">Limite de crédito:*</label>
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1">$</span>
                  <input type="text" maxlength="11" class="form-control numericDecimal-only" name="txtLimiteCreditoU" id="txtLimiteCreditoU" readonly>
                </div>
              </div>
              <div class="form-group">
                <label for="usr">Crédito Utilizado:*</label>
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1">$</span>
                  <input type="text" maxlength="11" class="form-control numericDecimal-only" name="txtCreditoUtilizadoU" id="txtCreditoUtilizadoU" readonly>
                </div>
              </div>
            </div>
            <!-- UPDATE DE CUENTAS OTRAS-->
            <div id="otrasU">
              <div class="form-group">
                <label for="usr">Identificador De la Cuenta:*</label>
                <div class="input-group mb-3">
                  <input type="text" maxlength="20" class="form-control numeric-only" name="txtIdentificadorU" id="txtIdentificadorU" onKeyUp="validacionUnicoIdentificadorU()">
                  <div class="invalid-feedback" id="invalid-identfCntU">La cuenta debe tener un identificador.</div>
                </div>
              </div>
              <div class="form-group">
                <label for="usr">Descripción:</label>
                <input type="text" class="form-control" name="txtDescripcionU" id="txtDescripcionU">
                <div class="invalid-feedback" id="invalid-descrOtU">La cuenta debe tener una descripción.</div>
              </div>
              <div class="form-group">
                <label for="usr">Saldo:*</label>
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1">$</span>
                  <input type="text" maxlength="11" class="form-control numericDecimal-only" name="txtSaldoInicialUOtras" id="txtSaldoInicialUOtras" readonly>
                </div>
              </div>
            </div>
            <!-- UPDATE cuenta caja chica -->
            <div id="cajaU">
              <div class="form-group">
                <label for="usr">Responsable:*</label>
                <select class="form-control" name="cmbResponsableU" id="cmbResponsableU" readonly disabled>
                </select>
              </div>
              <div class="form-group">
                <label for="usr">Descripción:</label>
                <input type="text" class="form-control" name="txtDescripcionUCaja" id="txtDescripcionUCaja">
                <div class="invalid-feedback" id="invalid-descrCajaU">La cuenta debe tener una descripción.</div>
              </div>
              <div class="form-group">
                <label for="usr">Saldo:*</label>
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1">$</span>
                  <input type="text" maxlength="11" class="form-control numericDecimal-only" name="txtSaldoInicialUCaja" id="txtSaldoInicialUCaja" readonly disabled>
                </div>
              </div>
            </div>
            <div class="modal-footer justify-content-end">
              <button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnEditarCuenta" onclick="editaCuenta()"><span class="ajusteProyecto">Modificar</span></button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>
  <script src="../../js/validaciones.js"></script>
  <script src="js/validacion_datos.js" charset="utf-8"></script>
  <script src="js/agregar_TransferenciaCapital.js" charset="utf-8"></script>
  <script src="js/funciones_caja.js" charset="utf-8"></script>
  <script src="js/validacion_clabes.js" charset="utf-8"></script>
  <script src="js/editar_Cuentas.js" charset="utf-8"></script>
  <script>
    $(document).ready(function(){
    var cmbTipoPersona = '';
      new SlimSelect({
        select: "#cmbResponsableGasto",
        deselectLabel: '<span class="">✖</span>',
      });
      new SlimSelect({
        select: "#cmbProvedoresGasto",
        deselectLabel: '<span class="">✖</span>',
      });
      new SlimSelect({
        select: "#cmbCuentaDestinoCj",
        deselectLabel: '<span class="">✖</span>',
      });
      cmbTipoPersona = new SlimSelect({
        select: "#cmbTipoPersona",
        deselectLabel: '<span class="">✖</span>',
      });
    });

    $("#btnTipoCuenta").click(function() {
      var idTipo = $('#tipoIdCuentaU').val();
      //alert("Tipo cuenta: "+idTipo);
      $.ajax({
        type: 'POST',
        url: 'functions/movimientos_Cuentas.php',
        data: {
          'tipoIdCuentaU': idTipo
        },
        success: function(r) {
          var datos = JSON.parse(r);
          console.log(datos.html);
          //$("#idCuentaU").val(datos.pkcuenta);
          //
        }
      });

    });

    console.log(parseInt(idTipo));
    function eliminarCuenta() {
      var id = $("#idCuentaU").val();
      //alert("oo: "+id);
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          actions: "d-flex justify-content-around",
          confirmButton: "btn-custom btn-custom--border-blue",
          cancelButton: "btn-custom btn-custom--blue",
        },
        buttonsStyling: false,
      });
      swalWithBootstrapButtons.fire({
        title: '¿Desea eliminar el registro de esta cuenta?2',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<span class="verticalCenter2">Eliminar Cuenta</span>',
        cancelButtonText: '<span class="verticalCenter2">Cancelar</span>',
        reverseButtons: true
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "functions/eliminar_Cuenta.php",
            type: "POST",
            data: {
              "idCuentaU": parseInt(getId)
            },
            success: function(data, status, xhr) {
              if (data == "exito") {
                window.location.href = '../cuentas_bancarias/';
                Lobibox.notify('success', {
                  size: 'mini',
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: 'center top', //or 'center bottom'
                  icon: true,
                  img: '../../img/chat/checkmark.svg',
                  msg: '¡Registro eliminado!'
                });
              } else {
                Lobibox.notify('error', {
                  size: 'mini',
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: 'center top',
                  icon: true,
                  img: '../../img/timdesk/notificacion_error.svg',
                  msg: 'Ocurrió un error al eliminar'
                });
              }
            }
          });
        } else if (
          /* Read more about handling dismissals below */
          result.dismiss === Swal.DismissReason.cancel
        ) {}
      });

      swal("¿Desea eliminar el registro de esta cuenta?", {
          buttons: {
            cancel: {
              text: "Cancelar",
              value: null,
              visible: true,
              className: "",
              closeModal: true,
            },
            confirm: {
              text: "Eliminar turno",
              value: true,
              visible: true,
              className: "",
              closeModal: true,
            },
          },
          icon: "warning"
        })
        .then((value) => {
          if (value) {
            $.ajax({
              url: "functions/eliminar_Cuenta.php",
              type: "POST",
              data: {
                "idTurnoD": id
              },
              success: function(data, status, xhr) {
                if (data == "exito") {
                  $('#editar_Cuenta').modal('toggle');
                  $('#tblCuentasBancarias').DataTable().ajax.reload();
                  Lobibox.notify('error', {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/chat/notificacion_error.svg',
                    msg: 'Cuenta eliminada!'
                  });
                } else {
                  Lobibox.notify('warning', {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top',
                    icon: false,
                    img: '../../img/timdesk/warning_circle.svg',
                    msg: 'Ocurrió un error al eliminar'
                  });
                }
              }
            });
          }
        });
    }

    $("#btnEditarCuenta").click(function(){
      $('#editar_Cuenta').modal('toggle');
    });

    $(document).on('change', '#cmbTipoCuenta', function(event) {
      if ($("#cmbTipoCuenta option:selected").val() == 1) {
        $('#cheques').show();
        $('#cmbBanco').prop('required', true);
        $('#cmbEmpresaCheques').prop('required', true);
        $('#txtNoCuenta').prop('required', true);
        $('#txtCLABE').prop('required', true);
        $('#txtSaldo').prop('required', true);
        $('#cmbBancoCredito').prop('required', false);
        //$('#txtNoCredito').prop('required', false);
        $('#txtLimiteCredito').prop('required', false);
        $('#txtCreditoUtilizado').prop('required', false);
        $('#txtIdCuenta').prop('required', false);
        $('#txtSaldoInicial').prop('required', false);
        $('#credito').hide();
        $('#otros').hide();
        $('#cajaChicaA').hide();
        var nota = document.getElementById("notaBotones");
        nota.setAttribute("type", "hidden");
      } else if ($("#cmbTipoCuenta option:selected").val() == 2) {
        $('#credito').show();
        $('#cmbBanco').prop('required', false);
        $('#txtNoCuenta').prop('required', false);
        $('#txtSaldo').prop('required', false);
        $('#cmbBancoCredito').prop('required', true);
        //$('#txtNoCredito').prop('required', true);
        $('#txtLimiteCredito').prop('required', true);
        $('#txtCreditoUtilizado').prop('required', true);
        $('#txtIdCuenta').prop('required', false);
        $('#txtSaldoInicial').prop('required', false);
        $('#cheques').hide();
        $('#otros').hide();
        $('#cajaChicaA').hide();
        var nota = document.getElementById("notaBotones");
        nota.setAttribute("type", "hidden");
      } else if ($("#cmbTipoCuenta option:selected").val() == 3) {
        $('#otros').show();
        $('#cmbBanco').prop('required', false);
        $('#txtNoCuenta').prop('required', false);
        $('#txtSaldo').prop('required', false);
        $('#cmbBancoCredito').prop('required', false);
        $('#txtNoCredito').prop('required', false);
        $('#txtLimiteCredito').prop('required', false);
        $('#cmbEmpresaOtros').prop('required', true);
        $('#txtCreditoUtilizado').prop('required', false);
        $('#txtIdCuenta').prop('required', true);
        $('#txtSaldoInicial').prop('required', true);
        $('#cheques').hide();
        $('#credito').hide();
        $('#cajaChicaA').hide();
        var nota = document.getElementById("notaBotones");
        nota.setAttribute("type", "hidden");

      } else if ($("#cmbTipoCuenta option:selected").val() == 4) {
        $('#cajaChica').show();
        $('#otros').hide();
        $('#credito').hide();
        $('#cheques').hide();
        $('#txtSaldoInicialCaja').prop('required', true);
        var nota = document.getElementById("notaBotones");
        nota.setAttribute("type", "hidden");

      } else {
        var nota = document.getElementById("notaBotones");
        nota.setAttribute("type", "text");
      }

    });

    $('#cajaChicaA').hide();
    $('#otros').hide();
    $('#credito').hide();
    $('#cheques').hide();

    $(document).on('change', '#cmbTipoCuenta', function(event) {
      if ($("#cmbTipoCuenta option:selected").val() == 1) {
        $('#cheques').show();
        $('#cmbBanco').prop('required', true);
        $('#cmbEmpresaCheques').prop('required', true);
        $('#txtNoCuenta').prop('required', true);
        $('#txtSaldo').prop('required', true);
        $('#credito').hide();
        $('#otros').hide();
        $('#cajaChicaA').hide();
      } else if ($("#cmbTipoCuenta option:selected").val() == 2) {
        $('#credito').show();
        $('#cmbBancoCredito').prop('required', true);
        $('#txtNoCredito').prop('required', true);
        $('#txtLimiteCredito').prop('required', true);
        $('#txtCreditoUtilizado').prop('required', true);
        $('#cheques').hide();
        $('#otros').hide();
        $('#cajaChicaA').hide();
      } else if ($("#cmbTipoCuenta option:selected").val() == 3) {
        $('#otros').show();
        $('#txtIdCuenta').prop('required', true);
        $('#txtSaldoInicial').prop('required', true);
        $('#cheques').hide();
        $('#credito').hide();
        $('#cajaChicaA').hide();
      } else if ($("#cmbTipoCuenta option:selected").val() == 4) {
        $('#cajaChicaA').show();
        $('#otros').hide();
        $('#credito').hide();
        $('#cheques').hide();
      } else {
        $('#cajaChicaA').hide();
        $('#otros').hide();
        $('#credito').hide();
        $('#cheques').hide();
      }
    });

    /* Reiniciar el modal al cerrarlo */
    $("#editar_Cuenta").on("hidden.bs.modal", function(e) {
      $("#invalid-nombreCntU").css("display", "none");
      $("#txtNombreCuentaU").removeClass("is-invalid");
      $("#txtNombreCuentaU").val("");

      $("#invalid-noCuentaU").css("display", "none");
      $("#txtNoCuentaU").removeClass("is-invalid");
      $("#txtNoCuentaU").val("");

      $("#invalid-clabeCuentaU").css("display", "none");
      $("#txtClabeUp").removeClass("is-invalid");
      $("#txtClabeUp").val("");

      $("#invalid-noCreditoU").css("display", "none");
      $("#txtNoCreditoU").removeClass("is-invalid");
      $("#txtNoCreditoU").val("");

      $("#invalid-referenciaU").css("display", "none");
      $("#txtReferenciaU").removeClass("is-invalid");
      $("#txtReferenciaU").val("");

      $("#invalid-identfCntU").css("display", "none");
      $("#txtIdentificadorU").removeClass("is-invalid");
      $("#txtIdentificadorU").val("");

      $("#invalid-descrOtU").css("display", "none");
      $("#txtDescripcionU").removeClass("is-invalid");
      $("#txtDescripcionU").val("");

      $("#txtTipoCuentaU").val("");
      $("#txtSaldoInicialU").val("");
      $("#txtLimiteCreditoU").val("");
      $("#txtCreditoUtilizadoU").val("");
      $("#txtSaldoInicialUOtras").val("");
    });
  </script>

  <script>
    $("#btnGuardarMovimiento").click(function() {
      var idCuentaCaja = "<?= $idDet; ?>";
      var hayArchivo = 0;
      var file = $("#inputFile").val();
      var responsable = $("#cmbResponsableGasto").val();
      var importe = $("#txtImporteGasto").val();
      var fechaGasto = $("#txtFechaGasto").val();
      var observaciones = $("#areaDescripcionGasto").val();
      var proveedor = $("#cmbProvedoresGasto").val();
      var categoria = $("#cmbCategoria").val();
      var subcategoria = $("#cmbSubcategoria").val();
      var miArchivo = $('#inputFile').prop('files')[0];

      var s1 = parseFloat($('#saldoCuentaCaja').val());
      var s2 = parseFloat($('#txtImporteGasto').val());
      if (Math.fround(s2) > Math.fround(s1)) {
        lobiboxAlert("error", "¡El saldo es insuficiente!");
        return;
      }
      if (!responsable) {
        $("#invalid-responsableRet").css("display", "block");
        $("#cmbResponsableGasto").addClass("is-invalid");
      }
      if (!importe) {
        $("#invalid-importeRet").css("display", "block");
        $("#txtImporteGasto").addClass("is-invalid");
      }
      if (!fechaGasto) {
        $("#invalid-fechaRet").css("display", "block");
        $("#txtFechaGasto").addClass("is-invalid");
      }
      if (!proveedor) {
        $("#invalid-provRet").css("display", "block");
        $("#cmbProvedoresGasto").addClass("is-invalid");
      }
      if (!observaciones) {
        $("#invalid-observRet").css("display", "block");
        $("#areaDescripcionGasto").addClass("is-invalid");
      }
      if ($('#checkCaja').is(':checked')) {
        check = 0;
        hayArchivo = 0;
      } else {
        if (!file) {
          $("#invalid-archivoRet").css("display", "block");
          $("#inputFile").addClass("is-invalid");
        } else {
          hayArchivo = 1;
          check = 1;
        }
      }

      var badResponsableRet =
        $("#invalid-responsableRet").css("display") === "block" ? false : true;
      var importeRet =
        $("#invalid-importeRet").css("display") === "block" ? false : true;
      var badFechaRet =
        $("#invalid-fechaRet").css("display") === "block" ? false : true;
      var badProvRet =
        $("#invalid-provRet").css("display") === "block" ? false : true;
      var badObsRet =
        $("#invalid-observRet").css("display") === "block" ? false : true;
      var badArchivoRet =
        $("#invalid-archivoRet").css("display") === "block" ? false : true;

      if (badResponsableRet && importeRet && badFechaRet && badProvRet && badObsRet && badArchivoRet) {
        var fd = new FormData();
        fd.append('inputFile', miArchivo);
        fd.append('idCuentaCaja', idCuentaCaja);
        fd.append('cmbResponsableGasto', responsable);
        fd.append('txtImporteGasto', importe);
        fd.append('txtFechaGasto', fechaGasto);
        fd.append('cmbProvedoresGasto', proveedor);
        fd.append('areaDescripcionGasto', observaciones);
        fd.append('cmbCategoria', categoria);
        fd.append('cmbSubcategoria', subcategoria);
        fd.append('comprobado', check);
        fd.append('hayArchivo', hayArchivo);

        $.ajax({
          type: "POST",
          cache: false,
          contentType: false,
          processData: false,
          data: fd,
          url: "functions/agregar_MovimientoCajaChica.php",
          success: function(data, status, xhr) {
            console.log(data);
            if (data.trim() == "exito") {
              $('#retiro_Gasto').modal('toggle');
              $('#retiroGasto').trigger("reset");
              $('#tblDetalles').DataTable().ajax.reload();
              tablaInicialCaja(idCuentaCaja);
              $("#checkCaja").prop("disabled", false);
              $("#checkCaja").prop("checked", true);
              $("#inputFile").css("display", "none");
              $("#inputFile").val("");
              lobiboxAlert("success", "¡Retiro realizado!")
            } else {
              lobiboxAlert("error", "¡Ocurrió un error al agregar el retiro!")
            }
          },
          error: function(error) {
            console.log(error);
          }
        });
      }
    });
  </script>
  <script>
    $("#btnAgregarProveedor").click(function() {
      var nombre = $("#nombreProv").val();
      var email = $("#emailProv").val();
      var tipoPersona = $("#cmbTipoPersona").val();
      var isCreditoCheck = $("#creditoProv").is(':checked');
      var diascredito = $("#txtDiasCredito").val();
      var limitepractico = $("#txtLimiteCredito").val();

      if (!nombre) {
        $("#invalid-nombreProv").css("display", "block");
        $("#nombreProv").addClass("is-invalid");
      }
      if (!email) {
        $("#invalid-emailProv").css("display", "block");
        $("#emailProv").addClass("is-invalid");
      }
      if (!tipoPersona) {
        $("#invalid-tipoPersonaProv").css("display", "block");
      }
      if (isCreditoCheck) {
        if (!diascredito) {
          $("#invalid-diasProv").css("display", "block");
          $("#txtDiasCredito").addClass("is-invalid");
        }
        if (!limitepractico) {
          $("#invalid-credProv").css("display", "block");
          $("#txtLimiteCredito").addClass("is-invalid");
        }
      }

      var badNombreProv =
        $("#invalid-nombreProv").css("display") === "block" ? false : true;
      var badEmailProv =
        $("#invalid-emailProv").css("display") === "block" ? false : true;
      var badTipoPersonaProv =
        $("#invalid-tipoPersonaProv").css("display") === "block" ? false : true;
      var badDiasProv =
        $("#invalid-diasProv").css("display") === "block" ? false : true;
      var badCredProv =
        $("#invalid-credProv").css("display") === "block" ? false : true;

      if (badNombreProv && badEmailProv && badDiasProv && badCredProv) {
        $.ajax({
          url: "functions/agregar_Proveedor.php",
          type: "POST",
          data: {
            "nombre": nombre,
            "email": email,
            "tipoPersona": tipoPersona,
            "isCreditoCheck": isCreditoCheck,
            "diascredito": diascredito,
            "limitepractico": limitepractico,
          },
          success: function(data, status, xhr) {
            console.log(data);
            if (data.trim() == "exito") {
              $('#nuevo_Provedor').modal('toggle');
              $("#nombreProv").val("");
              $("#emailProv").val("");
              $("#creditoProv").val("");
              $("#txtDiasCredito").val("");
              $("#txtLimiteCredito").val("");
              $('#agregarProveedor').trigger("reset");
              $('#tblProveedores').DataTable().ajax.reload();
              cargarCMBProveedor("cmbProvedoresGasto");
              Lobibox.notify('success', {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: true,
                //img: '<i class="fas fa-check-circle"></i>',
                img: '../../img/timdesk/checkmark.svg',
                msg: '¡Registro agregado!'
              });
            } else {
              Lobibox.notify('warning', {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top',
                icon: true,
                img: '../../../../img/timdesk/warning_circle.svg',
                img: null,
                msg: 'Ocurrió un error al agregar'
              });
            }
          }
        });
      }
    });

    $("#checkCajaInyeccion").change(function() {
      var archivoComprob = document.getElementById("inputFileInyeccion");
      document.getElementById("invalid-archivoCjChica").style.display = "none";
      if (this.checked) {
        archivoComprob.style.display = "none";
        archivoComprob.required = false;
      } else {
        archivoComprob.style.display = "block";
        archivoComprob.required = true;
      }
    });

    $("#checkCaja").change(function() {
      var archivoComprobRet = document.getElementById("inputFile");
      document.getElementById("invalid-archivoRet").style.display = "none";
      if (this.checked) {
        archivoComprobRet.style.display = "none";
        archivoComprobRet.required = false;
      } else {
        archivoComprobRet.style.display = "block";
        archivoComprobRet.required = true;
      }
    });

    function validTipoPersona() {
      $("#invalid-tipoPersonaProv").css("display", "none");
    }

    function lobiboxAlert(tipo, mensaje) {
      var tipoImg = tipo === "success" ? "checkmark.svg" : "warning_circle.svg";
      Lobibox.notify(tipo, {
        size: "mini",
        rounded: true,
        delay: 4000,
        delayIndicator: false,
        position: "center top", //or 'center bottom'
        icon: true,
        img: "../../img/timdesk/" + tipoImg,
        msg: mensaje,
      });
    }

    function validEmptyInput(item, invalid = null) {
      const val = item.value;
      const parent = item.parentNode;
      let invalidDiv;
      if (invalid) {
        invalidDiv = document.getElementById(invalid);
      } else {
        for (let i = 0; i < parent.children.length; i++) {
          if (parent.children[i].classList.contains("invalid-feedback")) {
            invalidDiv = parent.children[i];
            break;
          }
        }
      }
      if (!val) {
        item.classList.add("is-invalid");
        invalidDiv.style.display = "block";
      } else {
        item.classList.remove("is-invalid");
        invalidDiv.style.display = "none";
      }
    }

    function escribirNombre() {
      var valor = document.getElementById("nombreProv").value;
      console.log("Valor nombre: " + valor);
      $.ajax({
        url: "functions/validar_proveedor.php",
        type: "POST",
        data: {
          "nombre": valor,
        },
        dataType: "json",
        success: function(data) {
          console.log("respuesta nombre valida: ", data);
          /* Validar si ya existe el identificador con ese nombre*/
          if (parseInt(data[0]["existe"]) == 1) {
            $("#invalid-nombreProv").css("display", "block");
            $("#invalid-nombreProv").text("El nombre ya esta en el registro.");
            $("#nombreProv").addClass("is-invalid");
          } else {
            if (!valor) {
              $("#invalid-nombreProv").css("display", "block");
              $("#invalid-nombreProv").text("El producto debe tener un nombre.");
              $("#nombreProv").addClass("is-invalid");
            } else {
              $("#invalid-nombreProv").css("display", "none");
              $("#nombreProv").removeClass("is-invalid");
            }
          }
        },
        error: function(error) {
          console.log(error);
        }
      });
    }

    function validarCorreo(item) {
      const val = item.value;
      const invalidDiv = item.nextElementSibling;

      const reg =
        /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      const regOficial =
        /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;

      //Se muestra un texto a modo de ejemplo, luego va a ser un icono
      if (reg.test(val) && regOficial.test(val)) {
        invalidDiv.style.display = "none";
        invalidDiv.innerText = "El usuario debe tener un correo.";
        item.classList.remove("is-invalid");
      } else if (reg.test(val)) {
        invalidDiv.style.display = "none";
        invalidDiv.innerText = "El usuario debe tener un correo.";
        item.classList.remove("is-invalid");
      } else {
        invalidDiv.style.display = "block";
        invalidDiv.innerText = "El correo debe ser valido.";
        item.classList.add("is-invalid");
      }
    }
  </script>

  <script>
    var ruta = "../";
  </script>

</body>

</html>
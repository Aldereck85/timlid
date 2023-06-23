<?php
session_start();
require_once '../../include/db-conn.php';
$user = $_SESSION["Usuario"];
$pkususario = $_SESSION["PKUsuario"];
$idempresa = $_SESSION["IDEmpresa"];

$ruta = "../";
$screen = 2;
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
  <title>Timlid | Gastos</title>

  <!-- ESTILOS -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../css/sweetalert2.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="../../css/stylesNewTable.css" rel="stylesheet">
  <link href="css/sytles_gastos.css" rel="stylesheet">
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
  <script src="../../js/jquery.redirect.min.js"></script>
  <script src="../../js/numeral.min.js"></script>
  <script>
    $(document).ready(function() {
      $("#cmbCuentaFiltro").on("change", function() {
        var cuenta = $("#cmbCuentaFiltro").val();
        $("#tblGastos").DataTable().destroy();
        $("#tblGastos").DataTable({
          "language": idioma_espanol,
          info: false,
          scrollX: true,
          bSort: false,
          pageLength: 50,
          responsive: true,
          lengthChange: false,
          columnDefs: [{
              orderable: false,
              targets: 0,
              visible: false
            },
            {
              targets: 7,

            }
          ],
          dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
                <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
          buttons: {
            dom: {
              button: {
                tag: "button",
                className: "btn-custom mr-2",
              },
              buttonLiner: {
                tag: null,
              },
            },
            buttons: [{
                text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> Añadir registro</span>',
                className: "btn-custom--white-dark",
                action: function() {
                  $('#retiro_Gasto').modal('show');
                },
              },
              {
                extend: "excelHtml5",
                text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar excel</span>',
                className: "btn-custom--white-dark",
                titleAttr: "Excel",
              }
            ],
          },
          "ajax": "functions/historialGastos.php?cuenta=" + cuenta,
          "columns": [{
              "data": "Id"
            },
            {
              "data": "Folio"
            },
            {
              "data": "Nombre"
            },
            {
              "data": "Proveedor"
            },
            {
              "data": "Fecha"
            },
            {
              "data": "Descripcion"
            },
            {
              "data": "Retiro"
            },
            {
              "data": "Saldo"
            },
            {
              "data": "Responsable"
            },
            {
              "data": "Referencia",
              "width": "300px",
            },
            {
              "data": "Comprobado"
            },
            {
              "data": "Categoria"
            }
          ],
        })
      });

      var idioma_espanol = {
        "sProcessing": "Procesando...",
        "sZeroRecords": "No se encontraron resultados",
        "sEmptyTable": "Ningún dato disponible en esta tabla",
        "sSearch": "<img src='../../img/timdesk/buscar.svg' width='20px' />",
        "sLoadingRecords": "<img src='../../img/timdesk/Preloader.gif' width='100px' style='transition-duration:300ms;' />",
        searchPlaceholder: "Buscar...",
        "oPaginate": {
          "sFirst": "Primero",
          "sLast": "Último",
          "sNext": "<i class='fas fa-chevron-right'></i>",
          "sPrevious": "<i class='fas fa-chevron-left'></i>"
        },
      }

      $("#tblGastos").DataTable({
        "language": idioma_espanol,
        info: false,
        scrollX: true,
        bSort: false,
        pageLength: 50,
        responsive: true,
        lengthChange: false,
        columnDefs: [{
          orderable: false,
          targets: 0,
          visible: false
        }],
        dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
              <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
        buttons: {
          dom: {
            button: {
              tag: "button",
              className: "btn-custom mr-2",
            },
            buttonLiner: {
              tag: null,
            },
          },
          buttons: [{
              text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> Añadir registro</span>',
              className: "btn-custom--white-dark",
              action: function() {
                $('#retiro_Gasto').modal('show');
                $("#btnGuardarMovimiento").prop("disabled", false);
              },
            },
            {
              extend: "excelHtml5",
              text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar excel</span>',
              className: "btn-custom--white-dark",
              titleAttr: "Excel",
            }
          ],
        },
        "ajax": "functions/historialGastos.php",
        "columns": [{
            "data": "Id"
          },
          {
            "data": "Folio"
          },
          {
            "data": "Nombre"
          },
          {
            "data": "Proveedor"
          },
          {
            "data": "Fecha"
          },
          {
            "data": "Descripcion"
          },
          {
            "data": "Retiro"
          },
          {
            "data": "Responsable"
          },
          {
            "data": "Referencia",
            "width": "300px"
          },
          {
            "data": "Comprobado"
          },
          {
            "data": "Categoria"
          }
        ],
      })
    });
  </script>

</head>

<body id="page-top" class="sidebar-toggled">
  <!-- Page Wrapper -->
  <div id="wrapper">
    <!-- Sidebar -->
    <?php
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
      <input type="hidden" id="txtPantalla" value="<?= $screen; ?>">

      <!-- Main Content -->
      <div id="content">
        <?php
        $rutatb = "../";
        $icono = 'ICONO-GASTOS-AZUL.svg';
        $titulo = 'Gastos';
        require_once '../topbar.php';
        ?>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <ul class="nav nav-tabs" id="gastos_detalles-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="gastos-tab" data-toggle="tab" href="#gastos" role="tab" aria-controls="gastos" aria-selected="true">Gastos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="detalleGastos-tab" data-toggle="tab" href="#detalleGastos" role="tab" aria-controls="detalleGastos" aria-selected="false">Detalle gastos</a>
                </li>
            </ul>
            <div class="card">
              <div class="tab-content"> 
                <div class="tab-pane active" id="gastos" role="tabpanel" aria-labelledby="gastos-tab">
                    <!-- Page Heading -->
                    <input type="hidden" name="" id="emp_id" value="<?php echo $idempresa ?>">
                    <div class="card-body">
                    <!-- <div class="row">
                        <div class="col-lg-12">
                            <h3 class="textBlue text-center"><b>Categorías de gasto:</b><span><b class="totalText" id="categoriesTotalNet"></b></span></h3>
                            <div class="row" id="categoriesTotals"></div>
                            <p></p>
                            <h3 class=" textBlue text-center"><b>Subcategorías de gasto:</b><span><b class="totalText" id="subcategoriesTotalNet"></b></span></h3>
                            <div class="row" id="subcategoriesTotals"></div>
                        </div>
                    </div>
                    <p></p> -->
                    <div class="row mb-5">
                        <div class="col-2">
                        <label for="cmbCuentaFiltro">Cuenta:</label>
                        <select class="form-control" name="cmbCuentaFiltro" id="cmbCuentaFiltro">
                            <option data-placeholder="true"></option>
                            <option value="0">Todas</option>
                            <?php
                            $stmt = $conn->prepare("SELECT PKCuenta, Nombre FROM cuentas_bancarias_empresa where empresa_id=:idempresa and tipo_cuenta != 2 ORDER BY PKCuenta desc");
                            $stmt->bindValue(':idempresa', $idempresa);
                            $stmt->execute();
                            while ($row = $stmt->fetch()) {
                            ?>
                            <option value="<?= $row['PKCuenta']; ?>"><?= $row['Nombre'] ?>
                            </option>
                            <?php } ?>
                        </select>
                        </div>
                        <div class="col-1"></div>
                        
                    </div>
                    <div class="table-responsive">
                        <table class="table" id="tblGastos" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                            <th>Id</th>
                            <th>Folio</th>
                            <th>Cuenta</th>
                            <th>Proveedor</th>
                            <th>Fecha</th>
                            <th>Descripción</th>
                            <th>Retiro/cargo</th>
                            <th>Responsable</th>
                            <th>Referencia</th>
                            <th>Estatus de comprobación</th>
                            <th>Categoría</th>
                            </tr>
                        </thead>
                        </table>
                    </div>
                    </div>
                </div>
                <div class="tab-pane" id="detalleGastos" role="tabpanel" aria-labelledby="detalleGastos-tab">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4"></div>
                            <div class="col-4"></div>
                            <div class="col-4 text-center textData">
                                <h2>
                                    <p><b class="textBlue" id="ejercicioAnioActual">Ejercicio <?= date('Y')?>:</b></p>
                                    <b class="textBlue" from="totalText">Total:</b>
                                    <div class="totalText">$0.00</div>
                                </h2>
                            </div>
                        </div>
                        <div class="row">
                          <div class="col-2">
                            <label for="cmbFiltroCuentaDetalles">Cuenta:</label>
                            <select name="cmbFiltroCuentaDetalles" id="cmbFiltroCuentaDetalles">
                              <option value="" selected>Seleccione una cuenta</option>
                              <option value="0">Todas</option>
                              <?php
                                  $stmt = $conn->prepare("SELECT PKCuenta, Nombre FROM cuentas_bancarias_empresa where empresa_id=:idempresa and tipo_cuenta != 2 ORDER BY PKCuenta desc");
                                  $stmt->bindValue(':idempresa', $idempresa);
                                  $stmt->execute();
                                  while ($row = $stmt->fetch()) {
                                  ?>
                                <option value="<?= $row['PKCuenta']; ?>"><?= $row['Nombre'] ?>
                                </option>
                              <?php } ?>
                            </select>
                          </div>
                          <div class="col-2">
                            <label for="cmbFiltroCategoriasDetalles">Categoría de gasto:</label>
                            <select name="cmbFiltroCategoriasDetalles" id="cmbFiltroCategoriasDetalles">
                                <option value="0" selected>Seleccione una categoria</option>
                            </select>
                          </div>
                          <div class="col-2">
                            <label for="cmbFiltroSubcategoriasDetalles">Subcategoría de gasto:</label>
                            <select name="cmbFiltroSubcategoriasDetalles" id="cmbFiltroSubcategoriasDetalles">
                                <option disabled value="0" selected>Seleccione una subcategoria</option>
                            </select>
                          </div>
                          <div class="col-2">
                            <label for="cmbFiltroFechaInicioDetalles">Fecha inicial:</label>
                            <input class="form-control" type="date" name="fchFiltroFechaInicioDetalles" id="fchFiltroFechaInicioDetalles">
                          </div>
                          <div class="col-2">
                            <label for="cmbFiltroFechaFinalDetalles">Fecha final:</label>
                            <input class="form-control" type="date" name="fchFiltroFechaFinalDetalles" id="fchFiltroFechaFinalDetalles">
                          </div>
                          <div class="col-2 d-flex align-items-end">
                            <button type="button" class="btn-custom btn-custom--blue espAgregar" id="btnFiltroGastosDetalles">Filtrar</button>
                          </div>
                          
                        </div>
                        <br>
                        <div class="" id="reporte_detallesGastos">
                          <div class="row" id="cards-reporting-gastos">
                            
                            <!-- <div class="card" style="width: 18rem;border-left: 1px solid #006dd9;border-right: 1px solid #006dd9;margin:0 5rem 5rem 0">
                              <div class="card-header text-center" style="background-color:#006dd9;color:#fff">
                                Featured
                              </div>
                              <div class="card-body">
                                <h5 class="card-title">Special title treatment</h5>
                                <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                              </div>
                              <div class="card-footer text-center" style="background-color:#006dd9;color:#fff">
                                Total: $ <span id="total1">0.00</span>
                              </div>
                            </div> -->
                          </div>
                        </div>
                        
                    </div>
                </div>
              </div> 
            </div>
        </div>
        <!-- /.container-fluid -->
      </div> <!-- End of Main Content -->

      <!-- Footer -->
      <?php
      $rutaf = "../";
      require_once '../footer.php';
      ?>
      <!-- End of Footer -->
    </div>
    <!-- End of Main Content -->
  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- ELIMINAR GASTO -->
  <div class="modal fade" id="eliminar_Gasto" tabindex="-1" role="dialog" aria-labelledby="eliminarGasto" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="eliminarGasto">Eliminar gasto</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="idGasto">
          <label>¿Deseas eliminar el registro?</label>
        </div>
        <div class="modal-footer">
          <button type="button" data-dismiss="modal" class="btnesp first espCancelar"><span class="ajusteProyecto"></span>Cerrar<span class="ajusteProyecto"></span></button>
          <button type="button" id="eliminarGasto" onclick="eliminarGasto()" class="btnesp first espAgregar"><span class="ajusteProyecto"></span>Eliminar<span class="ajusteProyecto"></span></button>
        </div>
      </div>
    </div>
  </div>

  <!-- MODAL PARA AGREAGR UN GASTO DE CUENTA -->
  <div class="modal fade right" id="retiro_Gasto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="overflow-y: scroll;">
    <div class="modal-dialog modal-full-height modal-right  modal-md" role="document">
      <div class="modal-content">
        <form method="POST" name="retiroGasto" action="" id="retiroGasto">
          <input type="hidden" id="emp_id" name="emp_id" value="<?= $idempresa; ?>">
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
              <div class="row">
                <div class="col">
                  <label for="cmbCuenta">Cuenta:*</label>
                  <select class="form-control" name="cmbCuenta" id="cmbCuenta" required onchange="validEmptyInput(this)">
                    <option data-placeholder="true"></option>
                    <?php
                    $stmt = $conn->prepare("SELECT PKCuenta, Nombre FROM cuentas_bancarias_empresa where empresa_id=:idempresa and tipo_cuenta != 2 ORDER BY PKCuenta desc");
                    $stmt->bindValue(':idempresa', $idempresa);
                    $stmt->execute();
                    while ($row = $stmt->fetch()) {
                    ?>
                      <option value="<?= $row['PKCuenta']; ?>"><?= $row['Nombre'] ?>
                      </option>
                    <?php } ?>
                  </select>
                  <div class="invalid-feedback" id="invalid-cuentaRet">Elige una cuenta.
                  </div>
                </div>
                <div class="col">
                  <label for="lblSaldo">Saldo: $</label>
                  <label id="lblSaldo"></label>
                </div>
              </div>
            </div>
            <div id="inputs">
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
                  $stmt->bindValue(':empresa', $idempresa);
                  $stmt->bindValue(':tipoEmpleado', $idTipoResponsable);
                  $stmt->execute();
                  while ($row = $stmt->fetch()) {
                  ?>
                    <option value="<?= $row['PKEmpleado']; ?>"><?= $row['Nombres'] ?> <?= $row['PrimerApellido'] ?>
                      <?= $row['SegundoApellido']; ?>
                    </option>
                  <?php } ?>
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
                <input class="form-control" style="border:none;" type="date" min="2021-01-01" max="2030-01-01" id="txtFechaGasto" name="txtFechaGasto" required onchange="validEmptyInput(this)">
                <div class="invalid-feedback" id="invalid-fechaRet">El retiro debe tener una fecha.
                </div>
              </div>
              <div class="form-group">
                <label for="usr" class="d-flex justify-content-between"><span>Proveedor:</span><a href="" data-toggle="modal" data-target="#nuevo_Provedor" type="text" style="text-align: right">Nuevo
                    proveedor</a></label>
                <select name="cmbProvedoresGasto" class="form-control" id="cmbProvedoresGasto">
                  <option value="" disabled selected hidden>Seleccionar un proveedor</option>
                  <?php
                  $stmt = $conn->prepare("SELECT * FROM proveedores WHERE empresa_id = :empresa AND tipo = 1");
                  $stmt->bindValue(':empresa', $idempresa);
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
              </div>
              <div class="form-group">
                <label for="usr">Observaciones:</label>
                <textarea class="form-control" name="areaDescripcionGasto" id="areaDescripcionGasto" type="text"></textarea>
              </div>
              <div class="form-group">
                <label for="usr" class="d-flex justify-content-between"><span>Categoría:</span><a href="" data-toggle="modal" data-target="#nueva_categoria" type="text" style="text-align: right" id="aNuevaCat">Nueva
                    categoria</a></label>
                <select name="cmbCategoria" id="cmbCategoria" onchange="categoria()" required>
                </select>
                <div class="invalid-feedback" id="invalid-nombreCat">La subcategoría de tener una categoria.
                </div>
              </div>
              <br><br>
              <div class="form-group">
                <label for="usr" class="d-flex justify-content-between"><span>Subcategoría:</span> <a href="" data-toggle="modal" data-target="#nueva_subCategoria" type="text" style="text-align: right" id="aNuevaSubcat" onclick="cargarCMBCategoriasG('','#cmCategoria')">Nueva
                    subcategoria</a></label>
                <select class="form-control" name="cmbSubcategoria" id="cmbSubcategoria">
                </select>
                <div class="invalid-feedback" id="invalid-nombreSubcat">Elige una subcategoría.
                </div>
              </div>
              <div class="form-group">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="checkCaja" name="checkCaja" enabled checked>
                  <label class="form-check-label" for="checkCaja">
                    Por comprobar
                  </label>
                  <input id="inputFile" name="inputFile" type="file" accept="image/*, .pdf, .xlsx, .xml " class="file" data-browse-on-zone-click="true" class="form-control" style="display: none;" onchange="validar_documento()">
                  <div class="invalid-feedback" id="invalid-archivoRet">El retiro debe tener un documento de
                    comprobación.
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacionCajaChica" data-dismiss="modal" id="btnCancelarActualizacionCajaChica"><span class="ajusteProyecto">Cancelar</span></button>
              <button type="button" class="btnesp first espAgregar" id="btnGuardarMovimiento"><span class="ajusteProyecto" disabled>Guardar</span></button>
            </div>
          </div> <!-- end div Class MODAL BODY-->
        </form>
      </div>
    </div>
  </div>

  <!-- MODAL PARA EDITAR UN GASTO DE CUENTA -->
  <div class="modal fade right" id="editar_Gasto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="overflow-y: scroll;">
    <div class="modal-dialog modal-full-height modal-right  modal-md" role="document">
      <div class="modal-content">
        <form method="POST" name="edicionGasto" action="" id="edicionGasto">
          <input type="hidden" id="emp_id" name="emp_id" value="<?= $idempresa; ?>">
          <input type="hidden" name="idCuentaCaja" id="idCuentaCaja">
          <input type="hidden" name="Edit" id="saldoCuentaCajaEdit">
          <input type="hidden" name="idMovimiento" id="idMovimiento">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Editar gasto</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <div class="row">
                <div class="col">
                  <label for="cmbCuentaEdit">Cuenta:*</label>
                  <select class="form-control" name="cmbCuentaEdit" id="cmbCuentaEdit" required onchange="validEmptyInput(this)" disabled>
                  </select>
                  <div class="invalid-feedback" id="invalid-cuentaRetEdit">Elige una cuenta.
                  </div>
                </div>
                <div class="col">
                  <label for="lblSaldoEdit">Saldo: $</label>
                  <label id="lblSaldoEdit"></label>
                </div>
              </div>
            </div>
            <div id="inputsEdit">
              <div class="form-group">
                <label for="cmbResponsableGastoEdit">Responsable:*</label>
                <select class="form-control" name="cmbResponsableGastoEdit" id="cmbResponsableGastoEdit" required onchange="validEmptyInput(this)">
                </select>
                <div class="invalid-feedback" id="invalid-responsableRetEdit">El retiro debe tener un responsable.
                </div>
              </div>
              <div class="form-group">
                <label for="txtImporteGastoEdit">Importe del gasto: *</label>
                <input class="form-control numericDecimal-only" placeholder="Ej: 00.00" type="numeric" required id="txtImporteGastoEdit" name="txtImporteGastoEdit" onkeyup="validEmptyInput(this)" disabled>
                <div class="invalid-feedback" id="invalid-importeRetEdit">El retiro debe tener una cantidad.
                </div>
              </div>
              <div class="form-group">
                <label for="txtFechaGastoEdit">Fecha del movimiento: *</label>
                <input class="form-control" style="border:none;" type="date" min="2021-01-01" max="2030-01-01" id="txtFechaGastoEdit" name="txtFechaGastoEdit" required onchange="validEmptyInput(this)">
                <div class="invalid-feedback" id="invalid-fechaRetEdit">El retiro debe tener una fecha.
                </div>
              </div>
              <div class="form-group">
                <label for="cmbProvedoresGastoEdit" class="d-flex justify-content-between"><span>Proveedor:</span><a href="" data-toggle="modal" data-target="#nuevo_Provedor" type="text" style="text-align: right">Nuevo
                    proveedor</a></label>
                <select name="cmbProvedoresGastoEdit" class="form-control" id="cmbProvedoresGastoEdit">
                </select>
              </div>
              <div class="form-group">
                <label for="areaDescripcionGastoEdit">Observaciones:</label>
                <textarea class="form-control" name="areaDescripcionGastoEdit" id="areaDescripcionGastoEdit" type="text"></textarea>
              </div>
              <div class="form-group">
                <label for="cmbCategoriaEdit" class="d-flex justify-content-between"><span>Categoría:</span><a href="" data-toggle="modal" data-target="#nueva_categoria" type="text" style="text-align: right" id="aNuevaCatEdit">Nueva
                    categoria</a></label>
                <input type="hidden" id="hddCategoria">
                <select name="cmbCategoriaEdit" id="cmbCategoriaEdit" onchange="categoriaEdit()" value=''>
                </select>
              </div>
              <br><br>
              <div class="form-group">
                <label for="cmbSubcategoriaEdit" class="d-flex justify-content-between"><span>Subcategoría:</span> <a href="" data-toggle="modal" data-target="#nueva_subCategoria" type="text" style="text-align: right" id="aNuevaSubcatEdit" onclick="cargarCMBCategoriasG('','#cmCategoria')">Nueva
                    subcategoria</a></label>
                <input type="hidden" id="hddSubcategoria">
                <select class="form-control" name="cmbSubcategoriaEdit" id="cmbSubcategoriaEdit">
                </select>
              </div>
              <div class="form-group">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="checkCajaEdit" name="checkCajaEdit" enabled checked>
                  <label class="form-check-label" for="checkCajaEdit">
                    Por comprobar
                  </label>
                  <input id="inputFileEdit" name="inputFileEdit" type="file" accept="image/*, .pdf, .xlsx, .xml " class="file" data-browse-on-zone-click="true" class="form-control" style="display: none;" onchange="validar_documento()">
                  <div class="invalid-feedback" id="invalid-archivoRetEdit">El retiro debe tener un documento de
                    comprobación.
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btnesp first espCancelar"  data-toggle="modal" data-target="#eliminar_Gasto" onclick="obtenerIdGastoEliminar($('#idMovimiento').val());" id="btnEliminarMovimiento"><span class="ajusteProyecto" disabled>Eliminar</span></button>
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacionCajaChica" data-dismiss="modal"><span class="ajusteProyecto">Cancelar</span></button>
              <button type="button" class="btnesp first espAgregar" id="btnEditarMovimiento"><span class="ajusteProyecto" disabled>Guardar</span></button>
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
            <button type="button" class="close" onclick="$('#nueva_categoria').modal('hide');" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="tipoCmbCat">
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
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" onclick="$('#nueva_categoria').modal('hide');" id="cancelarCategoria"><span class="ajusteProyecto">Cancelar</span></button>
              <button type="button" class="btnesp first espAgregar " id="btnGuardarCategoria" onclick="guardarCategoria($('#tipoCmbCat').val())"><span class="ajusteProyecto">Guardar</span></button>
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
            <button type="button" class="close" onclick="$('#nueva_subCategoria').modal('hide');" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="tipoCmbSubcat">
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
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" onclick="$('#nueva_subCategoria').modal('hide');" id="cancelarCategoria"><span class="ajusteProyecto">Cancelar</span></button>
              <button type="button" class="btnesp first espAgregar " id="btnGuardarSubCategoria" onclick="guardarSubCategoria($('#tipoCmbSubcat').val())"><span class="ajusteProyecto">Guardar</span></button>
            </div>
          </div>
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
            <button type="button" class="close" onclick="$('#nuevo_Provedor').modal('hide');" aria-label="Close">
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
              <button type="button" class="btnesp first espCancelar btnCancelarNuevoProv" onclick="$('#nuevo_Provedor').modal('hide');" id="btnCancelarNuevoProv"><span class="ajusteProyecto">Cancelar</span></button>
              <button type="button" class="btnesp espAgregar float-right" name="btnAgregarProv" id="btnAgregarProveedor"><span class="ajusteProyecto">Agregar</span></button>
            </div>
        </form>
      </div>
    </div>
  </div>

  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/slimselect.min.js"></script>
  <script src="../../js/scripts.js"></script>

  <script>
    var cmbTipoPersona = '';
    var cmbCuenta = '';
    var cmbResponsable = '';
    var cmbProveedor = '';
    var cmbCategoria = '';
    var cmbSubcategoria = '';
    var cmbCuentaEdit = '';
    var cmbResponsableEdit = '';
    var cmbProveedorEdit = '';
    var cmbCategoriaEdit = '';
    var cmbSubcategoriaEdit = '';
    $(document).ready(function() {
    //   getCategoriesTotals()
    //   getSubcategoriesTotals();
      cmbCuenta = new SlimSelect({
        select: "#cmbCuenta",
        deselectLabel: '<span class="">✖</span>',
      });
      cmbResponsable = new SlimSelect({
        select: "#cmbResponsableGasto",
        deselectLabel: '<span class="">✖</span>',
      });
      cmbProveedor = new SlimSelect({
        select: "#cmbProvedoresGasto",
        deselectLabel: '<span class="">✖</span>',
      });
      cmbTipoPersona = new SlimSelect({
        select: "#cmbTipoPersona",
        deselectLabel: '<span class="">✖</span>',
      });
      new SlimSelect({
        select: "#cmbCuentaFiltro",
        deselectLabel: '<span class="">✖</span>',
      });
      cmbCategoria = new SlimSelect({
        select: "#cmbCategoria",
        deselectLabel: '<span class="">✖</span>',
        placeholder: "Seleccionar categoría",
      });
      cmbSubcategoria = new SlimSelect({
        select: "#cmbSubcategoria",
        deselectLabel: '<span class="">✖</span>',
        placeholder: "Selecciona la subcategoría",
      });
      cmbCuentaEdit = new SlimSelect({
        select: "#cmbCuentaEdit",
        deselectLabel: '<span class="">✖</span>',
      });
      cmbResponsableEdit = new SlimSelect({
        select: "#cmbResponsableGastoEdit",
        deselectLabel: '<span class="">✖</span>',
      });
      cmbProveedorEdit = new SlimSelect({
        select: "#cmbProvedoresGastoEdit",
        deselectLabel: '<span class="">✖</span>',
      });
      cmbCategoriaEdit = new SlimSelect({
        select: "#cmbCategoriaEdit",
        deselectLabel: '<span class="">✖</span>',
        placeholder: "Seleccionar categoría",
      });
      cmbSubcategoriaEdit = new SlimSelect({
        select: "#cmbSubcategoriaEdit",
        deselectLabel: '<span class="">✖</span>',
        placeholder: "Selecciona la subcategoría",
      });
      new SlimSelect({
        select: "#cmCategoria",
        deselectLabel: '<span class="">✖</span>',
        placeholder: "Selecciona la subcategoría",
      });
      cmbFiltroCuentaDetallesSelect =new SlimSelect({
        select: "#cmbFiltroCuentaDetalles",
        deselectLabel: '<span class="">✖</span>',
        placeholder: "Selecciona la cuenta",
      });
      cmbFiltroCategoriasDetallesSelect = new SlimSelect({
        select: "#cmbFiltroCategoriasDetalles",
        deselectLabel: '<span class="">✖</span>',
        placeholder: "Selecciona la categoría",
        event:{
          afterChange: (newVal) => {
            console.log(newVal)
          }
        }
      });
      cmbFiltroSubcategoriasDetallesSelect = new SlimSelect({
        select: "#cmbFiltroSubcategoriasDetalles",
        deselectLabel: '<span class="">✖</span>',
        placeholder: "Selecciona la subcategoría",
      });
      cmbFiltroSubcategoriasDetallesSelect.disable();
      if (!$("#cmbCuenta").val()) {
        $("#inputs").css("display", "none");
      }
      document.getElementById('cmbFiltroCategoriasDetalles').addEventListener('change',(e)=>{
        cmbFiltroSubcategoriasDetallesSelect.enable();
        
        cargarCMBSubcategorias(e.target.value);
      });
      $(document).on('click','#btnFiltroGastosDetalles',()=>{
        cmbFiltroCuentaDetallesSelect.setSelected('');
        cmbFiltroCategoriasDetallesSelect.setSelected('');
        cmbFiltroSubcategoriasDetallesSelect.setSelected('');
      })
      $("#cmbCuenta").on("change", function() {
        $("#inputs").css("display", "block");
        if (cmbCuenta.selected() != '') {
          $.ajax({
            type: "POST",
            url: "functions/get_Combos.php",
            data: {
              id: $("#cmbCuenta").val()
            },
            success: function(res) {
              var datos = JSON.parse(res);
              $("#saldoCuentaCaja").val(datos.saldoInicialCaja);
            },
          });
          $.ajax({
            type: "POST",
            url: "functions/get_CombosFormat.php",
            data: {
              id: $("#cmbCuenta").val()
            },
            success: function(res) {
              var datos = JSON.parse(res);
              $("#lblSaldo").text(datos.saldoInicialCaja);
              if($("#lblSaldo").text() == ''){
                $("#lblSaldo").text('0')
              }
            },
          });
        }
      });

      $('#retiro_Gasto').on('show.bs.modal', function(e) {
        //cmbSubcategoria.disable();
        cmbCategoria.set('1');
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1; //January is 0!

        var yyyy = today.getFullYear();
        if (dd < 10) {
          dd = '0' + dd
        }
        if (mm < 10) {
          mm = '0' + mm
        }
        today = yyyy + '-' + mm + '-' + dd;

        $("#txtFechaGasto").val(today);
        $("#inputs").css("display", "none");
      });
      $("#cmbCategoria").on("change", () => {
        //cmbSubcategoria.enable();
        cmbSubcategoria.set('1');
      });
      $('#retiro_Gasto').on('hide.bs.modal', function(e) {
        cmbCuenta.set('');
        cmbCategoria.set('');
        cmbSubcategoria.set('');
        $("#invalid-cuentaRet").css("display", "none");
        $("#cmbCuenta").removeClass("is-invalid");
        $("#lblSaldo").text('');
        $("#txtImporteGasto").val('');
        $("#inputs").css("display", "none");
      });

      $('#editar_Gasto').on('hide.bs.modal', function(e) {
        cmbCuentaEdit.set('');
        cmbCategoriaEdit.set('');
        cmbSubcategoriaEdit.set('');
        if (!cmbCategoriaEdit.selected()) {
          cmbSubcategoriaEdit.disable();
        }
        $("#invalid-cuentaRetEdit").css("display", "none");
        $("#cmbCuentaEdit").removeClass("is-invalid");
        $("#lblSaldoEdit").text('');
      });

      $("#editar_Gasto").on("shown.bs.modal", () => {
        $("#btnEditarMovimiento").prop("disabled", false);
      });
      /*$('#editar_Gasto').on('shown.bs.modal', function (e) {  
        if(!cmbCategoriaEdit.selected()){
          cmbSubcategoriaEdit.disable();
        }else{
          cmbSubcategoriaEdit.enable();
        }
      });*/
      $("#cmbCategoriaEdit").on("change", () => {
        cmbSubcategoriaEdit.enable();
      });

      var element = document.querySelector('#txtImporteGasto');

      element.oninput = e => {
        let value = e.currentTarget.value;
        let result = value
          .replace(/[^0-9-.]/g, '')
          .replace(/(?!^)-/g, '')
          // prevent inserting dots after the first one
          .replace(/([^.]*\.[^.]*)\./g, '$1');
        element.value = result;
      }
    });
  </script>

  <script src="../../js/validaciones.js"></script>
  <script src="js/validacion_datos.js" charset="utf-8"></script>
  <script src="js/funciones_caja.js" charset="utf-8"></script>

  <script>
    $("#btnTipoCuenta").click(function() {
      alert();
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
  </script>
  <script>
    $("#btnGuardarMovimiento").click(function() {
      var idCuenta = $("#cmbCuenta").val();
      var hayArchivo = 0;
      var file = $("#inputFile").val();
      var responsable = $("#cmbResponsableGasto").val();
      var importe = $("#txtImporteGasto").val();
      var fechaGasto = $("#txtFechaGasto").val();
      var observaciones = $("#areaDescripcionGasto").val();
      var proveedor = $("#cmbProvedoresGasto").val() == null ? 0 : $("#cmbProvedoresGasto").val();
      var categoria = $("#cmbCategoria").val();
      var subcategoria = $("#cmbSubcategoria").val();
      var miArchivo = $('#inputFile').prop('files')[0];
      var s1 = parseFloat($('#saldoCuentaCaja').val() != '' && $('#saldoCuentaCaja').val() != null ? $('#saldoCuentaCaja').val() : 0);
      var s2 = parseFloat($('#txtImporteGasto').val() != '' && $('#txtImporteGasto').val() != null ? $('#txtImporteGasto').val() : 0);
      console.log(s1);
      console.log(s2);
      if (s2 > s1) {
        lobiboxAlert("error", "¡El saldo es insuficiente!");
        return;
      }
      if (!idCuenta) {
        $("#invalid-cuentaRet").css("display", "block");
      }
      if (!responsable) {
        $("#invalid-responsableRet").css("display", "block");
      }
      if (!importe) {
        $("#invalid-importeRet").css("display", "block");
        $("#txtImporteGasto").addClass("is-invalid");
      }
      if (!fechaGasto) {
        $("#invalid-fechaRet").css("display", "block");
        $("#txtFechaGasto").addClass("is-invalid");
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

      var badCuentaRet =
        $("#invalid-cuentaRet").css("display") === "block" ? false : true;
      var badResponsableRet =
        $("#invalid-responsableRet").css("display") === "block" ? false : true;
      var importeRet =
        $("#invalid-importeRet").css("display") === "block" ? false : true;
      var badFechaRet =
        $("#invalid-fechaRet").css("display") === "block" ? false : true;
      var badArchivoRet =
        $("#invalid-archivoRet").css("display") === "block" ? false : true;

      if (badCuentaRet && badResponsableRet && importeRet && badFechaRet && badArchivoRet) {
        var fd = new FormData();
        fd.append('inputFile', miArchivo);
        fd.append('idCuenta', idCuenta);
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
          url: "functions/agregar_Gasto.php",
          success: function(data, status, xhr) {
            console.log(data);
            if (data.trim() == "exito") {
              $('#retiro_Gasto').modal('toggle');
              $('#retiroGasto').trigger("reset");
              $("#checkCaja").prop("disabled", false);
              $("#checkCaja").prop("checked", true);
              $("#inputFile").css("display", "none");
              $("#inputFile").val("");
              $("#txtImporteGasto").val('');
              $("#txtFechaGasto").val('');
              $("#areaDescripcionGasto").val('');
              cmbCuenta.set('');
              cmbProveedor.set('');
              cmbResponsable.set('');
              cmbCategoria.set('');
              cmbSubcategoria.set('');
              $("#invalid-cuentaRet").css("display", "none");
              $("#invalid-responsableRet").css("display", "none");
              $("#invalid-provRet").css("display", "none");
              $("#invalid-nombreCat").css("display", "none");
              $("#invalid-nombreSubcat").css("display", "none");
              $("#cmbCuenta").removeClass("is-invalid");
              $("#cmbResponsableGasto").removeClass("is-invalid");
              $("#cmbProvedoresGasto").removeClass("is-invalid");
              $("#cmbCategoria").removeClass("is-invalid");
              $("#cmbSubcategoria").removeClass("is-invalid");
              $("#inputsEdit").css("display", "none");
              $('#tblGastos').DataTable().ajax.reload();
              lobiboxAlert("success", "¡Retiro realizado!");
              $("#btnGuardarMovimiento").prop("disabled", true);
              getCategoriesTotals();
              getSubcategoriesTotals();
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

    $("#btnEditarMovimiento").click(function() {
      var idMovimiento = $("#idMovimiento").val();
      var idCuenta = $("#cmbCuentaEdit").val();
      var hayArchivo = 0;
      var file = $("#inputFileEdit").val();
      var responsable = $("#cmbResponsableGastoEdit").val();
      var importe = $("#txtImporteGastoEdit").val();
      var fechaGasto = $("#txtFechaGastoEdit").val();
      var observaciones = $("#areaDescripcionGastoEdit").val();
      var proveedor = $("#cmbProvedoresGastoEdit").val() == null ? 0 : $("#cmbProvedoresGastoEdit").val();
      var categoria = $("#cmbCategoriaEdit").val();
      var subcategoria = $("#cmbSubcategoriaEdit").val();
      var miArchivo = $('#inputFileEdit').prop('files')[0];
      var s1 = parseFloat($('#saldoCuentaCajaEdit').val() != '' && $('#saldoCuentaCajaEdit').val() != null ? $('#saldoCuentaCajaEdit').val() : 0);
      var s2 = parseFloat($('#txtImporteGastoEdit').val() != '' && $('#txtImporteGastoEdit').val() != null ? $('#txtImporteGastoEdit').val() : 0);
      console.log(s1);
      console.log(s2);
      if (s2 > s1) {
        lobiboxAlert("error", "¡El saldo es insuficiente!");
        return;
      }
      if (!idCuenta) {
        $("#invalid-cuentaRet").css("display", "block");
      }
      if (!responsable) {
        $("#invalid-responsableRet").css("display", "block");
      }
      if (!importe) {
        $("#invalid-importeRet").css("display", "block");
        $("#txtImporteGasto").addClass("is-invalid");
      }
      if (!fechaGasto) {
        $("#invalid-fechaRet").css("display", "block");
        $("#txtFechaGasto").addClass("is-invalid");
      }
      if ($('#checkCajaEdit').is(':checked')) {
        check = 0;
        hayArchivo = 0;
      } else {
        if (!file) {
          $("#invalid-archivoRetEdit").css("display", "block");
          $("#inputFileEdit").addClass("is-invalid");
        } else {
          hayArchivo = 1;
          check = 1;
        }
      }

      var badCuentaRetEdit =
        $("#invalid-cuentaRetEdit").css("display") === "block" ? false : true;
      var badResponsableRetEdit =
        $("#invalid-responsableRetEdit").css("display") === "block" ? false : true;
      var importeRetEdit =
        $("#invalid-importeRetEdit").css("display") === "block" ? false : true;
      var badFechaRetEdit =
        $("#invalid-fechaRetEdit").css("display") === "block" ? false : true;
      var badArchivoRetEdit =
        $("#invalid-archivoRetEdit").css("display") === "block" ? false : true;

      if (badCuentaRetEdit && badResponsableRetEdit && importeRetEdit && badFechaRetEdit && badArchivoRetEdit) {
        var fd = new FormData();
        fd.append('idMovimiento', idMovimiento);
        fd.append('inputFile', miArchivo);
        fd.append('idCuenta', idCuenta);
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
          url: "functions/editar_Gasto.php",
          success: function(data, status, xhr) {
            console.log(data);
            if (data.trim() == "exito") {
              $('#editar_Gasto').modal('toggle');
              $("#checkCajaEdit").prop("disabled", false);
              $("#checkCajaEdit").prop("checked", true);
              $("#inputFileEdit").css("display", "none");
              $("#inputFileEdit").val("");
              $("#invalid-cuentaRet").css("display", "none");
              $("#invalid-responsableRet").css("display", "none");
              $("#invalid-provRet").css("display", "none");
              $("#invalid-nombreCat").css("display", "none");
              $("#invalid-nombreSubcat").css("display", "none");
              $('#tblGastos').DataTable().ajax.reload();
              lobiboxAlert("success", "¡Registro de gasto actualizado!");
              getCategoriesTotals();
              getSubcategoriesTotals();
            } else {
              lobiboxAlert("error", "¡Ocurrió un error al agregar el retiro!")
            }
          },
          error: function(error) {
            console.log(error);
          }
        });
      }

      $("#btnEditarMovimiento").prop("disabled", true);
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

      if (badNombreProv && badEmailProv && badTipoPersonaProv && badDiasProv && badCredProv) {
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
              cmbTipoPersona.set('');
              $("#creditoProv").val("");
              $("#txtDiasCredito").val("");
              $("#txtLimiteCredito").val("");
              $('#agregarProveedor').trigger("reset");
              $('#tblProveedores').DataTable().ajax.reload();
              cargarCMBProveedor("cmbProvedoresGasto");
              cargarCMBProveedorEdit($("#cmbProvedoresGastoEdit").val(), "cmbProvedoresGastoEdit");
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

    $("#checkCajaEdit").change(function() {
      var archivoComprobRetEdit = document.getElementById("inputFileEdit");
      document.getElementById("invalid-archivoRetEdit").style.display = "none";
      if (this.checked) {
        archivoComprobRetEdit.style.display = "none";
        archivoComprobRetEdit.required = false;
      } else {
        archivoComprobRetEdit.style.display = "block";
        archivoComprobRetEdit.required = true;
      }
    });

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

    function validTipoPersona() {
      $("#invalid-tipoPersonaProv").css("display", "none");
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

    function getCategoriesTotals()
    {   var total_general = 0;
        $.ajax({
        url: "php/funciones.php",
        type: "POST",
        data: {
          "clase": 'get_data',
          'funcion': 'get_categoriesTotals'
        },
        dataType: "json",
        success: function(request) {
            console.log(request.categorias);
            var html = "";
            if(request.categorias.length>0){
                request.categorias.forEach((i)=>{
                    html += '<div class="col-lg-2 textData text-center"><h5><b class="textBlue">'+i.nombre+':</b><div class="text-center" id="totalText"><b>$'+i.total+'</b></div></h5></div>';
                    total_general+=i.total
                })
            }
            
            $("#categoriesTotals").html(html);
            $("#categoriesTotalNet").text(" $"+request.total);
            
        },
        error: function(error) {
          console.log(error);
        }
      });
    }

    function getSubcategoriesTotals()
    {
        $.ajax({
        url: "php/funciones.php",
        type: "POST",
        data: {
          "clase": 'get_data',
          'funcion': 'get_subcategoriesTotals'
        },
        dataType: "json",
        success: function(request) {
            console.log("hola",request);
            var html = "";
            if(request.subcategorias.length>0){
                request.subcategorias.forEach((i)=>{
                    html += '<div class="col-lg-2 textData text-center"><h5><b class="textBlue">'+i.nombre+':</b><div class="text-center" id="totalText"><b>$'+i.total+'</b></div></h5></div>';
                })
            }
            $("#subcategoriesTotals").html(html);
            $("#subcategoriesTotalNet").text(" $"+request.total);
        },
        error: function(error) {
          console.log(error);
        }
      });
    }

    /* $('#retiro_Gasto').on('hidden.bs.modal', function (e) {
      cmbCuenta.set('');
      cmbResponsable.set('');
      cmbProveedor.set('');
      cmbCategoria.set('');
      cmbSubcategoria.set('');
      $("#lblSaldo").text('');
      $("#txtImporteGasto").val('');
      $("#txtFechaGasto").val('');
      $("#areaDescripcionGasto").val('');
      $("#checkCaja").prop('checked', false);
      $("#inputFile").val('');
    }); */
  </script>

  <script>
    var ruta = "../";
  </script>
  <script src="js/index.js"></script>
</body>

</html>
<?php
$ruta = "../";
$screen = 1;
require_once $ruta . 'validarPermisoPantalla.php';

if ($permiso === 0) {
  header("location:../dashboard.php");
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | Empleados</title>

  <!-- ESTILOS -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <!-- JS -->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="../../js/sweet/sweetalert2.js"></script>
  <script src="../../js/jquery.redirect.min.js"></script>
  <script src="js/empleados.js" charset="utf-8"></script>
  <script src="../../js/lobibox.min.js"></script>
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
    </ul>
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
        $icono = '../../img/nuevos-iconos/ICONO-EMPLEADOS AZUL NVO-01.svg';
        $titulo = "Empleados";
        require_once '../topbar.php';
        ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <p class="mb-4"></p>

          <!-- DataTales Example -->
          <div class="card mb-4">
            <div class="card-body">
              <!-- INICIO FILTROS -->
              <div class="listaColumnas listaColumnasEmpleados d-none" id="listaColumnas">
                <div class="mt-2 text-center">
                  <strong>Mostrar columnas</strong>
                </div>
                <div class="row">
                  <div class="col-4">
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="statusFiltroTarea" class="check-type-column checked-type-column checked-type-column-imgEmpleados filtro filtro-columna" data-column="0">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Acciones</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column checked-type-column checked-type-column-imgEmpleados filtro filtro-columna" data-column="1">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">ID</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column checked-type-column checked-type-column-imgEmpleados filtro filtro-columna" data-column="2">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Nombre</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column checked-type-column checked-type-column-imgEmpleados filtro filtro-columna" data-column="3">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Primer apellido</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column checked-type-column checked-type-column-imgEmpleados filtro filtro-columna" data-column="4">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Segundo apellido</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column checked-type-column checked-type-column-imgEmpleados filtro filtro-columna" data-column="5">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Estado civil</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column checked-type-column checked-type-column-imgEmpleados filtro filtro-columna" data-column="6">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Genero</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column checked-type-column checked-type-column-imgEmpleados filtro filtro-columna" data-column="7">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Estado</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column checked-type-column checked-type-column-imgEmpleados filtro filtro-columna" data-column="8">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Dirección</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column checked-type-column checked-type-column-imgEmpleados filtro filtro-columna" data-column="9">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Colonia</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column checked-type-column checked-type-column-imgEmpleados filtro filtro-columna" data-column="10">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">CP</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column checked-type-column checked-type-column-imgEmpleados filtro filtro-columna" data-column="11">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Ciudad</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="statusFiltroTarea" class="check-type-column checked-type-column checked-type-column-imgEmpleados filtro filtro-columna" data-column="12">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">CURP</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column checked-type-column checked-type-column-imgEmpleados filtro filtro-columna" data-column="13">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">RFC</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column checked-type-column checked-type-column-imgEmpleados filtro filtro-columna" data-column="14">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Fecha nacimiento</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column filtro filtro-columna" data-column="15">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Teléfono</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column filtro filtro-columna" data-column="16">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Estatus</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column filtro filtro-columna" data-column="17">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Fecha ingreso</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column filtro filtro-columna" data-column="18">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Infonavit</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column filtro filtro-columna" data-column="19">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Deuda interna</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column filtro filtro-columna" data-column="20">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Deuda restante</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column filtro filtro-columna" data-column="21">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Turno</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column filtro filtro-columna" data-column="22">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Puesto</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column filtro filtro-columna" data-column="23">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Sucursal</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="statusFiltroTarea" class="check-type-column filtro filtro-columna" data-column="24">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Empresa</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column filtro filtro-columna" data-column="25">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">NSS</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column filtro filtro-columna" data-column="26">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Tipo de sangre</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column filtro filtro-columna" data-column="27">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Contacto emergencia</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column filtro filtro-columna" data-column="28">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Numero emergencia</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column filtro filtro-columna" data-column="29">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Alergias</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column filtro filtro-columna" data-column="30">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Notas medicas</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column filtro filtro-columna" data-column="31">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Banco</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column filtro filtro-columna" data-column="32">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Cuenta bancaria</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column filtro filtro-columna" data-column="33">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Clabe</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column filtro filtro-columna" data-column="34">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Numero de tarjeta</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- FIN FILTROS -->
              <div class="table-responsive">
                <table class="table" id="tblEmpleados" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th></th>
                      <th>ID</th>
                      <th>Nombre</th>
                      <th>Primer apellido</th>
                      <th>Segundo apellido</th>
                      <th>Estado civil</th>
                      <th>Genero</th>
                      <th>Estado</th>
                      <th>Dirección</th>
                      <th>Colonia</th>
                      <th>CP</th>
                      <th>Ciudad</th>
                      <th>CURP</th>
                      <th>RFC</th>
                      <th>Fecha de nacimiento</th>
                      <th>Teléfono</th>
                      <th>Estatus</th>
                      <th>Fecha de ingreso</th>
                      <th>Infonavit</th>
                      <th>Deuda interna</th>
                      <th>Deuda restante</th>
                      <th>Turno</th>
                      <th>Puesto</th>
                      <th>Sucursal</th>
                      <th>Empresa</th>
                      <th>NSS</th>
                      <th>Tipo de sangre</th>
                      <th>Contacto de emergencia</th>
                      <th>Numero de emergencia</th>
                      <th>Alergias</th>
                      <th>Notas medicas</th>
                      <th>Banco</th>
                      <th>Cuenta bancaria</th>
                      <th>Clabe</th>
                      <th>Numero de tarjeta</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!-- /.container-fluid -->

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

  <!-- EXCEL MODAL-->
  <div class="modal fade" id="excelmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Empleados</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="container">
            <div class="row">
              <div class="col-12">
                <img id="formato" src="../../img/empleados/layout empleados.png" style="width: 100%;">
                <a href="exportLayout.php" class="mt-3">Descargar layout</a><br>
              </div>
              <div class="col-12 mt-1 mb-2">
                <span class="text-danger d-block">*Formatos aceptados: .XLS, .XLSX</span><br>
              </div>
              <div class="col-12">
                <form action="uploadExcel.php" method="post" enctype="multipart/form-data" id="formexcel">
                  <div class="row">
                    <input type="file" class="btn-custom btn-custom--blue" id="dataexcel" name="dataexcel" accept=".xls,.xlsx">
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer d-flex justify-content-around">
          <button class="btn-custom btn-custom--border-blue" data-dismiss="modal">Cancelar</button>
          <button class="btn-custom btn-custom--blue" id="importExcel" data-dismiss="modal" onclick="validarExcel()">Importar</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    var ruta = "../";
  </script>
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>

</body>

</html>
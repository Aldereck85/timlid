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
   <title>Timlid | Notas de crédito</title>

   <!-- ESTILOS -->
   <link href="../../css/slimselect.min.css" rel="stylesheet">
   <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" async>
   <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
   <link href="../../css/jquery.dataTables.min.css" rel="stylesheet">
   <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
   <link href="../../css/styles.css" rel="stylesheet">
   <link href="../../css/stylesNewTable.css" rel="stylesheet">
   <link href="../../css/stylesModal-lg.css" rel="stylesheet">
   <link href="../../css/lobibox.min.css" rel="stylesheet">
   <link href="https://cdn.datatables.net/datetime/1.1.0/css/dataTables.dateTime.min.css" rel="stylesheet">
   <!-- JS -->
   <script src="../../vendor/jquery/jquery.min.js"></script>
   <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
   <script src="https://cdn.datatables.net/datetime/1.1.0/js/dataTables.dateTime.min.js"></script>
   <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
   <script src="https://cdn.datatables.net/plug-ins/1.10.21/sorting/datetime-moment.js"></script>
   <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
   <script src="../../js/sweet/sweetalert2.js"></script>
   <script src="../../js/validaciones.js"></script>
   <script src="../../js/lobibox.min.js"></script>
   <script src="../../js/slimselect.min.js"></script>
   <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
   <script src="../../vendor/datatables/dataTables.buttons.js"></script>
   <script src="../../vendor/datatables/buttons.html5.min.js"></script>
   <script src="../../vendor/jszip/jszip.min.js"></script>
   <script src="js/index.js"></script>
   <script src="js/descargas.js"></script>
   <script src="../../js/jquery.redirect.min.js"></script>
   <script src="https://cdn.datatables.net/searchbuilder/1.1.0/js/dataTables.searchBuilder.min.js"></script>
   <script src="https://cdn.datatables.net/datetime/1.1.0/js/dataTables.dateTime.min.js"></script>
   <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
   <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>

</head>

<body id="page-top" class="sidebar-toggled">
   <!-- Page Wrapper -->
   <div id="wrapper">
      <!-- Comprobar permisos para estar en la pagina -->
      <?php
      ///Primera parte comprueba si puede ver
      $pkuser = $_SESSION["PKUsuario"];
      $stmt = $conn->prepare("Select funcion_ver, funcion_agregar, funcion_editar, funcion_eliminar, funcion_exportar, 
             pantalla_id, fp.perfil_id from funciones_permisos as fp inner join usuarios as us 
             on fp.perfil_id = us.perfil_id where us.id = $pkuser and pantalla_id = 31");
      $stmt->execute();
      $row = $stmt->fetch();
      //Ponemos en el DOM el permiso ver
      echo ('<input id="ver" type="hidden" value="' . $row['funcion_ver'] . '">');
      ?>
      <!-- Sidebar -->
      <?php
      $titulo = "Notas de crédito";
      $ruta = "../";
      $ruteEdit = $ruta . "central_notificaciones/";
      require_once '../menu3.php';
      if (isset($_SESSION["FacEgreso"])) {
         echo ('<input type="hidden" id="notifi" value="' . $_SESSION["FacEgreso"] . '">');
         unset($_SESSION['FacEgreso']);
      } else {
         echo ('<input type="hidden" id=/"notifi/" value="f">');
      }
      ?>
      <!-- End of Sidebar -->
      <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
      <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
      <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">
      <!-- Content Wrapper -->
      <div id="content-wrapper" class="d-flex flex-column">
         <!-- Main Content -->
         <div id="content">
            <?php
            $rutatb = "../";
            $icono = 'ICONO-NOTAS-CREDITO-CARGO-AZUL.svg';
            require_once $rutatb . "topbar.php"
            ?>
            <!-- End of Topbar -->
            <!-- Begin Page Content -->
            <div class="container-fluid">
               <!-- DataTales Example -->
               <div class="card mb-4">
                  <div class="card-body">
                     <div class="form-group">
                        <div class="row">
                           <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6" style="margin-left:20px;">
                              <label for="cmbCliente">Cliente:</label>
                              <select name="cmbCliente" id="cmbCliente" class="form-select" required></select>
                              <div class="invalid-feedback" id="invalid-cmbCliente">.</div>
                           </div>
                           <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6">
                              <label for="txtDateFrom">De:</label>
                              <input class="form-control" type="date" name="txtDateFrom" id="txtDateFrom">
                              <div class="invalid-feedback" id="invalid-txtDateFrom"></div>
                           </div>
                           <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6">
                              <label for="txtDateTo">Hasta:</label>
                              <input class="form-control" type="date" name="txtDateTo" id="txtDateTo">
                              <div class="invalid-feedback" id="invalid-txtDateTo">.</div>
                           </div>
                           <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6">
                              <a data-toggle="tooltip" data-placement="top" title="Aplicar Filtro" class="btn-custom btn-custom--blue" id="btnFilterExits" style="margin-top: 10px!important" onclick="validarImputs2()">Filtrar</a>
                           </div>
                        </div>
                     </div>
                     <div class="table-responsive">
                        <div id="tabla_histo">
                           <table class="table" id="tblNotasCredit" style="width: 100%;" cellspacing="0">
                              <thead>
                                 <tr>
                                    <th>Folio</th>
                                    <th style="width: 12%;">Docs. Relacionados</th>
                                    <th>Cliente</th>
                                    <th>Importe</th>
                                    <th style="width: 12%;">F.Creación</th>
                                    <th style="width: 12%;">Estado</th>
                                 </tr>
                              </thead>
                           </table>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <!-- /.container-fluid -->
         </div> <!-- End of Content Wrapper -->
         <!-- Footer -->
         <?php
         $rutaf = "../";
         require_once '../footer.php';
         ?>
         <?php
         require_once 'modal_alert_confirm.php';
         require_once 'modal_alert.php';
         ?>
         <!-- End of Footer -->
      </div>
   </div>
   <!-- End of Page Wrapper -->
   <!-- Logout Modal-->
   <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title" id="exampleModalLabel">¿En serio quieres salir?</h5>
               <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
               </button>
            </div>
            <div class="modal-body">Selecciona "Salir" Para cerrar tu sesión</div>
            <div class="modal-footer">
               <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
               <a class="btn btn-primary" href="../logout.php">Salir</a>
            </div>
         </div>
      </div>
   </div>
   <!-- Custom scripts for all pages-->
   <script src="../../js/sb-admin-2.min.js"></script>
   <script src="../../js/scripts.js"></script>
   <script>
      loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
      setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());

      jQuery(function($) {
         var notifi = $("#notifi").val();
         console.log(notifi);
         console.log(notifi);
         if (notifi == "1") {
            Lobibox.notify("success", {
               size: "mini",
               rounded: true,
               delay: 1500,
               delayIndicator: false,
               position: "center top",
               icon: true,
               img: "../../img/timdesk/checkmark.svg",
               msg: "Nota de crédito registrada!",
            });
         }
      });
   </script>
   <script src="../../js/slimselect.min.js"></script>
</body>

</html>
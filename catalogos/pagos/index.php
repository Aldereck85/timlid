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
   <title>Timlid | Pagos</title>

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
   <link rel="stylesheet" href="../../css/notificaciones.css">
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
   <script src="js/indexstyle.js"></script>
   <script src="https://cdn.datatables.net/searchbuilder/1.1.0/js/dataTables.searchBuilder.min.js"></script>
   <script src="https://cdn.datatables.net/datetime/1.1.0/js/dataTables.dateTime.min.js"></script>
   <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
   <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>
   <script src="../../js/jquery.redirect.min.js"></script>
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
      on fp.perfil_id = us.perfil_id where us.id = $pkuser and pantalla_id = 28");
      $stmt->execute();
      $row = $stmt->fetch();
      //Ponemos en el DOM el permiso ver
      echo ('<input id="ver" type="hidden" value="' . $row['funcion_ver'] . '">');
      echo ('<input id="add" type="hidden" value="' . $row['funcion_agregar'] . '">');
      ?>
      <!-- Sidebar -->
      <?php
      $titulo = "Pagos";
      $ruta = "../";
      $ruteEdit = $ruta . "central_notificaciones/";
      require_once '../menu3.php';
      if (isset($_SESSION["mensaje"])) {
         echo ('<input type="hidden" id="notifi" value="' . $_SESSION["mensaje"] . '">');
         unset($_SESSION['mensaje']);
      } else {
         echo ('<input type="hidden" id=/"notifi/" value="f">');
      }
      ?>

      <!-- End of Sidebar -->
      <div id="content-wrapper" class="d-flex flex-column">
         <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
         <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
         <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">
         <!-- Main Content -->
         <div id="content">
            <?php
            $rutatb = "../";
            $icono = 'ICONO-PAGOS-ANTICIPOS-AZUL.svg';

            require_once $rutatb . "topbar.php"
            ?>
            <!-- End of Topbar -->
            <!-- Begin Page Content -->

            <div class="container-fluid">
               <!-- DataTales Example -->
               <div class="card">
                  <div class="card-body">
                     <div class="table-responsive">
                        <div id="tabla">
                           <table class="table" id="tblpagos" width="100%" cellspacing="0">
                              <thead>
                                 <tr>
                                    <th>Id</th>
                                    <th>Proveedor</th>
                                    <th>Fecha de registro</th>
                                    <th>Comentarios</th>
                                    <th>Total</th>
                                    <th>Responsable</th>
                                    <th>Tipo</th>
                                 </tr>
                              </thead>
                           </table>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <!-- /.container-fluid -->
         </div>
         <!-- End of Content Wrapper -->
         <!-- Footer -->
         <?php
         require_once 'modal_alert.php';
         $rutaf = "../";
         require_once '../footer.php';
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
   <?php
   $accion = "eliminar el registro?";
   require_once 'modal_alert_confirm.php';
   ?>
   <!-- Custom scripts for all pages-->
   <script src="../../js/sb-admin-2.min.js"></script>
   <script src="../../js/scripts.js"></script>
   <script>
      loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
      setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());

      //Muestra la notificacion cada que se recibe la variable por post
      jQuery(function($) {
         var notifi = $("#notifi").val();
         //  console.log(notifi);
         //  console.log(notifi);
         if (notifi == "1") {
            Lobibox.notify("success", {
               size: "mini",
               rounded: true,
               delay: 1500,
               delayIndicator: false,
               position: "center top",
               icon: true,
               img: "../../img/timdesk/checkmark.svg",
               msg: "Pago registrado!",
            });
         } else if (notifi == "0") {
            Lobibox.notify("success", {
               size: "mini",
               rounded: true,
               delay: 1500,
               delayIndicator: false,
               position: "center top",
               icon: true,
               img: "../../img/timdesk/checkmark.svg",
               msg: "Pago registrado!",
            });
         } else if (notifi == "2") {
            Lobibox.notify("success", {
               size: "mini",
               rounded: true,
               delay: 1500,
               delayIndicator: false,
               position: "center top",
               icon: true,
               img: "../../img/timdesk/checkmark.svg",
               msg: "Pago actualizado!",
            });
         } else if (notifi == "3") {
            Lobibox.notify("success", {
               size: "mini",
               rounded: true,
               delay: 1500,
               delayIndicator: false,
               position: "center top",
               icon: true,
               img: "../../img/timdesk/checkmark.svg",
               msg: "Anticipo actualizado!",
            });
         } else if (notifi == "4") {
            Lobibox.notify("success", {
               size: "mini",
               rounded: true,
               delay: 1500,
               delayIndicator: false,
               position: "center top",
               icon: true,
               img: "../../img/timdesk/checkmark.svg",
               msg: "Pago eliminado!",
            });
         }

      });
   </script>
</body>

</html>
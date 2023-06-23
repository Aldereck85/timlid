<?php
session_start();

$jwt_ruta = "../../";
require_once '../jwt.php';

date_default_timezone_set('America/Mexico_City');

$token = $_SESSION['token_ld10d'];

if (isset($_SESSION["Usuario"])) {
    require_once '../../include/db-conn.php';

} else {
    header("location:../dashboard.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Timlid | Conceptos nómina</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Page level plugins -->
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.bootstrap4.min.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>

  <!-- Custom fonts for this template -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.bootstrap4.min.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="../../css/stylesTable.css" rel="stylesheet">
  <link href="../../css/stylesModal-lg.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <script src="../../js/lobibox.min.js"></script>
  <script src="../../js/sweet/sweetalert2.js"></script>
  <link rel="stylesheet" href="../../css/notificaciones.css">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <script src="../../js/slimselect.min.js"></script>
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
$icono = '../../img/icons/puestos.svg';
$titulo = "Conceptos nómina";
$ruta = "../";
$ruteEdit = $ruta . "central_notificaciones/";
require_once '../menu3.php';
?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <input type="hidden" id="txtUsuario" value="<?=$_SESSION['PKUsuario'];?>">
      <input type="hidden" id="txtRuta" value="<?=$ruta;?>">
      <input type="hidden" id="txtEdit" value="<?=$ruteEdit;?>">
      <input type="hidden" id="txtPantalla" value="13">

      <!-- Main Content -->
      <div id="content">

        <?php
$rutatb = "../";
require_once "../topbar.php"
?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <!-- DataTales Example -->
          <div class="card mb-4 data-table">
            <div class="card-header py-3">
              <div class="float-right">
                <div class="button-container2" id="">
                  <div class="button-icon-container">
                    <a href="#" class="btn btn-circle float-right waves-effect waves-light shadow btn-table" id="btn-nomina" data-toggle="modal" data-target="#agregar_clave"><i class="fas fa-plus"></i></a>
                  </div>
                  <div class="button-text-container">
                    <span>Agregar concepto</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table stripe" id="tblConceptos" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Concepto</th>
                      <th>Tipo</th>
                      <th>Clave</th>
                    </tr>
                  </thead>
                </table>
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


  <!--ADD MODAL-->
    <div class="modal fade right" id="agregar_clave" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
          <div class="modal-content">
            <form action="#" method="POST" id="agregarNomina">
              <!--<input type="hidden" name="idProyectoA" value="">-->
              <div class="modal-header">
                <h4 class="modal-title w-100" id="myModalLabel">Agregar clave</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">x</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label for="usr">Tipo*:</label>
                  <div class="row">
                      <div class="col-lg-6">
                        <input type="radio" id="tipoConcepto" name="tipoConcepto" value="1" class="tipoConceptoC" checked>
                        <label for="percepcion">Percepción</label>
                      </div>
                      <div class="col-lg-6">
                        <input type="radio" id="tipoConcepto" name="tipoConcepto" value="2" class="tipoConceptoC">
                        <label for="deduccion">Deducción</label>
                      </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="usr">Concepto*:</label>
                  <select class="form-control" id="cmbConcepto" name="cmbConcepto">
                    <?php
                      $stmt = $conn->prepare('SELECT id,codigo, concepto FROM tipo_percepcion');
                      $stmt->execute();
                      $claves = $stmt->fetchAll();
                      foreach ($claves as $c) {
                    ?>
                        <option value="<?=$c['id']?>"><?=$c["codigo"]." - ".$c["concepto"]?></option>
                    <?php
                      }
                    ?>
                  </select>
                </div>
                <div class="form-group">
                  <label for="usr">Clave*:</label>
                  <input type="text" class="form-control" name="txtClave" id="txtClave" value="" maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" required>
                </div>
                <label style="color:#006dd9;font-size: 13px;"> (*) Campos requeridos</label>
                <div class="modal-footer justify-content-center">
                  <button type="button" class="btn-custom btn-custom--border-blue btnCancelarActualizacion"
                  data-dismiss="modal" id="btnCancelarClave"><span class="ajusteProyecto">Cancelar</span></button>
                  <button type="button" class="btn-custom btn-custom--blue" name="btnAgregar" id="btnAgregarClave"><span
                    class="ajusteProyecto">Agregar</span></button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  <!--END ADD MODAL-->


<!--EDIT MODAL-->
    <div class="modal fade right" id="editar_clave" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
          <div class="modal-content">
            <form action="#" method="POST" id="editarConcepto">
              <!--<input type="hidden" name="idProyectoA" value="">-->
              <div class="modal-header">
                <h4 class="modal-title w-100" id="myModalLabel">Editar concepto</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">x</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label for="usr">Tipo*:</label>
                  <div class="row">
                      <div class="col-lg-6">
                        <input type="radio" id="tipoConceptoEdit" name="tipoConceptoEdit" value="1" class="percepcionEdit" checked>
                        <label for="percepcion">Percepción</label>
                      </div>
                      <div class="col-lg-6">
                        <input type="radio" id="tipoConceptoEdit" name="tipoConceptoEdit" value="2" class="deduccionEdit">
                        <label for="deduccion">Deducción</label>
                      </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="usr">Concepto*:</label>
                  <select class="form-control" id="cmbConceptoEdit" name="cmbConceptoEdit">
                  </select>
                </div>
                <div class="form-group">
                  <label for="usr">Clave*:</label>
                  <input type="text" class="form-control" name="txtClaveEdit" id="txtClaveEdit" value="" maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" required>
                </div>

                <label style="color:#006dd9;font-size: 13px;"> (*) Campos requeridos</label>
                <div class="modal-footer justify-content-center">
                  <input type="hidden" name="txtIdConceptoEdit" id="txtIdConceptoEdit" value="">
                  <button type="button" class="btn-custom btn-custom--border-blue btnCancelarActualizacion"
                  data-dismiss="modal" id="btnCancelarClaveEdit"><span class="ajusteProyecto">Cancelar</span></button>
                  <button type="button" class="btn-custom btn-custom--blue" name="btnEditar" id="btnEditarClave"><span
                    class="ajusteProyecto">Modificar</span></button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  <!--END EDIT MODAL-->


  

    <input type="hidden" name="csr_token_UT5JP" id="csr_token_UT5JP" value="<?=$token?>">

  <!-- Custom scripts for all pages-->
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>
  <script src="js/conceptos.js"></script>
  

  <script>  
     $("#btnAgregarClave").click(function(){
        let clave = $("#txtClave").val().trim();
        let concepto = $("#cmbConcepto").val().trim();
        let tipo = $('input[name="tipoConcepto"]:checked').val();
        let token = $("#csr_token_UT5JP").val(); 

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

        $("#btnCancelarClave").prop("disabled", true);
        $("#btnAgregarClave").prop("disabled", true);

        $.ajax({
          type: 'POST',
          url: 'functions/agregar_Clave.php',
          data: { 
                  concepto : concepto,
                  clave: clave,
                  tipo: tipo,
                  csr_token_UT5JP : token
                },
          success: function(r) {

            if(r == "exito"){
              Lobibox.notify("success", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/checkmark.svg",
                msg: "Clave agregada!",
              });
              $("#txtClave").val("");
              $("#btnCancelarClave").prop("disabled", false);
              $("#btnAgregarClave").prop("disabled", false);
              $('#tblConceptos').DataTable().ajax.reload();
              $('#agregar_clave').modal('hide');
            }
            
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
                msg: "Ese concepto ya tiene asignada una clave.",
              });
            }

            if(r == "fallo"){
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
            $("#btnCancelarClave").prop("disabled", false);
            $("#btnAgregarClave").prop("disabled", false);
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
            $("#btnAgregarClave").prop("disabled", false);
          }
        });

     });
    
     let idConceptoEdit = 0;
     let clave_guardada, concepto_guardado, tipo_guardado;
     function obtenerEditar(idConcepto, tipo){
        idConceptoEdit = idConcepto;
        let token = $("#csr_token_UT5JP").val(); 

        $.ajax({
          type: 'POST',
          url: 'functions/getDatosConcepto.php',
          data: { 
                  idConcepto : idConceptoEdit,
                  tipo: tipo,
                  csr_token_UT5JP : token
                },
          success: function(r) {

            var datos = JSON.parse(r);

            if(datos.respuesta == "exito"){
              
              $("#txtClaveEdit").val(datos.clave);
              clave_guardada = datos.clave;
              concepto_guardado = datos.movimiento_id;
              tipo_guardado = tipo;
              $("#txtIdConceptoEdit").val(idConceptoEdit);

              if(tipo == 1){
                $(".percepcionEdit").prop("checked", true);
              }
              else{
                $(".deduccionEdit").prop("checked", true);
              }

              selectClaveEdit.destroy();

              $("#cmbConceptoEdit").html(datos.select);

              selectClaveEdit = new SlimSelect({
                select: '#cmbConceptoEdit',
                deselectLabel: '<span class="">✖</span>'
              });
            }
            else{
              Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "Ocurrió un error, intentalo cargar la nómina nuevamente",
              });
              $('#editar_clave').modal('hide');
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
              msg: "Ocurrió un error, intentalo cargar la nómina nuevamente",
            });
            $('#editar_clave').modal('hide');
          }
        });
     }

     $("#btnEditarClave").click(function(){
        let clave = $("#txtClaveEdit").val().trim();
        let concepto = $("#cmbConceptoEdit").val().trim();
        let tipo = $('input[name="tipoConceptoEdit"]:checked').val();
        let idConcepto = $("#txtIdConceptoEdit").val();
        let token = $("#csr_token_UT5JP").val(); 

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

        $("#btnCancelarClaveEdit").prop("disabled", true);
        $("#btnEditarClave").prop("disabled", true);

        $.ajax({
          type: 'POST',
          url: 'functions/editar_Clave.php',
          data: { 
                  concepto : concepto,
                  clave: clave, 
                  clave_guardada: clave_guardada,
                  tipo: tipo,
                  tipo_guardado: tipo_guardado,
                  concepto_guardado : concepto_guardado,
                  idConcepto : idConcepto,
                  csr_token_UT5JP : token
                },
          success: function(r) {

            if(r == "exito"){
              Lobibox.notify("success", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/checkmark.svg",
                msg: "Concepto modificada!",
              });
              $('#tblConceptos').DataTable().ajax.reload();
              $('#editar_clave').modal('hide');
            }
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
                msg: "Ese concepto ya tiene asignada una clave.",
              });
            }
            
            if(r == "fallo"){
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
            $("#btnCancelarClaveEdit").prop("disabled", false);
            $("#btnEditarClave").prop("disabled", false);
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
            $("#btnCancelarClaveEdit").prop("disabled", false);
            $("#btnEditarClave").prop("disabled", false);
          }
        });

     });
   

  function eliminarClave(idConcepto, tipo){
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
        text: "Se eliminará la clave.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: '<span class="verticalCenter">Eliminar clave</span>',
        cancelButtonText: '<span class="verticalCenter">Cerrar</span>',
        reverseButtons: true,
      })
      .then((result) => {
        if (result.isConfirmed) {

          $.ajax({
            type: 'POST',
            url: 'functions/eliminar_Clave.php',
            data: {
              tipoCon: tipo,
              idConcepto: idConcepto,
              csr_token_UT5JP: token
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
                  msg: "Se ha eliminado el concepto de nómina"
                });

                $('#tblConceptos').DataTable().ajax.reload();

              } 
              if (data == "fallo") {

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
              if (data == "fallo-existe") {

                Lobibox.notify("error", {
                  size: 'mini',
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: 'center top', //or 'center bottom'
                  icon: false,
                  img: '../../img/timdesk/warning_circle.svg',
                  msg: "El concepto ya ha sido asignado en un nómina, no se puede eliminar."
                });

              }
              if (data == "fallo-IMSS") {

                Lobibox.notify("error", {
                  size: 'mini',
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: 'center top', //or 'center bottom'
                  icon: false,
                  img: '../../img/timdesk/warning_circle.svg',
                  msg: "No se puede eliminar el concepto del IMSS."
                });

              }
              if (data == "fallo-ISR") {

                Lobibox.notify("error", {
                  size: 'mini',
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: 'center top', //or 'center bottom'
                  icon: false,
                  img: '../../img/timdesk/warning_circle.svg',
                  msg: "No se puede eliminar el concepto del ISR."
                });

              }
            }
          });



        } else if (
          /* Read more about handling dismissals below */
          result.dismiss === Swal.DismissReason.cancel
        ) {}
      });
  }

  $(".tipoConceptoC").change(function(){
    let tipo = this.value;
    let token = $("#csr_token_UT5JP").val(); 
    
    $.ajax({
          type: 'POST',
          url: 'functions/cargar_claves.php',
          data: { 
                  tipo : tipo,
                  csr_token_UT5JP : token
                },
          success: function(r) {
                
              if(r == "fallo"){
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
              else{
                  selectClave.destroy();

                  $("#cmbConcepto").html(r);

                  selectClave = new SlimSelect({
                    select: '#cmbConcepto',
                    deselectLabel: '<span class="">✖</span>'
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

  });

  $(".percepcionEdit,.deduccionEdit").change(function(){
    let tipo = this.value;
    let token = $("#csr_token_UT5JP").val(); 
    
    $.ajax({
          type: 'POST',
          url: 'functions/cargar_claves.php',
          data: { 
                  tipo : tipo,
                  csr_token_UT5JP : token
                },
          success: function(r) {
                
              if(r == "fallo"){
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
              else{
                  selectClaveEdit.destroy();

                  $("#cmbConceptoEdit").html(r);

                  selectClaveEdit = new SlimSelect({
                    select: '#cmbConceptoEdit',
                    deselectLabel: '<span class="">✖</span>'
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


  });

  var selectClave = new SlimSelect({
    select: '#cmbConcepto',
    deselectLabel: '<span class="">✖</span>'
  });

  selectClaveEdit = new SlimSelect({
    select: '#cmbConceptoEdit',
    deselectLabel: '<span class="">✖</span>'
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
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
  <title>Timlid | Deducciones</title>

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
    $titulo = "Deducciones - Claves";
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
                  <a class="nav-link" href="percepcionesclave.php">Percepciones</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link active" href="#">Deducciones</a>
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
                <table class="table" id="tblDeduccionesBase" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Concepto</th>
                      <th>Clave</th>
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
    <div class="modal fade right" id="agregar_clave_tipo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
          <div class="modal-content">
            <form action="#" method="POST" id="agregarClaveTipo">
              <!--<input type="hidden" name="idProyectoA" value="">-->
              <div class="modal-header">
                <h4 class="modal-title w-100" id="myModalLabel">Deducciones</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">x</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label for="usr">Concepto:</label>
                  <input type="text" class="form-control" name="txtConcepto" id="txtConcepto" value="" readonly>
                  <input type="hidden" name="idconcepto" id="idconcepto" value="">
                </div>
                <div class="form-group">
                  <label for="usr">Clave:</label>
                  <input type="text" class="form-control" name="txtClave" id="txtClave" value="" maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" required>
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
  <!--END ADD MODAL-->

  <input type="hidden" name="csr_token_UT5JP" id="csr_token_UT5JP" value="<?= $token ?>">

  <!-- Custom scripts for all pages-->
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>
  <script src="./js/deduccionesclave.js"></script>

  <script>
    let claveguardada = '';
    let existe_base_empresa = 0;
    let esNecesarioValidar = 0;

    function cargarDeduccion(idDeduccion){
      let token = $("#csr_token_UT5JP").val(); 
      $.ajax({
        type: 'POST',
        url: 'functions/cargarDeduccion.php',
        data: { 
                idDeduccion : idDeduccion,
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
          $("#idconcepto").val(idDeduccion);
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

          if(idDeduccion == 1 || idDeduccion == 2 || idDeduccion == 6 || idDeduccion == 20){

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

     
     $("#btnGuardarBase").click(function(){
        let clave = $("#txtClave").val().trim();
        let idconcepto = $("#idconcepto").val();
        let token = $("#csr_token_UT5JP").val(); 
        let baseEmpresa = "";
        let valor = "", valorObj;

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

        if(esNecesarioValidar == 1 && idconcepto != 1){
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

            if(clave.length < 1){
                Lobibox.notify("error", {
                  size: 'mini',
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: 'center top', //or 'center bottom'
                  icon: false,
                  img: '../../img/timdesk/warning_circle.svg',
                  msg: "Si agregas base por empresa, la clave no puede estar vacía."
                });
                return;
            }
        }

        $("#btnCancelarClave").prop("disabled", true);
        $("#btnGuardarBase").prop("disabled", true);

        $.ajax({
          type: 'POST',
          url: 'functions/agregarClaveDeduccion.php',
          data: { 
                  idconcepto : idconcepto,
                  clave: clave,
                  csr_token_UT5JP : token,
                  claveguardada: claveguardada,
                  existe_base_empresa: existe_base_empresa,
                  baseEmpresa: baseEmpresa,
                  valorEmpresa: valor
                },
          success: function(r) {

            var datos = JSON.parse(r);

            if(datos.estatus_clave == "existe-clave"){
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
              $("#btnCancelarClave").prop("disabled", false);
              $("#btnGuardarBase").prop("disabled", false);
            }

            if(datos.estatus_clave == "exito"){
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
              $("#btnCancelarClave").prop("disabled", false);
              $("#btnGuardarBase").prop("disabled", false);
              $('#tblDeduccionesBase').DataTable().ajax.reload();
              $('#agregar_clave_tipo').modal('hide');
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
                msg: "Se ha actualizado la base de la deducción.",
              });
              $("#btnCancelarClave").prop("disabled", false);
              $("#btnGuardarBase").prop("disabled", false);
              $('#tblDeduccionesBase').DataTable().ajax.reload();
              $('#agregar_clave_tipo').modal('hide');
            }

            if(datos.estatus_clave == "sin_cambio"){
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
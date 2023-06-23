<?php
  session_start();

  if(isset($_SESSION["Usuario"])){
    $user = $_SESSION["Usuario"];
    require_once('../../include/db-conn.php');

    if(isset($_GET['id'])){
      $id = $_GET['id'];
    }else{
      $id = 1;
    }
  }else {
    header("location:../dashboard.php");
  }

?>
<!-- html -->
<!DOCTYPE html>
<html lang="es">
  <head>
    <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Timlid | Tareas</title>

    <!-- Bootstrap core JavaScript-->
    <script src="../../vendor/jquery/jquery.min.js"></script>
    <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../../js/sb-admin-2.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha256-c4gVE6fn+JRKMRvqjoDp+tlG4laudNYrXI1GncbfAYY=" crossorigin="anonymous"></script>

    <!-- Page level plugins -->
    <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../../vendor/datatables/dataTables.responsive.js"></script>
    <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts
    <script src="js/demo/datatables-demo.js"></script>
    -->

    <!-- Custom fonts for this template -->
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" integrity="sha256-EH/CzgoJbNED+gZgymswsIOrM9XhIbdSJ6Hwro09WE4=" crossorigin="anonymous" />
    <link href="../../css/chosen.css" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template -->
    <link href="../../css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">

    <!-- jquery ui -->
    <script src="../../js/jquery-ui.js"></script>
    <link rel="stylesheet" href="../../css/jquery-ui.css">

    <!-- mdb -->
    <script src="../../js/mdb.min.js"></script>
    <link rel="stylesheet" href="../../css/mdb.min.css">

    <script src="../../js/mdb.min.js.map"></script>
    <link rel="stylesheet" href="../../css/mdb.min.css.map">

    <!-- select2 -->
    <script src="../../js/select2.min.js"></script>
    <link rel="stylesheet" href="../../css/select2.min.css">

    <!-- flatpickr plugin -->
    <script src="../../vendor/flatpickr/flatpickr.min.js"></script>
    <script src="../../vendor/flatpickr/lang/es.js"></script>
    <link rel="stylesheet" type="text/css" href="../../vendor/flatpickr/themes/dark.css">

    <style>

    div.custom-control-right {
        padding-right: 24px;
        padding-left: 0;
        margin-left: 0;
        margin-right: 0;
    }
    div.custom-control-right .custom-control-label::after{
        right: -1.5rem;
        left: auto;
    }
    div.custom-control-right .custom-control-label::before {
        right: -2.35rem;
        left: auto;
    }
    .buttons { display:block; text-align: right; }
    .buttons .btn { float: none; }
    .btn {
      text-transform: unset !important;
      font-size : 14px ;
    }
    /* .header-screen{
      display: block;
      width:400px;
      height: 35px;
      align-items: center;
      vertical-align: middle;
    } */

    .header-title-screen {
      display: inline-block;
      color:#15589B;
      float:left;
      top:0;
    }

    .header-title-screen img{
      display: inline-block;
      height: 40px;
    }

    .vl {
      color: #E5E5E5;
      height: 35px;
      display: inline-block;
      background: #E5E5E5;
      border:1px solid;
      margin-left: 25px;
      margin-right: 25px;
      float:left;
      top:1px;
    }

    .logo-views{
      display: inline-block;
      height: 35px;
      float:left;
      top:0;
      color:#15589B;
    }

    .logo-views img{
      display: inline-block;
      height: 30px;
    }

    .logo-views h1{
      font-size: 12px
    }

    </style>

  </head>
  <body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">

      <!-- Sidebar -->
        <?php
          $ruta = "../";
          require_once('../menu3.php');
          $rutes = "../";
        ?>
      <!-- End of Sidebar -->

      <!-- Content Wrapper -->
      <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">
          <?php
            $rutatb = "../";
            $titulo = '<div class="header-screen">
              <div class="header-title-screen">
                <h1 class="h3 mb-2"><img src="../../img/scrum/scrum_vista lista_mesa.svg" alt=""> Tareas</h1>
              </div>
              <div class="vl"></div>
              <div class="logo-views">
                <h1 class="h3 mb-2"><img src="../../img/scrum/vistas1.svg" alt=""> Vistas</h1>
              </div>
            </div>';
            require_once('../topbar.php');
          ?>

          <!-- Begin Page Content -->
          <div class="container-fluid">
            <!-- Page Heading -->

            <div id="alertas"></div>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
              <div class="card-header py-3">
                <div class="row">
                  <div class="col-lg-2">
                    <h1 class="h3 mb-2 text-gray-800"><i class="fas fa-tasks"></i> Tareas</h1>
                  </div>
                  <div class="col-lg-10 buttons">
                      <!--<button class="btn btn-success btn-sm" id="addRow" type="button" name="button"><i class="fas fa-plus"> Agregar tarea</i></button>-->

                    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                      <button id="btnGroupDrop1" type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Agregar </button>
                      <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <a class="dropdown-item" id="addRow" href="#">Agregar tarea</a>
                        <a class="dropdown-item" id="ModalEtapa"href="#">Agregar etapa</a>
                      </div>
                      <a class="btn btn-primary" type="button" href="../calendario_tareas/index.php?id=<?=$id; ?>" name="button">Calendario</a>
                      <a class="btn btn-primary" type="button" href="../pantalla_scrum/index.php?id=<?=$id; ?>" name="button">Pantalla Scrum</a>
                    </div>



                  </div>

                </div>

              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <div id="live_data"></div>
                </div>
              </div>
            </div>
          </div>
          <!-- /.container-fluid -->
        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <footer class="sticky-footer">
  					<img style="float:right;margin-right:20px" src="../../img/header/timlidAzul.png" width="120 px">
        </footer>
        <!-- End of Footer -->

      </div>
      <!-- End of Content Wrapper -->

      <!-- Scroll to Top Button-->
      <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
      </a>

      <!-- Modal Agregar-->
      <div class="modal fade right" id="ModalAgregar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-full-height modal-right modal-notify modal-info" style="width:100%;" role="document">
        <div class="modal-content">
          <!--Header-->
          <div class="modal-header">
            <p class="heading lead">Programar tarea</p>

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="white-text">x</span>
            </button>
          </div>

          <!--Body-->
          <div class="modal-body">
            <div class="text-center">
              <i class="fas fa-tasks fa-4x mb-3 animated rotateIn"></i>
              <p>
                <strong>Opciones programables para la tarea</strong>
              </p>
            </div>
              <div class="text-center" id="tarea"></div>
            <hr>

            <form action="" method="post">
              <div class="form-group">
                <div class="row">
                  <div class="col-lg-4">
                    <label for="txaDescripcion">Descripción:</label>
                  </div>
                  <div class="col-lg-8">
                    <textarea class="form-control" rows="3" cols="25" placeholder="Agregue más detalles a esta tarea..." id="txaDescripcion"></textarea>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-lg-4">
                    <label for="">Repetible:</label>
                  </div>
                  <div class="col-lg-8" id="repetible">
                    <select class="form-control" name="cmbRepetible" id="cmbRepetible">
                      <option value="">...</option>
                      <option value="1">No se repite</option>
                      <option value="2">Cada día</option>
                      <option value="3">Cada semana</option>
                      <option value="4">Cada 15 días</option>
                      <option value="5">Cada mes</option>
                      <option value="6">Cada 3 meses</option>
                      <option value="7">Cada 6 meses</option>
                      <option value="8">Cada año</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-lg-4">
                    <label for="">Subtareas:</label>
                  </div>
                  <div class="col-lg-8">
                    <span class="badge badge-pill badge-success text-white"><a href="#" style="color:white;" id="addRowSub"><i class="fas fa-plus" style="color:white;"></i> Agregar subtareas</a></span>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-lg-12">
                    <div id="live_data_sub"></div>
                  </div>
                </div>
              </div>

            </form>


          </div>

          <div id="alertasSub"></div>
          <!--Footer-->
          <div class="modal-footer justify-content-center">
            <input type="hidden" name="txtIdSub" id="txtIdSub">
            <a type="button" class="btn btn-primary waves-effect waves-light" id="btn_save">Guardar
              <i class="fas fa-save m-1"></i></i>
            </a>
            <a type="button" class="btn btn-outline-danger waves-effect" data-dismiss="modal">Cancelar</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Central Modal Medium Danger -->
     <div class="modal fade" id="ModalEliminar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
       aria-hidden="true">
       <div class="modal-dialog modal-notify modal-danger" role="document">
         <!--Content-->
         <div class="modal-content">
           <!--Header-->
           <div class="modal-header">
             <p class="heading lead">Eliminar tarea</p>

             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true" class="white-text">&times;</span>
             </button>
           </div>

           <!--Body-->
           <div class="modal-body">
             <div class="text-center">
               <i class="fas fa-times fa-4x mb-3 animated rotateIn"></i>
               <p>¿Está seguro de realizar esta acción?<br><br>Esta acción es irreversible.</p>
             </div>
           </div>

           <!--Footer-->
           <div class="modal-footer justify-content-center">
             <input type="hidden" name="txtId" id="txtId">
             <a type="button" class="btn btn-danger" id="btn_eliminar">Eliminar</a>
             <a type="button" class="btn btn-outline-danger waves-effect" data-dismiss="modal">Cancelar</a>
           </div>
         </div>
         <!--/.Content-->
       </div>
     </div>
     <!-- Central Modal Medium Danger-->

     <!-- Central Modal Medium Danger -->
      <div class="modal fade" id="ModalEliminarSub" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-notify modal-danger" role="document">
          <!--Content-->
          <div class="modal-content">
            <!--Header-->
            <div class="modal-header">
              <p class="heading lead">Eliminar subtarea</p>

              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" class="white-text">&times;</span>
              </button>
            </div>

            <!--Body-->
            <div class="modal-body">
              <div class="text-center">
                <i class="fas fa-times fa-4x mb-3 animated rotateIn"></i>
                <p>¿Está seguro de realizar esta acción?<br><br>Esta acción es irreversible.</p>
              </div>
            </div>

            <!--Footer-->
            <div class="modal-footer justify-content-center">
              <input type="hidden" name="txtIdSubD" id="txtIdSubD">
              <a type="button" class="btn btn-danger" id="btn_eliminar_sub">Eliminar</a>
              <a type="button" class="btn btn-outline-danger waves-effect" data-dismiss="modal">Cancelar</a>
            </div>
          </div>
          <!--/.Content-->
        </div>
      </div>
      <!-- Central Modal Medium Danger-->

      <!-- Central Modal Medium Info -->
 <div class="modal fade" id="modalEtapaAgregar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
   aria-hidden="true">
   <div class="modal-dialog modal-notify modal-info" role="document">
     <!--Content-->
     <div class="modal-content">
       <!--Header-->
       <div class="modal-header">
         <p class="heading lead">Agregar etapa</p>

         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true" class="white-text">&times;</span>
         </button>
       </div>

       <!--Body-->
       <div class="modal-body">
         <div class="text-center">
           <i class="fas fa-plus fa-4x mb-3 animated rotateIn text-center"></i>
           <form class="" action="" method="post">
             <div class="row">
               <div class="col-lg-12 text-left">
                 <label for="txtEtapa">Etapa:</label>
                 <input class="form-control" type="text" name="txtEtapa" id="txtEtapa" value="">
               </div>

             </div>
           </form>
         </div>
       </div>

       <!--Footer-->
       <div class="modal-footer justify-content-center">
         <a type="button" id="btnAddPhase" class="btn btn-primary">Agregar <i class="fas fa-plus ml-1 text-white"></i></a>
         <a type="button" class="btn btn-outline-danger waves-effect" data-dismiss="modal">Cancelar</a>
       </div>
     </div>
     <!--/.Content-->
   </div>
 </div>
 <!-- Central Modal Medium Info-->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>
    <script>

      $(document).ready(function(){
        fetch_data();


        //select2 aplicado en combo prioridad
        $('#cmbPrioridad').select2({
          placeholder: '...',
          width: '100%',
          minimumResultsForSearch: -1
        });
        //select2 aplicado en repetible
        $('#cmbRepetible').select2({
          placeholder: '...',
          width: '100%',
          minimumResultsForSearch: -1
        });
        //oculta las alertas
        function refrescar(){
          $('#alertas').hide();
          $('#alertasSub').hide();
        }
        //Cargar tabla
        function fetch_data()
        {
          var cadena = "id="+<?=$id; ?>;
          $.ajax({
            url : "functions/select.php",
            data : cadena,
            method : "POST",
            success : function(data)
            {
              $('#live_data').html(data);
              $('.cmbUser').select2({
                placeholder: '...',
                width: '100%',
                minimumResultsForSearch: -1
              });
              $('.date').flatpickr({
                mode: "range",
                dateFormat: "d/m/Y",
                locale: {
                  firstDayOfWeek: 1,

                  weekdays: {
                    shorthand: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
                    longhand: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                  },
                  months: {
                    shorthand: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Оct', 'Nov', 'Dic'],
                    longhand: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                  },
                },
              });
              $('.cmbPriority').select2({
                placeholder: '...',
                width: '100%',
                minimumResultsForSearch: -1
              });
            }
          });
        }
        //Agregar un nuevo registro
        $(document).on('click','#btn_add',function(){
          var nombre_tarea = $('#task_name').text();
          var responsable = $('#cmbUser').val();
          var auxDate = $('#date').val();
          var prioridad = $('#cmbPriority').val();
          var d;
          var d2;
          var formatDate;
          var formatDate2;
          var date;
          var date2;
          if(nombre_tarea == "")
          {
              $('#alertas').html('<div class="alert alert-warning" role="alert">Ingrese el nombre de la tarea.</div>');
              $('#alertas').show();
              setTimeout(refrescar, 3000);
              return false;
          }
          if(responsable == "")
          {
              $('#alertas').html('<div class="alert alert-warning" role="alert">Ingrese el responsable de la tarea.</div>');
              $('#alertas').show();
              setTimeout(refrescar, 3000);

              return false;
          }
          if(auxDate == "")
          {
              $('#alertas').html('<div class="alert alert-warning" role="alert">Ingrese la fecha de término de la tarea.</div>');
              $('#alertas').show();
              setTimeout(refrescar, 3000);

              return false;
          }
          if(prioridad == "")
          {
              $('#alertas').html('<div class="alert alert-warning" role="alert">Ingrese la fecha de término de la tarea.</div>');
              $('#alertas').show();
              setTimeout(refrescar, 3000);

              return false;
          }
          if(auxDate.length < 11){
            d = auxDate.split('/');
            formatDate = new Date(d[2],d[1],d[0]);
            date = formatDate.getFullYear()+"-"+formatDate.getMonth()+"-"+formatDate.getDate();
            $.ajax({
              url:"functions/insert.php",
              type:"POST",
              data:{nombre_tarea,responsable,date,prioridad},
              datatype:"text",
              success:function(data){
                //alert(data);
                if(data == 1){
                  fetch_data();
                  $('#alertas').html('<div class="alert alert-success" role="alert">Se registraron los datos con éxito</div>');
                  $('#alertas').show();
                }else{
                  $('#alertas').html('<div class="alert alert-warning" role="alert">No se registraron los datos. ERROR<br>'+data+'</div>');
                  $('#alertas').show();
                }
                setTimeout(refrescar, 3000);
              }
            });
          }else{
            d = auxDate.split(' to ');
            d2 = d[0].split('/');
            formatDate = new Date(d2[2],d2[1],d2[0]);
            d2 = d[1].split('/');
            formatDate2 = new Date(d2[2],d2[1],d2[0]);
            date = formatDate.getFullYear()+"-"+formatDate.getMonth()+"-"+formatDate.getDate();
            date2 = formatDate2.getFullYear()+"-"+formatDate2.getMonth()+"-"+formatDate2.getDate();
            $.ajax({
              url:"functions/insert.php",
              type:"POST",
              data:{nombre_tarea,responsable,date,date2,prioridad},
              datatype:"text",
              success:function(data){
                //alert(data);
                if(data == 1){
                  fetch_data();
                  $('#alertas').html('<div class="alert alert-success" role="alert">Se registraron los datos con éxito</div>');
                  $('#alertas').show();
                }else{
                  $('#alertas').html('<div class="alert alert-warning" role="alert">No se registraron los datos. ERROR<br>'+data+'</div>');
                  $('#alertas').show();
                }
                setTimeout(refrescar, 3000);
              }
            });
          }


/*
          $.ajax({
            url:"functions/insert.php",
            type:"POST",
            data:{nombre_tarea,responsable,date,date2,prioridad},
            datatype:"text",
            success:function(data){
              //alert(data);
              if(data == 1){
                fetch_data();
                $('#alertas').html('<div class="alert alert-success" role="alert">Se registraron los datos con éxito</div>');
                $('#alertas').show();
              }else{
                $('#alertas').html('<div class="alert alert-warning" role="alert">No se registraron los datos. ERROR<br>'+data+'</div>');
                $('#alertas').show();
              }
              setTimeout(refrescar, 3000);
            }
          });
*/
        });
        //editar nombre de tarea
        $(document).on('blur','.task_name',function(){
          var id = $(this).data('id1');
          var task_name = $(this).text();
          $.ajax({
            url : "functions/edit_task.php",
            type : "POST",
            data : {id:id, text:task_name},
            success:function(data){
              if(data == 1){
                fetch_data();
                $('#alertas').html('<div class="alert alert-success" role="alert">Se actualizaron los datos con éxito</div>');
                $('#alertas').show();
              }else{
                $('#alertas').html('<div class="alert alert-warning" role="alert">No se actualizaron los datos. ERROR</div>');
                $('#alertas').show();
              }
              setTimeout(refrescar, 3000);
            }
          });
        });
        //editar responsable de la tarea
        $(document).on('change','.responsable',function(){
          var id = $(this).data('id2');
          var user = $('#cmbUser'+id).val();
          $.ajax({
            url : "functions/edit_employe.php",
            type : "POST",
            data : {id:id, text:user},
            success:function(data){
              if(data == 1){
                fetch_data();
                $('#alertas').html('<div class="alert alert-success" role="alert">Se actualizaron los datos con éxito</div>');
                $('#alertas').show();
              }else{
                $('#alertas').html('<div class="alert alert-warning" role="alert">No se actualizaron los datos. ERROR</div>');
                $('#alertas').show();
              }
              setTimeout(refrescar, 3000);
            }
          });
        });
        //editar prioridad de la tarea
        $(document).on('change','.prioridad',function(){
          var id = $(this).data('id4');
          var priority = $('#cmbPriority'+id).val();
          $.ajax({
            url : "functions/edit_priority.php",
            type : "POST",
            data : {id:id, text:priority},
            success:function(data){
              if(data == 1){
                fetch_data();
                $('#alertas').html('<div class="alert alert-success" role="alert">Se actualizaron los datos con éxito'+data+'</div>');
                $('#alertas').show();
              }else{
                $('#alertas').html('<div class="alert alert-warning" role="alert">No se actualizaron los datos. ERROR '+data+'</div>');
                $('#alertas').show();
              }
              setTimeout(refrescar, 3000);
            }
          });
        });
        //editar fecha de entrega de la tarea
        $(document).on('change','.fecha_entrega',function(){
          var id = $(this).data('id3');
          var d;
          var d2;
          var formatDate;
          var formatDate2;
          var date;
          var date2;
          var auxDate = $('#date'+id).val();
          if(auxDate.length < 11){
            d = auxDate.split('/');
            formatDate = new Date(d[2],d[1],d[0]);
            date = formatDate.getFullYear()+"-"+formatDate.getMonth()+"-"+formatDate.getDate();
            $.ajax({
              url : "functions/edit_date.php",
              type : "POST",
              data : {id, date},
              success:function(data){
                if(data == 1){
                  fetch_data();

                  $('#alertas').html('<div class="alert alert-success" role="alert">Se actualizaron los datos con éxito</div>');
                  $('#alertas').show();
                }else{
                  $('#alertas').html('<div class="alert alert-warning" role="alert">No se actualizaron los datos. ERROR<br><br>'+data+'</div>');
                  $('#alertas').show();
                }
                setTimeout(refrescar, 3000);
              }
            });
          }else{
            d = auxDate.split(' to ');
            d2 = d[0].split('/');
            formatDate = new Date(d2[2],d2[1],d2[0]);
            d2 = d[1].split('/');
            formatDate2 = new Date(d2[2],d2[1],d2[0]);
            date = formatDate.getFullYear()+"-"+formatDate.getMonth()+"-"+formatDate.getDate();
            date2 = formatDate2.getFullYear()+"-"+formatDate2.getMonth()+"-"+formatDate2.getDate();

            $.ajax({
              url : "functions/edit_date.php",
              type : "POST",
              data : {id, date, date2},
              success:function(data){
                //alert(data)
                if(data == 1){
                  fetch_data();

                  $('#alertas').html('<div class="alert alert-success" role="alert">Se actualizaron los datos con éxito</div>');
                  $('#alertas').show();
                }else{
                  $('#alertas').html('<div class="alert alert-warning" role="alert">No se actualizaron los datos. ERROR<br><br>'+data+'</div>');
                  $('#alertas').show();
                }
                setTimeout(refrescar, 3000);
              }
            });
          }

        });
        //agrega una fila en la tabla tareas
        $('#addRow').click(function(){
          var cadena = "data=1";
          //alert(cadena);
          $.ajax({
            url : "functions/select.php",
            type : "POST",
            data : cadena,
            success : function(data)
            {
              $('#live_data').html(data);
              $('.cmbUser').select2({
                placeholder: '...',
                width: '100%',
                minimumResultsForSearch: -1
              });
              $('.date').flatpickr({
                mode: "range",
                dateFormat: "d/m/Y",
                weekNumbers: true,
                locale: {
                firstDayOfWeek: 1,

                weekdays: {
                  shorthand: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
                  longhand: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                },
                months: {
                  shorthand: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Оct', 'Nov', 'Dic'],
                  longhand: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                },
              },
              });
              $('.cmbPriority').select2({
                placeholder: '...',
                width: '100%',
                minimumResultsForSearch: -1
              });
            }
          });
        });

        //muestra la ventana popup
        $(document).on('click','#showModalAdd', function(){
          var id = $(this).data('id');
          var data = "id="+id;
          $.ajax({
            data : data,
            type : "POST",
            url : "functions/getTasksName.php",
            dataType : "JSON",
            success:function(data){
              var auxData = JSON.parse(data);
              $('#tarea').html('<p class="text-center"><b>'+auxData.tarea+'</b></p>');
              $('#txtIdSub').val(auxData.id);
              fetch_data_subtask(auxData.id);
            }
          });
          //var data = "id="+$('#txtIdSub').val();
          $.ajax({
            data : data,
            type : "POST",
            url : "functions/get_description.php",
            success:function(data){
              $('#txaDescripcion').val(data);
            }
          });
          $.ajax({
            data : data,
            type : "POST",
            url : "functions/get_priority.php",
            success:function(data){
              $('#cmbRepetible').val(data).change();
            }
          });


        });
        //asigna valor txtId para eliminar tarea
        $(document).on('click','.btn_delete',function(){
          var id = $(this).data('id4');
          $('#ModalEliminar').modal('toggle');
          $('#txtId').val(id);
        });
        //Elimina la tarea de la tabla
        $('#btn_eliminar').click(function(){
          var id = "id="+$('#txtId').val();
          $.ajax({
            data : id,
            type : 'POST',
            url : 'functions/delete.php',
            success : function(data){
              if(data == 1){
                fetch_data();
                $('#alertas').html('<div class="alert alert-success" role="alert">Se eliminaron los datos con éxito</div>');
                $('#alertas').show();
                $('#ModalEliminar').modal('toggle');
              }else{
                $('#alertas').html('<div class="alert alert-warning" role="alert">No se actualizaron los datos. ERROR</div>');
                $('#alertas').show();
              }
              setTimeout(refrescar, 3000);
            }
          });

        });
        //guardar datos complementarios de las tareas
        $(document).on('click','#btn_save',function(){
          var descripcion = $('#txaDescripcion').val();
          var repetible = $('#cmbRepetible').val();
          var id = $('#txtIdSub').val();
          var cadena = "descripcion="+descripcion+
                       "&repetible="+repetible+
                       "&id="+id;
          $.ajax({
            data : cadena,
            type : 'POST',
            url : 'functions/insert_addon.php',
            success : function(data){
              if(data == 1){
                $('#ModalAgregar').modal('toggle');
                $('#alertas').html('<div class="alert alert-success" role="alert">Se agregaron los datos con éxito</div>');
                $('#alertas').show();

              }else{
                $('#ModalAgregar').modal('toggle');
                $('#alertas').html('<div class="alert alert-warning" role="alert">No se agregaron los datos. ERROR<br><br>'+data+'</div>');
                $('#alertas').show();

              }

              setTimeout(refrescar, 3000);
            }
          });

        });

        //funciones Subtareas
        //Mostrar subtareas en tabla
        function fetch_data_subtask(id)
        {
          var cadena = "id="+id;
          $.ajax({
            url : "functions/subtask_select.php",
            type : "POST",
            data : cadena,
            success : function(data)
            {
              $('#live_data_sub').html(data);
              $('.cmbUser').select2({
                placeholder: '...',
                width: '100%',
                minimumResultsForSearch: -1
              });
            }
          });
        }
        //Agregar subtarea nueva
        $(document).on('click','#btn_addsub',function(){
          var nombre_subtarea = $('#subtask_name').text();
          var id_tarea = $('#txtIdSub').val();
          var responsable_subtarea = $('#cmbUserSub').val();
          if(nombre_subtarea == "")
          {
              $('#alertasSub').html('<div class="alert alert-warning" role="alert">Ingrese el nombre de la subtarea.</div>');
              $('#alertasSub').show();
              setTimeout(refrescar, 3000);
              return false;
          }
          if(id_tarea == "")
          {
              $('#alertasSub').html('<div class="alert alert-warning" role="alert">No se encuentra el id de la tarea.</div>');
              $('#alertasSub').show();
              setTimeout(refrescar, 3000);
              return false;
          }
          if(responsable_subtarea == "")
          {
            $('#alertasSub').html('<div class="alert alert-warning" role="alert">Ingrese un responsable de la subtarea.</div>');
            $('#alertasSub').show();
            setTimeout(refrescar, 3000);
            return false;
          }

          $.ajax({
            url:"functions/insert_subtask.php",
            type:"POST",
            data:{nombre_subtarea,id_tarea,responsable_subtarea},
            datatype:"text",
            success:function(data){
              //alert(data);
              if(data == 1){
                fetch_data_subtask(id_tarea);
                $('#alertasSub').html('<div class="alert alert-success" role="alert">Se registraron los datos con éxito</div>');
                $('#alertasSub').show();
              }else{
                $('#alertasSub').html('<div class="alert alert-warning" role="alert">No se registraron los datos. ERROR<br><br>'+data+'</div>');
                $('#alertasSub').show();
              }
              setTimeout(refrescar, 3000);
            }
          });
        });
        //agrega una fila a la tabla subtareas
        $('#addRowSub').click(function(){
          var id = $('#txtIdSub').val();
          var cadena = "data=1&id="+id;
          //alert(cadena);
          $.ajax({
            url : "functions/subtask_select.php",
            type : "POST",
            data : cadena,
            success : function(data)
            {
              $('#live_data_sub').html(data);
              $('.cmbUser').select2({
                placeholder: '...',
                width: '100%',
                minimumResultsForSearch: -1
              });
            }
          });
        });
        //asigna valor txtId para eliminar tarea
        $(document).on('click','.btn_delete_sub',function(){
          var id = $(this).data('id3');
          $('#ModalEliminarSub').modal('toggle');
          $('#txtIdSubD').val(id);
        });
        //Elimina la subtarea de la tabla
        $('#btn_eliminar_sub').click(function(){
          var id = "id="+$('#txtIdSubD').val();
          var id_tarea = $('#txtIdSub').val();
          $.ajax({
            data : id,
            type : 'POST',
            url : 'functions/delete_subtask.php',
            success : function(data){
              if(data == 1){
                fetch_data_subtask(id_tarea);
                $('#alertasSub').html('<div class="alert alert-success" role="alert">Se eliminaron los datos con éxito</div>');
                $('#alertasSub').show();
                $('#ModalEliminarSub').modal('toggle');
              }else{
                $('#alertasSub').html('<div class="alert alert-warning" role="alert">No se actualizaron los datos. ERROR</div>');
                $('#alertasSub').show();
              }
              setTimeout(refrescar, 3000);
            }
          });
        });
        //editar nombre de subtarea
        $(document).on('blur','.subtask_name',function(){
          var id = $(this).data('id1');
          var id_tarea = $('#txtIdSub').val();
          var task_name = $(this).text();
          $.ajax({
            url : "functions/edit_subtask.php",
            type : "POST",
            data : {id:id, text:task_name},
            success:function(data){
              if(data == 1){
                fetch_data_subtask(id_tarea);
                $('#alertasSub').html('<div class="alert alert-success" role="alert">Se actualizaron los datos con éxito</div>');
                $('#alertasSub').show();
              }else{
                $('#alertasSub').html('<div class="alert alert-warning" role="alert">No se actualizaron los datos. ERROR</div>');
                $('#alertasSub').show();
              }
              setTimeout(refrescar, 3000);
            }
          });
        });
        //editar responsable de la subtarea
        $(document).on('change','.sub_responsable',function(){
          var id = $(this).data('id2');
          var user = $('#cmbUserSub'+id).val();
          var id_tarea = $('#txtIdSub').val();
          $.ajax({
            url : "functions/edit_employe.php",
            type : "POST",
            data : {id:id, text:user},
            success:function(data){
              if(data == 1){
                fetch_data_subtask(id_tarea);
                $('#alertasSub').html('<div class="alert alert-success" role="alert">Se actualizaron los datos con éxito</div>');
                $('#alertasSub').show();
              }else{
                $('#alertasSub').html('<div class="alert alert-warning" role="alert">No se actualizaron los datos. ERROR</div>');
                $('#alertasSub').show();
              }
              setTimeout(refrescar, 3000);
            }
          });
        });

        //funciones etapas
        $('#ModalEtapa').click(function(){
          $('#modalEtapaAgregar').modal('toggle');
        });
        $('#btnAddPhase').click(function(){
          var data = "etapa="+$('#txtEtapa').val();
          $.ajax({
            data : data,
            type : 'POST',
            url : 'functions/addPhase.php',
            success : function(data){
              if(data == 1){
                $('#modalEtapaAgregar').modal('toggle');
              }else{
                $('#modalEtapaAgregar').modal('toggle');
              }
            }
          });
        });
      });




    </script>
  </body>
</html>

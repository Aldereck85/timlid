<?php
session_start();

  if(isset($_SESSION["Usuario"])){
    require_once('../../include/db-conn.php');
    $user = $_SESSION["Usuario"];
  }else {
    header("location:../dashboard.php");
  }

  if(isset($_POST['id'])){
    $id =  $_POST['id'];
    $stmt = $conn->prepare("SELECT p.Proyecto, p.FKResponsable
                              FROM proyectos as p
                                LEFT JOIN usuarios as u ON u.PKUsuario = p.FKResponsable
                                LEFT JOIN empleados as e ON e.PKEmpleado = u.FKEmpleado
                                  WHERE PKProyecto= :id");
    $stmt->execute(array(':id'=>$id));
    $row = $stmt->fetch();
    $proyecto = $row['Proyecto'];
    $idUsuario = $row['FKResponsable'];
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

  <title>Timlid | Proyectos</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>


  <!-- Custom scripts for all pages-->
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/mdb.min.js"></script>
  <script src="../../js/slimselect.min.js"></script>
  <script src="../../js/sweet/sweetalert2.js"></script>

  <!-- Page level plugins -->
  <script src="../../vendor/datatables/jquery.dataTables.js"></script>
  <script src="../../vendor/datatables/dataTables.responsive.js"></script>
  <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts
  <script src="js/demo/datatables-demo.js"></script>
  -->

  <!-- Custom fonts for this template -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">


  <!-- Custom styles for this template -->
  <link href="../../css/mdb.min.css" rel="stylesheet">
  <link href="../../css/sb-admin-2.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet"></link>

  <!-- Custom styles for this page -->
  <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <script src="../../js/lobibox.min.js"></script>

  <script>
    $(document).ready(function(){
      var idioma_espanol = {
          "searchPlaceholder": "Buscar...",
          "sProcessing":     "Procesando...",
          "sLengthMenu":     "",//Mostrar _MENU_ registros
          "sZeroRecords":    "No se encontraron resultados",
          "sEmptyTable":     "Ningún dato disponible en esta tabla",
          "sInfo":           "",
          "sInfoEmpty":      "",
          "sInfoFiltered":   "",//(filtrado de un total de _MAX_ registros)
          "sInfoPostFix":    "",
          "sSearch":         "",
          "sUrl":            "",
          "sInfoThousands":  ",",
          "sLoadingRecords": "Cargando...",
          "oPaginate": {
              "sFirst":    "",
              "sLast":     "",
              "sNext":     "<img src='../../img/icons/pagination.svg' width='15px'>",
              "sPrevious": "<img src='../../img/icons/pagination.svg' width='15px' style='-webkit-transform: scaleX(-1); transform=scaleX(-1);'>"
          },
          "oAria": {
              "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
              "sSortDescending": ": Activar para ordenar la columna de manera descendente"
          }
      }
      $("#tblProyectos").dataTable(
      {
        "lengthChange": false,
        "pageLength": 50,
        //"paging": true,
        "info": false,
        "pagingType": "full_numbers",
        "ajax":"functions/function_Proyectosor.php",
          "columns":[
            {"data":"Proyecto"},
            {"data":"Usuario"}
          ],
          "language": idioma_espanol,
            responsive: true,
          "columnDefs": [
            { "width": "50%", "targets": 0 }
          ]
      }

      )
    });
  </script>
<style type="text/css">
    a:link, a:visited, a:active {
        text-decoration:none;
    }
    .link{
      color: #4e73df;
    }
    /* .header-screen{
        display: block;
        width:400px;
        height: 35px;
        align-items: center;
        vertical-align: middle;
      } */
      .header-title-screen img {
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
      .logo-views img {
        display: inline-block;
        height: 30px;
    }
    .logo-views h1 {
        font-size: 12px;
    }
    .logo-views {
        display: inline-block;
        height: 35px;
        float: left;
        top: 0;
        color: #15589B;
    }
    select:invalid {color: #b1b1b1;}
</style>
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
      $ruta = "../";
      $ruteEdit = $ruta."central_notificaciones/";
      require_once('../menu3.php');
    ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">
        <div class="container-sm" id="TopbarDiv">
          <?php
              $titulo = '<div class="header-screen">
                <div class="header-title-screen">
                  <h1 class="h3 mb-2">Timdesk  <img src="../../img/timdesk/timdesk_icon.svg" alt="" style="position:relative;top:-5px;left:-7px;"></h1>
                </div>
              </div>';
              $rutatb = "../";
              require_once('../topbar.php');
          ?>
        </div>
        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="divPageTitle">
            <img src="../../img/timdesk/proyectos.svg" width="45px" style="position:relative;top:-10px;">
            <label class="lblPageTitle">&nbsp;Proyectos</label>
          </div>
          <!--<button class="btn btn-info btn-circle float-right" data-toggle="modal" data-target="#agregar_Proyecto"><i class="fas fa-plus"></i></button><br>
          <br>-->


          <!--<h1 class="h3 mb-2 text-gray-800" style="padding-bottom:40px">Proyectos</h1></img>-->
          <!--<p class="mb-4">Información general de los proyectos</p>-->

          <!-- DataTales Example -->
          <div class="button-container" id="myButton">
              <div class="button-icon-container">
                <button class="btn btn-info btn-circle float-right waves-effect waves-light" type="button" id="btn-proyectos" data-toggle="modal" data-target="#agregar_Proyecto"><i class="fas fa-plus"></i></button>
              </div>
              <div class="button-text-container">
                <span>Agregar proyecto</span>
              </div>
          </div>

          <div class="">

            <div class="card-body">
              <br>
              <div class="table-responsive">
                <table class="table stripe" id="tblProyectos" width="100%" cellspacing="0" style="text-align: center;">
                  <thead>
                    <tr>
                      <th>Proyecto</th>
                      <th>Encargado</th>
                      <!--<th>Acciones</th>-->
                    </tr>
                  </thead>
                  <!--<tfoot>
                    <tr>
                      <th>PROYECTO</th>
                      <th>ENCARGADO</th>
                    </tr>-->
                  </tfoot>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->
      <?php
        $rutaf = "../";
        require_once('../footer.php');
      ?>
    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>
  <!-- Add Fluid Modal mis proyectos -->
  <div class="modal fade right" id="agregar_Proyecto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">

    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">


      <div class="modal-content">
        <form action="functions/agregar_Proyecto.php" method="POST">
          <!--<input type="hidden" name="idProyectoA" value="">-->
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Agregar proyecto</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
          </div>
          <div class="modal-body">

              <div class="form-group">
                  <label for="usr">Nombre del proyecto:</label>
                  <input type="text" id="txtarea" class="form-control alpha-only" maxlength="40" name="txtProyecto" required>
              </div>

              <div class="form-group">
                  <label for="usr">Encargado del proyecto:</label>
                  <select name="cmbIdUsuario" id="cmbIdUsuario" class="form-control" required>
                  <!--<select name="cmbIdUsuario[]" id="multiple"  multiple>-->
                    <option value="" style="" disabled selected hidden>Seleccionar encargado</option>
                      <?php
                        $stmt = $conn->prepare("SELECT u.PKUsuario, CONCAT(e.Nombres,' ',e.PrimerApellido,' ',e.SegundoApellido) as nombre_empleado
                        FROM usuarios as u INNER JOIN empleados as e ON u.FKEmpleado = e.PKEmpleado");
                        $stmt->execute();
                        $row = $stmt->fetchAll();

                        if(count($row) > 0 ){
                          foreach($row as $r)//Mostrar usuarios
                            echo '<option value="'.$r['PKUsuario'].'">'.$r['nombre_empleado'].'</option>';
                        }
                        else{
                          echo '<option value="" disabled>No hay usuarios para mostrar.</option>';
                        }
                      ?>
                  </select>
              </div>
              <div class="form-group">
                  <label for="usr">Equipos participantes en el proyecto:</label>
                  <select name="cmbIdEquipo[]" id="multiple2"  multiple>
                    <!--<option value="">Elegir usuarios o equipos</option>-->
                      <?php
                        $stmt2 = $conn->prepare("SELECT * FROM equipos");
                        $stmt2->execute();
                        $row2 = $stmt2->fetchAll();

                        if (count($row2) > 0 ){

                          foreach($row2 as $r2)//mostrar equipos
                            echo '<option value="'.$r2['PKEquipo'].'">'.$r2['Nombre_Equipo'].'</option>';//'<option value="'.$r['PKEquipo'].'">'.$r['Nombre_Equipo'].'</option>';
                        }
                        else{
                          echo '<option value="" disabled>No hay equipos para mostrar.</option>';
                        }
                      ?>
                  </select>
              </div>
              <div class="form-group">
                  <label for="usr">Integrantes participantes en el proyecto:</label>
                  <select name="cmbIntegrantes[]" id="multiple3"  multiple>
                    <!--<option value="">Elegir usuarios o equipos</option>-->
                    <?php
                        $stmt = $conn->prepare("SELECT u.PKUsuario, CONCAT(e.Nombres,' ',e.PrimerApellido,' ',e.SegundoApellido) as nombre_empleado
                        FROM usuarios as u INNER JOIN empleados as e ON u.FKEmpleado = e.PKEmpleado");
                        $stmt->execute();
                        $row = $stmt->fetchAll();

                        if(count($row) > 0 ){
                          foreach($row as $r)//Mostrar usuarios
                            echo '<option value="'.$r['PKUsuario'].'">'.$r['nombre_empleado'].'</option>';
                        }
                        else{
                          echo '<option value="" disabled>No hay usuarios para mostrar.</option>';
                        }
                    ?>
                  </select>
              </div>
              <label style="color:#006dd9;font-size: 13px;"> Nota: Los integrantes que pertenezcan a un equipo no apareceran como integrantes individuales</label>
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal" id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="submit" class="btnesp espAgregar float-right" name="btnAgregar" id="btnAgregar"><span class="ajusteProyecto">Agregar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
  <!-- End Add Fluid Modal mis proyectos -->
  <!--UPDATE MODAL DENTRO DE PROYECTOS 04/09/2020-->
  <div class="modal fade right" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
      <div class="modal-content">
        <form action="functions/editar_Proyecto.php" method="POST">
          <input type="hidden" name="idProyectoU" id="idProyectoU">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Editar proyecto</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

                <div class="form-group">
                    <label for="usr">Nombre del proyecto:</label>
                    <input type="text"  class="form-control alpha-only" value="" maxlength="40" name="txtProyectoU" id="txtProyectoU">
                </div>

                <div class="form-group">
                    <label for="usr">Encargado del proyecto:</label>
                    <select name="cmbIdUsuarioU" id="cmbIdUsuarioU"  class="form-control" required>
                      <option value="" disabled selected hidden>Seleccione un encargado</option>

                    </select>
                </div>
                <div class="form-group">
                    <label for="usr">Equipos participantes en el proyecto:</label>
                    <select name="cmbIdEquipoU[]" id="multipleU"  multiple>
                    </select>
                </div>
                <div class="form-group">
                  <label for="usr">Integrantes participantes en el proyecto:</label>
                  <select name="cmbIntegrantesU[]" id="multipleU2"  multiple>
                    <!--<option value="">Elegir usuarios o equipos</option>-->
                  </select>
                </div>
                <label style="color:#006dd9;font-size: 13px;"> Nota: Los integrantes que pertenezcan a un equipo no apareceran como integrantes individuales</label>
          </div>
          <div class="modal-footer justify-content-center">

            <a class="btnesp first espVerProyecto" href="../tareas/timDesk/index.php?id="  name="btntimdeskProyecto" id="btntimdeskProyecto"><span class="ajusteProyecto">Ver Proyecto</span></a>
            <!--<a class="btn btn-danger" href="../proyectos/functions/eliminar_Proyecto.php?idProyectoD=<?=$id;?>"  name="idProyectoD" ><i class="fas fa-trash-alt"></i> Eliminar Proyecto</a>-->
            <!--<a href="../tareas/timdesk/index.php?id="  name="btntimdeskProyecto" id="btntimdeskProyecto" data-toggle="modal">Timdesk<img src="../../img/timdesk/timdesk_icon.svg" alt="" style="position:relative;" width="50", height="50"></a><i class="h3 mb-2"></i> -->
            <!--<a class="btn btn-danger" href="#"  name="btnEliminarProyecto" id="btnEliminarProyecto" data-toggle="modal" data-target="#eliminar_Proyecto_Conf" ><i class="fas fa-trash-alt"></i> Eliminar Proyecto</a>-->
            <a class="btnesp first espEliminar" href="#" onclick="eliminarProyecto(this.value);" name="btnEliminarProyecto" id="idProyectoD" data-toggle="modal" data-target=""><span class="ajusteProyecto">Eliminar proyecto</span></a>
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal" 0id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
            <!--<input type="submit" class="btn btn-primary float-right" name="btnEditar" id="btnEditar" value="Guardar">-->
            <button type="submit" class="btnesp espAgregar float-right" name="btnEditar" id="btnEditar"><span class="ajusteProyecto">Guardar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!--END UPDATE MODAL DENTRO DE PROYECTOS-->

<!--CONFIRMACION DE ELIMINAR PROYECTO-->
  <div id="eliminar_Proyecto_Conf" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="functions/eliminar_Proyecto.php" method="POST"><!--?idProyectoD=-->
          <input type="hidden" name="idProyectoD" id="idProyectoD" value="">
          <div class="modal-header">
            <h4 class="modal-title">Eliminar proyecto</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <p>¿Está seguro de realizar esta acción?</p>
            <p class="text-warning"><small>Esta acción es irreversible.</small></p>
          </div>
          <div class="modal-footer justify-content-center">
            <input type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal" value="Cancelar">
            <input type="submit" class="btnesp first espEliminar"  value="Eliminar">
          </div>
        </form>
      </div>
    </div>
  </div>
  <!--END CONFIRMACION DE ELIMINAR PROYECTO-->



  <script>
    function obtenerIdProyectoEditar(id){
      document.getElementById('idProyectoU').value = id;
      document.getElementById('idProyectoD').value = id;
      var id = "id="+id;
      /*$.ajax({
        type: 'POST',
        url: 'functions/getEncargado.php',
        data: data,
        success:function(r){
          $("#cmbIdUsuarioU").html(r);
        }
      });*/
      $.ajax({
        type: 'POST',
        url: 'functions/getProyecto.php',
        data: id,
        success:function(r){

          var datos = JSON.parse(r);
          console.log(datos.html);
          $("#txtProyectoU").val(datos.html);
          $("#cmbIdUsuarioU").html(datos.html2);
          $("#multipleU").html(datos.html3);
          $("#multipleU2").html(datos.html4);
          $("#btntimdeskProyecto").attr("href","../tareas/timDesk/index.php?" + id );
          //$("#idProyectoU").attr("value", id );
        }
      });
    }
/*Funcion para  mostrar una alerta al eliminar un proyecto*/
function eliminarProyecto(id){

  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: 'btn',
      cancelButton: 'btn'
    },
    buttonsStyling: false
  })

  swalWithBootstrapButtons.fire({
    title: '¿Desea eliminar el registro de este proyecto?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: '<span class="verticalCenter2">Eliminar proyecto</span>',
    cancelButtonText: '<span class="verticalCenter2">Cancelar</span>',
    reverseButtons: true
  }).then((result) => {
    if (result.isConfirmed) {

      $.ajax({
          url : "functions/eliminar_Proyecto.php",
          type: "POST",
          data : { "idProyectoD" : id },
          success: function(data,status,xhr)
          {
            if(data == "exito"){
              $('#modalEditar').modal('toggle');
              $('#tblProyectos').DataTable().ajax.reload();
              Lobibox.notify('success', {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: false,
                img: '../../img/chat/notificacion_error.svg',
                msg: '¡Proyecto eliminado!'
              });
            }
            else{
              Lobibox.notify('warning', {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top',
                icon: false,
                img: '../../img/chat/notificacion_error.svg',
                msg: 'Ocurrió un error al eliminar'
              });
            }
          }
      });

    } else if (
      /* Read more about handling dismissals below */
      result.dismiss === Swal.DismissReason.cancel
    ) {

    }
  })

  swal("¿Desea eliminar el registro de este proyecto?",{
    buttons: {
      cancel: {
        text:"Cancelar",
        value:null,
        visible:true,
        className:"",
        closeModal:true,
      },
      confirm: {
      text: "Eliminar proyecto",
      value:true,
      visible:true,
      className:"",
      closeModal:true,
      },
    },
    icon: "warning"
  })
.then((value) => {
  if (value) {
    $.ajax({
          url : "functions/eliminar_Proyecto.php",
          type: "POST",
          data : { "idProyectoD" : id },
          success: function(data,status,xhr)
          {
            if(data == "exito"){
              $('#modalEditar').modal('toggle');
              $('#tblProyectos').DataTable().ajax.reload();
              Lobibox.notify('success', {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: false,
                img: '../../img/chat/notificacion_error.svg',
                msg: '¡Proyecto eliminado!'
              });
            }
            else{
              Lobibox.notify('warning', {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top',
                icon: false,
                img: '../../img/chat/notificacion_error.svg',
                msg: 'Ocurrió un error al eliminar'
              });
            }
          }
    });
  } else {
    //cuando se presiona el boton de cancelar
  }
});


}
/*End Funcion para  mostrar una alerta al eliminar un proyecto*/


    $(document).ready(function(){
      $("#alertaTareas").load('../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>&ruteEdit=<?=$ruteEdit;?>');
        setInterval(refrescar, 50000);
      });
    function refrescar(){
      $("#alertaTareas").load('../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>&ruteEdit=<?=$ruteEdit;?>');
    }

    $('#editarventana').click(function(){
      $('#editar_Proyecto').modal('hide');
    });
    new SlimSelect({
        select: '#multiple3',
        placeholder: 'Seleccionar integrantes',
        deselectLabel: '<span class="">✖</span>'
    });
    new SlimSelect({
        select: '#multiple2',
        placeholder: 'Seleccionar equipos',
        deselectLabel: '<span class="">✖</span>',
        onChange: (info) => {
          console.log(info);
          var id=0;

        }
    });
    new SlimSelect({
        select: '#multipleU',
        //placeholder: 'Seleccionar equipos'
        deselectLabel: '<span class="">✖</span>'
    });
    new SlimSelect({
        select: '#multipleU2',
        //placeholder: 'Seleccionar equipos'
        deselectLabel: '<span class="">✖</span>'
    });
    $( "#txtarea" ).on('input', function() {
    if ($(this).val().length>=40) {
      Lobibox.notify('warning', {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: 'center top',
        icon: true,
        img: '../../img/timdesk/warning_circle.svg',
        msg: 'Maximo 40 caractéres!'
      });
    }
    });
    $( "#txtProyectoU" ).on('input', function() {
    if ($(this).val().length>=40) {
      Lobibox.notify('warning', {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: 'center top',
        icon: true,
        img: '../../img/timdesk/warning_circle.svg',
        msg: 'Maximo 40 caractéres!'
      });
    }
    });
  </script>
  <script> var ruta = "../";</script>
  <script src="../../js/sb-admin-2.min.js"></script>


</body>

</html>

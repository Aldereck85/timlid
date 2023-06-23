<?php
  session_start();
  require_once('../../../../include/db-conn.php');
  $id = 2;
  $usuarioId = 1;

  $stmt = $conn->prepare('SELECT * FROM permisos_secciones INNER JOIN secciones ON PKSeccion = FKSeccion INNER JOIN usuarios ON FKUsuario = PKUsuario WHERE FKUsuario = :id');
  $stmt->bindValue(':id',$usuarioId);
  $stmt->execute();
  //$row = $stmt->fetch();
  $idSeccion = [];
  $seccion = [];
  $permitido = [];
  $x = 0;
  $fkRol = 0;
  //////
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="icon" type="image/png" href="../../../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Timlid | Roles del usuario</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../../js/validaciones.js"></script>
  <!-- Core plugin JavaScript-->
  <script src="../../../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../../../js/sb-admin-2.min.js"></script>

  <script src="../../../../js/bootstrap-clockpicker.min.js"></script>
  <script src="../../../../js/rol.js"></script>

  <!-- Custom fonts for this template-->
  <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../../css/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../../../../css/sb-admin-2.css" rel="stylesheet">
  <link href="../../../../css/admin_roles.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha256-c4gVE6fn+JRKMRvqjoDp+tlG4laudNYrXI1GncbfAYY=" crossorigin="anonymous"></script>
  <link href="../../../../css/chosen.css" rel="stylesheet" type="text/css">
  <link href="../../../../css/roles.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" integrity="sha256-EH/CzgoJbNED+gZgymswsIOrM9XhIbdSJ6Hwro09WE4=" crossorigin="anonymous" />
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
      <?php
      $ruta = "../../../";
      require_once('../../../menu3.php');
      ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

          <?php
            $rutatb = "../../../";
            require_once('../../../topbar.php');
          ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

		<?php
            if($redirect == 1){
                echo '<div class="container" id="mensaje_exito">
                      <br>
                      <div class="alert alert-success" role="alert">
                        Se ha enviado un correo electrónico al email de este usuario para activar su cuenta.
                      </div>
                    </div>';

                $to = $email;
                $subject = "Activar cuenta | Timlid";

                $message = "
                <h2 align='center'>Activar cuenta</h2>
                <hr>
                <p align='left'>Saludos, ".$nombreUsuario."</p>
                <p align='justify'>Bienvenido al sistema ERP de Timlid, para completar tu registro solo necesitas acceder al siguiente enlace:</p>
                <p align='center'><a href='http://erpghmedic.com.mx/index.php?id=".$idUsuario."&codigo=".$codigoBD."' >Timlid - Activar cuenta</a></p>
                <hr>
                <center><img src='http://erpghmedic.com.mx/img/Logo-transparente.png' width='15%' /></center>
                ";

                // Always set content-type when sending HTML email
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

				$headers .= 'From: <erpghmed@erpghmedic.com.mx>' . "\r\n";

                mail($to,$subject,$message,$headers);
            }
          ?>
          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Administración de roles</h1>
          </div>
          <div class="row">

            <div class="col-lg-12">
              <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link" id="administrador-tab" data-toggle="tab" href="#" role="tab" aria-controls="home" aria-selected="true">Administrador</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="rh-tab" data-toggle="tab" href="#" role="tab" aria-controls="profile" aria-selected="false">Recursos humanos</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="personalizado-tab" onclick="mostrarPersonalizado()" data-toggle="tab" href="#" role="tab" aria-controls="contact" aria-selected="false">Personalizado</a>
                </li>
              </ul>
              <!-- Basic Card Example -->
              <div class="card mb-4" style="position:relative;top:-10px;">
                <div class="card-body">
                    <div class="row">
                      <div class="col-lg-12">
                        <div class="tab-content" id="myTabContent">
                          <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <form action="" method="post">
                              <br>
                              <div class="row">
                                <div id="divRolControl" class="col-lg-12">
                                  <?php
                                  echo '<div class="accordion" id="accordionExample">';
                                  $z = 1;
                                  $x = 1;
                                  $numPantalla = 1;
                                  $numFuncion = 1;
                                  $totalPantallas = 0;
                                  while($row = $stmt->fetch()){
                                    $fkRol = $row['FKRol'];
                                    if($row['Permiso'] == 1){
                                      echo '
                                      <div class="card">
                                        <div class="card-hr" id="heading'.$z.'">
                                          <h5 class="mb-0">
                                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse'.$z.'" aria-expanded="false" aria-controls="collapse'.$z.'">
                                              <i id="opt-group-173" class="opt-menu-icon"></i>
                                            </button>
                                            '.$row['Seccion'].'
                                          </h5>
                                        </div>
                                        <div id="collapse'.$z.'" class="collapse" aria-labelledby="heading'.$z.'" data-parent="#accordionExample">
                                          <div class="card-body">';
                                            /*echo '
                                            <div class="row">
                                              <div class="col-lg-6">
                                                <input type="radio" id="rdoControlTotal'.$row['Siglas'].'" class="rdbHeader" name="rdb'.$row['Siglas'].'" checked><label for="rdbControl">&nbsp;Control total</label><br>
                                                <input type="radio" id="rdoNoEliminar'.$row['Siglas'].'" class="rdbHeader" name="rdb'.$row['Siglas'].'"><label for="noEliminar">&nbsp;Controlar todo excepto eliminar</label><br>
                                                <input type="radio" id="rdoVer'.$row['Siglas'].'" class="rdbHeader" name="rdb'.$row['Siglas'].'" ><label for="female">&nbsp;Solo lectura</label><br>
                                              </div>
                                              <div class="col-lg-6">
                                                <input type="radio" id="rdoSinPermisosRh" class="rdbHeader" name="rdb'.$row['Siglas'].'"><label for="female">&nbsp;Sin permisos</label><br>
                                                <input type="radio" id="rdoPersonalizadoRh" class="rdbHeader" name="rdb'.$row['Siglas'].'"><label for="female">&nbsp;Personalizado</label>
                                              </div>
                                            </div>
                                            ';*/
                                            //SELECT * FROM permisos_funciones INNER JOIN funciones on funciones.PKFuncion = permisos_funciones.FKFuncion WHERE FKUsuario = 1 AND FKPantalla = 1
                                            //$stmt2 = $conn->prepare('SELECT * FROM permisos_pantallas INNER JOIN pantallas ON PKPantalla = FKPantalla WHERE FKUsuario = :id');
                                            $stmt2 = $conn->prepare('SELECT * FROM permisos_pantallas INNER JOIN pantallas ON PKPantalla = FKPantalla INNER JOIN secciones ON PKSeccion = FKSeccion WHERE FKUsuario = :id AND FKSeccion = :fkSeccion');
                                            $stmt2->bindValue(':id',$usuarioId);
                                            $stmt2->bindValue(':fkSeccion',$row['PKSeccion']);
                                            $stmt2->execute();

                                            while($row2 = $stmt2->fetch()){

                                              echo '<div class="row"><div class="col-lg-12" style="background:#ececec;padding:5px;"><input type="checkbox" name="chkTodo'.$row2['Pantalla'].'" class="chkAvailable chkRh chkPantalla" value="'.$row2['PKPantalla'].'" id="chkTodo'.$numPantalla.'"';
                                              $numPantalla = $numPantalla+1;
                                              if($row2['Permiso']){echo 'checked';}
                                              echo ' value="'.$row2['PKPantalla'].'">';
                                              echo '&nbsp;<label for="chkTodo'.$row2['Pantalla'].'"> <b>'.$row2['Pantalla'].'</b></label></div>&nbsp;&nbsp;';
                                              $fkPantalla = $row2['PKPantalla'];
                                              $stmt3 = $conn->prepare('SELECT * FROM permisos_funciones INNER JOIN funciones on funciones.PKFuncion = permisos_funciones.FKFuncion WHERE FKUsuario = :id AND FKPantalla = :fkPantalla');
                                              $stmt3->bindValue(':id',$usuarioId);
                                              $stmt3->bindValue(':fkPantalla',$fkPantalla);
                                              $stmt3->execute();
                                              while($row3 = $stmt3->fetch()){
                                                echo '<div class="col-lg-1" style="padding:5px;"><input type="checkbox" name="chk'.$row2['Pantalla'].$row3['Funcion'].'" class="chkAvailable chkRh chkFuncion" id="chkFuncion'.$numFuncion.'"';
                                                $numFuncion++;
                                                if($row3['Permiso']){echo 'checked';}
                                                echo ' value="'.$row3['PKFuncion'].'">';
                                                echo '&nbsp;<label for="chkTodo'.$row3['Funcion'].'"> '.$row3['Funcion'].'</label></div>&nbsp;&nbsp;';
                                              }
                                              echo "</div>";

                                            }
                                            $totalPantallas = $numPantalla - 1;
                                          echo'</div>
                                        </div>
                                      </div>
                                      ';
                                      $z++;
                                    }
                                    echo '</div>';
                                  }
                                  ?>
                                </div>
                              </div>
                              <input type="hidden" id="txtId" name="txtId" value="<?=$id;?>">
                              <input type="hidden" id="txtRol" name="txtRol" value="<?=$fkRol;?>">
                              <input type="hidden" name="txtTotalPantallas" id="txtTotalPantallas" value="<?=$totalPantallas;?>">
                              <input type="submit" class="btnesp espAgregar float-right" name="btnGuardar" id="btnGuardar" value="Guardar">
                            </form>
                          </div>
                          <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            Como estas
                          </div>
                          <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                            bien gracias a Dios y tu ?
                          </div>
                        </div>
                      </div>
                    </div>


                </div>
              </div>

            </div>
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <?php
        $rutaf = "../../../";
        require_once('../../../footer.php');
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

  <script>
    function refrescar(){
      //$("#alertaTareas").load('../../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUser'];?>+'&ruta='+'<?=$ruta;?>');
    }
    $(document).ready(function() {
      var numPantallas = $('.chkPantalla').length;
      var numFunciones = $('.chkFuncion').length;
      $('#btnGuardar').click(function(){
        var id = $("#txtId").val();
        //var contador = 1;
        var datosPermisos = "idUsuario="+id+"&numPantallas="+numPantallas+"&numFunciones="+numFunciones;
        var permisos;
        var pantallas;
        for(var contador = 1;contador<numPantallas+1;contador++){
            var checado = $("#chkTodo"+contador).is(':checked');
            var idPantalla = $("#chkTodo"+contador).val();
            permisos = "&Permiso"+contador;
            pantallas = "&Pantalla"+contador;
            datosPermisos = datosPermisos +permisos+"="+checado;
            datosPermisos = datosPermisos +pantallas+"="+idPantalla;
        }

        for(var contador = 1;contador<numFunciones+1;contador++){
            var checado = $("#chkFuncion"+contador).is(':checked');
            var idFuncion = $("#chkFuncion"+contador).val();
            permisosFunciones = "&PermisoFuncion"+contador;
            funciones = "&Funcion"+contador;
            datosPermisos = datosPermisos +permisosFunciones+"="+checado;
            datosPermisos = datosPermisos +funciones+"="+idFuncion;
        }

        alert(datosPermisos);
        $.ajax({
          url: 'cambiar_Rol_Empleado_2.php?'+datosPermisos,
          data: datosPermisos,
          type: 'POST',
          success:function(){
            alert("exito");
          }
        });
        return false;
      });
    });
  </script>
  <script> var ruta = "../../../";</script>
  <script src="../../../../js/sb-admin-2.min.js"></script>

</body>

</html>
<?php
if($redirect == 1){
    sleep(3);
    ?>
    <script>
    window.location.replace("../../index.php");
    </script>

   <?php
}
?>

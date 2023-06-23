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
  <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Timlid | Editar Anticipo</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script type="text/javascript" src="js\anticipo_ver.js"></script>
  <!-- Core plugin JavaScript-->
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../js/sweet/sweetalert2.js"></script>
  <script src="../../js/validaciones.js"></script>
  <script src="../../js/lobibox.min.js"></script>
 

  
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  
  

  <!-- Page level plugins -->
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.bootstrap4.min.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>

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
  <link href="../../css/lobibox.min.css" rel="stylesheet">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
  

  <!-- Personales -->
  <link href="css\menus.css" rel="stylesheet">
  <link rel="stylesheet" href="../../css/notificaciones.css">
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="../../js/jquery.redirect.min.js"></script>
</head>

<body id="page-top" class="sidebar-toggled">

  

  <div id="wrapper">
    <!-- Sidebar -->
    <?php
      $titulo = "Pagos-anticipos";
      $ruta = "../";
      $ruteEdit = $ruta . "central_notificaciones/";
      require_once '../menu3.php';
    ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      
      

        <!-- Topbar -->
        <?php
          $rutatb = "../";
          $backIcon = true;
          $icono = '../../img/icons/CUENTAS POR PAGAR.svg';
          require_once "../topbar.php";
          $_SESSION["actualizado"]= "NO";
        ?>
        <!-- End of Topbar -->
        <input type="hidden" id="txtUsuario" value="<?=$_SESSION['PKUsuario'];?>">
        <input type="hidden" id="txtRuta" value="<?=$ruta;?>">
        <input type="hidden" id="txtEdit" value="<?=$ruteEdit;?>">
        <input type="hidden" id="idpago" value="<?php echo (Int)($_GET['id']); ?>">
      <div id="content-wrapper" class="d-flex flex-column">
          <!-- Comprobar permisos para estar en la pagina -->
          <?php
          ///Primera parte comprueba si puede ver
            $pkuser = $_SESSION["PKUsuario"];
            $stmt = $conn->prepare("Select funcion_ver, funcion_agregar, funcion_editar, funcion_eliminar, funcion_exportar, 
            pantalla_id, fp.perfil_id from funciones_permisos as fp inner join usuarios as us 
            on fp.perfil_id = us.perfil_id where us.id = $pkuser and pantalla_id = 60");
                $stmt->execute();
                $row = $stmt->fetch();
                //Ponemos en el DOM el permiso ver
                echo ('<input id="ver" type="hidden" value="'.$row['funcion_ver'].'">');
                //Ponemos en el DOM el permiso editar.
                echo ('<input id="edit" type="hidden" value="'.$row['funcion_editar'].'">');
          ?>
        <div id="content">
          <!-- Begin Page Content -->
          <div class="card mb-4">
              <div class="card-header">
                Editar un Anticipo
              </div>
              <div class="container-fluid">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                              <!-- Example single danger button -->
                              <div class="form-group">
                              <label for="prov_id"></label>

                                <div class="row">
                                  <div class="col-sm-4">
                                  <input type="hidden" id="user_id" value="<?php echo (Int)($_GET['id']); ?>" />
                                    <!-- <div class="row"> -->
                                    <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                                    <label for="cmbProveedor">Proveedor:*</label>
                                            <select name="cmbProveedor " class="form-select disabled" id="cmbProveedor" aria-label="Default select example" onchange="validateSelects('cmbProveedor', 'invalid-nombreProv')">
                                            </select>
                                            <div class="invalid-feedback" id="invalid-nombreProv">El producto debe tener un fecha de Factura.</div>
                                    </div>
                                    <!-- </div> -->
                                  </div>
                                  <div class="col-sm-4">
                                  <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                                    <label for="usr">Fecha:*</label>
                                      <div class="col-sm input-group pegar">
                                        <input  class="form-control disabled" type="date" name="txtfecha" value="<?php echo (date('Y-m-d')); ?>" id="txtfecha"  max="<?php echo (date('Y-m-d')); ?>">
                                        <div class="invalid-feedback" id="invalid-nombreProd">El producto debe tener un fecha de Factura.</div>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-sm-4">
                                  <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                                    <label for="cmbTipoPag">Tipo:*</label>
                                    <input type="hidden" id="tipopagoid">
                                      <select  name="cmbTipoPag" class="form-select disabled" id="cmbTipoPag" aria-label="Default select example" onClick="click(this)" onchange="validateSelects('cmbTipoPag', 'invalid-tipo')">
                                      </select>
                                      <div class="invalid-feedback" id="invalid-tipo">Campo requerido</div>
                                  </div>
                                  </div>
                                </div>
                                <br>

                                <div class="row">
                                <div class="col-sm-4">
                                  <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                                    <label for="cmbCuenta">Cuenta:*</label>
                                      <select class="form-select disabled" name="cmbCuenta"  id="cmbCuenta" aria-label="Default select example" onchange="validateSelects('cmbCuenta', 'invalid-cuenta')"></select>
                                      <div class="invalid-feedback" id="invalid-cuenta">El producto debe tener un fecha de Factura.</div>
                                  </div>
                                </div>
                                <div class="col-sm-4">
                                  <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                                    <label for="usr">Referencia:</label>
                                        <div class="col-sm input-group pegar">
                                            <input type="text" maxlength="50" class="form-control alphaNumeric-only disabled" name="txtreferencia" id="txtreferencia" value="" maxlength="50" placeholder="Ej. AA - 0001">
                                            <div class="invalid-feedback" id="invalid-reference">El producto debe tener una clave interna.</div>
                                        </div>
                                  </div>
                                </div>
                                <div class="col-sm-4">
                                  <div class="form-group">
                                    <label for="textareaCoemtarios">Comentarios</label>
                                    <textarea class="form-control alphaNumeric-only disabled" id="textareaCoemtarios" rows="3" maxlength="140"></textarea>
                                    <div class="invalid-feedback" id="invalid-txtTotal">Maximo 140 caracteres en el comenario.</div>
                                  </div>
                                </div>
                              </div>
                              
                              <div class="row">
                                <div class="col-sm-4">
                                    <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                                      <label for="usr">Total:*</label>
                                          <div class="col-sm input-group pegar">
                                              <input  type="text" maxlength="50" class="form-control disabled" name="txtTotal" id="txtTotal" value="0"  required maxlength="50" placeholder="Ej. AA - 0001" onkeyup="validEmptyInput('txtTotal', 'invalid-txtTotal', 'Campo requerido')">
                                              <div class="invalid-feedback" id="invalid-txtTotal">El producto debe tener una clave interna.</div>
                                          </div>
                                    </div>
                                  </div>
                                  <div class="col-sm-4">
                                                              
                                  </div>                             
                                <div class="col-sm-4">
                                    <label class="float-right">* Campos requeridos</label>                                    
                                </div>
                              </div>
                              <div class="card-body">
                                <div class="table-responsive">
                                  <table class="table" id="tblcuentas" width="100%" cellspacing="0">
                                    <thead>
                                      <tr>
                                        <th>Proveedor</th>
                                        <th>Folio de Factura</th>
                                        <th>Serie de Factura</th>
                                        <th>Fecha de Vencimiento</th>
                                        <th>Importe</th>
                                        <th>Saldo insoluto</th>
                                        <th>Pago</th>
                                        <th>Estatus</th>
                                        <th>Id</th>
                                      </tr>
                                    </thead>
                                  </table>
                                </div>
                              </div>
                              <?php
                              // require_once 'modal_alert_confirm.php';
                              ?>
                                <?php
                                //  require_once 'modal_alert.php';
                                ?>
                              <br><br><br>
                              <!-- Footer -->
                              <?php
                                $rutaf = "../";
                                require_once '../footer.php';
                              ?>
                              <!-- End of Footer -->

                              </div>
                              <!-- End of Content Wrapper -->
                          </div>
                      </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
  <!-- End of Page Wrapper -->
  <script>
    //VAlidar que no este vacio
  function validEmptyInput(inputID, invalidDivID, textInvalidDiv) {
    if (!$("#" + inputID).val()) {
      $("#" + inputID).addClass("is-invalid");
      $("#" + invalidDivID).css("display", "block");
      $("#" + invalidDivID).text(textInvalidDiv);
    } else {
      $("#" + inputID).removeClass("is-invalid");
      $("#" + invalidDivID).css("display", "none");
      $("#" + invalidDivID).text(textInvalidDiv);
    }
  }

  //Validar los selects
  function validateSelects(selectID,invalidDivId){
    textInvalidDiv = "Campo requerido";
  if (($('select[name='+selectID+'] option').filter(':selected').val())=="f") {
    $("#" + selectID).addClass("is-invalid");
    document.getElementById(invalidDivID).style.display = 'block';
    $("#" + invalidDivID).text(textInvalidDiv);
  } else {
    $("#" + selectID).removeClass("is-invalid");
    document.getElementById(invalidDivID).style.display = 'none';
    $("#" + invalidDivID).text("");
  }
  
  }
  //Separar los numero en grupos de 3
  function numberWithSpaces(inputID) {
    var parts = $("#" + inputID).val().toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    var union =parts.join(".");
    $("#" + inputID).val(union);
}
var ar=[];
	$("input:checkbox").change(function() {
  	ar.length=0;
    $("input:checkbox").each ( function() {
    	if ($(this).is(':checked')) {
      	ar.push($(this).val());
      }
    });
    console.log(JSON.stringify(ar));
    alert(JSON.stringify(ar));
  });
</script>
<script>
  loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
  setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit')
    .val());
  </script>
  <script src="../../js/slimselect.min.js"></script>
</body>

</html>
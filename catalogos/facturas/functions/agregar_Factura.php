<?php

session_start();
$mensaje = "";
$commit = true;
$resultado = array();
  if(isset($_SESSION["Usuario"])){
      require_once('../../../include/db-conn.php');
        if(isset ($_POST['btnAgregar'])){
          
            if($_FILES["xml_data"]["type"] == "application/xml" || $_FILES["xml_data"]["type"] == "text/xml"){
              
            $xml = simplexml_load_file($_FILES['xml_data']['tmp_name']);

            $idCliente = "0";
            $idFactura = "0";

            try{
            $conn->beginTransaction();
        
                foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor') as $receptor){
                  $rfc = $receptor['Rfc'];
                  $nombre = $receptor['Nombre'];

                  $stmt = $conn->prepare('SELECT c.PKCliente, df.PKDomicilioFiscal FROM clientes AS c LEFT JOIN domicilio_fiscal AS df ON df.FKCliente = c.PKCliente AND df.RFC= :rfc WHERE c.Nombre_comercial = :nombre');
                  $stmt->bindValue(':rfc', $rfc);
                  $stmt->bindValue(':nombre', $nombre);
                  $stmt->execute();

                  $row_cliente = $stmt->fetch();

                  if($row_cliente['PKCliente'] != '')
                  {
                  $stmt = $conn->prepare('SELECT * FROM clientes WHERE Nombre_comercial= :nombre');
                  $stmt->execute(array(':nombre'=>$nombre));
                  $row = $stmt->fetch();
                  $idCliente = $row['PKCliente'];

                  }else{
                  $stmt = $conn->prepare('INSERT INTO clientes (Nombre_comercial, Medio, FKEstatus, FechaAlta) VALUES(:razonSocial,"Factura",4, DATE(now()))');
                  $stmt->bindValue(':razonSocial',$nombre);
                  $stmt->execute();
                  $idCliente = $conn->lastInsertId();
                  }

                  if($row_cliente['PKDomicilioFiscal'] != '')
                  {
                  $stmt = $conn->prepare('SELECT * FROM domicilio_fiscal WHERE RFC= :rfc');
                  $stmt->execute(array(':rfc'=>$rfc));
                  $row = $stmt->fetch();
                  $idDomicilioFiscal= $row['PKDomicilioFiscal'];

                  }else{
                  $stmt = $conn->prepare('INSERT INTO domicilio_fiscal (Razon_Social, RFC, FKCliente) VALUES (:razonSocial,:rfc,:idcliente)');
                  $stmt->bindValue(':razonSocial',$nombre);
                  $stmt->bindValue(':rfc',$rfc);
                  $stmt->bindValue(':idcliente',$idCliente);
                  $stmt->execute();
                  $idDomicilioFiscal = $conn->lastInsertId();
                  }


                }


                $y = 0;
                foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante){
                    $folio = $cfdiComprobante['Folio'];
                    $importe = $cfdiComprobante['Total'];
                    $fecha = $cfdiComprobante['Fecha'];
                    $estatus = "Pendiente";
                    $rfc = $cfdiComprobante['Rfc'];
                    $nombre = $cfdiComprobante['Nombre'];


                    /***************** Evitar ejecuciones inecesarias**************************/
                    $stmt = $conn->prepare('SELECT count(*) FROM facturas WHERE PKFactura= :id');
                    $stmt->execute(array(':id'=>$folio));
                    $number_of_rows = $stmt->fetchColumn();
                    if($number_of_rows > 0)
                    {
                      $commit = false;
                      $mensaje = "Tu ya cuentas con esta factura en base de datos.";
                    }else{
                          try{
                          $stmt = $conn->prepare('INSERT INTO facturas (PKFactura,Importe,Fecha_de_Emision,Estatus,FKDomiciliofiscal)
                          VALUES(:folio,:importe,:fecha,:estatus,:domicilio_fiscal)');
                          $stmt->bindValue(':folio',$folio);
                          $stmt->bindValue(':importe',$importe);
                          $stmt->bindValue(':fecha',$fecha);
                          $stmt->bindValue(':estatus',$estatus);
                          $stmt->bindValue(':domicilio_fiscal',$idDomicilioFiscal);
                          $stmt->execute();
                          $idFactura = $folio;
                          }catch(PDOException $ex){
                          echo $ex->getMessage();
                          }

                          foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Conceptos//cfdi:Concepto') as $Concepto){
                          $clave = $Concepto['NoIdentificacion'];
                          $descripcion = $Concepto['Descripcion'];
                          $cantidad = $Concepto['Cantidad'];
                          $unidad = $Concepto['Unidad'];
                          $valorUnitario = $Concepto['ValorUnitario'];
                          $importe = $Concepto['Importe'];

                          $stmt = $conn->prepare('SELECT count(*) FROM productos WHERE Clave= :clave');
                          $stmt->execute(array(':clave'=>$clave));
                          $number_of_rows = $stmt->fetchColumn();

                          $resultado[0][$y] = $clave;
                          $resultado[1][$y] = $descripcion;

                          if($number_of_rows > 0)
                          {
                            $stmt = $conn->prepare('SELECT PKProducto FROM productos WHERE Clave= :clave');
                            $stmt->execute(array(':clave'=>$clave));
                            $row = $stmt->fetch();
                            $idProducto = $row['PKProducto'];
                            try{
                            $stmt = $conn->prepare('INSERT INTO ventas (FKProducto,FKFactura,Cantidad)
                            VALUES(:fkProducto,:fkFactura,:cantidad)');
                            $stmt->bindValue(':fkProducto',$idProducto);
                            $stmt->bindValue(':fkFactura',$idFactura);
                            $stmt->bindValue(':cantidad',$cantidad);
                            $stmt->execute();
                            
                            $resultado[2][$y] = "El producto se encuentra guardado.";
                            $y++;

                            }catch(PDOException $ex){
                            echo $ex->getMessage();
                            }
                          }else{
                            $commit = false;
                            $resultado[2][$y] = "El producto no se encuentra guardado, favor de darlo de alta para ingresar la factura.";
                            $y++;
                            //ya no se deben dar de alata productos que no esten en la base de datos 
                            /*try{
                            $stmt = $conn->prepare('INSERT INTO productos (Clave,Descripcion,Unidad_de_Medida,Precio_Unitario)
                            VALUES(:clave,:descripcion,:unidad_de_Medida,:precio_Unitario)');
                            $stmt->bindValue(':clave',$clave);
                            $stmt->bindValue(':descripcion',$descripcion);
                            $stmt->bindValue(':unidad_de_Medida',$unidad);
                            $stmt->bindValue(':precio_Unitario',$cantidad);
                            $stmt->execute();
                            $idProducto = $conn->lastInsertId();

                            $stmt = $conn->prepare('INSERT INTO ventas (FKProducto,FKFactura,Cantidad)
                            VALUES(:fkProducto,:fkFactura,:cantidad)');
                            $stmt->bindValue(':fkProducto',$idProducto);
                            $stmt->bindValue(':fkFactura',$idFactura);
                            $stmt->bindValue(':cantidad',$cantidad);
                            $stmt->execute();
                            }catch(PDOException $ex){
                            echo $ex->getMessage();
                            }*/
                          }
                        }
                    }
                /***************** Evitar ejecuciones inecesarias**************************/
            }
            if($commit){
              $conn->commit();
              header("location:../index.php");
            }
            else{
              $conn->rollBack();
            }
            }catch(PDOException $ex){
              echo $ex->getMessage();
              $conn->rollBack();
            }
            
          }else{
            $mensaje = "El archivo no es de tipo XML";
          }
    }
  }else {
    header("location:../../dashboard.php");
  }
 ?>
<!DOCTYPE html>
<html lang="es">

<head>
  <link rel="icon" type="image/png" href="../../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Timlid | Agregar factura</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../js/validaciones.js"></script>
  <!-- Core plugin JavaScript-->
  <script src="../../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../../js/sb-admin-2.min.js"></script>

  <!-- Custom fonts for this template-->
  <link href="../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../../../css/sb-admin-2.css" rel="stylesheet">
  <link href="../../../css/dashboard.css" rel="stylesheet">
  <script>
  /*
    $( document ).ready(function() {
      $custom-file-text: (
        en: "Browse",
        es: "Elegir"
      );
    });*/
      /**/
  </script>
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../../dashboard.php">
        <div class="sidebar-brand-icon">
          <i class="fas fa-users"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Timlid</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item">
        <a class="nav-link" href="../../dashboard.php">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>
      <?php
      $ruta = "../../";
      require_once('../../menu3.php');
      ?>
    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>


          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">
            <div id="alertaTareas"></div>
            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION["Usuario"] ?></span>
                <i class="fas fa-user-circle fa-3x"></i>
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Perfil
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Salir
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Agregar factura</h1>
          </div>


          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header">
                  Cargar documento
                </div>
                <div class="card-body">
                    <div class="row">
                      <div class="col-lg-12">

                        <form method="post" id="import_xml" enctype="multipart/form-data">
                          <div class="form-group">
                            <div class="custom-file">
                              <input type="file" class="custom-file-input" name="xml_data" id="customFileLang" lang="es" accept=".xml" required>
                              <label class="custom-file-label" for="customFileLang">Seleccionar Archivo</label>
                            </div>
                          </div>

              <?php 
								if($mensaje != ""){
									echo '<div class="alert alert-danger" role="alert">
											'.$mensaje.'
										  </div>';
                }
                
                if($resultado != "" && isset ($_POST['btnAgregar']) && $mensaje == ""){
                    echo "<table class='table'>
                                          <thead>
                                            <tr>
                                              <th>Archivo</th>
                                              <th>Revisión</th>
                                              <th>Resultado</th>
                                            </tr>
                                          </thead>
                                          <tbody>";

                    for($z = 0;$z < count($resultado[0]);$z++){
                      echo '<tr ';
                          
                          if($resultado[2][$z] != "El producto se encuentra guardado.")
                            echo "class='alert alert-danger'";
                      echo '    >
                                <td>'.$resultado[0][$z].'</td>
                                <td>'.$resultado[1][$z].'</td>
                                <td>'.$resultado[2][$z].'</td>
                            </tr>';
                    }

                    echo "  </tbody>
                          </table>";
                }
							?>
                          <br>
                          <div class="form-group">
                            <button type="submit" class="btn btn-success float-right" name="btnAgregar" id="btn-submit">Archivo</button>
                          </div>
                        </form>
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
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy;  Timlid 2020</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>


    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">¿En serio quieres salir?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">Selecciona "Salir" Para cerrar tu sesión</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
            <a class="btn btn-primary" href="../../logout.php">Salir</a>
          </div>
        </div>
      </div>
    </div>

  <script>
    $(document).ready(function(){
      $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
        setInterval(refrescar, 5000);
      });
    function refrescar(){
      $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
    }
  </script>

</body>

</html>

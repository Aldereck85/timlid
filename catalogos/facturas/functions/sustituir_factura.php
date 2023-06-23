<?php

session_start();

  if(isset($_SESSION["Usuario"])){
    require_once('../../../include/db-conn.php');
      if(isset ($_POST['btnAgregar'])){

        $id = $_GET['id'];
        $factura = $id;
        $idCliente = "0";
        $idFactura = "0";
        $estatus = "Cancelado";
        $xml = simplexml_load_file($_FILES['xml_data']['tmp_name']);

        $xmlArray = array();
        $productosEnviados = array();

        foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Conceptos//cfdi:Concepto') as $Concepto){
            $cto = strval($Concepto['NoIdentificacion']);
            array_push($xmlArray, $cto);
        }
        $stmt = $conn->prepare('SELECT FKProducto,Clave FROM productos_en_envio INNER JOIN productos on FKProducto = PKProducto WHERE FKFactura = :factura');
        $stmt->execute(array(':factura' => $factura));
        while (($row = $stmt->fetch()) !== false) {
          array_push($productosEnviados, $row['Clave']);
          if (in_array($row['Clave'], $xmlArray)) {
              $bandera = 1;
          }else{
            $bandera = 0;
          }
        }

        if($bandera == 0){
            echo "Tu factura no puede sustituir a la anterior debido a que no cuenta con todos los productos que ya fueron puestos en envio";
        }else{
          $stmt = $conn->prepare('UPDATE facturas set Estatus= :estatus WHERE PKFactura = :id');
          $stmt->bindValue(':estatus',$estatus);
          $stmt->bindValue(':id', $id, PDO::PARAM_INT);
          $stmt->execute();

          /***************************************/
          $idCliente = "0";
          $idFactura = "0";
          foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor') as $receptor){
            $rfc= $receptor['Rfc'];
            $nombre = $receptor['Nombre'];

            $stmt = $conn->prepare('SELECT count(*) FROM clientes WHERE RFC= :rfc');
            $stmt->execute(array(':rfc'=>$rfc));
            $number_of_rows = $stmt->fetchColumn();
            if($number_of_rows > 0)
            {
              $stmt = $conn->prepare('SELECT * FROM clientes WHERE RFC= :rfc');
              $stmt->execute(array(':rfc'=>$rfc));
              $row = $stmt->fetch();
              $idCliente = $row['PKCliente'];

            }else{
              $stmt = $conn->prepare('INSERT INTO clientes (Razon_Social,RFC)
              VALUES(:razonSocial,:rfc)');
              $stmt->bindValue(':razonSocial',$nombre);
              $stmt->bindValue(':rfc',$rfc);
              $stmt->execute();
              $idCliente = $conn->lastInsertId();
            }
          }

          /**************************************/
          foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante){
              $folio = $cfdiComprobante['Folio'];
              $importe = $cfdiComprobante['Total'];
              $fecha = $cfdiComprobante['Fecha'];
              $estatus = "Enviando";
              $rfc = $cfdiComprobante['Rfc'];
              $nombre = $cfdiComprobante['Nombre'];


              /***************** Evitar ejecuciones inecesarias**************************/
                $stmt = $conn->prepare('SELECT count(*) FROM facturas WHERE PKFactura= :id');
                $stmt->execute(array(':id'=>$folio));
                $number_of_rows = $stmt->fetchColumn();
                if($number_of_rows > 0)
                {
                  echo "Tu ya cuentas con esta factura en base de datos";
                }else{
                  try{
                    $stmt = $conn->prepare('INSERT INTO facturas (PKFactura,Importe,Fecha_de_Emision,Estatus,FKCliente)
                    VALUES(:folio,:importe,:fecha,:estatus,:cliente)');
                    $stmt->bindValue(':folio',$folio);
                    $stmt->bindValue(':importe',$importe);
                    $stmt->bindValue(':fecha',$fecha);
                    $stmt->bindValue(':estatus',$estatus);
                    $stmt->bindValue(':cliente',$idCliente);
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
                      }catch(PDOException $ex){
                        echo $ex->getMessage();
                      }
                    }else{
                      try{
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
                      }

                    }
                  }
                  try{
                    //$stmt = $conn->prepare('UPDATE envios INNER JOIN productos_en_envio ON envios.FKFactura = productos_en_envio.FKFactura SET envios.FKFactura = :folio, productos_en_envio.FKFactura = :folio WHERE envios.FKFactura = :id');
                    $stmt = $conn->prepare('UPDATE envios SET FKFactura = :folio WHERE FKFactura= :id');
                    $stmt->bindValue(':folio',$folio);
                    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();

                  }catch(PDOException $ex){
                    echo $ex->getMessage();
                  }

                  try{
                    //$stmt = $conn->prepare('UPDATE envios INNER JOIN productos_en_envio ON envios.FKFactura = productos_en_envio.FKFactura SET envios.FKFactura = :folio, productos_en_envio.FKFactura = :folio WHERE envios.FKFactura = :id');
                    $stmt = $conn->prepare('UPDATE productos_en_envio SET FKFactura = :folio WHERE FKFactura= :id');
                    $stmt->bindValue(':folio',$folio);
                    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                    header("location:../index.php");
                  }catch(PDOException $ex){
                    echo $ex->getMessage();
                  }
                }
                /***************** Evitar ejecuciones inecesarias**************************/
        }
          /**************************************/
        }

      }
  }else {
    header("location:../../index.php");
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

  <title>Timlid | Sustituir factura</title>

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
        if($_SESSION["FKRol"] == 1 || $_SESSION["FKRol"] == 4){
          echo'
          <hr class="sidebar-divider">
          <div class="sidebar-heading">
            Recursos humanos
          </div>

          <!-- Nav Item - Pages Collapse Menu -->
          <li class="nav-item">
            <a class="nav-link collapsed" href="../../empleados/">
              <i class="fas fa-address-book"></i>
              <span>Empleados</span>
            </a>
          </li>
          ';
          if($_SESSION["FKRol"] == 4){
            echo '<li class="nav-item">
              <a class="nav-link collapsed" href="../../usuarios/">
                <i class="fas fa-address-card"></i>
                <span>Usuarios</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../../puestos/">
                <i class="fas fa-briefcase"></i>
                <span>Puestos</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../../turnos/">
                <i class="far fa-calendar-alt"></i>
                <span>Turnos</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../../locaciones">
                <i class="fas fa-map-marker-alt"></i>
                <span>Locaciones</span></a>
            </li>';
          }
        }
       ?>

      <hr class="sidebar-divider">
      <div class="sidebar-heading">
        Ventas
      </div>
      <li class="nav-item">
        <a class="nav-link" href="../../facturas/">
          <i class="fas fa-file-invoice"></i>
          <span>Facturas</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../../envios/">
          <i class="fas fa-shipping-fast"></i>
          <span>Envios</span></a>
      </li>
      <?php
        if($_SESSION["FKRol"] == 4){
          echo '<hr class="sidebar-divider">
          <div class="sidebar-heading">
            Compras
          </div>
          <li class="nav-item">
            <a class="nav-link" href="../../productos/">
              <i class="fas fa-boxes"></i>
              <span>Productos</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../../paqueterias/">
              <i class="fas fa-dolly"></i>
              <span>Paqueterias</span></a>
          </li>';
        }
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
            <h1 class="h3 mb-0 text-gray-800">Sustituir factura</h1>
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
                              <input type="file" class="custom-file-input" name="xml_data" id="customFileLang" lang="es" required>
                              <label class="custom-file-label" for="customFileLang">Seleccionar Archivo</label>
                            </div>
                          </div>

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

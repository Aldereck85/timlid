<?php

$rutes = $_GET['ruta'];
require_once('../include/db-conn.php');

  if(isset($_SESSION['PKUsuario'])){
    $usuario = $_SESSION['PKUsuario'];
  }else{
    $usuario = $_GET['user'];
  }

?>
<!-- Nav Item - Alerts -->
<li id="notificationContainer" class="nav-item dropdown no-arrow mx-1">
  <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fas fa-umbrella-beach"></i>
    <!-- Counter - Alerts -->
    <?php
      $stmt = $conn->prepare('SELECT FKEmpleado FROM usuarios WHERE PKUsuario = :id');
      $stmt->bindValue(':id', $usuario, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch();

      $stmt = $conn->prepare('SELECT count(*) FROM organigrama as o INNER JOIN organigrama as o2 ON o.PKOrganigrama = o2.ParentNode INNER JOIN permiso_vacaciones as pv ON pv.FKEmpleado = o2.FKEmpleado WHERE o.FKEmpleado = :id AND pv.Estatus = 0');
      $stmt->bindValue(':id', $row['FKEmpleado'], PDO::PARAM_INT);
      $stmt->execute();
      $no_permisos_nuevos = $stmt->fetchColumn();

      $stmt = $conn->prepare('SELECT pv.DiasVacaciones,DAY(pv.FechaIni), MONTH(pv.FechaIni), YEAR(pv.FechaIni) FROM organigrama as o INNER JOIN organigrama as o2 ON o.PKOrganigrama = o2.ParentNode INNER JOIN permiso_vacaciones as pv ON pv.FKEmpleado = o2.FKEmpleado WHERE o.FKEmpleado = :id AND pv.Estatus = 0');
      $stmt->bindValue(':id', $row['FKEmpleado'], PDO::PARAM_INT);
      $stmt->execute();

      if($no_permisos_nuevos > 0){

    ?>
    <span id="contadorTareas" class="badge badge-danger badge-counter"><?=$no_permisos_nuevos?>+</span>
  <?php } ?>
  </a>

<!-- Dropdown - Alerts -->
<div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">

  <h6 class="dropdown-header">
    Permisos vacaciones
  </h6>
  <?php
    if($no_permisos_nuevos > 0){
      while($row = $stmt->fetch()){
        $fecha = $row['DAY(pv.FechaIni)'].'/'.$row['MONTH(pv.FechaIni)'].'/'.$row['YEAR(pv.FechaIni)'];
        $permiso = "Solicitaron ".$row['DiasVacaciones']." día(s) de vacaciones.";
        echo '
          <a class="dropdown-item d-flex align-items-center" href="'.$rutes.'vacaciones/">
            <div class="mr-3">
              <div class="icon-circle bg-primary">
                <i class="far fa-map text-white"></i>
              </div>
            </div>
            <div id="notification-latest">
                  <div id="fechaTarea" class="small text-gray-500">'.$fecha.'</div>
                  <span id="tarea" class="font-weight-bold">'.$permiso.'</span>
            </div>
          </a>
        ';
      }
    }else{
      echo '
        <a class="dropdown-item d-flex align-items-center" href="'.$rutes.'vacaciones/">
          <div class="mr-3">
            <div class="icon-circle bg-secondary">
              <i class="far fa-map text-white"></i>
            </div>
          </div>
          <div id="notification-latest">
            <span id="tarea" class="font-weight-bold">No hay nuevas solicitudes de vacaciones</span>
          </div>
        </a>
      ';
  }
  ?>
</div>
</li>

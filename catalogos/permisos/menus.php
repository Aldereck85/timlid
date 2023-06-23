<?php
$pkUsuario = $_SESSION['PKUsuario'];
$stmt1 = $conn->prepare('SELECT * FROM permisos_secciones INNER JOIN secciones ON PKSeccion = FKSeccion WHERE FKUsuario = :id AND Permiso = 1');
$stmt1->bindValue(':id',$pkUsuario);
$stmt1->execute();

echo '
<ul class="navbar-nav sidebar accordion toggled side-menu" id="accordionSidebar">

  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="'.$ruta.'dashboard.php">
    <div class="sidebar-brand-icon iconShadow">
    </div>
  </a>

  <!-- Divider -->
  <hr class="sidebar-divider bg-light my-0">

  <!-- Nav Item -->
  <li class="nav-item">
    <a class="nav-link menu-icons" href="'.$ruta.'dashboard.php">
      <img src="'.$ruta.'../img/menu/dashboard.svg" width="35px" data-toggle="tooltip" data-placement="right"
        title="Dashboard">
    </a>
  </li>';

  while (($row1 = $stmt1->fetch()) !== false) {
    echo'
    <li class="nav-item">
      <a class="nav-link menu-icons collapsed" href="#" data-toggle="collapse" data-target="#'.$row1["Siglas"].'" aria-expanded="true" aria-controls="collapseTwo">
          <img src="'.$ruta.'../img/menu/'.$row1["Icono"].'" width="35px"  id="imagen-icono" data-toggle="tooltip" data-placement="right" title="'.$row1["Seccion"].'">
      </a>
      <div id="'.$row1["Siglas"].'" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-subMenu py-2 collapse-inner rounded">';
        $stmt2 = $conn->prepare('SELECT * FROM permisos_pantallas INNER JOIN pantallas ON PKPantalla = FKPantalla WHERE FKUsuario = :id AND Permiso = 1 AND FKSeccion = :seccion');
        $stmt2->bindValue(':id',$pkUsuario);
        $stmt2->bindValue(':seccion',$row1["PKSeccion"]);
        $stmt2->execute();
        while (($row2 = $stmt2->fetch()) !== false) {
          echo '
          <a class="collapse-item linkWhite" href="'.$ruta.$row2["Url"].'">'.$row2["Pantalla"].'</a>
          ';
        }
  echo'</div>
      </div>
    </li>
    ';
  }

  ?>


</ul>
<!-- End of Sidebar -->

<script>
$(function() {
  $('[data-toggle="tooltip"]').tooltip();
})
</script>

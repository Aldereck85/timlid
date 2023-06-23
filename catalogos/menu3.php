<?php
$pkUsuario = $_SESSION['PKUsuario'];

$stmtPerfil = $conn->prepare('SELECT perfil_id FROM usuarios WHERE id = :idUsario');
$stmtPerfil->execute([':idUsario' => $pkUsuario]);
$resPerfil = $stmtPerfil->fetch(PDO::FETCH_ASSOC);
$perfil = $resPerfil['perfil_id'];

$querySecciones = 'SELECT s.*
FROM secciones AS s
INNER JOIN permisos_secciones AS ps ON s.id = ps.seccion_id
WHERE ps.perfil_permiso_id = :perfil AND ps.permiso = 1 AND s.seccion != "Configuración" ORDER BY s.orden';
$stmtSec = $conn->prepare($querySecciones);
$stmtSec->execute([':perfil' => $perfil]);
$secciones = $stmtSec->fetchAll(PDO::FETCH_ASSOC);
?>

<ul class="navbar-nav sidebar accordion toggled side-menu" id="accordionSidebar">
  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= $ruta ?>dashboard.php">
    <div class="sidebar-brand-icon iconShadow">
    </div>
  </a>
  <!-- Divider -->
  <hr class="sidebar-divider bg-light my-0">
  <!-- Nav Item -->
  <?php foreach ($secciones as $seccion) { $url = $seccion["url"] !== null && $seccion["url"] !== "" ? $ruta . $seccion["url"] : '#'?>
    <li class="nav-item">
  <?php if($seccion["url"] !== null && $seccion["url"] !== "") {?>
      <a class="nav-link" href="<?=$url;?>" >
  <?php } else { ?>  
      <a class="nav-link menu-icons collapsed" href="<?=$url;?>" data-toggle="collapse" data-target="#<?= $seccion["siglas"] ?>" aria-expanded="true" 
      aria-controls="collapseTwo">
  <?php } ?>
        <img src="<?= $ruta ?>../img/menu/<?= $seccion["icono"] ?>" width="28px" id="imagen-icono">
        <span class="nav-item--name mt-1"><?= $seccion['seccion']?></span>
      </a>
      <div id="<?= $seccion["siglas"] ?>" class="collapse shadow" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-subMenu py-1 collapse-inner rounded-0">
          <?php
          $sql_com = (int)$_SESSION['tim_impulsa'] === 1 ? " AND p.id <> 16 AND p.id <> 68 " : "";
          $queryPantallas = 'SELECT p.url, p.pantalla
          FROM pantallas AS p
          INNER JOIN permisos_pantallas AS pp ON p.id = pp.pantalla_id
          WHERE pp.perfil_permiso_id = :perfil AND pp.permiso = 1 AND p.status = 1 AND p.seccion_id = :seccion '.$sql_com.' ORDER BY p.orden';
          $stmtPant = $conn->prepare($queryPantallas);
          $stmtPant->execute([':perfil' => $perfil, ':seccion' => $seccion["id"]]);
          $pantallas = $stmtPant->fetchAll(PDO::FETCH_ASSOC);
          ?>
          <?php foreach ($pantallas as $pantalla) { if($pantalla["pantalla"] !== 'Punto de Venta') {?>
            <a class="collapse-item linkWhite rounded-0" href="<?= $ruta . $pantalla["url"] ?>"><?= $pantalla["pantalla"] ?></a>
          <?php }} ?>
        </div>
      </div>
    </li>
  <?php } ?>
</ul>
<!-- End of Sidebar -->
<script>
  $(function() {
    $('[data-toggle="tooltip"]').tooltip();
  })
</script>
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
  <div>
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?=$ruta?>dashboard.php">
      <div class="sidebar-brand-icon iconShadow">
        <img src="<?=$ruta?>../img/header/timlidIsotipoBlanco.png" width="30px" id="imagen-icono" class="jump">
      </div>
    </a>

    <!-- Divider -->
    <!--<hr class="sidebar-divider my-0">-->

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
      <a class="nav-link menu-icons" href="<?=$ruta?>dashboard.php">
        <img src="<?=$ruta?>../img/menu/dashboard.svg" width="35px" id="imagen-icono" data-toggle="tooltip"
          data-placement="right" title="Dashboard">
      </a>
    </li>
    <?php
if ($_SESSION["FKRol"] == 1 || $_SESSION["FKRol"] == 6) {
    ?>

    <li class="nav-item">
      <a class="nav-link menu-icons collapsed" href="#" data-toggle="collapse" data-target="#recursos_humanos"
        aria-expanded="true" aria-controls="collapseTwo">
        <img src="<?=$ruta?>../img/menu/rh.svg" width="35px" id="imagen-icono" data-toggle="tooltip"
          data-placement="right" title="Recursos humanos">
      </a>
      <div id="recursos_humanos" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-subMenu collapse-inner rounded">
          <a class="collapse-item linkWhite" href="<?=$ruta?>empleados/">
            Lista de empleados
          </a>
          <a class="collapse-item linkWhite" href="<?=$ruta?>usuarios/">Usuarios</a>
          <a class="collapse-item linkWhite" href="<?=$ruta?>nomina/">Nomina por turno</a>
          <a class="collapse-item linkWhite" href="<?=$ruta?>bono/">Bono mensual</a>
          <a class="collapse-item linkWhite" href="<?=$ruta?>empleados/estatus_empleado.php">Estatus de
            empleado</a>
          <?php
if ($_SESSION["FKRol"] == 6) {
        ?>
          <a class="collapse-item linkWhite" href="<?=$ruta?>puestos/">Puestos</a>
          <a class="collapse-item linkWhite" href="<?=$ruta?>turnos/">Turnos</a>
          <a class="collapse-item linkWhite" href="<?=$ruta?>locaciones">Locaciones</a>
          <a class="collapse-item linkWhite" href="<?=$ruta?>organigrama">Organigrama</a>
          <?php
}
}
if ($_SESSION["FKRol"] != 6) {
    ?>
          <a class="collapse-item" href="<?=$ruta?>organigrama/organigrama.php"><i class="fas fa-sitemap"></i>
            Organigrama</a>
          <?php
}

if ($_SESSION["FKRol"] == 4) {
    ?>
    <li class="nav-item">
      <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#recursos_humanos" aria-expanded="true"
        aria-controls="collapseTwo">
        <i class="fas fa-user-tie"></i>
        <span>Recursos Humanos</span>
      </a>
      <div id="recursos_humanos" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-subMenu py-2 collapse-inner rounded">
          <a class="collapse-item" href="<?=$ruta?>nomina/"><i class="fas fa-business-time"></i> Nomina por
            turno</a>
          <a class="collapse-item" href="<?=$ruta?>bono/"><i class="fas fa-money-check-alt"></i> Bono
            mensual</a>
          <?php
}
?>
        </div>
      </div>
    </li>

    <li class="nav-item">
      <a class="nav-link collapsed menu-icons" href="#" data-toggle="collapse" data-target="#ventas"
        aria-expanded="true" aria-controls="collapseTwo">
        <img src="<?=$ruta?>../img/menu/ventas.svg" width="35px" id="imagen-icono" data-toggle="tooltip"
          data-placement="right" title="Ventas">
      </a>
      <div id="ventas" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-subMenu py-2 collapse-inner rounded">
          <a class="collapse-item" href="<?=$ruta?>cotizaciones/">Cotizaciones</a>
          <a class="collapse-item" href="<?=$ruta?>cuentas_bancarias/">Cuentas</a>
          <a class="collapse-item" href="<?=$ruta?>facturacion/">Facturación</a>
          <a class="collapse-item" href="<?=$ruta?>clientes/">Clientes</a>
          <a class="collapse-item" href="<?=$ruta?>prospectos/">Prospectos</a>
          <a class="collapse-item" href="<?=$ruta?>envios/">Envíos</a>
          <a class="collapse-item" href="<?=$ruta?>paqueterias/">Paqueterias</a>
          <a class="collapse-item" href="<?=$ruta?>guias/">Guias de envio</a>
        </div>
      </div>
    </li>

    <li class="nav-item">
      <a class="nav-link collapsed menu-icons" href="#" data-toggle="collapse" data-target="#administracion"
        aria-expanded="true" aria-controls="collapseTwo">
        <img src="<?=$ruta?>../img/menu/timdesk.svg" width="35px" id="imagen-icono" data-toggle="tooltip"
          data-placement="right" title="Timdesk">
        <span>Tim desk</span>
      </a>
      <div id="administracion" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-subMenu py-2 collapse-inner rounded">
          <a class="collapse-item" href="<?=$ruta?>proyectos/">Timdesk Pro</a>
          <a class="collapse-item" href="<?=$ruta?>tareas/timdeskLite">Timdesk Lite</a>
        </div>
      </div>
    </li>

    <li class="nav-item">
      <a class="nav-link collapsed menu-icons" href="#" data-toggle="collapse" data-target="#inventarios"
        aria-expanded="true" aria-controls="collapseTwo">
        <img src="<?=$ruta?>../img/menu/inventarios.svg" width="35px" id="imagen-icono" data-toggle="tooltip"
          data-placement="right" title="Inventarios y productos">
      </a>
      <div id="inventarios" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-subMenu py-2 collapse-inner rounded">
          <a class="collapse-item" href="<?=$ruta;?>inventarios_productos/catalogos/proveedores/">Proveedores</a>
          <a class="collapse-item" href="<?=$ruta;?>inventarios_productos/catalogos/orden_compras/">Ordenes de
            compras</a>
          <a class="collapse-item" href="<?=$ruta;?>inventarios_productos/catalogos/entradas_productos/">Entradas</a>
          <a class="collapse-item" href="<?=$ruta;?>inventarios_productos/catalogos/tipos_producto/">Tipos de
            productos</a>
          <a class="collapse-item" href="<?=$ruta;?>inventarios_productos/catalogos/productos/">Lista de productos</a>
          <!--<a class="collapse-item" href="<?//=$ruta; ?>unidad_medida/">Unidades de medida</a>-->
          <a class="collapse-item" href="<?=$ruta;?>inventarios_productos/catalogos/marcas/">Listado de marcas</a>
          <a class="collapse-item" href="<?=$ruta;?>inventarios_productos/catalogos/inventario/">Inventario</a>
          <a class="collapse-item" href="<?=$ruta;?>inventarios_productos/catalogos/categoria_productos/">Categoria de producto</a>
          <!--<a class="collapse-item" href="<?//=$ruta; ?>claves_sat/">Clave productos SAT</a>
          <a class="collapse-item" href="<?//=$ruta; ?>claves_sat_unidades/">Clave unidades SAT</a>
          <a class="collapse-item" href="<?//=$ruta; ?>claves_sat_monedas/">Clave monedas SAT</a>
          -->
        </div>
      </div>
    </li>

    <li class="nav-item">
      <a class="nav-link collapsed menu-icons" href="#" data-toggle="collapse" data-target="#vehiculos"
        aria-expanded="true" aria-controls="collapseTwo">
        <img src="<?=$ruta?>../img/menu/vehiculos.svg" width="35px" id="imagen-icono" data-toggle="tooltip"
          data-placement="right" title="Control vehicular">
      </a>
      <div id="vehiculos" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-subMenu py-2 collapse-inner rounded">
          <a class="collapse-item" href="<?=$ruta;?>vehiculos/">Vehiculos</a>
        </div>
      </div>
    </li>

    <li class="nav-item">
      <a class="nav-link collapsed menu-icons" href="#" data-toggle="collapse" data-target="#textiles"
        aria-expanded="true" aria-controls="collapseTwo">
        <img src="<?=$ruta?>../img/menu/textil.svg" width="35px" id="imagen-icono" data-toggle="tooltip"
          data-placement="right" title="Productos textiles">
      </a>
      <div id="textiles" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-subMenu py-2 collapse-inner rounded">
          <a class="collapse-item" href="<?=$ruta;?>piezas_fabricadas/">Piezas fabricadas</a>
          <a class="collapse-item" href="<?=$ruta;?>rollos/">Rollos</a>
          <a class="collapse-item" href="<?=$ruta;?>productos_fabricados/">Productos fabricados</a>
        </div>
      </div>
    </li>


  <!-- INSUMOS -->
    <li class="nav-item">
      <a class="nav-link collapsed menu-icons" href="#" data-toggle="collapse" data-target="#insumos"
        aria-expanded="true" aria-controls="collapseTwo">
        <img src="<?=$ruta?>../img/menu/insumos.svg" width="35px" id="imagen-icono" data-toggle="tooltip"
          data-placement="right" title="Insumos">
      </a>
      <div id="insumos" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-subMenu py-2 collapse-inner rounded">
          <a class="collapse-item" href="<?=$ruta;?>insumos/catalogos/stock/">Stock</a>
          <a class="collapse-item" href="<?=$ruta;?>insumos/catalogos/entradas/">Entradas</a>
          <a class="collapse-item" href="<?=$ruta;?>insumos/catalogos/salidas/">Salidas</a>
          <a class="collapse-item" href="<?=$ruta;?>insumos/catalogos/tipos/">Tipos de insumo</a>
          <a class="collapse-item" href="<?=$ruta;?>insumos/catalogos/unidades/">Unidades de medida</a>
        </div>
      </div>
    </li>
    
  </div>
</ul>

<script>
$(function() {
  $('[data-toggle="tooltip"]').tooltip();
})
</script>

<!--
    <li class="nav-item">
    <a class="nav-link" href="'.$ruta.'facturas/">
      <i class="fas fa-file-invoice"></i>
      <span>Facturas</span></a>
    </li>
    <li class="nav-item">
    <a class="nav-link" href="'.$ruta.'envios/">
      <i class="fas fa-shipping-fast"></i>
      <span>Envios</span></a>
    </li>
    -->
<?php
/*
if($_SESSION["FKRol"] == 4){
echo '
<div class="sidebar-heading">
Compras
</div>
<li class="nav-item">
<a class="nav-link" href="'.$ruta.'productos/">
<i class="fas fa-boxes"></i>
<span>Productos</span></a>
</li>
<li class="nav-item">
<a class="nav-link" href="'.$ruta.'paqueterias/">
<i class="fas fa-dolly"></i>
<span>Paqueterias</span></a>
</li>';
}*/
?>

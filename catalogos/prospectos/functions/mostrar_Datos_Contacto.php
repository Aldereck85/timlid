<?php
if (isset($_SESSION["Usuario"])) {
    require_once '../../../include/db-conn.php';
    $stmt = $conn->prepare('SELECT clientes.NombreComercial, clientes.Monto_credito, clientes.Dias_credito, medios_contacto_clientes.MedioContactoCliente
    FROM clientes
    LEFT JOIN medios_contacto_clientes ON medios_contacto_clientes.PKMedioContactoCliente = clientes.FKMedioContactoCliente
    WHERE PKCliente= :id');
    $stmt->execute(array(':id' => $idProspecto));
    $row = $stmt->fetch();
    $nombre = $row['NombreComercial'];
    $monto = $row['Monto_credito'];
    $dias = $row['Dias_credito'];
    $medio = $row['MedioContactoCliente'];
} else {
    header("location:../../dashboard.php");
}
?>

<div class="row contenedor-seccion" id="contenedor-contactos">
  <div class="col-lg-12">
    <!-- DataTales Example -->
    <div class="card mb-4">
      <div class="card-header py-3">
        <div class="float-right">
          <div class="button-container2" id="">
            <div class="button-icon-container">
              <a href="" class="btn btn-circle float-right waves-effect waves-light shadow btn-table" id=""
                data-toggle="modal" data-target="#modalAddContacto"><i class="fas fa-plus"></i></a>
            </div>
            <div class="button-text-container">
              <span>Agregar contacto</span>
            </div>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table" id="tblDatosContacto" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Puesto</th>
                <th>Telefono</th>
                <th>Celular</th>
                <th>Email</th>
              </tr>
            </thead>
          </table>
          <input type="hidden" name="txtId" id="txtId" value="<?=$idProspecto;?>">
        </div>
      </div>
    </div>
  </div>
</div>
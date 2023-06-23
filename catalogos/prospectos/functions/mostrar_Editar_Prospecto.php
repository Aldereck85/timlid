<?php
require_once '../../../include/db-conn.php';

$stmt = $conn->prepare('SELECT
    clientes.NombreComercial,
    medios_contacto_clientes.PKMedioContactoCliente
    FROM clientes
    LEFT JOIN medios_contacto_clientes ON medios_contacto_clientes.PKMedioContactoCliente = clientes.FKMedioContactoCliente
    WHERE PKCliente= :id');
$stmt->execute(array(':id' => $idProspecto));
$prospecto = $stmt->fetch(PDO::FETCH_ASSOC);
$nombre = $prospecto['NombreComercial'];
$medio = $prospecto['PKMedioContactoCliente'];

$stmt = $conn->prepare('SELECT
    medios_contacto_clientes.PKMedioContactoCliente,
    medios_contacto_clientes.MedioContactoCliente
    FROM medios_contacto_clientes');
$stmt->execute();
$mediosContactos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="row contenedor-seccion active" id="contenedor-prospecto">
  <div class="col-lg-12">
    <form id="datos-generales">
      <div class="form-group">
        <div class="row">
          <div class="col-lg-6">
            <label for="usr">Nombre comercial:</label>
            <input type="text" class="form-control alphaNumeric-only" maxlength="30" name="nombreProspecto"
              id="nombreProspecto" value="<?=$nombre?>">
          </div>
          <div class="col-lg-6">
            <label for="usr">Medio de contacto:</label>
            <div class="input-group mb-3">
              <select class="custom-select" name="medioProspecto" id="medioProspecto">
                <?php
foreach ($mediosContactos as $medioContacto) {
    $medioContacto['PKMedioContactoCliente'] === $medio ? $selected = 'selected' : $selected = '';
    ?>
                <option value="<?=$medioContacto['PKMedioContactoCliente']?>" <?=$selected?>>
                  <?=$medioContacto['MedioContactoCliente']?></option>
                <?php
}
?>
              </select>
            </div>
          </div>
        </div>
      </div>
      <input type="hidden" name="idProspecto" id="idProspecto" value="<?=$idProspecto?>">
      <button type="button" class="btn-custom btn-custom--blue float-right" id="btnEditarProspecto">Editar</button>
    </form>
  </div>
</div>

<script>

</script>
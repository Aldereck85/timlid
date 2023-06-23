<?php
if (isset($_SESSION["Usuario"])) {
    require_once '../../../include/db-conn.php';
    $stmt = $conn->prepare('SELECT PKBitacoraNotas, Nota, DATE_FORMAT(FechaModificacion, "%d/%m/%Y %H:%i:%s") as Fecha FROM bitacora_notas_clientes WHERE FKCliente= :id ORDER BY Fecha DESC');
    $stmt->execute(array(':id' => $idProspecto));
    $notas = $stmt->fetchAll(PDO::FETCH_ASSOC);

} else {
    header("location:../../dashboard.php");
}
?>

<div class="row contenedor-seccion" id="contenedor-notas">
  <div class="col-md-12">
    <div class="float-right">
      <div class="button-container2" id="">
        <div class="button-icon-container">
          <a href="" class="btn btn-circle float-right waves-effect waves-light shadow btn-table" id=""
            data-toggle="modal" data-target="#agregar_Proyecto"><i class="fas fa-plus"></i></a>
        </div>
        <div class="button-text-container">
          <span>Agregar nota</span>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-12">

    <?php
$cont = 1;
$numeronota = count($notas);
echo "<input type='hidden' id='numeronota' value='" . $numeronota . "' />";
echo "<input type='hidden' id='contador' value='" . $numeronota . "' />";
echo "<input type='hidden' id='ladoNota' value='primero' />";
if (count($notas) > 0) {

    echo '<ul class="timeline" id="add-timeline">';

    foreach ($notas as $nota) {

        if ($cont % 2 == 0) {
            $clase = 'class="timeline-inverted"';
            $color = 'warning';
        } else {
            $clase = '';
            $color = 'info';
        }
        ?>
    <li <?=$clase?> id="<?php echo "nota_" . $nota['PKBitacoraNotas']; ?>">
      <div class="timeline-badge <?=$color?>"><i class="glyphicon glyphicon-credit-card"></i></div>
      <div class="timeline-panel">
        <div class="timeline-heading">
          <h4 class="timeline-title">Nota <?=$numeronota?></h4>
        </div>
        <div class="timeline-body" id="editarNota_<?=$nota['PKBitacoraNotas']?>">
          <p id="nota-par-<?=$nota['PKBitacoraNotas']?>"><?=$nota['Nota']?></p>
        </div>
        <hr>
        <div class="row">
          <div class="col-md-9" align="left">
            <button type="button" class="btn btn-primary btn-circle" data-toggle="modal" data-target="#modalNotaEditar"
              id="botonEditar_<?=$nota['PKBitacoraNotas']?>"
              onclick="obtenerDatosNota(<?=$nota['PKBitacoraNotas']?>)"><i class="fas fa-edit"></i></button>
            <button type="button" class="btn btn-danger btn-circle"
              onclick="elimarNota(<?=$nota['PKBitacoraNotas']?>)"><i class="fas fa-trash-alt"></i></button>
          </div>
          <div class="col-md-3" align="right">
            <small><?=$nota['Fecha']?></small>
          </div>
        </div>
      </div>
    </li>

    <?php

        $cont++;
        $numeronota--;
    }
    echo "</ul>";

} else {
    echo '<ul id="add-timeline">';
    echo "<h3 id='nuevo_mensaje'><center>PROSPECTO SIN NOTAS</center></h3>";
    echo '</ul>';
}
?>
  </div>
</div>
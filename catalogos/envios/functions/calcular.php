<?php
  if(isset($_GET['id'])){
    require_once('../../../include/db-conn.php');
    $id =  $_GET['id'];
    $factura =  $_GET['factura'];
    $cantidades = 0;
    $stmt = $conn->prepare('SELECT COUNT(*) FROM productos_en_envio WHERE FKProducto = :id AND FKFactura = :factura');
    $stmt->execute(array(':id' => $id, ':factura' => $factura));
    $number_of_rows = $stmt->fetchColumn();
    if($number_of_rows > 0)
    {
      $stmt = $conn->prepare('SELECT Cajas_por_enviar FROM productos_en_envio WHERE FKProducto = :id AND FKFactura = :factura');
      $stmt->execute(array(':id' => $id, ':factura' => $factura));
      while (($row = $stmt->fetch()) !== false) {
        $cantidades = $cantidades + $row['Cajas_por_enviar'];
      }

      $stmt = $conn->prepare('SELECT FKProducto,FKFactura,Cantidad,Piezas_por_caja FROM ventas INNER JOIN productos on FKProducto =PKProducto WHERE FKProducto = :id AND FKFactura = :factura');
      $stmt->execute(array(':id' => $id, ':factura' => $factura));
      $row = $stmt->fetch();
      $cantidad =  $row['Cantidad'];
      $piezas =  $row['Piezas_por_caja'];
      $maxCajas = ($cantidad / $piezas)-$cantidades;
      $max = $maxCajas % 2;
      if($maxCajas == 0){
        echo "<input type='text' value='El producto ya fue enviado' class='form-control' disabled>";
      }else if(($maxCajas % 2) == 0){
        $restante = $maxCajas * $cantidad;
        //echo "<input type='number' id='txtCantidad' class='form-control numeric-only'  name='txtCantidad' min='0' max='".$maxCajas."' required>";
        echo '<div class="col-lg-6">
            <label for="usr">Cantidad de cajas a enviar:</label>
            <input type="number" id="txtCantidad" class="form-control numeric-only"  name="txtCantidad" min="0" max="'.$maxCajas.'" cpu="'.$restante.'" required>
          </div>
          <div class="col-lg-6">
            <label for="usr">Cantidad de piezas:</label>
            <input type="number" id="txtCantidad" class="form-control numeric-only"  name="txtCantidad" min="0" max="'.$maxCajas.'" cpu="'.$cantidad.'" required>
          </div>';
      }

    }else{
      $stmt = $conn->prepare('SELECT FKProducto,FKFactura,Cantidad,Piezas_por_caja FROM ventas INNER JOIN productos on FKProducto =PKProducto WHERE FKProducto = :id AND FKFactura = :factura');
      $stmt->execute(array(':id' => $id, ':factura' => $factura));
      $row = $stmt->fetch();
      $cantidad =  $row['Cantidad'];
      $piezas =  $row['Piezas_por_caja'];
      $maxCajas = $cantidad / $piezas;
      if($maxCajas == 0){
        echo "<input type='text' value='El producto ya fue enviado' class='form-control' disabled>";
      }else{
        echo "<input type='number' id='txtCantidad' class='form-control numeric-only'  name='txtCantidad' min='0' max='".$maxCajas."' required>";
      }
    }

  }

 ?>

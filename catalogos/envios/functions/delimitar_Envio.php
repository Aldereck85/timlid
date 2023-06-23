<?php
  if(isset($_GET['id'])){
    require_once('../../../include/db-conn.php');
    $id =  $_GET['id'];
    $factura =  $_GET['factura'];
    $cantidades = 0;
    $remanente = 0;
    $pedido = $_GET['pedido'];

    //Pedido tipo 1 en envios del superusuario o administrador
    if($pedido == 1){
      //Conocer si hay productos en el envio
        $stmt = $conn->prepare('SELECT COUNT(*) FROM productos_en_envio WHERE FKProducto = :id AND FKFactura = :factura');
        $stmt->execute(array(':id' => $id, ':factura' => $factura));
        $number_of_rows = $stmt->fetchColumn();

        if($number_of_rows > 0)
        {
          //Saber cuantas cajas de un producto hay en el pedido que se enviará
          $stmt = $conn->prepare('SELECT Cajas_por_enviar FROM productos_en_envio WHERE FKProducto = :id AND FKFactura = :factura');
          $stmt->execute(array(':id' => $id, ':factura' => $factura));
          while (($row = $stmt->fetch()) !== false) {
            $cantidades = $cantidades + $row['Cajas_por_enviar'];
          }

          //Obtener datos del pedido
          /*$stmt = $conn->prepare("SELECT ventas.FKProducto,ventas.FKFactura,ventas.Cantidad,unidad_medida.Piezas_por_caja,productos_en_envio.Cajas_por_enviar, productos_en_envio.Piezas_por_enviar FROM ventas LEFT JOIN productos ON ventas.FKProducto = productos.PKProducto LEFT JOIN productos_en_envio ON productos_en_envio.FKProducto = ventas.FKProducto AND productos_en_envio.FKFactura = ventas.FKFactura LEFT JOIN inventario ON inventario.FKProducto = productos.PKProducto LEFT JOIN unidad_medida ON unidad_medida.PKUnidadMedida = productos.FKUnidadMedida WHERE ventas.FKProducto = :id AND ventas.FKFactura = :factura ORDER BY unidad_medida.Piezas_por_caja DESC");*/
          $stmt = $conn->prepare("SELECT dc.FKCotizacion, dc.FKProducto,pe.FKFactura, dc.Cantidad, um.Piezas_por_caja,SUM(pe.Cajas_por_enviar) as Cajas_por_enviar, SUM(pe.Piezas_por_enviar) as Piezas_por_enviar  FROM productos_en_envio as pe LEFT JOIN detallecotizacion as dc ON pe.FKProducto = dc.FKProducto LEFT JOIN facturacion as f ON f.PKFacturacion = pe.FKFactura LEFT JOIN cotizacion as c ON c.PKCotizacion = f.FKCotizacion LEFT JOIN productos as p ON dc.FKProducto = p.PKProducto LEFT JOIN unidad_medida as um ON um.PKUnidadMedida = p.FKUnidadMedida WHERE dc.FKProducto = :id AND pe.FKFactura = :factura AND dc.FKCotizacion = (select FKCotizacion FROM facturacion where PKFacturacion = :facturacot) GROUP BY pe.FKFactura, dc.FKCotizacion");
          $stmt->execute(array(':id' => $id, ':factura' => $factura, ':facturacot' => $factura));
          $row = $stmt->fetch();
          $cantidad =  $row['Cantidad'];
          $piezas =  $row['Piezas_por_caja'];
          $cajasPorEnviar = $row['Cajas_por_enviar'];
          $piezasPorEnviar =  $row['Piezas_por_enviar'];

          //Si no hay datos en el pedido
          if($cantidad == NULL){
            echo '<div class="col-lg-12">
                <label for="usr">Cantidad de cajas a enviar:</label>
                <input type="number" id="txtCantidad" class="form-control numeric-only txtDisabled"  name="txtCantidad"  disabled>
                <div id="errorcantidad" style="display:none;color: #d9534f;">La cantidad es más grande de las piezas que restan.</div>
              </div>';
          }else{
            //////////////////
            $maxCajas = $cantidad / $piezas;
            $cant = (int)$maxCajas * $piezas; 
            $piezasRestantes = $cantidad - $cant;
            $piezasRest = $piezasRestantes - $piezasPorEnviar;
            $maxCajas = $maxCajas - $cajasPorEnviar;  

            $bandera = (is_int($maxCajas));
            $max = $maxCajas;
            if($maxCajas == 0){
              echo '<div class="col-lg-12">
                      <label for="usr">Estatus:</label>
                      <input type="text" value="El producto ya fue enviado" class="form-control" disabled>
                    </div>';
            }else if($bandera == true){
              if((int)$max == 0 && (int)$piezasRest ==0){
                echo '<div class="col-lg-12">
                  <label for="usr">Estatus:</label>
                  <input type="text" value="El producto ya fue enviado" class="form-control" disabled>
                </div>';
              }else{
                echo '<div class="col-lg-12">
                    <label for="usr">Cantidad de cajas a enviar:</label>
                    <input type="number" id="txtCantidad" class="form-control numeric-only txtDisabled"  name="txtCantidad" min="0" max="'.$maxCajas.'" required>
                    <div id="errorcantidad" style="display:none;color: #d9534f;">La cantidad de cajas es más grande de las cajas que restan.</div>
                  </div>';
              }
            }else {
                if((int)$max == 0 && (int)$piezasRest ==0){
                  echo '<div class="col-lg-12">
                    <label for="usr">Estatus:</label>
                    <input type="text" value="El producto ya fue enviado" class="form-control" disabled>
                  </div>';
                }else{
                  echo '<div class="col-lg-6">
                      <label for="usr">Cantidad de cajas a enviar:</label>
                      <input type="number" id="txtCantidad" class="form-control numeric-only txtDisabled"  name="txtCantidad" min="0" max="'.$max.'" required>
                      <div id="errorcantidad" style="display:none;color: #d9534f;">La cantidad de cajas es más grande de las cajas que restan.</div>
                    </div>
                    <div class="col-lg-6">
                      <label for="usr">Cantidad de piezas:</label>
                      <input type="number" id="txtCantidadPiezas" class="form-control numeric-only txtDisabled"  name="txtCantidadPiezas" min="0" max="'.$piezasRest.'" required>
                      <div id="errorcantidadpiezas" style="display:none;color: #d9534f;">La cantidad de piezas es más grande de las piezas que restan.</div>
                    </div>';
                }

            }
            /////////////////
          }



        }else{
          //Bsuca el número de piezas y cajas que tiene el pedido y cuantas quedan.
          /*$stmt = $conn->prepare('SELECT v.FKProducto,v.FKFactura,v.Cantidad,um.Piezas_por_caja FROM ventas as v LEFT JOIN productos as p on v.FKProducto = p.PKProducto LEFT JOIN inventario as i ON i.FKProducto = p.PKProducto LEFT JOIN unidad_medida as um ON um.PKUnidadMedida = p.FKUnidadMedida WHERE v.FKProducto = :id AND v.FKFactura = :factura ORDER BY um.Piezas_por_caja DESC');*/
          $stmt = $conn->prepare('SELECT dc.FKProducto,dc.Cantidad,um.Piezas_por_caja FROM detallecotizacion as dc LEFT JOIN productos as p on dc.FKProducto = p.PKProducto LEFT JOIN inventario as i ON i.FKProducto = p.PKProducto LEFT JOIN unidad_medida as um ON um.PKUnidadMedida = p.FKUnidadMedida LEFT JOIN cotizacion as c ON c.PKCotizacion = dc.FKCotizacion LEFT JOIN facturacion as f ON f.FKCotizacion = c.PKCotizacion WHERE dc.FKProducto = :id AND f.PKFacturacion = :factura ORDER BY um.Piezas_por_caja DESC');
          $stmt->execute(array(':id' => $id, ':factura' => $factura));
          $row = $stmt->fetch();

          $cantidad =  $row['Cantidad'];
          $piezas =  $row['Piezas_por_caja'];
          if($cantidad == NULL){
            echo '<div class="col-lg-12">
                <label for="usr">Cantidad de cajas a enviar:</label>
                <input type="number" id="txtCantidad" class="form-control numeric-only txtDisabled"  name="txtCantidad"  disabled>
              </div>';
          }else{
            //////////////////////
            $maxCajas = $cantidad / $piezas;
            $cant = (int)$maxCajas * $piezas;
            $piezasRestantes = $cantidad - $cant;
            $bandera = (is_int($maxCajas));
            if($maxCajas == 0){
              if($bandera == true){
                echo "<input type='text' value='El producto ya fue enviado' class='form-control' disabled>" ;
              }else {
                echo '<div class="col-lg-6">
                    <label for="usr">Cantidad de cajas a enviar:</label>
                    <input type="number" id="txtCantidad" class="form-control numeric-only txtDisabled"  name="txtCantidad" min="0" max="'.$maxCajas.'" required>
                    <div id="errorcantidad" style="display:none;color: #d9534f;">La cantidad es más grande de las piezas que restan.</div>
                  </div>
                  <div class="col-lg-6">
                    <label for="usr">Cantidad de piezas:</label>
                    <input type="number" id="txtCantidadPiezas" class="form-control numeric-only txtDisabled"  name="txtCantidadPiezas" min="0" max="'.$piezasRestantes.'" required>
                    <div id="errorcantidadpiezas" style="display:none;color: #d9534f;">La cantidad de piezas es más grande de las piezas que restan.</div>
                  </div>';
              }

            }else{
              if($bandera == true){
                echo '<div class="col-lg-12">
                    <label for="usr">Cantidad de cajas a enviar:</label>
                    <input type="number" id="txtCantidad" class="form-control numeric-only txtDisabled"  name="txtCantidad" min="0" max="'.$maxCajas.'" required>
                    <div id="errorcantidad" style="display:none;color: #d9534f;">La cantidad es más grande de las piezas que restan.</div>
                  </div>';
              }
              else {
                echo '<div class="col-lg-6">
                    <label for="usr">Cantidad de cajas a enviar:</label>
                    <input type="number" id="txtCantidad" class="form-control numeric-only txtDisabled"  name="txtCantidad" min="0" max="'.$maxCajas.'"  required>
                    <div id="errorcantidad" style="display:none;color: #d9534f;">La cantidad es más grande de las piezas que restan.</div>
                  </div>
                  <div class="col-lg-6">
                    <label for="usr">Cantidad de piezas:</label>
                    <input type="number" id="txtCantidadPiezas" class="form-control numeric-only txtDisabled"  name="txtCantidadPiezas" min="0" max="'.$piezasRestantes.'"  required>
                    <div id="errorcantidadpiezas" style="display:none;color: #d9534f;">La cantidad de piezas es más grande de las piezas que restan.</div>
                  </div>';
              }

            }
            /////////////////////
          }


        }
      }else if($pedido == 2){
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

          $stmt = $conn->prepare('SELECT dc.FKProducto,f.PKFacturacion,dc.Cantidad,um.Piezas_por_caja FROM detallecotizacion as dc INNER JOIN productos as p on dc.FKProducto = p.PKProducto INNER JOIN facturacion as f ON f.FKCotizacion = dc.FKCotizacion INNER JOIN unidad_medida as um ON um.PKUnidadMedida = p.FKUnidadMedida WHERE dc.FKProducto = :id AND f.PKFacturacion = :factura');
          $stmt->execute(array(':id' => $id, ':factura' => $factura));
          $row = $stmt->fetch();
          $cantidad =  $row['Cantidad'];
          $piezas =  $row['Piezas_por_caja'];
          //echo $cantidad." ".$piezas;
          if($cantidad == NULL){
            echo '<div class="col-lg-12">
                <label for="usr">Cantidad de cajas a enviar:</label>
                <input type="number" id="txtCantidad" class="form-control numeric-only txtDisabled"  name="txtCantidad"  disabled>
              </div>';
          }else{
            $maxCajas = ($cantidad / $piezas)-$cantidades;
            if($maxCajas == 0){
              echo '<div class="col-lg-12">
                  <label for="usr">Cantidad de cajas a enviar:</label>
                  <input type="text" value="El producto ya fue enviado" class="form-control" disabled>
                </div>';
            }else{
              echo '<div class="col-lg-12">
                  <label for="usr">Cantidad de cajas a enviar:</label>
                  <input type="number" id="txtCantidad" class="form-control numeric-only txtDisabled"  name="txtCantidad" min="0" max="'.$maxCajas.'"  required>
                  <div id="errorcantidad" style="display:none;color: #d9534f;">La cantidad es más grande de las piezas que restan.</div>
                </div>';
            }
          }


        }else{
          $stmt = $conn->prepare('SELECT dc.FKProducto,f.PKFacturacion,dc.Cantidad,um.Piezas_por_caja FROM detallecotizacion as dc INNER JOIN productos as p on dc.FKProducto = p.PKProducto INNER JOIN facturacion as f ON f.FKCotizacion = dc.FKCotizacion INNER JOIN unidad_medida as um ON um.PKUnidadMedida = p.FKUnidadMedida WHERE dc.FKProducto = :id AND f.PKFacturacion = :factura');
          $stmt->execute(array(':id' => $id, ':factura' => $factura));
          $row = $stmt->fetch();
          $cantidad =  $row['Cantidad'];
          $piezas =  $row['Piezas_por_caja'];
          if($cantidad == NULL){
            echo '<div class="col-lg-12">
                <label for="usr">Cantidad de cajas a enviar:</label>
                <input type="number" id="txtCantidad" class="form-control numeric-only txtDisabled"  name="txtCantidad"  disabled>
                <div id="errorcantidad" style="display:none;color: #d9534f;">La cantidad es más grande de las piezas que restan.</div>
              </div>';
          }else{
            $maxCajas = $cantidad / $piezas;
            if($maxCajas == 0){

              echo '<div class="col-lg-12">
                  <label for="usr">Cantidad de cajas a enviar:</label>
                  <input type="text" value="El producto ya fue enviado" class="form-control" disabled>
                </div>';
            }else{
              echo '<div class="col-lg-12">
                  <label for="usr">Cantidad de cajas a enviar:</label>
                  <input type="number" id="txtCantidad" class="form-control numeric-only txtDisabled"  name="txtCantidad" min="0" max="'.$maxCajas.'"  required>
                  <div id="errorcantidad" style="display:none;color: #d9534f;">La cantidad es más grande de las piezas que restan.</div>
                </div>';
            }
          }

        }

        }

    }

 ?>

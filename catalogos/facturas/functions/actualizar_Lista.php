<?php
  require_once('../../../include/db-conn.php');
  //if(isset($_GET['id'])){
      $id = $_GET['id'];
      $stmt = $conn->prepare('SELECT p.Clave,p.Descripcion,v.Cantidad,um.Unidad_de_Medida,pl.Precio_Unitario FROM ventas as v INNER JOIN facturas as f ON f.PKFactura = v.FKFactura INNER JOIN domicilio_fiscal as df ON df.PKDomicilioFiscal = f.FKDomiciliofiscal INNER JOIN clientes as c ON c.PKCliente = df.FKCliente INNER JOIN productos as p on v.FKProducto = p.PKProducto LEFT JOIN inventario as i ON i.FKProducto = p.PKProducto LEFT JOIN unidad_medida as um ON um.PKUnidadMedida = p.FKUnidadMedida LEFT JOIN precio_lista as pl ON pl.FKProducto = p.PKProducto AND pl.FKCliente = c.PKCliente WHERE v.FKFactura = :id GROUP BY p.Clave');
      $stmt->execute(array(':id'=>$id));
      $rowclass="rowWhite";
      $rowCount = 1;
      while (($row = $stmt->fetch()) !== false) {
          $total = $row['Cantidad'] * $row['Precio_Unitario'];
          echo "<div class='row ".$rowclass."' style='padding:5px;'><div class='col-lg-2'>".$row['Clave']."</div><div class='col-lg-2'>".$row['Descripcion']."</div><div class='col-lg-2'>".$row['Cantidad']."</div><div class='col-lg-2'>".$row['Unidad_de_Medida']."</div><div class='col-lg-2'>".$row['Precio_Unitario']."</div><div class='col-lg-2'>".$total."</div></div>";

          $x = $rowCount % 2;
          if($x == 0){
            $rowclass="rowWhite";
            $rowCount = 1;
          }else{
            $rowclass="rowBlack";
            $rowCount = 2;
          }
      }
    //}
   ?>

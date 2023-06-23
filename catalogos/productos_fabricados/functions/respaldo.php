<?php
  require_once('../../../include/db-conn.php');
  if(isset($_GET['id'])){
      try{
        $id =  $_GET['id'];
        $stmt = $conn->prepare('SELECT piezas_por_producto.PKPiezaProducto, piezas_fabricadas.NombrePiezas, piezas_por_producto.CantidadPiezas, piezas_fabricadas.Ancho, piezas_fabricadas.Largo, rollos.PKRollo, rollos.Gramos FROM piezas_por_producto INNER JOIN piezas_fabricadas ON piezas_por_producto.FKPiezaFabricada = piezas_fabricadas.PKPiezaFabricada INNER JOIN rollos ON piezas_fabricadas.FKRollo = rollos.PKRollo WHERE piezas_por_producto.FKProductoFabricado = :id ORDER BY rollos.PKRollo ASC');
        $stmt->execute(array(':id'=>$id));

        while (($row = $stmt->fetch()) !== false) {
          $rollo = 0;
          $pieza = $row['PKPiezaProducto'];
          $area = (($row['Ancho'] * $row['Largo']) /100) * $row['CantidadPiezas'];
          echo "<div class='row' style='padding:5px;'><div class='col-lg-2'>".$row['NombrePiezas']."</div><div class='col-lg-2'>".$row['CantidadPiezas']."</div><div class='col-lg-2'>".$row['Ancho']."</div><div class='col-lg-2'>".$row['Largo']."</div><div class='col-lg-2'>".$area."</div><div class='col-lg-2'>".$row['Gramos']."</div></div>";
        }

      }catch(Exception $e){
        echo $e->getMessage();
        exit;
      }
    }
?>
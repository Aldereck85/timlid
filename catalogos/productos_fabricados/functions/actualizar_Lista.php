<?php
  require_once('../../../include/db-conn.php');
  session_start();

  if(isset($_GET['id'])){
    try{
      $id =  $_GET['id'];
      $stmt = $conn->prepare('SELECT piezas_por_producto.PKPiezaProducto, piezas_fabricadas.NombrePiezas, piezas_por_producto.CantidadPiezas FROM piezas_por_producto INNER JOIN piezas_fabricadas ON piezas_por_producto.FKPiezaFabricada = piezas_fabricadas.PKPiezaFabricada WHERE piezas_por_producto.FKProductoFabricado = :id');
      $stmt->execute(array(':id'=>$id));
    }catch(Exception $e){
      echo $e->getMessage();
      exit;
    }
        while (($row = $stmt->fetch()) !== false) {
          $pieza = $row['PKPiezaProducto'];
            echo "<div class='row' style='padding:5px;'><div class='col-lg-6'>".$row['NombrePiezas']."</div><div class='col-lg-3'>".$row['CantidadPiezas']."</div><div class='col-lg-3'>";
            //echo "<a class='btn btn-danger' href='eliminar_PiezaLista.php?id=".$row['PKPiezaProducto']."'>Eliminar</a>";}
            echo "<a class='btn btn-danger' href='#' data-toggle='modal' data-target='#eliminarModal' onclick='obtenerIdPiezaEliminar(".$pieza.")'>Eliminar</a>";
            echo "</div></div>";
        }
    }
   ?>

<?php
  require_once('../../../include/db-conn.php');
  session_start();
  
  if(isset($_GET['id'])){
    try{
      $id =  $_GET['id'];
      $pedido =  $_GET['pedido'];
      $stmt = $conn->prepare('SELECT PKProductoEnvio,Descripcion,Cajas_por_enviar,Piezas_por_enviar FROM productos_en_envio INNER JOIN productos on FKProducto = PKProducto WHERE FKEnvio = :id');
      $stmt->execute(array(':id'=>$id));
      //
    }catch(Exception $e){
      echo $e->getMessage();
      exit;
    }
      $rowclass="rowWhite";
      $rowCount = 1;
      if($pedido == 1){
        while (($row = $stmt->fetch()) !== false) {
            echo "<div class='row ".$rowclass."' style='padding:5px;'><div class='col-lg-6'>".$row['Descripcion']."</div><div class='col-lg-2'>".$row['Cajas_por_enviar']."</div><div class='col-lg-2'>".$row['Piezas_por_enviar']."</div><div class='col-lg-2'>";

              if($_SESSION['modoenvio'] != 1 && $_SESSION['modoenvio'] != 2) 
                echo "<a class='btn btn-danger' href='eliminar_ProductoLista.php?id=".$row['PKProductoEnvio']."&idEnvio=".$id."'>Eliminar</a>";

              echo "</div></div>";

            $x = $rowCount % 2;
            if($x == 0){
              $rowclass="rowWhite";
              $rowCount = 1;
            }else{
              $rowclass="rowBlack";
              $rowCount = 2;
            }
        }

      }else if($pedido == 2){
        while (($row = $stmt->fetch()) !== false) {
            echo "<div class='row ".$rowclass."' style='padding:5px;'><div class='col-lg-6'>".$row['Descripcion']."</div><div class='col-lg-2'>".$row['Cajas_por_enviar']."</div><div class='col-lg-2'><a class='btn btn-danger' href='eliminar_ProductoLista.php?id=".$row['PKProductoEnvio']."&idEnvio=".$id."'>Eliminar</a></div></div>";
            $x = $rowCount % 2;
            if($x == 0){
              $rowclass="rowWhite";
              $rowCount = 1;
            }else{
              $rowclass="rowBlack";
              $rowCount = 2;
            }
        }
      }

    }
   ?>

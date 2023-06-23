<?php
  require_once('../../../include/db-conn.php');
  if(isset($_GET['id'])){
    $id =  $_GET['id'];
    $total = 0;
    try{
      $stmt = $conn->prepare('SELECT PKFormula,FKProducto,Ingrediente,Cantidad,Unidad_de_Medida,Precio FROM formulas_productos INNER JOIN ingredientes on PKIngrediente = FKIngrediente WHERE FKProducto = :id');
      $stmt->execute(array(':id'=>$id));
      //
    }catch(Exception $e){
      echo $e->getMessage();
      exit;
    }
      $rowclass="rowWhite";
      $rowCount = 1;
        while (($row = $stmt->fetch()) !== false) {
          $precio = number_format($row['Precio'], 2, '.', '');
          $precioCant = $row['Precio'] * $row['Cantidad'];
          $precioCant = number_format($precioCant, 2, '.', '');
          $total = $total + $precioCant;
            echo "<div class='row ".$rowclass."' style='padding:5px;'><div class='col-lg-3'><label class='float-left'>".$row['Ingrediente']."</label></div><div class='col-lg-2'><label class='float-right'>".$row['Cantidad']." ".$row['Unidad_de_Medida']."</label></div><div class='col-lg-2'><label class='float-right'>".$precio."</label></div><div class='col-lg-2'><label class='float-right'>".$precioCant."</label></div><div class='col-lg-3'><a class='btn btn-danger d-flex justify-content-center' href='eliminar_Ingrediente_Lista.php?id=".$row['PKFormula']."&idProducto=".$id."'>Eliminar</a></div></div>";
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
   ?>

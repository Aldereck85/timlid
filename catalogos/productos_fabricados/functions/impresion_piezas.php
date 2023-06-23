<?php
  require_once('../../../include/db-conn.php');
  if(isset($_GET['id'])){
      try{
        $id =  $_GET['id'];
        $conteoFilas = 0;
        $conteoFilasRollos = 0;
        //$conteoNombreR
        ///////// Inicio de conteo de filas ///////////////
        $stmt = $conn->prepare('SELECT DISTINCT rollos.PKRollo,rollos.Gramos,rollos.Area FROM piezas_por_producto INNER JOIN piezas_fabricadas ON piezas_por_producto.FKPiezaFabricada = piezas_fabricadas.PKPiezaFabricada INNER JOIN rollos ON piezas_fabricadas.FKRollo = rollos.PKRollo WHERE piezas_por_producto.FKProductoFabricado = :id ORDER BY rollos.PKRollo ASC');
        $stmt->execute(array(':id'=>$id));
        $conteoFilas = $stmt->rowCount();

        for($x= 0;$x < $conteoFilas; $x++){
          ${"areaTotal" . $x} = 0;
          ${"areaRollo" . $x} = 0;
          ${"nombreRollo" . $x} = 0;
        }

        while (($row = $stmt->fetch()) !== false) {
          ${"nombreRollo" . $conteoFilasRollos} = $row['Gramos'];
          ${"areaRollo" . $conteoFilasRollos} = $row['Area'];
          $conteoFilasRollos++;
        }


        ///////// Fin de conteo de filas //////////////////

        $stmt = $conn->prepare('SELECT piezas_por_producto.PKPiezaProducto, piezas_fabricadas.NombrePiezas, piezas_por_producto.CantidadPiezas, piezas_fabricadas.Ancho, piezas_fabricadas.Largo, rollos.PKRollo, rollos.Gramos FROM piezas_por_producto INNER JOIN piezas_fabricadas ON piezas_por_producto.FKPiezaFabricada = piezas_fabricadas.PKPiezaFabricada INNER JOIN rollos ON piezas_fabricadas.FKRollo = rollos.PKRollo WHERE piezas_por_producto.FKProductoFabricado = :id ORDER BY rollos.PKRollo ASC');
        $stmt->execute(array(':id'=>$id));

        $y = 0;
        $rollo = 0;
        $rollo2 = 0;
        $contador= 0;

        while (($row = $stmt->fetch()) !== false) {
          $pieza = $row['PKPiezaProducto'];
          $area = (($row['Ancho'] * $row['Largo']) /100) * $row['CantidadPiezas'];

          if($contador == 0){
            $rollo = $row['PKRollo'];
            ${"areaTotal" . $y} = ${"areaTotal" . $y} + $area;
            $contador++;
          }else{
            $rollo2 = $row['PKRollo'];
            if($rollo != $rollo2){
              $y++;
              $rollo = $rollo2;
            }
            ${"areaTotal" . $y} = ${"areaTotal" . $y} + $area;
          }
          echo "<div class='row' style='padding:5px;'><div class='col-lg-2'>".$row['NombrePiezas']."</div><div class='col-lg-2'>".$row['CantidadPiezas']."</div><div class='col-lg-2'>".$row['Ancho']."</div><div class='col-lg-2'>".$row['Largo']."</div><div class='col-lg-2'>".$area."</div><div class='col-lg-2'>".$row['Gramos']."</div></div>";
        }
        echo "<br>";
        for($y=0;$y<$conteoFilas;$y++){
          echo "Total de piezas completas fabricadas con el rollo de ".${"nombreRollo" . $y}." gramos: ".intdiv(${"areaRollo" . $y}, ${"areaTotal" . $y})."<br>";
        }

      }catch(Exception $e){
        echo $e->getMessage();
        exit;
      }
    }
?>




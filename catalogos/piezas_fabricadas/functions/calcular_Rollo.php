<?php
//session_start();

    require_once('../../../include/db-conn.php');
    if(isset($_GET['largo'])){
        $largoPieza =  $_GET['largo'];
        $anchoPieza =  $_GET['ancho'];
        $areaPieza = ($largoPieza * $anchoPieza) / 100;

        try{
          $stmt = $conn->prepare('SELECT * FROM rollos');
          $stmt->execute();

          while (($row = $stmt->fetch()) !== false) {
            $gramosRollo = "<b>Gramaje: ".$row['Gramos']." grs. / Ancho: ".$row['Ancho']."mts.</b>";
            $areaRollo = $row['Area'];
            $total = intdiv($areaRollo,$areaPieza) ;
            echo $gramosRollo."<br>";
            echo "<hr>";
            echo "<label style='font-style: italic;'>Total de piezas</label>: ".$total."<br><br>";
          }

          //header('Location:../index.php');
        }catch(PDOException $ex){
          echo $ex->getMessage();
        }
      }

      //echo "Rollo de 20g";
 ?>

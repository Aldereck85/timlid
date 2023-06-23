<?php
  session_start();
  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 1 || $_SESSION["FKRol"] == 2 || $_SESSION["FKRol"] == 4)){
    require_once('../../../include/db-conn.php');
    $id = $_POST['idCotizacionD'];

      if(isset($_POST['idCotizacionD'])){

        $stmt = $conn->prepare("SELECT Facturado FROM cotizacion WHERE PKCotizacion=?");
        $stmt->execute(array($id));
        $row = $stmt->fetch();

        if($row['Facturado'] == 0){

            try{
              $conn->beginTransaction();

              $stmt = $conn->prepare("DELETE FROM detalleimpuesto WHERE FKCotizacion=?");
              $stmt->execute(array($id));

              $stmt = $conn->prepare("DELETE FROM detallecotizacion WHERE FKCotizacion=?");
              $stmt->execute(array($id));

              $stmt = $conn->prepare("DELETE FROM cotizacion WHERE PKCotizacion=?");
              $stmt->execute(array($id));

              $conn->commit(); 
              header('Location:../index.php?estatus=1');
            }catch(PDOException $ex){
              $conn->rollBack(); 
              header('Location:../index.php?estatus=0');
            }
        }
        else{
          header('Location:../index.php?estatus=2');
        }
      }
      else{
          header('Location:../index.php');
      }
  }
  else{
    header('Location:../index.php');
  }
?>

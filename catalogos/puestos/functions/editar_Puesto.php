<?php
session_start();

  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 1)){
    require_once('../../../include/db-conn.php');

      $id = (int) $_POST['idPuestoU'];
      $puesto = $_POST['txtPuestoU'];
      //$sueldo = floatval($_POST['txtSueldoU']);
      $tipoPago = $_POST['txtTipoPagoU'];

      try{
        $stmt0 = $conn->prepare('SELECT Puesto FROM puestos WHERE PKPuesto= :id');
        $stmt0->execute(array(':id'=>$id));
        $row0 = $stmt0->fetch();
        $puesto1 = $row0['Puesto'];

        $stmt = $conn->prepare('UPDATE puestos set Puesto= :puesto, FKTipoPagoNomina=:tipoPago  WHERE PKPuesto = :id');
        $stmt->bindValue(':puesto',$puesto);
        //$stmt->bindValue(':sueldo',$sueldo);
        $stmt->bindValue(':tipoPago',$tipoPago);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        if($stmt->execute()){
          echo "exito";
        }else{
          echo "fallo";
        }
        
      }catch(PDOException $ex){
        echo $ex->getMessage();
      }
    

      /*if(isset($_REQUEST['ver'])){
        $id =  $_REQUEST['ver'];
        $stmt = $conn->prepare('SELECT * FROM puestos WHERE PKPuesto= :id');
        $stmt->execute(array(':id'=>$id));
        $row = $stmt->fetch();
        $puesto1 = $row['Puesto'];
        $sueldo1 = $row['Sueldo_semanal'];
      }*/
  }else {
    header("location:../../dashboard.php");
  }
?>

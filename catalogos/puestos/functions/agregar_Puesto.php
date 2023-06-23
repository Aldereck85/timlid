<?php
session_start();

  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 1)){
    require_once('../../../include/db-conn.php');
        $puesto = $_POST['txtPuesto'];
        //$sueldo = $_POST['txtSueldo'];
        $tipoPago = $_POST['txtTipoPago'];

        try{
          $stmt = $conn->prepare('INSERT INTO puestos (Puesto, FKTipoPagoNomina)VALUES(:puesto,:tipoPago)');
          $stmt->bindValue(':puesto',$puesto);
          //$stmt->bindValue(':sueldo',$sueldo);
          $stmt->bindValue(':tipoPago',$tipoPago);
          if ($stmt->execute()){
            echo "exito";
          }else{
            echo "fallo";
          }
          //header('Location:../index.php?p='.$puesto.'&a=add');
        }catch(PDOException $ex){
          echo $ex->getMessage();
        }
  }else {
    header("location:../../dashboard.php");
  }
 ?>


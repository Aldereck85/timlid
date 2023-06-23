<?php
  require_once('../../../include/db-conn.php');
  $id = $_POST['idPuestoD'];
  if(isset($_POST['idPuestoD'])){
    try{
      $stmt0 = $conn->prepare('SELECT * FROM puestos WHERE PKPuesto= :id');
      $stmt0->execute(array(':id'=>$id));
      $row = $stmt0->fetch();
      $puesto1 = $row['Puesto'];

      $stmt = $conn->prepare("DELETE FROM puestos WHERE PKPuesto=?");
      if($stmt->execute(array($id))){
        echo "exito";
      }else{
        echo "fallo";
      }
      //header('Location:../index.php?p='.$puesto1.'&a=elim');
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>

<?php
session_start();

    if(isset($_SESSION["Usuario"])){
        require_once('../../../../../include/db-conn.php');

        $id = (int) $_POST['idInsumoD'];


      try{
        $stmt = $conn->prepare('call spd_EliminarTipoInsumoUnico(:id)');
        $stmt->bindValue(':id',$id,PDO::PARAM_INT);
        /*$stmt->bindValue(':id', $id, PDO::PARAM_INT);*/
        if($stmt->execute()){
          $_SESSION['message'] = 'Operación exitosa. Tipo de insumo eliminado correctamente.';
          $_SESSION['message_type'] = 'success';

          header("Location: ../");
        }else{
          echo "fallo";
          $_SESSION['message'] = 'Operación fallida. Tipo de insumo no eliminado correctamente.';
          $_SESSION['message_type'] = 'warning';

          header("Location: ../");
        }
        
      }catch(PDOException $ex){
        echo $ex->getMessage();
      }
    
  }else {
    header("location:../../../../dashboard.php");
  }
?>

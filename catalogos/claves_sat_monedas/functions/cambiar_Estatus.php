<?php
  session_start();
  date_default_timezone_set('America/Mexico_City');
  if(isset($_SESSION["Usuario"]) && ($_SESSION['FKRol'] == 1 || $_SESSION['FKRol'] == 4)){
    require_once('../../../include/db-conn.php');
      if(isset($_POST['id'])){
        if(isset($_POST['estado'])){
          $id = $_POST['id'];
          $estado = $_POST['estado'];
          if($estado == 'Habilitar'){
            try{
              $stmt = $conn->prepare("UPDATE monedas SET Estatus = :estatus WHERE PKMoneda = :id");
              $stmt->bindValue(':estatus',1);
              $stmt->bindValue(':id', $id, PDO::PARAM_INT);
              echo $stmt->execute();
            }catch(PDOException $ex){
              echo $ex->getMessage();
            }
        }else{
          $estado = $_POST['estado'];
          try{
            $stmt = $conn->prepare("UPDATE monedas SET Estatus = :estatus WHERE PKMoneda = :id");
            $stmt->bindValue(':estatus',0);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
          }catch(PDOException $ex){
            echo $ex->getMessage();
          }
        }
      }
    }
  }

?>

<?php
session_start();

    if(isset($_SESSION["Usuario"])){
        require_once('../../../../../include/db-conn.php');

        $id = (int) $_POST['idInsumoU'];

        //$tipo = filter_var($_POST['txtTipoU'], FILTER_SANITIZE_FULL_SPECIAL_CHARS); // Codificar caracteres HTML.
        $tipo = substr($_POST['txtTipoU'], 0, 99);//Truncar la cadena de texto a los primeros 100 caracteres.
        
      try{
        $stmt = $conn->prepare('call spu_ActualizarTiposInsumoUnico(:id,:tipo)');
        $stmt->bindValue(':id',$id,PDO::PARAM_INT);
        $stmt->bindValue(':tipo',$tipo,PDO::PARAM_STR);
        /*$stmt->bindValue(':id', $id, PDO::PARAM_INT);*/
        if($stmt->execute()){
          $_SESSION['message'] = 'Operación exitosa. Tipo de insumo actualizado correctamente.';
          $_SESSION['message_type'] = 'success';

          header("Location: ../");
        }else{
          echo "fallo";
          $_SESSION['message'] = 'Operación fallida. Tipo de insumo no actualizado correctamente.';
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

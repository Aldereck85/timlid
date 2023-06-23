<?php
session_start();

    if(isset($_SESSION["Usuario"])){
        require_once('../../../../../include/db-conn.php');

        $id = (int) $_POST['idInsumoU'];

        //$tipo = filter_var($_POST['txtTipoU'], FILTER_SANITIZE_FULL_SPECIAL_CHARS); // Codificar caracteres HTML.
        $unidadMedida = substr($_POST['txtUnidadMedidaU'], 0, 99);//Truncar la cadena de texto a los primeros 100 caracteres.
        
      try{
        $stmt = $conn->prepare('call spu_ActualizarUnidadesMedidaUnico(:id,:unidadMedida)');
        $stmt->bindValue(':id',$id,PDO::PARAM_INT);
        $stmt->bindValue(':unidadMedida',$unidadMedida,PDO::PARAM_STR);
        /*$stmt->bindValue(':id', $id, PDO::PARAM_INT);*/
        if($stmt->execute()){
          $_SESSION['message'] = 'Operación exitosa. Unidad de medida actualizada correctamente.';
          $_SESSION['message_type'] = 'success';

          header("Location: ../");
        }else{
          echo "fallo";
          $_SESSION['message'] = 'Operación fallida. Unidad de medida no actualizada correctamente.';
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

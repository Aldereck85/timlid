<?php
session_start();

    if(isset($_SESSION["Usuario"])){
        require_once('../../../../../include/db-conn.php');

        $id = (int) $_POST['idInsumoU'];
        $fkInsumo = $_POST['cmbInsumoU'];
        $costo = $_POST['txtCostoU'];
        $cantidadMov = $_POST['txtCantidadEntU'];

        //$descripcion = filter_var($_POST['txtDescripcionLargaU'], FILTER_SANITIZE_FULL_SPECIAL_CHARS); // Codificar caracteres HTML.
        $descripcion = substr($_POST['txtDescripcionLargaU'], 0, 99);//Truncar la cadena de texto a los primeros 100 caracteres.

        $usuario = filter_var($_POST['txtUsuarioU'],FILTER_SANITIZE_EMAIL); //Elimina todos los caracteres menos letras, dígitos y !#$%&'*+-=?^_`{|}~@.[].
        $usuario = substr($usuario, 0, 39);//Truncar la cadena de texto a los primeros 40 caracteres.

        
      try{
        $stmt = $conn->prepare('call spu_ActualizarEntradaUnica(:id,:fkInsumo,:costo,:cantidadMov,:descripcion,:usuario)');
        $stmt->bindValue(':id',$id,PDO::PARAM_INT);
        $stmt->bindValue(':fkInsumo',$fkInsumo,PDO::PARAM_INT);
        $stmt->bindValue(':costo',$costo);
        $stmt->bindValue(':cantidadMov',$cantidadMov);
        $stmt->bindValue(':descripcion',$descripcion,PDO::PARAM_STR);
        $stmt->bindValue(':usuario',$usuario,PDO::PARAM_STR);
        /*$stmt->bindValue(':id', $id, PDO::PARAM_INT);*/
        if($stmt->execute()){
          $_SESSION['message'] = 'Operación exitosa. Entrada actualizada correctamente.';
          $_SESSION['message_type'] = 'success';

          header("Location: ../");
        }else{
          echo "fallo";
          $_SESSION['message'] = 'Operación fallida. Entrada no actualizada correctamente.';
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

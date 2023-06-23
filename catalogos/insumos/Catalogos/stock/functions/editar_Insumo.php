<?php
session_start();

    if(isset($_SESSION["Usuario"])){
        require_once('../../../../../include/db-conn.php');

        $id = (int) $_POST['idInsumoU'];

        //$identificador = filter_var($_POST['txtIdentidicadorU'], FILTER_SANITIZE_FULL_SPECIAL_CHARS); // Codificar caracteres HTML.
        $identificador = substr($_POST['txtIdentidicadorU'], 0, 19);//Truncar la cadena de texto a los primeros 20 caracteres.
        
        //$nombre = filter_var($_POST['txtNombreU'], FILTER_SANITIZE_FULL_SPECIAL_CHARS); // Codificar caracteres HTML.
        $nombre = substr($_POST['txtNombreU'], 0, 49);//Truncar la cadena de texto a los primeros 50 caracteres.
        
        $tipoInsumo = $_POST['cmbTipoInsumoU'];
        $unidadMedida = $_POST['cmbUnidadMedidaU'];
        $cantidadMin = $_POST['txtCantidadMinU'];
        $cantidadExi = $_POST['txtCantidadExiU'];
        
        //$descripcionBreve = filter_var($_POST['txtDescripcionBreveU'], FILTER_SANITIZE_FULL_SPECIAL_CHARS); // Codificar caracteres HTML.
        $descripcionBreve = substr($_POST['txtDescripcionBreveU'], 0, 49);//Truncar la cadena de texto a los primeros 50 caracteres.

        //$descripcionLarga = filter_var($_POST['txtDescripcionLargaU'], FILTER_SANITIZE_FULL_SPECIAL_CHARS); // Codificar caracteres HTML.
        $descripcionLarga = substr($_POST['txtDescripcionLargaU'], 0, 99);//Truncar la cadena de texto a los primeros 100 caracteres.

        $usuario = filter_var($_POST['txtUsuarioU'],FILTER_SANITIZE_EMAIL); //Elimina todos los caracteres menos letras, dígitos y !#$%&'*+-=?^_`{|}~@.[].
        $usuario = substr($usuario, 0, 39);//Truncar la cadena de texto a los primeros 40 caracteres.

        $estatusInsumo = $_POST['cmbEstatusInsumoU'];
        $costo = $_POST['txtCostoU'];
        
      try{
        $stmt = $conn->prepare('call spu_ActualizarInsumoUnico(:id,:identificador,:nombre,:tipoInsumo,:unidadMedida,:cantidadMin,:cantidadExi,:descripcionBreve,:descripcionLarga,:usuario,:estatusInsumo,:costo)');
        $stmt->bindValue(':id',$id,PDO::PARAM_INT);
        $stmt->bindValue(':identificador',$identificador,PDO::PARAM_STR);
        $stmt->bindValue(':nombre',$nombre,PDO::PARAM_STR);
        $stmt->bindValue(':tipoInsumo',$tipoInsumo,PDO::PARAM_INT);
        $stmt->bindValue(':unidadMedida',$unidadMedida,PDO::PARAM_INT);
        $stmt->bindValue(':cantidadMin',$cantidadMin);
        $stmt->bindValue(':cantidadExi',$cantidadExi);
        $stmt->bindValue(':descripcionBreve',$descripcionBreve,PDO::PARAM_STR);
        $stmt->bindValue(':descripcionLarga',$descripcionLarga,PDO::PARAM_STR);
        $stmt->bindValue(':usuario',$usuario,PDO::PARAM_STR);
        $stmt->bindValue(':estatusInsumo',$estatusInsumo,PDO::PARAM_INT);
        $stmt->bindValue(':costo',$costo);
        /*$stmt->bindValue(':id', $id, PDO::PARAM_INT);*/
        if($stmt->execute()){
          $_SESSION['message'] = 'Operación exitosa. Insumo actualizado correctamente.';
          $_SESSION['message_type'] = 'success';

          header("Location: ../");
        }else{
          echo "fallo";
          $_SESSION['message'] = 'Operación fallida. Insumo no actualizado correctamente.';
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
